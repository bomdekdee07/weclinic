<link rel="stylesheet" href="assets/css/bom.css?t<? echo("=".time()); ?>" />

<?
if (session_status() == PHP_SESSION_NONE) {
  //  include_once("../in_auth.php");

}
//include_once("../a_app_info.php");
include_once("../in_auth_db.php");
include_once("../function/in_fn_sql_update.php"); // sql update
include_once("../function/in_fn_date.php"); // date function

if(!isset($s_id)){
  $s_id= isset($_GET["s_id"])?$_GET["s_id"]:"";
}
//echo "s_id: $s_id";
$lab_order_id= isset($_GET["lab_order_id"])?$_GET["lab_order_id"]:"";
$uid= isset($_GET["uid"])?$_GET["uid"]:"";
$collect_date= isset($_GET["collect_date"])?$_GET["collect_date"]:"";
$collect_time= isset($_GET["collect_time"])?$_GET["collect_time"]:"";
$is_pribta= isset($_GET["is_pribta"])?$_GET["is_pribta"]:"";

// get staff cf
$staff_cf = "";
$bind_param = "sss";
$array_val = array($uid, $collect_date, $collect_time);

$query = "SELECT staff_confirm from p_lab_order 
where uid = ?
and collect_date = ?
and collect_time = ?;";
$stmt = $mysqli->prepare($query);
$stmt->bind_param($bind_param, ...$array_val);
if($stmt->execute()){
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()){
    $staff_cf = $row["staff_confirm"];
  }
  // echo "tets: ".$staff_cf;
}
$stmt->close();

$js_staff_cf = "";
if($staff_cf != ""){
  $js_staff_cf .= '$("[name=ic_staff_cf]").show();';
}
else{
  $js_staff_cf .= '$("[name=ic_staff_cf]").hide();';
}


//$is_pribta = '1'; //test pribta clinic mode
//echo "<br>$uid /$collect_date/$collect_time<br>";

$data_attr = "data-pribta='$is_pribta' data-oid='$lab_order_id'
  data-sid='$s_id'  data-uid='$uid' data-coldate='$collect_date' data-coltime='$collect_time'";

/*
<div class="card" id="div_lab_result" data-pribta='<? echo $is_pribta;?>' data-oid='<? echo $lab_order_id ?>'
  data-sid='<? echo $s_id ?>'  data-uid='<? echo $uid ?>' data-coldate='<? echo $collect_date ?>' data-coltime='<? echo $collect_time ?>'>
*/
?>
<style>
.td_ext_lab{
  vertical-align: middle;
  text-align: center;
  width: 15px;
}
.td_ext_lab input{
  /* Double-sized Checkboxes */
    -ms-transform: scale(1.5); /* IE */
    -moz-transform: scale(1.5); /* FF */
    -webkit-transform: scale(1.5); /* Safari and Chrome */
    -o-transform: scale(1.5); /* Opera */
    padding: 5px;
    margin-right:5px;
    vertical-align: middle;
    margin-top:3px;
}

.td_lab_range{
  font-size: smaller;
  width:250px;
}
.inporderid{
  font-size: smaller;
  width:90px;
}

.inpresult{
  font-size: smaller;
  width:220px;
}

.inpstatus{
  font-size: smaller;
  width:60px;
}

.txtpaid{
  font-size: 10px;
  color:green;
}



</style>
<div class="card" id="div_lab_result" <? echo $data_attr;?>>
  <div class="card-header bg-primary text-white" >
      <div class="row py-0">
         <div class="col-sm-4">
           <b><i class="fa fa-clipboard-list fa-lg" aria-hidden="true"></i> Lab Result <span id="txt_lab_result_id"></span> :</b><br>

              <span id = "txt_lab_result_title" class='ptxt-b ptxt-s20' style='color:yellow;'></span>
         </div>
         <div class="col-sm-4">
            <div class = 'fl-wrap-row ph50 fl-fill'>
              <div class = 'fl-fill'>
                <div class = 'fl-wrap-row ph20 fl-fix'>
                  Specimen Received:
                </div>
                <div class = 'fl-wrap-row ph30 fl-fix'>
                  <input type="text" id="lab_specimen_receive" class="specimendate" size="20" disabled>
                </div>
              </div>
              <div class = 'fl-fill'>
                <div class = 'fl-wrap-row ph20 fl-fix'>
                  Specimen Collected:
                </div>
                <div class = 'fl-wrap-row ph30 fl-fix'>
                  <input type="text" id="lab_specimen_collect" class="specimendate" size="20" disabled>
                </div>
              </div>
              <div class = 'fl-fix pw50'>
                <div class = 'fl-wrap-row ph20 fl-fix'>
                </div>
                <div class = 'fl-wrap-row ph30 fl-fix fl-mid btn-edit-specimendate'>
                   Edit
                </div>
                <div class = 'fl-wrap-row ph30 fl-fix fl-mid pbtn btn-save-specimendate' style='display:none;'>
                   Save
                </div>
              </div>
            </div>
         </div>
         <div class="col-sm-3 px-4">
            Status:<br> <input type="text" id="lab_report_status" size="20" disabled>
         </div>
         <div class="col-sm-1">
           <button type="button" class="btn btn-sm btn-white  py-1 float-right lab-part" onclick="closeToLabOrderList();"> <i class="fa fa-times fa-lg" ></i> Close</button>
       </div>
      </div>
  </div>
  <div class="card-body" >
    <div class="row my-1">
      <div class="col-sm-9">

      <select id="sel_specimen_report" style="display:none;"></select>

      <button id="btn_print_lab_result" class="my-1 bg-primary text-white" type="button" style='margin-right:30px'>
        <i class="fa fa-print fa-lg" ></i> Print All Lab Report
      </button>
      <label class='px-2'> <input type='checkbox' class='chk-hidename'> Hide patient name </label>
      <label class='px-2'> <input type='checkbox' class='chk-hideproject'> Hide Project name </label>

      <button id='btnDeletePdf' class='lab-part'>X</button><SELECT id='ddlPDFList' style='max-width:150px'>
        <?
        if($is_pribta != '1') include("db_lab_pdf.php");
        ?>
      </SELECT>
        <i id="btn_view_pdf" style='background-color: white' class="btn far fa-file-pdf fa-lg" ></i>

      <form method="post" action="" enctype="multipart/form-data" id="myform" class="lab-part" style="margin-left:30px;display:inline;">
          New File : <input id='txtFileDesc' name='filedesc' title='File Title' />
          <input type="file" id="filePDF" name="file" accept=".pdf" title='Less than 8MB only' /><input type="button" class="button" value="Upload" style='display:none' id="btnUploadPdf"  <? echo("data-uid='".$uid."' data-coldate='".$collect_date."' data-coltime='".$collect_time."'") ?> /><br>
      </form>
      <img id='filespinner' src='image/spinner.gif' style='height:30px;display:none' />

      </div>
      <div class="col-sm-3 div-pdf-thumbnail">

      </div>


   </div>
    <div class="row my-1 div-table-lab-report" >
      <div class="col-sm-12">
        <div style="min-height: 300px; border:1px solid grey;">
          <form id='form_custom_lab_report' action='lab/custom_lab_report.php' method='POST' target='_blank'>
            <input type='hidden' name='oid' value='<? echo $lab_order_id; ?>'>
            <input type='hidden' name='printid' value='<? echo $s_id; ?>'>
            <input type='hidden' name='hidename' value=''>
            <input type='hidden' name='hideproject' value=''>
            <input type='hidden' name='check_bt' value=''>

          <table id="tbl_lab_test_result" class="table table-bordered table-sm table-striped table-hover">
              <thead>
                <tr>
                  <th><input type="checkbox" class='chk-lab-report-all'> </th>
                  <th>
                    Lab Test     <button id="btn_sel_print_lab_result" class="my-1 bg-mdark2 text-white" type="button" style='margin-right:30px'><i class="fa fa-print fa-lg" ></i> Print Selected Lab</button>
                    <button id="btn_sel_print_lab_result_custom" class="my-1 bg-mdark2 text-white" type="button" style='margin-right:10px' disabled>Print Custom</button>
                    Date custom: <input type="text" name="fix_date_print_now" disabled>
                  </th>
                  <th>Lab Result  <button type="button" class="pbtn pbtn-blue mr-auto" onclick="showCalc_CREA();"><i class="fa fa-calculator fa-lg" ></i> CREA Calculate</button>
</th>
                  <th><small>Ext. Lab</small></th>
                  <th>Result Report</th>
                  <th>Ref. Range</th>
                  <th>Normal?</th>

                  <th style='min-width:150px'>Lab Note</th>
                </tr>
              </thead>
              <tbody>

              </tbody>

          </table>

          </form>
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
<select id="sel_confirm_by" class="btn-lab-confirm" style="display:none;">
  <? include_once('p_lab_opt_license_staff.php'); ?>
</select>

<button type="button" id="btn_confirm_lab_result" class="btn btn-warning btn-lab-report btn-lab-confirm mr-auto" style="display:none;"><i class="fa fa-clipboard-check fa-lg" ></i> Confirm Lab Report</button>
<i class="fas fa-spinner fa-spin spinner" style="display:none;"></i>
<button class="btn" name="ic_staff_cf" style="padding: 0px 2px 0px 2px; display: none;"><i class="fa fa-check fa-2x" style="color: #91EF11;">Approved</i></button>

<button type="button" id="btn_save_lab_result" class="btn btn-success mx-1 btn-lab-report float-right lab-part" style="display:none;"> <i class="fa fa-save fa-lg" ></i> Save Data</button>
<i class="fas fa-spinner fa-spin spinner" style="display:none;"></i>

  </div>
</div>

<script src="lab/js/pribta.js?t<? echo("=".time()); ?>"></script>
<script>
var is_confirm_lab = 0;
var cur_lab_order_id = "";
var p_lab_result_status = "A0";
var cur_lab_result_specimen_id = "";
var cur_txt_result = [];
sWait = false;


$(document).ready(function(){
  <? echo $js_staff_cf; ?>

  $("#div_lab_result").unbind();

  $(".div-table-lab-report .chk-lab-report-all").off('click');
  $(".div-table-lab-report .chk-lab-report-all").on("click",function(){
    $(".div-table-lab-report .chk-lab-report").prop('checked', $(this).prop('checked'));
  });


$(".div-table-lab-report #btn_sel_print_lab_result").off('click');
$(".div-table-lab-report #btn_sel_print_lab_result").on("click",function(ev){
  ev.preventDefault()

  let sUid = $('#div_lab_result').attr('data-uid');
  let sColDate = $('#div_lab_result').attr('data-coldate');
  let sColTime = $('#div_lab_result').attr('data-coltime');
  var sDoctype = "LAB_REPORT_HIS";
  var sTrLabId = "";

  $.each($(".chk-lab-report:checked"), function(){
      var chk_this = $(this).val();
      sTrLabId += "'"+chk_this+"',"
  });

  sTrLabId = sTrLabId.slice(0,-1);
  var sUrl = "lab/p_lab_report_his_dlg.php?uid="+sUid+"&coldate="+sColDate+"&coltime="+sColTime+"&doctype="+sDoctype+"&lab_id="+encodeURIComponent(sTrLabId);

  showDialog(sUrl, "Lab Report Management", "70%", "60%","",
  function(sResult){
      //CLose function
  },false,function(){
      //Load Done Function
  });

  $("input[name='oid']").val($("#txt_lab_result_id").html());
  if($('.chk-hidename').is(":checked")){
    $("input[name='hidename']").val('1');
  }
  if($('.chk-hideproject').is(":checked")){
    $("input[name='hideproject']").val('1');
  }

  $("[name=check_bt]").val("");
  // $("#form_custom_lab_report").submit();
});

$(".div-table-lab-report #btn_sel_print_lab_result_custom").off('click');
$(".div-table-lab-report #btn_sel_print_lab_result_custom").on("click",function(ev){
  ev.preventDefault()
  $("input[name='oid']").val($("#txt_lab_result_id").html());
  if($('.chk-hidename').is(":checked")){
    $("input[name='hidename']").val('1');
  }
  if($('.chk-hideproject').is(":checked")){
    $("input[name='hideproject']").val('1');
  }

  $("[name=check_bt]").val("1");
  $("#form_custom_lab_report").submit();
});


$(".btn-edit-specimendate").off('click');
//$(".btn-edit-specimendate").on("click",function(){
  $("#div_lab_result").on("click",".btn-edit-specimendate", function(){
    $(".specimendate").prop('disabled', false);
    $(this).hide();
    $('.btn-save-specimendate').show();
});

$(".btn-save-specimendate").off('click');
$("#div_lab_result").on("click",".btn-save-specimendate", function(){


    let sUid = $('#div_lab_result').attr('data-uid');
    let sColDate = $('#div_lab_result').attr('data-coldate');
    let sColTime = $('#div_lab_result').attr('data-coltime');


           var aData = {
               u_mode:"update_specimen_time",
               uid: sUid,
               coldate:sColDate,
               coltime: sColTime,
               specimen_receive:$('#lab_specimen_receive').val(),
               specimen_collect:$('#lab_specimen_collect').val()
           };

           //startLoad(btnclick, btnclick.next(".spinner"));
            callAjax("lab/db_lab_test_specimen_v2.php",aData,function(rtnObj,aData){
              $.notify('Update: '+rtnObj.update_amt, 'info');

              $(".specimendate").prop('disabled', true);
              $(this).hide();
              $('.btn-edit-specimendate').show();
            });// call ajax




});





  $("#tbl_lab_test_result").on("change",".chkexternal",function(){
    let objTr = $(this).closest("tr");
    $(objTr).find(".txt-edit-order").val("See Lab result from attached PDF file.");
  });
/*
  $("#tbl_lab_test_result").on("change",".chkexternal",function(){
    let objTr = $(this).closest("tr");
    let objChk = $(this);
    sColDate = $(objTr).attr("data-coldate");
    sColTime = $(objTr).attr("data-coltime");
    sUid  = $(objTr).attr("data-uid");
    sLabId = $(objTr).attr("data-lab_id");
    sBar = $(objTr).attr("data-barcode");
    sSpecId = $(objTr).attr("data-specimen_id");
    sSerial = $(objTr).attr("data-lab_serial_no");
    sExtLab = ($(this).is(":checked")?"1":"0");


    let fd = new FormData();
    fd.append("u_mode","set_external_lab");
    fd.append("bar",sBar);
    fd.append("specid",sSpecId);
    fd.append("labid",sLabId);
    fd.append("labser", sSerial);
    fd.append("uid",sUid);
    fd.append("coldate",sColDate);
    fd.append("coltime",sColTime);
    fd.append("onote",sExtLab);

    $(objChk).hide();
    $(objChk).next(".spinner").show();
    $.ajax({
        url: 'lab/db_lab_save.php',
        type: 'post',
        data: fd,
        contentType: false,
        processData: false,

        success: function(response){
          if(response != 0){
            if(fd.get("u_mode")=="set_external_lab"){
              if(fd.get("onote")=="1") {

                $(objTr).find(".txt-edit-order").val("See Lab result from attached PDF file.");
              }
              else $(objTr).find(".txt-edit-order").val("");
            }
          }
          else{
              $.notify("check error","error");
          }
          $(objChk).show();
          $(objChk).next(".spinner").hide();
        }
    });


  });

  */

  $("#filePDF").change(function(){
    if($(this).val()!=""){
      $("#btnUploadPdf").show();
    }else{
      $("#btnUploadPdf").hide();
    }
  });
  $("#btnDeletePdf").click(function(){
    let sFileId = $("#ddlPDFList").val();

    if(sFileId=="" || sFileId== undefined){
      $.notify("No file selected.");
      return;
    }

    let sFileText = $("#ddlPDFList option[value='"+sFileId+"']").text();
    if(confirm("Confirm delete this PDF : "+sFileText)==false){
      return;
    }

    sReason = prompt("Enter your reason.\r\nขอเหตุผลดีๆซักข้อ");
    if(sReason.trim() == ""){
      $.notify("Please give me a reason to delete.");
      return;
    }
    sReason = sReason.trim();
    sUid = $("#btnUploadPdf").attr("data-uid");
    sColDate = $("#btnUploadPdf").attr("data-coldate");
    sColTime = $("#btnUploadPdf").attr("data-coltime");

    var fd = new FormData();
      fd.append("mode","delete_pdf");
      fd.append("uid",sUid);
      fd.append("collect_date",sColDate);
      fd.append("collect_time",sColTime);
      fd.append("reason",sReason);
      fd.append("fileid",sFileId);
      $("#btnDeletePdf").hide();
      $("#myform").hide();
      $("#filespinner").show();
      $.ajax({
          url: 'lab/db_lab_pdf.php',
          type: 'post',
          data: fd,
          contentType: false,
          processData: false,

          success: function(response){
              if(response != 0){
                $("#ddlPDFList").find("option[value='"+response+"']").remove();
              }
              else{
                  $.notify("delete error","error");
              }
            $("#filespinner").hide();
            $("#btnDeletePdf").show();
            $("#myform").show();
            getPDFThumbnailShow();

          },
      });

  });
  $("#btnUploadPdf").click(function() {
      var fd = new FormData();
      var files = $('#filePDF')[0].files[0];
      fd.append('file', files);
      var sUid = $(this).attr('data-uid');
      var sColDate = $(this).attr("data-coldate");
      var sColTime = $(this).attr("data-coltime");
      var sDesc = $("#txtFileDesc").val();

      if(sDesc==""){
        $("#txtFileDesc").focus();
        $("#txtFileDesc").notify("Please enter short file info\r\n กรุณาใส่รายละเอียดไฟล์สั้นๆก่อนครับ");
        return;
      }
      fd.append("uid",sUid);
      fd.append("coldate",sColDate);
      fd.append("coltime",sColTime);
      fd.append("filedesc",sDesc);

      $("#myform").hide();
      $("#filespinner").show();

      $.ajax({
          url: 'lab/pdf_upload.php',
          type: 'post',
          data: fd,
          contentType: false,
          processData: false,

          success: function(response){
              if(response != 0){
                $("#filePDF").val("");
                 $.notify("file uploaded","success");
                 $("#txtFileDesc").val("");
                 $("#btnUploadPdf").hide();
                 $("#ddlPDFList").append(response);
              }
              else{
                  $.notify("uploaded error","error");
              }
            $("#filespinner").hide();
            $("#myform").show();
            getPDFThumbnailShow();

          },
      });
  });


  $("#btn_view_pdf").click(function(){
    let sFileId = $("#ddlPDFList").val();
    if(sFileId==""||sFileId==undefined){
      $.notify("No file available\r\nยังไม่มีไฟล์");
      return;
    }

    sUid = $("#btnUploadPdf").attr("data-uid");
    sColDate = $("#btnUploadPdf").attr("data-coldate");
    sColTime = $("#btnUploadPdf").attr("data-coltime");
    sTime = sColTime.split(":");
    sNewTime = sTime[0]+sTime[1]+sTime[2];
    window.open("lab/pdf_result/"+sUid+"_"+sColDate+"_"+sNewTime+"_"+sFileId+".pdf");
  });

  $("#btn_print_lab_result").click(function(){
     printLabTestReport();
  }); // btn_print_lab_result

  $("#btn_print_lab_result").on("keypress",function (event) {
    if (event.which == 13) {
      printLabTestReport();
    }
  });

  $("#btn_save_lab_result").click(function(){
     saveLabResult();
  }); // btn_save_lab_result

  $("#btn_confirm_lab_result").click(function(){
     confirmLabResultData();

  }); // btn_save_lab_result

//  $(".labname").off("dblclick");
  $(".labname").unbind();
  $("#div_lab_result").on("dblclick",".labname",function(){
     $(this).next(".btndellab").show();
  });

  //$(".btndellab").off("click");
  $(".btndellab").unbind();
  $("#div_lab_result").on("click",".btndellab",function(){
      jRLS = $(this).closest(".r_lab_result");
      let sLabId = $(jRLS).attr("data-lab_id");
      if(confirm("Please confirm remove this lab?\r\n ยืนยันเอาแล๊บตัวนี้ออก? \r\n"+sLabId)== false){
        return;
      }
      sReason = prompt("Please specific reason.\r\nเหตุผลที่ลบ\r\n"+ sLabId);
      sReason = ((sReason==null||sReason==undefined||sReason.trim()=="")?"":sReason.trim());

      let sUid = $('#div_lab_result').attr('data-uid');
      let sColDate = $('#div_lab_result').attr('data-coldate');
      let sColTime = $('#div_lab_result').attr('data-coltime');
      if(sReason != ""){
        var aData = {
            u_mode:"remove_lab_result",
            labid:sLabId,
            reason:sReason,
            uid:sUid,
            coldate:sColDate,
            coltime:sColTime
        };
        save_data_ajax(aData,"lab/db_lab_test_result.php",labresultRemoveComplete);
      }
    });
    function labresultRemoveComplete(flagSave, rtnDataAjax, aData){
      if(flagSave){
        if(rtnDataAjax.res == '1'){
          $.notify('Remove: '+aData.labid, 'info');
          sObj = $("#tbl_lab_test_result tbody").find(".r_lab_result[data-lab_id='"+aData.labid+"']");
          if(sObj.length){
            $(sObj).remove();
          }
        }
        else{
          $.notify('Fail to delete lab result');
          if(rtnDataAjax.msg_error != '')
          $.notify(rtnDataAjax.msg_error, 'error');
        }

      }
    }

  $("#div_lab_result").on("change",".txt-edit-order",function(){
     $(this).next(".btneditlabnote").show();
  });


  $("#div_lab_result").on("click",".btneditlabnote",function(){
      if(sWait==true){
        return;
      }

      jRLS = $(this).closest(".r_lab_result");
      let sBar = $(jRLS).attr("data-barcode");
      let sSpecId = $(jRLS).attr("data-specimen_id");
      let sLabId = $(jRLS).attr("data-lab_id");
      let sLabSerial = $(jRLS).attr("data-lab_serial_no");
      sReason = prompt("Please enter reason for lab order changed?");

      sReason = ((sReason==null||sReason=="")?"":sReason.trim());
      sUid =  $(jRLS).attr("data-uid");
      sColDate =  $(jRLS).attr("data-coldate");
      sColTime =  ($(jRLS).attr("data-coltime"));
      sOrderNote = urlEncode($(jRLS).find(".txt-edit-order").val());
      if(sReason != ""){
        sWait=true;
        var aData = {
            u_mode:"edit_lab_note",
            bar:sBar,
            specid:sSpecId,
            labid:sLabId,
            labser: sLabSerial,
            reason:sReason,
            uid:sUid,
            coldate:sColDate,
            coltime:sColTime,
            onote:sOrderNote
        };
        save_data_ajax(aData,"lab/db_lab_save.php",labsavecomplete);
      }
    });


  function labsavecomplete(flagSave, rtnDataAjax, aData){
    if(flagSave){
        if(aData.u_mode=="edit_lab_note"){
          sObj = $("#tbl_lab_test_result tbody").find("tr[data-uid='"+aData.uid+"'][data-coldate='"+aData.coldate+"'][data-coltime='"+aData.coltime+"'][data-barcode='"+aData.bar+"'][data-specimen_id='"+aData.specid+"'][data-lab_id='"+aData.labid+"'][data-lab_serial_no='"+aData.labser+"']");
          if(sObj.length){
            $(sObj).find(".btneditlabnote").hide();
          }
        }else if(aData.u_mode=="edit_lab_result"){
          sObj = $("#tbl_lab_test_result tbody").find("tr[data-uid='"+aData.uid+"'][data-coldate='"+aData.coldate+"'][data-coltime='"+aData.coltime+"'][data-barcode='"+aData.bar+"'][data-specimen_id='"+aData.specid+"'][data-lab_id='"+aData.labid+"'][data-lab_serial_no='"+aData.labser+"']");
          if(sObj.length){
            $(sObj).find(".btneditlabresult").hide();
          }
        }else if(aData.u_mode=="remove_lab_id"){
          sObj = $("#tbl_lab_test_result tbody").find("tr[data-uid='"+aData.uid+"'][data-coldate='"+aData.coldate+"'][data-coltime='"+aData.coltime+"'][data-barcode='"+aData.barcode+"'][data-specimen_id='"+aData.specid+"'][data-lab_id='"+aData.labid+"'][data-lab_serial_no='"+aData.serialno+"']");
          if(sObj.length){
            $(sObj).remove();
          }
        }
    }
    sWait = false;
  }

initDataLabResult();
});


function initDataLabResult(){
  // $('#btn_confirm_lab_result').prop("disabled", true);
  if($('#div_lab_result').attr('data-pribta') == '1'){
    $('.lab-part').hide();
    $("#btn_save_lab_result").remove();
    $(".btn-lab-confirm").remove();
    $("#lab_report_note").prop('disabled', true);
  }
   selectLabTestResult();
   getPDFThumbnailShow();
}

function getPDFThumbnailShow(){
  let sUid = $('#div_lab_result').attr('data-uid');
  let sColDate = $('#div_lab_result').attr('data-coldate');
  let sColTime = $('#div_lab_result').attr('data-coltime');
  sTime = sColTime.split(":");
  sNewTime = sTime[0]+sTime[1]+sTime[2];

  let txt_thumbnail = "";
  $("#ddlPDFList option").each(function(){
      //console.log("file -"+$(this).text());
      txt_thumbnail += "<a href='lab/pdf_result/"+sUid+"_"+sColDate+"_"+sNewTime+"_"+$(this).val()+".pdf' target='_blank'> <i class='mx-1 far fa-file-pdf fa-2x btn btn-warning file-pdf-view' title='"+$(this).text()+"' data-id='"+$(this).val()+"'></i></a>";
  });
  //console.log("txt: "+txt_thumbnail);
  if(txt_thumbnail != ""){
    $('.div-pdf-thumbnail').html(txt_thumbnail);
  }
}

function showCalc_CREA(){ //Creatinine Clearance dialog
    let uid = $('#div_lab_result').attr('data-uid');
    let coldate = $('#div_lab_result').attr('data-coldate');
    let coltime = $('#div_lab_result').attr('data-coltime');

    let sUrl = "lab/p_lab_uid_cal_crea.php?uid="+uid+"&coldate="+coldate+"&coltime="+coltime;
    showDialog(sUrl," Creatinine Clearance ["+uid+"]","650","700","",function(sResult){
    },false,"");

}


function printLabTestReport(){
  var specimen_id =  $("#sel_specimen_report").val();
  var uid="<? echo $uid;?>";
  var collect_date="<? echo $collect_date;?>";
  var collect_time="<? echo $collect_time;?>";

  var visit = collect_date.replaceAll("-", "") + collect_time.replaceAll(":", "");
  let hidename = ($('.chk-hidename').is(":checked"))?"&hidename=1":"";
  var hideproject = ($('.chk-hideproject').is(":checked"))?"&hideproject=1":"";

  var link = "link_lab_report.php?lab_order_id="+cur_lab_order_id+"&sp_id="+specimen_id+"&uid="+uid+"&visit="+visit+hidename+hideproject;

  //link = "http://192.168.100.11/weclinic/lab/"+link;
  var sPath = window.location.origin+"/";
  var sFPath = window.location.pathname;
  aFP = sFPath.split("/");
  iCnt = aFP.length;
  $.each(aFP, function( iIn, sVal ) {
      if(iIn!=iCnt-1 && sVal!="") sPath += sVal+"/";
  });
  sPath += "lab/"+link;

  window.open(sPath, "_blank", "toolbar=no,location=no");
}

function selectLabTestResult(){

  var aData = {
      u_mode:"select_lab_test_result",
      uid:"<? echo $uid;?>",
      collect_date:"<? echo $collect_date;?>",
      collect_time:"<? echo $collect_time;?>"
  };
  save_data_ajax(aData,"lab/db_lab_test_result.php",selectLabTestResultComplete);

}


function selectLabTestResultComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

  var dataObj = rtnDataAjax.data_lab_order;
  cur_lab_order_id= dataObj.lab_order_id;
  $("#txt_lab_result_id").html(cur_lab_order_id);
  $("#txt_lab_result_title").html("UID: <u>"+aData.uid+"</u> | Visit Date: <u>"+changeToThaiDate(aData.collect_date)+" "+aData.collect_time+"</u> ") ;
  $("#lab_report_status").val(dataObj.status_name);
  $("#lab_report_note").html(dataObj.lab_order_note);


  if(dataObj.lab_specimen_receive != '')
  $("#lab_specimen_receive").val(dataObj.lab_specimen_receive);
  else {
    if(dataObj.time_specimen_collect != '' || dataObj.time_specimen_collect != 'null'){
      $("#lab_specimen_receive").val(dataObj.time_specimen_collect);
    }
  }
  if(dataObj.lab_specimen_collect != '')
  $("#lab_specimen_collect").val(dataObj.lab_specimen_collect);
  else{
    $("#lab_specimen_collect").val($("#lab_specimen_receive").val());
  }


  $("#btn_save_lab_result").show();

    cur_txt_result = rtnDataAjax.datalist_result_choice;
    if(rtnDataAjax.data_lab_result.length > 0){

         $("#sel_specimen_report").empty();
         $("#sel_specimen_report").append(new Option("All", "all"));

         /*
         var datalist = rtnDataAjax.data_lab_specimen;
         for (id in datalist) {
           $("#sel_specimen_report").append(new Option(datalist[id], id));
         }//for
*/


       if(rtnDataAjax.data_lab_result.length > 0){
         var datalist = rtnDataAjax.data_lab_result;
         var is_all_confirm = true;
         var lab_min = 0; lab_max=0;
         datalist.forEach(function (itm) {
//console.log("result: "+itm.lab_result);

            if(dataObj.sex == '1') {lab_min=itm.m_min; lab_max=itm.m_max;} //male
            else if(dataObj.sex == '2') {lab_min=itm.f_min; lab_max=itm.f_max;} // female
            else{
              lab_min=(itm.f_min < itm.m_min )?itm.f_min:itm.m_min;
              lab_max=(itm.f_max > itm.m_max )?itm.f_max:itm.m_max;
            }

            addRowLabTestResult('',
              itm.lab_id2, itm.lab_id, itm.lab_name, itm.lab_unit, itm.lab_serial_no,itm.barcode,
              itm.lab_result, itm.lab_result_report, itm.lab_result_note, itm.lab_result_type,
              itm.lab_result_status, itm.m_lab_std_txt , itm.f_lab_std_txt,
              lab_min, lab_max,
              aData.uid,aData.collect_date,aData.collect_time,itm.external_lab, itm.time_confirm, itm.is_paid

            );
            if(itm.time_confirm == null) is_all_confirm = false;
//console.log("lab : "+itm.lab_result_status+" / "+dataObj.lab_id);

         });

         if(!is_all_confirm) $(".btn-lab-confirm").show();

         if($('#div_lab_result').attr('data-pribta') == '1'){
           $('#div_lab_result .save-data').prop('disabled', true);
           $('#div_lab_result .chkexternal').prop('disabled', true);
         }
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





function addRowLabTestResult(specimen_id,
  lab_id2, lab_id, lab_name, lab_unit, lab_serial_no, barcode,
  lab_result, lab_result_report, lab_result_note, lab_result_type,
  lab_result_status, male_normal_range, female_normal_range,
  lab_min, lab_max,
  uid,collect_date,collect_time,external_lab,

  time_confirm, is_paid
){

  var row_id = barcode+lab_id2;
  let btnRemove = "";
  if(is_paid == '0'){
    btnRemove = '<i  class="fa fa-times fa-lg mx-2 text-danger pbtn btndellab" title="Delete this lab result." style="display:none"></i>';
  }


  barcode = (barcode == null)?"":barcode;

  lab_result = (lab_result == null)?"":lab_result;
  lab_result_status = (lab_result_status == null)?"L0":lab_result_status;
  lab_result_report = (lab_result_report == null)?"":lab_result_report;
  lab_result_note = (lab_result_note == null)?"":lab_result_note;
  external_lab = (external_lab == null)?"0":external_lab;

  is_paid = (is_paid == '1')?"<i class='fa fa-dollar-sign fa-lg' ></i><b> PAID</b>":""; //dollar-sign <i class='fa fa-vials fa-lg' ></i>

  if(lab_id == "HIV_Ab"){
    if(lab_result_note== "")
    lab_result_note="Tested by HISCL Ag+Ab Assay- 4th gen CLEIA.";
  }

  if(time_confirm == null){
    if(lab_result == "")
    time_confirm = '<span id="confirm'+row_id+'" class="badge badge-secondary time-confirm">Wait Lab Result</span>';
    else
    time_confirm = '<span id="confirm'+row_id+'" class="badge badge-warning  time-confirm">Confirm Pending</span>';

  }
  else{
    time_confirm = '<span id="confirm'+row_id+'"  class="text-primary time-confirm">'+time_confirm+"</span>";
  }


  var txt_input_result = "";
  if(lab_result_type == "txt"){

    if(typeof cur_txt_result[lab_id2]  !== 'undefined'){
      txt_input_result = "<select id='rs_"+row_id+"' data-odata='"+lab_result+"' class='save-data result-txt inpresult' onchange='setResultReport(\""+row_id+"\");'><option value=''>Select</option>";
      for (k in cur_txt_result[lab_id2]) {
          var res = cur_txt_result[lab_id2][k].split("|"); // result choice | is_normal (1=normal, 0= abnormal)
          txt_input_result += "<option value='"+k+"' data-id='"+res[1]+"'>"+res[0]+"</option>";
          //alert("enter2"+"col:"+k+" / "+cur_txt_result[lab_id2][k]);
      }
      txt_input_result += "</select>";
    }
  }
  else{
    txt_input_result = "<input type='text' data-odata='"+lab_result+"' class='save-data result-txt input-decimal inpresult' id='rs_"+row_id+"' size='15' value='"+lab_result+"' onfocusout='autoFillLabReport(\""+row_id+"\");' placeholder='Lab Result'> <br><small>("+lab_unit+")</small>";
  }

  //lab_result_report = (lab_result_report == null)?"<span class='text-secondary'>Pending</span>":"<span class='text-success'><b>"+lab_result_report+"</b></span>";
  let ref_range_txt = "";
  if(male_normal_range == female_normal_range)
    ref_range_txt += male_normal_range;
  else ref_range_txt += "<b>Male:</b> "+male_normal_range+"<br><b>Female:</b> "+female_normal_range;


  let sBtnLabName = '<span class="labname">'+lab_name+'</span>';

  var txt_row = '<tr class="r_lab_result" id="'+row_id+'" data-barcode="'+barcode+'" ' ;
  txt_row += ' data-min="'+lab_min+'"  data-max="'+lab_max+'"  ';
  txt_row += ' data-lab_id="'+lab_id+'"  data-lab_serial_no="'+lab_serial_no+'"  data-unit="'+lab_unit+'"  data-r_type="'+lab_result_type+'" ';
  txt_row += ' >';

  txt_row += '<td ><input type="checkbox" class="chk-lab-report"  name="lablist[]" value="'+lab_id+'" checked></td>';



//  txt_row += '<td ><input type="text" id="rsb_'+row_id+'" data-odata="'+barcode+'" class="save-data inporderid" placeholder="Barcode" value="'+barcode+'"></td>';
  txt_row += '<td >'+sBtnLabName+btnRemove+'<br><small>'+time_confirm+'</small></td>';

  txt_row += '<td>';
//  txt_row += lab_result_report ;
  txt_row += txt_input_result ;
  txt_row += '<div class="txtpaid mt-1">'+is_paid+'</div>' ;
//  txt_row += "<input type='text' class='result-report-txt' id='rsr_"+row_id+"' size='20'  value='"+lab_result_report+"' disabled> ";
  txt_row += '</td>';

//  txt_row += '<td class="td_ext_lab"><input type="checkbox" class="chkexternal" '+((external_lab=="1")?"checked=\"true\"":"")+' /><img class="spinner" src="image/spinner.gif" style="height:25px;display:none" /></td>';
  txt_row += '<td class="td_ext_lab"><input type="checkbox" id="extlab'+row_id+'" data-odata="'+external_lab+'"  class="chkexternal" '+((external_lab=="1")?"checked=\"true\"":"")+' /></td>';


  txt_row += '<td ><input type="text" id="rsr_'+row_id+'" data-odata="'+lab_result_report+'"  class="save-data inpresult result-report-txt" size="12" placeholder="Lab Report" value="'+lab_result_report+'"></td>';
  txt_row += '<td class="td_lab_range">'+ref_range_txt+'</td>';

  txt_row += '<td ><select id="rss_'+row_id+'" data-odata="'+lab_result_status+'" class="save-data inpstatus"><option value="L0">Pending</option><option value="L1">Yes</option><option value="L2">No</option></select><button class="btneditlabresult" style="display:none">Save Result</button></td>';

  txt_row += '<td >';
  //txt_row += "<input type='text' id='rsn_"+row_id+"' size='20'>";
  txt_row += '<textarea id="rsn_'+row_id+'" data-odata="'+lab_result_note+'" rows="1"  class="save-data form-control form-control-sm txt-edit-order" placeholder="Result Note">'+lab_result_note+'</textarea><button class="btneditlabnote" style="display:none">Save Note</button>';
  txt_row += '</td>';


  txt_row += '</tr">';

  $("#tbl_lab_test_result tbody").append(txt_row);

  if(lab_result_type == "txt"){ // set value to dropdown
    $("#rs_"+row_id).val(lab_result);
  //  console.log(row_id+"/"+lab_result);
  }

  if(lab_result_status != null)
  $("#rss_"+row_id).val(lab_result_status); // is normal?
  else{
    //console.log("lab status: "+lab_result_status);
    $("#rss_"+row_id).val("L0"); // is normal?
    $("#rss_"+row_id).prop("disabled", true);
    $("#rsn_"+row_id).prop("disabled", true);

  }

}


// set result for lab txt type when focus in lab result report
function setResultReport(rowID){
  //console.log("setResultReport "+rowID);
  if($("#rs_"+rowID).val() != "")
  $("#rsr_"+rowID).val($("#rs_"+rowID+" option:selected").text());
  else{
    $("#rsr_"+rowID).val("");
  }

// select is normal?
//console.log("select: "+$("#"+rowID).find(':selected').data('id'));

  if($("#"+rowID).find(':selected').data('id') == 1){ // normal range
    $("#rss_"+rowID).val("L1");
    $("#rss_"+rowID).prop("disabled", false);
    $("#rsn_"+rowID).prop("disabled", false);
  }
  else if($("#"+rowID).find(':selected').data('id') == 0){ // not normal range
    $("#rss_"+rowID).val("L2");
    $("#rss_"+rowID).prop("disabled", false);
    $("#rsn_"+rowID).prop("disabled", false);
  }
  else{ // pending confirm normal?
    $("#rss_"+rowID).val("L0");
    $("#rss_"+rowID).prop("disabled", true);
    $("#rsn_"+rowID).prop("disabled", true);

  }




}


// set result for lab num type when focus in lab result report
function autoFillLabReport(rowID){
  //console.log("autofill "+rowID+" / "+$("#"+rowID).data("r_type"));
  if($("#"+rowID).data("r_type") == "num"){

    if($("#rs_"+rowID).val().trim() != ""){
      $("#rsr_"+rowID).val($("#rs_"+rowID).val().trim()+" "+$("#"+rowID).data("unit"));

      if(!Number.isNaN($("#"+rowID).data("min")) &&
         !Number.isNaN($("#"+rowID).data("max")) &&
         !Number.isNaN($("#rs_"+rowID).val().trim())
       ){
        var min = parseFloat($("#"+rowID).data("min"));
        var max = parseFloat($("#"+rowID).data("max"));
        var rs_val = parseFloat($("#rs_"+rowID).val().trim());
  //console.log(min+"/"+max+"/"+rs_val);
        if((rs_val < min) || (rs_val > max) ){ // not normal
        //  console.log("value : "+rs_val+" ("+min+"/"+max+")");
          $("#rss_"+rowID).val("L2"); // NO (not normal)
          if(rs_val < min) $("#rsn_"+rowID).val("L");
          else if(rs_val > max) $("#rsn_"+rowID).val("H");
        }
        else{ // normal
          $("#rss_"+rowID).val("L1"); // Yes (normal)
        }
      }


    }
    else{
      $("#rsr_"+rowID).val("");
      $("#rss_"+rowID).val("L0");
    }


  }
}//autoFillLabReport




function saveLabResultData(){
  //is_confirm_lab = 0;
  saveLabResult();
}

var arr_lab_rowid = [];
function saveLabResult(){
  arr_lab_rowid = [];
  var flag_valid = 1;
  var lst_data = [];
  $("#tbl_lab_test_result .r_lab_result").each(function(ix,objx){
     var row_id = $(objx).attr("id");

    if($("#rs_"+row_id).data("odata") == "" && $("#rs_"+row_id).val()=="") { //no result update, pending lab

    }
    else{
      var o_str = $("#rs_"+row_id).data("odata")+':'+$("#rsr_"+row_id).data("odata")+$("#rss_"+row_id).data("odata")+$("#rsn_"+row_id).data("odata")+"/"+$("#extlab"+row_id).data("odata");

     // var str = $("#rsb_"+row_id).val()+$("#rs_"+row_id).val()+$("#rsr_"+row_id).val()+$("#rss_"+row_id).val()+$("#rsn_"+row_id).val();
      var extlab = ($("#extlab"+row_id).is(':checked')?"1":"0");
      var str = $("#rs_"+row_id).val()+':'+$("#rsr_"+row_id).val()+$("#rss_"+row_id).val()+$("#rsn_"+row_id).val()+"/"+extlab;
    //  console.log("str: "+str+"|||"+o_str);


          if(str != o_str){ // chk data changed to save
          //   console.log("change: "+row_id+" compare: "+str+" / "+o_str);
             var arr_obj = {};
             arr_obj["barcode"] = $("#"+row_id).data("barcode");
             arr_obj["lab_id"] = $(objx).data("lab_id");
             arr_obj["lab_result"] = $("#rs_"+row_id).val();
             arr_obj["lab_result_report"] = $("#rsr_"+row_id).val();
             arr_obj["lab_result_status"] = $("#rss_"+row_id).val();
             arr_obj["lab_result_note"] = $("#rsn_"+row_id).val();
             arr_obj["external_lab"] = ($("#extlab"+row_id).is(':checked')?"1":"0");
//console.log("save: "+arr_obj["lab_id"]);
             lst_data.push(arr_obj);
             arr_lab_rowid.push(row_id);

           }
         }
    });



  if(lst_data.length > 0){

    var aData = {
        u_mode:"update_lab_result",
        uid:"<? echo $uid;?>",
        collect_date:"<? echo $collect_date;?>",
        collect_time:"<? echo $collect_time;?>",

        lst_data_result:lst_data
    };
    save_data_ajax(aData,"lab/db_lab_test_result.php",saveLabResultComplete);
    $("#btn_save_lab_result").next(".spinner").show();
    $("#btn_save_lab_result").hide();
  }
  else{
    $.notify("No lab result to save", "info");
  }


}

function saveLabResultComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $.notify("Save lab result successfully.", "success");
  //  console.log("save lab result affect row: "+rtnDataAjax.affect_row);
    //update odata
    arr_lab_rowid.forEach(function(row_id){
      $("#rsb_"+row_id).data("odata", $("#rsb_"+row_id).val());
      $("#rs_"+row_id).data("odata", $("#rs_"+row_id).val());
      $("#rsr_"+row_id).data("odata", $("#rsr_"+row_id).val());
      $("#rss_"+row_id).data("odata", $("#rss_"+row_id).val());
      $("#rsn_"+row_id).data("odata", $("#rsn_"+row_id).val());
      $("#extlab"+row_id).data("odata", ($("#extlab"+row_id).is(':checked')?"1":"0"));

      $("#confirm"+row_id).removeClass("badge-secondary");
      $("#confirm"+row_id).addClass("badge-warning");
      $("#confirm"+row_id).html("Confirm Pending");
      //console.log("confirm "+row_id);
    });

    $(".btn-lab-confirm").show();

  }
  else{
    alert("Not save, Please check.");
    $.notify("Not save, Please check.", "error");
    //console.log("confirm "+row_id);
  }

  $("#btn_save_lab_result").next(".spinner").hide();
  $("#btn_save_lab_result").show();

}

function confirmLabResultData(){
  var aData = {
      u_mode:"confirm_lab_result",
      uid:"<? echo $uid;?>",
      collect_date:"<? echo $collect_date;?>",
      collect_time:"<? echo $collect_time;?>",
      s_id_confirm:$("#sel_confirm_by").val()
  };

  save_data_ajax(aData,"lab/db_lab_test_result.php",confirmLabResultDataComplete);
  $("#btn_confirm_lab_result").next(".spinner").show();
  $("#btn_confirm_lab_result").hide();
}
function confirmLabResultDataComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

    //  $.notify("ยืนยันผลแล๊ป "+rtnDataAjax.ttl_confirm_affect+" รายการ สำเร็จแล้ว.", "success");
      $(".time-confirm").each(function(ix,objx){
          if($(objx).html() == "Confirm Pending") {
            $(objx).removeClass("badge");
            $(objx).removeClass("badge-warning");
            $(objx).html(rtnDataAjax.time_confirm);
          }
      });
      $(".btn-lab-confirm").hide();

      if(rtnDataAjax.confirm_row > 0){
        $.notify("ยืนยันผล "+rtnDataAjax.confirm_row+" รายการ", "success");
      }

    if(rtnDataAjax.ttl_wait_confirm > 0){
      $.notify("รอการยืนยันผลอีก "+rtnDataAjax.ttl_wait_confirm+" รายการ", "info");
    }



  }
  $("#btn_confirm_lab_result").next(".spinner").hide();
  $("#btn_confirm_lab_result").show();
}





</script>

<?
/*
include_once("../in_savedata.php");
include_once("../inc_foot_include.php");
include_once("../function_js/js_fn_validate.php");
*/
?>
