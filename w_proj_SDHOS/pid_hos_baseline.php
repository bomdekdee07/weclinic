<div class="fixme">
  <div class="row my-1 bg-info">
     <div class="col-sm-3 py-1 text-white">
       <b><i class="fa fa-notes-medical fa-lg"></i> Baseline</b>
     </div>
     <div class="col-sm-2 py-1">
       <button id="btn_baseline_save" class="form-control form-control-sm btn btn-success" type="button">
          บันทึก
       </button>
     </div>
     <div class="col-sm-7 py-1">

       <select id="sel_hos_pid_baseline" class="form-control form-control-sm " >
        <option value='0' class="text-secondary" >เลือกส่วนที่ต้องการใน Baseline</option>
        <option value='character_title' ><i class="fa fa-user fa-lg" ></i> ข้อมูลผู้ใช้บริการ (Subject characteristic)</option>
        <option value='pe_title' ><i class="fa fa-notes-medical fa-lg" ></i> การตรวจ และประเมินด้านร่างกาย (Physical Exam)</option>
        <option value='lab_title' ><i class="fa fa-stethoscope fa-lg" ></i> การประเมินทางห้องปฏิบัติการ (Laboratory)</option>
        <option value='artinitial_title' ><i class="fa fa-procedures fa-lg" ></i> การจ่ายยาต้านไวรัส (ART Initiation)</option>
        <option value='referral_title' ><i class="fa fa-procedures fa-lg" ></i> การส่งต่อ หรือ ย้ายสิทธิ ผู้ใช้บริการ</option>
      </select>
     </div>

  </div>
</div>
<div id="div_baseline_form" class='div-hos-load'>

</div>




<script>

$(document).ready(function(){

  $("#sel_hos_pid_baseline").change(function(){

     var choice = $(this).val();
     if(choice == "0") return;

     $("body,html").animate(
       {
         scrollTop: ($('#'+choice+"-c").offset().top -70)
       },500 //speed
     );
  }); //menu form section


  $("#btn_baseline_save").click(function(){
    saveDataBaseLine();

    //is_update_form=1;
  }); //menu form section


});

var fixmeTop = $('.fixme').offset().top;       // get initial position of the element
$(window).scroll(function() {                  // assign scroll event listener
    var currentScroll = $(window).scrollTop(); // get current position
    if (currentScroll >= fixmeTop) {           // apply position: fixed if you
        $('.fixme').css({                      // scroll to that element or below it
            position: 'fixed',
            top: '0',
            right: '0'
        });
    } else {                                   // apply position: static
        $('.fixme').css({                      // if you scroll above it
            position: 'static'
        });
    }

});




 function getBaseline_pid(){
   ResetTimeOutTimer();
   //alert("getBaseline_pid 1 ");
   var link = "w_proj_SDHOS/pid_hos_baseline_form.php?";
   link += "pid="+cur_hos_pid; // pid
   link += "&visit_date="+cur_hos_pid_create_date; // pid create date

   //alert("openUIDFormxx "+link);
   //$('#div_baseline_form').html("รอสักครู่");
   $('.div-hos-load').html("รอสักครู่");

   $("#div_baseline_form").load(link, function(){
       after_goHosMnu();
   });
//after_goHosMnu();

 }




     function getPIDAge(){
       //alert("checkExistLogData 1 ");

         var aData = {
                   u_mode:"get_pid_age",
                   pid:cur_hos_pid
         };
         save_data_ajax(aData,"w_proj_SDHOS/db_hos_pid.php",getPIDAgeComplete);

       }
       function getPIDAgeComplete(flagSave, rtnDataAjax, aData){
         //alert("flag save is getPersonalData_pidComplete : "+flagSave);
         if(flagSave){
           $("#age").val(rtnDataAjax.age);
         }
       }


</script>
