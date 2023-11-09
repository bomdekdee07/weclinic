<?
$uid = isset($_GET["uid"])?$_GET["uid"]:"";
$queue = isset($_GET["q"])?$_GET["q"]:"";
$selroom = isset($_GET["selroom"])?$_GET["selroom"]:"last";

$sUrl = "../pribta21/ext_index.php?file=queue_inc_fwd&uid=".$uid."&q=".$queue."&selroom=".$selroom;

echo"<iframe src='$sUrl' style='width:100%;height: 99%'></iframe>";

?>
