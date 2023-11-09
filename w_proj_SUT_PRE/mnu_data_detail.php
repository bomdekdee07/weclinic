<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}
//echo "auth view : ".$auth["enroll"];
//echo "clinic id : $staff_clinic_id";
?>


<script>
var s_clinic_id = "<? echo $staff_clinic_id; ?>";



ResetTimeOutTimer();
</script>

<div id='div_sut_list' class='div-hos-main mt-2'>
  <div class="row mt-1">
     <div class="col-sm-4">
       <h5><i class="fa fa-calendar-alt fa-lg" ></i> STANDUP-TEEN <b>HIV SELF-Testing</b></h5>
     </div>
     <div class="col-sm-6 px-1">
       <small>
อาสาสมัครทดลองใช้ชุดตรวจเอชไอวีด้วยตนเอง
      </small>

     </div>
     <div class="col-sm-2">
       <!--
       <label for="btn_sut_export_all" class="text-light">.</label>
       <button class="btn btn-primary form-control" type="button" id="btn_sut_export_all"><i class="fa fa-file-export" ></i> Data Export ALL</button>
     -->
     </div>
   </div>
  <div class="row mt-2">
     <div class="col-sm-3">
       <label for="btn_new_sut" class="text-light">.</label>
      <button class="btn btn-success form-control" type="button" id="btn_new_sut"><i class="fa fa-plus" ></i> เพิ่มอาสาสมัครรายใหม่</button>
     </div>

     <div class="col-sm-4">
       <label for="txt_search_sut">คำค้นหา: (PID, UID, UIC)</label>
       <input type="text" id="txt_search_sut" class="form-control" placeholder="พิมพ์คำค้นหา">
     </div>

     <div class="col-sm-2">
       <label for="btn_search_sut" class="text-light">.</label>
      <button class="btn btn-info form-control" type="button" id="btn_search_sut"><i class="fa fa-search" ></i> ค้นหา</button>
     </div>
     <div class="col-sm-3">
       <label for="sel_sut_form_opt">ความสมบูรณ์แบบฟอร์ม:</label>
       <select id="sel_sut_form_opt" class="form-control" >
         <option value="all" selected >ทั้งหมด</option>
         <option value="notdone">ยังไม่ได้ทำ</option>
         <option value="done">ทำแล้ว</option>
       </select>
     </div>


   </div>
   <div class="mt-2">
     <table id="tbl_sut_pid_list" class="table table-bordered table-sm table-striped table-hover">
         <thead>
           <tr>
             <th>PID</th><th>UID</th><th>UIC</th>

             <th>Screen?</th>
             <th>Consent/Form?</th>
             <th>วันส่ง / วันรับ </th>
             <th>ตรวจ?</th>
             <th>ทำลาย?</th>
             <th>วันติดตามผล</th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div>

<div id='div_sut_detail' class="div-hos-main mt-1">
  <div class="row bg-primary text-white">
     <div class="col-sm-5">
       <h4><b><span id="title_sut_detail" ></span></b></h4>
     </div>
     <div class="col-sm-6">
        <button type="button" class="btn btn-primary btn-sut-mnu" data-id="1"><i class="fa fa-user fa-lg" ></i> อาสาสมัครกรอก</button>
        <button type="button" class="btn btn-primary btn-sut-mnu" data-id="2"><i class="fa fa-notes-medical fa-lg" ></i> เจ้าหน้าที่กรอก</button>
     </div>

     <div class="col-sm-1 pr-0">
       <button id="btn_close_sut_detail" class="my-1 form-control form-control-sm btn btn-light btn-sm float-right" type="button">
         <i class="fa fa-times-circle fa-lg" ></i> ปิด
       </button>
     </div>
  </div>

  <div>

    <div id='div_sut_detail_form' class='div-sut-menu'>

    </div>




  </div>
</div> <!-- div_sut_detail -->




<script>


$(document).ready(function(){
//  alert("xx "+s_clinic_id);
  $.notify.defaults({ autoHideDelay: 15000 });
  showMenuSUTDiv("sut_list");

  $("#btn_sut_export_all").click(function(){
     exportData_sut_all();
  }); // btn_sut_export_all

  $("#btn_search_sut").click(function(){
     searchData_sut();
  }); // btn_search_sut

  $("#txt_search_sut").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_sut();
    }
  });


$("#sel_sut_form_opt").change(function(){
   //alert("sel "+$(this).val());

   if($(this).val() == "all"){
        $(".r_sut").show();
   }
   else if($(this).val() == "notdone"){
        $(".fnd").show();
        $(".fd").hide();
   }
   else if($(this).val() == "done"){
        $(".fnd").hide();
        $(".fd").show();
   }
}); // btn_search_sut

  $("#btn_new_sut").click(function(){
     //alert("add new ja");
     //addData_sut();
     cur_sut = '[add new]';
     cur_hos_ul = '[add new]';
     cur_mnu_hos = '1';
     refreshTitleHosPID();

     addData_hos_ul();
     after_goHosMnu();

     showhideMenuHosLog(0);// hide log menu
    // clearHosPidPersonalData();
    // showMainHosDiv("sut_detail");

  }); // btn_new_sut




});


function searchData_sut(){

    //  $('#txt_search_sut').removeClass("bg-warning")

      var aData = {
                u_mode:"select_sut_list",
                txt_search:$('#txt_search_sut').val()
      };
      save_data_ajax(aData,"w_hos/db_sut.php",searchData_sutComplete);

}

function searchData_sutComplete(flagSave, rtnDataAjax, aData){
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
              txt_btn_log += ' <td><button class="btn btn-'+rtn_color+' log-'+dataObj.pid+'" type="button" onclick="goHosMnu(\''+dataObj.pid+'\', \'3\')""><i class="fa fa-stethoscope"></i> Retention  '+txt_rtn+'</button></td>';
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

              txt_btn_log += ' <td><button class="btn btn-'+rtn_color+'" type="button" onclick="goHosMnu(\''+dataObj.pid+'\', \'3\')""><i class="fa fa-stethoscope"></i> Retention  '+txt_rtn+'</button></td>';
              txt_btn_log += ' <td><button class="btn btn-'+ae_color+'" type="button" onclick="goHosMnu(\''+dataObj.pid+'\', \'4\')""><i class="fa fa-procedures"></i> AE '+txt_ae+'</button></td>';
          }





            txt_row += '<tr class="r_sut '+flag_done+'" id="r_'+dataObj.pid+'" data-ul="'+dataObj.ul+'" data-log="'+dataObj.art_pass+'" data-create="'+dataObj.create_date+'" data-name="'+dataObj.name+'" data-im="'+dataObj.im+'">';
            txt_row += ' <td>'+dataObj.pid+'</td>';
            txt_row += ' <td>'+dataObj.ul+'</td>';
            txt_row += ' <td>'+dataObj.name+'</td>';
            txt_row += ' <td>'+dataObj.hn+'</td>';

            txt_row += txt_btn_main;

          txt_row += txt_btn_log;
          txt_row += '</tr">';
        }//for
        $('.r_sut').remove(); // row pid list
        $('#tbl_sut_pid_list > tbody:last-child').append(txt_row);

    }
    else{
      $.notify("No record found.", "info");
      $('.r_sut').remove(); // row pid list
      txt_row += '<tr class="r_sut"><td colspan="5" align="center">ไม่พบข้อมูล</td></tr">';
      $('#tbl_sut_pid_list > tbody:last-child').append(txt_row);
    }

    $('#sel_sut_form_opt').val("all");

  }
}


function exportData_sut_all(){

      var aData = {
                u_mode:'all'
      };

      save_data_ajax(aData,"w_hos/xls_sdhos_data_export_all.php",exportData_sutComplete);

}

function exportData_sutComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    window.open(rtnDataAjax.link_xls, '_blank');
  }
}

// div in Menu
function showMenuSUTDiv(choice){
  $('.div-sut-menu').hide();
  $('#div_'+choice).show();
}



</script>
