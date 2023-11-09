<?

$is_view = "0";
$is_data = "0";
$is_delete = "0";

if(isset($_SESSION)){
  if(isset($_SESSION["auth_DM"])){
     $is_view = (isset($_SESSION["auth_DM"]["view"])?$_SESSION["auth_DM"]["view"]:"0");
     $is_data = (isset($_SESSION["auth_DM"]["data"])?$_SESSION["auth_DM"]["data"]:"0");
     $is_delete = (isset($_SESSION["auth_DM"]["delete"])?$_SESSION["auth_DM"]["delete"]:"0");
  }
}

function getQueryString($sName){
 $sResult = (isset($_GET[$sName])?urldecode($_GET[$sName]):"");
 if($sResult=="") $sResult = (isset($_POST[$sName])?urldecode($_POST[$sName]):"");
 return $sResult;
}
?>
