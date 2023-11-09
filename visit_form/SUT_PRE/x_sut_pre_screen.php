<?

$age = isset($_GET["age"])?$_GET["age"]:"";
$clinic_id = isset($_GET["clinic_id"])?$_GET["clinic_id"]:"x";



echo "clinic_id : $clinic_id";
include_once("inc_param.php");

//echo "$uid/$visit_date/$visit_id/$project_id/$group_id/$open_link/$form_id";

$form_id = "sut_pre_screen";
$form_name = "STANDUP-TEEN <b>Screening</b> ";

$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$before_save_function = ""; // trigger before save function
$after_save_function = "checkAfterSaveScreen();"; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php
$require_form_complete ="Y";
$js_sut_form = "";
if($open_link != "Y"){ // open link by staff
  include_once("../in_auth_db.php");

  if(!isset($auth["data"]) && !isset($auth["log"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }

}

$initJSForm .= '$("#scr_age-Y").prop("checked", true);';
$initJSForm .= '$("#div-scr_age-N").hide();';

if($age != ""){// enable data
  $initJSForm .= '$("#age").val("'.$age.'");';
}
else{ // lock data
  $initJSForm .= '$("#btn_save").hide();';
}


$option_showhide = "

// show/hide question

shData['scr_gender-Y'] = {dtype:'radio',
show_q:'scr_gender_spec'};

shData['scr_gender-N'] = {dtype:'radio',
hide_q:'scr_gender_spec,consent,consent_type'};

shData['scr_age-N'] = {dtype:'radio',
hide_q:'consent,consent_type'};

shData['scr_thai-N'] = {dtype:'radio',
hide_q:'consent,consent_type'};

shData['consent-Y'] = {dtype:'radio',
show_q:'consent_type'};

shData['consent-N'] = {dtype:'radio',
hide_q:'consent_type'};

";

$form_top = "<div class='my-2'><b>อายุ (ปี-เดือน-วัน): <span id='txt_age_sut'></span></b></div>";

include_once("f_form_main.php");


?>


<script>

$("#txt_age_sut").html($("#age").val());

$("#clinic_id").val("<? echo $clinic_id; ?>");



$(document).ready(function(){
  $("#scr_thai-Y").click(function(){ // consent yes
     checkSUTPass();

  });
  $("#scr_gender-Y").click(function(){ // consent yes
     checkSUTPass();

  });
  $("#scr_age-Y").click(function(){ // consent yes
     checkSUTPass();

  });


});

function checkSUTPass(){
   var flag_pass = 0;
   if($("#scr_age-Y").prop("checked")== true){
        flag_pass += 1;
   }
   if($("#scr_gender-Y").prop("checked")== true){
        flag_pass += 1;
   }
   if($("#scr_thai-Y").prop("checked")== true){
        flag_pass += 1;
   }

   //alert("flag_pass "+flag_pass);

   if(flag_pass == 3){ // pass all criteria
     $("#q_consent").show();
     $("#q_consent").data("is_show", "1");
   }
   else{
     $("#q_consent").hide();
     $("#q_consent").data("is_show", "0");
     $("#q_consent_type").hide();
     $("#q_consent_type").data("is_show", "0");
   }

}


function checkAfterSaveScreen(){
  $("#btn_save").hide();
  if($("#consent-Y").prop("checked")== true){

       if($("#consent_type-1").prop("checked")== true)
       afterScreen_FormPaper();
       else if($("#consent_type-2").prop("checked")== true)
       afterScreen_FormOnline();

  }
  else if($("#consent-N").prop("checked")== true){
       afterScreen_notConsent();
  }
}



</script>
