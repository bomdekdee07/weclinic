<?
/*
include_once("../in_db_conn.php");
include_once("../../function/in_fn_link.php");
*/
include_once("./a_app_info.php");
//include_once("./in_auth.php");

?>


<!doctype html>
<html>
<head>
<title><? echo $GLOBALS['title']; ?></title>
<?
include_once("./inc_head_include.php");
?>

<style>
#div_loading {
  /*  background-color:#EEE;*/
    display: table;
    width: 100%;
    height: 400px;
}
#loading_box {

    display: inline-block;
    vertical-align: top;
}
.v-align {
    padding: 10px;
    display: table-cell;
    text-align: center;
    vertical-align: middle;
}
</style>

</head>
<body>

  <div id="head_title" class="row px-0 mx-0 py-2 bg-info" style="margin:5px;">

    <div class="col-sm-12 my-4 py-4 mx-4 px-4">
      <center>
        <span class="text-light"><h1><i class="fas fa-clinic-medical"></i> IHRI <b>we</b>Clinic</h1></span>
      </center>

    </div>
  </div>
  <!-- Page Content -->
<div class="container-fluid" style="margin-top:30px">
  <div id="div_login">
  <div class="row pt-1 py-4">
   <div class="col-md-2">

   </div>
   <div class="col-md-8" id="div_login">

        <div class="row px-4 pt-2 pb-1 mx-4 mt-4 my-0">
         <div class="col-md-4">
           <div class="form-group">
             <label for="staff_id"><i class="fas fa-id-card-alt"></i> รหัสประจำตัว</label>
             <input class="form-control v-no-blank" id="staff_id" data-title="รหัสประจำตัว" placeholder="รหัสประจำตัว" maxlength="20">
           </div>
         </div>
         <div class="col-md-4">
           <div class="form-group">
             <label for="staff_pwd"><i class="fas fa-key"></i> รหัสผ่าน</label>
             <input class="form-control v-no-blank" id="staff_pwd" type="password" data-title="รหัสผ่าน" placeholder="Password" autocomplete="off" maxlength="20">
           </div>
        </div>
         <div class="col-md-4">
           <label for="btn_login" class="text-info">.</label>
           <button id="btn_login" class="btn btn-warning btn-block"> <i class="fa fa-sign-in-alt fa-lg"></i> เข้าสู่ระบบ</button>
         </div>
       </div>

   </div>
   <div class="col-md-2">

   </div>
  </div>

  <div class="my-4 py-4" id = "msg_login">

    <center>
      <button id="btn_forgot_pwd" class="btn btn-sm btn-primary"> <i class="fa fa-question-circle "></i> ลืมรหัสผ่าน</button>
    </center>

  </div>


</div>


<div id = div_loading>
    <div class="v-align">
        <div id="loading_box">
          <h1><i class="fas fa-spinner fa-spin text-danger"></i> Loading </h1><br>
          <i class="fas fa-cat fa-lg text-info"></i> กรุณารอสักครู่

        </div>
    </div>
</div>


</div>



<div class="jumbotron text-center" style="margin-bottom:0">
  <? include_once("./inc_footer.php"); ?>
</div>

</body>
</html>




<script>
  $(document).ready(function(){
      loadingHide();
      $("#staff_pwd").on("keypress",function (event) {
        if (event.which == 13) {

          loginCheck();
        }
      });

      $("#btn_login").click(function(){
          loginCheck();
      });
      $("#btn_forgot_pwd").click(function(){
          forgotPassword();
      });

  });


  function loginCheck(){
     $('.v-no-blank').removeClass("bg-warning");
     if(validateInput("div_login")){
       var aData = { 
                 u_mode:"login_check",
                 staff_id:$('#staff_id').val().trim(),
                 staff_pwd:$('#staff_pwd').val().trim()
       };

       save_data_ajax(aData,"system-access/db_user.php",loginCheckComplete);

     }
  }

  function loginCheckComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave);
    if(flagSave){
       window.location = "index.php"; // go to member section
    }
  }



  function forgotPassword(){

     if($('#staff_id').val().trim() != ""){
       if($('#staff_id').val().length < 5){
         $('#staff_id').notify("รหัสประจำตัว (user id) ไม่ถูกต้อง", "info");
         return;
       }
       var aData = {
                 u_mode:"forgot_pwd",
                 staff_id:$('#staff_id').val().trim()
       };

       save_data_ajax(aData,"system-access/db_user.php",forgotPasswordComplete);

     }
     else{
       $('#staff_id').notify("กรุณากรอกรหัสประจำตัว (user id) ก่อนกดปุ่มลืมรหัสผ่าน", "info");
     }
  }
  function forgotPasswordComplete(flagSave, rtnDataAjax, aData){
    //alert("flag save is : "+flagSave);
    if(flagSave){
       $('#msg_login').html("<center>ระบบได้ส่งลิงค์ในการเปลี่ยนรหัสผ่านให้ท่านทาง Email: "+rtnDataAjax.email+" เรียบร้อยแล้ว <br>กรุณาเปลี่ยนรหัสผ่านใหม่<b>ภายในวันที่ "+rtnDataAjax.expired_date+"</b><center>");

    }

  }

  function loadingShow(){
    $("#div_loading").show();
    $("#div_login").hide();
  }
  function loadingHide(){
    $("#div_loading").hide();
    $("#div_login").show();
  }

  function extendSession(){

  }


</script>

<?
include_once("./inc_foot_include.php");
include_once("./function_js/js_fn_validate.php");
include_once("./in_savedata.php");
?>
