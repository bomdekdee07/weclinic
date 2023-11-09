<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}
//echo "auth view : ".$auth["enroll"];
//echo "clinic id : $staff_clinic_id";
?>


<script>
var s_clinic_id = "<? echo $staff_clinic_id; ?>";
var cur_hos_pid = "";
var cur_hos_pid_create_date = "";
var cur_hos_ul = "";
var cur_mnu_hos = ""; // current hos menu 1=personal data, 2=baseline, 3=retention, 4=ae
var cur_hos_pid_data_log = 0; // 0= disable, 1= enable (all log form can be updated)
var u_mode_retention = ""; // add, update
var u_mode_ae = "";// add, update
var is_update_form = 0;// 0 = no update, 1 = update (log form eg. retention, ae)

var ae_collect_date = "";

ResetTimeOutTimer();
</script>

<div id='div_hos_pid_list' class='div-hos-main mt-2'>
  <div class="row mt-1">
     <div class="col-sm-4">
       <h5><i class="fa fa-calendar-alt fa-lg" ></i> SAME DAY Hospital <b>Prospective</b></h5>
     </div>
     <div class="col-sm-6 px-1">
       <small>
คนที่มีผลตรวจเอชไอวีเป็นบวก ที่ รพ. ของท่าน <b>ตั้งแต่ 1 สิงหาคม 2561</b>
      </small>

     </div>
     <div class="col-sm-2">
       <!--
       <label for="btn_hos_pid_export_all" class="text-light">.</label>
       <button class="btn btn-primary form-control" type="button" id="btn_hos_pid_export_all"><i class="fa fa-file-export" ></i> Data Export ALL</button>
     -->
     </div>
   </div>
  <div class="row mt-2">
     <div class="col-sm-2">
       <label for="btn_new_hos_pid" class="text-light">.</label>
      <button class="btn btn-success form-control" type="button" id="btn_new_hos_pid"><i class="fa fa-plus" ></i> เพิ่มผู้รับบริการรายใหม่</button>
     </div>

     <div class="col-sm-2">
       <label for="search_hos_opt2">การติดตามล่าสุด: </label>
       <select id="search_hos_opt2" class="form-control" >
         <option value="" selected>ทั้งหมด</option>
         <option value="1">การรักษา: อยู่ในระบบรักษา/หยุดกินยาต้าน/ติดต่อไม่ได้</option>
         <option value="2">การตรวจ VL</option>
         <option value="3">การตรวจ CD4</option>
         <option value="4">การเปลี่ยนยาต้านไวรัส</option>
         <option value="5">อาการเจ็บป่วยเพิ่ม (A/E)</option>
         <option value="0">ยังไม่เคยมีประวัติการติดตาม</option>
       </select>
     </div>

     <div class="col-sm-3">
       <label for="txt_search_hos_pid">คำค้นหา: (PID, UL, ชื่อ-นามสกุล, HN ID)</label>
       <input type="text" id="txt_search_hos_pid" class="form-control" placeholder="พิมพ์คำค้นหา">
     </div>

     <div class="col-sm-2">
       <label for="btn_search_hos_pid" class="text-light">.</label>
      <button class="btn btn-info form-control" type="button" id="btn_search_hos_pid"><i class="fa fa-search" ></i> ค้นหา</button>
     </div>
     <div class="col-sm-3">
       <label for="sel_hos_form_opt">ความสมบูรณ์แบบฟอร์ม:</label>
       <select id="sel_hos_form_opt" class="form-control" >
         <option value="all" selected >ทั้งหมด</option>
         <option value="notdone">แบบฟอร์มกรอกไม่ครบ (ปุ่มสีแดง)</option>
         <option value="done">แบบฟอร์มกรอกปกติ</option>
       </select>
     </div>


   </div>
   <div class="mt-2">
     <table id="tbl_hos_pid_list" class="table table-bordered table-sm table-striped table-hover">
         <thead>
           <tr>
             <th>PID</th><th>UL</th>

             <th>ชื่อ-นามสกุล</th>
             <th>HN </th>
             <th>ข้อมูลส่วนตัว</th>
             <th>Base Line</th>
             <th>Retention</th>
             <th>Adverse Event</th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div>

<div id='div_hos_pid_detail' class="div-hos-main mt-1">
  <div class="row bg-primary text-white">
     <div class="col-sm-5">
       <h4><b><span id="title_hos_pid_detail" ></span></b></h4>
     </div>
     <div class="col-sm-6">
        <button type="button" class="btn btn-primary btn-hos-mnu btn-hos-mnu-main" data-id="1"><i class="fa fa-user fa-lg" ></i> Info</button>
        <button type="button" class="btn btn-primary btn-hos-mnu btn-hos-mnu-main" data-id="2"><i class="fa fa-notes-medical fa-lg" ></i> Baseline</button>
        <button type="button" class="btn btn-primary btn-hos-mnu btn-hos-mnu-log" data-id="3"><i class="fa fa-stethoscope fa-lg" ></i> Retention</button>
        <button type="button" class="btn btn-primary btn-hos-mnu btn-hos-mnu-log" data-id="4"><i class="fa fa-procedures fa-lg" ></i> Adverse Event</button>

     </div>

     <div class="col-sm-1 pr-0">
       <button id="btn_close_hos_pid_detail" class="my-1 form-control form-control-sm btn btn-light btn-sm float-right" type="button">
         <i class="fa fa-times-circle fa-lg" ></i> ปิด
       </button>
     </div>
  </div>

  <div>

    <div id='div_hos1' class='div-hos-menu'>
       <?  include_once("pid_personal_data.php"); ?>
    </div>
    <div id='div_hos2' class='div-hos-menu'>
       <?  include_once("pid_hos_baseline.php"); ?>
    </div>
    <div id='div_hos3' class='div-hos-menu'>
       <?  include_once("pid_hos_retention.php"); ?>
    </div>
    <div id='div_hos4' class='div-hos-menu'>
       <?  include_once("pid_hos_ae.php"); ?>
    </div>


  </div>
</div> <!-- div_hos_pid_detail -->

<div id="div_w_data_view" class='div-hos-main'></div>
<div id="div_w_monitor" class='div-hos-main'></div>


<script>


$(document).ready(function(){
//  alert("xx "+s_clinic_id);
  $.notify.defaults({ autoHideDelay: 15000 });
  showMainHosDiv("hos_pid_list");

  $("#btn_hos_pid_export_all").click(function(){
     exportData_hos_pid_all();
  }); // btn_hos_pid_export_all

  $("#btn_search_hos_pid").click(function(){
     searchData_hos_pid();
  }); // btn_search_hos_pid

  $("#txt_search_hos_pid").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_hos_pid();
    }
  });


$("#sel_hos_form_opt").change(function(){
   //alert("sel "+$(this).val());

   if($(this).val() == "all"){
        $(".r_hos_pid").show();
   }
   else if($(this).val() == "notdone"){
        $(".fnd").show();
        $(".fd").hide();
   }
   else if($(this).val() == "done"){
        $(".fnd").hide();
        $(".fd").show();
   }
}); // btn_search_hos_pid

  $("#btn_new_hos_pid").click(function(){
     //alert("add new ja");
     //addData_hos_pid();
     cur_hos_pid = '[add new]';
     cur_hos_ul = '[add new]';
     cur_mnu_hos = '1';
     refreshTitleHosPID();

     addData_hos_ul();
     after_goHosMnu();

     showhideMenuHosLog(0);// hide log menu
    // clearHosPidPersonalData();
    // showMainHosDiv("hos_pid_detail");

  }); // btn_new_hos_pid
  $("#btn_close_hos_pid_detail").click(function(){
     var flag_close = true;
     //alert("btn_close_hos_pid_detail");
     if(cur_mnu_hos == '2') { // baseline
        var flag_change = checkFormDataChange();
        if(flag_change){
          if(confirm("คำเตือน: ข้อมูลมีการเปลี่ยนแปลง ท่านต้องการที่จะบันทึกข้อมูลหรือไม่ ?")){
            saveDataBaseLine();
            return;
          }
        }
        else{
          flag_close = true;
        }
     }
     else{ // log form
       if(cur_mnu_hos == '3') { // retention
         if($('#div_retention_detail').is(':visible')){
           var flag_change = checkFormDataChange();
           if(flag_change){
             if(confirm("คำเตือน: ข้อมูลมีการเปลี่ยนแปลง ท่านต้องการที่จะบันทึกข้อมูลหรือไม่ ?")){
               saveFormData(cData);
               return;
             }
           }
         }
       } // retention
       else if(cur_mnu_hos == '4') { // AE
         if($('#div_ae_detail').is(':visible')){
           var flag_change = checkFormDataChange();
           if(flag_change){
             if(confirm("คำเตือน: ข้อมูลมีการเปลี่ยนแปลง ท่านต้องการที่จะบันทึกข้อมูลหรือไม่ ?")){
               saveFormData(cData);
               return;
             }
           }
         }

       } // AE

     }

//alert("final "+flag_close);
     if(flag_close){
         showMainHosDiv("hos_pid_list");
     }
     else{
       if(confirm("คำเตือน: ข้อมูลมีการเปลี่ยนแปลง ท่านยืนยันจะปิด ใช่หรือไม่?")){
         showMainHosDiv("hos_pid_list");
       }
     }

  }); // btn_close_hos_pid_detail

  $(".btn-hos-mnu").click(function(){
     var choice = $(this).data("id");
     goHosMnu(cur_hos_pid, choice);
  }); // btn_new_hos_pid


});


function searchData_hos_pid(){

    //  $('#txt_search_hos_pid').removeClass("bg-warning")

      var aData = {
                u_mode:"select_hos_pid_list",
                txt_search:$('#txt_search_hos_pid').val(),
                search_hos_opt2:$('#search_hos_opt2').val()
      };
      save_data_ajax(aData,"w_hos/db_hos_pid.php",searchData_hos_pidComplete);

}

function searchData_hos_pidComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var txt_row="";

    if(rtnDataAjax.datalist.length > 0){
      var datalist = rtnDataAjax.datalist;
        for (i = 0; i < datalist.length; i++) {
          var dataObj = datalist[i];
          var flag_done = "fd"; // fd:form done, fnd:form not done
          var txt_btn_main="";
          var txt_btn_log="";

          var txt_rtn_date = ""; //lastest retention date
    //      if(aData.search_hos_opt2 != ''){
          //  if(aData.search_hos_opt2 != "0")
            if(dataObj.rtn_date != '')
            txt_rtn_date = " "+changeToThaiDate(dataObj.rtn_date);
    //      }

          if(dataObj.im == "0"){ // current data

            var base_color = "info";
            var base_txt = "";
            if(dataObj.base_pend != ""){
               base_color = "danger";
               flag_done = "fnd";
            }

            txt_btn_main += ' <td><button class="btn btn-warning" type="button" onclick="goHosMnu(\''+dataObj.pid+'\', \'1\')""><i class="fa fa-user"></i> ข้อมูลส่วนตัว</button></td>';
            txt_btn_main += ' <td><button class="btn btn-'+base_color+'" type="button" onclick="goHosMnu(\''+dataObj.pid+'\', \'2\')""><i class="fa fa-notes-medical"></i> Base Line</button></td>';


            if(dataObj.art_pass == "1"){

              var rtn_color = "primary"; var ae_color = "primary";
              var txt_rtn = ""; var txt_ae = "";
              if(dataObj.rtn_pend > 0) {
              //  txt_rtn = " <small>("+dataObj.rtn_pend+")</small>";
                rtn_color = "danger";
                flag_done = "fnd";
              }
              if(dataObj.ae_pend > 0) {
              //  txt_ae = " <small>("+dataObj.ae_pend+")</small>";
                ae_color = "danger";
                flag_done = "fnd";
              }
              txt_btn_log += ' <td><button class="btn btn-'+rtn_color+' log-'+dataObj.pid+'" type="button" onclick="goHosMnu(\''+dataObj.pid+'\', \'3\')""><i class="fa fa-stethoscope"></i> Retention  '+txt_rtn+txt_rtn_date+'</button></td>';
              txt_btn_log += ' <td><button class="btn btn-'+ae_color+' log-'+dataObj.pid+'" type="button" onclick="goHosMnu(\''+dataObj.pid+'\', \'4\')""><i class="fa fa-procedures"></i> AE '+txt_ae+'</button></td>';
            }
            else{
              /*
              txt_btn_log += ' <td>-</td>';
              txt_btn_log += ' <td>-</td>';
              */
              txt_btn_log += ' <td><button style="display:none" class="btn btn-primary log-'+dataObj.pid+'" type="button" onclick="goHosMnu(\''+dataObj.pid+'\', \'3\')""><i class="fa fa-stethoscope"></i> Retention </button></td>';
              txt_btn_log += ' <td><button style="display:none" class="btn btn-primary log-'+dataObj.pid+'" type="button" onclick="goHosMnu(\''+dataObj.pid+'\', \'4\')""><i class="fa fa-procedures"></i> AE </button></td>';

            }


          }
          else{ // data import from screen log in google drive
              //dataObj.name = "<span class='badge badge-info'>Data Import</span> "+dataObj.name;

              txt_btn_main += ' <td></td><td><span class="badge badge-info">Coming Soon</span></td>';



              var rtn_color = "primary"; var ae_color = "primary";
              var txt_rtn = ""; var txt_ae = "";
              if(dataObj.rtn_pend > 0) {
                //txt_rtn = " <small>("+dataObj.rtn_pend+")</small>";
                rtn_color = "danger";
                flag_done = "fnd";
              }
              if(dataObj.ae_pend > 0) {
                //txt_ae = " <small>("+dataObj.ae_pend+")</small>";
                ae_color = "danger";
                flag_done = "fnd";
              }

              txt_btn_log += ' <td><button class="btn btn-'+rtn_color+'" type="button" onclick="goHosMnu(\''+dataObj.pid+'\', \'3\')""><i class="fa fa-stethoscope"></i> Retention  '+txt_rtn+txt_rtn_date+'</button></td>';
              txt_btn_log += ' <td><button class="btn btn-'+ae_color+'" type="button" onclick="goHosMnu(\''+dataObj.pid+'\', \'4\')""><i class="fa fa-procedures"></i> AE '+txt_ae+'</button></td>';
          }





            txt_row += '<tr class="r_hos_pid '+flag_done+'" id="r_'+dataObj.pid+'" data-ul="'+dataObj.ul+'" data-log="'+dataObj.art_pass+'" data-create="'+dataObj.create_date+'" data-name="'+dataObj.name+'" data-im="'+dataObj.im+'">';
            txt_row += ' <td>'+dataObj.pid+'</td>';
            txt_row += ' <td>'+dataObj.ul+'</td>';
            txt_row += ' <td>'+dataObj.name+'</td>';
            txt_row += ' <td>'+dataObj.hn+'</td>';

            txt_row += txt_btn_main;

          txt_row += txt_btn_log;
          txt_row += '</tr">';
        }//for
        $('.r_hos_pid').remove(); // row pid list
        $('#tbl_hos_pid_list > tbody:last-child').append(txt_row);

    }
    else{
      $.notify("No record found.", "info");
      $('.r_hos_pid').remove(); // row pid list
      txt_row += '<tr class="r_hos_pid"><td colspan="5" align="center">ไม่พบข้อมูล</td></tr">';
      $('#tbl_hos_pid_list > tbody:last-child').append(txt_row);
    }

    $('#sel_hos_form_opt').val("all");

  }
}

function addNewRowPID(pid, ul, name, hn,create_date){
  var txt_row ="";
  txt_row += '<tr class="r_hos_pid" id="r_'+pid+'" data-ul="'+ul+'" data-log="0" data-create="'+create_date+'" data-name="'+name+'" data-im="0">';
  txt_row += ' <td>'+pid+'</td>';
  txt_row += ' <td>'+ul+'</td>';
  txt_row += ' <td>'+name+'</td>';
  txt_row += ' <td>'+hn+'</td>';

  txt_row += ' <td><button class="btn btn-warning" type="button" onclick="goHosMnu(\''+pid+'\', \'1\')""><i class="fa fa-user"></i> ข้อมูลส่วนตัว</button></td>';
  txt_row += ' <td><button class="btn btn-info" type="button" onclick="goHosMnu(\''+pid+'\', \'2\')""><i class="fa fa-notes-medical"></i> Base Line</button></td>';

  txt_row += ' <td><button style="display:none;" class="btn btn-primary log-'+pid+'" type="button" onclick="goHosMnu(\''+pid+'\', \'3\')""><i class="fa fa-stethoscope"></i> Retention</button></td>';
  txt_row += ' <td><button style="display:none;" class="btn btn-primary log-'+pid+'" type="button" onclick="goHosMnu(\''+pid+'\', \'4\')""><i class="fa fa-procedures"></i> AE</button></td>';

  txt_row += '</tr">';


  $('#tbl_hos_pid_list > tbody > tr:first').before(txt_row);
}

function updateRowPID(pid, ul, name, hn,create_date){

  var txt_row ="";
  txt_row += '<tr class="r_hos_pid" id="r_'+pid+'" data-ul="'+ul+'" data-create="'+create_date+'" data-name="'+name+'" data-im="0">';
  txt_row += ' <td>'+pid+'</td>';
  txt_row += ' <td>'+ul+'</td>';
  txt_row += ' <td>'+name+'</td>';
  txt_row += ' <td>'+hn+'</td>';

  txt_row += ' <td><button class="btn btn-warning" type="button" onclick="goHosMnu(\''+pid+'\', \'1\')""><i class="fa fa-user"></i> ข้อมูลส่วนตัว</button></td>';
  txt_row += ' <td><button class="btn btn-info" type="button" onclick="goHosMnu(\''+pid+'\', \'2\')""><i class="fa fa-notes-medical"></i> Base Line</button></td>';

  txt_row += ' <td><button class="btn btn-primary" type="button" onclick="goHosMnu(\''+pid+'\', \'3\')""><i class="fa fa-stethoscope"></i> Retention</button></td>';
  txt_row += ' <td><button class="btn btn-primary" type="button" onclick="goHosMnu(\''+pid+'\', \'4\')""><i class="fa fa-procedures"></i> AE</button></td>';

  txt_row += '</tr">';

  $('#r_'+pid).remove(); // row pid list
  $('#tbl_hos_pid_list > tbody > tr:first').before(txt_row);
}



function goHosMnu(pid, choice){
  //alert("goHosMnu "+choice);
  cur_hos_pid = pid;
  cur_hos_ul = $('#r_'+pid).data("ul");
  cur_hos_pid_data_log = $('#r_'+pid).data("log"); // 1:enable, 0:disable

  var is_import = $('#r_'+pid).data("im"); // 0:new data, 1:old import data from prevention program
  if(is_import == "0") $('.btn-hos-mnu-main').show();
  else $('.btn-hos-mnu-main').hide();


  if(typeof $('#r_'+pid).data("create") !== 'undefined'){
    cur_hos_pid_create_date = $('#r_'+pid).data("create");
  }

  cur_mnu_hos = choice;

  if(choice == '1'){
     getPersonalData_pid();
  }
  else if(choice == '2'){
     getBaseline_pid();
  }
  else if(choice == '3'){
     getRetention_pid();
  }
  else if(choice == '4'){
     ae_collect_date = ''; // all AE show
     getAE_pid();
  }

  showhideMenuHosLog(cur_hos_pid_data_log);

}

function showhideMenuHosLog(is_enable){
  if(is_enable == "1"){
    $('.btn-hos-mnu-log').show();
  }
  else if(is_enable == "0"){
    $('.btn-hos-mnu-log').hide();
  }
  cur_hos_pid_data_log = is_enable;
  $('#r_'+cur_hos_pid).data("log",is_enable);
}

function after_goHosMnu(){
//  alert("after_goHosMnu "+cur_mnu_hos+"/"+cur_hos_pid);
  refreshTitleHosPID();
  showMenuHosDiv("hos"+cur_mnu_hos);
  showMainHosDiv("hos_pid_detail");

  is_update_form = 0;

}

function refreshTitleHosPID(){

  $('#title_hos_pid_detail').html("PID: "+cur_hos_pid+" / UL: "+cur_hos_ul);
}





    function checkExistLogData(logForm, visitDate){
      //alert("checkExistLogData 1 ");
        var aData = {
                  u_mode:"check_exist_log_data",
                  log_form:logForm,
                  pid:cur_hos_pid,
                  visit_date:visitDate
        };
        save_data_ajax(aData,"w_hos/db_hos_pid.php",checkExistLogDataComplete);

      }
      function checkExistLogDataComplete(flagSave, rtnDataAjax, aData){
        //alert("flag save is getPersonalData_pidComplete : "+flagSave);
        if(flagSave){
          if(rtnDataAjax.rec_found == 0){
            saveDataSDHosLog();
          }
          else{
            myModalContent("Error",
            "<b>ไม่สามารถบันทึกได้ </b> <br>เนื่องจาก ข้อมูลวันที่ติดตามผู้ใช้บริการ (Visit Date) ซ้ำกับ Retention เดิมที่มีอยู่",
            "info");
/*
            if(confirm("ข้อมูลซ้ำกับที่มีอยู่ ต้องการบันทึกซ้ำข้อมูลเก่าหรือไม่ ?")){
              saveDataSDHosLog();
            }
            */
          }
        }
      }

/*
      function getMaxSeqNo(logForm){ // get max seq_no to insert new log record (eg. AE)
        //alert("checkExistLogData 1 ");
          var aData = {
                    u_mode:"get_max_seq_no_log",
                    log_form:logForm,
                    pid:cur_hos_pid
          };
          save_data_ajax(aData,"w_hos/db_hos_pid.php",getMaxSeqNoComplete);

        }
        function getMaxSeqNoComplete(flagSave, rtnDataAjax, aData){
          //alert("flag save is getPersonalData_pidComplete : "+flagSave);
          if(flagSave){
            cur_seq_no_log = rtnDataAjax.max_seq_no;
            if(rtnDataAjax.rec_found == 0){
              saveDataSDHosLog();
            }
            else{
              if(confirm("ข้อมูลซ้ำกับที่มีอยู่ ต้องการบันทึกซ้ำข้อมูลเก่าหรือไม่ ?")){
                saveDataSDHosLog();
              }
            }
          }
        }

*/


// div in Main
function showMainHosDiv(choice){
  $('.div-hos-main').hide();
  $('#div_'+choice).show();
}

// div in Menu
function showMenuHosDiv(choice){
  $('.div-hos-menu').hide();
  $('#div_'+choice).show();
}



function exportData_hos_pid_all(){

      var aData = {
                u_mode:'all'
      };

      save_data_ajax(aData,"w_hos/xls_sdhos_data_export_all.php",exportData_hos_pidComplete);

}

function exportData_hos_pidComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    window.open(rtnDataAjax.link_xls, '_blank');
  }
}





</script>
