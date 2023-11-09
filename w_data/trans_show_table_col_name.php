<?
//session_start();

// trainee attend course  check in-out
//echo "enterhere2 ";

set_time_limit(60);

//include_once("../in_auth.php");
include_once("../in_file_prop.php");
include_once("../in_db_conn.php");
include_once("../asset/xlsxwriter/xlsxwriter.class.php"); // include excel class
include_once("../function/in_file_func.php"); // include file function
include_once("../function/in_fn_date.php"); // include date function


$datetime = new DateTime();

$msg_error = "";
$msg_info = "";
$returnData = "";


$file_dir = __DIR__."/data";



$proj_id = isset($_POST["proj_id"])?urldecode($_POST["proj_id"]):"poc";
//$txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";

$date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:"";
$date_end = isset($_POST["date_end"])?$_POST["date_end"]:"";

$file_name = "export_$proj_id";

/*
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
*/
$writer = new XLSXWriter();
$sheet1 = "Data Export $proj_id";
$formatHead = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'color'=>'#000','fill'=>'#BBDDFF', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatData = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'color'=>'#000','fill'=>'#A3D900', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
//$formatHeadOut = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'color'=>'#000','fill'=>'#FF9326', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');

$row_head = array("Type", "Fund", "ID", "Name", "Org");
$row_head_prop = array($formatHead,$formatHead, $formatHead, $formatHead, $formatHead);
$writer->writeSheetRow($sheet1, $row_head, $formatHead);
$writer->writeSheetRow("pop99", $row_head, $formatHead);

$tmp_arr_domain = array();
$tmp_arr_domain_format = array();
$arr_domain = array();
$inQuery = "SELECT domain_id, domain_name from p_data_domain";
$stmt = $mysqli->prepare($inQuery);
//$stmt->bind_param("s", $course_id);
$stmt->execute();

$stmt->bind_result($domain_id, $domain_name);

while ($stmt->fetch()) {
   $arr_domain[] = array('id'=>$domain_id, 'name'=>$domain_name);
   //$tmp_arr_domain[]=$domain_id;
   //$tmp_arr_domain_format[] = $formatData;
}
//$writer->writeSheetRow("pop99", $tmp_arr_domain, $tmp_arr_domain_format);

$stmt->close();

foreach($arr_domain as $domain){
   echo "<br><b>".$domain['id']. "- ".$domain['name']."</b>";

   $arr_col = array();
   $inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='x_".$domain['id']."' AND TABLE_SCHEMA='$db_name'";
   $stmt = $mysqli->prepare($inQuery);
   //$stmt->bind_param("s", $course_id);
   $stmt->execute();

   $stmt->bind_result($col_name);
   $row_head = array();
   $row_head_format = array();
   while ($stmt->fetch()) {
     $row_head[] = $col_name;
     $row_head_format[] = $formatHead;
     echo "<br>colname: $col_name";
   }
  // $writer->writeSheetRow($domain['name'], $row_head, $row_head_format);
   $stmt->close();

/*
   $query = "select * from x_".$domain['id']." ORDER BY collect_date LIMIT 3 ";
   $stmt = $mysqli->prepare($query);
   if ($stmt->execute()){
     $result = $stmt->get_result();

     while($row = $result->fetch_assoc()) {
         $row_data = array();
         foreach($row_head as $col){
           $row_data[]=(isset($row[$col])?$row[$col]:"");
             //echo "<br>data : ".(isset($row[$col])?$row[$col]:"");
         }
         $writer->writeSheetRow($domain['name'], $row_data, $formatData);
     }//while
   }
   $stmt->close();
*/


}//foreach




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

$file_name = $file_name.time().".xlsx";

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
