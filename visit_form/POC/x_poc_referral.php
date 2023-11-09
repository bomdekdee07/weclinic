<?

// Point of care Referral ส่งต่อการรักษา แบบแยกประเภทโรค 4 ตัว

include_once("inc_param.php");

$form_id = "poc_referral";
$form_name = "Point of care Referral (ส่งต่อการรักษา)";
$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php

if($open_link != "Y"){
  include_once("../in_auth_db.php");
  if(!isset($auth["data"]) && !isset($auth["log"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
}

$initJSForm .= '
$("#t_referral_seperate").hide();
$("#t_referral_title_hiv").hide();
$("#t_referral_title_ct").hide();
$("#t_referral_title_ng").hide();
$("#t_referral_title_syphilis").hide();

';

$section_hiv_q="referral_date_hiv,referral_place_hiv,referral_province_hiv,referral_note_hiv";
$section_hiv_t="referral_title_hiv";

$section_ct_q="referral_date_ct,referral_place_ct,referral_province_ct,referral_note_ct";
$section_ct_t="referral_title_ct";

$section_ng_q="referral_date_ng,referral_place_ng,referral_province_ng,referral_note_ng";
$section_ng_t="referral_title_ng";

$section_syphilis_q="referral_date_syphilis,referral_place_syphilis,referral_province_syphilis,referral_note_syphilis";
$section_syphilis_t="referral_title_syphilis";

$section_main_q = "referral_opt_hiv,referral_opt_ct,referral_opt_ng,referral_opt_syphilis";
$section_main_t = "referral_seperate";


$js_opt_referral_Y = "
  $('#referral_opt-Y').prop('checked',true);
  $('#referral_opt_y_note').prop('disabled',false);

  $('#q_referral_opt_hiv').show();
  $('#q_referral_opt_ct').show();
  $('#q_referral_opt_ng').show();
  $('#q_referral_opt_syphilis').show();

  $('#q_referral_opt_hiv').data('is_show','1');
  $('#q_referral_opt_ct').data('is_show','1');
  $('#q_referral_opt_ng').data('is_show','1');
  $('#q_referral_opt_syphilis').data('is_show','1');
";
$js_opt_referral_N = "
  //$('#div-referral_opt-Y').hide();
";

$js_opt_hiv_referral_Y = "
  $('#referral_opt_hiv-Y').prop('checked',true);

  $('#t_referral_title_hiv').show();
  $('#q_referral_date_hiv').show();
  $('#q_referral_place_hiv').show();
  $('#q_referral_province_hiv').show();
  $('#q_referral_note_hiv').show();

  $('#q_referral_date_hiv').data('is_show','1');
  $('#q_referral_place_hiv').data('is_show','1');
  $('#q_referral_province_hiv').data('is_show','1');
  $('#q_referral_note_hiv').data('is_show','1');
";
$js_opt_hiv_referral_N = "
  $('#div-referral_opt_hiv-Y').hide();
";

$js_opt_ct_referral_Y = "
  $('#referral_opt_ct-Y').prop('checked',true);

  $('#t_referral_title_ct').show();
  $('#q_referral_date_ct').show();
  $('#q_referral_place_ct').show();
  $('#q_referral_province_ct').show();
  $('#q_referral_note_ct').show();

  $('#q_referral_date_ct').data('is_show','1');
  $('#q_referral_place_ct').data('is_show','1');
  $('#q_referral_province_ct').data('is_show','1');
  $('#q_referral_note_ct').data('is_show','1');
";
$js_opt_ct_referral_N = "
  $('#div-referral_opt_ct-Y').hide();
";

$js_opt_ng_referral_Y = "
  $('#referral_opt_ng-Y').prop('checked',true);

  $('#t_referral_title_ng').show();
  $('#q_referral_date_ng').show();
  $('#q_referral_place_ng').show();
  $('#q_referral_province_ng').show();
  $('#q_referral_note_ng').show();

  $('#q_referral_date_ng').data('is_show','1');
  $('#q_referral_place_ng').data('is_show','1');
  $('#q_referral_province_ng').data('is_show','1');
  $('#q_referral_note_ng').data('is_show','1');
";
$js_opt_ng_referral_N = "
  $('#div-referral_opt_ng-Y').hide();
";

$js_opt_syphilis_referral_Y = "
  $('#referral_opt_syphilis-Y').prop('checked',true);

  $('#t_referral_title_syphilis').show();
  $('#q_referral_date_syphilis').show();
  $('#q_referral_place_syphilis').show();
  $('#q_referral_province_syphilis').show();
  $('#q_referral_note_syphilis').show();

  $('#q_referral_date_syphilis').data('is_show','1');
  $('#q_referral_place_syphilis').data('is_show','1');
  $('#q_referral_province_syphilis').data('is_show','1');
  $('#q_referral_note_syphilis').data('is_show','1');
";
$js_opt_syphilis_referral_N = "

";





$option_showhide = "
shData['referral_opt-Y'] = {dtype:'radio',
show_q:'$section_main_q',
show_t:'$section_main_t'};
shData['referral_opt-N'] = {dtype:'radio',
hide_q:'$section_main_q,$section_hiv_q,$section_ct_q,$section_ng_q,$section_syphilis_q',
hide_t:'$section_main_t,$section_hiv_t,$section_ct_t,$section_ng_t,$section_syphilis_t'};


shData['referral_opt_hiv-Y'] = {dtype:'radio',
show_q:'$section_hiv_q',
show_t:'$section_hiv_t'};


shData['referral_opt_hiv-N'] = {dtype:'radio',
hide_q:'$section_hiv_q',
hide_t:'$section_hiv_t'};

shData['referral_opt_ct-Y'] = {dtype:'radio',
show_q:'$section_ct_q',
show_t:'$section_ct_t'};

shData['referral_opt_ct-N'] = {dtype:'radio',
hide_q:'$section_ct_q',
hide_t:'$section_ct_t'};

shData['referral_opt_ng-Y'] = {dtype:'radio',
show_q:'$section_ng_q',
show_t:'$section_ng_t'};

shData['referral_opt_ng-N'] = {dtype:'radio',
hide_q:'$section_ng_q',
hide_t:'$section_ng_t'};

shData['referral_opt_syphilis-Y'] = {dtype:'radio',
show_q:'$section_syphilis_q',
show_t:'$section_syphilis_t'};

shData['referral_opt_syphilis-N'] = {dtype:'radio',
hide_q:'$section_syphilis_q',
hide_t:'$section_syphilis_t'};

";
 //$option_showhide = "";


//show lab result
include_once("../in_db_conn.php");

$div_lab = "";
$div_old_referral = "";

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


$query = "SELECT l.hiv_result, l.ct_pool_result, l.ng_pool_result, l.tpha_result, l.rpr_result, l.rpr_titer ,
r.referral_opt,
r.referral, r.referral_txt, r.referral_date, r.referral_place, r.referral_province, r.referral_note
FROM x_lab_result as l
LEFT JOIN x_referral as r ON (l.uid=r.uid AND l.collect_date=r.collect_date)
WHERE l.uid = ? AND l.collect_date=?
";
//echo "$uid, $visit_date / $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("ss", $uid, $visit_date);
         if($stmt->execute()){
           $stmt->bind_result($hiv_result, $ct_pool_result, $ng_pool_result, $tpha_result, $rpr_result, $rpr_titer,
           $referral_opt,
           $o_referral, $o_referral_txt, $o_referral_date, $o_referral_place, $o_referral_province, $o_referral_note
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


             // old referral data show
              if($o_referral !== NULL){

                if($o_referral == "Y"){
                  $div_old_referral .= "การส่งต่อ: ใช่ <br>";
                  $div_old_referral .= "วันที่ส่งต่อ: $o_referral_date <br>";
                  $div_old_referral .= "สถานที่: $o_referral_place <br>";
                  $div_old_referral .= "จังหวัด: $o_referral_province <br>";
                }
                else if($o_referral == "N") {
                  $div_old_referral .= "การส่งต่อ: ไม่ <br>";
                  $div_old_referral .= "เหตุผล: $o_referral_txt <br>";
                }
                else if($o_referral == "W") {
                  $div_old_referral .= "การส่งต่อ: รอการส่งต่อ <br>";
                }
                $div_old_referral .= "Note: $o_referral_note <br>";

              }


           }

          $stmt->close();
         }
//echo "row: $row_count <br>";

if($referral_opt == "" || $referral_opt === NULL){ // first referral input
  //echo "referral_opt is null $flag_referral/$opt_hiv/$opt_ct/$opt_ng/$opt_syphilis";

  $txt_first_save = "<br><h4><b><span class='text-danger'>ยังไม่ได้บันทึกข้อมูลในครั้งแรก</span></b></h4>";

  if($flag_referral == "Y") $initJSForm .= " $js_opt_referral_Y ";
  else $initJSForm .= " $js_opt_referral_N ";

  if($opt_hiv == "Y") $initJSForm .= " $js_opt_hiv_referral_Y "; 
  else $initJSForm .= " $js_opt_hiv_referral_N ";

  if($opt_ct == "Y") $initJSForm .= " $js_opt_ct_referral_Y ";
  else $initJSForm .= " $js_opt_ct_referral_N ";

  if($opt_ng == "Y") $initJSForm .= " $js_opt_ng_referral_Y ";
  else $initJSForm .= " $js_opt_ng_referral_N ";

  if($opt_syphilis == "Y") $initJSForm .= " $js_opt_syphilis_referral_Y ";
  else $initJSForm .= " $js_opt_syphilis_referral_N ";

}
else{
  //echo "referral_opt is not null";
}

$div_all = "";

$div_lab = "

<div class='px-4 py-1'>
  <div><h4>ผลตรวจ LAB [$visit_date]</h4></div>

  <div>
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

if($div_old_referral == ""){
  $div_all = "<center>$div_lab</center>";
}
else{
  $div_old_referral = "
     <div class='px-4 py-1 bg-warning'>
       <div><h4>ข้อมูลการส่งต่อที่มีเบื้องต้น</h4></div>
       <div>$div_old_referral</div>
     </div>
  ";

    $div_all = "
    <center>
      <table>
      <tr><td valign='top'>$div_lab</td><td valign='top'>$div_old_referral</td></tr>
      </table>$txt_first_save
    </center>
    ";
}




echo $div_all;


include_once("f_form_main.php");

?>


<script>
$(document).ready(function(){
  $("#referral_opt-N").click(function(){ // ไม่ส่งต่อ
    $("#referral_opt_n_note").val("ผลเป็นปกติ");
  });

  $("#referral_opt_hiv-N").click(function(){ // ไม่ส่งต่อ hiv
    $("#referral_opt_hiv_note").val("ผลเป็นปกติ");
  });
  $("#referral_opt_ct-N").click(function(){ // ไม่ส่งต่อ ct
    $("#referral_opt_ct_note").val("ผลเป็นปกติ");
  });
  $("#referral_opt_ng-N").click(function(){ // ไม่ส่งต่อ ng
    $("#referral_opt_ng_note").val("ผลเป็นปกติ");
  });
  $("#referral_opt_syphilis-N").click(function(){ // ไม่ส่งต่อ syphilis
    $("#referral_opt_syphilis_note").val("ผลเป็นปกติ");
  });
});


</script>
