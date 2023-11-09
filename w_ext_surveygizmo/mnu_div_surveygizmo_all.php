<?
include_once("../in_auth.php");

?>

<div class="card" id="div_surveygizmo_case_list">
  <div class="card-body">
    <div class="card-title">
      <div class="row">
        <div class="col-sm-3">
          <label for="sel_surveygizmo_opt">การตรวจสอบ:</label>
          <select id="sel_surveygizmo_opt" class="form-control" >
            <option value="all" selected>ทั้งหมด</option>
            <option value="0">ยังไม่ตรวจ</option>
            <option value="1">ตรวจแล้ว</option>
            <option value="2">รอตรวจแก้ไข UIC</option>

          </select>
        </div>
        <div class="col-sm-3">
          <label for="txt_search_surveygizmo_uic">ค้นโดย UIC:</label>
          <input type="text" id="txt_search_surveygizmo_uic" class="form-control">
        </div>
         <div class="col-sm-2">
           <label for="sel_surveygizmo_date_beg">ตั้งแต่วันที่:</label>
           <input type="text" id="sel_surveygizmo_date_beg" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="sel_surveygizmo_date_end">ถึงวันที่:</label>
           <input type="text" id="sel_surveygizmo_date_end" class="form-control" readonly='readonly'>
         </div>

         <div class="col-sm-2">

           <label for="btn_search_sgm3" class="text-light">.</label>
           <button class="btn btn-info form-control" type="button" id="btn_search_sgm3"><i class="fa fa-search" ></i> ค้นหา</button>

         </div>


       </div>


    </div>

    <div>
      <table id="tbl_surveygizmo_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th>Site</th>
              <th>Visit Date</th>
              <th>UIC</th>
              <th>PrEP Intake</th>
              <th>PrEP Follow Up</th>
              <th>Risk Behavior</th>
              <th>Assist</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
      </table>
      <br>
      <button class="btn btn-info form-control" type="button" id="btn_viewmore_sgm3" style="display:none;"><i class="fa fa-plus" ></i> ดูเพิ่ม (View More)</button>

    </div>
  </div>
</div>


<script>
  var sgm3_offset = 0; // data offset

$(document).ready(function(){

  $("#btn_search_sgm3").click(function(){
     sgm3_offset = 0; // data offset
     searchsurveygizmo();
  }); // btn_search_sgm3

  $("#btn_viewmore_sgm3").click(function(){
     searchsurveygizmo();
  }); // btn_search_sgm3

    var currentDate = new Date();
    currentDate.setYear(currentDate.getFullYear() + 543);

      $("#sel_surveygizmo_date_beg").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_surveygizmo_date_beg").addClass('filled');
        }
      });
      $("#sel_surveygizmo_date_end").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onSelect: function(date) {
          $("#sel_surveygizmo_date_end").addClass('filled');
        }
      });

      $('#sel_surveygizmo_date_beg').datepicker("setDate",currentDate );
      $('#sel_surveygizmo_date_end').datepicker("setDate",currentDate );

      $('#sel_surveygizmo_date_beg').change(function(){
        //alert("change ja");
        //$("#sel_surveygizmo_date_end" ).datepicker('setDate', new Date($("#sel_surveygizmo_date_beg" ).val()));
      });



});

function searchsurveygizmo(){

    var aData = {
              u_mode:"select_list",
              offset:sgm3_offset,
              txt_search:$('#txt_search_surveygizmo_uic').val().trim(),
              sel_opt:$('#sel_surveygizmo_opt').val().trim(),
              date_beg:changeToEnDate($('#sel_surveygizmo_date_beg').val()),
              date_end:changeToEnDate($('#sel_surveygizmo_date_end').val())
    };

    save_data_ajax(aData,"w_ext_surveygizmo/db_surveygizmo.php",searchsurveygizmoComplete);

}

function searchsurveygizmoComplete(flagSave, rtnDataAjax, aData){
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

          if(dataObj.f1 != ""){
            form1 = dataObj.f1+' ';
            if(dataObj.fc1 == ""){
              flag_check = 1;
              form1 += '<span class="badge badge-warning '+dataObj.uic+dataObj.visit_date+'"><i class="fa fa-exclamation-circle"></i> รอตรวจ</span>';
            }
            else form1 += '<span class="badge badge-success"><i class="fa fa-check"></i> ตรวจแล้ว</span>';
          }
          if(dataObj.f2 != ""){
            form2 = dataObj.f2+' ';
            if(dataObj.fc2 == ""){
              flag_check = 1;
              form2 += '<span class="badge badge-warning '+dataObj.uic+dataObj.visit_date+'"><i class="fa fa-exclamation-circle"></i> รอตรวจ</span>';
            }
            else form2 += '<span class="badge badge-success"><i class="fa fa-check"></i> ตรวจแล้ว</span>';
          }
          if(dataObj.f3 != ""){
            form3 = dataObj.f3+' ';
            if(dataObj.fc3 == ""){
              flag_check = 1;
              form3 += '<span class="badge badge-warning '+dataObj.uic+dataObj.visit_date+'"><i class="fa fa-exclamation-circle"></i> รอตรวจ</span>';
            }
            else form3 += '<span class="badge badge-success"><i class="fa fa-check"></i> ตรวจแล้ว</span>';
          }
          if(dataObj.f4 != ""){
            form4 = dataObj.f4+' ';
            if(dataObj.fc4 == ""){
              flag_check = 1;
              form4 += '<span class="badge badge-warning '+dataObj.uic+dataObj.visit_date+'"><i class="fa fa-exclamation-circle"></i> รอตรวจ</span>';
            }
            else form4 += '<span class="badge badge-success"><i class="fa fa-check"></i> ตรวจแล้ว</span>';
          }

          var btn_visit_check = '';

          var uid = "";
          var uic = dataObj.uic;
          if(dataObj.uid != ""){
            uid = ' <span class="badge badge-info" >'+dataObj.uid+'</span>';
            if(dataObj.r_uic != "")
            btn_visit_check = ' <span class="badge badge-secondary" > แก้ไขมาจาก '+dataObj.r_uic+'</span>';
          }
          else{ // invalid uid
            if(dataObj.r_uic != ""){ // revise uic process
              uic = '<span id="u'+dataObj.uic+dataObj.visit_date+'">'+dataObj.uic+'</span>';
              uid = ' <span class="text-primary r'+dataObj.uic+dataObj.visit_date+'" > แก้ไขเป็น <b>'+dataObj.r_uic+'</b></span>';

            }
            else{
              btn_visit_check = ' <span class="badge badge-danger" >UIC ผิด <br>รอการแก้ไขจาก CBO</span>';
            }

          }

          txt_row += '<tr class="r_svgm">';
          txt_row += ' <td>'+dataObj.site+'</td>';
          txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+btn_visit_check+'</td>';
          txt_row += ' <td>'+uic+uid+'</td>';

          txt_row += ' <td>'+form1+'</td>';
          txt_row += ' <td>'+form2+'</td>';
          txt_row += ' <td>'+form3+'</td>';
          txt_row += ' <td>'+form4+'</td>';
          txt_row += '</tr">';
        }//for

       $('#btn_viewmore_sgm3').show();
    }
    else{

      $('#btn_viewmore_sgm3').hide();
      if(sgm3_offset == 0){
        $.notify("No record found.", "info");
        txt_row += '<tr class="r_svgm"><td colspan="7" align="center">ไม่พบข้อมูล</td></tr">';

      }
      else{
        $.notify("No more record found.", "info");
      }

    }

    if(sgm3_offset == 0) $('.r_svgm').remove(); // row uic proj summary

    $('#tbl_surveygizmo_list > tbody:last-child').append(txt_row);

    sgm3_offset += 1000; // data offset
  }
}

function checkSG_FormDone(c_uic, c_visit_date){
  var aData = {
            u_mode:"check_formdone",
            uic:c_uic,
            visit_date:c_visit_date
  };

  save_data_ajax(aData,"w_ext_surveygizmo/db_surveygizmo.php",checkSG_FormDoneComplete);
}

function checkSG_FormDoneComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.u_mode);
  if(flagSave){
     $('#btn_'+aData.uic+aData.visit_date).hide();
     $('.'+aData.uic+aData.visit_date).removeClass('badge-warning');
     $('.'+aData.uic+aData.visit_date).addClass('badge-success');
     $('.'+aData.uic+aData.visit_date).html('<i class="fa fa-check"></i> ตรวจแล้ว');
  }
}

function checkReviseSG_FormDone(c_uic, revise_uic, c_visit_date){

    var aData = {
              u_mode:"check_revise_uic",
              uic:c_uic,
              r_uic:revise_uic,
              visit_date:c_visit_date
    };

    save_data_ajax(aData,"w_ext_surveygizmo/db_surveygizmo.php",checkReviseSG_FormDoneComplete);

}

function checkReviseSG_FormDoneComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.u_mode);
  if(flagSave){
     $('#btn_'+aData.uic+aData.visit_date).hide();
     $('#u'+aData.uic+aData.visit_date).html(aData.r_uic);
     $('#b'+aData.uic+aData.visit_date).html(" แก้ไขจาก ["+aData.uic+"]");
     $('.r'+aData.uic+aData.visit_date).hide();



     $('.'+aData.uic+aData.visit_date).removeClass('badge-warning');
     $('.'+aData.uic+aData.visit_date).addClass('badge-success');
     $('.'+aData.uic+aData.visit_date).html('<i class="fa fa-check"></i> ตรวจแล้ว');

  }
}


</script>
