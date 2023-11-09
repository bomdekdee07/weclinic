<?
$schedule_date = isset($_GET["schedule_date"])?$_GET["schedule_date"]:"";
$d_before = isset($_GET["d_before"])?$_GET["d_before"]:"";
$d_after = isset($_GET["d_after"])?$_GET["d_after"]:"";
//echo "$schedule_date/$d_before/$d_after";
?>

<div class="card" id="div_uid_visit_list">
  <div class="card-header">
    <div class="row">
      <div class="col-sm-11">
        <h4><i class="fa fa-calendar-alt fa-lg" ></i> เปลี่ยนวันนัดหมายใหม่จาก <? echo $schedule_date;?></h4>
     </div>
     <div class="col-sm-1">
       <button type="button" id="btn_close_schedule_date_update" class="close " aria-label="Close">
            <span aria-hidden="true" class="text-danger"><b><h2>&times;</h2></b></span>
       </button>
    </div>
    </div>
  </div>

  <div class="card-body">
    <h5 class="card-title"><i class="fa fa-calendar-alt fa-lg" ></i> กรุณาเลือกวันนัดหมายใหม่ </h5>
    <div id="div_schedule_date_update_detail">
      <div class="row ">
         <div class="col-sm-12">
           <p>นัดหมายใหม่: <input type="text" id="new_schedule_date"></p>
         </div>
      </div>

      <div class="row ">
         <div class="col-sm-6">
           <button id="btn_update_schedule_date" class="form-control btn btn-success btn-lg" type="button">
             <h5> <i class="fa fa-check fa-lg" ></i> ตกลง </h5>
           </button>
         </div>
         <div class="col-sm-6">
           <button id="btn_cancel_schedule_date" class="form-control btn btn-danger btn-lg" type="button">
             <h5> <i class="fa fa-times fa-lg" ></i> ยกเลิก </h5>
           </button>
         </div>
      </div>

    </div>
  </div>
</div>

<input type="hidden" id="cur_schedule_date" value='<? echo $schedule_date; ?>'>

<script>
$(document).ready(function(){


  $("#btn_close_schedule_date_update").click(function(){
     showUIDDivVisit("uid_visit_list");
  }); // btn_close_schedule_date_update
  $("#btn_cancel_schedule_date").click(function(){
     showUIDDivVisit("uid_visit_list");
  }); // btn_cancel_schedule_date

  $("#btn_update_schedule_date").click(function(){
    updateScheduleDate();
  }); // btn_update_schedule_date


//  $.datepicker.setDefaults( $.datepicker.regional[ "th" ] );
  var currentDate = new Date();
  var begDate = new Date();
  var endDate = new Date();

  var dateVal = '<? echo $schedule_date;?>';
  var arrDate = dateVal.split("-");
  //alert("<? echo $schedule_date;?>/"+arrDate[0]+"-"+arrDate[1]+"-"+arrDate[2]);
  currentDate.setFullYear(arrDate[0], parseInt(arrDate[1])-1, arrDate[2]);
  begDate.setFullYear(arrDate[0], parseInt(arrDate[1])-1, arrDate[2]);
  endDate.setFullYear(arrDate[0], parseInt(arrDate[1])-1, arrDate[2]);
//alert("currentDate1: "+currentDate);

  begDate.setDate(begDate.getDate() - <? echo $d_before;?>);
  endDate.setDate(endDate.getDate() + <? echo $d_after;?>);

  currentDate.setYear(currentDate.getFullYear());
  begDate.setYear(begDate.getFullYear());
  endDate.setYear(endDate.getFullYear());


    $("#new_schedule_date").datepicker({
      changeMonth: true,
      changeYear: true,

      dateFormat: 'dd/mm/yy',
      minDate: begDate,
      maxDate: endDate,

      onSelect: function(date) {
        $("#new_schedule_date").addClass('filled');
      }
    });
    $('#new_schedule_date').datepicker("setDate",currentDate );
//alert("currentDate: "+currentDate);
});



function updateScheduleDate(){
  var aData = {
            u_mode:"update_schedule_date",
            uid:$('#cur_uid').val(),
            visit_id:$('#cur_visit_id').val(),
            proj_id:$('#cur_proj_id').val(),
            old_schedule_date:'<? echo $schedule_date; ?>',
            group_id:$('#cur_group_id').val(),
            new_schedule_date:changeToEnDate($('#new_schedule_date').val())
  };
  save_data_ajax(aData,"w_user/db_proj_visit.php",updateScheduleDateComplete);
}

function updateScheduleDateComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
     $.notify("เปลี่ยนวันนัดหมายแล้วเป็น "+$('#new_schedule_date').val(),"info");
     selectVisitList();
     showUIDDivVisit("uid_visit_list");

  }
}


</script>
