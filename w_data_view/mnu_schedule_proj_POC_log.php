<?
include_once("../in_auth.php");



$txt_thead = '  <thead>
            <tr>
              <th class="bg-primary text-white fixleft-col"><i class="fa fa-user" ></i> PID/UIC</th>
              <th>UID</th>
              <th>วันเข้าโครงการ</th>
              <th>กลุ่ม</th>
              <th>เพศสภาพ</th>
              <th>ชื่อ-นามสกุล</th>
              <th>โทร</th>
              <th><b>Extra</b></th>
              <th class="poc_blue"><b>M0 Schedule</b></th>
              <th class="poc_green"><b>M0 Visit</b></th>
              <th class="poc_lightblue"><b>M0 Note</b></th>

              <th class="poc_blue"><b>M1 Schedule</b></th>
              <th class="poc_green"><b>M1 Visit</b></th>
              <th class="poc_lightblue"><b>M1 Note</b></th>
              <th class="poc_blue"><b>M3 Schedule</b></th>
              <th class="poc_green"><b>M3 Visit</b></th>
              <th class="poc_lightblue"><b>M3 Note</b></th>
              <th class="poc_blue"><b>M6 Schedule</b></th>
              <th class="poc_green"><b>M6 Visit</b></th>

              <th class="poc_lightblue"><b>M6 Note</b></th>
              <th class="poc_blue"><b>M9 Schedule</b></th>
              <th class="poc_green"><b>M9 Visit</b></th>
              <th class="poc_lightblue"><b>M9 Note</b></th>
              <th class="poc_blue"><b>M12 Schedule</b></th>
              <th class="poc_green"><b>M12 Visit</b></th>
              <th class="poc_lightblue"><b>M12 Note</b></th>
              <th class="extra-desc"><b>Extra Visit Desc</b></th>
            </tr>
          </thead>
          ';
?>

<style>
/*
div.poc_schedule {
  background-color: #eee;
  width: 100%;
  height: 500px;
  overflow: scroll;
}
*/


.poc_green{
  background-color: #A3D900;
}.poc_yellow{
  background-color: #FFFF99;
}.poc_blue{
  background-color: #BBDDFF;
}.poc_lightblue{
  background-color: #BFFFFF;
}.poc_pink{
  background-color: #FF9999;
}.poc_purple{
  background-color: #9370DB;
}


.tbl-fixleft {
    border-collapse: collapse;
    width: 100%;
    overflow-x: scroll;
    display: block;
}

table.tbl-fixleft thead {
    background-color: #EFEFEF;
    border-bottom: 3px solid #0066cc;
}
table.tbl-fixleft thead, table.tbl-fixleft tbody {
    display: block;
}

table.tbl-fixleft tbody {
    overflow-y: scroll;
    overflow-x: hidden;
    height: 500px;
}


table.tbl-fixleft td, table.tbl-fixleft th {
    min-width: 150px;
    height: 25px;
    border: dashed 1px lightblue;
}

.extra-desc {
    min-width: 300px;
    background-color: #EEE;
}


table.tbl-fixleft thead tr th:first-child,
table.tbl-fixleft tbody tr td:first-child{
    position:relative;
    z-index:10;
    background-color:#EFEFEF;
}



</style>

<div class="card div-main-poc" id="div_schedule_poc_list">
  <div class="card-body">
    <div class="card-title">
      <div class="row">
         <div class="col-sm-6">
           <div><h5><i class="fa fa-table fa-lg" ></i> ข้อมูลตารางนัดหมาย Point of Care <u><span id="txt_poc_group"></span></u></h5></div>
         </div>
         <div class="col-sm-2">

         </div>
         <div class="col-sm-2">

           <?
 if($s_section_id == "DATA"){
   $btn_referral = '
   <button class="btn btn-primary form-control" type="button" id="btn_poc_export_referral">
     <i class="fa fa-file-export" ></i> Referral Export
   </button>
   ';
   echo $btn_referral;
 }
           ?>

         </div>
         <div class="col-sm-2">
           <button class="btn btn-primary form-control" type="button" id="btn_poc_export_all">
             <i class="fa fa-file-export" ></i> Data Export (All)
           </button>
         </div>
    </div>



    <div class="row my-2">
       <div class="col-sm-4">
         <label for="sel_project_group_id">เลือกกลุ่ม:</label>
         <select id="sel_project_group_id" class="form-control" >
           <option value="%" selected >ทั้งหมด</option>
           <option value="001">กลุ่ม 1 เริ่ม PrEP</option>
           <option value="002">กลุ่ม 2 กิน PrEP</option>
           <option value="003">กลุ่ม 3 ไม่กิน PrEP</option>
           <option value="004">กลุ่ม 4 ผลเลือดบวก</option>
         </select>
       </div>
       <div class="col-sm-6">
         <label for="txt_search_poc_schedule">คำค้นหา: (PID, UIC, UID)</label>
         <input type="text" id="txt_search_poc_schedule" class="form-control" placeholder="พิมพ์คำค้นหา PID, UIC, UID">
       </div>
       <div class="col-sm-2">
         <label for="btn_schedule_poc_search" class="text-light">.</label>
         <button class="btn btn-primary form-control" type="button" id="btn_schedule_poc_search">
           <i class="fa fa-search" ></i> ค้นหา
         </button>
       </div>
    </div>


    <div id="div_poc_group" class="poc_schedule mt-2">
      <table id="tbl_poc_group"  class="tbl-fixleft ">
          <? echo $txt_thead; ?>
          <tbody>
            <tr class="r_poc_sd"><td colspan=28 align="center"><b><h4>- กรุณาเลือกข้อมูล -</h4></b></td></tr>
          </tbody>
      </table>
      <div class="my-3">
        <!--
        <button id="btn_poc_export_selected" class="btn btn-primary" type="button"> <i class="fa fa-file-export"></i> Data Export Group</button>
      -->
      </div>
    </div>


  </div>
</div>
</div>

<input type="hidden" id="cur_poc_group" >


<script>


$(document).ready(function(){
//  $(".poc_schedule").hide();

  $('.tbl-fixleft').on('scroll', function () {
    $(".tbl-fixleft > *").width($(".tbl-fixleft").width() + $(".tbl-fixleft").scrollLeft());
  });

  // New JavaScript

  //var $stickyHeader = $('table thead tr th:first-child');
  //var $stickyCells = $('table tbody tr td:first-child');

  var $stickyHeader = $('.tbl-fixleft thead tr th:first-child');
  //var $stickyCells = $('.tbl-fixleft tbody tr td:first-child');
  var $stickyCells = $('.fixleft-col');

  $('.tbl-fixleft').on('scroll', function () {
      $stickyHeader.css('left', ($(this).scrollLeft()+'px'));
      $stickyCells.css('left', ($(this).scrollLeft()+'px'));
  });





  $("#btn_schedule_poc_search").click(function(){
    getDataPOC_Schedule();
  });


  $("#btn_poc_export_all").click(function(){
    dataExportAllGroup();
  }); // btn_export_schedule_poc

  $("#btn_poc_export_referral").click(function(){
    dataExportReferral();
  }); // btn_poc_export_referral




  $(".btn_poc_export_selected").click(function(){

      dataExportInGroup($("#cur_poc_group"));
  }); // btn_export_schedule_poc





});

function showDivPOC_Schedule(groupID){
  $(".poc_schedule").hide();
  $("#div_poc_group"+groupID).show();
}


function dataExportInGroup(groupID){
  var lst_data_obj = [];
  lst_data_obj.push(groupID);
  var aData = {
    u_mode: "export_sel_group",
    lst_data:lst_data_obj
  };
  save_data_ajax(aData,"w_data/xls_uid_schedule_select_group.php",dataExportSelectedGroupComplete);

}

function dataExportAllGroup(){
  var lst_data_obj = [];
  lst_data_obj.push('001');
  lst_data_obj.push('002');
  lst_data_obj.push('003');
  lst_data_obj.push('004');

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

function dataExportReferral(){
  var aData = {
    u_mode: "export_poc_referral"
  };
  save_data_ajax(aData,"w_data/xls_poc_referral.php",dataExportReferralComplete);

}
function dataExportReferralComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.proj_id+"/"+rtnDataAjax.link_xls+"'");
  if(flagSave){
    window.open(rtnDataAjax.link_xls, '_blank');
  }
}




function getDataPOC_Schedule(){

  //alert("enter group0 "+groupID);
    var aData = {
      u_mode:"schedule_data",
      txt_search: $("#txt_search_poc_schedule").val().trim(),
      project_group_id:$("#sel_project_group_id").val().trim()
    };
    save_data_ajax(aData,"w_data/db_data_POC.php",getDataPOC_ScheduleComplete);

}


function getDataPOC_ScheduleComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.proj_id+"/"+rtnDataAjax.link_xls+"'");
  if(flagSave){
    var txt_row = "";
    var class_visit_date = "";
    var extra_change_group = "";

    var datalist = rtnDataAjax.datalist;
    if(datalist.length > 0){

      for (i = 0; i < datalist.length; i++) {
        var dataObj = datalist[i];
        class_visit_date = "";
        visit_date = "";
        extra_change_group = "";


        txt_row += '<tr class="r_poc_sd">';
        txt_row += '<td class="fixleft-col bg-primary text-white"><b><button class="btn btn-primary btn-poc-visit" data-uid="'+dataObj.uid+'" data-uic="'+dataObj.uic+'" data-pid="'+dataObj.pid+'"><b>'+dataObj.pid+'</b><br>'+dataObj.uic+'</button></b></td>';

        txt_row += '<td><b>'+dataObj.uid+'</b></td>';
        txt_row += '<td>'+changeToThaiDate(dataObj.enroll)+'</td>';
        txt_row += '<td>'+dataObj.group+'</td>';
        txt_row += '<td>'+dataObj.gender+'</td>';
        txt_row += '<td>'+dataObj.name+'</td>';
        txt_row += '<td>'+dataObj.tel+'</td>';

        if(dataObj.extra_chg == "Y") extra_change_group = "poc_pink";
        txt_row += '<td class="'+extra_change_group+'">'+dataObj.extra_amt+'</td>';

        // schedule_data

        txt_row += '<td class="poc_blue">'+changeToThaiDate(dataObj.M0s_date)+'</td>';
        txt_row += '<td class="'+getVisitClassColor(dataObj.M0st)+'">'+changeToThaiDate(dataObj.M0v_date)+'</td>';
        txt_row += '<td class="poc_lightblue">'+dataObj.M0s_note+'</td>';

        txt_row += '<td class="poc_blue">'+changeToThaiDate(dataObj.M1s_date)+'</td>';
        txt_row += '<td class="'+getVisitClassColor(dataObj.M1st)+'">'+((dataObj.M1st!='10')?changeToThaiDate(dataObj.M1v_date):"ไม่มาตามนัด")+'</td>';
        txt_row += '<td class="poc_lightblue">'+dataObj.M1s_note+'</td>';

        txt_row += '<td class="poc_blue">'+changeToThaiDate(dataObj.M3s_date)+'</td>';
        txt_row += '<td class="'+getVisitClassColor(dataObj.M3st)+'">'+((dataObj.M3st!='10')?changeToThaiDate(dataObj.M3v_date):"ไม่มาตามนัด")+'</td>';
        txt_row += '<td class="poc_lightblue">'+dataObj.M3s_note+'</td>';

        txt_row += '<td class="poc_blue">'+changeToThaiDate(dataObj.M6s_date)+'</td>';
        txt_row += '<td class="'+getVisitClassColor(dataObj.M6st)+'">'+((dataObj.M6st!='10')?changeToThaiDate(dataObj.M6v_date):"ไม่มาตามนัด")+'</td>';
        txt_row += '<td class="poc_lightblue">'+dataObj.M6s_note+'</td>';

        txt_row += '<td class="poc_blue">'+changeToThaiDate(dataObj.M9s_date)+'</td>';
        txt_row += '<td class="'+getVisitClassColor(dataObj.M9st)+'">'+((dataObj.M9st!='10')?changeToThaiDate(dataObj.M9v_date):"ไม่มาตามนัด")+'</td>';
        txt_row += '<td class="poc_lightblue">'+dataObj.M9s_note+'</td>';

        txt_row += '<td class="poc_blue">'+changeToThaiDate(dataObj.M12s_date)+'</td>';
        txt_row += '<td class="'+getVisitClassColor(dataObj.M12st)+'">'+((dataObj.M12st!='10')?changeToThaiDate(dataObj.M12v_date):"ไม่มาตามนัด")+'</td>';
        txt_row += '<td class="poc_lightblue">'+dataObj.M12s_note+'</td>';

        txt_row += '<td style="width:300px;">'+dataObj.extra+'</td>';

        txt_row += '</tr>';

      }//for
      $('.r_poc_sd').remove(); // remove old poc schedule row
    } // datalist length
    else{ // no data list (empty row)
         txt_row += "<tr class='r_poc_sd'><td colspan=15 align='center'>-ยังไม่มีข้อมูล-</td></tr>";
    }

    $('#tbl_poc_group > tbody:last-child').append(txt_row);


    var $stickyHeader = $('.tbl-fixleft thead tr th:first-child');
    //var $stickyCells = $('.tbl-fixleft tbody tr td:first-child');
    var $stickyCells = $('.fixleft-col');

    $('.tbl-fixleft').on('scroll', function () {
        $stickyHeader.css('left', ($(this).scrollLeft()+'px'));
        $stickyCells.css('left', ($(this).scrollLeft()+'px'));
    });

  //  showDivPOC_Schedule(aData.project_group_id);
    $(".btn-poc-visit").click(function(){
    //    alert("gotovisit: "+$(this).data("id"));
        gotoVisit($(this).data("uid"), $(this).data("uic"), $(this).data("pid"));
    }); // btn-poc-visit

      $('#cur_poc_group').val(aData.project_group_id);
      $('#txt_poc_group').html('กลุ่ม: '+aData.project_group_id);

  }
}

function getVisitClassColor(visitStatus){
  if(visitStatus == 0) class_visit_date = "";
  else if (visitStatus == 1) class_visit_date = "poc_green";
  else if (visitStatus == 10) class_visit_date = "poc_purple";
  else if (visitStatus == 11) class_visit_date = "poc_pink";
  else class_visit_date = "poc_yellow";

  return class_visit_date;
}


function gotoVisit(uid, uic, pid){
  <?
    if(!isset($auth["data"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }
  ?>

    goVisitList3("POC", "Point of Care", uid, uic, pid);

}






</script>
