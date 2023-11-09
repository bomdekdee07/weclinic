<input type="hidden" id="cur_s_id" >
<input type="hidden" id="cur_sc_id" >
<input type="hidden" id="cur_s_name" >
<input type="hidden" id="cur_clinic_id" >
<input type="hidden" id="cur_clinic_name" >
<input type="hidden" id="cur_job_name" >


<input type="hidden" id="u_mode_staff" >

<div id='div_staff_list' class='div-main-staff'>
  <div class="row mb-4">
    <div class="col-sm-2">
      <div class="my-2">
        <button class="btn btn-success form-control" type="button" id="btn_new_staff" ><i class="fa fa-folder-plus fa-lg" ></i> Add Staff</button>
      </div>
    </div>

    <div class="col-sm-4">

      หน่วยงาน
      <select id="sel_clinic_id" class="form-control form-control-sm" >
        <option value="" selected class="text-dark">ทั้งหมด</option>
         <?
            echo (isset($opt_clinic)?$opt_clinic:"");
          ?>
      </select>

    </div>
    <div class="col-sm-6">
      คำค้นหา
      <div class="input-group">
        <input id="txt_search_staff" type="text" class="form-control form-control-sm input-sm" placeholder="Search for... Staff ID, Name">
        <span class="input-group-btn">
          <button id="btn_search_staff" class="btn btn-primary btn-sm" type="button"><i class="fa fa-search fa-lg"></i> Search</button>
        </span>
      </div>

    </div>
  </div>
  <div>
    <table id="tbl_staff_list" class="table table-bordered table-sm table-striped table-hover">
        <thead>
          <tr>
            <th>Control</th>
            <th>Name <br>(s_id) sc_id</th>
            <th>Clinic</th>
            <th>Job</th>
            <th>Status</th>
            <th>Send Auth</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
  </div>
</div><!-- end div_staff_list-->

<div id="div_staff_detail"  class='card div-main-staff'>
  <div  class="card-header bg-primary text-white">

     <div class="row ">
       <div class="col-md-4">
         <h4><i class="fa fa-user fa-lg" ></i> <span id="staff_title"> </span></h4>
       </div>
       <div class="col-md-6">
         <div class="row">

            <div class="col-sm-6">
              <button class="form-control btn btn-light btn-staff-menu" data-id="personal_data" type="button">
                <i class="fa fa-user fa-lg" ></i> Personal Data
              </button>
            </div>
            <div class="col-sm-6">
              <button class="form-control btn btn-light btn-staff-menu" data-id="clinic_profile" type="button">
                <i class="fa fa-file fa-lg" ></i> Clinic Profile
              </button>
            </div>
         </div>
       </div>
       <div class="col-md-1">

       </div>
       <div class="col-md-1">
         <button id="btn_close_staff_detail" class="form-control btn btn-light" type="button">
           <i class="fa fa-times-circle fa-lg" ></i> ปิด
         </button>
       </div>
     </div>
  </div>
  <div class="card-body" id="div_staff_detail_data">
    <div id='div_staff_personal_data' class='div-detail-staff'>
        <? include_once("staff_mgt_data.php"); ?>
    </div>
    <div id='div_staff_clinic' class='div-detail-staff'>

    </div>
  </div>
</div> <!-- div_staff_detail -->

<div id="dlgAuth" title="" style='display:none'>
    <div id='dlgContent'>

    </div>
</div>




<script>
$(document).ready(function(){
  showStaffMainDiv("staff_list");

$("#btn_close_staff_detail").click(function(){
   showStaffMainDiv("staff_list");
}); // close staff detail
$(".btn-staff-menu").click(function(){
   var choice = $(this).data("id");
   if(choice == "personal_data")
     getStaffData($("#cur_s_id").val(), $("#cur_sc_id").val());
   else if(choice == "clinic_profile")
     getStaffClinic($("#cur_s_id").val(), $("#cur_sc_id").val());
}); // close staff detail

  $("#btn_search_staff").click(function(){
     searchData_staff();
  }); // btn_search_uid

  $("#txt_search_staff").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_staff();
    }
  });

  $("#btn_new_staff").click(function(){

  }); // btn_new_staff

  $("#tbl_staff_list").on("click",".btn-auth",function(){
      loadUserAuth($(this).attr("data-sid"));
  });



});

function loadUserAuth(sID){
  var sUrl = "w_admin/dlg_user_auth.php?sid="+sID;
  
  $.ajax( sUrl)
  .done(function( retdata ) {

    $("#dlgAuth").find("#dlgContent").html(retdata);
    $("#dlgAuth").dialog({
      modal:true,
      width:1170,
      height:480,
      title:"User Authorization",
      resizable: false,
      open: function(event, ui) {
         
      }
    });

   
  })
  .fail(function() {
    alert( "error loading" );
  })
  .always(function() {
    //alert( "complete " );
  });

}

function searchData_staff(){

  var aData = {
            u_mode:"select_list",
            txt_search:$('#txt_search_staff').val().trim()
  };
  alert("1");
  save_data_ajax(aData,"w_admin/db_staff_mgt.php",searchData_staffComplete);
  alert("2");
}


function searchData_staffComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

      txt_row="";
      if(rtnDataAjax.data_list.length > 0){

        var btn_staff = "";
        var datalist = rtnDataAjax.data_list;
          for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            btn_staff = "staff id";
            btn_mail = "staff auth login";
            if(dataObj.sc_status == 1){ // already enroll

              btn_staff = '<button class="btn btn-sm btn-info" type="button" onclick="getStaffData(\''+dataObj.s_id+'\', \''+dataObj.sc_id+'\')""><i class="fa fa-user"></i> Data</button>';
              btn_staff += '<button class="btn btn-sm btn-auth btn-warning" data-sid="'+dataObj.s_id+'"  type="button" ><i class="fa fa-street-view"></i> Auth</button>';

              btn_mail = '<button class="btn btn-sm btn-primary" type="button" onclick="sendStaffLogin(\''+dataObj.s_id+'\', \''+dataObj.sc_id+'\')""><i class="fa fa-envelope-square"></i><small> '+dataObj.email+' </small></button>';
            }

            txt_row += '<tr class="r_staff '+dataObj.sc_id+'" data-name='+dataObj.name+'>';
            txt_row += ' <td>'+btn_staff+'</td>';
            txt_row += ' <td>'+dataObj.name+'<br>['+dataObj.s_id+'] <b>'+dataObj.sc_id+'</b></td>';

            txt_row += ' <td>'+dataObj.job_name+'</td>';
            txt_row += ' <td>'+dataObj.clinic_name+'</td>';
            txt_row += ' <td>'+dataObj.sc_status+'</td>';
            //txt_row += ' <td>'+dataObj.last_access+'</td>';

            txt_row += ' <td>'+btn_mail+'</td>';

            txt_row += '</tr">';
          }//for


      }
      $('.r_staff').remove(); // row  staff list
      $('#tbl_staff_list > tbody:last-child').append(txt_row);
      showStaffMainDiv("staff_list");
  }
}


function getStaffAuth(staffID, staffName){
  $('#cur_s_id').val(staffID);
  $('#cur_s_name').val(staffName);

  var aData = {
            u_mode:"select_data_staff",
            staff_id:staffID
  };
  save_data_ajax(aData,"w_admin/db_staff_mgt.php",getStaffAuthComplete);

}


function getStaffAuthComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

      showStaffMainDiv("staff_auth");
  }
}


function sendStaffLogin(sID, scID){


  var aData = {
            u_mode:"send_email_auth",
            s_id:sID,
            sc_id:scID
  };
  save_data_ajax(aData,"w_admin/db_staff_mgt.php",sendStaffLoginComplete);

}


function sendStaffLoginComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

     alert("send mail to "+aData.sc_id+" successfully.");
  }
}


function updateStaffTitle(sID, sName){
  $('#staff_title').html(sID+": "+sName);
}

function clearData_uid(){
  $('#title_uid_id').html("");
  $('#info_uid').html("");
  $('#cur_uid').val("");
  $('#cur_proj_id').val("");
  $('#cur_group_id').val("");
  $('.r_staff').remove(); // row uic proj summary
}

// div in div-main-staff
function showStaffMainDiv(choice){
  //alert("showStaffMainDiv "+choice);
  $('.div-main-staff').hide();
  $('#div_'+choice).show();
}

// div in div-detail-staff
function showStaffDetailDiv(choice){
  //alert("showStaffDetailDiv "+choice);
  $('.div-detail-staff').hide();
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
