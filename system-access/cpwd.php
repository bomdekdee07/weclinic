<?

include_once("../function/in_fn_link.php");
$flag=1;
if(isset($_GET["link"])){
  $decode_link = decodeSingleLink($_GET["link"]);
  $arr = explode(":",$decode_link);
  if(count($arr)==2){
    $sc_id = $arr[0]; // sc_id
    $visit_date = $arr[1]; // visit_date
    $cur_date = (new DateTime())->format('Y-m-d');

      if($cur_date != $visit_date){
        //echo "enter 01 $sc_id/$cur_date/$visit_date";

        header( "location: ../info/invalid.php?e=e2" );
        exit(0);

      }
  }
  else{
    $flag=0;
  }
}
else{
  $flag=0;
}

if($flag==0){
  //echo "enter 02 $sc_id/$visit_date";

  header( "location: ../info/invalid.php?e=e1" );
  exit(0);

}

include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function
include_once("../function/in_fn_link.php");

?>
<!doctype html>
<html>
<head>
<title><? echo "weClinic เปลี่ยนรหัสผ่านใหม่" ?></title>

<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<link rel="stylesheet" href="../asset/jquery-ui.css">
<script src="../asset/jquery.min.js"></script>
<script src="../asset/jquery-ui-custom.js"></script>

<link rel="stylesheet" href="../asset/bootstrap4.1.3/css/bootstrap.min.css">
<script src="../asset/popper.min.js" ></script>
<script src="../asset/bootstrap4.1.3/js/bootstrap.min.js"></script>

<script src="../asset/notify.min.js"></script>

<link rel="stylesheet" href="../asset/fontawesome/css/all.css">




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
        <span class="text-light"><h1><i class="fas fa-clinic-medical"></i> PREVENTION <b>we</b>Clinic</h1></span>
      </center>

    </div>
  </div>
<div class="container-fluid" style="margin-top:30px">

    <div id = div_loading>
        <div class="v-align">
            <div id="loading_box">
              <h1><i class="fas fa-spinner fa-spin text-danger"></i> Loading </h1><br>
              <i class="fas fa-cat fa-lg text-info"></i> กรุณารอสักครู่

            </div>
        </div>
    </div>
    <div id = "div_main">
          <div id="div_change_password">
             <div class="my-4">
               <center>
                 กรุณาเปลี่ยนรหัสผ่านของท่านภายในวันที่ <b><? echo changeToThaiDate($visit_date); ?></b><br>
                 รหัสผ่านขั้นต่ำ 4 ตัวอักษร
               </center>
             </div>
             <div class="row px-1 pb-1 my-1">
               <div class="col-md-2">
               </div>
               <div class="col-md-8">
                 <div class="form-group my-1">
                   <label class = "text-dark" for="user_login_password1">New Password</label>
                   <input class="form-control" id="user_login_password1" type="password" placeholder="Password" autocomplete="off">
                 </div>
                 <div class="form-group my-1">
                   <label class = "text-dark" for="user_login_password2">Confirm New Password</label>
                   <input class="form-control" id="user_login_password2" type="password" placeholder="Password" autocomplete="off">
                 </div>
               </div>
               <div class="col-md-2">
               </div>

             </div>


            <div class="row mt-4 mb-0 pb-4" >
             <div class="col-md-12 text-light">

               <center>
                 <button id="btn_change_pwd" class="btn btn-warning">
                   <span class="font-kanit" style="font-size:20px;">
                     <i class="fa fa-arrow-circle-right  fa-lg" aria-hidden="true"></i> เปลี่ยนรหัสผ่าน / Change Password
                   </span>
                 </button>
               </center>

             </div>
           </div>

         </div> <!-- change password dialog -->


    </div> <!-- div_main-->
</div>



</body>
</html>

<script>

$(document).ready(function(){
  loadingHide();
//loadingShow();

$("#user_login_password2").on("keypress",function (event) {
  if (event.which == 13) {
    changePwd();
  }
});


$("#btn_change_pwd").click(function(){
    changePwd();
});




});

function loadingShow(){
  $("#div_loading").show();
  $("#div_main").hide();
}
function loadingHide(){
  $("#div_loading").hide();
  $("#div_main").show();
}

function changePwd(){
  var flag = true;
  if($("#user_login_password1").val().trim() == "") flag = false;
  if($("#user_login_password2").val().trim() == "") flag = false;
  if($("#user_login_password1").val().trim() != $("#user_login_password2").val().trim())
  flag = false;
  if($("#user_login_password1").val().length < 4)
  flag = false;

  if(flag) {
    var aData = {
              u_mode:"change_forgot_pwd",
              staff_id:"<? echo $sc_id; ?>",
              staff_pwd_new:$('#user_login_password1').val().trim()
    };

    save_data_ajax(aData,"db_user.php",changePwdComplete);

  }
  else{
    $('#user_login_password2').notify("รหัสผ่านไม่ถูกต้อง", "error");
  }



}

function changePwdComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
     window.location = "index.php"; // go to member section
  }
}
function extendSession(){

}

</script>
<?
include_once("../function_js/js_fn_validate.php");
include_once("../in_savedata.php");

?>
