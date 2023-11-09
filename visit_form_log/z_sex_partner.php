
<?
include_once("../in_auth.php");


$user_id = (isset($_SESSION["sc_id"]))?$_SESSION["sc_id"]:"";

// FORM LOG : Adverse Event
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";

$open_visit = isset($_GET["open_visit"])?$_GET["open_visit"]:""; // open from main visit

//echo "open_visit $open_visit <br>";
$dom_id = "sex_partner";
$form_id = "sex_partner";
$form_name = "Concomitant Medication";

$visit_info="วันที่: $visit_date [UID: $uid] <br>Project ID: $project_id / VISIT: $visit_id";

$num_date = (int) (new DateTime())->format('d');
$num_month = (int) (new DateTime())->format('m');
$thai_year = (int)(new DateTime())->format('Y') + 543;
$today_date_th = "$num_date/$num_month/$thai_year";


//echo "auth log:".$auth['log'];



$opt_partner_relate = '<option value="" selected disabled class="text-secondary">- เลือก -</option><option value="1" >คู่นอนประจำ</option><option value="2" >คู่ชั่วคราว</option><option value="3" >พนักงานบริการ</option><option value="4" >ผู้ใช้บริการ (ให้เงินเพื่อแลกกับการมีเพศสัมพันธ์)</option>';
$opt_hiv_result = '<option value="" selected disabled class="text-secondary">- เลือก -</option><option value="NR" >ลบ  </option><option value="R" >บวก </option><option value="NS" >ไม่ทราบผล</option>';
$opt_gender = '<option value="" selected disabled class="text-secondary">- เลือก -</option><option value="M">ชาย </option><option value="F">หญิง </option><option value="TGW">ชายข้ามเพศเป็นหญิง</option><option value="TGF">หญิงข้ามเพศเป็นชาย</option>';
$opt_partner_thai = '<option value="" selected disabled class="text-secondary">- เลือก -</option><option value="Y">ใช่ </option><option value="N">ไม่ใช่ </option>';

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


<div id="div_form_log" class='card div-log'>
  <div class="card-header my-2 px-0 bg-warning">
    <div class="row">
       <div class="col-sm-8">
         <div><h4>แบบบันทึกคู่นอนที่มีเพศสัมพันธ์ด้วย (Sex Partner Record)</h4></div>
         <small>กรุณาลงรายละเอียดคู่นอนที่เคสมีเพศสัมพันธ์ด้วย</small>
       </div>
       <div class="col-sm-2">
         <button id="btn_add_log" class="btn btn-success btn-sm" type="button">
           <i class="fa fa-plus-square fa-lg" ></i> เพิ่มคู่นอน
         </button>
       </div>

       <div class="col-sm-2">
         <button id="btn_close_form_log" class="form-control form-control-sm btn btn-danger btn-sm" type="button">
           <i class="fa fa-times fa-lg" ></i> ปิด <? echo $form_id; ?>
         </button>
       </div>
    </div>


  </div>
  <div class="card-body px-0">
    <table id="tbl_form_log" class="table table-bordered table-sm table-striped table-hover">
        <thead>
          <tr>
            <th>Collect Date</th>
            <th>ชื่อเรียก</th>
            <th>อายุ</th>
            <th>คนไทย?</th>
            <th>ความสัมพันธ์คู่นอน</th>
            <th>เพศ</th>
            <th>ผลเลือด HIV</th>
            <th>หมายเหตุ</th>
            <th>ประวัติการมีเพศสัมพันธ์ุ</th>
            <th>Initial Staff</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

  </div>
  <div class="card-footer">

  </div>
</div>


<div id="div_form_log_detail" class="่div-log">
  <div class="row py-2 bg-primary text-white">
     <div class="col-sm-11">
       <h5><i class="fa fa-bed fa-lg" ></i> ข้อมูลประวัติการมีเพศสัมพันธ์</h5>
     </div>
     <div class="col-sm-1">
       <button id="btn_close_partner_sex_history" class="form-control form-control-sm btn btn-white btn-sm" type="button">
         <i class="fa fa-times fa-lg" ></i> ปิด
       </button>
     </div>
   </div>
   <div id="div_form_log_detail_data">
     detail
   </div>

</div>

<input type="hidden" id="ttl_amt_log">
<input type="hidden" id="cur_row_id">


<script>
$(document).ready(function(){





  $("#ttl_amt_log").val(0);
  initDataFormLog();


  $("#btn_close_partner_sex_history").click(function(){
     //closeFormLog();
     showDivLog("form_log");
     $("#div_form_log_detail").hide();
     showSexHistory($("#cur_row_id").val());
     /*
     $('html, body').animate({
        scrollTop: $("#r_log_div_"+$("#cur_row_id").val()).offset().top
     }, 500);
     */

  }); // btn_close_partner_sex_history

  $("#btn_add_log").click(function(){
    <?
      if(!isset($auth["log"])){
        echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
        echo "return;";
      }
    ?>

     addFormLog();
  }); // btn_add_log

  $("#btn_close_form_log").click(function(){
     //closeFormLog();
     checkFormComplete();
  }); // btn_close_form_log

});


function initDataFormLog(){
  showDivLog("form_log");
  $("#div_form_log_detail").hide();

   if($('#visit_log_enable').val() == "0"){ // disable insert button only visit status in 1
     //$('.btn-add-log').hide();
   }
   selectListFormLog();
}



function addFormLog(){
  var last_visit_date = changeToThaiDate("<? echo $last_visit_date; ?>");
  var k = parseInt($("#ttl_amt_log").val())+1;
  var txt_row = '<tr id="r_log_'+k+'"  class="r_log">';

  txt_row += '<td width=70px>';
  txt_row += '<input id="'+k+'collect_date" data-id="collect_date" data-row="'+k+'" data-odata="" type="text" maxlength="8" size="8" class="save-data-'+k+' chk-odata v_date" data-title="collect_date" value="'+last_visit_date+'" disabled>';
  txt_row += '<button id="'+k+'btn_remove_log" class="form-control form-control-sm btn btn-danger btn-sm" type="button" onclick="removeLog(\''+k+'\')"> ';
  txt_row += '<i class="fa fa-times fa-lg" ></i> ลบ</button>';
  txt_row += '</td>';

  txt_row += '<td width=150px>';
  txt_row += '<div>ชื่อเรียก: <input id="'+k+'partner_name" data-id="partner_name" data-row="'+k+'" data-odata="" type="text" class="form-control form-control-sm save-data-'+k+' v-no-blank chk-odata" placeholder="ชื่อเรียก " data-title="ชื่อเรียก "></div>';
  txt_row += '</td>';

  txt_row += '<td width=50px>';
  txt_row += '<div>อายุ: <input id="'+k+'age" data-id="age" data-row="'+k+'" data-odata="" type="text" class="save-data-'+k+' v-no-blank v-int chk-odata" placeholder="อายุ" data-title="อายุ" maxlength="2" size="5"></div>';
  txt_row += '</td>';

  txt_row += '<td width=120px>';
  txt_row += '<div>คนไทยหรือไม่?: <select id="'+k+'partner_thai" data-id="partner_thai" data-row="'+k+'" data-odata="" class="sel-partner-thai form-control form-control-sm save-data-'+k+'">';
  txt_row += '<? echo $opt_partner_thai; ?> </select></div>';
  txt_row += '</td>';

  txt_row += '<td width=200px>';
  txt_row += '<div>ความสัมพันธ์คู่นอน: <select id="'+k+'partner_relate" data-id="partner_relate" data-row="'+k+'" data-odata="" class="sel-partner-type form-control form-control-sm save-data-'+k+'">';
  txt_row += '<? echo $opt_partner_relate; ?> </select></div>';
  txt_row += '</td>';

  txt_row += '<td width=200px>';
  txt_row += '<div>เพศ: <select id="'+k+'gender" data-id="gender" data-row="'+k+'" data-odata="" class="form-control form-control-sm save-data-'+k+' chk-odata" >';
  txt_row += '<? echo $opt_gender; ?> </select></div>';
  txt_row += '</td>';

  txt_row += '<td width=120px>';
  txt_row += '<div>ผลเลือด: <select id="'+k+'hiv_result" data-id="hiv_result" data-row="'+k+'" data-odata="" class="form-control form-control-sm save-data-'+k+' chk-odata" >';
  txt_row += '<? echo $opt_hiv_result; ?> </select></div>';
  txt_row += '</td>';

  txt_row += '<td >';
  txt_row += '<textarea rows="2" cols="50" id="'+k+'remark" data-id="remark" data-row="'+k+'" data-odata="" type="text" class="form-control form-control-sm save-data-'+k+' chk-odata"  placeholder="หมายเหตุ/Remark " data-title="หมายเหตุ/Remark "></textarea>';
  txt_row += '</td>';

  txt_row += '<td width=120px>';
  txt_row += '<div class="mt-2"><button id="'+k+'btn_show_history" class="form-control form-control-sm btn btn-warning btn-sm" type="button" onclick="showSexHistory(\''+k+'\')"><i class="fa fa-kiss-wink-heart fa-lg" ></i> ประวัติเพศสัมพันธุ์</button>  </div>';
  txt_row += '</td>';

  txt_row += '<td width=100px>';
  txt_row += '<input id="'+k+'seq_no" data-id="seq_no" type="hidden" class="save-data-'+k+'" data-title="seq_no" value="">';
  txt_row += '<div><button id="'+k+'btn_save_log" class="btn_save_log form-control form-control-sm btn btn-danger btn-sm" type="button" onclick="saveFormLog(\''+k+'\')"> <i class="fa fa-check fa-lg" ></i> SAVE</button> </div>';
  txt_row += '<div class="mt-1"><button id="'+k+'btn_log_info" class="btn btn-sm btn-info" onclick="infoFormLog(\''+k+'\')"><span id="'+k+'row_log_info"></span></button></div>';
  txt_row += '</td>';
  txt_row += '</tr>';

    txt_row += '<tr >';
    txt_row += '<td colspan="10"><div id="r_log_div_'+k+'"  class="r_log_div" style="background-color:#FFF0F5;"></div></td>';
    txt_row += '</tr>';

  //  $('#tbl_form_log > tbody:last-child').append(txt_row);
  $('#tbl_form_log > tbody').prepend(txt_row);
  $("#ttl_amt_log").val(k);
}


function selectSexHist(rowID){
  var aData = {
            u_mode:"select_list",
            uid:"<? echo $uid; ?>",
            collect_date:changeToEnDate($("#"+rowID+"collect_date").val()),
            seq_no:$("#"+rowID+"seq_no").val(),
            row_id:rowID
  };

  //alert("Pending this function");
  save_data_ajax(aData,"visit_form_log/db_log_partner_sexhist.php",selectSexHistComplete);

}

function selectSexHistComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var txt_row = "";

    var datalist = rtnDataAjax.datalist;
    if(datalist.length > 0){

      for (i = 0; i < datalist.length; i++) {
        var dataObj = datalist[i];

        var txt_alc = "";
        var txt_drug = "";
        var txt_risk = "";


        if(dataObj.alc == "Y") txt_alc += "<i class='fa fa-check text-success'></i>";
        else if(dataObj.alc == "N") txt_alc += "<i class='fa fa-times text-danger'></i>";

        if(dataObj.druguse == "Y") txt_drug += "<i class='fa fa-check text-success'></i> <br>";
        else if(dataObj.druguse == "N") txt_drug += "<i class='fa fa-times text-danger'></i>";

        if(dataObj.drug_eat == "1") txt_drug += "ทาน ";
        if(dataObj.drug_smell == "1") txt_drug += "ดม ";
        if(dataObj.drug_smoke == "1") txt_drug += "สูบ ";
        if(dataObj.drug_inject == "1"){
          txt_drug += "ฉีด";
          if(dataObj.drug_needleshare == "Y"){
              txt_drug += "(ร่วมกับผู้อื่น) ";
          }
        }
        if(dataObj.drug_oth == "1"){
          txt_drug += "(อื่นๆ ";
          txt_drug += dataObj.drug_oth_spec+")";
        }

        if(dataObj.risk_evaluate == "H") txt_risk += "<i class='fa fa-thermometer-full text-danger'></i> สูง";
        else if(dataObj.risk_evaluate == "M") txt_risk += "<i class='fa fa-thermometer-half text-warning'></i> กลาง";
        else if(dataObj.risk_evaluate == "L") txt_risk += "<i class='fa fa-thermometer-empty text-success'></i> ต่ำ";


        txt_row += "<tr>";

        txt_row += "<td class='row_num"+aData.row_id+"' data-sexhist_no='"+dataObj.sexhist_no+"'>"+(i+1)+"</td>";

        txt_row += "<td><div><b>"+changeToThaiDate(dataObj.date)+"</b></div>";
        txt_row += '<div><button class="form-control form-control-sm btn btn-info btn-sm" type="button" onclick="openSexHist(\''+aData.row_id+'\',\''+dataObj.sexhist_no+'\',\''+dataObj.sexhist_seq_no+'\');"> <i class="fa fa-edit fa-lg" ></i> แก้ไข </div>';
    //    txt_row += '<div><button class="form-control form-control-sm btn btn-info btn-sm"  type="button" onclick="openSexHist(\''+aData.row_id+'\',\''+dataObj.sexhist_no+'\',\''+dataObj.sexhist_seq_no+'\');"> <i class="fa fa-edit fa-lg" ></i> แก้ไข </div>';

        txt_row += "</td>";

        txt_row += "<td>";
        txt_row += "<u>ดื่มแอลกอฮอล์</u>: "+txt_alc+"<br>";
        txt_row += "<u>ใช้ยาเสพติด</u>: "+txt_drug;
        txt_row += "</td>";

        txt_row += "<td align='center'>"+getInsertRecepTxt(1, dataObj.anal_insert, dataObj.anal_insert_condom, dataObj.anal_insert_nocondom)+"</td>";
        txt_row += "<td align='center'>"+getInsertRecepTxt(2, dataObj.anal_recep, dataObj.anal_recep_condom, dataObj.anal_recep_nocondom)+"</td>";

        txt_row += "<td align='center'>"+getInsertRecepTxt(1, dataObj.oral_insert, dataObj.oral_insert_condom, dataObj.oral_insert_nocondom)+"</td>";
        txt_row += "<td align='center'>"+getInsertRecepTxt(2, dataObj.oral_recep, dataObj.oral_recep_condom, dataObj.oral_recep_nocondom)+"</td>";

        txt_row += "<td align='center'>"+getInsertRecepTxt(1, dataObj.vagina_insert, dataObj.vagina_insert_condom, dataObj.vagina_insert_nocondom)+"</td>";
        txt_row += "<td align='center'>"+getInsertRecepTxt(2, dataObj.vagina_recep, dataObj.vagina_recep_condom, dataObj.vagina_recep_nocondom)+"</td>";

        txt_row += "<td align='center'>"+getInsertRecepTxt(1, dataObj.neovagina_insert, dataObj.neovagina_insert_condom, dataObj.neovagina_insert_nocondom)+"</td>";
        txt_row += "<td align='center'>"+getInsertRecepTxt(2, dataObj.neovagina_recep, dataObj.neovagina_recep_condom, dataObj.neovagina_recep_nocondom)+"</td>";

        txt_row += "<td>";
        txt_row += "<u>ความเสี่ยง</u>: "+txt_risk+"<br>";
        txt_row += "<u>หมายเหตุ</u>: "+dataObj.note;
        txt_row += "</td>";

        txt_row += "</tr>";
      }//for
    } // datalist length
    else{ // no data list (empty row)
         txt_row += "<tr><td colspan=11 align='center'>-ยังไม่มีข้อมูล-</td></tr>";
    }

    $('#tbl_sexhist'+aData.row_id+' > tbody:last-child').append(txt_row);
    $('html, body').animate({
       scrollTop: $("#r_log_div_"+aData.row_id).offset().top
    }, 500);

  }//flagSave
}


function getInsertRecepTxt(insert_recep_mode, insert_recep, insert_recep_condom, insert_recep_nocondom){
  var txtRtn = "";

  if(insert_recep == "Y"){
    txtRtn += "<i class='fa fa-check text-success'></i> ";

    if(insert_recep_mode == "1") txtRtn += "รุก <br>";
    else if(insert_recep_mode == "2") txtRtn += "รับ <br>";

    if(insert_recep_condom == "1") txtRtn += "ใช้ถุงทุกครั้ง ";
    else{
      if(insert_recep_condom == "2") txtRtn += "ใช้ถุงบางครั้ง ";
      else if(insert_recep_condom == "3") txtRtn += "ไม่เคยใช้ถุง ";

      if(insert_recep_nocondom == "Y") txtRtn += "<br><u>หลั่งใน</u> ";
      else if(insert_recep_nocondom == "N") txtRtn += "<br><u><span class='text-danger'>ไม่หลั่งใน</span></u> ";
    }
  }
  else txtRtn += "<i class='fa fa-times text-danger'></i>";

  return txtRtn;
}

function showSexHistory(rowID){
  //alert("showSexHistory " + rowID);
  <?
    if(!isset($auth["log"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }
  ?>

  var link = "visit_form_log/z_sex_partner_sexhist.php?";
  link += "partner_name="+$("#"+rowID+"partner_name").val(); // partner name
  link += "&row_id="+rowID; // row_id in table
//alert("loaded0 r_log_div_"+link);
  $("#r_log_div_"+rowID).load(link, function(){
    //alert("loaded r_log_div_"+rowID);
  });

/*
  var txt_row = '<center><button id="'+rowID+'btn_add_sexhist" class="btn_add_sexhist form-control form-control-sm btn btn-success btn-sm" type="button" onclick="openSexHist(\''+rowID+'\')" > <i class="fa fa-plus fa-lg" ></i> เพิ่มประวัติ</button></center>';
  $("#r_log_div_"+rowID).html(txt_row);
*/

} // showSexHistory

function closeSexHist(rowID){
  //alert("closeSexHist " + rowID);
  $("#r_log_div_"+rowID).html("");

} // closeSexHist


function openSexHist(rowID, sexhistNo, sexhistSeqNo){
  //alert("openSexHist " + rowID);
  <?
    if(!isset($auth["log"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }

  ?>

    var link = "visit_form/x_partner_sex_history.php?";
    link += "uid="+$('#cur_uid').val(); // uid
    link += "&seq_no="+$("#"+rowID+"seq_no").val(); // seq_no
    link += "&collect_date="+changeToEnDate($("#"+rowID+"collect_date").val()) ; // collect_date
    link += "&sexhist_no="+sexhistNo ; // sexhist_no
    link += "&sexhist_seq_no="+sexhistSeqNo ; // sexhist_seq_no

  //  alert("openUIDFormxx "+link);
  //  $('#div_form_log_detail').html("รอสักครู่");
  //  $('#form_title').html(formName);
    $("#div_form_log_detail_data").load(link, function(){
      //  alert("enter load "+link);
        showDivLog("form_log_detail");
        $("#cur_row_id").val(rowID);
    });
  //  showDivLog("form_log_detail");

} // openSexHist

function addSexHist(rowID){
  //alert("openSexHist " + rowID);
  <?
    if(!isset($auth["log"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }

  ?>

    var link = "visit_form/x_partner_sex_history.php?";
    link += "uid="+$('#cur_uid').val(); // uid
    link += "&seq_no="+$("#"+rowID+"seq_no").val(); // seq_no
    link += "&collect_date="+changeToEnDate($("#"+rowID+"collect_date").val()) ; // collect_date


    //alert("openUIDFormxx "+link);
  //  $('#div_form_log_detail').html("รอสักครู่");
    $("#div_form_log_detail_data").load(link, function(){
        //alert("enter load "+link);
        showDivLog("form_log_detail");
        $("#cur_row_id").val(rowID);
    });
  //  showDivLog("form_log_detail");

} // addSexHist


function updateSeqNoSexHist(rowID, arrSexHist){
  var aData = {
            u_mode:"update_seq_no_sexhist",
            uid:"<? echo $uid; ?>",
            collect_date:changeToEnDate($("#"+rowID+"collect_date").val()),
            seq_no:$("#"+rowID+"seq_no").val(),
            lst_data:arrSexHist,
            row_id:rowID
  };
  //alert("updateSeqNoSexHist this function "+rowID);
  save_data_ajax(aData,"visit_form_log/db_log_partner_sexhist.php",updateSeqNoSexHistComplete);
}

function updateSeqNoSexHistComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $('html, body').animate({
       scrollTop: $("#r_log_div_"+aData.row_id).offset().top
    }, 300);

    //$("#r_log_div_"+aData.row_id).offset().top

    $.notify("เรียงลำดับใหม่","info");
  }//flagSave
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
                   dom_id:"sex_partner",
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
                dom_id:"sex_partner",
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
        $("#"+k+"partner_relate").val(dataObj.partner_relate);
        $("#"+k+"partner_name").val(dataObj.partner_name);
        $("#"+k+"partner_thai").val(dataObj.partner_thai);
        $("#"+k+"age").val(dataObj.age);
        $("#"+k+"gender").val(dataObj.gender);
        $("#"+k+"hiv_result").val(dataObj.hiv_result);
        $("#"+k+"remark").val(dataObj.remark);

        $("#"+k+"row_log_info").html(dataObj.initial_staff);
        $("#"+k+"seq_no").val(dataObj.seq_no);
        $("#"+k+"collect_date").val(changeToThaiDate(dataObj.collect_date));

        // set old data

        $("#"+k+"partner_relate").data('odata',dataObj.partner_relate);
        $("#"+k+"partner_name").data('odata',dataObj.partner_name);
        $("#"+k+"partner_thai").data('odata',dataObj.partner_thai);
        $("#"+k+"age").data('odata',dataObj.age);
        $("#"+k+"gender").data('odata',dataObj.gender);
        $("#"+k+"hiv_result").data('odata',dataObj.hiv_result);
        $("#"+k+"remark").data('odata',dataObj.remark);

        $("#"+k+"row_log_info").html(dataObj.initial_staff);
        $("#"+k+"collect_date").data('odata',changeToThaiDate(dataObj.collect_date));

        $("#"+k+"btn_save_log").removeClass("btn-danger");

        var log_collect_date = Date.parse(dataObj.collect_date);


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

  $("#cur_row_id").val(rowID);
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
            dom:"sex_partner",
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
    var k = $("#cur_row_id").val();
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

function showDivLog(choice){
  //alert("showDivLog - "+choice);
  $('.div-log').hide();
  $('#div_'+choice).show();
}

</script>
