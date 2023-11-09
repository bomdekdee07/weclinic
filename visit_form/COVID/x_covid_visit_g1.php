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
    $cur_date = (new DateTime())->format('Y-m-d');

    if($is_backdate == "N"){
      if($cur_date != $visit_date){
        header( "location: ../info/invalid.php?e=e2" );
        exit(0);
      }
    }

  }
  else{
  //  echo "count: ".count($arr);
    header( "location: ../info/invalid.php?e=e1" );
    exit(0);
  }

}




$form_id = "covid_visit_g1";
$form_name = "COVID Group 1 ";

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
/*
if($open_link != "Y"){ // open link by staff
  include_once("../in_auth_db.php");

  if(!isset($auth["data"]) && !isset($auth["log"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }


}
else{ // open link by patient
   $visit_date = (new DateTime())->format('Y-m-d');
}
*/

$initJSForm .= '$("#btn_save").hide();';

$option_showhide = "
// show/hide question
shData['fearworry6-1'] = {dtype:'radio',
show_q:'fearworry6_1,fearworry6_2'};
shData['fearworry6-2'] = {dtype:'radio',
hide_q:'fearworry6_1,fearworry6_2'};

";


?>

<div class="container" id="divCovid_Consent">

  <?
  include_once("COVID/x_covid_consent.php");
  ?>
</div>

<script>
 var flag_update_contact = 0;
</script>

<div class="my-4" id="divCovid_Form">


    <div><center><b><h4>แบบสอบถามชุดที่ 1 ผู้ติดเชื้อโควิด-19 และผู้เข้าเกณฑ์สอบสวนโรค</h4></b></center></div>
    <div class="row my-2">
       <div class="col-sm-1"></div>
       <div class="col-sm-10">
         <div><center><u><b>คำชี้แจง</b></u></center></div>
         <div>
           <p>สถาบันเพื่อการวิจัยและนวัตกรรมด้านเอชไอวี (Institute of HIV Research and Innovation (IHRI)) ร่วมกับภาคีเครือข่ายที่เกี่ยวข้อง มีความประสงค์จะรวบรวมข้อมูลเกี่ยวกับความคิดเห็นและประสบการณ์การรับบริการสุขภาพและการอยู่ร่วมกับครอบครัว ชุมชน และสังคมของท่าน เพื่อนำไปใช้ในการพัฒนาคุณภาพบริการสุขภาพและการอยู่ร่วมกันในครอบครัว ชุมชน และสังคมเพื่อลดการตีตราและเลือกปฏิบัติที่เกี่ยวกับโรคติดเชื้อไวรัสโคโรน่าสายพันธุ์ใหม่ หรือ โควิด-19 (COVID-19) จึงขอความร่วมมือจากท่านในการตอบแบบสอบถามซึ่งจะใช้เวลาประมาณ 20 นาที</p>
           <p>การตอบแบบสอบถามนี้จะไม่กระทบต่อการรับบริการทางด้านสุขภาพในอนาคตของท่านแต่อย่างใด คำตอบของท่านจะเป็นความลับ และไม่มีการถามข้อมูลส่วนบุคคลที่จะสามารถระบุตัวตนของท่านได้</p>
         </div>
       </div>
       <div class="col-sm-1"></div>
     </div>

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
      </div>
     </center>
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

<div id="div_covid_contact" class="px-1 pt-2 pb-1 my-4" style="background-color:#C8F1FF;display:None;" >
<?
include_once("COVID/x_covid_contact.php");
?>
<div class="my-4">
  <button id="btn_complete_contact" class=" btn btn-primary form-control " type="button">
    <i class="fa fa-file-alt fa-lg" ></i> ส่งแบบฟอร์ม
  </button>
</div>
</div>

<?
include_once("COVID/inc_covid_js.php");
?>
