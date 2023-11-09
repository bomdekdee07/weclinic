<?

/*
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
*/

$form_id = "q_demo";
$form_name = "Demographic";
include_once("inc_param.php");
include_once("inc_check_form_done.php");




$form_top = "
<div class='mb-4 px-2 py-4' style='background-color:#eee;'>
  <div><h4>แบบสอบถามข้อมูลส่วนตัว (ใช้เวลาในการตอบแบบสอบถามประมาณ 5 นาที)</h4></div>
  โครงการประเมินความเป็นไปได้ของการใช้ชุดตรวจ ณ จุดดูแลผู้ป่วย สำหรับโรคติดต่อทางเพศสัมพันธ์และปริมาณเชื้อเอชไอวีในเลือด
  ในศูนย์สุขภาพชุมชนสำหรับชายมีเพศสัมพันธ์กับชายและสาวประเภทสองในประเทศไทย <br>
  <ul>
  <li>คุณมีสิทธิที่จะตอบหรือไม่ตอบคำถามใดก็ได้ในแบบสอบถามชุดนี้ โดยจะไม่เกิดผลเสียใดๆ ต่อตัวคุณ อย่างไรก็ตาม ข้อมูลที่คุณตอบจะช่วยให้เราเข้าใจลักษณะทั่วไปของคุณได้ดีขึ้น</li>
  <li>ข้อมูลทั้งหมดจะถูกเก็บไว้เป็นความลับ และจะนำมาใช้ในงานวิจัยเท่านั้น ข้อมูลเหล่านี้จะไม่มีผลใดๆ ทั้งสิ้นต่อตัวคุณทั้งในทางส่วนตัวและทางกฎหมาย</li>
  <li>คำถามบางข้ออาจจะทำให้คุณรู้สึกไม่สบายใจ หรืออึดอัดใจ ซึ่งเราต้องขออภัยไว้ล่วงหน้า และต้องขอขอบพระคุณอย่างยิ่งที่คุณกรุณาสละเวลาตอบแบบสอบถามชุดนี้</li>
  </ul>
</div>
"; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$after_save_function = ""; // trigger after save function
$initJSForm = '

'; // initial js in f_form_main.php

if($open_link != "Y"){
  include_once("../in_auth_db.php");
  if(!isset($auth["data"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
}

$option_showhide = "
shData['hivtestprev-Y'] = {dtype:'radio',
show_q:'hivtestlast'};
shData['hivtestprev-N'] = {dtype:'radio',
hide_q:'hivtestlast'};

shData['sti_diag_prev-Y'] = {dtype:'radio',
show_q:'sti_diag_last,sti_diag_place'};
shData['sti_diag_prev-N'] = {dtype:'radio',
hide_q:'sti_diag_last,sti_diag_place'};

";


include_once("f_form_main.php");

?>


<script>



</script>
