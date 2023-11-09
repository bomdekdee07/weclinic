<?
include_once("../in_auth.php");

?>

<style>
.status_pink {
  color: #000;
  background-color: #FFBFFF;
}
</style>

<div class="card" id="div_xpress_send_list">
  <div class="card-body">
    <div class="card-title">


      <div class="row">
         <div class="col-sm-2">
           <div>
             <center><h5><i class="fa fa-paper-plane fa-lg" ></i> XPress Service</h5></center>
           </div>
           <div>
             <center><h4>ส่งผลตรวจ</h4></center>
           </div>
         </div>
         <div class="col-sm-2">
           <label for="sel_xpress_send_date_beg">ตั้งแต่วันที่:</label>
           <input type="text" id="sel_xpress_send_date_beg" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="sel_xpress_send_date_end">ถึงวันที่:</label>
           <input type="text" id="sel_xpress_send_date_end" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">

           <label for="sel_send_result">การส่งผลตรวจ:</label>
           <select id="sel_send_result" class="form-control form-control-sm" >
             <option value="Y" selected class="text-success">ส่งแล้ว</option>
             <option value="N" class="text-danger">ยังไม่ส่ง</option>
             <option value=""  class="text-dark">ทั้งหมด</option>
           </select>
         </div>

         <div class="col-sm-3">
           <label for="txt_search_send_xpress">ค้นหา (uic, uid, name):</label>
           <input type="text" id="txt_search_send_xpress" class="form-control form-control-sm" >
         </div>
         <div class="col-sm-1">
           <label for="btn_search_send_xpress" class="text-light">.</label>
          <button class="btn btn-info form-control" type="button" id="btn_search_send_xpress"><i class="fa fa-search" ></i> ค้นหา</button>
         </div>


       </div>


    </div>
    <div>
      <table id="tbl_xpress_send_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th>วันที่เข้าตรวจ</th>
              <th>UID / UIC</th>
              <th>เบอร์โทรติดต่อ</th>
              <th>ช่องทางการส่ง<br>(ระบุใน Consent)</th>
              <th>สถานะ</th>
              <th>ข้อมูลการส่ง</th>
              <th>แบบประเมินความพอใจ<br>(หลังตรวจ)</th>
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


        $("#sel_xpress_send_date_beg").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy',
          onSelect: function(date) {
            $("#sel_xpress_send_date_beg").addClass('filled');
          }
        });
        $("#sel_xpress_send_date_end").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy',
          onSelect: function(date) {
            $("#sel_xpress_send_date_end").addClass('filled');
          }
        });

        $('#sel_xpress_send_date_beg').datepicker("setDate",currentDate );
        $('#sel_xpress_send_date_end').datepicker("setDate",currentDate );

  $("#btn_search_send_xpress").click(function(){
     //alert("clinic scheud");
     selectXpressSendResultList();
  }); // btn_search_send_xpress


  $(".v_date").change(function(){ // validate date field
    if($(this).val().trim() != ''){
      if(!validateDate($(this).val())){
        $(this).addClass("input_invalid");
        $(this).css("background-color","#FFBFBF");
        $(this).notify("วันที่ไม่ถูกต้อง","warn");
      }
      else{
        $(this).removeClass("input_invalid");
        $(this).css("background-color","#FFF");
      }
    }
  });

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

function checkDateInput(comp){
  if($("#"+comp).val().trim() != ''){
    if(!validateDate($("#"+comp).val())){
      $("#"+comp).addClass("input_invalid");
      $("#"+comp).css("background-color","#FFBFBF");
      $("#"+comp).notify("วันที่ไม่ถูกต้อง","warn");
    }
    else{
      $("#"+comp).removeClass("input_invalid");
      $("#"+comp).css("background-color","#FFF");
    }
  }
}







function selectXpressSendResultList(){
  var aData = {
            u_mode:"select_xpress_service_list",
            send_result:$('#sel_send_result').val(),
            txt_search:$('#txt_search_send_xpress').val(),
            date_beg:changeToEnDate($('#sel_xpress_send_date_beg').val()),
            date_end:changeToEnDate($('#sel_xpress_send_date_end').val())
  };
  save_data_ajax(aData,"xpress_service/db_xpress_service_send.php",selectXpressSendResultListComplete);
}

function selectXpressSendResultListComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
//tbl_uid_schedule_list
      txt_row="";
      if(rtnDataAjax.datalist.length > 0){
        var btn_send_result = "";
        var datalist = rtnDataAjax.datalist;
          for (i = 0; i < datalist.length; i++) {

            var dataObj = datalist[i];
            var xpress_status = "";
            var xpress_send_info = "";
            var xpress_send_by = "";
            var xpress_after_service = ""; // after service satisfaction form

              // option of xpress channel
              if(dataObj.ch == '1') xpress_send_by += "Line: ";
              else if(dataObj.ch == '2') xpress_send_by += "Email: ";
              else if(dataObj.ch == '3') xpress_send_by += "Tel: ";
              else if(dataObj.ch == '4') xpress_send_by += "SMS: ";

              xpress_send_by += dataObj.ch_info;

              if(dataObj.s_name != ""){
                xpress_send_info += '['+dataObj.send_date+'] <small>โดย '+dataObj.s_name+' </small>';
                if(dataObj.send_status == '1'){
                  xpress_status += '<span class="badge badge-success"> ส่งผลตรวจ </span> ';
                }
                else if(dataObj.send_status == '2'){
                  xpress_status += '<span class="badge status_pink"> โทรแจ้งภายหลัง </span> ';
                  xpress_status += '<br><small><span class="text-danger">นัดกลับ: '+changeToThaiDate(dataObj.rtn_schedule_date)+'</span></small>';
                  //xpress_status += '<span class="badge badge-danger"> โทรแจ้งภายหลัง </span> ';
                }

                if(dataObj.xpress_note != "")
                xpress_send_info += '<br><b>Note: '+dataObj.xpress_note+' </b>';

              }
              else{
                xpress_status += '<span class="badge badge-warning"> ยังไม่ส่งผล </span> ';

                xpress_send_info += '<div class="px-2">';
                xpress_send_info += ' <div class="row">';
                xpress_send_info += '  <div class="col-md-12">';
                xpress_send_info += '    <button class="btn btn-sm btn-success btn-block" type="button" onclick="sendXPressResult(\''+dataObj.uid+'\',\''+dataObj.collect_date+'\', 1)"><i class="fa fa-paper-plane"></i> ส่งผลตรวจ </button>';
                xpress_send_info += '  </div>';
                xpress_send_info += ' </div>';
                xpress_send_info += '<div class="row mt-1">';
                xpress_send_info += '  <div class="col-md-6">';
                xpress_send_info += '    <button class="btn btn-sm status_pink btn-block" type="button" onclick="sendXPressResult(\''+dataObj.uid+'\',\''+dataObj.collect_date+'\', 2)"><i class="fa fa-phone-square-alt"></i> โทรแจ้งภายหลัง </button>';
                xpress_send_info += '  </div>';
                xpress_send_info += '  <div class="col-md-6">';
                xpress_send_info += '     <input type="text" id="'+dataObj.uid+dataObj.collect_date+'_rtn_schedule_date" class="v_date" data-title="วันที่กลับมาตรวจ" onclick="setDatePick(\''+dataObj.uid+dataObj.collect_date+'_rtn_schedule_date\')" onchange="checkDateInput(\''+dataObj.uid+dataObj.collect_date+'_rtn_schedule_date\')" placeholder="วันนัดกลับมาตรวจ" >';
                xpress_send_info += '  </div>';
                xpress_send_info += '</div>';
                xpress_send_info += '<div class="row mt-1"><div class="col-md-12">';
                xpress_send_info += '<textarea class="form-control" id="'+dataObj.uid+dataObj.collect_date+'_note" rows="2" cols="30" placeholder="Xpress Note"></textarea> ';
                xpress_send_info += '</div></div>';
                xpress_send_info += '</div>';

              }

              if(dataObj.after_service != ""){
                xpress_after_service += '<span class="badge badge-success"> ทำแล้ว </span> ';
              }
              else{
                xpress_after_service += 'ยังไม่ทำ <button class="btn btn-primary btn-sm" type="button" onclick="openAfterServiceXpress(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\')"> LINK</button>';
              }


            txt_row += '<tr class="r_xpress_result">';
            txt_row += ' <td>'+changeToThaiDate(dataObj.collect_date)+'</td>';
            txt_row += ' <td>'+dataObj.uid+' / '+dataObj.uic+'</td>';

            txt_row += ' <td>'+dataObj.c_agree_tel+'</td>';
            txt_row += ' <td>'+xpress_send_by+'</td>';
            txt_row += ' <td><span id="'+dataObj.uid+dataObj.collect_date+'_status">'+xpress_status+'</span></td>';
            txt_row += ' <td><span id="'+dataObj.uid+dataObj.collect_date+'_info">'+xpress_send_info+'</td>';
            txt_row += ' <td>'+xpress_after_service+'</td>';
            txt_row += '</tr">';

          }//for

      }
      $('.r_xpress_result').remove(); // row uic proj summary
      $('#tbl_xpress_send_list > tbody:last-child').append(txt_row);


  }
}

//sendXPressResult
function sendXPressResult(uidSend, visitDate, sendType){
  var msgConfirm = "";
  if(sendType == "1"){
    msgConfirm = "ยืนยันการส่งผลตรวจคนไข้ "+uidSend+" ?";
  }
  else if(sendType == "2"){
    msgConfirm = "ยืนยันเลือกการโทรแจ้งผลภายหลัง "+uidSend+" ?";
  }

  var result = confirm(msgConfirm);
  if (result) {
   if(sendType == "2"){ // ถ้าผลไม่ปกติ ให้กรอกวันที่นัดหมายกลับมาตรวจใหม่
      if($('#'+uidSend+visitDate+'_rtn_schedule_date').val() == ""){
        $('#'+uidSend+visitDate+'_rtn_schedule_date').notify("กรุณากรอกวันแจ้งนัดหมายกลับมาตรวจ", "info");
        return;
      }
      else{
        if(!validateDate($('#'+uidSend+visitDate+'_rtn_schedule_date').val())){
          $('#'+uidSend+visitDate+'_rtn_schedule_date').notify($('#'+uidSend+visitDate+'_rtn_schedule_date').data("title") +" ไม่ถูกต้อง","error");
          return;
        }
      }
   }

//alert("return date : "+$('#'+uidSend+visitDate+'_rtn_schedule_date').val());

    var aData = {
              u_mode:"send_xpress_result",
              uid: uidSend,
              visit_date: visitDate,
              send_status: sendType,
              xpress_note: $('#'+uidSend+visitDate+'_note').val().trim(),
              rtn_schedule_date: changeToEnDate($('#'+uidSend+visitDate+'_rtn_schedule_date').val())
    };
    save_data_ajax(aData,"xpress_service/db_xpress_service_send.php",sendXPressResultComplete);
  }

}

function sendXPressResultComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

     var xpress_status = '';
     var xpress_info = '['+rtnDataAjax.send_date+'] <small>โดย '+rtnDataAjax.s_name+'</small>';

     if(aData.xpress_note != '')
     xpress_info += '<br><b>Note: '+aData.xpress_note+'</b>';

    if(rtnDataAjax.send_status == '1')
    xpress_status = '<span class="badge badge-success"> ส่งผลตรวจ </span> ';

    else if(rtnDataAjax.send_status == '2'){
      //xpress_status = '<span class="badge badge-danger"> โทรแจ้งภายหลัง </span> ';
      xpress_status = '<span class="badge status_pink"> โทรแจ้งภายหลัง </span> ';
      xpress_status += '<br><small><span class="text-danger"><b>นัดกลับ: '+changeToThaiDate(aData.rtn_schedule_date)+'</b></span></small>';
    }


     $('#'+aData.uid+aData.visit_date+'_status').html(xpress_status);
     $('#'+aData.uid+aData.visit_date+'_info').html(xpress_info);

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
