<?

$u_id = isset($_GET["u"])?$_GET["u"]:"";
$u_name = "";
$u_email = "";

if(!isset($_COOKIE['prevention_mail'])) {
  header("Location: ../index.php");
  die();
}
else{

// use to transit email period trcarc.org -> prevention-trcarc.org
   if(strpos($_COOKIE['prevention_mail'],"@trcarc.org") > 0){
    // echo "change to prevention-trcarc";
    setcookie("prevention_mail", "", time() + (86400 * 1), "/"); // 86400 = 1 day
    setcookie("prevention_name", "", time() + (86400 * 1), "/"); // 86400 = 1 day
     header("Location: ../login.php");
     die();
   }

   $u_name = $_COOKIE['prevention_name'];
   $u_email = $_COOKIE['prevention_mail'];
}


include_once("../in_site_info.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><? echo $site_name; ?></title>
  <!-- Bootstrap core CSS-->
  <link href="../asset/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="../asset/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="../asset/css/trcarc_web.css" rel="stylesheet">
  <script src="../asset/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../asset/vendor/jquery/jquery.min.js"></script>
</head>


<body >
  <div id="head_title" class="row" style="margin:5px;">
    <div id="div_logo" class="col-sm-4 img-wb-post-detail ">
       <img src = "../img/comp_logo.jpg" style="margin:auto;width:100%; ">
    </div>
    <div class="col-sm-4">

    </div>
    <div class="col-sm-4" style="position:relative; ">

    </div>
  </div>

  <div>

    <div  style="background-color:#1AAAB4;">


       <div id="div_alert" class="row py-2 px-4 my-2 bg-white" >
         <div class="col-md-12">
           <center><span id="login_alert" class="text-danger"></span></center>
         </div>
       </div>


        <div class="row pt-1 py-4">
         <div class="col-md-2">

         </div>
         <div class="col-md-8">

           <div class="row px-4 pt-2 pb-1 mx-4 mt-4 my-0">
            <div class="col-md-12">
              <center>
                <span class='text-white' style="font-size:30px;">
              <? echo "<i class='fa fa-user-circle'></i>
 $u_name" ?>
                </span>
              </center>
            </div>
          </div>

           <div class="row px-4 pt-2 pb-1 mx-4 mt-4 my-0">
            <div class="col-md-3">
            </div>
           <div class="col-md-6">
               <label for="user_login_password" class="text-light">Insert Password :</label>
               <div class="input-group">
                 <input id="user_login_password" type="password" class="form-control" placeholder="Password" autocomplete="off">
                 <span class="input-group-btn">
                   <button id="btn_login" class="btn btn-warning" type="button"><i class="fa fa-key fa-lg"></i> </button>
                 </span>
               </div>
           </div>
           <div class="col-md-3">
           </div>
          </div>


         </div>
         <div class="col-md-2">

         </div>
       </div>

       <div class="row mt-4 mb-0 pb-4" >
        <div class="col-md-12 text-light">

          <center>
            <br>
            <a href="forgetmenow.php"><span class="text-light" style="font-size:12px;">Do not remember me in this computer.</span></a>
           <br><br>
            <button id="btn_forgot_pwd" class="btn btn-info btn-sm">
              <span class="font-kanit text-light" style="font-size:12px;">
                <i class="fa fa-arrow-circle-right  fa-lg" aria-hidden="true"></i> Forgot password click here
              </span>
            </button>
          </center>

        </div>
      </div>
    </div>



  </div>

  <footer class="pt-5 pb-2 bg-dark trc_footer">
    <div class="container">
      <div class="row">
          <div class="col-xl-4 text-white">

          </div>
          <div class="col-xl-4 text-white">
            <h2> </h2>
            <p>

            </p>
          </div>
          <div class="col-xl-4 text-white">
            <h2> </h2>
            <p>


            </p>
          </div>
      </div>


      <div style="margin-top:50px; margin-bottom:10px;">
      <p class="m-0 text-center text-white">Copyright &copy; 2018 PREVENTION Thai Red Cross AIDS Research Centre </p>
      </div>
    </div>
  </footer>




<script>
$(document).ready(function(){
  $("#div_alert").hide();

  $("#user_login_password").on("keypress",function (event) {
    if (event.which == 13) {
      verifyUser();
    }
  });


	$("#btn_login").click(function(){

      verifyUser();
	});
  $("#btn_forgot_pwd").click(function(){
      forgotPassword();
  });

});


function validateData(){
  var flag = true;
  var msg_error = "";
   if($("#user_login_password").val() == "") flag = false;

   if(!flag){
     msg_error += "Please enter password.";
   }

   if(msg_error != ""){
     showAlert("error", msg_error);
   }


  return flag;
}

function verifyUser(){
  if(validateData()) {

      var aData = {
          u_name:"<? echo $u_email; ?>",
          u_pwd:$("#user_login_password").val()
      };

      var request = $.ajax({
          url:"login_check.php",
          type:'POST',
          data:aData,

          success: function(result) {
          //  alert("result: "+result);
            if(result == "p"){
                location.href = "../index.php";
            }
            else{
                showAlert("info", result);
            //    $("#login_alert").html(result);
            }
          },
          error: function(xhr){
            //$("#login_alert").html("error: "+xhr.status);
            showAlert("info", rtnObj.msg_error);
          }
      });
  } // validateEmpty

}


function forgotPassword(){

      var aData = {
          u_email:"<? echo $u_email; ?>"
      };
      showAlert("info", "Wait for request");
      $("#btn_forgot_pwd").hide();

      var request = $.ajax({
          url:"login_forgot.php",
          type:'POST',
          data:aData,

          success: function(result) {
            //alert("result: "+result);
            var rtnObj = jQuery.parseJSON( result );

            if(rtnObj.msg_info != ""){
                //location.href = "index.php";
                //alert("success : "+rtnObj.msg_info);
                showAlert("info", rtnObj.msg_info);
            }
            else{
                //alert("not success : "+rtnObj.msg_error);
                showAlert("info", rtnObj.msg_error);
                if(strpos(rtnObj.msg_error,"already sent") < 1)
                $("#btn_forgot_pwd").show();
            }
          },
          error: function(xhr){
             showAlert("error", xhr.status);
             $("#btn_forgot_pwd").show();
          //  $("#login_alert").html("error: "+xhr.status);
          }
      });
}

function showAlert(mode, msg ){
  if(mode == "info"){
    $("#login_alert").removeClass("text-danger");
    $("#login_alert").addClass("text-dark");
  }
  else{
    $("#login_alert").removeClass("text-dark");
    $("#login_alert").addClass("text-danger");
  }

  $("#div_alert").show();
  $("#login_alert").html(msg);
}

</script>

</body>
</html>
