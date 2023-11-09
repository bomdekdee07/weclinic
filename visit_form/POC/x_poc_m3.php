<?

// Point of care Month3

/*
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
*/
include_once("inc_param.php");



$form_id = "poc_m3";
$form_name = "Point of care Month3";
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

/*
if($group_id != "003"){ //show option for group 3 only
  $initJSForm .= '$("#div-ch_vl_less40-NA").hide();'; // Not Applicable (สำหรับอาสาสมัครในกลุ่ม 3 เท่านั้น)
  $initJSForm .= '$("#div-specimen_plasma-NA").hide();'; // Not Applicable (สำหรับอาสาสมัครในกลุ่ม 3 เท่านั้น)
}
*/

if($group_id != "003" && $group_id != "004"){ //show option for group 3 & 4 only
  $initJSForm .= '$("#div-prep_ae-NA").hide();'; // Not Applicable **เฉพาะกลุ่ม 3 และกลุ่ม 4
}

/* // ให้เปลี่ยนกลุ่มนอกฟอร์มแล้ว 16/11/2019
if($group_id == "001") $initJSForm .= '$("#div-groupchange-1").hide();'; // เปลี่ยนเป็นกลุ่ม 1
else if($group_id == "002") $initJSForm .= '$("#div-groupchange-2").hide();'; // เปลี่ยนเป็นกลุ่ม 2
else if($group_id == "003") $initJSForm .= '$("#div-groupchange-3").hide();'; // เปลี่ยนเป็นกลุ่ม 3
else if($group_id == "004") $initJSForm .= '$("#div-groupchange-4").hide();'; // เปลี่ยนเป็นกลุ่ม 4
*/

$option_showhide = "
// show/hide component
/*
// log form ae
shData['prep_ae-Y'] = {dtype:'radio',show:'btnlog_prep_ae-Y'};
shData['prep_ae-N'] = {dtype:'radio',hide:'btnlog_prep_ae-Y'};
shData['prep_ae-NA'] = {dtype:'radio',hide:'btnlog_prep_ae-Y'};

// log form con med
shData['conmed-Y'] = {dtype:'radio',show:'btnlog_conmed-Y'};
shData['conmed-N'] = {dtype:'radio',hide:'btnlog_conmed-Y'};
shData['conmed-NA'] = {dtype:'radio',hide:'btnlog_conmed-Y'};
*/
/*
shData['groupchange-1'] = {dtype:'radio',hide:'groupchange_txt'};
shData['groupchange-2'] = {dtype:'radio',show:'groupchange_txt'};
shData['groupchange-3'] = {dtype:'radio',show:'groupchange_txt'};
shData['groupchange-4'] = {dtype:'radio',show:'groupchange_txt'};
shData['groupchange-5'] = {dtype:'radio',show:'groupchange_txt'};
*/

// show/hide question
shData['sti_history-Y'] = {dtype:'radio',
show_q:'gc_dx,gc_treat,syphilis_dx,tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
show_t:'tpha_title,vdrl_before_title'};

shData['sti_history-N'] = {dtype:'radio',
hide_q:'gc_dx,gc_treat,syphilis_dx,tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
hide_t:'tpha_title,vdrl_before_title'};

shData['gc_dx-Y'] = {dtype:'radio',
show_q:'gc_treat'};
shData['gc_dx-NS'] = {dtype:'radio',
show_q:'gc_treat'};
shData['gc_dx-N'] = {dtype:'radio',
hide_q:'gc_treat'};

shData['syphilis_dx-Y'] = {dtype:'radio',
show_q:'tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
show_t:'tpha_title,vdrl_before_title'};

shData['syphilis_dx-N'] = {dtype:'radio',
hide_q:'tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
hide_t:'tpha_title,vdrl_before_title'};

shData['syphilis_dx-NS'] = {dtype:'radio',
hide_q:'tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
hide_t:'tpha_title,vdrl_before_title'};


shData['gc_dx-Y'] = {dtype:'radio', show_q:'gc_treat'};
shData['gc_dx-N'] = {dtype:'radio', hide_q:'gc_treat'};
shData['gc_dx-NS'] = {dtype:'radio', hide_q:'gc_treat'};



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

shData['referral-N'] = {dtype:'radio',
hide_q:'referral_date_title,referral_place_title',
hide_t:'referral_case'};

shData['referral-W'] = {dtype:'radio',
hide_q:'referral_date_title,referral_place_title',
hide_t:'referral_case'};

shData['referral-Y'] = {dtype:'radio',
show_q:'referral_date_title,referral_place_title',
show_t:'referral_case'};

shData['ct_pool_result-N'] = {dtype:'radio',
hide_q:'ct_pool_invalid'};
shData['ct_pool_result-D'] = {dtype:'radio',
hide_q:'ct_pool_invalid'};
shData['ct_pool_result-I'] = {dtype:'radio',
show_q:'ct_pool_invalid'};


shData['ng_pool_result-N'] = {dtype:'radio', hide_q:'ng_pool_invalid'};
shData['ng_pool_result-D'] = {dtype:'radio', hide_q:'ng_pool_invalid'};
shData['ng_pool_result-I'] = {dtype:'radio', show_q:'ng_pool_invalid'};

shData['specimen_pool-Y'] = {dtype:'radio',
show_q:'pool_title'};
shData['specimen_pool-N'] = {dtype:'radio',
hide_q:'pool_title'};

";
 //$option_showhide = "";

include_once("f_form_main.php");

?>


<script>



</script>
