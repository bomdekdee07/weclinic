
<script>

ResetTimeOutTimer();

</script>

<style>
/*
#tbl_test_menu_specimen tr td:first-child{
    width:10%;
    white-space:nowrap;
}
*/
</style>

<div class="card">
  <div class="card-header bg-primary text-white" style="max-height: 3rem;">
      <div class="row ">
         <div class="col-sm-4">
           <h4><i class="fa fa-flask fa-lg" aria-hidden="true"></i> <b>Lab Testing Menu</b></h4>
         </div>
         <div class="col-sm-7">

           Record ID: <input type="text" id="lab_group_id" size="20" disabled>

         </div>
         <div class="col-sm-1 pr-0">
           <button id="btn_close_test_menu_detail" class="my-1 form-control form-control-sm btn btn-light btn-sm float-right mb-1" type="button">
             <i class="fa fa-times-circle fa-lg" ></i> ปิด
           </button>
         </div>
      </div>
  </div>
  <div class="card-body">
    <div class="row my-1">
      <div class="col-sm-6">
        <label for="lab_group_name">Lab Testing Menu:</label>
        <input type="text" id="lab_group_name" data-title="Name"  class="form-control form-control-sm save-data v-no-blank " maxlength="150">
      </div>
      <div class="col-sm-6">
        <label for="lab_method_id">Lab Method:</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <button class="btn btn-primary" type="button" onclick="openSettingDlgSelect('lab_method', 'lab_method_id');"> <i class="fa fa-hand-pointer fa-lg" ></i></button>
          </div>
          <input type="text" id="lab_method_id" data-id="" class="form-control form-control-sm save-data-id" placeholder="Select" value="" disabled>
        </div>
      </div>
    </div>






    <div class="my-2">
      <div class="row my-1">
        <div class="col-sm-6">
          <div class="my-1">
            <div>
              <label for="lab_group_reject">Reject Criteria:</label>
              <textarea id="lab_group_reject" rows="4"  data-title="Note" class="form-control save-data" placeholder="Reject Criteria"></textarea>
            </div>
             <div>
              <label for="lab_group_note">Note:</label>
              <textarea id="lab_group_note" rows="4"  data-title="Note" class="form-control save-data" placeholder="Remark"></textarea>
            </div>
          </div>

        </div>

        <div class="col-sm-6">
          <div class="my-2">
             <div>
               <b><u>Specimen Requirement</u></b>
                <button id="btn_add_test_menu_specimen" class="btn btn-light" type="button">
                 <i class="fa fa-plus fa-lg" ></i> ADD
               </button>
             </div>
             <div>
               <table id="tbl_test_menu_specimen" class="table table-bordered table-sm table-striped table-hover">
                   <thead>
                     <tr>
                       <th>Specimen</th>
                       <th>Operator</th>
                       <th>Amount</th>
                       <th>Unit</th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>

                   </tbody>
               </table>
             </div>
          </div>
          <div class="my-2">

             <div id="div_sel_ref_labtest" style="display:none;">
               <label>
               <b>Cost/Sale</b> Reference from <u>selected Lab Test</u>
               </label>
               <div class="input-group mb-3">
                 <div class="input-group-prepend">
                   <button class="btn btn-sm btn-warning" type="button" onclick="openDlgSelect('lab_test', 'ref_lab_id');"> <i class="fa fa-hand-pointer fa-lg" ></i></button>
                 </div>
                 <input type="text" id="ref_lab_id" data-id="" data-tbl="none" data-parent_id="" class="form-control form-control-sm data-id save-data-id"  placeholder="Select Lab Test Reference" value="" size="50" disabled>
                 <div class="input-group-append">
                   <button class="btn btn-sm btn-outline-warning" type="button" onclick="clearDlgSelect('ref_lab_id');">Clear</button>
                 </div>
               </div>

             </div>
          </div>

        </div>

      </div>

    </div>


  </div><!-- cardbody -->

  <div class="card-footer ">
    <button type="button" id="btn_cancel_test_menu" class="btn btn-danger mx-1 float-right" > <i class="fa fa-times-circle fa-lg" ></i> Cancel</button>
    <button type="button" id="btn_save_test_menu" class="btn btn-success mx-1 float-right" > <i class="fa fa-save fa-lg" ></i> Save Data</button>
  </div>
</div>


<script>

var row_amt_specimen = 0;
var lst_delete_data = [];

$(document).ready(function(){

  $("#btn_save_test_menu").click(function(){
     saveTestMenuData();
  }); // btn_search_test_menu

  $("#btn_save_test_menu").on("keypress",function (event) {
    if (event.which == 13) {
      saveTestMenuData();
    }
  });

  $("#btn_cancel_test_menu").click(function(){
     closeTestMenu();
  }); // btn_new_test_menu
  $("#btn_close_test_menu_detail").click(function(){
     closeTestMenu();
  }); // btn_close_test_menu_detail

  $("#btn_add_test_menu_specimen").click(function(){
     addNewRow_lab_test_menu('', '', '1', '', 'ml');
  }); // btn_close_test_menu_detail



});
function closeTestMenu(){
    showMenuTestDiv("list");
}

function clearData_lab_test_menu(){
  row_amt_specimen =0;
  cur_test_menu_id = "";
  lstDataObj_delete_sp = [];
  $('#lab_group_name').val("");
  $('#lab_method_id').val("");
  $('#lab_group_note').val("");
  $('#lab_group_reject').val("");
  $('.r_test_menu_sp').remove();

  $('#div_sel_ref_labtest').hide();
  $('.save-data').val("");
  $('.save-data-id').val("");
  $('.save-data-id').data("id", "");

}



function selectTestMenuDetail(test_menu_id){
  var aData = {
      u_mode:"select_lab_test_menu_detail",
      id:test_menu_id
  };
  save_data_ajax(aData,"lab/db_lab_test_menu.php",selectTestMenuDetailComplete);
}

function selectTestMenuDetailComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    clearData_lab_test_menu();
    u_mode_test_menu = "update_test_menu";
    cur_test_menu_id = aData.id;
    $('#lab_group_name').focus();
    $('#lab_group_id').val(cur_test_menu_id);

    var dataObj = rtnDataAjax.data;
    $('#lab_group_name').val(dataObj.lab_group_name);
    $('#lab_group_reject').val(dataObj.lab_group_reject);
    $('#lab_group_note').val(dataObj.lab_group_note);

    setSelectData($('#lab_method_id'),dataObj.lab_method_id,dataObj.lab_method_name );

    $('#ref_lab_id').data("parent_id", $('#lab_group_id').val());
    if(dataObj.ref_lab_id != "")
    setSelectData($('#ref_lab_id'),dataObj.ref_lab_id,dataObj.ref_lab_name );
    $('#div_sel_ref_labtest').show();
        //  setSelectData($('#lab_method_id'),dataObj.lab_method_id,dataObj.lab_method_name );

    var dataList_specimen = rtnDataAjax.datalist_specimen;
    dataList_specimen.forEach(function (itm) {
        addNewRow_lab_test_menu(itm.id, itm.name, itm.opr, itm.amt, itm.unit);
    });

    showMenuTestDiv("menu_detail");
  }
}


function addNewRow_lab_test_menu(id, name, operator, amt, unit){
  row_amt_specimen +=1;
  var add_data = (id == "")?"add_new":"";

  var txt_row = '<tr class="r_test_menu_sp '+add_data+'" id="rsp'+row_amt_specimen+'" data-row="'+row_amt_specimen+'"  >' ;
  if(id == ''){ // add new data
  txt_row += '<td>';
  txt_row += '<div class="input-group mb-3">';
  txt_row += '  <div class="input-group-prepend">';
  txt_row += '    <button class="btn btn-primary" type="button" onclick="openSettingDlgSelect(\'specimen\', \'tmn1_'+row_amt_specimen+'\');"> <i class="fa fa-hand-pointer fa-lg" ></i></button>';
  txt_row += '  </div>';
  txt_row += '  <input type="text" id="tmn1_'+row_amt_specimen+'" data-id="" data-tbl="tbl_test_menu_specimen" class="form-control form-control-sm" placeholder="Select" value="" size="50" disabled>';
  txt_row += '</div>';
  txt_row += '</td>';
//txt_row += '<td width=45%><a class="text-success" id="tmn1_'+row_amt_specimen+'" data-id="" href="javascript:void(0)"  onclick="openDlgSpecimen('+row_amt_specimen+');">-Select Specimen-</a></td>';

  }
  else{ // update data

  txt_row += '<td ><span class="text-primary data-id" id="tmn1_'+row_amt_specimen+'" data-id="'+id+'" "  ">'+name+'</span></td>';
  }


  txt_row += '<td>';
  txt_row += '<select id="tmn2_'+row_amt_specimen+'" onchange="changeSpecimenOperator('+row_amt_specimen+');">';
  txt_row += '<option value="1"> = </option>';
  txt_row += '<option value="2"> >= </option>';
  txt_row += '<option value=""> None </option>';
  txt_row += '<select">';
  txt_row += '</td>';

  txt_row += '<td>';
  txt_row += '<input type="text" id="tmn3_'+row_amt_specimen+'" placeholder="Amount" size="8" value="'+amt+'"  class="input-right input-decimal" data-digit="1" >';
  txt_row += '</td>';
  txt_row += '<td>';
  txt_row += '<input type="text" id="tmn4_'+row_amt_specimen+'" placeholder="Unit" size="10" value="'+unit+'">';
  txt_row += '</td>';
  txt_row += '<td>';
  txt_row += '<button class="btn btn-danger" type="button" onclick="deleteRowData(\'rsp'+row_amt_specimen+'\', \'tmn1_'+row_amt_specimen+'\',\'specimen\' );"><i class="fa fa-times fa-lg" ></i></button>';
  txt_row += '</td>';

  txt_row += '</tr">';
  $("#tbl_test_menu_specimen tbody").prepend(txt_row);
  $("#tmn2_"+row_amt_specimen).val(operator);
  if(operator == "") {
    $("#tmn3_"+row_amt_specimen).prop("disabled", true);
    $("#tmn4_"+row_amt_specimen).prop("disabled", true);
  }

}


function changeSpecimenOperator(rowNum){
   if($('#tmn2_'+rowNum).val() == ""){
     $('#tmn3_'+rowNum).val("");
     $('#tmn4_'+rowNum).val("");

     $('#tmn3_'+rowNum).prop("disabled", true);
     $('#tmn4_'+rowNum).prop("disabled", true);
   }
   else{
     $('#tmn3_'+rowNum).prop("disabled", false);
     $('#tmn4_'+rowNum).prop("disabled", false);
   }

   //alert("choice : "+choice);
}

function deleteRowData(row_id, row_component_id, tblName){
  if($('#'+row_id).hasClass("add-new")){
    $('#'+row_id).remove();
  }
  else{
    var result = confirm("Are you sure to delete this data ?");
    //var result = confirm("ท่านต้องการลบข้อมูล "+$('#'+row_component_id).val()+" นี้ใช่หรือไม่ ?");
    if (result) {
      if($('#'+row_id).data("id") != ""){ // there is data id
        //lst_data_delete[] = $('#'+row_id).data("id");
          lst_delete_data.push({tbl_name:tblName, id:$('#'+row_component_id).data("id")});
      }
      $('#'+row_id).remove();
    }
  }
}//deleteRowData

function saveTestMenuData(){

  //$("#btn_save_test_menu").prop("disabled", true);
  var divSaveData = "div_lab_test_menu_detail";

  if(validateInput(divSaveData)){

   var lstDataObj = [];


   var lstDataObj_add_sp = [];
   var lstDataObj_update_sp = [];


   $("#"+divSaveData +" .save-data").each(function(ix,objx){
     //alert("grab : "+$(objx).attr("id"));
      lstDataObj.push({name:$(objx).attr("id"), value:$(objx).val()});
   });
   $("#"+divSaveData +" .save-data-id").each(function(ix,objx){
     //alert("grab : "+$(objx).attr("id"));
      lstDataObj.push({name:$(objx).attr("id"), value:$(objx).data("id")});
   });


   $("#"+divSaveData +" .r_test_menu_sp").each(function(ix,objx){

      var row = $(objx).data("row");
      var arr_list_sp = [];
      arr_list_sp.push({name:"specimen_id", value:$("#tmn1_"+row).data("id")});
      arr_list_sp.push({name:"operator", value:$("#tmn2_"+row).val()});
      arr_list_sp.push({name:"specimen_amt", value:$("#tmn3_"+row).val()});
      arr_list_sp.push({name:"specimen_unit", value:$("#tmn4_"+row).val()});

      lstDataObj_update_sp.push(arr_list_sp);

   });

   var lstDataList = {
             specimen_update: lstDataObj_update_sp,
             delete_list: lst_delete_data
   };

    var aData = {
              u_mode:u_mode_test_menu,
              id:$("#lab_group_id").val(),
              lst_data_obj: lstDataObj,
              lst_data_list: lstDataList
    };

    save_data_ajax(aData,"lab/db_lab_test_menu.php",saveTestMenuDataComplete);

  }
  else{
    $("#btn_save_test_menu").prop("disabled", false);
  }

}

function saveTestMenuDataComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
   if(u_mode_test_menu == "add_test_menu"){
     u_mode_test_menu = "update_test_menu";
     $('#lab_group_id').val(rtnDataAjax.id);
     $('.r_test_menu_sp').removeClass("add_new");
     $("#sel_test_menu").append(new Option($('#lab_group_name').val(), $('#lab_group_id').val()));
     $("#sel_test_menu").val($('#lab_group_id').val());

     $.notify("Insert Lab Testing Menu successfully.", "success");
     // addrow in list
   }
   else{
     $.notify("Update Lab Testing Menu successfully.", "info");
     $("#sel_test_menu option:selected").text($('#lab_group_name').val());
   }

   changeTestMenu();
   closeTestMenu();

  }

  $("#btn_save_test_menu").prop("disabled", false);
}


</script>
