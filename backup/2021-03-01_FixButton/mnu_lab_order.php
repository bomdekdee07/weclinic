<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

include_once("inc_auth.php"); // set permission view, update, delete
include_once("../a_app_info.php");

?>


<script>

//**** lab  var
var v_sale_option = "";
var v_laboratory_option = "";


var cur_lab_order_id = ""; // current lab order id
var cur_lab_uid = ""; // current uid
var cur_lab_collect_date = ""; // current collect date
var cur_lab_collect_time = ""; // current collect time
//****

var sel_room_txt = "";

</script>


<div id='div_lab_data_list' class='div-lab-order my-0'>
  <div class="row mt-0">
    <div class="col-sm-12">
      <h4><i class="fa fa-database fa-lg" ></i> <b>Lab Order Summary</b> </h4>
    </div>
  </div>

  <div class="row mt-0">

    <div class="col-sm-10">
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
             <th>Lab Order ID</th>
             <th>UID (Clinic)</th>
             <th>Visit Date/Time</th>
             <th>Lab Order Status</th>
             <th>Requested By (Room)</th>
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




<script>

$(document).ready(function(){

  setRoomClinic();
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


});

function closeToLabOrderList(){
  showDataLabOrderDiv("list");
}

function searchData_LabOrder(){
  var aData = {
      u_mode:"select_lab_order_list",
      txt_search:$("#txt_search_lab_order").val().trim()
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
             dataObj.lab_order_id, dataObj.uid, dataObj.collect_date,dataObj.collect_time,
             dataObj.status_id, dataObj.status_name, dataObj.wait_lab_result,
              dataObj.request_by, dataObj.room_no, dataObj.clinic_type ,dataObj.time_confirm_order,dataObj.time_specimen_collect,dataObj.time_lab_order_pmt,dataObj.time_lab_report_confirm

            );

        }//for

        $('#tbl_lab_order_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }
  }
}



function addRowData_LabOrder(lab_order_id,  uid, collect_date, collect_time,
  status_id, status_name,wait_lab_result, request_lab_by, requested_room_no, clinic_type,time_confirm_order,time_specimen_collect,time_lab_order_pmt,time_lab_report_confirm){


    var  clinic_name = "Unknown";
    if(clinic_type == "T"){
      clinic_name = "<b>Tangerine</b>";
    }
    else if(clinic_type == "P"){
      clinic_name = "Pribta";
    }


    if(wait_lab_result=='1'){
      wait_lab_result = '<span class="text-success"><b>Yes</b></span><br>';
    //  wait_lab_result +='<button class="btn btn-info" type="button" onclick="sendQueue(\''+lab_order_id+'\');"> Send Queue</button>';
      wait_lab_result +='<select id="sel_'+lab_order_id+'">';

      if(requested_room_no != '1')
      wait_lab_result +='<option value="'+requested_room_no+'">ห้องตรวจที่สั่ง Lab Order ['+requested_room_no+']</option>';
      else
      wait_lab_result +='<option value="1">ไม่ระบุห้องตรวจมา ส่งกลับ Reception [1]</option>';

      wait_lab_result +='<option value="26">ห้องการเงิน (Cashier) [26]</option>';
      if(clinic_type == "T"){
        wait_lab_result +='<option value="29">เคาท์เตอร์พยาบาล Tengerine </option>';
      }
      else if(clinic_type == "P"){
        wait_lab_result +='<option value="25">เคาท์เตอร์พยาบาล Pribta </option>';
      }

      wait_lab_result +=sel_room_txt;
      wait_lab_result +='</select>';

      wait_lab_result +='<button class="btn btn-info" type="button" onclick="sendQueue(\''+lab_order_id+'\');"> Send Queue</button>';



    }
    else if(wait_lab_result=='0') wait_lab_result = '<span class="text-danger"><b>No</b></span>';
    else if(wait_lab_result=='2') wait_lab_result = '<span class="text-primary"><b>Sent Queue</b></span>';
    else wait_lab_result = 'Unknown';

    sBtnFix = ""; sFixUBtn="";
    if(status_id=='A0' || status_id=='A1' ||status_id=='B0'){ 
      sNewStat = 'A2';
      if(status_id=='B0') sNewStat ='A3';

      sBtnFix = '<button class="btn btn-primary" onclick="fixLabStatus(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\',\'fix_order_status\',\''+sNewStat+'\');">Fix</button>';
    }

    isRequireFix = (request_lab_by=="" || request_lab_by=="NULL" || request_lab_by==null);

    if(isRequireFix){
      sFixUBtn = '<button class="btn btn-primary" onclick="fixLabOrder(\''+uid+'\',\''+collect_date+'\',\''+collect_time+'\',\'fix_missing_user\',\''+((wait_lab_result=='Unknown')?'0':'')+'\');" >Get User</button>';
    }else{
      sFixUBtn = request_lab_by+" ("+requested_room_no+")<br/><span style='font-size:smaller'>"+time_confirm_order+"</span>";
    }

    var txt_row = '<tr class="r_data" id="r'+lab_order_id+'" data-id="'+lab_order_id+'" >' ;
    txt_row += '<td width="10%"><b>'+lab_order_id+'</b></td>';
    txt_row += '<td width="10%">'+uid+' ('+clinic_name+')</td>';
    txt_row += '<td width="10%">'+collect_date+' '+collect_time+'</td>';
    txt_row += '<td width="10%">'+status_name+' '+sBtnFix+'</td>';
    txt_row += '<td width="20%">'+sFixUBtn+'</td>';
    txt_row += '<td width="15%">'+wait_lab_result+'</td>';
    txt_row += '<td>';
    txt_row += '<button class="btn btn-primary" type="button" onclick="openLabOrder(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\');"> <b> Lab Order</b></button><br/><span style="font-size:smaller">'+time_lab_order_pmt+'</span>';
    txt_row += '</td>';
    txt_row += '<td>';
    txt_row += '<button class="btn btn-warning" type="button" onclick="openSpecimenCollect(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\');"> <b> Specimen Collected</b></button><br/><span style="font-size:smaller">'+time_specimen_collect+'</span>';
    txt_row += '</td>';
    txt_row += '<td>';
    txt_row += '<button class="btn btn-info" type="button" onclick="openLabResult(\''+uid+'\', \''+collect_date+'\', \''+collect_time+'\');"> <b> Lab Result</b></button><br/><span style="font-size:smaller">'+time_lab_report_confirm+'</span>';
    txt_row += '</td>';

    txt_row += '</tr">';
    $("#tbl_lab_order_list tbody").append(txt_row);

}

function fixLabStatus(sUid,sColDate,sColTime,sUmode,labStatus){
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
      alert("Done. Please goto Specimen Collect");
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

function openLabOrder(uid, collect_date, collect_time){ //  openLabOrder
  var page = "p_lab_order";
  if(collect_date == today_date) page = "p_lab_order_edit";

  //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
  var link = page+".php?uid="+uid+"&collect_date="+collect_date+"&collect_time="+collect_time;
  $("#div_lab_data_lab_order").html("");
  $("#div_lab_data_lab_order").load("lab/"+link, function(){
    showDataLabOrderDiv("lab_order");
  });
}

function openSpecimenCollect(uid, collect_date, collect_time){ //  openSpecimenCollect
  //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
  var page = "p_lab_specimen";
  if(collect_date == today_date) page = "p_lab_specimen_edit";

  var link = page+".php?uid="+uid+"&collect_date="+collect_date+"&collect_time="+collect_time;

  $("#div_lab_data_specimen_collect").html("");
  $("#div_lab_data_specimen_collect").load("lab/"+link, function(){
    showDataLabOrderDiv("specimen_collect");
  });
}

function openLabResult(uid, collect_date, collect_time){ //  openLabResult
  //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
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
      $.notify("Send Queue to Nurse Counter complete", "success");
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

  // set all available rooms to send queue
  function setRoomClinic(){ //  setRoomClinic
    var aData = {
        u_mode:"select_clinic_room_list"
    };
    save_data_ajax(aData,"lab/db_lab_test_order.php",setRoomClinic_Complete);
  }

  function setRoomClinic_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){

      if(rtnDataAjax.datalist.length > 0){

        var datalist = rtnDataAjax.datalist;
        datalist.forEach(function (itm) {
          sel_room_txt += "<option value='"+itm.id+"'>"+itm.name+" ["+itm.id+"]</option>";
        });// foreach

      }

    }
  }//setRoomClinic_Complete




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
