<?

function getQS($sName,$sDef=""){
	$sResult = (isset($_GET[$sName])?urlencode($_GET[$sName]):"");
	if($sResult=="") $sResult = (isset($_POST[$sName])?urlencode($_POST[$sName]):"");
	if($sResult=="null" || $sResult=="") $sResult=$sDef;
	return urlDecode($sResult);

}


function getSS($sName){
	$sResult = (isset($_SESSION[$sName])?urldecode($_SESSION[$sName]):"");
	return $sResult;
}
?>
