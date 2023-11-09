<?
include_once("../in_auth.php");
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$group_id = isset($_GET["group_id"])?urldecode($_GET["group_id"]):"";
$is_online = isset($_GET["is_online"])?urldecode($_GET["is_online"]):"";

include_once("../in_db_conn.php");

      $query = "SELECT v.uid as pid, v.collect_date,
      v.contact_tel, v.contact_email, v.contact_lineid, v.contact_other,
      v.pmt_bank,v.pmt_bank_other, v.pmt_bank_acc_code, v.pmt_bank_acc_name,
      v.pmt_promptpay_id_card, v.pmt_promptpay_tel,
      v.name,v.sur_name, v.address, v.dob, v.initials, v.consent_version
      FROM x_z202009_covid_visit_g$group_id as v
      WHERE v.uid = ?
      ";

//echo "$uid/ $query" ;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('s',$uid);
      if($stmt->execute()){
        $stmt->bind_result($pid,$visit_date,$contact_tel, $contact_email, $contact_lineid, $contact_other,
        $pmt_bank, $pmt_bank_other, $pmt_bank_acc_code, $pmt_bank_acc_name, $pmt_promptpay_id_card, $pmt_promptpay_tel,
        $name, $sur_name, $address, $dob, $initials, $consent_version );

        if ($stmt->fetch()) {

        }// while

      }
      else{
        echo $stmt->error;
      }
      $stmt->close();

include_once("COVID/x_covid_contact.php");

?>
<div class="mb-2 px-2 py-4" >
  <button id="btn_save_contact" class=" btn btn-primary form-control " type="button">
    <i class="fa fa-file-alt fa-lg" ></i> บันทึกข้อมูลติดต่อ
  </button>
</div>


<div class="my-4 px-2 py-4" style="background-color:#C8F1FF;">

  <div class="row my-2">
    <div class="col-sm-1"></div>
     <div class="col-sm-8">
       <div><u><b>ข้อมูลส่วนตัว</b></u></div>
       <div class="row my-2">
          <div class="col-sm-3">

            <label for="txt_name">ชื่อ:</label>
            <input type="text" id="txt_name" class="form-control form-control-sm ">
          </div>
          <div class="col-sm-4">

            <label for="txt_name">นามสกุล:</label>
            <input type="text" id="txt_sur_name" class="form-control form-control-sm ">
          </div>

          <div class="col-sm-2">

            <label for="txt_name">ชื่อย่อ 4อักษร:</label>
            <input type="text" id="txt_initials" class="form-control form-control-sm " maxlength="4">
          </div>
          <div class="col-sm-3">
            <label for="txt_dob">วันเดือนปีเกิด:</label>
            <input type="text" id="txt_dob" class="form-control form-control-sm " disabled>
          </div>
        </div>
        <div class="row my-2">
           <div class="col-sm-12">
             <label for="txt_address">ที่อยู่:</label>
             <textarea id="txt_address" rows="2" class="form-control" placeholder="กรุณากรอกชื่ออาคาร ชื่อชุมชน ตำบล/แขวง อำเภอ/เขต และจังหวัด"><? echo $address; ?></textarea>
           </div>
         </div>

         <div class="row my-2">
            <div class="col-sm-6">
              <label for="txt_consent_version">Consent Version:</label>
              <input type="text" id="txt_consent_version" class="form-control form-control-sm ">
            </div>
            <div class="col-sm-6">
            </div>
          </div>

     </div>
    <div class="col-sm-1"></div>
  </div>
  <div class="mb-2 px-2 py-4" >
    <button id="btn_save_personal" class=" btn btn-primary form-control " type="button">
      <i class="fa fa-file-alt fa-lg" ></i> บันทึก ข้อมูลส่วนตัว
    </button>
  </div>

</div>

<script>

$("#txt_contact_tel").val('<? echo $contact_tel; ?>') ;
$("#txt_contact_email").val('<? echo $contact_email; ?>') ;
$("#txt_contact_lineid").val('<? echo $contact_lineid; ?>') ;
$("#txt_contact_other").val('<? echo $contact_other; ?>') ;
//$("#txt_contact_other").val(json_encode('<? echo $contact_other; ?>')) ;

$("#sel_bank").val('<? echo $pmt_bank; ?>') ;
$("#txt_pmt_bank_other").val('<? echo $pmt_bank_other; ?>') ;
$("#txt_pmt_bank_acc_code").val('<? echo $pmt_bank_acc_code; ?>') ;
$("#txt_pmt_bank_acc_name").val('<? echo $pmt_bank_acc_name; ?>') ;
$("#txt_pmt_promptpay_id_card").val('<? echo $pmt_promptpay_id_card; ?>') ;
$("#txt_pmt_promptpay_tel").val('<? echo $pmt_promptpay_tel; ?>') ;
$("#txt_name").val('<? echo $name; ?>') ;
$("#txt_sur_name").val('<? echo $sur_name; ?>') ;
$("#txt_initials").val('<? echo $initials; ?>') ;
$("#txt_dob").val(changeToThaiDate('<? echo $dob; ?>')) ;
$("#txt_consent_version").val('<? echo $consent_version; ?>') ;
//$("#txt_address").val('<? echo $address; ?>') ;

$("#sel_pmt_opt").hide();
$(".div-pmt-opt").show();



$("#btn_save_contact").hide();
$("#btn_save_personal").hide();

$(document).ready(function(){

  <?
if($s_section_id == "DATA" || $s_id == "P20032"){
  echo '
  $("#btn_save_contact").show();
  $("#btn_save_personal").show();
  ';
}
  ?>

  $("#btn_save_contact").click(function(){
     saveContact_covid();
  }); // saveContact_covid
  $("#btn_save_personal").click(function(){
     savePersonal_covid();
  }); // savePersonal_covid

});



function saveContact_covid(){
  if(!checkContactInfo()){
    return;
  }

  var lst_data_obj=[];
  lst_data_obj.push({name:"contact_tel", value:$("#txt_contact_tel").val()});
  lst_data_obj.push({name:"contact_email", value:$("#txt_contact_email").val()});
  lst_data_obj.push({name:"contact_lineid", value:$("#txt_contact_lineid").val()});
  lst_data_obj.push({name:"contact_other", value:$("#txt_contact_other").val()});

  lst_data_obj.push({name:"pmt_bank", value:$("#sel_bank").val()});
  lst_data_obj.push({name:"pmt_bank_other", value:$("#txt_pmt_bank_other").val()});
  lst_data_obj.push({name:"pmt_bank_acc_code", value:$("#txt_pmt_bank_acc_code").val()});
  lst_data_obj.push({name:"pmt_bank_acc_name", value:$("#txt_pmt_bank_acc_name").val()});
  lst_data_obj.push({name:"pmt_promptpay_id_card", value:$("#txt_pmt_promptpay_id_card").val()});
  lst_data_obj.push({name:"pmt_promptpay_tel", value:$("#txt_pmt_promptpay_tel").val()});

      var aData = {
        u_mode:"update_contact_data",
        uid:'<? echo $uid; ?>',
        group_id:'<? echo $group_id; ?>',
        visit_date:'<? echo $visit_date; ?>',
        is_tc:"Y",
        lst_data:lst_data_obj
      };
      save_data_ajax(aData,"w_proj_covid19/db_covid.php",savePersonal_covid_Complete);

}

function savePersonal_covid(){
  var flag = true;

        if($("#txt_name").val().trim() == ""){
          flag = false;
          $("#txt_name").notify("ดำเนินต่อไม่ได้ เนื่องจากกรอกข้อมูลไม่ครบ", "info");
        }

        if($("#txt_address").val().trim() == ""){
          flag = false;
          $("#txt_address").notify("ดำเนินต่อไม่ได้ เนื่องจากกรอกข้อมูลไม่ครบ", "info");
        }

        if($("#txt_initials").val().trim() == ""){
          flag = false;
          $("#txt_initials").notify("ดำเนินต่อไม่ได้ เนื่องจากกรอกข้อมูลไม่ครบ", "info");
        }

  if(!flag){
    return;
  }

  var lst_data_obj=[];
  lst_data_obj.push({name:"name", value:$("#txt_name").val()});
  lst_data_obj.push({name:"initials", value:$("#txt_initials").val()});
  lst_data_obj.push({name:"address", value:$("#txt_address").val()});

  lst_data_obj.push({name:"sur_name", value:$("#txt_sur_name").val()});
  lst_data_obj.push({name:"consent_version", value:$("#txt_consent_version").val()});
  <?
if($is_online == "1"){
  echo '
  lst_data_obj.push({name:"consent_voice_rec_name", value:$("#txt_initials").val()});
  lst_data_obj.push({name:"consent_video_rec_name", value:$("#txt_initials").val()});
  ';
}
  ?>


//  lst_data_obj.push({name:"dob", value:$("#txt_dob").val()});


      var aData = {
                u_mode:"update_contact_data",
                uid:'<? echo $uid; ?>',
                group_id:'<? echo $group_id; ?>',
                visit_date:'<? echo $visit_date; ?>',
                is_tc:"Y",
                lst_data:lst_data_obj
      };
      save_data_ajax(aData,"w_proj_covid19/db_covid.php",savePersonal_covid_Complete);

}

function savePersonal_covid_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $.notify("บันทึกข้อมูลเรียบร้อยแล้ว", "info");
  }
}



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




      if($("#sel_bank").val() != undefined){
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

  return flag;
}

</script>
