<?
include_once("./a_app_info.php");
include_once("./in_auth.php");
?>


<!doctype html>
<html>
<head>
<title><? echo $GLOBALS['title']; ?></title>
<?
include_once("inc_head_include.php");
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

.btnMnu {
  background-color: DodgerBlue;
  border: none;
  color: white;
  padding: 12px 16px;
	margin: 16px;
  font-size: 16px;
  cursor: pointer;
	border-radius: 8px;
}
.btnMnu1 {
  background-color: #698C00;
  border: none;
  color: white;
  padding: 12px 16px;
	margin: 16px;
  font-size: 16px;
  cursor: pointer;
	border-radius: 8px;
}

.btnMnu2 {
  background-color: #0049B7;
  border: none;
  color: white;
  padding: 12px 16px;
	margin: 16px;
  font-size: 16px;
  cursor: pointer;
	border-radius: 8px;
}

/* Darker background on mouse-over */
.btnMnu:hover {
  background-color: #FF901E;
}
.btnMnu1:hover {
  background-color: #93C400;
}
.btnMnu2:hover {
  background-color: #FF901E;
}

</style>

</head>
<body>

<div id="div_main_logo" class="jumbotron bg-info text-center text-white">
<div>
  <h1><i class="fas fa-clinic-medical"></i> IHRI <b>we</b>Clinic </a> <span class='badge badge-warning px-1 py-1'><? echo "$clinic_name";?></span></h1>
</div>
<div>
  <h4><? echo "<b>$sc_id</b> $s_name [$job_name]" ?></h4>
</div>
<div>
  <button class="btn btn-info btn-sm" id="btn_logout"><i class="fa fa-sign-out-alt "></i> ออกจากระบบ</button>
</div>


</div>
<center>



<div id="div_main_mnu_lv1" class="pt-2 pb-2 px-4">

  <?
  /*
  $btn_menu1 = "";
  if(isset($_SESSION["auth_uid_mgt"]))
     $btn_menu1 .= '<button class="btnMnu1 mnu" data-id="uid_mgt"><div><i class="fa fa-users fa-3x"></i></div><div>UID Mgt</div> </button>
  ';
  echo "$btn_menu1";
  */
  ?>

</div>

<div id="div_main_mnu_choice" class="pt-2 pb-4 px-4">

<?

$btn_menu = "";

if(isset($_SESSION["auth"]))
   $btn_menu .= '<button class="btnMnu mnu" data-id="POC"><div><i class="fa fa-prescription-bottle-alt fa-3x"></i></div><div>Point of Care</div> </button>
';

if(isset($_SESSION["auth_SDHOS"]))
   $btn_menu .= '<button class="btnMnu mnu" data-id="SDHOS"><div><i class="fa fa-notes-medical fa-3x"></i></div><div>SD Hos</div> </button>
';

if(isset($_SESSION["auth_SUT_PRE"]))
   $btn_menu .= '<button class="btnMnu mnu" data-id="SUT_PRE"><div><i class="fa fa-child fa-3x"></i></div><div>Standup-Teen <small>(Previsit)</small></div> </button>
';

if(isset($_SESSION["auth_SGM"]))
   $btn_menu .= '<button class="btnMnu mnu" data-id="SGM"><div><i class="fa fa-file-alt fa-3x"></i></div><div>SurveyGizmo</div> </button>
';

if(isset($_SESSION["auth_covid19"]))
   $btn_menu .= '<button class="btnMnu mnu" data-id="covid19"><div><i class="fa fa-star-of-life fa-3x"></i></div><div>COVID S&D</div> </button>
';

if(isset($_SESSION["auth_LAB"]))
   $btn_menu .= '<button class="btnMnu2 mnu text-warning" data-id="LAB"><div><i class="fa fa-flask fa-3x"></i></div><div>LAB</div> </button>
';

if(isset($_SESSION["auth_DM"]))
   $btn_menu .= '<button class="btnMnu2 mnu text-warning" data-id="DM"><div><i class="fa fa-database fa-3x"></i></div><div>Data Mgt</div> </button>
';

if($btn_menu == ""){
  $btn_menu = "
    <div class='my-4 px-4 py-4'>
      <i class='fa fa-info-circle fa-3x text-primary'></i> <p><h4>ยังไม่มีส่วนในการเข้าใช้งานของท่านใน weClinic กรุณาติดต่อเจ้าหน้าที่</h4></p>
    </div>
  ";
}

echo $btn_menu;
?>


</div>
</center>

<div class="jumbotron text-center" style="margin-bottom:0">
  <? include_once("inc_footer.php"); ?>
</div>


</body>
</html>
<script>
$(document).ready(function(){
	$("#btn_logout").click(function(){
     window.location = "logout.php";
  }); // btn_logout

	$(".mnu").click(function(){
		 window.location = "change_menu.php?mnu="+$(this).data("id");
	}); // btn_logout
});
</script>
