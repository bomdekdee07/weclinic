<?
include_once("../function/in_fn_link.php");
include_once("../function/in_ts_log.php");
$open_link="N";
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$uic = isset($_GET["uic"])?urldecode($_GET["uic"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";

$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
$is_backdate = isset($_GET["bd"])?$_GET["bd"]:"N"; // back date filled


$is_paper = isset($_GET["is_paper"])?$_GET["is_paper"]:"";
$ip="";

if(isset($_GET["link"])){ // if there is link param (from open link / qr code)
    $group_id = decodeSingleLink($_GET["link"]);
    $open_link="Y";

    $ip_address = get_client_ip();
    $ip_address =  str_replace(":","",$ip_address) ;

    // create temp uid (screen id)
    $uid = $ip_address.$group_id.(new DateTime())->format('d M y H:i:s');
    $uid = encodeSingleLink($uid);
    $uid = str_shuffle($uid);
    if(substr($uid,0,4) == "SCRN") $uid = str_shuffle($uid);

    $uid = substr($uid,0,10) ;

    $cur_date = (new DateTime())->format('Y-m-d');

    if($is_backdate == "N"){
      if($cur_date != $visit_date){
        header( "location: ../info/invalid.php?e=e2" );
        exit(0);
      }
    }

      $visit_date = $cur_date;
//echo $_GET["link"]."/count: ".count($arr)."/$group_id-$uid-$ip";

}



if($is_paper == "Y"){
  $ip_address = get_client_ip();
  $ip_address =  str_replace(":","",$ip_address) ;

  // create temp uid (screen id)
  $uid = $ip_address.$group_id.(new DateTime())->format('d M y H:i:s');
  $uid = encodeSingleLink($uid);
  $uid = str_shuffle($uid);
  if(substr($uid,0,4) == "SCRN") $uid = str_shuffle($uid);

  $uid = substr($uid,0,10) ;
  $open_link = "Y";
}

//echo "$uid/$visit_date/$visit_id/$project_id/$group_id/$open_link/$form_id";
//echo "group:$group_id<br>";
$form_id = "covid_screen_g3";
$form_name = "COVID Screening Group 3 ";

$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$before_save_function = ""; // trigger before save function
$after_save_function = "updateScreenID();"; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php
$require_form_complete ="Y";
$open_link_page = ""; // trigger open target page after save complete
if($open_link != "Y"){ // open link by staff
  include_once("../in_auth_db.php");

  /*
    if(!isset($auth["data"]) && !isset($auth["log"])){ // check auth
       $initJSForm .= '$("#btn_save").hide();';
    }
  */
    $initJSForm .= '$("#btn_save").hide();';
    $initJSForm .= '$("#div_covid_scrn_dob").hide();';

}
else{
  $initJSForm .= '$("#q_age18").hide();';
  //$initJSForm .= '$("#q_age18").data("is_show", "0");';
  $initJSForm .= '$("#group_id").val("'.$group_id.'");';
  $initJSForm .= '$("#ip").val("'.$ip_address.'");';

}


$initJSForm .= '$("#form_head_id").html("");';
/*
$open_link_page = "
if(typeof rtnDataAjax.pid  !== 'undefined' ){
  window.location = '../info/inf_txt.php?e=f1&f=$form_name&u='+rtnDataAjax.pid;
}
";
*/
/*
if($open_link == "Y"){// enable data
  $initJSForm .= '$("#age18").val("'.$age.'");';
}
else{ // lock data
  $initJSForm .= '$("#btn_save").hide();';
}
*/

$option_showhide = "

";

$form_bottom = "<div class='my-2' id='div_covid_scrn_dob'>
กรุณากรอก วันเดือนปีเกิด ของท่าน: วว/ดด/ปปปป (ปี พ.ศ.):
<input type='text' id='birth_date' >
</div>";

$before_save_function = "
   if(!validateDate($('#birth_date').val())) return;
   checkScreenPass_Covid();
";



include_once("f_form_main.php");
include_once("../function_js/js_fn_age.php");

?>


<script>
var age_amt = "";
//$("#txt_age_sut").html($("#age").val());
$("#birth_date").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});
$("#form_head_id").html("");

$(document).ready(function(){
$("#birth_date").focusout(function(){
   if($("#birth_date").val().trim() != ""){

     if(!validateDate($("#birth_date").val().trim())){
        $("#birth_date").notify("วันที่ไม่ถูกต้อง", "error");
     }

   }
}); //birthdate

});

function checkScreenPass_Covid(){
   var is_pass = 'Y';
   if($("#covid_hcw-N").prop("checked") == true){
     is_pass = 'N';
   }
   if($("#accept_proj-N").prop("checked") == true){
     is_pass = 'N';
   }
   if($("#thai_nation-N").prop("checked") == true){
     is_pass = 'N';
   }

   var arr_dob = changeToEnDate($('#birth_date').val()).split("-");
   age_amt = calculate_age(new Date(arr_dob[0], arr_dob[1], arr_dob[2]))

   if(age_amt < 18){
     is_pass = 'N';
     $("#age18-N").prop("checked", true);
   }
   else{
     $("#age18-Y").prop("checked", true);
   }

   $("#is_pass").val(is_pass);

}

function updateScreenID(){
  var aData = {
            u_mode:"update_screen_id",
            uid:'<? echo $uid; ?>',
            group_id:'<? echo $group_id; ?>',
            visit_date:'<? echo $visit_date; ?>',
            birth_date:$("#birth_date").val(),
            age:age_amt,
            open_link:'Y'
  };
  save_data_ajax(aData,"../w_proj_covid19/db_covid.php",updateScreenIDComplete);

}

function updateScreenIDComplete(flagSave, rtnDataAjax, aData){
  if(flagSave){
        //alert("screen id is "+rtnDataAjax.uid+"/"+$("#is_pass").val());
        if($("#is_pass").val() == "Y"){
          //alert("link0 "+rtnDataAjax.link);
          var form_id = "covid_visit_g<? echo $group_id; ?>";
          <?
          if($is_paper == "Y")
          echo "form_id = 'paper_covid_visit';  ";
          ?>
          var link = "f_form_proj.php?proj_id=COVID&form_id="+form_id+"&link="+rtnDataAjax.link;
          window.location = link;
        }
        else{
          var link = "../info/inf_txt.php?e=np&f=<? echo $form_name; ?>";
          window.location = link;
        }
  }
}



</script>
