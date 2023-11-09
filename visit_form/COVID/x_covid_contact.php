
<div class="row ">
   <div class="col-sm-1">
   </div>
   <div class="col-sm-10">
 <div class="my-2">
  <h4><b>โปรดระบุข้อมูลของท่านเพื่อใช้ในการติดต่อยืนยันเรื่องค่าตอบแทน</b></h4>
 </div>
 <div class="row ">
    <div class="col-sm-6">
      <label for="txt_contact_tel">เบอร์โทรศัพท์ติดต่อ: (เบอร์มือถือที่ขึ้นต้นด้วย 08 06 09 หรือ เบอร์บ้านที่ขึ้นต้นด้วย 02)<span class="text-danger"><b>*</b></span></label>
      <input type="text" id="txt_contact_tel" size="20" maxlength="10" placeholder="เบอร์โทรศัพท์ (เบอร์มือถือ 10 หลัก หรือเบอร์บ้าน 9 หลัก)" class="form-control form-control-sm v_int2 data-contact">
    </div>
    <div class="col-sm-6">
    </div>
  </div>

   <div class="row ">
     <div class="col-sm-6">
       <label for="txt_contact_email">อีเมล์:</label>
       <input type="text" id="txt_contact_email" size="20" maxlength="150" placeholder="อีเมล์" class="form-control form-control-sm data-contact">
     </div>
     <div class="col-sm-6">
       <label for="txt_contact_lineid">ไลน์ไอดี:</label>
       <input type="text" id="txt_contact_lineid" size="20" maxlength="150" placeholder="Line ID" class="form-control form-control-sm data-contact">

     </div>
    </div>
    <div class="row ">
      <div class="col-sm-12">
        <label for="txt_contact_other">ติดต่อแบบอื่น (ระบุ):</label>
        <input type="text" id="txt_contact_other" size="150" maxlength="250" class="form-control form-control-sm data-contact">

<!--        <textarea rows='4' cols='50' maxlength='250' id='txt_contact_other' name='txt_contact_other'  class='form-control'></textarea> -->
      </div>
     </div>

 <div class="my-2">
  <h4><u>ข้อมูลในการรับค่าตอบแทน</u></h4>
 <div class="my-4">
   <label for="sel_pmt_opt">ช่องทางในการรับค่าตอบแทน:</label>
   <select id="sel_pmt_opt" class="form-control form-control-sm" >
     <option value="" disabled selected>-กรุณาเลือก-</option>
     <option value="1">บัญชีธนาคาร (Bank Account)</option>
     <option value="2">พร้อมเพย์ (Prompt Pay)</option>
   </select>
 </div>
 <div class="my-4 div-pmt-opt" id="div_pay_bank"  style="display:none;">
 <div class="row ">
    <div class="col-sm-6">
      <label for="sel_bank">ธนาคาร:</label>
      <select id="sel_bank" class="form-control form-control-sm" >
        <option value="" disabled selected>-กรุณาเลือกธนาคาร-</option>
        <option value="SCB">SCB ธนาคารไทยพาณิชย์</option>
        <option value="KBANK">KBANK ธนาคารกสิกรไทย</option>
        <option value="KTB">KTB ธนาคารกรุงไทย</option>
        <option value="BBL">BBL ธนาคารกรุงเทพ</option>
        <option value="TMB">TMB ธนาคารทหารไทย</option>
        <option value="BAY">BAY ธนาคารกรุงศรีอยุธยา</option>
        <option value="GSB">GSB ธนาคารออมสิน</option>
        <option value="KKP">KKP ธนาคารเกียรตินาคิน</option>
        <option value="CIMBT">CIMBT ธนาคารซีไอเอ็มบีไทย</option>
        <option value="TISCO">TISCO ธนาคารทิสโก้</option>
        <option value="TBANK">TBANK ธนาคารธนชาต</option>
        <option value="UOBT">UOBT ธนาคารยูโอบี</option>
        <option value="BAAC">BAAC ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร</option>
        <option value="OTH">OTH ธนาคารอื่น (ระบุ)</option>
      </select>
    </div>
    <div class="col-sm-6">
      <label for="txt_pmt_bank_other" class="text-white">.</label>
      <input type="text" id="txt_pmt_bank_other" size="20" maxlength="30" placeholder="ธนาคารอื่น โปรดระบุ" class="form-control form-control-sm data-contact" style="display:none;">
    </div>
  </div>
  <div class="row ">
    <div class="col-sm-6">
      <label for="sel_bank">เลขที่บัญชี:</label>
      <input type="text" id="txt_pmt_bank_acc_code" size="20" maxlength="20" placeholder="เลขที่บัญชี" class="form-control form-control-sm v_int2 data-contact">
    </div>
    <div class="col-sm-6">
      <label for="sel_bank">ชื่อบัญชี:</label>
      <input type="text" id="txt_pmt_bank_acc_name" size="20" maxlength="30" placeholder="ชื่อบัญชี" class="form-control form-control-sm data-contact">
    </div>
   </div>
 </div>
   <div class="row my-4 div-pmt-opt" id="div_pay_promptpay"  style="display:none;">
     <div class="col-sm-6">
       <label for="txt_pmt_promptpay_id_card">พร้อมเพย์ผูกบัญชีกับบัตรประชาชน:</label>
       <input type="text" id="txt_pmt_promptpay_id_card" size="20" maxlength="13" placeholder="ระบุเลขที่บัตรประชาชน 13 หลัก" class="form-control form-control-sm v_int2 data-contact">
     </div>
     <div class="col-sm-6">
       <label for="txt_pmt_promptpay_tel">พร้อมเพย์ผูกบัญชีกับเบอร์โทรศัพท์มือถือ:</label>
       <input type="text" id="txt_pmt_promptpay_tel" size="20" maxlength="10" placeholder="ระบุเบอร์โทรศัพท์มือถือ 10 หลัก" class="form-control form-control-sm v_int2 data-contact">
     </div>
    </div>
</div>


</div>
<div class="col-sm-1">
</div>
</div>

<script>


$(document).ready(function(){

  $("#sel_pmt_opt").change(function(){

    $(".div-pmt-opt").hide();
    if($(this).val() == "1") {
      $("#div_pay_bank").show();
      $("#sel_bank").focus();
    }
    else if($(this).val() == "2") {
      $("#div_pay_promptpay").show();
      $("#txt_pmt_promptpay_id_card").focus();
    }
  }); //sel_pmt_opt()


    $("#sel_bank").change(function(){
      if($(this).val() == "OTH") {

        $("#txt_pmt_bank_other").show();
        $("#txt_pmt_bank_other").focus();
      }
      else{
        $("#txt_pmt_bank_other").val("");
        $("#txt_pmt_bank_other").hide();
      }
    }); //sel_bank()

  $(".v_int2").on("keypress keyup blur",function (event) {
      //$(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)
           && (event.which != 8) ) {
                 event.preventDefault();
        }
  });

});

function checkContactInfo(){

  var flag = true;
  if($("#txt_contact_tel").val() == ""){
      $("#txt_contact_tel").notify("กรุณากรอกเบอร์โทรศัพท์มือถือ หรือเบอร์โทรศัพท์บ้าน(ที่ขึ้นต้นด้วย 02)", "warn");
      flag = false;
  }
  else{

    if($("#txt_contact_tel").val().length < 8){
      flag = false;
      $("#txt_contact_tel").notify("กรุณาตรวจสอบเบอร์โทรศัพท์", "info");
      if(typeof comp == 'undefined' ) comp = $("#txt_contact_tel");
    }

    else if($("#txt_contact_tel").val().length == 9){
      var prefix_tel = $("#txt_contact_tel").val().substring(0, 2);
      if(prefix_tel != "02"){
        flag = false;
        $("#txt_contact_tel").notify("กรุณาตรวจสอบเบอร์โทรศัพท์ติดต่อ", "info");
        if(typeof comp == 'undefined' ) comp = $("#txt_contact_tel");

      }

    }
    else if($("#txt_contact_tel").val().length == 10){
      var prefix_tel = $("#txt_contact_tel").val().substring(0, 2);
      if(prefix_tel == "02"){
        flag = false;
        $("#txt_contact_tel").notify("กรุณาตรวจสอบเบอร์โทรศัพท์ติดต่อ", "info");
        if(typeof comp == 'undefined' ) comp = $("#txt_contact_tel");
      }

    }

  }
  if($("#txt_contact_email").val() != ""){
    if(!validateEmail($("#txt_contact_email").val())) {
      $("#txt_contact_email").notify("อีเมล์ไม่ถูกต้อง", "warn");
      flag = false;
    }
  }

  if($("#sel_pmt_opt").val() == undefined){
     $("#sel_pmt_opt").notify("กรุณาเลือกช่องทางการรับค่าตอบแทน", "warn");
     flag = false;
  }
  else{
    if($("#sel_pmt_opt").val() == '1'){ // bank
      if($("#sel_bank").val() == undefined){
         $("#sel_bank").notify("กรุณาเลือกกรอกข้อมูลธนาคาร หรือเลือกกรอกข้อมูลพร้อมเพย์", "warn");
         flag = false;
      }
      else{
        if($("#sel_bank").val() == "OTH"){
          if($("#txt_pmt_bank_other").val() == ""){
            $("#txt_pmt_bank_other").notify("กรุณาระบุชื่อธนาคารอื่น", "warn");
            flag = false;
          }
        }
          if($("#txt_pmt_bank_acc_code").val() == ""){
            $("#txt_pmt_bank_acc_code").notify("กรุณากรอกข้อมูลเลขที่บัญชี", "warn");
            flag = false;
          }
          if($("#txt_pmt_bank_acc_name").val() == ""){
            $("#txt_pmt_bank_acc_name").notify("กรุณากรอกข้อมูลชื่อบัญชี", "warn");
            flag = false;
          }
       }
    }
    else if($("#sel_pmt_opt").val() == '2'){ // promptpay
      if($("#txt_pmt_promptpay_id_card").val() == "" && $("#txt_pmt_promptpay_tel").val() == ""){
        flag = false;
        $("#txt_pmt_promptpay_id_card").notify("กรุณาเลือกกรอกข้อมูลพร้อมเพย์", "warn");
        $("#txt_pmt_promptpay_tel").notify("กรุณาเลือกกรอกข้อมูลพร้อมเพย์", "warn");
      }
      if($("#txt_pmt_promptpay_id_card").val() != "" && $("#txt_pmt_promptpay_id_card").val().length < 13){
        flag = false;
        $("#txt_pmt_promptpay_id_card").notify("เลขที่บัตรประชาชนไม่ถูกต้อง (ไม่ครบ 13 หลัก)", "warn");
      }
      if($("#txt_pmt_promptpay_tel").val() != "" && $("#txt_pmt_promptpay_tel").val().length < 10){
        flag = false;
        $("#txt_pmt_promptpay_tel").notify("หมายเลขโทรศัพท์มือถือไม่ถูกต้อง ", "warn");
      }
      else if($("#txt_pmt_promptpay_tel").val() != "" && $("#txt_pmt_promptpay_tel").val().length == 10){
        var prefix_tel2 = $("#txt_pmt_promptpay_tel").val().substring(0, 2);
        if(prefix_tel2 == "02"){
          flag = false;
          $("#txt_pmt_promptpay_tel").notify("กรุณาตรวจสอบเบอร์โทรศัพท์มือถือที่ใช้กับพร้อมเพย์", "info");
          if(typeof comp == 'undefined' ) comp = $("#txt_pmt_promptpay_tel");
        }
      }


    }
  }

  return flag;
}


</script>
