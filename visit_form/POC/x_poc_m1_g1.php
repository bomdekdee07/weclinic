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


$form_id = "poc_m1_g1";
$form_name = "Point of care Month1 Group1";
$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php


if($open_link != "Y"){
  include_once("../in_auth_db.php");
  if(!isset($auth["data"]) && !isset($auth["log"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
}


$option_showhide = "
/*
// log form ae
shData['prep_ae-Y'] = {dtype:'radio',show:'btnlog_prep_ae-Y'};
shData['prep_ae-N'] = {dtype:'radio',hide:'btnlog_prep_ae-Y'};

// log form con med
shData['conmed-Y'] = {dtype:'radio',show:'btnlog_conmed-Y'};
shData['conmed-N'] = {dtype:'radio',hide:'btnlog_conmed-Y'};
shData['conmed-NA'] = {dtype:'radio',hide:'btnlog_conmed-Y'};
*/
// show/hide component

shData['prep_ae-Y'] = {dtype:'radio',
show_q:'ae_title'};
shData['prep_ae-N'] = {dtype:'radio',
hide_q:'ae_title'};



shData['groupchange-1'] = {dtype:'radio',hide:'groupchange_txt'};
shData['groupchange-2'] = {dtype:'radio',show:'groupchange_txt'};
shData['groupchange-3'] = {dtype:'radio',show:'groupchange_txt'};

shData['referral-N'] = {dtype:'radio',
hide_q:'referral_date_title,referral_place_title',
hide_t:'referral_case'};

shData['referral-W'] = {dtype:'radio',
hide_q:'referral_date_title,referral_place_title',
hide_t:'referral_case'};

shData['referral-Y'] = {dtype:'radio',
show_q:'referral_date_title,referral_place_title',
show_t:'referral_case'};


";


include_once("f_form_main.php");

//include_once("../visit_form_log/z_con_med.php");

?>


<script>
/*
$(document).ready(function(){

  $("#prep_ae-Y").click(function(){ //
    $("#div-btnlog_prep_ae-Y").show();
  });
  $("#prep_ae-N").click(function(){ //
    $("#div-btnlog_prep_ae-Y").hide();
  });

  if ($("#prep_ae-Y").prop("checked")) {
     $("#div-btnlog_prep_ae-Y").show();
  }
  else{
     $("#div-btnlog_prep_ae-Y").hide();
  }

});
*/
</script>
