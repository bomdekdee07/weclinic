<div id='div_dashboard' class='div-uic'>
  <div class="row mb-4">

    <div class="col-sm-3">
      <div>
      <div class="input-group mb-3">
        <input type="text" id="txt_search_uic" class="form-control v-no-blank" data-title="UIC" placeholder="กรอก UIC" aria-label="UIC" aria-describedby="UIC">
        <div class="input-group-append">
          <button class="btn btn-primary" type="button" id="btn_search_uic"><i class="fa fa-search" ></i> ค้นหา</button>
        </div>
      </div>
      </div>
      <div class="my-4">
        <button class="btn btn-warning form-control" type="button" id="btn_new_uic" ><i class="fa fa-folder-plus fa-lg" ></i> ลงทะเบียน UIC ใหม่</button>
      </div>
    </div>

    <div class="col-sm-9">
      <div class="card">
        <h5 class="card-header">ตารางนัดหมาย</h5>
        <div class="card-body">
          <table id="tbl_form_list" class="table table-bordered table-sm table-striped table-hover">
              <thead>
                <tr>

                  <th>Visit Schedule Date</th>
                  <th>UIC</th>
                  <th>Project</th>
                  <th>PID</th>

                </tr>
              </thead>
              <tbody>

              </tbody>
          </table>

        </div>
      </div>

    </div>
  </div>
</div>

<div id="div_uic_info" class='div-uic'>
  <div class="my-4">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-11">
            <h1>UIC: <b><span id="title_uic_id">UIC_CODE x</span></b></h1>
         </div>
         <div class="col-sm-1">
           <button type="button" id="btn_close_uic_info" class="close " aria-label="Close">
                <span aria-hidden="true" class="text-danger"><b><h2>&times;</h2></b></span>
           </button>
        </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row" >
          <div class="col-sm-3">

            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><i class="fa fa-folder fa-lg" ></i> ข้อมูล UIC</h5>
                <p class="card-text" id="info_uic">UIC INFO</p>
              </div>
            </div>

            <div class="card my-1">
              <div class="card-body">
                <h5 class="card-title"><i class="fa fa-first-aid fa-lg" ></i> คัดกรองเข้าโครงการใหม่</h5>
                <div class="my-1" id="div_sel_proj_reg">
                  <div class="row ">
                    <div class="col-sm-8 px-0">
                      <select id="sel_proj_screen" class="form-control form-control-sm" >
                       <option value='POC' selected >Point of Care</option>
                      </select>
                    </div>
                    <div class="col-sm-4 px-1">
                      <button id="btn_proj_screening" class="form-control form-control-sm btn btn-info btn-sm" type="button"> OK</button>
                    </div>
                  </div>



                </div>
              </div>
            </div>

          </div>
          <div class="col-sm-9">
            <table id="tbl_uic_proj" class="table table-bordered table-sm table-striped table-hover">
                <thead>
                  <tr>

                    <th>PID</th>
                    <th>โครงการที่เข้า</th>
                    <th>วันลงทะเบียน</th>
                    <th>Visit ล่าสุด</th>
                    <th>Visit ถัดไป</th>

                  </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
          </div>
        </div>


      </div>
    </div>
  </div>

</div> <!-- end div_uic-->

<div id ="div_uic_screen" class='div-uic'>

</div>
<div id ="div_uic_enroll" class='div-uic'>

</div>

<input type="hidden" id="cur_uic" >
<input type="hidden" id="cur_proj_id" >
<input type="hidden" id="cur_group_id" >


<script>
$(document).ready(function(){
  $('#txt_search_uic').val('สอ280835');
//$("#div_dashboard").hide();

  showUICDiv("dashboard");



  var opt_proj=[];
  opt_proj['POC'] = {proj_id:'POC',proj_name:'Point of Care'};
  opt_proj['POC2'] = {proj_id:'POC2',proj_name:'Point of Care 2'};

  $("#btn_search_uic").click(function(){
     searchData_UIC();
  }); // btn_search_uic

  $("#txt_search_uic").on("keypress",function (event) {
    if (event.which == 13) {
      searchData_UIC();
    }
  });

  $("#btn_new_uic").click(function(){

  }); // btn_new_uic

  $("#btn_proj_screening").click(function(){
    addProjScreening();
  });
  $("#btn_close_uic_info").click(function(){
    clearData_UIC();
    showUICDiv("dashboard");
  });

});


function projScreen_UIC(){
  var proj_id = $("#sel_proj_screen").val();
  $('#cur_proj_id').val(proj_id);

  $("#div_uic_screen").load("w_user/proj_screen.php?u_mode=new", function(){
      showUICDiv("uic_screen");
  });


}



function addProjScreening(){
  var aData = {
            u_mode:"add_proj_screen",
            uic:$('#cur_uic').val(),
            proj_id:$('#sel_proj_screen').val()
  };
  save_data_ajax(aData,"w_user/db_proj_screen.php",addProjScreeningComplete);
}

function addProjScreeningComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
//สอ280835
    if(rtnDataAjax.is_success == "Y"){
      $('#cur_proj_id').val($("#sel_proj_screen").val());
      $("#div_uic_screen").load("w_user/proj_screening.php", function(){
          showUICDiv("uic_screen");
      });
    }
    else{
      $.notify("เกิดข้อผิดพลาด","error");
    }
  }
}



function searchData_UIC(){

  if(validateInput("div_dashboard")){
    if($('#txt_search_uic').val().trim().length != 8){
      $('#txt_search_uic').notify("UIC ไม่ถูกต้อง","warn");
    }
    else{//valid
      var aData = {
                u_mode:"select_data_uic",
                uic:$('#txt_search_uic').val()
      };
      save_data_ajax(aData,"w_user/db_uic_data.php",searchData_UICComplete);

    }
    clearData_UIC();
  }
}



function searchData_UICComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
//สอ280835

    if(rtnDataAjax.uic_data.name != undefined ){
      $('#title_uic_id').html(rtnDataAjax.uic_data.uic);
      var txt_row = "<b>"+rtnDataAjax.uic_data.name+"</b><br>"+rtnDataAjax.uic_data.address+"<br>Tel: "+rtnDataAjax.uic_data.tel+"<br>Email: "+rtnDataAjax.uic_data.email;
      $('#info_uic').html(txt_row);
      txt_row="";
      if(rtnDataAjax.proj_list.length > 0){
        var enroll_date = "";
        var btn_pid = "";
        var datalist = rtnDataAjax.proj_list;
          for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            btn_pid = "";
            if(dataObj.uic_status == 1){ // already enroll
              enroll_date = changeToThaiDate(dataObj.enroll_date);
              btn_pid = '<button class="btn btn-primary" type="button" onclick="goVisitList(\''+dataObj.proj_id+'\')""><i class="fa fa-user"></i> '+dataObj.pid+'</button>';
            }
            else if(dataObj.uic_status == 0){ // in screening process
              enroll_date = "";
              btn_pid = '<button class="btn btn-warning" type="button" onclick="goScreening(\''+dataObj.proj_id+'\')""><i class="fa fa-user"></i> คัดกรองอยู่</button>';
            }

            txt_row += '<tr class="r_uic_proj">';
            txt_row += ' <td>'+btn_pid+'</td>';
            txt_row += ' <td>'+dataObj.proj_name+'</td>';
            txt_row += ' <td>'+enroll_date+'</td>';
            txt_row += ' <td>'+enroll_date+'</td>';
            txt_row += ' <td>'+enroll_date+'</td>';
            //txt_row += ' <td>'+dataObj.visit_date+'</td>';
            txt_row += '</tr">';
          }//for
        $('.r_uic_proj').remove(); // row uic proj summary
        $('#tbl_uic_proj > tbody:last-child').append(txt_row);

      }

      var txt_row = "";

      $('#cur_uic').val(rtnDataAjax.uic_data.uic);
      showUICDiv("uic_info");

    }
    else{
      $('#txt_search_uic').notify("UIC นี้ไม่พบประวัติ","warn");
    }



  }
}

function clearData_UIC(){
  $('#title_uic_id').html("");
  $('#info_uic').html("");
  $('#cur_uic').val("");
  $('#cur_proj_id').val("");
  $('#cur_group_id').val("");
  $('.r_uic_proj').remove(); // row uic proj summary
}

function showUICDiv(choice){
  $('.div-uic').hide();
  $('#div_'+choice).show();

}

</script>
