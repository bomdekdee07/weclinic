<?

include_once("../in_file_prop.php"); // file path
include_once("$ROOT_FILE_PATH/function/in_fn_link.php"); // include Link encode/decode

include_once("$ROOT_FILE_PATH/function/in_fn_sendmail.php") ; // sending mail function
include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber



$msg_error = "";
$msg_info = "";
$rtn_link = "";

$u_id = strtolower(isset($_POST["u_id"])?$_POST["u_id"]:"");

include_once("../in_db_conn.php");
/*
  $inQuery  = "SELECT user_id, user_fullname, user_position, user_group ";
  $inQuery .= "FROM pv_user WHERE user_hash=? AND user_pwd=? AND user_status='1' ";
*/


  $inQuery  = "SELECT t.trainee_id, trainee_name, trainee_email, c.r_id, TIMESTAMPDIFF(MINUTE,c.req_date,now()) as time_diff
               FROM t_trainee as t
                    LEFT JOIN pv_change_pwd as c ON(t.trainee_id=c.user_id AND c.cpwd_ip='')
               WHERE t.trainee_id=? AND t.status_id > 0
               ORDER BY c.req_date DESC LIMIT 1";

  $stmt = $mysqli->prepare($inQuery);
  $stmt->bind_param("s", $u_id);
  $stmt->execute();

  /* bind result variables */
  $stmt->bind_result($trainee_id, $trainee_name, $trainee_email, $r_id, $time_diff);
  $stmt->store_result();
  /* fetch values */
  if ($stmt->fetch()) {

  }


  if($trainee_id != ""){
    $flag_verify = true;

    if(isset($time_diff)){
       //$req_ip .="$time_diff";

       if($time_diff < 120){
           $flag_verify = false;
           $msg_error .= "System already sent change password link to you recently.<br>Please check your email : <b>$trainee_email</b>.";
       }
    }
    //  echo "have time diff : $time_diff";


    if($flag_verify)  {
      /*
      $string = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-';
      $string_shuffled = str_shuffle($string);
      $r_id = substr($string_shuffled, 1, 55);
*/
      $r_id = createRandomCode(40);
      $req_ip =  get_client_ip() ; // client ip address

      $inQuery = "INSERT INTO pv_change_pwd
      (user_id, r_id, req_ip, req_date) VALUES (?,?,?,now()) ";
    //  echo "sql : ".$inQuery;
      $stmt = $mysqli->prepare($inQuery);
      $stmt->bind_param("sss", $trainee_id,$r_id, $req_ip);
      if($stmt->execute()){
          $msg_info .= "<i class='fa fa-envelope'></i> System has sent the link to change password to <b>$trainee_email</b>.";
          $msg_info .= "<br>Please check email now. <small>(Link to change password will be expired within 2 hours)</small>";
        //  $msg_info .= "<br> link :  $r_id.";

          $emailTO = array();
          $emailCC = array();
          $emailBCC = array();

          $emailTO[$trainee_email] = $trainee_name;
          $current_date = (new DateTime())->format('d M y H:i:s');
          $mailSubject = "Change password request (PREVENTION Timesheet System)";
          $mailMessage =
          "Dear  $trainee_name <br><br>
           As your change password request on $current_date, Please click at the link below.
           <br>
           <a href='$WEB_PATH/system-access/cpwd.php?r_id=$r_id' target='_blank'><b>Change new password here / เปลี่ยนรหัสผ่าน</b></a>
           <br>
           (Link to change password will be expired within 2 hours after request)<br>
           <br><br>
           Best Regards <br>
           USAID Community Partnership
           <small>(Do not reply to this email)</small>
          "
          ;


        //  $mailSubject = "(DEMO Version) ".$mailSubject;

          $msgInfo = sendEmail($mailSubject, $mailMessage,
                    $emailTO,$emailCC,$emailBCC);


          setLogNote("[$trainee_id] $msgInfo");



      }
      else{
          $msg_error .= htmlspecialchars($stmt->error);
      }

    } // flag_verify


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
