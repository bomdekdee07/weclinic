<?

include_once("./a_app_info.php");
include_once("./in_auth.php");

?>


<!doctype html>
<html>
<head>
<title><? echo $GLOBALS['title']." STANDUP TEEN (Previsit)"; ?></title>
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

  <nav class="navbar navbar-expand-sm navbar-dark bg-info">
    <a class="navbar-brand mr-4" href="change_menu.php?mnu="><i class="fas fa-clinic-medical"></i> IHRI <b>we</b>Clinic </a> <h3><span class='badge badge-warning px-2 py-1'><? echo "$clinic_name";?></span></h3>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navb">



      <ul class="navbar-nav ml-auto mr-4">
        <li class="nav-item dropdown no-arrow active">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i> <span class="d-none d-md-inline-block"> <? echo "<b>$sc_id</b> $s_name [$job_name]" ?> </span>


          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item mnu-user" href="javascript:void(0);" data-id="w_change_pwd"><i class="fa fa-key" aria-hidden="true"></i> เปลี่ยนรหัสผ่าน</a>
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

<div class="container-fluid" style="margin-top:10px">
<div id = div_main>
 <?
include_once("w_proj_SUT_PRE/mnu_data_list.php");
 ?>
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
  <? include_once("./in_modal_change_pwd.php"); ?>
  <? include_once("./in_modal_user_login_dialog.php"); ?>

</body>
</html>
<script>

$(document).ready(function(){
  loadingHide();
//loadingShow();


  $(".mnu-main-sh").click(function(){
    var link = "";
    <?
    if($s_section_id == "TS"){ // technical support
      echo "link = 'dashboard_center_'";
    }
    else{
      echo "link = 'dashboard_'";
    }
    ?>

    link += $(this).data("id")+".php";
    $("#div_main").load("w_hos/"+link, function(){
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

StartWarningTimer();
</script>


<? include_once("./inc_foot_include.php"); ?>
<? include_once("./function_js/js_fn_validate.php"); ?>
<? include_once("./in_savedata.php");
?>
