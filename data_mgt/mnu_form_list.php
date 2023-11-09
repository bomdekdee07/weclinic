<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

include_once("inc_auth.php"); // set permission view, update, delete

include_once("../a_app_info.php");

?>


<script>

//**** form  var

var cur_form_id = ""; // current cur_form_id
var cur_lang = "th"; // current language  eg. th, en

var form_id_copy_origin = ""; // form id to copy
var form_id_copy_dest = ""; // form id to paste
//****

</script>


<div id='div_form_list' class='div-form-data my-0'>
  <div class="row mt-0">
    <div class="col-sm-12">
      <h4><i class="fa fa-newspaper fa-lg" ></i> <b>Form List</b> </h4>
    </div>
  </div>

  <div class="row mt-0">
    <div class="col-sm-2">
      <label for="btn_new_form" class="text-white">.</label>
     <button class="btn btn-success btn-sm form-control form-control-sm " type="button" id="btn_new_form"><i class="fa fa-plus" ></i> ADD NEW</button>
    </div>
    <div class="col-sm-8">
      <label for="txt_search_form">คำค้นหา:</label>
      <input type="text" id="txt_search_form" class="form-control form-control-sm" placeholder="พิมพ์คำค้นหา ">
    </div>
     <div class="col-sm-2">
       <label for="btn_search_form" class="text-white">.</label>
      <button class="btn btn-primary btn-sm form-control form-control-sm " type="button" id="btn_search_form"><i class="fa fa-search" ></i> Search</button>
     </div>
   </div>

   <div class="mt-2"  style="min-height: 300px; border:1px solid grey;">
     <table id="tbl_form_list" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
         <thead>
           <tr>
             <th>Form ID</th>
             <th>Log?</th>
             <th>Form Name EN</th>
             <th>Form Name TH</th>
             <th>Form Editor</th>
             <th>Version</th>

             <th></th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_form -->

<div id='div_form_detail' class="div-form-data my-0" style="display:none;">


  <div class="card" >
    <div class="card-header bg-primary text-white" style="max-height: 3rem;">
        <div class="row ">
           <div class="col-sm-11">
             <h4><i class="fa fa-newspaper fa-lg" aria-hidden="true"></i> <b>Form Information</b> <span class="txt_form_name">x</span></h4>
           </div>


           <div class="col-sm-1">
             <button type="button"  class="btn btn-sm btn-white btn_close_form py-1 float-right" > <i class="fa fa-times fa-lg" ></i> Close</button>
         </div>

        </div>
    </div>
    <div class="card-body">

      <div class="row my-1">
        <div class="col-sm-4">
          <label for="form_id">Form ID:</label>
          <input type="text" id="form_id" data-datakey="1" data-odata="" class="form-control form-control-sm save-data v-no-blank input-text-code keydata" maxlength="50">
        </div>
        <div class="col-sm-2">
          <label for="is_log">Log Form?:</label>
          <select id='is_log' data-odata="" class="form-control form-control-sm save-data">
            <option value='0' selected>No</option>
            <option value='1'>Yes</option>
          </select>
        </div>
        <div class="col-sm-2">
          <button class="pbtn pbtn-warning btn-form-attr">Form Attribute</button>

        </div>
        <div class="col-sm-3">
        </div>
      </div>

      <div class="row my-1">
        <div class="col-sm-6">
          <label for="form_name_th">Form Name Thai:</label>
          <input type="text" id="form_name_th" data-odata="" class="form-control form-control-sm save-data v-no-blank" maxlength="200">
        </div>
        <div class="col-sm-6">
          <label for="form_name_en">Form Name Eng:</label>
          <input type="text" id="form_name_en" data-odata="" class="form-control form-control-sm save-data v-no-blank" maxlength="200">
        </div>
      </div>

      <div class="row my-1">
        <div class="col-sm-6">
          <label for="form_name_th">Form Project ID:</label>
          <input type="text" id="form_name_th" data-odata="" class="form-control form-control-sm save-data v-no-blank" maxlength="200">
        </div>
        <div class="col-sm-6"></div>
      </div>
      <div class="my-1">
        <label for="form_desc">Note</label>
        <textarea id="form_desc" rows="4" data-odata="" class="form-control save-data" placeholder="Group Note"></textarea>
      </div>


    </div><!-- cardbody -->

    <div class="card-footer ">
        <button type="button" id="btn_cancel_form" class="btn btn-danger mx-1 float-right" > <i class="fa fa-times-circle fa-lg" ></i> Cancel Data</button>
        <button type="button" id="btn_save_form" class="btn btn-info float-right "><i class="fa fa-check fa-lg" ></i> Save Data</button>
    </div>
  </div>

</div> <!-- div_form_detail -->

<div id='div_form_editor' class="div-form-data my-0" style="display:none;">
  <div class="card" >
    <div class="card-header bg-primary text-white" style="max-height: 3rem;">
        <div class="row ">
           <div class="col-sm-10">
             <h4><i class="fa fa-newspaper fa-lg" aria-hidden="true"></i> <b>Form Editor</b> <span class="txt_form_name">x</span></h4>
           </div>

           <div class="col-sm-1">
             <button type="button"  id="btn_form_view" class="btn btn-sm btn-white py-1 float-right " > <i class="fa fa-file-invoice fa-lg" ></i> Form View</button>
         </div>
           <div class="col-sm-1">
             <button type="button"  class="btn btn-sm btn-white btn_close_form py-1 float-right" > <i class="fa fa-times fa-lg" ></i> Close</button>
         </div>

        </div>
    </div>
    <div class="card-body" id="div_form_editor_info">
      <?
      //include_once("mnu_form_editor.php");
      ?>

    </div><!-- cardbody -->

    <div class="card-footer ">

    </div>
  </div>

</div> <!-- div_form_editor -->



<script>

$(document).ready(function(){
  searchData_Form();
  $("#btn_search_form").click(function(){
     searchData_Form();
  }); // btn_search_form


  $(".btn-form-attr").click(function(){
    if(cur_form_id != ""){
      let sUrl = "data_mgt/mnu_form_editor_data_attr.php?formid="+cur_form_id;
      showDialog(sUrl," Form Attribute ["+cur_form_id+"]","440","600","",function(sResult){

      },false,"");
    }else{
      $.notify("not found form id");
    }
  }); // btn_search_form



  $("#txt_search_form").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_Form();
    }
  });

$("#btn_new_form").click(function(){
   addNewForm();
}); // btn_new_form
$("#btn_save_form").click(function(){
   saveData_Form();
}); // btn_save_form
$("#btn_cancel_form").click(function(){
   close_form();
}); // btn_cancel_form
$(".btn_close_form").click(function(){
   close_form();
}); // btn_close_form

$("#btn_form_view").click(function(){
   openFormView();
}); // btn_search_form

});


function openEditor(formID, isLog){
//  console.log('openeditor '+formID+'/'+isLog);

  if(isLog == '0'){
    sURL = "data_mgt/mnu_form_editor.php?form_id="+formID;
  }
  else if(isLog == '1'){
    sURL = "data_mgt/mnu_form_editor_log.php?form_id="+formID;
  }


  $("#div_form_editor_info").load(sURL, function(){
      // alert("load "+link);
      showFormDiv("editor");
  });
//  showFormDiv("editor");
}



  function clearData_Form(){
  //  $('.r_data').remove();
    cur_form_id = "";
    $(".save-data").val("");
    $("#is_log").val("0");

    $(".input-text-code").prop("disabled", false);

  }
  function addNewForm(){
    showFormDiv("detail");
    clearData_Form();
    $("#form_id").focus();
  }

function searchData_Form(){
  var aData = {
      u_mode:"select_form_list",
      is_log:"0",
      txt_search:$("#txt_search_form").val().trim()
  };
  save_data_ajax(aData,"data_mgt/db_form.php",searchData_FormMain_Complete);
}

function searchData_FormMain_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $('.r_data').remove(); // row data list
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_Form(
             dataObj.form_id, dataObj.is_log, dataObj.form_name_en,dataObj.form_name_th, dataObj.form_version_id
            );

        }//for

        $('#tbl_form_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }
  }
}



function addRowData_Form(form_id, is_log, form_name_en,form_name_th,   form_version_id){



    var txt_row = '<tr class="r_data" id="r'+form_id+'" data-id="'+form_id+'" data-log ="'+is_log+'" >' ;

    txt_row += '<td width="20%">';
    txt_row += '<button class="btn btn-sm btn-primary" type="button" onclick="openForm(\''+form_id+'\');"> <b> '+form_id+'</b></button>';
    txt_row += '</td>';

    txt_row += '<td >';
    txt_row += (is_log == '0')?'':'Log';
    txt_row += '</td>';

    txt_row += '<td width="30%" >'+form_name_en+'</td>';
    txt_row += '<td width="30%" >'+form_name_th+'</td>';

    txt_row += '<td width="20%">';
    txt_row += '<button class="mr-2 btn btn-warning" type="button" onclick="openEditor(\''+form_id+'\',\''+is_log+'\' );"> <b> Form Editor </b></button>';

    txt_row += '<button class="btn btn-sm btn-info" type="button" onclick="copyForm(\''+form_id+'\');"> C </button>';
    txt_row += '<button class="btn btn-sm btn-secondary" type="button" onclick="pasteForm(\''+form_id+'\');"> P </button>';

    txt_row += '</td>';

    txt_row += '<td >'+form_version_id+'</td>';

    txt_row += '<td width="10%">';

    txt_row += '<button class="btn btn-danger" type="button" onclick="deleteForm(\''+form_id+'\');"> <b>X</b></button>';
    txt_row += '</td>';
    txt_row += '</tr">';
    $("#tbl_form_list tbody").append(txt_row);

}

function openForm(id){ // open Data Main
  //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
//  console.log("open "+id);
    var aData = {
        u_mode:"select_form_detail",
        id:$("#r"+id).data("id")
    };
    save_data_ajax(aData,"data_mgt/db_form.php",openForm_Complete);
  }

  function openForm_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      clearData_Form();

      $('.txt_form_name').html("["+$('#r'+aData.id).find('td:nth-child(1)').text()+"] "+$('#r'+aData.id).find('td:nth-child(2)').text());

      $(".input-text-code").prop("disabled", true);

      var dataObj = rtnDataAjax.data_obj;

      cur_form_id = dataObj.form_id;
      for (x in dataObj) {
            //     console.log("enter"+"col:"+x+" / "+dataObj[x]);
          setWObjValue($("#"+x),dataObj[x]);
      }


      /*
      setWObjValue($("#form_id"),dataObj.form_id);
      setWObjValue($("#form_name"),dataObj.form_name);
      setWObjValue($("#form_desc"),dataObj.form_desc);
      setWObjValue($("#form_code"),dataObj.form_code);
      setWObjValue($("#is_log"),dataObj.is_log);
      setWObjValue($("#form_export_code"),dataObj.form_code);
      setWObjValue($("#form_export_code"),dataObj.form_export_code);
*/

      showFormDiv("detail");
    }
  }




function deleteForm(id){ // delete Data Main
  //console.log("delete "+id);
  var result = confirm("ท่านต้องการลบบันทึกใช่หรือไม่ ?");
  if (result) {
    var aData = {
        u_mode:"delete_form",
        id:$("#r"+id).data("id")
    };
    save_data_ajax(aData,"data_mgt/db_form.php",deleteFormComplete);
  }

}

function deleteFormComplete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      $("#r"+aData.id).remove();
    }
  }

  function saveData_Form(){ // save data
      var divSaveData = "div_form_detail";
      var is_data_change = false;

      var adata_obj = {};

      if(validateInput(divSaveData)){

              $("#div_form_detail .save-data").each(function(ix,objx){
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

              if(is_data_change){
                var aData = {
                u_mode:"update_form",
                data_obj:adata_obj
                };
                save_data_ajax(aData,"data_mgt/db_form.php",saveData_FormMain_Complete);

              }
              else{
                $.notify("No data changed", "info");
              }


      }
      else{
        $.notify("Incomplete Data, Please Check!", "error");
      }



    }

    function saveData_FormMain_Complete(flagSave, rtnDataAjax, aData){
      if(flagSave){
        $.notify("Save Data Mained successfully.", "success");
        $("#div_form_detail .save-data").each(function(ix,objx){
            var data_val = getWObjValue(objx);
            setWObjValue(objx,data_val);
        });
        $(".input-text-code").prop("disabled", true);
        cur_form_id = $("#form_id").val();
        searchData_Form();
      //  showFormDiv("list");
      }
    }


    function copyForm(formID){ // copy from form id
      form_id_copy_origin=formID;
      $.notify("Select copy from : "+form_id_copy_origin+".", "info");

    }
    function pasteForm(formID){ // paste to form id
      //console.log("delete "+id);
      form_id_copy_dest=formID;
      if(form_id_copy_origin == ""){
        $.notify("Please select origin form to copy.", "warn");
        return;
      }
      else if (form_id_copy_origin == form_id_copy_dest){
        $.notify("Copy form origin and destination is not different.", "warn");
        return;
      }
      var result = confirm("Are you sure to copy "+form_id_copy_origin+" and paste to "+form_id_copy_dest+" ?");
      if (result) {
        var aData = {
            u_mode:"copy_paste_form",
            origin_form_id:form_id_copy_origin,
            dest_form_id:form_id_copy_dest
        };

        console.log("copy from "+form_id_copy_origin+" to "+form_id_copy_dest+".");
        save_data_ajax(aData,"data_mgt/db_form.php",pasteFormComplete);
      }

    }
    function pasteFormComplete(flagSave, rtnDataAjax, aData){
      if(flagSave){
        $.notify("Copy "+form_id_copy_origin+" to  "+form_id_copy_dest+"  successfully.", "success");

      }
    }


function close_form(){
  clearData_Form();
  showFormDiv("list");

}


function showFormDiv(choice){
  //alert("showFormDiv "+choice);
  $(".div-form-data").hide();
  $("#div_form_"+choice).show();
}

</script>
