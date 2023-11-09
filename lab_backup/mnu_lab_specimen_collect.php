<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

include_once("inc_auth.php"); // set permission view, update, delete

include_once("../a_app_info.php");

?>


<script>

//**** lab specimen collect  var

var cur_specimen_collect_uid = ""; // current uid in specimen collect
var cur_specimen_collect_date = ""; // current collect date in specimen collect
var cur_specimen_collect_time = ""; // current collect time in specimen collect
//****

</script>


<div id='div_spc_list' class='div-specimen-collect my-0'>
  <div class="row mt-0">
    <div class="col-sm-12">
      <h4><i class="fa fa-vials fa-lg" ></i> <b>Specimen Collect</b> <button class="btn btn-primary btn-sm" type="button" id="btn_reload_specimen_collect"><i class="fa fa-sync" ></i> Reload</button></h4>
    </div>
  </div>
<!--
  <div class="row mt-0">
    <div class="col-sm-10">
      <label for="txt_search_specimen_collect">คำค้นหา:</label>
      <input type="text" id="txt_search_specimen_collect" class="form-control form-control-sm" placeholder="พิมพ์คำค้นหา Lab ID หรือ Lab Test Name">
    </div>
     <div class="col-sm-2">
       <label for="btn_search_specimen_collect" class="text-white">.</label>
      <button class="btn btn-primary btn-sm form-control form-control-sm " type="button" id="btn_search_specimen_collect"><i class="fa fa-search" ></i> ค้นหา</button>
     </div>
   </div>
-->
   <div class="mt-2"  style="min-height: 300px; border:1px solid grey;">
     <table id="tbl_lab_specimen_collect_list" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
         <thead>
           <tr>
             <th>Queue</th>

             <th>UID</th>
             <th>Wait Lab Result?</th>
             <th>Confirm Lab Order</th>
             <th>Confirmed By (Room)</th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_lab_specimen_collect -->

<div id='div_spc_detail' class="div-specimen-collect my-0" style="display:none;">



</div> <!-- div_lab_spc -->



<script>
//var cur_qid = ""; var cur_qrd = ""; var cur_wait_lab_result="";

$(document).ready(function(){
  searchData_SpecimenCollect();
  $("#btn_reload_specimen_collect").click(function(){
     searchData_SpecimenCollect();
  }); // btn_search_specimen_collect



});





function searchData_SpecimenCollect(){

  var aData = {
      u_mode:"select_specimen_collect"
  };
  save_data_ajax(aData,"lab/db_lab_test_specimen.php",searchData_SpecimenCollect_Complete);

/*
  var aData = {
      u_mode:"select_specimen_collect_queue"
  };
  save_data_ajax(aData,"lab/db_lab_test_specimen_collect.php",searchData_SpecimenCollect_Complete);

*/
}

function searchData_SpecimenCollect_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $('.rspc').remove(); // row data list
    var txt_row="";

    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            //sDob = dataObj.date_o_b+"/"+dataObj.mon_o_b+"/"+dataObj.y_o_b
            addRowData_SpecimenCollectList(
             dataObj.qid, dataObj.uid, dataObj.wait_lab_result, dataObj.c_date, dataObj.c_time, dataObj.cf_time, dataObj.doctor_name,dataObj.qrd,dataObj.staff_order_room,dataObj.fname,dataObj.sname,dataObj.dob
            );
        }//for

        $('#tbl_lab_specimen_collect_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }
  }
}



function addRowData_SpecimenCollectList(qid, uid, wait_lab_result, collect_date, collect_time, confirm_order_time, confirm_by, qrd, order_room,fname,sname,dob){
  dob_th = changeToThaiDate(dob);
  isRequireFix = false;

  var wait_txt = "";
  if(wait_lab_result=='1') wait_txt = '<span class="text-success"><b>Yes</b></span>';
  else if(wait_lab_result=='0') wait_txt = '<span class="text-danger"><b>No</b></span>';
  else {
    wait_txt = 'Unknown';
    isRequireFix = true;
  }
  isRequireFix = (confirm_by=="" || confirm_by=="NULL" || confirm_by==null);
  sFixBtn = "";

  if(isRequireFix){
    sFixBtn = '<button class="btn btn-primary" onclick="fixLabOrder(\''+uid+'\',\''+collect_date+'\',\''+collect_time+'\',\'fix_missing_user\',\''+((wait_txt=='Unknown')?'0':'')+'\');" >Get User</button>';
  }else{
    sFixBtn = confirm_by+" ("+order_room+")";
  }

    var txt_row = '<tr class="rspc" id="rspc'+qid+uid+'"  data-uid="'+uid+'" data-collect_date="'+collect_date+'" data-collect_time="'+collect_time+'" data-qrd="'+qrd+'" data-wait_lab_result="'+wait_lab_result+'">' ;

    txt_row += '<td width="15%">';
    txt_row += '<button class="btn btn-primary" type="button" ';
    if(!isRequireFix) txt_row += ' onclick="openSPC_div(\''+uid+'\',\''+collect_date+'\',\''+collect_time+'\');">';
    txt_row += '<b># '+qid+'</b></button></td>';
    txt_row += '<td width="25%" ><b>'+uid+'</b><br/>'+fname+' '+sname+' ('+dob_th+' | '+dob+')</td>';
    txt_row += '<td width="10%" >'+wait_txt+'</td>';
    txt_row += '<td width="25%" class="text-primary">'+confirm_order_time+'</td>';
    txt_row += '<td >'+sFixBtn+'</td>';

    txt_row += '</tr">';
    $("#tbl_lab_specimen_collect_list tbody").append(txt_row);

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

function closeToLabOrderList(){
  showSpecimenCollectDiv("list");
}

function openSPC_div(uid, collectDate, collectTime){ // open specimen collect
  var link = "p_lab_specimen_edit.php?uid="+uid+"&collect_date="+collectDate+"&collect_time="+collectTime;
  $("#div_spc_detail").html("");
  $("#div_spc_detail").load("lab/"+link, function(){
    showSpecimenCollectDiv("detail");
  });
  showSpecimenCollectDiv("detail");
}


function showSpecimenCollectDiv(choice){
  //alert("showSpecimenCollectDiv "+choice);
  $(".div-specimen-collect").hide();
  $("#div_spc_"+choice).show();
}


function setLabStatus(lab_order_status, lab_order_id,
  uid, collect_date,collect_time){

}




</script>
