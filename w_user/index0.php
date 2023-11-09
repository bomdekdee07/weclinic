<?
/*
include_once("../in_db_conn.php");
include_once("../../function/in_fn_link.php");
*/
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

  <nav class="navbar navbar-expand-sm navbar-dark bg-info">
    <a class="navbar-brand mr-4" href="#"><i class="fas fa-clinic-medical"></i> PREVENTION <b>we</b>Clinic </a> <span class='badge badge-warning px-1 py-1'><? echo "$clinic_name";?></span>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navb">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active mnu" id="mnu_dashboard">
          <a class="nav-link mnu-main" data-id="dashboard" href="javascript:void(0)">หน้าแรก</a>
        </li>

        <li class="nav-item mnu" id="mnu_in_process">
          <a class="nav-link mnu-sub" data-id="in_process" href="javascript:void(0)">เคสรอดำเนินการ</a>
        </li>

<?
          if(isset($auth["data"])){ // counselor
            echo '
            <li class="nav-item mnu" id="mnu_counseling">
              <a class="nav-link mnu-sub" data-id="counseling" href="javascript:void(0)">การให้คำปรึกษา</a>
            </li>
            ';
          }

          if(isset($auth["lab"])){ // lab
            echo '
            <li class="nav-item mnu" id="mnu_lab">
              <a class="nav-link mnu-sub" data-id="lab" href="javascript:void(0)">ตรวจ LAB</a>
            </li>
            ';
          }

/*
          if(isset($auth["data"])){ // xpress service sending

            echo '
            <li class="nav-item dropdown no-arrow mnu" id="mnu_xpress_service">
              <a class="nav-link dropdown-toggle" href="#" id="xpressDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-paper-plane fa-lg" ></i> <span class="d-none d-md-inline-block"> <b>X</b>Press Service </span>

              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="xpressDropdown">
                <a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="xpress_service" data-id="mnu_xpress_list"><i class="fa fa-chalkboard-teacher" aria-hidden="true"></i> ข้อมูลแบบฟอร์ม XPress</a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="xpress_service" data-id="mnu_xpress_send"><i class="fa fa-paper-plane" aria-hidden="true"></i> การส่งผลตรวจ</a>
                <a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="xpress_service" data-id="mnu_xpress_return"><i class="fa fa-clipboard-list" aria-hidden="true"></i> นัดตรวจหลังแจ้งผล</a>
              </div>
            </li>
            ';


          }
*/

$mnu_data_mgt = "";

$mnu_viewlog = "";
$mnu_data_export = "";
$mnu_schedule_proj = "";
$mnu_sdhos = "";

$mnu_SUT = "";
$mnu_covid = "";
          if(isset($auth["data"]) || isset($auth["export"]) || isset($auth["query"]) ){ // proj schedule


            if($s_section_id == "DATA" || $s_section_id == "TS"){ // for SDHOS
              $mnu_sdhos .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_hos" data-id="dashboard_center_pro"><i class="fa fa-hospital fa-lg" ></i> SDART Hospital Prospective</a>';
              $mnu_sdhos .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_hos" data-id="dashboard_center_retro"><i class="fa fa-hospital fa-lg" ></i> SDART Hospital Retrospective</a>';

            }


            if(isset($auth["data"])){
              $mnu_schedule_proj = '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_data_view" data-id="mnu_schedule_proj_POC_log"><i class="fa fa-table"></i> ตารางนัดหมาย Point of Care</a>';

//$mnu_SUT .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_proj_SUT" data-id="mnu_data_list"><i class="fa fa-file fa-lg" ></i> STANDUP-TEEN Pre Study</a>';

            }
            if(isset($auth["export"])){

              if($staff_clinic_id == "%"){ // internal staff
                $mnu_data_export = '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_data_view" data-id="mnu_data_export"><i class="fa fa-file-export fa-lg" ></i> Data Export</a>';
              }
            }
            if(isset($auth["query"])){

              if($staff_clinic_id == "%"){ // internal staff
                if($job_id == "MNT"){
                  $mnu_schedule_proj = '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_data_view" data-id="mnu_schedule_proj_POC_log"><i class="fa fa-table"></i> ตารางนัดหมาย Point of Care</a>';
                }
                $mnu_viewlog = '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_monitor" data-id="mnu_viewlog"><i class="fa fa-eye fa-lg" ></i> View Log</a>';
              }
            }


            $mnu_surveygizmo ='';
            if($s_group == "0"){ // cbo staff
                $mnu_surveygizmo = '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="mnu_surveygizmo"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo</a>';
            }
            else{ //

              if($s_group == "1"){ // ihri staff
                if($s_section_id == ""){ // ตรวจสอบ survey gizmo
                  $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="mnu_div_surveygizmo_all"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo - TRC</a>';
                }
                else if($s_section_id == "DATA"){
$mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="mnu_surveygizmo"><span class="text-primary"><i class="fa fa-kiwi-bird fa-lg" ></i> ดูฟอร์มจาก SurveyGizmo แบบ CBO</span></a>';
                  $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="mnu_surveygizmo_revise"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo (CBO)</a>';
                  $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="trc_mnu_surveygizmo_revise"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo (TRC)</a>';

                  $mnu_covid .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_proj_covid19" data-id="mnu_data_list"><i class="fa fa-file fa-lg" ></i> COVID S&D</a>';


                }

              }
              else if($s_group == "99"){//admin
                $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="mnu_surveygizmo"><span class="text-primary"><i class="fa fa-kiwi-bird fa-lg" ></i> ดูฟอร์มจาก SurveyGizmo แบบ CBO</span></a>';

                $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="trc_mnu_surveygizmo"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo - TRC</a>';
                $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="mnu_surveygizmo_revise"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo (CBO)</a>';
                $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="trc_mnu_surveygizmo_revise"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo (TRC)</a>';
                $mnu_covid .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_proj_covid19" data-id="mnu_data_list"><i class="fa fa-file fa-lg" ></i> COVID S&D</a>';


              }


/*
              if(($s_group == "1" && $s_section_id == "TRC")){ // ihri staff trc
                $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="trc_mnu_surveygizmo"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo - TRC</a>';
              }
              else if(($s_group == "1" && $s_section_id == "DATA")){ // ihri staff
                $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="mnu_surveygizmo_revise"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo (CBO)</a>';
                $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="trc_mnu_surveygizmo_revise"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo (TRC)</a>';
              }
              if($s_group == "99"){ // admin
                $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="trc_mnu_surveygizmo"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo - TRC</a>';
                $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="mnu_surveygizmo_revise"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo (CBO)</a>';
                $mnu_surveygizmo .= '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="trc_mnu_surveygizmo_revise"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo (TRC)</a>';
              }
              */

            }

//echo "mnu_surveygizmo: $s_group / $s_section_id / $mnu_surveygizmo";
$mnu_SUT = '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_proj_SUT" data-id="mnu_data_list"><i class="fa fa-file fa-lg" ></i> STANDUP-TEEN Pre Study</a>';

            $mnu_data_mgt =  '
            <li class="nav-item dropdown no-arrow mnu" id="mnu_data">
              <a class="nav-link dropdown-toggle" href="#" id="projDataDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-table fa-lg" ></i> <span class="d-none d-md-inline-block"> จัดการข้อมูล </span>

              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="projDataDropdown">
                '.$mnu_schedule_proj.'
                '.$mnu_data_export.'

                '.$mnu_sdhos.'

                '.$mnu_surveygizmo.'
                '.$mnu_SUT.'
                '.$mnu_covid.'
                <div class="dropdown-divider"></div>
                '.$mnu_viewlog.'
              </div>
            </li>
            ';

          }


          if($mnu_data_mgt == ""){
            if($s_section_id == "SUT")
            $mnu_SUT = '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_proj_SUT" data-id="mnu_data_list"><i class="fa fa-file fa-lg" ></i> STANDUP-TEEN Pre Study</a>';

            $mnu_surveygizmo = '<a class="dropdown-item mnu-external" href="javascript:void(0);" data-path="w_ext_surveygizmo" data-id="mnu_surveygizmo"><i class="fa fa-file fa-lg" ></i> ตรวจฟอร์มจาก SurveyGizmo</a>';
            $mnu_data_mgt =  '
            <li class="nav-item dropdown no-arrow mnu" id="mnu_data">
              <a class="nav-link dropdown-toggle" href="#" id="projDataDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-table fa-lg" ></i> <span class="d-none d-md-inline-block"> จัดการข้อมูล </span>

              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="projDataDropdown">

                '.$mnu_surveygizmo.'
                '.$mnu_SUT.'

              </div>
            </li>
            ';
          }

echo $mnu_data_mgt ;
/*
if($staff_clinic_id == "%"){ // internal staff
  echo $mnu_data_mgt ;
}
*/


/*
          if(isset($auth["export"])){ // export report
            echo '
            <li class="nav-item mnu" id="mnu_export">
              <a class="nav-link mnu-sub" data-id="export" href="javascript:void(0)"> <i class="fa fa-file-export fa-lg" ></i> Data Export</a>
            </li>
            ';
          }


          if(isset($auth["query"])){ // query
            echo '
            <li class="nav-item mnu" id="mnu_viewlog">
              <a class="nav-link mnu-sub" data-id="viewlog" href="javascript:void(0)"> <i class="fa fa-eye fa-lg" ></i> View Log</a>
            </li>
            ';
          }
  */
?>


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

<div class="container-fluid" style="margin-top:30px">
  <div id="div_main">
      <? include_once("w_user/dashboard.php"); ?>
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
  <input type="hidden" id="cur_uic" >
  <input type="hidden" id="cur_proj_id" >
  <input type="hidden" id="cur_proj_name" >
  <input type="hidden" id="cur_group_id" >
  <input type="hidden" id="cur_pid" >
  <input type="hidden" id="cur_clinic_id" >

  <input type="hidden" id="u_mode_screen" >
  <input type="hidden" id="cur_screen_date" >

  <input type="hidden" id="u_mode_visit" >
  <input type="hidden" id="cur_visit_id" >
  <input type="hidden" id="cur_visit_date" >

  <input type="hidden" id="cur_form_id" >
  <input type="hidden" id="cur_proj_final_status_date" >


  <input type="hidden" id="data_update_proj" >
  <input type="hidden" id="data_update_visit" >


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
    $("#div_main").load("w_user/"+link, function(){
        // alert("load "+link);
    });
  }); // .mnu-main

  $(".mnu-sub").click(function(){
    var id = $(this).data("id");
    var link = "mnu_"+id+".php";
    $("#div_"+id).load("w_user/"+link, function(){
        $(".mnu").removeClass("active");
        $("#mnu_"+id).addClass("active");
        showMainDiv(id);
    });
  }); // .mnu-main

  $(".mnu-external").click(function(){
    var path = $(this).data("path");
    var link = path+"/"+$(this).data("id")+".php";

    $("#div_"+path).load(link, function(){
        $(".mnu").removeClass("active");
        $("#mnu_"+$(this).data("path")).addClass("active");
        showMainDiv(path);
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

</script>


<? include_once("./inc_foot_include.php"); ?>
<? include_once("./function_js/js_fn_validate.php"); ?>
<? include_once("./in_savedata.php");
?>
