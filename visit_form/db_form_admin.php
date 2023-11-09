<?
// personal data db mgt
include_once("../in_db_conn.php");
include_once("../in_file_prop.php");
include_once("$ROOT_FILE_PATH/function/in_fn_date.php"); // date function
include_once("$ROOT_FILE_PATH/function/in_file_func.php"); // file function
//include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
include_once("$ROOT_FILE_PATH/function/in_fn_link.php");
include_once("$ROOT_FILE_PATH/function/in_fn_number.php");

$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($u_mode == "select_list"){ // select uic form list
  $uic = isset($_POST["uic"])?urldecode($_POST["uic"]):"";
  $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";
  $project_id = isset($_POST["project_id"])?$_POST["project_id"]:"";


       $query = "SELECT p.uic, p.collect_date, pu.pid
       FROM x_$form_id as p LEFT JOIN p_project_uic_list as pu ON (p.uic=pu.uic)
       ORDER BY p.uic, p.collect_date
       ";

//echo $query;
       $stmt = $mysqli->prepare($query);
       if($stmt->execute()){
         $stmt->bind_result($uic,$visit_date, $pid);
         $arr_data = array();
         while ($stmt->fetch()) {
           $link = encodeSingleLink("$uic:$visit_date:$form_id");


           $arr_uic = array();
           $arr_uic["uic"]=$uic;
           $arr_uic["form_id"]=$form_id;
           $arr_uic["pid"]=($pid !== NULL)?$pid:"";
           $arr_uic["visit_date"]=$visit_date;
           $arr_uic["link"]=$link;
           $arr_data[]=$arr_uic;
         }// while
         $rtn['datalist'] = $arr_data;
       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();


}// select_list
else if($u_mode == "open_form_link"){ // select uic form list
  $uic = isset($_POST["uic"])?urldecode($_POST["uic"]):"";
  $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";
  $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";

  $link = encodeSingleLink("$uic:$visit_date:$form_id");
  $rtn["link"] = $link;

}// select_list



$mysqli->close();


 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
