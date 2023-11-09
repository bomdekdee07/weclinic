<?
$proj_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";

include_once("../in_auth.php");
include_once("proj_visit_conf/$proj_id/inc_proj.php");

?>
  <div class="row alert alert-primary" role="alert">
     <div class="col-sm-10">
         <h3><i class="fa fa-calendar-alt fa-lg" ></i> <b><span id="visit_proj_name">Proj_Name</span></b>  นัดหมาย  <span id="visit_proj_date">[Visit_Date]</span> <span id="visit_proj_pid">PID</span> <span id="visit_name">Visit_Name</span></h3>
     </div>
     <div class="col-sm-2">
       <button id="btn_close_proj_visit" class="form-control btn btn-warning btn-lg" type="button">
         <h5> <i class="fa fa-chevron-circle-left fa-lg" ></i> ย้อนกลับ </h5>
       </button>
     </div>
  </div>

<div  class="div-uid-visit" id="div_uid_visit_list">
  <div class="card " id="div_uid_visit_list_schedule">
    <div class="card-body">
      <h5 class="card-title"><i class="fa fa-calendar-alt fa-lg" ></i> ตารางนัดหมาย <span class="text-primary" id="last_date_visit"></span></h5>
      <div id="div_uid_visit_list_detail">
        <table id="tbl_visit_list" class="table table-bordered table-sm table-striped table-hover">
            <thead>
              <tr>
                <th>นัดหมาย</th>
                <th>กลุ่ม/หมายเหตุ</th>
                <th>วันนัดหมาย [Window Period]</th>
                <th>วันเข้าตรวจ</th>
                <th>สถานะ</th>
                <th>นัดเพิ่มเติม</th>
                <th width="250px">Schedule Note <small>(คลิกเพื่อแก้ไข)</small></th>
              </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      <div class='my-2' id="div_final_status_visit">

      </div>
    </div>


  </div>


</div>





<div class="card div-uid-visit" id="div_project_visit_info">
  <div class="card-body">
    <div class="row">
       <div class="col-sm-11">
         <h5><i class="fa fa-calendar-alt fa-lg" ></i> ข้อมูลนัดหมาย <span id="v_visit_name2"></span></h5>
       </div>
       <div class="col-sm-1">
         <button id="btn_close_project_visit_info" class="form-control form-control-sm btn btn-danger btn-sm" type="button">
           <i class="fa fa-times fa-lg" ></i> ปิด
         </button>
       </div>
     </div>

    <div class="row">
       <div class="col-sm-4">

         <div class="card my-1 div_auth" id="div_project_visit_info_detail">
           <div class="card-header"><i class="fa fa-calendar-day fa-lg" ></i> สรุปนัดหมาย </div>
           <div class="card-body">
              <div class="mt-1">
                 กลุ่ม/หมายเหตุ: <br><span id="v_visit_group"></span> <br><br>
                 PID: <span id="v_visit_pid"></span> <br>
                 <i class="fa fa-calendar-day text-info" ></i> วันนัดหมาย: <span id="v_schedule_date"></span> <br>
                 <i class="fa fa-calendar-day text-primary" ></i> วันเข้าพบ: <span id="v_visit_date"></span> <br>
                 <i class="fa fa-clipboard-check" ></i> สถานะ: <span id="v_visit_status"></span> <br>
             </div>


             <div class="my-4" id="div_btn_form_log">
               <div class="mt-1">
                 <button id="btn_visit_ae" class="form-control form-control-sm btn btn-info btn-sm" type="button" onclick="openFormLog2('ae');">
                  <i class="fa fa-file-medical-alt " ></i> Adverse Event
                 </button>
               </div>
               <div class="mt-1">
                 <button id="btn_visit_con_med" class="form-control form-control-sm btn btn-info btn-sm" type="button" onclick="openFormLog2('con_med');">
                  <i class="fa fa-file-medical-alt " ></i> Con Med
                 </button>
               </div>

<!--
               <div class="mt-1">
                 <button id="btn_visit_sex_partner" class="form-control form-control-sm btn btn-info btn-sm" type="button" onclick="openFormLog2('sex_partner');">
                  <i class="fa fa-bed " ></i> Sex Partner
                 </button>
               </div>
-->
             </div>

             <div class="my-2">
               <span id="v_groupchange_note" class="text-danger"></span>
             </div>

             <div class="mt-1">
               <b><i class='fa fa-info-circle fa-lg text-warning' ></i> Visit Note:</b> <br>
               <textarea class="form-control" id="v_visit_note" rows="5"  data-title='Visit Note'></textarea>

             </div>

         </div>


       </div>

       <div id='div_project_visit_menu'>


      </div>


     </div>

       <div class="col-sm-8" >
          <table>
            <tr>
              <td><h4><i class="fa fa-file-medical fa-lg text-info" ></i> แบบฟอร์ม </h4></td>
              <td>

                <button id="btn_form_list_reload" class="btn btn-sm btn-primary ml-4" type="button">
                      <i class="fa fa-sync-alt" ></i>
                </button>
              </td>
              <td>
                <div id="div_consent_btn">
                </div>

              </td>
            </tr>
          </table>

             <div class="my-1" id="div_visit_form">
               <table id="tbl_visit_form_list" class="table table-bordered table-sm table-striped table-hover">
                   <thead>
                     <tr>
                       <th></th>
                       <th>แบบฟอร์ม </th>
                       <th>ทำแล้ว?</th>
                     </tr>
                   </thead>
                   <tbody>

                   </tbody>
               </table>

             </div>

<!--
             <div class="my-2 px-1 py-1" id="div_visit_xpress" style="border-style: solid;border-width: 1px;border-color:#ccc;">
               <div id="visit_xpress_list">
                 <div class="my-1">
                   <span class="text-info" style="font-size:20px;"><i class="fa fa-paper-plane" ></i> <b>X</b></span>Press Service
                   <button id="btn_form_visit_xpress_list_reload" class="btn btn-sm btn-primary ml-4" type="button">
                         <i class="fa fa-sync-alt" ></i>
                   </button>
                 </div>
                 <div>
                   <table id="tbl_visit_xpress" class="table table-bordered table-sm table-striped table-hover">
                       <thead>
                         <tr>
                           <th><i class="fa fa-user-edit"></i> คนไข้กรอกข้อมูล</th>
                           <th><i class="fa fa-user-tie"></i> เจ้าหน้าที่ตรวจสอบ</th>
                           <th><i class="fa fa-smile-wink"></i> ความพึงพอใจ</th>
                           <th><i class="fa fa-paper-plane"></i> ส่งผลตรวจ</th>
                         </tr>
                       </thead>
                       <tbody>

                       </tbody>
                   </table>
                 </div>
               <div class="my-1" id="div_open_visit_xpress">

               </div>


               </div> <!-- visit_xpress_list -->
             </div><!-- div_visit_xpress -->



       </div>
    </div>

  </div>
</div> <!-- div_project_visit_info -->

<div class="card div-uid-visit" id="div_project_visit_form">
  <div  class="card-header bg-primary text-white" id="div_project_visit_form_title">
     <div class="row ">
       <div class="col-md-11">
         <h4><i class="fa fa-file-medical fa-lg" ></i> <span id="form_title"> </span></h4>
       </div>
       <div class="col-md-1">
         <button id="btn_close_form" class="form-control btn btn-light" type="button">
           <i class="fa fa-times-circle fa-lg" ></i> ปิด
         </button>
       </div>
     </div>
  </div>
  <div class="card-body" >

    <div id="div_project_visit_form_data">
      รอสักครู่
    </div>

  </div>
</div> <!-- div_project_visit_form -->


<div class="card div-uid-visit" id="div_project_visit_xpress_service">
  <div  class="card-header bg-primary text-white">
     <div class="row ">
       <div class="col-md-10">
         <table>
           <tr>
             <td class="px-1"><h4><i class="fa fa-paper-plane" ></i> <b>X</b>Press Service</h4></td>
             <td class="px-1">
               <button id="btn_visit_xpress_service_reload" class="form-control btn btn-light text-primary" type="button">
                 <i class="fa fa-sync-alt fa-lg" ></i>
               </button>
             </td>
           </tr>
         </table>

       </div>
       <div class="col-md-2">
         <button id="btn_close_visit_xpress_service" class="form-control btn btn-light" type="button">
           <i class="fa fa-times-circle fa-lg" ></i> ปิด XPress
         </button>
       </div>
     </div>
  </div>
  <div class="card-body" id="div_project_visit_xpress_service_data">
      รอสักครู่
  </div>
</div> <!-- div_project_visit_xpress_service -->


<div class="card div-uid-visit" id="div_project_visit_form_log">

</div> <!-- div_project_visit_form_log -->

<div class="card div-uid-visit" id="div_project_visit_update_schedule">

</div> <!-- div_project_visit_update_schedule -->


<? include_once("dlg_proj_consent.php");?>


<input type="hidden" id="cur_visit_name">
<input type="hidden" id="cur_visit_status_id">
<input type="hidden" id="data_update_visit_detail">
<input type="hidden" id="is_form_complete" value=''>
<input type="hidden" id="cur_uid_param">
<input type="hidden" id="is_lastest_visit">

<input type="hidden" id="visit_log_enable">



<script>
$(document).ready(function(){

  $(".div-uid-visit").hide();

  if($("#u_mode_visit").val() == "schedule_log"){
    $("#btn_close_proj_visit").hide();
  }


  initVisitData();


  $("#btn_close_proj_visit").click(function(){
     showUIDDiv("uid_info");
     //searchData_uid();

     // if there is project updated , reload list
    // alert("back to main "+$("#data_update_proj").val())
     if($("#data_update_proj").val() == "Y") searchData_uid2();

  }); // btn_close_proj_screen

  $("#btn_close_form").click(function(){
    //alert("close : "+ $('#cur_form_id').val())
      if($('#cur_form_id').val() == "final_status"){
        showUIDDivVisit("uid_visit_list");

      }
      else{
        showUIDDivVisit("project_visit_info");
        // if there is project updated , reload list
        if($("#data_update_visit").val() == "Y") selectProjVisitForm();
      }


  }); // btn_close_form

  $("#btn_close_project_visit_info").click(function(){
     showUIDDivVisit("uid_visit_list");

     // if there is project updated , reload list
     if($("#data_update_visit_detail").val() == "Y")
     selectVisitList();

  }); // btn_close_proj_screen

  $("#btn_close_visit_xpress_service").click(function(){
     showUIDDivVisit("project_visit_info");
  }); // btn_close_visit_xpress_service
  $("#btn_visit_xpress_service_reload").click(function(){
     selectVisitXpressList();
  }); // btn_visit_xpress_service_reload



  $("#btn_screening_pass").click(function(){
     if($('#is_form_complete').val() == "Y") enrollToProject();
     else $.notify("แบบฟอร์มกรอกไม่ครบ");
  }); // enrollToProject

  $("#btn_screening_fail").click(function(){
    if($('#visit_note').val().trim() != "") screenFailUID();
    else $.notify("กรุณากรอก Screen Note");
     //notEnrollToProject();
  }); // enrollToProject

  $("#btn_form_list_reload").click(function(){
    selectProjVisitForm();

  }); // btn_form_list_reload

  $("#btn_form_visit_xpress_list_reload").click(function(){
    selectProjVisitXpress();
  }); // btn_form_visit_xpress_list_reload



});


function initVisitData(){
  clearDataChangeProj();
  clearDataChangeVisit();

  $('#visit_proj_name').html($('#cur_proj_name').val());
  $('#visit_proj_pid').html("[PID: "+$('#cur_pid').val()+"]");
  $('#visit_proj_date').html("");
  $('#visit_name').html("");
  selectVisitList();
  showUIDDivVisit("uid_visit_list");

}


function selectVisitList(){
  var aData = {
            u_mode:"select_uid_visit_list",
            uid:$('#cur_uid').val(),
            proj_id:$('#cur_proj_id').val(),
  };

  //alert("uid/proj_id : "+aData.uid+'/'+aData.proj_id);
  save_data_ajax(aData,"w_user/db_proj_visit.php",selectVisitListComplete);
  //alert("uid/proj_id2 : "+aData.uid+'/'+aData.proj_id);
}

function selectVisitListComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);

  if(flagSave){
      $('#cur_uid_status').val(rtnDataAjax.uid_status); // uid status
      $('#cur_uid_param').val(rtnDataAjax.uid_param); // special uid param eg. poc200
      $('#cur_proj_final_status_date').val("");
      var datalist = rtnDataAjax.datalist;
      if(datalist.length > 0){
        var txt_final_status = "";

        if(rtnDataAjax.uid_status == "2"){
          var finalObj = rtnDataAjax.final_status_obj ;

          txt_final_status  = '<b>สิ้นสุดโครงการแล้ว บันทึกเมื่อ: '+changeToThaiDate(finalObj.collect_date)+"</b><br>หมายเหตุ: ";
          if(finalObj.f_status == '1' )txt_final_status += "Complete study ";
          else if(finalObj.f_status == '2' ) txt_final_status += "ออกจากโครงการก่อนกำหนด เนื่องจากเกิดเหตุการณ์ไม่พึงประสงค์ ";
          else if(finalObj.f_status == '3' ) txt_final_status += "ออกจากโครงการก่อนกำหนด เนื่องจากไม่สามารถปฏิบัติตามข้อปฏิบัติของโครงการ";
          else if(finalObj.f_status == '4' ) txt_final_status += "ต้องการถอนตัวออกจากโครงการวิจัย ";
          else if(finalObj.f_status == '5' ){
            txt_final_status += "ไม่สามารถติดตามอาสาสมัครได้ ";
            txt_final_status += "(วันสุดท้ายที่ติดตามได้คือวันที่: "+finalObj.fu_date+")";
          }
          else if(finalObj.f_status == '6' ) txt_final_status += "ต้องการออกจากโครงการก่อนกำหนด เนื่องจากเหตุผลอื่นๆ ";
          else if(finalObj.f_status == '7' ) txt_final_status += "เสียชีวิต ";
          else if(finalObj.f_status == '8' ) txt_final_status += "ผู้ทำการวิจัยพิจารณาให้อาสาสมัครออกจากโครงการก่อนกำหนด ";

          if(finalObj.f_reason != '' ) txt_final_status += "<br>เหตุผลประกอบ: "+finalObj.f_reason;
          if(finalObj.death_course != '' ) txt_final_status += "(วันที่เสียชีวิต: "+finalObj.death_date+" / สาเหตุ: "+finalObj.death_course+" )";

          if(finalObj.final_note != '' ) txt_final_status += "<br>Note: "+finalObj.final_note;

          $('#cur_proj_final_status_date').val(finalObj.collect_date);

          txt_final_status += '<br><button id="btn_final_status_proj" class="btn btn-warning btn-sm" type="button" onclick="openFinalStatus(2);">';
          txt_final_status += '<i class="fa fa-box fa-lg" ></i> สิ้นสุดโครงการของอาสาสมัคร / Final Status';
          txt_final_status += '</button>';

        }
        else{

          txt_final_status += '<br><button id="btn_final_status_proj" class="btn btn-info btn-sm" type="button" onclick="openFinalStatus(1);">';
          txt_final_status += '<i class="fa fa-box fa-lg" ></i> สิ้นสุดโครงการของอาสาสมัคร / Final Status';
          txt_final_status += '</button>';

        }






        <?
          if(isset($s_group)){
            if($s_group == '0'){ // normal staff group
              echo '$("#btn_final_status_proj").hide();';
            }
          }
        ?>

        $('#div_final_status_visit').html(txt_final_status);


        var txt_row = "";
        var flag_enable = 1;
        var txt_schedule_note = "";
        var btn_visit = "";
        var btn_visit_schedule = "";
        var btn_visit_meet = "";
        var btn_new_extra_visit = "";

        var extra_visit_no = 0;
        for (i = 0; i < datalist.length; i++) {

          var dataObj = datalist[i];
          btn_visit = "";
          btn_visit_meet = "";
          btn_visit_schedule = "";
          btn_new_extra_visit = '';

          if(flag_enable == 0){// disable visit info button
            btn_visit = '<b><span class="text-secondary"> <i class="fa fa-file-medical fa-lg"></i> '+dataObj.visit_name+"</span></b>";

          }
          else if(flag_enable == 1){// enable visit info button

            if(dataObj.status_id == '0'){ // pending visit

              if(i > 0 ){
              //  alert("prev " +datalist[i-1].status_id);
                if(datalist[i-1].status_id == '1' || datalist[i-1].status_id == '10' || datalist[i-1].status_id == '11'){ // previous visit is completed
                  btn_visit = '<b><span class="text-secondary"> <i class="fa fa-calendar fa-lg text-primary"></i> '+dataObj.visit_name+' (รอการเข้าพบ)</span></b>';

                  btn_visit_meet = ' <button class="btn btn-success btn-sm" type="button" onclick="meetVisit(\''+dataObj.visit_id+'\', \''+dataObj.group_id+'\',\''+dataObj.schedule_date+'\',  '+dataObj.d_before+', '+dataObj.d_after+')"><i class="fa fa-file-medical fa-lg"></i> UID เข้าพบในนัดหมายนี้</button>';
                //  btn_visit_schedule = ' <button class="btn btn-warning btn-sm" type="button" onclick="changeVisitSchedule(\''+dataObj.visit_id+'\', \''+dataObj.group_id+'\', \''+dataObj.schedule_date+'\', \''+dataObj.d_before+'\', \''+dataObj.d_after+'\')"><i class="fa fa-calendar-alt"></i> เปลี่ยน</button>';
                }
                else{ // previous visit is not completed
                  btn_visit = '<b><span class="text-secondary"> <i class="fa fa-file-medical fa-lg"></i> '+dataObj.visit_name+"</span></b>";
                }

                btn_visit_schedule = ' <button class="btn btn-warning btn-sm" type="button" onclick="changeVisitSchedule(\''+dataObj.visit_id+'\', \''+dataObj.group_id+'\', \''+dataObj.schedule_date+'\', \''+dataObj.d_before+'\', \''+dataObj.d_after+'\')"><i class="fa fa-calendar-alt"></i> เปลี่ยน</button>';
                flag_enable = 0;
              }
            }
            else{ // complete visit
              var btn_color = "btn-primary";
              var btn_name = dataObj.visit_name;

              if(dataObj.visit_id == "EX"){
                 btn_color = "btn-danger";
                 extra_visit_no ++;
                 btn_name = btn_name+' '+extra_visit_no;

                 dataObj.schedule_date = "";
              }



              btn_visit = '<button class="btn '+btn_color+' btn-sm btn-block" type="button" onclick="selectVisitInfo(\''+dataObj.visit_id+'\',\''+dataObj.visit_date+'\', \''+dataObj.group_id+'\')"><i class="fa fa-calendar-day fa-lg"></i> '+btn_name+'</button>';

              if(i < datalist.length){
                if(dataObj.status_id == '1' || dataObj.status_id == '10'){

                   if((i+1)<=datalist.length){ // check that datalist[i+1] is not null
//alert("enter list "+i);

/*
                      if(datalist[i+1].visit_date == "0000-00-00" && dataObj.visit_id != "SCRN" && datalist[i+1].status_id != "10"){
                        btn_new_extra_visit = '<button class="btn btn-info btn-sm" type="button" onclick="meetExtraVisit(\''+dataObj.visit_id+'\', \''+dataObj.group_id+'\')"><i class="fa fa-calendar-plus "></i> มานอกนัดหมาย</button>';
                      }
*/
                      if (datalist[i+1] !== undefined){
                        if(datalist[i+1].visit_date == "0000-00-00" && dataObj.visit_id != "SCRN" && datalist[i+1].status_id != "10"){
                          btn_new_extra_visit = '<button class="btn btn-info btn-sm" type="button" onclick="meetExtraVisit(\''+dataObj.visit_id+'\', \''+dataObj.group_id+'\')"><i class="fa fa-calendar-plus "></i> มานอกนัดหมาย</button>';
                        }
                      }

                   }//(i+1)<=dataObj.length
                }
              }

            }

          }

          //if(dataObj.is_lastest_visit == "Y"){

          var visit_remark = dataObj.group_name;
          if(dataObj.visit_note != "")
          visit_remark += "<br><small><span class='text-primary'>"+dataObj.visit_note+"</span></small>";

          var uid_visit_date = "";
          if(dataObj.status_id != '10') uid_visit_date = changeToThaiDate(dataObj.visit_date);


          var sdn_class = "";
          var schedule_note = dataObj.schedule_note
          if(schedule_note == ""){
            schedule_note = "ADD Note";
            sdn_class = "text-secondary";
          }

          txt_schedule_note = "<span id='sdn"+dataObj.visit_id+dataObj.group_id+"' class='"+sdn_class+"' sdn_class data-id='"+dataObj.visit_id+dataObj.group_id+"' data-odata='"+dataObj.schedule_note+"' onclick='editScheduleNote(\""+dataObj.visit_id+"\", \""+dataObj.group_id+"\");'>"+schedule_note+"</span>"
          txt_schedule_note+= "<div id='divsdn"+dataObj.visit_id+dataObj.group_id+"' style='display:none;'>"
          txt_schedule_note+= "<table><tr>";
          txt_schedule_note+= "<td colspan=2>";
          txt_schedule_note+= '<textarea rows="2" cols="50" maxlength="200" id="txtsdn'+dataObj.visit_id+dataObj.group_id+'" type="text" class="form-control form-control-sm"  placeholder="Add Note"></textarea></div>';
          txt_schedule_note+= "</td></tr><tr>";
          txt_schedule_note+= '<td><button class="form-control form-control-sm btn btn-success btn-sm" type="button" onclick="saveScheduleNote(\''+dataObj.visit_id+'\', \''+dataObj.group_id+'\')"> <i class="fa fa-check fa-lg" ></i> บันทึก</button></td>';
          txt_schedule_note+= '<td><button class="form-control form-control-sm btn btn-danger btn-sm" type="button" onclick="cancelEditScheduleNote(\''+dataObj.visit_id+'\', \''+dataObj.group_id+'\')"> <i class="fa fa-times fa-lg" ></i> ยกเลิก</button></td>';

          txt_schedule_note+= "</tr></table>";


          txt_row += '<tr class="r_uid_visit">';
          txt_row += ' <td>'+btn_visit+'</td>';
          txt_row += ' <td>'+visit_remark+'</td>';
        //  txt_row += ' <td>'+changeToThaiDate(dataObj.schedule_date)+btn_visit_schedule+'</td>';
          txt_row += ' <td>'+changeToThaiDate(dataObj.schedule_date)+btn_visit_schedule+'<br> <small>'+dataObj.w_period+'</small></td>';
        //  txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+btn_visit_meet+'</td>';
          txt_row += ' <td>'+uid_visit_date+btn_visit_meet+'</td>';
          txt_row += ' <td>'+dataObj.status_name+'</td>';
          //txt_row += ' <td>'+dataObj.is_lastest_visit+'</td>'; s
          txt_row += ' <td>'+btn_new_extra_visit+'</td>';

          txt_row += ' <td>'+txt_schedule_note+'</td>';
          txt_row += '</tr">';



        }//for



        $('.r_uid_visit').remove(); // row uid proj visit
        $('#tbl_visit_list > tbody:last-child').append(txt_row);


/*
        var enroll_date = new Date(cur_enroll_date);
        // add a day
        enroll_date.setDate(enroll_date.getDate() + 407);

        const dateTimeFormat = new Intl.DateTimeFormat('en', { year: 'numeric', month: 'numeric', day: '2-digit' })
        const [{ value: month },,{ value: day },,{ value: year }] = dateTimeFormat .formatToParts(enroll_date )
        var str = `${day}/${month}/${parseInt(year)+543 }`;
*/
        $('#last_date_visit').html(" วันที่สุดท้ายที่สามารถเข้าพบได้คือ: <b><u>"+changeToThaiDate(rtnDataAjax.last_visit_date)+"</u></b> ");




    }//if
    else{
    //  $('#div_uid_visit_list_detail').html("ไม่มีข้อมูลขณะนี้");
      $.notify("ไม่มีข้อมูลขณะนี้","info");
    }
  }
}

function editScheduleNote(visitID, groupID){
//  alert("click editScheduleNote "+visitID);
<?
  if(!isset($auth["schedule"])){
    echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
    echo "return;";
  }
?>

  $('#sdn'+visitID+groupID).hide();
  $('#divsdn'+visitID+groupID).show();
  $('#txtsdn'+visitID+groupID).val($('#sdn'+visitID+groupID).data("odata"));
  $('#txtsdn'+visitID+groupID).focus();
}

function cancelEditScheduleNote(visitID, groupID){
  var txt = $('#sdn'+visitID+groupID).data("odata");
  if(txt == "") txt= "ADD Note";
//  alert("cancel data "+txt);
  $('#sdn'+visitID+groupID).html(txt);
  $('#txtsdn'+visitID+groupID).val("");

  $('#sdn'+visitID+groupID).show();
  $('#divsdn'+visitID+groupID).hide();
}


function saveScheduleNote(visitID, groupID){
  <?
    if(!isset($auth["schedule"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }
  ?>

      var aData = {
                u_mode:"update_schedule_note",
                uid: $('#cur_uid').val(),
                proj_id: $('#cur_proj_id').val(),
                group_id: groupID,
                visit_id: visitID,
                schedule_note:$('#txtsdn'+visitID+groupID).val().trim()
      };
      save_data_ajax(aData,"w_user/db_proj_visit.php",saveScheduleNoteComplete);

}

function saveScheduleNoteComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $('#sdn'+aData.visit_id+aData.group_id).removeClass("text-secondary");
    var txt = $('#txtsdn'+aData.visit_id+aData.group_id).val().trim();
    $('#sdn'+aData.visit_id+aData.group_id).data("odata",txt);

    if(txt == ""){
      txt = "ADD Note";
      $('#sdn'+aData.visit_id+aData.group_id).addClass("text-secondary");
    }

    $('#sdn'+aData.visit_id+aData.group_id).html(txt);

    $('#divsdn'+aData.visit_id+aData.group_id).hide();
    $('#sdn'+aData.visit_id+aData.group_id).show();

  }

}

function checkWindowPeriod(scheduleDate, dateBefore, dateAfter){
   //alert("pop "+dateBefore+" / "+dateAfter);
   var result = "ok";

   var arrDate = scheduleDate.split("-");

   var currentDate = new Date();
   var begDate = new Date();
   var endDate = new Date();

   begDate.setFullYear(arrDate[0], parseInt(arrDate[1])-1, arrDate[2]);
   endDate.setFullYear(arrDate[0], parseInt(arrDate[1])-1, arrDate[2]);

   begDate.setDate(begDate.getDate() - dateBefore);
   endDate.setDate(endDate.getDate() + dateAfter);

   //alert("cur: "+currentDate+" / beg: "+begDate+" / end: "+endDate);

   if(currentDate < begDate){
     result = "before";
   }
   else if(currentDate > endDate){
     result = "after";
   }

   return result;
}


function meetVisit(visitID, groupID, scheduleDate, dateBefore, dateAfter){

  <?
    if(!isset($auth["schedule"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }
  ?>


  //var result = checkWindowPeriod(scheduleDate, dateBefore, dateAfter);
  var result = "ok";
  if(result == "ok"){ // in window period
      var aData = {
                u_mode:"meet_visit",
                uid: $('#cur_uid').val(),
                proj_id: $('#cur_proj_id').val(),
                group_id: groupID,
                visit_id: visitID
      };
      save_data_ajax(aData,"w_user/db_proj_visit.php",meetVisitComplete);
  }
  else if(result == "before"){ // meet visit before window period
    if(confirm("คนไข้มาก่อน window period ของนัดหมายนี้ ("+visitID+") ท่านต้องการสร้าง มานอกนัดหมาย (extra visit) ใช่หรือไม่?")){
      meetExtraVisit(visitID, groupID);
    }
  }
  else if(result == "after"){ // meet visit after window period
    if(confirm("คนไข้มาหลัง window period ของนัดหมายนี้ ("+visitID+") ระบบจะจัดการให้เปลี่ยนสถานะเป็น ไม่มาตามนัดหมาย (Lost to Follow Up)")){
      setLostToFollowupVisit(visitID, groupID);
    }
  }

}

function meetVisitComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $.notify("เริ่มการเข้าพบในนัดหมายนี้ของ "+$('#cur_uid').val()+" แล้ว", "info");
    $('#cur_group_id').val(aData.group_id);
    selectVisitList();
    //selectVisitInfo(rtnDataAjax.visit_date, $('#cur_group_id').val());
  }

}

function meetExtraVisit(visitID, groupID){

  <?
    if(!isset($auth["schedule"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }
  ?>


  if(confirm("ยืนยันจะสร้าง มานอกนัดหมาย (extra visit) ใช่หรือไม่?")){
    var aData = {
              u_mode:"meet_extra_visit",
              uid: $('#cur_uid').val(),
              proj_id: $('#cur_proj_id').val(),
              group_id: groupID
    };
    //alert("Pending this function");
    save_data_ajax(aData,"w_user/db_proj_visit.php",meetExtraVisitComplete);
  }

}

function meetExtraVisitComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $.notify("สร้างนัดหมายเพิ่มเติม "+$('#cur_uid').val()+" แล้ว", "info");

    selectVisitList();
  }
}




function setLostToFollowupVisit(visitID, groupID){
  <?
    if(!isset($auth["schedule"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }
  ?>

    var aData = {
              u_mode:"set_lost_to_followup_visit",
              uid: $('#cur_uid').val(),
              proj_id: $('#cur_proj_id').val(),
              group_id: groupID,
              visit_id:visitID
    };
    //alert("Pending this function");
    save_data_ajax(aData,"w_user/db_proj_visit.php",setLostToFollowupVisitComplete);

}

function setLostToFollowupVisitComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $.notify("เปลี่ยนสถานะของนัดหมาย "+aData.visit_id+" เป็น ไม่มาตามนัดหมาย แล้ว", "info");

    selectVisitList();
  }
}



// change visit schedule within window period
function changeVisitSchedule(visitID, groupID, scheduleDate, visitBefore, visitAfter){
  <?
    if(!isset($auth["schedule"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าถึงข้อมูลนี้', 'info');";
      echo "return;";
    }
  ?>

  $('#cur_visit_id').val(visitID);
  $('#cur_group_id').val(groupID);

  var link = "w_user/proj_visit_schedule_update.php?";
  link += "schedule_date="+scheduleDate;
  link += "&d_before="+visitBefore;
  link += "&d_after="+visitAfter;

  //$('#div_project_visit_menu').load(link);
//  alert("link : "+link);
  $("#div_project_visit_update_schedule").load(link, function(){
      showUIDDivVisit("project_visit_update_schedule");
  });

}



function selectVisitInfo(visitID, visitDate, groupID){

  var aData = {
            u_mode:"select_uid_visit_info",
            uid: $('#cur_uid').val(),
            proj_id: $('#cur_proj_id').val(),
            visit_id: visitID,
            group_id: groupID,
            visit_date:visitDate
  };

  save_data_ajax(aData,"w_user/db_proj_visit.php",selectVisitInfoComplete);

}

function selectVisitInfoComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var dataObj = rtnDataAjax.visit_info;
    $('#cur_visit_date').val(aData.visit_date);
    $('#cur_visit_id').val(dataObj.visit_id);
    $('#cur_group_id').val(dataObj.group_id);
    $('#cur_visit_status_id').val(dataObj.status_id);

    $('#v_visit_pid').html($('#cur_pid').val());
    $('#v_visit_name2').html(dataObj.visit_name);
    $('#v_visit_group').html(dataObj.group_name);

    $('#v_schedule_date').html(changeToThaiDate(dataObj.schedule_date));
    $('#v_visit_date').html(changeToThaiDate(dataObj.visit_date));

    $('#v_visit_status').html(dataObj.status_name);
    $('#v_visit_note').val(dataObj.visit_note);

    $('#v_groupchange_note').html(dataObj.groupchange);

    $('#is_lastest_visit').val(dataObj.is_lastest_visit);



    // if visit complete not show control menu
    //alert("visit status id : "+$('#cur_visit_status_id').val());
    var flag_consent = 0;
    if($('#cur_visit_status_id').val() == '1' ||
       $('#cur_visit_status_id').val() == '10' ||
       $('#cur_visit_status_id').val() == '11'
    ){ // complete visit
     $('#div_project_visit_menu').html("");

  //   $('#div_btn_form_log').hide();
      $('#visit_log_enable').val("0");

    }
    else{ // not complete visit
      flag_consent = 1;
      $('#visit_log_enable').val("1");
//      $('#div_btn_form_log').show();

      var link = "w_user/proj_visit_conf/";
      link += $('#cur_proj_id').val()+"/menu.php?";
      link += "status_id="+$('#cur_visit_status_id').val();
      link += "&group_id="+$('#cur_group_id').val();

      //$('#div_project_visit_menu').load(link);

      $("#div_project_visit_menu").load(link, function(){
          $('#visit_note').val(dataObj.visit_note);
      });

    }


    showUIDDivVisit("project_visit_info");
    showUIDDiv("uid_visit");
    showMainDiv("uid");

    selectProjVisitForm(); // select all this project visit form
//    selectProjVisitXpress(); // select xpress server for this visit date

//alert("consent : "+dataObj.is_consent+"/"+dataObj.visit_consent);

      clearConsent();
      if(flag_consent == 1){ // not complete visit
        var btn_consent='<button id="btn_open_consent" class="btn btn-sm btn-warning ml-4" type="button" onclick="openConsent(\''+flag_consent+'\');"><i class="fa fa-file-signature" ></i> Consent</button>';
        $("#div_consent_btn").html(btn_consent);

        if(dataObj.visit_consent == 0){ // no consent data in this visit
          checkConsent();// check consent in this visit
        }
      }
      else if(flag_consent == 0){ // complete visit
        if(dataObj.visit_consent == 0){ // no consent data in this visit
          $("#div_consent_btn").html("");
        }
        else if(dataObj.visit_consent == 1){ // has consent data in this visit
          var btn_consent='<button id="btn_open_consent" class="btn btn-sm btn-secondary ml-4" type="button" onclick="openConsent(\''+flag_consent+'\');"><i class="fa fa-file-signature" ></i> Consent</button>';
          $("#div_consent_btn").html(btn_consent);
        }
      }




/*
    if(flag_consent == 1){
      if(dataObj.is_consent == 0) checkConsent();

    }
*/


  }// flag_save
}

function openConsent(opt_save){ // opt_save 1=enable, 0=disable

      openProjConsent($("#cur_uid").val(),
      $("#cur_visit_date").val(),
      $("#cur_proj_id").val(),
      "<? echo $consent_txt; ?>" ,
      opt_save
      );
}

// check reconsent
function checkConsent(){

    //  dlgProjConsent("xxx");
      var consentTitle = $("#cur_uid").val()+"/"+$("#cur_visit_date").val();
      dlgProjConsent($("#cur_uid").val(),
      $("#cur_visit_date").val(),
      $("#cur_proj_id").val(),
      consentTitle,
      "<? echo $consent_txt; ?>",
      "<? echo $consent_version; ?>"
    );
}

function selectProjVisitForm(){ // select form list in this visit
  var aData = {
            u_mode:"sel_proj_visit_form",
            uid:$('#cur_uid').val(),
            proj_id:$('#cur_proj_id').val(),
            group_id:$('#cur_group_id').val(),
            visit_date:$('#cur_visit_date').val(),
            visit_id:$('#cur_visit_id').val()
  };

  //alert("show "+aData.uid+"/"+)
  save_data_ajax(aData,"w_user/db_proj_form.php",selectProjVisitFormComplete);
}

function selectProjVisitFormComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  clearDataChangeVisit();
  $('.r_visit_form').remove(); // row visit form
  if(flagSave){
      var datalist = rtnDataAjax.datalist;

      if(datalist.length > 0){
        var txt_row = "";
        var form_done = "";
        var open_link = "";

        var k=0;
        var i=0;
        for (i = 0; i < datalist.length; i++){
          var dataObj = datalist[i];
          open_link = "";

          //form_done = (dataObj.form_done == 'Y')?'<i class="fa fa-check-circle fa-lg text-success" ></i>':'<i class="fa fa-times-circle fa-lg text-danger" ></i>';
          if(dataObj.form_done == 'Y'){
            form_done ='<i class="fa fa-check-circle fa-lg text-success" ></i>';
            k++;
          }
          else{
            form_done ='<i class="fa fa-times-circle fa-lg text-danger" ></i>';
          }


          if(dataObj.open_link == '1'){
            //open_link = "LINK";
            open_link = "<a href='w_user/link_form.php?uid="+$('#cur_uid').val()+"&proj_id="+$('#cur_proj_id').val()+"&visit_id="+$('#cur_visit_id').val()+"&visit_date="+$('#cur_visit_date').val()+"&group_id="+$('#cur_group_id').val()+"&form_id="+dataObj.form_id+"&form_name="+dataObj.form_name+"' target='_blank'>";
            open_link+= " LINK </a>";


            open_link = "<a href='w_user/link_form.php?";
            open_link += "uid="+$('#cur_uid').val(); // uid
            open_link += "&visit_date="+$('#cur_visit_date').val(); // screen date
            open_link += "&visit_id="+$('#cur_visit_id').val();; // visit id
            open_link += "&proj_id="+$('#cur_proj_id').val(); // project id
            open_link += "&group_id="+$('#cur_group_id').val(); // group id (just screening/ no group )

            open_link += "&uic="+$('#cur_uic').val(); // uic
            open_link += "&pid="+$('#cur_pid').val(); // pid

            open_link += "&form_id="+dataObj.form_id; // form id
            open_link += "&form_name="+dataObj.form_name; // form name

            open_link += "' target='_blank'> LINK </a>";
          }


          txt_row += '<tr class="r_visit_form" id="'+dataObj.form_id+'">';
          //txt_row += ' <td><button class="btn btn-primary" type="button" onclick="openUIDForm(\''+dataObj.uid+'\',\''+dataObj.form_id+'\',\''+dataObj.visit_date+'\')""><i class="fa fa-user"></i> '+dataObj.uid+'</button></td>';
          txt_row += ' <td><i class="fa fa-first-aid fa-lg text-dark" ></i> ';
          txt_row += open_link;
          txt_row += ' </td>';
          txt_row += ' <td>'+dataObj.form_name+' <small><span class="text-secondary">('+dataObj.form_ver+')</span></small></td>';
          txt_row += ' <td align="center">'+form_done+'</td>';
          txt_row += ' <td align="center"><button class="btn btn-primary btn-block" type="button" onclick="openUIDForm(\''+dataObj.form_id+'\',\''+dataObj.form_name+'\')""><i class="fa fa-folder-open"></i> เปิด</button></td>';
          txt_row += '</tr">';
        } //for

        $('.r_visit_form').remove(); // row visit form
        $('#tbl_visit_form_list > tbody:last-child').append(txt_row);

        if(i == k) $('#is_form_complete').val("Y");
        else  $('#is_form_complete').val("N");

/*
        if($('#cur_visit_id').val() == 'M0'){
          // check sero conversion (other groups changed to group 004)
          if($('#cur_group_id').val() == '004' && $('#cur_pid').val().indexOf('-004-') > -1){
             $('#sero_con').remove(); // remove sero form if M0 and Group 004 by initial enroll
          }
        }
*/


<?

// POC : selectPOC200, check sero conversion when changed to group 004
   echo $fn_after_visit_form;

?>


    }//if
    else{
      $.notify("ไม่มีฟอร์มใส่ข้อมูลให้เลือกในขณะนี้","info");
    }
  }
}

<?
   echo $js_fn_project;
?>


function openUIDForm(formID, formName){
  //$('#div_project_visit_form_title').hide();
  <?
    if(!isset($auth["view"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าถึงข้อมูลนี้', 'info');";
      echo "return;";
    }

  ?>

  var link = "visit_form/f_form_proj.php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&form_id="+formID; // form id
  link += "&visit_date="+$('#cur_visit_date').val(); // screen date
  link += "&visit_id="+$('#cur_visit_id').val();; // visit id
  link += "&proj_id="+$('#cur_proj_id').val(); // project id
  link += "&group_id="+$('#cur_group_id').val(); // group id (just screening/ no group )
 //alert("link : "+link);
/* // old value
  var link = "visit_form/x_"+formID+".php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&visit_date="+$('#cur_visit_date').val(); // screen date
  link += "&visit_id="+$('#cur_visit_id').val();; // visit id
  link += "&proj_id="+$('#cur_proj_id').val(); // project id
  link += "&group_id="+$('#cur_group_id').val(); // group id (just screening/ no group )
*/



  //alert("openUIDForm "+formID+"/"+uid+"/"+visit_date);
//  alert("openUIDFormxx "+link);
  $('#div_project_visit_form_data').html("รอสักครู่");
  $('#form_title').html(formName);

  $("#div_project_visit_form_data").load(link, function(){
      showUIDDivVisit("project_visit_form");
      $('#cur_form_id').val(formID);

  });


}


function openFormLog(formLogID){
  //alert("openformlog "+formLogID);

  var link = "visit_form_log/z_"+formLogID+".php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&visit_date="+$('#cur_visit_date').val(); // screen date
  link += "&visit_id="+$('#cur_visit_id').val();; // visit id
  link += "&proj_id="+$('#cur_proj_id').val(); // project id
  link += "&group_id="+$('#cur_group_id').val(); // group id (just screening/ no group )
  link += "&is_lastest_visit="+$('#is_lastest_visit').val(); // formlog add?  ได้เฉพาะ visit ท้ายสุด

  //alert("openUIDForm "+formID+"/"+uid+"/"+visit_date);
//  alert("openformlog link : "+link);
  $('#div_project_visit_form_log').html("รอสักครู่");


  $("#div_project_visit_form_log").load(link, function(){
      showUIDDivVisit("project_visit_form_log");
      //$('#div_project_visit_form_title').show();
  });

}

function closeFormLog(){
  showUIDDivVisit("project_visit_form");;
}

function openFormLog2(formLogID){
  var link = "visit_form_log/z_"+formLogID+".php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&visit_date="+$('#cur_visit_date').val(); // screen date
  link += "&visit_id="+$('#cur_visit_id').val();; // visit id
  link += "&proj_id="+$('#cur_proj_id').val(); // project id
  link += "&group_id="+$('#cur_group_id').val(); // group id (just screening/ no group )

  link += "&open_visit=1" // open by main visit
  link += "&is_lastest_visit="+$('#is_lastest_visit').val(); // formlog add?  ได้เฉพาะ visit ท้ายสุด

//alert("openformlog link : "+link);

  $('#div_project_visit_form_log').html("รอสักครู่");
  $("#div_project_visit_form_log").load(link, function(){
      showUIDDivVisit("project_visit_form_log");

      //$('#div_project_visit_form_title').show();
  });

}

function closeFormLog2(){ // close to main visit
  showUIDDivVisit("project_visit_info");
}



function openUIDVisitScheduleUpdate(visitID, visitName){
  //$('#div_project_visit_form_title').hide();

  var link = "w_user/proj_visit_schedule_update.php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&visit_date="+$('#cur_visit_date').val(); // screen date
  link += "&visit_id="+$('#cur_visit_id').val();; // visit id
  link += "&proj_id="+$('#cur_proj_id').val(); // project id
  link += "&group_id="+$('#cur_group_id').val(); // group id (just screening/ no group )

  $('#div_project_visit_form_data').html("รอสักครู่");
  $('#form_title').html(formName);

  $("#div_project_visit_form_data").load(link, function(){
      showUIDDivVisit("project_visit_form");
      //$('#div_project_visit_form_title').show();
  });

}


function selectProjVisitXpress(){ // select xpress service in this visit date
  var aData = {
            u_mode:"select_visit_xpress",
            uid:$('#cur_uid').val(),
            visit_date:$('#cur_visit_date').val()
  };

  //alert("show "+aData.uid+"/"+)
  save_data_ajax(aData,"xpress_service/db_xpress_service.php",selectProjVisitXpressComplete);
}

function selectProjVisitXpressComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  $('.r_xpress').remove(); // row visit form
  if(flagSave){
      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
      if(datalist.length > 0){

        for (i = 0; i < datalist.length; i++){
          var dataObj = datalist[i];
          var patient_xpress_done = "";
          var csl_xpress_done = "";
          var xpress_satisfaction = "";
          var xpress_send_result = "";

          var btn_open_xpress = "";



          if(dataObj.p_x_sum == 'Y'){ // pass assessment
            patient_xpress_done += '<span class="badge badge-success px-1"><i class="fa fa-check"></i> ผ่านเกณฑ์ประเมิน </span>';
          }
          else if(dataObj.p_x_sum == 'N'){// not pass assessment
            patient_xpress_done += '<span class="badge badge-danger px-1"><i class="fa fa-times"></i> ไม่ผ่านเกณฑ์ประเมิน </span>';
          }

          // patient consent
          if(dataObj.p_c_agree == 'Y'){
            patient_xpress_done += '<br><span class="badge badge-success px-1"><i class="fa fa-user-check"></i> ยอมรับ XPress </span>';
          }
          else if(dataObj.p_c_agree == 'N'){
            patient_xpress_done += '<br><span class="badge badge-danger px-1"><i class="fa fa-user-times"></i> ไม่ยอมรับ XPress </span>';
          }
          else {
            patient_xpress_done += '<br><span class="badge badge-secondary px-1"><i class="fa fa-user"></i> ยังไม่ทำ </span>';
          }

// counselor
    if(dataObj.c_uid != ''){
      if(dataObj.c_x_sum == 'Y'){
        csl_xpress_done += '<span class="badge badge-success px-1"><i class="fa fa-check"></i> ผ่านเกณฑ์ประเมิน </span>';
      }
      else if(dataObj.c_x_sum == 'N'){
        csl_xpress_done += '<span class="badge badge-danger px-1"><i class="fa fa-times"></i> ไม่ผ่านเกณฑ์ประเมิน </span>';
      }
      else {
        csl_xpress_done += '<span class="badge badge-secondary px-1"><i class="fa fa-user"></i> ยังไม่ทำ </span>';
      }

      // patient consent (check by counselor)
      if(dataObj.c_c_agree == 'Y'){
        csl_xpress_done += '<br><span class="badge badge-success px-1"><i class="fa fa-user-check"></i> ยอมรับ XPress </span>';
      }
      else if(dataObj.c_c_agree == 'N'){
        csl_xpress_done += '<br><span class="badge badge-danger px-1"><i class="fa fa-user-times"></i> ไม่ยอมรับ XPress </span>';
      }
      else {
        csl_xpress_done += '<br><span class="badge badge-secondary px-1"><i class="fa fa-user"></i> ยังไม่ทำ </span>';
      }

    }
    else{ // not done by counselor

      //csl_xpress_done += ' <button class="btn btn-info btn-sm" type="button" onclick="confirmVisitXpressService()"><i class="fa fa-question-circle"></i> ยืนยันผลตามคนไข้ </small></button>';
       csl_xpress_done +=' <button class="btn btn-info btn-block" type="button" onclick="openVisitXpressService()"><i class="fa fa-folder-open"></i> เปิดดู </small></button>';
    }


    //xpress satisfaction
    if(dataObj.s_uid != ''){ // already filled part1 before send xpress
      xpress_satisfaction += ' <span class="badge badge-info px-1"><i class="fa fa-check"></i> ความพึงพอใจ (ก่อนส่งผล) </span>';

      if(dataObj.after_service != ''){ // already filled after send xpress
        xpress_satisfaction += '<br><span class="badge badge-info px-1"><i class="fa fa-check"></i> ความพึงพอใจ (หลังส่งผล) </span>';
      }
      else{ // not filee after send xpress
        xpress_satisfaction += '<br>ยังไม่ได้ทำ (หลังส่งผล) <br> <a href="xpress_service/link_xpress_satisfaction_after.php?uid='+$('#cur_uid').val()+'&visit_date='+$('#cur_visit_date').val()+'&uic='+$('#cur_uic').val()+'&site=<? echo $staff_clinic_id;?>" target="_blank">LINK ความพึงพอใจ (หลังส่งผล)</a> ';
      }

    }
    else{ // not filled (fill-in link)
      xpress_satisfaction += 'ยังไม่ได้ทำ (ก่อนส่งผล) <br> <a href="xpress_service/link_xpress_satisfaction.php?uid='+$('#cur_uid').val()+'&visit_date='+$('#cur_visit_date').val()+'&uic='+$('#cur_uic').val()+'&site=<? echo $staff_clinic_id;?>" target="_blank">LINK ความพึงพอใจ (ก่อนส่งผล)</a> ';
    }


    //xpress send result
    if(dataObj.sc_id != ''){ // already send
      xpress_send_result += ' <span class="badge badge-success px-1"><i class="fa fa-paper-plane"></i> ส่งผลตรวจแล้ว ('+dataObj.send_date+')</button>';
    }
    else{ // not send (send link)
      xpress_send_result += ' <span class="badge badge-secondary px-1"><i class="fa fa-times"></i> ยังไม่ส่งผลตรวจ </button>';
    }

    btn_open_xpress = ' <button class="btn btn-primary btn-block" type="button" onclick="openVisitXpressService()"><i class="fa fa-folder-open"></i> เปิด </small></button>';

    txt_row += '<tr class="r_xpress">';
    txt_row += ' <td align="center"><span id="visit_xpress_patient">'+patient_xpress_done+'</span></td>';
    txt_row += ' <td align="center"><span id="visit_xpress_csl">'+csl_xpress_done+'</span></td>';

    txt_row += ' <td align="center"><span id="visit_xpress_satisfaction">'+xpress_satisfaction+'</span></td>';
    txt_row += ' <td align="center"><span id="visit_xpress_result">'+xpress_send_result+'</span></td>';

    txt_row += '</tr">';



        } //for

    }//if
    else{ // not done

      txt_row += '<tr class="r_xpress">';
      txt_row += ' <td align="center" id="visit_xpress_patient"><i class="fa fa-times fa-lg text-danger"></i> <small>ยังไม่ทำ</small>   ';
      txt_row += ' <a href="xpress_service/link_xpress_service.php?uid='+$('#cur_uid').val()+'&visit_date='+$('#cur_visit_date').val()+'&uic='+$('#cur_uic').val()+'&site=<? echo $staff_clinic_id;?>" target="_blank">LINK</a> ';
      txt_row += ' </td>';

      txt_row += ' <td align="center"><i class="fa fa-times fa-lg text-danger"></i> <small>ยังไม่ทำ</small></td>';
      txt_row += ' <td align="center"><i class="fa fa-times fa-lg text-danger"></i> <small>ยังไม่ทำ</small></td>';
      txt_row += ' <td align="center"><i class="fa fa-times fa-lg text-danger"></i> <small>ยังไม่ส่งผลตรวจ</small></td>';
      txt_row += '</tr">';

      btn_open_xpress = ' <button class="btn btn-secondary btn-block" type="button" ><i class="fa fa-folder"></i> ยังไม่มีข้อมูล </small></button>';

    }

    $('.r_xpress').remove(); // row proj visit xpress
    $('#tbl_visit_xpress > tbody:last-child').append(txt_row);

    $('#div_open_visit_xpress').html(btn_open_xpress);





  }//flag_save
}

function openVisitXpressService(){
  //$('#div_project_visit_form_title').hide();
  <?
    if(!isset($auth["view"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าถึงข้อมูลนี้', 'info');";
      echo "return;";
    }
  ?>

  var link = "xpress_service/visit_xpress_service.php";
  $('#div_project_visit_xpress_service_data').html("รอสักครู่");
  $("#div_project_visit_xpress_service_data").load(link, function(){
      showUIDDivVisit("project_visit_xpress_service");
  });

}


function confirmVisitXpressService(){
  var aData = {
            u_mode:"confirm_xpress_service_patient",
            uid:$('#cur_uid').val(),
            visit_date:$('#cur_visit_date').val()
  };
  //alert("uid/proj_id : "+aData.uid+'/'+aData.proj_id);
  save_data_ajax(aData,"xpress_service/db_xpress_service.php",confirmVisitXpressServiceComplete);

}

function confirmVisitXpressServiceComplete(flagSave, rtnDataAjax, aData){
//  alert("flag save is : "+flagSave);
  if(flagSave){
    $('#visit_xpress_csl').html($('#visit_xpress_patient').html());
    $.notify("ยืนยันผล XPress ตามคนไข้แล้ว","info");
  }
}





function showUIDDivVisit(choice){
  $('.div-uid-visit').hide();
  $('#div_'+choice).show();
}

function setDataChangeVisitDetail(){
  $('#data_update_visit_detail').val("Y");
}
function clearDataChangeVisitDetail(){
  $('#data_update_visit_detail').val("");
}

// final status form (patient exit from project)
function openFinalStatus(mode){
  //alert("openFinalStatus "+mode);
  <?
    if(!isset($auth["data"]) && !isset($auth["query"])){
      echo "myModalContent('แจ้งเตือน', 'คุณไม่ได้รับอนุญาตให้เข้าจัดการส่วนนี้', 'info');";
      echo "return;";
    }
  ?>

  if(mode == 1){ // cbo staff check final status
    if(confirm("ท่านต้องการให้อาสาสมัครสิ้นสุดจากโครงการ ใช่หรือไม่?")){


        var link = "visit_form/f_form_proj.php?";
        link += "uid="+$('#cur_uid').val(); // uid
        link += "&form_id="+$("#cur_proj_id").val().toLowerCase()+"_final_status";
        link += "&visit_date="+$('#cur_visit_date').val(); // screen date
        link += "&visit_id="+$('#cur_visit_id').val();; // visit id
        link += "&proj_id="+$('#cur_proj_id').val(); // project id
        link += "&group_id="+$('#cur_group_id').val(); // group id (just screening/ no group )


        $('#div_project_visit_form_data').html("รอสักครู่");
        $('#form_title').html("แบบฟอร์มสิ้นสุดโครงการ (Final Status)");

        $("#div_project_visit_form_data").load(link, function(){
          //  alert("link99: "+link);
            showUIDDivVisit("project_visit_form");
            $('#cur_form_id').val("final_status");
        });
    }
  }
  else if (mode == 2) { // staff check data
        var link = "visit_form/f_form_proj.php?";
        link += "uid="+$('#cur_uid').val(); // uid
        link += "&form_id="+$("#cur_proj_id").val().toLowerCase()+"_final_status";
        link += "&visit_date="+$("#cur_proj_final_status_date").val(); // final status date
        link += "&visit_id="+$('#cur_visit_id').val();; // visit id
        link += "&proj_id="+$('#cur_proj_id').val(); // project id
        link += "&group_id="+$('#cur_group_id').val(); // group id (just screening/ no group )


        $('#div_project_visit_form_data').html("รอสักครู่");
        $('#form_title').html("แบบฟอร์มสิ้นสุดโครงการ (Final Status)");

        $("#div_project_visit_form_data").load(link, function(){
          //  alert("link99: "+link);
            showUIDDivVisit("project_visit_form");
            $('#cur_form_id').val("final_status");
        });

  }


} // openFinalStatus






/*
function selectPOC200(){ // select first 200 cases for POC
  var aData = {
            u_mode:"sel_poc_200",
            uid:$('#cur_uid').val(),
            proj_id:$('#cur_proj_id').val(),
            visit_date:$('#cur_visit_date').val()
  };

  save_data_ajax(aData,"w_user/proj_visit_conf/POC/db_POC.php",selectPOC200Complete);
}

function selectPOC200Complete(flagSave, rtnDataAjax, aData){
  if(flagSave){
    if(rtnDataAjax.is_poc200_visit == 'Y'){
      var txt_row = '<tr class="r_visit_form">';
      txt_row += ' <td><i class="fa fa-first-aid fa-lg text-warning" ></i> ';
    //  txt_row += open_link;
      txt_row += ' </td>';
      txt_row += ' <td> Point of Care LAB (First 200 Cases) </td>';
      txt_row += ' <td align="center"><i class="fa fa-check-circle fa-lg text-success" ></i></td>';
      txt_row += ' <td align="center"><button class="btn btn-primary btn-block" type="button" onclick="openUIDForm(\'poc_lab_enroll_200cases\',\'Point of Care (200 Cases)\')""><i class="fa fa-folder-open"></i> เปิด</button></td>';
      txt_row += '</tr">';

      $('#tbl_visit_form_list > tbody:last-child').append(txt_row);

    }

  }
}
*/



</script>
