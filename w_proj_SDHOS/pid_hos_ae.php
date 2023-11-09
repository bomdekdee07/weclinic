


  <div id="div_ae_list" class="div-hos-ae">
    <div class="row my-1 ">
       <div class="col-sm-10 py-1 ">
         <b><i class="fa fa-procedures fa-lg"></i> Adverse Event (AE)</b>
         <button id="btn_back_retention" class="btn btn-warning" type="button">
            <i class="fa fa-stethoscope fa-lg"></i> Retention <span id="back_retention_date"></span>
         </button>
       </div>
       <div class="col-sm-2 py-1">
         <button id="btn_ae_add" class="form-control form-control-sm btn btn-success" type="button">
            <i class="fa fa-plus fa-lg"></i> เพิ่ม AE
         </button>
       </div>
       <!--
       <div class="col-sm-2 py-1">
         <button id="btn_ae_save" class="form-control form-control-sm btn btn-success" type="button">
            บันทึก
         </button>
       </div>
       <div class="col-sm-7 py-1">

         <select id="sel_hos_pid_ae" class="form-control form-control-sm " >
          <option value='0' class="text-secondary" >เลือกส่วนที่ต้องการใน ae</option>
          <option value='character_title' ><i class="fa fa-user fa-lg" ></i> ข้อมูลผู้ใช้บริการ (Subject characteristic)</option>
          <option value='pe_title' ><i class="fa fa-notes-medical fa-lg" ></i> การตรวจ และประเมินด้านร่างกาย (Physical Exam)</option>
          <option value='lab_title' ><i class="fa fa-stethoscope fa-lg" ></i> การประเมินทางห้องปฏิบัติการ (Laboratory)</option>
          <option value='artinitial_title' ><i class="fa fa-procedures fa-lg" ></i> การจ่ายยาต้านไวรัส (ART Initiation)</option>
          <option value='referral_title' ><i class="fa fa-procedures fa-lg" ></i> การส่งต่อ หรือ ย้ายสิทธิ ผู้ใช้บริการ</option>
        </select>
       </div>
     -->
    </div>

    <div class="mt-1">
      <table id="tbl_hos_pid_ae_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th></th>
              <th>ลำดับที่</th>
              <th>ข้อมูล</th>
              <th>Visit Date</th>
              <th>อาการ/โรคที่เกิด</th>
              <th>วันเริ่มอาการ</th>
              <th>วันที่หาย</th>

              <th>การรักษา</th>
              <th>ผลการรักษา</th>

              <th>ตรวจ VL</th>
              <th>ตรวจ CD4</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
      </table>
    </div>

  </div>
  <div id="div_ae_detail" class="div-hos-ae">
    <div class="row my-1 bg-info">
       <div class="col-sm-11 py-1 text-white">
         <b><i class="fa fa-stethoscope fa-lg"></i> AE Form</b>
       </div>
       <div class="col-sm-1 py-1">
         <button id="btn_ae_form_close" class="form-control form-control-sm btn btn-danger" type="button">
            <i class="fa fa-times fa-lg"></i> ปิด
         </button>
       </div>
    </div>
    <div id="div_ae_form" class="my-1 div-hos-load">
detail
    </div>

  </div>


<script>

$(document).ready(function(){

  showAE("list");

  $("#btn_ae_add").click(function(){
    addHosAE();
  }); //menu form section
  $("#btn_ae_form_close").click(function(){

    if($('#ae_symptom').val() != ''){ // update data mode
      var flag_change = checkFormDataChange();
      if(flag_change){
        if(typeof hos_sc_id  === 'undefined' ){ // if hospital staff
          if(confirm("คำเตือน: ข้อมูลมีการเปลี่ยนแปลง ท่านต้องการที่จะบันทึกข้อมูลหรือไม่ ?")){
            saveFormData(cData);
            return;
          }
        }

      }
    }



    if(is_update_form == 1) getAEList_pid();

    showAE("list");
  }); //menu form section



});
function addHosAE(){
  u_mode_ae = "add";
  getAEForm_pid(''); // add new
}
// update ae
function getFormDetail_ae(seq_no){
//  alert("getFormDetail_ae "+visit_date);
  u_mode_ae = "update";
  getAEForm_pid(seq_no);
}


function getAEForm_pid(seq_no){
  //alert("getAEForm_pid 1 ");
  var link = "w_proj_SDHOS/pid_hos_ae_form.php?";
  link += "pid="+cur_hos_pid; // pid
  link += "&seq_no="+seq_no; // seq_no
  link += "&visit_date="+ae_collect_date; // visit_date

  //alert("openUIDForm "+formID+"/"+uid+"/"+visit_date);
  //alert("openUIDFormxx "+link);

//  $('#div_ae_form').html("รอสักครู่");
  $('.div-hos-load').html("รอสักครู่");
  $("#div_ae_form").load(link, function(){
      //alert("load div_ae_form ddddddd");
      showAE("detail");
  });
//showAE("detail");
}

function getAE_pid(){
  //alert("getAE_pid 1 "+ae_collect_date);
    if(ae_collect_date == ""){
      getAEList_pid();
      $("#btn_back_retention").hide();
      $("#btn_ae_add").hide();
    }
    else{
      getAEListRetention_pid();
      $("#btn_back_retention").show();
      $("#back_retention_date").html(changeToThaiDate(ae_collect_date));
      $("#btn_ae_add").show();

      if(typeof hos_sc_id  !== 'undefined' ){
        if(hos_sc_id.substring(0, 1) != "H" ){
          $("#btn_ae_add").hide();
        }
      }

    }

    showAE("list");
    after_goHosMnu();
  }

 function getAEList_pid(){
   //alert("getAE_pid 1 ");
     var aData = {
               u_mode:"select_hos_pid_ae",
               pid:cur_hos_pid

     };
     save_data_ajax(aData,"w_proj_SDHOS/db_hos_pid.php",getAEList_pidComplete);

 }

 function getAEListRetention_pid(){
   //alert("getAE_pid 1 ");
     var aData = {
               u_mode:"select_hos_pid_ae",
               pid:cur_hos_pid,
               collect_date:ae_collect_date

     };
     save_data_ajax(aData,"w_proj_SDHOS/db_hos_pid.php",getAEList_pidComplete);

 }

   function getAEList_pidComplete(flagSave, rtnDataAjax, aData){
     //alert("flag save is getPersonalData_pidComplete : "+flagSave);
     if(flagSave){

       txt_row="";
       if(rtnDataAjax.datalist.length > 0){
         var datalist = rtnDataAjax.datalist;
           for (i = 0; i < datalist.length; i++) {
             var dataObj = datalist[i];
             var txt_del_ae = '<button class="btn btn-sm btn-danger" type="button" onclick="delete_ae(\''+dataObj.seq_no+'\', \''+dataObj.ae_date+'\', \''+(i+1)+'\')"><i class="fa fa-times"></i> ลบ</button>';

             var txt_ae1 = "";
             if(dataObj.t0 == '1') txt_ae1 += "ไม่จำเป็นต้องดำเนินการ";
             if(dataObj.t1 == '1') txt_ae1 += "ปรับขนาดยาต้านไวรัส<br>";
             if(dataObj.t2 == '1') txt_ae1 += "ทำหัตถการ เช่น ผ่าตัด<br>";
             if(dataObj.t3 == '1') txt_ae1 += "แพทย์ให้หยุดยา<br>";
             if(dataObj.t4 == '1') txt_ae1 += "แพทย์ให้เปลี่ยนสูตรยา";

             var txt_ae2 = "";
             if(dataObj.outcome == '1') txt_ae2 = "แก้ปัญหาสำเร็จ";
             else if(dataObj.outcome == '2') txt_ae2 = "กำลังรักษา";
             else if(dataObj.outcome == '3') txt_ae2 = "อาการของผู้ใช้บริการแย่ลง";
             else if(dataObj.outcome == '4') txt_ae2 = "เสียชีวิต";

             var txt_ae_vl = "";
             if(dataObj.vl_check == '1'){
               if(dataObj.vl_complete == 'Y')
                txt_ae_vl += "<span class='text-success'><i class='fa fa-check'></i></span> ";
               else if(dataObj.vl_complete == 'N')
                txt_ae_vl += "<span class='text-warning'><i class='fa fa-check'></i></span> <small>(รอข้อมูลเพิ่ม)</small>";

               txt_ae_vl += " "+dataObj.vl;
             }
             else{
               txt_ae_vl += "<span class='text-dark'><i class='fa fa-times'></i></span> ไม่ตรวจ";
             }

             var txt_ae_cd4 = "";
             if(dataObj.cd4_check == '1'){
               if(dataObj.cd4_complete == 'Y')
                txt_ae_cd4 += "<span class='text-success'><i class='fa fa-check'></i></span> ";
               else if(dataObj.cd4_complete == 'N')
                txt_ae_cd4 += "<span class='text-danger'><i class='fa fa-times'></i></span> รอข้อมูลเพิ่ม";

               txt_ae_cd4 += " "+dataObj.cd4;
             }
             else{
               txt_ae_cd4 += "<span class='text-dark'><i class='fa fa-times'></i></span> ไม่ตรวจ";
             }

             txt_row += '<tr class="r_sdhos_log" id="r_'+dataObj.seq_no+'" data-seq_no="'+dataObj.seq_no+'">';

             txt_row += ' <td>'+txt_del_ae+'</td>';
             txt_row += ' <td width=80px><b>'+(i+1)+'</b></td>';
             txt_row += ' <td width=100px><button class="btn btn-sm fd'+dataObj.fd+'" type="button" onclick="getFormDetail_ae(\''+dataObj.seq_no+'\')""><i class="fa fa-file"></i> ดูข้อมูล</button></td>';

             txt_row += ' <td>'+changeToThaiDate(dataObj.ae_date)+'</td>';
             txt_row += ' <td>'+dataObj.symptom+'</td>';
             txt_row += ' <td width=100px> '+changeToThaiDate(dataObj.start_date)+'</td>';
             txt_row += ' <td width=100px> '+changeToThaiDate(dataObj.stop_date)+'</td>';

             txt_row += ' <td>'+txt_ae1+'</td>';
             txt_row += ' <td>'+txt_ae2+'</td>';

             txt_row += ' <td>'+txt_ae_vl+'</td>';
             txt_row += ' <td>'+txt_ae_cd4+'</td>';

             txt_row += '</tr">';
           }//for
           $('.r_sdhos_log').remove(); // row log list
           $('#tbl_hos_pid_ae_list > tbody:last-child').append(txt_row);
           $('.fd0').addClass("btn-danger"); // incomplete form
           $('.fd1').addClass("btn-primary"); // complete form
       }
       else{
         $.notify("No record found.", "info");
         $('.r_sdhos_log').remove(); // row log list
         var txt_row = '<tr class="r_sdhos_log">';
         txt_row += ' <td colspan=5 align="center"><b>- ไม่มีข้อมูล -</b></td>';
         txt_row += '</tr">';
         $('#tbl_hos_pid_ae_list > tbody:last-child').append(txt_row);

       }

     }
   }

   function delete_ae(seqNo, collectDate, itmNo){

     var result = confirm("ต้องการที่จะลบข้อมูล AE ("+changeToThaiDate(collectDate)+") ลำดับที่ "+itmNo+" ใช่หรือไม่ ?");
     if (result) {
       var aData = {
                 u_mode:"delete_ae",
                 seq_no:seqNo,
                 collect_date:collectDate,
                 pid:cur_hos_pid
       };
       save_data_ajax(aData,"w_proj_SDHOS/db_hos_pid.php",delete_aeComplete);

     }
   }
   function delete_aeComplete(flagSave, rtnDataAjax, aData){
       //alert("flag save is getPersonalData_pidComplete : "+flagSave);
       if(flagSave){

          $.notify("ลบข้อมูล AE ("+aData.collect_date+") เรียบร้อยแล้ว", "info");
          $('#r_'+aData.seq_no).remove(); // row log list

       }
     }

   function showAE(choice){
     $('.div-hos-ae').hide();
     $('#div_ae_'+choice).show();
   }


</script>
