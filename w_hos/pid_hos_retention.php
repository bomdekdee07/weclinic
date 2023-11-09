


  <div id="div_retention_list" class="div-hos-retention">
    <div class="row my-1 ">
       <div class="col-sm-10 py-1 ">
         <b><i class="fa fa-stethoscope fa-lg"></i> Retention</b>
       </div>
       <div class="col-sm-2 py-1">
         <button id="btn_retention_add" class="form-control form-control-sm btn btn-success" type="button">
            <i class="fa fa-plus fa-lg"></i> เพิ่ม Retention
         </button>
       </div>
       <!--
       <div class="col-sm-2 py-1">
         <button id="btn_retention_save" class="form-control form-control-sm btn btn-success" type="button">
            บันทึก
         </button>
       </div>
       <div class="col-sm-7 py-1">

         <select id="sel_hos_pid_retention" class="form-control form-control-sm " >
          <option value='0' class="text-secondary" >เลือกส่วนที่ต้องการใน retention</option>
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
      <table id="tbl_hos_pid_retention_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th></th>
              <th>ลำดับที่</th>
              <th>Visit Date</th>
              <th>ติดตามการรักษา</th>
              <th>ติดตาม Viral Load</th>
              <th>ติดตาม CD4</th>
              <th>ติดตามการเปลี่ยนยาต้านฯ</th>
              <th>ติดตาม AE</th>

            </tr>
          </thead>
          <tbody>

          </tbody>
      </table>
    </div>

  </div>
  <div id="div_retention_detail" class="div-hos-retention">
    <div class="row my-1 bg-info">
       <div class="col-sm-11 py-1 text-white">
         <b><i class="fa fa-stethoscope fa-lg"></i> Retention Form</b>
       </div>
       <div class="col-sm-1 py-1">
         <button id="btn_retention_form_close" class="form-control form-control-sm btn btn-danger" type="button">
            <i class="fa fa-times fa-lg"></i> ปิด
         </button>
       </div>
    </div>
    <div id="div_retention_form" class="my-1 div-hos-load">
detail
    </div>

  </div>


<script>

$(document).ready(function(){

  showRetention("list");

  $("#btn_retention_add").click(function(){
    u_mode_retention = "add";
    //var retention_day = getTodayDateEN();
    var retention_day = "";
    getRetentionForm_pid(retention_day);
  }); //menu form section
  $("#btn_retention_form_close").click(function(){
    if($('#collect_date').val() != ''){ // update data mode
      var flag_change = checkFormDataChange();
      if(flag_change){
        if(typeof hos_sc_id  === 'undefined' ){ // if hospital staff
          if(confirm("คำเตือน: ข้อมูลมีการเปลี่ยนแปลง ท่านต้องการที่จะบันทึกข้อมูลหรือไม่ ?")){
            saveSDHosRetention();
            return;
          }
        }

      }
    }


    if(is_update_form == 1) getRetentionList_pid();

    showRetention("list");
  }); //menu form section

  if(typeof hos_sc_id  !== 'undefined' ){
    if(hos_sc_id.substring(0, 1) != "H" ){
      $("#btn_retention_add").hide();

    }
  }


});

// update retention
function getFormDetail_retention(visit_date){
//  alert("getFormDetail_retention "+visit_date);
  u_mode_retention = "update";
  getRetentionForm_pid(visit_date);
}


function getRetentionForm_pid(visitDate){
  //alert("getBaseline_pid 1 ");

  var link = "w_hos/pid_hos_retention_form.php?";
  link += "pid="+cur_hos_pid; // pid
  link += "&visit_date="+visitDate; // pid create date

  //alert("openUIDForm "+formID+"/"+uid+"/"+visit_date);
  //alert("openUIDFormxx "+link);

  //$('#div_retention_form').html("รอสักครู่");
  $('.div-hos-load').html("รอสักครู่");
  $("#div_retention_form").load(link, function(){
      //alert("load div_retention_form ddddddd");
      showRetention("detail");
  });
//showRetention("detail");


}

function getRetention_pid(){
  //alert("getRetention_pid 1 ");
    showRetention("list");
    getRetentionList_pid();
    after_goHosMnu();

  }

 function getRetentionList_pid(){
   //alert("getRetention_pid 1 ");
     var aData = {
               u_mode:"select_hos_pid_retention",
               pid:cur_hos_pid
     };
     save_data_ajax(aData,"w_hos/db_hos_pid.php",getRetentionList_pidComplete);

   }
   function getRetentionList_pidComplete(flagSave, rtnDataAjax, aData){
     //alert("flag save is getPersonalData_pidComplete : "+flagSave);
     if(flagSave){


       txt_row="";
       if(rtnDataAjax.datalist.length > 0){
         var datalist = rtnDataAjax.datalist;
           for (i = 0; i < datalist.length; i++) {
             var dataObj = datalist[i];

             var txt_rtn = "";
             var txt_vl = "";
             var txt_cd4 = "";
             var txt_art = "";
             var txt_ae = "";

             var txt_del_retention = '<button class="btn btn-sm btn-danger" type="button" onclick="delete_retention(\''+dataObj.collect_date+'\')""><i class="fa fa-times"></i> ลบ</button>';


             if(dataObj.c_rtn == '1'){
               txt_rtn += "<span class='text-success'><i class='fa fa-check'></i></span> ";
               if(dataObj.rtn == '1') txt_rtn += "ยังอยู่ในระบบการรักษา";
               else if(dataObj.rtn == '2') txt_rtn += "หยุดกินยาต้านไวรัส ";
               else if(dataObj.rtn == '3') txt_rtn += "ไม่สามารถติดต่อผู้ใช้บริการได้";
               else if(dataObj.rtn == '4') txt_rtn += "เสียชีวิต Death";
               else if(dataObj.rtn == '5') txt_rtn += "ส่งต่อไปสถานพยาบาลอื่น";
             }
             if(dataObj.c_vl == '1'){
               txt_vl += "<span class='text-success'><i class='fa fa-check'></i></span> ";
               if(dataObj.vl_sign == '1') txt_vl += "< ";
               else if(dataObj.vl_sign == '2') txt_vl += "= ";
               else if(dataObj.vl_sign == '3') txt_vl += "> ";

               txt_vl += dataObj.vl ;

             }
             if(dataObj.c_cd4 == '1'){
               txt_cd4 += "<span class='text-success'><i class='fa fa-check'></i></span> ";
               txt_cd4 += dataObj.cd4 ;
             }
             if(dataObj.c_art == '1'){
               txt_art += "<span class='text-success'><i class='fa fa-check'></i></span> ";
               if(dataObj.art == '1') txt_art += "เปลี่ยนเป็นสูตรรวมเม็ด";
               else if(dataObj.art == '2') txt_art += "ผลข้างเคียงของยาต้านไวรัส";
               else if(dataObj.art == '3') txt_art += "แพ้ยาต้านไวรัส";
               else if(dataObj.art == '4') txt_art += "ผลการตรวจทางห้องปฏิบัติการผิดปกติ";
               else if(dataObj.art == '5') txt_art += "ดื้อยาต้านไวรัส";
               else if(dataObj.art == '6') txt_art += "คลอดบุตร";
             }

             if(dataObj.c_ae == '1'){
               var ae_color = '';
               if(dataObj.ae == '0'){
                 ae_color = 'btn-secondary';
               }
               else{
                 ae_color = 'btn-info';
               }
               txt_ae = '<button class="btn btn-block btn-sm '+ae_color+'" type="button" onclick="goto_AE(\''+dataObj.collect_date+'\')""><i class="fa fa-procedures"></i> AE ('+dataObj.ae+')</button>';
             }

             txt_row += '<tr class="r_sdhos_log" id="r_'+dataObj.collect_date+'" data-collect_date="'+dataObj.collect_date+'" data-ae="'+dataObj.c_ae+'" data-ae_amt="'+dataObj.ae+'" >';
             txt_row += ' <td>'+txt_del_retention+'</td>';
             txt_row += ' <td width=80px><b>'+(i+1)+'</b></td>';
             txt_row += ' <td width=100px><button class="btn btn-sm fd'+dataObj.fd+'" type="button" onclick="getFormDetail_retention(\''+dataObj.collect_date+'\')""><i class="fa fa-file"></i> '+changeToThaiDate(dataObj.collect_date)+'</button></td>';

             txt_row += ' <td>'+txt_rtn+'</td>';
             txt_row += ' <td>'+txt_vl+'</td>';
             txt_row += ' <td>'+txt_cd4+'</td>';
             txt_row += ' <td>'+txt_art+'</td>';
             txt_row += ' <td>'+txt_ae+'</td>';


             //txt_row += ' <td><button class="btn btn-sm btn-danger" type="button" onclick="delete_retention(\''+dataObj.collect_date+'\')""><i class="fa fa-times"></i> ลบ</button></td>';
             txt_row += '</tr">';
           }//for
           $('.r_sdhos_log').remove(); // row log list
           $('#tbl_hos_pid_retention_list > tbody:last-child').append(txt_row);

           $('.fd0').addClass("btn-danger"); // incomplete form
           $('.fd1').addClass("btn-primary"); // complete form
       }
       else{
         $.notify("No record found.", "info");
         $('.r_sdhos_log').remove(); // row log list
         var txt_row = '<tr class="r_sdhos_log">';
         txt_row += ' <td colspan=5 align="center"><b>- ไม่มีข้อมูล -</b></td>';
         txt_row += '</tr">';
         $('#tbl_hos_pid_retention_list > tbody:last-child').append(txt_row);

       }

     }
   }


    function delete_retention(collectDate){
      var ae_confirm = "";
/*
      var is_ae = $('#r_'+collectDate).data("ae");
      if(is_ae == '1') ae_confirm = " และลบ AE ที่อยู่กับ Retention นี้ ";
*/

      // if check ae && ae amt <> 0
      if($('#r_'+collectDate).data("ae") == '1' &&  $('#r_'+collectDate).data("ae_amt") != '0')
      ae_confirm = " และลบ AE ที่อยู่กับ Retention นี้ ";

      var result = confirm("ต้องการที่จะลบข้อมูล Retention ("+changeToThaiDate(collectDate)+") "+ae_confirm+" ใช่หรือไม่ ?");
      if (result) {
        var aData = {
                  u_mode:"delete_retention",
                  collect_date:collectDate,
                  pid:cur_hos_pid
        };
        save_data_ajax(aData,"w_hos/db_hos_pid.php",delete_retentionComplete);

      }
    }
    function delete_retentionComplete(flagSave, rtnDataAjax, aData){
        //alert("flag save is getPersonalData_pidComplete : "+flagSave);
        if(flagSave){

           $.notify("ลบข้อมูล Retention ("+aData.collect_date+") เรียบร้อยแล้ว", "info");

           if($('#r_'+aData.collect_date).data("ae") == '1' &&  $('#r_'+aData.collect_date).data("ae_amt") != '0')
           remove_ae(aData.collect_date);

           $('#r_'+aData.collect_date).remove(); // row log list

        }
      }

      function remove_ae(collectDate){
          var aData = {
                    u_mode:"remove_ae",
                    collect_date:collectDate,
                    pid:cur_hos_pid
          };
          save_data_ajax(aData,"w_hos/db_hos_pid.php",remove_aeComplete);
      }
      function remove_aeComplete(flagSave, rtnDataAjax, aData){
          //alert("flag save is getPersonalData_pidComplete : "+flagSave);
          if(flagSave){
             $.notify("นำข้อมูล AE ออกจาก Retention ("+aData.collect_date+") เรียบร้อยแล้ว", "info");
          }
      }


   function goto_AE(collectDate){
      ae_collect_date = collectDate;
      cur_mnu_hos = 4; // change to menu AE
      getAE_pid();

   }




   function showRetention(choice){
     $('.div-hos-retention').hide();
     $('#div_retention_'+choice).show();
   }


</script>
