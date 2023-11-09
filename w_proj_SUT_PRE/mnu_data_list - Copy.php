<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}
//echo "auth view : ".$auth["enroll"];

?>


<script>

var s_clinic_id = "<? echo $staff_clinic_id; ?>";



ResetTimeOutTimer();
</script>

<div id='div_sut_list' class='div-sut-menu mt-0 '>
  <div class="row mt-0">
     <div class="col-sm-5">
         <div>
           <h5><i class="fa fa-calendar-alt fa-lg" ></i> STANDUP-TEEN <b>HIV SELF-Testing </b></h5> (<? echo $staff_clinic_id; ?>)
         </div>
         <div><small>อาสาสมัครทดลองใช้ชุดตรวจเอชไอวีด้วยตนเอง</small></div>
     </div>
     <div class="col-sm-2">
         <a href="https://res99.org/home/reservation" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-calendar-alt" ></i> นัดหมาย TestMeNow</a>
     </div>
     <div class="col-sm-2">
         <button class="btn btn-primary btn-sm" type="button" id="btn_sut_pre_export"><i class="fa fa-file-export" ></i> Data Export</button>
     </div>



     <div class="col-sm-3 px-1 py-1" >
       <div>
       <table style="border-style: solid; border-color: #eee;">
         <tr><td colspan='2'>เพิ่มอาสาสมัครใหม่เข้าโครงการ:</td></tr>
         <tr>
           <td><input type="text" id="txt_new_sut"  placeholder="พิมพ์ UID หรือ UIC"></td>
           <td><button class="btn btn-success btn-sm" type="button" id="btn_new_sut"><i class="fa fa-plus" ></i> เพิ่ม</button></td>
         </tr>
       </table>
       </div>

     </div>
   </div>
  <div class="row mt-2">
    <div class="col-sm-2">
      <label for="sel_sut_form_opt">เลือกข้อมูลจาก:</label>
      <select id="sel_sut_form_opt" class="form-control" >
        <option value="">ทั้งหมดที่ผ่านการคัดกรองแล้ว</option>
        <option value="1">รอทำแบบฟอร์ม</option>
        <option value="2">รอส่งชุดตรวจ</option>
        <option value="3">รอใช้ชุดตรวจ</option>
        <option value="4">รอทำลายชุดตรวจ</option>
    <!--    <option value="0">ไม่ผ่านการคัดกรอง</option> -->
      </select>
    </div>
     <div class="col-sm-7">
       <label for="txt_search_sut">คำค้นหา: (PID, UID, UIC)</label>
       <input type="text" id="txt_search_sut" class="form-control" placeholder="พิมพ์คำค้นหา">
     </div>

     <div class="col-sm-2">
       <label for="btn_search_sut" class="text-light">.</label>
      <button class="btn btn-info form-control" type="button" id="btn_search_sut"><i class="fa fa-search" ></i> ค้นหา</button>
     </div>



   </div>
   <div class="mt-2">
     <table id="tbl_sut_pid_list" class="table table-bordered table-sm table-striped table-hover">
         <thead>
           <tr>

             <th>PID</th>
             <th>UID | UIC</th>
             <th>คัดกรอง</th>

             <th>วันคัดกรอง | <span class="text-primary">วันลงทะเบียน</span></th>

             <th>ใบยินยอม</th>
             <th>แบบฟอร์ม</th>
             <th>เจ้าหน้าที่กรอก</th>
             <th>วันส่ง | วันรับ</th>
             <th>วันใช้ตรวจ / วันทำลาย</th>



           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div>

<div id='div_sut_detail' class="div-sut-menu mt-0">
  <div class="row bg-primary text-white">
     <div class="col-sm-5">
       <b><span id="title_sut_detail" ></span></b>
     </div>
     <div class="col-sm-6">
        <div id="sut_info">
      <!--
        <button type="button" class="btn btn-primary btn-sut-mnu" data-id="1"><i class="fa fa-user fa-lg" ></i> ฟอร์มคัดกรอง</button>
        <button type="button" class="btn btn-primary btn-sut-mnu" data-id="2"><i class="fa fa-user fa-lg" ></i> อาสาสมัครกรอก</button>
        <button type="button" class="btn btn-primary btn-sut-mnu" data-id="3"><i class="fa fa-notes-medical fa-lg" ></i> การติดตามผล</button>
      -->
        </div>



     </div>

     <div class="col-sm-1 pr-0">
       <button id="btn_close_sut_detail" class="my-1 form-control form-control-sm btn btn-light btn-sm float-right" type="button">
         <i class="fa fa-times-circle fa-lg" ></i> ปิด
       </button>
     </div>
  </div>

  <div>

    <div id='div_sut_detail_form' >

    </div>

  </div>
</div> <!-- div_sut_detail -->




<script>
var proj_id = "SUT_PRE";
var cur_sut_pre_uid = "";
var cur_sut_pre_pid = "";
var cur_sut_pre_uic = "";
var cur_sut_pre_screen_date = "";
var cur_sut_pre_visit_date = "";

$(document).ready(function(){
//  alert("xx "+s_clinic_id);
  $.notify.defaults({ autoHideDelay: 15000 });
  showMenuSUTDiv("sut_list");

  $("#btn_sut_pre_export").click(function(){
     exportData_SUT_PRE();
  }); // btn_sut_pre_export

  $("#btn_search_sut").click(function(){
     searchData_sut();
  }); // btn_search_sut

  $(".btn-sut-mnu").click(function(){

     changeMenu_SUT_PRE($(this).data("id"));
  }); // btn_search_sut


  $("#txt_search_sut").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_sut();
    }
  });

  $("#btn_new_sut").click(function(){
     addNewSUT();

  }); // btn_new_sut
  $("#btn_close_sut_detail").click(function(){
     close_SUT_Detail();
  }); // btn_close_sut_detail

});
function close_SUT_Detail(){
  cur_sut_pre_uid = "";
  cur_sut_pre_pid = "";
  cur_sut_pre_uic = "";
  cur_sut_pre_screen_date = "";
  cur_sut_pre_visit_date = "";
  showMenuSUTDiv("sut_list");
}
function changeMenu_SUT_PRE(choice){
  var form_id="";
  var date = "";
  var visit_id="";

  if(choice == '1'){// screen
     form_id="sut_pre_screen";
     date = cur_sut_pre_screen_date;
     visit_id="SCRN";
  }
  else if(choice == '2'){// patient form
    form_id="sut_pre_visit";
    date = cur_sut_pre_visit_date;
    visit_id="PRE";
  }
  else if(choice == '3'){// followup form
    form_id="sut_pre_follow";
    date = cur_sut_pre_visit_date;
    visit_id="FOLLOW";
  }

  var link = "visit_form/f_form_proj.php?";
  link += "uid="+cur_sut_pre_uid; // uid
  link += "&form_id=sut_pre_screen";
  link += "&visit_date="+date; // screen date
  link += "&visit_id=SCRN"; // visit id
  link += "&proj_id="+proj_id; // project id
  link += "&group_id=0"; // group id (just screening/ no group )
  link += "&age="+age; // age at screen date
  link += "&clinic_id="+s_clinic_id; // clinic_id

//alert("link: "+link);
  $('#div_sut_detail_form').html("รอสักครู่");
  $('#title_sut_detail').html("SCREENING [UID: "+cur_sut_pre_uid+" / UIC: "+cur_sut_pre_uic+"]");

    $("#div_sut_detail_form").load(link, function(){
        showMenuSUTDiv("sut_detail");

    });
}

function addNewSUT(){ // add patient to screen
     if($('#txt_new_sut').val().trim().length > 7){
       var aData = {
                 u_mode:"addnew_sut",
                 txt_new_sut:$('#txt_new_sut').val()
       };
       save_data_ajax(aData,"w_proj_SUT_PRE/db_sut.php",addNewSUTComplete);
     }
     else{
       $('#txt_new_sut').notify("กรุณากรอก UID หรือ UIC ให้ถูกต้อง", "info");
     }


}

function addNewSUTComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
     cur_sut_pre_uid = rtnDataAjax.uid;
     cur_sut_pre_uic = rtnDataAjax.uic;
   openSUTScreen(rtnDataAjax.age);

   $('#txt_new_sut').val('');
  }
}

//function addRowData(uid,uic,pid,screen_date, visit_date, content_type,  s_consent,is_confirm_consent, send_date, get_date, follow_date  ){
function addRowData(uid,uic,pid,screen_date, visit_date, content_type,  s_consent, p_consent,is_confirm_consent, send_date, receive_date, test_date, destroy_date, test_no  ){
    var btn_screen = "";
    var btn_visit="";
    var btn_follow="";

    var btn_group = "";
    var content_type_txt = "";

    var btn_visit_disable = "";
    var btn_follow_disable = "";
//alert("ocntent type "+content_type);
    var row_id = uid+screen_date;
//alert("content "+content_type+"/"+p_consent+"/"+s_consent);
    if(content_type == 1){
      //btn_visit_disable = "disabled";

      if(visit_date == ''){ // not done form
        //send form link to do
        content_type_txt = 'กระดาษ <button  onclick="openSUT_FormLink(\'1\',\''+uid+'\',\''+uic+'\',\''+screen_date+'\')"><i class="fa fa-user" ></i> <b>ส่งฟอร์ม Online </b> </button>';

        btn_visit_disable = "disable";
        btn_follow_disable = "disable";
      }
      else{
        content_type_txt = 'กระดาษ';

        if(is_confirm_consent == '0'){

//alert("confirmconsent : "+p_consent);
           p_consent='Y'; // fake case consent
           btn_group = '<div id="cfm'+uid+screen_date+'" class="btn-group btn-group-sm mb-1" role="group" aria-label="confirm"><button class="btn btn-success btn-sm px-1" type="button" onclick="confirm_consent(\'1\', \''+p_consent+'\', \''+uid+'\', \''+screen_date+'\',\''+visit_date+'\' );"><i class="fa fa-check" ></i> ยืนยัน</button>';
           btn_group += '<button class="btn btn-danger btn-sm px-1" type="button" onclick="confirm_consent(\'0\',\''+p_consent+'\', \''+uid+'\', \''+screen_date+'\',\''+visit_date+'\' );"><i class="fa fa-times" ></i> ยกเลิก</button>';
           btn_group += '<button class="btn btn-secondary btn-sm px-1" type="button" onclick="remove_paper_consent(\''+uid+'\',\''+visit_date+'\', \''+screen_date+'\' );"><i class="fa fa-user-times" ></i> ลบทั้งหมด</button></div>';

           content_type_txt = btn_group+" "+content_type_txt;
        }

      }

    }
    else if(content_type == 2){//online
      //alert("uid "+uid+"/"+visit_date+"/"+p_consent);
      if(visit_date == ''){ // not done form
        //send form link to do
      //  alert("p_consent "+p_consent);
        if(p_consent == ""){
            if(is_confirm_consent == "0")
            content_type_txt = 'ออนไลน์ <button  onclick="openSUT_FormLink(\'2\',\''+uid+'\',\''+uic+'\',\''+screen_date+'\')"><i class="fa fa-user" ></i> <b>ส่งฟอร์ม Online '+screen_date+'</b> </button>';
        }
        else{
          content_type_txt = btn_group+'<div><button class="btn btn-primary btn-sm " type="button" data-uid="'+uid+'"  onclick="openSUT_FormMain(\'0\',\''+uid+'\',\''+uic+'\',\''+pid+'\',\''+screen_date+'\',\''+visit_date+'\',\''+test_no+'\')"><i class="fa fa-user" ></i> ออนไลน์</button></div>';

        }

        //content_type = '<button class="btn btn-warning btn-sm btn_sut_pid" type="button" data-uid="'+uid+'" onclick="openSUT_FormLink(\''+uid+'\',\''+uic+'\',\''+screen_date+'\')"><i class="fa fa-user" ></i> <b>ส่งฟอร์ม Online</b> </button>';
        btn_visit_disable = "disable";
        btn_follow_disable = "disable";
      }
      else{ // see consent form


      }
    }


//alert("s_consent "+s_consent+"/"+is_confirm_consent);
    if(s_consent == 'N'){ // not agree screen consent
      btn_visit_disable = "disable";
      btn_follow_disable = "disable";
    }
    else if(s_consent == 'Y'){
      if(is_confirm_consent == '0'){
         btn_group = '<div id="cfm'+uid+screen_date+'" class="btn-group btn-group-sm mb-1" role="group" aria-label="confirm"><button class="btn btn-success btn-sm px-1" type="button" onclick="confirm_consent(\'1\', \''+p_consent+'\', \''+uid+'\', \''+screen_date+'\',\''+visit_date+'\' );"><i class="fa fa-check" ></i> ยืนยัน</button>';
         btn_group += '<button class="btn btn-danger btn-sm px-1" type="button" onclick="confirm_consent(\'0\',\''+p_consent+'\', \''+uid+'\', \''+screen_date+'\',\''+visit_date+'\' );"><i class="fa fa-times" ></i> ยกเลิก</button></div>';
         btn_follow_disable = "disable";
         if(p_consent == "N") btn_visit_disable = "disable";
      }
      else if(is_confirm_consent == '1'){
         if (p_consent == "N"){
           pid = "Not Consent";
           btn_visit_disable = "disable";
           btn_follow_disable = "disable";
         }
      }

      if(content_type_txt == "")
      content_type_txt = btn_group+'<div><button class="btn btn-primary btn-sm " type="button" data-uid="'+uid+'"  onclick="openSUT_FormMain(\'0\',\''+uid+'\',\''+uic+'\',\''+pid+'\',\''+screen_date+'\',\''+visit_date+'\',\''+test_no+'\')"><i class="fa fa-user" ></i> ออนไลน์</button></div>';

    }
    else{
      btn_visit_disable = "disable";
      btn_follow_disable = "disable";
    }


    btn_screen = '<button class="btn btn-primary btn-sm " type="button" data-uid="'+uid+'"  onclick="openSUT_FormMain(\'1\',\''+uid+'\',\''+uic+'\',\''+pid+'\',\''+screen_date+'\',\''+visit_date+'\',\''+test_no+'\')""><i class="fa fa-user" ></i> คัดกรอง </button>';


    if(btn_visit_disable != "disable")
    btn_visit = '<button class="btn btn-warning btn-sm "  type="button" data-uid="'+uid+'"  onclick="openSUT_FormMain(\'2\',\''+uid+'\',\''+uic+'\',\''+pid+'\',\''+screen_date+'\',\''+visit_date+'\',\''+test_no+'\')""><i class="fa fa-user" ></i> แบบฟอร์ม </button>';

    var btn_follow_show = "";
    if(btn_follow_disable == "disable")
      btn_follow_show = ' id="f'+row_id+'" style="display:none;" ';


    btn_follow = '<button class="btn btn-info btn-sm " '+btn_follow_show+' type="button" data-uid="'+uid+'"  onclick="openSUT_FormMain(\'3\',\''+uid+'\',\''+uic+'\',\''+pid+'\',\''+screen_date+'\',\''+visit_date+'\',\''+test_no+'\')""><i class="fa fa-user" ></i> ติดตามผล ('+test_no+')</button>';


/*
    if(btn_follow_disable != "disable")
      btn_follow = '<button class="btn btn-info btn-sm " '+btn_follow_disable+' type="button" data-uid="'+uid+'"  onclick="openSUT_FormMain(\'3\',\''+uid+'\',\''+uic+'\',\''+pid+'\',\''+screen_date+'\',\''+visit_date+'\',\''+test_no+'\')""><i class="fa fa-user" ></i> ติดตามผล ('+test_no+')</button>';
    else {
       btn_follow = '<span id="f'+row_id+'"></span>';
    }
*/

    visit_date = (visit_date != '')?changeToThaiDate(visit_date):' ';
    screen_date = (screen_date != '')?changeToThaiDate(screen_date):'';

var row_content = "";
    //row_content += '<td><button class="btn btn-primary btn-sm btn_sut_pid" type="button" data-uid="'+uid+'" data-s_date="'+screen_date+'"  data-v_date="'+visit_date+'" data-s_consent="'+s_consent+'"><i class="fa fa-user" ></i> <b>'+pid+'</b> </button></td>';
    row_content += '<td> <b><span id="p'+row_id+'">'+pid+'</span></b></td>';
    row_content += ' <td>'+uid+'|'+uic+'</td>';
    row_content += ' <td>'+btn_screen+'</td>'; // screen form
    row_content += ' <td> <span class="txt-success"> '+screen_date+'</span> | <span class="text-primary"><b>'+visit_date+'</b></span></td>';
    row_content += ' <td><div id="c'+row_id+'">'+content_type_txt+'</div></td>';
    row_content += ' <td>'+btn_visit+'</td>'; // visit_form
    row_content += ' <td>'+btn_follow+'</td>'; // followup_form
    row_content += ' <td>'+send_date+' | '+receive_date+'</td>'; //วันส่ง / วันรับ (ชุดตรวจ)
    row_content += ' <td>'+test_date+' | '+destroy_date+'</td>'; //วันตรวจ / วันทำลาย (ชุดตรวจ)



  var txt_row = '<tr class="r_sut " id="r_'+row_id+'" data-uid="'+uid+'" data-uic="'+uic+'" data-pid="'+pid+'"';

  txt_row +=  ' data-screen_date="'+screen_date+'" data-visit_date="'+visit_date+'" ';
  txt_row +=  '>';
  txt_row += row_content;
  txt_row += '</tr">';
  return txt_row;
}

function confirm_consent(choice, p_consent, uid_param, screenDate, visitDate){
  var flag_ok = 0;
  if(choice == '0'){
    var result = confirm("ท่านต้องการยกเลิก Consent นี้ใช่หรือไม่ ?");
    if (result) {
        flag_ok = 1;
    }
  }
  else if(choice == '1'){
    flag_ok = 1;
  }


  if(flag_ok == 1){
    var aData = {
              u_mode:"confirm_consent",
              uid: uid_param,
              case_consent:p_consent,
              screen_date: screenDate,
              visit_date: visitDate,
              confirm_choice:choice
    };
    save_data_ajax(aData,"w_proj_SUT_PRE/db_sut.php",confirm_consent_Complete);
  }

}

function confirm_consent_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $('#cfm'+aData.uid+aData.screen_date).hide();
    if(aData.confirm_choice == '1'){ // confirm consent
       if(aData.case_consent == "Y"){
         $('#p'+aData.uid+aData.screen_date).html(rtnDataAjax.pid);
         $('#f'+aData.uid+aData.screen_date).show();
       }
       else if (aData.case_consent == "N"){
         $('#r_'+aData.uid+aData.screen_date).remove();
         alert("ลบออกจากตาราง เนื่องจากเลือก ไม่ยินยอม ในใบยินยอม");
       }
    }
    else if(aData.confirm_choice == '0'){ // cancel consent

      var content_type = 'ออนไลน์ <button  onclick="openSUT_FormLink(\'2\',\''+aData.uid+'\',\''+aData.uid+'\',\''+aData.screen_date+'\')"><i class="fa fa-user" ></i> <b>ส่งฟอร์ม Online '+aData.screen_date+'</b> </button>';
      $('#c'+aData.uid+aData.screen_date).html(content_type);


    }

  }
}



function remove_paper_consent(uid_param,visitDate, screenDate){
  var flag_ok = 0;

    var result = confirm("ท่านต้องลบการกรอกกระดาษ Consent นี้ใช่หรือไม่ ?");
    if (result) {
        flag_ok = 1;
    }

  if(flag_ok == 1){
    var aData = {
              u_mode:"remove_paper_consent",
              uid: uid_param,
              screen_date: screenDate,
              visit_date: visitDate
    };
    save_data_ajax(aData,"w_proj_SUT_PRE/db_sut.php",remove_paper_consent_Complete);
  }

}

function remove_paper_consent_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $('#r_'+aData.uid+aData.screen_date).remove();
    $.notify("ลบออกจากตารางแล้ว", "info");
    alert("ลบออกจากตารางแล้ว");

  }
}



function searchData_sut(){

      var aData = {
                u_mode:"select_sut_list",
                txt_search:$('#txt_search_sut').val(),
                form_opt:$('#sel_sut_form_opt').val()
      };

      save_data_ajax(aData,"w_proj_SUT_PRE/db_sut.php",searchDataSUT_Complete);

}

function searchDataSUT_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
     cur_sut_pre_uid = "";
     cur_sut_pre_pid = "";
     cur_sut_pre_uic = "";

    var txt_row="";

    if(rtnDataAjax.datalist.length > 0){
      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];

            txt_row += addRowData(
             dataObj.uid,dataObj.uic,dataObj.pid,
             dataObj.screen_date,
             dataObj.visit_date,
             dataObj.consent_type,
             dataObj.s_consent,
             dataObj.p_consent,
             dataObj.cfm_consent,

             dataObj.send_date,
             dataObj.receive_date,
             dataObj.test_date,
             dataObj.destroy_date,
             dataObj.test_no

            );

            /*
            var row_content = "";
            if(dataObj.s_consent == ""){
              row_content += '<td colspan=6 align="center">UID:'+dataObj.uid+' / UIC:'+dataObj.uic+' [นำเข้าระบบเมื่อ '+dataObj.visit_date+'] ' ;
              row_content += '<button class="btn btn-warning btn-sm btn_sut_link" type="button"  onclick="openSUT_FormLink(\''+dataObj.uid+'\',\''+dataObj.uic+'\',\''+dataObj.visit_date+'\')""><i class="fa fa-plus" ></i> ส่งลิงค์แบบฟอร์ม </button>';
              row_content += '</td>';
            }
            else{
              row_content += '<td><button class="btn btn-primary btn-sm btn_sut_pid" type="button" data-uid="'+dataObj.uid+'" data-visit_date="'+dataObj.visit_date+'" data-s_consent="'+dataObj.s_consent+'"><i class="fa fa-user" ></i> <b>'+dataObj.pid+'</b> </button></td>';
              row_content += ' <td>'+dataObj.uid+' ['+dataObj.uic+']</td>';
              row_content += ' <td>ส่งแล้ว '+dataObj.consent+'</td>';
              row_content += ' <td></td>';
              row_content += ' <td></td>';
              row_content += ' <td></td>';
            }


            txt_row += '<tr class="r_sut " id="r_'+dataObj.uid+dataObj.visit_date+'" data-uid="'+dataObj.uid+'"  data-visit_date="'+dataObj.visit_date+'" data-uic="'+dataObj.uic+'" >';
            txt_row += row_content;
            txt_row += '</tr">';
            */
        }//for

        $('.r_sut').remove(); // row pid list
        $('#tbl_sut_pid_list > tbody:last-child').append(txt_row);

    }
    else{
      $.notify("No record found.", "info");
      $('.r_sut').remove(); // row pid list
      txt_row += '<tr class="r_sut r_zero_sut" ><td colspan="7" align="center">ไม่พบข้อมูล</td></tr">';
      $('#tbl_sut_pid_list > tbody:last-child').append(txt_row);
    }

    //$('#sel_sut_form_opt').val("");

  }
}

function openSUTScreen(age){
  var screen_date = '<? echo (new DateTime())->format('Y-m-d');?>';

  var link = "visit_form/f_form_proj.php?";
  link += "uid="+cur_sut_pre_uid; // uid
  link += "&form_id=sut_pre_screen";
  link += "&visit_date="+screen_date; // screen date
  link += "&visit_id=SCRN"; // visit id
  link += "&proj_id="+proj_id; // project id
  link += "&group_id=0"; // group id (just screening/ no group )
  link += "&age="+age; // age at screen date
  link += "&clinic_id=<? echo $clinic_id;?>"; // clinic_id

//alert("link: "+link);
  $('#div_sut_detail_form').html("รอสักครู่");
  $('#title_sut_detail').html("SCREENING [UID: "+cur_sut_pre_uid+" / UIC: "+cur_sut_pre_uic+"]");

    $("#div_sut_detail_form").load(link, function(){
        showMenuSUTDiv("sut_detail");
        $('#sut_info').hide();
    });
}


function openSUT_FormMain(choice, uid,uic,pid, screen_date, visit_date, testNo){
//alert("open "+choice+"/"+uid+"/"+screen_date+"/"+visit_date);
  var form_id="";
  var visitDate = "";
  var visitID = "";
  cur_sut_pre_uic = uic;
  cur_sut_pre_uid = uid;
  cur_sut_pre_pid = pid;

  if(choice == '0'){ // consent
     form_id="v1_sut_pre_consent";
     visitDate = screen_date;
     visitID = "SCRN";
  }

  else if(choice == '1'){ // screen
     form_id="sut_pre_screen";
     visitDate = screen_date;
     visitID = "SCRN";
  }
  else if(choice == '2'){ // visit
     form_id="v1_sut_previsit";
     visitDate = visit_date;
     visitID = "PRE";
  }
  else if(choice == '3'){ // follow
    form_id="v1_sut_pre_follow";
    visitDate = screen_date;
    visitID = "FOLLOW";
  }


  var link = "visit_form/f_form_proj.php?";
  link += "uid="+cur_sut_pre_uid; // uid
  link += "&form_id="+form_id; // form_id
  link += "&visit_date="+visitDate; // visit date
  link += "&visit_id="+visitID; // visit date
  link += "&proj_id="+proj_id; // project id
  link += "&test_no="+testNo; // test no  (1 or 2)
  link += "&group_id=0"; // group id (just screening/ no group )


//alert("link: "+link);
  $('#div_sut_detail_form').html("รอสักครู่");
  $('#title_sut_detail').html("<b>PID: "+cur_sut_pre_pid+"</b> [UID: "+cur_sut_pre_uid+" / UIC: "+cur_sut_pre_uic+"]");

    $("#div_sut_detail_form").load(link, function(){
        showMenuSUTDiv("sut_detail");
        $('#sut_info').hide();
    });

}



function openSUT_FormLink(choice, uid, uic, visit_date){ // choice 1:paper, 2:online
  var form_id = "";
  if(choice == '1'){ // paper
    form_id = "v1_sut_previsit_paper";
  }
  else if(choice == '2'){ // online
    form_id = "v1_sut_previsit";
  }

  var open_link = "w_proj_SUT_PRE/link_form.php?";
  open_link += "uid="+uid; // uid
  open_link += "&visit_date="+visit_date; // screen date
  open_link += "&visit_id=PRE"; // visit id
  open_link += "&proj_id=SUT_PRE"; // project id

  open_link += "&uic="+uic; // uic
 // open_link += "&pid="+$('#cur_pid').val(); // pid

  open_link += "&form_id="+form_id;// form id
  open_link += "&form_name=STANDUP-TEEN"; // form name

//alert(" link "+open_link);
  window.open(open_link);
}


function afterScreen_FormOnline(){
      var aData = {
                u_mode:"add_sut_online",
                uid:cur_sut_pre_uid
      };
      save_data_ajax(aData,"w_proj_SUT_PRE/db_sut.php",afterScreen_FormOnlineComplete);
}

function afterScreen_FormOnlineComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

    cur_sut_pre_pid = 'wait_pid';
    cur_sut_pre_screen_date = rtnDataAjax.visit_date;
    cur_sut_pre_visit_date = '';
  //  $.notify("Update PID "+cur_sut_pre_pid, "info");


    $('#title_sut_detail').html("[PID:"+cur_sut_pre_pid+"/UID: "+cur_sut_pre_uid+"/UIC: "+cur_sut_pre_uic+"]");
    $('#sut_info').show();

      var txt_row = addRowData(
       cur_sut_pre_uid,cur_sut_pre_uic,cur_sut_pre_pid,
       cur_sut_pre_screen_date, cur_sut_pre_visit_date,
       2, 'Y', '','0',
       '', '', '','','1'  );
/*
content_type,  s_consent, p_consent,is_confirm_consent,
send_date, receive_date, test_date, destroy_date, test_no  ){
*/
     $('.r_zero_sut').remove();
     $('#tbl_sut_pid_list > tbody:last-child').append(txt_row);
     openSUT_FormLink('2',cur_sut_pre_uid, cur_sut_pre_uic, cur_sut_pre_screen_date);

  }


}


function afterScreen_FormPaper(){

  //alert("afterscreen_paper");
  /*
      var aData = {
                u_mode:"add_sut_paper",
                uid:cur_sut_pre_uid
      };
*/
     // use confirm from CSL like online type
      var aData = {
                u_mode:"add_sut_online",
                uid:cur_sut_pre_uid
      };
      save_data_ajax(aData,"w_proj_SUT_PRE/db_sut.php",afterScreen_FormPaperComplete);

}

function afterScreen_FormPaperComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
  //  alert("afterscreen_paper _complete");
/*
    cur_sut_pre_pid = rtnDataAjax.pid;
    cur_sut_pre_screen_date = rtnDataAjax.visit_date;
    cur_sut_pre_visit_date = '';
*/

    cur_sut_pre_pid = 'wait_pid';
    cur_sut_pre_screen_date = rtnDataAjax.visit_date;
    cur_sut_pre_visit_date = '';

    $('#title_sut_detail').html("[PID:"+cur_sut_pre_pid+"/UID: "+cur_sut_pre_uid+"/UIC: "+cur_sut_pre_uic+"]");
    $('#sut_info').show();

     var txt_row = addRowData(
      cur_sut_pre_uid,cur_sut_pre_uic,cur_sut_pre_pid,
      cur_sut_pre_screen_date, cur_sut_pre_visit_date,
      1, '', 'Y', '0',
      '', '', '','','1'  );

    $('.r_zero_sut').remove();
    $('#tbl_sut_pid_list > tbody:last-child').append(txt_row);

    openSUT_FormLink('1',cur_sut_pre_uid, cur_sut_pre_uic, cur_sut_pre_screen_date);

  }


}

function afterScreen_notConsent(){
/*
  var row_content = '<td><button class="btn btn-primary btn-sm btn_sut_pid" type="button" data-uid="'+cur_sut_pre_uid+'" data-visit_date="'+cur_sut_pre_screen_date+'" data-s_consent=""><i class="fa fa-user" ></i> <b>ไม่ผ่าน</b> </button></td>';
  row_content += ' <td>'+cur_sut_pre_uid+' ['+cur_sut_pre_uic+']</td>';
  row_content += ' <td>-</td>';
  row_content += ' <td>-</td>';
  row_content += ' <td></td>';
  row_content += ' <td></td>';

  var txt_row = '<tr class="r_sut " id="r_'+cur_sut_pre_uid+cur_sut_pre_screen_date+'" data-uid="'+cur_sut_pre_uid+'"  data-visit_date="'+cur_sut_pre_screen_date+'" data-uic="'+cur_sut_pre_uic+'" >';
  txt_row += row_content;
  txt_row += '</tr">';
  $('#tbl_sut_pid_list > tbody:last-child').append(txt_row);
*/
  close_SUT_Detail();
}



function exportData_SUT_PRE(){

      var aData = {
                u_mode:'all'
      };

      save_data_ajax(aData,"w_proj_SUT_PRE/xls_sut_pre_data_export.php",exportData_SUT_PRE_Complete);

}

function exportData_SUT_PRE_Complete(flagSave, rtnDataAjax, aData){
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
