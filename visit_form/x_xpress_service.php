<?

include_once("../function/in_fn_link.php");

$open_link="N";

$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$uic = isset($_GET["uic"])?urldecode($_GET["uic"]):"";
$site = isset($_GET["site"])?urldecode($_GET["site"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$version = isset($_GET["version"])?$_GET["version"]:"CSL";
$version2 = isset($_GET["version2"])?$_GET["version2"]:"RAW";

$is_backdate = isset($_GET["bd"])?$_GET["bd"]:"N"; // back date filled

$is_assess = "Y";
$link=(isset($_GET["link"]))?$_GET["link"]:"";
if($link != ""){ // if there is link param (from open link / qr code)

  $version = "RAW";
  $is_assess = "N";
  $decode_link = decodeSingleLink($_GET["link"]);
  $arr = explode(":",$decode_link);
  if(count($arr)==4){
    $uid = $arr[0]; // uid
    $visit_date = $arr[1]; // visit_date
    $uic = $arr[2]; // uic
    $site = $arr[3]; // site

    $open_link="Y";
    $cur_date = (new DateTime())->format('Y-m-d');
    // echo "$uic/$uid****$visit_date/$cur_date/";


    if($is_backdate == "N"){
      if($cur_date != $visit_date){
        header( "location: ../info/invalid.php?e=e2" );
        exit(0);
      }
    }
  }
  else{
    header( "location: ../info/invalid.php?e=e1" );
    exit(0);
  }

}



if($open_link == "Y"){

include_once("../in_db_conn.php");
$is_form_done = "N";

/*
$query = "SELECT collect_date
FROM p_visit_form_done
WHERE uid=? AND proj_id=? AND group_id=? AND form_id=? AND visit_id=?
";
*/
$query = "SELECT p.xpress_sum
           FROM x_xpress_service as p
           WHERE p.uid = ? AND p.collect_date=? AND p.version='RAW'
           ORDER BY p.collect_date DESC
";

         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("ss", $uid, $visit_date);
         if($stmt->execute()){
           $stmt->bind_result($x_sum);

           if ($stmt->fetch()) {
             if($x_sum != '') $is_form_done = "Y";
           }
    $stmt->close();
}
  //$mysqli->close();
  if($is_form_done == "Y"){
    $mysqli->close();
    header( "location: ../info/invalid.php?e=e2" ); // expired link
    exit(0);
  }
}


$form_id = "xpress_service";
$form_name = "XPress Service";
$form_top = ""; // text display at the top of the form
$form_bottom = ''; // text display at the bottom of the form
$before_save_function = "xpress_service_assessment();"; // trigger before save function
$after_save_function = "xpress_service_assessment_after();"; // trigger after save function
$initJSForm = '

'; // initial js in f_form_main.php

//echo "site: $site/$version";

if($open_link != "Y"){
  include_once("../in_auth_db.php");

  if($version == ''){ // version RAW not allow counselor to save
    $initJSForm .= '$("#btn_save").hide();';
  }
  else if(!isset($auth["data"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }


}


if($site != "%" && $site != "" ){

  $initJSForm .= '
//  alert("site :'.$site.'");
  $("#div-site-SBK").hide();
  $("#div-site-RBK").hide();
  $("#div-site-SPT").hide();
  $("#div-site-MCM").hide();
  $("#div-site-CCM").hide();
  $("#div-site-RHY").hide();

  ';



  $initJSForm .= '$("#div-site-'.$site.'").show(); ';
  $initJSForm .= '$("#site-'.$site.'").prop("checked",true);';
/*
  if($site == "SBK") {
    $initJSForm .= '$("#div-site-1").show(); ';
    $initJSForm .= '$("#site-1").prop("checked",true);';
  }
  else if($site == "RBK") {
    $initJSForm .= '$("#div-site-2").show(); ';
    $initJSForm .= '$("#site-2").prop("checked",true);';
  }
  else if($site == "SPT") {
    $initJSForm .= '$("#div-site-3").show(); ';
    $initJSForm .= '$("#site-3").prop("checked",true);';
  }
  else if($site == "MCM") {
    $initJSForm .= '$("#div-site-4").show(); ';
    $initJSForm .= '$("#site-4").prop("checked",true);';
  }
  else if($site == "CCM") {
    $initJSForm .= '$("#div-site-5").show(); ';
    $initJSForm .= '$("#site-5").prop("checked",true);';
  }
  else if($site == "RHY") {
    $initJSForm .= '$("#div-site-6").show(); ';
    $initJSForm .= '$("#site-6").prop("checked",true);';
  }
  */
}





$initJSForm .= '
//alert("version :'.$version.'");
   $("#t_section2").hide();
   $("#t_section3").hide();
   $("#t_section4").hide();

   $("#t_section2_title1").hide();
   $("#t_section3_title1").hide();
   $("#t_hivacute_symptom").hide();


';

if($is_assess == "Y"){
  $initJSForm .= '
     $("#t_hivacute_symptom").show();
     $("#t_section2").show();
     $("#t_section2_title1").show();
     $("#t_section3").show();
     $("#t_section3_title1").show();
     $("#t_section4").show();

     xpress_service_assessment();
  ';

  $initJSForm .= '
  //alert("version :'.$version.'");
     $("#t_accept_channel_agree").hide();
     $("#t_xpress_channel_end").hide();
     $("#t_xpress_conclude").hide();

  ';
}




$section2_q="sexual_condom";
$section2_t="section2,section2_title1";

$section3_q="hivacute_fever,hivacute_weak,hivacute_sorethoat,hivacute_heahache,hivacute_fatigue,hivacute_diarrhea,hivacute_rash,hivacute_jointpain,hivacute_wound,hivacute_nausea,hivacute_candidiasis,hivacute_stiffneck";
$section3_t="section3,section3_title1,hivacute_symptom";

$section4_q="prep_take";
$section4_t="section4";

$section41_q="prep_sexual,prep_type,prep_4pills,prep_6pills,prep_ondemand,fu_regular";

$option_showhide = "

shData['xpress_interest-Y'] = {dtype:'radio',
show_q:'$section2_q,$section3_q,$section4_q,$section41_q',
show_t:'$section2_t,$section3_t,$section4_t',
hide_q:'xpress_reason'};

shData['xpress_interest-N'] = {dtype:'radio',
show_q:'xpress_reason',
hide_q:'$section2_q,$section3_q,$section4_q,$section41_q',
hide_t:'$section2_t,$section3_t,$section4_t'};

// ท่านทานเพร็พอยู่หรือไม่
shData['prep_take-Y'] = {dtype:'radio',
show_q:'$section41_q'};

shData['prep_take-N'] = {dtype:'radio',
hide_q:'$section41_q'};


// ท่านเป็นชายที่มีเพศสัมพันธ์กับชาย (MSM) หรือหญิงข้ามเพศ (TGW)
shData['prep_sexual-MSM'] = {dtype:'radio',
show_q:'prep_type,prep_4pills',
hide_q:'prep_6pills,prep_ondemand,fu_regular'};

shData['prep_sexual-TGW'] = {dtype:'radio',
show_q:'prep_6pills',
hide_q:'prep_type,prep_4pills,prep_ondemand,fu_regular'};


// ตั้งแต่ท่านมารับบริการครั้งล่าสุด ท่านทานเพร็พแบบใด
shData['prep_type-1'] = {dtype:'radio',
show_q:'prep_4pills',
hide_q:'prep_6pills,prep_ondemand,fu_regular'};

shData['prep_type-2'] = {dtype:'radio',
show_q:'prep_ondemand',
hide_q:'prep_4pills,prep_6pills,fu_regular'};

shData['prep_type-3'] = {dtype:'radio',
show_q:'prep_4pills,prep_ondemand',
hide_q:'prep_6pills,fu_regular'};


//5. ท่านทานเพร็พมากกว่า หรือเท่ากับ 4 เม็ด ในทุกๆ สัปดาห์ใช่หรือไม่

shData['prep_4pills-Y'] = {dtype:'radio',
show_q:'fu_regular',
hide_q:'prep_6pills'};

shData['prep_4pills-N'] = {dtype:'radio',
hide_q:'prep_6pills,fu_regular'};


//6. ท่านทานเพร็พมากกว่า หรือเท่ากับ 6 เม็ด ในทุกๆ สัปดาห์ใช่หรือไม่

shData['prep_6pills-Y'] = {dtype:'radio',
show_q:'fu_regular',
hide_q:'prep_ondemand'
};

shData['prep_6pills-N'] = {dtype:'radio',
hide_q:'prep_ondemand,fu_regular'};

//7. ท่านทานเพร็พ On-demand PrEP แบบถูกวิธี (2-1-1) ทุกครั้งใช่หรือไม่

shData['prep_ondemand-Y'] = {dtype:'radio',
show_q:'fu_regular'};

shData['prep_ondemand-N'] = {dtype:'radio',
hide_q:'fu_regular'};



// xpress consent
shData['consent_agree-Y'] = {dtype:'radio',
show_q:'accept_channel_agree',
hide_q:'accept_channel,accept_channel_info',
show_t:'accept_channel_agree', hide_t:'xpress_channel_end,xpress_conclude'
};

shData['consent_agree-N'] = {dtype:'radio',
hide_q:'accept_channel_agree,accept_channel,accept_channel_info',
hide_t:'accept_channel_agree,xpress_channel_end,xpress_conclude'
};


shData['accept_channel_agree-Y'] = {dtype:'radio',
show_q:'accept_channel,accept_channel_info',
show_t:'accept_channel_agree,xpress_channel_end,xpress_conclude'
};

shData['accept_channel_agree-N'] = {dtype:'radio',
hide_q:'accept_channel,accept_channel_info',
hide_t:'accept_channel_agree,xpress_channel_end,xpress_conclude'
};

";

include_once("xpress_form_main.php");

?>


<script>

$(document).ready(function(){


});


</script>
