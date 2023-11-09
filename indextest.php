





<!doctype html>
<html>
<head>
<title>weClinic ระบบจัดการข้อมูลคลินิก</title>

	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<!-- Apple devices fullscreen -->
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<!-- Apple devices fullscreen -->
	<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	<!-- Favicon -->
	<link rel="shortcut icon" href="img/favicon.ico" />
	<!-- Apple devices Homescreen icon -->
	<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png" />


<!--
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/i18n/datepicker-th.js"></script>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

  <script src="js/notify.min.js"></script>
-->



<link rel="stylesheet" href="asset/jquery-ui.css">
<script src="asset/jquery.min.js"></script>
<script src="asset/jquery-ui-custom.js"></script>
<script src="asset/datepicker-th.js"></script>

<link rel="stylesheet" href="asset/bootstrap4.1.3/css/bootstrap.min.css">
<script src="asset/popper.min.js" ></script>
<script src="asset/bootstrap4.1.3/js/bootstrap.min.js"></script>

<script src="asset/notify.min.js"></script>
<script src="asset/jquery.qrcode.min.js"></script>
<script src="asset/jquery.maskedinput.js"></script>

<script src="asset/jquery.floatThead.min.js"></script>

<link rel="stylesheet" href="asset_iclinic/iclinic.css">
<link rel="stylesheet" href="asset/fontawesome/css/all.css">

<style>
#div_loading {
  /*  background-color:#EEE;*/
    display: table;
    width: 100%;
    height: 400px;
}
#loading_box {

    display: inline-block;
    vertical-align: top;
}
.v-align {
    padding: 10px;
    display: table-cell;
    text-align: center;
    vertical-align: middle;
}

.dropdown-submenu {
  position: relative;
}

.dropdown-submenu a::after {
  transform: rotate(-90deg);
  position: absolute;
  right: 6px;
  top: .8em;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-left: .1rem;
  margin-right: .1rem;
}

</style>

</head>
<body>

  <nav class="navbar navbar-expand-sm navbar-dark bg-info">
    <a class="navbar-brand mr-4" href="#"><i class="fas fa-clinic-medical"></i> PREVENTION <b>we</b>Clinic </a> <span class='badge badge-warning px-1 py-1'>IHRI Bangkok</span>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navb">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active mnu" id="mnu_dashboard">
          <a class="nav-link mnu-main" data-id="dashboard" href="javascript:void(0)">หน้าแรก</a>
        </li>

        <li class="nav-item mnu" id="mnu_in_process">
          <a class="nav-link mnu-sub" data-id="in_process" href="javascript:void(0)">เคสรอดำเนินการ</a>
        </li>


            <li class="nav-item mnu" id="mnu_counseling">
              <a class="nav-link mnu-sub" data-id="counseling" href="javascript:void(0)">การให้คำปรึกษา</a>
            </li>

            <li class="nav-item mnu" id="mnu_lab">
              <a class="nav-link mnu-sub" data-id="lab" href="javascript:void(0)">ตรวจ LAB</a>
            </li>

            <li class="nav-item dropdown no-arrow mnu" id="mnu_data">
              <a class="nav-link dropdown-toggle" href="#" id="projDataDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-table fa-lg" ></i> <span class="d-none d-md-inline-block"> จัดการข้อมูล </span>

              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="projDataDropdown">
                <a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_data_view" data-id="mnu_schedule_proj_POC_log"><i class="fa fa-table"></i> ตารางนัดหมาย Point of Care</a>
                <a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_data_view" data-id="mnu_data_export"><i class="fa fa-file-export fa-lg" ></i> Data Export</a>



                <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Submenu</a>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="#">Submenu action</a></li>
                          <li><a class="dropdown-item" href="#">Another submenu action</a></li>


                          <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Subsubmenu</a>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="#">Subsubmenu action</a></li>
                              <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                            </ul>
                          </li>
                          <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Second subsubmenu</a>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="#">Subsubmenu action</a></li>
                              <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                            </ul>
                          </li>



                        </ul>
                      </li>








                <a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_hos" data-id="dashboard_center_pro"><i class="fa fa-hospital fa-lg" ></i> SDART Hospital Prospective</a><a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_hos" data-id="dashboard_center_retro"><i class="fa fa-hospital fa-lg" ></i> SDART Hospital Retrospective</a>

                <a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="mnu_surveygizmo"><span class="text-primary"><i class="fa fa-kiwi-bird fa-lg" ></i> ดูฟอร์มจาก SurveyGizmo แบบ CBO</span></a><a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="trc_mnu_surveygizmo"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo - TRC</a><a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="mnu_surveygizmo_revise"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo (CBO)</a><a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="trc_mnu_surveygizmo_revise"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo (TRC)</a>
                <a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_proj_SUT" data-id="mnu_data_list"><i class="fa fa-file fa-lg" ></i> STANDUP-TEEN Pre Study</a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_monitor" data-id="mnu_viewlog"><i class="fa fa-eye fa-lg" ></i> View Log</a>
              </div>
            </li>


      <!--
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            เลือกโครงการ
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#"><i class="fas fa-arrow-alt-circle-right"></i> Point of Care</a>
            <a class="dropdown-item disabled" href="#"><i class="fas fa-arrow-alt-circle-right"></i> Princess PrEP</a>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link disabled" href="javascript:void(0)">Disabled</a>
        </li>
    -->

      </ul>

      <ul class="navbar-nav ml-auto mr-4">
        <li class="nav-item dropdown no-arrow active">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i> <span class="d-none d-md-inline-block"> <b>P1901</b> ภาณุ ศรีวชิรโรจน์ [System Administrator] </span>


          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item mnu-user" href="javascript:void(0);" data-id="w_change_pwd"><i class="fa fa-key" aria-hidden="true"></i> เปลี่ยนรหัสผ่าน</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="logout.php" ><span class='text-danger'><i class="fa fa-sign-out-alt" aria-hidden="true"></i> ออกจากระบบ</span></a>
          </div>
        </li>
      </ul>
      <!--
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" placeholder="Search" type="text">
        <button class="btn btn-success my-2 my-sm-0" type="button">Search</button>
      </form>
    -->
    </div>
  </nav>

<div class="container-fluid" style="margin-top:30px">
  <div id="div_main">
      <div id='div_dashboard' class='div-main'>
  <div class="row mb-4">

    <div class="col-sm-3">
      <div>
      <div class="input-group mb-3" id ="div_search_uid">
        <input type="text" id="txt_search_uic" class="form-control v-no-blank" data-title="UID" placeholder="กรอก UIC หรือ UID" aria-label="UID" aria-describedby="UID">
        <div class="input-group-append">
          <button class="btn btn-primary" type="button" id="btn_search_uid"><i class="fa fa-search" ></i> ค้นหา</button>
        </div>
      </div>
      </div>




      <div class="my-4">
        <button class="btn btn-warning form-control" type="button" id="btn_new_uid" ><i class="fa fa-folder-plus fa-lg" ></i> ลงทะเบียน UID ใหม่</button>
      </div>


      <div class="mt-3 px-2 py-3" style="border: 1px solid #CCC;">
        <i class="fa fa-user-tag fa-lg text-info"></i>  ค้นหาโดย PID<br>
        <div class="my-2">
          <select id="sel_proj_search_pid" class="form-control">
            <option value="POC" data-name="Point of Care">Point of Care (POC)</option>
          </select>
        </div>
        <div class="input-group mb-2" id ="div_search_pid">
          <input type="text" id="txt_search_pid" class="form-control v-no-blank" data-title="PID" placeholder="กรอก PID" aria-label="PID" aria-describedby="PID">
          <div class="input-group-append">
            <button class="btn btn-primary" type="button" id="btn_search_pid"><i class="fa fa-search" ></i> ค้นหา</button>
          </div>
        </div>
      </div>
      <div id="div_iclinic" class="mt-4">

      </div>

    </div>

    <div class="col-sm-9">


<div class="card" id="div_uid_schedule_list">
  <div class="card-body">
    <div class="card-title">


      <div class="row">
         <div class="col-sm-2">
           <h5><i class="fa fa-calendar-alt fa-lg" ></i> ตารางนัดหมาย</h5>
         </div>

         <div class="col-sm-2">
           <label for="btn_export_uid_schedule" class="text-light">.</label>
          <button class="btn btn-primary form-control" type="button" id="btn_export_uid_schedule"><i class="fa fa-file-export" ></i> Export</button>
         </div>
         <div class="col-sm-2">
           <label for="search_date_opt">ค้นหาจาก:</label>
           <select id="search_date_opt" class="form-control" >
             <option value="schedule_date" selected >วันนัดหมาย (Schedule Date)</option>
             <option value="visit_date">วันเข้าตรวจ (Visit Date)</option>
           </select>
         </div>
         <div class="col-sm-2">
           <label for="sel_uid_schedule_date_beg">ตั้งแต่วันที่:</label>
           <input type="text" id="sel_uid_schedule_date_beg" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="sel_uid_schedule_date_end">ถึงวันที่:</label>
           <input type="text" id="sel_uid_schedule_date_end" class="form-control" readonly='readonly'>
         </div>
         <div class="col-sm-2">
           <label for="btn_search_uid_schedule" class="text-light">.</label>
          <button class="btn btn-info form-control" type="button" id="btn_search_uid_schedule"><i class="fa fa-search" ></i> ค้นหา</button>
         </div>



       </div>


    </div>
    <div>
      <table id="tbl_uid_schedule_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th>วันที่นัดหมาย</th>
              <th>วันที่เข้าตรวจ</th>
              <th>โครงการ</th>
              <th>PID</th>
              <th>UID / UIC</th>
              <th>สถานะ</th>
              <th>หมายเหตุ</th>
              <th>ติดต่อ</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
      </table>
    </div>
  </div>
</div>




<input type="hidden" id="cur_schedule_date">

<script>

$(document).ready(function(){

  initScheduleList();

  $("#btn_search_uid_schedule").click(function(){
     //alert("clinic scheud");
     selectUIDScheduleList();
  }); // btn_search_uid_schedule
  $("#btn_export_uid_schedule").click(function(){
     //alert("clinic scheud");
     exportUIDSchedule();
  }); // btn_export_uid_schedule


  var currentDate = new Date();
  currentDate.setYear(currentDate.getFullYear() + 543);

    $("#sel_uid_schedule_date_beg").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy',
      onSelect: function(date) {
        $("#sel_uid_schedule_date_beg").addClass('filled');
      }
    });
    $("#sel_uid_schedule_date_end").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy',
      onSelect: function(date) {
        $("#sel_uid_schedule_date_end").addClass('filled');
      }
    });

    $('#sel_uid_schedule_date_beg').datepicker("setDate",currentDate );
    $('#sel_uid_schedule_date_end').datepicker("setDate",currentDate );

    $('#sel_uid_schedule_date_beg').change(function(){
      //alert("change ja");
      //$("#sel_uid_schedule_date_end" ).datepicker('setDate', new Date($("#sel_uid_schedule_date_beg" ).val()));
    });


    $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
      if (!$(this).next().hasClass('show')) {
        $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
      }
      var $subMenu = $(this).next(".dropdown-menu");
      $subMenu.toggleClass('show');


      $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
        $('.dropdown-submenu .show').removeClass("show");
      });


      return false;
    });



});


function initScheduleList(){
//  selectUIDScheduleList();
}


function selectUIDScheduleList(){

  var aData = {
            u_mode:"select_uid_schedule_list",
            search_date_opt:$('#search_date_opt').val(),
            schedule_date_beg:changeToEnDate($('#sel_uid_schedule_date_beg').val()),
            schedule_date_end:changeToEnDate($('#sel_uid_schedule_date_end').val())
  };
  save_data_ajax(aData,"w_user/db_uid_data.php",selectUIDScheduleListComplete);

}

function selectUIDScheduleListComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
//tbl_uid_schedule_list
      txt_row="";
      if(rtnDataAjax.uid_list.length > 0){
        var enroll_date = "";
        var btn_pid = "";
        var datalist = rtnDataAjax.uid_list;
          for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];

            btn_pid = "";

            btn_pid = '<button class="btn btn-info" type="button" onclick="goVisitListBySchedule(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\',\''+dataObj.pid+'\',\''+dataObj.uid+'\',\''+dataObj.uic+'\')""><i class="fa fa-user"></i> '+dataObj.pid+'</button>';

            txt_row += '<tr class="r_uid_schedule">';
            txt_row += ' <td>'+changeToThaiDate(dataObj.schedule_date)+'</td>';
            txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+'</td>';
            //txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+'</td>';
            txt_row += ' <td>'+dataObj.proj_name;
            txt_row += ' <span class="badge badge-warning">'+dataObj.visit_name+ '</span></td>';
            txt_row += ' <td>'+btn_pid+'</td>';
            txt_row += ' <td>'+dataObj.uid+' / '+dataObj.uic+'</td>';

            txt_row += ' <td>'+dataObj.status_name+'</td>';
            txt_row += ' <td>'+dataObj.schedule_note+'</td>';
            txt_row += ' <td>'+dataObj.tel+'</td>';

            txt_row += '</tr">';
          }//for

      }
      $('.r_uid_schedule').remove(); // row uic proj summary
      $('#tbl_uid_schedule_list > tbody:last-child').append(txt_row);


  }
}


function exportUIDSchedule(){
    var aData = {
      date_beg:changeToEnDate($('#sel_uid_schedule_date_beg').val()),
      date_end:changeToEnDate($('#sel_uid_schedule_date_end').val())
    };
    save_data_ajax(aData,"w_data/xls_uid_schedule_list.php",exportUIDScheduleComplete);
}

function exportUIDScheduleComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave+" / "+rtnDataAjax.link_xls);
  if(flagSave){
    window.open(rtnDataAjax.link_xls, '_blank');
  }
}


</script>

    </div>
  </div>
</div>

<div id="div_uid" class='div-main'>
  <div class="my-4">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-11">
            <h1>UID: <b><span id="title_uid_id">uid_CODE x</span></b> <b><span class="badge badge-primary" id="title_uic_id">uic_CODE</span></b></h1>
         </div>
         <div class="col-sm-1">
           <button type="button" id="btn_close_uid_info" class="close " aria-label="Close">
                <span aria-hidden="true" class="text-danger"><b><h2>&times;</h2></b></span>
           </button>
        </div>
        </div>
      </div>
      <div class="card-body" >

        <div id="div_uid_info" class="div-uid">
          <div class="row" >
            <div class="col-sm-3">

              <div class="card">
                <div class="card-body">
                  <h5 class="card-title"><i class="fa fa-folder fa-lg" ></i> ข้อมูล UID</h5>
                  <p class="card-text" id="info_uid">UID INFO</p>
                </div>
              </div>

              <div class="card my-1">
                <div class="card-body">


                       <div class="my-1" id="div_sel_proj_reg">
                         <button id="btn_proj_screening" class="form-control btn btn-info btn-lg" type="button">
                           <h5> <i class="fa fa-first-aid fa-lg" ></i> คัดกรองเข้าโครงการใหม่ </h5>
                         </button>
                       </div>

                <div class="my-1" id="div_mnu_uid_xpress_service">
<!--
                  <button id="btn_uid_xpress_service" class="form-control btn btn-primary btn-lg" type="button">
                    <h5> <i class="fa fa-notes-medical fa-lg" ></i> <b>X</b>Press Service </h5>
                  </button>


-->

<button id="btn_uid_xpress_service" class="form-control btn btn-primary btn-lg" type="button">
  <h5> <i class="fa fa-notes-medical fa-lg" ></i> <b>X</b>Press Service </h5>
</button>


                </div>



                </div>
              </div>
            </div>
            <div class="col-sm-9">
              <table id="tbl_uid_proj" class="table table-bordered table-sm table-striped table-hover">
                  <thead>
                    <tr>

                      <th>PID</th>
                      <th>โครงการที่เข้า</th>
                      <th>วันเข้าโครงการ</th>
                      <th>Visit ล่าสุด</th>
                      <th>นัดหมายถัดไป</th>

                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
              </table>
            </div>
          </div>

        </div><!-- div_uid_info -->

        <div id="div_uid_visit" class="div-uid">

        </div>

        <div id="div_uid_xpress_service" class="div-uid">
            xpress service
        </div>


      </div><!-- end card body-->
    </div>
  </div>

</div> <!-- end div_uid-->
<div id="div_in_process" class='div-main'>

</div>
<div id="div_counseling" class='div-main'>

</div>
<div id="div_lab" class='div-main'>

</div>

<div id="div_export" class='div-main'>

</div>

<div id="div_viewlog" class='div-main'>

</div>

<div id="div_xpress_service" class='div-main'></div>

<div id="div_w_hos" class='div-main'></div>
<div id="div_w_data_view" class='div-main'></div>
<div id="div_w_monitor" class='div-main'></div>
<div id="div_w_ext_surveygizmo" class='div-main'></div>
<div id="div_w_proj_SUT" class='div-main'></div>

<!--
<input type="hidden" id="s_clinic_id">
<input type="hidden" id="cur_uid" >
<input type="hidden" id="cur_uic" >
<input type="hidden" id="cur_proj_id" >
<input type="hidden" id="cur_proj_name" >
<input type="hidden" id="cur_group_id" >
<input type="hidden" id="cur_pid" >
<input type="hidden" id="cur_clinic_id" >

<input type="hidden" id="u_mode_screen" >
<input type="hidden" id="cur_screen_date" >

<input type="hidden" id="u_mode_visit" >
<input type="hidden" id="cur_visit_id" >
<input type="hidden" id="cur_visit_date" >

<input type="hidden" id="data_update_proj" >
<input type="hidden" id="data_update_visit" >

-->

<script>
$(document).ready(function(){
  $.notify.defaults({ autoHideDelay: 15000 });
  $('#s_clinic_id').val('%');
  //alert("staff_clinic_id :"+$('#s_clinic_id').val());
  //  $('#txt_search_uic').val('สอ280835');
//$("#div_dashboard").hide();

  showMainDiv("dashboard");


/*
  var opt_proj=[];
  opt_proj['POC'] = {proj_id:'POC',proj_name:'Point of Care'};
  opt_proj['POC2'] = {proj_id:'POC2',proj_name:'Point of Care 2'};
*/
  $("#btn_search_uid").click(function(){
     searchData_uid();
  }); // btn_search_uid

  $("#btn_search_pid").click(function(){
     searchData_pid();
  }); // btn_search_pid


  $("#txt_search_uic").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_uid();
    }
  });

  $("#btn_new_uid").click(function(){
     window.open('http://192.168.100.11/iclinic/dashboard/auth_l_0/uic_reg.php' , '_blank');
  }); // btn_new_uid

  $("#btn_proj_screening").click(function(){
    $('#u_mode_screen').val("new");
    $('#cur_proj_name').val("");
    projScreen_uid();
  });

  $("#btn_uid_xpress_service").click(function(){ // xpress service of each uid
    loadingShow();

    $("#div_uid_xpress_service").load("xpress_service/uid_xpress_service.php", function(){
        showUIDDiv("uid_xpress_service");
        loadingHide();
    });

/*
    $("#div_uid_xpress_service").load("w_user/proj_screening.php", function(){
        showUIDDiv("uid_xpress_service");
        loadingHide();
    });
*/

  });

  $("#btn_close_uid_info").click(function(){
    clearData_uid();
    if($("#u_mode_visit").val() == "schedule_log"){
      showMainDiv("w_data_view");
    }
    else{ // main dashboard
      showMainDiv("dashboard");
    }

  });

});


function projScreen_uid(){
  loadingShow();
  $("#div_uid_visit").load("w_user/proj_screening.php", function(){
      showUIDDiv("uid_visit");
      loadingHide();
  });
}

function goScreening(ProjectID, ProjectName, screenDate){
  $('#u_mode_screen').val("update");
  $('#cur_proj_id').val(ProjectID);
  $('#cur_proj_name').val(ProjectName);
  $('#cur_screen_date').val(screenDate);

  $("#div_uid_visit").load("w_user/proj_screening.php", function(){
      showUIDDiv("uid_visit");

  });
}


function goVisitList(ProjectID, ProjectName, pid){
  //alert("govisit "+ProjectID);
  $('#u_mode_visit').val("");
  $('#cur_proj_id').val(ProjectID);
  $('#cur_proj_name').val(ProjectName);
  $('#cur_pid').val(pid);

  $("#div_uid_visit").load("w_user/proj_visit.php?proj_id="+ProjectID, function(){
      showUIDDiv("uid_visit");
  });
}

// refresh proj list summary after project enrollment
function goVisitList2(ProjectID, ProjectName, pid){
  $('#u_mode_visit').val("");
  $('#cur_proj_id').val(ProjectID);
  $('#cur_proj_name').val(ProjectName);
  $('#cur_pid').val(pid);

//  $('#title_uid_id').html($('#cur_uid').val());
//  $('#title_uic_id').html($('#cur_uic').val());

  $("#div_uid_visit").load("w_user/proj_visit.php?proj_id="+ProjectID, function(){
      showMainDiv("uid");
      showUIDDiv("uid_visit");
      setDataChangeProj();
  });

}

// open from schedule log
function goVisitList3(ProjectID, ProjectName, uid, uic, pid){
  //alert("govisit "+ProjectID);
  $('#u_mode_visit').val("schedule_log");
  $('#cur_proj_id').val(ProjectID);
  $('#cur_proj_name').val(ProjectName);

  $('#cur_uid').val(uid);
  $('#cur_uic').val(uic);
  $('#cur_pid').val(pid);

  $('#title_uid_id').html($('#cur_uid').val());
  $('#title_uic_id').html($('#cur_uic').val());

  $("#div_uid_visit").load("w_user/proj_visit.php?proj_id="+ProjectID, function(){
    //alert("show uid visit");

      if($("#u_mode_visit").val() == "schedule_log"){
        showMainDiv("uid");
      }
      showUIDDiv("uid_visit");
  });
}

// refresh proj list summary after project enrollment
function goVisitListBySchedule(ProjectID, ProjectName, pid, uid, uic){

  $('#u_mode_visit').val("");
  $('#cur_proj_id').val(ProjectID);
  $('#cur_proj_name').val(ProjectName);
  $('#cur_pid').val(pid);
  $('#cur_uid').val(uid);
  $('#cur_uic').val(uic);

  $('#title_uid_id').html($('#cur_uid').val());
  $('#title_uic_id').html($('#cur_uic').val());

  $("#div_uid_visit").load("w_user/proj_visit.php?proj_id="+ProjectID, function(){
      showUIDDiv("uid_visit");
      showMainDiv("uid");
      setDataChangeProj();
  });
}


function projEnroll_uid(){
  loadingShow();
  $("#div_uid_enroll").load("w_user/proj_enroll.php", function(){
      loadingHide();
      showUIDDiv("uid_enroll");

  });
}



function searchData_uid(){
  if(validateInput("div_search_uid")){
    if($('#txt_search_uic').val().trim().length < 8){
      $('#txt_search_uic').notify("กรุณากรอกขั้นต่ำ 8 ตัวอักษร","warn");
    }
    else{//valid
      $('#txt_search_uic').removeClass("bg-warning")
      var aData = {
                u_mode:"select_data_uid",
                uid:$('#txt_search_uic').val()
      };
      save_data_ajax(aData,"w_user/db_uid_data.php",searchData_uidComplete);

    }

  }
}

function searchData_uid2(){
  if($('#cur_uid').val() != ""){
    var aData = {
              u_mode:"select_data_uid",
              uid:$('#cur_uid').val().trim()
    };
    save_data_ajax(aData,"w_user/db_uid_data.php",searchData_uidComplete);
    //alert("cur_uid "+$('#cur_uid').val());
  }
}

function searchData_uidComplete(flagSave, rtnDataAjax, aData){
//  alert("flag save is : "+flagSave);
  if(flagSave){
//สอ280835
    clearData_uid();
    if(rtnDataAjax.uid_data.name != undefined ){

      var staff_clinic_id="%";
      $('#title_uid_id').html(rtnDataAjax.uid_data.uid);
      $('#title_uic_id').html(rtnDataAjax.uid_data.uic);
      var txt_row = "<b>"+rtnDataAjax.uid_data.name+"</b><br>"+rtnDataAjax.uid_data.address+"<br>Tel: "+rtnDataAjax.uid_data.tel+"<br>Email: "+rtnDataAjax.uid_data.email;
      $('#info_uid').html(txt_row);
      txt_row="";
      if(rtnDataAjax.proj_list.length > 0){

        var enroll_date = "";
        var btn_pid = "";
        var datalist = rtnDataAjax.proj_list;
          for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            btn_pid = "";
            if(dataObj.uid_status == 1 || dataObj.uid_status == 2){ // already enroll
              enroll_date = changeToThaiDate(dataObj.enroll_date);
              /*
              enroll_date = changeToThaiDate(dataObj.enroll_date);
              btn_pid = '<button class="btn btn-info" type="button" onclick="goVisitList(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\',\''+dataObj.pid+'\')""><i class="fa fa-user"></i> '+dataObj.pid+'</button> <span class="badge badge-success">'+dataObj.clinic_name+'</span>';
*/
           var btnColor = "btn-info";
           if(dataObj.uid_status == 2) btnColor = "btn-primary";

           if(staff_clinic_id == "%"){
             btn_pid = '<button class="btn '+btnColor+'" type="button" onclick="goVisitList(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\',\''+dataObj.pid+'\')""><i class="fa fa-user"></i> '+dataObj.pid+'</button> <span class="badge badge-success">'+dataObj.clinic_name+'</span>';
           }
           else{
             if(staff_clinic_id == dataObj.clinic_id){
               btn_pid = '<button class="btn '+btnColor+'" type="button" onclick="goVisitList(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\',\''+dataObj.pid+'\')""><i class="fa fa-user"></i> '+dataObj.pid+'</button> <span class="badge badge-success">'+dataObj.clinic_name+'</span>';
             }
             else{
               btn_pid = '<button class="btn btn-secondary" type="button" onclick="goApproveClinicMove(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\',\''+dataObj.pid+'\')""><i class="fa fa-user"></i> '+dataObj.pid+' <br> [ขออนุมัติย้าย]</button> <span class="badge badge-warning">'+dataObj.clinic_name+'</span>';
             }
           }




            }
            else if(dataObj.uid_status == 0){ // in screening process
              enroll_date = "";
              btn_pid = '<button class="btn btn-warning" type="button" onclick="goScreening(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\',\''+dataObj.screen_date+'\')""><i class="fa fa-filter"></i> รอการคัดกรอง ['+dataObj.screen_date+']</button>';
            }

            else if(dataObj.uid_status == 11){ // screening fail
              enroll_date = "-";
              btn_pid = '<button class="btn btn-danger" type="button" onclick="goScreening(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\',\''+dataObj.screen_date+'\')""><i class="fa fa-filter"></i> ไม่ผ่านคัดกรอง ['+dataObj.screen_date+']</button>';
            }


            txt_row += '<tr class="r_uid_proj">';
            txt_row += ' <td>'+btn_pid+'</td>';
            txt_row += ' <td>'+dataObj.proj_name+'</td>';
            txt_row += ' <td>'+enroll_date+'</td>';
            txt_row += ' <td>'+changeToThaiDate(dataObj.last_visit_date)+'</td>';
            txt_row += ' <td>'+changeToThaiDate(dataObj.next_schedule_date)+'</td>';
            //txt_row += ' <td>'+dataObj.visit_date+'</td>';
            txt_row += '</tr">';

          }//for

        $('.r_uid_proj').remove(); // row uic proj summary
        $('#tbl_uid_proj > tbody:last-child').append(txt_row);

      }

      var txt_row = "";

      $('#cur_uid').val(rtnDataAjax.uid_data.uid);
      $('#cur_uic').val(rtnDataAjax.uid_data.uic);


      showUIDDiv("uid_info");
      showMainDiv("uid");
    }
    else{
      $('#txt_search_uic').notify("UIC นี้ไม่พบประวัติ","warn");
    }

     clearDataChangeProj();

  }
}


function searchData_pid(){
  if(validateInput("div_search_pid")){
    if($('#txt_search_pid').val().trim().length < 8){
      $('#txt_search_pid').notify("กรุณากรอกขั้นต่ำ 8 ตัวอักษร","warn");
    }
    else{//valid
      $('#txt_search_pid').removeClass("bg-warning")
      var aData = {
                u_mode:"select_data_pid",
                pid:$('#txt_search_pid').val(),
                proj_id: $('#sel_proj_search_pid').val()
      };
      save_data_ajax(aData,"w_user/db_uid_data.php",searchData_pidComplete);

    }

  }
}

function searchData_pidComplete(flagSave, rtnDataAjax, aData){
//  alert("flag save is : "+flagSave);
  if(flagSave){
    if(rtnDataAjax.uid != ""){
      $('#cur_uid').val(rtnDataAjax.uid);
      $('#cur_uic').val(rtnDataAjax.uic);
      $('#title_uid_id').html($('#cur_uid').val());
      $('#title_uic_id').html($('#cur_uic').val());

      goVisitList2(aData.proj_id, $('#sel_proj_search_pid').data("name"), aData.pid);
    }

  }
}



function goApproveClinicMove(ProjectID, ProjectName, pid){
  //alert("ขออนุมัติ "+ProjectID+" UID:"+uid);
  alert("ขออภัย!! ยังไม่เปิดใช้งานครับ (ขออนุมัติ "+ProjectID+" / PID:"+pid+")");
  /*
  $('#u_mode_visit').val("");
  $('#cur_proj_id').val(ProjectID);
  $('#cur_proj_name').val(ProjectName);
  $('#cur_pid').val(pid);

  $("#div_uid_visit").load("w_user/proj_visit.php?proj_id="+ProjectID, function(){
      showUIDDiv("uid_visit");
  });
  */
}



function clearData_uid(){
  $('#title_uid_id').html("");
  $('#info_uid').html("");
  $('#cur_uid').val("");
  $('#cur_pid').val("");
  $('#cur_proj_id').val("");
  $('#cur_group_id').val("");
  $('.r_uid_proj').remove(); // row uic proj summary
}

function showMainDiv(choice){
  $('.div-main').hide();
  $('#div_'+choice).show();
}

// div in div_uid_info
function showUIDDiv(choice){
  $('.div-uid').hide();
  $('#div_'+choice).show();
}





function setDataChangeVisit(){
  $('#data_update_visit').val("Y");
}
function clearDataChangeVisit(){
  $('#data_update_visit').val("");
}

function setDataChangeProj(){
  $('#data_update_proj').val("Y");
}
function clearDataChangeProj(){
  $('#data_update_proj').val("");
}


</script>
  </div>

  <div id = div_loading>
      <div class="v-align">
          <div id="loading_box">
            <h1><i class="fas fa-spinner fa-spin text-danger"></i> Loading </h1><br>
            <i class="fas fa-cat fa-lg text-info"></i> กรุณารอสักครู่

          </div>
      </div>
  </div>


  <input type="hidden" id="s_clinic_id">
  <input type="hidden" id="cur_uid" >
  <input type="hidden" id="cur_uic" >
  <input type="hidden" id="cur_proj_id" >
  <input type="hidden" id="cur_proj_name" >
  <input type="hidden" id="cur_group_id" >
  <input type="hidden" id="cur_pid" >
  <input type="hidden" id="cur_clinic_id" >

  <input type="hidden" id="u_mode_screen" >
  <input type="hidden" id="cur_screen_date" >

  <input type="hidden" id="u_mode_visit" >
  <input type="hidden" id="cur_visit_id" >
  <input type="hidden" id="cur_visit_date" >

  <input type="hidden" id="cur_form_id" >
  <input type="hidden" id="cur_proj_final_status_date" >


  <input type="hidden" id="data_update_proj" >
  <input type="hidden" id="data_update_visit" >


</div>

<div class="jumbotron text-center" style="margin-bottom:0">

<div class="row ">
   <div class="col-sm-11">

   </div>
   <div class="col-sm-1">
     <button id="btn_upto_top" class="form-control form-control-sm btn btn-info btn-sm" type="button" onclick="gotoTop();">
       <i class="fa fa-chevron-up fa-lg" ></i> Top
     </button>
   </div>
 </div>


  <p>
<center>weClinic ระบบจัดการข้อมูลคลินิก<br><small>พัฒนาโดย PopV99 (Build on 12 Sept 2019)</small></center>	</p>
</div>

  <!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="modalTitle" class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id= "modalContent" class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<script>
function myModalContent(mTitle, mContent, pic){
//  alert("title : "+mTitle+" Content : "+mContent);
  var is_pic = false;
  var content = "";

  if(pic == "info") mTitle = "<i class='fa fa-info-circle fa-lg'></i> "+mTitle;
  else if(pic == "success") mTitle = "<i class='fa fa-check-square fa-lg'></i> "+mTitle;
  else if(pic == "delete") mTitle = "<i class='fa fa-fa-times fa-lg'></i> "+mTitle;
  else{
    is_pic = true;
    pic = "<img src='img/"+pic+"'>";
  }

  if(is_pic)
  content = "<div class='row'><div class='col-md-4 col-centered' style='padding:5px'>"+pic+" </div><div class='col-md-8' style='padding:5px'>"+mContent+"</div></div>";

  else
  content = "<div style='padding:10px'>"+mContent+"</div>";


  $("#modalTitle").html(mTitle);
  //content = "<div class='row'><div class='col-md-4 col-md-offset-5' style='padding:5px'>abc</div><div class='col-md-8' style='padding:5px'>"+mContent+"</div></div>";
  $("#modalContent").html(content);

  $('#myModal').modal('show');
}

</script>
  <!-- Modal -->
<div class="modal fade" id="myModalChangePwd" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color:#EEEEEE;">
        <h4 id="modalChangePwdTitle" class="modal-title"><i class="fa fa-key  fa-lg"></i> Change Password</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id= "modalChangePwdBody" class="modal-body">

        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="user_old_password">Old Password</label>
            <input type="password"  class="form-control input-sm" id="user_old_password" data-title='User Old Password'  data-isrequire='1'>
          </div>
        </div>

        <div class="form-row py-4 my-4" style="background-color:#EEEEEE;">
          <div class="form-group col-md-6">
            <label for="user_new_password">New Password</label>
            <input type="password"  class="form-control input-sm" id="user_new_password" data-title='User New Password'  data-isrequire='1'>
          </div>
          <div class="form-group col-md-6">
            <label for="user_new_password">Confirm New Password</label>
            <input type="password"  class="form-control input-sm" id="user_new_password2" data-title='User New Password2'  data-isrequire='1'>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-12">
            <button class="btn btn-primary" id="btn_change_pwd"> <i class="fa fa-pencil-square-o fa-lg"></i> Change Password</button>

          </div>
        </div>


      </div>
      <div class="modal-footer">
        <span id="login_alert" style="color:red;"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<script>


$(document).ready(function(){
	$("#btn_change_pwd").click(function(){
			//alert("click change pwd : "+$('#user_old_password').val()+" / "+$('#user_new_password').val());
      if(validatePassword()){

        var aData = {
            u_mode:"change_pwd",
            staff_pwd_old:$('#user_old_password').val().trim(),
            staff_pwd_new:$('#user_new_password').val().trim()
        };

        save_data_ajax(aData,"system-access/db_user.php",changePasswordComplete);
      }
      else{
        alert("Error change password.");

      }
	});

});

function validatePassword(){
  var flag = true;
  if ($('#user_old_password').val() == "" || $('#user_new_password').val()== "" ||
     $('#user_new_password2').val() == ""){
     flag = false;
  }

  if($('#user_new_password').val().trim() != $('#user_new_password2').val().trim()) {
  //  msg_alert = "Error change password.";
    flag = false;
    $("#login_alert").html("Error change password.");
  }

  if(flag){
    if($('#user_new_password').val().trim().length < 4){
      $("#login_alert").html("Password length must be at least 4 charecters.");
      flag = false;
    }
  }
  return flag;
}

function changePasswordComplete(flagSave, rtnDataAjax, aData){
    //alert("changePasswordComplete flag save is : "+flagSave);
  if(flagSave){
       $('#myModalChangePwd').modal('hide');
  }
}



function myModalChangePwd(){

//  alert("title : "+mTitle+" Content : "+mContent);
    $('#user_old_password').val("");
    $('#user_new_password').val("");
    $('#user_new_password2').val("");

    $('#user_old_password').focus();
    $('#myModalChangePwd').modal('show');
}


</script>
  <!-- Modal -->
<div class="modal fade" id="myModalDlgLogin" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color:#EEEEEE;">
        <h4 id="modalLoginTitle" class="modal-title"><i class="fa fa-key  fa-lg"></i> System Access</h4>
      </div>
      <div id= "modalLogin" class="modal-body">
        <div class="my-2 mx-2 px-2 py-2 bg-warning">
            <b>คุณภาณุ ศรีวชิรโรจน์</b>
            กรุณาเข้าระบบอีกครั้งก่อนจะทำรายการต่อ เนื่องจาก <span class="text-danger">session expired</span> ครับ
        </div>

        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="login_user_name">รหัสประจำตัว</label>
            <input type="text"  class="form-control input-sm" id="login_user_name" data-title='User Name'  data-isrequire='1' value="P1901">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="login_user_pwd">รหัสผ่าน</label>
            <input type="password"  class="form-control input-sm" id="login_user_pwd" data-title='User Password'  data-isrequire='1' maxlength="25">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-12">
            <button class="btn btn-primary form-control" id="btn_login_access"> <i class="fa fa-key fa-lg"></i> เข้าระบบ</button>

          </div>
        </div>


      </div>
      <div id="modalLoginFooter" class="modal-footer text-danger" style="float:left;">

      </div>
    </div>

  </div>
</div>
<input type="hidden" id="login_mode" >

<script>


$(document).ready(function(){

	$("#btn_login_access").click(function(){
    systemAccess();
	});

  $("#login_user_pwd").on("keypress",function (event) {
    if (event.which == 13) {
      systemAccess();
    }
  });


});

function systemAccess(){
  if (validateLoginPassword()){

    var aData = {
        u_mode:"login_check",
        staff_id:$('#login_user_name').val().trim(),
        staff_pwd:$('#login_user_pwd').val().trim()
    };
   save_data_ajax(aData,"system-access/db_user.php",systemAccessComplete);
  }
  else{
    alert("Error system access.");
  }
}

function systemAccessComplete(flagSave, rtnDataAjax, aData){
    //alert("systemAccessComplete 555 flag save is : "+flagSave);
  if(flagSave){
    $.notify("เข้าระบบได้แล้ว", "success");
     $('#myModalDlgLogin').modal('hide');
  }
  else{
    $("#modalLoginFooter").html(rtnDataAjax.msg_error);
  }
}

function validateLoginPassword(){
  var flag = true;

  if ($('#login_user_name').val().trim() == "" || $('#login_user_pwd').val().trim() == ""){
     flag = false;
  }
  return flag;
}

function myModalDlgLogin(accessMode, // mode to access eg. te = login due to Time Expired, other system_access
                         msgContent) // message show in login dialog
                         {
  //alert("title : "+userName+" Content : "+msgContent);
   $('#login_mode').val(accessMode);
   $('#login_user_pwd').val("");


     $('#login_user_name').prop('disabled', true);
     $('#login_user_pwd').focus();


    $("#modalLoginFooter").html(msgContent);
    $('#myModalDlgLogin').modal('show');
}






</script>

</body>
</html>
<script>

$(document).ready(function(){
  loadingHide();
//loadingShow();


  $(".mnu-main").click(function(){
    var link = $(this).data("id")+".php";
    $("#div_main").load("w_user/"+link, function(){
        // alert("load "+link);
    });
  }); // .mnu-main

  $(".mnu-sub").click(function(){
    var id = $(this).data("id");
    var link = "mnu_"+id+".php";
    $("#div_"+id).load("w_user/"+link, function(){
        $(".mnu").removeClass("active");
        $("#mnu_"+id).addClass("active");
        showMainDiv(id);
    });
  }); // .mnu-main

  $(".mnu-external").click(function(){
    var path = $(this).data("path");
    var link = path+"/"+$(this).data("id")+".php";

    $("#div_"+path).load(link, function(){
        $(".mnu").removeClass("active");
        $("#mnu_"+$(this).data("path")).addClass("active");
        showMainDiv(path);
    });

  }); // .mnu-main


  $(".mnu-user").click(function(){
       myModalChangePwd();

  }); // .mnu-user


});

function loadingShow(){
  $("#div_loading").show();
  $("#div_main").hide();
}
function loadingHide(){
  $("#div_loading").hide();
  $("#div_main").show();
}

</script>


<script>

  function gotoLogin(){
		//alert("gotoLogin");
		window.location = "login.php";
	}
  function gotoTop(){
    //window.scrollTo(0, 0);
    $("body,html").animate(
     {
       scrollTop: 0
     },300 //speed
     );
	}


	function getDataObjValue(obj){
	  var sValue = "";

	  if($(obj).length){
	    var sTagName = $(obj).prop("tagName").toUpperCase();

	    if(sTagName=="INPUT"){
	      if($(obj).prop("type")){
	        if($(obj).prop("type").toLowerCase()=="checkbox"){
	          //sValue = ($(obj).is(":checked") )?"1":"0";
	          sValue = ($(obj).is(":checked") )?$(obj).val():"";

	        }else if($(obj).prop("type").toLowerCase()=="radio"){
	          var sName = $(obj).attr("name");
	          sValue = ( $(obj).parent().find("input[name='"+sName+"']").filter(":checked").length > 0 )? $(obj).parent().find("input[name='"+sName+"']").filter(":checked").val():$(obj).attr("data-odata");

	        }else{
	          sValue = $(obj).val();
	        }
	      }else{
	        sValue = $(obj).val();
	      }
	    }else if(sTagName=="SELECT"){
	      sValue=$(obj).find(":selected").val();
	      if($(obj).find(":selected").text()=="") sValue="";
	    }else{
	      sValue = $(obj).val();
	    }


			if($(obj).hasClass("v_date")){
	        var arrDate = sValue.split("/");
					if(arrDate.length == 3){
	          //sValue = arrDate[0]+"/"+ arrDate[1]+"/"+ (parseInt(arrDate[2]) - 543);
	          sValue = (parseInt(arrDate[2]) - 543)+"-"+arrDate[1]+"-"+ arrDate[0] ;
	          //alert("date : "+sValue);
					}
			}


	/*
	    if($(obj).hasClass("datedata") || $(obj).hasClass("showdate")|| $(obj).hasClass("datagroupdate")){
	      sValue=getDateData(sValue);
	      if($(obj).attr("data-odata").length > 10){
	        //sValue += " 00:00:00";
	      }else{

	      }
	    }
	*/


	  }
	  //alert($(obj).attr("name") + " : " + sValue);
	  return sValue;
	}


	function changeToThaiDate(sValue){ // eg. 2019-09-10 -> 10/09/2562
		var arrDate = sValue.split("-");
		if(arrDate.length == 3){
      if(sValue != "0000-00-00") {
        sValue = arrDate[2]+"/"+ arrDate[1]+'/'+(parseInt(arrDate[0]) + 543);
      }
      else{
        sValue = "";
      }
			//alert("date : "+sValue);
		}
		return sValue;
	}

	function changeToEnDate(sValue){ // eg. 10/09/2562 -> 2019-09-10
		var arrDate = sValue.split("/");
		if(arrDate.length == 3){
			//sValue = arrDate[0]+"/"+ arrDate[1]+"/"+ (parseInt(arrDate[2]) - 543);
			sValue = (parseInt(arrDate[2]) - 543)+"-"+arrDate[1]+"-"+ arrDate[0] ;
			//alert("date : "+sValue);
		}
    else{
      sValue ="0000-00-00";
    }
		return sValue;
	}

  function getTodayDateTH(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!

    var yyyy = today.getFullYear()+543;
    if (dd < 10) {
      dd = '0' + dd;
    }
    if (mm < 10) {
      mm = '0' + mm;
    }
    return dd + '/' + mm + '/' + yyyy;
  }

  function getTodayDateEN(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    return  yyyy+"-"+mm+"-"+dd;
  }

    function getPartialDateTH(){
      var today = new Date();
      /*
      var dd = today.getDate();
      var mm = today.getMonth() + 1; //January is 0!
*/
      var yyyy = today.getFullYear()+543;

      return  'dd/mm/' + yyyy;
    }
/*
  function initPartialDate(dataObj){
    if($(dataObj).val() == ''){
      $(dataObj).val(getTodayDateTH());
    }
  }
  */
  function initPartialDate(dataObj){
    if($(dataObj).val() == ''){
      $(dataObj).val(getPartialDateTH());
    }
  }

  // check thai date
  function checkPartialDate(dataObj)
  {
      var selectedDate = $(dataObj).val();
      if(selectedDate == '')
          return false;

      var regExp = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
      var dateArray = selectedDate.match(regExp); // is format OK?

      if (dateArray == null){
          return false;
      }

      day = dateArray[1];
      month= dateArray[3];
      year = dateArray[5];

    //  alert("dd/mm/yyyy : "+day+"/"+month+"/"+year);

      if (month > 12){
          $(dataObj).notify("เดือนไม่ถูกต้อง");
          return false;
      }else if (day> 31){
          $(dataObj).notify("วันที่ไม่ถูกต้อง");
          return false;
      }else if ((month==4 || month==6 || month==9 || month==11) && day ==31){
          $(dataObj).notify("วันที่ไม่ถูกต้อง");
          return false;
      }else if (month == 2){

        var enYear = parseInt(year-543);
        var isLeapYear = (enYear % 4 == 0 && (enYear % 100 != 0 || enYear % 400 == 0));
        if (day> 29 || (day ==29 && !isLeapYear)){
            $(dataObj).notify("วันที่ไม่ถูกต้อง");
            return false
        }
      }

      if(year < 2490){ // validate thai year
        $.notify("กรุณาใส่ปี พ.ศ.","error");
        return false;
      }



      return true;
  }




</script>
<script>

function validateInput(divSaveData){
  //alert("validateInput: "+divSaveData);
  var isValid = true;
  var sMessage = "";
  if(divSaveData != undefined){
    divSaveData = "#"+divSaveData;
  }

  $(divSaveData +" .save-data").removeClass("bg-warning");

  $(divSaveData +" .v-no-blank").each(function(ix,objx){
    if(!validateBlank($(objx).val())){
      sMessage += "กรุณากรอกข้อมูล <b>"+($(objx).data("title") + "</b><br>");
      $(objx).addClass("bg-warning");
      $(objx).notify("กรุณากรอกข้อมูล "+$(objx).data("title"),"error");
      $.notify("กรุณากรอกข้อมูล "+$(objx).data("title"),"error");
    }
  });


  $(divSaveData +" .v-email").each(function(ix,objx){
    if(!validateEmail($(objx).val())){
      sMessage += "<b>"+($(objx).data("title") + "</b> ไม่ถูกต้อง<br>");
      $(objx).addClass("bg-warning");
      $(objx).notify( $(objx).data("title") +" ไม่ถูกต้อง","error");
      $.notify( $(objx).data("title") +" ไม่ถูกต้อง","error");
    }

  });

  $(divSaveData +" .v_date").each(function(ix,objx){
    //if(validateBlank($(objx).val()){
      if(!validateDate($(objx).val())){
        sMessage += "ข้อมูลวันที่ <b>"+($(objx).data("title") + " ไม่ถูกต้อง</b><br>");
        $(objx).addClass("bg-warning");
        $(objx).notify( $(objx).data("title") +" ไม่ถูกต้อง","error");
        $.notify("ข้อมูลวันที่ "+$(objx).data("title") +" ไม่ถูกต้อง","error");
      }
    //}

  });



  if(sMessage != ""){
    isValid = false;
  //  alert(""+sMessage);
  }
  return isValid;
}

function validateEmail2(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function validateEmail(email) {
  if(email.trim() != ""){
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }
  else{
    return false;
  }

}



function validatePhoneNo(phone_no) {
  if(phone_no.trim() != ""){
    var re = /^[0-9-]*$/;
    return re.test(phone_no);
  }
  else{
    return false;
  }

}

function validateBlank(txt) {
  if(txt.trim() == '') return false;
  else return true;
}


function validateDate(dateValue)
{
    var selectedDate = dateValue;
    if(selectedDate == '')
        return false;

    var regExp = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
    var dateArray = selectedDate.match(regExp); // is format OK?

    if (dateArray == null){
        return false;
    }

    day = dateArray[1];
    month= dateArray[3];
    year = dateArray[5];


  //  alert("dd/mm/yyyy : "+day+"/"+month+"/"+year);

    if (month < 1 || month > 12){
        return false;
    }else if (day < 1 || day> 31){
        return false;
    }else if ((month==4 || month==6 || month==9 || month==11) && day ==31){
        return false;
    }else if (month == 2){
        var enYear = parseInt(year-543);
        var isLeapYear = (enYear % 4 == 0 && (enYear % 100 != 0 || enYear % 400 == 0));
        if (day> 29 || (day ==29 && !isLeapYear)){
            return false
        }
    }

    if(year < 2490){ // validate thai year
      $.notify("กรุณาใส่ปี พ.ศ.","error");
      return false;
    }


    return true;
}


function validatePartialDate(dateValue)
{
    var selectedDate = dateValue;
    if(selectedDate == '')
        return false;

    var regExp = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
    var dateArray = selectedDate.match(regExp); // is format OK?

    if (dateArray == null){
        return false;
    }

    day = dateArray[1];
    month= dateArray[3];
    year = dateArray[5];

  //  alert("dd/mm/yyyy : "+day+"/"+month+"/"+year);

    if (month > 12){
        return false;
    }else if (day> 31){
        return false;
    }else if ((month==4 || month==6 || month==9 || month==11) && day ==31){
        return false;
    }else if (month == 2 && day >29){
      return false;
    }

    if(year < 2490){ // validate thai year
      $.notify("กรุณาใส่ปี พ.ศ.","error");
      return false;
    }


    return true;
}


</script>
<script>

function save_data_ajax(objData, pageURL, returnFunc){
  //alert("save_data_ajax: "+returnFunc);
  //alert("pageURL: "+pageURL);

  extendSession();
  loadingShow();

  var flag_save_success = true;
  var request = $.ajax({
      url:pageURL,
      type:'POST',
      data:objData, // should be consisted of u_mode and any data to update

      success: function(result) {
      //alert("successx: "+result);

      loadingHide();

       var rtnObj = jQuery.parseJSON( result );
       if(typeof rtnObj.flag_auth !== "undefined"){

         //alert("flag_auth : "+rtnObj.flag_auth);
         if(rtnObj.flag_auth == '0'){
           myModalDlgLogin("te", "Time Expired");
           /*
           alert("session expired / Please Login to system");
           gotoLogin();
           */

         }
       }

        //alert ("rtnObj : "+rtnObj.msg_error+" / "+rtnObj.msg_info);

       if(rtnObj.msg_error == ""){
         flag_save_success = true;

         if(rtnObj.msg_info != ""){
           /*
           myModalContent("Information",
           rtnObj.msg_info,
           "info");
           */
           $.notify(rtnObj.msg_info, "info");

         }
       } //msg_err == ""
       else {
         flag_save_success = false;
         if(rtnObj.msg_error == "session_expired"){
           //sessionExpired();
           $.notify(rtnObj.msg_error, "error");
         }
         else{
           /*
           myModalContent("Error",
           rtnObj.msg_error,
           "info");
           */

           $.notify(rtnObj.msg_error, "error");
         }


       }

        returnFunc(flag_save_success, rtnObj, objData );

     }, // end success
      error: function(xhr){
        loadingHide();
        /*
        myModalContent("Error",
        "xhr.status : "+xhr.status,
        "info");
        */
        alert("error: "+xhr.status);
        returnFunc(flag_save_success, xhr.status, objData );
      }
  });

 }


function validateEmpty(divSaveData){

  var isValid = true;

  var sMessage = "";
  if(divSaveData != undefined){
    divSaveData = "#"+divSaveData;
  }

  $(divSaveData +" .save-data").each(function(ix,objx){
    if($(objx).data("isrequire") && $(objx).val().trim() == ""){
      sMessage += ($(objx).data("title") + " can not be empty.<br>");
      $(objx).addClass("bg-warning");
    }
  });


  if(sMessage != ""){
    isValid = false;
    $.notify(sMessage, "info");
  }


  return isValid;
}

function validateEmptyListData(divSaveData){
  //alert("validateEmptyListData  : ");
  var isValid = true;
//alert("validateEmptyListData  : "+divSaveData+" .save-data-list");
  var sMessage = "";
  if(divSaveData != undefined){
    divSaveData = "#"+divSaveData;
  }

  $(divSaveData +" .save-data-list").each(function(ix,objx){
    //alert("validate : "+$(objx).data("title"));
    if($(objx).data("isrequire") && $(objx).val().trim() == ""){
      sMessage += "ID ["+$(objx).data("rowid") +"] "+$(objx).data("title") + " can not be empty.<br>";
      $(objx).addClass("bg-warning");
    }
  });

  if(sMessage != ""){
    isValid = false;
    $.notify(sMessage, "info");
  }
  return isValid;
}


function checkDataChange(divSaveData){
  var isChanged = false;
  if(divSaveData != undefined){
    divSaveData = "#"+divSaveData;
  }
  var txt = "";
  var txt2 = "";
  $(divSaveData +" .save-data").each(function(ix,objx){
   if($(objx).data("odata") != undefined){
     var objValue = getDBMSObjValue($(objx));
     //if($(objx).data("odata").trim() != $(objx).val().trim()){
     if($(objx).data("odata") != objValue){
       txt += "[obj:"+$(objx).attr("id")+"/"+$(objx).val().trim()+"]";
       //alert("data changed : "+$(objx).data("odata")+"/"+$(objx).val().trim());
       isChanged = true;
     }
     else{
       txt2 += "[obj2:"+$(objx).attr("id")+"/"+$(objx).val().trim()+"]";
     }
   }

  });
  alert("check change : "+txt);
  alert("check change2 : "+txt2);
  //alert("flag data change : "+isChanged);
  return isChanged;
}

function getDataListChange(tblID){
  var arrListID = [];
  $("#"+tblID+" .save-data-list").each(function(ix,objx){
//alert("data list : "+$(objx).data("odata")+"/"+$(objx).val());
   if($(objx).data("odata") != undefined){

     // check if it is chk box
     if ( $(objx).is( ".chk_box" )) {
        //alert("chkbox1 : ");
        sValue = ($(objx).is(":checked"))?1:0;
        $(objx).val(sValue);
        //alert("chkbox : "+$(objx).val());
     }

     //if($(objx).data("odata").trim() != $(objx).val().trim()){
     if($(objx).data("odata") != $(objx).val().trim()){
       //alert("data list changed : "+$(objx).data("odata")+"/"+$(objx).val().trim());
       arrListID.push($(objx).data("rowid"));

     }
   }

  });

  var uniqueID = [];
  $.each(arrListID, function(i, el){
      if($.inArray(el, uniqueID) === -1) uniqueID.push(el);
  });
//alert("arrListID/uniqueID : "+arrListID+" / "+uniqueID);
  return uniqueID;
}

function urlEncode(urlText){
  if (typeof urlText === "undefined") return "";
  else if(urlText=="0") return "0";
  else if(urlText==false) return "";
  else if(urlText=="") return "";
  urlText =	encodeURIComponent(urlText);
  urlText = urlText.toString().replace(/!/g, '%21');
    urlText = urlText.toString().replace(/'/g, '%27');
    urlText = urlText.toString().replace(/\(/g, '%28');
    urlText = urlText.toString().replace(/\)/g, '%29');
    urlText = urlText.toString().replace(/\*/g, '%2A');
    //urlText = urlText.replace(/%20/g, '+')
  return (urlText);
}
function urlDecode(urlText){
  if (typeof urlText === "undefined") return "";
  else if(urlText=="0") return "0";
  else if(urlText==false) return "";
  else if(urlText=="") return "";
  return decodeURIComponent(urlText.toString().replace(/\+/g, "%20"));
}



function getDBMSObjValue(obj){
  var sValue = "";
  if($(obj)){
    var sTagName = $(obj).prop("tagName").toUpperCase();

    if(sTagName=="INPUT"){
      if($(obj).prop("type")){
        if($(obj).prop("type").toLowerCase()=="checkbox"){
          sValue = ($(obj).is(":checked"))?1:0;
        }else if($(obj).prop("type").toLowerCase()=="radio"){
          var sName = $(obj).attr("name");

          sValue = ( $(obj).parent().find("input[name='"+sName+"']").filter(":checked").length > 0 )? $(obj).parent().find("input[name='"+sName+"']").filter(":checked").val():$(obj).data("odata");

        }else{
          sValue = $(obj).val();
        }
      }else{
        sValue = $(obj).val();
      }
    }else{
      sValue = $(obj).val();
    }
    if($(obj).hasClass("datedata") || $(obj).hasClass("showdate")){
      sValue=getDateData(sValue);
      if($(obj).data("odata").length > 10){
        //sValue += " 00:00:00";
      }else{

      }
    }

  }
  return sValue;
}


function setDBMSObjValue(obj,value){
  if($(obj).length){
    //alert($(obj).attr("data-name") );
    var sTagName = $(obj).prop("tagName").toUpperCase();

    if(sTagName=="INPUT" && ($(obj).prop("type"))){
        if($(obj).prop("type").toLowerCase()=="checkbox"){
          if(value=="1")	$(obj).attr("checked","checked");
          else $(obj).removeAttr("checked");
        }else if($(obj).prop("type").toLowerCase()=="radio"){
          //alert($(obj).prop("id") + " : " + value);
          $(obj).filter("[value='" + value + "']").prop('checked', true);
        }else{
          $(obj).val(value);
        }

    }else{
      $(obj).val(value);
    }
    /*
    if($(obj).hasClass("datedata") || $(obj).hasClass("showdate")){
      $(obj).val(getLongDateData(value));
    }
    */

    //$(obj).data("odata",urlEncode(value));
    $(obj).data("odata",value);
  }
}

function setOData(divSaveData){
  $("#"+divSaveData + " .save-data").each(function(i,objx){
    setDBMSObjValue(objx, getDBMSObjValue(objx)  );
  });
}

function setODataList(divSaveData){
  $("#"+divSaveData + " .save-data-list").each(function(i,objx){
    setDBMSObjValue(objx, getDBMSObjValue(objx)  );
  });
}




// set value to form main
function setFormObject(dataObj, dataID, dataValue){
  //dataObj["'"+data_ID+"'"] = {dval:"'"+dataValue+"'",odata:"'"+dataValue+"'"};
  if(dataValue == '0') dataValue = '';
  dataObj[dataID] = {dval:dataValue,odata:dataValue};
}

// set form old data
function setFormOData(dataObj){
  //dataObj["'"+data_ID+"'"] = {dval:"'"+dataValue+"'",odata:"'"+dataValue+"'"};
  //var txt = "";
  for (var key in dataObj) {
      dataObj[key]['odata']=dataObj[key]['dval'];
      //txt += key+"/"+dataObj[key]['dval']+", ";
  }
//  alert("txt : "+txt);
}

// check data changed in form
function checkDataChangeFormObject(dataObj, dataID, dataValue){
  if(typeof dataObj[dataID] !== 'undefined'){
    dataObj[dataID]['dval'] =  dataValue;
    if(dataValue == dataObj[dataID]['odata']){ // data not changed
      return false;
    }
  }
  else{ // no record in dataObj, new record
    dataObj[dataID] = {dval:dataValue,odata:''};
  }

  //alert("datachange :"+dataID+"/"+dataValue+"="+dataObj[dataID]['odata']);
  return true; // data changed
}




</script>
