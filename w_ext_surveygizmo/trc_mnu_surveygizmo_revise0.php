<?
include_once("../in_auth.php");

?>

<div class="card" id="div_surveygizmo_case_list">
  <div class="card-body">
    <div class="card-title">
      <div class="row">
        <div class="col-sm-6">
          <h5><i class="fa fa-eye fa-lg" ></i> ตรวจฟอร์มทำจาก Survey Gizmo</h5>
        </div>
        <div class="col-sm-6">
          <small> ตัวย่อ Site: <br>
          <table  class="table table-bordered table-sm table-striped table-hover">
                <tr>
                  <td>
                    TRC: Anonymous Clinic <br>
                  </td>
                  <td>
                    ATS: ATS <br>
                  </td>
                  <td>
                    TGB: Tangerine <br>
                  </td>

                </tr>
          </table>
          </small>
        </div>
      </div>


      <div class="row">
        <div class="col-sm-3">

          <label for="sel_surveygizmo_opt">การตรวจสอบ:</label>
          <select id="sel_surveygizmo_opt" class="form-control" >
            <!--
            <option value="0" selected>ยังไม่ตรวจ</option>
            <option value="1">ตรวจแล้ว</option>
            <option value="2">รอตรวจแก้ไข UIC</option>
            -->
            <option value="all">ทั้งหมด</option>
          </select>

        </div>

        <div class="col-sm-3">
          <label for="txt_search_surveygizmo">ค้นโดย UIC:</label>
          <input type="text" id="txt_search_surveygizmo" class="form-control">
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

           <label for="btn_search_surveygizmo" class="text-light">.</label>
           <button class="btn btn-info form-control" type="button" id="btn_search_surveygizmo"><i class="fa fa-search" ></i> ค้นหา</button>

         </div>


       </div>


    </div>

    <div>
      <table id="tbl_surveygizmo_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th>Site</th>
              <th>Visit Date</th>
              <th>PID</th>
              <th>ACID</th>
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
    </div>
  </div>
</div>


<script>

$(document).ready(function(){

  $("#btn_search_surveygizmo").click(function(){
     searchsurveygizmo();
  }); // btn_search_surveygizmo

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
              txt_search:$('#txt_search_surveygizmo').val().trim(),
              sel_opt:$('#sel_surveygizmo_opt').val().trim(),
              date_beg:changeToEnDate($('#sel_surveygizmo_date_beg').val()),
              date_end:changeToEnDate($('#sel_surveygizmo_date_end').val())
    };

    save_data_ajax(aData,"w_ext_surveygizmo/trc_db_surveygizmo.php",searchsurveygizmoComplete);

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
          var id = dataObj.pid+dataObj.acid+dataObj.uic+dataObj.visit_date; // row id

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

          var btn_visit_check = '';
          var pid = dataObj.pid;
          var acid = dataObj.acid;
          var uic = dataObj.uic;

          var r_id = dataObj.r_pid+dataObj.r_acid+dataObj.r_uic; // revise id
          if(r_id != ""){ // there is revised id
            if(dataObj.r_pid != ""){
              pid += ' <br>แก้เป็น <span class="text-primary" > <b>'+dataObj.r_pid+'</b></span>';
              pid = '<span id="txt_pid'+id+'">'+pid+'</span>';
            }
            if(dataObj.r_acid != ""){
              acid += ' <br>แก้เป็น <span class="text-primary" > <b>'+dataObj.r_acid+'</b></span>';
              acid = '<span id="txt_acid'+id+'">'+acid+'</span>';
            }
            if(dataObj.r_uic != ""){
              uic += ' <br>แก้เป็น <span class="text-primary" > <b>'+dataObj.r_uic+'</b></span>';
              uic = '<span id="txt_uic'+id+'">'+uic+'</span>';
            }

            btn_visit_check += ' <button id="btn_'+id+'" data-r_pid="'+dataObj.r_pid+'" data-r_acid="'+dataObj.r_acid+'" data-r_uic="'+dataObj.r_uic+'" class="btn btn-sm  btn-primary" type="button" onclick="checkReviseSG_FormDone(\''+dataObj.pid+'\',\''+dataObj.acid+'\',\''+dataObj.uic+'\',\''+dataObj.visit_date+'\')""><i class="fa fa-check"></i> ตรวจการแก้ไข ID</button>';

          }


          txt_row += '<tr class="r_svgm">';
          txt_row += ' <td>'+dataObj.site+'</td>';
          txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+btn_visit_check+'</td>';
          txt_row += ' <td>'+pid+'</td>';
          txt_row += ' <td>'+acid+'</td>';
          txt_row += ' <td>'+uic+'</td>';

          txt_row += ' <td>'+form1+'</td>';
          txt_row += ' <td>'+form2+'</td>';
          txt_row += ' <td>'+form3+'</td>';
          txt_row += ' <td>'+form4+'</td>';
          txt_row += '</tr">';
        }//for


    }
    else{
      $.notify("No record found.", "info");
      txt_row += '<tr class="r_svgm"><td colspan="7" align="center">ไม่พบข้อมูล</td></tr">';


    }
    $('.r_svgm').remove(); // row uic proj summary
    $('#tbl_surveygizmo_list > tbody:last-child').append(txt_row);


  }
}

function checkSG_FormDone(c_uic, c_visit_date){
  var aData = {
            u_mode:"check_formdone",
            uic:c_uic,
            visit_date:c_visit_date
  };

  save_data_ajax(aData,"w_ext_surveygizmo/trc_db_surveygizmo.php",checkSG_FormDoneComplete);
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

function checkReviseSG_FormDone(c_pid, c_acid, c_uic, c_visit_date){
    var id = c_pid+c_acid+c_uic+c_visit_date;
    var revise_pid = $("#btn_"+id).data("r_pid");
    var revise_acid = $("#btn_"+id).data("r_acid");
    var revise_uic = $("#btn_"+id).data("r_uic");

    revise_pid = (revise_pid != c_pid)?revise_pid:"";
    revise_acid = (revise_acid != c_acid)?revise_acid:"";
    revise_uic = (revise_uic != c_uic)?revise_uic:"";

    var aData = {
              u_mode:"check_revise_id",
              pid:c_pid,
              acid:c_acid,
              uic:c_uic,
              r_pid:revise_pid,
              r_acid:revise_acid,
              r_uic:revise_uic,
              visit_date:c_visit_date
    };

    save_data_ajax(aData,"w_ext_surveygizmo/trc_db_surveygizmo.php",checkReviseSG_FormDoneComplete);

}

function checkReviseSG_FormDoneComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.u_mode);
  if(flagSave){
     var id = aData.pid+aData.acid+aData.uic+aData.visit_date;
     if(aData.r_pid != "") $('#txt_pid'+id).html(aData.r_pid);
     if(aData.r_acid != "") $('#txt_acid'+id).html(aData.r_acid);
     if(aData.r_uic != "") $('#txt_uic'+id).html(aData.r_uic);

     $('#btn_'+id).hide();
/*
     $('.'+aData.uic+aData.visit_date).removeClass('badge-warning');
     $('.'+aData.uic+aData.visit_date).addClass('badge-success');
     $('.'+aData.uic+aData.visit_date).html('<i class="fa fa-check"></i> ตรวจแล้ว');
*/
  }
}


</script>
