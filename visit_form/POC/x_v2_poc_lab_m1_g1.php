<?

// Point of care Month1 Group1

/*
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
*/
include_once("inc_param.php");


//$link = isset($_GET["link"])?urldecode($_GET["link"]):"";


$form_id = "v2_poc_lab_m1_g1";
$form_name = "Point of care Month1 Group1 LAB";
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


$option_showhide = "";


include_once("f_form_main.php");

?>


<script>

$(document).ready(function(){

  var poc_group = '<? echo $group_id; ?>';

    // referral warning when hiv negative but select below options
    $("#ch_vl_less40-Y").click(function(){ //
      if(poc_group == '001' || poc_group== '002'){
        if($("#hiv_result-NR").prop("checked") == true){
            myModalContent("Information",
            "ผลตรวจ HIV เป็นลบ ควรส่งต่อการรักษาเนื่องจากอาจจะเกิด HIV Accute ",
            "info");
        }
      }
    });
    $("#ch_vl_less40-N").click(function(){ //
      if(poc_group == '001' || poc_group== '002'){
        if($("#hiv_result-NR").prop("checked") == true){
            myModalContent("Information",
            "ผลตรวจ HIV เป็นลบ ควรส่งต่อการรักษาเนื่องจากอาจจะเกิด HIV Accute ",
            "info");
        }
      }
    });

    $("#ch_vl_less40-N2").click(function(){ //
      if(poc_group == '001' || poc_group== '002'){
        if($("#hiv_result-NR").prop("checked") == true){
            myModalContent("Information",
            "ผลตรวจ HIV เป็นลบ ควรส่งต่อการรักษาเนื่องจากอาจจะเกิด HIV Accute ",
            "info");
        }
      }
    });

});


</script>
