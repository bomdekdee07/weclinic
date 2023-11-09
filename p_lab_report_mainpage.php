<?

$s_id= isset($_GET["s_id"])?$_GET["s_id"]:"";
if($s_id == "") {
  echo "<center>INVALID: No staff id found</center>";
  exit();

}

include_once("in_db_conn.php");
?>


<!doctype html>
<html>
<head>
  <meta http-equiv=Content-Type content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>weClinic LAB Report</title>

<link rel="stylesheet" href="asset_iclinic/css/pribta.css">
<link rel="stylesheet" href="asset_iclinic/css/pop99.css">
<link rel="stylesheet" href="asset_iclinic/css/themeclinic1.css">

<?
include_once("./inc_head_include.php");
?>
<script src="asset_iclinic/js/pribta.js"></script>
<script src="asset_iclinic/js/pop99.js"></script>

<style>

.tbl-mtn-list tr td:first-child{
    width:1%;
    white-space:nowrap;
}
.input-right {
    text-align: right;
}

#modal_test_menu .modal-lg {
  /*  max-height: 80%; */
    max-width: 90%;
}

.modal-dialog {
  height: 90%; /* = 90% of the .modal-backdrop block = %90 of the screen */
}
.modal-content {
  height: 100%; /* = 100% of the .modal-dialog block */
}
.modal-header {
    padding: 0.5rem;
}

#div_loading {
  /*  background-color:#EEE;*/
    display: table;
    width: 100%;
    height: 400px;
}
#loading_box {

    display: inline-block;
    vertical-align: top;
}
.v-align {
    padding: 10px;
    display: table-cell;
    text-align: center;
    vertical-align: middle;
}

.div-inline{
  display: inline;
}


</style>

</head>
<body>

  <?

    include_once("inc_foot_include.php");
    include_once("function_js/js_fn_validate.php");
    include_once("in_savedata.php");

  ?>
<script>


function loadingShow(){
  /*
  $("#div_loading").show();
  $("#div_main").hide();
  */
}
function loadingHide(){
  /*
  $("#div_loading").hide();
  $("#div_main").show();
  */
}
function extendSession(){

}
function myModalDlgLogin(){

}



</script>
<?
include_once("./lab/p_lab_report.php");
?>
</body>
</html>
<script>

$(document).ready(function(){

});
//console.log("enter1");

</script>
