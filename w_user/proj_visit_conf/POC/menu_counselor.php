<?
// status_id, group_id from menu.php

$option_visit = "
<option value='2' class='text-danger '>รอความสมบูรณ์ของข้อมูล/Wait for complete data</option>
<option value='3' class='text-danger'>รอส่งต่อเพื่อรับการรักษา</option>
<option value='10' class='text-danger'>ไม่มาตามนัดหมาย/Lost to Follow Up</option>
<option value='30' class='text-info'>รอผลตรวจ/Wait for Lab Result</option>
<option value='1' class='text-success'>เสร็จสิ้นนัดหมาย/Complete</option>

";


$arr_opt_group = array();
$arr_opt_group["001"] = "Group 1 HIV neg & start PrEP";
$arr_opt_group["002"] = "Group 2 HIV neg & on PrEP";
$arr_opt_group["003"] = "Group 3 HIV neg";
$arr_opt_group["004"] = "Group 4 HIV pos";

$option_group = "";
foreach ($arr_opt_group as $id => $name){
  if($id != $group_id){
    $option_group .= "<option value='$id' >$name</option>";
  }
}

//echo "group_id : $group_id";
/*
$option_group = "
<option value='001' >Group 1 HIV neg & start PrEP</option>
<option value='002' >Group 2 HIV neg & on PrEP</option>
<option value='003' >Group 3 HIV neg</option>
<option value='004' >Group 4 HIV pos</option>
";
*/

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

<!-- เปลี่ยนกลุ่ม -->
  <div class="card my-1" id="div_group_change">
    <div class="card-body">
      <div class="row">
         <div class="col-sm-9">
           <div class="my-1">
             <label for="sel_visit_group_id"><i class="fa fa-users-cog"></i> เปลี่ยนกลุ่ม </label>
             <select id="sel_visit_group_id" class="form-control form-control-sm" >
               <option class="text-secondary" selected="true" disabled="disabled">เลือกกลุ่มใหม่</option>
              <? echo $option_group; ?>
             </select>
           </div>
           <div class="my-1">
             <label for="groupchange_note"> เหตุผลการเปลี่ยนกลุ่ม </label>
             <textarea class="form-control" id="groupchange_note" rows="4"  data-title='Group Change Note'></textarea>
           </div>

         </div>
         <div class="col-sm-3">
           <label for="btn_change_project_group" class="text-light">.</label>
           <button id="btn_change_project_group" class="form-control form-control-sm btn btn-warning btn-sm" type="button">
             OK
           </button>
         </div>
      </div>
     </div>
   </div>
<!-- end เปลี่ยนกลุ่ม -->

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
  <?
  if($group_id == "004"){
    echo '$("#div_group_change").hide();';
  }
  ?>

  $("#btn_change_visit_status").click(function(){
    if($('#sel_visit_status_id').val()){
      if($('#sel_visit_status_id').val() == '1'){
        var result = confirm("ยืนยันที่จะเปลี่ยนสถานะเป็นเสร็จสิ้น ?");
        if (result) {
          updateVisitStatus();
        }
      }
      else if($('#sel_visit_status_id').val() == '10'){
        var result = confirm("ยืนยันที่จะเปลี่ยนสถานะเป็น ไม่มาตามนัดหมาย ?");
        if (result) {
          updateVisitStatus();
        }
      }
      else{
        updateVisitStatus();
      }
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
//  alert("change group : "+$('#sel_visit_group_id').val());
  if($('#sel_visit_group_id').val()){
    if($('#groupchange_note').val().trim() != ""){
      var aData = {
                u_mode:"change_group",
                uid:$('#cur_uid').val(),
                proj_id:$('#cur_proj_id').val(),
                visit_id:$('#cur_visit_id').val(),
                visit_date:$('#cur_visit_date').val(),
                group_id:$('#cur_group_id').val(),
                new_group_id:$('#sel_visit_group_id').val(),
                groupchange_note:$('#groupchange_note').val().trim()
      };
      save_data_ajax(aData,"w_user/proj_visit_conf/POC/db_POC.php",changeProjectGroupComplete);

    }
    else{
      $('#groupchange_note').notify("กรุณากรอกเหตุผลที่เปลี่ยนกลุ่มก่อน","error");
    }

  }
  else{
      $('#sel_visit_group_id').notify("กรุณาเลือกกลุ่มใหม่","error");
  }



}

function changeProjectGroupComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
      $.notify("เปลี่ยนกลุ่ม่สำเร็จ","success");
      $('#cur_group_id').val(aData.new_group_id);
      selectVisitList();
      showUIDDivVisit("uid_visit_list");
      if(aData.new_group_id == "004"){
        myModalContent("เปลี่ยนกลุ่ม", "เมื่อเปลี่ยนเป็นกลุ่ม 4 แล้ว<br><b>กรุณากรอกฟอร์ม Sero-converseion ในกระดาษด้วยนะครับ</b> ", "info");
      }


    }//if
    else{
      $.notify("เปลี่ยนกลุ่มไม่สำเร็จ","error");
    }
}



</script>
