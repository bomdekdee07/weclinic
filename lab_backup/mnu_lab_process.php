<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

include_once("inc_auth.php"); // set permission view, update, delete



?>


<script>

//**** lab specimen collect  var

</script>


<div id='div_lab_process_list' class='div-lab-process my-0'>
  <div class="row mt-0">
    <div class="col-sm-12">
      <h4><i class="fa fa-hdd fa-lg" ></i> <b>Lab Process</b> </h4>


    </div>

  </div>

  <div class="row my-2" >

    <div class="col-sm-2 pr-1">
      <label for="sel_laboratory">เลือก Laboratory:</label>
      <select id="sel_laboratory" class="form-control form-control-sm" >

      </select>
    </div>
    <div class="col-sm-2 pr-1">
      <label for="sel_test_menu">เลือก Test Menu:</label>
      <select id="sel_test_menu" class="form-control form-control-sm" >

      </select>
    </div>
    <div class="col-sm-2 pr-1">
      <label for="sel_process_status">เลือกสถานะ:</label>
      <select id="sel_process_status" class="form-control form-control-sm" >
         <option value=""> ALL </option>
         <option value="P0"> Lab Process Working </option>
         <option value="P1"> Lab Process Complete </option>
      </select>
    </div>



    <div class="col-sm-2 pr-1">
      <label for="txt_search_lab_process">ค้นหา Lab Serial No:</label>
      <input type="text" id="txt_search_lab_process" class="form-control form-control-sm" placeholder="ค้นหาจาก lab serial no">
    </div>
    <div class="col-sm-2 pr-1">
      <label for="txt_search_lab_process_uid">ค้นหา UID หรือ Barcode:</label>
      <input type="text" id="txt_search_lab_process_uid" class="form-control form-control-sm" placeholder="ค้นหาจาก UID หรือ Barcode">
    </div>

    <div class="col-sm-1 pr-1">
      <label for="btn_load_lab_process" class="text-white">.</label>
      <button class="btn btn-primary btn-sm form-control" type="button" id="btn_load_lab_process"><i class="fa fa-search" ></i> Search</button>
    </div>
    <div class="col-sm-1 ">
      <label for="btn_new_lab_process" class="text-white">.</label>
      <button class="btn btn-success btn-sm form-control" type="button" id="btn_new_lab_process" style="display:none;"><i class="fa fa-plus" ></i> New Lab Process</button>
    </div>

   </div>

   <div class="mt-2"  style="min-height: 300px; border:1px solid grey;">
     <table id="tbl_lab_process_list" class="table table-bordered table-sm table-striped table-hover">
         <thead>
           <tr>
             <th>Lab Serial No.</th>
             <th>Laboratory</th>
             <th>Test Menu</th>
             <th>Specimen</th>
             <th>Lab Result</th>
             <th>Status</th>
             <th>Start</th>
             <th>Complete</th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_lab_process -->


<div id="div_lab_process_detail" class='div-lab-process my-0' style="display:none">

  <div class="card" id="div_lab_process_info">
    <div class="card-header bg-primary text-white" style="max-height: 3rem;">
        <div class="row ">
           <div class="col-sm-3">
             <h4><i class="fa fa-hdd fa-lg" aria-hidden="true"></i> <b>Lab Process</b></h4>
           </div>

           <div class="col-sm-5">
               Laboratory/Test Menu: <b><u><span id = "txt_lab_process_title"></span></b></u>
           </div>

           <div class="col-sm-3">
              Status: <input type="text" id="lab_process_status" size="20" disabled>
           </div>
           <div class="col-sm-1">
             <button type="button" id="btn_close_lab_process_detail" class="btn btn-sm btn-white" > <i class="fa fa-times fa-lg" ></i> Close</button>
           </div>


        </div>
    </div>
    <div class="card-body">

      <div class="row my-1">
        <div class="col-sm-2">
           <div class="mb-2">
             Lab Serial No:<br>
             <input type="text" id="txt_lab_serial_no" class="form-control form-control-sm bg-warning" disabled>
           </div>
           <div>
             Start Time:<br>
             <input type="text" id="txt_time_start" class="form-control form-control-sm " disabled>
           </div>
           <div>
             Complete Time:<br>
             <input type="text" id="txt_time_complete" class="form-control form-control-sm " disabled>
           </div>


           <div class="mt-4" id="div_lab_process_note" style="display:none">
             <label for="lab_process_note">
               <button type="button" id="btn_add_note" class="btn btn-info btn-sm mx-1" > <i class="fa fa-edit fa-lg" ></i> Add Lab Process Note</button>
             </label>
             <textarea id="lab_process_note" rows="4"  data-title="Note" data-odata="" class="form-control form-control-sm bg-white" placeholder="Lab Process Note" disabled></textarea>

           </div>

        </div>
        <div class="col-sm-10">
          <div id= "div_lab_process_specimen" class="div-lab-detail">
          <?
          include_once("mnu_lab_process_specimen.php");
          ?>
          </div>
          <div id= "div_lab_process_result" class="div-lab-detail">
          <?
          include_once("mnu_lab_process_result.php");
          ?>
          </div>
        </div>



      </div>



    </div><!-- cardbody -->

    <div class="card-footer ">
      <button type="button" id="btn_start_lab_process" class="btn btn-success mr-auto lab-process-specimen"><i class="fa fa-hdd fa-lg" ></i> Start Lab Process</button>
      <button type="button" id="btn_check_lab_result" class="btn btn-warning mr-auto lab-process-result"><i class="fa fa-check-double fa-lg" ></i> Check & Confirm Lab Result</button>

      <button type="button" id="btn_save_lab_result" class="btn btn-success mr-auto lab-process-result"><i class="fa fa-save fa-lg" ></i> Save Lab Result</button>

<!--
      <button type="button" id="btn_close_lab_process_detail" class="btn btn-secondary mx-1 float-right lab-process-result" > <i class="fa fa-time fa-lg" ></i> Close</button>
-->
    </div>
  </div>




</div><!-- div_lap_process_specimen -->



  <!-- The Modal  -->
  <div class="modal fade" id="modal_new_lab_process" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h4 class="modal-title ">
            <i class="fa fa-file fa-lg" aria-hidden="true"></i>
            Select New Lab Process</h4>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body" id="div_new_lab_process" style="overflow-y: auto;">

        </div>

        <!-- Modal footer -->
        <div class="modal-footer">

        </div>
      </div>
    </div>
  </div>




<?
$col_note = "lab_process";
$tbl_note = "p_lab_process";
include_once("dlg_add_lab_note.php");
?>




<script>
var choice_lab_process = "";
var cur_lab_serial_no = "";
var cur_lab_process_lab_group_id = "";
var cur_lab_process_laboratory_id = "";
var lst_data_process = [];

$(document).ready(function(){

  initDataLabProcess();


  var row_num_spc = 0;


  $("#btn_load_lab_process").click(function(){
     searchData_labProcess();
  }); // btn_load_lab_process

  $("#txt_search_lab_process").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_labProcess();
    }
  });


  $("#btn_new_lab_process").click(function(){
     addNewLabProcess();
  }); // btn_search_specimen_collect
  $("#btn_start_lab_process").click(function(){
     startLabProcess();
  }); // btn_start_lab_process

  $("#btn_save_lab_result").click(function(){
     saveLabResultData();
  }); // btn_save_lab_result

  $("#btn_check_lab_result").click(function(){
     confirmLabResultData();
  }); // btn_load_lab_process



  $("#btn_close_lab_process_detail").click(function(){
     showLabProcessDiv("list");
  }); // btn_load_lab_process

  $("#btn_add_note").click(function(){

         var lst_data = [];
         lst_data.push({name:"lab_serial_no", value:cur_lab_serial_no});
         openAddLabNote(
           $("#lab_process_note"),
           lst_data,
           "p_lab_process",
           "Add Lab Process Note",
           "<? echo $s_name; ?>"
         );
  }); // btn_add_note


});

function initDataLabProcess(){
  $("#sel_process_status").val("P0");
  selectLaboratoryList();
  selectLabTestMenuList();
//searchData_labProcess();
}
function clearLabProcessDetail(){
  $('.r_lab_result').remove();
  $(".r_spc_chk").remove();
  $("txt_time_start").val("");
  $("txt_time_complete").val("");
  $("lab_process_note").val("");
  cur_lab_serial_no = "";
  cur_lab_process_lab_group_id = "";
  cur_lab_process_laboratory_id = "";
}
function searchData_labProcess(){
  var aData = {
      u_mode:"select_lab_process",
      laboratory_id:$('#sel_laboratory').val(),
      lab_group_id:$('#sel_test_menu').val(),
      lab_process_status:$('#sel_process_status').val(),
      txt_search:$('#txt_search_lab_process').val(),
      txt_search_uid:$('#txt_search_lab_process_uid').val()
  };
  save_data_ajax(aData,"lab/db_lab_process.php",searchData_labProcess_Complete);
}

function searchData_labProcess_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

    $('.r_lab_process').remove(); // row data list
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_LabProcess(
             dataObj.lab_serial_no, dataObj.status_id, dataObj.status_name,
             dataObj.lbt_name, dataObj.test_menu, dataObj.time_start, dataObj.time_lab_confirm
            );

        }//for

        $('#tbl_lab_process_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }
  }
}


/*
function addNewLabProcess(){

 var flag_valid = 1;

 if($('#sel_laboratory').val() == ""){
   flag_valid = 0;
   $('#sel_laboratory').notify("Please choose Laboratory for new lab process.", "info");
 }
 if($('#sel_test_menu').val() == ""){
   flag_valid = 0;
   $('#sel_test_menu').notify("Please choose Test Menu for new lab process.", "info");
 }
  if(flag_valid == 1){
     cur_lab_serial_no = "";
     cur_lab_process_laboratory_id = $('#sel_laboratory').val();
     cur_lab_process_lab_group_id = $('#sel_test_menu').val();
     addLabProcess_specimen();
    // showLabProcessDiv("detail");
  }

}
*/


function addNewLabProcess(){
  var aData = {
      u_mode:"select_new_lab_process"
  };
  save_data_ajax(aData,"lab/db_lab_process.php",addNewLabProcessComplete);
}

function addNewLabProcessComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "<center><h4>Laboratory / Test Menu</h4></center>";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            txt_row += "<div class='my-2'><button class='btn btn-success form-control' onclick='openNewProcess(\""+dataObj.l_id+"\",\""+dataObj.g_id+"\" )'> "+dataObj.l_name+" / "+dataObj.g_name+"</button></div>";

        }//for
      $("#div_new_lab_process").html(txt_row);
      $("#modal_new_lab_process").modal("show");
    }
    else{
      $("#div_new_lab_process").html("");
      $.notify("No lab test menu found.", "info");
    }
  }
}

function openNewProcess(laboratoryID, testMenuID){
  cur_lab_serial_no = "";
  cur_lab_process_laboratory_id = laboratoryID;
  cur_lab_process_lab_group_id = testMenuID;

  $('#sel_laboratory').val(laboratoryID);
  $('#sel_test_menu').val(testMenuID);
  addLabProcess_specimen();
  $("#modal_new_lab_process").modal("hide");
}


function addRowData_LabProcess(lab_serial_no,
  status_id, status_name, laboratory_name, lab_group_name,
  time_start, time_complete
){
    if(time_complete == null) time_complete="";

    if(status_id == "P1") status_name = "<span class='text-success'>"+status_name+"</span>";

    var txt_row = '<tr class="r_lab_process" id="'+lab_serial_no+'" >' ;
    txt_row += '<td >';
    txt_row += '<b>'+lab_serial_no+'</b>';
    txt_row += '</td>';
    txt_row += '<td >';
    txt_row += laboratory_name;
    txt_row += '</td>';
    txt_row += '<td >';
    txt_row += lab_group_name;
    txt_row += '</td>';
    txt_row += '<td width="20%">';
    txt_row += '<button class="btn btn-primary" type="button" onclick="openSP(\''+lab_serial_no+'\');"><i class="fa fa-vials fa-lg" ></i> Specimen</button>';
    txt_row += '</td width="20%">';
    txt_row += '<td >';
    txt_row += '<button class="btn btn-warning" type="button" onclick="openResult(\''+lab_serial_no+'\');"><i class="fa fa-clipboard-list fa-lg" ></i> Result</button>';
    txt_row += '</td>';
    txt_row += '<td >';
    txt_row += status_name;
    txt_row += '</td>';

    txt_row += '<td >';
    txt_row += time_start;
    txt_row += '</td>';
    txt_row += '<td >';
    txt_row += time_complete;
    txt_row += '</td>';


    txt_row += '</tr">';
    $("#tbl_lab_process_list tbody").append(txt_row);

}





function selectLabTestMenuList(){
  var aData = {
      u_mode:"select_lab_test_menu_list"
  };
  save_data_ajax(aData,"lab/db_lab_test_menu.php",selectLabTestMenuListComplete);
}

function selectLabTestMenuListComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){
    //  $("#sel_test_menu").val([]);
      $("#sel_test_menu").empty();
      $("#sel_test_menu").append(new Option("All Lab Testing Menu", ""));
      var datalist = rtnDataAjax.datalist;
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            $("#sel_test_menu").append(new Option(dataObj.name, dataObj.id));
        }//for

    }
    else{
      $.notify("No lab test menu found.", "info");
    }
  }
}


function selectLaboratoryList(){
  var aData = {
      u_mode:"select_setting_list",
      setting_choice:"laboratory"

  };
  save_data_ajax(aData,"lab/db_lab_setting.php",selectLaboratoryListComplete);
}

function selectLaboratoryListComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){
    //  $("#sel_test_menu").val([]);
      $("#sel_laboratory").empty();
      $("#sel_laboratory").append(new Option("All Laboratory", ""));
      var datalist = rtnDataAjax.datalist;
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            $("#sel_laboratory").append(new Option(dataObj.name, dataObj.id));
        }//for
    //  $("#sel_laboratory").val(cur_lab_order_sale_opt_id);
    }
    else{
      $.notify("No Laboratory found.", "info");
    }
  }
}

function setLabProcessData(objData){
  clearLabProcessDetail();
  if(objData.time_complete == "null") objData.time_complete = "";
  $("#txt_lab_serial_no").val(objData.lab_serial_no);
  $("#txt_time_start").val(objData.time_start);
  $("#txt_time_complete").val(objData.time_lab_confirm);
  $("#lab_process_status").val(objData.status_name);

  $("#lab_process_note").val(objData.lab_process_note);
  $('#txt_lab_process_title').html(objData.group_info);
  cur_lab_serial_no = objData.lab_serial_no;
  cur_lab_process_lab_group_id = objData.lab_group_id;
  cur_lab_process_laboratory_id = objData.laboratory_id;


  $(".div-lab-detail").hide();

  if(choice_lab_process == "lab_specimen"){
    $(".lab-process-specimen").show();
    $(".lab-process-result").hide();
    $("#div_lab_process_specimen").show();
    $("#btn_start_lab_process").hide();
    $("#btn_load_specimen_check").hide();

  }
  else if(choice_lab_process == "lab_result"){
    $(".lab-process-specimen").hide();
    $(".lab-process-result").show();
    $("#div_lab_process_result").show();

    if(objData.status_id == "P1"){
      $("#btn_check_lab_result").hide();
      $("#btn_save_lab_result").hide();
    }
  }

/*
  if(objData.status_id == "P0"){ // lab process working
     $(".lab-process-specimen").hide();
     $(".lab-process-result").show();
     $("#div_lab_process_result").show();
  }
  else if(objData.status_id == "P1"){ // lab result complete (complete)
     $(".lab-process-result").show();
     $("#div_lab_process_result").show();
  }

  */
}




function showLabProcessDiv(choice){
  //alert("showLabProcessDiv "+choice);
  $(".div-lab-process").hide();
  $("#div_lab_process_"+choice).show();
}

</script>
