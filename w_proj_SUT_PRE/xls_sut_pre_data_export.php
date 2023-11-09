<?

set_time_limit(60);

include_once("../in_auth.php");
include_once("../in_file_prop.php");
include_once("../in_db_conn.php");
include_once("../asset/xlsxwriter/xlsxwriter.class.php"); // include excel class
include_once("../function/in_file_func.php"); // include file function
include_once("../function/in_fn_date.php"); // include date function
include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber

include_once("inc_pid_format.php");





$datetime = new DateTime();

$msg_error = "";
$msg_info = "";
$returnData = "";

$file_dir = __DIR__."/data";

$file_name = "export_sut_pre_$sc_id";


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

$proj_id = "SUT_PRE";

$prefix_id= $clinic_prefix_id[$clinic_id];



// sheet screen
$sheet_screen = "Screen $clinic_id";
$inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='x_sut_pre_screen' AND TABLE_SCHEMA='$db_name'";
//echo "<br><b>".$inQuery."</b>";
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();

$stmt->bind_result($col_name);
$row_head = array();
$row_head_format = array();

$row_head[] = "pid";
$row_head[] = "pid_consent";
$row_head_format[] = $formatHead;
while ($stmt->fetch()) {
  $row_head[] = $col_name;
  $row_head_format[] = $formatHead;
 // echo "<br>colname: $col_name";
}
$writer->writeSheetRow($sheet_screen, $row_head, $row_head_format);
$stmt->close();


$query = "SELECT ul.pid, hs.consent as pid_consent, s.*
from x_sut_pre_screen as s
LEFT JOIN p_project_uid_list as ul ON (ul.uid=s.uid)
LEFT JOIN x_hivtest_self as hs ON (hs.uid=s.uid)
WHERE s.clinic_id like ? and ul.proj_id=? 
ORDER BY s.collect_date ";


//echo "$query";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $clinic_id, $proj_id);
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



$query_add = " AND uv.visit_clinic_id like '$clinic_id' ";
// sheet PID
$sheet_pid = "PID List";
$row_head = array();
$row_head_format = array();

$row_head[] = "clinic id";
$row_head[] = "pid";
$row_head[] = "uic";
$row_head[] = "uid";

$row_head[] = "age";
$row_head[] = "consent";
$row_head[] = "ผลคัดกรอง"; //
$row_head[] = "วันคัดกรอง"; // screen_date
$row_head[] = "วัน Consent"; // consent_date
$row_head[] = "ชุดตรวจครั้งที่";
$row_head[] = "วันส่ง";
$row_head[] = "วันรับ";
$row_head[] = "วันตรวจ";
$row_head[] = "วันทำลาย";

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
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;


$writer->writeSheetRow($sheet_pid, $row_head, $row_head_format);

$query = "SELECT ul.clinic_id, ul.uid, ul.pid, u.uic, uv.schedule_date, uv.visit_date, uv.schedule_date, hs.consent, hs.age,
ps.consent as scrn_consent, ps.consent_type, ul.is_consent ,
fu.st_exp_send_date, fu.st_sent_date, fu.st_receive_date, fu.st_exp_test_date,fu.st_test_date, fu.st_destroy_date,fu.st_need_repeat,
fu.st2_exp_send_date,fu.st2_sent_date,fu.st2_receive_date, fu.st2_exp_test_date,fu.st2_test_date, fu.st2_destroy_date

FROM p_project_uid_list as ul, uic_gen as u, x_sut_pre_screen as ps,
p_project_uid_visit as uv
   LEFT JOIN x_hivtest_self as hs ON(uv.uid=hs.uid AND uv.visit_date=hs.collect_date)
   LEFT JOIN x_sut_pre_follow as fu ON(uv.uid=fu.uid AND uv.schedule_date=fu.collect_date)

WHERE ul.uid=ps.uid AND ul.screen_date=ps.collect_date AND
ul.uid=u.uid AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND
ul.proj_id='$proj_id' and ul.pid <> 'wait_pid' $query_add
ORDER BY ul.pid desc
";


//echo "<br>$query";
$stmt = $mysqli->prepare($query);
if($stmt->execute()){
    $stmt->bind_result($sut_clinic_id, $uid, $pid, $uic,$screen_date, $visit_date, $schedule_date, $consent, $age,
    $scrn_consent, $consent_type, $is_confirm_consent,
    $st_exp_send_date, $st_send_date, $st_receive_date, $st_exp_test_date,$st_test_date, $st_destroy_date,$st_need_repeat,
    $st2_exp_send_date,$st2_send_date,$st2_receive_date, $st2_exp_test_date,$st2_test_date, $st2_destroy_date

  );

}
while ($stmt->fetch()) {
  $row_data = array();
  $row_data_format = array();

  $row_data[]= $sut_clinic_id;
  $row_data[]= $pid;
  $row_data[]= $uic;
  $row_data[]= $uid;
  $row_data[]= $age;
  $row_data[]=$consent;
  $row_data[]=$scrn_consent;
  $row_data[]=$screen_date;
  $row_data[]= ($visit_date != "0000-00-00")?$visit_date:"";
  $row_data[]=($st_need_repeat == 'Y')?"2":"1";

  $send_date = "";
  $receive_date = "-";
  $test_date = "";
  $destroy_date = "-";

  $arrDate = array();
  if($st_need_repeat != 'Y'){ // 1st test
    if($st_send_date != "0000-00-00" && $st_send_date !==NULL) $send_date = changeToThaiDate($st_send_date);
    else{
      if($st_exp_send_date != "0000-00-00"  && $st_exp_send_date !==NULL){
        $send_date = "(นัด) ".changeToThaiDate($st_exp_send_date);
      }
      else if($st_receive_date != "0000-00-00"  && $st_receive_date !==NULL){
        $send_date = "รับที่ CBO";
      }
      else $send_date = "ยังไม่กำหนด";
    }

    if($st_receive_date != "0000-00-00" && $st_receive_date !==NULL) $receive_date = changeToThaiDate($st_receive_date);

    if($st_test_date != "0000-00-00" && $st_test_date !==NULL) $test_date = changeToThaiDate($st_test_date);
    else{
      if($st_exp_test_date != "0000-00-00" && $st_exp_test_date !==NULL){
        $test_date = "(นัด) ".changeToThaiDate($st_exp_test_date);
      }
      else $test_date = "ยังไม่กำหนด";
    }

    if($st_destroy_date != "0000-00-00" && $st_destroy_date !==NULL) $destroy_date = changeToThaiDate($st_destroy_date);
  }
  else{ // 2nd test
    if($st2_send_date != "0000-00-00" && $st2_send_date !==NULL) $send_date = changeToThaiDate($st2_send_date);
    else{
      if($st2_exp_send_date != "0000-00-00"  && $st2_exp_send_date !==NULL){
        $send_date = "(นัด) ".changeToThaiDate($st2_exp_send_date);
      }
      else if($st2_receive_date != "0000-00-00"  && $st2_receive_date !==NULL){
        $send_date = "รับที่ CBO";
      }
      else $send_date = "ยังไม่กำหนด";
    }

    if($st2_receive_date != "0000-00-00" && $st2_receive_date !==NULL) $receive_date = changeToThaiDate($st2_receive_date);

    if($st2_test_date != "0000-00-00" && $st2_test_date !==NULL) $test_date = changeToThaiDate($st2_test_date);
    else{
      if($st2_exp_test_date != "0000-00-00" && $st2_exp_test_date !==NULL){
        $test_date = "(นัด) ".changeToThaiDate($st2_exp_test_date);
      }
      else $test_date = "ยังไม่กำหนด";
    }

    if($st2_destroy_date != "0000-00-00" && $st2_destroy_date !==NULL) $destroy_date = changeToThaiDate($st2_destroy_date);
  } // else

  $row_data[]= $send_date;
  $row_data[]= $receive_date;
  $row_data[]=$test_date;
  $row_data[]=$destroy_date;

  $writer->writeSheetRow($sheet_pid, $row_data, $row_data_format);
}// while


$stmt->close();




// sheet visit form
$sheet_screen = "Consent Visit Form";
$inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='x_hivtest_self' AND TABLE_SCHEMA='$db_name'";
//echo "<br><b>".$inQuery."</b>";
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();

$stmt->bind_result($col_name);
$row_head = array();
$row_head_format = array();

$row_head[] = "pid";
$row_head_format[] = $formatHead;
while ($stmt->fetch()) {
  $row_head[] = $col_name;
  $row_head_format[] = $formatHead;
 // echo "<br>colname: $col_name";
}
$writer->writeSheetRow($sheet_screen, $row_head, $row_head_format);
$stmt->close();

$query = "SELECT ul.pid, s.* from x_hivtest_self as s,
p_project_uid_list as ul, p_project_uid_visit as uv
WHERE s.uid=uv.uid AND uv.uid=ul.uid $query_add
ORDER BY ul.pid";
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


// sheet Follow Up
$sheet_screen = "Follow Up";
$inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='x_sut_pre_follow' AND TABLE_SCHEMA='$db_name'";
//echo "<br><b>".$inQuery."</b>";
$stmt = $mysqli->prepare($inQuery);
$stmt->execute();

$stmt->bind_result($col_name);
$row_head = array();
$row_head_format = array();

$row_head[] = "pid";
$row_head_format[] = $formatHead;
while ($stmt->fetch()) {
  $row_head[] = $col_name;
  $row_head_format[] = $formatHead;
 // echo "<br>colname: $col_name";
}
$writer->writeSheetRow($sheet_screen, $row_head, $row_head_format);
$stmt->close();

$query = "SELECT ul.pid, s.* from x_sut_pre_follow as s,
p_project_uid_list as ul, p_project_uid_visit as uv
WHERE s.uid=uv.uid AND uv.uid=ul.uid $query_add
ORDER BY ul.pid";
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

setLogNote($sc_id, "[$proj_id] Export Data");


 //echo "dir : $file_dir";
makeDirectory($file_dir);

$time_issue = "[".(new DateTime())->format('d-M-y_H-i')."]";

$file_name = $file_name.$time_issue.".xlsx";
//$file_name = $file_name.time().".xlsx";

$writer->writeToFile($file_dir."/".$file_name);
//echo "output file :".$file_dir."/".$file_name;






$web_path = "w_proj_SUT_PRE/data/$file_name";
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
