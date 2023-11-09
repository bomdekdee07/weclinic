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



$form_id = "poc_lab_m3";
$form_name = "Point of care Month3 LAB";
$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php

if($open_link != "Y"){
  include_once("../in_auth_db.php");
  if(!isset($auth["lab"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
}

if($group_id == "003"){
  $initJSForm .= '$("#specimen_vl_cepheid-NA").prop("checked",true);';
  $initJSForm .= '$("#div-specimen_vl_cepheid-NA").show();';

  $initJSForm .= '$("#div-specimen_vl_cepheid-Y").hide();';
  $initJSForm .= '$("#div-specimen_vl_cepheid-N").hide();';
  $initJSForm .= '$("#div-specimen_vl_cepheid_no_txt").hide();';

  $initJSForm .= '$("#ch_vl_less40-NA").prop("checked",true);';
  $initJSForm .= '$("#div-ch_vl_less40-NA").show();';

  $initJSForm .= '$("#div-ch_vl_less40-Y").hide();';
  $initJSForm .= '$("#div-ch_vl_less40-N").hide();';
  $initJSForm .= '$("#div-ch_vl_less40-N2").hide();';
  $initJSForm .= '$("#div-ch_vl_less40-ND").hide();';
  $initJSForm .= '$("#div-ch_vl_result").hide();';
}
else if ($group_id == "004"){
  if($visit_id == "M3" || $visit_id == "M9"){ // month 3, 9  in group 3  N/A in VL
    $initJSForm .= '$("#specimen_vl_cepheid-NA").prop("checked",true);';
    $initJSForm .= '$("#div-specimen_vl_cepheid-NA").show();';

    $initJSForm .= '$("#div-specimen_vl_cepheid-Y").hide();';
    $initJSForm .= '$("#div-specimen_vl_cepheid-N").hide();';
    $initJSForm .= '$("#div-specimen_vl_cepheid_no_txt").hide();';

    $initJSForm .= '$("#ch_vl_less40-NA").prop("checked",true);';
    $initJSForm .= '$("#div-ch_vl_less40-NA").show();';

    $initJSForm .= '$("#div-ch_vl_less40-Y").hide();';
    $initJSForm .= '$("#div-ch_vl_less40-N").hide();';
    $initJSForm .= '$("#div-ch_vl_less40-N2").hide();';
    $initJSForm .= '$("#div-ch_vl_less40-ND").hide();';
    $initJSForm .= '$("#div-ch_vl_result").hide();';
  }
  else{
    $initJSForm .= '$("#div-specimen_vl_cepheid-NA").hide();';
    $initJSForm .= '$("#div-ch_vl_less40-NA").hide();';
  }

}
else{
  $initJSForm .= '$("#div-specimen_vl_cepheid-NA").hide();';
  $initJSForm .= '$("#div-ch_vl_less40-NA").hide();';
}


$option_showhide = "

shData['specimen_ctng_cepheid-Y'] = {dtype:'radio',
show_q:'ct_pool_result,ng_pool_result'
};
shData['specimen_ctng_cepheid-N'] = {dtype:'radio',
hide_q:'ct_pool_result,ng_pool_result,ct_pool_invalid,ng_pool_invalid'
};



shData['ct_pool_result-ND'] = {dtype:'radio',
hide_q:'ct_pool_invalid'};
shData['ct_pool_result-D'] = {dtype:'radio',
hide_q:'ct_pool_invalid'};
shData['ct_pool_result-I'] = {dtype:'radio',
show_q:'ct_pool_invalid'};

shData['ng_pool_result-ND'] = {dtype:'radio', hide_q:'ng_pool_invalid'};
shData['ng_pool_result-D'] = {dtype:'radio', hide_q:'ng_pool_invalid'};
shData['ng_pool_result-I'] = {dtype:'radio', show_q:'ng_pool_invalid'};
";
 //$option_showhide = "";

include_once("f_form_main.php");

?>


<script>



</script>
