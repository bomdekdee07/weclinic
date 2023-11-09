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

<div id='div_covid_list' class='div-covid-menu mt-0 '>
  <div class="row mt-4">
     <div class="col-sm-3">

           <div><h5><i class="fa fa-users fa-lg" ></i>แบบสอบถาม COVID19</h5></div>
           <div><h5><span id="txt_group_name_covid" style="font-weight: bold;text-decoration: underline;"> กรุณาเลือกกลุ่ม </span></h5>
           </div>
           <div id="div_paper_input">
             <input type="text" id="txt_paper_date" size="20">
             <button type="button"  id="btn_covid_new_paper" style="display:none;"><i class="fa fa-file fa-lg" ></i> กรอกกระดาษ (ย้อนหลัง)</button>
           </div>


     </div>
     <div class="col-sm-1 pl-0">
       <div><span class="text-white">.</span></div>
       <button type="button" class="form-control btn btn-warning " id="btn_covid_export"><i class="fa fa-file fa-lg" ></i> Export</button>

     </div>
     <div class="col-sm-2 pl-0">
       <div><center>PUIs and Infected cases</center></div>
       <button type="button" class="form-control btn btn-primary  btn-mnu-covid" data-id="1"><i class="fa fa-users fa-lg" ></i> กลุ่ม 1  </button>

     </div>
     <div class="col-sm-2 pl-0">
       <div><center>Community</center></div>
       <button type="button" class="form-control btn btn-primary  btn-mnu-covid" data-id="2"><i class="fa fa-users fa-lg" ></i> กลุ่ม 2</button>

     </div>
     <div class="col-sm-2 pl-0">
       <div><center>HCW</center></div>
       <button type="button" class="form-control btn btn-primary  btn-mnu-covid" data-id="3"><i class="fa fa-users fa-lg" ></i> กลุ่ม 3</button>

     </div>
     <div class="col-sm-2 pl-0">
       <div><center>Public Online</center></div>
       <button type="button" class="form-control btn btn-primary  btn-mnu-covid" data-id="4"><i class="fa fa-users fa-lg" ></i> กลุ่ม 4</button>

     </div>

   </div>



  <div class="row mt-2">
    <div class="col-sm-2">
      <label for="btn_new_covid" class="text-light">.</label>
     <button class="btn btn-success form-control" type="button" id="btn_new_covid"><i class="fa fa-qrcode" ></i> ฟอร์มใหม่</button>
    </div>
    <div class="col-sm-3">
      <label for="sel_covid_form_opt">เลือกข้อมูลจาก:</label>
      <select id="sel_covid_form_opt" class="form-control" >
        <option value="select_pid">ผ่านการคัดกรอง/ตอบแบบสอบถาม</option>
        <option value="select_pid2">ผ่านการคัดกรอง/ไม่ตอบแบบสอบถาม</option>
        <option value="select_not_pass_scrn">ไม่ผ่านการคัดกรอง</option>
      </select>
    </div>
     <div class="col-sm-5">
       <label for="txt_search_covid">ค้นหาโดย PID</label>
       <input type="text" id="txt_search_covid" class="form-control" placeholder="พิมพ์คำค้นหา">
     </div>

     <div class="col-sm-2">
       <label for="btn_search_covid" class="text-light">.</label>
      <button class="btn btn-info form-control" type="button" id="btn_search_covid"><i class="fa fa-search" ></i> ค้นหา</button>
     </div>



   </div>
   <div class="mt-2">
     <table id="tbl_covid_pid_list" class="table table-bordered table-sm table-striped table-hover">
         <thead>
           <tr>
             <th>วันที่เก็บข้อมูล</th>
             <th>คัดกรอง ScreenID</th>
             <th>PID</th>
             <th>ใบยินยอม</th>
             <th>แบบสอบถาม</th>
             <th>ข้อมูลติดต่อ</th>
             <th>เจ้าหน้าที่ตรวจสอบ</th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div>

<div id='div_covid_detail' class="div-covid-menu mt-0">
  <div class="row bg-primary text-white">
     <div class="col-sm-11">
       <b><span id="title_covid_detail" ></span></b>
     </div>


     <div class="col-sm-1 pr-0">
       <button id="btn_close_covid_detail" class="my-1 form-control form-control-sm btn btn-light btn-sm float-right" type="button">
         <i class="fa fa-times-circle fa-lg" ></i> ปิด
       </button>
     </div>
  </div>

  <div>

    <div id='div_covid_detail_form' >

    </div>

  </div>
</div> <!-- div_covid_detail -->




<script>
alert("enter01");
//$("#div_paper_input").hide();
<?
 if($s_section_id == "DATA")
 $cur_date = (new DateTime())->format('Y-m-d');
 echo '
$("#btn_covid_new_paper").show();

$("#txt_paper_date").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});
$("#txt_paper_date").val(changeToThaiDate("'.$cur_date.'"));
 '

?>


alert("enter02");
var cur_covid_group_id = "";
var cur_covid_pid = "";
var cur_covid_screen_id = "";


$(document).ready(function(){
//  alert("xx "+s_clinic_id);
  $.notify.defaults({ autoHideDelay: 15000 });
  showMenuCovidDiv("covid_list");



$("#btn_covid_new_paper").click(function(){
   addNewCovid_Paper();
}); // btn_covid_new_paper

  $("#btn_covid_export").click(function(){
     exportData_covid();
  }); // btn_covid_pre_export

  $("#btn_search_covid").click(function(){
     searchData_covid();
  }); // btn_search_covid

  $(".btn-mnu-covid").click(function(){
     cur_covid_group_id = $(this).data("id");
     clearAllData_Covid();

     $("#sel_covid_form_opt").val("select_pid");
     $("#txt_group_name_covid").html("กลุ่มที่ "+cur_covid_group_id);

  }); // btn_search_covid


  $("#txt_search_covid").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_covid();
    }
  });

  $("#btn_new_covid").click(function(){
     addNewCovid();

  }); // btn_new_covid
  $("#btn_close_covid_detail").click(function(){
     close_covid_Detail();
  }); // btn_close_covid_detail

});

function clearAllData_Covid(){
   $('.r_covid').remove();
   $('#txt_search_covid').val("");
   showMenuCovidDiv("covid_list");

}

function close_covid_Detail(){
  cur_covid_pid = "";
  cur_covid_screen_id = "";
  showMenuCovidDiv("covid_list");
}



function addNewCovid(){ // add new form
    if(cur_covid_group_id == ""){
      $("#btn_new_covid").notify("กรุณาเลือกกลุ่ม", "info");
      return;
    }
    var form_id = "covid_screen_g"+cur_covid_group_id;
    var open_link = "w_proj_covid19/link_form.php?";
    open_link += "&group_id="+cur_covid_group_id; // group_id
    open_link += "&form_id="+form_id;// form id
    open_link += "&form_name=COVID19 Group "+cur_covid_group_id; // form name

    //alert(" link "+open_link);
    window.open(open_link);
}

function addNewCovid_Paper(){ // add new form
    if(!validateDate($('#txt_paper_date').val())){
      $("#txt_paper_date").notify("ใส่วันที่กรอกข้อมูลในกระดาษให้ถูกต้อง", "error");
      return;
    }
    if(cur_covid_group_id == ""){
      $("#btn_covid_new_paper").notify("กรุณาเลือกกลุ่ม", "info");
      return;
    }

    var visit_date = changeToEnDate($('#txt_paper_date').val());
    var form_id = "covid_form_paper";
    var open_link = "visit_form/f_form_proj.php?";
    open_link += "form_id=covid_screen_g"+cur_covid_group_id; // form_id
    open_link += "&proj_id=COVID"; //
    open_link += "&visit_date="+visit_date; // visit_date
    open_link += "&group_id="+cur_covid_group_id; // group_id
    open_link += "&is_paper=Y"; //
    //alert(" link "+open_link);
    window.open(open_link);
}


//function addRowData(uid,uic,pid,screen_date, visit_date, content_type,  s_consent,is_confirm_consent, send_date, get_date, follow_date  ){
function addRowData(u_mode, pid,screen_id, collect_date, staff_id, is_online){

    var btn_screen = "";
    var btn_consent = "";
    var btn_visit = "";
    var btn_contact = "";
    var txt_pid = "";
    btn_screen = '<button class="btn btn-primary btn-sm " type="button" data-pid="'+pid+'"  onclick="opencovid_FormMain(\''+screen_id+'\',\''+collect_date+'\',\''+is_online+'\',\'1\')""><i class="fa fa-user" ></i> คัดกรอง ['+screen_id+']</button>';
    if(pid != ''){// pass screen
      if(u_mode == "select_pid"){
        txt_pid = 'TRC063-00-<b>'+pid+'</b>';
        btn_consent = '<button class="btn btn-warning btn-sm " type="button" data-pid="'+pid+'"  onclick="opencovid_FormMain(\''+pid+'\',\''+collect_date+'\',\''+is_online+'\',\'2\')""><i class="fa fa-user" ></i> ใบยินยอม </button>';
        btn_visit = '<button class="btn btn-info btn-sm " type="button" data-pid="'+pid+'"  onclick="opencovid_FormMain(\''+pid+'\',\''+collect_date+'\',\''+is_online+'\',\'3\')""><i class="fa fa-user" ></i> แบบฟอร์ม </button>';
        btn_contact = '<button class="btn btn-success btn-sm " type="button" data-pid="'+pid+'"  onclick="opencovid_FormMain(\''+pid+'\',\''+collect_date+'\',\''+is_online+'\',\'4\')""><i class="fa fa-user" ></i> ข้อมูลติดต่อ </button>';
      }
      else if(u_mode == "select_pid2"){
        btn_consent = '<button class="btn btn-warning btn-sm " type="button" data-pid="'+pid+'"  onclick="opencovid_FormMain(\''+pid+'\',\''+collect_date+'\',\''+is_online+'\',\'2\')""><i class="fa fa-user" ></i> ใบยินยอม </button>';
        btn_visit = 'ไม่ยินดีตอบ';
      }

    }else{
      if(u_mode == "select_pid2"){
        btn_consent = '<button class="btn btn-warning btn-sm " type="button" data-pid="'+pid+'"  onclick="opencovid_FormMain(\''+pid+'\',\''+collect_date+'\',\''+is_online+'\',\'2\')""><i class="fa fa-user" ></i> ใบยินยอม </button>';
      }
    }

    if(is_online == '0' && u_mode != "select_not_pass_scrn"){
      btn_consent = 'กระดาษ';
      staff_id = '<span class="badge badge-info">ไม่ต้องลงนาม</span>';
    }

    collect_date = (collect_date != '')?changeToThaiDate(collect_date):'';

var row_content = "";
    //row_content += '<td><button class="btn btn-primary btn-sm btn_covid_pid" type="button" data-uid="'+uid+'" data-s_date="'+screen_date+'"  data-v_date="'+visit_date+'" data-s_consent="'+s_consent+'"><i class="fa fa-user" ></i> <b>'+pid+'</b> </button></td>';

    row_content += ' <td>'+collect_date+'</td>'; //วันเก็บข้อมูล
    row_content += ' <td>'+btn_screen+'</td>'; // screen form
    row_content += ' <td>'+txt_pid+'</td>';
    row_content += ' <td>'+btn_consent+'</td>'; // ใบยินยอม consent form
    row_content += ' <td>'+btn_visit+'</td>'; // แบบสอบถาม
    row_content += ' <td>'+btn_contact+'</td>'; // ข้อมูลติดต่อ
    row_content += ' <td><span id="c'+pid+'">'+staff_id+'</span></td>'; // เจ้าหน้าที่ตรวจสอบ

  var txt_row = '<tr class="r_covid " id="r_'+screen_id+'" data-pid="'+pid+'" >';
  txt_row += row_content;
  txt_row += '</tr">';
  return txt_row;
}


function searchData_covid(){
  if(cur_covid_group_id == ""){
    $("#btn_new_covid").notify("กรุณาเลือกกลุ่ม", "info");
    return;
  }

      var aData = {
                u_mode:$("#sel_covid_form_opt").val() ,
                txt_search:$('#txt_search_covid').val(),
                group_id:cur_covid_group_id
      };
      save_data_ajax(aData,"w_proj_covid19/db_covid.php",searchDatacovid_Complete);

}

function searchDatacovid_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var txt_row="";

    if(rtnDataAjax.datalist.length > 0){
      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];

            txt_row += addRowData(
              aData.u_mode,
             dataObj.pid,
             dataObj.sid,
             dataObj.c_date,
             dataObj.staff,
             dataObj.is_online
            );


        }//for

        $('.r_covid').remove(); // row pid list
        $('#tbl_covid_pid_list > tbody:last-child').append(txt_row);

    }
    else{
      $.notify("No record found.", "info");
      $('.r_covid').remove(); // row pid list
      txt_row += '<tr class="r_covid"><td colspan="7" align="center">ไม่พบข้อมูล</td></tr">';
      $('#tbl_covid_pid_list > tbody:last-child').append(txt_row);
    }

    //$('#sel_covid_form_opt').val("");

  }
}



function opencovid_FormMain(id, visitDate, is_online, choice){
  var form_id="";
  var proj_id = "COVID";
  var txtID = "";

  if(choice == '1'){ // screen
     form_id="covid_screen_g"+cur_covid_group_id;
     txtID = "Screen ID: "+id;
     cur_covid_screen_id = id;
  }
  else if(choice == '2'){ // consent
     form_id="covid_adm_consent";
     txtID = "PID: "+id;
     cur_covid_pid = id;
  }
  else if(choice == '3'){ // visit
     //form_id="covid_adm_visit_g"+cur_covid_group_id;
     form_id="covid_adm_visit";
     txtID = "PID: "+id;
     cur_covid_pid = id;
  }
  else if(choice == '4'){ // contact
     form_id="covid_adm_contact";
     txtID = "PID: "+id;
     cur_covid_pid = id;
  }

  var link = "visit_form/f_form_proj.php?";
  link += "uid="+id; // uid
  link += "&form_id="+form_id; // form_id
  link += "&visit_date="+visitDate; // visit date
  link += "&proj_id="+proj_id; // project id
  link += "&group_id="+cur_covid_group_id; // group id
  link += "&is_online="+is_online; // is_online

  //alert("link: "+link);
  $('#div_covid_detail_form').html("รอสักครู่");
  $('#title_covid_detail').html("<center>"+txtID+"</center>");
  $("#div_covid_detail_form").load(link, function(){
    //  showMenuCovidDiv("covid_detail");
  });
showMenuCovidDiv("covid_detail");
}



function exportData_covid(){

      var aData = {
                u_mode:'all'
      };

      save_data_ajax(aData,"w_proj_covid19/xls_covid_data_export.php",exportData_covid_Complete);

}

function exportData_covid_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    window.open(rtnDataAjax.link_xls, '_blank');
  }
}

// div in Menu
function showMenuCovidDiv(choice){
  $('.div-covid-menu').hide();
  $('#div_'+choice).show();
}



</script>
