<?
//$proj_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";

?>

<div class="card" id="div_uid_schedule_list">
  <div class="card-body">
    <div class="card-title">


      <div class="row">
         <div class="col-sm-2">
           <h5><i class="fa fa-calendar-alt fa-lg" ></i> ตารางนัดหมาย</h5>
         </div>

         <div class="col-sm-2">
           <label for="btn_export_uid_schedule" class="text-light">.</label>
          <button class="btn btn-primary form-control" type="button" id="btn_export_uid_schedule"><i class="fa fa-file-export" ></i> Export</button>
         </div>
         <div class="col-sm-2">
           <label for="search_date_opt">ค้นหาจาก:</label>
           <select id="search_date_opt" class="form-control" >
             <option value="schedule_date" selected >วันนัดหมาย (Schedule Date)</option>
             <option value="visit_date">วันเข้าตรวจ (Visit Date)</option>
           </select>
         </div>
         <div class="col-sm-2">
           <label for="sel_uid_schedule_date_beg">ตั้งแต่วันที่:</label>
           <input type="text" id="sel_uid_schedule_date_beg" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="sel_uid_schedule_date_end">ถึงวันที่:</label>
           <input type="text" id="sel_uid_schedule_date_end" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="btn_search_uid_schedule" class="text-light">.</label>
          <button class="btn btn-info form-control" type="button" id="btn_search_uid_schedule"><i class="fa fa-search" ></i> ค้นหา</button>
         </div>



       </div>


    </div>
    <div>
      <table id="tbl_uid_schedule_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th>วันที่นัดหมาย</th>
              <th>วันที่เข้าตรวจ</th>
              <th>โครงการ</th>
              <th>PID</th>
              <th>UID / UIC</th>
              <th>สถานะ</th>
              <th>หมายเหตุ</th>
              <th>ติดต่อ</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
      </table>
    </div>
  </div>
</div>




<input type="hidden" id="cur_schedule_date">

<script>

$(document).ready(function(){

  initScheduleList();

  $("#btn_search_uid_schedule").click(function(){
     //alert("clinic scheud");
     selectUIDScheduleList();
  }); // btn_search_uid_schedule
  $("#btn_export_uid_schedule").click(function(){
     //alert("clinic scheud");
     exportUIDSchedule();
  }); // btn_export_uid_schedule


  var currentDate = new Date();
  currentDate.setYear(currentDate.getFullYear() + 543);

    $("#sel_uid_schedule_date_beg").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy',
      onSelect: function(date) {
        $("#sel_uid_schedule_date_beg").addClass('filled');
      }
    });
    $("#sel_uid_schedule_date_end").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy',
      onSelect: function(date) {
        $("#sel_uid_schedule_date_end").addClass('filled');
      }
    });

    $('#sel_uid_schedule_date_beg').datepicker("setDate",currentDate );
    $('#sel_uid_schedule_date_end').datepicker("setDate",currentDate );

    $('#sel_uid_schedule_date_beg').change(function(){
      //alert("change ja");
      //$("#sel_uid_schedule_date_end" ).datepicker('setDate', new Date($("#sel_uid_schedule_date_beg" ).val()));
    });

});


function initScheduleList(){
//  selectUIDScheduleList();
}


function selectUIDScheduleList(){

  var aData = {
            u_mode:"select_uid_schedule_list",
            search_date_opt:$('#search_date_opt').val(),
            schedule_date_beg:changeToEnDate($('#sel_uid_schedule_date_beg').val()),
            schedule_date_end:changeToEnDate($('#sel_uid_schedule_date_end').val())
  };
  save_data_ajax(aData,"w_user/db_uid_data.php",selectUIDScheduleListComplete);

}

function selectUIDScheduleListComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
//tbl_uid_schedule_list
      txt_row="";
      if(rtnDataAjax.uid_list.length > 0){
        var enroll_date = "";
        var btn_pid = "";
        var datalist = rtnDataAjax.uid_list;
          for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];

            btn_pid = "";

            btn_pid = '<button class="btn btn-info" type="button" onclick="goVisitListBySchedule(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\',\''+dataObj.pid+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\')""><i class="fa fa-user"></i> '+dataObj.pid+'</button>';

            txt_row += '<tr class="r_uid_schedule">';
            txt_row += ' <td>'+changeToThaiDate(dataObj.schedule_date)+'</td>';
            txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+'</td>';
            //txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+'</td>';
            txt_row += ' <td>'+dataObj.proj_name;
            txt_row += ' <span class="badge badge-warning">'+dataObj.visit_name+ '</span></td>';
            txt_row += ' <td>'+btn_pid+'</td>';
            txt_row += ' <td>'+dataObj.uid+' / '+dataObj.uic+'</td>';

            txt_row += ' <td>'+dataObj.status_name+'</td>';
            txt_row += ' <td>'+dataObj.schedule_note+'</td>';
            txt_row += ' <td>'+dataObj.tel+'</td>';

            txt_row += '</tr">';
          }//for

      }
      $('.r_uid_schedule').remove(); // row uic proj summary
      $('#tbl_uid_schedule_list > tbody:last-child').append(txt_row);


  }
}


function exportUIDSchedule(){
    var aData = {
      date_opt:$('#search_date_opt').val(),
      date_beg:changeToEnDate($('#sel_uid_schedule_date_beg').val()),
      date_end:changeToEnDate($('#sel_uid_schedule_date_end').val())
    };
    save_data_ajax(aData,"w_data/xls_uid_schedule_list.php",exportUIDScheduleComplete);
}

function exportUIDScheduleComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+rtnDataAjax.link_xls);
  if(flagSave){
    window.open(rtnDataAjax.link_xls, '_blank');
  }
}


</script>
