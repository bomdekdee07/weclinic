<?




include_once("inc_param.php");

/*
แก้ไขฟอร์ม poc_lab_extra_visit โดยได้รับแจ้งจากคุณอ้อว่าใน lab extra visit น่าจะตรวจได้ทุกอย่าง
จึงนำ poc_lab_m3 มาใส่ให้แทน และปลดล๊อกกลุ่ม 3 และ 4 ให้ใส่ค่า vl ได้ โดยไม่ติด N/A
*/

$form_id = "poc_lab_m3";
$form_name = "Point of Care : Extra Visit LAB";
$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$after_save_function = ""; // trigger after save function
$initJSForm = '
$("#div-specimen_vl_cepheid-NA").hide();
$("#div-ch_vl_less40-NA").hide();
'; // initial js in f_form_main.php

if($open_link != "Y"){
  include_once("../in_auth_db.php");
  if(!isset($auth["lab"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
}



$option_showhide = "

shData['specimen_ctng_cepheid-Y'] = {dtype:'radio',
show_q:'ct_pool_result,ng_pool_result'
};
shData['specimen_ctng_cepheid-N'] = {dtype:'radio',
hide_q:'ct_pool_result,ng_pool_result,ct_pool_invalid,ng_pool_invalid'
};

shData['ct_pool_result-ND'] = {dtype:'radio',
hide_q:'ct_pool_invalid'};
shData['ct_pool_result-D'] = {dtype:'radio',
hide_q:'ct_pool_invalid'};
shData['ct_pool_result-I'] = {dtype:'radio',
show_q:'ct_pool_invalid'};

shData['ng_pool_result-ND'] = {dtype:'radio', hide_q:'ng_pool_invalid'};
shData['ng_pool_result-D'] = {dtype:'radio', hide_q:'ng_pool_invalid'};
shData['ng_pool_result-I'] = {dtype:'radio', show_q:'ng_pool_invalid'};
";
 //$option_showhide = "";

include_once("f_form_main.php");

?>


<script>



</script>
