<?
include_once("../in_auth.php");

?>

<div class="card div-main-xpress" id="div_main_xpress_list">
  <div class="card-body">
    <div class="card-title">


      <div class="row">
         <div class="col-sm-2">
           <div><h5><i class="fa fa-paper-plane fa-lg" ></i> XPress Service</h5></div>
           <div><button class="btn btn-info form-control" type="button" id="btn_export_main_xpress"><i class="fa fa-file-export" ></i> Data Export</button></div>
         </div>
         <div class="col-sm-2">
           <label for="sel_xpress_main_date_beg">ตั้งแต่วันที่:</label>
           <input type="text" id="sel_xpress_main_date_beg" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="sel_xpress_main_date_end">ถึงวันที่:</label>
           <input type="text" id="sel_xpress_main_date_end" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="sel_csl_check">ตรวจสอบจากเจ้าหน้าที่:</label>
           <select id="sel_csl_check" class="form-control form-control-sm" >
             <option value="Y" selected class="text-success">ตรวจแล้ว</option>
             <option value="N" class="text-danger">ยังไม่ตรวจ</option>
             <option value=""  class="text-dark">ทั้งหมด</option>
           </select>
         </div>

         <div class="col-sm-3">
           <label for="txt_search_main_xpress">ค้นหา (uic, uid, name):</label>
           <input type="text" id="txt_search_main_xpress" class="form-control form-control-sm" >
         </div>
         <div class="col-sm-1">
           <label for="btn_search_main_xpress" class="text-light">.</label>
          <button class="btn btn-info form-control" type="button" id="btn_search_main_xpress"><i class="fa fa-search" ></i> ค้นหา</button>
         </div>


       </div>


    </div>
    <div>
      <table id="tbl_xpress_main_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th rowspan='2' >วันที่เข้าตรวจ</th>
              <th rowspan='2' >UID / UIC</th>

              <td colspan='2' align='center' bgcolor="#EFFFBF"><b>คนไข้กรอกแบบฟอร์ม</b></td>
              <td colspan='2' align='center' bgcolor="#BFEFFF"><b>เจ้าหน้าที่ตรวจแบบฟอร์ม</b></td>
              <td colspan='2' align='center' bgcolor="#EEE"><b>แบบประเมินความพึงพอใจ</b></td>
              <td rowspan='2' align='center'>การส่งผลตรวจ</td>
            </tr>
            <tr>

              <td>Xpress</td>
              <td>Consent</td>

              <td>Xpress</td>
              <td>Consent</td>

              <td>ก่อนตรวจ</td>
              <td>หลังตรวจ</td>

            </tr>
          </thead>
          <tbody>

          </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card div-main-xpress" id="div_main_xpress_info">
  <div class="card-body">
    <div class="row">
       <div class="col-sm-11">
         <h5><i class="fa fa-calendar-alt fa-lg" ></i> ข้อมูล XPress Service <span id="v_xpress_date_txt"></span></h5>
       </div>
       <div class="col-sm-1">
         <button id="btn_close_main_xpress_info" class="form-control form-control-sm btn btn-danger btn-sm" type="button">
           <i class="fa fa-times fa-lg" ></i> ปิด
         </button>
       </div>
     </div>
    <div id="div_main_xpress_info_data">

    </div>

  </div>
</div> <!-- div_main_xpress_info -->

<input type="hidden" id="data_update_xpress">

<script>

$(document).ready(function(){
  showMainDivXpress("main_xpress_list");

  $("#btn_close_main_xpress_info").click(function(){
    if($('#data_update_xpress').val()=="Y"){
      selectXpressList();
      $('#data_update_xpress').val('N');
    }
    showMainDivXpress("main_xpress_list");

  }); // btn_close_uid xpress info

      var currentDate = new Date();
      currentDate.setYear(currentDate.getFullYear() + 543);

        $("#sel_xpress_main_date_beg").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy',
          onSelect: function(date) {
            $("#sel_xpress_main_date_beg").addClass('filled');
          }
        });
        $("#sel_xpress_main_date_end").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy',
          onSelect: function(date) {
            $("#sel_xpress_main_date_end").addClass('filled');
          }
        });

        $('#sel_xpress_main_date_beg').datepicker("setDate",currentDate );
        $('#sel_xpress_main_date_end').datepicker("setDate",currentDate );

  $("#btn_search_main_xpress").click(function(){
     selectXpressAllList();
  }); // btn_search_main_xpress
  $("#btn_export_main_xpress").click(function(){
     alert("export");
     dataExportXpress();
  }); // btn_export_main_xpress



});



function selectXpressAllList(){
  var aData = {
            u_mode:"select_search_xpress_list",
            csl_check:$('#sel_csl_check').val(),
            txt_search:$('#txt_search_main_xpress').val(),
            date_beg:changeToEnDate($('#sel_xpress_main_date_beg').val()),
            date_end:changeToEnDate($('#sel_xpress_main_date_end').val())
  };
  save_data_ajax(aData,"xpress_service/db_xpress_service.php",selectXpressAllListComplete);
}

function selectXpressAllListComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);

    if(flagSave){
        var uid = $('#cur_uid').val();
        var datalist = rtnDataAjax.datalist;
        var cur_visit_date = rtnDataAjax.cur_visit_date;
  //alert("row count :"+datalist.length);
        if(datalist.length > 0){
          var txt_row = "";
          for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            var btn_reload = "";
            var btn_patient = "";
            var btn_counselor = "";
            var btn_patient_consent = "";
            var btn_counselor_consent = "";
            var btn_xpress_satisfaction = "";
            var btn_xpress_satisfaction_link = "";
            var btn_xpress_satisfaction_after = "";
            var btn_xpress_satisfaction_after_link = "";

            var xpress_send_result = "";

            var counselor_check = "";
            var btn_consent_link = "";

            var csl_done = ''; // counselor
            var csl_edit = ''; // mark that counselor can edit or not / 'CSL'=edit, ''=lock (hide save)

            //xpress send result (counselor can edit if not yet send the result)
            if(dataObj.sc_id != ''){ // already send
              xpress_send_result += ' <span class="badge badge-success px-1"><i class="fa fa-paper-plane"></i> ส่งผลตรวจแล้ว ('+dataObj.send_date+')</button>';
              csl_edit = '';//lock save
            }
            else{ // not send (send link)
              xpress_send_result += ' <span class="badge badge-secondary px-1"><i class="fa fa-times"></i> ยังไม่ส่งผลตรวจ </button>';
              csl_edit = 'CSL'; // Counselor can edit
            }


            // patient xpress form
            if(dataObj.p_x_sum == 'Y'){ // pass assessment
              btn_patient = '<button class="btn btn-success btn-sm" type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\'\',\'RAW\')"><i class="fa fa-check"></i> XPress Form <small>(ผ่าน)</small></button>';

              // patient consent
              if(dataObj.p_c_agree == 'Y'){
                btn_patient_consent = '<button class="btn btn-success btn-sm" type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\'\',\'RAW\')"><i class="fa fa-check"></i> Consent Form <small>(ยอมรับ)</small></button>';
              }
              else if(dataObj.p_c_agree == 'N'){
                btn_patient_consent = '<button class="btn btn-danger btn-sm" type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\'\',\'RAW\')"><i class="fa fa-times"></i> Consent Form <small>(ไม่ยอมรับ)</small></button>';
              }
              else {
                btn_patient_consent = '<button class="btn btn-warning btn-sm" type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\'CSL\',\'RAW\')"><i class="fa fa-question-circle"></i> Consent Form <small>(รอการยืนยัน)</small></button>';
              }

            }
            else if(dataObj.p_x_sum == 'N'){// not pass assessment
              btn_patient = '<button class="btn btn-danger btn-sm" type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\'\',\'RAW\')"><i class="fa fa-times"></i> XPress Form <small>(ไม่ผ่าน)</small></button>';
              btn_patient_consent = 'ไม่ต้องทำ';
            }
            else {// not done
              btn_patient = '<button class="btn btn-warning btn-sm" type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\'\',\'RAW\')"><i class="fa fa-question-circle"></i> XPress Form <small>(รอการยืนยัน)</small></button>';
              btn_consent_link = '';
            }


  // counselor
      if(dataObj.c_uid != ''){
        if(dataObj.c_x_sum == 'Y'){
          btn_counselor = '<button class="btn btn-success btn-sm " type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\''+csl_edit+'\',\'CSL\')"><i class="fa fa-check"></i> XPress Form <small>(ผ่าน)</small></button>';
          btn_consent_link = '<button class="btn btn-primary btn-sm" type="button" onclick="openXpressConsent_patient(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\'Y\')"> LINK</button>';

          // patient consent (check by counselor)
          if(dataObj.c_c_agree == 'Y'){
            btn_counselor_consent = '<button class="btn btn-success btn-sm " type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\''+csl_edit+'\',\'CSL\')"><i class="fa fa-check"></i> Consent Form <small>(ยอมรับ)</small></button>';

            //xpress satisfaction
            if(dataObj.s_uid != ''){ // already filled
              btn_xpress_satisfaction = '<button class="btn btn-success btn-sm" type="button" onclick="view_xpress_satisfaction(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\', 1)"><i class="fa fa-smile-wink"></i> ก่อนส่งผล</small></button>';
            }
            else{ // not filled
              btn_xpress_satisfaction_link = '<button class="btn btn-primary btn-sm" type="button" onclick="openXpressSatisfaction_patient(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\', 1)"> LINK</button>';
              btn_xpress_satisfaction = "ยังไม่ทำ ";
            }

            //xpress satisfaction after
            if(dataObj.after_service != ''){ // already filled
              btn_xpress_satisfaction_after = '<button class="btn btn-success btn-sm" type="button" onclick="view_xpress_satisfaction(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\', 2)"><i class="fa fa-smile-wink"></i> หลังส่งผล</small></button>';
            }
            else{ // not filled
              btn_xpress_satisfaction_after_link = '<button class="btn btn-primary btn-sm" type="button" onclick="openXpressSatisfaction_patient(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\', 2)"> LINK</button>';
              btn_xpress_satisfaction_after = "ยังไม่ทำ ";
            }

          }
          else if(dataObj.c_c_agree == 'N'){
            btn_counselor_consent = '<button class="btn btn-danger btn-sm " type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\''+csl_edit+'\',\'CSL\')"><i class="fa fa-times"></i> Consent Form <small>(ไม่ยอมรับ)</small></button>';
          }
          else {
            btn_counselor_consent = '<button class="btn btn-warning btn-sm " type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\'CSL\',\'RAW\')"><i class="fa fa-question-circle"></i> Consent Form <small>(รอการยืนยัน)</small></button> '+btn_consent_link;
          }
        }
        else if(dataObj.c_x_sum == 'N'){
          btn_counselor = '<button class="btn btn-danger btn-sm " type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\''+csl_edit+'\',\'CSL\')"><i class="fa fa-times"></i> XPress Form <small>(ไม่ผ่าน)</small></button>';
          btn_counselor_consent = 'ไม่ต้องทำ';
        }
        else {
          btn_counselor = '<button class="btn btn-warning btn-sm " type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\'CSL\',\'RAW\')"><i class="fa fa-question-circle"></i> XPress Form <small>(รอการยืนยัน)</small></button>';
        }



      }
      else{
        counselor_check = '<span id="'+dataObj.collect_date+'_CSL"><button class="btn btn-info btn-sm" type="button" onclick="counselor_confirm_xpress(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\' )"><i class="fa fa-question-circle"></i> ยืนยันผลตามคนไข้</small></button></span> ';
        btn_counselor = '<button id="'+dataObj.collect_date+'_x_result" class="btn btn-warning btn-sm csl-'+dataObj.collect_date+'" type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\'CSL\',\'RAW\')"> XPress Form <small><span id="'+dataObj.collect_date+'_x_result_txt">(รอการยืนยัน)</span></small></button>';
        btn_counselor_consent = '<button id="'+dataObj.collect_date+'_x_consent" class="btn btn-warning btn-sm csl-'+dataObj.collect_date+'" type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\',\'CSL\',\'RAW\')"> Consent Form <small><span id="'+dataObj.collect_date+'_x_consent_txt">(รอการยืนยัน)</span></small></button> '+btn_consent_link;

      }



            txt_row += '<tr class="r_main_xpress">';
            txt_row += ' <td>'+changeToThaiDate(dataObj.collect_date)+'</td>';
            txt_row += ' <td>'+dataObj.uid+' / '+dataObj.uic+'</td>';
            txt_row += ' <td>'+btn_patient+'</td>';
            txt_row += ' <td>'+btn_patient_consent+'</td>';
            txt_row += ' <td>'+counselor_check+btn_counselor+'</td>';
            txt_row += ' <td>'+btn_counselor_consent+'</td>';

            txt_row += ' <td>'+btn_xpress_satisfaction+' '+btn_xpress_satisfaction_link+'</td>';
            txt_row += ' <td>'+btn_xpress_satisfaction_after+' '+btn_xpress_satisfaction_after_link+'</td>';

            txt_row += ' <td>'+xpress_send_result+'</td>';

            txt_row += '</tr">';
          }//for

          $('.r_main_xpress').remove(); // row uid proj visit
          $('.r_main_xpress_add').remove(); // row uid proj visit



          $('#tbl_xpress_main_list > tbody:last-child').append(txt_row);

      }//if
      else{
        $.notify("ไม่มีข้อมูลขณะนี้","info");
        $('.r_main_xpress').remove(); // row uid proj visit
      }


    }
}


function openXpressForm_patient(visitDate, xpressUID, xpressUIC){
  var link = "xpress_service/link_xpress_service.php?";
  link += "uid="+xpressUID; // uid
  link += "&visit_date="+visitDate; // visit date
  link += "&uic="+xpressUIC; // uic
  link += "&site=<? echo $staff_clinic_id;?>"; // site

  window.open(link,'_blank');
}

function openXpressConsent_patient(visitDate,xpressUID, xpressUIC, xpress_result){
  var link = "xpress_service/link_xpress_service_consent.php?";
  link += "uid="+xpressUID; // uid
  link += "&visit_date="+visitDate; // visit date
  link += "&uic="+xpressUIC; // uic
  link += "&site=<? echo $staff_clinic_id;?>"; // site
  link += "&r="+xpress_result; // xpress result


  window.open(link,'_blank');
}


function openXpressSatisfaction_patient(visitDate,xpressUID, xpressUIC, choice){ // choice 1:before send xpress , 2:after send xpress
  var link = "xpress_service/";
  if(choice == 1) link += "link_xpress_satisfaction.php";
  else if(choice == 2) link += "link_xpress_satisfaction_after.php";

  link += "?uid="+xpressUID; // uid
  link += "&visit_date="+visitDate; // visit date
  link += "&uic="+xpressUIC; // uic
  link += "&site=<? echo $staff_clinic_id;?>"; // site

  window.open(link,'_blank');
}



function view_xpress_service(visitDate, xpressUID, xpressUIC, version, version2){
  <?
    if(!isset($auth["view"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าถึงข้อมูลนี้', 'info');";
      echo "return;";
    }
  ?>

  var link = "visit_form/x_xpress_service.php?";
  link += "uid="+xpressUID; // uid
  link += "&uic="+xpressUIC; // uic
  link += "&visit_date="+visitDate; // visit date

// version RAW=patient done, CSL=counselor approved
  link += "&version="+version; // version to save
  link += "&version2="+version2; // version to view
//alert("link : "+link);

  $('#div_main_xpress_info_data').html("รอสักครู่");
  $('#form_title').html("XPress Service");

  //showMainDivXpress("main_xpress_info");

  $("#div_main_xpress_info_data").load(link, function(){

      showMainDivXpress("main_xpress_info");
  });

}


function view_xpress_service_consent(visitDate, xpressUID, xpressUIC, version, version2, x_result){
  <?
    if(!isset($auth["view"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าถึงข้อมูลนี้', 'info');";
      echo "return;";
    }

  ?>


  var link = "visit_form/x_xpress_service_consent_staff.php?";
  link += "uid="+xpressUID; // uid
  link += "&uic="+xpressUIC; // uic
  link += "&visit_date="+visitDate; // visit date

// version RAW=patient done, CSL=counselor approved
  link += "&version="+version; // version to save
  link += "&version2="+version2; // version to view

//alert("link : "+link);

  $('#div_main_xpress_info_data').html("รอสักครู่");
  $('#form_title').html("XPress Service Consent");

  //showMainDivXpress("main_xpress_info");

  $("#div_main_xpress_info_data").load(link, function(){

      showMainDivXpress("main_xpress_info");
  });

}


function view_xpress_satisfaction(visitDate,xpressUID, xpressUIC, choice){
  <?
    if(!isset($auth["view"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าถึงข้อมูลนี้', 'info');";
      echo "return;";
    }

  ?>
  var link = "visit_form/";
  if(choice == 1) link += "x_xpress_service_satisfaction.php";
  else if(choice == 2) link += "x_xpress_service_satisfaction_after.php";

  link += "?uid="+xpressUID; // uid
  link += "&uic="+xpressUIC; // uic
  link += "&visit_date="+visitDate; // visit date
  link += "&site=<? echo $staff_clinic_id;?>"; // site
//alert("link: "+link);

  $('#div_main_xpress_info_data').html("รอสักครู่");
  $('#form_title').html("XPress Service Satisfaction");

  //showMainDivXpress("main_xpress_info");
  $("#div_main_xpress_info_data").load(link, function(){
      showMainDivXpress("main_xpress_info");
  });

}


function counselor_confirm_xpress(visitDate, xpressUID){
  var aData = {
            u_mode:"confirm_xpress_service_patient",
            uid:xpressUID, 
            visit_date:visitDate
  };
  //alert("uid/proj_id : "+aData.uid+'/'+aData.proj_id);
  save_data_ajax(aData,"xpress_service/db_xpress_service.php",counselor_confirm_xpressComplete);
}

function counselor_confirm_xpressComplete(flagSave, rtnDataAjax, aData){
//  alert("flag save is : "+flagSave);
  if(flagSave){
       if(rtnDataAjax.is_success){
          $('.csl-'+aData.visit_date).removeClass("btn-warning");
          if(rtnDataAjax.x_result == 'Y'){
            $('#'+aData.visit_date+'_x_result').addClass("btn-success");
            $('#'+aData.visit_date+'_x_result_txt').html("(ผ่าน)");
          }
          else if(rtnDataAjax.x_result == 'N'){
            $('#'+aData.visit_date+'_x_result').addClass("btn-danger");
            $('#'+aData.visit_date+'_x_result_txt').html("(ไม่ผ่าน)");
          }

          if(rtnDataAjax.x_consent == 'Y'){
            $('#'+aData.visit_date+'_x_consent').addClass("btn-success");
            $('#'+aData.visit_date+'_x_consent_txt').html("(ยอมรับ)");
            $('#'+aData.visit_date+'_x_consent_txt2').html("<i class='fa fa-check'></i> ยอมรับ");
          }
          else if(rtnDataAjax.x_consent == 'N'){
            $('#'+aData.visit_date+'_x_consent').addClass("btn-danger");
            $('#'+aData.visit_date+'_x_consent_txt').html("(ไม่ยอมรับ)");
            $('#'+aData.visit_date+'_x_consent_txt2').html("<i class='fa fa-times'></i> ไม่ยอมรับ");
          }



          $('#'+aData.visit_date+'_CSL').html("<span class='text-success'>ยืนยันแล้ว<span>");
       }
  }
}


function dataExportXpress(){
    var aData = {
      date_beg:changeToEnDate($('#sel_xpress_main_date_beg').val()),
      date_end:changeToEnDate($('#sel_xpress_main_date_end').val())
    };
    save_data_ajax(aData,"w_data/xls_xpress.php",dataExportXpressComplete);
}

function dataExportXpressComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+aData.proj_id+"/"+rtnDataAjax.link_xls+"'");
  if(flagSave){
    window.open(rtnDataAjax.link_xls, '_blank');
  }
}





function showMainDivXpress(choice){
  $('.div-main-xpress').hide();
  $('#div_'+choice).show();
}

</script>
