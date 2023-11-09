<?
// POC db mgt
include_once("../../../in_auth_db.php");


$msg_error = "";
$msg_info = "";
$returnData = "";

$proj_id="PrEP";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($flag_auth != 0){ // valid user session
  include_once("../../../in_db_conn.php");
  include_once("../../../function/in_fn_date.php"); // date function
  include_once("../../../function/in_file_func.php"); // file function
  include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../../../function/in_fn_link.php");
  include_once("../../../function/in_fn_number.php");



  if($u_mode == "schedule_next_visit"){ // create next visit schedule
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";

    $next_visit_date = isset($_POST["next_visit_date"])?$_POST["next_visit_date"]:"";
    $next_visit_no = isset($_POST["visit_no"])?$_POST["visit_no"]:"";

    if($next_visit_date != ""){
      // create new visit schedule to new group as the visit amt left
      $query = "REPLACE INTO p_project_uid_visit
       (proj_id, uid, visit_id, visit_main, visit_status, schedule_date, visit_no)
       VALUES (?,?,?,'1', '0',?,?) ";

     //  echo "[visit_id :$proj_id, $uid, $new_group_id, $visit_id, $visit_schedule_date] ";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param('ssss',$proj_id, $uid, $visit_id, $visit_schedule_date, $visit_no);
         if($stmt->execute()){
           setLogNote($sc_id, "[$proj_id] schedule visit $uid month:$visit_no/$visit_schedule_date");
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
    }
  }// schedule_next_visit

  else if($u_mode == "restart_visit"){ // restart visit
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";

    $query = "INSERT INTO p_project_uid_visit
     (proj_id, uid, visit_id, visit_main, visit_status, schedule_date, visit_no)
     VALUES (?,?,'RE','1', '1',?,'0') ";

   //  echo "[visit_id :$proj_id, $uid, $new_group_id, $visit_id, $visit_schedule_date] ";
       $stmt = $mysqli->prepare($query);
       $stmt->bind_param('ssss',$proj_id, $uid, $visit_id, $visit_schedule_date, $visit_no);
       if($stmt->execute()){
         setLogNote($sc_id, "[$proj_id] Restart Visit $uid month:$visit_no/$visit_schedule_date");
       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();


       $query = "DELETE FROM p_project_uid_visit
       WHERE uid=? AND proj_id=? AND visit_status=0 ";
       $stmt = $mysqli->prepare($query);
       $stmt->bind_param('ss', $uid, $proj_id);
       if($stmt->execute()){
       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();
  }// restart_visit


  $mysqli->close();

}// $flag_auth != 0



 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
