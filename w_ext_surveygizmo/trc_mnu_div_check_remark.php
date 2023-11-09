<?
include_once("../in_auth.php");

?>

    <div class="row">
      <div class="col-sm-3">
        <label for="sel_sgm_remark_opt">การตรวจสอบ Note:</label>
        <select id="sel_sgm_remark_opt" class="form-control" >
          <option value="0" selected>รอตรวจแก้ไข</option>
          <option value="1">ตรวจแล้ว</option>
          <option value="all" >ทั้งหมด</option>

        </select>
      </div>
      <div class="col-sm-3">
        <label for="txt_search_surveygizmo_uic">ค้นโดย UIC:</label>
        <input type="text" id="txt_search_surveygizmo_uic" class="form-control">
      </div>
       <div class="col-sm-2">
         <label for="sel_sgm_remark_date_beg">ตั้งแต่วันที่:</label>
         <input type="text" id="sel_sgm_remark_date_beg" class="form-control" readonly='readonly'>
       </div>
       <div class="col-sm-2">
         <label for="sel_sgm_remark_date_end">ถึงวันที่:</label>
         <input type="text" id="sel_sgm_remark_date_end" class="form-control" readonly='readonly'>
       </div>

       <div class="col-sm-2">

         <label for="btn_search_surveygizmo2" class="text-light">.</label>
         <button class="btn btn-info form-control" type="button" id="btn_search_surveygizmo2"><i class="fa fa-search" ></i> ค้นหา</button>

       </div>


     </div>
    <div>
      <table id="tbl_surveygizmo_list2" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th>Site</th>
              <th>Visit Date</th>
              <th>Submitted</th>
              <th>Checked</th>
              <th>PID</th>
              <th>ACID</th>
              <th>UIC</th>

              <th>Note </th>
              <th>PrEP Intake</th>
              <th>PrEP FU</th>
              <th>Risk Behavior</th>
              <th>Assist</th>

            </tr>
          </thead>
          <tbody>

          </tbody>
      </table>
    </div>




<script>

$(document).ready(function(){

  $("#btn_search_surveygizmo2").click(function(){
     searchSGM_Remark();
  }); // btn_search_surveygizmo2

    var currentDate = new Date();
    currentDate.setYear(currentDate.getFullYear() + 543);

      $("#sel_sgm_remark_date_beg").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_sgm_remark_date_beg").addClass('filled');
        }
      });
      $("#sel_sgm_remark_date_end").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_sgm_remark_date_end").addClass('filled');
        }
      });

      $('#sel_sgm_remark_date_beg').datepicker("setDate",currentDate );
      $('#sel_sgm_remark_date_end').datepicker("setDate",currentDate );

      $('#sel_sgm_remark_date_beg').change(function(){
        //alert("change ja");
        //$("#sel_sgm_remark_date_end" ).datepicker('setDate', new Date($("#sel_sgm_remark_date_beg" ).val()));
      });



});

function searchSGM_Remark(){

    var aData = {
              u_mode:"select_remark_list",
              txt_search:$('#txt_search_surveygizmo_uic').val().trim(),
              sel_opt:$('#sel_sgm_remark_opt').val().trim(),
              date_beg:changeToEnDate($('#sel_sgm_remark_date_beg').val()),
              date_end:changeToEnDate($('#sel_sgm_remark_date_end').val())
    };

    save_data_ajax(aData,"w_ext_surveygizmo/trc_db_surveygizmo.php",searchSGM_RemarkComplete);

}

function searchSGM_RemarkComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave+" / "+aData.u_mode);
if(flagSave){

  txt_row="";
  if(rtnDataAjax.datalist.length > 0){
    var datalist = rtnDataAjax.datalist;
      for (i = 0; i < datalist.length; i++) {
        var dataObj = datalist[i];
        var form1 = ""; //prep_intake
        var form2 = ""; //prep_fu
        var form3 = ""; //risk Behavior
        var form4 = ""; //assist
        var flag_check = 0; // show visit date check
        var id = dataObj.pid+dataObj.acid+dataObj.uic+dataObj.visit_date;

        if(dataObj.f1 != ""){
          form1 = dataObj.f1+' ';
          if(dataObj.fc1 == ""){
            flag_check = 1;
            form1 += '<span class="badge badge-warning '+id+'"><i class="fa fa-exclamation-circle"></i> รอตรวจ</span>';
          }
          else form1 += '<span class="badge badge-success"><i class="fa fa-check"></i> ตรวจแล้ว</span>';
        }
        if(dataObj.f2 != ""){
          form2 = dataObj.f2+' ';
          if(dataObj.fc2 == ""){
            flag_check = 1;
            form2 += '<span class="badge badge-warning '+id+'"><i class="fa fa-exclamation-circle"></i> รอตรวจ</span>';
          }
          else form2 += '<span class="badge badge-success"><i class="fa fa-check"></i> ตรวจแล้ว</span>';
        }
        if(dataObj.f3 != ""){
          form3 = dataObj.f3+' ';
          if(dataObj.fc3 == ""){
            flag_check = 1;
            form3 += '<span class="badge badge-warning '+id+'"><i class="fa fa-exclamation-circle"></i> รอตรวจ</span>';
          }
          else form3 += '<span class="badge badge-success"><i class="fa fa-check"></i> ตรวจแล้ว</span>';
        }
        if(dataObj.f4 != ""){
          form4 = dataObj.f4+' ';
          if(dataObj.fc4 == ""){
            flag_check = 1;
            form4 += '<span class="badge badge-warning '+id+'"><i class="fa fa-exclamation-circle"></i> รอตรวจ</span>';
          }
          else form4 += '<span class="badge badge-success"><i class="fa fa-check"></i> ตรวจแล้ว</span>';
        }

        var btn_check = "";
        var submit_info = ""; //submit by
        var check_info = ""; //check by

        if(dataObj.check_name != ""){
          check_info = "<span class='badge badge-success px-2 py-1'><b>"+dataObj.check_name+"</b><br>";
          check_info+= "<small>("+dataObj.check_date+")</small></span>";
        }
        else{
          check_info = "<span id='txt"+id+"'>[รอตรวจ]</span>";
        //  btn_check = ' <button id="btn_'+id+'" class="btn btn-sm  btn-success" type="button" onclick="submitReviseSG_FormDone(\''+dataObj.uic+'\',\''+dataObj.visit_date+'\')""><i class="fa fa-paper-plane"></i> ตรวจและแก้ไข UIC</button>';
      //    btn_check = ' <span id="b'+id+'"><button id="btn_'+id+'" class="btn btn-sm  btn-success" type="button" onclick="checkReviseSG_FormDone(\''+dataObj.uic+'\',\''+dataObj.r_uic+'\',\''+dataObj.visit_date+'\',\''+dataObj.form_id+'\')""> <i class="fa fa-check"></i> ตรวจการแก้ UIC</button></span>';
          btn_check = ' <button id="btn_'+id+'" class="btn btn-sm  btn-success" type="button" onclick="checkRemarkSG(\''+dataObj.pid+'\',\''+dataObj.acid+'\',\''+dataObj.uic+'\',\''+dataObj.visit_date+'\')""><i class="fa fa-check"></i> ตรวจ Note</button>';

        }

        if(dataObj.submit_name != ""){
          submit_info = "<span class='badge badge-warning px-2 py-1'><b>"+dataObj.submit_name+"</b><br>";
          submit_info+= "<small>("+dataObj.submit_date+")</small></span>";
        }


        txt_row += '<tr class="r_svgm_r">';
        txt_row += ' <td>'+dataObj.site+'</td>';
        txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+btn_check+'</td>';
        txt_row += ' <td>'+check_info+'</td>';
        txt_row += ' <td>'+submit_info+'</td>';
        txt_row += ' <td>'+dataObj.pid+'</td>';
        txt_row += ' <td>'+dataObj.acid+'</td>';
        txt_row += ' <td>'+dataObj.uic+'</td>';

        txt_row += ' <td><b>'+dataObj.remark+'</b></td>';
        txt_row += ' <td>'+form1+'</td>';
        txt_row += ' <td>'+form2+'</td>';
        txt_row += ' <td>'+form3+'</td>';
        txt_row += ' <td>'+form4+'</td>';

        txt_row += '</tr">';

      }//for


  }
  else{
    $.notify("No record found.", "info");
    txt_row += '<tr class="r_svgm_r"><td colspan="6" align="center">ไม่พบข้อมูล</td></tr">';

  }


  $('.r_svgm_r').remove(); // row uic proj summary
  $('#tbl_surveygizmo_list2 > tbody:last-child').append(txt_row);

 }
}

function checkRemarkSG(c_pid, c_acid,c_uic, c_visit_date){
  var aData = {
            u_mode:"check_revise_remark",
            pid:c_pid,
            acid:c_acid,
            uic:c_uic,
            visit_date:c_visit_date
  };

  save_data_ajax(aData,"w_ext_surveygizmo/trc_db_surveygizmo.php",checkRemarkSGComplete);
}

function checkRemarkSGComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.u_mode);
  if(flagSave){
     var id = aData.pid+aData.acid+aData.uic+aData.visit_date;
     check_info = "<span id='txt"+id+"'>[รอตรวจ]</span>";
     $('#btn_'+id).hide();
     $('#txt'+id).html('<span class="badge badge-success"><i class="fa fa-check"></i> ตรวจแล้ว </span>');
  }
}



</script>
