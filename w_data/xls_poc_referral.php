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

$file_name = "export_poc_referral_$sc_id";


ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer = new XLSXWriter();

$formatHead = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatData = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'color'=>'#000','fill'=>'#A3D900', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');

$formatDataGreen = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#DFFFBF', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataYellow = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#FFFFBF', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataBlue = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#BFFFFF', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataLightBlue = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#BFFFFF', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataPink = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#FFCFBF', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataPurple = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#9370DB', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');



$proj_id = "poc_referral";
$query_add = " AND uv.visit_clinic_id like '$clinic_id' ";
// sheet PID
$sheet_pid = "POC Referral";
$row_head = array();
$row_head_format = array();

$row_head[] = "Clinic ID";
$row_head[] = "PID";
$row_head[] = "UIC";
$row_head[] = "UID";

$row_head[] = "Visit ID";
$row_head[] = "Visit Date";

$row_head[] = "NG Result"; //
$row_head[] = "NG Referral"; //
$row_head[] = "NG Ref Date"; // Referal date
$row_head[] = "NG Ref Place "; // referral place

$row_head[] = "CT Result"; //
$row_head[] = "CT Referral"; //
$row_head[] = "CT Ref Date"; // Referal date
$row_head[] = "CT Ref Place "; // referral place

$row_head[] = "Syphilis TPHA Result"; //
$row_head[] = "Syphilis RPR Result"; //
$row_head[] = "Syphilis Referral"; //
$row_head[] = "Syphilis Ref Date"; // Referal date
$row_head[] = "Syphilis Ref Place "; // referral place

$row_head[] = "HIV Result"; //
$row_head[] = "HIV Referral"; //
$row_head[] = "HIV Ref Date"; // Referal date
$row_head[] = "HIV Ref Place "; // referral place

$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;

$row_head_format[] = $formatDataGreen;
$row_head_format[] = $formatDataGreen;
$row_head_format[] = $formatDataGreen;
$row_head_format[] = $formatDataGreen;

$row_head_format[] = $formatDataYellow;
$row_head_format[] = $formatDataYellow;
$row_head_format[] = $formatDataYellow;
$row_head_format[] = $formatDataYellow;

$row_head_format[] = $formatDataBlue;
$row_head_format[] = $formatDataBlue;
$row_head_format[] = $formatDataBlue;
$row_head_format[] = $formatDataBlue;
$row_head_format[] = $formatDataBlue;

$row_head_format[] = $formatDataPink;
$row_head_format[] = $formatDataPink;
$row_head_format[] = $formatDataPink;
$row_head_format[] = $formatDataPink;


$writer->writeSheetRow($sheet_pid, $row_head, $row_head_format);

// ignore visit_status: 10 (lost fu) 11 (change group)
$query = "SELECT ul.clinic_id, ul.uid, ul.pid, u.uic, uv.visit_id, uv.visit_date,
l.hiv_result, l.ct_pool_result, l.ng_pool_result, l.tpha_result, l.rpr_result, l.rpr_titer,
r.referral_opt_hiv , r.referral_date_hiv, r.referral_place_hiv ,r.referral_province_hiv ,
r.referral_opt_ct , r.referral_date_ct, r.referral_place_ct , r.referral_province_ct ,
r.referral_opt_ng , r.referral_date_ng, r.referral_place_ng ,  r.referral_province_ng ,
r.referral_opt_syphilis , r.referral_date_syphilis, r.referral_place_syphilis, r.referral_province_syphilis

FROM p_project_uid_list as ul, uic_gen as u, x_lab_result as l ,
p_project_uid_visit as uv
LEFT JOIN x_referral as r ON
(uv.visit_date=r.collect_date AND uv.uid=r.uid)

WHERE ul.uid=u.uid AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND
l.collect_date=uv.visit_date AND l.uid=uv.uid AND ul.proj_id='POC'
AND uv.visit_id <> 'SCRN' AND uv.visit_status NOT IN('10', '11')
AND (l.hiv_result='R' OR l.ng_pool_result='D' OR l.ct_pool_result='D' OR
l.tpha_result='R' OR l.rpr_result='R')
$query_add
ORDER BY ul.pid,uv.visit_date
";


//echo "<br>$query";
$stmt = $mysqli->prepare($query);
if($stmt->execute()){
    $stmt->bind_result($cbo_clinic_id, $uid, $pid, $uic, $visit_id, $visit_date,
$hiv_result, $ct_pool_result, $ng_pool_result, $tpha_result, $rpr_result, $rpr_titer,
$referral_opt_hiv , $referral_date_hiv, $referral_place_hiv ,$referral_province_hiv,
$referral_opt_ct , $referral_date_ct, $referral_place_ct,$referral_province_ct,
$referral_opt_ng , $referral_date_ng, $referral_place_ng,$referral_province_ng,
$referral_opt_syphilis , $referral_date_syphilis, $referral_place_syphilis, $referral_province_syphilis
  );

}
while ($stmt->fetch()) {
  $row_data = array();

//echo "$pid <br>";
  $row_data[]= $cbo_clinic_id;
  $row_data[]= $pid;
  $row_data[]= $uic;
  $row_data[]= $uid;
  $row_data[]= $visit_id;
  $row_data[]= ($visit_date != "0000-00-00")?$visit_date:"";

  $row_data[]= $ng_pool_result;
  $row_data[]= $referral_opt_ng;
  $row_data[]= ($referral_date_ng != "0000-00-00")?$referral_date_ng:"";
  $referral_province_ng = ($referral_province_ng != "")?" ".$referral_province_ng:"";
  $row_data[]= "$referral_place_ng $referral_province_ng";

  $row_data[]= $ct_pool_result;
  $row_data[]= $referral_opt_ct;
  $row_data[]= ($referral_date_ct != "0000-00-00")?$referral_date_ct:"";
  $referral_province_ct = ($referral_province_ct != "")?" ".$referral_province_ct:"";
  $row_data[]= "$referral_place_ct $referral_province_ct";

  $row_data[]= $tpha_result;
  $rpr_titer = ($rpr_titer != "")?" (titer $rpr_titer)":"";
  $row_data[]= "$rpr_result $rpr_titer";
  $row_data[]= $referral_opt_syphilis;
  $row_data[]= ($referral_date_syphilis != "0000-00-00")?$referral_date_syphilis:"";
  $referral_province_syphilis = ($referral_province_syphilis != "")?" ".$referral_province_syphilis:"";
  $row_data[]= "$referral_place_syphilis $referral_province_syphilis";

  $row_data[]= $hiv_result;
  $row_data[]= $referral_opt_hiv;
  $row_data[]= ($referral_date_hiv != "0000-00-00")?$referral_date_hiv:"";
  $referral_province_hiv = ($referral_province_hiv != "")?" ".$referral_province_hiv:"";
  $row_data[]= "$referral_place_hiv $referral_province_hiv";

  $writer->writeSheetRow($sheet_pid, $row_data, $row_head_format);
}// while


$stmt->close();


$sheet_howto = "How to use";
$row_head = array();
$row_head_format = array();
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;

$row_data_format = array();
$row_data_format[] = $formatDataGreen;
$row_data_format[] = $formatDataGreen;
$row_data_format[] = $formatDataGreen;

$row_head[] = "ข้อมูล";
$row_head[] = "ตัวย่อ";
$row_head[] = "ความหมาย";


$writer->writeSheetRow($sheet_howto, $row_head, $row_head_format);

$row_data = array();
$row_data[] = "ผลแล๊ป";
$row_data[] = "D";
$row_data[] = "Detect (ตรวจพบ)";
$writer->writeSheetRow($sheet_howto, $row_data, $row_data_format);

$row_data = array();
$row_data[] = "ผลแล๊ป";
$row_data[] = "ND";
$row_data[] = "Not Detect (ตรวจไม่พบ)";
$writer->writeSheetRow($sheet_howto, $row_data, $row_data_format);


$row_data = array();
$row_data[] = "ผลแล๊ป";
$row_data[] = "R";
$row_data[] = "Reactive (ผลเป็นบวก)";
$writer->writeSheetRow($sheet_howto, $row_data, $row_data_format);

$row_data = array();
$row_data[] = "ผลแล๊ป";
$row_data[] = "NR";
$row_data[] = "Non Reactive (ผลเป็นลบ)";
$writer->writeSheetRow($sheet_howto, $row_data, $row_data_format);

$row_data = array();
$row_data[] = "ผลแล๊ป";
$row_data[] = "I";
$row_data[] = "Inconclusive (สรุปผลไม่ได้)";
$writer->writeSheetRow($sheet_howto, $row_data, $row_data_format);

$row_data = array();
$row_data[] = "ผลแล๊ป";
$row_data[] = "NT";
$row_data[] = "Not Test (ไม่ได้ตรวจ)";
$writer->writeSheetRow($sheet_howto, $row_data, $row_data_format);



$row_data_format = array();
$row_data_format[] = $formatDataYellow;
$row_data_format[] = $formatDataYellow;
$row_data_format[] = $formatDataYellow;

$row_data = array();
$row_data[] = "การส่งต่อ";
$row_data[] = "N";
$row_data[] = "ไม่ (ไม่ส่งต่อ)";
$writer->writeSheetRow($sheet_howto, $row_data, $row_data_format);

$row_data = array();
$row_data[] = "การส่งต่อ";
$row_data[] = "Y";
$row_data[] = "ใช่ (มีการส่งต่อ)";
$writer->writeSheetRow($sheet_howto, $row_data, $row_data_format);








setLogNote($sc_id, "[$proj_id] Export Data");


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
