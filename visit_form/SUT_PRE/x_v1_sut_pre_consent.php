<?


include_once("inc_param.php");

//echo "$uid/$visit_date/$visit_id/$project_id/$group_id/$open_link/$form_id";


$form_id = "v1_sut_pre_consent";
$form_name = "STANDUP-TEEN <b>Consent</b> ";

$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$before_save_function = ""; // trigger before save function
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php

$js_sut_form ='';
$screen_date_open_link = $visit_date; // for open_link=Y

if($open_link != "Y"){ // open link by staff
  include_once("../in_auth_db.php");

  if(!isset($auth["data"]) && !isset($auth["log"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }

  $js_sut_form .='

  $("#divSUT_Consent").show();

  ';

  include_once("../in_db_conn.php");
          $query = "SELECT age
                     FROM x_sut_pre_screen
                     WHERE uid = ? AND collect_date=?
          ";

                   $stmt = $mysqli->prepare($query);
                   $stmt->bind_param("ss", $uid, $visit_date);
                   if($stmt->execute()){
                     $stmt->bind_result($sut_age);

                     if ($stmt->fetch()) {

                     }
                   }
              $stmt->close();

    $sut_age = substr($sut_age,0,2) ;

    $js_sut_form .='
    $("#divSaveData").hide();
    $("#age'.$sut_age.'").addClass("text-primary");
    $("#age'.$sut_age.'").css("font-weight", "bold");
    $(".consent-opt").prop("disabled", true);
    $(".consent-age").prop("disabled", true);

    ';

}


/*
$initJSForm .= '$("#div-studygroup-1").hide();';
$initJSForm .= '$("#div-studygroup-2").hide();';
$initJSForm .= '$("#div-studygroup-3").hide();';
$initJSForm .= '$("#div-studygroup-4").hide();';
*/

$option_showhide = "

";

$js_sut_form .= "
if($('#consent').val() == 'Y'){
  $('#consent-Y').prop('checked', true);
}
else if($('#consent').val() == 'N'){
  $('#consent-N').prop('checked', true);
}

if($('#age').val() != ''){
  $('#age-'+$('#age').val()).prop('checked', true);
}


$('#btn_save').hide();
$('#divSaveData').hide();


";

?>
<div class="container" id="divSUT_Consent">

  <div class="my-3">
  <h4><b>เอกสารแสดงความยินยอมเข้าร่วม
โครงการ ในการเชิญชวนอาสาสมัครทดลองใช้ชุดตรวจเอชไอวีด้วยตนเอง</b>
</h4>
</div>
<div class="px-1 pt-2 pb-1" style="background-color:#eee;">
  <p>
      <b>การวิจัยเรื่อง</b>
      “โครงการเพื่อประเมินผลของการใช้ชุดตรวจเอชไอวีด้วยตนเองต่อการใช้ยาต้านเอชไอวีเพื่อการป้องกันการติดเชื้อก่อนการสัมผัสและการคงอยู่ในระบบบริการในวัยรุ่นตอนปลายที่เป็นชายที่มีเพศสัมพันธ์กับชายและสาวประเภทสองชาวไทย”
  </p>
  <p class="mt-1">
      “Study to determine the effect of HIV self-testing on the uptake of and retention in pre-exposure prophylaxis service among older adolescent men who have sex with men and transgender women” (STANDUP-TEEN)
  </p>
</div>


  <div class="row mt-2">
     <div class="col-sm-3">
       <label for="consent_date">วันให้คำยินยอม:</label>
       <input type="text" id="consent_date" class="form-control form-control-sm" value="<? echo $visit_date;?>" disabled>
     </div>
     <div class="col-sm-9">
     </div>
 </div>

 <div class="mt-2">
   <p>
ข้าพเจ้าได้ฟังรายละเอียดจากเจ้าหน้าที่ในโครงการที่ได้รับมอบหมายในการเชิญชวนอาสาสมัครเข้าร่วมการทดลองใช้ชุดตรวจเอชไอวีด้วยตนเอง และข้าพเจ้ายินยอมเข้าร่วมโครงการโดยสมัครใจ
   </p>
   <p>
ทั้งนี้ก่อนที่จะให้การยินยอมให้ทำการวิจัยนี้ ข้าพเจ้าได้รับการอธิบายจากผู้วิจัยถึงวัตถุประสงค์ของการวิจัย ระยะเวลาของการทำวิจัย วิธีการวิจัย อันตราย หรืออาการที่อาจเกิดขึ้นจากการวิจัย รวมทั้งประโยชน์ที่จะเกิดขึ้นจากการวิจัย และแนวทางรักษาโดยวิธีอื่นอย่างละเอียด ข้าพเจ้ามีเวลาและโอกาสเพียงพอในการซักถามข้อสงสัยจนมีความเข้าใจอย่างดีแล้ว โดยผู้วิจัยได้ตอบคำถามต่างๆ ด้วยความเต็มใจไม่ปิดบังซ่อนเร้นจนข้าพเจ้าพอใจ
   </p>
   <p>
ข้าพเจ้ารับทราบจากผู้วิจัยว่าหากเกิดอันตรายใดๆ จากการวิจัยดังกล่าว ผู้เข้าร่วมวิจัยจะได้รับการรักษาพยาบาลโดยไม่เสียค่าใช้จ่าย
   </p>
   <p>
ข้าพเจ้ามีสิทธิที่จะบอกเลิกเข้าร่วมในโครงการวิจัยเมื่อใดก็ได้ โดยไม่จำเป็นต้องแจ้งเหตุผล และการบอกเลิกการเข้าร่วมการวิจัยนี้จะไม่มีผลต่อการรักษาโรคหรือสิทธิอื่นๆ ที่ข้าพเจ้าจะพึงได้รับต่อไป
   </p>
   <p>
ผู้วิจัยรับรองว่าจะเก็บข้อมูลส่วนตัวของข้าพเจ้าเป็นความลับ และจะเปิดเผยได้เฉพาะเมื่อได้รับการยินยอมจากข้าพเจ้าเท่านั้น บุคคลอื่นในนามของผู้สนับสนุนการวิจัย คณะกรรมการพิจารณาจริยธรรมการวิจัยในคน สำนักงานคณะกรรมการอาหารและยาอาจได้รับอนุญาตให้เข้ามาตรวจและประมวลข้อมูลของผู้เข้าร่วมวิจัย ทั้งนี้จะต้องกระทำไปเพื่อตรวจสอบความถูกต้องของข้อมูลเท่านั้น โดยการตกลงที่จะเข้าร่วมการศึกษานี้ข้าพเจ้าได้ให้คำยินยอมที่จะให้มีการตรวจสอบข้อมูลประวัติทางการแพทย์ของผู้เข้าร่วมวิจัยได้
   </p>
   <p>
ผู้วิจัยรับรองว่าจะไม่มีการเก็บข้อมูลใดๆ ของผู้เข้าร่วมวิจัยเพิ่มเติม หลังจากที่ข้าพเจ้าขอยกเลิกการเข้าร่วมโครงการวิจัยและต้องการให้ทำลายเอกสารและ/หรือ ตัวอย่างที่ใช้ตรวจสอบทั้งหมดที่สามารถสืบค้นถึงตัวข้าพเจ้าได้
   </p>
   <p>
ข้าพเจ้าเข้าใจว่า  ข้าพเจ้ามีสิทธิ์ที่จะตรวจสอบหรือแก้ไขข้อมูลส่วนตัวของข้าพเจ้าและสามารถยกเลิกการให้สิทธิในการใช้ข้อมูลส่วนตัวของข้าพเจ้าได้ โดยต้องแจ้งให้ผู้วิจัยรับทราบ
   </p>
   <p>
ข้าพเจ้าได้ตระหนักว่าข้อมูลในการวิจัยรวมถึงข้อมูลทางการแพทย์ของข้าพเจ้าที่ไม่มีการเปิดเผยชื่อจะผ่านกระบวนการต่างๆ เช่น การเก็บข้อมูล การบันทึกข้อมูลในแบบบันทึกและในคอมพิวเตอร์ การตรวจสอบ การวิเคราะห์ และการรายงานข้อมูลเพื่อวัตถุประสงค์ทางวิชาการ รวมทั้งการใช้ข้อมูลทางการแพทย์ในอนาคตเท่านั้น
   </p>
   <p>
ข้าพเจ้าได้อ่านข้อความข้างต้นและมีความเข้าใจดีทุกประการแล้ว  ยินดีที่จะเข้าร่วมในการวิจัยด้วยความเต็มใจ และสนใจที่จะเข้าร่วมโครงการวิจัยในส่วนของการทดลองใช้ชุดตรวจเอชไอวีด้วยตนเองหรือไม่
   </p>
   <div class="my-2">

        <center>
        <div class="form-check form-check-inline mx-4">
          <input class="form-check-input consent-opt" type="radio" name="q_consent" id="consent-Y" value="Y" >
          <label class="form-check-label" for="consent-Y"><b>ยินยอม</b></label>
        </div>
        <div class="form-check form-check-inline mx-4">
          <input class="form-check-input consent-opt" type="radio" name="q_consent" id="consent-N" value="N" >
          <label class="form-check-label" for="consent-N"><b>ไม่ยินยอม</b></label>
        </div>

      </center>

  </div>

</div>
  <div id="div_sut_consent2" class="px-1 pt-2 pb-1 my-4" style="background-color:#C8F1FF;">
    <p>
เนื่องจากในการเชิญชวนอาสาสมัครเข้าร่วมการทดลองใช้ชุดตรวจเอชไอวีด้วยตนเองนั้นอาสาสมัครจะไม่ถูกสอบถามข้อมูลเกี่ยวกับข้อมูลส่วนตัว เช่น ชื่อ-นามสกุล และไม่ต้องแสดงบัตรประจำตัวประชาชนเพื่อเป็นการเก็บความลับของอาสาสมัคร ทางทีมผู้วิจัยจึงขอให้อาสาสมัครที่เข้าร่วมโครงการเป็นผู้แจ้งอายุของอาสาสมัครเอง อาสาสมัครกรุณาเลือกช่วงอายุของคุณในช่องที่กำหนด
</p>
   <div class="my-2">
    <center>
      <div class="form-check form-check-inline mx-4">
        <input class="form-check-input consent-age" type="radio" name="q_pid_age" id="age-15" value="15">
        <label class="form-check-label" for="age-15"><span id="age15" class="">15</span></label>
      </div>
     <div class="form-check form-check-inline mx-4">
       <input class="form-check-input consent-age" type="radio" name="q_pid_age" id="age-16" value="16">
       <label class="form-check-label" for="age-16"><span id="age16" class="">16</span></label>
     </div>
     <div class="form-check form-check-inline mx-4">
       <input class="form-check-input consent-age" type="radio" name="q_pid_age" id="age-17" value="17">
       <label class="form-check-label" for="age-17"><span id="age17" class="">17</span></label>
     </div>
     <div class="form-check form-check-inline mx-4">
       <input class="form-check-input consent-age" type="radio" name="q_pid_age" id="age-18" value="18">
       <label class="form-check-label" for="age-18"><span id="age18" class="">18</span></label>
     </div>
     <div class="form-check form-check-inline mx-4">
       <input class="form-check-input consent-age" type="radio" name="q_pid_age" id="age-19" value="19">
       <label class="form-check-label" for="age-19"><span id="age19" class="">19</span></label>
     </div>
   </center>
   </div>

   <p>
เนื่องจากอาสาสมัครที่จะเข้าร่วมโครงการวิจัยนี้เป็นกลุ่มชายที่มีเพศสัมพันธ์กับชายและสาวประเภทสอง ที่มีความเสี่ยงต่อการได้รับเชื้อเอชไอวี อายุ 15 ปี ถึง ต่ำกว่า 18 ปีบริบูรณ์ ข้อมูลดังกล่าวเป็นข้อมูลเกี่ยวกับสุขภาวะทางเพศ และเป็นความลับทางด้านสุขภาพส่วนบุคคล การมารับบริการเพื่อตรวจเอชไอวีและการป้องกันการติดเชื้อเอชไอวีเป็นไปตามมาตรฐานการป้องกันการติดเชื้อเอชไอวีและเป็นประโยชน์ต่อตัวอาสาสมัครด้วย <b>จึงขอยกเว้นการขอยินยอมเข้าร่วมโครงการวิจัยจากผู้ปกครอง</b>
   </p>
</div>





</div>

<?
include_once("f_form_main.php");
?>


<script>

$("#consent_date").val(changeToThaiDate('<? echo $visit_date; ?>'));
<? echo $js_sut_form; ?>

</script>
