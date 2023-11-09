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



$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$coldate = isset($_GET["coldate"])?urldecode($_GET["coldate"]):"";
$coltime = isset($_GET["coltime"])?urldecode($_GET["coltime"]):"";
//$gender = isset($_GET["gender"])?$_GET["gender"]:"M";
$arr_data_lab_order = array();
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php");

$query = "SELECT o.lab_order_id, o.lab_report_note,
o.uid, o.collect_date, o.collect_time,
s.id as status_id , s.name as status_name,
p.fname, p.sname, p.date_of_birth as dob , p.sex, po.s_name as staff_order
,o.proj_id, o.proj_pid, o.proj_visit,
ps.s_name as staff_lab_save, ps.license_lab as staff_lab_save_license,
pc.s_name as staff_confirm, pc.license_lab as staff_confirm_license, o.time_lab_report_confirm
FROM p_lab_order as o
LEFT JOIN p_lab_status as s ON o.lab_order_status = s.id
LEFT JOIN patient_info as p ON (o.uid=p.uid)
LEFT JOIN p_staff as po ON (o.staff_order=po.s_id)
LEFT JOIN p_staff as ps ON (o.staff_lab_save=ps.s_id)
LEFT JOIN p_staff as pc ON (o.staff_confirm=pc.s_id)
WHERE o.uid=? and o.collect_date=? and o.collect_time=?

";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss",$uid, $coldate, $coltime);

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

if(count($arr_data_lab_order)==0){
  echo "Not found lab order from UID: $uid, Collect Date: $coldate, Collect Time: $coltime";
  return;
}

      $arr_data_list = array();
      $query_add = "";

$query = "
SELECT LT.lab_name, SO.sale_opt_name, LL.laboratory_name, OLT.sale_price, OLT.sale_cost
FROM p_lab_order_lab_test OLT
LEFT JOIN p_lab_test LT ON OLT.lab_id=LT.lab_id
LEFT JOIN p_lab_laboratory LL ON LL.laboratory_id=OLT.laboratory_id
LEFT JOIN sale_option SO ON SO.sale_opt_id = OLT.sale_opt_id

WHERE OLT.uid=? AND OLT.collect_date=? AND OLT.collect_time=?
ORDER BY OLT.laboratory_id, OLT.lab_group_id, LT.lab_id2";

      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("sss",$uid,$coldate,$coltime);
      $arr_obj = array();
      $txt_row_result = "";

      if($stmt->execute()){
        $result = $stmt->get_result();
        $ttl_price=0; $ttl_cost=0;
        while($row = $result->fetch_assoc()) {

            $txt_row_result.="<tr>";
            $txt_row_result.="<td width='35%' class='lab-row'>".$row["lab_name"]."</td>";
            $txt_row_result.="<td width='15%' class='lab-row'>".$row["laboratory_name"]."</td>";
            $txt_row_result.="<td width='30%' class='lab-row'>".$row["sale_opt_name"]."</td>";
            $txt_row_result.="<td width='10%' class='lab-row lab-price' align='right'>".number_format($row["sale_price"], 2)."</td>";
            $txt_row_result.="<td width='10%' class='lab-row lab-price' align='right'>".number_format($row["sale_cost"], 2)."</td>";
            $txt_row_result.="</tr>";

            $ttl_price += $row["sale_price"];
            $ttl_cost += $row["sale_cost"];

        }//while

                    $txt_row_result.="<tr>";
                    $txt_row_result.="<td width='80%' align='right' colspan=3  class='lab-row line-1'>TOTAL: </td>";
                    $txt_row_result.="<td width='10%' class='lab-row line-1 lab-price' align='right'>".number_format($ttl_price, 2) ."</td>";
                    $txt_row_result.="<td width='10%' class='lab-row line-1 lab-price' align='right'>".number_format($ttl_cost, 2)."</td>";
                    $txt_row_result.="</tr>";
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();

      // patient age
      $age = "";

      if(isset($arr_data_lab_order["dob"])){
        $today = date("Y-m-d");
        $diff = date_diff(date_create($arr_data_lab_order["dob"]), date_create($today));
        $age= $diff->format('%y');
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

.lab-price {
  color:#000;
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
. </div>
 <div style='text-align:left;font-size:8px;'>
11th Floor, Chamchuri Square Building, 319 Phayathai Road, Pathumwan, Bangkok, 10330 Tel: 02-160-5373: ใบอนุญาตเลขที่ 10110004863
 </div>
</td>
</tr>

</table>

<div style='text-align:center;font-size:16px;font-weight:bold;margin-top:10px;margin-bottom:5px;' >
Lab Order Sale Report
</div>

";



$lab_report_note = "";

$txt_footer = "

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
<td width='20%'>Lab Order ID:</td><td width='30%'>".$arr_data_lab_order["lab_order_id"]."</td>

</tr>
<tr>
<td width='20%'>Date of birth:</td><td width='30%'>".(new DateTime($arr_data_lab_order["dob"]))->format('d/m/Y')."</td>
<td width='20%'>Visit:</td><td width='30%'>".(new DateTime($arr_data_lab_order["collect_date"]." ".$arr_data_lab_order["collect_time"]))->format('d/m/Y H:i')."</td>

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
<td class='lab-row-header'><b>Test Name</b></td>
<td class='lab-row-header'><b>Laboratory</b></td>
<td class='lab-row-header'><b>Sale Option</b></td>
<td class='lab-row-header' align='right'><b>Sale Price</b></td>
<td class='lab-row-header' align='right'><b>Sale Cost</b></td>
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

   $mpdf->SetTitle("Lab Report: ".$arr_data_lab_order['lab_order_id']);
   $mpdf->SetHTMLHeader($txt_header);
   $mpdf->SetHTMLFooter($txt_footer);

   $mpdf->WriteHTML($html);
   $file_name = "labsale_".$arr_data_lab_order['uid']."_".$arr_data_lab_order['lab_order_id'];
   $mpdf->Output($file_name, 'I');


   // Output a PDF file directly to the browser
   //$mpdf->Output();




?>
