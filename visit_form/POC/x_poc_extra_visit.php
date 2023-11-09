<?



/*
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
*/
include_once("inc_param.php");


$form_id = "poc_extra_visit";
$form_name = "Point of Care : Extra Visit";
$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$after_save_function = ""; // trigger after save function
$initJSForm = '

'; // initial js in f_form_main.php



if($open_link != "Y"){
  include_once("../in_auth_db.php");
  if(!isset($auth["data"]) && !isset($auth["log"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
}

$initJSForm .= '$("#div-studygroup-1").hide();';
$initJSForm .= '$("#div-studygroup-2").hide();';
$initJSForm .= '$("#div-studygroup-3").hide();';
$initJSForm .= '$("#div-studygroup-4").hide();';

if($group_id == "001"){
  $initJSForm .= '$("#studygroup-1").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-1").show();';
}
else if($group_id == "002"){
  $initJSForm .= '$("#studygroup-2").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-2").show();';
}
else if($group_id == "003"){
  $initJSForm .= '$("#studygroup-3").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-3").show();';
}
else if($group_id == "004"){
  $initJSForm .= '$("#studygroup-4").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-4").show();';
}


$option_showhide = "
// show/hide component

// log form ae
shData['extr_symptom1-Y'] = {dtype:'radio',show:'btnlog_extr_symptom1-Y'};
shData['extr_symptom1-N'] = {dtype:'radio',hide:'btnlog_extr_symptom1-Y'};
// log form con med
shData['extr_symptom2-Y'] = {dtype:'radio',show:'btnlog_extr_symptom2-Y'};
shData['extr_symptom2-N'] = {dtype:'radio',hide:'btnlog_extr_symptom2-Y'};

shData['studygroup-1'] = {dtype:'radio',hide:'extr_symptom1-NA'};
shData['studygroup-2'] = {dtype:'radio',hide:'extr_symptom1-NA'};
shData['studygroup-3'] = {dtype:'radio',show:'extr_symptom1-NA'};
shData['studygroup-4'] = {dtype:'radio',show:'extr_symptom1-NA'};

// show/hide question
shData['sti_history-Y'] = {dtype:'radio',
show_q:'gc_dx,gc_treat,syphilis_dx,tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
show_t:'tpha,vdrl_before'};

shData['sti_history-N'] = {dtype:'radio',
hide_q:'gc_dx,gc_treat,syphilis_dx,tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
hide_t:'tpha,vdrl_before'};


shData['gc_dx-Y'] = {dtype:'radio',show_q:'gc_treat'};
shData['gc_dx-N'] = {dtype:'radio',hide_q:'gc_treat'};
shData['gc_dx-NS'] = {dtype:'radio',hide_q:'gc_treat'};


shData['vagina_insert-Y'] = {dtype:'radio',
show_q:'vagina_insert_condom,vagina_insert_nocondom'};
shData['vagina_insert-N'] = {dtype:'radio',
hide_q:'vagina_insert_condom,vagina_insert_nocondom'};

shData['anal_insert-Y'] = {dtype:'radio',
show_q:'anal_insert_condom,anal_insert_nocondom'};
shData['anal_insert-N'] = {dtype:'radio',
hide_q:'anal_insert_condom,anal_insert_nocondom'};

shData['anal_recep-Y'] = {dtype:'radio',
show_q:'anal_recep_condom,anal_recep_nocondom'};
shData['anal_recep-N'] = {dtype:'radio',
hide_q:'anal_recep_condom,anal_recep_nocondom'};

shData['oral_insert-Y'] = {dtype:'radio',
show_q:'oral_insert_condom,oral_insert_nocondom'};
shData['oral_insert-N'] = {dtype:'radio',
hide_q:'oral_insert_condom,oral_insert_nocondom'};

shData['oral_recep-Y'] = {dtype:'radio',
show_q:'oral_recep_condom,oral_recep_nocondom'};
shData['oral_recep-N'] = {dtype:'radio',
hide_q:'oral_recep_condom,oral_recep_nocondom'};

shData['neovagina_insert-Y'] = {dtype:'radio',
show_q:'neovagina_insert_condom,neovagina_insert_nocondom'};
shData['neovagina_insert-N'] = {dtype:'radio',
hide_q:'neovagina_insert_condom,neovagina_insert_nocondom'};

shData['neovagina_recep-Y'] = {dtype:'radio',
show_q:'neovagina_recep_condom,neovagina_recep_nocondom'};
shData['neovagina_recep-N'] = {dtype:'radio',
hide_q:'neovagina_recep_condom,neovagina_recep_nocondom'};


shData['vagina_insert_condom-1'] = {dtype:'radio',
hide_q:'vagina_insert_nocondom'};
shData['vagina_insert_condom-2'] = {dtype:'radio',
show_q:'vagina_insert_nocondom'};
shData['vagina_insert_condom-3'] = {dtype:'radio',
show_q:'vagina_insert_nocondom'};

shData['anal_insert_condom-1'] = {dtype:'radio',
hide_q:'anal_insert_nocondom'};
shData['anal_insert_condom-2'] = {dtype:'radio',
show_q:'anal_insert_nocondom'};
shData['anal_insert_condom-3'] = {dtype:'radio',
show_q:'anal_insert_nocondom'};

shData['oral_insert_condom-1'] = {dtype:'radio',
hide_q:'oral_insert_nocondom'};
shData['oral_insert_condom-2'] = {dtype:'radio',
show_q:'oral_insert_nocondom'};
shData['oral_insert_condom-3'] = {dtype:'radio',
show_q:'oral_insert_nocondom'};

shData['neovagina_insert_condom-1'] = {dtype:'radio',
hide_q:'neovagina_insert_nocondom'};
shData['neovagina_insert_condom-2'] = {dtype:'radio',
show_q:'neovagina_insert_nocondom'};
shData['neovagina_insert_condom-3'] = {dtype:'radio',
show_q:'neovagina_insert_nocondom'};

shData['anal_recep_condom-1'] = {dtype:'radio',
hide_q:'anal_recep_nocondom'};
shData['anal_recep_condom-2'] = {dtype:'radio',
show_q:'anal_recep_nocondom'};
shData['anal_recep_condom-3'] = {dtype:'radio',
show_q:'anal_recep_nocondom'};

shData['neovagina_recep_condom-1'] = {dtype:'radio',
hide_q:'neovagina_recep_nocondom'};
shData['neovagina_recep_condom-2'] = {dtype:'radio',
show_q:'neovagina_recep_nocondom'};
shData['neovagina_recep_condom-3'] = {dtype:'radio',
show_q:'neovagina_recep_nocondom'};

shData['referral-Y'] = {dtype:'radio', show_q:'referral_txt_title'};
shData['referral-N'] = {dtype:'radio', hide_q:'referral_txt_title'};
shData['referral-W'] = {dtype:'radio', hide_q:'referral_txt_title'};




shData['syphilis_dx-Y'] = {dtype:'radio',
show_q:'tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
show_t:'tpha,vdrl_before'};

shData['syphilis_dx-N'] = {dtype:'radio',
hide_q:'tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
hide_t:'tpha,vdrl_before'};

shData['groupchange-1'] = {dtype:'radio',hide_q:'groupchange_txt_title'};
shData['groupchange-2'] = {dtype:'radio',show_q:'groupchange_txt_title'};
shData['groupchange-3'] = {dtype:'radio',show_q:'groupchange_txt_title'};
shData['groupchange-4'] = {dtype:'radio',show_q:'groupchange_txt_title'};
shData['groupchange-5'] = {dtype:'radio',show_q:'groupchange_txt_title'};

";


include_once("f_form_main.php");

//include_once("../visit_form_log/z_con_med.php");

?>


<script>



</script>
