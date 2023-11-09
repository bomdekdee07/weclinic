<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

include_once("inc_auth.php"); // set permission view, update, delete

include_once("../a_app_info.php");

?>


<script>



</script>


<div id='div_data_group_list' class='div-data-group my-0'>
  <div class="row mt-0">
    <div class="col-sm-12">
      <h4><i class="fa fa-database fa-lg" ></i> <b>Data Group</b> </h4>
    </div>
  </div>

  <div class="row mt-0">
    <div class="col-sm-2">
      <label for="btn_new_data_group" class="text-white">.</label>
     <button class="btn btn-success btn-sm form-control form-control-sm " type="button" id="btn_new_data_group"><i class="fa fa-plus" ></i> ADD NEW</button>
    </div>
    <div class="col-sm-3">
      <label for="sel_data_group_main">Data Main:</label>
      <select id = "sel_data_group_main" class="form-control form-control-sm">

      </select>
    </div>
    <div class="col-sm-5">
      <label for="txt_search_data_group">คำค้นหา:</label>
      <input type="text" id="txt_search_data_group" class="form-control form-control-sm" placeholder="พิมพ์คำค้นหา ">
    </div>
     <div class="col-sm-2">
       <label for="btn_search_data_group" class="text-white">.</label>
      <button class="btn btn-primary btn-sm form-control form-control-sm " type="button" id="btn_search_data_group"><i class="fa fa-search" ></i> Search</button>
     </div>
   </div>

   <div class="mt-2"  style="min-height: 300px; border:1px solid grey;">
     <table id="tbl_data_group_list" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
         <thead>
           <tr>
             <th>Main Group</th>
             <th>Group ID</th>
             <th>Group Name TH</th>
             <th>Group Name EN</th>
             <th>Log?</th>
             <th>Code / Export Code</th>
             <th></th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_data_group -->

<div id='div_data_group_detail' class="div-data-group my-0" style="display:none;">


  <div class="card" >
    <div class="card-header bg-primary text-white" style="max-height: 3rem;">
        <div class="row ">
           <div class="col-sm-11">
             <h4><i class="fa fa-vials fa-lg" aria-hidden="true"></i> <b>Data Group</b> | Main Group: <span id="txt_data_group_title"></span></h4>
           </div>


           <div class="col-sm-1">
             <button type="button" id="btn_close_data_group" class="btn btn-sm btn-white  py-1 float-right" > <i class="fa fa-times fa-lg" ></i> Close</button>
         </div>

        </div>
    </div>
    <div class="card-body">

      <div class="row my-1">
        <div class="col-sm-4">
          <label for="data_group_id">Data Group ID:</label>
          <input type="text" id="data_group_id" data-title="Data Group ID" data-datakey="1" data-odata="" class="form-control form-control-sm save-data v-no-blank input-text-code keydata" maxlength="50">
        </div>

        <div class="col-sm-4">
          <label for="data_group_export_code">Data Group Export Code:</label>
          <input type="text" id="data_group_export_code" data-title="Data Group Export Code"  data-odata="" class="form-control form-control-sm save-data v-no-blank input-text-code" maxlength="50">
        </div>
        <div class="col-sm-4">
        </div>
      </div>

      <div class="row my-1">
        <div class="col-sm-5">
          <label for="data_group_name">Group Name Thai:</label>
          <input type="text" id="data_group_name_th" data-title="Group Name"  data-odata="" class="form-control form-control-sm save-data" maxlength="200">
        </div>
        <div class="col-sm-5">
          <label for="data_group_name">Group Name Eng:</label>
          <input type="text" id="data_group_name_en" data-title="Group Name"  data-odata="" class="form-control form-control-sm save-data" maxlength="200">
        </div>
        <div class="col-sm-2">
            <label for="is_log">.</label>
            <div class="form-check">
              <input type="checkbox" class="form-check-input save-data" id="is_log">
              <label class="form-check-label" for="is_log"> Log?</label>
            </div>
        </div>
      </div>
      <div class="my-1">
        <label for="data_group_desc">Note</label>
        <textarea id="data_group_desc" rows="4"  data-title="Note" data-odata="" class="form-control save-data" placeholder="Group Note"></textarea>
      </div>


    </div><!-- cardbody -->

    <div class="card-footer ">
      <button type="button" id="btn_cancel_data_group" class="btn btn-danger mx-1 float-right" > <i class="fa fa-times-circle fa-lg" ></i> Cancel Data</button>
      <button type="button" id="btn_save_data_group" class="btn btn-info float-right "><i class="fa fa-check fa-lg" ></i> Save Data</button>
    </div>
  </div>

</div> <!-- div_data_group_detail -->




<script>
var cur_data_group_main_id = "";
$(document).ready(function(){
//  searchData_DataGroup();
    searchData_DataGroupMain_dropdown();
  $("#btn_search_data_group").click(function(){
     searchData_DataGroup();
  }); // btn_search_data_group


  $("#txt_search_data_group").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_DataGroup();
    }
  });

$("#btn_new_data_group").click(function(){
   addNewDataGroup();
}); // btn_new_data_group
$("#btn_save_data_group").click(function(){
   saveData_DataGroup();
}); // btn_save_data_group
$("#btn_cancel_data_group").click(function(){
   close_data_group();
}); // btn_cancel_data_group
$("#btn_close_data_group").click(function(){
   close_data_group();
}); // btn_close_data_group



});


  function clearData_DataGroup(){
  //  $('.r_data').remove();
    cur_data_group_main_id = "";
    $(".save-data").val("");
    $(".input-text-code").prop("disabled", false);
    $("data_group_id").focus();
  }
  function addNewDataGroup(){
    if($("#sel_data_group_main").val() == ""){
      $("#sel_data_group_main").notify("Please select main group to add here.", "info");
    }
    else{
      cur_data_group_main_id = $("#sel_data_group_main").val();

      $("#txt_data_group_title").html($("#sel_data_group_main option:selected").text());
      $(".save-data").val("");
      $(".input-text-code").prop("disabled", false);
      $("#data_group_id").focus();
      showDataGroupDiv("detail");
    }



  }



  function searchData_DataGroupMain_dropdown(){
    var aData = {
        u_mode:"select_data_group_main_dropdown"
    };
    save_data_ajax(aData,"data_mgt/db_data_group_main.php",searchData_DataGroupMain_dropdown_Complete);
  }

  function searchData_DataGroupMain_dropdown_Complete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave);
    if(flagSave){
      var txt_row="";
      if(rtnDataAjax.datalist.length > 0){
      //  $("#sel_test_menu").val([]);
        $("#sel_data_group_main").empty();
        $("#sel_data_group_main").append(new Option("All Main Group", ""));
        var datalist = rtnDataAjax.datalist;
          for (i = 0; i < datalist.length; i++) {
              var dataObj = datalist[i];
              $("#sel_data_group_main").append(new Option("["+dataObj.id+"] "+dataObj.name, dataObj.id));
          }//for
      }
      else{
        $.notify("No record found.", "info");
      }
    }
  }



function searchData_DataGroup(){

  var aData = {
      u_mode:"select_data_group_list",
      group_main_id: $("#sel_data_group_main").val(),
      txt_search:$("#txt_search_data_group").val().trim()
  };
  save_data_ajax(aData,"data_mgt/db_data_group.php",searchData_DataGroup_Complete);
}

function searchData_DataGroup_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $('.r_data').remove(); // row data list
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_DataGroup(
             dataObj.gm_id,dataObj.gm_name, dataObj.group_id, dataObj.group_name_en,dataObj.group_name_th, dataObj.is_log, dataObj.group_export
            );

        }//for

        $('#tbl_data_group_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }
  }
}



function addRowData_DataGroup(group_main_id,group_main_name,
  group_id, group_name_en,group_name_th, is_log,  group_export){

    var txt_row = '<tr class="r_data" id="r'+group_id+'" data-id="'+group_id+'" data-group_main_id="'+group_main_id+'" >' ;
    txt_row += '<td width="20%" > ['+group_main_id+'] '+group_main_name+'</td>';

    txt_row += '<td width="20%">';
    txt_row += '<button class="btn btn-primary" type="button" onclick="openDataGroup(\''+group_id+'\');"> <b> '+group_id+'</b></button>';
    txt_row += '</td>';
    txt_row += '<td width="20%" >'+group_name_en+'</td>';
    txt_row += '<td width="20%" >'+group_name_th+'</td>';
    if(is_log == '1') is_log = "Yes";
    else is_log = "No";
    txt_row += '<td class="text-primary">'+is_log+'</td>';
    txt_row += '<td >'+group_export+'</td>';
    txt_row += '<td width="10%">';
    txt_row += '<button class="btn btn-danger" type="button" onclick="deleteDataGroup(\''+group_id+'\');"> <b>X</b></button>';
    txt_row += '</td>';
    txt_row += '</tr">';
    $("#tbl_data_group_list tbody").append(txt_row);

}

function openDataGroup(groupID){ // open Data Group
  //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
    var aData = {
        u_mode:"select_data_group_detail",
        id:groupID
    };
    save_data_ajax(aData,"data_mgt/db_data_group.php",openDataGroup_Complete);
  }

  function openDataGroup_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      clearData_DataGroup();
      $(".input-text-code").prop("disabled", true);

      var dataObj = rtnDataAjax.data_obj;

      cur_data_group_id = dataObj.data_group_id;
      for (x in dataObj) {
              //   console.log("enter"+"col:"+x+" / "+dataObj[x]);
          setWObjValue($("#"+x),dataObj[x]);
      }
      cur_data_group_main_id = $("#r"+aData.id).data("group_main_id");
    //  console.log("cur : "+cur_data_group_main_id+"/"+aData.id);
      $("#txt_data_group_title").html($("#r"+aData.id).find('td:first-child').text());
      showDataGroupDiv("detail");
    }
  }


function deleteDataGroup(id){ // delete data group
  //console.log("delete "+id);
  var result = confirm("ท่านต้องการลบบันทึกใช่หรือไม่ ?");
  if (result) {
    var aData = {
        u_mode:"delete_data_group",
        id:$("#r"+id).data("id")
    };
    save_data_ajax(aData,"data_mgt/db_data_group.php",deleteDataGroupComplete);
  }

}

function deleteDataGroupComplete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      $("#r"+aData.id).remove();
    }
  }

  function saveData_DataGroup(){ // save data
      var divSaveData = "div_data_group_detail";
      var is_data_change = false;

      var adata_obj = {};

      if(validateInput(divSaveData)){

              $("#div_data_group_detail .save-data").each(function(ix,objx){
//console.log("id: "+$(objx).attr("id")+"/"+ $(objx).val()+"/"+$(objx).data("odata"));
                 var newVal = getWObjValue(objx);
                 if(newVal != $(objx).data("odata")){
                   adata_obj[($(objx).attr("id"))] = newVal;
                   is_data_change = true;
                 }
                 else if($(objx).hasClass("keydata")){
                   adata_obj[($(objx).attr("id"))] = newVal;
                 }

              });

              adata_obj["data_group_main_id"] = cur_data_group_main_id;

              if(is_data_change){
                var aData = {
                u_mode:"update_data_group",
                data_obj:adata_obj
                };
                save_data_ajax(aData,"data_mgt/db_data_group.php",saveData_DataGroup_Complete);

              }
              else{
                $.notify("No data changed", "info");
              }


      }
      else{
        $.notify("Incomplete Data, Please Check!", "error");
      }



    }

    function saveData_DataGroup_Complete(flagSave, rtnDataAjax, aData){
      if(flagSave){
        $.notify("Save Data Grouped successfully.", "success");
        $("#div_data_group_detail .save-data").each(function(ix,objx){
            var data_val = getWObjValue(objx);
            console.log("value: "+data_val);
            setWObjValue(objx,data_val);
        });
        searchData_DataGroup();
      //  showDataGroupDiv("list");
      }
    }

function close_data_group(){
  showDataGroupDiv("list");
}


function showDataGroupDiv(choice){
  //alert("showDataGroupDiv "+choice);
  $(".div-data-group").hide();
  $("#div_data_group_"+choice).show();
}

</script>
