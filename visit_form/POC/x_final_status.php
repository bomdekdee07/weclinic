<?

// สิ้นสุดโครงการ

include_once("inc_param.php");

$form_id = "final_status";
$form_name = "สิ้นสุดโครงการ (Final Status)";
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


include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function



$query = "SELECT ul.pid, ul.enroll_date, ul.proj_group_id, ul.clinic_id,
uv.visit_id, uv.visit_date, v.visit_name, c.clinic_name
FROM p_project_uid_list as ul, p_project_uid_visit as uv,
p_clinic as c, p_visit_list as v
WHERE ul.uid=uv.uid AND uv.proj_id=ul.proj_id
AND c.clinic_id = ul.clinic_id AND uv.visit_id=v.visit_id
AND uv.visit_status <> 0 AND ul.uid=? AND ul.proj_id=?
ORDER BY uv.visit_date, v.visit_order DESC LIMIT 1
";
//echo "$uid, $visit_date / $query";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $uid, $proj_id);
    if($stmt->execute()){
           $stmt->bind_result( $cur_pid, $cur_enroll_date, $cur_group_id, $cur_clinic_id,
           $cur_visit_id, $cur_visit_date, $cur_visit_name, $cur_clinic_name
         );
         if ($stmt->fetch()) {



         }
        $stmt->close();
    }



$div_data = "
<div class='px-4 py-1'>
  <div><h3>ข้อมูล UID </h3></div>
  <div><b>วันเข้าโครงการ: <u>$cur_enroll_date</u> กลุ่ม: $cur_group_id
  นัดหมายล่าสุด <u>$cur_visit_name</u> ($cur_visit_date) คลีนิค: $cur_clinic_name</b></div>
  </div>
</div>
";


echo $div_data;


$form_top .= '
<div class="mb-1 ">
  <button class="btn btn-primary btn-sm btn-block" type="button" onclick="openFormLog(\'sex_partner\');">
    <i class="fa fa-heart fa-lg" ></i>
  </button>
</div>' ;


if($x_lasthiv_result == NULL){
   $initJSForm .= '$("#lasthiv_date").val("'.$l_collect_date.'");';
   $initJSForm .= '$("#lasthiv_place-'.$l_clinic_id.'").prop("checked",true);';
   $initJSForm .= '$("#lasthiv_result-'.$hiv_result.'").prop("checked",true);';
}

$initJSForm .= '
$("#t_lastneg").hide();

';

$section_lastneg_q="lastneg_date,lastneg_place,lastneg_result";
$section_lastneg_t="lastneg";

$option_showhide = "


shData['lasthiv_result-NR'] = {dtype:'radio',
hide_q:'$section_lastneg_q',
hide_t:'$section_lastneg_t'};

shData['lasthiv_result-R'] = {dtype:'radio',
show_q:'$section_lastneg_q',
show_t:'$section_lastneg_t'};
shData['lasthiv_result-I'] = {dtype:'radio',
show_q:'$section_lastneg_q',
show_t:'$section_lastneg_t'};
shData['lasthiv_result-NS'] = {dtype:'radio',
show_q:'$section_lastneg_q',
show_t:'$section_lastneg_t'};

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
    setLastNeg();
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
  $("#lastneg_date").val($("#lasthiv_date").val());
  $("#lastneg_place_hos").val($("#lasthiv_place_hos").val());
  $("#lastneg_place_oth").val($("#lasthiv_place_oth").val());

  $("#lastneg_place-RBK").prop("checked", $("#lasthiv_place-RBK").is(':checked'));
  $("#lastneg_place-RHY").prop("checked", $("#lasthiv_place-RHY").is(':checked'));
  $("#lastneg_place-SBK").prop("checked", $("#lasthiv_place-SBK").is(':checked'));
  $("#lastneg_place-SPT").prop("checked", $("#lasthiv_place-SPT").is(':checked'));
  $("#lastneg_place-MCM").prop("checked", $("#lasthiv_place-MCM").is(':checked'));
  $("#lastneg_place-CCM").prop("checked", $("#lasthiv_place-CCM").is(':checked'));
  $("#lastneg_place-OTH").prop("checked", $("#lasthiv_place-OTH").is(':checked'));

  $("#lastneg_result-NR").prop("checked", $("#lasthiv_result-NR").is(':checked'));

  $('#lastneg_date').data("is_show",'1');
  $('#lastneg_place').data("is_show",'1');
  $('#lastneg_result').data("is_show",'1');
  alert("setlastneg");
}

</script>
