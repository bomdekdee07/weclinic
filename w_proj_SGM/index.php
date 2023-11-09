<?
/*
include_once("../in_db_conn.php");
include_once("../../function/in_fn_link.php");
*/




include_once("./a_app_info.php");
include_once("./in_auth.php");

$menu_txt = "";
if($s_section_id == "DATA"){
  $menu_txt .= '
  <ul class="navbar-nav mr-auto">
    <li class="nav-item active mnu" >
      <a class="nav-link mnu-main" data-id="mnu_surveygizmo" href="javascript:void(0)"><i class="fa fa-kiwi-bird fa-lg" ></i> ดูฟอร์มแบบ CBO</span></a>
    </li>
    <li class="nav-item active mnu" >
      <a class="nav-link mnu-main" data-id="mnu_surveygizmo_revise" href="javascript:void(0)">ตรวจฟอร์มจาก CBO</a>
    </li>
    <li class="nav-item active mnu" >
      <a class="nav-link mnu-main" data-id="trc_mnu_surveygizmo_revise" href="javascript:void(0)">ตรวจฟอร์มจาก TRC</a>
    </li>

  </ul>
  ';
}


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

  <nav class="navbar navbar-expand-sm navbar-dark bg-info">
    <a class="navbar-brand mr-4" href="change_menu.php?mnu="><i class="fas fa-clinic-medical"></i> IHRI <b>we</b>Clinic </a> <h3><span class='badge badge-warning px-2 py-1'><? echo "$clinic_name";?></span></h3>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navb">
      <? echo $menu_txt; ?>
      <!--
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active mnu" id="mnu_dashboard">
          <a class="nav-link mnu-main" data-id="dashboard" href="javascript:void(0)">หน้าหลัก</a>
        </li>
        <li class="nav-item active mnu" id="mnu_dashboard">
          <a class="nav-link mnu-main" data-id="patient_new" href="javascript:void(0)">ผู้รับบริการรายใหม่</a>
        </li>

      </ul>
    -->
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

<div class="container-fluid" style="margin-top:0px">
  <div id="div_main">
      <?
      if($s_section_id != "DATA"){
        if($s_group == "0"){ // cbo
          include_once("w_proj_SGM/mnu_surveygizmo.php");
        }
        else if($s_group == "1"){ // trc
          include_once("w_proj_SGM/trc_mnu_surveygizmo.php");
        }
      }


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


  <input type="hidden" id="s_clinic_id">
  <input type="hidden" id="cur_uid" >
  <input type="hidden" id="cur_ul" >
  <input type="hidden" id="cur_proj_id" >
  <input type="hidden" id="cur_proj_name" >
  <input type="hidden" id="cur_group_id" >
  <input type="hidden" id="cur_pid" >
  <input type="hidden" id="cur_clinic_id" >



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


  $(".mnu-main").click(function(){
    var link = $(this).data("id")+".php";
    $("#div_main").load("w_proj_SGM/"+link, function(){
        // alert("load "+link);
    });
  }); // .mnu-main


  $(".mnu-user").click(function(){
       myModalChangePwd();

  }); // .mnu-user


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
