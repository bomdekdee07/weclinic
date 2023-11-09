<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}
//echo "auth view : ".$auth["enroll"];
//echo "clinic id : $staff_clinic_id";
?>
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


    </div>

    <div class="col-sm-9">

      <?  include_once("uid_schedule_list.php"); ?>

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

                <?
                     if(isset($auth["enroll"])){ // screen & enroll
                       echo '
                       <div class="my-1" id="div_sel_proj_reg">
                         <button id="btn_proj_screening" class="form-control btn btn-info btn-lg" type="button">
                           <h5> <i class="fa fa-first-aid fa-lg" ></i> คัดกรองเข้าโครงการใหม่ </h5>
                         </button>
                       </div>
                       ';
                     }

                ?>

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

<div id="div_xpress_service" class='div-main'>
sss
</div>

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
  $('#s_clinic_id').val('<? echo $staff_clinic_id; ?>');
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
    showMainDiv("dashboard");
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

  $("#div_uid_visit").load("w_user/proj_visit.php?proj_id="+ProjectID, function(){
      showMainDiv("uid");
      showUIDDiv("uid_visit");
      setDataChangeProj();
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

      var staff_clinic_id="<? echo $staff_clinic_id;  ?>";
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
            if(dataObj.uid_status == 1){ // already enroll
              enroll_date = changeToThaiDate(dataObj.enroll_date);
              /*
              enroll_date = changeToThaiDate(dataObj.enroll_date);
              btn_pid = '<button class="btn btn-info" type="button" onclick="goVisitList(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\',\''+dataObj.pid+'\')""><i class="fa fa-user"></i> '+dataObj.pid+'</button> <span class="badge badge-success">'+dataObj.clinic_name+'</span>';
*/

           if(staff_clinic_id == "%"){
             btn_pid = '<button class="btn btn-info" type="button" onclick="goVisitList(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\',\''+dataObj.pid+'\')""><i class="fa fa-user"></i> '+dataObj.pid+'</button> <span class="badge badge-success">'+dataObj.clinic_name+'</span>';
           }
           else{
             if(staff_clinic_id == dataObj.clinic_id){
               btn_pid = '<button class="btn btn-info" type="button" onclick="goVisitList(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\',\''+dataObj.pid+'\')""><i class="fa fa-user"></i> '+dataObj.pid+'</button> <span class="badge badge-success">'+dataObj.clinic_name+'</span>';
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
