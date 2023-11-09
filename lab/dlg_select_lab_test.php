<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}
include_once("inc_auth.php"); // set permission view, update, delete

?>


<script>

var s_clinic_id = "<? echo $staff_clinic_id; ?>";
ResetTimeOutTimer();


var tbl_choice = "lab_test";
var choice_title = "Lab Test";
/*
var selObj = [];
selObj["lab_test"]=[];
selObj["lab_test"]["title"] = "Lab Test";
selObj["lab_test"]["tbl_head"] = [{col_name:"Name", col_width:""},{col_name:"Test Menu", col_width:"40%"} ]
*/
</script>


<div id='div_dlg_select_list' class='div-setting-menu my-0'>
  <div class="row mt-0">
    <div class="col-sm-9">
      <input type="text" id="txt_search_select" class="form-control" placeholder="พิมพ์คำค้นหา">
    </div>
     <div class="col-sm-3">
      <button class="btn btn-info form-control" type="button" id="btn_search_select"><i class="fa fa-search" ></i> ค้นหา</button>
     </div>
   </div>
   <div class="mt-2">
      <table id="tbl_dlg_select_list" class="table table-bordered table-sm table-striped table-hover">
         <thead>

           <tr>
             <th>Mode</th>
             <th>Name</th>
             <th>Test Menu</th>
           </tr>

         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_dlg_select_list -->


<script>

$('#select_detail_title').html(choice_title);
var u_mode_select = "";
var parent_id_param = ""; // lab_group_id

$(document).ready(function(){

  $("#btn_search_select").click(function(){
     searchData_DlgSelect();
  }); // btn_search_select

  $("#txt_search_select").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_DlgSelect();
    }
  });


});

function searchData_DlgSelect(){
  var aData = {
      u_mode:"select_list",
      choice: tbl_choice,
      parent_id:cur_component_act.data("parent_id"),
      txt_search:$('#txt_search_select').val()
  };
  save_data_ajax(aData,"lab/db_lab_select.php",searchDataDlgSelect_Complete);
}

function searchDataDlgSelect_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            var arr_obj = [dataObj.name,dataObj.group_name];
            txt_row += addRowData_DlgSelect(
             dataObj.id, arr_obj
            );

        }//for

        $('.r_sel').remove(); // row select list
        $('#tbl_dlg_select_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
      $('.r_sel').remove(); // row pid list
      txt_row += '<tr class="r_zero r_sel"><td colspan="3" align="center">ไม่พบข้อมูล</td></tr">';
      $('#tbl_dlg_select_list > tbody:last-child').append(txt_row);
    }
  }
}



  function getDlgSelectData(id){
      //setSelectData(cur_component_act, id, name);
      setDataSelectToComponent(id, $('#r_sel'+id).find('td:nth-child(2)').text());
  }


</script>
