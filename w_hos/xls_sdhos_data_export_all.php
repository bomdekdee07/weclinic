<?

set_time_limit(60);

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


$query_add = " WHERE s.clinic_id like '$clinic_id' ";





$file_name = "export_sdhos_pro_$sc_id";


ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer = new XLSXWriter();

$formatHead = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'color'=>'#000','fill'=>'#BBDDFF', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatData = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'color'=>'#000','fill'=>'#A3D900', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');



// sheet PID
$sheet_pid = "PID";
$row_head = array();
$row_head_format = array();

$row_head[] = "clinic id";
$row_head[] = "pid";
$row_head[] = "ul";
$row_head[] = "hn";
$row_head[] = "remark";

$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;


$writer->writeSheetRow($sheet_pid, $row_head, $row_head_format);

$inQuery = "SELECT pid, ul, clinic_id, hn, remark
from sdhos_pid as s
 $query_add
order by s.clinic_id,  s.pid";


$inQuery_PID = " pid IN (SELECT s.pid from sdhos_pid as s
 $query_add
)";


//echo $inQuery;
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();
$stmt->bind_result($pid, $ul, $clinic_id, $hn, $remark);

while ($stmt->fetch()) {
  $row_data = array();
  $row_data_format = array();
  $row_data[] = "$clinic_id";
  $row_data[] = "$pid";
  $row_data[] = "$ul";
  $row_data[] = "$hn";
  $row_data[] = "$remark";
  $writer->writeSheetRow($sheet_pid, $row_data, $row_data_format);
}//while

$stmt->close();


// sheet Baseline
$sheet_baseline = "Baseline";
$inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='sdhos_sh_baseline' AND TABLE_SCHEMA='$db_name'";
//echo "<br><b>".$inQuery."</b>";
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();

$stmt->bind_result($col_name);
$row_head = array();
$row_head_format = array();

while ($stmt->fetch()) {
  if($col_name != "collect_date" && $col_name != "collect_time"){
    $row_head[] = $col_name;
    $row_head_format[] = $formatHead;
  }
 // echo "<br>colname: $col_name";
}
$writer->writeSheetRow($sheet_baseline, $row_head, $row_head_format);
$stmt->close();


// baseline data  export only new input data in weclinic (is_import=0)
$inQuery_PID_baseline = " pid IN (SELECT s.pid from sdhos_pid as s
 $query_add AND is_import=0
) ";

$query = "select *
from sdhos_sh_baseline as s
WHERE $inQuery_PID_baseline
ORDER BY s.pid, s.visit_date";
//echo "$query";

$stmt = $mysqli->prepare($query);
//$stmt->bind_param("s", $proj_id);
if ($stmt->execute()){
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()) {
      $row_data = array();
      $row_data_format = array();

      foreach($row_head as $col){

        $row_data[]=(isset($row[$col])?$row[$col]:"");
        $row_data_format[] = $formatData;
          //echo "<br>data : ".(isset($row[$col])?$row[$col]:"");
      }
     // $writer->writeSheetRow($domain['name'], $row_data, $row_data_format);
      $writer->writeSheetRow($sheet_baseline, $row_data);
  }//while
}//if

$sheet_retention = "Retention";
$inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='sdhos_sh_retention' AND TABLE_SCHEMA='$db_name'";
//echo "<br><b>".$inQuery."</b>";
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();

$stmt->bind_result($col_name);
$row_head = array();
$row_head_format = array();

while ($stmt->fetch()) {
  $row_head[] = $col_name;
  $row_head_format[] = $formatHead;
 // echo "<br>colname: $col_name";
}
$row_header = $row_head;
$row_header[1] = "visit date"; // collect_date -> visit_date * just change to excel header column


$writer->writeSheetRow($sheet_retention, $row_header, $row_head_format);
$stmt->close();

// retention
$query = "select *
from sdhos_sh_retention as s
WHERE $inQuery_PID
ORDER BY s.pid, s.collect_date";
//echo "$query";

$stmt = $mysqli->prepare($query);
//$stmt->bind_param("s", $proj_id);
if ($stmt->execute()){
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()) {
      $row_data = array();
      $row_data_format = array();

      foreach($row_head as $col){

        $row_data[]=(isset($row[$col])?$row[$col]:"");
        $row_data_format[] = $formatData;
          //echo "<br>data : ".(isset($row[$col])?$row[$col]:"");
      }
     // $writer->writeSheetRow($domain['name'], $row_data, $row_data_format);
      $writer->writeSheetRow($sheet_retention, $row_data);
  }//while
}//if




$sheet_ae = "AE (Adverse Event)";
$inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='sdhos_sh_ae' AND TABLE_SCHEMA='$db_name'";
//echo "<br><b>".$inQuery."</b>";
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();

$stmt->bind_result($col_name);
$row_head = array();
$row_head_format = array();

while ($stmt->fetch()) {
  if($col_name != "seq_no"){
    $row_head[] = $col_name;
    $row_head_format[] = $formatHead;
  }

 // echo "<br>colname: $col_name";
}

$row_header = $row_head;
$row_header[1] = "visit date"; // ae_collect_date -> visit_date * just change to excel header column

$writer->writeSheetRow($sheet_ae, $row_header, $row_head_format);
$stmt->close();

// retention
$query = "select *
from sdhos_sh_ae as s
WHERE $inQuery_PID
ORDER BY s.pid, s.ae_collect_date, s.seq_no";
//echo "$query";

$stmt = $mysqli->prepare($query);
//$stmt->bind_param("s", $proj_id);
if ($stmt->execute()){
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()) {
      $row_data = array();
      $row_data_format = array();

      foreach($row_head as $col){
          $row_data[]=(isset($row[$col])?$row[$col]:"");
          $row_data_format[] = $formatData;
      }
     // $writer->writeSheetRow($domain['name'], $row_data, $row_data_format);
      $writer->writeSheetRow($sheet_ae, $row_data);
  }//while
}//if


setLogNote($sc_id, "[SDHos] Export Data");


 //echo "dir : $file_dir";
makeDirectory($file_dir);

$time_issue = "[".(new DateTime())->format('d-M-y_H-i')."]";

$file_name = $file_name.$time_issue.".xlsx";
//$file_name = $file_name.time().".xlsx";

$writer->writeToFile($file_dir."/".$file_name);
//echo "output file :".$file_dir."/".$file_name;






$web_path = "w_hos/data/$file_name";
//echo " web_path: $web_path";
// return object
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
