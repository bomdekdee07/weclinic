<?
include_once("../in_auth.php");

?>

<div class="card" id="div_viewlog_case_list">
  <div class="card-body">
    <div class="card-title">


      <div class="row">
         <div class="col-sm-2">
           <h5><i class="fa fa-eye fa-lg" ></i> View Log</h5>
         </div>

         <div class="col-sm-2">
           <label for="sel_viewlog_date_beg">ตั้งแต่วันที่:</label>
           <input type="text" id="sel_viewlog_date_beg" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="sel_viewlog_date_end">ถึงวันที่:</label>
           <input type="text" id="sel_viewlog_date_end" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-3">
           <label for="txt_viewlog_log_note">ค้นโดย Log Note: (uid, visit id)</label>
           <input type="text" id="txt_viewlog_log_note" class="form-control">
         </div>
         <div class="col-sm-2">
           <label for="txt_viewlog_staff">ค้นโดย Staff (staff id/name)</label>
           <input type="text" id="txt_viewlog_staff" class="form-control">
         </div>
         <div class="col-sm-1">

           <label for="btn_search_viewlog" class="text-light">.</label>
           <button class="btn btn-info form-control" type="button" id="btn_search_viewlog"><i class="fa fa-search" ></i> ค้นหา</button>

         </div>


       </div>


    </div>

    <div>
      <table id="tbl_viewlog_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th>วันที่</th>
              <th>Log Note</th>
              <th>Staff</th>
              <th>Site</th>
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

  $("#btn_search_viewlog").click(function(){
     searchViewLog();
  }); // btn_search_viewlog

    var currentDate = new Date();
    currentDate.setYear(currentDate.getFullYear() + 543);

      $("#sel_viewlog_date_beg").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_viewlog_date_beg").addClass('filled');
        }
      });
      $("#sel_viewlog_date_end").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_viewlog_date_end").addClass('filled');
        }
      });

      $('#sel_viewlog_date_beg').datepicker("setDate",currentDate );
      $('#sel_viewlog_date_end').datepicker("setDate",currentDate );

      $('#sel_viewlog_date_beg').change(function(){
        //alert("change ja");
        //$("#sel_viewlog_date_end" ).datepicker('setDate', new Date($("#sel_viewlog_date_beg" ).val()));
      });



});

function searchViewLog(){

    var aData = {
              u_mode:"select_viewlog",
              txt_search_lognote:$('#txt_viewlog_log_note').val().trim(),
              txt_search_staff:$('#txt_viewlog_staff').val().trim(),
              date_beg:changeToEnDate($('#sel_viewlog_date_beg').val()),
              date_end:changeToEnDate($('#sel_viewlog_date_end').val())
    };

    save_data_ajax(aData,"w_monitor/db_monitor.php",searchViewLogComplete);

}

function searchViewLogComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.u_mode);
  if(flagSave){

    txt_row="";
    if(rtnDataAjax.datalist.length > 0){
      var datalist = rtnDataAjax.datalist;
        for (i = 0; i < datalist.length; i++) {
          var dataObj = datalist[i];

          txt_row += '<tr class="r_viewlog">';
          txt_row += ' <td>'+dataObj.log_date+'</td>';
          txt_row += ' <td>'+dataObj.log_note+'</td>';
          txt_row += ' <td>'+dataObj.staff+'</td>';
          txt_row += ' <td>'+dataObj.site+'</td>';
          txt_row += '</tr">';
        }//for
        $('.r_viewlog').remove(); // row uic proj summary
        $('#tbl_viewlog_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }



  }
}


</script>
