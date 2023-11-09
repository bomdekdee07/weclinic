<?
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$form_id = isset($_GET["form_id"])?$_GET["form_id"]:"";
$link = isset($_GET["link"])?"&link=".$_GET["link"]:"";

include_once("$project_id/x_$form_id.php?$link");

?>
