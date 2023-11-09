





  <div class="row alert alert-success" role="alert">
     <div class="col-sm-10">
         <h3><i class="fa fa-filter fa-lg" ></i> <b><span id="screen_proj_name">Proj_Name</span></b>  คัดกรองเข้าโครงการ  <span id="screen_proj_date">[Screen_Date]</span></h3>
     </div>
     <div class="col-sm-2">
       <button id="btn_close_proj_screening" class="form-control btn btn-warning btn-lg" type="button">
         <h5> <i class="fa fa-chevron-circle-left fa-lg" ></i> ย้อนกลับ </h5>
       </button>
     </div>
  </div>


<div class="card div-uid-screen" id="div_choose_project">
  <div class="card-body">
    <h5 class="card-title"><i class="fa fa-folder fa-lg" ></i> กรุณาเลือกโครงการที่เข้าคัดกรอง</h5>
    <div id="div_choose_project_detail">
       <center>รอสักครู่</center>
    </div>
  </div>
</div>

<div class="card div-uid-screen" id="div_project_screen_info">
  <div class="card-body">
    <div class="row">
       <div class="col-sm-4">
<!--
         <div class="card my-1 div_auth" id="div_screen_to_enroll">
           <div class="card-body">
             <h5 class="card-title"><i class="fa fa-first-aid fa-lg" ></i> ลงทะเบียนเข้าโครงการ </h5>
             <div class="my-1" >
               <div class="mt-1">
                 <i class="fa fa-check-square fa-lg" ></i> ผ่านการคัดกรอง <br>
                 <button id="btn_screening_pass" class="form-control btn btn-success" type="button">ลงทะเบียนเข้าโครงการ</button>
               </div>
               <div class="mt-4">
                 <i class="fa fa-times-circle fa-lg" ></i> ไม่ผ่านการคัดกรอง <br>
                 <button id="btn_screening_fail" class="form-control btn btn-danger" type="button">ไม่ผ่านการคัดกรอง</button>
               </div>
             </div>

           </div>
         </div>


         <div class="card my-1">
           <div class="card-body">
               <label for="visit_note">Screen Note :</label>
               <textarea class="form-control save-data" id="visit_note" rows="4"  data-title='Visit Note' ></textarea>
           </div>
         </div>
            -->

            <div class="card my-1">
              <div class="card-header"><b><i class="fa fa-file-alt text-info"></i> รายละเอียดโครงการ</b></div>
              <div class="card-body" >
                <span id="screen_proj_txt">xx</span>
              </div>
            </div>

       </div>

       <div class="col-sm-8" >
             <h4><i class="fa fa-file-medical fa-lg text-danger" ></i> แบบฟอร์มคัดกรอง </h4>
             <div class="my-1" id="div_screen_form">
               <table id="tbl_screen_form_list" class="table table-bordered table-sm table-striped table-hover">
                   <thead>
                     <tr>
                       <th></th>
                       <th>แบบฟอร์ม</th>
                       <th>ทำแล้ว?</th>
                     </tr>
                   </thead>
                   <tbody>

                   </tbody>
               </table>

             </div>

       </div>
    </div>

  </div>
</div> <!-- div_project_screen_info -->




<div class="card div-uid-screen" id="div_project_screen_form">
  <div  class="card-header bg-primary text-white" id="div_project_screen_form_title">
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

    <div id="div_project_screen_form_data">
      รอสักครู่
    </div>

  </div>
</div> <!-- div_project_screen_form -->


<div id="div_enroll_uid" class="div-uid-screen" >

</div>

<input type="hidden" id="is_form_complete" value=''>

<script>
var proj_txt_POC='โครงการประเมินความเป็นไปได้ของการใช้ชุดตรวจ ณ จุดดูแลผู้ป่วย สำหรับโรคติดต่อทางเพศสัมพันธ์และปริมาณเชื้อเอชไอวีในเลือด ในศูนย์สุขภาพชุมชนสำหรับชายมีเพศสัมพันธ์กับชายและสาวประเภทสองในประเทศไทย';
var proj_txt_PrEP='โครงการ PrEP ในศูนย์สุขภาพชุมชน';

$(document).ready(function(){
  initScreenData();

  $("#btn_close_proj_screening").click(function(){
     showUIDDiv("uid_info");

     // if there is project updated , reload list
     if($("#data_update_proj").val() == "Y") searchData_uid();

  }); // btn_close_proj_screen

  $("#btn_close_form").click(function(){
     showUIDDivScreen("project_screen_info");

     // if there is project updated , reload list
     if($("#data_update_visit").val() == "Y") selectProjScreenForm();

  }); // btn_close_proj_screen

  $("#btn_screening_pass").click(function(){
     if($('#is_form_complete').val() == "Y") {
       setDataChangeProj(); // reload project list in dashboard
       enrollToProject();
     }
     else $.notify("แบบฟอร์มกรอกไม่ครบ");
  }); // enrollToProject
  $("#btn_screening_fail").click(function(){
    if($('#visit_note').val().trim() != "") screenFailUID();
    else $.notify("กรุณากรอก Screen Note");
     //notEnrollToProject();
  }); // enrollToProject

});


function initScreenData(){

  clearDataChangeProj();
  clearDataChangeVisit();

  $('#screen_proj_name').html($('#cur_proj_name').val());
  $('#screen_proj_date').html("["+changeToThaiDate($('#cur_screen_date').val())+"]");
//alert("u_mode_screen "+$("#u_mode_screen").val());
  if($("#u_mode_screen").val() == "new"){
    showUIDDivScreen("choose_project");
    selectProjScreenChoice();
  }
  else if($("#u_mode_screen").val() == "update"){
    showUIDDivScreen("project_screen_info");
    selectProjScreenForm();
    //selectProjScreenInfo();
  }
}

function selectProjScreenChoice(){
  var aData = {
            u_mode:"sel_proj_screen",
            uid:$('#cur_uid').val()
  };
  save_data_ajax(aData,"w_user/db_proj_screen.php",selectProjScreenChoiceComplete);
}

function selectProjScreenChoiceComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
      var datalist = rtnDataAjax.datalist;
      if(datalist.length > 0){
        var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
          var dataObj = datalist[i];
          if(dataObj.is_enable == '1'){
            txt_row += '<button class="btn btn-primary btn-lg btn-block" type="button" onclick="addProjScreening(\''+dataObj.proj_id+'\',\''+dataObj.proj_name+'\')""><i class="fa fa-file-medical fa-lg"></i> '+dataObj.proj_name+'</button>';
          }
          else{ // disable
            txt_row += '<button class="btn btn-secondary btn-lg btn-block" type="button" ><i class="fa fa-file-medical fa-lg" disable></i> '+dataObj.proj_name+' (รอการปรับปรุง)</button>';
          }

        }//for
        $('#div_choose_project_detail').html(txt_row);
    }//if
    else{
      $('#div_choose_project_detail').html("ไม่มีโครงการให้เลือกในขณะนี้");
      $.notify("ไม่มีโครงการให้เลือกในขณะนี้","info");
    }
  }
}

function addProjScreening(projectID, projectName){
  $('#cur_proj_name').val(projectName);
  var aData = {
            u_mode:"add_proj_screen",
            uid:$('#cur_uid').val(),
            proj_id:projectID
  };
  save_data_ajax(aData,"w_user/db_proj_screen.php",addProjScreeningComplete);
}

function addProjScreeningComplete(flagSave, rtnDataAjax, aData){
  setDataChangeProj();
  //alert("flag save is : "+flagSave);
  if(flagSave){
//สอ280835
    if(rtnDataAjax.is_success == "Y"){
      $('#screen_proj_name').html($('#cur_proj_name').val());
      $('#cur_proj_id').val(aData.proj_id);
      $('#cur_screen_date').val(rtnDataAjax.screen_date);
      $('#screen_proj_date').html("["+changeToThaiDate($('#cur_screen_date').val())+"]");
      $('#cur_visit_id').val('SCRN');


      showUIDDivScreen("project_screen_info");
      selectProjScreenForm();
    }
    else{
      $('#cur_proj_name').val("");
      $.notify("เกิดข้อผิดพลาด","error");
    }
  }
}


function selectProjScreenForm(){
  var aData = {
            u_mode:"sel_proj_visit_form",
            uid:$('#cur_uid').val(),
            proj_id:$('#cur_proj_id').val(),
            visit_date:$('#cur_screen_date').val(),
            visit_id:"SCRN"
  };

  //alert("show "+aData.uid+"/"+aData.proj_id+"/"+aData.visit_date+"/");
  save_data_ajax(aData,"w_user/db_proj_form.php",selectProjScreenFormComplete);
}

function selectProjScreenFormComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  clearDataChangeVisit();
  if(flagSave){
      var datalist = rtnDataAjax.datalist;

      if(datalist.length > 0){
        var txt_row = "";
        var form_done = "";

        var k=0;
        var i=0;
        for (i = 0; i < datalist.length; i++){
          var dataObj = datalist[i];

          //form_done = (dataObj.form_done == 'Y')?'<i class="fa fa-check-circle fa-lg text-success" ></i>':'<i class="fa fa-times-circle fa-lg text-danger" ></i>';
          if(dataObj.form_done == 'Y'){
            form_done ='<i class="fa fa-check-circle fa-lg text-success" ></i>';
            k++;
          }
          else{
            form_done ='<i class="fa fa-times-circle fa-lg text-danger" ></i>';
          }


          txt_row += '<tr class="r_screen_form">';
          //txt_row += ' <td><button class="btn btn-primary" type="button" onclick="openUIDForm(\''+dataObj.uid+'\',\''+dataObj.form_id+'\',\''+dataObj.visit_date+'\')""><i class="fa fa-user"></i> '+dataObj.uid+'</button></td>';
          txt_row += ' <td><i class="fa fa-first-aid fa-lg text-dark" ></i></td>';
          txt_row += ' <td>'+dataObj.form_name+'</td>';
          txt_row += ' <td align="center">'+form_done+'</td>';
          txt_row += ' <td align="center"><button class="btn btn-primary btn-block" type="button" onclick="openUIDFormScreen(\''+dataObj.form_id+'\',\''+dataObj.form_name+'\')""><i class="fa fa-folder-open"></i> เปิด</button></td>';
          txt_row += '</tr">';
        } //for

        $('.r_screen_form').remove(); // row course taken
        $('#tbl_screen_form_list > tbody:last-child').append(txt_row);

        if(i == k) $('#is_form_complete').val("Y");
        else  $('#is_form_complete').val("N");


    }//if
    else{
      $('#div_choose_project_detail').html("ไม่มีฟอร์มใส่ข้อมูลให้เลือกในขณะนี้");
      $.notify("ไม่มีฟอร์มใส่ข้อมูลให้เลือกในขณะนี้","info");
    }

    $('#screen_proj_txt').html(eval('proj_txt_'+$('#cur_proj_id').val()));

  }

}





function openUIDFormScreen(formID, formName){

//alert("form_id "+formID);
    $('#cur_visit_id').val("SCRN");
    var link = "visit_form/f_form_proj.php?";
    link += "uid="+$('#cur_uid').val(); // uid
    link += "&form_id="+formID; // form id
    link += "&visit_date="+$('#cur_screen_date').val(); // screen date
    link += "&visit_id="+$('#cur_visit_id').val(); // visit id
    link += "&proj_id="+$('#cur_proj_id').val(); // project id
    link += "&group_id="+$('#cur_group_id').val(); // group id (just screening/ no group )

  //link += "&group_id="+$('#cur_group_id').val(); // group id (just screening/ no group )

  //alert("openUIDForm "+formID+"/"+link);
  $('#div_project_screen_form_data').html("รอสักครู่");
  $('#form_title').html(formName);


  $("#div_project_screen_form_data").load(link, function(){
      showUIDDivScreen("project_screen_form");
      //$('#div_project_screen_form_title').show();
  });

}


function showUIDDivScreen(choice){
  $('.div-uid-screen').hide();
  $('#div_'+choice).show();
}

function enrollToProject(){
  //alert("enrollToProject");
  $("#div_enroll_uid").load("w_user/proj_enroll.php", function(){
      showUIDDivScreen("enroll_uid");
  });
}

// screen fail
function screenFailUID(){
  var aData = {
            u_mode:"screen_fail",
            uid:$('#cur_uid').val(),
            proj_id:$('#cur_proj_id').val(),
            visit_note:$('#visit_note').val(),
            visit_date:$('#cur_screen_date').val()
  };
  save_data_ajax(aData,"w_user/db_proj_screen.php",screenFailUIDComplete);
}

function screenFailUIDComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
      $('#div_screen_to_enroll').hide();
      setDataChangeProj();
  }
}


</script>
