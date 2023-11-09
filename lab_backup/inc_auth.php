<?

$is_view = "0";
$is_data = "0";
$is_delete = "0";

if(isset($_SESSION)){
  if(isset($_SESSION["auth_LAB"])){
     $is_view = (isset($_SESSION["auth_LAB"]["view"])?$_SESSION["auth_LAB"]["view"]:"0");
     $is_data = (isset($_SESSION["auth_LAB"]["data"])?$_SESSION["auth_LAB"]["data"]:"0");
     $is_delete = (isset($_SESSION["auth_LAB"]["delete"])?$_SESSION["auth_LAB"]["delete"]:"0");
  }
}

?>
