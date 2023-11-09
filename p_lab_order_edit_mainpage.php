<?

$s_id= isset($_GET["s_id"])?$_GET["s_id"]:"";
if($s_id == "") {
  echo "<center>INVALID: No staff id found</center>";
  exit();

}

include_once("in_db_conn.php");

      $txt_sale_option = "";
      $query = "SELECT * FROM sale_option
      WHERE is_enable = 1 ORDER BY data_seq
      ";

      $stmt = $mysqli->prepare($query);
      if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
          $txt_sale_option .= "<option value='".$row['sale_opt_id']."'>".$row['sale_opt_name']."</option>";
        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();

      $txt_laboratory_option = "";
      $query = "SELECT * FROM p_lab_laboratory
      ORDER BY laboratory_id
      ";

      $stmt = $mysqli->prepare($query);
      if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
          $txt_laboratory_option .= "<option value='".$row['laboratory_id']."'>".$row['laboratory_name']."</option>";
        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();

?>


<!doctype html>
<html>
<head>
  <meta http-equiv=Content-Type content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>weClinic LAB Order</title>

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
var v_sale_option = "<? echo $txt_sale_option; ?>";
var v_laboratory_option="<? echo $txt_laboratory_option; ?>";

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
include_once("./lab/p_lab_order_edit.php");
?>
</body>
</html>
<script>

$(document).ready(function(){

});
//console.log("enter1");

</script>
