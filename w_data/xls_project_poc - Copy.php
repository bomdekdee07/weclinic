<?
//session_start();

// trainee attend course  check in-out
//echo "enterhere2 ";

set_time_limit(3600);

include_once("../in_auth.php");
include_once("../in_file_prop.php");
include_once("../in_db_conn.php");
include_once("../asset/xlsxwriter/xlsxwriter.class.php"); // include excel class
include_once("../function/in_file_func.php"); // include file function
include_once("../function/in_fn_date.php"); // include date function
include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber


$datetime = new DateTime();

$msg_error = "";
$msg_info = "";
$returnData = "";


$file_dir = __DIR__."/data";



$proj_id = "POC";
//$txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";

$date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:"";
$date_end = isset($_POST["date_end"])?$_POST["date_end"]:"";

$file_name = "export_".$sc_id."_$proj_id";

ini_set('display_errors', 'on');
//ini_set('display_errors', 1);
//ini_set('log_errors', 1);
//error_reporting(E_ALL & E_NOTICE);

header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($file_name).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer = new XLSXWriter();

$formatHead = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'color'=>'#000','fill'=>'#BBDDFF', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatData = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'color'=>'#000','fill'=>'#A3D900', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');

/*
$sheet1 = "Data Export $proj_id";
$row_head = array("Type", "Fund", "ID", "Name", "Org");
$row_head_prop = array($formatHead,$formatHead, $formatHead, $formatHead, $formatHead);
//$writer->writeSheetRow($sheet1, $row_head, $formatHead);
//$writer->writeSheetRow("pop99", $row_head, $formatHead);
*/



// all poc visit
$sheet_visit = $proj_id."_all_visit";
$row_head = array();
$row_head_format = array();

$row_head[] = "pid";
$row_head[] = "uic";
$row_head[] = "uid";

$row_head[] = "visit_id";
$row_head[] = "group_id";
$row_head[] = "visit_date";
$row_head[] = "status";
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;

$row_data_format[] = $formatData;
$row_data_format[] = $formatData;
$row_data_format[] = $formatData;
$row_data_format[] = $formatData;
$row_data_format[] = $formatData;
$row_data_format[] = $formatData;
$row_data_format[] = $formatData;

$writer->writeSheetRow($sheet_visit, $row_head, $row_head_format);

$inQuery = "SELECT distinct u.uid, ug.uic2, u.pid,  v.visit_id, vs.status_name, v.group_id, v.visit_date
from p_project_uid_visit as v, p_project_uid_list as u,
p_visit_list as vl, uic_gen as ug, p_visit_status as vs
where v.uid=u.uid and vl.visit_id=v.visit_id and ug.uid=u.uid
and v.visit_status <> 0 and u.proj_id = '$proj_id'
and u.uid_status IN(1,2) and v.visit_status=vs.status_id
order by u.pid,  v.visit_date, v.group_id, vl.visit_order";

$stmt = $mysqli->prepare($inQuery);
$stmt->execute();
$stmt->bind_result($uid, $uic, $pid, $visit_id, $visit_status_name, $group_id, $visit_date);

while ($stmt->fetch()) {
  $row_data = array();
  $row_data_format = array();
  $row_data[] = "$pid";
  $row_data[] = "$uic";
  $row_data[] = "$uid";
  $row_data[] = "$visit_id";
  $row_data[] = "$group_id";
  $row_data[] = "$visit_date";
  $row_data[] = "$visit_status_name";
  $writer->writeSheetRow($sheet_visit, $row_data, $row_data_format);
}//while

$stmt->close();






$arr_uid_visit = array();
//$inQuery = "SELECT domain_id, domain_name from p_data_domain where domain_id not in ('con_med', 'ae')";
/*
$inQuery = "SELECT u.uid, v.visit_id, v.group_id, v.visit_date
from p_project_uid_visit as v, p_project_uid_list as u, p_visit_list as vl
where v.uid=u.uid and vl.visit_id=v.visit_id
and v.visit_status NOT IN(0, 11, 10) and v.visit_id <> 'SCRN'
and u.uid_status=1
order by v.visit_date, vl.visit_order";
*/

// not include  visit status 0 (นัดหมาย)  10(ไม่มาตามนัดหมาย) / uid_status 0=wait screening, 11=screen fail
$inQuery = "SELECT distinct u.uid, v.visit_id, v.group_id, v.visit_date
from p_project_uid_visit as v, p_project_uid_list as u, p_visit_list as vl
where v.uid=u.uid and vl.visit_id=v.visit_id and u.proj_id = '$proj_id'
and v.visit_id <> 'SCRN' and v.visit_status NOT IN(0,10)
and u.uid_status IN(1,2)
order by v.visit_date, vl.visit_order";


$stmt = $mysqli->prepare($inQuery);
//$stmt->bind_param("s", $course_id);
$stmt->execute();

$stmt->bind_result($uid, $visit_id,$group_id, $visit_date);

while ($stmt->fetch()) {

   if(!isset($arr_uid_visit["$uid-$visit_date"])){
     $arr_uid_visit["$uid-$visit_date"] = array('v_id'=>"$visit_id", 'g_id'=>"$group_id");
   }
   else{ // multiple visit_id or group_id
     $arr_uid_visit["$uid-$visit_date"]["v_id"].= " $visit_id";
     $arr_uid_visit["$uid-$visit_date"]["g_id"].= " $group_id";
   }


}

$stmt->close();





$arr_domain = array();
//$inQuery = "SELECT domain_id, domain_name from p_data_domain where domain_id not in ('con_med', 'ae')";
$inQuery = "SELECT domain_id, domain_name, domain_type from p_data_domain order by domain_type";
$stmt = $mysqli->prepare($inQuery);
//$stmt->bind_param("s", $course_id);
$stmt->execute();

$stmt->bind_result($domain_id, $domain_name,$domain_type);

while ($stmt->fetch()) {
   $arr_domain[] = array('id'=>$domain_id, 'name'=>$domain_name, 'type'=>$domain_type);
}

$stmt->close();

foreach($arr_domain as $domain){
  // echo "<br><b>".$domain['id']. "- ".$domain['type']."</b>";

   $arr_col = array();
   $prefix_db = "x_"; // normal
   if($domain['type'] == "1"){ // log form
     $prefix_db = "z_";
   }
   $inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='$prefix_db".$domain['id']."' AND TABLE_SCHEMA='$db_name'";
 //echo "<br><b>".$inQuery."</b>";


   $stmt = $mysqli->prepare($inQuery);
   //$stmt->bind_param("s", $course_id);
   $stmt->execute();

   $stmt->bind_result($col_name);
   $row_head = array();
   $row_head_format = array();

   $row_head[] = "visit_id";
   $row_head[] = "group_id";
   $row_head[] = "uic";
   $row_head[] = "pid";

   $row_head_format[] = $formatHead;
   $row_head_format[] = $formatHead;
   $row_head_format[] = $formatHead;
   $row_head_format[] = $formatHead;

   while ($stmt->fetch()) {
     $row_head[] = $col_name;
     $row_head_format[] = $formatHead;
    // echo "<br>colname: $col_name";
   }
   $writer->writeSheetRow($domain['name'], $row_head, $row_head_format);
   $stmt->close();


   if($domain['id'] != "poc_screen"){

     $query = "select distinct u.pid, u2.uic2 as uic, t.*
     from $prefix_db".$domain['id']." as t,
     p_project_uid_list as u, uic_gen as u2
     WHERE t.uid=u.uid AND u.uid=u2.uid AND u.proj_id=?
     AND u.uid_status IN(1,2)
     ORDER BY collect_date";
/*
     $query = "select distinct  u.pid, u2.uic2 as uic,  v.visit_id, t.*
     from p_project_uid_list as u, uic_gen as u2,
     $prefix_db".$domain['id']." as t
     LEFT JOIN p_project_uid_visit as v ON (t.uid=v.uid AND t.collect_date=v.visit_date)

     WHERE t.uid=u.uid AND u.uid=u2.uid AND u.proj_id=?
     ORDER BY collect_date, u.pid";
*/
     $stmt = $mysqli->prepare($query);
     $stmt->bind_param("s", $proj_id);
     if ($stmt->execute()){
       $result = $stmt->get_result();

       while($row = $result->fetch_assoc()) {
           $row_data = array();
           $row_data_format = array();
           /*
           $row_data[]=$row["uic"];
           $row_data[]=$row["pid"];
  */

  if(isset($arr_uid_visit[$row["uid"]."-".$row["collect_date"]])){
    $row['visit_id']=$arr_uid_visit[$row["uid"]."-".$row["collect_date"]]["v_id"];
    $row['group_id']=$arr_uid_visit[$row["uid"]."-".$row["collect_date"]]["g_id"];
  }


           foreach($row_head as $col){

             $row_data[]=(isset($row[$col])?$row[$col]:"");
             $row_data_format[] = $formatData;
               //echo "<br>data : ".(isset($row[$col])?$row[$col]:"");
           }
          // $writer->writeSheetRow($domain['name'], $row_data, $row_data_format);
           $writer->writeSheetRow($domain['name'], $row_data);
       }//while
     }//if
   }
   else{ // poc_screen
     $query = "select distinct  u.pid, u2.uic2 as uic,u.proj_group_id as group_id,
       'SCRN' as visit_id, t.*
     from p_project_uid_list as u, uic_gen as u2,
     $prefix_db".$domain['id']." as t
     WHERE t.uid=u.uid AND u.uid=u2.uid AND u.proj_id=?
     AND u.uid_status IN(1,2)
     ORDER BY collect_date, u.pid";

     $stmt = $mysqli->prepare($query);
     $stmt->bind_param("s", $proj_id);
     if ($stmt->execute()){
       $result = $stmt->get_result();

       while($row = $result->fetch_assoc()) {
           $row_data = array();
           $row_data_format = array();
           /*
           $row_data[]=$row["uic"];
           $row_data[]=$row["pid"];
  */
           foreach($row_head as $col){

             $row_data[]=(isset($row[$col])?$row[$col]:"");
             $row_data_format[] = $formatData;
               //echo "<br>data : ".(isset($row[$col])?$row[$col]:"");
           }
          // $writer->writeSheetRow($domain['name'], $row_data, $row_data_format);
           $writer->writeSheetRow($domain['name'], $row_data);
       }//while
     }//if
   }

   $stmt->close();



}//foreach domain data
$mysqli->close();

//setLogNote($sc_id, "[$proj_id] Export Data");

/*
$arr_col = array();
$inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='x_poc_screen' ";
$stmt = $mysqli->prepare($inQuery);
//$stmt->bind_param("s", $course_id);
$stmt->execute();

$stmt->bind_result($col_name);

while ($stmt->fetch()) {
   $arr_domain[] = array('id'=>$proj_id, 'name'=>$col_name);
}

$stmt->close();

foreach($arr_domain as $domain){
   echo "<br>".$domain['id']. "- ".$domain['name'];

}//foreach
*/











 //echo "dir : $file_dir";
makeDirectory($file_dir);

$time_issue = "[".(new DateTime())->format('d-M-y_H-i')."]";

$file_namex = $file_name.$time_issue.".xlsx";
$file_namex = $file_name.time().".xlsx";

$writer->writeToFile($file_dir."/".$file_namex);
//echo "output file :".$file_dir."/".$file_name;



$web_path = "w_data/data/$file_namex";
//echo " web_path: $web_path";
// return object
//$rtn['mode'] = $u_mode;
$rtn['msg_error'] = $msg_error;
$rtn['msg_info'] = $msg_info;
$rtn['link_xls'] = $web_path;
if($msg_error != "") {
  //setLogNote($_SESSION["user_id"], $msg_error);
  $rtn['link_xls'] = "";
}
$rtn['flagsave'] = "1";

// change to javascript readable form
$returnData = json_encode($rtn);
echo $returnData;

?>
