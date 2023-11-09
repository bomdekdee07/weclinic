<?

include_once("inc_param.php");

// i=intake, r=restart, pr=
$start_type = isset($_GET["start_type"])?$_GET["start_type"]:"";


$form_id = "prep_m0";
$form_name = "PrEP Intake/Restart";
$form_top = ""; // text display at the top of the form
$form_bottom = '<div class="my-4">
<button id="btn_poc_screening_pass" class="form-control btn btn-success btn-screen" type="button">ผ่านการคัดกรอง ลงทะเบียนเข้าโครงการ</button>
<button id="btn_poc_screening_fail" class="form-control btn btn-danger btn-screen" type="button">ไม่ผ่านการคัดกรอง</button>
</div>'; // text display at the bottom of the form
$after_save_function = "enrollProject();"; // trigger after save function
$initJSForm = '

'; // initial js in f_form_main.php


  include_once("../in_auth_db.php");
  if(!isset($auth["data"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
/*

  include_once("../in_db_conn.php");
  $is_agree="";
  $is_consent="";
  $query = "SELECT sc.is_agree, sc.is_consent , p.uid_status
FROM x_poc_screen as sc, p_project_uid_list as p
WHERE p.uid =? AND sc.collect_date=?
AND p.uid=sc.uid
  ";
//echo "$uid, $visit_date/$query";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ss", $uid, $visit_date);
  if($stmt->execute()){
    $stmt->bind_result($is_agree, $is_consent, $uid_status);
    if ($stmt->fetch()) {

    }// if
  }
  else{
    $msg_error .= $stmt->error;
  }
  $stmt->close();


*/


//$initJSForm .= '$("#weight").mask("99.99",{placeholder:"##.##"});';


$option_showhide = "
shData['sti_hist-Y'] = {dtype:'radio',
show_q:'sti_symptom,sti_diagnosis,sti_treatment'};
shData['sti_hist-N'] = {dtype:'radio',
hide_q:'sti_symptom,sti_diagnosis,sti_treatment'};

shData['hiv_hist-Y'] = {dtype:'radio',
show_q:'hiv_test_freq,hiv_lastdate,hiv_lastresult'};
shData['hiv_hist-N'] = {dtype:'radio',
hide_q:'hiv_test_freq,hiv_lastdate,hiv_lastresult'};

shData['hormone_hist-Y'] = {dtype:'radio',
show_q:'hormone_freq,hormone_lastdate'};
shData['hormone_hist-N'] = {dtype:'radio',
hide_q:'hormone_freq,hormone_lastdate'};

shData['prep_before-Y'] = {dtype:'radio',
show_q:'prep_lastdate,prep_stop,prep_start'};
shData['prep_before-N'] = {dtype:'radio',
hide_q:'prep_lastdate,prep_stop,prep_start'};

shData['pep_before-Y'] = {dtype:'radio',
show_q:'pep_lastdate'};
shData['pep_before-N'] = {dtype:'radio',
hide_q:'pep_lastdate'};

shData['unsafesex-N'] = {dtype:'radio',
hide_q:'unsafesex_date,unsafesex_time,unsafesex_hr'};
shData['unsafesex-Y1'] = {dtype:'radio',
show_q:'unsafesex_date,unsafesex_time,unsafesex_hr'};
shData['unsafesex-Y2'] = {dtype:'radio',
show_q:'unsafesex_date',hide_q:'unsafesex_time,unsafesex_hr'};

shData['risk1'] = {dtype:'check',hide_q:'risk4_title'};
shData['risk2'] = {dtype:'check',hide_q:'risk4_title'};
shData['risk3'] = {dtype:'check',hide_q:'risk4_title'};
shData['risk4'] = {dtype:'check',show_q:'risk4_title'};
shData['risk5'] = {dtype:'check',hide_q:'risk4_title'};
shData['risk6'] = {dtype:'check',hide_q:'risk4_title'};
shData['risk7'] = {dtype:'check',hide_q:'risk4_title'};
shData['risk8'] = {dtype:'check',hide_q:'risk4_title'};
shData['risk9'] = {dtype:'check',hide_q:'risk4_title'};
shData['risk10'] = {dtype:'check',hide_q:'risk4_title'};
shData['risk11'] = {dtype:'check',hide_q:'risk4_title'};
shData['risk12'] = {dtype:'check',hide_q:'risk4_title'};
shData['risk13'] = {dtype:'check',hide_q:'risk4_title'};

";

include_once("f_form_main_v2.php");

?>


<script>

$(document).ready(function(){

  $('.v_radio[data-dom="hiv_acute"]').click(function(){ // hiv_acute
     //alert("check hivacute");
    if(check_HIVacute()){
      $("#offered_prep-N").prop("checked", true);
      $("#offered_prep3-N").prop("checked", true);
      $("#offered_pep-N").prop("checked", true);

      $("#offered_prep_nd").prop("disabled", false);
      $("#offered_prep3_nd").prop("disabled", false);
      $("#offered_pep_nd").prop("disabled", false);

    }
    else{
      $("#offered_prep-N").prop("checked", false);
      $("#offered_prep3-N").prop("checked", false);
      $("#offered_pep-N").prop("checked", false);

      $("#offered_prep_nd").prop("disabled", true);
      $("#offered_prep3_nd").prop("disabled", true);
      $("#offered_pep_nd").prop("disabled", true);
    }

  });

});


function check_HIVacute(){ //check ภาวะการติดเชื้อ HIV ระยะเฉียบพลัน
  var num = 0;
  if($("#hivacute_fever-Y").prop("checked") == true) { // มีไข้
     if($("#hivacute_weak-Y").prop("checked") == true) num +=1;
     if($("#hivacute_sorethoat-Y").prop("checked") == true) num +=1;
     if($("#hivacute_heahache-Y").prop("checked") == true) num +=1;
     if($("#hivacute_fatigue-Y").prop("checked") == true) num +=1;
     if($("#hivacute_diarrhea-Y").prop("checked") == true) num +=1;
     if($("#hivacute_rash-Y").prop("checked") == true) num +=1;
     if($("#hivacute_jointpain-Y").prop("checked") == true) num +=1;
     if($("#hivacute_wound-Y").prop("checked") == true) num +=1;
     if($("#hivacute_nausea-Y").prop("checked") == true) num +=1;
     if($("#hivacute_stiffneck-Y").prop("checked") == true) num +=1;
  }
  if(num >= 3) return true;
  else return false;
}



function enrollProject(){
  if($("#is_pass-Y").is(':checked')){
    //alert("enroll Y");
     enrollToProject();
  }
  else if($("#is_pass-N").is(':checked')){
    //alert("enroll N");
     screenFailUID();
  }

}





</script>
