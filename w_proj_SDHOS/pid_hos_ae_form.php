<?
// sdhos baseline form

$pid = isset($_GET["pid"])?urldecode($_GET["pid"]):"";
$seq_no = isset($_GET["seq_no"])?$_GET["seq_no"]:"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";



$form_id = "sdhos_ae";
$form_name = "Adverse Event (AE)";
$u_mode_save = "save_data_log_sdhos";

$sel_sql_add = " AND seq_no='$seq_no' "; //log form

$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$before_save_function = ""; // trigger before save function
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php
$option_showhide = "";

//show lab result
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function

$initJSForm .= '$("#btn_save_sdhos_form").hide();';
$option_showhide = "

";

$form_bottom = "
<div class='my-4'>
<button id='btn_save_sdhos_form_ae' class='btn btn-primary form-control' type='button'> บันทึกข้อมูล</button>
<input type='hidden' id='seq_no' value='$seq_no'>
</div>
"; // text display at the bottom of the form



$after_save_function = "
if($('#seq_no').val() == ''){
  $('#seq_no').val(rtnDataAjax.seq_no);
}
is_update_form = 1;

"; // trigger after save function

$before_save_function = "
/*
if($('#ae_collect_date').val() == ''){
  $.notify('กรุณาใส่ข้อมูลวันที่ติดตามอาการ', 'error');
  $('#ae_collect_date').notify('กรุณาใส่ข้อมูลวันที่ติดตามอาการ', 'error');
  $('#ae_collect_date').focus();
  return;
}
*/

if($('#ae_symptom').val() == ''){
  $.notify('กรุณาใส่ข้อมูลอาการที่เกิด', 'error');
  $('#ae_symptom').focus();
  return;
}

if(!checkStopDate()){
  $.notify('กรุณาเช็ควันทีเริ่ม และวันที่หยุดอาการ AE');
  return;
}


if($('#seq_no').val() != ''){
  seqNo = $('#seq_no').val();
}


"; // trigger before save function


/*
$form_bottom = "
<div class='my-4'>
<button id='btn_save_sdhos_form_ae' class='btn btn-primary form-control' type='button'> บันทึกข้อมูล</button>
</div>
"; // text display at the bottom of the form
*/

include_once("pid_hos_form_main.php");

?>


<script>
$(document).ready(function(){
  $("#ae_collect_date").prop("disabled", true);
  if(u_mode_ae == "add"){
    var visit_date="<? echo $visit_date; ?>";
    if(visit_date != ""){
      $("#ae_collect_date").val(changeToThaiDate(visit_date));
    }
  }

  if(typeof hos_sc_id  !== 'undefined' ){
    if(hos_sc_id.substring(0, 1) != "H" ){
      $("#btn_save_sdhos_form").hide();
    }
  }

  $("#btn_save_sdhos_form_ae").click(function(){
      saveDataSDHosLog();
      is_update_form = 1;
  }); // btn_save_sdhos_form



  $(".q_ae_treatment").click(function(){ // hivrelated
    if($(this).attr('id')=="ae_treatment0"){ // none
      $(".q_ae_treatment:not(#ae_treatment0)").prop("checked", false);

      $("#q_ae_treatment").find('input[type=text]').val('');
      $("#q_ae_treatment").find('input[type=text]').prop( "disabled", true );
      $("#q_ae_treatment").find('input[type=text]').removeClass('input_invalid');
    }
    else{
      $("#ae_treatment0").prop("checked", false);
    }
  });


});

function checkStopDate(){ // check whether stop date is not before start date
  if($("#ae_start_date").val() != '' && $("#ae_stop_date").val() != ''){
    var date_start = new Date(changeToEnDate($("#ae_start_date").val())) ;
    var date_stop = new Date(changeToEnDate($("#ae_stop_date").val())) ;

    if(date_start > date_stop){
      return false;
    }
    else return true;
  }
  else{
    return true;
  }
}


</script>
