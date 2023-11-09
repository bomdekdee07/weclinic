<?

/*
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
*/
$form_id = "poc_satisfaction";
$form_name = "Point of Care : Satisfaction";
include_once("inc_param.php");
include_once("inc_check_form_done.php");

$form_top = "
<div class='mb-4 px-2 py-4' style='background-color:#eee;'>
  <div><b>แบบสอบถามประเมินความพึงพอใจต่อการตรวจด้วยชุดตรวจ ณ จุดดูแลผู้ป่วย สำหรับโรคติดต่อทางเพศสัมพันธ์และปริมาณเชื้อเอชไอวีในเลือด สำหรับอาสาสมัครในโครงการ (ใช้เวลาในการตอบแบบสอบถามประมาณ 1 นาที)</b></div>
  โครงการประเมินความเป็นไปได้ของการใช้
ชุดตรวจ ณ จุดดูแลผู้ป่วย สำหรับโรคติดต่อทางเพศสัมพันธ์และปริมาณเชื้อเอชไอวีในเลือด ในศูนย์สุขภาพชุมชนสำหรับชาย
มีเพศสัมพันธ์กับชายและสาวประเภทสองในประเทศไทย
 <br>
  <ul>
  <li>คุณมีสิทธิที่จะตอบหรือไม่ตอบคำถามใดก็ได้ในแบบสอบถามชุดนี้ โดยจะไม่เกิดผลเสียใดๆ ต่อตัวคุณ อย่างไรก็ตาม ข้อมูลที่คุณตอบจะช่วยให้เราเข้าใจลักษณะทั่วไปของคุณได้ดีขึ้น</li>
  <li>ข้อมูลทั้งหมดจะถูกเก็บไว้เป็นความลับ และจะนำมาใช้ในงานวิจัยเท่านั้น ข้อมูลเหล่านี้จะไม่มีผลใดๆ ทั้งสิ้นต่อตัวคุณทั้งในทางส่วนตัวและทางกฎหมาย</li>
  <li>คำถามบางข้ออาจจะทำให้คุณรู้สึกไม่สบายใจ หรืออึดอัดใจ ซึ่งเราต้องขออภัยไว้ล่วงหน้า และต้องขอขอบพระคุณอย่างยิ่งที่คุณกรุณาสละเวลาตอบแบบสอบถามชุดนี้</li>
  </ul>
</div>
"; // text display at the top of the form


$form_bottom = ""; // text display at the bottom of the form
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php
$option_showhide = "";

if($open_link != "Y"){
  include_once("../in_auth_db.php");
  if(!isset($auth["data"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
}

/*
if($visit_id == "M0") $initJSForm .= '$("#visit-m0").prop("checked",true);';
if($visit_id == "M1") $initJSForm .= '$("#visit-m1").prop("checked",true);';
if($visit_id == "M3") $initJSForm .= '$("#visit-m3").prop("checked",true);';
if($visit_id == "M6") $initJSForm .= '$("#visit-m6").prop("checked",true);';
if($visit_id == "M9") $initJSForm .= '$("#visit-m9").prop("checked",true);';
if($visit_id == "M12") $initJSForm .= '$("#visit-m12").prop("checked",true);';
*/

include_once("f_form_main.php");

?>


<script>



</script>
