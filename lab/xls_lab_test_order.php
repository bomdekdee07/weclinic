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
include_once("../function/in_ts_log.php"); // include log file graber


$datetime = new DateTime();

$msg_error = "";
$msg_info = "";
$returnData = "";


$file_dir = __DIR__."/data_export";


$s_id =  (isset($s_id))?$s_id:"none";

//$txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
$clinic_id = isset($clinic_id)?$clinic_id:"%";
$date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:"";
$date_end = isset($_POST["date_end"])?$_POST["date_end"]:"";

$file_name = "lab_$date_beg"."to".$date_end."_".$s_id;

$query_add = "";

if($date_beg != ""){
  $query_add .= " AND (uv.schedule_date >= '$date_beg' AND uv.schedule_date <='$date_end') ";
}




ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($file_name).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer = new XLSXWriter();

$formatHead = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'color'=>'#000','fill'=>'#EEE', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'middle');
$formatData = array('font'=>'Arial','font-size'=>10, 'border'=>'top,left', 'halign'=>'left', 'valign'=>'middle');

$formatHead2 = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'color'=>'#000', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'middle');
$formatData2 = array('font'=>'Arial','font-size'=>10, 'border'=>'top,left', 'halign'=>'left', 'valign'=>'middle');

$formatDataGreen = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#A3D900', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'middle');
$formatDataYellow = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#FFFF99', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'middle');


$sheet1 = "Lab Test Order ($date_beg - $date_end)";

// title
$sheet_title = "Lab Data Export ($date_beg - $date_end)";
$sheet_title .= " [Issued Date: ".(new DateTime())->format('d M y H:i:s')."]";
$writer->writeSheetRow($sheet1, array("$sheet_title"));


$row_head_format = array();
$row_data_format = array();

$row_head = array();
$row_head[] = "ORDER ID";
$row_head[] = "Clinic";
$row_head[] = "UID";
$row_head[] = "Visit Date";
$row_head[] = "PID";
$row_head[] = "Project";
$row_head[] = "Visit ID";
$row_head[] = "Timepoint";

$row_head[] = "Lab Test";
$row_head[] = "Test Menu";
$row_head[] = "Result";
$row_head[] = "Cost";
$row_head[] = "Sale";
$row_head[] = "Laboratory";
$row_head[] = "Sale Option";

$row_head[] = "Time Specimen Collect";
$row_head[] = "Time Last Update";
$row_head[] = "Time Confirmed";


$row_head[] = "Lab Result Note";

$col_amt = count($row_head);
for($i=0; $i<$col_amt; $i++){
  $row_head_format[] = $formatHead;
  $row_data_format[] = $formatData;
}


// row head
$writer->writeSheetRow($sheet1, $row_head, $row_head_format);




$arr_specimen_collect_time = array();
$query = "SELECT distinct PLO.uid, PLO.lab_order_id, PLO.collect_date, PLO.collect_time,
PLOSP.laboratory_id, PLOSP.lab_group_id, PLOS.time_specimen_collect
FROM p_lab_order PLO
LEFT JOIN p_lab_order_specimen PLOS
ON PLOS.uid=PLO.uid
AND PLOS.collect_date=PLO.collect_date
AND PLOS.collect_time=PLO.collect_time

LEFT JOIN p_lab_order_specimen_process PLOSP
ON PLOSP.barcode=PLOS.barcode

LEFT JOIN p_lab_order_lab_test PLOLT
ON PLOLT.uid=PLO.uid
AND PLOLT.collect_date = PLO.collect_date
AND PLOLT.collect_time = PLO.collect_time
AND PLOLT.lab_group_id = PLOSP.lab_group_id
AND PLOLT.laboratory_id = PLOSP.laboratory_id
WHERE
PLO.collect_date >= ? AND PLO.collect_date <= ?
AND PLO.lab_order_status != 'C'
ORDER BY PLO.uid, PLO.lab_order_id, PLOS.time_specimen_collect";

//echo "$clinic_id, $date_beg, $date_beg/ $query";
       $stmt = $mysqli->prepare($query);
       $stmt->bind_param("ss", $date_beg, $date_end);
       if($stmt->execute()){
         $result = $stmt->get_result();
         while($row_data = $result->fetch_assoc()) {
           // row data
           $row_id = $row_data["lab_order_id"].$row_data["laboratory_id"].$row_data["lab_group_id"];

           $arr_specimen_collect_time[$row_id] = $row_data["time_specimen_collect"];
         }// while
       }
       else{
         $msg_error .= $stmt->error;
       }

       $stmt->close();


$query = "SELECT ord.lab_order_id, p.clinic_type,
      o.uid, CONCAT(ord.collect_date, ' ', ord.collect_time) as visit_datetime,
      ord.proj_pid, ord.proj_id, ord.proj_visit, ord.timepoint_id,
      l.lab_name, g.lab_group_name, r.lab_result_report,
      sc.lab_cost, sp.lab_price,
      lbt.laboratory_name, s.sale_opt_name,
      '' as time_specimen_collect, r.time_lastupdate , r.time_confirm,
      r.lab_result_note,
      lbt.laboratory_id, g.lab_group_id
      FROM
      p_lab_test as l, p_lab_test_group as g, p_lab_order as ord,
      p_lab_order_lab_test as o
      LEFT JOIN patient_info as p  ON (p.uid=o.uid)
      LEFT JOIN p_lab_result as r  ON (r.lab_id=o.lab_id AND r.uid=o.uid
      AND r.collect_date=o.collect_date AND r.collect_time=o.collect_time)
      LEFT JOIN p_lab_laboratory as lbt ON o.laboratory_id=lbt.laboratory_id
      LEFT JOIN sale_option as s ON o.sale_opt_id = s.sale_opt_id
      LEFT JOIN p_lab_test_sale_price as sp ON (o.lab_id=sp.lab_id AND o.sale_opt_id=sp.sale_opt_id)
      LEFT JOIN p_lab_test_sale_cost as sc ON (o.lab_id=sc.lab_id AND o.laboratory_id=sc.laboratory_id)
      WHERE
      o.collect_date >= ? AND o.collect_date <= ?
      AND ord.uid=o.uid AND ord.collect_date=o.collect_date AND ord.collect_time=o.collect_time
      AND o.lab_id=l.lab_id AND l.lab_group_id=g.lab_group_id
      ORDER BY ord.lab_order_id ";

//echo "$clinic_id, $date_beg, $date_beg/ $query";
       $stmt = $mysqli->prepare($query);
       $stmt->bind_param("ss", $date_beg, $date_end);
       if($stmt->execute()){
         $result = $stmt->get_result();
         while($row_data = $result->fetch_assoc()) {
           $row_id = $row_data["lab_order_id"].$row_data["laboratory_id"].$row_data["lab_group_id"];

           if(isset($arr_specimen_collect_time[$row_id])){
             $row_data["time_specimen_collect"] = $arr_specimen_collect_time[$row_id];
           }

           unset($row_data["laboratory_id"]);
           unset($row_data["lab_group_id"]);


           // row data
           $writer->writeSheetRow($sheet1, $row_data, $row_data_format);
         }// while
       }
       else{
         $msg_error .= $stmt->error;
       }

       $stmt->close();


/*
       // manual sheet (คู่มือการใช้งาน)
       $sheet2 = "How to use?";
       $color_blank = "";

       $writer->writeSheetRow($sheet2, array("การใช้งานของข้อมูล Schedule Date "), array($formatHead2));

       $writer->writeSheetRow($sheet2, array($color_blank,
       "แถวสีขาว คือนัดหมายที่ คนไข้/อาสา ยังไม่เข้ามา"),
       array($formatData, $formatData2));

       $writer->writeSheetRow($sheet2, array($color_blank,
       "แถวสีเหลือง คือนัดหมายที่ คนไข้/อาสา เข้ามาแล้วแต่ยังไม่ได้ปิด Visit"),
       array($formatDataYellow, $formatData2));

       $writer->writeSheetRow($sheet2, array($color_blank,
       "แถวสีเขียว คือนัดหมายที่ คนไข้/อาสา เข้ามาแล้ว และเสร็จสิ้น Visit ไปแล้ว"),
       array($formatDataGreen, $formatData2));
*/



//setLogNote($sc_id, "[$proj_id] Export Data $date_beg - $date_end");

 //echo "dir : $file_dir";
makeDirectory($file_dir);
$time_issue = "[".(new DateTime())->format('d-M-y_H-i')."]";

$file_name = $file_name.$time_issue.".xlsx";
//$file_name = $file_name.time().".xlsx";

$writer->writeToFile($file_dir."/".$file_name);
//echo "output file :".$file_dir."/".$file_name;



$web_path = "lab/data_export/$file_name";
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
