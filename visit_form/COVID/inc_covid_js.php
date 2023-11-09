
<script>
$("#screen_id").val("<? echo $screen_id; ?>");
$("#age").val("<? echo $age; ?>");
$("#dob").val(changeToEnDate("<? echo $birth_date; ?>"));

$("#initials").prop("disabled", true);
$("#age").prop("disabled", true);

$("#divCovid_Form").hide();
if($("#txt_consent_date").val() == "" ){
  var visit_date_th = changeToThaiDate("<? echo $visit_date;?>");
  //alert("thaidate "+visit_date_th);
  $("#txt_consent_date").val(visit_date_th);
  $(".consent_date_th").html(visit_date_th);
}
else{
  $(".consent_date_th").html($("#consent_date").val());
}

$(".short-name-sign").prop("disabled", true);



$(document).ready(function(){

  //$(".short-name-sign").mask("****",{placeholder:"ชื่อย่อ 4 ตัวอักษร"});
  $(".short-name-sign").click(function(){
    $(this).notify("กรอกอักษร 2 ตัวแรกของชื่อและ 2 ตัวแรกของนามสกุล โดยไม่ต้องเว้นวรรค", "info");

  }); //short-name-sign

  $(".consentname").focusout(function(){
    $("#name2").html($("#txt_name").val()+" "+$("#txt_sur_name").val());
  }); //txt_name

  $(".rdo_voice_consent").click(function(){
    //alert("choose "+$(this).data("id"));
    $(".consent_voice").val("");
    $(".consent_voice").prop("disabled", true);
    $("#txt_consent_voice_rec_name_"+$(this).data("id")).prop("disabled", false);
  }); //rdo_voice_consent

  $(".rdo_video_consent").click(function(){
    $(".consent_video").val("");
    $(".consent_video").prop("disabled", true);
    $("#txt_consent_video_rec_name_"+$(this).data("id")).prop("disabled", false);
  }); //rdo_video_consent



        $(".q_family").click(function(){ // family (ท่านอาศัยอยู่กับ (สามารถตอบได้มากกว่า 1 ข้อ))
          if($(this).attr('id')=="family8"){
            $(".q_family:not(#family8)").prop("checked", false);

            $("#q_family").find('input[type=text]').val('');
            $("#q_family").find('input[type=text]').prop( "disabled", true );
            $("#q_family").find('input[type=text]').removeClass('input_invalid');
          }
          else{
            $("#family8").prop("checked", false);
          }
        });
        $(".q_family_care").click(function(){ // family (ท่านอาศัยอยู่กับ (สามารถตอบได้มากกว่า 1 ข้อ))
          if($(this).attr('id')=="family_care6"){
            $(".q_family_care:not(#family_care6)").prop("checked", false);

            $("#q_family_care").find('input[type=text]').val('');
            $("#q_family_care").find('input[type=text]').prop( "disabled", true );
            $("#q_family_care").find('input[type=text]').removeClass('input_invalid');
          }
          else{
            $("#family_care6").prop("checked", false);
          }
        });

        $(".data_form_consent").click(function(){
           if($(this).val() == "Y"){
             $("#consent").val("Y");
             $("#div_qn_content").show();
           }
           else if($(this).val() == "N"){
             $("#consent").val("N");
             $("#div_qn_content").hide();
           }
        }); //btn_complete_form()

        /*
          $("#btn_complete_consent").click(function(){
              $("#divCovid_Consent").hide();
              $("#divCovid_Form").show();
          }); //btn_complete_consent()
        */
        $("#btn_complete_consent").click(function(){
          if($("#consent_accept-Y").prop("checked") == false && $("#consent_accept-N").prop("checked") == false) {
            $("#name2").notify("กรุณาเลือกลงนามอาสาสมัครแบบออนไลน์ (ยินยอม หรือไม่ยินยอม)", "warn");
            return;
          }

          if($("#consent_accept-Y").prop("checked") == true){
             $("#consent_accept").val("Y");
             if(checkConsentPass()){

               checkDuplicatePID();

               if($("#consent_voice_rec_name").val() != "")
                 $("#initials").val($("#consent_voice_rec_name").val());
               else $("#initials").val($("#consent_video_rec_name").val());


               $("#form_head_id").hide();
               $("#divCovid_Consent").hide();
               $("#divCovid_Form").show();
                 $("body,html").animate(
                   {
                     scrollTop: 0
                   },500 //speed
                 );
             }


          }
          else if($("#consent_accept-N").prop("checked") == true){
            $("#consent_accept").val("N");
            if(checkConsentPass()){
               sendNotPassConsent();
            }
          }
        }); //btn_complete_consent()

  $("#btn_complete_form").click(function(){
     if($("#consent-Y").prop("checked") == true){
       saveFormData();
     }
     else if($("#consent-N").prop("checked") == true){
       sendNotPassConsent();
     }
     else{
       $.notify("กรุณาเลือก ยินยอม หรือไม่ยินยอม ตอบแบบสอบถาม)", "warn");
     }

  }); //btn_complete_form()

  $("#btn_complete_contact").click(function(){

    if(checkContactInfo()){
      flag_update_contact = 1;
      $("#contact_tel").val($("#txt_contact_tel").val());
      $("#contact_lineid").val($("#txt_contact_lineid").val());
      $("#contact_email").val($("#txt_contact_email").val());
      $("#contact_other").val($("#txt_contact_other").val());
      $("#pmt_bank").val($("#sel_bank").val());
      $("#pmt_bank_other").val($("#txt_pmt_bank_other").val());
      $("#pmt_bank_acc_code").val($("#txt_pmt_bank_acc_code").val());
      $("#pmt_bank_acc_name").val($("#txt_pmt_bank_acc_name").val());
      $("#pmt_promptpay_id_card").val($("#txt_pmt_promptpay_id_card").val());
      $("#pmt_promptpay_tel").val($("#txt_pmt_promptpay_tel").val());


      $("#btn_complete_contact").prop("disabled", true);
      saveFormData();
    }
  }); //btn_complete_contact()



});


function openUpdateContact(){ // after press ดำเนินการต่อ (2) กรอกข้อมูลติดต่อ
  $("#btn_complete_form").hide();
  $("#div_covid_contact").show();
  $("body,html").animate(
    {
      scrollTop: $("#txt_contact_tel").offset().top - 70
    },500 //speed
    );

}



function sendNotPassConsent(){ // save not pass consent
  var dom_name = "z202009_covid_visit_g<? echo $group_id; ?>";
  var lst_data_obj=[];
  lst_data_obj.push({name:"screen_id",dom:dom_name, value:$("#screen_id").val()});
  lst_data_obj.push({name:"age", dom:dom_name, value:$("#age").val()});
  lst_data_obj.push({name:"dob", dom:dom_name, value:$("#dob").val()});
  lst_data_obj.push({name:"name", dom:dom_name, value:$("#name").val()});
  lst_data_obj.push({name:"address", dom:dom_name, value:$("#address").val()});

  lst_data_obj.push({name:"name_title", dom:dom_name, value:$("#name_title").val()});
  lst_data_obj.push({name:"consent_voice_rec", dom:dom_name, value:$("#consent_voice_rec").val()});
  lst_data_obj.push({name:"consent_voice_rec_name", dom:dom_name, value:$("#consent_voice_rec_name").val()});
  lst_data_obj.push({name:"consent_video_rec", dom:dom_name, value:$("#consent_video_rec").val()});
  lst_data_obj.push({name:"consent_video_rec_name", dom:dom_name, value:$("#consent_video_rec_name").val()});
  lst_data_obj.push({name:"consent_accept", dom:dom_name, value:"N"});

  if($("#consent-N").prop("checked") == true){
    lst_data_obj.push({name:"consent", dom:dom_name, value:"N"});
  }

  var aData = {
            u_mode:"save_data",

            uid:'<? echo $uid; ?>',
            group_id:'<? echo $group_id; ?>',
            form_id:'<? echo $form_id; ?>',
            form_done:'N',
            lst_data:lst_data_obj,
            visit_date:'<? echo $visit_date; ?>',
            open_link:'<? echo $open_link; ?>'
  };

  save_data_ajax(aData,"db_form_data.php",sendNotPassConsentComplete);

}
function sendNotPassConsentComplete(flagSave, rtnDataAjax, aData){
  if(flagSave){
    updateNotPassPID();
  }
}


function checkDuplicatePID(){

  var aData = {
            u_mode:"check_duplicate_pid",
            name:$("#txt_name").val().trim(),
            sur_name:$("#txt_sur_name").val().trim(),
            dob:$("#dob").val(),
            group_id:'<? echo $group_id; ?>'
  };
  save_data_ajax(aData,"../w_proj_covid19/db_covid.php",checkDuplicatePIDComplete);

}


function checkDuplicatePIDComplete(flagSave, rtnDataAjax, aData){

  if(flagSave){
    if(rtnDataAjax.pid != ""){
      var link = "../info/inf_txt_covid.php?e=dup&pid="+rtnDataAjax.pid;
      window.location = link;
    }

  }
}

function updatePID(){
  var aData = {
            u_mode:"update_pid",
            uid:'<? echo $uid; ?>',
            group_id:'<? echo $group_id; ?>',
            visit_date:'<? echo $visit_date; ?>',
            open_link:'Y'
  };
  save_data_ajax(aData,"../w_proj_covid19/db_covid.php",updatePIDComplete);

}


function updatePIDComplete(flagSave, rtnDataAjax, aData){
  if(flagSave){
    //alert("PID "+rtnDataAjax.pid);
    var link = "../info/inf_txt.php?e=f1&f=<? echo $form_name; ?>&u="+rtnDataAjax.pid;
    window.location = link;
  }
} 

function updateNotPassPID(){
  var aData = {
            u_mode:"update_not_pass_pid",
            uid:'<? echo $uid; ?>',
            group_id:'<? echo $group_id; ?>',
            visit_date:'<? echo $visit_date; ?>',
            open_link:'Y'
  };
  save_data_ajax(aData,"../w_proj_covid19/db_covid.php",updateNotPassPIDComplete);

}
function updateNotPassPIDComplete(flagSave, rtnDataAjax, aData){
  if(flagSave){
    var link = "../info/inf_txt.php?e=np&f=<? echo $form_name; ?>&c=no";
    window.location = link;
  }
}

</script>
