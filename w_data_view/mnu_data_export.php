<?
include_once("../in_auth.php");

?>

<div class="card" id="div_export_case_list">
  <div class="card-body">
    <div class="card-title">


      <div class="row">
         <div class="col-sm-2">
           <h5><i class="fa fa-file-export fa-lg" ></i> Data Export</h5>
         </div>

         <div class="col-sm-2">
           <label for="sel_export_date_beg">ตั้งแต่วันที่:</label>
           <input type="text" id="sel_export_date_beg" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="sel_export_date_end">ถึงวันที่:</label>
           <input type="text" id="sel_export_date_end" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-4">
           <label for="txt_export_uid">UID/UIC:</label>
           <input type="text" id="txt_export_uid" class="form-control">
         </div>
         <div class="col-sm-2">
        <!--
           <label for="btn_search_export" class="text-light">.</label>
           <button class="btn btn-info form-control" type="button" id="btn_search_export"><i class="fa fa-search" ></i> ค้นหา</button>
        -->
         </div>


       </div>


    </div>

    <div>
      <table id="tbl_export_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th>Export</th>
              <th>โครงการ</th>
              <th>หมายเหตุ</th>
            </tr>
          </thead>
          <tbody>

            <tr>
              <td><button class="btn btn-info  btn-export form-control" type="button" data-id="iclinic"><i class="fa fa-file-export" ></i> Export CSV</button></td>
              <td>iClinic </td>
              <td>ข้อมูลมีตั้งแต่ 30/01/2558 (2015) </td>
            </tr>

            <tr>
              <td><button class="btn btn-info  btn-export form-control" type="button" data-id="poc"><i class="fa fa-file-export" ></i> Export CSV</button></td>
              <td>Point of Care </td>
              <td>ข้อมูลมีตั้งแต่ 07/08/2562 (2019) เริ่มใช้งานจริงกับ CBO ครั้งแรกเมื่อ 08/11/2562 (RSAT BKK)</td>
            </tr>
          </tbody>
      </table>
      <a href='' id='aDLXLS' style='display:none'>Download Now</a>
    </div>
  </div>
</div>


<script>

$(document).ready(function(){

  $(".btn-export").click(function(){
     //alert("clinic scheud");
     var proj_id = $(this).data("id");
     //alert("clinic "+proj_id);
     exportExcel(proj_id);
  }); // btn_search_export

    var currentDate = new Date();
    currentDate.setYear(currentDate.getFullYear() + 543);

      $("#sel_export_date_beg").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_export_date_beg").addClass('filled');
        }
      });
      $("#sel_export_date_end").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_export_date_end").addClass('filled');
        }
      });

      $('#sel_export_date_beg').datepicker("setDate",currentDate );
      $('#sel_export_date_end').datepicker("setDate",currentDate );

      $('#sel_export_date_beg').change(function(){
        //alert("change ja");
        //$("#sel_export_date_end" ).datepicker('setDate', new Date($("#sel_export_date_beg" ).val()));
      });

      initDataExport();

});


function initDataExport(){



}


function exportExcel(projID){
    var aData = {
              proj_id:projID,
              date_beg:changeToEnDate($('#sel_export_date_beg').val()),
              date_end:changeToEnDate($('#sel_export_date_end').val())
    };
    save_data_ajax(aData,"w_data/xls_project_"+projID+".php",exportExcelComplete);
}

function exportExcelComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.proj_id+"/"+rtnDataAjax.link_xls+"'");
  if(flagSave!=false){
    window.open(rtnDataAjax.link_xls, '_blank');
  }
  $("#aDLXLS").attr('href',rtnDataAjax.link_xls);
  $("#aDLXLS").show();
}


</script>
