<?
include_once("../in_auth.php");
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$group_id = isset($_GET["group_id"])?urldecode($_GET["group_id"]):"";

include_once("../in_db_conn.php");

      $query = "SELECT v.uid as pid,
      v.contact_tel, v.contact_email, v.contact_lineid, v.contact_other,
      v.pmt_bank,v.pmt_bank_other, v.pmt_bank_acc_code, v.pmt_bank_acc_name,
      v.pmt_promptpay_id_card, v.pmt_promptpay_tel,
      v.name, v.address, v.dob, v.initials
      FROM x_z202009_covid_visit_g$group_id as v
      WHERE v.uid = ?
      ";

//echo "$uid/ $query" ;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('s',$uid);
      if($stmt->execute()){
        $stmt->bind_result($pid,$contact_tel, $contact_email, $contact_lineid, $contact_other,
        $pmt_bank, $pmt_bank_other, $pmt_bank_acc_code, $pmt_bank_acc_name, $pmt_promptpay_id_card, $pmt_promptpay_tel,
        $name, $address, $dob, $initials );

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
          <div class="col-sm-6">

            <label for="txt_name">ชื่อ-นามสกุล:</label>
            <input type="text" id="txt_name" class="form-control form-control-sm ">
          </div>
          <div class="col-sm-2">

            <label for="txt_name">ชื่อย่อ 4อักษร:</label>
            <input type="text" id="txt_initials" class="form-control form-control-sm ">
          </div>
          <div class="col-sm-4">
            <label for="txt_dob">วันเดือนปีเกิด:</label>
            <input type="text" id="txt_dob" class="form-control form-control-sm ">
          </div>
        </div>
        <div class="row my-2">
           <div class="col-sm-12">
             <label for="txt_address">ที่อยู่:</label>
             <textarea id="txt_address" rows="2" class="form-control" placeholder="กรุณากรอกชื่ออาคาร ชื่อชุมชน ตำบล/แขวง อำเภอ/เขต และจังหวัด"><? echo $address; ?></textarea>
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
$("#txt_initials").val('<? echo $initials; ?>') ;
$("#txt_dob").val(changeToThaiDate('<? echo $dob; ?>')) ;
//$("#txt_address").val('<? echo $address; ?>') ;

$("#sel_pmt_opt").hide();
$(".div-pmt-opt").show();

$(document).ready(function(){
/*
  $("#btn_save_contact").click(function(){
     saveContact_covid();
  }); // saveContact_covid
  $("#btn_save_personal").click(function(){
     savePersonal_covid();
  }); // savePersonal_covid
*/
});



</script>
