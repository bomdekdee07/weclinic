<?

// Sero Converse (ผลเลือดเปลี่ยน)

include_once("inc_param.php");

$form_id = "sdart_hos";
$form_name = "Sero Converse (ผลเลือดเปลี่ยน)";
$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$before_save_function = ""; // trigger before save function
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php

if($open_link != "Y"){
  include_once("../in_auth_db.php");
  if(!isset($auth["data"]) && !isset($auth["log"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
}



//show lab result
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function

$div_lab = "";

$hiv_result_txt = "-";
$ct_result_txt = "-";
$ng_result_txt = "-";
$tpha_result_txt = "-";
$rpr_result_txt = "-";

$opt_hiv = "N";
$opt_ct = "N";
$opt_ng = "N";
$opt_syphilis = "N";

$flag_referral = "N";
$txt_first_save = "";

$row_count = 0;


$query = "SELECT x.lasthiv_result, l.collect_date, ul.clinic_id, c.clinic_name,
l.hiv_result, l.ct_pool_result, l.ng_pool_result, l.tpha_result, l.rpr_result, l.rpr_titer

FROM p_project_uid_list as ul, p_clinic as c, x_lab_result as l
LEFT JOIN x_sero_con as x ON (l.uid=x.uid AND x.collect_date=?)
WHERE ul.clinic_id=c.clinic_id AND ul.uid=l.uid AND l.uid = ? AND l.collect_date <> ?
ORDER BY l.collect_date DESC LIMIT 1
";
//echo "$uid, $visit_date / $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("sss", $visit_date, $uid, $visit_date);
         if($stmt->execute()){
           $stmt->bind_result( $x_lasthiv_result,
           $l_collect_date, $l_clinic_id, $l_clinic_name,
           $hiv_result, $ct_pool_result, $ng_pool_result, $tpha_result, $rpr_result, $rpr_titer

         );


           if ($stmt->fetch()) {
             $row_count = $stmt->num_rows;

             if($hiv_result !== NULL){
               if($hiv_result == "NR")  $hiv_result_txt = "ผลเป็นลบ (Non-reactive)";
               else if($hiv_result == "R") {
                 $hiv_result_txt = "ผลเป็นบวก (Reactive)";
                 $opt_hiv = "Y";
                 $flag_referral = "Y";
               }
               else if($hiv_result == "I")  $hiv_result_txt = "สรุปผลไม่ได้ (Inconclusive)";
               else if($hiv_result == "NT")  $hiv_result_txt = "ไม่ได้ตรวจ";
             }

             if($ct_pool_result !== NULL){
               if($ct_pool_result == "ND")  $ct_result_txt = "Not Detected";
               else if($ct_pool_result == "D"){
                 $opt_ct = "Y";
                 $flag_referral = "Y";
                 $ct_result_txt = "Detected";
               }
               else if($ct_pool_result == "I")  $ct_result_txt = "Inconclusive";
             }
             if($ng_pool_result !== NULL){
               if($ng_pool_result == "ND")  $ng_result_txt = "Not Detected";
               else if($ng_pool_result == "D"){
                 $opt_ng = "Y";
                 $flag_referral = "Y";
                 $ng_result_txt = "Detected";
               }
               else if($ng_pool_result == "I")  $ng_result_txt = "Inconclusive";
             }

             $syphilis_check = 0;
             if($tpha_result !== NULL){
               if($tpha_result == "NR")  $tpha_result_txt = "ผลเป็นลบ (Non-reactive)";
               else if($tpha_result == "R"){
                 $tpha_result_txt = "ผลเป็นบวก (Reactive)";
                 $syphilis_check += 1;
               }
               else if($tpha_result == "I")  $tpha_result_txt = "สรุปผลไม่ได้ (Inconclusive)";
               else if($tpha_result == "NT")  $tpha_result_txt = "ไม่ได้ตรวจ";
             }
             if($rpr_result !== NULL){
               if($rpr_result == "NR")  $rpr_result_txt = "ผลเป็นลบ (Non-reactive)";
               else if($rpr_result == "R"){
                 $rpr_result_txt = "ผลเป็นบวก (Reactive) ";
                 $rpr_result_txt .= "Titer $rpr_titer";

                 $syphilis_check += 1;
               }
               else if($rpr_result == "I")  $rpr_result_txt = "สรุปผลไม่ได้ (Inconclusive)";
               else if($rpr_result == "NT")  $rpr_result_txt = "ไม่ได้ตรวจ";
             }

             if($syphilis_check == 2){
               $opt_syphilis="Y";
               $flag_referral = "Y";
             }
           }

          $stmt->close();
         }
//echo "row: $row_count <br>";


$div_all = "";

if(isset($l_collect_date)){
  $l_collect_date = changeToThaiDate($l_collect_date);
  $div_lab = "
  <div class='px-4 py-1'>
    <div><h3>ผลตรวจ LAB ครั้งล่าสุดก่อนนัดหมายนี้จากระบบ weClinic</h3></div>
    <div><b>วันที่ตรวจ: <u>$l_collect_date</u>  สถานที่ตรวจ: <u>$l_clinic_name</u> </b></div>

    <div class='mt-2'>
     <table>
      <tr><td class='px-3'>
     HIV: $hiv_result_txt <br>
     CT: $ct_result_txt <br>
     NG: $ng_result_txt <br>
     </td><td class='px-3' valign='top'>
     TPHA: $tpha_result_txt <br>
     RPR: $rpr_result_txt <br>
      </td></tr>
     </table>

    </div>

  </div>
  ";

}
else{
  $div_lab = "ไม่มีผล Lab ก่อนหน้านัดหมายนี้ในระบบ weClinic";
}

  $div_all = "<center>$div_lab</center>";

echo $div_all;


$form_top .= '
<div class="mb-1 ">
  <button class="btn btn-primary btn-sm btn-block" type="button" onclick="openFormLog(\'sex_partner\');">
    <i class="fa fa-heart fa-lg" ></i> ประวัติการมีเพศสัมพันธ์ของคนไข้
  </button>
</div>' ;

$form_bottom .= '
<div class="mt-1 mb-4">
  <button class="btn btn-primary btn-sm btn-block" type="button" onclick="openFormLog(\'sex_partner\');">
    <i class="fa fa-heart fa-lg" ></i> ประวัติการมีเพศสัมพันธ์ของคนไข้
  </button>
</div>' ;

if($x_lasthiv_result == NULL){
   $initJSForm .= '$("#lasthiv_date").val("'.$l_collect_date.'");';
   $initJSForm .= '$("#lasthiv_place-'.$l_clinic_id.'").prop("checked",true);';
   $initJSForm .= '$("#lasthiv_result-'.$hiv_result.'").prop("checked",true);';
}

$initJSForm .= '
$("#t_lasthiv_method").hide();
$("#t_lastneg_method").hide();
$("#t_lastneg").hide();
';

$section_lastneg_q="lastneg_date,lastneg_place";
$section_lastneg_t="lastneg";

// last hiv check
$section_lasthiv_method_q="lasthiv_method,lasthiv_alere,lasthiv_colloidal_wantai,lasthiv_achitect,lasthiv_colloidaldevice,lasthiv_advia,lasthiv_aptima,lasthiv_cephied,lasthiv_oth1,lasthiv_oth2";
$section_lasthiv_method_t="lasthiv_method";

$section_lasthiv_method_detail_q="lasthiv_alere_date,lasthiv_alere_result";
$section_lasthiv_method_detail_q.="lasthiv_colloidal_wantai_date,lasthiv_colloidal_wantai_result";
$section_lasthiv_method_detail_q.="lasthiv_sdbioline_date,lasthiv_sdbioline_result";
$section_lasthiv_method_detail_q.="lasthiv_achitect_date,lasthiv_achitect_result";
$section_lasthiv_method_detail_q.="lasthiv_colloidaldevice_date,lasthiv_colloidaldevice_result";
$section_lasthiv_method_detail_q.="lasthiv_advia_date,lasthiv_advia_result";
$section_lasthiv_method_detail_q.="lasthiv_aptima_date,lasthiv_aptima_result";
$section_lasthiv_method_detail_q.="lasthiv_cephied_date,lasthiv_cephied_result";
$section_lasthiv_method_detail_q.="lasthiv_oth1_date,lasthiv_oth1_result";
$section_lasthiv_method_detail_q.="lasthiv_oth2_date,lasthiv_oth2_result";

// last negative
$section_lastneg_method_q="lastneg_method,lastneg_alere,lastneg_colloidal_wantai,lastneg_achitect,lastneg_colloidaldevice,lastneg_advia,lastneg_aptima,lastneg_cephied,lastneg_oth1,lastneg_oth2";
$section_lastneg_method_t="lastneg_method";

$section_lastneg_method_detail_q="lastneg_alere_date";
$section_lastneg_method_detail_q.="lastneg_colloidal_wantai_date";
$section_lastneg_method_detail_q.="lastneg_sdbioline_date";
$section_lastneg_method_detail_q.="lastneg_achitect_date";
$section_lastneg_method_detail_q.="lastneg_colloidaldevice_date";
$section_lastneg_method_detail_q.="lastneg_advia_date";
$section_lastneg_method_detail_q.="lastneg_aptima_date";
$section_lastneg_method_detail_q.="lastneg_cephied_date,lastneg_cephied_result";
$section_lasthiv_method_detail_q.="lastneg_oth1_date,lastneg_oth1_result";
$section_lasthiv_method_detail_q.="lastneg_oth2_date,lastneg_oth2_result";

$option_showhide = "
shData['lasthiv_place-SBK'] = {dtype:'radio',hide_q:'$section_lasthiv_method_q,$section_lasthiv_method_detail_q',hide_t:'$section_lasthiv_method_t'};
shData['lasthiv_place-SPT'] = {dtype:'radio',hide_q:'$section_lasthiv_method_q,$section_lasthiv_method_detail_q',hide_t:'$section_lasthiv_method_t'};
shData['lasthiv_place-RBK'] = {dtype:'radio',hide_q:'$section_lasthiv_method_q,$section_lasthiv_method_detail_q',hide_t:'$section_lasthiv_method_t'};
shData['lasthiv_place-RHY'] = {dtype:'radio',hide_q:'$section_lasthiv_method_q,$section_lasthiv_method_detail_q',hide_t:'$section_lasthiv_method_t'};
shData['lasthiv_place-MCM'] = {dtype:'radio',hide_q:'$section_lasthiv_method_q,$section_lasthiv_method_detail_q',hide_t:'$section_lasthiv_method_t'};
shData['lasthiv_place-CCM'] = {dtype:'radio',hide_q:'$section_lasthiv_method_q,$section_lasthiv_method_detail_q',hide_t:'$section_lasthiv_method_t'};

shData['lasthiv_place-HOS'] = {dtype:'radio',show_q:'lasthiv_method'};
shData['lasthiv_place-OTH'] = {dtype:'radio',show_q:'lasthiv_method'};

shData['lasthiv_method-Y'] = {dtype:'radio',
show_q:'$section_lasthiv_method_q',show_t:'$section_lasthiv_method_t'};
shData['lasthiv_method-N'] = {dtype:'radio',
hide_q:'$section_lasthiv_method_detail_q',hide_t:'$section_lasthiv_method_t'};

shData['lasthiv_alere-Y'] = {dtype:'radio',show_q:'lasthiv_alere_date,lasthiv_alere_result'};
shData['lasthiv_alere-N'] = {dtype:'radio',hide_q:'lasthiv_alere_date,lasthiv_alere_result'};

shData['lasthiv_colloidal_wantai-Y'] = {dtype:'radio',show_q:'lasthiv_colloidal_wantai_date,lasthiv_colloidal_wantai_result'};
shData['lasthiv_colloidal_wantai-N'] = {dtype:'radio',hide_q:'lasthiv_colloidal_wantai_date,lasthiv_colloidal_wantai_result'};

shData['lasthiv_sdbioline-Y'] = {dtype:'radio',show_q:'lasthiv_sdbioline_date,lasthiv_sdbioline_result'};
shData['lasthiv_sdbioline-N'] = {dtype:'radio',hide_q:'lasthiv_sdbioline_date,lasthiv_sdbioline_result'};

shData['lasthiv_achitect-Y'] = {dtype:'radio',show_q:'lasthiv_achitect_date,lasthiv_achitect_result'};
shData['lasthiv_achitect-N'] = {dtype:'radio',hide_q:'lasthiv_achitect_date,lasthiv_achitect_result'};

shData['lasthiv_colloidaldevice-Y'] = {dtype:'radio',show_q:'lasthiv_colloidaldevice_date,lasthiv_colloidaldevice_result'};
shData['lasthiv_colloidaldevice-N'] = {dtype:'radio',hide_q:'lasthiv_colloidaldevice_date,lasthiv_colloidaldevice_result'};

shData['lasthiv_advia-Y'] = {dtype:'radio',show_q:'lasthiv_advia_date,lasthiv_advia_result'};
shData['lasthiv_advia-N'] = {dtype:'radio',hide_q:'lasthiv_advia_date,lasthiv_advia_result'};

shData['lasthiv_aptima-Y'] = {dtype:'radio',show_q:'lasthiv_aptima_date,lasthiv_aptima_result'};
shData['lasthiv_aptima-N'] = {dtype:'radio',hide_q:'lasthiv_aptima_date,lasthiv_aptima_result'};

shData['lasthiv_cephied-Y'] = {dtype:'radio',show_q:'lasthiv_cephied_date,lasthiv_cephied_result'};
shData['lasthiv_cephied-N'] = {dtype:'radio',hide_q:'lasthiv_cephied_date,lasthiv_cephied_result'};

shData['lasthiv_oth1-Y'] = {dtype:'radio',show_q:'lasthiv_oth1_date,lasthiv_oth1_result'};
shData['lasthiv_oth1-N'] = {dtype:'radio',hide_q:'lasthiv_oth1_date,lasthiv_oth1_result'};

shData['lasthiv_oth2-Y'] = {dtype:'radio',show_q:'lasthiv_oth2_date,lasthiv_oth2_result'};
shData['lasthiv_oth2-N'] = {dtype:'radio',hide_q:'lasthiv_oth2_date,lasthiv_oth2_result'};


shData['lasthiv_result-NR'] = {dtype:'radio',
hide_q:'$section_lastneg_q,$section_lastneg_method_q,$section_lastneg_method_detail_q',
hide_t:'$section_lastneg_t,$section_lastneg_method_t'};

shData['lasthiv_result-R'] = {dtype:'radio',
show_q:'$section_lastneg_q',
show_t:'$section_lastneg_t'};
shData['lasthiv_result-I'] = {dtype:'radio',
show_q:'$section_lastneg_q',
show_t:'$section_lastneg_t'};
shData['lasthiv_result-NS'] = {dtype:'radio',
show_q:'$section_lastneg_q',
show_t:'$section_lastneg_t'};


shData['lastneg_place-SBK'] = {dtype:'radio',hide_q:'$section_lastneg_method_q,$section_lastneg_method_detail_q',hide_t:'$section_lastneg_method_t'};
shData['lastneg_place-SPT'] = {dtype:'radio',hide_q:'$section_lastneg_method_q,$section_lastneg_method_detail_q',hide_t:'$section_lastneg_method_t'};
shData['lastneg_place-RBK'] = {dtype:'radio',hide_q:'$section_lastneg_method_q,$section_lastneg_method_detail_q',hide_t:'$section_lastneg_method_t'};
shData['lastneg_place-RHY'] = {dtype:'radio',hide_q:'$section_lastneg_method_q,$section_lastneg_method_detail_q',hide_t:'$section_lastneg_method_t'};
shData['lastneg_place-MCM'] = {dtype:'radio',hide_q:'$section_lastneg_method_q,$section_lastneg_method_detail_q',hide_t:'$section_lastneg_method_t'};
shData['lastneg_place-CCM'] = {dtype:'radio',hide_q:'$section_lastneg_method_q,$section_lastneg_method_detail_q',hide_t:'$section_lastneg_method_t'};

shData['lastneg_place-HOS'] = {dtype:'radio',show_q:'lastneg_method'};
shData['lastneg_place-OTH'] = {dtype:'radio',show_q:'lastneg_method'};

shData['lastneg_method-Y'] = {dtype:'radio',
show_q:'$section_lastneg_method_q',show_t:'$section_lastneg_method_t'};
shData['lastneg_method-N'] = {dtype:'radio',
hide_q:'$section_lastneg_method_detail_q',hide_t:'$section_lastneg_method_t'};

shData['lastneg_alere-Y'] = {dtype:'radio',show_q:'lastneg_alere_date'};
shData['lastneg_alere-N'] = {dtype:'radio',hide_q:'lastneg_alere_date'};

shData['lastneg_colloidal_wantai-Y'] = {dtype:'radio',show_q:'lastneg_colloidal_wantai_date'};
shData['lastneg_colloidal_wantai-N'] = {dtype:'radio',hide_q:'lastneg_colloidal_wantai_date'};

shData['lastneg_sdbioline-Y'] = {dtype:'radio',show_q:'lastneg_sdbioline_date'};
shData['lastneg_sdbioline-N'] = {dtype:'radio',hide_q:'lastneg_sdbioline_date'};

shData['lastneg_achitect-Y'] = {dtype:'radio',show_q:'lastneg_achitect_date'};
shData['lastneg_achitect-N'] = {dtype:'radio',hide_q:'lastneg_achitect_date'};

shData['lastneg_colloidaldevice-Y'] = {dtype:'radio',show_q:'lastneg_colloidaldevice_date'};
shData['lastneg_colloidaldevice-N'] = {dtype:'radio',hide_q:'lastneg_colloidaldevice_date'};

shData['lastneg_advia-Y'] = {dtype:'radio',show_q:'lastneg_advia_date'};
shData['lastneg_advia-N'] = {dtype:'radio',hide_q:'lastneg_advia_date'};

shData['lastneg_aptima-Y'] = {dtype:'radio',show_q:'lastneg_aptima_date'};
shData['lastneg_aptima-N'] = {dtype:'radio',hide_q:'lastneg_aptima_date'};

shData['lastneg_cephied-Y'] = {dtype:'radio',show_q:'lastneg_cephied_date,lastneg_cephied_result'};
shData['lastneg_cephied-N'] = {dtype:'radio',hide_q:'lastneg_cephied_date,lastneg_cephied_result'};

shData['lastneg_oth1-Y'] = {dtype:'radio',show_q:'lastneg_oth1_date,lastneg_oth1_result'};
shData['lastneg_oth1-N'] = {dtype:'radio',hide_q:'lastneg_oth1_date,lastneg_oth1_result'};

shData['lastneg_oth2-Y'] = {dtype:'radio',show_q:'lastneg_oth2_date,lastneg_oth2_result'};
shData['lastneg_oth2-N'] = {dtype:'radio',hide_q:'lastneg_oth2_date,lastneg_oth2_result'};


shData['prep-Y'] = {dtype:'radio',
show_q:'prep_lastpill,prepstop,prep_pill'};
shData['prep-N'] = {dtype:'radio',
hide_q:'prep_lastpill,prepstop,prepstop_date,prep_pill'};

shData['prepstop-Y'] = {dtype:'radio',
show_q:'prepstop_date'};
shData['prepstop-N'] = {dtype:'radio',
hide_q:'prepstop_date'};

";
 //$option_showhide = "";
$before_save_function = "
  if($('#lasthiv_result-NR').is(':checked')){
    //setLastNeg();
  }
";

include_once("f_form_main.php");

?>


<script>
$(document).ready(function(){

  $("#lasthiv_result-NR").click(function(){ // ไม่ส่งต่อ
    //setLastNeg();
  });

});

function setLastNeg(){ // set last negative result same as last result
  /*
  $("#lastneg_date").val($("#lasthiv_date").val());
  $("#lastneg_place_hos").val($("#lasthiv_place_hos").val());
  $("#lastneg_place_oth").val($("#lasthiv_place_oth").val());

  $("#lastneg_place-RBK").prop("checked", $("#lasthiv_place-RBK").is(':checked'));
  $("#lastneg_place-RHY").prop("checked", $("#lasthiv_place-RHY").is(':checked'));
  $("#lastneg_place-SBK").prop("checked", $("#lasthiv_place-SBK").is(':checked'));
  $("#lastneg_place-SPT").prop("checked", $("#lasthiv_place-SPT").is(':checked'));
  $("#lastneg_place-MCM").prop("checked", $("#lasthiv_place-MCM").is(':checked'));
  $("#lastneg_place-CCM").prop("checked", $("#lasthiv_place-CCM").is(':checked'));
  $("#lastneg_place-HOS").prop("checked", $("#lasthiv_place-HOS").is(':checked'));
  $("#lastneg_place-OTH").prop("checked", $("#lasthiv_place-OTH").is(':checked'));

  $("#lastneg_alere_date").val($("#lasthiv_alere_date").val());
  $("#lastneg_colloidal_wantai_date").val($("#lasthiv_colloidal_wantai_date").val());
  $("#lastneg_sdbioline_date").val($("#lasthiv_sdbioline_date").val());
  $("#lastneg_achitect_date").val($("#lasthiv_achitect_date").val());
  $("#lastneg_colloidaldevice_date").val($("#lasthiv_colloidaldevice_date").val());
  $("#lastneg_advia_date").val($("#lasthiv_advia_date").val());
  $("#lastneg_aptima_date").val($("#lasthiv_aptima_date").val());
  $("#lastneg_cephied_date").val($("#lasthiv_cephied_date").val());
  $("#lastneg_oth1_date").val($("#lasthiv_oth1_date").val());
  $("#lastneg_oth2_date").val($("#lasthiv_oth2_date").val());

  $("#lastneg_alere-Y").prop("checked", $("#lasthiv_alere-Y").is(':checked'));
  $("#lastneg_colloidal_wantai-Y").prop("checked", $("#lasthiv_colloidal_wantai-Y").is(':checked'));
  $("#lastneg_sdbioline-Y").prop("checked", $("#lasthiv_sdbioline-Y").is(':checked'));
  $("#lastneg_achitect-Y").prop("checked", $("#lasthiv_achitect-Y").is(':checked'));
  $("#lastneg_colloidaldevice-Y").prop("checked", $("#lasthiv_colloidaldevice-Y").is(':checked'));
  $("#lastneg_advia-Y").prop("checked", $("#lasthiv_advia-Y").is(':checked'));
  $("#lastneg_aptima-Y").prop("checked", $("#lasthiv_aptima-Y").is(':checked'));
  $("#lastneg_cephied-Y").prop("checked", $("#lasthiv_cephied-Y").is(':checked'));
  $("#lastneg_oth1-Y").prop("checked", $("#lasthiv_oth1-Y").is(':checked'));
  $("#lastneg_oth2-Y").prop("checked", $("#lasthiv_oth2-Y").is(':checked'));

  $("#lastneg_alere-N").prop("checked", $("#lasthiv_alere-N").is(':checked'));
  $("#lastneg_colloidal_wantai-N").prop("checked", $("#lasthiv_colloidal_wantai-N").is(':checked'));
  $("#lastneg_sdbioline-N").prop("checked", $("#lasthiv_sdbioline-N").is(':checked'));
  $("#lastneg_achitect-N").prop("checked", $("#lasthiv_achitect-N").is(':checked'));
  $("#lastneg_colloidaldevice-N").prop("checked", $("#lasthiv_colloidaldevice-N").is(':checked'));
  $("#lastneg_advia-N").prop("checked", $("#lasthiv_advia-N").is(':checked'));
  $("#lastneg_aptima-N").prop("checked", $("#lasthiv_aptima-N").is(':checked'));
  $("#lastneg_cephied-N").prop("checked", $("#lasthiv_cephied-N").is(':checked'));
  $("#lastneg_oth1-N").prop("checked", $("#lasthiv_oth1-N").is(':checked'));
  $("#lastneg_oth2-N").prop("checked", $("#lasthiv_oth2-N").is(':checked'));

  $('#lastneg_date').data("is_show",'1');
  $('#lastneg_place').data("is_show",'1');

  $('#lastneg_alere_date').data("is_show",'1');
  $('#lastneg_colloidal_wantai_date').data("is_show",'1');
  $('#lastneg_sdbioline_date').data("is_show",'1');
  $('#lastneg_achitect_date').data("is_show",'1');
  $('#lastneg_colloidaldevice_date').data("is_show",'1');
  $('#lastneg_advia_date').data("is_show",'1');
  $('#lastneg_aptima_date').data("is_show",'1');
  $('#lastneg_cephied_date').data("is_show",'1');
  $('#lastneg_oth1_date').data("is_show",'1');
  $('#lastneg_oth2_date').data("is_show",'1');

  $('#lastneg_alere').data("is_show",'1');
  $('#lastneg_colloidal_wantai').data("is_show",'1');
  $('#lastneg_sdbioline').data("is_show",'1');
  $('#lastneg_achitect').data("is_show",'1');
  $('#lastneg_colloidaldevice').data("is_show",'1');
  $('#lastneg_advia').data("is_show",'1');
  $('#lastneg_aptima').data("is_show",'1');
  $('#lastneg_cephied').data("is_show",'1');
  $('#lastneg_oth1').data("is_show",'1');
  $('#lastneg_oth2').data("is_show",'1');
*/

  //alert("setlastneg");
}

</script>
