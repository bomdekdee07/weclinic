<?
if(!isset($_SESSION)) session_start();
function getQueryString($sName,$sDef=""){
	$sResult = (isset($_GET[$sName])?urlencode($_GET[$sName]):"");
	if($sResult=="") $sResult = (isset($_POST[$sName])?urlencode($_POST[$sName]):"");
	if($sResult=="null") $sResult=$sDef;
	return $sResult;
	
}
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

$sUid  = getQueryString("uid");
$sColDate= getQueryString("coldate");
$sColTime= urldecode(getQueryString("coltime"));
$sMode = getQueryString("u_mode");
$sBar = getQueryString("bar");
$sSpecId = getQueryString("specid");


$rtn = array();
$sUser = $_SESSION["sc_id"];


$query="";
include("../in_db_conn.php");
if($sMode =="store_specimen"){
	$query = "UPDATE p_lab_order_specimen SET specimen_status='S4' WHERE uid=? AND collect_date=? AND collect_time=? AND barcode=? AND specimen_id=?";
}
//error_log($sNote." ".$sUid." ".$sColDate." ".$sColTime." ".$sBar." ".$sLabSer);
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sssss",$sUid,$sColDate,$sColTime,$sBar,$sSpecId);
if($stmt->execute()){
	$rtn['msg_error'] = "";
}else{
	$rtn['msg_error'] = "ERROR";
}

$logNote = "[Pribta] update ordernote ".$sUid." ".$sColDate." ".$sColTime." ".$sLabId." ".$sNote;
$client_ip_address = get_client_ip();
$today_date = new DateTime();
$inQuery = "INSERT INTO pv_log (log_id, log_ip_address, log_note, staff_id) ";
$inQuery.= " SELECT @keyid := CONCAT('".$today_date->format("y")."',
LPAD( (SUBSTRING(  IF(MAX(log_id) IS NULL,0,MAX(log_id))   ,3,6)*1)+1, '6','0') )";
$inQuery.= ",?,?,?  FROM pv_log WHERE SUBSTRING(log_id,1,2) = '".$today_date->format("y")."';";
$stmt = $mysqli->prepare($inQuery);
$stmt->bind_param("sss",$client_ip_address, $logNote, $sUser);
if ($stmt->execute()) {

}



$mysqli->close();
 // return object
$rtn['mode'] = $sMode;
$rtn['msg_info'] = "";

$rtn['flag_auth'] = 1;
// change to javascript readable form
$returnData = json_encode($rtn);
echo $returnData;

?>