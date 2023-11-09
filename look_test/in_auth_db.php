<?
date_default_timezone_set('Asia/Bangkok');

include_once("function/in_php_func.php");

$ROOT_FILE_PATH = $_SERVER['DOCUMENT_ROOT']."/weclinic/";
if (session_status() === PHP_SESSION_NONE) session_start();

$flag_auth = 1;

  if(isset($_SESSION["s_id"])){
    if($_SESSION["s_id"] !== NULL && $_SESSION["s_id"] != ""){

      $s_id = isset($_SESSION["s_id"])?$_SESSION["s_id"]:"";
      $user_id = isset($_SESSION["sc_id"])?$_SESSION["sc_id"]:"";
      $sc_id = isset($_SESSION["sc_id"])?$_SESSION["sc_id"]:"";
      $s_name = isset($_SESSION["s_name"])?$_SESSION["s_name"]:"";
      $s_email = isset($_SESSION["s_email"])?$_SESSION["weclinic_section_id"]:"";
      $s_group = isset($_SESSION["s_group"])?$_SESSION["s_group"]:"";
      $s_section_id = isset($_SESSION["weclinic_section_id"])?$_SESSION["weclinic_section_id"]:"";
      $clinic_id = isset($_SESSION["weclinic_id"])?$_SESSION["weclinic_id"]:"";
      $staff_clinic_id = isset($_SESSION["weclinic_id"])?$_SESSION["weclinic_id"]:"";
      $job_id = isset($_SESSION["job_id"])?$_SESSION["job_id"]:"";
      $clinic_name = isset($_SESSION["clinic_name"])?$_SESSION["clinic_name"]:"";
      $job_name = isset($_SESSION["job_name"])?$_SESSION["job_name"]:"";

      if(isset($_SESSION["auth"]))
      $auth= $_SESSION["auth"];

      //echo "s_id: $s_id";
    }
    else{
      //echo "no s_id";
      $flag_auth = 0;
    }

//echo "scId: $sc_id";
  }
  else{// session expired , no session
    //echo "no s_id2";
    $flag_auth = 0;
  }



//$flag_auth=0;
if($flag_auth == 0){
/*
	echo "
  <script>
  window.location = 'logout.php';
  </script>
  ";
  //exit();
  */
}

$flag_auth = 1;

?>
