<?
include_once("../in_auth.php");

?>

<div class="card" id="div_counseling_case_list">
  <div class="card-body">
    <div class="card-title">


      <div class="row">
         <div class="col-sm-6">
           <h5><i class="fa fa-address-book fa-lg" ></i> การให้คำปรึกษา</h5>
         </div>

         <div class="col-sm-2">
           <label for="ps_trainee_id">ตั้งแต่วันที่:</label>
           <input type="text" id="sel_counseling_date_beg" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="ps_trainee_id">ถึงวันที่:</label>
           <input type="text" id="sel_counseling_date_end" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="btn_search_uid_schedule" class="text-light">.</label>
          <button class="btn btn-info form-control" type="button" id="btn_search_counseling"><i class="fa fa-search" ></i> ค้นหา</button>
         </div>


       </div>


    </div>
    <div>
      <table id="tbl_counseling_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th>วันที่เข้าตรวจ</th>
              <th>โครงการ</th>
              <th>PID</th>
              <th>UID / UIC</th>
              <th>สถานะ</th>
              <th>หมายเหตุ</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
      </table>
    </div>
  </div>
</div>


<script>

$(document).ready(function(){



  $("#btn_search_counseling").click(function(){
     //alert("clinic scheud");
     selectCounselingCaseList();
  }); // btn_search_uid_schedule

    var currentDate = new Date();
    currentDate.setYear(currentDate.getFullYear() + 543);

      $("#sel_counseling_date_beg").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_counseling_date_beg").addClass('filled');
        }
      });
      $("#sel_counseling_date_end").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_counseling_date_end").addClass('filled');
        }
      });

      $('#sel_counseling_date_beg').datepicker("setDate",currentDate );
      $('#sel_counseling_date_end').datepicker("setDate",currentDate );

      $('#sel_counseling_date_beg').change(function(){
        //alert("change ja");
        //$("#sel_counseling_date_end" ).datepicker('setDate', new Date($("#sel_counseling_date_beg" ).val()));
      });

      initInProcessList();

});


function initInProcessList(){


}


function selectCounselingCaseList(){

  var aData = {
            u_mode:"select_in_process_list",
            date_beg:changeToEnDate($('#sel_counseling_date_beg').val()),
            date_end:changeToEnDate($('#sel_counseling_date_end').val()),
            case_to:"CSL"
  };
  save_data_ajax(aData,"w_user/db_case_list.php",selectCounselingCaseListComplete);

}

function selectCounselingCaseListComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
//tbl_uid_schedule_list
      txt_row="";
      if(rtnDataAjax.data_list.length > 0){
        var enroll_date = "";
        var btn_pid = "";
        var datalist = rtnDataAjax.data_list;
          for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            btn_pid = "";
            btn_pid = '<button class="btn btn-info" type="button" onclick="selectVisit(\''+dataObj.uid+'\',\''+dataObj.uic+'\',\''+dataObj.pid+'\',\''+dataObj.proj_id+'\',\''+dataObj.visit_id+'\',\''+dataObj.visit_date+'\', \''+dataObj.group_id+'\')""><i class="fa fa-user"></i> '+dataObj.pid+'</button>';

            txt_row += '<tr class="r_visit_id">';
            txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+' <span class="badge badge-warning">'+dataObj.visit_name+'</span> </td>';
            //txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+'</td>';
            txt_row += ' <td>'+dataObj.proj_name+'</td>';
            txt_row += ' <td>'+btn_pid+'</td>';
            txt_row += ' <td>'+dataObj.uid+' / '+dataObj.uic+'</td>';

            txt_row += ' <td>'+dataObj.status_name+'</td>';
            txt_row += ' <td>'+dataObj.visit_note+'</td>';

            txt_row += '</tr">';
          }//for

      }
      $('.r_visit_id').remove(); // row uic proj summary
      $('#tbl_counseling_list > tbody:last-child').append(txt_row);


  }
}

function selectVisit(uid,uic, pid, projectID, visitID, visitDate, groupID){
   $('#cur_uid').val(uid);
   $('#cur_uic').val(uic);
   $('#cur_pid').val(pid);

   $('#title_uid_id').html($('#cur_uid').val());
   $('#title_uic_id').html($('#cur_uic').val());

   $('#cur_proj_id').val(projectID);

   $("#div_uid_visit").load("w_user/proj_visit.php?proj_id="+projectID, function(){
     selectVisitInfo(visitID, visitDate, groupID);
     setDataChangeProj();

   });
}

</script>
