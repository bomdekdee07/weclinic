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



$proj_id = "poc_all_visit";
//$txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
$clinic_id = isset($clinic_id)?$clinic_id:"%";
$txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";

$file_name = "export_".$sc_id."_$proj_id"."_";

 $sheet_title = "Point of Care Schedule and Visit Date ";
 $sheet_title .= "[Issued Date: ".(new DateTime())->format('d M y H:i:s');
 $query_add = "";
      if($txt_search != "" ){
        $sheet_title .= " | Searched by: $txt_search ";

        $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
        $query_add .= " AND (u.uid LIKE '$txt_search' OR u.uic2 LIKE '$txt_search' OR ul.pid LIKE '$txt_search' ) ";


      }


      if($staff_clinic_id != "%" ){
        $sheet_title .= " | Clinic: $staff_clinic_id";
        $query_add .= " AND ul.clinic_id ='$staff_clinic_id' ";
      }

      $sheet_title .= "]";

//$txt_search = "01-001-";


ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer = new XLSXWriter();

$formatHead = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'color'=>'#000','fill'=>'#EEE', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatHeadSchedule = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'color'=>'#000','fill'=>'#BBDDFF', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');

$formatData = array('font'=>'Arial','font-size'=>10, 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatDataLeft = array('font'=>'Arial','font-size'=>10, 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataGreen = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#A3D900', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatDataYellow = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#FFFF99', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatDataBlue = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#BBDDFF', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatDataPink = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#FF9999', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');
$formatDataPurple = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#9370DB', 'border'=>'top,left', 'halign'=>'center', 'valign'=>'center');

// for manual sheet (คู่มือใช้งาน)
$formatHead2 = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'color'=>'#000', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'middle');
$formatData2 = array('font'=>'Arial','font-size'=>10, 'border'=>'top,left', 'halign'=>'left', 'valign'=>'middle');



$sheet1 = "POC Visit Schedule ";




// title
$writer->writeSheetRow($sheet1, array("$sheet_title"), $row_head_format);

$row_head_format = array();

$row_head = array();
$row_head[] = "PID";
$row_head[] = "UIC";
$row_head[] = "UID";
$row_head[] = "Enroll";
$row_head[] = "GROUP";
$row_head[] = "Extra";
$row_head[] = "M0 Schedule";
$row_head[] = "M0 Visit";
$row_head[] = "M1 Schedule";
$row_head[] = "M1 Visit";
$row_head[] = "M3 Schedule";
$row_head[] = "M3 Visit";
$row_head[] = "M6 Schedule";
$row_head[] = "M6 Visit";
$row_head[] = "M9 Schedule";
$row_head[] = "M9 Visit";
$row_head[] = "M12 Schedule";
$row_head[] = "M12 Visit";

$row_head[] = "Extra Visit Desc";



$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;

$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;

// row head
$writer->writeSheetRow($sheet1, $row_head, $row_head_format);


// create schedule visit_id
$arr_visit_id = array();
$arr_visit_id[] = "M0";
$arr_visit_id[] = "M1";
$arr_visit_id[] = "M3";
$arr_visit_id[] = "M6";
$arr_visit_id[] = "M9";
$arr_visit_id[] = "M12";





        $arr_data_list = array();

        $query = "SELECT u.uic2, ul.pid, uv.uid, ul.enroll_date, uv.visit_id,uv.schedule_date,
        uv.visit_date, uv.visit_status, uv.group_id

        FROM uic_gen as u, p_project_uid_list as ul,
        p_project_uid_visit as uv

        WHERE uv.proj_id='POC'
        AND uv.uid=u.uid AND uv.visit_id <> 'SCRN'
        AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND ul.uid_status=1
        $query_add
        ORDER BY ul.pid, uv.schedule_date, uv.visit_date
        ";

       $stmt = $mysqli->prepare($query);
       //$stmt->bind_param("s", $clinic_id);
       if($stmt->execute()){
         $stmt->bind_result($uic, $pid, $uid, $enroll_date, $visit_id,$schedule_date,
         $visit_date, $visit_status, $group_id
         );
         $arr_uid = array();
         $cur_visit_id = "";
         $extra_amt = 0;
         $extra_change_group = "";
         while ($stmt->fetch()) {

           if(!isset($arr_uid["$uid-$group_id"])){
              $arr_uid["$uid-$group_id"] = array();
              $arr_uid["$uid-$group_id"]["uid"]=$uid;
              $arr_uid["$uid-$group_id"]["uic"]=$uic;
              $arr_uid["$uid-$group_id"]["pid"]=$pid;
              $arr_uid["$uid-$group_id"]["group"]=$group_id;
              $arr_uid["$uid-$group_id"]["enroll"]=$enroll_date;
              $arr_uid["$uid-$group_id"]["extra"]="";
              $arr_uid["$uid-$group_id"]["extra_amt"] = "";
              $extra_amt = 0;
              $extra_change_group = "";
           }

           $visit_date = ($visit_date != "0000-00-00")?$visit_date:"";

           if($visit_id != "EX"){ // normal visit
             $arr_uid["$uid-$group_id"]["$visit_id-s_date"]=$schedule_date;
             $arr_uid["$uid-$group_id"]["$visit_id-v_date"]=$visit_date;
             $arr_uid["$uid-$group_id"]["$visit_id-st"]=$visit_status;
             $cur_visit_id = $visit_id;

           }
           else{ // extra visit
             if($visit_status == 11) // change group in extra visit
             $extra_change_group = "Y";

             $arr_uid["$uid-$group_id"]["extra"].= "[$cur_visit_id|$visit_date] ";
             $extra_amt += 1;
             $arr_uid["$uid-$group_id"]["extra_amt"] = "$extra_amt";
             $arr_uid["$uid-$group_id"]["extra_chg"] = "$extra_change_group";


           }

         }// while
       }
       else{
         $msg_error .= $stmt->error;
       }

       $stmt->close();


foreach($arr_uid as $uid_index => $uid_obj){
  //echo "<br>uic " .$uid_obj["pid"]." | ".$uid_obj["uic"];
  $row_data = array();
  $row_data_format = array();
  $rowFormatData = $formatData;

  $row_data[] = $uid_obj["pid"] ;
  $row_data[] = $uid_obj["uic"]  ;
  $row_data[] = $uid_obj["uid"]  ;
  $row_data[] = $uid_obj["enroll"]  ;
  $row_data[] = $uid_obj["group"]  ;
  $row_data[] = $uid_obj["extra_amt"]  ;

  $row_data_format[] = $formatData;
  $row_data_format[] = $formatData;
  $row_data_format[] = $formatData;
  $row_data_format[] = $formatData;
  $row_data_format[] = $formatData;

  if($uid_obj["extra_chg"] == "Y") $row_data_format[] = $formatDataPink; // extra amt
  else $row_data_format[] = $formatData;

  foreach($arr_visit_id as $visit_id){ // each proj visit id

    $row_data[] = isset($uid_obj["$visit_id-s_date"])?$uid_obj["$visit_id-s_date"]:"";


    $row_data_format[] = $formatDataBlue;
    // background color of visit_date
    if(isset($uid_obj["$visit_id-st"])){
       if($uid_obj["$visit_id-st"] == "0"){ // นัดหมาย
         $row_data[] = isset($uid_obj["$visit_id-v_date"])?$uid_obj["$visit_id-v_date"]:"";
         $row_data_format[] = $formatData;
       }
       else if($uid_obj["$visit_id-st"] == "1"){ // เสร็จสิ้น
         $row_data[] = isset($uid_obj["$visit_id-v_date"])?$uid_obj["$visit_id-v_date"]:"";
         $row_data_format[] = $formatDataGreen;
       }
       else if($uid_obj["$visit_id-st"] == "11"){ // change group
         $row_data[] = isset($uid_obj["$visit_id-v_date"])?$uid_obj["$visit_id-v_date"]:"";
         $row_data_format[] = $formatDataPink;
       }
       else if($uid_obj["$visit_id-st"] == "10"){ // lost to followup
         $row_data[] = "ไม่มา Lost FU";
         $row_data_format[] = $formatDataPurple;
       }
       else{ // other status  (visit pending)
         $row_data[] = isset($uid_obj["$visit_id-v_date"])?$uid_obj["$visit_id-v_date"]:"";
         $row_data_format[] = $formatDataYellow;
       }
    }
    else{
      $row_data[] = "";
      $row_data_format[] = $formatData;
    }

  }// foreach

  $row_data[] = $uid_obj["extra"]  ;
  $row_data_format[] = $formatDataLeft;

  // row data
  $writer->writeSheetRow($sheet1, $row_data, $row_data_format);
}// foreach



// manual sheet (คู่มือการใช้งาน)
$sheet2 = "How to use?";
$blank_data = "";

$writer->writeSheetRow($sheet2, array("การใช้งานของข้อมูล Schedule / Visit Date "), array($formatHead2));
$writer->writeSheetRow($sheet2, array("ในช่องของ Visit Date แต่ละสีจะจำแนกได้ดังนี้"), array($formatData2));

$writer->writeSheetRow($sheet2, array($blank_data,
"ช่องสีขาว คือนัดหมายที่ คนไข้/อาสา ยังไม่เข้ามา"),
array($formatData, $formatData2));

$writer->writeSheetRow($sheet2, array($blank_data,
"ช่องสีเหลือง คือนัดหมายที่ คนไข้/อาสา เข้ามาแล้วแต่ยังไม่ได้ปิด Visit"),
array($formatDataYellow, $formatData2));

$writer->writeSheetRow($sheet2, array($blank_data,
"ช่องสีเขียว คือนัดหมายที่ คนไข้/อาสา เข้ามาแล้ว และเสร็จสิ้น Visit ไปแล้ว"),
array($formatDataGreen, $formatData2));

$writer->writeSheetRow($sheet2, array($blank_data,
"ช่องสีม่วง คือนัดหมายที่ คนไข้/อาสา ไม่มาตามนัดหมาย (Lost to Followup)"),
array($formatDataPurple, $formatData2));

$writer->writeSheetRow($sheet2, array($blank_data,
"ช่องชมพู คือนัดหมายที่ คนไข้/อาสา เปลี่ยนกลุ่มใน visit นั้น (แถวถัดลงไปจะเป็นกลุ่มใหม่ และนัดหมายใหม่ของคนไข้)"),
array($formatDataPink, $formatData2));


$writer->writeSheetRow($sheet2, array($blank_data));
$writer->writeSheetRow($sheet2, array("Extra คือ จำนวนของ Extra Visit ของคนไข้รายนั้น"));
$writer->writeSheetRow($sheet2, array("Extra Desc (คอลัมภ์สุดท้าย) จะระบุว่ามา extra visit หลัง visit อะไร เช่น [M3|2020-02-01] คือมา extra visit หลัง month3 ในวันที่ 1/2/2563"));

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
