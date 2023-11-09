
<?
$txt_require_data = "<span class='text-danger'>*</span>";
?>

<div class="card">
  <div  class="card-header ">
   <h5><i class="fa fa-user fa-lg" ></i> <b>ข้อมูลผู้รับบริการ Retrospective</b></h5>
  </div>
  <div class="card-body">


    <div class="row">
       <div class="col-sm-6 px-2">
         <div class="row mt-1">
            <div class="col-sm-5 px-1">
              <label for="hos_f_name">ชื่อ:</label>
              <input type="text" id="hos_f_name" class="form-control form-control-sm save-data v-no-blank" data-title="ชื่อ">
            </div>
            <div class="col-sm-5 px-1">
              <label for="hos_s_name">นามสกุล:</label>
              <input type="text" id="hos_s_name" class="form-control form-control-sm save-data v-no-blank" data-title="นามสกุล">
            </div>

        </div>
        <div class="row mt-1">
          <div class="col-sm-5 px-1">
            <label for="hos_birth_date">วันเกิด: วว/ดด/ปปปป (ปี พ.ศ.) <span class="text-danger"><b>*</b></span></label>
            <input type="text" id="hos_birth_date" class="form-control form-control-sm save-data v_date" data-title="วันเกิด / Birth Date">
          </div>
           <div class="col-sm-5 px-1">
             <label for="ul_hos">UL:</label>
             <div class="input-group mb-3" id="ul_hos">

               <input type="text" id="hos_ul" class="form-control form-control-sm " >
               <div class="input-group-append">
                 <button id="btn_gen_new_hos_ul" class="form-control form-control-sm btn btn-info" type="button">
                   <i class="fa fa-sync-alt fa-lg" ></i> Generate
                 </button>
               </div>
             </div>
            </div>

        </div>
        <div class="mt-0 px-1 py-1" style="background-color:#EEE;">
          <span class="text-danger"><b>*</b></span>
          <small>
           <b>ในกรณีที่ทราบเพียงปีเกิด</b> กรุณากรอกวันเกิดเป็น 01/01/yyyy (yyyy แทนปีเกิด) และเมื่อ Generate UIC แล้ว กรุณาแทนค่า NANA ที่ต่อจาก 2 อักษรแรก เป็น <b>NNNN</b>
         </small>
        </div>

        <div class="row mt-1">
          <div class="col-sm-5 px-1">
            <label for="hos_hn">HN:</label>
            <input type="text" id="hos_hn" class="form-control form-control-sm save-data" data-title="HN">
          </div>
       </div>

       </div>
       <div class="col-sm-6 px-2">
         <div class="row mt-1">
            <div class="col-sm-12">
              <label for="hos_remark">หมายเหตุ:</label>
              <textarea class="form-control form-control-sm save-data" id="hos_remark" rows="8"  data-title='หมายเหตุ'></textarea>
            </div>
        </div>
       </div>
    </div>


   </div>
   <div class="card-footer ">
     <button id="btn_save_hos_pid_info" class="form-control form-control-sm btn btn-primary" type="button">
       <i class="fa fa-save fa-lg" ></i> SAVE
     </button>
   </div>
</div> <!-- patient_personal_data -->


<script>

var u_mode_pid_personal = "add_new_pid";
$(document).ready(function(){


clearHosPidPersonalData();
//addData_hos_ul();


  var currentDate = new Date();
  currentDate.setYear(currentDate.getFullYear() + 543);
/*
    $("#hos_birth_date").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy',
      onSelect: function(date) {
        $("#hos_birth_date").addClass('filled');
      }
    });
    */
    $('#hos_birth_date').datepicker("setDate",currentDate );


    $("#hos_birth_date").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});
    $("#hos_citizen_id").mask("9999999999999",{placeholder:"#############"});
  //  $('#hos_birth_date').datepicker("setDate",currentDate );

  $("#hos_birth_date").focusout(function(){
     if($("#hos_birth_date").val().trim() != ""){

       if(!validateDate($("#hos_birth_date").val().trim())){
          $("#hos_birth_date").notify("วันที่ไม่ถูกต้อง", "error");
       }

     }
  }); //savePersonalData_pid()

  $("#btn_save_hos_pid_info").click(function(){
     savePersonalData_pid();
  }); //savePersonalData_pid()

  $("#btn_close_hos_pid").click(function(){
    closeHosPid();

  }); // btn_new_hos_ul

  $("#btn_gen_new_hos_ul").click(function(){
    getNewHosUL();
  }); // btn_gen_new_hos_ul


  $("#hos_ul").focus(function(){
    if($("#hos_ul").val() == ""){
      getNewHosUL();
    }
  }); // btn_search_hos_ul


});

function getNewHosUL(){
  var thai_birthDate = $("#hos_birth_date").val();
  var fname = $("#hos_f_name").val().trim();
  var lname = $("#hos_s_name").val().trim();

  if(thai_birthDate != "" && fname != "" && lname != ""){
    $("#hos_ul").val(generateHosUL(thai_birthDate, fname, lname));
  }
  else{
    myModalContent("Error",
    "ข้อมูลไม่ครบ กรุณากรอก ชื่อ-นามสกุล และวันเกิด ก่อนกด GENERATE",
    "info");
  }

}



function addData_hos_ul(){
//  alert("add addData_hos_ul 1");
    clearHosPidPersonalData();
    u_mode_pid_personal = "add_new_pid";
    $("#hos_ul").val('');
/*
    $("#hos_ul").prop('disabled', false);
    $("#btn_gen_new_hos_ul").prop('disabled', false);
*/
}



function clearHosPidPersonalData(){
   $("#div_hos1 .save-data").val("");
   $("#div_hos1 .hos_citizen").val("");

/*
   $("#hos_ul").prop('disabled', true);
   $("#btn_gen_new_hos_ul").prop('disabled', true);
*/


  // alert("clear all ");
}

function closeHosPid(){
  clearHosPidPersonalData();
  showMainDiv("dashboard");
}

function getPersonalData_pid(){
  //alert("getPersonalData_pid "+cur_hos_pid);

  var aData = {
            u_mode:"get_pid_personal_data",
            pid:cur_hos_pid
  };
  save_data_ajax(aData,"w_proj_SDHOS/db_hos_pid_retro.php",getPersonalData_pidComplete);

}
function getPersonalData_pidComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is getPersonalData_pidComplete : "+flagSave);
  if(flagSave){
      var dataObj = rtnDataAjax.data_obj;
      if(dataObj.pid !=""){

        $('#hos_ul').val(dataObj.ul);
        $('#hos_f_name').val(dataObj.f_name);
        $('#hos_s_name').val(dataObj.s_name);

        dataObj.birth_date = (!dataObj.birth_date)?"":dataObj.birth_date;
        $('#hos_birth_date').val(changeToThaiDate(dataObj.birth_date));


        $('#hos_hn').val(dataObj.hn);
        $('#hos_remark').val(dataObj.remark);

        u_mode_pid_personal = "update_pid";
        after_goHosMnu();
      }


  }
}


function savePersonalData_pid(){
  var hos_ul = $("#hos_ul").val();
  var hos_citizen_id = "";
  if(hos_ul.length != 8){
    $("#hos_ul").notify("กรุณากรอก UL","warn");
    return;
  }



  if(validateInput("div_hos1")){

    var aData = {
              u_mode:u_mode_pid_personal,
              pid:cur_hos_pid,
              ul:hos_ul,
              f_name:$('#hos_f_name').val().trim(),
              s_name:$('#hos_s_name').val().trim(),
              birth_date:changeToEnDate($('#hos_birth_date').val()),

              hn:$('#hos_hn').val().trim(),
              remark:$('#hos_remark').val().trim()
    };

    save_data_ajax(aData,"w_proj_SDHOS/db_hos_pid_retro.php",savePersonalData_pidComplete);

  }

}
function savePersonalData_pidComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

    if(u_mode_pid_personal == 'add_new_pid'){
      cur_hos_pid = rtnDataAjax.pid;
      cur_hos_ul = aData.ul;
      cur_hos_pid_create_date = rtnDataAjax.create_date;
/*
      $("#hos_ul").prop('disabled', true);
      $("#btn_gen_new_hos_ul").prop('disabled', true);
*/
      u_mode_pid_personal = 'update_pid';
      addNewRowPID(rtnDataAjax.pid, aData.ul, aData.f_name+" "+aData.s_name, aData.hn,cur_hos_pid_create_date);

      refreshTitleHosPID();
    }
    else if(u_mode_pid_personal == 'update_pid'){
      updateRowPID(aData.pid, aData.ul, aData.f_name+" "+aData.s_name, aData.hn,cur_hos_pid_create_date);
      refreshTitleHosPID();
    }


  }
}

// ul management
function generateHosUL(thai_birthDate, fname, lname){
  var newPID = "";
  newPID += getFirstThaiChar(fname);
  newPID += getFirstThaiChar(lname);
  newPID += getHosBirthEncode(thai_birthDate);

  return newPID;
}

function getFirstThaiChar(str){
  var rtnStr = "";
  var i;

  for(i=0; i< str.length; i++){
    if(check_first_char(str.substring(i,i+1))){
      rtnStr = str.substring(i,i+1);
      break;
    }
  }//for

  return rtnStr;
}
function check_first_char(str){
    str = str.substring(0,1);
    return /[^ะัาำิีึืฺุูเแโใไๅๆ็่้๊๋์]/.test(str);
}

function getHosBirthEncode(birthDate){
  var arrDate = birthDate.split("/");
  var birth_encode = "";
  birth_encode += arrDate[0].substring(0, 2);
  birth_encode += arrDate[1].substring(0, 2);
  birth_encode += arrDate[2].substring(2, 4);

  // replace all number to alphabet
  birth_encode = birth_encode.replace(/1/g, "A");
  birth_encode = birth_encode.replace(/2/g, 'B');
  birth_encode = birth_encode.replace(/3/g, "C");
  birth_encode = birth_encode.replace(/4/g, "D");
  birth_encode = birth_encode.replace(/5/g, "E");
  birth_encode = birth_encode.replace(/6/g, "F");
  birth_encode = birth_encode.replace(/7/g, "G");
  birth_encode = birth_encode.replace(/8/g, "H");
  birth_encode = birth_encode.replace(/9/g, "M");
  birth_encode = birth_encode.replace(/0/g, "N");

  return birth_encode;
}


</script>
