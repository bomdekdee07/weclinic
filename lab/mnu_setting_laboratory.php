<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

include_once("inc_auth.php"); // set permission view, update, delete
include_once("css_lab_setting.php"); // set permission view, update, delete


//echo "auth view : ".$auth["enroll"];
//echo "clinic id : $staff_clinic_id";

$setting_col_id = "laboratory_id";

$search_head = '
<div class="col-sm-9">
  <input type="text" id="txt_search_setting" class="form-control" placeholder="พิมพ์คำค้นหา">
</div>
';
if($is_data == "1"){
  $search_head = '
  <div class="col-sm-2">
   <button class="btn btn-success form-control" type="button" id="btn_new_setting"><i class="fa fa-plus" ></i> เพิ่ม</button>
  </div>
  <div class="col-sm-7">
    <input type="text" id="txt_search_setting" class="form-control" placeholder="พิมพ์คำค้นหา">
  </div>
  ';
}

?>


<script>

var s_clinic_id = "<? echo $staff_clinic_id; ?>";
ResetTimeOutTimer();

 u_mode_setting = ""; // update mode in setting dialog
 cur_setting_id = ""; // current setting record id
 cur_setting_choice = "laboratory"; // current setting choice eg. lab_specimen, lab_testing_menu
 cur_setting_col_id = "laboratory_id"; // current setting table eg. lab_specimen, lab_testing_menu
 cur_setting_title = "Laboratory (ห้องแล็ป)";
</script>


<div id='div_setting_list' class='div-setting-menu my-0'>
  <div class="row mt-0">
     <? echo $search_head; ?>
     <div class="col-sm-3">
      <button class="btn btn-info form-control" type="button" id="btn_search_setting"><i class="fa fa-search" ></i> ค้นหา</button>
     </div>
   </div>
   <div class="mt-2">
      <table id="tbl_setting_list" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
         <thead>
           <tr>
             <th>Mode</th>
             <th>Name</th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_setting_list -->

<div id='div_setting_detail' class="div-setting-menu my-0" style="display:none;">

  <div id='div_setting_detail_form' >
    <div class=" my-1" style="background-color:#eee;">
      <center>
      Record ID: <input type="text" id="s<? echo $setting_col_id; ?>" value="xx" size="20" disabled>
    </center>
    </div>
    <div class="row my-1">
      <div class="col-sm-12">
        <label for="laboratory_name">Name:</label>
        <input type="text" id="laboratory_name" data-title="Name"  class="form-control form-control-sm save-data v-no-blank " maxlength="150">
      </div>

     </div>
     <div class="row my-1">
       <div class="col-sm-12">
         <label for="laboratory_note">Note:</label>
         <textarea id="laboratory_note" rows="4"  data-title="Note" class="form-control save-data" placeholder="กรอกหมายเหตุ"></textarea>
       </div>

      </div>


  </div>
</div> <!-- div_setting_detail -->

<script>

u_mode_setting = "";
$('#setting_detail_title').html(cur_setting_title);

$(document).ready(function(){

  $("#btn_search_setting").click(function(){
     searchData_Setting();
  }); // btn_search_setting

  $("#txt_search_setting").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_Setting();
    }
  });

  $("#btn_new_setting").click(function(){
     addSettingData();
  }); // btn_new_setting







});

function addSettingData(){
  showMenuSettingDiv("detail");
  <?
    if($is_delete == '1'){
      echo '$("#btn_delete_setting").hide();';
    }
  ?>
  u_mode_setting = "add_setting";
  $("#s"+cur_setting_col_id).val("ADD NEW");


  $('#laboratory_name').val('');
  $('#laboratory_note').val('');

  $('#laboratory_name').focus();
}


function closeSettingData(){
  showMenuSettingDiv("list");
  <?
    if($is_delete == '1'){
      echo '$("#btn_delete_setting").hide();';
    }
  ?>
}


function saveSettingData(){
  $("#btn_save_setting").prop("disabled", true);
  var divSaveData = "div_setting_detail";
  if(validateInput(divSaveData)){
   var lstDataObj = [];
   $("#"+divSaveData +" .save-data").each(function(ix,objx){
      lstDataObj.push({name:$(objx).attr("id"), value:$(objx).val()});
   });

    var aData = {
              u_mode:u_mode_setting,
              id:$("#s"+cur_setting_col_id).val(),
              setting_choice:cur_setting_choice,
              lst_data_obj: lstDataObj
    };
    save_data_ajax(aData,"lab/db_lab_setting.php",saveSettingDataComplete);

  }
  else{
    $("#btn_save_setting").prop("disabled", false);
  }

}

function saveSettingDataComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
   if(u_mode_setting == "add_setting"){
     u_mode_setting = "update_setting";
     cur_setting_id = rtnDataAjax.id;
     $("#s"+cur_setting_col_id).val(cur_setting_id);

   }
   else{
     // remove updated row  to replace with new data
     $('#r'+cur_setting_choice+$("#s"+cur_setting_col_id).val()).remove();
   }
   var arr_obj = [$('#laboratory_name').val()];
   var txt_row = addRowData_Setting(
      $("#s"+cur_setting_col_id).val(),
      arr_obj
   );

   $(".r_zero").remove();
   $("#tbl_setting_list tbody").prepend(txt_row);

   $.notify("Save data successfully.", "info");


   <?
     if($is_delete == '1'){
       echo '$("#btn_delete_setting").hide();';
     }
   ?>

   showMenuSettingDiv("list");
  }
  $("#btn_save_setting").prop("disabled", false);
}



function searchData_Setting(){
  var aData = {
      u_mode:"select_setting_list",
      setting_choice: cur_setting_choice,
      txt_search:$('#txt_search_setting').val(),
  };
  save_data_ajax(aData,"lab/db_lab_setting.php",searchDatasetting_Complete);
}

function searchDatasetting_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            var arr_obj = [dataObj.name];
            txt_row += addRowData_Setting(
             dataObj.id, arr_obj
            );

        }//for

        $('.r_setting').remove(); // row setting list
        $('#tbl_setting_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
      $('.r_setting').remove(); // row pid list
      txt_row += '<tr class="r_zero r_setting"><td colspan="3" align="center">ไม่พบข้อมูล</td></tr">';
      $('#tbl_setting_list > tbody:last-child').append(txt_row);
    }
  }
}



  function openSettingData(id_param){
    var aData = {
        u_mode:"get_setting_data",
        id:id_param,
        setting_choice: cur_setting_choice,

    };
    save_data_ajax(aData,"lab/db_lab_setting.php",openSettingData_Complete);
  }

  function openSettingData_Complete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave);
    if(flagSave){

        var dataObj = rtnDataAjax.data;
        $("#s"+cur_setting_col_id).val(dataObj.id);
        $('#laboratory_name').val(dataObj.name);
        $('#laboratory_note').val(dataObj.note);

        showMenuSettingDiv("detail");
        u_mode_setting = "update_setting";

        <?
          if($is_delete == '1'){
            echo '$("#btn_delete_setting").show();';
          }
            //echo '$("#btn_delete_setting").show();';
        ?>

    }
  }




  function deleteSettingData(){
    var result = confirm("ท่านต้องการลบข้อมูล "+$("#s"+cur_setting_col_id).val()+" นี้ใช่หรือไม่ ?");
    if (result) {
      var aData = {
          u_mode:"delete_setting_data",
          id:$("#s"+cur_setting_col_id).val(),
          setting_choice: cur_setting_choice

      };
      save_data_ajax(aData,"lab/db_lab_setting.php",deleteSettingData_Complete);
    }
  }

  function deleteSettingData_Complete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave);
    if(flagSave){
        $('#r'+cur_setting_choice+aData.id).remove();
        showMenuSettingDiv("list");
    }
  }


  function getSettingData(id){
      setDataSettingToComponent(id, $('#r'+cur_setting_choice+id).find('td:nth-child(2)').text());
  }

</script>
