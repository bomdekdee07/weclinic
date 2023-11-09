
<style>
/*  group choose button color */
.POC001 {
  background-color : #F8AC8C;
}
.POC002 {
  background-color : #E5AACA;
}
.POC003 {
  background-color : #B2E0DC;
}
.POC004 {
  background-color : #00ABBC;
}

</style>

<link rel="stylesheet" href="../asset_iclinic/iclinic.css">


<div class="card div-uid-enroll" id="div_choose_enroll_group">
  <div class="card-body">
    <h5 class="card-title"><i class="fa fa-folder fa-lg" ></i> กรุณาเลือกกลุ่ม</h5>
    <div id="div_choose_enroll_group_detail">
       <center>รอสักครู่</center>
    </div>
  </div>
</div>

<div class="card div-uid-enroll" id="div_project_enroll_info">
  <div class="card-body">
    <p id="div_project_enroll_info_detail"> </p>

    <div class="my-2"><h4><span id="div_project_enroll_pid">
      <i class="fa fa-clipboard-check fa-lg"></i> ยืนยันการลงทะเบียน UID เข้าโครงการ</span></h4>
    </div>
    <div class="row mt-1">
      <div class="col-md-2">โครงการ:</div>
      <div class="col-md-10" id="p_enroll_proj_name">xxx</div>
    </div>
    <div class="row mt-1">
      <div class="col-md-2">UID:</div>
      <div class="col-md-10" id="p_enroll_uid">xxx</div>
    </div>

    <div class="row mt-1 mb-4" id="div_enroll_group">
      <div class="col-md-2">กลุ่ม:</div>
      <div class="col-md-10" id="p_enroll_group">xxx</div>
    </div>

    <div class="row mt-4" id="div_enroll_confirm">
      <div class="col-md-6">
        <button type="button" id="btn_enroll_confirm" class="form-control btn btn-success btn-lg">
             <i class="fa fa-check fa-lg"></i> ยืนยัน
        </button>
      </div>
      <div class="col-md-6">
        <button type="button" id="btn_enroll_cancel" class="form-control btn btn-danger btn-lg">
             <i class="fa fa-times fa-lg"></i> ยกเลิก
        </button>
      </div>
    </div>




  </div>
</div> <!-- div_project_enroll_info -->



<input type="hidden" id="enroll_group_id" >
<input type="hidden" id="enroll_group_name" >
<input type="hidden" id="enroll_pid_format" >
<input type="hidden" id="enroll_clinic_prefix_id" >
<input type="hidden" id="enroll_pid_digit" >

<script>
var uid_enroll = "";
$(document).ready(function(){

  initEnrollData();

  $("#btn_enroll_confirm").click(function(){
    // prevent doubleclick
     if(uid_enroll != $('#cur_uid').val())
     enrollUIDtoProject();
  }); // enrollToProject

  $("#btn_enroll_cancel").click(function(){
     showUIDDivScreen("project_screen_info");
  }); // cancel enrollToProject

});


function initEnrollData(){
  $('.div-uid-enroll').hide();
  selectPreEnrollment();
}



function confirmEnrollUIDtoProject(pidFormat, clinicPrefixID, runningDigit, groupID, groupName){

  $('#enroll_group_id').val(groupID);
  $('#enroll_group_name').val(groupName);
  $('#enroll_pid_format').val(pidFormat);
  $('#enroll_clinic_prefix_id').val(clinicPrefixID);
  $('#enroll_pid_digit').val(runningDigit);

  $('#p_enroll_uid').html($('#cur_uid').val());
  $('#p_enroll_proj_name').html($('#cur_proj_name').val());
  $('#p_enroll_group').html("["+groupID+"] "+groupName);



  showUIDDivEnroll("project_enroll_info");

}
/*
function enrollUIDtoProject(pidFormat, clinicPrefixID, runningDigit, groupID){
  var aData = {
            u_mode:"enroll_uid",
            uid:$('#cur_uid').val(),
            proj_id:$('#cur_proj_id').val(),
            pid_format:pidFormat,
            clinic_prefix_id:clinicPrefixID,
            pid_digit:runningDigit,
            group_id:groupID
  };
  save_data_ajax(aData,"w_user/db_proj_enroll.php",enrollUIDtoProjectComplete);
}
*/
function enrollUIDtoProject(){
  uid_enroll = $('#cur_uid').val();
  var aData = {
            u_mode:"enroll_uid",
            uid:$('#cur_uid').val(),
            proj_id:$('#cur_proj_id').val(),
            pid_format:$('#enroll_pid_format').val(),
            clinic_prefix_id:$('#enroll_clinic_prefix_id').val(),
            pid_digit:$('#enroll_pid_digit').val(),
            group_id:$('#enroll_group_id').val(),
            visit_note:$('#visit_note').val(),
            visit_date:$('#cur_screen_date').val()
  };
  save_data_ajax(aData,"w_user/db_proj_enroll.php",enrollUIDtoProjectComplete);

}

function enrollUIDtoProjectComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
      if(rtnDataAjax.id != ""){
        $('#cur_group_id').val($('#enroll_group_id').val());
        createNewScheduleVisit();
        $('#div_project_enroll_pid').html("PID : "+rtnDataAjax.id);
        $('#cur_pid').val(rtnDataAjax.id);
        $.notify("ลงทะเบียนสำเร็จ","info");
        showUIDDivEnroll("project_enroll_info");
        //setDataChangeProj();

        goVisitList($('#cur_proj_id').val(), $('#cur_proj_name').val(), $('#cur_pid').val());
      }
      else{
        $.notify(rtnDataAjax.msg_enroll,"info");
      }

  }
}

function createNewScheduleVisit(){
  var aData = {
            u_mode:"create_new_visit_schedule",
            uid:$('#cur_uid').val(),
            proj_id:$('#cur_proj_id').val(),
            group_id:$('#cur_group_id').val()
  };
  save_data_ajax(aData,"w_user/db_proj_visit.php",createNewScheduleVisitComplete);
}

function createNewScheduleVisitComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
      $.notify("สร้างตารางนัดหมายสำเร็จ "+rtnDataAjax.visit_count+" นัดหมาย","info");
  }
}






function selectPreEnrollment(){
  var aData = {
            u_mode:"sel_pre_project_enroll",
            proj_id:$('#cur_proj_id').val()
  };
  save_data_ajax(aData,"w_user/db_proj_enroll.php",selectPreEnrollmentComplete);
}

function selectPreEnrollmentComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave+" / "+$('#cur_proj_id').val());
  if(flagSave){
      var datalist = rtnDataAjax.datalist;

      if(datalist.length > 0){ // project has group to choose
        var txt_row = "";

        for (i = 0; i < datalist.length; i++){
          var dataObj = datalist[i];
          txt_row += ' <button class="'+$('#cur_proj_id').val()+dataObj.group_id+' btn btn-block mt-4 " type="button"  onclick="confirmEnrollUIDtoProject(\''+rtnDataAjax.pid_format+'\',\''+rtnDataAjax.clinic_prefix_id+'\',\''+rtnDataAjax.pid_digit+'\',\''+dataObj.group_id+'\',\''+dataObj.group_name+'\')""><i class="fa fa-users"></i> ['+dataObj.group_id+'] <b>'+dataObj.group_name+'</b></button></td>';
        //  txt_row += ' <button class="btn btn-success btn-block mt-4" type="button"  onclick="confirmEnrollUIDtoProject(\''+rtnDataAjax.pid_format+'\',\''+rtnDataAjax.clinic_prefix_id+'\',\''+rtnDataAjax.pid_digit+'\',\''+dataObj.group_id+'\',\''+dataObj.group_name+'\')""><i class="fa fa-users"></i> ['+dataObj.group_id+'] <b>'+dataObj.group_name+'</b></button></td>';


        } //for
        $("#div_choose_enroll_group_detail").html(txt_row);
        showUIDDivEnroll("choose_enroll_group");
      }//if
      else{
        confirmEnrollUIDtoProject(rtnDataAjax.pid_format,rtnDataAjax.clinic_prefix_id,rtnDataAjax.pid_digit,'', ''); // no group select
        //showUIDDivEnroll("project_enroll_info");
      }

  }
}

function showUIDDivEnroll(choice){
  $('.div-uid-enroll').hide();
  $('#div_'+choice).show();
}


</script>
