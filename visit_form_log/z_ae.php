
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


$dom_id = "ae";
$form_id = "ae";
$form_name = "Adverse Events";

//$visit_info="วันที่: $visit_date [UID: $uid] <br>Project ID: $project_id / VISIT: $visit_id";
$visit_info="";

$num_date = (int) (new DateTime())->format('d');
$num_month = (int) (new DateTime())->format('m');
$thai_year = (int)(new DateTime())->format('Y') + 543;
$today_date_th = "$num_date/$num_month/$thai_year";

$opt_1 = '<option value="" selected disabled class="text-secondary">- เลือก -</option><option value="1" >1 น้อย (Mild)  </option><option value="2" >2 ปานกลาง (Moderate) </option><option value="3" >3 รุนแรง (Severe) </option><option value="4" >4 เป็นอันตรายถึงชีวิต (Life-threatening) </option><option value="5" >5 เสียชีวิต (Death)</option>';


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
       <div class="col-sm-11">
         <div><h2>แบบบันทึกอาการข้างเคียง (Adverse Events)</h2></div>
         <div><? echo $visit_info; ?></div>
         <div>
           กรุณาระบุรายละเอียดในแบบบันทึกนี้ หากอาสาสมัครมีอาการเจ็บป่วย หรือมีผลข้างเคียงจากยาเพร็พ (ตั้งแต่ระดับ 1-4) หรือจากการตรวจทางห้องปฏิบัติการ (ตั้งแต่ระดับ 3/4)
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
            <td rowspan="2" >Collect
              <center>
                <button id="btn_add_log" class="btn btn-success btn-sm btn-add-log" type="button">
                  <i class="fa fa-plus-square fa-lg" ></i> เพิ่มรายการ
                </button>
              </center>
            </td>
            <td rowspan="2" >รายละเอียดอาการ<br>(ใส่การวินิจฉัยถ้าทราบ)</td>
            <td rowspan="2" width="8%">วันที่เริ่มมีอาการ<br>วว/ดด/ปปปป<br>(พ.ศ.)</td>
            <td rowspan="2" width="8%">วันที่อาการหยุด<br>วว/ดด/ปปปป<br>(พ.ศ.)</td>
            <td rowspan="2" >ระดับความรุนแรงของอาการ</td>
            <td colspan="2" width="10%" style="background-color:#FFDC73;">อาการครั้งนี้เป็นอาการไม่พึงประสงค์ที่รุนแรง (SAE2) ด้วยหรือไม่</td>
            <td colspan="2" width="10%" style="background-color:#A3D900;">อาการดังกล่าวเกี่ยวข้องกับกระบวนการในโครงการวิจัยหรือไม่</td>
            <td colspan="3" width="18%" style="background-color:#FF9999;" align="center">มีการใช้ยาหรือไม่</td>
            <td rowspan="2" width="5%">ชื่อย่อเจ้าหน้าที่ (Initial)</td>
          </tr>
          <tr>


            <td >ไม่ใช่</td><td >ใช่</td>
            <td >ไม่ใช่</td><td >ใช่</td>
            <td >ไม่ใช่</td><td >ใช่</td><td >ไม่ทราบ</td>

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


  $("#btn_close_form_log").click(function(){
    checkFormComplete();
    // closeFormLog();
  }); // btn_close_form_log

});


function initDataFormLog(){
  <?
  if($is_lastest_visit == "N"){
    echo "$('.btn-add-log').hide();";
  }
  ?>
  /*
   if($('#visit_log_enable').val() == "0"){ // disable insert button only visit status in 1, 10, 11
     $('.btn-add-log').hide();
   }
   */
   selectListFormLog();
}

function addFormLog(){
  var last_visit_date = changeToThaiDate("<? echo $last_visit_date; ?>");
  var k = parseInt($("#ttl_amt_log").val())+1;
  var txt_row = '<tr id="r_log_'+k+'"  class="r_log">';
  //txt_row += '<td class="save-data row_num">'+k+'</td>';

  txt_row += '<td width="5%">';

  txt_row += '<input id="'+k+'collect_date" data-id="collect_date" data-row="'+k+'" data-odata="" type="text" maxlength="8" size="8" class="save-data-'+k+' chk-odata v_date" data-title="collect_date" value="'+last_visit_date+'" disabled>';
  txt_row += '<button id="'+k+'btn_remove_log" class="form-control form-control-sm btn btn-danger btn-sm" type="button" onclick="removeLog(\''+k+'\')"> ';
  txt_row += '<i class="fa fa-times fa-lg" ></i> ลบ</button></td>';

  txt_row += '<td><input id="'+k+'ae_detail" data-id="ae_detail" data-row="'+k+'" data-odata="" type="text" class="form-control form-control-sm save-data-'+k+' v-no-blank chk-odata" placeholder="รายละเอียดอาการ " data-title="รายละเอียดอาการ "></td>';
  txt_row += '<td><input id="'+k+'ae_startdate" data-id="ae_startdate" data-row="'+k+'" data-odata="" type="text" class="form-control form-control-sm save-data-'+k+' chk-odata" placeholder="วันที่เริ่มมีอาการ dd/mm/yyyy" data-title="วันที่เริ่มมีอาการ " onfocus="initPartialDate(this);"></td>';
  txt_row += '<td><input id="'+k+'ae_stopdate" data-id="ae_stopdate" data-row="'+k+'" data-odata="" type="text" class="a_stop_date form-control form-control-sm save-data-'+k+' chk-odata" placeholder="วันที่อาการหยุด dd/mm/yyyy" data-title="วันที่อาการหยุด "></td>';
//  txt_row += '<td><input id="'+k+'ae_severe" data-id="ae_severe" type="text" class="form-control form-control-sm save-data-'+k+' v-no-blank" placeholder="ระดับความรุนแรงของอาการ " data-title="ระดับความรุนแรงของอาการ "></td>';

  txt_row += '<td><select id="'+k+'ae_severe" data-id="ae_severe" data-row="'+k+'" data-odata="" class="form-control form-control-sm save-data-'+k+' chk-odata" >';
  txt_row += '<? echo $opt_1; ?>';
  txt_row += '</select></td>';



  txt_row += '<td style="background-color:#FFDC73;"><label class="form-check-label" for="'+k+'sae-N" >';
  txt_row += '<input type="radio" id="'+k+'sae-N" name="'+k+'sae" data-id="sae" class="save-data-'+k+' v_radio" value="N"> ไม่ใช่ ';
  txt_row += '</label></td>';
  txt_row += '<td style="background-color:#FFDC73;"><label class="form-check-label" for="'+k+'sae-Y" >';
  txt_row += '<input type="radio" id="'+k+'sae-Y" name="'+k+'sae" data-id="sae"  class="save-data-'+k+' v_radio" value="Y"> ใช่ ';
  txt_row += '</label></td>';

  txt_row += '<td style="background-color:#A3D900;"><label class="form-check-label" for="'+k+'ae_relation-N" >';
  txt_row += '<input type="radio" id="'+k+'ae_relation-N" name="'+k+'ae_relation" data-id="ae_relation"  class="save-data-'+k+' v_radio" value="N"> ไม่ใช่ ';
  txt_row += '</label></td>';
  txt_row += '<td style="background-color:#A3D900;"><label class="form-check-label" for="'+k+'ae_relation-Y" >';
  txt_row += '<input type="radio" id="'+k+'ae_relation-Y" name="'+k+'ae_relation" data-id="ae_relation"  class="save-data-'+k+' v_radio" value="Y"> ใช่ ';
  txt_row += '</label></td>';

  txt_row += '<td style="background-color:#FF9999;"><label class="form-check-label" for="'+k+'ae_druguse-N" >';
  txt_row += '<input type="radio" id="'+k+'ae_druguse-N" name="'+k+'ae_druguse" data-id="ae_druguse"  class="save-data-'+k+' v_radio" value="N"> ไม่ใช่ ';
  txt_row += '</label></td>';
  txt_row += '<td style="background-color:#FF9999;"><label class="form-check-label" for="'+k+'ae_druguse-Y" >';
  txt_row += '<input type="radio" id="'+k+'ae_druguse-Y" name="'+k+'ae_druguse" data-id="ae_druguse"  class="save-data-'+k+' v_radio" value="Y"> ใช่ ';
  txt_row += '</label></td>';
  txt_row += '<td style="background-color:#FF9999;"><label class="form-check-label" for="'+k+'ae_druguse-UK" >';
  txt_row += '<input type="radio" id="'+k+'ae_druguse-UK" name="'+k+'ae_druguse" data-id="ae_druguse"  class="save-data-'+k+' v_radio" value="UK"> ไม่ทราบ ';
  txt_row += '</label></td>';

//  txt_row += '<td><input type="text" id="'+k+'initial_staff" value="'+<? echo $user_id; ?>+'"></td>';
//  txt_row += '<td><input id="'+k+'initial_staff" data-id="initial_staff"  type="text" class="form-control form-control-sm save-data-'+k+' v-no-blank" value="<? echo $user_id; ?>" data-title="staff ">';
  txt_row += '<td><button id="'+k+'btn_log_info" class="btn btn-sm btn-info" onclick="infoFormLog(\''+k+'\')"><span id="'+k+'row_log_info"></span></button>';

  txt_row += '<input id="'+k+'seq_no" data-id="seq_no" type="hidden" class="save-data-'+k+'" data-title="seq_no" value="">';

  txt_row += '</td>';
  txt_row += '<td><button id="'+k+'btn_save_log" class="btn_save_log form-control form-control-sm btn btn-danger btn-sm" type="button" onclick="saveFormLog(\''+k+'\')"> ';
  txt_row += '<i class="fa fa-check fa-lg" ></i> SAVE</button></td>';

  txt_row += "</tr>";

//  $('#tbl_form_log > tbody:last-child').append(txt_row);
  $('#tbl_form_log > tbody').prepend(txt_row);
  $("#ttl_amt_log").val(k);

  $("#"+k+"ae_startdate").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});
  $("#"+k+"ae_stopdate").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});

}



function removeLog(rowID){
  <?
    if(!isset($auth["log"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }
  ?>

  if (confirm("ต้องการที่จะลบข้อมูลที่เลือก ใช่หรือไม่")) {

     var collectDate = getDataObjValue($("#"+rowID+"collect_date"));
      var aData = {
                u_mode:"remove_data",
                dom_id:"ae",
                uid:'<? echo $uid; ?>',
                seq_no:$("#"+rowID+"seq_no").val(),
                collect_date:collectDate,
                row_id:rowID
      };

      //alert("Pending this function");
      save_data_ajax(aData,"visit_form_log/db_form_log_data.php",removeLogComplete);
  }

}


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
                dom_id:"ae",
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

      //  alert("collect date: "+dataObj.collect_date);
        $("#"+k+"ae_detail").val(dataObj.ae_detail);
        $("#"+k+"ae_startdate").val(dataObj.ae_startdate);
        $("#"+k+"ae_stopdate").val(dataObj.ae_stopdate);
        $("#"+k+"ae_severe").val(dataObj.ae_severe);
        $("#"+k+"sae-"+dataObj.sae).prop('checked',true);
        $("#"+k+"ae_relation-"+dataObj.ae_relation).prop('checked',true);
        $("#"+k+"ae_druguse-"+dataObj.ae_druguse).prop('checked',true);
        //$("#"+k+"initial_staff").val(dataObj.initial_staff);
        $("#"+k+"row_log_info").html(dataObj.initial_staff);
        $("#"+k+"seq_no").val(dataObj.seq_no);
        $("#"+k+"collect_date").val(changeToThaiDate(dataObj.collect_date));

        // set old data
        $("#"+k+"ae_detail").data('odata',dataObj.ae_detail);
        $("#"+k+"ae_startdate").data('odata',dataObj.ae_startdate);
        $("#"+k+"ae_stopdate").data('odata',dataObj.ae_stopdate);
        $("#"+k+"ae_severe").data('odata',dataObj.ae_severe);
        //$("#"+k+"initial_staff").data('odata',dataObj.initial_staff);
        $("#"+k+"seq_no").data('odata',dataObj.seq_no);
        $("#"+k+"collect_date").data('odata',changeToThaiDate(dataObj.collect_date));

        $("#"+k+"btn_save_log").removeClass("btn-danger");
        var log_collect_date = Date.parse(dataObj.collect_date);
/*
        // lock to save if collect date is older than last visit
        if(log_collect_date < last_visit_date){

          // ถ้าวันหยุดอาการ ไม่ได้ใส่มาก็ยังให้ save ได้
          $(".a_stop_date").each(function(ix,objx){
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
            dom:"ae",
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
//alert("odata : checkFormComplete");
  var flag = 0;
  var divSaveData = "#div_form_log";
  $(divSaveData +" .chk-odata").each(function(ix,objx){
    if($(objx).data("odata") != $(objx).val()){
      flag = 1;
      // alert("odata : "+$(objx).data("odata")+"/"+$(objx).data("row"));
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
       if($open_visit == '1'){ // close to main visit
         echo "closeFormLog2();";
       }
       else{ // close to visit form
         echo "closeFormLog();";
       }
    ?>
    //closeFormLog();
  }


}



</script>
