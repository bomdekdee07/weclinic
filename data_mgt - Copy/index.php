<?

include_once("./a_app_info.php");
include_once("./in_auth.php");

include_once("inc_auth.php"); // set permission view, update, delete



?>
<script>

//**** modal setting var
var u_mode_setting = ""; // update mode in setting dialog
var cur_setting_id = ""; // current setting record id
var cur_setting_choice = ""; // current setting choice eg. specimen, testing_menu
var cur_setting_col_id = ""; // current setting primary key col  eg. specimen_id
var is_modal_select = 0; // select from outside component
var modalSettingSelect = {id:"", name:"", src:""}; // value to select & selected object
var cur_setting_title = "";
var cur_component_act ;


//****
</script>

<!doctype html>
<html>
<head>
  <meta http-equiv=Content-Type content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>weClinic Data Management</title>
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
    width:15%;
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

  <nav class="navbar navbar-expand-sm navbar-dark bg-info">
    <a class="navbar-brand mr-4" href="change_menu.php?mnu="><i class="fas fa-clinic-medical"></i> IHRI <b>we</b>Clinic </a> <h3><span class='badge badge-warning px-2 py-1'><? echo "$clinic_name";?></span></h3>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navb">


      <ul class="navbar-nav mr-auto">
<!--
        <li class="nav-item mnu-main" data-id="form">
          <a class="nav-link active" href="javascript:void(0)"><i class="fa fa-clipboard-list fa-lg" ></i>  Form</a>
        </li>
-->
        <li class="nav-item dropdown no-arrow mnu" >
          <a class="nav-link dropdown-toggle active" href="#" id="dataSettingDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-clipboard fa-lg" ></i> <span class="d-none d-md-inline-block"> Form Mgt</span>

          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dataSettingDropdown">

            <a class="dropdown-item mnu-main-setting" href="javascript:void(0);"  data-id="form_list" data-name="Form List"><i class="fa fa-database" aria-hidden="true"></i> Form List</a>
<!--
            <a class="dropdown-item mnu-main-setting" href="javascript:void(0);"  data-id="data_group" data-name="Form version"><i class="fa fa-table" aria-hidden="true"></i> Form Version</a>
-->
          </div>
        </li>

        <li class="nav-item dropdown no-arrow mnu" >
          <a class="nav-link dropdown-toggle active" href="#" id="dataSettingDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-cogs fa-lg" ></i> <span class="d-none d-md-inline-block"> Data Setting </span>

          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dataSettingDropdown">

            <a class="dropdown-item mnu-main-setting" href="javascript:void(0);"  data-id="data_group_main" data-name="Data Main"><i class="fa fa-database" aria-hidden="true"></i> Data Main</a>
            <a class="dropdown-item mnu-main-setting" href="javascript:void(0);"  data-id="data_group" data-name="Data Group"><i class="fa fa-table" aria-hidden="true"></i> Data Group</a>
            <a class="dropdown-item mnu-main-setting" href="javascript:void(0);"  data-id="data_list" data-name="Data List"><i class="fa fa-server" aria-hidden="true"></i> Data Item</a>

          </div>
        </li>

      </ul>



      <ul class="navbar-nav ml-auto mr-4">
        <li class="nav-item dropdown no-arrow active">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i> <span class="d-none d-md-inline-block"> <? echo "<b>$sc_id</b> $s_name [$job_name]" ?> </span>


          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item mnu-user" href="javascript:void(0);" data-id="w_change_pwd"><i class="fa fa-key" aria-hidden="true"></i> เปลี่ยนรหัสผ่าน</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="logout.php" ><span class='text-danger'><i class="fa fa-sign-out-alt" aria-hidden="true"></i> ออกจากระบบ</span></a>
          </div>
        </li>
      </ul>
      <!--
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" placeholder="Search" type="text">
        <button class="btn btn-success my-2 my-sm-0" type="button">Search</button>
      </form>
    -->
    </div>
  </nav>

<div class="container-fluid" style="margin-top:0px">
  <div id="div_main" class="my-2">
    <div id="div_main_menu" class="div-main">

    </div>
    <div id="div_main_setting" class="div-main">

    </div>
  </div>


    <div id = div_loading>
        <div class="v-align">
            <div id="loading_box">
              <h1><i class="fas fa-spinner fa-spin text-danger"></i> Loading </h1><br>
              <i class="fas fa-cat fa-lg text-info"></i> กรุณารอสักครู่

            </div>
        </div>
    </div>

</div>

<div class="jumbotron text-center" style="margin-bottom:0">
  <? include_once("./inc_footer.php"); ?>
</div>


  <!-- The Modal  -->
  <div class="modal fade" id="modal_setting" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h4 class="modal-title ">
            <i class="fa fa-cog fa-lg" aria-hidden="true"></i>
            <span id="setting_detail_title" ></span></h4>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body" id="div_modal_setting_detail" style="overflow-y: auto;">
          Modal body..
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" id="btn_delete_setting" class="btn btn-danger mr-auto" style="display:none;"><i class="fa fa-times fa-lg" ></i> Delete Record</button>
          <div id="div_modal_btn_list" class="div-setting-btn">
            <button type="button" id="btn_close_setting" class="btn btn-secondary" data-dismiss="modal"> <i class="fa fa-times fa-lg" ></i> Close</button>
          </div>
          <div id="div_modal_btn_detail" class="div-setting-btn" style="display:none;">

            <button type="button" id="btn_save_setting" class="btn btn-success" > <i class="fa fa-save fa-lg" ></i> Save Data</button>
            <button type="button" id="btn_cancel_setting" class="btn btn-danger" > <i class="fa fa-times-circle fa-lg" ></i> Cancel</button>
          </div>
        </div>


      </div>
    </div>
  </div>




    <!-- The Modal select -->
    <div class="modal fade" id="modal_select" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header bg-primary text-white">
            <h4 class="modal-title ">
              <i class="fa fa-hand-pointer fa-lg" aria-hidden="true"></i>
              <span id="select_detail_title" ></span></h4>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>

          <!-- Modal body -->
          <div class="modal-body" id="div_modal_select_detail" style="overflow-y: auto;">
            Modal body..
          </div>

          <!-- Modal footer -->
          <div class="modal-footer">
            <div id="div_modal_btn_select1" class="div-select-btn">
              <button type="button" id="btn_close_select" class="btn btn-secondary" data-dismiss="modal"> <i class="fa fa-times fa-lg" ></i> Close</button>
            </div>

          </div>


        </div>
      </div>
    </div>


  <? include_once("./in_modal_content.php"); ?>
  <? include_once("./in_modal_change_pwd.php"); ?>
  <? include_once("./in_modal_user_login_dialog.php"); ?>

</body>
</html>
<script>


$(document).ready(function(){

  $.notify.defaults({ autoHideDelay: 3000 });

  loadingHide();
//loadingShow();
  $(document).on('keydown', '.input-decimal', function() {
          //console.log("keycode: "+event.keyCode);
          if (event.shiftKey == true) {
              if(event.keyCode == 188 || event.keyCode == 190 || event.keyCode == 61){

              }
              else event.preventDefault();

          }
// allow -+><
          if ((event.keyCode >= 48 && event.keyCode <= 57) ||
          (event.keyCode >= 96 && event.keyCode <= 105) ||
          event.keyCode == 8 || event.keyCode == 9 ||
          event.keyCode == 37 || event.keyCode == 39 ||
          event.keyCode == 46 || event.keyCode == 110 ||
          event.keyCode == 107 || event.keyCode == 109 ||
          event.keyCode == 173 || event.keyCode == 61 ||
          event.keyCode == 188 ||event.keyCode == 190) {
            if($(this).val().indexOf('.') !== -1){ // decimal
              if(typeof $(this).data("digit") !== "undefined"){
                var digit = $(this).val().substring($(this).val().indexOf('.'), $(this).val().length);

                if(digit.length > parseInt($(this).data("digit"))){
                  event.preventDefault();
                }
              }
            }

          } else {
              event.preventDefault();
          }


      });


$(".mnu-main").click(function(){
  // alert("main "+$(this).attr("id"));
   var link = "mnu_"+$(this).data("id")+".php";

   $("#div_main_menu").html("");
   $("#div_main_menu").load("data_mgt/"+link, function(){

   });
}); // mnu-main

$(".mnu-main-setting").click(function(){
  // alert("main "+$(this).attr("id"));
   var link = "mnu_"+$(this).data("id")+".php";

   $("#div_main_setting").html("");
   $("#div_main_setting").load("data_mgt/"+link, function(){

   });
}); // mnu-main



$("#btn_save_setting").click(function(){
   saveSettingData();
}); // btn_save_setting
$("#btn_close_setting").click(function(){
   $("#modal_setting").modal("hide");
}); // btn_cancel_setting
$("#btn_cancel_setting").click(function(){
   closeSettingData();
}); // btn_cancel_setting
$("#btn_delete_setting").click(function(){
   deleteSettingData();
}); // btn_save_setting


  $(".mnu-setting").click(function(){
    var link = $(this).data("id")+".php";
    //alert("link "+link+"/"+$(this).data("name"));
    $("#div_modal_setting_detail").html("");
    $("#div_modal_setting_detail").load("data_mgt/"+link, function(){
        $('#modal_setting').modal('show');
        showMenuSettingDiv("list");
        is_modal_select = 0;
    });

  }); // .mnu-setting




});


// choice: setting choice , comp: component to get data
function openSettingDlgSelect(choice, comp_id){
  var link = "mnu_setting_"+choice+".php";
  //alert("opensettingselect "+link);
  $("#div_modal_setting_detail").html("");
  $("#div_modal_setting_detail").load("data_mgt/"+link, function(){
      $('#modal_setting').modal('show');
      showMenuSettingDiv("list");
      is_modal_select = 1 ;
      //alert("xx "+$('#'+comp_id).val());
      cur_component_act = $('#'+comp_id);
  });
}

function openDlgSelect(choice, comp_id){
  //console.log("openDlgSelect "+comp_id);
  var link = "dlg_select_"+choice+".php";
  $("#div_modal_select_detail").html("");
  $("#div_modal_select_detail").load("data_mgt/"+link, function(){
      $('#modal_select').modal('show');
      cur_component_act = $('#'+comp_id);
      searchData_DlgSelect();
      //alert("xx "+$('#'+comp_id).val());

  });
}
function clearDlgSelect(comp_id){
  $('#'+comp_id).data("id", "");
  $('#'+comp_id).val("Select");
}



// choice: setting choice , comp: component to get data
function openSettingDlgSelect2(choice, comp){
  var link = "mnu_setting_"+choice+".php";
  $("#div_modal_setting_detail").html("");
  $("#div_modal_setting_detail").load("data_mgt/"+link, function(){
      $('#modal_setting').modal('show');
      showMenuSettingDiv("list");
      is_modal_select = 1 ;
      cur_component_act = comp;

  });
}

// set data in select component (select data from dialog select)

function checkDuplicateRowRecID(comp){
  var rec_id = comp.data("id");
  if(rec_id == "") return;

  var tbl_name = comp.data("tbl");
//alert("checkDuplicateRowRecID "+rec_id+"/"+tbl_name);
  $("#"+tbl_name+" tbody .data-id").each(function(ix,objx){
      // console.log("val id :"+$(objx).data("id"));
       if($(objx).data("id") == rec_id && comp.attr("id") !=$(objx).attr("id") ){
         comp.val("Select");
         comp.data("id", "");
         comp.notify("Duplicate ID", "error");
       }
  });
  comp.data("id", rec_id);
}


function checkDuplicateTextID(comp_name){
  var rec_id = $("#"+comp_name).val();
  if(rec_id == "") return;

  var tbl_name = $("#"+comp_name).data("tbl");

  $("#"+tbl_name+" tbody .data-id").each(function(ix,objx){
       //console.log("val id :"+$(objx).val());
       if($(objx).val() == rec_id && $("#"+comp_name).attr("id") !=$(objx).attr("id") ){
         $("#"+comp_name).val("");
         $("#"+comp_name).data("id", "");
         $("#"+comp_name).notify("Duplicate ID", "error");
         alert("Duplicate ID ");
       }
  });
  $("#"+comp_name).data("id", rec_id);
}

// set data in select component (select data from dialog select)
function setSelectData(component, id, name){
  component.val(name);
  component.data("id", id);
}
// set data select from setting dialog to component and close dialog
function setDataSettingToComponent(id, name){
  setSelectData(cur_component_act, id, name);
  checkDuplicateRowRecID(cur_component_act);
  $('#modal_setting').modal('hide');
}

// set data select from select dialog to component and close dialog
function setDataSelectToComponent(id, name){
  setSelectData(cur_component_act, id, name);
  checkDuplicateRowRecID(cur_component_act);
  $('#modal_select').modal('hide');
}

function showMenuSettingDiv(choice){
  //alert("showMenuSettingDiv "+choice);
  $(".div-setting-menu").hide();
  $(".div-setting-btn").hide();
  $("#div_setting_"+choice).show();
  $("#div_modal_btn_"+choice).show();


  <?
    if($is_delete == '1'){
      echo '
      if(choice == "list")
      $("#btn_delete_setting").hide();
      else if(choice == "detail")
      $("#btn_delete_setting").show();
      ';
    }
  ?>
}

function addRowData_Setting(id, arrData){
  var row_content = "";
  var mode = "";
  if(is_modal_select == 1){
    mode += '<button class="btn btn-primary btn-sm" type="button" data-toggle="tooltip" data-placement="bottom" title="Select Data" onclick=\"getSettingData(\''+id+'\')\"><i class="fa fa-hand-pointer"></i> Select</button>';

  }
      //row_content += '<td><button class="btn btn-primary btn-sm btn_setting_pid" type="button" data-uid="'+uid+'" data-s_date="'+screen_date+'"  data-v_date="'+visit_date+'" data-s_consent="'+s_consent+'"><i class="fa fa-user" ></i> <b>'+pid+'</b> </button></td>';
<?
  if($is_data == '1'){
      echo "mode += ' <button class=\"btn btn-warning btn-sm\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit Data\"><i class=\"fa fa-edit fa-lg\" onclick=\"openSettingData(\''+id+'\')\"></i></button>';";
  }
  /*
  if($is_delete == '1'){
      echo "mode += ' <button class=\"btn btn-danger btn-sm\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Data\"><i class=\"fa fa-times\" onclick=\"deleteSettingData(\''+id+'\')\"></i></button>';";
  }
  */
?>
      row_content += '<td>'+mode+'</td>';

      arrData.forEach(function (itmData) {
          row_content += '<td>'+itmData+'</td>';
      });

    var txt_row = '<tr class="r_setting" id="r'+cur_setting_choice+id+'"  data-name="'+name+'" >' ;
    txt_row += row_content;
    txt_row += '</tr">';
    return txt_row;
}

function addRowData_DlgSelect(id, arrData){
  var row_content = "";
  var mode = '<button class="btn btn-primary btn-sm" type="button" data-toggle="tooltip" data-placement="bottom" title="Select Data" onclick=\"getDlgSelectData(\''+id+'\')\"><i class="fa fa-hand-pointer"></i> Select</button>';

      row_content += '<td>'+mode+'</td>';

      arrData.forEach(function (itmData) {
          row_content += '<td>'+itmData+'</td>';
      });

    var txt_row = '<tr class="r_sel" id="r_sel'+id+'">' ;
    txt_row += row_content;
    txt_row += '</tr">';
    return txt_row;
}



function loadingShow(){
  $("#div_loading").show();
  $("#div_main").hide();
}
function loadingHide(){
  $("#div_loading").hide();
  $("#div_main").show();
}


StartWarningTimer();
</script>


<? include_once("./inc_foot_include.php"); ?>
<? include_once("./function_js/js_fn_validate.php"); ?>
<? include_once("./in_savedata.php");
?>
