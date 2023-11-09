<?


include_once("../function/in_fn_link.php");

$open_link="N";
$uic="";
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";

$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
$is_backdate = isset($_GET["bd"])?$_GET["bd"]:"N"; // back date filled

$link = isset($_GET["link"])?$_GET["link"]:"";

if(isset($_GET["link"])){ // if there is link param (from open link / qr code)

  $decode_link = decodeSingleLink($_GET["link"]);
  $arr = explode(":",$decode_link);
  if(count($arr)==6){

    $group_id = $arr[0]; // group_id
    $screen_id = $arr[1]; // screen_id
    $visit_date = $arr[2]; // visit date
    $birth_date = $arr[3]; // birth date
    $age = $arr[4]; // age
    $uid = $arr[5]; // pid

    $open_link="Y";
  }
  else{
  //  echo "count: ".count($arr);
    header( "location: ../info/invalid.php?e=e1" );
    exit(0);
  }

}



$form_id = "covid_visit_g$group_id";
$form_name = "COVID Group $group_id ";

$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$before_save_function = ""; // trigger before save function
$after_save_function = ""; // trigger after save function
$before_save_data = "
   if(flag_update_contact == 0) {
     openUpdateContact();
     return;
   }
"; // trigger before save data (after validate data before save)
$require_form_complete ="Y";
$initJSForm = ''; // initial js in f_form_main.php
$open_link_page = "updatePID();"; // trigger open target page after save complete
//$open_link_page = ""; // trigger open target page after save complete

$screen_date_open_link = $visit_date; // for open_link=Y


$initJSForm .= '$("#btn_save").hide();';


$option_showhide = "
// show/hide question
shData['fearworry6-1'] = {dtype:'radio',
show_q:'fearworry6_1,fearworry6_2'};
shData['fearworry6-2'] = {dtype:'radio',
hide_q:'fearworry6_1,fearworry6_2'};

";

if($group_id == '3')
$option_showhide .= "
shData['job_position-1'] = {dtype:'radio',
show_q:'job_position1',
hide_q:'job_position2,job_position3'};
shData['job_position-2'] = {dtype:'radio',
show_q:'job_position2',
hide_q:'job_position1,job_position3'};
shData['job_position-3'] = {dtype:'radio',
show_q:'job_position3',
hide_q:'job_position2,job_position1'};

";


?>

<div class="container" id="divCovid_Consent">
  <center><h4>ใบยินยอม</h4></center>
    <div>
      ชื่อ-นามสกุล
     <div class="form-check form-check-inline mx-2">
       <input class="form-check-input name_title-opt" type="radio" name="name_title" id="name_title-MR" value="MR">
       <label class="form-check-label" for="name_title-MR"><b>นาย</b></label>
     </div>
     <div class="form-check form-check-inline mx-2">
       <input class="form-check-input name_title-opt" type="radio" name="name_title" id="name_title-MS" value="MS">
       <label class="form-check-label" for="name_title-MS"><b>นาง</b></label>
     </div>
     <div class="form-check form-check-inline mx-2">
       <input class="form-check-input name_title-opt" type="radio" name="name_title" id="name_title-MRS" value="MRS">
       <label class="form-check-label" for="name_title-MRS"><b>นางสาว</b></label>
     </div>
    </div>

    <input type="text" id="txt_name" class="form-control" placeholder="ชื่อ-นามสกุล">

      <div>
      <label for="txt_address">ที่อยู่ (กรุณากรอกชื่ออาคาร ชื่อชุมชน ตำบล/แขวง อำเภอ/เขต และจังหวัด)</label>
      <input type="text" id="txt_address" class="form-control" placeholder="กรุณากรอกชื่ออาคาร ชื่อชุมชน ตำบล/แขวง อำเภอ/เขต และจังหวัด">

      </div>

      <div class="row my-2">
         <div class="col-sm-12">
         <b>ยินดีเข้าร่วมโครงการหรือไม่</b>
           <div class="form-check form-check-inline mx-4">
             <input class="form-check-input " type="radio" name="consent_accept" id="consent_accept-Y" value="Y">
             <label class="form-check-label" for="consent_accept-Y"> ยินยอมเข้าร่วม</label>
           </div>
           <div class="form-check form-check-inline mx-4">
             <input class="form-check-input " type="radio" name="consent_accept" id="consent_accept-N" value="N">
             <label class="form-check-label" for="consent_accept-N"> ไม่ยินยอมเข้าร่วม</label>
           </div>
         </div>
       </div>
       <div class="my-4">
         <button id="btn_complete_consent" class=" btn btn-primary form-control " type="button">
           <i class="fa fa-file-alt fa-lg" ></i> ดำเนินการต่อ
         </button>
       </div>
</div>




<script>
 var flag_update_contact = 0;
</script>

<div class="my-4" id="divCovid_Form" style="display:none;">

  <div><center><b><h4>แบบสอบถามชุดที่ <? echo $group_id ;?></h4></b></center></div>

   <div class="row my-2">

      <div class="col-sm-12">
      <center>
      <b>ท่านยินดีตอบแบบสอบถามหรือไม่ </b>
        <div class="form-check form-check-inline mx-4">
          <input class="form-check-input data_form_consent" type="radio" name="consent" id="consent-Y" value="Y">
          <label class="form-check-label" for="consent-Y"><i class="fa fa-check fa-lg text-success" ></i> ยินดี</label>
        </div>
        <div class="form-check form-check-inline mx-4">
          <input class="form-check-input data_form_consent" type="radio" name="consent" id="consent-N" value="N">
          <label class="form-check-label" for="consent-N"><i class="fa fa-times fa-lg text-danger" ></i> ไม่ยินดี</label>
        </div>
      </center>
      </div>

    </div>

    <div id="div_qn_content" style="display:none;">
      <?
      include_once("f_form_main.php");
      ?>
    </div>


<div class="my-4">
  <button id="btn_complete_form" class=" btn btn-primary form-control " type="button">
    <i class="fa fa-file-alt fa-lg" ></i> ดำเนินการต่อ (2)
  </button>
</div>
</div>

<div id="div_covid_contact" class="px-2 pt-2 pb-1 my-4" style="background-color:#C8F1FF;display:None;" >

<?
include_once("COVID/x_covid_contact.php");
?>
<div class="my-4">
  <button id="btn_complete_contact" class=" btn btn-primary form-control " type="button">
    <i class="fa fa-file-alt fa-lg" ></i> ส่งแบบฟอร์ม
  </button>
</div>
</div>



<script>

//$("#div_covid_contact").show();

var flag_update_contact = 0;

$("#screen_id").val("<? echo $screen_id; ?>");
$("#age").val("<? echo $age; ?>");
$("#dob").val(changeToEnDate("<? echo $birth_date; ?>"));

$("#initials").val("x");
//$("#initials").prop("disabled", true);
$("#age").prop("disabled", true);



$(document).ready(function(){
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
        $("#btn_complete_consent").click(function(){

          if($("#consent_accept-Y").prop("checked") == false && $("#consent_accept-N").prop("checked") == false) {
            $.notify("กรุณาเลือกลงนามอาสาสมัครแบบกระดาษ (ยินยอม หรือไม่ยินยอม)", "warn");
            return;
          }

          if($("#consent_accept-Y").prop("checked") == true){
             $("#consent_accept").val("Y");
             if(checkConsentPass()){
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


  $("#btn_complete_form").click(function(){
     if($("#consent-Y").prop("checked") == true){
       saveFormData();
     }
     else if($("#consent-N").prop("checked") == true){
       sendNotPassConsent();
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

      saveFormData();
    }
  }); //btn_complete_contact()



});

function checkConsentPass(){
   var flag = true;
   var comp;

      if($("#name_title-MR").prop("checked") == true) $("#name_title").val("MR");
      else if($("#name_title-MS").prop("checked") == true) $("#name_title").val("MS");
      else if($("#name_title-MRS").prop("checked") == true) $("#name_title").val("MRS");

      else {
        flag = false;
        $.notify("ดำเนินต่อไม่ได้ เนื่องจากยังไม่เลือกคำนำหน้า", "info");
        if(typeof comp == 'undefined' ) comp = $("#name_title-MR");
      }


      if($("#txt_name").val().trim() == ""){
        flag = false;
        $("#txt_name").notify("ดำเนินต่อไม่ได้ เนื่องจากกรอกข้อมูลไม่ครบ", "info");
        if(typeof comp == 'undefined' ) comp = $("#txt_name");
      }
      else $("#name").val($("#txt_name").val().trim());

      if($("#txt_address").val().trim() == ""){
        flag = false;
        $("#txt_address").notify("ดำเนินต่อไม่ได้ เนื่องจากกรอกข้อมูลไม่ครบ", "info");
        if(typeof comp == 'undefined' ) comp = $("#txt_address");
      }
      else $("#address").val($("#txt_address").val().trim());



   if(typeof comp !== 'undefined'){
     $("body,html").animate(
       {
         scrollTop: comp.offset().top - 50
       },500 //speed
       );
     //comp.notify("กรุณากรอกข้อมูลให้ถูกต้อง", "error");
   }

   return flag;
}

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
  lst_data_obj.push({name:"consent_voice_rec", dom:dom_name, value:""});
  lst_data_obj.push({name:"consent_accept", dom:dom_name, value:$("#consent_accept").val()});
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
