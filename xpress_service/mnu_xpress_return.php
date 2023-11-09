<?
include_once("../in_auth.php");

?>

<style>
.status_pink {
  color: #000;
  background-color: #FFBFFF;
}
</style>

<div class="card" id="div_xpress_rtn_list">
  <div class="card-body">
    <div class="card-title">


      <div class="row">
         <div class="col-sm-2">
           <div>
             <center><h5><i class="fa fa-clipboard-list fa-lg" ></i> XPress Service</h5></center>
           </div>
           <div>
             <center><h5>นัดตรวจหลังแจ้งผล</h5></center>
           </div>
         </div>
         <div class="col-sm-2">
           <label for="sel_xpress_rtn_date_beg">ตั้งแต่วันที่:</label>
           <input type="text" id="sel_xpress_rtn_date_beg" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="sel_xpress_rtn_date_end">ถึงวันที่:</label>
           <input type="text" id="sel_xpress_rtn_date_end" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">

           <label for="sel_rtn_back">นัดหมาย:</label>
           <select id="sel_rtn_back" class="form-control form-control-sm" >
             <option value="N" selected class="text-success">ยังไม่มา</option>
             <option value="Y" class="text-danger">มาแล้ว</option>
             <option value=""  class="text-dark">ทั้งหมด</option>
           </select>
         </div>

         <div class="col-sm-3">
           <label for="txt_search_rtn_xpress">ค้นหา (uic, uid, name):</label>
           <input type="text" id="txt_search_rtn_xpress" class="form-control form-control-sm" >
         </div>
         <div class="col-sm-1">
           <label for="btn_search_return_xpress" class="text-light">.</label>
          <button class="btn btn-info form-control" type="button" id="btn_search_return_xpress"><i class="fa fa-search" ></i> ค้นหา</button>
         </div>


       </div>


    </div>
    <div>
      <table id="tbl_xpress_rtn_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>

              <th>UID / UIC</th>
              <th>เบอร์โทรติดต่อ</th>
              <th>วันที่เข้าตรวจ<br>(xpress)</th>
              <th>วันที่ส่งผล<br>(xpress)</th>
              <th>นัดหมายกลับมา<br>หลังโทรแจ้ง</th>
              <th>วันเข้าตรวจ<br>หลังโทรแจ้ง</th>
              <th>Note</th>
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


      var currentDate = new Date();
      currentDate.setYear(currentDate.getFullYear() + 543);


        $("#sel_xpress_rtn_date_beg").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy',
          onSelect: function(date) {
            $("#sel_xpress_rtn_date_beg").addClass('filled');
          }
        });
        $("#sel_xpress_rtn_date_end").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy',
          onSelect: function(date) {
            $("#sel_xpress_rtn_date_end").addClass('filled');
          }
        });

        $('#sel_xpress_rtn_date_beg').datepicker("setDate",currentDate );
        $('#sel_xpress_rtn_date_end').datepicker("setDate",currentDate );

  $("#btn_search_return_xpress").click(function(){
     //alert("clinic scheud");
     selectXpressRtnList();
  }); // btn_search_return_xpress

});

// set date picker for loaded component
function setDatePick(comp){
  //$("#"+comp).addClass("bg-warning");
  $("#"+comp).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd/mm/yy',
    onSelect: function(date) {
      $("#"+comp).addClass('filled');
    }
  });

  if($("#"+comp).val() == ''){
    var curDate = new Date();
    //curDate.setYear(curDate.getFullYear() + 543);
    $("#"+comp).datepicker("setDate",curDate );
  }
}

function selectXpressRtnList(){
  var aData = {
            u_mode:"select_xpress_rtn_list",
            rtn_back:$('#sel_rtn_back').val(),
            txt_search:$('#txt_search_rtn_xpress').val(),
            date_beg:changeToEnDate($('#sel_xpress_rtn_date_beg').val()),
            date_end:changeToEnDate($('#sel_xpress_rtn_date_end').val())
  };
  save_data_ajax(aData,"xpress_service/db_xpress_service_send.php",selectXpressRtnListComplete);
}

function selectXpressRtnListComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
//tbl_uid_schedule_list
      txt_row="";
      if(rtnDataAjax.datalist.length > 0){
        var btn_rtn_back = "";
        var datalist = rtnDataAjax.datalist;
          for (i = 0; i < datalist.length; i++) {

            var dataObj = datalist[i];
            var xpress_note = (dataObj.xpress_note != "")?"<br"+dataObj.xpress_note:"";

            var xpress_rtn_schedule_date = "";
            var xpress_rtn_visit_date = "";
            var xpress_rtn_visit_info = "";


              // return schedule date with staff id (who call and make the appointment to patient)
              xpress_rtn_schedule_date += changeToThaiDate(dataObj.rtn_schedule_date);
              xpress_rtn_schedule_date += '<br><span class="badge status_pink"> '+dataObj.schedule_by+' </span> ';

              if(dataObj.rtn_by != ''){
                if(dataObj.rtn_visit_date != ''){
                  xpress_rtn_visit_date += changeToThaiDate(dataObj.rtn_visit_date);
                  xpress_rtn_visit_date += '<br><span class="badge badge-info"> '+dataObj.rtn_by+' </span> ';
                }
                else{
                  xpress_rtn_visit_date += '<span class="badge badge-danger"> ไม่กลับมาตรวจ </span> ';
                  xpress_rtn_visit_date += '<br><span class="badge badge-secondary"> '+dataObj.rtn_by+' </span> ';
                }

                xpress_rtn_visit_info += dataObj.rtn_visit_note;
              }
              else{
                xpress_rtn_visit_date += '<span class="badge badge-warning"> ยังไม่เข้าตรวจ </span> ';

                xpress_rtn_visit_info += '<div class="px-2">';
                xpress_rtn_visit_info += ' <div class="row">';
                xpress_rtn_visit_info += '  <div class="col-md-6">';
                xpress_rtn_visit_info += '    <button class="btn btn-sm btn-success btn-block" type="button" onclick="setReturnVisit(\''+dataObj.uid+'\',\''+dataObj.collect_date+'\', 1)"><i class="fa fa-person-booth"></i> กลับมาตรวจ </button>';
                xpress_rtn_visit_info += '  </div>';
                xpress_rtn_visit_info += '  <div class="col-md-6">';
                xpress_rtn_visit_info += '     <input type="text" id="'+dataObj.uid+dataObj.collect_date+'_rtn_visit_date" class="v_date" onclick="setDatePick(\''+dataObj.uid+dataObj.collect_date+'_rtn_visit_date\')" placeholder="วันที่กลับมาตรวจ" readonly="readonly">';
                xpress_rtn_visit_info += '  </div>';
                xpress_rtn_visit_info += ' </div>';
                xpress_rtn_visit_info += '<div class="row mt-1">';
                xpress_rtn_visit_info += '  <div class="col-md-6">';
                xpress_rtn_visit_info += '    <button class="btn btn-sm btn-block btn-danger" type="button" onclick="setReturnVisit(\''+dataObj.uid+'\',\''+dataObj.collect_date+'\', 2)"><i class="fa fa-times"></i> ไม่ได้กลับมาตรวจ </button>';
                xpress_rtn_visit_info += '  </div>';
                xpress_rtn_visit_info += '  <div class="col-md-6">';

                xpress_rtn_visit_info += '  </div>';
                xpress_rtn_visit_info += '</div>';
                xpress_rtn_visit_info += '<div class="row mt-1"><div class="col-md-12">';
                xpress_rtn_visit_info += '<textarea class="form-control" id="'+dataObj.uid+dataObj.collect_date+'_rtn_visit_note" rows="2" cols="30" placeholder="Xpress Note"></textarea> ';
                xpress_rtn_visit_info += '</div></div>';
                xpress_rtn_visit_info += '</div>';

              }


            txt_row += '<tr class="r_xpress_rtn">';
            txt_row += ' <td>'+dataObj.uid+' / '+dataObj.uic+'</td>';
            txt_row += ' <td>'+dataObj.tel+'</td>';
            txt_row += ' <td>'+changeToThaiDate(dataObj.collect_date)+'</td>';
            txt_row += ' <td>'+changeToThaiDate(dataObj.send_date)+xpress_note+'</td>';
            txt_row += ' <td>'+xpress_rtn_schedule_date+'</td>';
            txt_row += ' <td><span id="'+dataObj.uid+dataObj.collect_date+'_rtn_visit_date_div">'+xpress_rtn_visit_date+'</span></td>';
            txt_row += ' <td><span id="'+dataObj.uid+dataObj.collect_date+'_rtn_visit_note_div">'+xpress_rtn_visit_info+'</span></td>';
            txt_row += '</tr">';

          }//for

      }
      $('.r_xpress_rtn').remove(); // row uic proj summary
      $('#tbl_xpress_rtn_list > tbody:last-child').append(txt_row);


  }
}

//setReturnVisit
function setReturnVisit(uidReturn, visitDate, returnType){ // returnType 1:patient comeback, 2:patient not comeback
  var msgConfirm = "";
  if(returnType == "1"){
    msgConfirm = "ยืนยันการกลับมาตรวจของคนไข้ "+uidReturn+" ?";
  }
  else if(returnType == "2"){
    msgConfirm = "ยืนยันการไม่มาตรวจของคนไข้ "+uidReturn+" ?";
  }

  var result = confirm(msgConfirm);
  if (result) {
   if(returnType == "1"){ // ถ้ากลับมาตรวจ ให้กรอกวันที่กลับมาตรวจใหม่
      if($('#'+uidReturn+visitDate+'_rtn_visit_date').val() == ""){
        $('#'+uidReturn+visitDate+'_rtn_visit_date').notify("กรุณากรอกวันแจ้งนัดหมายกลับมาตรวจ", "info");
        return;
      }
   }

//alert("return date : "+$('#'+uidReturn+visitDate+'_rtn_date').val());
    var aData = {
              u_mode:"return_visit_xpress",
              uid: uidReturn,
              visit_date: visitDate,
              return_status: returnType,
              rtn_visit_note: $('#'+uidReturn+visitDate+'_rtn_visit_note').val().trim(),
              rtn_visit_date: changeToEnDate($('#'+uidReturn+visitDate+'_rtn_visit_date').val())
    };
    save_data_ajax(aData,"xpress_service/db_xpress_service_send.php",setReturnVisitComplete);
  }

}

function setReturnVisitComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var rtn_visit_date = '';

    if(aData.return_status == "1"){ // comeback to visit
      rtn_visit_date = changeToThaiDate(aData.rtn_visit_date);
    }
    else if(aData.return_status == "2"){ // not comeback to visit
      rtn_visit_date = '<span class="badge badge-danger"> ไม่กลับมาตรวจ </span> ';
    }

    $('#'+aData.uid+aData.visit_date+'_rtn_visit_date_div').html(rtn_visit_date);
    $('#'+aData.uid+aData.visit_date+'_rtn_visit_note_div').html(aData.rtn_visit_note);
  }
}

function openAfterServiceXpress(visitDate, xpressUID){
  var link = "xpress_service/link_xpress_satisfaction_after.php";

  link += "?uid="+xpressUID; // uid
  link += "&visit_date="+visitDate; // visit date
  link += "&uic="+$('#cur_uic').val(); // uic
  link += "&site=<? echo $staff_clinic_id;?>"; // site
  link += "&bd=Y"; // backdate

  window.open(link,'_blank');
}

</script>
