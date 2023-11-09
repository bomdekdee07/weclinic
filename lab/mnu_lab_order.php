<?
include_once("../in_auth.php");
include_once("inc_auth.php"); // set permission view, update, delete
include_once("../a_app_info.php");

?>

<style>

.btn:hover{
  cursor: pointer;
  filter:brightness(90%);
}
.spanproj{
  float:left;
  width:50px;
  font-size:smaller;
}
.inpproj{
  font-size: smaller;
  width:90px;
}



</style>
<script>

//**** lab  var
var v_sale_option = "";
var v_laboratory_option = "";


var cur_lab_order_id = ""; // current lab order id
var cur_lab_uid = ""; // current uid
var cur_lab_collect_date = ""; // current collect date
var cur_lab_collect_time = ""; // current collect time
//****



</script>


<div id='div_lab_data_list' class='div-lab-order my-0'>
  <div class="row mt-0">
    <div class="col-sm-5">
      <h4><i class="fa fa-database fa-lg" ></i> <b>Lab Order Summary</b> </h4>
    </div>
    <div class="col-sm-5" >
      <div >
      Export Data:
      <button type="button" id="btn_export_lab_data"><i class="fa fa-file-download" ></i> Export Lab Data</button>
      <i class="fas fa-spinner fa-spin spinner" style="display:none;"></i>
      </div>
    </div>
    <div class="col-sm-2">
      <button class="btn btn-success btn-sm form-control form-control-sm " type="button" id="btn_new_lab_order"><i class="fa fa-plus" ></i> New Lab Order</button>
    </div>
  </div>

  <div class="row mt-0">
    <div class="col-sm-1 pr-1">
      <label for="txt_labdate_from">วันที่เริ่มต้น:</label>
      <input type="text" id="txt_labdate_from" class="form-control form-control-sm v_date inpproj export-date bg-warning" placeholder="Begin Date">
    </div>
    <div class="col-sm-1 pl-1">
      <label for="txt_labdate_to">วันที่สิ้นสุด:</label>
      <input type="text" id="txt_labdate_to" class="form-control form-control-sm v_date inpproj export-date bg-warning" placeholder="End Date">
    </div>
    <div class="col-sm-2">
      <label for="sel_lab_status">สถานะ:</label>
      <select id="sel_lab_status" class="form-control form-control-sm">
        <option value="">All</option>
        <option value="A2">Specimen Collect Pending</option>
        <option value="A3">Lab Result Pending</option>
        <option value="A4">Lab Result Complete</option>
      </select>
    </div>
    <div class="col-sm-6">
      <label for="txt_search_lab_order">คำค้นหา:</label>
      <input type="text" id="txt_search_lab_order" class="form-control form-control-sm" placeholder="พิมพ์คำค้นหา ">
    </div>
     <div class="col-sm-2">
       <label for="btn_search_lab_order" class="text-white">.</label>
      <button class="btn btn-primary btn-sm form-control form-control-sm " type="button" id="btn_search_lab_order"><i class="fa fa-search" ></i> Search</button>
     </div>
   </div>

   <div class="mt-2"  style="min-height: 300px; border:1px solid grey;">
     <table id="tbl_lab_order_list" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
         <thead>
           <tr>
             <th width="100px">Lab Order ID</th>
             <th width="10%">UID (Clinic)</th>
             <th width="10%">Visit Date/Time</th>
             <th width="10%">Lab Order Status</th>
             <th>Requested By </th>
             <th width='160px'>Order Note</th>
             <th width='160px'>Project</th>
             <th>Wait Lab Result?</th>
             <th>Lab Order</th>
             <th>Specimen Collect</th>
             <th>Lab Result</th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_data_group -->

<div id='div_lab_data_lab_order' class="div-lab-order my-0" style="display:none;">
</div> <!-- div_lab_data_detail -->
<div id='div_lab_data_specimen_collect' class="div-lab-order my-0" style="display:none;">
</div> <!-- div_lab_data_specimen_collect -->
<div id='div_lab_data_lab_result' class="div-lab-order my-0" style="display:none;">
</div> <!-- div_lab_data_specimen_collect -->

<div id='div_lab_data_new_blank_order' class="div-lab-order my-0" style="display:none;">
</div> <!-- div_lab_data_new_blank_order -->







<script>

$(document).ready(function(){


  $.datepicker.setDefaults( $.datepicker.regional[ "th" ] );
  var currentDate = new Date();
  //currentDate.setYear(currentDate.getFullYear() + 543);
  currentDate.setYear(currentDate.getFullYear());

  $('.v_date').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd/mm/yy'
  });

  $(".v_date").focus(function(){ // set to current date when focus to date field
    if($(this).val() == ''){
      $(this).datepicker('setDate',currentDate);
    }
  });

    $(".v_date").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});

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

    $(".export-date").datepicker('setDate',currentDate);
    $("#btn_export_lab_data").unbind();
    $("#btn_export_lab_data").click(function(){
       exportLabData();
    }); // btn_search_lab_order


  $("#tbl_lab_order_list").on("change",".txtProjId,.txtPid,.txtVid",function(){
    let sObjTr = $(this).closest("tr");
    //Check data changed.
    sOProjId = $(sObjTr).find(".txtProjId").attr("data-odata");
    sOPid = $(sObjTr).find(".txtPid").attr("data-odata");
    sOVid = $(sObjTr).find(".txtVid").attr("data-odata");

    let isChanged = false;

    if(sOProjId!= $(sObjTr).find(".txtProjId").val()) isChanged = true;
    else if(sOPid!= $(sObjTr).find(".txtPid").val()) isChanged = true;
    else if(sOVid!= $(sObjTr).find(".txtVid").val()) isChanged = true;


    if(isChanged) $(sObjTr).find(".btnSaveProj").show();
    else $(sObjTr).find(".btnSaveProj").hide();


  });


  selectSaleOption();
  selectLaboratoryOption();

  searchData_LabOrder();

  $("#btn_search_lab_order").click(function(){
     searchData_LabOrder();
  }); // btn_search_lab_order


  $("#txt_search_lab_order").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_LabOrder();
    }
  });
  $("#btn_new_lab_order").click(function(){
     openNewBlankLabOrder("");
  }); // btn_search_lab_order

});

function closeToLabOrderList(){
  //searchData_LabOrder();
  showDataLabOrderDiv("list");
}



function exportLabData(){

  if($("#txt_labdate_from").val() == ""){
    $("#txt_labdate_from").notify("เลือกวันที่ไม่ถูกต้อง", "error");
    return;
  }
  if($("#txt_labdate_to").val() == ""){
    $("#txt_labdate_to").notify("เลือกวันที่ไม่ถูกต้อง", "error");
    return;
  }



  if(validateDate($("#txt_labdate_from").val()) && validateDate($("#txt_labdate_to").val())){
    dateFrom = changeToEnDate($("#txt_labdate_from").val());
    dateTo = changeToEnDate($("#txt_labdate_to").val());
    if(dateTo < dateFrom){
      $("#txt_labdate_to").notify("เลือกวันที่ไม่ถูกต้อง", "error");
      return;
    }
  }

  var aData = {
      date_beg:dateFrom,
      date_end:dateTo
  };
  save_data_ajax(aData,"lab/xls_lab_test_order.php",exportLabDataComplete);
  $("#btn_export_lab_data").next(".spinner").show();
  $("#btn_export_lab_data").hide();

}

function exportLabDataComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);

  if(flagSave){
    window.open(rtnDataAjax.link_xls, '_blank');
  }

  $("#btn_export_lab_data").next(".spinner").hide();
  $("#btn_export_lab_data").show();

}



function searchData_LabOrder(){
  let dateFrom = "";
  let dateTo = "";
  if($("#txt_labdate_from").val().trim() != "" && $("#txt_labdate_to").val().trim() != "" ){
    if(validateDate($("#txt_labdate_from").val()) && validateDate($("#txt_labdate_to").val())){
      dateFrom = changeToEnDate($("#txt_labdate_from").val());
      dateTo = changeToEnDate($("#txt_labdate_to").val());
      if(dateTo < dateFrom){
        $("#txt_labdate_to").notify("เลือกวันที่ไม่ถูกต้อง", "error");
        return;
      }
    }

  }


  var aData = {
      u_mode:"select_lab_order_list",
      txt_search:$("#txt_search_lab_order").val().trim(),
      lab_status: $("#sel_lab_status").val(),
      date_beg: dateFrom,
      date_end: dateTo

  };
  save_data_ajax(aData,"lab/db_lab_test_order.php",searchData_LabOrder_Complete);
}

function searchData_LabOrder_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $('.r_data').remove(); // row data list
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_LabOrder(
             dataObj.lab_order_id,dataObj.uid, dataObj.collect_date,dataObj.collect_time,
             dataObj.status_id, dataObj.status_name, dataObj.wait_lab_result,
              dataObj.request_by, dataObj.room_no, dataObj.clinic_type ,dataObj.time_confirm_order,dataObj.time_specimen_collect,dataObj.time_lab_order_pmt,dataObj.time_lab_report_confirm,dataObj.proj_id,dataObj.proj_pid,dataObj.proj_visit,dataObj.timepoint_id, dataObj.lab_order_note
              , dataObj.queue, dataObj.room_no, '16'
            );

        }//for

        $('#tbl_lab_order_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }
  }
}


function openNewBlankLabOrder(uid){
  var link = "mnu_lab_order_new.php?uid="+uid;
  $("#div_lab_data_new_blank_order").html("");
  $("#div_lab_data_new_blank_order").load("lab/"+link, function(){
    showDataLabOrderDiv("new_blank_order");
  });
}

function close_new_lab_order(){
  closeToLabOrderList();
}
function afterInsertBlankLabOrder(){
  searchData_LabOrder();
  closeToLabOrderList();
}


function addRowData_LabOrder(lab_order_id,  uid, collect_date, collect_time,
  status_id, status_name,wait_lab_result, request_lab_by, requested_room_no, clinic_type,time_confirm_order,time_specimen_collect,time_lab_order_pmt,time_lab_report_confirm,projid,pid,vid,timepointid, lab_order_note
  ,queue, room_no, prev_room ){


    var  clinic_name = "Pribta";
    if(clinic_type == "T"){
      clinic_name = "<b>Tangerine</b>";
    }
    else if(clinic_type == "P"){
      clinic_name = "Pribta";
    }

    let send_queue_txt = '';
    if(queue != null){
      //send_queue_txt = '<b>Q# '+queue+' | Room: '+room_no+' ('+prev_room+')</b>';
      send_queue_txt = '<b>Q# '+queue+' | Room: '+room_no+'</b>';
      if(room_no == '24' && status_id != "C"){
          send_queue_txt += '<br><button class="btn btn-sm btn-warning" onclick="checkQueueExist(\''+lab_order_id+'\');">ส่งคิว | Send Queue</button>';
      }
    }
    else{
      if(status_id != "C"){
        send_queue_txt += '<br><button class="btn btn-sm btn-warning" onclick="checkQueueExist(\''+lab_order_id+'\');">ส่งคิว | Send Queue</button>';
      }
    }

    if(time_confirm_order == null) time_confirm_order = "";
    if(time_specimen_collect == null) time_specimen_collect = "";
    if(time_lab_report_confirm == null) time_lab_report_confirm = "";
    if(time_lab_order_pmt == null) time_lab_order_pmt = ""; 


    if(wait_lab_result=='1') wait_lab_result = '<span class="text-success"><b>Yes</b></span>';
    else if(wait_lab_result=='0') wait_lab_result = '<span class="text-danger"><b>No</b></span>';
    else if(wait_lab_result=='2') wait_lab_result = '<span class="text-primary"><b>Sent Queue</b></span>';
    else wait_lab_result = 'Unknown';


/*
    sBtnFix = ""; sFixUBtn="";
    if(status_id=='A0' || status_id=='A1' ||status_id=='B0'){
      sNewStat = 'A2';
      if(status_id=='B0') sNewStat ='A3';

      sBtnFix = status_name+' <button class="btn btn-primary" onclick="fixLabStatus(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\',\'fix_order_status\',\''+sNewStat+'\');">Fix</button>';
    }else if(status_id=='A2'){
      sBtnFix = '<span class=\'btnshowbtn\'>'+status_name+'</span>'+'<button class="btn btn-primary btn-status" style="display:none" title=\'Make status pending result\' onclick="$(this).hide(); fixLabStatus(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\',\'pending_lab_result\',\'A3\');">Pending Result</button>';
    }else if(status_id=='A3'){
      sBtnFix = '<span class=\'btnshowbtn\'>'+status_name+'</span>'+' <button class="btn btn-primary btn-status" style="display:none" title=\'Make it : Specimen Collect Pending\' onclick="$(this).hide(); fixLabStatus(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\',\'specimen_collect_pending\',\'A2\');">Pending Specimen</button><button class="btn btn-primary btn-status" style="display:none" title=\'Make it : Lab Result Complete\' onclick="$(this).hide(); fixLabStatus(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\',\'force_complete_lab\',\'A4\');">Complete</button>';
    }else if(status_id=='A4'){
      sBtnFix = '<span class=\'btnshowbtn\'>'+status_name+'</span>'+' <button style=\'display:none\' class="btn btn-primary btn-status" title=\'Revoke Result Lab and Repeat enter lab result again\' onclick="$(this).hide(); fixLabStatus(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\',\'revoke_order_status\',\'A3\');">Revoke</button>';
    }else{
      sBtnFix = status_name;
    }

    isRequireFix = (request_lab_by=="" || request_lab_by=="NULL" || request_lab_by==null);

    if(isRequireFix){
      sFixUBtn = '<button class="btn btn-primary" onclick="fixLabOrder(\''+uid+'\',\''+collect_date+'\',\''+collect_time+'\',\'fix_missing_user\',\''+((wait_lab_result=='Unknown')?'0':'')+'\');" >Get User</button>';
    }else{
      sFixUBtn = request_lab_by+" ("+requested_room_no+")<br/><span style='font-size:smaller'>"+time_confirm_order+"</span>";
    }
*/



    var txt_row = '<tr class="r_data" id="r'+lab_order_id+'" data-id="'+lab_order_id+'" data-uid="'+uid+'" data-coldate="'+collect_date+'" data-coltime="'+collect_time+'"  data-q="'+queue+'" data-room="'+room_no+'" data-prevroom="'+prev_room+'"   >' ;
    txt_row += '<td><b>'+lab_order_id+'</b></td>';
    txt_row += '<td>'+uid+' ('+clinic_name+') <span class="badge badge-success" onclick="openNewBlankLabOrder(\''+uid+'\');">new order</span></td>';
    txt_row += '<td>'+collect_date+' '+collect_time+'</td>';
    /*
    txt_row += '<td><div id="s'+lab_order_id+'">'+sBtnFix+'</div></td>';
    txt_row += '<td>'+sFixUBtn+'</td>';
    */
    txt_row += '<td>'+status_name+'</td>';
    txt_row += '<td>'+request_lab_by+'<br/><span style="font-size:smaller">'+time_confirm_order+'</span></td>';
    txt_row += '<td><span style="font-size:smaller">'+lab_order_note+'</span></td>';

    txt_row += '<td><span class="spanproj">Project : </span><input data-odata="'+projid+'" class="txtProjId inpproj" size="5" value="'+projid+'"  /></br><span class="spanproj">PID : </span><input class="txtPid inpproj" size="5" data-odata="'+pid+'" value="'+pid+'" /></br><span class="spanproj">Visit : </span><input data-odata="'+vid+'" class="txtVid inpproj" size="5" value="'+vid+'"  /></br><span class="spanproj">TP : </span><input data-odata="'+timepointid+'" class="txtTimepointid inpproj" size="5" value="'+timepointid+'"  /></br><button class="btn btn-primary btnSaveProj" style="display:none" onclick="saveLabProject(this,\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\');">Save</button></td>';
    txt_row += '<td>'+wait_lab_result+'<br>'+send_queue_txt+'</td>';
    txt_row += '<td>';
    txt_row += '<button class="btn btn-primary" type="button" onclick="openLabOrder(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\');"> <b> Lab Order</b></button><br/><span style="font-size:smaller">'+time_lab_order_pmt+'</span>';
    txt_row += '</td>';
    txt_row += '<td>';
    txt_row += '<button class="btn btn-warning" type="button" onclick="openSpecimenCollect(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\');"> <b> Specimen Collected</b></button><br/><span style="font-size:smaller">'+time_specimen_collect+'</span>';
    txt_row += '</td>';
    txt_row += '<td>';

    if(status_id != "C")
    txt_row += '<button class="btn btn-info" type="button" onclick="openLabResult(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\');"> <b> Lab Result </b></button><br/><span style="font-size:smaller">'+time_lab_report_confirm+'</span>';
    else
    txt_row += 'Cancel Order';


    txt_row += '</td>';

    txt_row += '</tr">';
    $("#tbl_lab_order_list tbody").append(txt_row);

}
$("#tbl_lab_order_list tbody").on("dblclick",".btnshowbtn",function(){
  $(this).parent().find(".btn-status").toggle();
});



function checkQueueExist(labOrderID){
  let sUid = $('.r_data#r'+labOrderID).attr('data-uid');
  let sColdate = $('.r_data#r'+labOrderID).attr('data-coldate');
  let sColtime = $('.r_data#r'+labOrderID).attr('data-coltime');

  var aData = {
      u_mode:"check_queue_exist",
      uid:sUid,
      coldate: sColdate,
      coltime: sColtime
  };
//  console.log("enterhere01 "+sUid+sColdate+sColtime);
  callAjax("lab/db_lab_test_order.php",aData,function(rtnObj,aData){
      if(rtnObj.res == 1){
        sendQueueDlg(labOrderID);
      //  console.log("enterhere02 "+sUid+sColdate+sColtime);
      }
      else{
        createExtraQueue(labOrderID);
    //    console.log("enterhere03 "+sUid+sColdate+sColtime);
      }
    });// call ajax

}

function createExtraQueue(labOrderID){
  let sUid = $('.r_data#r'+labOrderID).attr('data-uid');
  let sColdate = $('.r_data#r'+labOrderID).attr('data-coldate');
  let sColtime = $('.r_data#r'+labOrderID).attr('data-coltime');

  var aData = {
      u_mode:"q_create_extra",
      uid:sUid,
      qtype: '2',
      coldate: sColdate,
      coltime: sColtime,
      qprefix: 'L',
      room_no: '24'
  };
  callAjax(window.location.origin+"/pribta21/queue_a.php",aData,function(rtnObj,aData){
      if(rtnObj.res == 1){
        $.notify("Create Extra Queue "+rtnObj.q);
        sendQueueDlg(labOrderID, rtnObj.q);
      }
      else{
        $.notify("Fail to create Extra Queue.", "error");
        if(rtnObj.msg != ""){
            $.notify(rtnObj.msg, "error");
        }
      }
    });// call ajax
}
function sendQueueDlg(labOrderID, queue){
  var sProjId = $(".txtProjId").val();
  let uid = $('.r_data#r'+labOrderID).attr('data-uid');
  let q = $('.r_data#r'+labOrderID).attr('data-q');
  if(q == "" || q == 'null') q = queue;

  //console.log("q: "+q+"/"+queue);
  let room = $('.r_data#r'+labOrderID).attr('data-room');
  let prevroom = $('.r_data#r'+labOrderID).attr('data-prevroom');

  prevroom = 'last';

  let sUrl = "lab/mnu_lab_queue_dlg.php?uid="+uid+"&q="+q+"&selroom="+prevroom+"&projid="+sProjId;
  showDialog(sUrl," ส่งต่อคิว | Send Queue ["+uid+"]",'700','95%',"",function(sResult){
  },false,"");

}



function setLabStatus(status_id, lab_order_id, uid,collect_date, collect_time){

  sBtnFix = ""; sFixUBtn=""; status_name="Lab Confirm Pending";
  if(status_id=='A0' || status_id=='A1' ||status_id=='B0'){
    sNewStat = 'A2';
    if(status_id=='B0') {
      sNewStat ='A3'; status_name="Payment Pending";
    }
    sBtnFix = status_name+' <button class="btn btn-primary" onclick="fixLabStatus(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\',\'fix_order_status\',\''+sNewStat+'\');">Fix</button>';
  }else if(status_id=='A2'){
    status_name = "Specimen Collect Pending";
    sBtnFix = '<span class=\'btnshowbtn\'>'+status_name+'</span>'+'<button class="btn btn-primary btn-status" style="display:none" title=\'Make status pending result\' onclick="$(this).hide(); fixLabStatus(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\',\'pending_lab_result\',\'A3\');">Pending Result</button>';
  }else if(status_id=='A3'){
    status_name = "Lab Result Pending";
    sBtnFix = '<span class=\'btnshowbtn\'>'+status_name+'</span>'+' <button class="btn btn-primary btn-status" style="display:none" title=\'Make it : Specimen Collect Pending\' onclick="$(this).hide(); fixLabStatus(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\',\'specimen_collect_pending\',\'A2\');">Pending Specimen</button><button class="btn btn-primary btn-status" style="display:none" title=\'Make it : Lab Result Complete\' onclick="$(this).hide(); fixLabStatus(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\',\'force_complete_lab\',\'A4\');">Complete</button>';
  }else if(status_id=='A4'){
    status_name = "Lab Result Complete";
    sBtnFix = '<span class=\'btnshowbtn\'>'+status_name+'</span>'+' <button style=\'display:none\' class="btn btn-primary btn-status" title=\'Revoke Result Lab and Repeat enter lab result again\' onclick="$(this).hide(); fixLabStatus(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\',\'revoke_order_status\',\'A3\');">Revoke</button>';
  }else{
    sBtnFix = status_name;
  }

  //console.log(lab_order_id+"sbtnfix: "+sBtnFix);

  $(document).find('#s'+lab_order_id).html(sBtnFix);
  //$('#tbl_lab_order_list #s'+lab_order_id).html(sBtnFix);

}

function fixLabStatus(sUid,sColDate,sColTime,sUmode,labStatus){
  if(sUmode=='revoke_order_status'){
    if(confirm('If you revoke Complete Lab, all result will removed. Do you want to continue?\r\n ถ้าหากทำการยกเลิกแล๊บที่สมบูรณ์แล้ว ผลแล๊บทั้งหมดจะถูกเอาออก ยืนยันที่จะทำต่อ?')){

    }else{
      return;
    }

  }
    var aData={
        u_mode:sUmode,
        uid:sUid,
        coldate:sColDate,
        coltime:sColTime,
        labstat:labStatus
    }
    save_data_ajax(aData,"lab/j_db_fix_lab.php",fixLabStatus_Complete);
}
function fixLabStatus_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      alert("Done.");
    }
}

function fixLabOrder(sUid,sColDate,sColTime,sUmode,isWait){
    var aData={
        u_mode:sUmode,
        uid:sUid,
        coldate:sColDate,
        coltime:sColTime,
        iswait:isWait
    }
    save_data_ajax(aData,"lab/j_db_fix_lab.php",fixLabOrder_Complete);
}
function fixLabOrder_Complete(flagSave, rtnDataAjax, aData){

    if(flagSave){
      alert("Done. Please click reload.");
    }
}

function saveLabProject(evObj,sUid, collect_date, collect_time){
    objTr = $(evObj).closest("tr");
    sProjId = $(objTr).find(".txtProjId").val();
    sPid = $(objTr).find(".txtPid").val();
    sVid = $(objTr).find(".txtVid").val();
    sTPid = $(objTr).find(".txtTimepointid").val();

     var aData={
        u_mode:"save_lab_project",
        uid:sUid,
        coldate:collect_date,
        coltime:collect_time,
        pid:sPid,
        projid:sProjId,
        vid:sVid,
        tpid:sTPid
    }
    save_data_ajax(aData,"lab/db_lab_save.php",function(flagSave, rtnDataAjax, aData){
      if(flagSave){
        $(evObj).hide();
        $(objTr).find(".txtProjId").attr("data-odata",aData.projid);
        $(objTr).find(".txtPid").attr("data-odata",aData.pid);
        $(objTr).find(".txtVid").attr("data-odata",aData.vid);
      }else{

      }
    });
}


function openLabOrder(uid, collect_date, collect_time){ //  openLabOrder
  /*
  var page = "p_lab_order";
  if(collect_date == today_date) page = "p_lab_order_edit";
*/
var page = "p_lab_order_edit";
  //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
  var link = page+".php?uid="+uid+"&collect_date="+collect_date+"&collect_time="+collect_time;
  $("#div_lab_data_lab_order").html("");
  $("#div_lab_data_lab_order").load("lab/"+link, function(){
    showDataLabOrderDiv("lab_order");
  });
}

function openSpecimenCollect(uid, collect_date, collect_time){ //  openSpecimenCollect

  var page = "p_lab_specimen_edit";
/*
  var page = "p_lab_specimen";
  if(collect_date == today_date) page = "p_lab_specimen_edit";
*/
  var link = page+".php?uid="+uid+"&collect_date="+collect_date+"&collect_time="+collect_time;

  $("#div_lab_data_specimen_collect").html("");
  $("#div_lab_data_specimen_collect").load("lab/"+link, function(){
    showDataLabOrderDiv("specimen_collect");
  });
}

function openLabResult(uid, collect_date, collect_time){ //  openLabResult
//  console.log("open "+uid+" / "+collect_date);
  var link = "p_lab_report.php?uid="+uid+"&collect_date="+collect_date+"&collect_time="+collect_time;

  $("#div_lab_data_lab_result").html("");
  $("#div_lab_data_lab_result").load("lab/"+link, function(){
    showDataLabOrderDiv("lab_result");
  });


}



function sendQueue(labOrderID){ //  openLabOrder
  //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
    var roomNo = $("#sel_"+labOrderID).val();
    var aData = {
        u_mode:"send_queue_by_lab",
        lab_order_id:labOrderID,
        room_no:roomNo
    };
    save_data_ajax(aData,"lab/db_lab_test_order.php",sendQueue_Complete);
  }

  function sendQueue_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      $.notify("Send Queue completed", "success");
      searchData_LabOrder();
    }
  }





/*

function sendQueue(labOrderID){ //  openLabOrder
  //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
    var aData = {
        u_mode:"send_queue_to_nurse",
        lab_order_id:labOrderID
    };
    save_data_ajax(aData,"lab/db_lab_test_order.php",sendQueue_Complete);
  }

  function sendQueue_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      $.notify("Send Queue to Nurse Counter complete", "success");
      searchData_LabOrder();
    }
  }
*/




  function selectSaleOption(){
    var aData = {
        u_mode:"select_setting_list",
        setting_choice: "sale_option"
    };
    save_data_ajax(aData,"lab/db_lab_setting.php",selectSaleOptionComplete);
  }

  function selectSaleOptionComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave);
    if(flagSave){
    //   v_sale_option ="<select id='sel_sale_option_xxx'>";
       v_sale_option ="";
      if(rtnDataAjax.datalist.length > 0){
        var datalist = rtnDataAjax.datalist;
          for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            v_sale_option += "<option value='"+dataObj.id+"'>";
            v_sale_option += dataObj.name+" ["+dataObj.id+"]";
            v_sale_option += "</option>";
          }//for
      //    v_sale_option +="</select>";
      }
      else{
        $.notify("No sale option found.", "info");
      }
    }
  }




  function selectLaboratoryOption(){
    var aData = {
        u_mode:"select_setting_list",
        setting_choice: "laboratory"
    };
    save_data_ajax(aData,"lab/db_lab_setting.php",selectLaboratoryOptionComplete);
  }

  function selectLaboratoryOptionComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave);
    if(flagSave){
       //v_laboratory_option ="<select id='sel_laboratory_xxx' class='result-txt'>";
       v_laboratory_option ="";
      if(rtnDataAjax.datalist.length > 0){
        var datalist = rtnDataAjax.datalist;
          for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            v_laboratory_option += "<option value='"+dataObj.id+"'>";
            v_laboratory_option += dataObj.name+" ["+dataObj.id+"]";
            v_laboratory_option += "</option>";
          }//for
        //  v_laboratory_option +="</select>";

          //alert("v_laboratory_option: "+v_laboratory_option);
      }
      else{
        $.notify("No laboratory option found.", "info");
      }
    }
  }






function close_data_group(){
  showDataLabOrderDiv("list");
}


function showDataLabOrderDiv(choice){
  //alert("showDataLabOrderDiv "+choice);
  $(".div-lab-order").hide();
  $("#div_lab_data_"+choice).show();
}

</script>
