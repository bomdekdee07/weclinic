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



$proj_id = "schedule_list";
//$txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
$clinic_id = isset($clinic_id)?$clinic_id:"%";
$date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:"";
$date_end = isset($_POST["date_end"])?$_POST["date_end"]:"";
$txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";

$file_name = "export_".$sc_id."_$proj_id"."_$date_beg"."_to_$date_end"."_";

$query_add = "";

if($date_beg != ""){
  $query_add .= " AND (uv.schedule_date >= '$date_beg' AND uv.schedule_date <='$date_end') ";
}

if($txt_search != ""){
  $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
  $query_add .= " AND (u.uid LIKE '$txt_search' OR u.uic2 LIKE '$txt_search') ";
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

$formatHead = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'color'=>'#000','fill'=>'#EEE', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'middle');
$formatData = array('font'=>'Arial','font-size'=>10, 'border'=>'top,left', 'halign'=>'center', 'valign'=>'middle');

$formatHead2 = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'color'=>'#000', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'middle');
$formatData2 = array('font'=>'Arial','font-size'=>10, 'border'=>'top,left', 'halign'=>'left', 'valign'=>'middle');

$formatDataGreen = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#A3D900', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'middle');
$formatDataYellow = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#FFFF99', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'middle');


$sheet1 = "Schedule Date:($date_beg - $date_end)";

// title
$sheet_title = "Point of Care Schedule Date ($date_beg - $date_end)";
$sheet_title .= " [Issued Date: ".(new DateTime())->format('d M y H:i:s')."]";
$writer->writeSheetRow($sheet1, array("$sheet_title"));


$row_head_format = array();

$row_head = array();
$row_head[] = "proj_id";
$row_head[] = "pid";
$row_head[] = "uic";
$row_head[] = "uid";
$row_head[] = "group_id";
$row_head[] = "tel";
$row_head[] = "visit_name";
$row_head[] = "schedule_date";
$row_head[] = "window_period";
$row_head[] = "visit_date";
$row_head[] = "visit_status";

$row_head[] = "visit_note";

$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;

// row head
$writer->writeSheetRow($sheet1, $row_head, $row_head_format);


$query = "SELECT u.uid, u.uic2, ul.pid, uv.schedule_date, uv.visit_date, uv.visit_id,
uv.proj_id, uv.group_id ,uv.visit_status,uv.schedule_note, p.proj_name, ps.status_name
,bg.contact ,v.visit_name, uv.visit_note,
DATE_ADD(uv.schedule_date, INTERVAL -(v.visit_day_before) DAY) as before_date ,
DATE_ADD(uv.schedule_date, INTERVAL v.visit_day_after DAY) as after_date

FROM  p_project_uid_list as ul,
p_project as p, p_visit_status as ps,

uic_gen as u LEFT JOIN basic_reg as bg ON (u.uic=bg.uic),
p_project_uid_visit as uv LEFT JOIN p_visit_list as v ON(uv.proj_id=v.proj_id AND uv.visit_id=v.visit_id)

WHERE uv.proj_id=p.proj_id AND uv.proj_id=ul.proj_id AND uv.visit_status=ps.status_id
AND u.uid=uv.uid AND uv.uid=ul.uid AND ul.uid_status=1 AND uv.visit_id <> 'SCRN'
AND ul.uid_status = '1' AND uv.visit_status NOT IN('11')
AND ul.clinic_id like ?
$query_add
ORDER BY uv.schedule_date, ul.pid asc   ";

//echo "$clinic_id, $date_beg, $date_beg/ $query";
       $stmt = $mysqli->prepare($query);
       $stmt->bind_param("s", $clinic_id);
       if($stmt->execute()){
         $stmt->bind_result($uid, $uic, $pid, $schedule_date, $visit_date,
         $visit_id, $proj_id, $group_id ,$visit_status,$schedule_note, $proj_name, $status_name,
         $uid_tel, $visit_name, $visit_note,
         $visit_date_before, $visit_date_after
         );

         while ($stmt->fetch()) {
           $row_data = array();
           $row_data_format = array();

           $rowFormatData = $formatData;

           if($visit_status == 0){ // นัดหมาย
             $visit_date = "";
           }
           else if($visit_status == 1){ // เสร็จสิ้น
             $rowFormatData = $formatDataGreen;
           }
           else{ // ยังไม่ปิด visit
             $rowFormatData = $formatDataYellow;
           }

           $row_data_format[] = $rowFormatData;
           $row_data_format[] = $rowFormatData;
           $row_data_format[] = $rowFormatData;
           $row_data_format[] = $rowFormatData;
           $row_data_format[] = $rowFormatData;
           $row_data_format[] = $rowFormatData;
           $row_data_format[] = $rowFormatData;
           $row_data_format[] = $rowFormatData;
           $row_data_format[] = $rowFormatData;
           $row_data_format[] = $rowFormatData;
           $row_data_format[] = $rowFormatData;
           $row_data_format[] = $rowFormatData;


           $row_data[] = $proj_id ;
           $row_data[] = $pid ;
           $row_data[] = $uic ;
           $row_data[] = $uid ;
           $row_data[] = $group_id ;
           $row_data[] = $uid_tel ;
           $row_data[] = $visit_name ;
           $row_data[] = $schedule_date ;
           $row_data[] = "$visit_date_before - $visit_date_after" ;

           $row_data[] = $visit_date ;
           $row_data[] = $status_name ;
           $row_data[] = $visit_note ;


           // row data
           $writer->writeSheetRow($sheet1, $row_data, $row_data_format);
         }// while
       }
       else{
         $msg_error .= $stmt->error;
       }

       $stmt->close();



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
