<?
include_once("../in_auth.php");

$is_update = "";
$is_delete = "";
$is_view = "";
if(isset($_SESSION)){
  if(isset($_SESSION["auth_covid19"])){
     $auth= $_SESSION["auth_covid19"];

     $is_view = (isset($auth["view"]))?$auth["view"]:"";
     $is_update = (isset($auth["data"]))?$auth["data"]:"";
     $is_delete = (isset($auth["data_backdate"]))?$auth["data_backdate"]:"";
  }
}

//echo "auth: $is_view/$is_update/$is_delete";
?>
