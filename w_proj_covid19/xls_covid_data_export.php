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

$file_name = "export_covid19_$sc_id";


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

$proj_id = "COVID";

// sheet screen
$sheet_screen = "Screen";
$inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='x_z202009_covid_screen' AND TABLE_SCHEMA='$db_name'";
//echo "<br><b>".$inQuery."</b>";
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();

$stmt->bind_result($col_name);
$row_head = array();
$row_head_format = array();


$row_head_format[] = $formatHead;
while ($stmt->fetch()) {
  $row_head[] = $col_name;
  $row_head_format[] = $formatHead;
 // echo "<br>colname: $col_name";
}
$row_head_title = $row_head ;
$row_head_title[0] = "screen_id";
$writer->writeSheetRow($sheet_screen, $row_head_title, $row_head_format);
$stmt->close();

$query = "SELECT * from x_z202009_covid_screen
ORDER BY uid";
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
          //echo "<br>data $col : ".(isset($row[$col])?$row[$col]:"");
      }
     // $writer->writeSheetRow($domain['name'], $row_data, $row_data_format);
      $writer->writeSheetRow($sheet_screen, $row_data);
  }//while
}//if


for($group_id = 1; $group_id<5; $group_id++){
  $sheet_group = "Group $group_id";
  $inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='x_z202009_covid_visit_g$group_id' AND TABLE_SCHEMA='$db_name'";
  //echo "<br><b>".$inQuery."</b>";
  $stmt = $mysqli->prepare($inQuery);
  $stmt->execute();

  $stmt->bind_result($col_name);
  $row_head = array();
  $row_head_format = array();

  $row_head_format[] = $formatHead;
  while ($stmt->fetch()) {
    $row_head[] = $col_name;
    $row_head_format[] = $formatHead;
   // echo "<br>colname: $col_name";
  }
  $row_head_title = $row_head ;
  $row_head_title[1] = "pid";
  $writer->writeSheetRow($sheet_group, $row_head_title, $row_head_format);
  $stmt->close();

  $query = "SELECT * from x_z202009_covid_visit_g$group_id
  WHERE is_disable=0
  ORDER BY uid";
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
        $writer->writeSheetRow($sheet_group, $row_data);
    }//while
  }//if

  $stmt->close();
}//for


setLogNote($sc_id, "[$proj_id] Export Data");


 //echo "dir : $file_dir";
makeDirectory($file_dir);

$time_issue = "[".(new DateTime())->format('d-M-y_H-i')."]";

$file_name = $file_name.$time_issue.".xlsx";
//$file_name = $file_name.time().".xlsx";

$writer->writeToFile($file_dir."/".$file_name);
//echo "output file :".$file_dir."/".$file_name;






$web_path = "w_proj_covid19/data/$file_name";
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
