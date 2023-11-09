<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

include_once("inc_auth.php"); // set permission view, update, delete



?>


<script>

//**** lab test menu  var
var u_mode_test_menu = ""; // update mode in test menu
var cur_test_menu_id = ""; // current test menu id
var cur_test_menu_title = "";

//**** lab test   var
var u_mode_lab_test = "";
var cur_lab_test_id = "";

//****
var lst_delete_data = []; // delete list data
</script>


<div id='div_lab_test_list' class='div-testing-menu my-0'>
  <div class="row mt-0">
    <div class="col-sm-6">
      <h4><i class="fa fa-flask fa-lg" ></i> <b>Lab Test Menu</b> <button class="btn btn-info btn-sm" type="button" id="btn_new_test_menu"><i class="fa fa-plus" ></i> เพิ่ม Testing Menu</button></h4>


    </div>
    <div class="col-sm-6">
       <span id="test_menu_title" class="text-primary"></span>
       <div id="test_menu_btn" style="display:none;">
       <button class="btn btn-primary btn-sm" type="button" id="btn_edit_test_menu"><i class="fa fa-cog" ></i> แก้ไข Testing Menu</button>
       <button class="btn btn-success btn-sm" type="button" id="btn_new_test_txt"><i class="fa fa-plus" ></i> เพิ่ม Lab Test (อักษร)</button>
       <button class="btn btn-warning btn-sm" type="button" id="btn_new_test_num"><i class="fa fa-plus" ></i> เพิ่ม Lab Test (ตัวเลข)</button>
       </div>
    </div>
  </div>

  <div class="row mt-0">
    <div class="col-sm-4">
      <label for="sel_test_menu">เลือก Testing Menu:</label>
      <select id="sel_test_menu" class="form-control form-control-sm" >

      </select>
    </div>
    <div class="col-sm-6">
      <label for="txt_search_lab_test">คำค้นหา:</label>
      <input type="text" id="txt_search_lab_test" class="form-control form-control-sm" placeholder="พิมพ์คำค้นหา Lab ID หรือ Lab Test Name">
    </div>
     <div class="col-sm-2">
       <label for="btn_search_test_menu" class="text-white">.</label>
      <button class="btn btn-primary btn-sm form-control form-control-sm " type="button" id="btn_search_test_menu"><i class="fa fa-search" ></i> ค้นหา</button>
     </div>
   </div>
   <div class="mt-2" id="div_lab_test_list_data" style="min-height: 300px; border:1px solid grey;">

     <table id="tbl_lab_test_main_list" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
         <thead>
           <tr>
             <th></th>
             <th>Testing Menu</th>
             <th>Lab ID</th>
             <th>Lab Test Name</th>
             <th>Type</th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_lab_test_list -->

<div id='div_lab_test_menu_detail' class="div-testing-menu my-0" style="display:none;">
  <? include_once("mnu_lab_test_menu_data.php"); ?>
</div> <!-- div_lab_test_menu_detail -->

<div id='div_lab_test_detail' class="div-testing-menu my-0" style="display:none;">
  <? include_once("mnu_lab_test_data.php"); ?>
</div> <!-- div_lab_test_detail -->





<script>

$(document).ready(function(){
  initDataTestMenu();
  $("#btn_search_test_menu").click(function(){
     searchData_LabTest();
  }); // btn_search_test_menu

  $("#txt_search_lab_test").on("keypress",function (event) {
  //  console.log("key "+event.which);
    if (event.which == 13) {
      searchData_LabTest();
    }
  });

  $("#btn_new_test_menu").click(function(){
     addTestMenu();
  }); // btn_new_test_menu
  $("#btn_edit_test_menu").click(function(){
     selectTestMenuDetail($("#sel_test_menu").val());
  }); // btn_edit_test_menu

  $("#btn_new_test_txt").click(function(){
     addLabTest("txt");
  }); // btn_edit_test_menu
  $("#btn_new_test_num").click(function(){
     addLabTest("num");
  }); // btn_edit_test_menu




  $("#sel_test_menu").on("change",function (event) {
    changeTestMenu();
  });


});
function initDataTestMenu(){
  selectLabTestMenuList();
/*
  addRowData_LabTest(
   'ALP', 'ALP', 'Chemistry', 'num'
  );
  */
}

function addTestMenu(){
  u_mode_test_menu = "add_test_menu";
  clearData_lab_test_menu();
  $('#lab_group_name').focus();
  $('#lab_group_id').val("ADD NEW");
  showMenuTestDiv("menu_detail");
}
function addTestMenuComplete(id, name){
  $("#sel_test_menu").append(new Option(name, id));
  $("#sel_test_menu").val(id);
  cur_test_menu_id = id;
}

// change to current test menu page
function changeTestMenu(){
  cur_test_menu_id = $("#sel_test_menu").val();
  cur_test_menu_title = $("#sel_test_menu option:selected").text();
  $("#test_menu_title").html('<h4><b>'+cur_test_menu_title+'</b></h4>');

  if($("#sel_test_menu").val() != "%"){
    $("#test_menu_btn").show();
  }
  else{
    $("#test_menu_btn").hide();
  }
  $(".r_labtest").remove();

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
      $("#sel_test_menu").append(new Option("All Lab Testing Menu", "%"));
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



function searchData_LabTest(){
  var aData = {
      u_mode:"select_lab_test_list",
      txt_search:$('#txt_search_lab_test').val(),
      group_id:$("#sel_test_menu").val(),
  };
  save_data_ajax(aData,"lab/db_lab_test.php",searchData_LabTest_Complete);
}

function searchData_LabTest_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $('.r_labtest').remove(); // row data list
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_LabTest(
             dataObj.id, dataObj.name, dataObj.g_name, dataObj.type
            );

        }//for

        $('#tbl_lab_test_main_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
/*
      txt_row += '<tr class="r_labtest_zero r_labtest"><td colspan="3" align="center">ไม่พบข้อมูล</td></tr">';
      $('#tbl_lab_test_main_list > tbody:last-child').append(txt_row);
      */
    }
  }
}


function addRowData_LabTest(id, name, g_name, type){

    var txt_row = '<tr class="r_labtest" id="r'+id+'" data-row="'+row_amt_test_sale+'"  >' ;

    txt_row += '<td>';
    txt_row += '<button class="btn btn-primary" type="button" onclick="openLabTest(\''+id+'\', \''+type+'\');"><i class="fa fa-vial fa-lg" ></i> View</button>';
    txt_row += '</td>';
    txt_row += '<td width="25%" class="text-primary">'+g_name+'</td>';
    txt_row += '<td width="10%">'+id+'</td>';
    txt_row += '<td >'+name+'</td>';
    txt_row += '<td width="20%">';
    if(type == "txt"){
      txt_row += '<span class="badge badge-success"><b>TEXT</b></span>';
    }
    else if(type == "num"){
      txt_row += '<span class="badge badge-warning"><b>Numeric</b></span>';
    }

    txt_row += '</td>';
    txt_row += '</tr">';
    $("#tbl_lab_test_main_list tbody").append(txt_row);

}


function showMenuTestDiv(choice){
  //alert("showMenuTestDiv "+choice);
  // menu_detail, detail, list
  $(".div-testing-menu").hide();
  $("#div_lab_test_"+choice).show();
}

</script>
