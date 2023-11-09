<?

include_once("inc_auth.php");
include_once("../function/in_fn_link.php");

$is_update = "";
$is_delete = "";
$is_view = "";
if(isset($_SESSION)){
  if(isset($_SESSION["auth_covid19"])){
     $auth= $_SESSION["auth_covid19"];

     $is_view = (isset($auth["view"]))?$auth["view"]:"";
     $is_update = (isset($auth["data"]))?$auth["data"]:"";
     $is_delete = (isset($auth["delete"]))?$auth["delete"]:"";
  }
}




$open_link="N";
$uic="";
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";

$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
$is_online = isset($_GET["is_online"])?$_GET["is_online"]:""; // back date filled


$form_id = "covid_visit_g$group_id";
$form_name = "COVID Group $group_id ";
$is_tc="Y";

$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$before_save_function = ""; // trigger before save function
$after_save_function = ""; // trigger after save function
$before_save_data = ""; // trigger before save data (after validate data before save)
$require_form_complete ="Y";
$initJSForm = ''; // initial js in f_form_main.php
//$open_link_page = ""; // trigger open target page after save complete
$screen_date_open_link = $visit_date; // for open_link=Y

//echo "enrter $open_link/$is_online";

if($is_update == '1'){
  $initJSForm .= '$("#btn_save").show();';
}
else{
  $initJSForm .= '$("#btn_save").hide();';
}


$option_showhide = "
// show/hide question
shData['fearworry6-1'] = {dtype:'radio',
show_q:'fearworry6_1,fearworry6_2'};
shData['fearworry6-2'] = {dtype:'radio',
hide_q:'fearworry6_1,fearworry6_2'};

";

if($group_id == '3')
$option_showhide .= "
shData['job_position-1'] = {dtype:'radio',
show_q:'job_position1',
hide_q:'job_position2,job_position3'};
shData['job_position-2'] = {dtype:'radio',
show_q:'job_position2',
hide_q:'job_position1,job_position3'};
shData['job_position-3'] = {dtype:'radio',
show_q:'job_position3',
hide_q:'job_position2,job_position1'};

";


?>

<div class="my-4" id="divCovid_Form">
<?
include_once("f_form_main_x.php");
//include_once("f_form_main.php");
?>
</div>


<script>


$(document).ready(function(){


});



</script>
