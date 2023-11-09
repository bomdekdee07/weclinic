<?
include_once("../in_auth.php");

?>



<div class="row">
  <div class="col-sm-3">

    <label for="sel_sgm_revise_opt">การตรวจแก้ไข ID:</label>
    <select id="sel_sgm_revise_opt" class="form-control" >
      <option value="0" selected>ยังไม่ตรวจ</option>
      <option value="all">ทั้งหมด</option>
    <!--
      <option value="0" selected>ยังไม่ตรวจ</option>
      <option value="1">ตรวจแล้ว</option>
      <option value="2">รอตรวจแก้ไข UIC</option>
    -->

    </select>

  </div>

  <div class="col-sm-3">
    <label for="txt_search_sgm_revise">ค้นโดย PID/ACID/UIC:</label>
    <input type="text" id="txt_search_sgm_revise" class="form-control">
  </div>
   <div class="col-sm-2">
     <label for="sel_sgm_revise_date_beg">ตั้งแต่วันที่:</label>
     <input type="text" id="sel_sgm_revise_date_beg" class="form-control" readonly='readonly'>
   </div>
   <div class="col-sm-2">
     <label for="sel_sgm_revise_date_end">ถึงวันที่:</label>
     <input type="text" id="sel_sgm_revise_date_end" class="form-control" readonly='readonly'>
   </div>

   <div class="col-sm-2">

     <label for="btn_search_sgm_revise" class="text-light">.</label>
     <button class="btn btn-info form-control" type="button" id="btn_search_sgm_revise"><i class="fa fa-search" ></i> ค้นหา</button>

   </div>


 </div>


</div>

<div>
<table id="tbl_surveygizmo_list1" class="table table-bordered table-sm table-striped table-hover">
    <thead>
      <tr>
        <th>Site</th>
        <th>Visit Date</th>
        <th>Checked</th>
        <th>Submitted</th>
        <th>PID</th>
        <th>ACID</th>
        <th>UIC</th>
        <th>Form Name</th>
        <th>Visit Name</th>


      </tr>
    </thead>
    <tbody>

    </tbody>
</table>
</div>

<script>

$(document).ready(function(){

  $("#btn_search_sgm_revise").click(function(){
     searchSGM_Revise();
  }); // btn_search_sgm_revise

    var currentDate = new Date();
    currentDate.setYear(currentDate.getFullYear() + 543);

      $("#sel_sgm_revise_date_beg").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_sgm_revise_date_beg").addClass('filled');
        }
      });
      $("#sel_sgm_revise_date_end").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_sgm_revise_date_end").addClass('filled');
        }
      });

      $('#sel_sgm_revise_date_beg').datepicker("setDate",currentDate );
      $('#sel_sgm_revise_date_end').datepicker("setDate",currentDate );

      $('#sel_sgm_revise_date_beg').change(function(){
        //alert("change ja");
        //$("#sel_sgm_revise_date_end" ).datepicker('setDate', new Date($("#sel_sgm_revise_date_beg" ).val()));
      });



});

function searchSGM_Revise(){
    var aData = {
              u_mode:"select_revise_list",
              txt_search:$('#txt_search_sgm_revise').val().trim(),
              sel_opt:$('#sel_sgm_revise_opt').val().trim(),
              date_beg:changeToEnDate($('#sel_sgm_revise_date_beg').val()),
              date_end:changeToEnDate($('#sel_sgm_revise_date_end').val())
    };

    save_data_ajax(aData,"w_ext_surveygizmo/trc_db_surveygizmo.php",searchSGM_ReviseComplete);

}

function searchSGM_ReviseComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.u_mode);
    if(flagSave){

      txt_row="";
      if(rtnDataAjax.datalist.length > 0){
        var datalist = rtnDataAjax.datalist;
          for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];

            var submit_info = ""; //submit by
            var check_info = ""; //check by
            var flag_check = 0; // check
            var btn_check = ""; // btn check

            var id = dataObj.pid+dataObj.acid+dataObj.uic+dataObj.visit_date+dataObj.form_id;

            if(dataObj.check_name != ""){
              check_info = "<span class='badge badge-success px-2 py-1'><b>"+dataObj.check_name+"</b><br>";
              check_info+= "<small>("+dataObj.check_date+")</small></span>";
            }
            else{
              check_info = "<span id='txt"+id+"'>[รอตรวจ]</span>";
            //  btn_check = ' <button id="btn_'+id+'" class="btn btn-sm  btn-success" type="button" onclick="submitReviseSG_FormDone(\''+dataObj.uic+'\',\''+dataObj.visit_date+'\')""><i class="fa fa-paper-plane"></i> ตรวจและแก้ไข UIC</button>';
              btn_check = ' <span id="b'+id+'"><button id="btn_'+id+'" class="btn btn-sm  btn-success" type="button" onclick="checkReviseSG_FormDone(\''+dataObj.pid+'\',\''+dataObj.acid+'\',\''+dataObj.uic+'\',\''+dataObj.r_pid+'\',\''+dataObj.r_acid+'\',\''+dataObj.r_uic+'\',\''+dataObj.visit_date+'\',\''+dataObj.form_id+'\')""> <i class="fa fa-check"></i> ตรวจการแก้ ID</button></span>';

            }

            if(dataObj.submit_name != ""){
              submit_info = "<span class='badge badge-warning px-2 py-1'><b>"+dataObj.submit_name+"</b><br>";
              submit_info+= "<small>("+dataObj.submit_date+")</small></span>";
            }

            var pid=dataObj.pid; var acid=dataObj.acid; var uic=dataObj.uic;
            if(dataObj.r_pid != ""){
              pid += "<br>แก้เป็น <b>"+dataObj.r_pid+"</b>";
            }
            if(dataObj.r_acid != ""){
              acid += "<br>แก้เป็น <b>"+dataObj.r_acid+"</b>";
            }
            if(dataObj.r_uic != ""){
              uic += "<br>แก้เป็น <b>"+dataObj.r_uic+"</b>";
            }

            txt_row += '<tr class="r_svgm_u">';
            txt_row += ' <td>'+dataObj.site+'</td>';
            txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+btn_check+'</td>';
            txt_row += ' <td>'+check_info+'</td>';
            txt_row += ' <td>'+submit_info+'</td>';
            txt_row += ' <td>'+pid+'</td>';
            txt_row += ' <td>'+acid+'</td>';
            txt_row += ' <td>'+uic+'</td>';
            txt_row += ' <td>'+dataObj.form+'</td>';
            txt_row += ' <td>'+dataObj.visit_name+'</td>';


            txt_row += '</tr">';

          }//for


      }
      else{
        $.notify("No record found.", "info");
        txt_row += '<tr class="r_svgm_u"><td colspan="7" align="center">ไม่พบข้อมูล</td></tr">';


      }

      $('.r_svgm_u').remove(); // row uic proj summary
      $('#tbl_surveygizmo_list1 > tbody:last-child').append(txt_row);


    }
}


function checkReviseSG_FormDone(c_pid, c_acid, c_uic, rv_pid, rv_acid, rv_uic, c_visit_date, c_form_id){
    var id = c_pid+c_acid+c_uic+c_visit_date;

    rv_pid = (rv_pid != c_pid)?rv_pid:"";
    rv_acid = (rv_acid != c_acid)?rv_acid:"";
    rv_uic = (rv_uic != c_uic)?rv_uic:"";

    var aData = {
              u_mode:"check_revise_id",
              pid:c_pid,
              acid:c_acid,
              uic:c_uic,
              r_pid:rv_pid,
              r_acid:rv_acid,
              r_uic:rv_uic,
              form_id:c_form_id,
              visit_date:c_visit_date
    };

    save_data_ajax(aData,"w_ext_surveygizmo/trc_db_surveygizmo.php",checkReviseSG_FormDoneComplete);

}

function checkReviseSG_FormDoneComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.u_mode);
  if(flagSave){
     var id = aData.pid+aData.acid+aData.uic+aData.visit_date+aData.form_id;
     $('#btn_'+id).hide();
     $('#txt'+id).html('<span class="badge badge-success"><i class="fa fa-check"></i> ตรวจแล้ว</span>');
  }
}


</script>
