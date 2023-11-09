<?
// sdhos baseline form

$pid = isset($_GET["pid"])?urldecode($_GET["pid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$seq_no = isset($_GET["seq_no"])?$_GET["seq_no"]:"";
/*
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
$is_backdate = isset($_GET["bd"])?$_GET["bd"]:"N"; // back date filled
*/

$form_id = "sdhos_retention";
$form_name = "Retention";
$u_mode_save = "save_data_sdhos";

$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$before_save_function = ""; // trigger before save function
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php
$option_showhide = "";

//show lab result
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function


$initJSForm .= '$("#CD4pc").mask("99.99",{placeholder:"##.##"});';
$initJSForm .= '$("#btn_save_sdhos_form").hide();';

$q_main = "VL_date,VL_sign,VL,CD4_date,CD4_count,CD4pc,ART_change_date,ART_reason,ART_regimen,note_reten";
$t_main = "VL_title,CD4_title,ART_change_title";

$q_retention = "retention,retention_txt,retention_1";
$t_retention = "retention_title";

$q_vl = "VL_date,VL_sign,VL";
$t_vl = "VL_title";

$q_cd4 = "CD4_date,CD4_count,CD4pc";
$t_cd4 = "CD4_title";

$q_art = "ART_change,ART_change_date,ART_reason,ART_regimen";
$t_art = "ART_change_title";


$option_showhide = "
shData['check_retention'] = {dtype:'check',show_q:'$q_retention',show_t:'$t_retention'};
shData['check_vl'] = {dtype:'check',show_q:'$q_vl',show_t:'$t_vl'};
shData['check_cd4'] = {dtype:'check',show_q:'$q_cd4',show_t:'$t_cd4'};
shData['check_ART_change'] = {dtype:'check',show_q:'$q_art',show_t:'$t_art'};



shData['retention-1'] = {dtype:'radio',show_q:'retention_1'};
shData['retention-2'] = {dtype:'radio',hide_q:'retention_1'};
shData['retention-3'] = {dtype:'radio',hide_q:'retention_1'};
shData['retention-4'] = {dtype:'radio',hide_q:'retention_1'};
shData['retention-5'] = {dtype:'radio',hide_q:'retention_1'};

shData['retention_1-Y'] = {dtype:'radio',show_q:'retention_txt'};
shData['retention_1-N'] = {dtype:'radio',hide_q:'retention_txt'};

";

$form_bottom = "
<div class='my-4'>
<button id='btn_save_sdhos_form_retention' class='btn btn-primary form-control' type='button'> บันทึกข้อมูล</button>
</div>
"; // text display at the bottom of the form


$before_save_function = "
if($('#collect_date').val() == ''){
  $.notify('กรุณาใส่ข้อมูลวันติดตาม', 'error');
  return;
}

$('#collect_date').removeClass('save-data');
if(visitDate == '') visitDate = $('#collect_date').val();

if(u_mode_retention == 'add'){
  visitDate = changeToEnDate($('#collect_date').val());
  //alert('enter here visitdate '+visitDate);
}
"; // trigger before save function

$after_save_function = "
  is_update_form = 1;
  $('#collect_date').prop('disabled', true);
  u_mode_retention = 'update';
"; // trigger after save function

include_once("pid_hos_form_main.php");

?>


<script>



$(document).ready(function(){
  if(u_mode_retention != 'add'){
    $('#collect_date').prop('disabled', true);
    //alert('enter here visitdate '+visitDate);
  }
  else{
    $('#collect_date').prop('disabled', false);
  }

  if(typeof hos_sc_id  !== 'undefined' ){
    if(hos_sc_id.substring(0, 1) != "H" ){
      $("#btn_save_sdhos_form").hide();

    }
  }

    $("#btn_save_sdhos_form_retention").click(function(){
      saveSDHosRetention();
    }); // btn_save_sdhos_form



});

function saveSDHosRetention(){
  if(u_mode_retention == 'add'){
    checkExistLogData('retention', changeToEnDate($('#collect_date').val()));
  }
  else{
    saveDataSDHosLog();
  }
}



</script>
