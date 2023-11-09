<?
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$job_id = isset($_SESSION["job_id"])?$_SESSION["job_id"]:"";
$status_id = isset($_GET["status_id"])?$_GET["status_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
//echo "level : $auth_level";

if($job_id == 'CSL'){ // counselor
  include_once("menu_counselor.php");
}
else if($job_id == 'LB'){ // lab
  include_once("menu_lab.php");
}
else if($job_id == 'CS'){ // care & support
  include_once("menu_cs.php");
}
else if($job_id == 'RCP'){ // receiption
  include_once("menu_rcp.php");
}

else if($job_id == 'ADM'){ // counselor
  include_once("menu_counselor.php");
}





?>
