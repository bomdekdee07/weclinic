<?

include_once('../in_file_prop.php') ; // sending mail function
include_once("$ROOT_FILE_PATH/function/in_fn_link.php"); // include Link encode/decode
include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber




$msg_error = "";
$msg_info = "";
$rtn_link = "";
//strtoupper(isset($_GET["u"])?$_GET["u"]:"");
$r_id = isset($_POST["r_id"])?$_POST["r_id"]:"";
$u_pwd = isset($_POST["u_pwd"])?$_POST["u_pwd"]:"";



include_once("../in_db_conn.php");

$inQuery = "SELECT trainee_id, trainee_name, o.org_name, trainee_email, trainee_type
FROM t_trainee as t, t_organization as o, pv_change_pwd as c
WHERE t.trainee_org_id=o.org_id
AND t.trainee_id=c.user_id AND c.r_id=?";

  $stmt = $mysqli->prepare($inQuery);
  $stmt->bind_param("s", $r_id);
  $stmt->execute();

  /* bind result variables */
  $stmt->bind_result( $trainee_id, $trainee_name,$org_name, $trainee_email, $trainee_type);
  $stmt->store_result();
  /* fetch values */
  if ($stmt->fetch()) {

  }


  if($trainee_id != ""){

    $new_pwd = encodeSingleLink($u_pwd);


    $inQuery = "UPDATE t_trainee SET trainee_pwd =? ,last_access=NOW() WHERE trainee_id=? ";
    $stmt = $mysqli->prepare($inQuery);
    $stmt->bind_param("ss", $new_pwd, $trainee_id);


    if($stmt->execute()){
        $msg_info .= "Welcome <b>$trainee_name</b>, your password has been changed.<br>";
        $msg_info .= "<span class='text-primary'>System will redirect you to your system dashboard or
<a href='../index.php'><b> CLICK HERE </b></a>.</span> ";

session_start();
$_SESSION["p_id"] = $trainee_id;
$_SESSION["p_name"] = $trainee_name;
$_SESSION["p_pos"] = "Trainee";
$_SESSION["p_org_name"] = $org_name;

$_SESSION["p_email"] = $trainee_email;
$_SESSION["p_type"] = $trainee_type;
$_SESSION["side_menu_major_course"] = "";
$_SESSION["logintime"] = time() + (60*60);



         $ip_address =  get_client_ip();
         $inQuery  = "UPDATE pv_change_pwd SET cpwd_ip=?, cpwd_date=now() WHERE user_id=? AND r_id=? ";
         $stmt = $mysqli->prepare($inQuery);
         $stmt->bind_param("sss", $ip_address, $trainee_id, $r_id);
         $stmt->execute();

        setLogNote("[$trainee_id] Login to system by changed password");

    }
    else{
        $msg_error .= htmlspecialchars($stmt->error);
    }

  }
  else{ // no email existing
    $msg_error .= "Invalid Data !";
  }

$stmt->close();
$mysqli->close();


// return object
$rtn['msg_error'] = $msg_error;
$rtn['msg_info'] = $msg_info;


// change to javascript readable form
$returnData = json_encode($rtn);
echo $returnData;

?>
