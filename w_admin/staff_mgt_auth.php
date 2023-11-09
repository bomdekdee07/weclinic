
<?
$txt_require_data = "<span class='text-danger'>*</span>";
?>

<div class="card" id="div_staff_data_detail">
  <div  class="card-header bg-primary text-white">
    <div class="row ">
     <div class="row ">
       <div class="col-md-11">
         <h4><i class="fa fa-user fa-lg" ></i> <span id="staff_title"> </span></h4>
       </div>
       <div class="col-md-1">
         <button id="btn_close_staff_data" class="form-control btn btn-light" type="button">
           <i class="fa fa-times-circle fa-lg" ></i> ปิด
         </button>
       </div>
     </div>
  </div>
  <div class="card-body">

  </div>
</div> <!-- div_staff_data_detail -->



<input type="hidden" id="is_form_complete" value=''>

<script>
$(document).ready(function(){
  initStaffData();

  $("#btn_close_staff_data").click(function(){
     showStaffMainDiv("staff_list");
  }); // btn_close_staff_data



  $("#btn_save_staff_data").click(function(){


     if($('#is_form_complete').val() == "Y") {
       setDataChangeProj(); // reload project list in dashboard
       enrollToProject();
     }
     else $.notify("แบบฟอร์มกรอกไม่ครบ");
  }); // enrollToProject


});


function initStaffData(){
  selectStaffData();
}

function selectStaffData(){
  var aData = {
      u_mode:"select_data_staff",
      s_id:$('#cur_s_id').val()
  };
  save_data_ajax(aData,"w_admin/db_staff_mgt.php",selectStaffDataComplete);
}

function selectStaffDataComplete(flagSave, rtnDataAjax, aData){
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

  //alert("show "+aData.uid+"/"+)
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
          txt_row += ' <td align="center"><button class="btn btn-primary btn-block" type="button" onclick="openUIDForm(\''+dataObj.form_id+'\',\''+dataObj.form_name+'\')""><i class="fa fa-folder-open"></i> เปิด</button></td>';
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
  }
}





function openUIDForm(formID, formName){
  //$('#div_project_screen_form_title').hide();

  var link = "visit_form/x_"+formID+".php?";
  link += "uid="+$('#cur_uid').val(); // uid
  link += "&visit_date="+$('#cur_screen_date').val(); // screen date
  link += "&visit_id=SCRN"; // visit id screen
  link += "&proj_id="+$('#cur_proj_id').val(); // project id
  //link += "&group_id="+$('#cur_group_id').val(); // group id (just screening/ no group )

  //alert("openUIDForm "+formID+"/"+uid+"/"+visit_date);
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
