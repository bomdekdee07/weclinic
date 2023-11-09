
<?
//session_start();
include_once("../in_auth.php");


$user_id = (isset($_SESSION["sc_id"]))?$_SESSION["sc_id"]:"";

// FORM LOG : Adverse Event
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
$is_lastest_visit = isset($_GET["is_lastest_visit"])?$_GET["is_lastest_visit"]:"N";

$open_visit = isset($_GET["open_visit"])?$_GET["open_visit"]:""; // open from main visit

//echo "open_visit $open_visit <br>";
$dom_id = "con_med";
$form_id = "con_med";
$form_name = "Concomitant Medication";

$visit_info="วันที่: $visit_date [UID: $uid] <br>Project ID: $project_id / VISIT: $visit_id";

$num_date = (int) (new DateTime())->format('d');
$num_month = (int) (new DateTime())->format('m');
$thai_year = (int)(new DateTime())->format('Y') + 543;
$today_date_th = "$num_date/$num_month/$thai_year";


//echo "auth log:".$auth['log'];

/*
$opt_1 = '
<option value="" selected disabled class="text-secondary">- เลือก -</option>
<option value="mg" >mg (มิลลิกรัม)</option>
<option value="mcg" >mcg (ไมโครกรัม)</option>
<option value="TAB" >TAB (เม็ด)</option>
<option value="Gtt" >Gtt (หยด)</option>
<option value="U" >U (หน่วย)</option>
<option value="CAP" >CAP (แคปซูล)</option>
<option value="mEq" >mEq (มิลลิอิควิวาเลนท์)</option>
<option value="IU" >IU (หน่วยสากล)</option>
<option value="G" >G (กรัม)</option>
<option value="ml" >ml (มิลลิลิตรหรือซีซี)</option>
';

$opt_2 = '
<option value="" selected disabled class="text-secondary">- เลือก -</option>
<option value="PO" >PO (ทางปาก)  </option>
<option value="IM" >IM (ฉีดยาเข้าชั้นกล้ามเนื้อ) </option>
<option value="IV" >IV (ฉีดยาเข้าทางหลอดเลือดดำ) </option>
<option value="SQ" >SQ (ฉีดยาเข้าชั้นใต้ผิวหนัง) </option>
<option value="Rectal" >Rectal (เหน็บทางทวารหนัก) </option>
<option value="Topical" >Topical (ทางผิวหนัง) </option>
<option value="Nasal" >Nasal (ทางจมูก) </option>
<option value="Inhale" >Inhale (ทางการสูดดม) </option>
<option value="Other" >Other อื่นๆ</option>
';
*/

$opt_type = '<option value="" selected disabled class="text-secondary">เลือก (ยาสำหรับ)</option><option value="STI" >STI โรคติดต่อทางเพศสัมพันธ์</option><option value="STI NG" >STI NG หนองใน</option><option value="STI CT" >STI CT หนองในเทียม</option><option value="SYP" >SYP ซิฟิลิส</option><option value="HIV" >HIV or ARV เอชไอวี หรือยาต้านไวรัส</option><option value="PrEP" >PrEP เพร็พ</option><option value="PrEP3" >PrEP3 เพร็พ3</option><option value="PEP" >PEP n-PEP</option><option value="PrEPOD" >PrEP On Demand</option><option value="Hormone" >Hormone</option><option value="Other" >Other อื่นๆ  </option>';

$opt_1 = '<option value="" selected disabled class="text-secondary">เลือก (หน่วยยา)</option><option value="mg" >mg (มิลลิกรัม)</option><option value="mcg" >mcg (ไมโครกรัม)</option><option value="TAB" >TAB (เม็ด)</option><option value="Gtt" >Gtt (หยด)</option><option value="U" >U (หน่วย)</option><option value="CAP" >CAP (แคปซูล)</option><option value="mEq" >mEq (มิลลิอิควิวาเลนท์)</option><option value="IU" >IU (หน่วยสากล)</option><option value="G" >G (กรัม)</option><option value="ml" >ml (มิลลิลิตรหรือซีซี)</option><option value="MU" >MU (million units)</option>';

$opt_2 = '<option value="" selected disabled class="text-secondary">เลือก (วิธีการได้รับยา)</option><option value="PO" >PO (ทางปาก)  </option><option value="IM" >IM (ฉีดยาเข้าชั้นกล้ามเนื้อ) </option><option value="IV" >IV (ฉีดยาเข้าทางหลอดเลือดดำ) </option><option value="SQ" >SQ (ฉีดยาเข้าชั้นใต้ผิวหนัง) </option><option value="Rectal" >Rectal (เหน็บทางทวารหนัก) </option><option value="Topical" >Topical (ทางผิวหนัง) </option><option value="Nasal" >Nasal (ทางจมูก) </option><option value="Inhale" >Inhale (ทางการสูดดม) </option><option value="Other" >Other อื่นๆ</option>';

//$opt_3 = '<option value="" selected disabled class="text-secondary">- เลือก (ความถี่) -</option><option value="PRN" >PRN -ใช้เมื่อมีอาการ  </option><option value="OD" >OD -ทุกวัน </option><option value="Weekly" >Weekly -สัปดาห์ละวัน </option><option value="Bi-Weekly" >Bi-Weekly -สัปดาห์เว้นสัปดาห์ </option><option value="Monthly" >Monthly -เดือนละครั้ง </option><option value="Other" >Other -อื่นๆ (ระบุ) </option>';

$opt_3 = '<option value="" selected disabled class="text-secondary">- เลือก (ความถี่) -</option><option value="PRN" >PRN -ใช้เมื่อมีอาการ  </option><option value="OD" >OD -วันละ 1 ครั้ง  </option><option value="BID" >BID -วันละ 2 ครั้ง  </option><option value="TID" >TID -วันละ 3 ครั้ง  </option><option value="QID" >QID -วันละ 4 ครั้ง  </option><option value="Single dose" >Single dose -ครั้งเดียว  </option><option value="Weekly" >Weekly -สัปดาห์ละวัน </option><option value="Bi-Weekly" >Bi-Weekly -สัปดาห์เว้นสัปดาห์ </option><option value="Monthly" >Monthly -เดือนละครั้ง </option><option value="HS" >HS -ก่อนนอน </option><option value="Other" >Other -อื่นๆ (ระบุ) </option>';




include_once("../in_db_conn.php");
  $query = "SELECT visit_date as last_visit_date FROM p_project_uid_visit
  WHERE uid=?
  ORDER BY visit_date DESC LIMIT 1";
//echo "$form_id/$query";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("s", $uid);
  if($stmt->execute()){
      $stmt->bind_result($last_visit_date);
      if ($stmt->fetch()) {

      }// if
      else{
        $last_visit_date = "0000-00-00";
      }
  }
  else{
    $msg_error .= $stmt->error;
  }
  $stmt->close();
  $mysqli->close();
?>


<div id="div_form_log" class='card'>
  <div class="card-header my-2 bg-warning">
    <div class="row">
       <div class="col-sm-6">
         <div><h4>แบบบันทึกรายการยาที่ใช้ร่วมกัน (Concomitant Medication)</h4></div>
         <div><? echo $visit_info; ?></div>
         <small>กรุณาลงรายละเอียดชนิดของยาอื่นๆทุกชนิดที่อาสาสมัครได้รับ</small>
       </div>
       <div class="col-sm-5">
         <div class="row ">
            <div class="col-sm-8">
              <label for="sel_sti_add" >ยาสำหรับโรคติดต่อทางเพศสัมพันธ์</label>
              <select id="sel_sti_add" class="form-control form-control-sm" >
                <option value="" selected disabled class="text-secondary">- เลือก -</option>
                <option value="Cefixime=400=mg=PO" >Cefixime	400 mg	PO</option>
                <option value="Azithromycin=1000=mg=PO" >Azithromycin	1000 mg	PO</option>
                <option value="Ceftriaxone=250=mg=IM" >Ceftriaxone 	250 mg	IM</option>
                <option value="Ceftriaxone=500=mg=IM" >Ceftriaxone 	500 mg	IM</option>
                <option value="Ceftriaxone=1000=mg=IM" >Ceftriaxone 1000 mg	IM</option>
                <option value="Doxycycline=200=mg=PO" >Doxycycline 	200 mg	PO</option>
                <option value="Benzathine Penicillin G=2.4=MU=IM" >Benzathine Penicillin G 	2.4 MU	IM</option>              </select>
            </div>
            <div class="col-sm-2 pl-0">
              <label for="btn_add_sti" class="text-warning">.</label>
              <button id="btn_add_sti" class="btn btn-sm btn-info btn-block btn-add-log" type="button">
                <i class="fa fa-plus" ></i> เพิ่ม STI
              </button>
            </div>
          </div>

       </div>
       <div class="col-sm-1">
         <button id="btn_close_form_log" class="form-control form-control-sm btn btn-danger btn-sm" type="button">
           <i class="fa fa-times fa-lg" ></i> ปิด <? echo $form_id; ?>
         </button>
       </div>
    </div>


  </div>
  <div class="card-body">
    <table id="tbl_form_log" class="table table-bordered table-sm table-striped table-hover">
        <thead>
          <tr>
            <th>Visit Date <br>
              <center>
                <button id="btn_add_log" class="btn btn-success btn-sm btn-add-log" type="button">
                  <i class="fa fa-plus-square fa-lg" ></i> เพิ่มรายการยา
                </button>
              </center>
            </th>
            <th>ยาสำหรับ </th>
            <th>
                ชื่อยา (ชื่อสามัญ) <br>
                ปริมาณยาได้รับ-หน่วยยา-ความถี่
            </th>

            <th>วิธีการได้รับยา <br>
                วิธีอื่นๆ (ระบุ)
            </th>
            <th>วันที่เริ่มยา<br>
                วันที่หยุดยา<br><small>(วว/ดด/ปปปป)</small>
            </th>

            <th>หมายเหตุอื่นๆ <br>(Remark)</th>
            <th>Initial Staff</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

  </div>
  <div class="card-footer">
    <!--
    <button id="btn_save_log" class="btn btn-danger btn-sm" type="button">
      <i class="fa fa-save fa-lg" ></i> บันทึกข้อมูล
    </button>
  -->
  </div>


</div>

<input type="hidden" id="ttl_amt_log">
<input type="hidden" id="cur_seq_no">


<script>
$(document).ready(function(){



  $("#ttl_amt_log").val(0);
  initDataFormLog();

/*
  var fixHelperModified = function(e, tr) {
      var $originals = tr.children();
      var $helper = tr.clone();
      $helper.children().each(function(index) {
          $(this).width($originals.eq(index).width())
      });
      return $helper;
  },
  updateIndex = function(e, ui) {
      $('td.row_num', ui.item.parent()).each(function (i) {
          $(this).html(i + 1);
      });
  };

  $('#tbl_form_log tbody').sortable({
      helper: fixHelperModified,
      stop: updateIndex
  }).disableSelection();
*/



  $("#btn_add_log").click(function(){
    <?
      if(!isset($auth["log"])){
        echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
        echo "return;";
      }
    ?>

     addFormLog();
  }); // btn_add_log

  $("#btn_add_sti").click(function(){
    <?
      if(!isset($auth["log"])){
        echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
        echo "return;";
      }
    ?>

     addMedSTI();
  }); // btn_add_log
/*
  $("#btn_save_log").click(function(){
     saveFormLog();
  }); // btn_add_log
*/
  $("#btn_close_form_log").click(function(){
     //closeFormLog();
     checkFormComplete();
  }); // btn_close_form_log



});

/*
function initDataFormLog(){
   selectListFormLog();
}
*/
function initDataFormLog(){
  <?
  if($is_lastest_visit == "N"){
    echo "$('.btn-add-log').hide();";
  }
  ?>
  /*
   if($('#visit_log_enable').val() == "0"){ // disable insert button only visit status in 1
     $('.btn-add-log').hide();
   }
   */
   selectListFormLog();
}

function changeMedFor(row_id){
  var sel_choice = $('#'+row_id+'med_for').val();
  if(sel_choice == "Other"){
    $('#'+row_id+'med_for_other').prop('disabled', false);
  }
  else{ // not other
    $('#'+row_id+'med_for_other').val("");
    $('#'+row_id+'med_for_other').prop('disabled', true);
  }
}

function addFormLog(){
  var last_visit_date = changeToThaiDate("<? echo $last_visit_date; ?>");
  var k = parseInt($("#ttl_amt_log").val())+1;
  var txt_row = '<tr id="r_log_'+k+'"  class="r_log">';

  txt_row += '<td width=70px>';

  txt_row += '<input id="'+k+'collect_date" data-id="collect_date" data-row="'+k+'" data-odata="" type="text" maxlength="8" size="8" class="save-data-'+k+' chk-odata v_date" data-title="collect_date" value="'+last_visit_date+'" disabled>';
  txt_row += '<button id="'+k+'btn_remove_log" class="form-control form-control-sm btn btn-danger btn-sm" type="button" onclick="removeLog(\''+k+'\')"> ';
  txt_row += '<i class="fa fa-times fa-lg" ></i> ลบ</button></td>';

  txt_row += '<td width=150px><select id="'+k+'med_for" data-id="med_for" data-row="'+k+'" data-odata="" class="sel-med-for form-control form-control-sm save-data-'+k+'" onchange="changeMedFor(\''+k+'\')" >';
  txt_row += '<? echo $opt_type; ?> </select>';

  txt_row += '<div class="mt-1">';
  txt_row += '<input id="'+k+'med_for_other" data-id="med_for_other" data-row="'+k+'" data-odata="" type="text" class="save-data-'+k+' chk-odata text-primary" placeholder="ระบุสาเหตุใช้ยา" data-title="ระบุสาเหตุใช้ยา" maxlength="50" size="15" disabled>';
  txt_row += '</div></td>';

  txt_row += '<td width=500px><div><input id="'+k+'generic_name" data-id="generic_name" data-row="'+k+'" data-odata="" type="text" class="form-control form-control-sm save-data-'+k+' v-no-blank chk-odata" placeholder="ชื่อยา (ชื่อสามัญ) " data-title="ชื่อยา (ชื่อสามัญ) "></div>';
  txt_row += '<div class="mt-1"><input id="'+k+'dosage" data-id="dosage" data-row="'+k+'" data-odata="" type="text" class="save-data-'+k+' v-no-blank chk-odata" placeholder="ปริมาณยาที่ได้รับ" data-title="ปริมาณยาที่ได้รับ" maxlength="30" size="10"> ';
  txt_row += '<select id="'+k+'dosage_unit" data-id="dosage_unit"  class="save-data-'+k+'" >';
  txt_row += '<? echo $opt_1; ?></select>';
  txt_row += '<input id="'+k+'dosage_unit_other" data-id="dosage_unit_other" data-row="'+k+'" data-odata="" type="text" class="save-data-'+k+' chk-odata text-primary" placeholder="ระบุหน่วยยาอื่นๆ" data-title="ระบุหน่วยยาอื่นๆ" maxlength="50" size="15">';

  txt_row += '</div>';

  txt_row += '<div class="mt-1">';
  txt_row += '<select id="'+k+'dosage_freq" data-id="dosage_freq"  class="save-data-'+k+'" >';
  txt_row += '<? echo $opt_3; ?></select>';
  txt_row += '<input id="'+k+'dosage_freq_other" data-id="dosage_freq_other" data-row="'+k+'" data-odata="" type="text" class="save-data-'+k+' chk-odata text-primary" placeholder="ระบุความถี่อื่นๆ" data-title="ระบุความถี่อื่นๆ" maxlength="50" size="15">';

  txt_row += '</div></td>';



  txt_row += '<td><div><select id="'+k+'route" data-id="route" data-row="'+k+'" data-odata="" class="form-control form-control-sm save-data-'+k+' chk-odata" >';
  txt_row += '<? echo $opt_2; ?>';
  txt_row += '</select></div>';
  txt_row += '<div class="mt-1"><input id="'+k+'route_text" data-id="route_text" data-row="'+k+'" data-odata="" type="text" class="form-control form-control-sm save-data-'+k+' chk-odata" placeholder="วิธีรับยาอื่นๆ (ระบุ)" data-title="วิธีรับยาอื่นๆ (ระบุ)"></div></td>';
  txt_row += '<td width=100px><div><input id="'+k+'startdate" data-id="startdate" data-row="'+k+'" data-odata="" type="text" class="form-control form-control-sm save-data-'+k+' chk-odata bg-success v_partial_date" placeholder="วันที่เริ่มยา dd/mm/yyyy" data-title="วันที่เริ่มยา " onfocus="initPartialDate(this);" onfocusout="checkPartialDate(this);"></div>';
  txt_row += '<div class="mt-1"><input id="'+k+'stopdate" data-id="stopdate" data-row="'+k+'" data-odata="" type="text" class="c_stop_date form-control form-control-sm save-data-'+k+' chk-odata bg-warning v_partial_date" placeholder="วันที่หยุดยา dd/mm/yyyy" data-title="วันที่หยุดยา onfocus="initPartialDate(this);" onfocusout="checkPartialDate(this);" ></div></td>';
  txt_row += '<td><textarea rows="3" cols="50" id="'+k+'use_specific" data-id="use_specific" data-row="'+k+'" data-odata="" type="text" class="form-control form-control-sm save-data-'+k+' chk-odata"  placeholder="หมายเหตุ/Remark " data-title="หมายเหตุ/Remark "></textarea></td>';
  txt_row += '<td><button id="'+k+'btn_log_info" class="btn btn-sm btn-info" onclick="infoFormLog(\''+k+'\')"><span id="'+k+'row_log_info"></span></button>';

  txt_row += '<input id="'+k+'seq_no" data-id="seq_no" type="hidden" class="save-data-'+k+'" data-title="seq_no" value="">';

  txt_row += '</td>';
  txt_row += '<td><button id="'+k+'btn_save_log" class="btn_save_log form-control form-control-sm btn btn-danger btn-sm" type="button" onclick="saveFormLog(\''+k+'\')"> ';
  txt_row += '<i class="fa fa-check fa-lg" ></i> SAVE</button></td>';
  txt_row += "</tr>";

  //  $('#tbl_form_log > tbody:last-child').append(txt_row);
  $('#tbl_form_log > tbody').prepend(txt_row);
  $("#ttl_amt_log").val(k);

  $("#"+k+"startdate").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});
  $("#"+k+"stopdate").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});

}



function addMedSTI(){ // add Med STI Template

  var k = parseInt($("#ttl_amt_log").val())+1;
  addFormLog();

  stiOpt = $("#sel_sti_add").val();
  var arrStiOpt = stiOpt.split("=");

  $("#"+k+"med_for").val("STI");
  $("#"+k+"generic_name").val(arrStiOpt[0]);
  $("#"+k+"dosage").val(arrStiOpt[1]);
  $("#"+k+"dosage_unit").val(arrStiOpt[2]);
  $("#"+k+"route").val(arrStiOpt[3]);
}


function removeLog(rowID){
  <?
    if(!isset($auth["log"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }
  ?>

    if (confirm("ต้องการที่จะลบข้อมูลที่เลือก ใช่หรือไม่")) {
      if($("#"+rowID+"seq_no").val()!= ""){ // server remove
        var collectDate = getDataObjValue($("#"+rowID+"collect_date"));
         var aData = {
                   u_mode:"remove_data",
                   dom_id:"con_med",
                   uid:'<? echo $uid; ?>',
                   seq_no:$("#"+rowID+"seq_no").val(),
                   collect_date:collectDate,
                   row_id:rowID
         };

         //alert("Pending this function");
         save_data_ajax(aData,"visit_form_log/db_form_log_data.php",removeLogComplete);
      }
      else{ // client remove
        $('#r_log_'+rowID).remove();
      }


    } // confirm remove

} // remove


function removeLogComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $.notify("ลบข้อมูลที่เลือกใน <? echo $form_name; ?> แล้ว ", "info");
    $('#r_log_'+aData.row_id).remove();
  }
}


function infoFormLog(rowID){
     var collectDate = getDataObjValue($("#"+rowID+"collect_date"));
      var aData = {
                u_mode:"row_info_log",
                dom_id:"con_med",
                uid:'<? echo $uid; ?>',
                seq_no:$("#"+rowID+"seq_no").val(),
                collect_date:collectDate,
                row_id:rowID
      };
      //alert("Pending this function");
      save_data_ajax(aData,"visit_form_log/db_form_log_data.php",infoFormLogComplete);
}


function infoFormLogComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
  //  $('#r_log_'+aData.row_id).remove();
      myModalContent("Form Log", rtnDataAjax.form_log, "info");
  }
}

function selectListFormLog(){
  var aData = {
            u_mode:"select_list_visit",
            dom_id:'<? echo $dom_id; ?>',
            uid:'<? echo $uid; ?>',
            visit_id:'<? echo $visit_id; ?>',
            project_id:'<? echo $project_id; ?>',
            group_id:'<? echo $group_id; ?>',
            visit_date:'<? echo $visit_date; ?>'
  };

  //alert("Pending this function");
  save_data_ajax(aData,"visit_form_log/db_form_log_data.php",selectListFormLogComplete);

}

function selectListFormLogComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

    var last_visit_date = Date.parse("<? echo $last_visit_date; ?>");
    var datalist = rtnDataAjax.datalist;
    $("#ttl_amt_log").val(0);
    $('.r_log').remove(); // remove all row
    if(datalist.length > 0){

      for (i = 0; i < datalist.length; i++) {
        $("#ttl_amt_log").val(i);

        addFormLog();
        var k = i+1;
        var dataObj = datalist[i];
        $("#"+k+"med_for").val(dataObj.med_for);
        $("#"+k+"med_for_other").val(dataObj.med_for_other);
        $("#"+k+"generic_name").val(dataObj.generic_name);
        $("#"+k+"dosage").val(dataObj.dosage);
        $("#"+k+"dosage_unit").val(dataObj.dosage_unit);
        $("#"+k+"dosage_unit_other").val(dataObj.dosage_unit_other);

        $("#"+k+"dosage_freq").val(dataObj.dosage_freq);
        $("#"+k+"dosage_freq_other").val(dataObj.dosage_freq_other);

        $("#"+k+"route").val(dataObj.route);
        $("#"+k+"route_text").val(dataObj.route_text);
        $("#"+k+"startdate").val(dataObj.startdate);
        $("#"+k+"stopdate").val(dataObj.stopdate);
        $("#"+k+"use_specific").val(dataObj.use_specific);


        $("#"+k+"row_log_info").html(dataObj.initial_staff);
        $("#"+k+"seq_no").val(dataObj.seq_no);
        $("#"+k+"collect_date").val(changeToThaiDate(dataObj.collect_date));

        // set old data
        $("#"+k+"med_for").data('odata',dataObj.med_for);
        $("#"+k+"generic_name").data('odata',dataObj.generic_name);
        $("#"+k+"dosage").data('odata',dataObj.dosage);
        $("#"+k+"dosage_unit").data('odata',dataObj.dosage_unit);
        $("#"+k+"route").data('odata',dataObj.route);
        $("#"+k+"route_text").data('odata',dataObj.route_text);
        $("#"+k+"startdate").data('odata',dataObj.startdate);
        $("#"+k+"stopdate").data('odata',dataObj.stopdate);
        $("#"+k+"use_specific").data('odata',dataObj.use_specific);

        $("#"+k+"dosage_freq").data('odata',dataObj.dosage_freq);
        $("#"+k+"dosage_freq_other").data('odata',dataObj.dosage_freq_other);
        $("#"+k+"med_for_other").data('odata',dataObj.med_for_other);


        $("#"+k+"collect_date").data('odata',changeToThaiDate(dataObj.collect_date));

        $("#"+k+"btn_save_log").removeClass("btn-danger");

        if(dataObj.med_for != "Other")
        $('#'+k+'med_for_other').prop('disabled', false);
        else $('#'+k+'med_for_other').prop('disabled', true);


        var log_collect_date = Date.parse(dataObj.collect_date);

//alert("lastvisitdate : "+last_visit_date+"/"+dataObj.collect_date);

/*
        // lock to save if collect date is older than last visit
        if(log_collect_date < last_visit_date){
           // ถ้าวันหยุดยา ไม่ได้ใส่มาก็ยังให้ save ได้
           $(".c_stop_date").each(function(ix,objx){
             var k = $(objx).data("row");
             if($(objx).val().trim() == ''){
                  $("#"+k+"btn_save_log").addClass("btn-success");
                  $("#"+k+"btn_remove_log").show();
             }
             else{
               $("#"+k+"btn_save_log").addClass("btn-secondary");
               $("#"+k+"btn_save_log").prop('disabled', true);
               $("#"+k+"btn_remove_log").hide();
             }
           });
        }
        else{
           $("#"+k+"btn_save_log").addClass("btn-success");
           $("#"+k+"btn_remove_log").show();
        }
        */

        // unlock to remove if collect date = last visit
        $("#"+k+"btn_save_log").addClass("btn-success");
        if(log_collect_date == last_visit_date){
          $("#"+k+"btn_remove_log").show();
        }
        else{
           $("#"+k+"btn_remove_log").hide();
        }

        /*
        $("#"+k+"btn_save_log").addClass("btn-success");
        $("#"+k+"btn_remove_log").show();
        */
      }//for
    }


  }
}



function saveFormLog(rowID){

  <?
    if(!isset($auth["log"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }
  ?>

  $("#cur_seq_no").val(rowID);
  var uMode = "update_data";
  if($("#"+rowID+"seq_no").val() == ""){
    uMode = "add_data";
  }
//alert("save :"+$("#"+rowID+"seq_no").val()+"/"+uMode);
  var divSaveData = "#div_form_log";
  var lst_data_obj = [];

  var seqNo = 0;

  $(divSaveData +" .save-data-"+rowID).each(function(ix,objx){
//alert("enter1 "+seqNo);
     // collect data
      if($(objx).hasClass("v_radio")){ // radio button opt data
        if($(objx).prop("checked")){
          var objData = {
            name: $(objx).data("id"),
            value:$(objx).val()
          }
          //alert("check : "+$(objx).data("id")+"/"+$(objx).val());
          lst_data_obj.push(objData);
        }
      }
      else{ // not radio button
        var objVal = getDataObjValue($(objx));
        var objData = {
          name: $(objx).data("id"),
          value:objVal
        }

        if(uMode == "update_data"){
          lst_data_obj.push(objData);
        }
        else{ // add data not include seq_no value
          if($(objx).data("id") != "seq_no"){
            lst_data_obj.push(objData);
          }

        }

      }


  });



  var aData = {
            u_mode:uMode,
            dom:"con_med",
            uid:'<? echo $uid; ?>',
            visit_id:'<? echo $visit_id; ?>',
            project_id:'<? echo $project_id; ?>',
            group_id:'<? echo $group_id; ?>',
            dom_id:'<? echo $dom_id; ?>',
            visit_date:'<? echo $visit_date; ?>',
            lst_data:lst_data_obj
  };

  //alert("Pending this function");
  save_data_ajax(aData,"visit_form_log/db_form_log_data.php",saveFormLogComplete);

}

function saveFormLogComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

    $.notify("บันทึกข้อมูล<? echo $form_name; ?>แล้ว ", "info");
    var k = $("#cur_seq_no").val();
  //  alert("k:"+k);
  //  alert("enter here "+rtnDataAjax.seq_no+"/<? echo $user_id; ?>-"+aData.u_mode);
    if(aData.u_mode == "add_data"){
      $("#"+k+"seq_no").val(rtnDataAjax.seq_no);
      $("#"+k+"row_log_info").html("<? echo $user_id; ?>");
    }
    else if(aData.u_mode == "update_data"){

    }

    $(".save-data-"+k).each(function(ix,objx){
    //  alert("set odata :"+$(objx).val()+"/"+$(objx).data("odata"));
      $(objx).data("odata", $(objx).val());
    });

    // change save btn to already save (green)
    $("#"+k+"btn_save_log").removeClass("btn-danger");
    $("#"+k+"btn_save_log").addClass("btn-success");

  }
}



function checkFormComplete(){
//alert("odata : checkFormCompletes <? echo $open_visit;?>");
  var flag = 0;
  var divSaveData = "#div_form_log";
  $(divSaveData +" .chk-odata").each(function(ix,objx){
    if($(objx).data("odata") != $(objx).val()){
      flag = 1;
       //alert("odata : "+$(objx).data("odata")+"/"+$(objx).data("row")+"/"+$(objx).val());
       $("#"+$(objx).data("row")+"btn_save_log").removeClass("btn-success");
       $("#"+$(objx).data("row")+"btn_save_log").addClass("btn-danger");
    }

  });

 if(flag == 1){ // there is some change
   if (confirm("มีการเปลี่ยนแปลงข้อมูลแต่ยังไม่ได้บันทึก  ท่านยืนยันที่จะปิดหรือไม่")) {
      <?
         if($open_visit == '1'){
           echo "closeFormLog2();";
         }
         else{
           echo "closeFormLog();";
         }
      ?>
      //closeFormLog();
    }
 }
 else{
   <?
      if($open_visit == '1'){
        echo "closeFormLog2();";
      }
      else{
        echo "closeFormLog();";
      }
   ?>
   //closeFormLog();
 }


}

</script>
