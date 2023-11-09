<?
$uid = isset($_GET["uid"])?$_GET["uid"]:"";

$sUrl = "../pribta21/ext_index.php?file=patient_inc_info&uid=".$uid."&hideedit=1";

echo"<iframe src='$sUrl' style='width:100%;height: 99%'></iframe>";

?>
