<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

include_once("inc_auth.php"); // set permission view, update, delete

include_once("../a_app_info.php");

?>


<script>

var cur_data_group_id = "";
var cur_data_type = "";

</script>


<div id='div_data_list' class='div-data-list my-0'>
  <div class="row mt-0">
    <div class="col-sm-6">
      <h4><i class="fa fa-database fa-lg" ></i> <b>Data Item List</b> </h4>
    </div>
    <div class="col-sm-6">
      <div>
        <table>
           <tr>
             <td>
               <select id = "sel_data_type">
                 <option value="radio">Radio Button</option>
                 <option value="dropdown">Dropdown List</option>
                 <option value="checkbox">Check Box</option>
                 <option value="date">Date</option>
                 <option value="number">Number</option>
                 <option value="text">Text</option>
                 <option value="textarea">Text Area</option>
                 <option value="fileimage">File Upload (Image)</option>
                 <option value="filepdf">File Upload (PDF)</option>
               </select>
             </td>
             <td>
               <button class="btn btn-success btn-sm " type="button" id="btn_new_data"><i class="fa fa-plus" ></i> ADD NEW</button>

             </td>
           </tr>
        </table>
      </div>
    </div>
  </div>

  <div class="row mt-0">
    <div class="col-sm-3">
      <label for="sel_data_group_main">Data Main:</label>
      <select id = "sel_data_group_main" class="form-control form-control-sm">

      </select>
    </div>
    <div class="col-sm-3">
      <label for="sel_data_group">Data Group:</label>
      <select id = "sel_data_group" class="form-control form-control-sm">

      </select>
    </div>
    <div class="col-sm-4">
      <label for="txt_search_data">คำค้นหา:</label>
      <input type="text" id="txt_search_data" class="form-control form-control-sm" placeholder="พิมพ์คำค้นหา ">
    </div>
     <div class="col-sm-2">
       <label for="btn_search_data" class="text-white">.</label>
      <button class="btn btn-primary btn-sm form-control form-control-sm " type="button" id="btn_search_data"><i class="fa fa-search" ></i> Search</button>
     </div>
   </div>

   <div class="mt-2"  style="min-height: 300px; border:1px solid grey;">
     <table id="tbl_data_list" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
         <thead>
           <tr>
             <th>DATA ID</th>
             <th>Main Group</th>
             <th>Group</th>
             <th>Data Name EN</th>
             <th>Data Name TH</th>

             <th>Type</th>
             <th>Category</th>
             <th></th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_data -->

<div id='div_data_detail' class="div-data-list my-0" style="display:none;">


  <div class="card" >
    <div class="card-header bg-primary text-white" style="max-height: 3rem;">
        <div class="row ">
           <div class="col-sm-11">
             <h5><i class="fa fa-vials fa-lg" aria-hidden="true"></i> <b>Data</b> ( Group: <span id="txt_data_title"></span> )</h5>
           </div>


           <div class="col-sm-1">
             <button type="button" id="btn_close_data" class="btn btn-sm btn-white  py-1 float-right" > <i class="fa fa-times fa-lg" ></i> Close</button>
         </div>

        </div>
    </div>
    <div class="card-body">

      <div class="row my-1">
        <div class="col-sm-3">
          <label for="data_id">Data ID:</label>
          <input type="text" id="data_id" data-title="Data ID" data-datakey="1" data-odata="" class="form-control form-control-sm save-data v-no-blank input-text-code keydata" maxlength="50">
        </div>

        <div class="col-sm-3">
          <label for="data_export_code">Data Export Code:</label>
          <input type="text" id="data_export_code" data-title="Data Export Code"  data-odata="" class="form-control form-control-sm save-data v-no-blank input-text-code" maxlength="50">
        </div>
        <div class="col-sm-2">
          <label for="ewat_data_id">EWAT Data ID:</label>
          <input type="text" id="ewat_data_id" data-title="eWAT Data ID"  data-odata="" class="form-control form-control-sm save-data" maxlength="50">

        </div>

        <div class="col-sm-2">
          <label for="data_category">Data Category:</label>
          <select id = "data_category" class="form-control form-control-sm save-data v-no-blank" data-odata="">
            <option value="" disabled>Select</option>
            <option value="1">General</option>
            <option value="2">Lastest</option>
            <option value="3">Patient Info</option>
          </select>
        </div>
        <div class="col-sm-2">
          <label for="data_type">Data Type:</label>
          <!-- <input type="text" id="txt_data_type"  class="form-control form-control-sm bg-warning" disabled>
          -->
          <select id = "data_type" class="form-control form-control-sm bg-warning save-data" data-odata = ''>
            <option value="radio" data-type="text">Radio Button</option>
            <option value="dropdown" data-type="text">Dropdown List</option>
            <option value="checkbox" data-type="text">Check Box</option>
            <option value="date" data-type="text">Date</option>
            <option value="number" data-type="text">Number</option>
            <option value="text" data-type="text">Text</option>
            <option value="textarea" data-type="text">Text Area</option>
            <option value="fileimage" data-type="text">File Upload (Image)</option>
            <option value="filepdf" data-type="text">File Upload (PDF)</option>
          </select>
        </div>
      </div>

<div class="my-2"  style="border:2px solid #CCC; padding:10px; background-color: #EEE; ">
      <div><b>THAI</b></div>
      <div class="row my-1">
        <div class="col-sm-6">
          <label for="data_name_th">Data Name TH:</label>
          <input type="text" id="data_name_th"  data-odata="" class="form-control form-control-sm save-data v-no-blank data-req" maxlength="200">
        </div>
        <div class="col-sm-6">
          <label for="data_question_th">Data Question TH:</label>
          <input type="text" id="data_question_th"  data-odata="" class="form-control form-control-sm save-data v-no-blank data-req" maxlength="200">
        </div>
      </div>

      <div class="row my-1 div-data-type data-type-text">
        <div class="col-sm-6">
          <label for="data_prefix_th">Data Prefix TH:</label>
          <input type="text" id="data_prefix_th"  data-odata="" class="form-control form-control-sm save-data data-req" maxlength="200">
        </div>
        <div class="col-sm-6">
          <label for="data_suffix_th">Data Suffix TH:</label>
          <input type="text" id="data_suffix_th"  data-odata="" class="form-control form-control-sm save-data data-req" maxlength="200">
        </div>
      </div>
</div>


<div class="my-2" style="border:2px solid #CCC; padding:10px;">
      <div><b>ENG</b></div>
      <div class="row my-1">
        <div class="col-sm-6">
          <label for="data_name_en">Data Name Eng:</label>
          <input type="text" id="data_name_en"  data-odata="" class="form-control form-control-sm save-data data-req" maxlength="200">
        </div>
        <div class="col-sm-6">
          <label for="data_question_en">Data Question Eng:</label>
          <input type="text" id="data_question_en"  data-odata="" class="form-control form-control-sm save-data data-req" maxlength="200">
        </div>
      </div>

      <div class="row my-1 div-data-type data-type-text" >
        <div class="col-sm-6">
          <label for="data_prefix_en">Data Prefix Eng:</label>
          <input type="text" id="data_prefix_en"  data-odata="" class="form-control form-control-sm save-data data-req" maxlength="200">
        </div>
        <div class="col-sm-6">
          <label for="data_suffix_en">Data Suffix Eng:</label>
          <input type="text" id="data_suffix_en"  data-odata="" class="form-control form-control-sm save-data data-req" maxlength="200">
        </div>
      </div>
</div>

      <div class="my-4 div-data-type data-type-list" >
        <? include_once("mnu_data_list_radio.php"); ?>
      </div>


    </div><!-- cardbody -->

    <div class="card-footer ">
      <button type="button" id="btn_cancel_data" class="btn btn-danger mx-1 float-right" > <i class="fa fa-times-circle fa-lg" ></i> Cancel Data</button>
      <button type="button" id="btn_save_data" class="btn btn-info float-right "><i class="fa fa-check fa-lg" ></i> Save Data</button>
    </div>
  </div>

</div> <!-- div_data_detail -->




<script>

$(document).ready(function(){
//  searchData_DataList();
   searchData_DataGroupMain_Dropdown();
   searchData_DataGroup_Dropdown();
  // searchData_DataGroup();



  $("#sel_data_group_main").change(function(){
    //console.log("id "+$(this).val());

    $('#sel_data_group').val("");
    var val = $(this).val()
    if (val == "") { // if there is no value means first option is selected

      $('#sel_data_group option').hide();
    }
    else {
      $('#sel_data_group option').hide().filter(function(){
        if(val == $(this).data('main_id')) return true;
      }).show();
    }
  }); // sel_data_group_main

  $("#btn_search_data").click(function(){
     searchData_DataList();
  }); // btn_search_data


  $("#txt_search_data").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_DataList();
    }
  });

$("#btn_new_data").click(function(){
   addNewDataList();
}); // btn_new_data
$("#btn_save_data").click(function(){
   saveData_DataList();
}); // btn_save_data
$("#btn_cancel_data").click(function(){
   close_data();
}); // btn_cancel_data
$("#btn_close_data").click(function(){
   close_data();
}); // btn_close_data

$("#data_question_th").focusin(function(){
   if($("#data_question_th").val() == ""){
     $("#data_question_th").val($("#data_name_th").val());
   }
}); // data_question_th
$("#data_question_en").focusin(function(){
   if($("#data_question_en").val() == ""){
     $("#data_question_en").val($("#data_name_en").val());
   }
}); // data_question_en


$("#data_type").change(function(){

   showDataListTypeDiv();
}); // btn_close_data



});


  function clearData_DataList(){
    cur_data_group_id = $("#sel_data_group").val();
    cur_data_type = $("#sel_data_type").val();
    $(".save-data").val("");
    //$(".save-data:not(.input-text-code)").val("");
    $(".save-data").data("odata", "");
    $(".data-item").remove();
    $(".input-text-code").prop("disabled", false);
    $("#data_id").focus();
  }

  function addNewDataList(){
    if($("#sel_data_group").val() == ""){
      $("#sel_data_group").notify("Please select data group to add here.", "info");
    }
    else{
      clearData_DataList();
      cur_data_group_id = $("#sel_data_group").val();
      cur_data_type = $("#sel_data_type").val();

      $("#data_type").val($("#sel_data_type option:selected").val());
      $("#txt_data_title").html($("#sel_data_group option:selected").text());
      $(".save-data").val("");
      $(".input-text-code").prop("disabled", false);
      $("#data_id").focus();
      showDataListDiv("detail");
      showDataListTypeDiv();



    }
  }





    function searchData_DataGroupMain_Dropdown(){
      var aData = {
          u_mode:"select_data_group_main_dropdown"
      };
      save_data_ajax(aData,"data_mgt/db_data_group_main.php",searchData_DataGroupMain_Dropdown_Complete);
    }

    function searchData_DataGroupMain_Dropdown_Complete(flagSave, rtnDataAjax, aData){
      //alert("flag save is : "+flagSave);
      if(flagSave){
        var txt_row="";
        if(rtnDataAjax.datalist.length > 0){
        //  $("#sel_test_menu").val([]);
          $("#sel_data_group_main").empty();
          $("#sel_data_group_main").append(new Option("All Data", ""));
          var datalist = rtnDataAjax.datalist;
          for (i = 0; i < datalist.length; i++) {
              var dataObj = datalist[i];
              $("#sel_data_group_main").append(new Option(dataObj.name+" ["+dataObj.id+"]", dataObj.id));


          }//for
        }
        else{
          $.notify("No record found.", "info");
        }
      }
    }

  function searchData_DataGroup_Dropdown(){
    var aData = {
        u_mode:"select_data_group_dropdown"
    };
    save_data_ajax(aData,"data_mgt/db_data_group.php",searchData_DataGroup_Dropdown_Complete);
  }

  function searchData_DataGroup_Dropdown_Complete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave);
    if(flagSave){
      var txt_row="";
      if(rtnDataAjax.datalist.length > 0){
      //  $("#sel_test_menu").val([]);
        $("#sel_data_group").empty();
        $("#sel_data_group").append(new Option("All Data", ""));
        var datalist = rtnDataAjax.datalist;
          for (i = 0; i < datalist.length; i++) {
              var dataObj = datalist[i];
            //  $("#sel_data_group").append(new Option("["+dataObj.id+"] "+dataObj.name, dataObj.name));
              $('#sel_data_group')
                .append($('<option />')  // Create new <option> element
                .val(dataObj.id)
                .text(dataObj.name+" ["+dataObj.id+"]")
                .data({                  // Set multiple data-* attributes
                    main_id: dataObj.main_id
                })
              );

          }//for

       $('#sel_data_group option').hide(); // show only first value (all data)


      }
      else{
        $.notify("No record found.", "info");
      }
    }
  }



function searchData_DataList(){
  var aData = {
      u_mode:"select_data_list",
      group_main_id: $("#sel_data_group_main").val(),
      group_id: $("#sel_data_group").val(),
      txt_search:$("#txt_search_data").val().trim()
  };
  save_data_ajax(aData,"data_mgt/db_data_list.php",searchData_DataList_Complete);
}

function searchData_DataList_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $('.r_data').remove(); // row data list
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_DataList(
             dataObj.gm_id,dataObj.gm_name, dataObj.g_id, dataObj.g_name,
             dataObj.d_id, dataObj.d_name_th, dataObj.d_name_en, dataObj.d_type, dataObj.d_cat
            );

        }//for

        $('#tbl_data_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }
  }
}



function addRowData_DataList(group_main_id,group_main_name,
  group_id, group_name,data_id, data_name_th,data_name_en, data_type, data_category){

    if(data_category == '1') data_category="General";
    else if(data_category == '2') data_category="Lastest";
    else if(data_category == '3') data_category="Patient Info";

    var txt_row = '<tr class="r_data" id="r'+data_id+'" data-id="'+data_id+'"  data-group_id="'+group_id+'" data-group_main_id="'+group_main_id+'" >' ;
    txt_row += '<td width="20%">';
    txt_row += '<button class="btn btn-primary" type="button" onclick="openDataList(\''+data_id+'\');"> <b> '+data_id+'</b></button>';
    txt_row += '</td>';
    txt_row += '<td width="15%" > ['+group_main_id+'] '+group_main_name+'</td>';
    txt_row += '<td width="15%" > ['+group_id+'] '+group_name+'</td>';
    txt_row += '<td width="20%" >'+data_name_en+'</td>';
    txt_row += '<td width="20%" >'+data_name_th+'</td>';

    txt_row += '<td >'+data_type+'</td>';
    txt_row += '<td >'+data_category+'</td>';
    txt_row += '<td width="10%">';
    txt_row += '<button class="btn btn-danger" type="button" onclick="deleteDataList(\''+data_id+'\');"> <b>X</b></button>';
    txt_row += '</td>';
    txt_row += '</tr">';
    $("#tbl_data_list tbody").append(txt_row);

}

function openDataList(dataID){ // open Data
  //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
    var aData = {
        u_mode:"select_data_list_detail",
        id:dataID
    };
    save_data_ajax(aData,"data_mgt/db_data_list.php",openDataList_Complete);
  }

  function openDataList_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      clearData_DataList();
      $(".input-text-code").prop("disabled", true);

      var dataObj = rtnDataAjax.data_obj;
      var dataObjItem = rtnDataAjax.data_obj_itm;

      cur_data_group_id = dataObj.data_group_id;
      cur_data_type = dataObj.data_type;
      $("#data_type").val(cur_data_type); $("#data_type").attr("data-odata", cur_data_type);


      for (x in dataObj) {
              //   console.log("enter"+"col:"+x+" / "+dataObj[x]);
          setWObjValue($("#"+x),dataObj[x]);
      }

      //console.log("curdatatype: "+cur_data_type+"/"+dataObj.data_type);
      if(cur_data_type == "radio" || cur_data_type == "dropdown"){
        dataObjItem.forEach(function(itm) {
           addData_list(itm['data_seq'], itm['data_value'], itm['data_name_th'], itm['data_name_en'], itm['ewat_data_value'] );
        });

      }
      showDataListTypeDiv();

      $("#txt_data_title").html($("#r"+aData.id).data("group_id"));
      showDataListDiv("detail");
    }
  }


function deleteDataList(id){ // delete Data
  //console.log("delete "+id);
  var result = confirm("ท่านต้องการลบบันทึกใช่หรือไม่ ?");
  if (result) {
    var aData = {
        u_mode:"delete_data_list",
        id:$("#r"+id).data("id")
    };
    save_data_ajax(aData,"data_mgt/db_data_list.php",deleteDataListComplete);
  }

}

function deleteDataListComplete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      $("#r"+aData.id).remove();
    }
  }

  function saveData_DataList(){ // save data

      cur_data_type = $("#data_type").val();
      var ttl_invalid = 0;
      $("#div_data_detail .v-no-blank").each(function(ix,objx){
        //console.log("check : "+$(objx).attr("id")+" / value: "+$(objx).val());
        if($(objx).val() == "" || $(objx).val() == null){
          $(objx).notify("Please insert data.", "error");
          ttl_invalid++;
        }
      });
      if(ttl_invalid > 0){
        $.notify("Total invalid data : "+ttl_invalid, "error");
        return;
      }

      var divSaveData = "div_data_detail";
      var is_data_change = false;

      var adata_obj = {};
      var adata_obj_data_item = [];

  //    if(validateInput(divSaveData)){

              $("#div_data_detail .save-data").each(function(ix,objx){
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

              adata_obj["data_group_id"] = cur_data_group_id;
              adata_obj["data_type"] = cur_data_type;

              var data_id = $("#data_id").val().trim();
              if(cur_data_type == "radio" || cur_data_type == "dropdown"){
                $("#tbl_data_list_item .data-item").each(function(ix,objx){

                  var rowid = $(objx).data("rowid");
  //console.log("id: "+$(objx).attr("id")+"/"+ rowid);
                  var data_item = {};
                  data_item["data_id"] = data_id;
                  $(".dataitm"+rowid).each(function(ix,objx){
                    //  console.log("id: "+$(objx).data("col_id")+"/"+getWObjValue(objx));
                      var newVal = getWObjValue(objx);
                      data_item[$(objx).data("col_id")] = newVal;
                  });
                  adata_obj_data_item.push(data_item);
                });
              }// radio, dropdown


  //console.log("count : "+adata_obj_data_item.length);

  if(!is_data_change){
     adata_obj = {};
  }
    var aData = {
    u_mode:"update_data_list",
    data_id:$("#data_id").val(),
    data_type:cur_data_type,
    data_group_id:cur_data_group_id,
    data_obj:adata_obj,
    data_obj_itm:adata_obj_data_item
    };
    save_data_ajax(aData,"data_mgt/db_data_list.php",saveData_DataList_Complete);

/*
              if(is_data_change){
                var aData = {
                u_mode:"update_data_list",
                data_obj:adata_obj,
                data_obj_itm:adata_obj_data_item
                };
                save_data_ajax(aData,"data_mgt/db_data_list.php",saveData_DataListMain_Complete);

              }
              else{
                $.notify("No data changed", "info");
              }
*/


/*
      }
      else{
        $.notify("Incomplete Data, Please Check!", "error");
      }

*/

    }

    function saveData_DataList_Complete(flagSave, rtnDataAjax, aData){
      if(flagSave){
        $.notify("Save Dataed successfully.", "success");
        $("#div_data_detail .save-data").each(function(ix,objx){
            var data_val = getWObjValue(objx);
          //  console.log("value: "+data_val);
            setWObjValue(objx,data_val);
            $("#data_id").prop('disabled', true);
        });
        searchData_DataList();
      //  showDataListDiv("list");
      }
    }

function close_data(){
  showDataListDiv("list");
}


function showDataListDiv(choice){
  //alert("showDataListDiv "+choice);
  $(".div-data-list").hide();
  $("#div_data_"+choice).show();
}

function showDataListTypeDiv(){
  //alert("showDataListDiv "+choice);
  let data_type = $('#data_type').val();
  $(".div-data-type").hide();
  if(data_type == "radio" || data_type=="dropdown"){
    $(".data-type-list").show();
    $(".data-type-text .save-data").val("");
  }
  else if (data_type == "checkbox"){}
  else{
    $(".data-type-text").show();
    $(".data-type-list .save-data").val("");
  }

}

</script>
