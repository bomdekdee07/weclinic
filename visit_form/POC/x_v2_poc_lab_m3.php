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



$form_id = "v2_poc_lab_m3";
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


include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php");
/*
$query = "SELECT tpha_result, collect_date
        FROM x_lab_result
        WHERE uid = ?  AND tpha_result='R' AND collect_date <> ?
        ";
  */
$query = "SELECT tpha_result, collect_date
          FROM x_lab_result
          WHERE uid = ?  AND tpha_result='R' AND collect_date < ?
                ";
//echo "$uid, $visit_date / query : $query";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ss", $uid, $visit_date);
        if($stmt->execute()){
          $stmt->bind_result($tpha_prev, $tpha_prev_date);
          if($stmt->fetch()) {

          }
        }
        $stmt->close();

$tpha_check = "";
if($tpha_prev != ''){
  $tpha_prev_date = ($tpha_prev_date !== NULL)?changeToThaiDate($tpha_prev_date):"";
  $tpha_check .= '$("#syphilis_tpha_check").html("เคยตรวจ TPHA ผลเป็นบวกแล้วเมื่อ: '.$tpha_prev_date.'");';

  $tpha_check .= '$("#div-tpha_result-R").hide();';
  $tpha_check .= '$("#div-tpha_result-NR").hide();';
  $tpha_check .= '$("#div-tpha_result-I").hide();';
  $tpha_check .= '$("#div-tpha_result-NT").show();';
  $tpha_check .= '$("#tpha_result-NT").prop("checked",true);';

  $tpha_check .= '$("#div-tpha_result_notest-OTH").hide();';
  $tpha_check .= '$("#div-tpha_notest").hide();';
  $tpha_check .= '$("#div-tpha_result_notest-POS").show();';
  $tpha_check .= '$("#tpha_result_notest-POS").prop("checked",true);';

}









$specimen_vl_cepheid_Y = "";
if($group_id == "003"){
  /*
  $initJSForm .= '$("#specimen_vl_cepheid-NA").prop("checked",true);';
  $initJSForm .= '$("#div-specimen_vl_cepheid-NA").show();';

  $initJSForm .= '$("#div-specimen_vl_cepheid-Y").hide();';
  $initJSForm .= '$("#div-specimen_vl_cepheid-N").hide();';
  $initJSForm .= '$("#div-specimen_vl_cepheid_no_txt").hide();';
*/

  $initJSForm .= '$("#ch_vl_less40-NA").prop("checked",true);';
  $initJSForm .= '$("#div-ch_vl_less40-NA").show();';

  $initJSForm .= '$("#div-ch_vl_less40-Y").hide();';
  $initJSForm .= '$("#div-ch_vl_less40-N").hide();';
  $initJSForm .= '$("#div-ch_vl_less40-N2").hide();';
  $initJSForm .= '$("#div-ch_vl_less40-ND").hide();';
  $initJSForm .= '$("#div-ch_vl_result").hide();';

  $initJSForm .= '$("#div-specimen_hiv3gen-NA").hide();';

  $specimen_vl_cepheid_Y .= '$("#q_specimen_vl_cepheid_y_box").show();';
  $specimen_vl_cepheid_Y .= '$("#q_specimen_vl_cepheid_y_box").data("is_show", "1");';

  $initJSForm .= $specimen_vl_cepheid_Y;
  $initJSForm .= '$("#specimen_vl_cepheid_plasma_out_date").prop("disabled", true);';

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

  $initJSForm .= '$("#div-specimen_hiv3gen-Y").hide();';
  $initJSForm .= '$("#div-specimen_hiv3gen-N").hide();';
  $initJSForm .= '$("#div-specimen_hiv3gen_no_txt").hide();';
  $initJSForm .= '$("#specimen_hiv3gen-NA").prop("checked", true);';

  $initJSForm .= '$("#hiv_result-NR").hide();';
  $initJSForm .= '$("#hiv_result-R").hide();';
  $initJSForm .= '$("#hiv_result-I").hide();';
  $initJSForm .= '$("#hiv_result-NT").prop("checked", true);';

  $initJSForm .= '$("#q_hiv_result_notest").show();';
  $initJSForm .= '$("#q_hiv_result_notest").data("is_show","1");';
  $initJSForm .= '$("#hiv_result_notest-POS").prop("checked", true);';

}
else{
  $initJSForm .= '$("#div-specimen_vl_cepheid-NA").hide();';
  $initJSForm .= '$("#div-ch_vl_less40-NA").hide();';
  $initJSForm .= '$("#div-specimen_hiv3gen-NA").hide();';
}


$option_showhide = "

shData['specimen_ctng_cepheid-Y'] = {dtype:'radio',
show_q:'ct_pool_result,ng_pool_result'
};
shData['specimen_ctng_cepheid-N'] = {dtype:'radio',
hide_q:'ct_pool_invalid,ct_pool_result_re,ng_pool_invalid,ng_pool_result_re'
};
shData['specimen_ctng_cepheid-NA'] = {dtype:'radio',
hide_q:'ct_pool_invalid,ct_pool_result_re,ng_pool_invalid,ng_pool_result_re'
};

shData['ct_pool_result-ND'] = {dtype:'radio', hide_q:'ct_pool_invalid,ct_pool_result_re'};
shData['ct_pool_result-D'] = {dtype:'radio', hide_q:'ct_pool_invalid,ct_pool_result_re'};
shData['ct_pool_result-I'] = {dtype:'radio', show_q:'ct_pool_invalid'};

shData['ng_pool_result-ND'] = {dtype:'radio', hide_q:'ng_pool_invalid,ng_pool_result_re'};
shData['ng_pool_result-D'] = {dtype:'radio', hide_q:'ng_pool_invalid,ng_pool_result_re'};
shData['ng_pool_result-I'] = {dtype:'radio', show_q:'ng_pool_invalid'};

shData['ct_pool_invalid-1'] = {dtype:'radio', show_q:'ct_pool_result_re'};
shData['ct_pool_invalid-3'] = {dtype:'radio', show_q:'ct_pool_result_re'};
shData['ct_pool_invalid-4'] = {dtype:'radio', hide_q:'ct_pool_result_re'};

shData['ng_pool_invalid-1'] = {dtype:'radio', show_q:'ng_pool_result_re'};
shData['ng_pool_invalid-3'] = {dtype:'radio', show_q:'ng_pool_result_re'};
shData['ng_pool_invalid-4'] = {dtype:'radio', hide_q:'ng_pool_result_re'};


shData['hiv_result-NR'] = {dtype:'radio', hide_q:'hiv_result_notest'};
shData['hiv_result-R'] = {dtype:'radio', hide_q:'hiv_result_notest'};
shData['hiv_result-I'] = {dtype:'radio', hide_q:'hiv_result_notest'};
shData['hiv_result-NT'] = {dtype:'radio', show_q:'hiv_result_notest'};

shData['tpha_result-NR'] = {dtype:'radio', hide_q:'rpr_result,tpha_result_notest'};
shData['tpha_result-R'] = {dtype:'radio', show_q:'rpr_result', hide_q:'tpha_result_notest'};
shData['tpha_result-I'] = {dtype:'radio', show_q:'rpr_result', hide_q:'tpha_result_notest'};
shData['tpha_result-NT'] = {dtype:'radio', show_q:'tpha_result_notest,rpr_result'};


shData['specimen_hiv3gen-Y'] = {dtype:'radio',hide:'hiv_result-NT',show:'hiv_result-NR,hiv_result-R,hiv_result-I'};
shData['specimen_hiv3gen-N'] = {dtype:'radio',show:'hiv_result-NT',hide:'hiv_result-NR,hiv_result-R,hiv_result-I'};
shData['specimen_hiv3gen-NA'] = {dtype:'radio',show:'hiv_result-NT',hide:'hiv_result-NR,hiv_result-R,hiv_result-I'};

shData['specimen_vl_cepheid-Y'] = {dtype:'radio',hide:'ch_vl_less40-NT',show:'ch_vl_less40-Y,ch_vl_less40-N,ch_vl_less40-N2,ch_vl_less40-ND,ch_vl_result'};
shData['specimen_vl_cepheid-N'] = {dtype:'radio',show:'ch_vl_less40-NT',hide:'ch_vl_less40-Y,ch_vl_less40-N,ch_vl_less40-N2,ch_vl_less40-ND,ch_vl_result'};
shData['specimen_vl_cepheid-NA'] = {dtype:'radio',show:'ch_vl_less40-NT',hide:'ch_vl_less40-Y,ch_vl_less40-N,ch_vl_less40-N2,ch_vl_less40-ND,ch_vl_result'};


shData['specimen_syphilis-Y'] = {dtype:'radio',
show_q:'tpha_result',
show_t:'tpha_result'};
shData['specimen_syphilis-N'] = {dtype:'radio',
hide_q:'tpha_result,rpr_result',
hide_t:'tpha_result'};
shData['specimen_syphilis-NA'] = {dtype:'radio',
hide_q:'tpha_result,rpr_result',
hide_t:'tpha_result'};




";

//$option_showhide = "";
$initJSForm .= '$("#specimen_vl_cepheid_y_box").mask("**/***",{placeholder:"##/###"});';
$initJSForm .= '$("#specimen_vl_cepheid_plasma_out_date").prop("disabled",true);';





include_once("f_form_main.php");

?>


<script>
<? echo $tpha_check; ?>
$(document).ready(function(){
  $("#specimen_vl_cepheid-Y").click(function(){ // พลาสมาสำหรับใช้ตรวจปริมาณเชื้อเอชไอวีในเลือด โดยเครื่อง Cepheid - ได้รับ
     <? echo $specimen_vl_cepheid_Y;?>
  });
  $("#specimen_vl_cepheid-N").click(function(){ // พลาสมาสำหรับใช้ตรวจปริมาณเชื้อเอชไอวีในเลือด โดยเครื่อง Cepheid - ได้รับ
      $("#q_specimen_vl_cepheid_y_box").hide();
      $("#q_specimen_vl_cepheid_y_box").data("is_show", "0");
  });





  var poc_group = '<? echo $group_id; ?>';

    // referral warning when hiv negative but select below options
    $("#ch_vl_less40-Y").click(function(){ //
      if(poc_group == '001' || poc_group== '002'){
        if($("#hiv_result-NR").prop("checked") == true){
            myModalContent("Information",
            "ผลตรวจ HIV เป็นลบ ควรส่งต่อการรักษาเนื่องจากอาจจะเกิด HIV acute ",
            "info");
        }
      }
    });
    $("#ch_vl_less40-N").click(function(){ //
      if(poc_group == '001' || poc_group== '002'){
        if($("#hiv_result-NR").prop("checked") == true){
            myModalContent("Information",
            "ผลตรวจ HIV เป็นลบ ควรส่งต่อการรักษาเนื่องจากอาจจะเกิด HIV acute ",
            "info");
        }
      }
    });

    $("#ch_vl_less40-N2").click(function(){ //
      if(poc_group == '001' || poc_group== '002'){
        if($("#hiv_result-NR").prop("checked") == true){
            myModalContent("Information",
            "ผลตรวจ HIV เป็นลบ ควรส่งต่อการรักษาเนื่องจากอาจจะเกิด HIV acute ",
            "info");
        }
      }
    });

});

</script>
