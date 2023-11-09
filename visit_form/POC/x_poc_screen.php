<?
/*
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
*/

include_once("inc_param.php");

//$link = isset($_GET["link"])?urldecode($_GET["link"]):"";

/*
$uid = "สอ280835";
$visit_date = "2019-09-05";
$project_id = "POC";
*/

$form_id = "poc_screen";
$form_name = "Point of Care : <b>Screening</b>";
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







$option_showhide = "
shData['is_pass_criteria-Y'] = {dtype:'radio',
show:'is_agree-Y, is_agree-N, is_agree_date', hide:'is_agree-N'};
shData['is_pass_criteria-N'] = {dtype:'radio',
show:'is_agree-N', hide:'is_agree-Y, is_agree_date'};

shData['is_consent-Y'] = {dtype:'radio', show:'is_agree-Y, is_agree_date', hide:'is_agree-N'};
shData['is_consent-N'] = {dtype:'radio', show:'is_agree-N', hide:'is_agree-Y, is_agree_date'};
";

include_once("f_form_main.php");

?>


<script>

$(document).ready(function(){

 initFormScreen();

 //$("#btn_save").hide();
 $(".btn-screen").hide();

  $("#is_agree-Y").click(function(){ // agree to join project
  //  checkScreening();

  });

  $("#is_agree-N").click(function(){ // not agree to join project
  //  checkScreening();
  });

  $("#is_pass_criteria-Y").click(function(){ // agree to join project
    $("#is_agree-Y").prop('checked', false);
    $("#is_agree-N").prop('checked', false);
    $("#is_consent-N").prop('checked', false);
    $("#is_consent-Y").prop('checked', false);
    $("#div-is_agree_date").show();

     hideSave();
     //$("#btn_save").hide();
  });

  $("#is_pass_criteria-N").click(function(){ // not agree to join project
    $("#is_agree-Y").prop('checked', false);
    $("#is_agree-N").prop('checked', false);
    $("#is_consent-N").prop('checked', true);
    $("#is_consent-Y").prop('checked', false);

    $("#is_agree_date").val('');
    $("#div-is_agree_date").hide();

    hideSave();
    // $("#btn_save").hide();

  });  

  $("#is_consent-Y").click(function(){ // agree to join project
    $("#div-is_agree_date").show();

     hideSave();
     //$("#btn_save").hide();
  });

  $("#is_consent-N").click(function(){ // not agree to join project
    hideSave();
    //$("#btn_save").hide();

    $("#div-is_agree_date").hide();
  });

  //**** part 1 screen
  $("#is_thai_nation-Y").click(function(){ checkScreeningPart1(); });
  $("#is_thai_nation-N").click(function(){ checkScreeningPart1(); });

  $("#is_over_18yrs-Y").click(function(){ checkScreeningPart1(); });
  $("#is_over_18yrs-N").click(function(){ checkScreeningPart1(); });

  $("#is_msm_tg-Y").click(function(){ checkScreeningPart1(); });
  $("#is_msm_tg-N").click(function(){ checkScreeningPart1(); });
  //******************

  //**** part 2 screen
  $("#is_risk_6mth_1-Y").click(function(){ checkScreeningPart2(); });
  $("#is_risk_6mth_1-N").click(function(){ checkScreeningPart2(); });

  $("#is_risk_6mth_2-Y").click(function(){ checkScreeningPart2(); });
  $("#is_risk_6mth_2-N").click(function(){ checkScreeningPart2(); });

  $("#is_risk_6mth_3-Y").click(function(){ checkScreeningPart2(); });
  $("#is_risk_6mth_3-N").click(function(){ checkScreeningPart2(); });

  $("#is_risk_6mth_4-Y").click(function(){ checkScreeningPart2(); });
  $("#is_risk_6mth_4-N").click(function(){ checkScreeningPart2(); });
  //******************

$("#is_agree-Y").click(function(){ showSave(); });
$("#is_agree-N").click(function(){ showSave(); });

});


function checkScreeningPart1(){
  $("#project_criteria-c").removeClass("badge");
  $("#project_criteria-c").removeClass("badge-success");
  $("#project_criteria-c").removeClass("badge-danger");

  var flag_valid = 1;
  var c1 = 0; // criteria 1 (1-3 = all Y)
  var pass_txt = "<i class='fa fa-check'></i> <b> ผ่านเกณฑ์ </b>"
  var not_pass_txt = "<i class='fa fa-times'></i> <b> ยังไม่ผ่านเกณฑ์ </b>"

  if($("#is_thai_nation-Y").is(':checked')) c1 += 1;
  if($("#is_over_18yrs-Y").is(':checked')) c1 += 1;
  if($("#is_msm_tg-Y").is(':checked')) c1 += 1;

  if(c1 == 3){
    $("#project_criteria-c").addClass("badge badge-success");
    $("#project_criteria-c").html(pass_txt);
    $("#tmp_form_main1").val('1');
  }
  else{
    $("#project_criteria-c").addClass("badge badge-danger");
    $("#project_criteria-c").html(not_pass_txt);
    $("#tmp_form_main1").val('0');
  }

   checkePassScreen();
}

function checkScreeningPart2(){
  $("#is_risk_6mth-c").removeClass("badge");
  $("#is_risk_6mth-c").removeClass("badge-success");
  $("#is_risk_6mth-c").removeClass("badge-danger");

  var flag_valid = 1;
  var pass_txt = "<i class='fa fa-check'></i> <b> ผ่านเกณฑ์ </b>";
  var not_pass_txt = "<i class='fa fa-times'></i> <b> ยังไม่ผ่านเกณฑ์ </b>";
  var c2 = 0; // criteria 2 (1.4.1-1.4.4 = at least 1 Y)

  if($("#is_risk_6mth_1-Y").is(':checked')) c2 += 1;
  if($("#is_risk_6mth_2-Y").is(':checked')) c2 += 1;
  if($("#is_risk_6mth_3-Y").is(':checked')) c2 += 1;
  if($("#is_risk_6mth_4-Y").is(':checked')) c2 += 1;

  //alert("c1:"+c1+"/c2:"+c2);
  if(c2 > 0){
    $("#is_risk_6mth-c").addClass("badge badge-success");
    $("#is_risk_6mth-c").html(pass_txt);
    $("#tmp_form_main2").val('1');
  }
  else{
    $("#is_risk_6mth-c").addClass("badge badge-danger");
    $("#is_risk_6mth-c").html(not_pass_txt);
    $("#tmp_form_main2").val('0');
  }

   checkePassScreen();

}

function checkePassScreen(){
  $("#is_risk_6mth_remark-c").removeClass("badge");
  $("#is_risk_6mth_remark-c").removeClass("badge-success");
  $("#is_risk_6mth_remark-c").removeClass("badge-danger");

  if($("#tmp_form_main1").val() == '1' && $("#tmp_form_main2").val() == '1'){//pass
    $("#is_risk_6mth_remark-c").addClass("badge badge-success");
    $("#is_risk_6mth_remark-c").html("สรุป: ผ่านเกณฑ์คัดกรอง");
    showPassAgreement()
  }
  else{ // not pass
    $("#is_risk_6mth_remark-c").addClass("badge badge-danger");
    $("#is_risk_6mth_remark-c").html("สรุป: ไม่ผ่านเกณฑ์คัดกรอง");
    showNotPassAgreement();
  }
}

function showPassAgreement(){
  $("#is_pass_criteria-Y").prop('checked', true);
  $("#is_pass_criteria-N").prop('checked', false);
  $("#div-is_pass_criteria-Y").show();
  $("#div-is_pass_criteria-N").hide();

  $("#is_consent-Y").prop('checked', false);
  $("#is_consent-N").prop('checked', false);
  $("#div-is_consent-Y").show();
  $("#div-is_consent-N").show();

  hideFinalAgreement();
}
function showNotPassAgreement(){
  $("#is_pass_criteria-Y").prop('checked', false);
  $("#is_pass_criteria-N").prop('checked', true);
  $("#div-is_pass_criteria-Y").hide();
  $("#div-is_pass_criteria-N").show();

  $("#is_consent-Y").prop('checked', false);
  $("#is_consent-N").prop('checked', true);
  $("#div-is_consent-Y").hide();
  $("#div-is_consent-N").show();

  $("#is_agree-Y").prop('checked', false);
  $("#is_agree-N").prop('checked', false);
     $("#div-is_agree-Y").hide();
     $("#div-is_agree_date").hide();
     $("#div-is_agree-N").show();

}


function hideFinalAgreement(){
  $("#is_agree-Y").prop('checked', false);
  $("#is_agree-N").prop('checked', false);
     $("#div-is_agree-Y").hide();
     $("#div-is_agree-N").hide();
     $("#div-is_agree_date").hide();
   hideSave();
}
function showSave(){

  <?
  if($uid_status != "1" && isset($auth["data"])){
    echo '$("#btn_save").show();';
  }
  ?>

  //$("#btn_save").show();

}
function hideSave(){
  <?
  //hide save only cbo staff
  if($_SESSION["s_group"] == "1" || $_SESSION["s_group"] == "99"){
     echo '$("#btn_save").show();';
  }
  else{
    echo '$("#btn_save").hide();';
  }
  ?>
  //$("#btn_save").hide();
}

function initFormScreen(){
  //alert("initFormscreen");
  //alert("uid status : <? echo $uid_status;?>");

  checkScreeningPart1();
  checkScreeningPart2();
  <?
    if($is_consent == "Y"){
      echo '$("#is_consent-Y").prop("checked", true);';
      echo '$("#is_consent-Y").show();';
    }
    else if($is_consent == "N"){
      echo '$("#is_consent-N").prop("checked", true);';
      echo '$("#div-is_consent-N").show();';
    }

    $flag_btn_save = "N";

    if($is_agree == "Y"){
      echo '$("#is_agree-Y").prop("checked", true);';
      echo '$("#div-is_agree-Y").show();';
      echo '$("#div-is_agree_date").show();';
      $flag_btn_save = "Y";
    }
    else if($is_agree == "N"){
      echo '$("#is_agree-N").prop("checked", true);';
      echo '$("#div-is_agree-N").show();';
      $flag_btn_save = "Y";
    }

    echo '$("#btn_save").hide();';
    if($uid_status == "0" && $flag_btn_save == "Y"){
      echo '$("#btn_save").show();';
    }
    else{

      if($uid_status == "1" && isset($auth["data"])){
         if($_SESSION["s_group"] == "1" || $_SESSION["s_group"] == "99"){
            echo '$("#btn_save").show();';
         }
      }

    }


  ?>

}


function enrollProject(){
  if($("#is_agree-Y").is(':checked')){
    //alert("enroll Y");
     enrollToProject();
  }
  else if($("#is_agree-N").is(':checked')){
    //alert("enroll N");
     screenFailUID();
  }

}


</script>
