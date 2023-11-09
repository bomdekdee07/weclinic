
<?
$txt_require_data = "<span class='text-danger'>*</span>";
?>

<div id="staff_personal_data" class="card">
  <div  class="card-header bg-primary text-white">

  </div>
  <div class="card-body">
    <!--
    <div class="row bg-warning">

       <div class="col-sm-6">
         <label for="staff_clinic_id">หน่วยงาน:</label>
         <select id="staff_clinic_id" class="form-control" >
               <? include_once("/opt_data/opt_clinic.php"); ?>
         </select>
       </div>
       <div class="col-sm-6">
         <label for="staff_status_id">สถานะ:</label>
         <select id="staff_status_id" class="form-control" >
           <option value="1" selected >Active</option>
           <option value="0">Inactive</option>
         </select>
       </div>

     </div>
   -->
     <div class="row">
        <div class="col-sm-12">
          <label for="s_name">ชื่อ-นามสกุล:</label>
          <input type="text" id="s_name" class="form-control save-data v-no-blank" data-title="ชื่อ-นามสกุล">
        </div>
    </div>
    <div class="row">

       <div class="col-sm-6">
         <label for="s_email">Email:</label>
         <input type="text" id="s_email" class="form-control save-data v-email v-no-blank" data-title="Email">
       </div>
       <div class="col-sm-2">
         <label for="s_tel">Tel:</label>
         <input type="text" id="s_tel" class="form-control save-data" data-title="Tel">
       </div>
     </div>
     <div class="row">
        <div class="col-sm-12">
          <label for="s_remark">หมายเหตุ:</label>
          <textarea class="form-control save-data" id="s_remark" rows="5"  data-title='หมายเหตุ'></textarea>
        </div>
    </div>

  </div>
  <div class="card-footer">
    <button id="btn_save_staff_detail" class="form-control btn btn-primary" type="button">
      <i class="fa fa-check fa-lg" ></i> SAVE
    </button>
  </div>
</div> <!-- staff_personal_data -->


<script>
$(document).ready(function(){

  $("#btn_save_staff_detail").click(function(){
     saveStaffPersonalData();
  }); //saveStaffPersonalData()
});


function getStaffClinic(staffID, scID){
  //alert("getStaffData "+staffID);
  $('#cur_s_id').val(staffID);
  $('#cur_sc_id').val(scID);
  var aData = {
            u_mode:"get_staff_clinic",
            sc_id:scID
  };
  save_data_ajax(aData,"w_admin/db_staff_mgt.php",getStaffClinicComplete);

}
function getStaffClinicComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

      var arr_staff = rtnDataAjax.arr_staff;
      if(arr_staff['s_id'] !=""){

        $('#s_id').val(arr_staff['s_id']);
        $('#s_name').val(arr_staff['s_name']);
        $('#s_email').val(arr_staff['s_email']);
        $('#s_tel').val(arr_staff['s_tel']);
        $('#s_remark').val(arr_staff['s_remark']);
        $('#s_status').val(arr_staff['s_status']);

        updateStaffTitle(arr_staff['s_id'], arr_staff['s_name']);
        showStaffDetailDiv("staff_personal_data");
        showStaffMainDiv("staff_detail");

      }

  }
}


function saveStaffPersonalData(){
  alert("saveStaffPersonalData ");

  if(validateInput("staff_personal_data")){
    var aData = {
              u_mode:"update_staff_personal_data",
              s_id:$('#cur_s_id').val(),
              s_name:$('#s_name').val(),
              s_email:$('#s_email').val(),
              s_remark:$('#s_remark').val(),
              s_status:$('#s_status').val(),
    };
    save_data_ajax(aData,"w_admin/db_staff_mgt.php",saveStaffPersonalDataComplete);
  }


}
function saveStaffPersonalDataComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
     updateStaffTitle($('#cur_s_id').val(), aData.s_name);
  }
}




</script>
