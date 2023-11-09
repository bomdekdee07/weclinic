<?


/*
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
*/
include_once("inc_param.php");

//echo "$uid/$visit_date/$visit_id/$project_id/$group_id/$open_link/$form_id";


$form_id = "v2_poc_enroll";
$form_name = "Point of Care <b>Enrollment</b> ";

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



$initJSForm .= '$("#div-studygroup-1").hide();';
$initJSForm .= '$("#div-studygroup-2").hide();';
$initJSForm .= '$("#div-studygroup-3").hide();';
$initJSForm .= '$("#div-studygroup-4").hide();';

$specimen_vl_cepheid_Y = "";

if($group_id == "001"){
  $initJSForm .= '$("#studygroup-1").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-1").show();';

  $initJSForm .= '$("#q_g1_prep").show();';
  $initJSForm .= '$("#q_g1_prep").data("is_show", "1");';

  $initJSForm .= '$("#g1_prep-N").prop("checked",true);';
  $initJSForm .= '$("#div-g1_prep-Y").hide();';

}
else if($group_id == "002"){
  $initJSForm .= '$("#studygroup-2").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-2").show();';

    $initJSForm .= '$("#q_prep_ae2").show();';
    $initJSForm .= '$("#q_prep_ae2").data("is_show", "1");';
    $initJSForm .= '$("#q_prep_ae_title").show();';
    $initJSForm .= '$("#q_prep_ae_title").data("is_show", "1");';
/*
    $initJSForm .= '$("#q_g1_prep").show();';
    $initJSForm .= '$("#q_g1_prep").data("is_show", "1");';

    $initJSForm .= '$("#g1_prep-Y").prop("checked",true);';
    $initJSForm .= '$("#div-g1_prep-N").hide();';
 */
    $initJSForm .= '$("#q_g1_prep_date").show();';
    $initJSForm .= '$("#q_g1_prep_date").data("is_show", "1");';


}
else if($group_id == "003"){
  $initJSForm .= '$("#studygroup-3").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-3").show();';

  $initJSForm .= '$("#t_part1_topic").hide();';
  $initJSForm .= '$("#q_prep_princess").hide();';
  $initJSForm .= '$("#q_prep_princess").data("is_show", "0");';



//  $initJSForm .= '$("#div-specimen_hiv4gen-NA").hide();';

}
else if($group_id == "004"){
  $initJSForm .= '$("#studygroup-4").prop("checked",true);';
  $initJSForm .= '$("#div-studygroup-4").show();';

  $initJSForm .= '$("#t_part1_topic").hide();';
  $initJSForm .= '$("#q_prep_princess").hide();';
  $initJSForm .= '$("#q_prep_princess").data("is_show", "0");';

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

// show/hide question
shData['prep_ae2-Y'] = {dtype:'radio',
show_q:'prep_ae_title'};
shData['prep_ae2-N'] = {dtype:'radio',
hide_q:'prep_ae_title'};


/*
shData['sti_history-Y'] = {dtype:'radio',
show_q:'gc_dx,gc_treat,syphilis_dx,tpha,vdrl,rpr,syphilis_treat,ctng_symptom,ctng_symptom_list,syphilis_symptom,syphilis_treat_type,vdrl_before,vdrl_after,rpr_before,rpr_after',
show_t:'tpha_title,vdrl_before_title'};

shData['sti_history-N'] = {dtype:'radio',
hide_q:'gc_dx,gc_treat,syphilis_dx,tpha,vdrl,rpr,syphilis_treat,ctng_symptom,ctng_symptom_list,syphilis_symptom,syphilis_treat_type,vdrl_before,vdrl_after,rpr_before,rpr_after',
hide_t:'tpha_title,vdrl_before_title'};
*/

shData['sti_history-Y'] = {dtype:'radio',
show_q:'gc_dx,gc_treat'};

shData['sti_history-N'] = {dtype:'radio',
hide_q:'gc_dx,gc_treat'};

shData['ctng_symptom-Y'] = {dtype:'radio',
show_q:'ctng_symptom_list'};
shData['ctng_symptom-N'] = {dtype:'radio',
hide_q:'ctng_symptom_list'};

shData['syphilis_treat-TX'] = {dtype:'radio',
show_q:'syphilis_treat_type'};
shData['syphilis_treat-N'] = {dtype:'radio',
hide_q:'syphilis_treat_type'};
shData['syphilis_treat-NS'] = {dtype:'radio',
hide_q:'syphilis_treat_type'};
shData['syphilis_treat-Y'] = {dtype:'radio',
hide_q:'syphilis_treat_type'};



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
hide_q:'tpha,vdrl,rpr,syphilis_treat,syphilis_treat_type,vdrl_before,vdrl_after,rpr_before,rpr_after',
hide_t:'tpha_title,vdrl_before_title'};

shData['syphilis_dx-N'] = {dtype:'radio',
hide_q:'tpha,vdrl,rpr,syphilis_treat,syphilis_treat_type,vdrl_before,vdrl_after,rpr_before,rpr_after',
hide_t:'tpha_title,vdrl_before_title'};


shData['specimen_pool_anal'] = {dtype:'check',show_q:'spec_pool_anal_collect'};
shData['specimen_pool_neovaginal'] = {dtype:'check',show_q:'spec_pool_neovaginal_collect'};
shData['specimen_pool_oropharyngeal'] = {dtype:'check',show_q:'spec_pool_oropharyngeal_collect'};

shData['specimen_pool-Y'] = {dtype:'radio',
show_q:'specimen_pool_sample'};
shData['specimen_pool-N'] = {dtype:'radio',
hide_q:'specimen_pool_sample,spec_pool_anal_collect,spec_pool_neovaginal_collect,spec_pool_oropharyngeal_collect'};

shData['sti_history-Y'] = {dtype:'radio',
show_q:'gc_dx,gc_treat'};


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



";


include_once("f_form_main.php");

?>


<script>




</script>
