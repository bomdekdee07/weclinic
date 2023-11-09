<?
function getQueryString($sName,$sDef=""){
	$sResult = (isset($_GET[$sName])?urlencode($_GET[$sName]):"");
	if($sResult=="") $sResult = (isset($_POST[$sName])?urlencode($_POST[$sName]):"");
	if($sResult=="null") $sResult=$sDef;
	return $sResult;
}
include_once("../in_auth_db.php");

$sMode = getQueryString("u_mode");
$sUid = getQueryString("uid");
$sProjId = getQueryString("projid");

$msg_error = "";
$msg_info = "";


include("../in_db_conn.php"); 
$rtn=array();
$aData=array();

if($sMode=="get_pid"){
	$query =" SELECT pid,screen_date,enroll_date,clinic_id FROM p_project_uid_list WHERE uid=? AND proj_id = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ss",$sUid,$sProjId);
	if($stmt->execute()){
		$stmt->bind_result($pid,$screen_date,$enroll_date,$clinic_id);
		while ($stmt->fetch()) {
			$aData["pid"] = $pid;
			$aData["screen_date"] = $screen_date;
			$aData["enroll_date"] = $enroll_date;
			$aData["clinic_id"] = $clinic_id;
		}
		$rtn['datalist'] = $aData;
	}else{
		$msg_error="ERROR";
		$msg_info="ERROR PULL DATA";
	}
}
$mysqli->close();




$rtn['mode'] = $sMode;
$rtn['msg_error'] = $msg_error;
$rtn['msg_info'] = $msg_info;

$rtn['flag_auth'] = 1;

// change to javascript readable form
$returnData = json_encode($rtn);
echo $returnData;

?>