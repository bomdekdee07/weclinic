<?
//include_once("../in_auth.php");

?>

<div class="card" id="div_surveygizmo_case_list">
  <div class="card-body">
    <div class="card-title">


      <div class="row">
        <div class="col-sm-6">
          <h5><i class="fa fa-eye fa-lg" ></i> ตรวจฟอร์มที่เคสทำจาก Survey Gizmo </h5>

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
            <option value="0" selected>ยังไม่ตรวจ</option>
          <!--  <option value="1">ตรวจแล้ว</option> -->
            <option value="all">ทั้งหมด</option>
          </select>
        </div>
        <div class="col-sm-3">
          <label for="txt_search_surveygizmo">ค้นโดย PID/ACID/UIC:</label>
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
          if(flag_check == 1){
            btn_visit_check += ' <button id="btn_'+id+'" class="btn btn-sm  btn-primary" type="button" onclick="checkSG_FormDone(\''+dataObj.pid+'\',\''+dataObj.acid+'\',\''+dataObj.uic+'\',\''+dataObj.visit_date+'\')"">ตรวจและแก้ไข</button>';
          }

          var pid = dataObj.pid;
          var acid = dataObj.acid;
          var uic = dataObj.uic;

          var r_id = dataObj.r_pid+dataObj.r_acid+dataObj.r_uic; // revise id
          if(r_id != ""){ // there is revised id
            if(dataObj.r_pid != ""){
              pid += ' <br><small>แก้เป็น</small> <span class="badge badge-info" > '+dataObj.r_pid+'</span>';
            }
            if(dataObj.r_acid != ""){
              acid += ' <br><small>แก้เป็น</small>  <span class="badge badge-info" >  '+dataObj.r_acid+'</span>';
            }
            if(dataObj.r_uic != ""){
              uic += ' <br><small>แก้เป็น</small>  <span class="badge badge-info" >  '+dataObj.r_uic+'</span>';
            }
          }
          else{ // no revised id
            var txt_disable = "";
            if(flag_check == 0){ // disable txtbox if they are checked.
              txt_disable = ' disabled ';
            }
            pid = ' <input type="text" id="pid'+id+'" value="'+dataObj.pid+'" data-odata="'+dataObj.pid+'"  maxlength="20" size="10" placeholder="PID" '+txt_disable+'>';
            acid = ' <input type="text" id="acid'+id+'" value="'+dataObj.acid+'" data-odata="'+dataObj.acid+'"  maxlength="20" size="10" placeholder="ACID" '+txt_disable+'>';
            uic = ' <input type="text" id="uic'+id+'" value="'+dataObj.uic+'" data-odata="'+dataObj.uic+'" maxlength="20" size="10" placeholder="UIC" '+txt_disable+'>';


        }


          var frm_class = "";
          var form_remark = dataObj.remark;
          if(form_remark == ""){
            form_remark = "ADD Note";
            frm_class = "text-secondary";
          }

          btn_visit_check+= "<br><span id='frm"+id+"' class='"+frm_class+"' frm_class data-id='"+id+"' data-odata='"+dataObj.remark+"' onclick='editRemark(\""+id+"\");'><small>"+form_remark+"</small></span>"
          btn_visit_check+= "<div id='divfrm"+id+"' style='display:none;'>"
          btn_visit_check+= "<table><tr>";
          btn_visit_check+= "<td colspan=2>";
          btn_visit_check+= '<textarea rows="2" cols="50" maxlength="300" id="txtfrm'+id+'" type="text" class="form-control form-control-sm"  placeholder="Add Note"></textarea></div>';
          btn_visit_check+= "</td></tr><tr>";
          btn_visit_check+= '<td><button class="form-control form-control-sm btn btn-success btn-sm" type="button" onclick="saveRemark(\''+dataObj.pid+'\',\''+dataObj.acid+'\',\''+dataObj.uic+'\', \''+dataObj.visit_date+'\')"> <i class="fa fa-check fa-lg" ></i> บันทึก</button></td>';
          btn_visit_check+= '<td><button class="form-control form-control-sm btn btn-danger btn-sm" type="button" onclick="cancelEditRemark(\''+id+'\')"> <i class="fa fa-times fa-lg" ></i> ยกเลิก</button></td>';
          btn_visit_check+= "</tr></table>";



          txt_row += '<tr class="r_svgm">';
          txt_row += ' <td>'+dataObj.site+'</td>';
          txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+" "+btn_visit_check+'</td>';
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
      txt_row += '<tr class="r_svgm"><td colspan="8" align="center">ไม่พบข้อมูล</td></tr">';

    }

    $('.r_svgm').remove(); // row uic proj summary
    $('#tbl_surveygizmo_list > tbody:last-child').append(txt_row);

  }
}

function checkSG_FormDone(c_pid, c_acid, c_uic,c_visit_date){
  var id = c_pid+c_acid+c_uic+c_visit_date;

  var revise_pid = $("#pid"+id).val().trim();
  var revise_acid = $("#acid"+id).val().trim();
  var revise_uic = $("#uic"+id).val().trim();

  var r_id = revise_pid+revise_acid+revise_uic+c_visit_date;

  var IsReviseID = 0;
  if(id != r_id){ // there is revised id
    IsReviseID = 1;
    // extract only changed revise id
    revise_pid = (revise_pid != c_pid)?revise_pid:"";
    revise_acid = (revise_acid != c_acid)?revise_acid:"";
    revise_uic = (revise_uic != c_uic)?revise_uic:"";
  }

    var aData = {
              u_mode:"check_formdone",
              pid:c_pid,
              acid:c_acid,
              uic:c_uic,
              is_revise_id: IsReviseID,
              r_pid:revise_pid,
              r_acid:revise_acid,
              r_uic:revise_uic,
              visit_date:c_visit_date
    };
    //alert("r_uic "+aData.r_uic);
    save_data_ajax(aData,"w_ext_surveygizmo/trc_db_surveygizmo.php",checkSG_FormDoneComplete);


}

function checkSG_FormDoneComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.u_mode);
  if(flagSave){
     var id = aData.pid+aData.acid+aData.uic+aData.visit_date;
    // alert("id "+id);
     $('#btn_'+id).hide();
     $('.'+id).removeClass('badge-warning');
     $('.'+id).addClass('badge-success');
     $('.'+id).html('<i class="fa fa-check"></i> ตรวจแล้ว');

     $('#pid'+id).prop("disabled", true);
     $('#acid'+id).prop("disabled", true);
     $('#uic'+id).prop("disabled", true);

     if(aData.is_revise_id == "1"){
    //   $.notify("ส่งขอแก้ไข ID เรียบร้อยแล้ว", "info");
     }


  }
}


// add note
function editRemark(rowID){ // uic+visit_date
//  alert("click editRemark "+visitID);
  $('#frm'+rowID).hide();
  $('#divfrm'+rowID).show();
  $('#txtfrm'+rowID).val($('#frm'+rowID).data("odata"));
  $('#txtfrm'+rowID).focus();
}

function cancelEditRemark(rowID){
  var txt = $('#frm'+rowID).data("odata");
  if(txt == "") txt= "ADD Note";
//  alert("cancel data "+txt);
  $('#frm'+rowID).html(txt);
  $('#txtfrm'+rowID).val("");

  $('#frm'+rowID).show();
  $('#divfrm'+rowID).hide();
}


function saveRemark(c_pid,c_acid,c_uic, c_visit_date){
  var rowID = c_pid+c_acid+c_uic + c_visit_date;

  if($('#txtfrm'+rowID).val().trim() == ""){
    $('#txtfrm'+rowID).notify("กรุณากรอก Note ", "warn");
    return;
  }


      var aData = {
                u_mode:"update_form_remark",
                pid: c_pid,
                acid: c_acid,
                uic: c_uic,
                visit_date: c_visit_date,
                remark:$('#txtfrm'+rowID).val().trim()
      };

      save_data_ajax(aData,"w_ext_surveygizmo/trc_db_surveygizmo.php",saveRemarkComplete);

}

function saveRemarkComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var id = aData.pid+aData.acid+aData.uic+aData.visit_date;

    $('#frm'+id).removeClass("text-secondary");
    var txt = $('#txtfrm'+id).val().trim();
    $('#frm'+id).data("odata",txt);

    if(txt == ""){
      txt = "ADD Note";
      $('#frm'+id).addClass("text-secondary");
    }

    $('#frm'+id).html(txt);

    $('#divfrm'+id).hide();
    $('#frm'+id).show();

  }

}

</script>
