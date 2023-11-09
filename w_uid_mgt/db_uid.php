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


  if($u_mode == "add_uid"){ // add_uid




  }// add_uid
  else if($u_mode == "update_uid"){ // update_uid




  }// update_uid



  $mysqli->close();
}//$flag_auth != 0



 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;
 $rtn['flag_auth'] = $flag_auth;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
