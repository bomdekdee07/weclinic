<?
include_once("../in_auth.php");

?>

<style>
.tbl_data_export th, td {
  padding: 15px;
  text-align: left;
}
</style>

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

       </div>


    </div>


    <div>

      <div>เลือกกลุ่มที่ต้องการจะ Data Export</div>

      <table id="tbl_group_poc_list" class="table table-bordered table-sm table-striped table-hover tbl_data_export">
          <thead>
            <tr>
              <td>
                <div class="form-group form-check">
                  <label class="form-check-label">
                    <input id="poc_export_chkall" class="form-check-input" type="checkbox">
                    <b>กลุ่ม / Group</b>
                  </label>
                </div>
              </td>
              <td><b><i class="fa fa-file-export"></i>  Data Export</b></td>
            </tr>

          </thead>
          <tbody>
            <tr>
              <td >
                <div class="form-group form-check">
                  <label class="form-check-label">
                    <input class="form-check-input chk_poc_group_export" type="checkbox" data-id="001">
                    <b>กลุ่มที่ 1</b>: เริ่มทาน PrEP
                  </label>
                </div>
              </td>
              <td>
                <button class="btn btn-primary btn_poc_group_export" type="button" data-id="001"> <i class="fa fa-file-export"></i> Export กลุ่ม 1</button>
              </td>
            </tr>
            <tr>
              <td>
                <div class="form-group form-check">
                  <label class="form-check-label">
                    <input class="form-check-input chk_poc_group_export" type="checkbox" data-id="002">
                    <b>กลุ่มที่ 2</b>: ทาน PrEP ต่อเนื่อง
                  </label>
                </div>
              </td>
              <td>
                <button class="btn btn-primary btn_poc_group_export" type="button" data-id="002"> <i class="fa fa-file-export"></i> Export กลุ่ม 2</button>
              </td>
            </tr>
            <tr>
              <td>
                <div class="form-group form-check">
                  <label class="form-check-label">
                    <input class="form-check-input chk_poc_group_export" type="checkbox" data-id="003">
                    <b>กลุ่มที่ 3</b>: ไม่ทาน PrEP
                  </label>
                </div>
              </td>
              <td>
                <button class="btn btn-primary btn_poc_group_export" type="button" data-id="003"> <i class="fa fa-file-export"></i> Export กลุ่ม 3</button>
              </td>
            </tr>
            <tr>
              <td>
                <div class="form-group form-check">
                  <label class="form-check-label">
                    <input class="form-check-input chk_poc_group_export" type="checkbox" data-id="004">
                    <b>กลุ่มที่ 4</b>: ผลเลือดบวก
                  </label>
                </div>
              </td>
              <td>
                <button class="btn btn-primary btn_poc_group_export" type="button" data-id="004"> <i class="fa fa-file-export"></i> Export กลุ่ม 4</button>
              </td>
            </tr>
          </tbody>
      </table>
      <div>

        <button id="btn_poc_export_selected" class="btn btn-primary" type="button" > <i class="fa fa-file-export"></i> Data Export ที่เลือกไว้</button>
      </div>
    </div>



  </div>
</div>



<script>

$(document).ready(function(){


  $("#btn_export_schedule_poc").click(function(){
     dataExportSchedulePOC();
  }); // btn_export_schedule_poc

  $("#btn_poc_export_selected").click(function(){
    dataExportSelectedGroup();
  }); // btn_export_schedule_poc

  $("#poc_export_chkall").click(function(){
      if($(this).prop("checked") == true){
        $(".chk_poc_group_export").prop('checked', true);
      }
      else if($(this).prop("checked") == false){
        $(".chk_poc_group_export").prop('checked', false);
      }
  }); // btn_export_schedule_poc

  $(".btn_poc_group_export").click(function(){

      dataExportInGroup($(this).data("id"));
  }); // btn_export_schedule_poc



});

function dataExportInGroup(groupID){
  var lst_data_obj = [];
  lst_data_obj.push(groupID);
  var aData = {
    u_mode: "export_sel_group",
    lst_data:lst_data_obj
  };
  save_data_ajax(aData,"w_data/xls_uid_schedule_select_group.php",dataExportSelectedGroupComplete);

}

function dataExportSelectedGroup(){
  var lst_data_obj = [];
  $(".chk_poc_group_export:checked").each(function(ix,objx){

  //alert("Checkbox is checked. "+$(objx).data("id"));
     lst_data_obj.push($(objx).data("id"));
  });

  var aData = {
    u_mode: "export_sel_group",
    lst_data:lst_data_obj
  };
  save_data_ajax(aData,"w_data/xls_uid_schedule_select_group.php",dataExportSelectedGroupComplete);

}
function dataExportSelectedGroupComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.proj_id+"/"+rtnDataAjax.link_xls+"'");
  if(flagSave){
    window.open(rtnDataAjax.link_xls, '_blank');
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


</script>
