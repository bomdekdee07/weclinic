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



ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer = new XLSXWriter();

$formatHead = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'color'=>'#000','fill'=>'#EEE', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatHeadSchedule = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'color'=>'#000','fill'=>'#BBDDFF', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatHeadNote = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'color'=>'#000','fill'=>'#BFFFFF', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');


$formatData = array('font'=>'Arial','font-size'=>10, 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
//$formatData = array('font'=>'Arial','font-size'=>10, 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');

$formatDataLeft = array('font'=>'Arial','font-size'=>10, 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataGreen = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#A3D900', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataYellow = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#FFFF99', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataBlue = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#BBDDFF', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataLightBlue = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#BFFFFF', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataPink = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#FF9999', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');
$formatDataPurple = array('font'=>'Arial','font-size'=>10,'color'=>'#000','fill'=>'#9370DB', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'center');

// for manual sheet (คู่มือใช้งาน)
$formatHead2 = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'color'=>'#000', 'border'=>'top,left', 'halign'=>'left', 'valign'=>'middle');
$formatData2 = array('font'=>'Arial','font-size'=>10, 'border'=>'top,left', 'halign'=>'left', 'valign'=>'middle');


$datetime = new DateTime();

$msg_error = "";
$msg_info = "";
$returnData = "";


$file_dir = __DIR__."/data";



$proj_id = "poc_referral";
//$txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
$clinic_id = isset($clinic_id)?$clinic_id:"%";
$txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";
$lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[]; // list proj_group

//$lst_data = array('001', '003');

$file_name = "export_".$sc_id."_$proj_id"."_";

$row_head_format = array();

$row_head = array();
$row_head[] = "PID";
$row_head[] = "UIC";
$row_head[] = "UID";
$row_head[] = "วันที่เข้าโครงการ";
$row_head[] = "กลุ่ม";
$row_head[] = "เพศสภาพ";
$row_head[] = "ชื่อ-นามสกุล";
$row_head[] = "โทร";
$row_head[] = "Consent?";

$row_head[] = "Extra";
$row_head[] = "M0 Schedule";
$row_head[] = "M0 Visit";
$row_head[] = "M0 Note";
$row_head[] = "M1 Schedule";
$row_head[] = "M1 Visit";
$row_head[] = "M1 Note";
$row_head[] = "M3 Schedule";
$row_head[] = "M3 Visit";
$row_head[] = "M3 Note";
$row_head[] = "M6 Schedule";
$row_head[] = "M6 Visit";
$row_head[] = "M6 Note";
$row_head[] = "M9 Schedule";
$row_head[] = "M9 Visit";
$row_head[] = "M9 Note";
$row_head[] = "M12 Schedule";
$row_head[] = "M12 Visit";
$row_head[] = "M12 Note";
$row_head[] = "Extra Visit Desc";



$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHead;

$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadNote;
$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadNote;
$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadNote;
$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadNote;
$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadNote;
$row_head_format[] = $formatHeadSchedule;
$row_head_format[] = $formatHead;
$row_head_format[] = $formatHeadNote;
$row_head_format[] = $formatHead;


$row_head2_format = array();

$row_head2 = array();
$row_head2[] = "PID";
$row_head2[] = "UIC";
$row_head2[] = "UID";
$row_head2[] = "วันที่เข้าโครงการ";
$row_head2[] = "กลุ่ม";
$row_head2[] = "เพศสภาพ";
$row_head2[] = "ชื่อ-นามสกุล";
$row_head2[] = "โทร";

$row_head2[] = "นัดหมาย";
$row_head2[] = "วันนัดหมาย";
$row_head2[] = "Schedule Note";


$row_head2_format[] = $formatHead;
$row_head2_format[] = $formatHead;
$row_head2_format[] = $formatHead;
$row_head2_format[] = $formatHead;
$row_head2_format[] = $formatHead;
$row_head2_format[] = $formatHead;
$row_head2_format[] = $formatHead;
$row_head2_format[] = $formatHead;
$row_head2_format[] = $formatHeadSchedule;
$row_head2_format[] = $formatHeadSchedule;
$row_head2_format[] = $formatHeadNote;

$sheet2_title = "นัดหมายคนไข้ที่ยังไม่มาวันล่าสุด";
$sheet2 = "Incoming Schedule Date";

// title
$writer->writeSheetRow($sheet2, array("$sheet2_title"), $row_head2_format);
// row head
$writer->writeSheetRow($sheet2, $row_head2, $row_head2_format);

// get gender at Month0 of poc

$query_add_group = "";

foreach($lst_data as $proj_group){
   $query_add_group .= "'$proj_group',";
}
$query_add_group = substr($query_add_group, 0, strlen($query_add_group)-1);

$arr_demo = array();
/*  don't use this query because some patient did not fill q demo
$query = "SELECT  u.uic2, uv.uid, uv.group_id, d.gender, d.gender_text,
b.fname, b.sname, b.contact
FROM uic_gen as u, basic_reg as b, p_project_uid_list as ul,
p_project_uid_visit as uv, x_q_demo as d

WHERE uv.proj_id='POC' AND uv.visit_id = 'M0'
AND ul.proj_group_id = uv.group_id AND ul.uid=u.uid
AND u.uic=b.uic
AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND ul.uid_status=1
AND d.uid=uv.uid AND d.collect_date=uv.visit_date
AND ul.proj_group_id IN ($query_add_group)
ORDER BY ul.pid
";
*/
$query = "SELECT  u.uic2, uv.uid, uv.group_id, ul.pid, ul.enroll_date,
d.gender, d.gender_text,
d.sexatbirth, d.sexorient_male, d.sexorient_female,
b.fname, b.sname, b.contact,
ul.is_consent, ul.uid_status
FROM
p_project_uid_list as ul
LEFT JOIN uic_gen as u
   LEFT JOIN basic_reg as b ON (u.uic=b.uic)
ON (ul.uid=u.uid) ,

p_project_uid_visit as uv
LEFT JOIN x_q_demo as d ON (uv.uid=d.uid AND d.collect_date=uv.visit_date)

WHERE ul.proj_id='POC' AND uv.visit_id = 'M0'
AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND ul.uid_status IN (1,2)
AND uv.group_id IN ($query_add_group)
ORDER BY ul.pid
";

//echo "$query<br>";

$stmt = $mysqli->prepare($query);
//$stmt->bind_param("s", $clinic_id);
if($stmt->execute()){
 $stmt->bind_result($uic, $uid, $group_id, $pid, $enroll_date, $gender, $gender_other,
 $sexatbirth, $sexorient_male, $sexorient_female, $fname, $sname, $tel, $is_consent, $uid_status  );

 while ($stmt->fetch()) {
  // echo "uid : $uic, $uid, $group_id, $gender, $gender_other,$fname, $sname, $tel <br>";
   if(!isset($arr_demo["$uid-$group_id"])){
      $arr_demo["$uid-$group_id"] = array();
      $arr_demo["$uid-$group_id"]["uid"]=$uid;
      $arr_demo["$uid-$group_id"]["uic"]=$uic;
      $arr_demo["$uid-$group_id"]["pid"]=$pid;
      $arr_demo["$uid-$group_id"]["enroll"]=$enroll_date;
      $arr_demo["$uid-$group_id"]["group_id"]=$group_id;

      if($gender !== NULL){
        if($gender == 1) {//male
          if($sexorient_male == "Y" && $sexatbirth==1) $gender = "MSM"; // ผู้ชาย
          else $gender = "M"; // ผู้ชาย
        }

        if($gender == 2) {//female
          if($sexorient_male == "Y" && $sexatbirth==1) $gender = "MSM"; // ผู้ชาย
          else $gender = "F"; // ผู้หญิง
        }

        else if($gender == 3) $gender = "TGM"; //ู้ชายข้ามเพศ/ ทอม (Transgender men)
        else if($gender == 4) $gender = "TGW"; //ผู้หญิงข้ามเพศ/ สาวประเภทสอง/ กะเทย (Transgender women)
        else if($gender == 5) $gender = "OTHER ($gender_other)"; //อื่นๆ

      }
      else{
        $gender = "";
      }
      $arr_demo["$uid-$group_id"]["gender"]=$gender;

      $arr_demo["$uid-$group_id"]["name"]=($fname !== NULL)?"$fname $sname":"รอการกรอกข้อมูล";
      $arr_demo["$uid-$group_id"]["tel"]=($tel !== NULL)?"$tel":"";


      if($uid_status == 1){ // in visit loop
        $arr_demo["$uid-$group_id"]["consent"]=($is_consent==1)?"Yes":"No";
      }
      else if($uid_status == 2){ // final status
        $arr_demo["$uid-$group_id"]["consent"]="Final Status";
      }



   }
 }// while
}
else{
 $msg_error .= $stmt->error;
}

$stmt->close();





foreach($lst_data as $proj_group){

  $txt_search .= "$proj_group-";
  $sheet_title = "Point of Care Schedule and Visit Date Group $proj_group ";
  $sheet_title .= "[Issued Date: ".(new DateTime())->format('d M y H:i:s');
  $query_add = "";

  if($staff_clinic_id != "%" ){
    $sheet_title .= " | Clinic: $staff_clinic_id";
    $query_add .= " AND ul.clinic_id ='$staff_clinic_id' ";
  }
  $query_add .= " AND uv.group_id='$proj_group' ";

  $sheet_title .= "]";
  $sheet1 = "Group $proj_group";

  // title
  $writer->writeSheetRow($sheet1, array("$sheet_title"), $row_head_format);
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

          $query = "SELECT uv.uid, uv.group_id, uv.visit_id,uv.schedule_date,uv.schedule_note,
          uv.visit_date, uv.visit_status, gc.groupchange


          FROM p_project_uid_list as ul,
          p_project_uid_visit as uv LEFT JOIN x_groupchange as gc ON (uv.uid=gc.uid AND uv.visit_date=gc.collect_date)

          WHERE uv.proj_id='POC'
          AND uv.visit_id <> 'SCRN'
          AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND ul.uid_status IN (1,2)
          $query_add
          ORDER BY ul.pid, uv.schedule_date, uv.visit_date
          ";
//echo "2 $query<br>";
         $stmt = $mysqli->prepare($query);
         //$stmt->bind_param("s", $clinic_id);
         if($stmt->execute()){

           $stmt->bind_result($uid, $group_id, $visit_id,$schedule_date,$schedule_note,
           $visit_date, $visit_status , $groupchange
           );

           $arr_uid = array();
           $cur_visit_id = "";
           $extra_amt = 0;
           $extra_change_group = "";
           while ($stmt->fetch()) {
             if(!isset($arr_uid["$uid-$group_id"])){
                $arr_uid["$uid-$group_id"] = array();
                $arr_uid["$uid-$group_id"]["uid"]=$arr_demo["$uid-$group_id"]["uid"];
                $arr_uid["$uid-$group_id"]["uic"]=$arr_demo["$uid-$group_id"]["uic"];
                $arr_uid["$uid-$group_id"]["gender"]=$arr_demo["$uid-$group_id"]["gender"];
                $arr_uid["$uid-$group_id"]["name"]=$arr_demo["$uid-$group_id"]["name"];
                $arr_uid["$uid-$group_id"]["tel"]=$arr_demo["$uid-$group_id"]["tel"];

                $arr_uid["$uid-$group_id"]["consent"]=$arr_demo["$uid-$group_id"]["consent"];

/*
                $arr_uid["$uid-$group_id"]["uid"]=$uid;
                $arr_uid["$uid-$group_id"]["uic"]=$uic;
*/
                $arr_uid["$uid-$group_id"]["pid"]=$arr_demo["$uid-$group_id"]["pid"];
                $arr_uid["$uid-$group_id"]["group"]=$arr_demo["$uid-$group_id"]["group_id"];
                $arr_uid["$uid-$group_id"]["enroll"]=$arr_demo["$uid-$group_id"]["enroll"];
                $arr_uid["$uid-$group_id"]["extra"]="";
                $arr_uid["$uid-$group_id"]["extra_amt"] = "";
                $extra_amt = 0;
                $extra_change_group = "";
             }

             $visit_date = ($visit_date != "0000-00-00")?$visit_date:"";

             if($visit_id != "EX"){ // normal visit
               if($groupchange !== NULL){
                 if($visit_status == 11) $groupchange = " ($groupchange)";
                 else $groupchange="";
               }

               $arr_uid["$uid-$group_id"]["$visit_id-s_date"]=$schedule_date;
               $arr_uid["$uid-$group_id"]["$visit_id-v_date"]=$visit_date.$groupchange;
               $arr_uid["$uid-$group_id"]["$visit_id-st"]=$visit_status;
               $arr_uid["$uid-$group_id"]["$visit_id-s_note"]=$schedule_note;
               $cur_visit_id = $visit_id;

             }
             else{ // extra visit
               if($visit_status == 11) // change group in extra visit
               $extra_change_group = "Y";
               if($schedule_note != "") $schedule_note = "|Note: $schedule_note";

               $arr_uid["$uid-$group_id"]["extra"].= "[$cur_visit_id|$visit_date $schedule_note] ";
               $extra_amt += 1;

               if($groupchange !== NULL) $groupchange = " ($groupchange)";
               else $groupchange = "";

               $arr_uid["$uid-$group_id"]["extra_amt"] = "$extra_amt $groupchange";
               $arr_uid["$uid-$group_id"]["extra_chg"] = "$extra_change_group";
             }

           }// while
         }
         else{
           $msg_error .= $stmt->error;
         }

         $stmt->close();



/*
         // get the first pending schedule of each uid
         $query = "SELECT uv.uid, uv.group_id, uv.visit_id
         FROM p_project_uid_visit as uv
         INNER JOIN (
             select uid, min(schedule_date) as min_schedule_date
             from p_project_uid_visit
             where visit_status=0
             AND group_id IN ($query_add_group)
             group by uid
         ) wait_visit on uv.uid = wait_visit.uid
           AND uv.schedule_date = wait_visit.min_schedule_date
                   ";
         //echo "2 $query<br>";
                  $stmt = $mysqli->prepare($query);
                  //$stmt->bind_param("s", $clinic_id);
                  if($stmt->execute()){
                    $stmt->bind_result($uid, $group_id, $visit_id);

                    while ($stmt->fetch()) {
                      if(isset($arr_uid["$uid-$group_id"])){
                        $arr_uid["$uid-$group_id"]["s"] = "Y";
                      }
                    }// while
                  }
                  else{
                    $msg_error .= $stmt->error;
                  }

                  $stmt->close();
*/




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
    $row_data[] = $uid_obj["gender"]  ;
    $row_data[] = $uid_obj["name"]  ;
    $row_data[] = $uid_obj["tel"]  ;
    $row_data[] = $uid_obj["consent"]  ;
    $row_data[] = $uid_obj["extra_amt"]  ;


    $row_data_format[] = $formatData;
    $row_data_format[] = $formatData;
    $row_data_format[] = $formatData;
    $row_data_format[] = $formatData;
    $row_data_format[] = $formatData;
    $row_data_format[] = $formatData;
    $row_data_format[] = $formatData;
    $row_data_format[] = $formatData;
    $row_data_format[] = $formatData;


    if($uid_obj["extra_chg"] == "Y") $row_data_format[] = $formatDataPink; // extra amt
    else $row_data_format[] = $formatData;

    $is_first_schedule_pending = "Y"; // check first pending schedule date
    foreach($arr_visit_id as $visit_id){ // each proj visit id

      $row_data[] = isset($uid_obj["$visit_id-s_date"])?$uid_obj["$visit_id-s_date"]:"";


      $row_data_format[] = $formatDataBlue;
      // background color of visit_date
      if(isset($uid_obj["$visit_id-st"])){
         if($uid_obj["$visit_id-st"] == "0"){ // นัดหมาย
           $row_data[] = isset($uid_obj["$visit_id-v_date"])?$uid_obj["$visit_id-v_date"]:"";
           $row_data_format[] = $formatData;

           if($is_first_schedule_pending == "Y"){
             $row_pending_visit = array();
             $row_pending_visit_format = array();

             $row_pending_visit[] = $uid_obj["pid"] ;
             $row_pending_visit[] = $uid_obj["uic"]  ;
             $row_pending_visit[] = $uid_obj["uid"]  ;
             $row_pending_visit[] = $uid_obj["enroll"]  ;
             $row_pending_visit[] = $uid_obj["group"]  ;
             $row_pending_visit[] = $uid_obj["gender"]  ;
             $row_pending_visit[] = $uid_obj["name"]  ;
             $row_pending_visit[] = $uid_obj["tel"]  ;
             $row_pending_visit[] = $visit_id;
             $row_pending_visit[] = isset($uid_obj["$visit_id-s_date"])?$uid_obj["$visit_id-s_date"]:"";
             $row_pending_visit[] = isset($uid_obj["$visit_id-s_note"])?$uid_obj["$visit_id-s_note"]:"";

             $row_pending_visit_format[] = $formatData;
             $row_pending_visit_format[] = $formatData;
             $row_pending_visit_format[] = $formatData;
             $row_pending_visit_format[] = $formatData;
             $row_pending_visit_format[] = $formatData;
             $row_pending_visit_format[] = $formatData;
             $row_pending_visit_format[] = $formatData;
             $row_pending_visit_format[] = $formatData;
             $row_pending_visit_format[] = $formatDataBlue;
             $row_pending_visit_format[] = $formatDataBlue;
             $row_pending_visit_format[] = $formatDataLightBlue;
             // row pending_visit
             $writer->writeSheetRow($sheet2, $row_pending_visit, $row_pending_visit_format);

             $is_first_schedule_pending = "N";
           }
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
      $row_data[] = isset($uid_obj["$visit_id-s_note"])?$uid_obj["$visit_id-s_note"]:"";
      $row_data_format[] = $formatDataLightBlue;
    }// foreach

    $row_data[] = $uid_obj["extra"]  ;
    $row_data_format[] = $formatDataLeft;

    // row data
    $writer->writeSheetRow($sheet1, $row_data, $row_data_format);



  }// foreach



}//foreach proj_group









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
