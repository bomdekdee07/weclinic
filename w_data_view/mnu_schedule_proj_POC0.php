<?
include_once("../in_auth.php");

?>

<div class="card div-main-poc" id="div_schedule_poc_list">
  <div class="card-body">
    <div class="card-title">


      <div class="row">
         <div class="col-sm-6">
           <div><h5><i class="fa fa-table fa-lg" ></i> ข้อมูลตารางนัดหมาย Point of Care</h5></div>
           <div><button class="btn btn-info form-control" type="button" id="btn_export_schedule_poc"><i class="fa fa-file-export" ></i> Data Export</button></div>
         </div>
         <div class="col-sm-6">
           <label for="txt_search_schedule_poc">ค้นหา (PID, UID, UIC):</label>
           <input type="text" id="txt_search_schedule_poc" class="form-control form-control-sm" >
         </div>
         <!--
         <div class="col-sm-2">
           <label for="btn_search_schedule_poc" class="text-light">.</label>
          <button class="btn btn-info form-control" type="button" id="btn_search_schedule_poc"><i class="fa fa-search" ></i> ค้นหา</button>
         </div>
       -->
       </div>


    </div>

<!--
    <div>
      <table id="tbl_schedule_poc_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th rowspan='2' >PID</th>
              <th rowspan='2' >UIC</th>
              <th rowspan='2' >UID</th>
              <th rowspan='2' >Enroll</th>
              <th colspan='2' >M0</th>
              <th colspan='2' >M1</th>
              <th colspan='2' >M3</th>
              <th colspan='2' >M6</th>
              <th colspan='2' >M9</th>
              <th colspan='2' >M12</th>
              <th rowspan='2' >Extra</th>
            </tr>
            <tr>

              <td align="center" bgcolor="#EFFFBF">Schedule</td>
              <td align="center" bgcolor="#EEE">Visit</td>

              <td align="center" bgcolor="#EFFFBF">Schedule</td>
              <td align="center" bgcolor="#EEE">Visit</td>

              <td align="center" bgcolor="#EFFFBF">Schedule</td>
              <td align="center" bgcolor="#EEE">Visit</td>

              <td align="center" bgcolor="#EFFFBF">Schedule</td>
              <td align="center" bgcolor="#EEE">Visit</td>

              <td align="center" bgcolor="#EFFFBF">Schedule</td>
              <td align="center" bgcolor="#EEE">Visit</td>

              <td align="center" bgcolor="#EFFFBF">Schedule</td>
              <td align="center" bgcolor="#EEE">Visit</td>

            </tr>
          </thead>
          <tbody>

          </tbody>
      </table>
    </div>
-->


  </div>
</div>



<script>

$(document).ready(function(){


  $("#btn_search_schedule_poc").click(function(){
     selectSchedulePOC();
  }); // btn_search_schedule_poc
  $("#btn_export_schedule_poc").click(function(){
     dataExportSchedulePOC();
  }); // btn_export_schedule_poc



});



function selectSchedulePOC(){
  var aData = {
            u_mode:"select_schedule_poc",
            txt_search:$('#txt_search_schedule_poc').val()
  };
  save_data_ajax(aData,"w_data_view/db_schedule_proj_POC.php",selectSchedulePOCComplete);
}

function selectSchedulePOCComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);

    if(flagSave){


    }
}




function dataExportSchedulePOC(){
    var aData = {
      txt_search:$('#txt_search_schedule_poc').val()
    };
    save_data_ajax(aData,"w_data/xls_uid_schedule_all_visit_list.php",dataExportSchedulePOCComplete);
}

function dataExportSchedulePOCComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.proj_id+"/"+rtnDataAjax.link_xls+"'");
  if(flagSave){
    window.open(rtnDataAjax.link_xls, '_blank');
  }
}





function showMainDivXpress(choice){
  $('.div-main-poc').hide();
  $('#div_'+choice).show();
}

</script>
