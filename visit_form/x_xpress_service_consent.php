<?
/*
patient version
if xpress result notpass it will skip xpress consent form.
*/
include_once("../function/in_fn_link.php");

$open_link="N";

$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$uic = isset($_GET["uic"])?urldecode($_GET["uic"]):"";
$site = isset($_GET["site"])?urldecode($_GET["site"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$version = isset($_GET["version"])?$_GET["version"]:"CSL"; // version to save
$version2 = isset($_GET["version2"])?$_GET["version2"]:"RAW"; // version to view

$xpress_result = isset($_GET["r"])?$_GET["r"]:"N";


$is_assess = "Y";

if(isset($_GET["link"])){ // if there is link param (from open link / qr code)
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

    /*
    if($cur_date != $visit_date){
      header( "location: ../info/invalid.php?e=e2" );
      exit(0);

    }
    */
  }
  else{
    header( "location: ../info/invalid.php?e=e1" );
    exit(0);
  }

}



if($open_link == "Y"){

include_once("../in_db_conn.php");
$is_form_done = "N";

$query = "SELECT p.consent_agree
           FROM x_xpress_service as p
           WHERE p.uid = ? AND p.collect_date=? AND p.version='CSL'
           ORDER BY p.collect_date DESC
";

         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("ss", $uid, $visit_date);
         if($stmt->execute()){
           $stmt->bind_result($x_consent_agree);

           if ($stmt->fetch()) {
             if($x_consent_agree != '') $is_form_done = "Y";
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
$form_name = "XPress Service Censent";
$form_top = ""; // text display at the top of the form
$form_bottom = ''; // text display at the bottom of the form
$before_save_function = ""; // trigger before save function
$after_save_function = ""; // trigger after save function
$initJSForm = '

'; // initial js in f_form_main.php



$initJSForm .= '
//alert("version :'.$version.'");
   $("#t_accept_channel_agree").hide();
   $("#t_xpress_channel_end").hide();
   $("#t_xpress_conclude").hide();

   $("#consent_agree_tel").prop("disabled", true);
   $("#accept_channel_info").prop("disabled", true);

   $("#xpress_pass_txt").hide();
   $("#xpress_fail_txt").hide();
';

// show /hide xpress result
if($xpress_result == "Y"){
  $initJSForm .= '$("#xpress_pass_txt").show();';
}
else if($xpress_result == "N"){
  $initJSForm .= '
  $("#xpress_fail_txt").show();
  $("#xpress_form").hide();
  $("#btn_save").hide();
  ';
}


$option_showhide = "
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
show_t:'accept_channel_agree',
hide_t:'xpress_channel_end,xpress_conclude'
};

";

include_once("xpress_consent_form_main.php");

?>


<script>

$(document).ready(function(){


  $("#consent_agree-Y").click(function(){ // agree to join project
    // alert("consent yes");
     $("#consent_agree_tel").val($("#data_tel").val().trim());

     if($("#consent_agree_tel").val() == ""){

       $("#consent_agree_tel").notify("ไม่มีข้อมูลเบอร์โทรศัพท์ติดต่อท่าน กรุณากรอกเบอร์โทรศัพท์ด้านบน","info");
       $.notify("ไม่มีข้อมูลเบอร์โทรศัพท์ติดต่อท่าน กรุณากรอกเบอร์โทรศัพท์ด้านบน","error");

     }

  });
  $("#consent_agree-N").click(function(){ // agree to join project
     //alert("consent no");
  });

// accept xpress channel
  $("#accept_channel-1").click(function(){ // LINE
     checkXpressChannel($("#data_line_id"));
  });
  $("#accept_channel-2").click(function(){ // email
     checkXpressChannel($("#data_email"));
  });
  $("#accept_channel-3").click(function(){ // tel
     checkXpressChannel($("#data_tel"));
  });
  $("#accept_channel-4").click(function(){ // sms_tel
     checkXpressChannel($("#data_sms_tel"));
  });



});






</script>
