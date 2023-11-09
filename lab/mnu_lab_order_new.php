

<!-- The Modal select
New lab order : put uid, collect_date, collect_time, proj_id, proj_pid, proj_visit
-->
<?
$uid= isset($_GET["uid"])?$_GET["uid"]:"";
$staff_order= isset($_GET["staff_order"])?$_GET["staff_order"]:"";
?>

<div>
<b>New Lab Order</b>
</div>

<div class="row mt-0">
  <div class="col-sm-6">

            <div class="row mt-0">
              <div class="col-sm-4">
                <label for="txt_new_uid">UID: </label>
                <input type="text" id="txt_new_uid" class="form-control form-control-sm no-blank" placeholder="UID">

              </div>
              <div class="col-sm-4">
                <label for="txt_new_collect_date">Collect Date: </label>
                <input type="text" id="txt_new_collect_date" class="form-control form-control-sm no-blank" placeholder="Collect Date">
              </div>
              <div class="col-sm-4">
                <label for="txt_new_collect_time">Collect Time: </label>
                <input type="text" id="txt_new_collect_time" class="form-control form-control-sm no-blank" placeholder="Collect Time">
              </div>

       </div>

        <div class="row mt-1">
          <div class="col-sm-4">
            <label for="sel_neworder_proj">Project ID: </label>
            <select id="sel_neworder_proj">
              <option value="" data-is_proj="N" >None</option>
              <option value="IFACT" data-is_proj="Y" data-tp="Y">IFACT</option>
              <option value="IMACT" data-is_proj="Y" data-tp="Y">IMACT</option>
              <option value="POC" data-is_proj="Y" data-tp="">POC (Point of Care)</option>
              <option value="SUT" data-is_proj="N" data-tp="">SUT (Standup Teen)</option>
            </select>
          </div>
          <div class="col-sm-4">
            <label for="txt_new_proj_pid">PID: </label>
            <input type="text" id="txt_new_proj_pid" class="form-control form-control-sm" placeholder="Proj PID">
          </div>
          <div class="col-sm-4">
            <div class='visit-id-comp visit-id-text' >
              <label for="txt_new_proj_visit">Visit: </label>
              <input type="text" id="txt_new_proj_visit" class="form-control form-control-sm visit-comp" placeholder="Proj Visit">
            </div>
            <div class='visit-id-comp visit-id-ddl' style='display:none;'>
              <label for="ddl_new_proj_visit">Visit: </label>
              <select id="ddl_new_proj_visit" class="form-control form-control-sm visit-comp"></select>
              <!--
              <label for="ddl_new_proj_visit_tp">Time point: </label>
              <select id="ddl_new_proj_visit_tp" class="form-control form-control-sm visit-comp"></select>
            -->
            </div>
          </div>
        </div>


  </div>
  <div class="col-sm-6">
    <div class="my-2">
      <label for="txt_new_order_note">Note: </label>
      <textarea id="txt_new_order_note" rows="4" class="form-control form-control-sm" placeholder="Lab Order Note"></textarea>
    </div>
  </div>
</div>





        <div class="my-2">
          <button type="button" id="btn_add_new_lab_order" class="btn btn-primary mr-auto"><i class="fa fa-disk fa-lg" ></i> Add New Lab Order</button>
          <button type="button" id="btn_new_lab_order_close" class="btn btn-secondary" data-dismiss="modal"> <i class="fa fa-times fa-lg" ></i> Close</button>
        </div>



<script>
var arr_timepoint = {};
arr_timepoint['IFACT'] = {};
arr_timepoint['IMACT'] = {};

arr_timepoint['IFACT']['plasma:0'] = 'Plasma: Time Point 0';
arr_timepoint['IFACT']['plasma:0.5'] = 'Plasma: Time Point 0.5';
arr_timepoint['IFACT']['plasma:1'] = 'Plasma: Time Point 1';
arr_timepoint['IFACT']['plasma:2'] = 'Plasma: Time Point 2';
arr_timepoint['IFACT']['plasma:4'] = 'Plasma: Time Point 4';
arr_timepoint['IFACT']['plasma:6'] = 'Plasma: Time Point 6';
arr_timepoint['IFACT']['plasma:8'] = 'Plasma: Time Point 8';
arr_timepoint['IFACT']['plasma:10'] = 'Plasma: Time Point 10';
arr_timepoint['IFACT']['plasma:12'] = 'Plasma: Time Point 12';
arr_timepoint['IFACT']['plasma:24'] = 'Plasma: Time Point 24';
arr_timepoint['IFACT']['PBMC:2'] = 'PBMC: Time Point 2';
arr_timepoint['IFACT']['PBMC:24'] = 'PBMC: Time Point 24';
arr_timepoint['IFACT']['urine:1'] = 'Urine: Time Point 1';
arr_timepoint['IFACT']['urine:2'] = 'Urine: Time Point 2';
arr_timepoint['IFACT']['urine:3'] = 'Urine: Time Point 3';
arr_timepoint['IFACT']['urine:4'] = 'Urine: Time Point 4';
arr_timepoint['IFACT']['urine:5'] = 'Urine: Time Point 5';
arr_timepoint['IFACT']['urine:6'] = 'Urine: Time Point 6';

arr_timepoint['IMACT']['plasma:0'] = 'Plasma: Time Point 0';
arr_timepoint['IMACT']['plasma:0.5'] = 'Plasma: Time Point 0.5';
arr_timepoint['IMACT']['plasma:1'] = 'Plasma: Time Point 1';
arr_timepoint['IMACT']['plasma:1.5'] = 'Plasma: Time Point 1.5';
arr_timepoint['IMACT']['plasma:2'] = 'Plasma: Time Point 2';
arr_timepoint['IMACT']['plasma:2.5'] = 'Plasma: Time Point 2.5';
arr_timepoint['IMACT']['plasma:3'] = 'Plasma: Time Point 3';
arr_timepoint['IMACT']['plasma:4'] = 'Plasma: Time Point 4';
arr_timepoint['IMACT']['plasma:7'] = 'Plasma: Time Point 7';
arr_timepoint['IMACT']['plasma:14'] = 'Plasma: Time Point 14';


var arr_visit = {};
arr_visit['IFACT'] = {};
arr_visit['IMACT'] = {};

arr_visit['IFACT']['BL'] = 'Base Line';
arr_visit['IFACT']['W3'] = 'Week 3';
arr_visit['IFACT']['W9'] = 'Week 9';
arr_visit['IFACT']['W12'] = 'Week 12';

arr_visit['IMACT']['BL'] = 'Base Line';
arr_visit['IMACT']['W2'] = 'Week 2';
arr_visit['IMACT']['W4'] = 'Week 4';
arr_visit['IMACT']['W6'] = 'Week 6';
arr_visit['IMACT']['W8'] = 'Week 8';
arr_visit['IMACT']['W10'] = 'Week 10';
arr_visit['IMACT']['W12'] = 'Week 12';
arr_visit['IMACT']['W16'] = 'Week 16';


$(document).ready(function(){
//  loadVisitTimepoint('IFACT');
<?

$dateData=(new DateTime())->format('Y-m-d');
$dateVal = explode("-", $dateData);
$dateVal[0] = $dateVal[0] + 543;
$today_date = $dateVal[2]."/".$dateVal[1]."/".$dateVal[0];
$today_time =  (new DateTime())->format('H:i:s');
echo "$('#txt_new_collect_date').val('$today_date');";
echo "$('#txt_new_collect_time').val('$today_time');";

?>


/*
var today_date = getTodayDateTH();
var today_time = getTodayTime();
$('#txt_new_collect_date').val(today_date);
$('#txt_new_collect_date').val(today_time);
*/
  $("#txt_new_uid").val("<? echo $uid;?>");
  $("#btn_add_new_lab_order").click(function(){
     addnew_lab_order();
  }); // btn_search_lab_test
  $("#btn_new_lab_order_close").click(function(){
     close_new_lab_order(); // set this function to close this page at the opener
  }); // btn_search_lab_test

  $("#sel_neworder_proj").change(function(){
    var is_proj = $('option:selected',this).data("is_proj");
  //  console.log("value: "+$('option:selected',this).val()+"/"+$(this).val());
    if(is_proj == "Y"){
      if($("#txt_new_uid").val().trim() != ""){
        selectProjPID($(this).val(), $("#txt_new_uid").val().trim() );
      }
    }

    $('.visit-id-comp').hide();
    $('.visit-comp').val('');
    if( $('option:selected',this).attr("data-tp") == 'Y'){
      $('.visit-id-ddl').show();
      loadVisitTimepoint($(this).val());
    }
    else{
      $('.visit-id-text').show();
    }


  }); // sel_neworder_proj

});

function loadVisitTimepoint(projid){
  $('#ddl_new_proj_visit').empty();
  $('#ddl_new_proj_visit').append('<option value="" disabled>-Select-</option>');

  if (typeof arr_visit[projid] !== 'undefined') {
    for (var key in arr_visit[projid]) {
       $('#ddl_new_proj_visit').append('<option value="'+key+'" >'+arr_visit[projid][key]+'</option>');

    }
  }

/*
  $('#ddl_new_proj_visit_tp').empty();
  $('#ddl_new_proj_visit_tp').append('<option value="" disabled>-Select-</option>');
  if (typeof arr_timepoint[projid] !== 'undefined') {
    for (var key in arr_timepoint[projid]) {
      $('#ddl_new_proj_visit_tp').append('<option value="'+key+'" >'+arr_timepoint[projid][key]+'</option>');

    }
  }

  */

}


function selectProjPID(projID, projUID){
//  console.log("selectProjPID "+projID+"/"+projUID);
  var aData = {
      u_mode:"select_proj_pid",
      proj_id:projID,
      uid:projUID
  };
  save_data_ajax(aData,"lab/db_lab_test_order.php",selectProjPIDComplete);

}

function selectProjPIDComplete(flagSave, rtnDataAjax, aData){
//  alert("selectLabTestMenuListComplete flag save is : "+flagSave);
  if(flagSave){
    if(rtnDataAjax.pid != ""){
      $.notify("PID: "+rtnDataAjax.pid+" is found.", "success");
      $("#txt_new_proj_pid").val(rtnDataAjax.pid);
      $("#txt_new_proj_visit").focus();
    }
    else{
      $.notify("No PID found.", "info");
    }
  }
}




function addnew_lab_order(){
  /*
  $(".no-blank").each(function(ix,objx){
  });
  */
  if($("#txt_new_uid").val() == "") {
    $.notify("Please insert UID" , "error");
    return;
  }
  else if(!validateDate($("#txt_new_collect_date").val())) {
    $("#txt_new_collect_date").notify("Wrong date format" , "error");
    return;
  }

  let s_proj_visit = '';
  if($("#ddl_new_proj_visit").is(":visible")){
    s_proj_visit = $("#ddl_new_proj_visit").val();

  }
  else{
    s_proj_visit = $("#txt_new_proj_visit").val();
  }



  var dataObj = {uid:$("#txt_new_uid").val().trim(),
  collect_date:changeToEnDate($("#txt_new_collect_date").val().trim()),
  collect_time:$("#txt_new_collect_time").val().trim(),
  proj_id:$("#sel_neworder_proj").val(),
  proj_pid:$("#txt_new_proj_pid").val().trim(),

  proj_visit:s_proj_visit,
  order_note:$("#txt_new_order_note").val(),
  staff_order:"<? echo $staff_order; ?>"
  };

  var aData = {
      u_mode:"add_blank_lab_order",
      data_obj:dataObj
  };
  save_data_ajax(aData,"lab/db_lab_test_order.php",addnew_lab_orderComplete);

}

function addnew_lab_orderComplete(flagSave, rtnDataAjax, aData){
//  alert("selectLabTestMenuListComplete flag save is : "+flagSave);
  if(flagSave){

    alert("New Lab Order No. is "+rtnDataAjax.lab_order_id);
    $.notify("New Lab Order No. is "+rtnDataAjax.lab_order_id, "success");
    afterInsertBlankLabOrder();
    /*
    let dataObj = aData.data_obj;
    var aData = {
        u_mode:"q_create_extra",
        uid:dataObj.uid,
        qtype: '2',
        coldate: dataObj.collect_date,
        coltime: dataObj.collect_time,
        qprefix: 'L',
        room_no: '24'
    };
    //startLoad(btn_add, btn_add.next(".spinner"));
    callAjax(window.location.origin+"/pribta21/queue_a.php",aData,function(rtnObj,aData){
    //  endLoad(btn_add, btn_add.next(".spinner"));
        if(rtnObj.res == 1){
          $.notify("Extra Queue "+rtnObj.q);
        }
        afterInsertBlankLabOrder(); // set this function to do after this to page source that open this page

      });// call ajax
     */
  }
}



</script>
<? include_once("../inc_foot_include.php"); ?>
<? include_once("../function_js/js_fn_validate.php"); ?>
