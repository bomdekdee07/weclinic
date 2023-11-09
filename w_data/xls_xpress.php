<?
//session_start();

// export data  XPress Service
// including xpress assessment, consent, satisfaction, send result, return visit

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



$proj_id = "xpress";
//$txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";

$date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:"";
$date_end = isset($_POST["date_end"])?$_POST["date_end"]:"";
$txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";

$file_name = "export_".$sc_id."_$proj_id"."_$date_beg"."_to_$date_end"."_";

$query_add = "";

if($date_beg != ""){
  $query_add .= "AND x.collect_date >= '$date_beg' AND x.collect_date <='$date_end' ";
}
if($txt_search != ""){
  $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
  $query_add .= " AND (x.uid LIKE '$txt_search' OR u.uic2 LIKE '$txt_search') ";
}


ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer = new XLSXWriter();

$formatHead1 = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'fill'=>'#BBDDFF', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatHead2 = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'fill'=>'#FFA64D', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatHead3 = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'fill'=>'#A3D900', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');


$formatData1 = array('font'=>'Arial','font-size'=>10,'fill'=>'#F4FCFF', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatData2 = array('font'=>'Arial','font-size'=>10,'fill'=>'#FFEBD7', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatData3 = array('font'=>'Arial','font-size'=>10,'fill'=>'#F7FFDB', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');


$sheet1 = "XPRESS Service $date_beg - $date_end";
$formatHead = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'color'=>'#000','fill'=>'#BBDDFF', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatData = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'color'=>'#000','fill'=>'#A3D900', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');


$row_head_format = array();

$row_head1 = array(); // xpress service
$row_head2 = array(); // xpress Satisfaction
$row_head3 = array(); // xpress send & return visit

$inQuery = "select COLUMN_NAME from information_schema.COLUMNS
where TABLE_NAME='x_xpress_service' AND TABLE_SCHEMA='$db_name'";
//echo "<br><b>".$inQuery."</b>";

$stmt = $mysqli->prepare($inQuery);
//$stmt->bind_param("s", $course_id);
$stmt->execute();
$stmt->bind_result($col_name);

$row_head1[] = "uic";
$row_head_format[] = $formatHead1;

while ($stmt->fetch()) {
  if($col_name != "version"){
    $row_head1[] = $col_name;
    $row_head_format[] = $formatHead1;
  }

}
$stmt->close();

// satisfaction
$inQuery = "select COLUMN_NAME from information_schema.COLUMNS
where TABLE_NAME='x_xpress_satisfaction' AND TABLE_SCHEMA='$db_name'";
//echo "<br><b>".$inQuery."</b>";

$stmt = $mysqli->prepare($inQuery);
$stmt->execute();

$stmt->bind_result($col_name);

while ($stmt->fetch()) {
  if($col_name != "uid" && $col_name != "collect_date" && $col_name != "collect_time"){
    $row_head2[] = $col_name;
    $row_head_format[] = $formatHead2;
  }
}
$stmt->close();

// send xpress and return visit
$inQuery = "select COLUMN_NAME from information_schema.COLUMNS
where TABLE_NAME='p_xpress_service' AND TABLE_SCHEMA='$db_name'";
//echo "<br><b>".$inQuery."</b>";
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();
$stmt->bind_result($col_name);

while ($stmt->fetch()) {
  if($col_name != "uid" && $col_name != "collect_date"){
    $row_head3[] = $col_name;
    $row_head_format[] = $formatHead3;
  }
}

$stmt->close();
// row head
$writer->writeSheetRow($sheet1, array_merge($row_head1,$row_head2,$row_head3), $row_head_format);


$arr_uid_data = array();

// xpress satisfaction
$inQuery = "SELECT u.uic2 as uic, x.*
FROM uic_gen as u, x_xpress_satisfaction AS x
WHERE u.uid = x.uid
$query_add
ORDER BY x.collect_date ASC
";
//echo "<br><b>".$inQuery."</b>";
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()) {
  foreach($row_head2 as $col){
  //  $row_data[]=(isset($row[$col])?$row[$col]:"");
    $arr_uid_data[$row["uid"].$row["collect_date"]][$col] = $row[$col];
  }
}//while
$stmt->close();


// xpress send result and return visit
$inQuery = "SELECT u.uic2 as uic, x.*
FROM uic_gen as u, p_xpress_service AS x
WHERE u.uid = x.uid
$query_add
ORDER BY x.collect_date ASC
";
//echo "<br><b>".$inQuery."</b>";
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()) {
  foreach($row_head3 as $col){
    $arr_uid_data[$row["uid"].$row["collect_date"]][$col] = $row[$col];
  }
}//while
$stmt->close();




$row_data = array();
$row_data_format = array();

// xpress service
$inQuery = "SELECT u.uic2 as uic, x.*
FROM uic_gen as u, x_xpress_service AS x
WHERE u.uid = x.uid AND x.version='CSL'
$query_add
ORDER BY x.collect_date ASC
";
//echo "<br><b>".$inQuery."</b>";
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()) {
  $row_data = array();
  foreach($row_head1 as $col){
  //  $row_data[]=(isset($row[$col])?$row[$col]:"");
    //$arr_uid_data[$row["uid"].$row["collect_date"]][$col] = $row[$col];
    $row_data[] = $row[$col];
    $row_data_format[] = $formatData1;
  }

  // there is satisfaction
  foreach($row_head2 as $col){
    if(isset($arr_uid_data[$row["uid"].$row["collect_date"]][$col])){
      $row_data[] = $arr_uid_data[$row["uid"].$row["collect_date"]][$col];
    }
    else{
      $row_data[] = "";
    }
    $row_data_format[] = $formatData2;
  }// for

// there is send & return visit
  foreach($row_head3 as $col){
    if(isset($arr_uid_data[$row["uid"].$row["collect_date"]][$col])){
      $row_data[] = $arr_uid_data[$row["uid"].$row["collect_date"]][$col];
    }
    else{
      $row_data[] = "";
    }
    $row_data_format[] = $formatData3;
  }// for

  // row data
  $writer->writeSheetRow($sheet1, $row_data, $row_data_format);

}//while
$stmt->close();










setLogNote($sc_id, "[$proj_id] Export Data $date_beg - $date_end");

 //echo "dir : $file_dir";
makeDirectory($file_dir);
$time_issue = "[".(new DateTime())->format('d-M-y_H-i')."]";

$file_name = $file_name.$time_issue.".xlsx";
//$file_name = $file_name.time().".xlsx";

$writer->writeToFile($file_dir."/".$file_name);
//echo "output file :".$file_dir."/".$file_name;



$web_path = "w_data/data/$file_name";
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


// change to javascript readable form
$returnData = json_encode($rtn);
echo $returnData;

?>
