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

$form_id = "sdhos_baseline";
$form_name = "Prospective Case Report Form";
$u_mode_save = "save_data_sdhos";

$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$before_save_function = ""; // trigger before save function
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php




//show lab result
include_once("../in_auth.php");
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function


$initJSForm .= '$("#cd4pc").mask("99.99",{placeholder:"##.##"});';
//$initJSForm .= 'alert("enterhere");';
//$initJSForm .= 'initData_Baseline();';

$q_physical_exam = "pe_date,CDCstage,CDCstage1,comorbidity,hivrelated,scrTB,scrTB_specify,scrCrypto,crypto_symptom,serious_oi,serious_oi_type,note_pe";
$t_physical_exam = "pe_title,CDCstage_title";

$q_lab = "cd4_count_ND,cd4_date,cd4_count,cd4pc,creatinine_ND,creatinine_date,CR_ND,CR,CR_unit,Clearance_ND,clearance,clearance_unit,eGFR_ND,eGFR,eGFR_unit,ALT_ND,ALT_date,ALT,ALT_unit,syphilis_ND,syphilis_date,Treponemal,VDRL1,Titer,HBsAg_ND,HBsAg_date,HBsAg,HCV_ND,HCV_date,HCV,UA_ND,UA_date,UA_wbc,UA_rbc,UA_protein,UA_sugar,CXR_ND,CXR_date,CXR,cryptococcal_ND,cryptococcal_date,cryptococcal,note_lab";
$t_lab = "lab_title";

$q_art = "diag_date,start_ART,startART_date,ART_regimen";
$t_art = "artinitial_title";

$q_referral = "refer_out,refer_out_date";
$t_referral = "referral_title";

$option_showhide = "

shData['arv_previous-Y'] = {dtype:'radio',
show_q:'arv_previous_place',
hide_q:'$q_physical_exam,$q_lab,$q_art,$q_referral',
hide_t:'$t_physical_exam,$t_lab,$t_art,$t_referral'
};
shData['arv_previous-N'] = {dtype:'radio',
hide_q:'arv_previous_place',
show_q:'$q_physical_exam,$q_lab,$q_art,$q_referral',
show_t:'$t_physical_exam,$t_lab,$t_art,$t_referral'
};

shData['arv_previous_place-1'] = {dtype:'radio',
hide_q:'$q_physical_exam,$q_lab,$q_art,$q_referral',hide_t:'$t_physical_exam,$t_lab,$t_art,$t_referral'};
shData['arv_previous_place-2'] = {dtype:'radio',
show_q:'$q_physical_exam,$q_lab,$q_art,$q_referral',show_t:'$t_physical_exam,$t_lab,$t_art,$t_referral'};
shData['arv_previous_place-3'] = {dtype:'radio',
hide_q:'$q_physical_exam,$q_lab,$q_art,$q_referral',hide_t:'$t_physical_exam,$t_lab,$t_art,$t_referral'};



shData['income_y-Y'] = {dtype:'radio',show_q:'income'};
shData['income_y-N'] = {dtype:'radio',hide_q:'income'};
shData['income_y-NA'] = {dtype:'radio',hide_q:'income'};



shData['knownpositive-Y'] = {dtype:'radio',show_q:'hivpos_repeat,hiv_inform_date'};
shData['knownpositive-N'] = {dtype:'radio',hide_q:'hivpos_repeat,hiv_inform_date'};

shData['hivpos_repeat-Y'] = {dtype:'radio',show_q:'hiv_inform_date'};
shData['hivpos_repeat-N'] = {dtype:'radio',hide_q:'hiv_inform_date'};

shData['refer-Y'] = {dtype:'radio',
show_q:'referdate,referbyCBOs'};
shData['refer-N'] = {dtype:'radio',
hide_q:'referdate,referbyCBOs'};


shData['hivpos_repeat-Y'] = {dtype:'radio',
show_q:'hiv_inform_date'};
shData['hivpos_repeat-N'] = {dtype:'radio',
hide_q:'hiv_inform_date'};

shData['acceptSDART-Y'] = {dtype:'radio',
hide_q:'reasonnotaccept,arv_notapplicable'};
shData['acceptSDART-N'] = {dtype:'radio',
show_q:'reasonnotaccept',hide_q:'arv_notapplicable'};
shData['acceptSDART-NA'] = {dtype:'radio',
show_q:'arv_notapplicable',hide_q:'reasonnotaccept'};

shData['righttotreat-Y'] = {dtype:'radio',
show_q:'healthcare_type',
hide_q:'paycash,nhso_start'};

shData['righttotreat-N'] = {dtype:'radio',
hide_q:'healthcare_type',
show_q:'paycash,nhso_start'};


shData['risk10'] = {dtype:'check',show_q:'risk10txt'};
shData['risk19'] = {dtype:'check',show_q:'risk19txt'};

shData['stageC'] = {dtype:'check',show_q:'stageC_txt'};
shData['hivrelate_oth'] = {dtype:'check',show_q:'hivrelate_oth_txt'};

shData['scrTB-Y'] = {dtype:'radio',show_q:'scrTB_specify'};
shData['scrTB-N'] = {dtype:'radio',hide_q:'scrTB_specify'};

shData['scrCrypto-Y'] = {dtype:'radio',show_q:'crypto_symptom'};
shData['scrCrypto-N'] = {dtype:'radio',hide_q:'crypto_symptom'};

shData['serious_oi-Y'] = {dtype:'radio',show_q:'serious_oi_type'};
shData['serious_oi-N'] = {dtype:'radio',hide_q:'serious_oi_type'};



shData['cd4_count_ND-ND'] = {dtype:'radio',
hide_q:'cd4_date,cd4_count,cd4pc'};
shData['cd4_count_ND-D'] = {dtype:'radio',
show_q:'cd4_date,cd4_count,cd4pc'};

shData['creatinine_ND-ND'] = {dtype:'radio',
hide_q:'creatinine_date,CR_ND,CR,CR_unit,Clearance_ND,clearance,clearance_unit,eGFR_ND,eGFR,eGFR_unit'};
shData['creatinine_ND-D'] = {dtype:'radio',
show_q:'creatinine_date,CR_ND,Clearance_ND,eGFR_ND'};

shData['CR_ND-ND'] = {dtype:'radio', hide_q:'CR,CR_unit'};
shData['CR_ND-D'] = {dtype:'radio',show_q:'CR,CR_unit'};

shData['Clearance_ND-ND'] = {dtype:'radio', hide_q:'clearance,clearance_unit'};
shData['Clearance_ND-D'] = {dtype:'radio',show_q:'clearance,clearance_unit'};

shData['eGFR_ND-ND'] = {dtype:'radio', hide_q:'eGFR,eGFR_unit'};
shData['eGFR_ND-D'] = {dtype:'radio',show_q:'eGFR,eGFR_unit'};


shData['ALT_ND-ND'] = {dtype:'radio',hide_q:'ALT_date,ALT,ALT_unit'};
shData['ALT_ND-D'] = {dtype:'radio',show_q:'ALT_date,ALT,ALT_unit'};


shData['syphilis_ND-ND'] = {dtype:'radio',
hide_q:'syphilis_date,Treponemal,VDRL1,Titer'};
shData['syphilis_ND-D'] = {dtype:'radio',
show_q:'syphilis_date,Treponemal,VDRL1'};

shData['VDRL1-R'] = {dtype:'radio',show_q:'Titer'};
shData['VDRL1-NR'] = {dtype:'radio',hide_q:'Titer'};
shData['VDRL1-ND'] = {dtype:'radio',hide_q:'Titer'};


shData['HBsAg_ND-ND'] = {dtype:'radio',hide_q:'HBsAg_date,HBsAg'};
shData['HBsAg_ND-D'] = {dtype:'radio',show_q:'HBsAg_date,HBsAg'};

shData['HCV_ND-ND'] = {dtype:'radio',hide_q:'HCV_date,HCV'};
shData['HCV_ND-D'] = {dtype:'radio',show_q:'HCV_date,HCV'};

shData['UA_ND-ND'] = {dtype:'radio',hide_q:'UA_date,UA_wbc,UA_rbc,UA_protein,UA_sugar'};
shData['UA_ND-D'] = {dtype:'radio',show_q:'UA_date,UA_wbc,UA_rbc,UA_protein,UA_sugar'};

shData['CXR_ND-ND'] = {dtype:'radio',hide_q:'CXR_date,CXR'};
shData['CXR_ND-D'] = {dtype:'radio',show_q:'CXR_date,CXR'};

shData['cryptococcal_ND-ND'] = {dtype:'radio',hide_q:'cryptococcal_date,cryptococcal'};
shData['cryptococcal_ND-D'] = {dtype:'radio',show_q:'cryptococcal_date,cryptococcal'};

shData['refer_out-N'] = {dtype:'radio',hide_q:'refer_out_date'};
shData['refer_out-Y'] = {dtype:'radio',show_q:'refer_out_date'};


shData['start_ART-N'] = {dtype:'radio',
show_q:'Not_ART_title',
hide_q:'Not_ART_oi_txt,Not_ART_lab_txt,Not_ART_wait_txt,ART_not_appli'};
shData['start_ART-Y'] = {dtype:'radio',
hide_q:'Not_ART_title,Not_ART_oi_txt,Not_ART_lab_txt,Not_ART_oth_txt,ART_not_appli'};

shData['start_ART-NA'] = {dtype:'radio',
show_q:'ART_not_appli',
hide_q:'Not_ART_title,Not_ART_oi_txt,Not_ART_lab_txt,Not_ART_oth_txt'};

shData['Not_ART_oth'] = {dtype:'check',show_q:'Not_ART_oth_txt'};
shData['Not_ART_lab'] = {dtype:'check',show_q:'Not_ART_lab_txt'};
shData['Not_ART_oi'] = {dtype:'check',show_q:'Not_ART_oi_txt'};



";


$before_save_function = "
if($('#visit_date').val() == ''){
  $.notify('กรุณาใส่ข้อมูลวันที่รับบริการ (Visit Date)', 'error');
  return;
}
else{
  if(!validateDate($('#visit_date').val())){
    $.notify('ข้อมูลวันที่รับบริการ (Visit Date) ไม่ถูกต้อง ', 'error');
    $('#visit_date').focus();
    return;
  }
}

"; // trigger before save function

//$initJSForm .= ' initData_Baseline();';

include_once("pid_hos_form_main.php");

?>


<script>
$(document).ready(function(){
initData_Baseline();
//alert("substring "+hos_sc_id.substring(0, 1));

if(typeof hos_sc_id  !== 'undefined' ){
  if(hos_sc_id.substring(0, 1) != "H" ){
    $("#btn_save_sdhos_form").hide();
    $("#btn_baseline_save").hide();
  }
}



if($("#age").val() == ""){ // auto calculate pid age
  getPIDAge();
}

$("#VDRL1-R").click(function(){ // VDRL1-Reactive
   $("#Titer").val('1:');
   $("#Titer").focus();
});


$("#acceptSDART-N").click(function(){ // acceptSDART-N
  //$q_art = "diag_date,start_ART,startART_date,ART_regimen";
  if(is_pass_baseline()){ //pass baseline criteria
    var hide_q = '<? echo "$q_physical_exam,$q_lab,$q_referral,diag_date,start_ART,ART_regimen,Not_ART_title,Not_ART_oi_txt,Not_ART_lab_txt"; ?>';
    var arr_hide_q = hide_q.split(",");
    var i;
     for (i = 0; i < arr_hide_q.length; i++) {
       $("#q_"+arr_hide_q[i]).hide();
       $('#q_'+arr_hide_q[i]).data("is_show",'0');
     } // for

     var hide_t = '<? echo "$t_physical_exam,$t_lab,$t_referral"; ?>';
     var arr_hide_t = hide_t.split(",");
      for (i = 0; i < arr_hide_t.length; i++) {
        $("#t_"+arr_hide_t[i]).hide();
      } // for


 }
});


$("#acceptSDART-Y").click(function(){ // acceptSDART-Y

   if(is_pass_baseline()){ //pass baseline criteria
     var show_q = '<? echo "$q_physical_exam,$q_lab,$q_referral,$q_art"; ?>';
     var arr_show_q = show_q.split(",");
     var i;
      for (i = 0; i < arr_show_q.length; i++) {
        $("#q_"+arr_show_q[i]).show();
        $('#q_'+arr_show_q[i]).data("is_show",'1');
      }

      var show_t = '<? echo "$t_physical_exam,$t_lab,$t_referral,$t_art"; ?>';
      var arr_show_t = show_t.split(",");
       for (i = 0; i < arr_show_t.length; i++) {
         $("#t_"+arr_show_t[i]).show();
       } // for
  }

});
$("#acceptSDART-NA").click(function(){ // acceptSDART-NA

   if(is_pass_baseline()){ //pass baseline criteria
     var show_q = '<? echo "$q_physical_exam,$q_lab,$q_referral,$q_art"; ?>';
     var arr_show_q = show_q.split(",");
     var i;
      for (i = 0; i < arr_show_q.length; i++) {
        $("#q_"+arr_show_q[i]).show();
        $('#q_'+arr_show_q[i]).data("is_show",'1');
      }
      var show_t = '<? echo "$t_physical_exam,$t_lab,$t_referral,$t_art"; ?>';
      var arr_show_t = show_t.split(",");
       for (i = 0; i < arr_show_t.length; i++) {
         $("#t_"+arr_show_t[i]).show();
       } // for
  }

});



    $(".q_hiv_risk").click(function(){ // hiv_risk
      //alert("q_comorbidity "+$(this).attr('id'));
      if($(this).attr('id')=="risk0"){
        $(".q_hiv_risk:not(#risk0)").prop("checked", false);

        $("#q_hiv_risk").find('input[type=text]').val('');
        $("#q_hiv_risk").find('input[type=text]').prop( "disabled", true );
        $("#q_hiv_risk").find('input[type=text]').removeClass('input_invalid');
      }
      else{
        $("#risk0").prop("checked", false);
      }
    });

  $(".q_comorbidity").click(function(){ // comorbid_none
    if($(this).attr('id')=="comorbid_none"){
      $(".q_comorbidity:not(#comorbid_none)").prop("checked", false);

      $("#q_comorbidity").find('input[type=text]').val('');
      $("#q_comorbidity").find('input[type=text]').prop( "disabled", true );
      $("#q_comorbidity").find('input[type=text]').removeClass('input_invalid');

    }
    else{
      $("#comorbid_none").prop("checked", false);
    }
  });

  $(".q_hivrelated").click(function(){ // hivrelated
    if($(this).attr('id')=="hivrelated_none"){
      $(".q_hivrelated:not(#hivrelated_none)").prop("checked", false);

      $("#q_hivrelated").find('input[type=text]').val('');
      $("#q_hivrelated").find('input[type=text]').prop( "disabled", true );
      $("#q_hivrelated").find('input[type=text]').removeClass('input_invalid');
    }
    else{
      $("#hivrelated_none").prop("checked", false);
    }
  });



});


function aftersaveBaseLine(){
//alert("aftersaveBaseLine 1");
  var flag_show_log = 0;
  if($("#arv_previous-Y").prop("checked") == true){ // เคยกินยาต้าน
    if($("#arv_previous_place-2").prop("checked") == true){ //enable log menu

      if(validateDate($("#startART_date").val())){ // fill valid startAE_date ข้อ39
         flag_show_log = 1;
      }
    }

  }
  else{
    if($("#arv_previous-N").prop("checked") == true){ // ไม่เคยกินยาต้าน
      if(validateDate($("#startART_date").val())){ // fill valid startAE_date ข้อ39
         flag_show_log = 1;
      }
    }
  }


      if(flag_show_log == 1){
        $(".log-"+cur_hos_pid).show();
        showhideMenuHosLog("1");

      }
      else{
        $(".log-"+cur_hos_pid).hide();
        showhideMenuHosLog("0");
        $.notify("ยังกรอกฟอร์ม Retention และ AE ไม่ได้ เนื่องจากยังไม่ระบุวันเริ่มยาต้านฯ ในข้อ 39","warning");
      //  alert("hide log-"+cur_hos_pid);
      }


}
function is_pass_baseline(){
  var flag = false;
  if($("#arv_previous-N").prop("checked") == true) flag = true; //ไม่เคยกินยาต้านไวรัสมาก่อน
  else if($("#arv_previous-Y").prop("checked") == true){//เคยกินยาต้านไวรัสมาก่อน
    if($("#arv_previous_place-2").prop("checked") == true) flag = true;//ที่ รพ.นี้ ตั้งแต่ เดือนสิงหาคม พ.ศ.2561
  }
  return flag;
}

function initData_Baseline(){
  //alert('initbase '+$("#arv_previous_place-2").prop("checked"));
  if(is_pass_baseline()){
     if($("#acceptSDART-N").prop("checked") == true){ // ไม่พร้อม / ไม่ต้องการเริ่มยาต้านไวรัสเอชไอวี

    var hide_q = '<? echo "$q_physical_exam,$q_lab,$q_referral,diag_date,start_ART,ART_regimen,Not_ART_title,Not_ART_oi_txt,Not_ART_lab_txt"; ?>';
    var arr_hide_q = hide_q.split(",");
    var i;
     for (i = 0; i < arr_hide_q.length; i++) {
       $("#q_"+arr_hide_q[i]).hide();
       $('#q_'+arr_hide_q[i]).data("is_show",'0');
     } // for

     var hide_t = '<? echo "$t_physical_exam,$t_lab,$t_referral"; ?>';
     var arr_hide_t = hide_t.split(",");
      for (i = 0; i < arr_hide_t.length; i++) {
        $("#t_"+arr_hide_t[i]).hide();
      } // for

    }
 }

}

</script>
