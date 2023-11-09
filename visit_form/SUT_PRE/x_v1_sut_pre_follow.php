<?


include_once("inc_param.php");
include_once("../w_proj_SUT_PRE/inc_pid_format.php");


//echo "$uid/$visit_date/$visit_id/$project_id/$group_id/$open_link/$form_id";

$form_id = "v1_sut_pre_follow";
$form_name = "STANDUP-TEEN <b>Follow UP ติดตามผล</b> ";

$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form

$before_save_function = "
if(!checkSUTVisit()){
  return;
}
"; // trigger before save function

//$before_save_function = ""; // trigger before save function
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php

$js_sut_form = "";
if($open_link != "Y"){ // open link by staff
  include_once("../in_auth_db.php");
/*
  if(!isset($auth["data"]) && !isset($auth["log"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
*/
}

$initJSForm .= '$("#t_self_test_second").hide();';


include_once("../in_db_conn.php");
        $query = "SELECT uv.visit_clinic_id
                   FROM p_project_uid_visit as uv
                   WHERE uid = ? AND visit_date=?
        ";

                 $stmt = $mysqli->prepare($query);
                 $stmt->bind_param("ss", $uid, $visit_date);
                 if($stmt->execute()){
                   $stmt->bind_result($cbo_clinic_id);

                   if ($stmt->fetch()) {

                   }
                 }
            $stmt->close();
//echo " $uid, $visit_date / $query / clinic_id : $cbo_clinic_id";

$q_sut2 = "st2_type,st2_receive,st2_post_number,st2_place,st2_sent_date,st2_receive_date,st2_result,st2_destroy,st2_destroy_date,st2_exp_test_date,st2_test,st2_test_date,st2_confirm,st2_confirm_date,st2_confirm_result";
$t_sut2="self_test_second";
$option_showhide = "

// show/hide question
shData['st_need_repeat-Y'] = {dtype:'radio',
show_q:'$q_sut2',show_t:'$t_sut2'};
shData['st_need_repeat-N'] = {dtype:'radio',
hide_q:'$q_sut2',hide_t:'$t_sut2'};

shData['st_receive-1'] = {dtype:'radio',
hide_q:'st_exp_send_date,st_place,st_sent_date,st_post_number'};
shData['st_receive-2'] = {dtype:'radio',
show_q:'st_exp_send_date,st_place,st_sent_date',
hide_q:'st_post_number'};
shData['st_receive-3'] = {dtype:'radio',
show_q:'st_exp_send_date,st_place,st_sent_date,st_post_number'};


shData['st_test-Y'] = {dtype:'radio',
show_q:'st_test_date,st_result,st_destroy,st_destroy_date',
show_t:'st_result_link'};
shData['st_test-N'] = {dtype:'radio',
hide_q:'st_test_date,st_result,st_confirm,st_confirm_date,st_confirm_result,st_confirm_place',hide_t:'st_result_link',
show_q:'st_destroy'};


shData['st_result-R'] = {dtype:'radio',
show_q:'st_confirm,st_confirm_date,st_confirm_result,st_confirm_place'};
shData['st_result-NR'] = {dtype:'radio',
hide_q:'st_confirm,st_confirm_date,st_confirm_result,st_confirm_place'};
shData['st_result-I'] = {dtype:'radio',
hide_q:'st_confirm,st_confirm_date,st_confirm_result,st_confirm_place'};

shData['st_confirm-Y'] = {dtype:'radio',
show_q:'st_confirm_date,st_confirm_result,st_confirm_place'};
shData['st_confirm-N'] = {dtype:'radio',
hide_q:'st_confirm_date,st_confirm_result,st_confirm_place'};
shData['st_confirm-L'] = {dtype:'radio',
hide_q:'st_confirm_date,st_confirm_result,st_confirm_place'};

shData['st_destroy-Y'] = {dtype:'radio',
show_q:'st_destroy_date'};
shData['st_destroy-N'] = {dtype:'radio',
hide_q:'st_destroy_date'};


shData['st2_receive-1'] = {dtype:'radio',
hide_q:'st2_exp_send_date,st2_place,st2_sent_date,st2_post_number,st2_exp_test_date'};
shData['st2_receive-2'] = {dtype:'radio',
show_q:'st2_exp_send_date,st2_place,st2_sent_date,st2_post_number,st2_exp_test_date'};
shData['st2_receive-3'] = {dtype:'radio',
show_q:'st2_exp_send_date,st2_place,st2_sent_date,st2_post_number,st2_exp_test_date'};


shData['st2_test-Y'] = {dtype:'radio',
show_q:'st2_test_date,st2_result,st2_destroy,st2_destroy_date',
show_t:'st2_result_link'};
shData['st2_test-N'] = {dtype:'radio',
hide_q:'st2_test_date,st2_result,st2_confirm,st2_confirm_date,st2_confirm_result,st2_confirm_place',hide_t:'st2_result_link',
show_q:'st2_destroy'};


shData['st2_result-R'] = {dtype:'radio',
show_q:'st2_confirm,st2_confirm_date,st2_confirm_result,st2_confirm_place'};
shData['st2_result-NR'] = {dtype:'radio',
hide_q:'st2_confirm,st2_confirm_date,st2_confirm_result,st2_confirm_place'};
shData['st2_result-I'] = {dtype:'radio',
hide_q:'st2_confirm,st2_confirm_date,st2_confirm_result,st2_confirm_place'};

shData['st2_confirm-Y'] = {dtype:'radio',
show_q:'st2_confirm_date,st2_confirm_result,st2_confirm_place'};
shData['st2_confirm-N'] = {dtype:'radio',
hide_q:'st2_confirm_date,st2_confirm_result,st2_confirm_place'};
shData['st2_confirm-L'] = {dtype:'radio',
hide_q:'st2_confirm_date,st2_confirm_result,st2_confirm_place'};

shData['st2_destroy-Y'] = {dtype:'radio',
show_q:'st2_destroy_date'};
shData['st2_destroy-N'] = {dtype:'radio',
hide_q:'st2_destroy_date'};




shData['sut_interest-Y'] = {dtype:'radio',
show_q:'telephone,line_id,other_contact',show_t:'sut_detail'};
shData['sut_interest-N'] = {dtype:'radio',
hide_q:'telephone,line_id,other_contact',hide_t:'sut_detail'};


";
$js_sut_form = "";
include_once("f_form_main.php");


?>


<script>
initSUT_Follow();
/*
$("#test_date_in").addClass("text-danger");
$("#test_date_in").html("pop99");
*/
calTestDate($("#st_sent_date").val());



$(document).ready(function(){

  $("#st_result-NR").click(function(){ // Non Reactive
     checkSUTResult();
  });
  $("#st_result-R").click(function(){ //  Reactive
     checkSUTResult();
  });
  $("#st_result-I").click(function(){ // InCon
     checkSUTResult();
  });


  $("#st_test_date").focusin(function(){ // consent yes
    calTestDate($("#st_sent_date").val());
    /*
    $("#test_date_in").addClass("text-danger");
    $("#test_date_in").html("pop99");
    */
  });



});

function initSUT_Follow(){
  <?
    $link = "javascript:void(0)";
    if(isset($clinic_link_hivtest_self[$cbo_clinic_id])){
      $link = $clinic_link_hivtest_self[$cbo_clinic_id];
    }

  ?>

  var btn_link = '<a href="<? echo $link; ?>" class="btn btn-primary btn-sm" target="_blank" >แนบไฟล์รูปที่นี่</a>';
  var btn_link2 = '<a href="<? echo $link; ?>" class="btn btn-primary btn-sm" target="_blank">แนบไฟล์รูปที่นี่</a>';
/*
  var btn_link = '<a href="" class="btn btn-primary btn-sm" target="_blank">แนบไฟล์รูปที่นี่</a>';
  var btn_link2 = '<a href="" class="btn btn-primary btn-sm" target="_blank">แนบไฟล์รูปที่นี่</a>';
*/

  $("#st_result_link-c").html(btn_link);
  $("#st2_result_link-c").html(btn_link2);

  $("#t_st_result_link").hide();
  $("#t_st2_result_link").hide();

  if($("#st_test-Y").prop("checked") == true){
    $("#t_st_result_link").show();
  }
  if($("#st2_test-Y").prop("checked") == true){
    $("#t_st2_result_link").show();
  }


  if($("#st_type-oral").prop("checked") == false && $("#st_type-finger").prop("checked") == false){
    checkST_Type(); // หาชุดตรวจจากที่เคสกรอกมา
  }

}

function checkST_Type(){ // check ชุดตรวจที่เคสเลือก

  var aData = {
            u_mode:"check_st_type",
            uid:'<? echo $uid; ?>',
            visit_date:'<? echo $visit_date; ?>'
  };
  save_data_ajax(aData,"./w_proj_SUT_PRE/db_sut.php",checkST_TypeComplete);

}
function checkST_TypeComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is saveSUT_VisitComplete : "+flagSave);
  if(flagSave){
    if(rtnDataAjax.hivself_type == "liquid")$("#st_type-oral").prop("checked", true);
    else if(rtnDataAjax.hivself_type == "blood")$("#st_type-finger").prop("checked", true);
  }
}

function checkSUTResult(){
  if($("#st_result-I").prop("checked") == true){
    $("#div-st_need_repeat-Y").show();
    $("#div-st_need_repeat-N").show();
    $("#st_need_repeat-Y").prop("checked", false);
    $("#st_need_repeat-N").prop("checked", false);
  }
  else{
    $("#div-st_need_repeat-Y").hide();
    $("#div-st_need_repeat-N").show();
    $("#st_need_repeat-N").prop("checked", true);
  }
}

function checkSUTVisit(){
//  alert("checkSUTVisit");
  var is_valid = true;

  var comp;
  if($("#st_receive-1").prop("checked") == true){ // รับชุดตรวจด้วยตัวเองที่ cbo
     if($("#st_receive_date").val() == ''){
       $("#st_receive_date").notify("กรุณากรอกวันรับชุดตรวจ", "error");
       comp = $("#st_receive_date");
       is_valid = false;
     }
     else{


       if($("#st_test_date").val() != ''){

         var diff = dateDiffCal(
         new Date(changeToEnDate($("#st_receive_date").val())),
         new Date(changeToEnDate($("#st_test_date").val()))
         );

       if(diff > 10 || diff < 0){
         $("#st_test_date").notify("วันใช้ชุดตรวจไม่ถูกต้อง (ต้องภายใน 10 วัน นับจากจากวันส่งชุดตรวจ)", "error");
         alert("วันใช้ชุดตรวจไม่ถูกต้อง (ต้องภายใน 10 วัน นับจากจากวันส่งชุดตรวจ)");
         calTestDate($("#st_receive_date").val());

         if(typeof comp == 'undefined' ) comp = $("#st_test_date");
         is_valid = false;
       }

     }

/*
     // วันได้รับชุดตรวจ ต้องมากกว่าวันที่ทำ consent
     var diff2 = dateDiffCal(
     new Date(changeToEnDate($("#st_receive_date").val())),
     new Date('<? echo $visit_date; ?>')
     );
     if(diff2 < 0){
       if(typeof comp == 'undefined' ) comp = $("#st_receive_date");
       is_valid = false;
     }
*/


   }//else
  }
  else{
    if(($("#st_receive-2").prop("checked") == true) || ($("#st_receive-3").prop("checked") == true)){ // ส่งชุดตรวจไปให้
       if($("#st_exp_send_date").val() == ''){
         $("#st_exp_send_date").notify("กรุณากรอกวันที่คาดว่าจะส่งชุดตรวจ", "error");
         comp = $("#st_exp_send_date");
         is_valid = false;
       }
    }
  }

  if($("#st_test-Y").prop("checked") == true){ // วันใช้ัชุดตรวจ
     if($("#st_test_date").val() == ''){
       $("#st_test_date").notify("กรุณากรอกวันใช้ชุดตรวจ", "error");
       if(typeof comp == 'undefined' ){
          comp = $("#st_test_date");
       }
       is_valid = false;
     }
     else if(($("#st_receive-2").prop("checked") == true) || ($("#st_receive-3").prop("checked") == true)){ // ส่งชุดตรวจไปให้
        if($("#st_sent_date").val() != ''){

          var diff = dateDiffCal(
          new Date(changeToEnDate($("#st_sent_date").val())),
          new Date(changeToEnDate($("#st_test_date").val()))
        );

          if(diff > 10 || diff < 0){
            $("#st_test_date").notify("วันใช้ชุดตรวจไม่ถูกต้อง (ต้องภายใน 10 วัน นับจากจากวันส่งชุดตรวจ)", "error");
            alert("วันใช้ชุดตรวจไม่ถูกต้อง (ต้องภายใน 10 วัน นับจากจากวันส่งชุดตรวจ)");
            calTestDate($("#st_sent_date").val());

            if(typeof comp == 'undefined' ) comp = $("#st_test_date");
            is_valid = false;
          }
        }
     }


     if($("#st_receive_date").val() == ''){
       $("#st_receive_date").notify("กรุณากรอกวันรับชุดตรวจ", "error");
       if(typeof comp == 'undefined' ) comp = $("#st_receive_date");
       is_valid = false;
     }

  }
  else if($("#st_test-N").prop("checked") == true){ // ไม่ใช้ชุดตรวจ
    if($("#st_receive_date").val() == ''){
      $("#st_receive_date").notify("กรุณากรอกวันรับชุดตรวจ", "error");
      if(typeof comp == 'undefined' ) comp = $("#st_receive_date");
      is_valid = false;
    }
  }


  if($("#st_receive_date").val() != ''){ // check วันรับชุดตรวจ

    var receive_date = changeToEnDate($("#st_receive_date").val());

    if($("#st_sent_date").val() != ''){
      var sent_date = changeToEnDate($("#st_sent_date").val());
      if(receive_date < sent_date){
        alert("ผิดพลาด: วันรับชุดตรวจ จะถึงก่อนวันส่งชุดตรวจไม่ได้");
        $("#st_receive_date").notify("กรุณากรอกวันรับชุดตรวจ", "error");
        if(typeof comp == 'undefined' ) comp = $("#st_receive_date");
        is_valid = false;
      }


    }

    if($("#st_test_date").val() != ''){
      var test_date = changeToEnDate($("#st_test_date").val());
      if(receive_date > test_date){
        alert("ผิดพลาด: วันรับชุดตรวจ จะอยูภายหลังวันใช้ชุดตรวจไม่ได้");
        $("#st_receive_date").notify("กรุณากรอกวันรับชุดตรวจ", "error");
        if(typeof comp == 'undefined' ) comp = $("#st_receive_date");
        is_valid = false;
      }

    }
  }


  if($("#st_destroy-Y").prop("checked") == true){ // วันใช้ัชุดตรวจ
     if($("#st_destroy_date").val() == ''){
       $("#st_destroy_date").notify("กรุณากรอกวันทำลายชุดตรวจ", "error");
       if(typeof comp == 'undefined' ){
          comp = $("#st_destroy_date");
       }
       is_valid = false;
     }
  }

  if($("#st_sent_date").val() != '' && $("#st_exp_test_date").val() != ''){
    var date_sent = new Date(changeToEnDate($("#st_sent_date").val())) ;
    var date_test = new Date(changeToEnDate($("#st_exp_test_date").val())) ;

    var diff = dateDiffCal(date_sent,date_test);
    if(diff > 10 || diff < 0){
      $("#st_exp_test_date").notify("วันนัดใช้ชุดตรวจไม่ถูกต้อง (ต้องภายใน 10 วัน นับจากจากวันส่งชุดตรวจ)", "error");
      alert("วันนัดใช้ชุดตรวจไม่ถูกต้อง (ต้องภายใน 10 วัน นับจากจากวันส่งชุดตรวจ)");
      calTestDate($("#st_sent_date").val());

      if(typeof comp == 'undefined' ) comp = $("#st_exp_test_date");
      is_valid = false;
    }

  }


  if(typeof comp !== 'undefined'){
    $("body,html").animate(
      {
        scrollTop: comp.offset().top - 50
      },500 //speed
      );
    comp.notify("กรุณากรอกข้อมูลให้ถูกต้อง", "error");
  }

  return is_valid;
}

function dateDiffCal(date1, date2){
  dt1 = new Date(date1);
  dt2 = new Date(date2);
  return Math.floor((Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate()) - Date.UTC(dt1.getFullYear(), dt1.getMonth(), dt1.getDate()) ) /(1000 * 60 * 60 * 24));

}

function calTestDate(dateTHVal){
  var sent_date = changeToEnDate(dateTHVal);
  if(sent_date != "0000-00-00"){
    var date = new Date(sent_date);
    // add 10 days
    date.setDate(date.getDate() + 10);

    const dateTimeFormat = new Intl.DateTimeFormat('en', { year: 'numeric', month: 'numeric', day: '2-digit' })
    const [{ value: month },,{ value: day },,{ value: year }] = dateTimeFormat .formatToParts(date )
    /*
    const dateTimeFormat = new Intl.DateTimeFormat('en', { year: 'numeric', month: 'short', day: '2-digit' })
    const [{ value: month },,{ value: day },,{ value: year }] = dateTimeFormat .formatToParts(date )
  */
    var str = `${day}/${month}/${year }`;
    $(".test_date_in").addClass("text-danger");
    $(".test_date_in").html("ใช้ภายในวันที่ "+str);

      /*
    var sutSendDate = new Date();
    var begDate = new Date();
    var endDate = new Date();

    var arrDate = sent_date.split("-");
    //alert("send date "+arrDate[0]+"-"+arrDate[1]+"-"+arrDate[2]);
    sutSendDate.setFullYear(arrDate[0], parseInt(arrDate[1])-1, arrDate[2]);
    begDate.setFullYear(arrDate[0], parseInt(arrDate[1])-1, arrDate[2]);
    endDate.setFullYear(arrDate[0], parseInt(arrDate[1])-1, arrDate[2]);
  //alert("sutSendDate1: "+sutSendDate);

    begDate.setDate(begDate.getDate());
    endDate.setDate(endDate.getDate() + 10);

    sutSendDate.setYear(sutSendDate.getFullYear());
    begDate.setYear(begDate.getFullYear());
    endDate.setYear(endDate.getFullYear());


      $("#st_exp_test_date").datepicker({
        changeMonth: false,
        changeYear: false,

        dateFormat: 'dd/mm/yy',
        minDate: 0,
        maxDate: 10,

        onSelect: function(date) {
          $("#st_exp_test_date").addClass('filled');
        }
      });
      */

      //$('#st_exp_test_date').datepicker("setDate",sutSendDate );
  }


}





</script>
