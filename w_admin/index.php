<?

include_once("./a_app_info.php");
include_once("./in_auth.php");

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


  <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
    <a class="navbar-brand mr-4" href="#"><i class="fas fa-clinic-medical"></i> PREVENTION <b>we</b>Clinic </a> <span class='badge badge-warning px-1 py-1'><? echo "$clinic_name";?></span>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navb">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link mnu-main" data-id="staff_mgt" href="javascript:void(0)"><i class="fas fa-users-cog"></i> Staff</a>
        </li>
        <li class="nav-item">
          <a class="nav-link mnu-main" data-id="proj_mgt" href="javascript:void(0)"><i class="fas fa-book-medical"></i> Project</a>
        </li>

      <!--
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            เลือกโครงการ
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#"><i class="fas fa-arrow-alt-circle-right"></i> Point of Care</a>
            <a class="dropdown-item disabled" href="#"><i class="fas fa-arrow-alt-circle-right"></i> Princess PrEP</a>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link disabled" href="javascript:void(0)">Disabled</a>
        </li>
    -->

      </ul>

      <ul class="navbar-nav ml-auto mr-4">
        <li class="nav-item dropdown no-arrow active">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i> <span class="d-none d-md-inline-block"> <? echo "<b>$sc_id</b> $s_name [$job_name]"; ?> </span>


          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item mnu-main" href="javascript:void(0);" data-id="w_user_info"><i class="fa fa-id-card" aria-hidden="true"></i> ข้อมูลส่วนตัว</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="logout.php" ><span class='text-danger'><i class="fa fa-sign-out-alt" aria-hidden="true"></i> ออกจากระบบ</span></a>
          </div>
        </li>

      </ul>
      <!--
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" placeholder="Search" type="text">
        <button class="btn btn-success my-2 my-sm-0" type="button">Search</button>
      </form>
    -->
    </div>
  </nav>

<div class="container-fluid" style="margin-top:30px">
  <div id="div_main">
      <?// include_once("w_admin/dashboard.php"); ?>
      <center>Administrator Control Panel</center>
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


  <? include_once("./in_modal_content.php"); ?>

</body>
</html>
<script>

  
$(document).ready(function(){
  loadingHide();
//loadingShow();


  $(".mnu-main").click(function(){
    var link = $(this).data("id")+".php";
    $("#div_main").load("w_admin/"+link, function(){
        // alert("load "+link);
    });
  }); // .mnu-main


});


function loadingShow(){
  $("#div_loading").show();
  $("#div_main").hide();
}
function loadingHide(){
  $("#div_loading").hide();
  $("#div_main").show();
}

function extendSession(){

}
</script>




<? include_once("./inc_foot_include.php"); ?>
<? include_once("./function_js/js_fn_validate.php"); ?>
<? include_once("./in_savedata.php");
?>
