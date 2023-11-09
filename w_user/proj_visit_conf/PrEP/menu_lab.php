<?
// lab menu
// status_id, group_id from menu.php

$option_visit = "
<option value='30' class='text-info'>รอผลตรวจแล็ป</option>
<option value='21' class='text-success'>ลงผลแล็ปเสร็จสิ้น</option>
<option value='31' class='text-danger '>รอผลตรวจ Lab นอก/Wait for External Lab Result</option>
";
?>


<div class="card my-1" id="div_project_visit_menu">
  <div class="card-header"><i class="fa fa-calendar-day fa-lg" ></i> ส่วนจัดการนัดหมาย </div>
  <div class="card-body">

<!-- เปลี่ยนสถานะนัดหมาย -->
  <div class="card my-1">
    <div class="card-body">
      <div class="row">
         <div class="col-sm-9">
           <label for="sel_visit_status_id" ><i class="fa fa-asterisk"></i> เปลี่ยนสถานะนัดหมาย</label>
           <select id="sel_visit_status_id" class="form-control form-control-sm" >
             <option class="text-secondary" selected="true" disabled="disabled">เลือกสถานะใหม่</option>
            <? echo $option_visit; ?>
           </select>
         </div>
         <div class="col-sm-3">
           <label for="btn_change_visit_status" class="text-light">.</label>
           <button id="btn_change_visit_status" class="form-control form-control-sm btn btn-warning btn-sm" type="button">
             OK
           </button>
         </div>
      </div>
     </div>
   </div>
<!-- end เปลี่ยนสถานะนัดหมาย -->

<!-- แก้ไข visit note -->
  <div class="card my-1">
    <div class="card-body">
      <h5 class="card-title"><i class="fa fa-sticky-note"></i> Visit Note:</h5>
      <div class="my-1">
        <textarea class="form-control" id="visit_note" rows="4"  data-title='Visit Note'></textarea>
      </div>
      <div>
        <button id="btn_change_visit_note" class="form-control form-control-sm btn btn-warning btn-sm" type="button">
          OK
        </button>
      </div>

     </div>
   </div>
<!-- end แก้ไข visit note -->

  </div>
</div>



<script>
$(document).ready(function(){

  $("#btn_change_visit_status").click(function(){
    if($('#sel_visit_status_id').val()){ 
      updateVisitStatus();
    }
    else{
      $.notify("กรุณาเลือกสถานะ","error");
    }
  }); // btn_change_visit_status

  $("#btn_change_project_group").click(function(){
     changeProjectGroup();
  }); // cancel enrollToProject

  $("#btn_change_visit_note").click(function(){
     updateVisitNote();
  }); // cancel enrollToProject

});

function initVisitMenu(){
  //$('#visit_note').val('visit_note');
}

function updateVisitStatus(){
  var aData = {
            u_mode:"update_visit_status",
            uid:$('#cur_uid').val(),
            proj_id:$('#cur_proj_id').val(),
            group_id:$('#cur_group_id').val(),
            visit_id:$('#cur_visit_id').val(),
            visit_date:$('#cur_visit_date').val(),
            status_id:$('#sel_visit_status_id').val()
  };
  //alert("show "+aData.uid+"/"+)
  save_data_ajax(aData,"w_user/db_proj_visit.php",updateVisitStatusComplete);
}

function updateVisitStatusComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
      if(aData.status_id == "1" || aData.status_id == "10"){ // if complete or cancel visit
         selectVisitList();
         showUIDDivVisit("uid_visit_list"); // back to visit list
      }
    }//if
    else{
      $.notify("เปลี่ยนสถานะไม่สำเร็จ","error");
    }
}



function  updateVisitNote(){
  var aData = {
    u_mode:"update_visit_note",
    uid:$('#cur_uid').val(),
    proj_id:$('#cur_proj_id').val(),
    group_id:$('#cur_group_id').val(),
    visit_id:$('#cur_visit_id').val(),
    visit_date:$('#cur_visit_date').val(),
    visit_note:$('#visit_note').val()
  };

  //alert("show "+aData.uid+"/"+)
  save_data_ajax(aData,"w_user/db_proj_visit.php",updateVisitNoteComplete);
}

function updateVisitNoteComplete(flagSave, rtnDataAjax, aData){
//  alert("flag save is : "+flagSave);
  if(flagSave){
    alert("save ja :"+aData.visit_note);
    $('#v_visit_note').val(aData.visit_note);
  }//if
  else{
    $.notify("แก้ไข visit note ไม่สำเร็จ","error");
  }
}


function changeProjectGroup(){
  var aData = {
            u_mode:"change_group",
            uid:$('#cur_uid').val(),
            proj_id:$('#cur_proj_id').val(),
            visit_id:$('#cur_visit_id').val(),
            visit_date:$('#cur_visit_date').val(),
            group_id:$('#cur_group_id').val(),
            new_group_id:$('#sel_visit_group_id').val()
  };
  //alert("show "+aData.uid+"/"+)
  save_data_ajax(aData,"w_user/proj_visit_conf/POC/db_POC.php",changeProjectGroupComplete);
}

function changeProjectGroupComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
      $.notify("เปลี่ยนกลุ่ม่สำเร็จ","success");
      selectVisitList();
      showUIDDivVisit("uid_visit_list");
    }//if
    else{
      $.notify("เปลี่ยนกลุ่มไม่สำเร็จ","error");
    }
}



</script>
