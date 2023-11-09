<?

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



$lab_order_id = isset($_GET["lab_order_id"])?$_GET["lab_order_id"]:"";
$specimen_id = isset($_GET["sp_id"])?$_GET["sp_id"]:"";
$uid = isset($_GET["uid"])?$_GET["uid"]:"";
//$gender = isset($_GET["gender"])?$_GET["gender"]:"M";

/*
$gender="M";
echo "$specimen_id/$uid/$lab_order_id";
*/
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php");

$arr_gender = array();
$arr_gender["1"] = "Male";
$arr_gender["2"] = "Female";

/*
// old sql 7/4/2021
$query = "SELECT o.lab_order_id, o.lab_report_note,
o.uid, o.collect_date, o.collect_time,
s.id as status_id , s.name as status_name,
p.fname, p.sname, p.date_of_birth as dob , p.sex, po.s_name as staff_order
,o.proj_id, o.proj_pid, o.proj_visit
FROM p_lab_order as o, p_lab_status as s, patient_info as p, p_staff as po
WHERE o.lab_order_id=? AND binary o.uid=p.uid
AND o.staff_order=po.s_id
AND o.lab_order_status = s.id
";
*/

$query = "SELECT o.lab_order_id, o.lab_report_note,
o.uid, o.collect_date, o.collect_time,
s.id as status_id , s.name as status_name,
p.fname, p.sname, p.date_of_birth as dob , p.sex, po.s_name as staff_order
,o.proj_id, o.proj_pid, o.proj_visit,
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

/*
// en name
$query = "SELECT o.lab_order_id, o.lab_report_note,
o.uid, o.collect_date, o.collect_time,
s.id as status_id , s.name as status_name,
p.en_fname as fname, p.en_sname as sname, p.date_of_birth as dob , p.sex, po.s_name as staff_order
FROM p_lab_order as o, p_lab_status as s, patient_info as p, p_staff as po
WHERE o.lab_order_id=? AND binary o.uid=p.uid
AND o.staff_order=po.s_id
AND o.lab_order_status = s.id
";
*/
//echo "query1: $lab_order_id / $query";

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



      $arr_data_list = array();
      //echo "$stop_date,$start_date, $id/ query: $query";
/*
      $col_add = "";
      if($arr_data_lab_order["sex"] == "1"){ // male
        $col_add = "rh.lab_std_male_txt as lab_std_txt ,
        l.lab_result_min_male as min, lab_result_max_male as max
        ";
      }
      else if($arr_data_lab_order["sex"] == "2"){ // female
        $col_add = "rh.lab_std_female_txt as lab_std_txt ,
        l.lab_result_min_female as min, lab_result_max_female as max
        ";
      }
      else{ // unknown
        $col_add = "rh.lab_std_female_txt as lab_std_txt ,
        l.lab_result_min_female as min, lab_result_max_female as max
        ";
      }
*/


      $query_add = "";
      if($specimen_id != "all"){
        $query_add = " AND sp.specimen_id='$specimen_id' ";
      }



      $query_add = "";
      if($specimen_id != "all"){
        $query_add = " AND PLOS.specimen_id='$specimen_id' ";
      }


$query = "SELECT
PLT.lab_id2, PLR.lab_id, PLT.lab_name, PLR.lab_serial_no, PLR.external_lab as ext_lab,
PLR.lab_result_report, PLR.lab_result_note,
PLR.lab_result_status,

PLM.lab_method_name ,

PS.s_name as staff_save, PS.license_lab as staff_save_license,
PC.s_name as staff_confirm, PC.license_lab as staff_confirm_license,
PP.s_name as staff_print_by,
PLP.time_lab_confirm,

PLTRH.lab_std_male_txt as m_lab_std_txt ,
PLT.lab_result_min_male as m_min, PLT.lab_result_max_male as m_max,

PLTRH.lab_std_female_txt as f_lab_std_txt ,
PLT.lab_result_min_female as f_min, PLT.lab_result_max_female as f_max

FROM p_lab_result PLR
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
AND PLR.uid = ? AND PLR.collect_date = ? AND PLR.collect_time = ?
AND PLR.lab_result <> ''
". $query_add.
" ORDER BY PLR.external_lab, PLT.lab_group_id, PLT.lab_seq";





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
          /*
          if($str_specimen != $row["specimen_name"]){
            $arr_obj[$row["specimen_id"]] = $row["specimen_name"];
            $specimen_type .= $row["specimen_name"].", ";
            $str_specimen = $row["specimen_name"];
          }
*/
          $ref_range = ""; $class_lab_result = "";
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

          if($row['ext_lab'] == 0){
            $txt_row_result.="<tr>";
            $txt_row_result.="<td width='25%' class='lab-row'>".$row["lab_name"]."<br><span class='lab-method'>".$row["lab_method_name"]."</span></td>";
            $txt_row_result.="<td width='25%' class='lab-row $class_lab_result'>".$row["lab_result_report"]."</td>";
            $txt_row_result.="<td width='30%' class='lab-row'>$ref_range</td>";
            $txt_row_result.="<td width='20%' class='lab-row lab-note'><span style='color:red;'>".str_replace("\n","<br>",$row["lab_result_note"]);"</span></td>";
        //    $txt_row_result.="<td width='20%' class='lab-row lab-note'><span style='color:red;'>".$row["lab_result_note"]."</span></td>";
            $txt_row_result.="</tr>";
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
<div style='text-align:center;font-size:8px;'>
ผ่านการรับรองระบบคุณภาพห้องปฏิบัติการ (LA) ตามมาตรฐานงานเทคนิคการแพทย์ 2563
</div>

<div style='text-align:center;font-size:16px;font-weight:bold;margin-top:5px;margin-bottom:5px;' >
Laboratory Report
</div>

";

if($arr_data_lab_order["proj_id"] != ""){
  $proj_info ="[Project: ".$arr_data_lab_order["proj_id"]." | PID: ".$arr_data_lab_order["proj_pid"]." | Visit: ".$arr_data_lab_order["proj_visit"]." ]";

  $txt_header .= "<div style='text-align:center;font-size:12px; color:blue;margin-top:5px; margin-bottom:5px;'>$proj_info</div>";
}

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


//<td width='20%'>Sex:</td><td width='30%'>".$arr_gender[$arr_data_lab_order["sex"]]."</td>
$general_info = "
<div class='line-1'></div>
<div style='padding:15px;'>
<table width='100%' class='general-info'>
<tr>
<td width='20%'>Patient Name:</td><td width='30%'>".$arr_data_lab_order["fname"]." ".$arr_data_lab_order["sname"]."</td>
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

</table>
</div>
";

/*
<tr>
<td width='20%'></td>
<td width='20%'>Specimen Type:</td><td width='30%'>$specimen_type</td>
</tr>
*/

$lab_result = "
<div style='text-align:center;font-size:10px;'>
<table width='100%' class='lab-info'>
<thead>
<tr ><td colspan='5' class='line-1'></td></tr>
<tr >
<td class='lab-row-header'><b>Test Name</b><br><span class='lab-method'>Lab Method</span></td>
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

   $mpdf->SetTitle("Lab Report: $lab_order_id");
   $mpdf->SetHTMLHeader($txt_header);
   $mpdf->SetHTMLFooter($txt_footer);

   $mpdf->WriteHTML($html);
   $file_name = $arr_data_lab_order['uid']."_".$arr_data_lab_order['lab_order_id'];
   $mpdf->Output($file_name, 'I');


   // Output a PDF file directly to the browser
   //$mpdf->Output();




?>
