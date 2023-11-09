<?


/*
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
*/
include_once("inc_param.php");


$form_id = "poc_enroll";
$form_name = "Point of Care <b>Enrollment</b>";

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

//  $initJSForm .= '$("#div-specimen_hiv4gen-NA").hide();';




}
else if($group_id == "004"){
  $initJSForm .= '$("#studygroup-4").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-4").show();';
}

/*
if($group_id == "001") $initJSForm .= '$("#studygroup-1").prop("checked",true);';
else if($group_id == "002") $initJSForm .= '$("#studygroup-2").prop("checked",true);';
else if($group_id == "003"){
    $initJSForm .= '$("#studygroup-3").prop("checked",true);';
    $initJSForm .= '$("#div-specimen_hiv4gen-NA").hide();';

}
else if($group_id == "004") $initJSForm .= '$("#studygroup-4").prop("checked",true);';
*/

$option_showhide = "
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
// show/hide component
shData['studygroup-3'] = {dtype:'radio',show:'specimen_vl-NA,ch_vl_less40-NA'};
shData['studygroup-1'] = {dtype:'radio',hide:'specimen_vl-NA,ch_vl_less40-NA'};
shData['studygroup-2'] = {dtype:'radio',hide:'specimen_vl-NA,ch_vl_less40-NA'};
shData['studygroup-4'] = {dtype:'radio',hide:'specimen_vl-NA,ch_vl_less40-NA'};
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
hide_q:'gc_treat'};
shData['gc_dx-N'] = {dtype:'radio',
hide_q:'gc_treat'};

shData['syphilis_dx-Y'] = {dtype:'radio',
show_q:'tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
show_t:'tpha_title,vdrl_before_title'};

shData['syphilis_dx-NS'] = {dtype:'radio',
show_q:'tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
show_t:'tpha_title,vdrl_before_title'};

shData['syphilis_dx-N'] = {dtype:'radio',
hide_q:'tpha,vdrl,rpr,syphilis_treat,vdrl_before,vdrl_after,rpr_before,rpr_after',
hide_t:'tpha_title,vdrl_before_title'};


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

shData['oral_recep_condom-1'] = {dtype:'radio',
hide_q:'oral_recep_nocondom'};
shData['oral_recep_condom-2'] = {dtype:'radio',
show_q:'oral_recep_nocondom'};
shData['oral_recep_condom-3'] = {dtype:'radio',
show_q:'oral_recep_nocondom'};

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

shData['specimen_pool-Y'] = {dtype:'radio',
show_q:'specimen_pool_sample'};
shData['specimen_pool-N'] = {dtype:'radio',
hide_q:'specimen_pool_sample'};

";


include_once("f_form_main.php");

?>


<script>




</script>
