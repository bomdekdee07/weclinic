
<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}
include_once("../a_app_info.php");

$lab_order_id= isset($_GET["lab_order_id"])?$_GET["lab_order_id"]:"";
$uid= isset($_GET["uid"])?$_GET["uid"]:"";
$collect_date= isset($_GET["collect_date"])?$_GET["collect_date"]:"";
$collect_time= isset($_GET["collect_time"])?$_GET["collect_time"]:"";


?>

<div class="card" id="div_lab_result">
  <div class="card-header bg-primary text-white" style="max-height: 3rem;">
      <div class="row ">
         <div class="col-sm-3">
           <h4><i class="fa fa-clipboard-list fa-lg" aria-hidden="true"></i> <b>Lab Result <span id="txt_lab_result_id"></span> :</b></h4>
         </div>

         <div class="col-sm-5">

            <b>
             <span id = "txt_lab_result_title"></span>
            </b>

         </div>

         <div class="col-sm-3">
            Status: <input type="text" id="lab_report_status" size="20" disabled>
         </div>
         <div class="col-sm-1">
           <button type="button" class="btn btn-sm btn-white  py-1 float-right" onclick="closeToLabOrderList();"> <i class="fa fa-times fa-lg" ></i> Close</button>
       </div>
      </div>
  </div>
  <div class="card-body" >
    <div class="my-1">
    Lab Report Specimen:
    <select id="sel_specimen_report">

    </select>
    <button id="btn_print_lab_result" class="my-1 bg-primary text-white" type="button">
      <i class="fa fa-print fa-lg" ></i> Print Lab Report
    </button>
   </div>
    <div class="row my-1">
      <div class="col-sm-12">
        <div style="min-height: 300px; border:1px solid grey;">
          <table id="tbl_lab_test_result" class="table table-bordered table-sm table-striped table-hover">
              <thead>
                <tr>
                  <th>Specimen</th>
                  <th>Lab Test</th>
                  <th>Lab Result</th>
                  <th>Ref. Male</th>
                  <th>Ref. Female</th>
                  <th>Normal?</th>

                  <th>Lab Note</th>
                </tr>
              </thead>
              <tbody>

              </tbody>

          </table>
        </div>
      </div>

    </div>
    <div class="my-2">
      <textarea id="lab_report_note" rows="4"  class="form-control form-control-sm" placeholder="Lab Report Note"></textarea>
    </div>


  </div><!-- cardbody -->

  <div class="card-footer ">
<!--
    <button type="button" id="btn_confirm_lab_result" class="btn btn-warning btn-lab-report mr-auto" style="display:none;"><i class="fa fa-clipboard-check fa-lg" ></i> Confirm Lab Report</button>

    <button type="button" id="btn_save_lab_result" class="btn btn-success mx-1 btn-lab-report float-right" style="display:none;"> <i class="fa fa-save fa-lg" ></i> Save Data</button>
-->
  </div>
</div>


<script>
var is_confirm_lab = 0;
var cur_lab_order_id = "";
var p_lab_result_status = "A0";
var cur_lab_result_specimen_id = "";

$(document).ready(function(){
  initDataLabResult();

  $("#btn_print_lab_result").click(function(){
     printLabTestReport();
  }); // btn_print_lab_result

  $("#btn_print_lab_result").on("keypress",function (event) {
    if (event.which == 13) {
      printLabTestReport();
    }
  });
  $("#btn_save_lab_result").click(function(){
     saveLabTestResultData();
  }); // btn_save_lab_result
  $("#btn_confirm_lab_result").click(function(){
     confirmLabResultData();

  }); // btn_save_lab_result

});


function initDataLabResult(){
  // $('#btn_confirm_lab_result').prop("disabled", true);
   selectLabTestResult();
}

function printLabTestReport(){
  var specimen_id =  $("#sel_specimen_report").val();
  var uid="<? echo $uid;?>";
  var collect_date="<? echo $collect_date;?>";
  var collect_time="<? echo $collect_time;?>";

  var visit = collect_date.replaceAll("-", "") + collect_time.replaceAll(":", "");

  var link = "link_lab_report.php?lab_order_id="+cur_lab_order_id+"&sp_id="+specimen_id+"&uid="+uid+"&visit="+visit;
  //link = "<? echo $GLOBALS['site_path'] ;?>lab/"+link;
  link = "http://192.168.100.11/weclinic/lab/"+link;
  window.open(link, "_blank", "toolbar=no,location=no");
}

function selectLabTestResult(){

  var aData = {
      u_mode:"select_lab_test_result",
      uid:"<? echo $uid;?>",
      collect_date:"<? echo $collect_date;?>",
      collect_time:"<? echo $collect_time;?>"
  };
  save_data_ajax(aData,"lab/db_lab_test_order.php",selectLabTestResultComplete);

}


function selectLabTestResultComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
  //  console.log("selectLabTestResultComplete ");
  var dataObj = rtnDataAjax.data_lab_order;

  cur_lab_order_id= dataObj.lab_order_id;
  $("#txt_lab_result_id").html(cur_lab_order_id);
  $("#txt_lab_result_title").html("UID: <u>"+aData.uid+"</u> | Visit Date: <u>"+changeToThaiDate(aData.collect_date)+" "+aData.collect_time+"</u> ") ;
  $("#lab_report_status").val(dataObj.status_name);
  $("#lab_report_note").html(dataObj.lab_report_note);

  if(dataObj.status_id != "A5"){ // A5= lab result confirmed by doctor/counselor
    if(dataObj.status_id == "A4"){ // A4 = lab result confirm from lab and pending for doctor confirmed
      $("#btn_confirm_lab_result").show();
      $("#btn_save_lab_result").show();
    }
    else{
      $("#btn_save_lab_result").show();
    }
  }
    if(rtnDataAjax.data_lab_result.length > 0){

         $("#sel_specimen_report").empty();
         $("#sel_specimen_report").append(new Option("All", "all"));
         var datalist = rtnDataAjax.data_lab_specimen;
         for (id in datalist) {
           $("#sel_specimen_report").append(new Option(datalist[id], id));
         }//for



       if(rtnDataAjax.data_lab_result.length > 0){
         var datalist = rtnDataAjax.data_lab_result;
         datalist.forEach(function (itm) {

            addRowLabTestResult(
              itm.specimen_id,itm.specimen_name,
              itm.lab_id2, itm.lab_id, itm.lab_name, itm.lab_serial_no,itm.barcode,
              itm.lab_result_report, itm.lab_result_note,
              itm.lab_result_status, itm.m_lab_std_txt , itm.f_lab_std_txt
            );
//console.log("lab : "+itm.lab_result_status+" / "+dataObj.lab_id);

         });
       }


    }
    else{

      $("#sel_specimen_report").hide();
      $("#btn_print_lab_result").hide();
      $("#btn_save_lab_result").hide();
      $("#lab_report_note").hide();
    }



  }
}


function saveLabTestResultData(){
   is_confirm_lab = 0;
   saveLabTestResult();
}
function confirmLabResultData(){
   is_confirm_lab = 1;
   saveLabTestResult();
}

function saveLabTestResult(){

    var flag_valid = 1;
    var lst_data = [];
    var lst_data_note = [];

    $("#tbl_lab_test_result .r_lab_result").each(function(ix,objx){
       var row_id = $(objx).attr("id");
    //console.log("str: "+$("#rsn_"+row_id).val()+"/"+$("#rsn_"+row_id).data("odata"));
       if($("#rss_"+row_id).val() != $("#rss_"+row_id).data("odata") ||
          $("#rsn_"+row_id).val() != $("#rsn_"+row_id).data("odata")
       ){ // chk data changed record  to save
         var arr_obj = [];

         arr_obj.push({name:"lab_id", value:$(objx).data("lab_id")});
         arr_obj.push({name:"barcode", value:$(objx).data("barcode")});
         arr_obj.push({name:"lab_serial_no", value:$(objx).data("lab_serial_no")});
         arr_obj.push({name:"lab_result_note", value:$("#rsn_"+row_id).val()});
         arr_obj.push({name:"lab_result_status", value:$("#rss_"+row_id).val()});
         lst_data.push(arr_obj); // lab result obj

       }

    });

    if($("#lab_report_note").val() != $("#lab_report_note").data("odata")){
      var arr_obj = [];
      arr_obj.push({name:"lab_report_note", value:$("#lab_report_note").val()});
      lst_data_note.push(arr_obj); // lab report note
    }


    if(lst_data.length > 0 || lst_data_note.length > 0){
      var aData = {
          u_mode:"save_lab_result",
          uid:"<? echo $uid; ?>",
          collect_date:"<? echo $collect_date; ?>",
          collect_time:"<? echo $collect_time; ?>",
          lst_note:lst_data_note,
          lst_data_result:lst_data
      };

      save_data_ajax(aData,"lab/db_lab_test_order.php",saveLabResultComplete);

    }
    else{
      $.notify("No lab result to save", "info");
    }
}

function saveLabResultComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $.notify("Save lab result successfully.", "info");
    if(is_confirm_lab == 1){
      confirmLabResult();
    }
  }
}

function confirmLabResult(){

  var aData = {
      u_mode:"confirm_lab_report",
      uid:"<? echo $uid; ?>",
      collect_date:"<? echo $collect_date; ?>",
      collect_time:"<? echo $collect_time; ?>",
      lab_order_id: cur_lab_order_id
  };
  save_data_ajax(aData,"lab/db_lab_test_order.php",confirmLabResultComplete);

}

function confirmLabResultComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
  //  console.log("selectLabTestResultComplete ");

     $("#lab_result_status").val("Lab Report Confirmed");
     $(".btn-lab-report").hide();
     $.notify("Confirm Lab order successfully.", "info");

  }
}

function addRowLabTestResult(
  specimen_id, specimen_name,
  lab_id2, lab_id, lab_name, lab_serial_no, barcode,
  lab_result_report, lab_result_note,
  lab_result_status, male_normal_range, female_normal_range
){

  var row_id = specimen_id+lab_id2;
  var lab_result = "";

  lab_result_report = (lab_result_report == null)?"<span class='text-secondary'>Pending</span>":"<span class='text-success'><b>"+lab_result_report+"</b></span>";
  lab_result_note = (lab_result_note == null)?"":lab_result_note;

  var txt_row = '<tr class="r_lab_result" id="'+row_id+'" ' ;
  txt_row += ' data-barcode="'+barcode+'" data-specimen_id="'+specimen_id+'" data-lab_id="'+lab_id+'"  data-lab_serial_no="'+lab_serial_no+'"';
  txt_row += ' >';

  txt_row += '<td ><b><span class="text-primary">'+ specimen_name+'</span></b></td>';
  txt_row += '<td >'+lab_name+'<br></td>';

  txt_row += '<td >';
  txt_row += lab_result_report ;
//  txt_row += "<input type='text' class='result-report-txt' id='rsr_"+row_id+"' size='20'  value='"+lab_result_report+"' disabled> ";
  txt_row += '</td>';

  txt_row += '<td >'+male_normal_range+'</td>';
  txt_row += '<td >'+female_normal_range+'</td>';

  txt_row += '<td ><select id="rss_'+row_id+'" data-odata="'+lab_result_status+'"><option value="L0">Pending</option><option value="L1">Yes</option><option value="L2">No</option></select></td>';

  txt_row += '<td >';
  //txt_row += "<input type='text' id='rsn_"+row_id+"' size='20'>";
  txt_row += '<textarea id="rsn_'+row_id+'" data-odata="'+lab_result_note+'" rows="1"  class="form-control form-control-sm" placeholder="Result Note">'+lab_result_note+'</textarea>';
  txt_row += '</td>';


  txt_row += '</tr">';

  $("#tbl_lab_test_result tbody").append(txt_row);

  if(lab_result_status != null)
  $("#rss_"+row_id).val(lab_result_status); // is normal?
  else{
    $("#rss_"+row_id).val("L0"); // is normal?
    $("#rss_"+row_id).prop("disabled", true);
    $("#rsn_"+row_id).prop("disabled", true);
  }

}


</script>
<? include_once("../in_savedata.php"); ?>
<? include_once("../inc_foot_include.php"); ?>
<? include_once("../function_js/js_fn_validate.php"); ?>
