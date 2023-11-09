<?

// ?UIC=xx123456&Date=05%2F21%2F2020&Visit=Month+1&Site=RSAT+Bkk

$form_id="4"; // assist

$pid = isset($_GET["PID"])?$_GET["PID"]:"";
$acid = isset($_GET["ACID"])?$_GET["ACID"]:"";
$uic = isset($_GET["UIC"])?$_GET["UIC"]:"";
$visit_date = isset($_GET["Date"])?$_GET["Date"]:"";
$visit_name = isset($_GET["Visit"])?$_GET["Visit"]:"";
$trc_site = isset($_GET["Site"])?$_GET["Site"]:"";
//echo "param: $uic=$visit_date=$visit_name=$trc_site";

$is_valid = 0;

//if($pid != "" && $acid != "" ){
  if($visit_date != ""){
    $arr_visitDate = explode('/', $visit_date);
    if(count($arr_visitDate) == 3){
      $visit_date = $arr_visitDate[2]."-".$arr_visitDate[1]."-".$arr_visitDate[0];
      $is_valid = 1;
    }
  }
//}


if($is_valid == 1){
  echo "<center>กรุณารอสักครู่</center>";
  include_once("trc_inc_add_survey_complete.php");
}
else{
  header( "location: p_survey_info.php?c=2" ); // invalid
  exit(0);
}


?>
