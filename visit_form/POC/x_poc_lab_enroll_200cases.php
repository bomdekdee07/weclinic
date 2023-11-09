<?


/*

lab for Point of care first 200 cases
CT/NG Cepheid NAAT

*/
include_once("inc_param.php");

$form_id = "lab_result_poc_200";
$form_name = "Point of Care Enrollment LAB (first 200 cases)";

$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php

if($open_link != "Y"){
  include_once("../in_auth_db.php");
  if(!isset($auth["lab"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
}


$option_showhide = "
";

include_once("f_form_main.php");

?>


<script>




</script>
