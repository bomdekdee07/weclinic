<?

// Point of care Referral ส่งต่อการรักษา แบบแยกประเภทโรค 4 ตัว

include_once("inc_param.php");

$form_id = "final_status";
$form_name = "Point of care Final Status (สิ้นสุดโครงการ)";
$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php


  include_once("../in_auth_db.php");
  if(!isset($auth["data"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }



//show uid data
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function

$query = "SELECT ul.pid, ul.enroll_date, ul.proj_group_id, uv.visit_clinic_id,
uv.visit_id, uv.visit_date, v.visit_name, c.clinic_name
FROM p_project_uid_list as ul, p_project_uid_visit as uv,
p_clinic as c, p_visit_list as v
WHERE ul.uid=uv.uid AND uv.proj_id=ul.proj_id
AND c.clinic_id = uv.visit_clinic_id AND uv.visit_id=v.visit_id
AND uv.visit_status <> 0 AND ul.uid=? AND ul.proj_id=?
ORDER BY uv.visit_date DESC, v.visit_order ASC  LIMIT 1
";
//echo "$uid, $project_id / $query";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $uid, $project_id);
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
  <div><h3>ข้อมูล Point of Care ของ $cur_pid</h3></div>
  <div><b>วันเข้าโครงการ: <u>".changeToThaiDate($cur_enroll_date)."</u> ปัจจุบันอยู่ที่กลุ่ม: <u>$cur_group_id</u> <br>
  เข้านัดหมายล่าสุด <u>$cur_visit_name</u> (".changeToThaiDate($cur_visit_date).") / คลีนิค: <u>$cur_clinic_name</u></b></div>
  </div>
</div>
";

echo $div_data;

$initJSForm .= '
$("#div-studygroup-1").hide();
$("#div-studygroup-2").hide();
$("#div-studygroup-3").hide();
$("#div-studygroup-4").hide();
';

if($cur_group_id == "001"){
  $initJSForm .= '$("#studygroup-1").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-1").show();';
}
else if($cur_group_id == "002"){
  $initJSForm .= '$("#studygroup-2").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-2").show();';
}
else if($cur_group_id == "003"){
  $initJSForm .= '$("#studygroup-3").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-3").show();';
}
else if($cur_group_id == "004"){
  $initJSForm .= '$("#studygroup-4").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-4").show();';
}


$initJSForm .= '
if($("#final_date").val() == ""){
  $("#final_date").val("'.changeToThaiDate($cur_visit_date).'");
}
';

if($visit_date == "")$visit_date = getToday();


$option_showhide = "
shData['finalstatus-1'] = {dtype:'radio',
hide_q:'finalfu_date,final_reason,death_date,death_cause'};

shData['finalstatus-2'] = {dtype:'radio',
hide_q:'finalfu_date,final_reason,death_date,death_cause'
};

shData['finalstatus-3'] = {dtype:'radio',
hide_q:'final_reason,finalfu_date,death_date,death_cause'
};

shData['finalstatus-3'] = {dtype:'radio',
hide_q:'final_reason,finalfu_date,death_date,death_cause'
};

shData['finalstatus-4'] = {dtype:'radio',
hide_q:'final_reason,finalfu_date,death_date,death_cause'
};

shData['finalstatus-5'] = {dtype:'radio',
show_q:'finalfu_date',
hide_q:'final_reason,death_date,death_cause'
};

shData['finalstatus-6'] = {dtype:'radio',
show_q:'final_reason',
hide_q:'finalfu_date,death_date,death_cause'
};

shData['finalstatus-7'] = {dtype:'radio',
show_q:'death_date,death_cause',
hide_q:'finalfu_date,final_reason'
};

shData['finalstatus-8'] = {dtype:'radio',
show_q:'final_reason',
hide_q:'finalfu_date,death_date,death_cause'
};

";

$after_save_function = "updateFinalStatus();"; // trigger after save function

include_once("f_form_main.php");

?>


<script>
$(document).ready(function(){

});



function updateFinalStatus(){
      var aData = {
                u_mode:"update_final_status",
                uid:$('#cur_uid').val(),
                proj_id: $('#cur_proj_id').val()
      };
      save_data_ajax(aData,"w_user/db_proj_visit.php",updateFinalStatusComplete);

}

function updateFinalStatusComplete(flagSave, rtnDataAjax, aData){
  if(flagSave){
     selectVisitList();
  }
}

</script>
