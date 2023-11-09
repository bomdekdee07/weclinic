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



$proj_id = "iclinic";
//$txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";

$date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:"";
$date_end = isset($_POST["date_end"])?$_POST["date_end"]:"";

$file_name = "export_".$sc_id."_$proj_id"."_$date_beg"."_to_$date_end"."_";

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


$sheet1 = "iclinic $date_beg - $date_end";
$row_head = array("Clinic Reg", "Clinic Visit", "UIC", "ชื่อผู้รับบริการ", "ID Card", "Visit Date", "Gender", "Age", "Nation", "Job", "กลุ่มเป้าหมาย", "มีความสัมพันธ์ล่าสุดกับ", "ใช้ถุงยางอนามัย", "การตรวจ HIV ที่ผ่านมา", "ประเภทบริการที่ได้รับ","บริการรับในวันนี้","บริการอื่นๆ ระบุ",
"Weight","Height","วิธีเจาะเลือด","CD4+Cells", "Cells/mm3",

"HIV Rapid Test วิธีที่ 1", "HIV Rapid Test วิธีที่ 2", "HIV Rapid Test วิธีที่ 3", "HIV Rapid Test สรุปผล",
"TRC-AC Anti-HIV Testing วิธีที่ 1", "TRC-AC Anti-HIV Testing วิธีที่ 2", "TRC-AC Anti-HIV Testing วิธีที่ 3", "TRC-AC Anti-HIV Testing สรุปผล",
"NAT for HIV", "Syphilis rapid test (TPHA)", "VDRL", "TPHA", "Anti-HCV",

"TST", "pharyngeal_gc", "pharyngeal_gt", "anal_gc", "anal_gt",
"urine_gc", "urine_gt", "neovagina_gc", "neovagina_gt",

"hivrna level", "hivrna copies ml"
);
$row_head_prop = array($formatHead,$formatHead, $formatHead, $formatHead, $formatHead,$formatHead,$formatHead, $formatHead, $formatHead, $formatHead,$formatHead, $formatHead, $formatHead);
$writer->writeSheetRow($sheet1, $row_head, $formatHead);
//$writer->writeSheetRow("pop99", $row_head, $formatHead);

$dt_clinic = array();
$dt_clinic['CZ69T7Q']="RSAT Bangkok";
$dt_clinic['QGAEJT6']="RSAT Chonburi";
$dt_clinic['2ZI59DS']="RSAT Hadyai";
$dt_clinic['1FQ43Y8']="RSAT Ubon";
$dt_clinic['KGE59XI']="SWING Bangkok";
$dt_clinic['5FABIXG']="SWING Saphan Khwai";
$dt_clinic['Q0ZYTPR']="SWING Pattaya";
$dt_clinic['UYL9RBV']="SISTER Pattaya";
$dt_clinic['CG4EWIF']="CAREMAT Chiang Mai";
$dt_clinic['WSTZUAM']="Adam Love";
$dt_clinic['MLPNJTR']="Anonymous Clinic";
$dt_clinic['PJY1CFV']="Mplus CM";
$dt_clinic['ELYM80G']="Mplus CR";


if ($gender=="1"){ $sex="ผู้ชาย"; }
  elseif ($gender=="2"){ $sex="ผู้หญิง"; }
  elseif ($gender=="3"){ $sex="สาวประเภทสอง"; }
  //elseif ($gender=="4"){ $sex="แปลงเพศเป็นหญิง"; }
  elseif ($gender=="4"){ $sex="ผู้ชาย"; }
  //elseif ($gender=="5"){ $sex="แปลงเพศเป็นชาย"; }
  elseif ($gender=="5"){ $sex="ผู้หญิง"; }

$dt_gender = array();
$dt_gender['1']="ผู้ชาย";
$dt_gender['2']="ผู้หญิง";
$dt_gender['3']="สาวประเภทสอง";
$dt_gender['4']="ผู้ชาย";
$dt_gender['5']="ผู้หญิง";



$dt_nation = array();
$dt_nation['1']="ไทย";
$dt_nation['2']="พม่า";
$dt_nation['3']="ลาว";
$dt_nation['4']="กัมพูชา";
$dt_nation['5']="อื่นๆ - ";

$dt_occup = array();
$dt_occup['1']="รับจ้าง / ลูกจ้างทั่วไป";
$dt_occup['2']="เกษตรกรรม";
$dt_occup['3']="ลูกจ้างโรงงาน / บริษัท";
$dt_occup['4']="ค้าขาย / เจ้าของกิจการ";
$dt_occup['5']="งานบริการ (เช่นเสริมสวย,ร้านอาหาร,โรงแรม)";
$dt_occup['6']="นักเรียน /  นักศึกษา";
$dt_occup['7']="ว่างงาน / ไม่มีงานทำ / อยู่บ้าน";
$dt_occup['8']="ประมง";
$dt_occup['9']="ประมงต่อเนื่อง";
$dt_occup['10']="โรงงานอุตสาหกรรม";
$dt_occup['11']="ก่อสร้าง";
$dt_occup['12']="ข้าราชการ / รัฐวิสาหกิจ";
$dt_occup['13']="ทหาร / ตำรวจ";
$dt_occup['14']="อื่นๆ - ";

  $dt_target = array();
  $dt_target['1.1']="ชายที่มีเพศสัมพันธ์กับชายทั่วไป (MSM)";
  $dt_target['1.2']="สาวประเภทสอง (TG)";
  $dt_target['1.3']="พนักงานบริการชาย (MSW)";
  $dt_target['2.1']="พนักงานบริการหญิง (FSW) อยู่เป็นหลักแหล่ง";
  $dt_target['2.2']="พนักงานบริการหญิง (FSW) อยู่ไม่เป็นหลักแหล่ง";
  $dt_target['3']="ผู้ใช้ยาเสพติดด้วยวิธีฉีด (IDU)";
  $dt_target['4']="แรงงานข้ามชาติ (MW)";
  $dt_target['5']="ผู้ต้องขัง (Prisoner)";
  $dt_target['6']="เยาวชน (อายุ 12-24 ปี) ";
  $dt_target['7']="ประชาชนทั่วไป";
  $dt_target['8']="พนักงานบริการสาวประเภทสอง (TGSW) อยู่เป็นหลักแหล่ง";
  $dt_target['9']="พนักงานบริการสาวประเภทสอง (TGSW) อยู่ไม่ป็นหลักแหล่ง";
  $dt_target['10']="ชายที่มีเพศสัมพันธ์กับสาวประเภทสอง";


  $dt_latest_inter = array();
  $dt_latest_inter['1']="ผู้ชาย";
  $dt_latest_inter['2']="ผู้หญิง";
  $dt_latest_inter['3']="สาวประเภทสอง";

  $dt_condom = array();
  $dt_condom['1']="ใช้";
  $dt_condom['2']="ไม่ได้ใช้";

  $dt_revisit = array();
  $dt_revisit['1']="เป็นผู้รับบริการรายใหม่";
  $dt_revisit['2']="เคยใช้บริการที่นี่มาก่อน";
  $dt_revisit['3']="อื่นๆ";

  $dt_service = array();
  $dt_service['1']="บริการในศูนย์หรือคลินิกให้บริการ";
  $dt_service['2']="บริการเชิงรุก";


  $dt_today = array(); // today service get
  $dt_today['1']="ตรวจแบบทราบผลภายในวันเดียวกัน";
  $dt_today['2']="ตรวจแบบกลับมาฟังผลหลังจากวันที่ตรวจ";
  $dt_today['3']="การให้ปรึกษาหลังตรวจเอชไอวี กลับมาเพื่อรับผลการตรวจ";
  $dt_today['4']="มาเข้าโครงการวิจัย";
  $dt_today['5']="มาตรวจตามนัดโครงการวิจัย";
  $dt_today['6']="อื่นๆ";

$arr_uid_visit = array();
/*
$inQuery = "SELECT q.visit_date, q.uic, b.reg_date, b.fname, b.sname, b.age, b.national_id, b.nation, b.country_other,
b.gender, b.occup, b.occup_other, b.latest_inter, b.condom, b.revisit, b.visit_other, b.cknow1,
b.cknow2, b.cknow2_remark, b.cknow3, b.cknow4, b.cknow5, b.cknow6, b.cknow7, b.cknow8, b.cknow9,
b.cknow10, b.cknow10_1, b.cknow10_2, b.cknow11, b.cknow12, b.cknow12_remark, c.target, c.service,
c.consult, c.testing, c.today, c.today1, c.know, c.know_d, c.know_m, c.know_y, c.result,
c.make, c.forward, c.forward_vocal, c.checking, c.check_cd4lv, c.check_d, c.check_m, c.check_y,
b.cknow13, b.cknow14,
l.cd4cells, l.cd4cellsmm3, l.op_method
FROM basic_reg AS b, qsystem AS q
LEFT JOIN counsel AS c ON c.case_no = q.case_no
LEFT JOIN lab_result AS l ON l.case_no = q.case_no
WHERE  b.uic = q.uic AND q.visit_date BETWEEN '$date_beg' AND '$date_end'
ORDER BY q.visit_date ASC";
*/

// sometime in counsel, lab_result has multiple entry (time)
$inQuery = "SELECT distinct q.visit_date, q.uic, b.clinic, q.clinic, b.fname, b.sname, b.age, b.national_id, b.nation, b.country_other,
b.gender, b.occup, b.occup_other, b.latest_inter, b.condom, b.revisit, b.visit_other,  c.target, c.service, c.today, c.today1,
l.op_method, l.cd4cells, l.cd4cellsmm3,

l.hivrt1, l.hivrt2, l.hivrt3, l.hivrtsum,
l.trcac1, l.trcac2, l.trcac3, l.trcachiv,
l.nathiv, l.tpha, l.vrdl, l.tpha1, l.antihcv,

l.tst, l.pharyngeal_gc, l.pharyngeal_gt, l.anal_gc, l.anal_gt,
l.urine_gc, l.urine_gt, l.neovagina_gc, l.neovagina_gt,

l.hivrnalevel, l.hivrnacopiesml,
n.wei, n.hei

FROM basic_reg AS b, qsystem AS q
LEFT JOIN counsel AS c ON c.case_no = q.case_no
LEFT JOIN lab_result AS l ON l.case_no = q.case_no
LEFT JOIN nurse AS n ON n.case_no = q.case_no
WHERE  b.uic = q.uic AND q.visit_date BETWEEN '$date_beg' AND '$date_end'
ORDER BY q.visit_date ASC";
//error_log($inQuery);
$stmt = $mysqli->prepare($inQuery);
//$stmt->bind_param("s", $course_id);
$stmt->execute();
$stmt->bind_result($visit_date, $uic, $clinic, $clinic_visit, $fname, $sname, $age, $national_id, $nation, $country_other,
$gender, $occup, $occup_other, $latest_inter, $condom, $revisit, $visit_other,  $target, $service, $today, $today1,
$op_method, $cd4cells, $cd4cellsmm3,

$hivrt1, $hivrt2, $hivrt3, $hivrtsum,
$trcac1, $trcac2, $trcac3, $trcachiv,
$nathiv, $tpha, $vrdl, $tpha1, $antihcv,

$tst, $pharyngeal_gc, $pharyngeal_gt, $anal_gc, $anal_gt,
$urine_gc, $urine_gt, $neovagina_gc, $neovagina_gt,

$hivrnalevel, $hivrnacopiesml,
$weight, $height
 );

while ($stmt->fetch()) {


  $row_data = array();
  $row_data[] = isset($dt_clinic[$clinic])?$dt_clinic[$clinic]:"";
  $row_data[] = isset($dt_clinic[$clinic_visit])?$dt_clinic[$clinic_visit]:"";
  $row_data[] = $uic;
  $row_data[] = "$fname $sname";
  $row_data[] = $national_id;
  $row_data[] = $visit_date;
  $row_data[] = isset($dt_gender[$gender])?$dt_gender[$gender]:"";
  $row_data[] = $age;
  $row_data[] = isset($dt_nation[$nation])?$dt_nation[$nation]." $country_other":"";
  $row_data[] = isset($dt_occup[$occup])?$dt_occup[$occup]." $occup_other":"";
  $row_data[] = isset($dt_target[$target])?$dt_target[$target]:"";
  $row_data[] = isset($dt_latest_inter[$latest_inter])?$dt_latest_inter[$latest_inter]:"";
  $row_data[] = isset($dt_condom[$condom])?$dt_condom[$condom]:"";
  $row_data[] = isset($dt_revisit[$revisit])?$dt_revisit[$revisit]." $visit_other":"";
  $row_data[] = isset($dt_service[$service])?$dt_service[$service]:"";
  $row_data[] = isset($dt_today[$today])?$dt_today[$today]:"";
  $row_data[] = $today1;

  $row_data[] = $weight;
  $row_data[] = $height;

  $row_data[] = $op_method;
  $row_data[] = $cd4cells;
  $row_data[] = $cd4cellsmm3;


  $row_data[] = $hivrt1;
  $row_data[] = $hivrt2;
  $row_data[] = $hivrt3;
  $row_data[] = $hivrtsum;
  $row_data[] = $trcac1;
  $row_data[] = $trcac2;
  $row_data[] = $trcac3;
  $row_data[] = $trcachiv;
  $row_data[] = $nathiv;
  $row_data[] = $tpha;
  $row_data[] = $vrdl;
  $row_data[] = $tpha1;
  $row_data[] = $antihcv;

  $row_data[] = $tst;
  $row_data[] = $pharyngeal_gc;
  $row_data[] = $pharyngeal_gt;
  $row_data[] = $anal_gc;
  $row_data[] = $anal_gt;
  $row_data[] = $urine_gc;
  $row_data[] = $urine_gt;
  $row_data[] = $neovagina_gc;
  $row_data[] = $neovagina_gt;

  $row_data[] = $hivrnalevel;
  $row_data[] = $hivrnacopiesml;

  $writer->writeSheetRow($sheet1, $row_data);
}

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
