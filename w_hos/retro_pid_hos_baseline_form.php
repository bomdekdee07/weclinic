<?
// sdhos retro form

$pid = isset($_GET["pid"])?urldecode($_GET["pid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";

$seq_no = isset($_GET["seq_no"])?$_GET["seq_no"]:"";
/*
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
$is_backdate = isset($_GET["bd"])?$_GET["bd"]:"N"; // back date filled
*/

$form_id = "sdhos_retro";
$form_name = "Retrospective Case Report Form";
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
$initJSForm .= '$("#birth_yr").mask("9999",{placeholder:"####"});';
//$initJSForm .= 'alert("enterhere");';
//$initJSForm .= 'initData_retro();';



$q_lab = "cd4_count_ND,cd4_date,cd4_count,cd4pc,creatinine_ND,creatinine_date,CR_ND,CR,CR_unit,Clearance_ND,clearance,clearance_unit,eGFR_ND,eGFR,eGFR_unit,ALT_ND,ALT_date,ALT,ALT_unit,syphilis_ND,syphilis_date,treponemal,VDRL1,Titer,HBsAg_ND,HBsAg_date,HBsAg,HCV_ND,HCV_date,HCV,UA_ND,UA_date,UA_wbc,UA_rbc,UA_protein,UA_sugar,CXR_ND,CXR_date,CXR,cryptococcal_ND,cryptococcal_date,cryptococcal,note_lab";
$t_lab = "lab_title";

$q_art = "diag_date,start_ART,startART_date,ART_regimen,Not_ART_title,ART_not_appli";
$t_art = "artinitial_title";



$option_showhide = "


shData['arv_previous-Y'] = {dtype:'radio',
hide_q:'$q_lab,$q_art',
hide_t:'$t_lab,$t_art'
};
shData['arv_previous-N'] = {dtype:'radio',
show_q:'$q_lab,$q_art',
show_t:'$t_lab,$t_art'
};

shData['income_y-Y'] = {dtype:'radio',show_q:'income'};
shData['income_y-N'] = {dtype:'radio',hide_q:'income'};
shData['income_y-NA'] = {dtype:'radio',hide_q:'income'};


shData['knownpositive-Y'] = {dtype:'radio',show_q:'hivpos_repeat,hiv_inform_date,hiv_test_date'};
shData['knownpositive-N'] = {dtype:'radio',hide_q:'hivpos_repeat,hiv_inform_date,hiv_test_date'};

shData['hivpos_repeat-Y'] = {dtype:'radio',show_q:'hiv_inform_date,hiv_test_date'};
shData['hivpos_repeat-N'] = {dtype:'radio',hide_q:'hiv_inform_date,hiv_test_date'};

shData['risk10'] = {dtype:'check',show_q:'risk10txt'};
shData['risk19'] = {dtype:'check',show_q:'risk19txt'};

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
hide_q:'syphilis_date,treponemal,VDRL1,Titer'};
shData['syphilis_ND-D'] = {dtype:'radio',
show_q:'syphilis_date,treponemal,VDRL1'};

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

if($('#birth_yr').val() != ''){
  var birth_year = parseInt($('#birth_yr').val());
  if(birth_year > 2580 || birth_year < 2480){
    $.notify('กรุณากรอก ข้อ 5 ปีเกิด ให้ถูกต้อง', 'error');
    $('#birth_yr').notify('กรุณากรอก ปีเกิด ให้ถูกต้อง', 'error');
    return;
  }
}


"; // trigger before save function

$after_save_function = "
  aftersaveRetro();
";
//$initJSForm .= ' initData_retro();';

include_once("pid_hos_form_main.php");

?>


<script>
$(document).ready(function(){
//initData_retro();
//alert("substring "+hos_sc_id.substring(0, 1));


if($("#birth_yr").val() == ""){ // auto calculate pid age
  getPID_BirthYear();
}

if(typeof hos_sc_id  !== 'undefined' ){
  if(hos_sc_id.substring(0, 1) != "H" ){
    $("#btn_save_sdhos_form").hide();
    $("#btn_retro_save").hide();
  }
}


$("#VDRL1-R").click(function(){ // VDRL1-Reactive
   $("#Titer").val('1:');
   $("#Titer").focus();
});





    $(".q_sexualorientation").click(function(){ // sexualorientation
      //alert("q_comorbidity "+$(this).attr('id'));
      if($(this).attr('id')=="sexualorientation0"){
        $(".q_sexualorientation:not(#sexualorientation0)").prop("checked", false);

        $("#q_sexualorientation").find('input[type=text]').val('');
        $("#q_sexualorientation").find('input[type=text]').prop( "disabled", true );
        $("#q_sexualorientation").find('input[type=text]').removeClass('input_invalid');
      }
      else{
        $("#sexualorientation0").prop("checked", false);
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


function aftersaveRetro(){ // check inclusion criteria retrospective
  var flag = 0;
  if($("#arv_previous-N").prop("checked") == true){ //enable log menu
    if(validateDate($("#startART_date").val())){ // fill valid startAE_date ข้อ27
      var hiv_art_date = changeToEnDate($("#startART_date").val());

      if((hiv_art_date > "2017-07-31") && (hiv_art_date < "2018-08-01")){ // ส.ค.60-ก.ค.61
          flag = 1;
      }

    }
    else{
      $.notify("ยังกรอกฟอร์ม Retention และ AE ไม่ได้ เนื่องจากยังไม่ระบุวันเริ่มยาต้านฯ ในข้อ 27","warning");
    }
  }


  if(flag == 1){ //enable log menu
     $(".log-"+cur_hos_pid).show();
     showhideMenuHosLog("1");
  }
  else{ // disable log menu
    $(".log-"+cur_hos_pid).hide();
     showhideMenuHosLog("0");
  }
//alert(hiv_test_date+"/aftersaveretro 1 /"+flag);

}


/*
function aftersaveRetro(){ // check inclusion criteria retrospective
  var flag = 0;
  var hiv_test_date = changeToEnDate($("#hiv_test_date").val());

  if((hiv_test_date > "2017-07-31") && (hiv_test_date < "2018-08-01")){ // ส.ค.60-ก.ค.61
    if($("#arv_previous-N").prop("checked") == true){ //enable log menu
      flag = 1;
    }
  }

  if(flag == 1){ //enable log menu
     $(".log-"+cur_hos_pid).show();
     showhideMenuHosLog("1");
  }
  else{ // disable log menu
    $(".log-"+cur_hos_pid).hide();
     showhideMenuHosLog("0");
  }
//alert(hiv_test_date+"/aftersaveretro 1 /"+flag);

}
*/

</script>
