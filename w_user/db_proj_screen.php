<?
// UID Data Mgt
include_once("../in_auth_db.php");


$msg_error = "";
$msg_info = "";
$returnData = "";
$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($flag_auth != 0){ // valid user session
  include_once("../in_db_conn.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_file_func.php"); // file function
  include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../function/in_fn_link.php");
  include_once("../function/in_fn_number.php");

  if($u_mode == "sel_proj_screen"){ // select project for uid to screen
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $clinic_id = $_SESSION['weclinic_id'];

         $query = "SELECT p.proj_id, p.proj_name, p.is_enable
         FROM p_project as p, p_project_clinic as c
         WHERE p.proj_id=c.proj_id AND c.clinic_id=?
         AND p.proj_id NOT IN (
           select proj_id from p_project_uid_list
           where uid=? AND uid_status IN (1, 2)
         )
         ORDER BY p.proj_id
         ";


  //echo "$uid/$clinic_id/ $query" ;
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param('ss',$clinic_id, $uid);
         if($stmt->execute()){
           $stmt->bind_result($proj_id, $proj_name, $is_enable);
           $arr_data = array();
           while ($stmt->fetch()) {
               $arr_proj = array();
               $arr_proj["proj_id"]=$proj_id;
               $arr_proj["proj_name"]=$proj_name;
               $arr_proj["is_enable"]=$is_enable;
               $arr_data[]=$arr_proj;
           }// while
           $rtn['datalist'] = $arr_data;
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
  }// select_list


  if($u_mode == "sel_proj_screen_form"){ // select project forms for uid to screen
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $clinic_id = $_SESSION['weclinic_id'];

         $query = "SELECT p.proj_id, p.proj_name
         FROM p_project as p, p_project_clinic as c
         WHERE p.proj_id=c.proj_id AND c.clinic_id=?
         AND p.proj_id NOT IN (
           select proj_id from p_project_uid_list
           where uid=? and uid_status ='1'
         )
         ORDER BY p.proj_id
         ";

  //echo "$uid/$clinic_id/ $query" ;
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param('ss',$clinic_id, $uid);
         if($stmt->execute()){
           $stmt->bind_result($proj_id, $proj_name);

           $arr_data = array();
           while ($stmt->fetch()) {
             $arr_proj = array();
             $arr_proj["proj_id"]=$proj_id;
             $arr_proj["proj_name"]=$proj_name;
             $arr_data[]=$arr_proj;

           }// while
           $rtn['datalist'] = $arr_data;
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
  }// select_list



  else if($u_mode == "add_proj_screen"){ // add project screen
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $clinic_id = $_SESSION['weclinic_id'];
    $create_by = $sc_id;
    $visit_id = "SCRN";

    $is_success = "N";

    if($uid != ""){
      $inQuery = "INSERT INTO p_project_uid_list
       (proj_id, uid, pid, clinic_id,create_by, screen_date,create_date)
        VALUES (?,?,'wait_pid',?,?,now(), now()) ";

  //echo "query / $inQuery";

      $stmt = $mysqli->prepare($inQuery);
      $stmt->bind_param("ssss", $proj_id,$uid, $clinic_id, $create_by);
        if($stmt->execute()){
           $row_affected = $stmt->affected_rows;
           if($row_affected > 0){
             $is_success = "Y";
             setLogNote($sc_id, "[$proj_id] Add $uid to screen");
           }
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

        $inQuery = "INSERT INTO p_project_uid_visit
         (proj_id, uid, visit_id, visit_main, visit_status, schedule_date, visit_date)
          VALUES (?,?,?,'Y', '0',now(), now()) ";

        $stmt = $mysqli->prepare($inQuery);
        $stmt->bind_param("sss", $proj_id,$uid, $visit_id);
          if($stmt->execute()){
             $row_affected = $stmt->affected_rows;
             if($row_affected > 0){
               $is_success = "Y";

             }
          }
          else{
            $msg_error .= $stmt->error;
          }
    }
    else {
      $msg_error .= "ไม่พบ UID ในการ Add to Screen";
    }



        $rtn['is_success'] = $is_success;
        $rtn['screen_date'] = getToday();
        $stmt->close();
  }// add_proj_screen

  else if($u_mode == "screen_fail"){ // enroll uid to project
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:""; // screen date
    $clinic_id = $_SESSION['weclinic_id'];


  $is_success = "";
  $uid_status = '11'; // screen fail


  $query = "UPDATE p_project_uid_list SET  uid_status=?
  WHERE uid=? AND proj_id=? AND screen_date=? ";
  //$query.= "WHERE uid='$uid' AND proj_id='$proj_id' ";
  //echo "$query" ;

  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('ssss',$uid_status, $uid, $proj_id, $visit_date);
  if($stmt->execute()){
      $msg_info .= "Screen Fail to $uid ";
      $is_success="Y";
      setLogNote($sc_id, "[$proj_id] $uid screen fail");
  }
  else{
    $msg_error .= $stmt->error;
  }
  $stmt->close();


  if($is_success =="Y"){

    $query = "UPDATE p_project_uid_visit SET
    visit_status=1, visit_note=?
    WHERE uid=? AND proj_id=? AND visit_date=?
    ";
    //echo "$query" ;

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssss',$visit_note, $uid, $proj_id, $visit_date);
    if($stmt->execute()){

    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();
  }


  }// screen fail

  $mysqli->close();

}




 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;
 $rtn['flag_auth'] = $flag_auth;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
