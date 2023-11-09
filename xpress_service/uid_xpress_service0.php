<?
include_once("../in_auth.php");
include_once("../a_app_info.php");


?>
  <div class="row alert alert-primary" role="alert">
     <div class="col-sm-6">
         <h3><i class="fa fa-notes-medical fa-lg" ></i> <b>X</b>Press Service </h3>
     </div>
     <div class="col-sm-4 px-2">


     </div>
     <div class="col-sm-2">
       <button id="btn_close_uid_xpress_service" class="form-control btn btn-warning btn-lg" type="button">
         <h5> <i class="fa fa-chevron-circle-left fa-lg" ></i> ย้อนกลับ </h5>
       </button>
     </div>
  </div>


<div class="card div-uid-xpress" id="div_uid_xpress_list">
  <div class="card-body">
      <table id="tbl_uid_xpress_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>

              <td rowspan='2' align='center'>วันเข้าตรวจ</td>
              <td colspan='3' align='center' bgcolor="#EFFFBF"><b>คนไข้กรอกแบบฟอร์ม</b></td>
              <td colspan='3' align='center' bgcolor="#BFEFFF"><b>เจ้าหน้าที่ตรวจแบบฟอร์ม</b></td>
              <td rowspan='2' align='center'>ยอมรับ xpress ?</td>
              <td rowspan='2' align='center'>ส่งผลตรวจแล้ว ?</td>
            </tr>
            <tr>

              <td>Xpress</td>
              <td>Consent</td>
              <td>ความพึงพอใจ</td>

              <td bgcolor="#73FFFF">ตรวจแล้ว ?</td>
              <td>Xpress</td>
              <td>Consent</td>

            </tr>
          </thead>
          <tbody>

          </tbody>
      </table>
  </div>
</div>

<div class="card div-uid-xpress" id="div_uid_xpress_info">
  <div class="card-body">
    <div class="row">
       <div class="col-sm-11">
         <h5><i class="fa fa-calendar-alt fa-lg" ></i> ข้อมูล XPress Service <span id="v_xpress_date_txt"></span></h5>
       </div>
       <div class="col-sm-1">
         <button id="btn_close_uid_xpress_info" class="form-control form-control-sm btn btn-danger btn-sm" type="button">
           <i class="fa fa-times fa-lg" ></i> ปิด
         </button>
       </div>
     </div>
    <div id="div_uid_xpress_info_data">

    </div>

  </div>
</div> <!-- div_uid_xpress_info -->














<input type="hidden" id="cur_xpress_date">
<input type="hidden" id="cur_xpress_status_id">

<input type="hidden" id="data_update_xpress">


<script>
$(document).ready(function(){

  $(".div-uid-xpress").hide();
  init_UID_xpress();

  $("#btn_close_uid_xpress_service").click(function(){
     showUIDDiv("uid_info");
  }); // btn_close_uid_xpress_service

  $("#btn_close_uid_xpress_info").click(function(){
    if($('#data_update_xpress').val()=="Y"){
      selectXpressList();
      $('#data_update_xpress').val('N');
    }
     showUIDDivXpress("uid_xpress_list");

  }); // btn_close_uid xpress info


});


function init_UID_xpress(){
  selectXpressList();
  showUIDDivXpress("uid_xpress_list");
}

function selectXpressList(){
  var aData = {
            u_mode:"select_uid_xpress_list",
            uid:$('#cur_uid').val()
  };

  //alert("uid/proj_id : "+aData.uid+'/'+aData.proj_id);
  save_data_ajax(aData,"xpress_service/db_xpress_service.php",selectXpressListComplete);
}

function selectXpressListComplete(flagSave, rtnDataAjax, aData){
//  alert("flag save is : "+flagSave);

  if(flagSave){
      var uid = $('#cur_uid').val();
      var datalist = rtnDataAjax.datalist;
      var is_new_xpress = rtnDataAjax.is_new_xpress;
      var cur_visit_date = rtnDataAjax.cur_visit_date;
//alert("row count :"+datalist.length);
      if(datalist.length > 0){
        var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
          var dataObj = datalist[i];
          var btn_reload = "";
          var btn_visit_patient = "";
          var btn_visit_counselor = "";
          var btn_visit_patient_consent = "";
          var btn_visit_counselor_consent = "";
          var btn_visit_xpress_satisfaction = "";
          var btn_visit_xpress_satisfaction_link = "";

          var counselor_check = "";
          var xpress_consent_result = "รอการยืนยัน";
          var btn_consent_link = "";

          var csl_done = ''; // counselor
          // patient xpress form
          if(dataObj.p_x_sum == 'Y'){ // pass assessment
            btn_visit_patient = '<button class="btn btn-success btn-sm" type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\'\',\'RAW\')"><i class="fa fa-check"></i> XPress Form <small>(ผ่าน)</small></button>';
            btn_consent_link = '<button class="btn btn-primary btn-sm" type="button" onclick="openXpressConsent_patient(\''+dataObj.collect_date+'\',\'Y\')"> LINK</button>';
          }
          else if(dataObj.p_x_sum == 'N'){// not pass assessment
            btn_visit_patient = '<button class="btn btn-danger btn-sm" type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\'\',\'RAW\')"><i class="fa fa-times"></i> XPress Form <small>(ไม่ผ่าน)</small></button>';
            btn_consent_link = '<button class="btn btn-primary btn-sm" type="button" onclick="openXpressConsent_patient(\''+dataObj.collect_date+'\',\'N\')"> LINK</button>';
          }
          else {// not done
            btn_visit_patient = '<button class="btn btn-warning btn-sm" type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\'\',\'RAW\')"><i class="fa fa-question-circle"></i> XPress Form <small>(รอการยืนยัน)</small></button>';
            btn_consent_link = '';
          }

          // patient consent
          if(dataObj.p_c_agree == 'Y'){
            btn_visit_patient_consent = '<button class="btn btn-success btn-sm" type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\'CSL\',\'CSL\')"><i class="fa fa-check"></i> Consent Form <small>(ยอมรับ)</small></button>';
          }
          else if(dataObj.p_c_agree == 'N'){
            btn_visit_patient_consent = '<button class="btn btn-danger btn-sm" type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\'CSL\',\'CSL\')"><i class="fa fa-times"></i> Consent Form <small>(ไม่ยอมรับ)</small></button>';
          }
          else {
            btn_visit_patient_consent = '<button class="btn btn-warning btn-sm" type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\'CSL\',\'RAW\')"><i class="fa fa-question-circle"></i> Consent Form <small>(รอการยืนยัน)</small></button>';
          }

    //xpress satisfaction
    if(dataObj.s_uid != ''){ // already filled
      btn_visit_xpress_satisfaction = '<button class="btn btn-primary btn-sm" type="button" onclick="view_xpress_satisfaction(\''+dataObj.collect_date+'\')"><i class="fa fa-smile-wink"></i> Satisfaction</small></button>';
    }
    else{ // not filled
      btn_visit_xpress_satisfaction_link = '<button class="btn btn-primary btn-sm" type="button" onclick="openXpressSatisfaction_patient(\''+dataObj.collect_date+'\')"> LINK</button>';
      btn_visit_xpress_satisfaction = "";
    }


// counselor
    if(dataObj.c_uid != ''){
      counselor_check = '<i class="fa fa-check"></i>';

      if(dataObj.c_x_sum == 'Y'){
        btn_visit_counselor = '<button class="btn btn-success btn-sm " type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\'CSL\',\'CSL\')"><i class="fa fa-check"></i> XPress Form <small>(ผ่าน)</small></button>';
      }
      else if(dataObj.c_x_sum == 'N'){
        btn_visit_counselor = '<button class="btn btn-danger btn-sm " type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\'CSL\',\'CSL\')"><i class="fa fa-times"></i> XPress Form <small>(ไม่ผ่าน)</small></button>';
      }
      else {
        btn_visit_counselor = '<button class="btn btn-warning btn-sm " type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\'CSL\',\'RAW\')"><i class="fa fa-question-circle"></i> XPress Form <small>(รอการยืนยัน)</small></button>';
      }

      // patient consent (check by counselor)
      if(dataObj.c_c_agree == 'Y'){
        btn_visit_counselor_consent = '<button class="btn btn-success btn-sm " type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\'CSL\',\'CSL\')"><i class="fa fa-check"></i> Consent Form <small>(ยอมรับ)</small></button>';
        xpress_consent_result = '<i class="fa fa-check"></i> ยอมรับ';
      }
      else if(dataObj.c_c_agree == 'N'){
        btn_visit_counselor_consent = '<button class="btn btn-danger btn-sm " type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\'CSL\',\'CSL\')"><i class="fa fa-times"></i> Consent Form <small>(ไม่ยอมรับ)</small></button>';
        xpress_consent_result = '<i class="fa fa-times"></i> ไม่ยอมรับ';
      }
      else {
        btn_visit_counselor_consent = '<button class="btn btn-warning btn-sm " type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\'CSL\',\'RAW\')"><i class="fa fa-question-circle"></i> Consent Form <small>(รอการยืนยัน)</small></button> '+btn_consent_link;
        xpress_consent_result = "<span>รอการยืนยัน</span>";
      }

    }
    else{
      counselor_check = '<span id="'+dataObj.collect_date+'_CSL"><button class="btn btn-info btn-sm" type="button" onclick="counselor_confirm_xpress(\''+dataObj.collect_date+'\')"><i class="fa fa-question-circle"></i> ยืนยันผลตามคนไข้</small></button></span>';
      btn_visit_counselor = '<button id="'+dataObj.collect_date+'_x_result" class="btn btn-warning btn-sm csl-'+dataObj.collect_date+'" type="button" onclick="view_xpress_service(\''+dataObj.collect_date+'\',\'CSL\',\'RAW\')"> XPress Form <small><span id="'+dataObj.collect_date+'_x_result_txt">(รอการยืนยัน)</span></small></button>';
      btn_visit_counselor_consent = '<button id="'+dataObj.collect_date+'_x_consent" class="btn btn-warning btn-sm csl-'+dataObj.collect_date+'" type="button" onclick="view_xpress_service_consent(\''+dataObj.collect_date+'\',\'CSL\',\'RAW\')"> Consent Form <small><span id="'+dataObj.collect_date+'_x_consent_txt">(รอการยืนยัน)</span></small></button> '+btn_consent_link;

      xpress_consent_result = '<span id="'+dataObj.collect_date+'_x_consent_txt2">รอการยืนยัน</span>';
    }


          txt_row += '<tr class="r_uid_xpress">';
          txt_row += ' <td>'+changeToThaiDate(dataObj.collect_date)+'</td>';
          txt_row += ' <td>'+btn_visit_patient+'</td>';
          txt_row += ' <td>'+btn_visit_patient_consent+'</td>';
          txt_row += ' <td>'+btn_visit_xpress_satisfaction+' '+btn_visit_xpress_satisfaction_link+'</td>';
          txt_row += ' <td>'+counselor_check+'</td>';
          txt_row += ' <td>'+btn_visit_counselor+'</td>';
          txt_row += ' <td>'+btn_visit_counselor_consent+'</td>';
          txt_row += ' <td>'+xpress_consent_result+'</td>';
          txt_row += ' <td>-</td>';
          txt_row += '</tr">';
        }//for

        $('.r_uid_xpress').remove(); // row uid proj visit
        $('.r_uid_xpress_add').remove(); // row uid proj visit

        if(is_new_xpress == "Y"){

          var txt_row_first = '<tr class="r_uid_xpress_add">';
          txt_row_first += '<td>';
          txt_row_first += ' <button class="btn btn-primary" type="button" onclick="reloadXpressForm_patient(\''+cur_visit_date+'\')"><i class="fa fa-sync-alt fa-lg"></i> Reload </button>';
          txt_row_first += '</td>';
          txt_row_first += '<td colspan="7" >';
          txt_row_first += ' <button class="btn btn-info" type="button" onclick="openXpressForm_patient(\''+cur_visit_date+'\')"><i class="fa fa-file-medical fa-lg"></i> คนไข้เข้ากรอกข้อมูล Xpress วันนี้ ('+changeToThaiDate(cur_visit_date)+')</button>';
          txt_row_first += '</td></tr>';

          $('#tbl_uid_xpress_list > tbody:last-child').append(txt_row_first);
        }

        $('#tbl_uid_xpress_list > tbody:last-child').append(txt_row);

    }//if
    else{
      $.notify("ไม่มีข้อมูลขณะนี้","info");
      $('.r_uid_xpress').remove(); // row uid proj visit
      var txt_row_first = '<tr class="r_uid_xpress_add">';
      txt_row_first += '<td>';
      txt_row_first += ' <button class="btn btn-primary" type="button" onclick="reloadXpressForm_patient(\''+cur_visit_date+'\')"><i class="fa fa-sync-alt fa-lg"></i> Reload </button>';
      txt_row_first += '</td>';
      txt_row_first += '<td colspan="7" >';
      txt_row_first += ' <button class="btn btn-info" type="button" onclick="openXpressForm_patient(\''+cur_visit_date+'\')"><i class="fa fa-file-medical fa-lg"></i> UID เข้ากรอกข้อมูล Xpress วันนี้ ('+changeToThaiDate(cur_visit_date)+')</button>';
      txt_row_first += '</td></tr>';

      $('#tbl_uid_xpress_list > tbody:last-child').append(txt_row_first);
    }


  }

}


function reloadXpressForm_patient(visitDate){
  var aData = {
            u_mode:"reload_new_xpress_service",
            uid:$('#cur_uid').val(),
            visit_date:visitDate
  };
  //alert("uid/proj_id : "+aData.uid+'/'+aData.proj_id);
  save_data_ajax(aData,"xpress_service/db_xpress_service.php",reloadXpressForm_patientComplete);

}

function reloadXpressForm_patientComplete(flagSave, rtnDataAjax, aData){
//  alert("flag save is : "+flagSave);
  if(flagSave){
         var datalist = rtnDataAjax.datalist;
         if(datalist.length > 0){
            selectXpressList();
           /*
           $('.r_uid_xpress_add').remove();

           var btn_visit_patient = "";
           var btn_visit_counselor = "";

           var rawdata_check = '';
           if(dataObj.form_done == 'Y'){
             rawdata_check = '<i class="fa fa-check text-success" ></i>';
             btn_visit_patient = '<button class="btn btn-info btn-sm" type="button" onclick="view_xpress_service(\''+cur_visit_date+'\')"><i class="fa fa-file-prescription fa-lg"></i> RAW </button>';
             btn_visit_counselor = '<button class="btn btn-warning btn-sm" type="button" onclick="view_xpress_service_csl(\''+cur_visit_date+'\')"><i class="fa fa-file-prescription fa-lg"></i> CSL </button>';
           }
           else {
             rawdata_check = '<i class="fa fa-times text-danger" ></i>';
             btn_visit_patient = 'รอการกรอก';
             btn_visit_counselor = '<i class="fa meh-rolling-eyes text-primary" ></i>';
           }


          var counselor_check = '<i class="fa fa-times text-danger" ></i>';

           txt_row = '<tr class="r_uid_xpress">';
           txt_row += ' <td>'+changeToThaiDate(dataObj.collect_date)+'</td>';
           txt_row += ' <td>'+rawdata_check+'</td>';
           txt_row += ' <td>'+btn_visit_patient+'</td>';
           txt_row += ' <td>'+counselor_check+'</td>';
           txt_row += ' <td>'+btn_visit_counselor+'</td>';
           txt_row += ' <td>'+dataObj.result+'</td>';
           txt_row += '</tr">';

           $('#tbl_uid_xpress_list > tbody:last-child').append(txt_row);
           */
         }
         else{
           $.notify("ยังไม่ได้ส่งแบบสอบถาม Xpress Service","warn");
         }
  }
}

function openXpressForm_patient(visitDate){
  var link = "xpress_service/link_xpress_service.php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&visit_date="+visitDate; // visit date
  link += "&uic="+$('#cur_uic').val(); // uic
  link += "&site=<? echo $staff_clinic_id;?>"; // site

  window.open(link,'_blank');
}

function openXpressConsent_patient(visitDate, xpress_result){
  var link = "xpress_service/link_xpress_service_consent.php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&visit_date="+visitDate; // visit date
  link += "&uic="+$('#cur_uic').val(); // uic
  link += "&site=<? echo $staff_clinic_id;?>"; // site
  link += "&r="+xpress_result; // xpress result


  window.open(link,'_blank');
}


function openXpressSatisfaction_patient(visitDate){
  var link = "xpress_service/link_xpress_satisfaction.php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&visit_date="+visitDate; // visit date
  link += "&uic="+$('#cur_uic').val(); // uic
  link += "&site=<? echo $staff_clinic_id;?>"; // site

  window.open(link,'_blank');
}


function view_xpress_service(visitDate, version, version2){
  <?
    if(!isset($auth["view"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าถึงข้อมูลนี้', 'info');";
      echo "return;";
    }
  ?>

  var link = "visit_form/x_xpress_service.php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&uic="+$('#cur_uic').val();; // uic
  link += "&visit_date="+visitDate; // visit date

// version RAW=patient done, CSL=counselor approved
  link += "&version="+version; // version to save
  link += "&version2="+version2; // version to view
//alert("link : "+link);

  $('#div_uid_xpress_info_data').html("รอสักครู่");
  $('#form_title').html("XPress Service");

  //showUIDDivXpress("uid_xpress_info");

  $("#div_uid_xpress_info_data").load(link, function(){

      showUIDDivXpress("uid_xpress_info");
  });

}


function view_xpress_service_consent(visitDate, version, version2, x_result){
  <?
    if(!isset($auth["view"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าถึงข้อมูลนี้', 'info');";
      echo "return;";
    }

  ?>


  var link = "visit_form/x_xpress_service_consent_staff.php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&uic="+$('#cur_uic').val();; // uic
  link += "&visit_date="+visitDate; // visit date

// version RAW=patient done, CSL=counselor approved
  link += "&version="+version; // version to save
  link += "&version2="+version2; // version to view

//alert("link : "+link);

  $('#div_uid_xpress_info_data').html("รอสักครู่");
  $('#form_title').html("XPress Service Consent");

  //showUIDDivXpress("uid_xpress_info");

  $("#div_uid_xpress_info_data").load(link, function(){

      showUIDDivXpress("uid_xpress_info");
  });

}



function view_xpress_satisfaction(visitDate){
  <?
    if(!isset($auth["view"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าถึงข้อมูลนี้', 'info');";
      echo "return;";
    }

  ?>

  var link = "visit_form/x_xpress_service_satisfaction.php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&uic="+$('#cur_uic').val();; // uic
  link += "&visit_date="+visitDate; // visit date
  link += "&site=<? echo $staff_clinic_id;?>"; // site

  $('#div_uid_xpress_info_data').html("รอสักครู่");
  $('#form_title').html("XPress Service Satisfaction");

  //showUIDDivXpress("uid_xpress_info");

  $("#div_uid_xpress_info_data").load(link, function(){

      showUIDDivXpress("uid_xpress_info");
  });

}


function counselor_confirm_xpress(visitDate){
  var aData = {
            u_mode:"confirm_xpress_service_patient",
            uid:$('#cur_uid').val(),
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

function showUIDDivXpress(choice){
  $('.div-uid-xpress').hide();
  $('#div_'+choice).show();
}




</script>
