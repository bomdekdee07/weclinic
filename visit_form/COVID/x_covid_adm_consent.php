<?
include_once("../in_auth.php");

$prefix_pid = "TRC063-00-";
include_once("../function/in_fn_link.php");
include_once("../in_db_conn.php");

$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";

$query = "SELECT v.uid as pid, screen_id,
collect_date, name_title, name, sur_name, consent_version, address,
consent_voice_rec, consent_voice_rec_name,
consent_video_rec, consent_video_rec_name,
consent_accept, consent_staff_check,
ps.s_name
FROM x_z202009_covid_visit_g$group_id as v
LEFT JOIN p_staff as ps ON (binary v.consent_staff_check = ps.s_id)
WHERE v.uid = ?
";

//echo " $uid/ $query" ;
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $uid);
if($stmt->execute()){
  $stmt->bind_result($pid, $screen_id,
  $collect_date, $name_title, $name,$sur_name, $consent_version, $address,
  $consent_voice_rec,$consent_voice_rec_name,
  $consent_video_rec,$consent_video_rec_name,
  $consent_accept, $consent_staff_check, $research_name);
  $arr_data = array();
  if ($stmt->fetch()) {
  }// if

}
else{
  echo $stmt->error;
}
$stmt->close();



?>
<div class="container" id="divCovid_Consent">
  <div class="my-3">
  <h4>
    <b>เอกสารแสดงความยินยอมเข้าร่วมโครงการสำหรับอาสาสมัคร </b>
    <? echo " [$prefix_pid"."$pid]"; ?>
  </h4>
</div>
<div class="px-1 pt-2 pb-1" style="background-color:#C8F1FF;">
  <p><b>ชื่อโครงการวิจัย</b> การศึกษาเพื่อแก้ไขการตีตราและการเลือกปฏิบัติต่อชุมชนที่ได้รับผลกระทบจาก โควิด-19 ผ่านการเตรียมชุมชนและการสื่อสารสาธารณะในประเทศไทย  </p>

</div>


  <div class="row mt-2">
     <div class="col-sm-3">
       <label for="consent_date">วันให้คำยินยอม:</label>
       <input type="text" id="txt_consent_date" class="form-control form-control-sm data-consent" value="">
     </div>
     <div class="col-sm-9">
     </div>
 </div>
 <div class="mt-3">


   <div class="row">
   <div class="col-sm-4">
      ข้าพเจ้า
     <div class="form-check form-check-inline mx-2">
       <input class="form-check-input name_title-opt data-consent" type="radio" name="name_title" id="name_title-MR" value="MR">
       <label class="form-check-label" for="name_title-MR"><b>นาย</b></label>
     </div>
     <div class="form-check form-check-inline mx-2">
       <input class="form-check-input name_title-opt data-consent" type="radio" name="name_title" id="name_title-MS" value="MS">
       <label class="form-check-label" for="name_title-MS"><b>นาง</b></label>
     </div>
     <div class="form-check form-check-inline mx-2">
       <input class="form-check-input name_title-opt data-consent" type="radio" name="name_title" id="name_title-MRS" value="MRS">
       <label class="form-check-label" for="name_title-MRS"><b>นางสาว</b></label>
     </div>
   </div>
   <div class="col-sm-8">

   </div>
   </div>
 </div>


 <div class="row">
   <div class="col-sm-4">
     <label for="name">ชื่อ</label>
     <input type="text" id="txt_name" class="form-control" placeholder="กรอกชื่อ">

   </div>
   <div class="col-sm-8">
     <label for="sur_name">นามสกุล</label>
     <input type="text" id="txt_sur_name" class="form-control" placeholder="กรอกนามสกุล">

   </div>


 </div>
 <div>
   <label for="address">ที่อยู่</label>
   <textarea id="txt_address" rows="2" class="form-control data-consent" placeholder="ที่อยู่">
   </textarea>
 </div>
 <div class="mt-2">
   <p>
รายละเอียดจากเอกสารข้อมูลสำหรับผู้เข้าร่วมโครงการวิจัยวิจัยที่แนบมา ฉบับวันที่ <input type="text" id="txt_consent_version"  placeholder="Consent Version" disabled>  และข้าพเจ้ายินยอมเข้าร่วมโครงการวิจัยโดยสมัครใจ   </p>
   <p>
ทั้งนี้ก่อนที่จะลงนามในเอกสารแสดงความยินยอมให้ทำการวิจัยนี้ ข้าพเจ้าได้รับการอธิบายจากผู้วิจัยถึงวัตถุประสงค์ของการวิจัย ระยะเวลาของการทำวิจัย วิธีการวิจัย อันตราย หรือความเสี่ยงที่อาจเกิดขึ้น  รวมทั้งประโยชน์ที่จะเกิดขึ้นจากการวิจัย และแนวทางอื่นอย่างละเอียด ข้าพเจ้ามีเวลาและโอกาสเพียงพอในการซักถามข้อสงสัยจนมีความเข้าใจอย่างดีแล้ว โดยผู้วิจัยได้ตอบคำถาม ต่างๆ ด้วยความเต็มใจไม่ปิดบังซ่อนเร้นจนข้าพเจ้าพอใจ   </p>
   <p>
ข้าพเจ้ามีสิทธิที่จะถอนตัวออกจากการเข้าร่วมในโครงการวิจัยเมื่อใดก็ได้ โดยไม่จำเป็นต้องแจ้งเหตุผล และการถอนตัวการเข้าร่วมโครงการวิจัยนี้ จะไม่มีผลต่อการรักษาโรคหรือสิทธิอื่นๆ ที่ข้าพเจ้าจะพึงได้รับต่อไป   </p>
   <p>
ผู้วิจัยรับรองว่าจะเก็บข้อมูลส่วนตัวของข้าพเจ้าเป็นความลับ และจะเปิดเผยได้เฉพาะเมื่อได้รับการยินยอมจากข้าพเจ้าเท่านั้น บุคคลอื่นในนามของ คณะกรรมการจริยธรรมการวิจัยในมนุษย์ คณะแพทยศาสตร์ จุฬาลงกรณ์มหาวิทยาลัย, เจ้าหน้าที่ของโครงการวิจัย, ผู้ตรวจสอบงานวิจัย, และหน่วยงานกำกับดูแลอื่น ๆ ในท้องถิ่น จะได้รับอนุญาตให้เข้ามาตรวจและประมวลข้อมูลของข้าพเจ้า ทั้งนี้จะต้องกระทำไปเพื่อวัตถุประสงค์ในการตรวจสอบความถูกต้องของข้อมูลเท่านั้น    </p>
   <p>
ผู้วิจัยรับรองว่าจะไม่มีการเก็บข้อมูลใด ๆ เพิ่มเติม หลังจากที่ข้าพเจ้าขอยกเลิกการเข้าร่วมโครงการวิจัย แต่สามารถใช้ข้อมูลของข้าพเจ้าในโครงการวิจัยนี้ได้จนถึงวันที่ถอนตัวออกจากโครงการวิจัย    </p>
   <p>
ข้าพเจ้ารับทราบว่าข้อมูลในการวิจัย เป็นข้อมูลที่จะใช้เพื่อวัตถุประสงค์ทางวิชาการ และการวิจัยเท่านั้น ข้าพเจ้าได้ตระหนักว่าข้อมูลที่เปิดเผยตัวตนของข้าพเจ้า เช่น ชื่อ และที่อยู่ จะไม่มีการบันทึกในชุดข้อมูลที่จะนำมาวิเคราะห์ผลการศึกษา หรือในการตีพิมพ์ผลการวิจัย    </p>
   <p>

<div class="my-4">
<u><b>การขอความยินยอมในการบันทึกเสียงขณะสัมภาษณ์</b></u>
<div class="mt-2">
  <div class="form-check mx-2">
    <input class="form-check-input rdo_voice_consent data-consent" data-id="y" type="radio" name="consent_voice_rec"  id="consent_voice_rec-Y" value="Y">
    <label class="form-check-label" for="consent_voice_rec-Y">
      <b>ใช่</b>
      <input type="text" id="txt_consent_voice_rec_name_y" size="15" maxlength="4" placeholder="กรุณาใส่ชื่อย่อของท่าน" class="short-name-sign consent_voice  data-consent"> ข้าพเจ้าอนุญาตให้บันทึกเสียงของข้าพเจ้าขณะสัมภาษณ์ และคัดลอก ภาพเคลื่อนไหว ข้าพเจ้าเข้าใจว่าจะไม่มีการระบุชื่อของข้าพเจ้าในการบันทึกภาพเคลื่อนไหว
    </label>
  </div>
</div>
<div>
  <div class="form-check mx-2">
    <input class="form-check-input rdo_voice_consent data-consent" data-id="n" type="radio" name="consent_voice_rec" id="consent_voice_rec-N" value="N">
    <label class="form-check-label" for="consent_voice_rec-N">
      <b>ไม่ใช่</b>
      <input type="text" id="txt_consent_voice_rec_name_n" size="15" maxlength="4" placeholder="กรุณาใส่ชื่อย่อของท่าน" class="short-name-sign consent_voice  data-consent"> ข้าพเจ้าไม่อนุญาตให้บันทึกเสียงของข้าพเจ้าขณะสัมภาษณ์
    </label>
  </div>
</div>
</div>

<div class="my-4">
<u><b>การขอความยินยอมในการบันทึกภาพเคลื่อนไหวขณะสัมภาษณ์</b></u>
<div class="mt-2">
  <div class="form-check mx-2">
    <input class="form-check-input rdo_video_consent data-consent" data-id="y" type="radio"  name="consent_video_rec" id="consent_video_rec-Y" value="Y">
    <label class="form-check-label" for="consent_video_rec-Y">
      <b>ใช่</b>
      <input type="text" id="txt_consent_video_rec_name_y" size="15" maxlength="4" placeholder="กรุณาใส่ชื่อย่อของท่าน" class="short-name-sign consent_video data-consent"> ข้าพเจ้าอนุญาตให้บันทึก<u><b>ภาพเคลื่อนไหว</u></b>ของข้าพเจ้าขณะสัมภาษณ์ และคัดลอกข้อความที่ให้สัมภาษณ์ได้  ข้าพเจ้าเข้าใจว่าจะไม่มีการระบุชื่อของข้าพเจ้าในการคัดลอกคำสัมภาษณ์
    </label>
  </div>
</div>
<div>
  <div class="form-check mx-2">
    <input class="form-check-input rdo_video_consent data-consent" data-id="n" type="radio" name="consent_video_rec" id="consent_video_rec-N" value="N">
    <label class="form-check-label" for="consent_video_rec-N">
      <b>ไม่ใช่</b>
      <input type="text" id="txt_consent_video_rec_name_n" size="15" maxlength="4" placeholder="กรุณาใส่ชื่อย่อของท่าน" class="short-name-sign consent_video data-consent"> ข้าพเจ้าไม่อนุญาตให้บันทึก<u><b>ภาพเคลื่อนไหว</u></b>ของข้าพเจ้าขณะสัมภาษณ์
    </label>
  </div>
</div>
</div>

<p>ข้าพเจ้าได้อ่าน (หรือมีเจ้าหน้าที่โครงการอ่านให้ฟัง) ข้อความข้างต้นและมีความเข้าใจดีทุกประการแล้ว และข้าพเจ้ายินดีเข้าร่วมในการวิจัยด้วยความเต็มใจ จึงได้ลงนามในเอกสารแสดงความยินยอมนี้ </p>
<div class="my-2">
<u><b>กรณีลงนามในเอกสาร</u></b> <br>
......................................................................................ลงนามอาสาสมัคร<br>
(....................................................................................) ชื่ออาสาสมัครตัวบรรจง<br>
วันที่ ................เดือน....................................พ.ศ.............................<br>



   <p>
ข้าพเจ้าได้ตระหนักว่าข้อมูลในการวิจัยรวมถึงข้อมูลทางการแพทย์ของข้าพเจ้าที่ไม่มีการเปิดเผยชื่อจะผ่านกระบวนการต่างๆ เช่น การเก็บข้อมูล การบันทึกข้อมูลในแบบบันทึกและในคอมพิวเตอร์ การตรวจสอบ การวิเคราะห์ และการรายงานข้อมูลเพื่อวัตถุประสงค์ทางวิชาการ รวมทั้งการใช้ข้อมูลทางการแพทย์ในอนาคตเท่านั้น
</div>

<div class="my-4 px-2 py-4" style="background-color:#C8F1FF;">

<u><b>กรณีลงนามอาสาสมัครแบบออนไลน์ </u></b> <br>
<b>อาสาสมัคร</b>
<div class="form-check form-check-inline mx-4">
  <input class="form-check-input" type="radio" name="consent_accept" id="consent_accept-Y" value="Y">
  <label class="form-check-label" for="consent_accept-Y"><b>ยินยอมเข้าร่วมโครงการ</b></label>
</div>
<div class="form-check form-check-inline mx-4">
  <input class="form-check-input" type="radio" name="consent_accept" id="consent_accept-N" value="N">
  <label class="form-check-label" for="consent_accept-N"><b>ไม่ยินยอมเข้าร่วมโครงการ</b></label>
</div>

<div class="mt-2"><u><span id="name2"></span></u><br>

วันที่ <span class="consent_date_th"></span>
</div>

<br><br>
<b>ผู้ทำวิจัยลงนามแบบออนไลน์</b>
<div id="div_researcher_sign" class="my-1 div-researcher">
  <div class="form-group form-check">
    <label class="form-check-label">
      <input class="form-check-input data-consent" type="checkbox" id="consent_check" value="Y" checked> ลงนามผู้ทำวิจัย <span id="researcher_name"><? echo "<u>$research_name</u> [$consent_staff_check]"; ?></span>
    </label>
  </div>
  <div>วันที่ <span class="consent_date_th"></span></div>
</div>

<div id="div_researcher_pend" class="my-1 div-researcher">
  <button id="btn_check_consent" class=" btn btn-primary form-control " type="button">
    <i class="fa fa-file-signature fa-lg" ></i>กดเพื่อ ลงนามผู้ทำวิจัย <? echo "$s_name [$s_id]";?>
  </button>
  <div>วันที่ <span class="consent_date_th"></span></div>
</div>

</div>


<div class="text-secondary my-3">
 Study to address stigma and discrimination against COVID-19 affected communities through community preparation and public communication in Thailand_ Thai Informed Consent Form Version <span id= "txt_consent_ver"> 1.3 dated 1 November 2020 </span>
</div>


</div>

</div>

<script>

initData();

$(".div-researcher").hide();

<?
if($consent_staff_check != ""){
  echo '$("#div_researcher_sign").show();';
}
else{
  echo '$("#div_researcher_pend").show();';
}
?>
$(document).ready(function(){

  $("#btn_check_consent").click(function(){
      checkCovidConsent();
  }); //btn_check_consent()

});

function initData(){
  var collect_date = changeToThaiDate('<? echo $collect_date; ?>');

  $("#txt_consent_date").val(collect_date);

  $(".consent_date_th").html(collect_date);

  $("#name_title-<? echo $name_title; ?>").prop("checked", true);
  $("#txt_name").val("<? echo $name; ?>");
  $("#txt_sur_name").val("<? echo $sur_name; ?>");
  $("#name2").html("<u><? echo "$name $sur_name" ; ?></u>");
  $("#txt_consent_version").val("<? echo $consent_version; ?>");



  <?
     if($consent_version == "1 สิงหาคม 2563"){
         echo '$("#txt_consent_ver").html("1.1 dated 1 June 2020");'; 
     }
  ?>



  $("#txt_address").val("<? echo $address; ?>");

  $("#consent_voice_rec-<? echo $consent_voice_rec; ?>").prop("checked", true);
  $("#consent_video_rec-<? echo $consent_video_rec; ?>").prop("checked", true);

  $("#txt_consent_voice_rec_name_<? echo strtolower($consent_voice_rec); ?>").val("<? echo $consent_voice_rec_name; ?>");
  $("#txt_consent_video_rec_name_<? echo strtolower($consent_video_rec); ?>").val("<? echo $consent_video_rec_name; ?>");
  $("#consent_accept-<? echo $consent_accept; ?>").prop("checked", true);



  //$(".data-consent").prop("disabled", true);

}


function checkCovidConsent(){
  var aData = {
            u_mode:"consent_staff_check",
            pid:'<? echo $pid; ?>',
            group_id:cur_covid_group_id
  };
  save_data_ajax(aData,"w_proj_covid19/db_covid.php",checkCovidConsentComplete);
}


function checkCovidConsentComplete(flagSave, rtnDataAjax, aData){
  if(flagSave){
    $(".div-researcher").hide();
    $("#div_researcher_sign").show();
    $("#researcher_name").html("<u>"+rtnDataAjax.staff_name+"</u> ");
    $("#c<? echo $pid; ?>").html("<? echo $s_id; ?>");
  }
}


</script>
