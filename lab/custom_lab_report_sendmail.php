<?
include_once("../../pribta21/in_php_function.php");
include("../../pribta21/assets/php-mailer-master/PHPMailerAutoload.php");
$s_id = "";
if (session_status() == PHP_SESSION_NONE) session_start();

if(isset($_SESSION["s_id"])){
  $s_id = $_SESSION["s_id"];
}
else if(isset($_SESSION["pribta_clinic_s_id"])){
  $s_id = $_SESSION["pribta_clinic_s_id"];
}



if($s_id == ""){
  echo "Not available / No permission : Please contact IT Staff.";
  return;
}



$lab_order_id = getQS("oid");
$specimen_id = isset($_GET["sp_id"])?$_GET["sp_id"]:"all";
$uid = getQS("uid");
$aLabList = explode("||", getQS("lablist"));
$sLabList = "'".implode("','", $aLabList)."'";
// $aLabList = isset($_POST["lablist"])?$_POST["lablist"]:array();
// $sLabList = "'".implode("','", $aLabList)."'";
$hidename = isset($_POST["hidename"])?$_POST["hidename"]:"";
$hideproject = isset($_POST["hideproject"])?$_POST["hideproject"]:"";
$email_send = getQS("email");
$subject_send = getQS("subject");
$language = getQS("language");
$interpret = getQS("interpret");
$email_from = getQS("email_from");
$type_clicni = getQS("type_clicni");

/*
$gender="M";
echo "$specimen_id/$uid/$lab_order_id";
*/
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php");

$arr_gender = array();
$arr_gender["1"] = "Male";
$arr_gender["2"] = "Female";

$tmp_plasma = 0;

$query = "SELECT o.lab_order_id, o.lab_report_note,
o.uid, o.collect_date, o.collect_time,
s.id as status_id , s.name as status_name,
p.fname, p.sname, p.en_fname, p.en_sname, p.date_of_birth as dob , p.sex, p.passport_id,po.s_name as staff_order
,o.proj_id, o.proj_pid, o.proj_visit, o.timepoint_id,
o.lab_specimen_receive, o.lab_specimen_collect, o.time_specimen_collect,
ps.s_name as staff_lab_save, ps.license_lab as staff_lab_save_license,
pc.s_name as staff_confirm, pc.license_lab as staff_confirm_license, o.time_lab_report_confirm
FROM p_lab_order as o
LEFT JOIN p_lab_status as s ON o.lab_order_status = s.id
LEFT JOIN patient_info as p ON (binary o.uid=p.uid)
LEFT JOIN p_staff as po ON (o.staff_order=po.s_id)
LEFT JOIN p_staff as ps ON (o.staff_lab_save=ps.s_id)
LEFT JOIN p_staff as pc ON (o.staff_confirm=pc.s_id)
WHERE o.lab_order_id=?

";


$stmt = $mysqli->prepare($query);
$stmt->bind_param("s",$lab_order_id);

if($stmt->execute()){
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()) {
    $arr_data_lab_order = $row;
  }
}
else{
$msg_error .= $stmt->error;
}
$stmt->close();


// specimen lab result
$arr_specimen = array();
$query = "SELECT SP.specimen_name, SP.specimen_transform, LOS.specimen_id, LOS.time_specimen_collect, LOSP.lab_group_id, LOSP.laboratory_id
FROM p_lab_order_specimen LOS
 LEFT JOIN p_lab_order_specimen_process LOSP ON LOSP.barcode = LOS.barcode
 LEFT JOIN p_lab_specimen SP ON SP.specimen_id=LOS.specimen_id
 WHERE LOS.uid= ? AND LOS.collect_date = ?  AND LOS.collect_time = ?
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss",$arr_data_lab_order['uid'],
$arr_data_lab_order['collect_date'],
$arr_data_lab_order['collect_time']);

if($stmt->execute()){
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()) {
    $specimen = ($row['specimen_transform'] == "")?$row['specimen_name']:$row['specimen_transform'];
    $specimen_collect_time = ($row['time_specimen_collect'] !== NULL)?$row['time_specimen_collect']:"-";
    $arr_specimen[$row['lab_group_id'].$row['laboratory_id']] = array($specimen, $specimen_collect_time);

  }

}
else{
$msg_error .= $stmt->error;
}
$stmt->close();


$arr_data_list = array();

$query = "SELECT
PLT.lab_id2, PLR.lab_id, PLT.lab_name, PLT.lab_name_report, PLR.lab_serial_no, PLR.external_lab as ext_lab,
PLR.lab_result_report, PLR.lab_result_note,
PLR.lab_result_status,
PLM.lab_method_name , PLT.lab_group_id, PLO.laboratory_id, PLT.specimen_transform,

PS.s_name as staff_save, PS.license_lab as staff_save_license,
PC.s_name as staff_confirm, PC.license_lab as staff_confirm_license,
PP.s_name as staff_print_by,
PLP.time_lab_confirm,

PLTRH.lab_std_male_txt as m_lab_std_txt ,
PLT.lab_result_min_male as m_min, PLT.lab_result_max_male as m_max,

PLTRH.lab_std_female_txt as f_lab_std_txt ,
PLT.lab_result_min_female as f_min, PLT.lab_result_max_female as f_max

FROM p_lab_result PLR
LEFT JOIN p_lab_order_lab_test PLO
ON PLO.uid= PLR.uid AND PLO.collect_date = PLR.collect_date AND PLO.collect_time = PLR.collect_time AND PLO.lab_id=PLR.lab_id
LEFT JOIN p_lab_test PLT
ON PLT.lab_id = PLR.lab_id
LEFT JOIN p_lab_test_group PLTG
ON PLTG.lab_group_id = PLT.lab_group_id
LEFT JOIN p_lab_method PLM
ON PLM.lab_method_id = PLTG.lab_method_id

LEFT JOIN p_lab_test_result_hist PLTRH
ON PLTRH.lab_id = PLT.lab_id
LEFT JOIN p_lab_process PLP
ON (PLP.lab_serial_no = PLR.lab_serial_no AND PLP.lab_process_status='P1')
LEFT JOIN p_staff PS
ON PS.s_id = PLP.staff_save
LEFT JOIN p_staff PC
ON PC.s_id = PLP.staff_confirm
LEFT JOIN p_staff PP
ON PP.s_id = '$s_id'

WHERE PLTRH.start_date <= now()  AND PLTRH.stop_date > now()
AND PLT.lab_id IN (".$sLabList.")
AND PLR.uid = ? AND PLR.collect_date = ? AND PLR.collect_time = ?
AND PLR.lab_result <> ''
ORDER BY PLR.external_lab, PLT.lab_group_id, PLT.lab_id2";

  //echo "<br>query2: ".$arr_data_lab_order['uid']."/".$arr_data_lab_order['collect_date']."/".$arr_data_lab_order['collect_time']." / $query";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("sss",$arr_data_lab_order['uid'],
      $arr_data_lab_order['collect_date'],
      $arr_data_lab_order['collect_time']);
      $arr_obj = array();
      $txt_row_result = "";

      $specimen_type = "";
      $staff_save = ""; $staff_confirm = ""; $staff_print_by = "";
      $staff_save_license = ""; $staff_confirm_license = ""; $time_confirm="";

      $str_specimen = "";
      if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
          $ref_range = ""; $class_lab_result = "";
          
          $tmp_diff = abs(strtotime(date("Y-m-d")) - strtotime($arr_data_lab_order["dob"]));
          $tmp_age = floor($tmp_diff / (365*60*60*24));


          $lab_test_specimen = "";
          if($row['specimen_transform'] != '') $lab_test_specimen = "| <i>".$row['specimen_transform']."</i>";
          else if(isset($arr_specimen[$row['lab_group_id'].$row['laboratory_id']])){
            // echo $arr_specimen[$row['lab_group_id'].$row['laboratory_id']][1];
            if($row["lab_id"] == "NAT_CT_A" || $row["lab_id"] == "NAT_NG_A" || $row["lab_id"] == "NAT_CT_U" || $row["lab_id"] == "NAT_NG_U"){
              if($row["lab_id"] == "NAT_CT_A")
                $lab_test_specimen = " | <i>Anal</i><br><small>".$arr_specimen[$row['lab_group_id'].$row['laboratory_id']][1]."</small>";
              else if($row["lab_id"] == "NAT_NG_A")
                $lab_test_specimen = " | <i>Anal</i><br><small>".$arr_specimen[$row['lab_group_id'].$row['laboratory_id']][1]."</small>";
              else if($row["lab_id"] == "NAT_CT_U")
                $lab_test_specimen = " | <i>Urine</i><br><small>".$arr_specimen[$row['lab_group_id'].$row['laboratory_id']][1]."</small>";
              else if($row["lab_id"] == "NAT_NG_U")
                $lab_test_specimen = " | <i>Urine</i><br><small>".$arr_specimen[$row['lab_group_id'].$row['laboratory_id']][1]."</small>";
            }
            else{
              $lab_test_specimen = " | <i>".$arr_specimen[$row['lab_group_id'].$row['laboratory_id']][0]."</i><br><small>".$arr_specimen[$row['lab_group_id'].$row['laboratory_id']][1]."</small>";
            }
          }


          if($row["m_lab_std_txt"] == $row["f_lab_std_txt"]){
            $ref_range = $row["m_lab_std_txt"];
            $class_lab_result = "lab-result";
          }
          else {

            $ref_range = "<b><u>Male</u>:</b> ".$row["m_lab_std_txt"]."<br><b><u>Female</u>:</b> ".$row["f_lab_std_txt"];
            $ref_range = str_replace("\n","<br>",$ref_range);
            $class_lab_result = "lab-result-red";
          }

         if($row["lab_result_status"] == "L1") $class_lab_result = "lab-result";
         else if($row["lab_result_status"] == "L2") $class_lab_result = "lab-result-red";
         
         $tmp_remarks = 0;
         if($row["lab_result_note"] != "" && $row["lab_name"] == "Creatinine Clearance"){
           $tmp_remarks = explode("\n", $row["lab_result_note"]);
          }
          
          if($row["lab_name"] == 'Creatinine, serum/plasma'){
             $tmp_plasma = $row["lab_result_report"];
           }  

          if($row['ext_lab'] == 0){
            $txt_row_result.="<tr>";
            
            if($row["lab_name"] == "Creatinine Clearance"){
            $txt_row_result.="<td width='25%' class='lab-row'>".$row["lab_name"]."<br><span class='lab-method'>".$row["lab_method_name"].$lab_test_specimen."</span><br/>(Cockcroft-Gault equation)</td>";
            }else{
              $txt_row_result.="<td width='25%' class='lab-row'>".$row["lab_name"]."<br><span class='lab-method'>".$row["lab_method_name"].$lab_test_specimen."</span></td>";
            }
            //$txt_row_result.="<td width='25%' class='lab-row'>".$row["lab_name"]."<br><span class='lab-method'>".$row["lab_method_name"].$lab_test_specimen."</span></td>";
            $txt_row_result.="<td width='25%' class='lab-row $class_lab_result'>".$row["lab_result_report"]."</td>";
            $txt_row_result.="<td width='30%' class='lab-row'>$ref_range</td>";
            
            
            if(count($tmp_remarks) > 0 && $row["lab_name"] == "Creatinine Clearance"){
              $txt_row_result.="<td width='20%' class='lab-row lab-note'><span style='color:red;'>".$tmp_remarks[0]."<br/>".$tmp_remarks[1]."<br/>".$tmp_remarks[2]."</span></td>";
            }else{
              $txt_row_result.="<td width='20%' class='lab-row lab-note'><span style='color:red;'>".$row["lab_result_note"]."</span></td>";  
            }
            
           // $txt_row_result.="<td width='20%' class='lab-row lab-note'><span style='color:red;'>".$row["lab_result_note"]."</span></td>";
            $txt_row_result.="</tr>";
            
            if($row["lab_name"] == "Creatinine Clearance"){
              
              
              $egfr_male = 0;
              $egfr_female = 0;
              
              if($tmp_plasma != 0){
                $tmp_serum = explode(" ", $tmp_plasma);
                if($tmp_serum[0] <= 0.9){
                    $egfr_male = (142)*(pow(($tmp_serum[0]/0.9),-0.302))*(pow(0.9938,$tmp_age));
                  }else if($tmp_serum[0] > 0.9){
                    $egfr_male = (142)*(pow(($tmp_serum[0]/0.9),-1.2))*(pow(0.9938,$tmp_age));
                  }
                
                 if($tmp_serum[0] <= 0.7){
                   $egfr_female = (142)*(pow(($tmp_serum[0]/0.7),-0.241))*(pow(0.9938,$tmp_age))*1.012;
                 }else if($tmp_serum[0] > 0.7){
                   $egfr_female = (142)*(pow(($tmp_serum[0]/0.7),-1.2))*(pow(0.9938,$tmp_age))*1.012;
                 }
                  // $egfr_male  = $tmp_serum[0];
                  // $egfr_female = ;
              }
              
              
              
             $txt_row_result.="<tr>";
             $txt_row_result.="<td width='25%'>eGFR, ml/min/1.73m^2<br>(CKD-EPI-2021)</td>";
             $txt_row_result.="<td width='25%'>Male: ".round($egfr_male,2)." <br/> Female: ".round($egfr_female,2)."</td>";
             $txt_row_result.="<td width='25%'> Normal >90 <br>Mild = 60-89 <br>Moderate= 30-59 <br>Severe = 15-29</td>";
             $txt_row_result.="<td width='25%'></td>";
             // $txt_row_result.="<td width='25%' class='lab-row $class_lab_result'>".$row["lab_result_report"]."</td>";
             // $txt_row_result.="<td width='30%' class='lab-row'>$ref_range</td>";
             // $txt_row_result.="<td width='20%' class='lab-row lab-note'><span style='color:red;'>".str_replace("\n","<br>",$row["lab_result_note"]);"</span></td>";
             $txt_row_result.="</tr>";
            }
            
          }
          else{
            $txt_row_result.="<tr>";
            $txt_row_result.="<td width='25%' class='lab-row'> ".$row["lab_name"]." <span class='ext-lab'>[External Lab]</span> </td>";
            $txt_row_result.="<td colspan='3' class='lab-row'><i>See Lab result from attached PDF file.</i> </td>";
            $txt_row_result.="</tr>";
          }

        //  $arr_data_list[] = $row;

        $staff_save = $row["staff_save"];
        $staff_save_license = " ".$row["staff_save_license"];



          $staff_confirm = (isset($arr_data_lab_order["staff_confirm"])?$arr_data_lab_order["staff_confirm"]:$row["staff_confirm"] );
          $staff_print_by = $row["staff_print_by"];

          //if($row["staff_save_license"] != "") $staff_save_license = "(".$row["staff_save_license"].")";
          if($row["staff_confirm_license"] != "") $staff_confirm_license = "(".$row["staff_confirm_license"].")";

          if($staff_confirm != "") $time_confirm = (new DateTime($row["time_lab_confirm"]))->format('d/m/Y H:i') ;



        }//while
        //$specimen_type = substr($specimen_type, 0, strlen($specimen_type)-2);
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();

      // patient age
      $today = date("Y-m-d");
      $diff = date_diff(date_create($arr_data_lab_order["dob"]), date_create($today));
      $age= $diff->format('%y');


// check to use new sys staff confirm
if($arr_data_lab_order["staff_lab_save"] !== NULL){
  $staff_save = $arr_data_lab_order["staff_lab_save"];
}

if($arr_data_lab_order["staff_confirm"] !== NULL){
  $staff_confirm = $arr_data_lab_order["staff_confirm"];
}

if($arr_data_lab_order["time_lab_report_confirm"] !== NULL){
  $time_confirm = $arr_data_lab_order["time_lab_report_confirm"];
  $time_confirm = (new DateTime($time_confirm))->format('d/m/Y H:i') ;
}

if($arr_data_lab_order["staff_lab_save_license"] !== NULL){
  if($arr_data_lab_order["staff_lab_save_license"] !="")
  $staff_save_license = "(".$arr_data_lab_order["staff_lab_save_license"].")";
}

if($arr_data_lab_order["staff_confirm_license"] !== NULL){
  if($arr_data_lab_order["staff_confirm_license"] !="")
  $staff_confirm_license = "(".$arr_data_lab_order["staff_confirm_license"].")";
}

//include_once('../asset/mpdf/vendor/autoload.php');
require('../asset/mpdf/vendor/autoload.php');




//$html .=$html_header.$tbl_general_info;
$html ="
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<style>

td.container {
    height: 50px;
}
.general-info {
  font-size:11px;
}
.lab-info {
  font-size:11px;
}
.lab-note {
  font-size:10px;
}
.lab-method {
  font-size:8px;
  color:#006DD9;
}
.lab-result {
  color:#238C00;
}

.lab-result-red {
  color:red;
}
.lab-row-header {
  height:30px;
  vertical-align:middle;
}
.line-1 {
  border-top: 1px solid #000;
}

.lab-row {
  height:20px;
  padding-top:10px;
  vertical-align:top
}
.ext-lab{
  background-color:yellow;
  font-size:8px;
  font-weight:bold;
  padding: 10px 15px;

}

.txtcenter {
  text-align:center;
}


</style>
";
//$html .="<img src='barcode_specimen/P20-0001_20201030112136_4.jpg' width='150px' >";

  //$html .= "<table><tr>";
$proj_info = "";
$convert_lab_proj_name_SDART = "";
$visit_SDART = "";
if($arr_data_lab_order["proj_id"] == "SDART"){
    $convert_lab_proj_name_SDART = "CB-SDART";
    if($arr_data_lab_order["proj_visit"] == "SCRN"){
        $visit_SDART = "Mo.0";
    }
    else{
        $visit_SDART = $arr_data_lab_order["proj_visit"];
    }
}
else{
    $convert_lab_proj_name_SDART = $arr_data_lab_order["proj_id"];
    $visit_SDART = $arr_data_lab_order["proj_visit"];
}

if($arr_data_lab_order["proj_id"] != "" && $hideproject != "1"){
    $proj_info ="[Project: ".$convert_lab_proj_name_SDART." | PID: ".$arr_data_lab_order["proj_pid"]." | Visit: ".$visit_SDART." | TP: ".$arr_data_lab_order["timepoint_id"]."  ]";
    $proj_info ="<div style='color:blue;text-align:center;font-size:10px;font-weight:bold;margin-top:0px;margin-bottom:0px;'>$proj_info</div>";
    //$txt_header .= "<div style='text-align:center;font-size:12px; color:blue;margin-top:5px; margin-bottom:5px;'>$proj_info</div>";
}

$txt_header = "
<table>
<tr>
<td style='padding-top:0px;'><img src='../image/ihri_pribta_tangerine_logo.jpg' width='100px'></td>
<td style='padding-top:15px;'>
<div style='text-align:left;font-weight:bold;font-size:13px;'>
Pribta Tangerine Polyclinic, Institute of HIV Research and Innovation
</div>
<div style='text-align:left;font-weight:bold;font-size:12px;'>
พริบตา แทนเจอรีน สหคลินิก, สถาบันเพื่อการวิจัยและนวัตกรรมด้านเอชไอวี
</div>
<div style='font-size:4px;color:white;'>
</div>
<div style='text-align:left;font-size:8px;'>
11th Floor, Chamchuri Square Building, 319 Phayathai Road, Pathumwan, Bangkok, 10330 Tel: 02-160-5373: ใบอนุญาตเลขที่ 10110004863
</div>
</td>
</tr>
</table>
<div style='text-align:center;font-size:9.5px;'>
ผ่านการรับรองระบบคุณภาพห้องปฏิบัติการ (LA) ตามมาตรฐานงานเทคนิคการแพทย์ 2563 (29 ตุลาคม 2564 ถึง 28 ตุลาคม 2567)
</div>

<div style='text-align:center;font-size:16px;font-weight:bold;margin-top:0px;margin-bottom:0px;' >
Laboratory Report
</div>
<div style='text-align:center;font-size:10px;font-weight:bold;margin-top:0px;margin-bottom:0px;'>$proj_info</div>
";

$lab_report_note = "";
if($arr_data_lab_order["lab_report_note"] != ""){
$lab_report_note = "Note: ".$arr_data_lab_order["lab_report_note"];
}

$txt_footer = "
<div style='text-align:left;font-size:10px;margin-bottom:10px;' >
$lab_report_note
</div>
<div style='font-size:10px;font-weight:bold;color:red;margin-bottom:5px;' >
Remark H = Above reference range	L = Below reference range
</div>

<div  >
<table width='100%' style='border-top: 1px solid #000;border-bottom: 1px solid #000; font-size:10px;'>

<tr>
<td width='50%' height='20px'>Reported by: $staff_save $staff_save_license</td><td>Lab Order Requested by: ".$arr_data_lab_order["staff_order"]."</td>
</tr>
<tr>
<td width='50%' height='20px'>Approved by: $staff_confirm $staff_confirm_license $time_confirm </td><td>Printed Date and Time: ".(new DateTime())->format('d/m/Y H:i')." by ".$staff_print_by."</td>
</tr>
</table>
</div>
<div style='text-align:center;font-size:10px;' >
**** FINAL REPORT, Please File ****
</div>
<div style='text-align:right;font-size:9px;' >
Page {PAGENO} / {nb}
</div>


";


if($arr_data_lab_order["lab_specimen_receive"] == "") {
    if($arr_data_lab_order["time_specimen_collect"] != NULL){
        $arr_data_lab_order["lab_specimen_receive"] = (new DateTime($arr_data_lab_order["time_specimen_collect"]))->format('d/m/Y H:i');
    }
}
if($arr_data_lab_order["lab_specimen_collect"] == "") {
    $arr_data_lab_order["lab_specimen_collect"] = $arr_data_lab_order["lab_specimen_receive"];
}

$patient_name = "Not specified";
if($hidename != '1'){
    if($arr_data_lab_order["passport_id"] != ''){
        $patient_name = $arr_data_lab_order["en_fname"]." ".$arr_data_lab_order["en_sname"];
        if(trim($patient_name) == ""){
            $patient_name = $arr_data_lab_order["fname"]." ".$arr_data_lab_order["sname"];
        }
    }
    else{
        $patient_name = $arr_data_lab_order["fname"]." ".$arr_data_lab_order["sname"];
    }
}

$passport_no = $arr_data_lab_order["passport_id"];
$txtrow_passport = "<td width='20%'></td><td width='30%'></td>";
if($passport_no != ""){
    if(strlen($passport_no) > 6)
    $txtrow_passport = "<td width='20%'>Passport:</td><td width='30%'>".$passport_no."</td>";
}

//<td width='20%'>Sex:</td><td width='30%'>".$arr_gender[$arr_data_lab_order["sex"]]."</td>
$general_info = "
<div class='line-1'></div>
<div style='padding:15px;'>
<table width='100%' class='general-info'>
<tr>
<td width='20%'>Patient Name:</td><td width='30%'>".$patient_name."</td>
<td width='20%'>Hospital Number:</td><td width='30%'>".$arr_data_lab_order["uid"]."</td>
</tr>
<tr>
<td width='20%'>Age:</td><td width='30%'>$age Years</td>
<td width='20%'>Lab Order ID:</td><td width='30%'>$lab_order_id</td>
</tr>
<tr>
<td width='20%'>Date of birth:</td><td width='30%'>".(new DateTime($arr_data_lab_order["dob"]))->format('d/m/Y')."</td>
<td width='20%'>Visit:</td><td width='30%'>".(new DateTime($arr_data_lab_order["collect_date"]." ".$arr_data_lab_order["collect_time"]))->format('d/m/Y H:i')."</td>
</tr>
<tr>
$txtrow_passport
<td width='20%'>Received Time:</td><td width='30%'>".$arr_data_lab_order["lab_specimen_receive"]."</td>
</tr>

</table>
</div>
";


$lab_result = "
<div style='text-align:center;font-size:10px;'>
<table width='100%' class='lab-info'>
<thead>
<tr ><td colspan='5' class='line-1'></td></tr>
<tr >
<td class='lab-row-header'><b>Test Name</b><br><span class='lab-method'>Lab Method | <i>Specimen</i><br><small>Collected time</small></span></td>
<td class='lab-row-header'><b>Result</b></td>
<td class='lab-row-header'><b>Reference Range</b></td>
<td class='lab-row-header'><b>Remarks</b></td>
</tr>

<tr ><td colspan='5' class='line-1' style='padding-bottom:10px;'></td></tr>
</thead>
$txt_row_result

</table>
</div>
";

$html .= "$general_info  $lab_result ";
// $arr_data_lab_order["status_name"]
//echo $html;


$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'margin_header' => 10,
    'margin_top' => 40,
    'margin_bottom' => 50,
    'default_font_size' => 9,
    'default_font' => 'Garuda'
]);

$create_pass = $arr_data_lab_order["dob"];
$create_pass = explode("-", $create_pass);
$create_pass = $create_pass[2].$create_pass[1].$create_pass[0];
$mpdf->SetProtection(array('copy','print'), $create_pass, $create_pass);
$mpdf->SetTitle("Lab Report: $lab_order_id");
$mpdf->SetHTMLHeader($txt_header);
$mpdf->SetHTMLFooter($txt_footer);

$mpdf->WriteHTML($html);
$coldate = (new DateTime($arr_data_lab_order['collect_date']))->format('dMy') ;
$file_name = $arr_data_lab_order['uid']."_".$coldate."_".$arr_data_lab_order['lab_order_id'];
$tcpdf = $mpdf->output($file_name, 'S');

// interpret attah file
$interpret_val = getQS("txt_interpret");
$interpret_con = strval($interpret_val);
$tcpdf2 = "";
$file_name2 = "";
$html_interpret = "";
$html_interpret .= $general_info;
$html_interpret .= "  <div class='line-1'></div>
                      <div style='padding:15px;'>
                        <div style='font-size:12px;'>".str_replace("\\t", "&nbsp;&nbsp;", str_replace("\\n", "<br>", $interpret_con))."</div>
                      </div>";
if($interpret_val != ""){
    $mpdf = new \Mpdf\Mpdf([
      'format' => 'A4',
      'margin_header' => 10,
      'margin_top' => 40,
      'margin_bottom' => 50,
      'default_font_size' => 9,
      'default_font' => 'Garuda'
  ]);

  $create_pass = $arr_data_lab_order["dob"];
  $create_pass = explode("-", $create_pass);
  $create_pass = $create_pass[2].$create_pass[1].$create_pass[0];
  $mpdf->SetProtection(array('copy','print'), $create_pass, $create_pass);
  $mpdf->SetTitle("Lab Report: $lab_order_id");
  $mpdf->SetHTMLHeader($txt_header);

  $mpdf->WriteHTML($html_interpret);

  $coldate = (new DateTime($arr_data_lab_order['collect_date']))->format('dMy') ;
  $file_name2 = "interpret_".$arr_data_lab_order['uid']."_".$coldate."_".$arr_data_lab_order['lab_order_id'];
  $tcpdf2 = $mpdf->output($file_name2, 'S');
}

$message = "";
$fm = $email_from;//"noreply@ihri.org";//"puritat.s@ihri.org"; // *** ต้องใช้อีเมล์ @gmail.com เท่านั้น ***
$to = $email_send;//"puritat.bom@gmail.com"; // อีเมล์ที่ใช้รับข้อมูลจากแบบฟอร์ม

$subj = $subject_send;

if($language == "TH"){
  /* ------------------------------------------------------------------------------------------------------------- */
  $message.= "<b>เรียน ผู้รับบริการ</b>\r\n";
  $message.= "ทางคลินิกขออนุญาตส่งผลการตรวจตามไฟล์แนบ และใส่รหัส (password) 8 หลัก (วันเดือนปีเกิดของผู้รับบริการในรูปแบบ 'DDMMYYYY')\r\n";
  $message.= "ตัวอย่าง  : เกิดวันที่ 5 กันยายน ค.ศ. 1988 รหัสคือ 05091988\r\n";
  $message.= "DD      : วันเกิดของท่าน วันที่ 5 ระบุ '05'\r\n";
  $message.= "MM      : เดือนเกิดของท่าน เดือนกันยายน ระบุ '09'\r\n";
  $message.= "YYYY    : ปี ค.ศ. เกิดของท่าน ปี 1988 ระบุ '1988'\r\n\r\n";

  $message.= "<b>ขอแสดงความนับถือ</b>\r\n";
  $message.= "พริบตา แทนเจอรีน สหคลินิก\r\n";
  $message.= "สถาบันเพื่อการวิจัยและนวัตกรรมด้านเอชไอวี\r\n\r\n";
  
  $message.= "<b>หมายเหตุ :</b>\r\n";
  if($type_clicni == "PRIBTA")
    $message.= "  - จดหมายอิเล็กทรอนิกส์ฉบับนี้ เป็นการส่งจากระบบอัตโนมัติ หากท่านมีข้อสงสัยหรือต้องการสอบถามรายละเอียดเพิ่มเติม โปรดตอบกลับอีเมลฉบับนี้ หรือติดต่อไลน์ของคลินิก Line ID: @Pribtaclinic\r\n";
  else
    $message.= "  - จดหมายอิเล็กทรอนิกส์ฉบับนี้ เป็นการส่งจากระบบอัตโนมัติ หากท่านมีข้อสงสัยหรือต้องการสอบถามรายละเอียดเพิ่มเติม โปรดตอบกลับอีเมลฉบับนี้ หรือติดต่อไลน์ของคลินิก Line ID: @Tangerineclinic\r\n";
  $message.= "  - กรณีเร่งด่วน รบกวนเข้ามาพบแพทย์ที่คลินิกในเวลาทำการ หรือไปรับบริการสถานพยาบาลใกล้ท่าน";
  /* ------------------------------------------------------------------------------------------------------------- */
}else{
  $message.= "<b>Dear Client,</b>\r\n";
  $message.= "Your results are now ready Please find the attached file and enter your 8 digit password to view details (The password is the client's birthday in the 'DDMMYYYY' format).\r\n";
  $message.= "Example : Your Birthday is 5 September 1988, please key in password 05091998.\r\n";
  $message.= "DD      : For 5, input '05'.\r\n";
  $message.= "MM      : For September, input '09'.\r\n";
  $message.= "YYYY    : For your birth year 1988, input '1988'.\r\n\r\n";

  $message.= "<b>Sincerely yours,</b>\r\n";
  $message.= "Pribta Tangerine Clinic.\r\n";
  $message.= "Institute for HIV Research and Innovation.\r\n\r\n";
  
  $message.= "<b>Remarks :</b>\r\n";
  if($type_clicni == "PRIBTA")
    $message.= "  - This email is auto-generated. If you have further inquiries or need more information, please reply to this email or contact us during clinic hours via Line ID: @Pribtaclinic\r\n";
  else
    $message.= "  - This email is auto-generated. If you have further inquiries or need more information, please reply to this email or contact us during clinic hours via Line ID: @Tangerineclinic\r\n";
  $message.= "  - In an urgent situation, please come to our clinic or your nearest care setting.";
}

$mesg = $message;

$mail = new PHPMailer();
$mail->SMTPDebug = 0;
$mail->CharSet = "utf-8"; 

/* ------------------------------------------------------------------------------------------------------------- */
/* ตั้งค่าการส่งอีเมล์ โดยใช้ SMTP ของ Gmail */
// $mail->IsSMTP();
// $mail->Mailer = "smtp";
$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPAuth = true;                  // enable SMTP authentication
$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
$mail->Host = "smtp.office365.com";      // sets GMAIL as the SMTP server
$mail->Port = 587;                   // set the SMTP port for the Outlook server
$mail->Username = $email_from; //"noreply@ihri.org";//"puritat.s@ihri.org";  // Gmail username หรือหากท่านใช้ G-suite / WorkSpace ให้ใช้อีเมล์ @you@yourdomain แทน
$mail->Password = "Ihri001!"; //"support@1";//"Ihri001!";    // Gmail password Ihri001!
/* ------------------------------------------------------------------------------------------------------------- */

$mail->setFrom($fm, 'IHRI Patient System.');
$mail->addAddress($to, 'User');     // Add a recipient

$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = $subj;
$mail->Body    = nl2br($mesg);
$mail->addStringAttachment($tcpdf, $file_name.'.pdf');
$mail->addStringAttachment($tcpdf2, $file_name2.'.pdf');
$mail->WordWrap = 50;  
//
if(!$mail->Send()) {
    echo 'Message was not sent.';
    echo 'ยังไม่สามารถส่งเมลล์ได้ในขณะนี้ ' . $mail->ErrorInfo."<br>";
    echo !extension_loaded('openssl')?"Not Available":"Available";
    exit;
} else {
    echo 'ส่งเมลล์สำเร็จ';
}
?>
