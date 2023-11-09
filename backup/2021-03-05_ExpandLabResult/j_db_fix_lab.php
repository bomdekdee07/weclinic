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
$sColDate = getQueryString("coldate");
$sColTime = urldecode(urldecode(getQueryString("coltime")));
$msg_error = "";
$msg_info = "";
$sIsWait = getQueryString("iswait");
$sLabStatus = getQueryString("labstat");
$sUser = $_SESSION["s_id"];
$sLogDetail="";
include("../in_db_conn.php"); $rtn=array();

//error_log($sUid.":".$sColDate.":".$sColTime);
$sSID = ""; $sRoom = ""; $sTime = "";

$query =" SELECT PH.physician_record,room_number,PH.time_record 
FROM k_physician PH
LEFT JOIN k_room KR
ON KR.room_who = PH.physician_record
WHERE uid = ? AND visit_date = ? AND visit_time = ? order by PH.time_record DESC,KR.time_record LIMIT 1";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss",$sUid,$sColDate,$sColTime);

$sFixSQL = "";
if($stmt->execute()){
	$stmt->bind_result($physician_record,$room_number,$time_record );
	while ($stmt->fetch()) {
		$sSID = $physician_record; 
		$sRoom = $room_number; 
		$sTime = $time_record;
	}
}else{
	$msg_error = "Error Query";
	$msg_info = "Can't execute prepare query";
}
//error_log($sSID."(".$sRoom.") ".$sTime);

if($sSID=="") $sSID=$sUser;
$today = date("Y-m-d h:i:s");
if($sTime=="" || $sTime=="0000-00-00 00:00:00"){
	$sTime= $today;
}

if($sMode=="fix_missing_user"){

	if($sSID!=""){
		$query =" UPDATE p_lab_order SET staff_order=?,staff_order_room=?,time_confirm_order=?";

		if($sIsWait!=""){
			$query .=",wait_lab_result='0' ";
		}

		$query .=" WHERE uid = ? AND collect_date = ? AND collect_time = ? AND staff_order=''";
		$stmt = $mysqli->prepare($query);
		
		$stmt->bind_param("ssssss",$sSID,$sRoom,$sTime,$sUid,$sColDate,$sColTime);


		$sFixSQL = "";
		if($stmt->execute()){

		}else{
			$msg_error = "Error Fix SID Query";
			$msg_info = "Can't execute prepare update Fix SID Query";
		}
		$sLogDetail = $sMode.",".$sUid.",".$sColDate.",".$sColTime.",".$sSID.",".$sRoom.",".$sTime;
	}
}else if($sMode=="fix_order_status" || $sMode=="specimen_collect_pending" || $sMode=="pending_lab_result"){
	if($sSID!=""){
		$query =" UPDATE p_lab_order SET lab_order_status=?";
		$query .=" WHERE uid = ? AND collect_date = ? AND collect_time = ?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("ssss",$sLabStatus,$sUid,$sColDate,$sColTime);

		if($stmt->execute()){

		}else{
			$msg_error = "Error Fix SID Query";
			$msg_info = "Can't execute prepare update Fix SID Query";
		}
		$sLogDetail = $sMode.",".$sUid.",".$sColDate.",".$sColTime.",".$sLabStatus;
	}
}else if($sMode=="revoke_order_status"){
	if($sSID!=""){
		//error_log("Start revoke_order_status");
		$query =" UPDATE p_lab_order SET lab_order_status=?";
		$query .=" WHERE uid = ? AND collect_date = ? AND collect_time = ?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("ssss",$sLabStatus,$sUid,$sColDate,$sColTime);

		if($stmt->execute()){
			//error_log("Complete revoke_order_status");
		}else{
			$msg_error = "Error Revoke Query";
			$msg_info = "Can't execute prepare update Fix Revoke Query";
		}

		$sLabProStat = (($sLabStatus=="A3")?"P0":(($sLabStatus=="A4")?"P1":""));
		//error_log("After revoke_order_status update lab process");
		$query =" UPDATE p_lab_process SET lab_process_status=?";
		$query .=" WHERE lab_serial_no IN (SELECT lab_serial_no FROM p_lab_result  WHERE uid = ? AND collect_date = ? AND collect_time = ?)";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("ssss",$sLabProStat,$sUid,$sColDate,$sColTime);

		if($stmt->execute()){
			//error_log("After revoke_order_status update lab process COMPLETE");
		}else{
			$msg_error = "Error Update Lab Process Query";
			$msg_info = "Can't execute prepare update Fix Lab Process Query";
		}

		//error_log("After revoke_order_status update lab result");
		$query =" UPDATE p_lab_result SET lab_result_status='L0' WHERE uid = ? AND collect_date = ? AND collect_time = ?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("sss",$sUid,$sColDate,$sColTime);
		if($stmt->execute()){
			//error_log("After revoke_order_status update lab result COMPLETE");
		}else{
			$msg_error = "Error Update Lab Process Query";
			$msg_info = "Can't execute prepare update Fix Lab Process Query";
		}
		$sLogDetail = $sMode.",".$sUid.",".$sColDate.",".$sColTime.",".$sLabStatus;
	}
}else if($sMode=="force_complete_lab"){
	if($sSID!=""){
		//error_log("Start revoke_order_status");
		$query =" UPDATE p_lab_order SET lab_order_status='A4'";
		$query .=" WHERE uid = ? AND collect_date = ? AND collect_time = ?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("sss",$sUid,$sColDate,$sColTime);

		if($stmt->execute()){
			//error_log("Complete revoke_order_status");
		}else{
			$msg_error = "Error Revoke Query";
			$msg_info = "Can't execute prepare update Fix Revoke Query";
		}


		//error_log("After revoke_order_status update lab process");
		$query =" UPDATE p_lab_process SET lab_process_status='P1'";
		$query .=" WHERE lab_serial_no IN (SELECT lab_serial_no FROM p_lab_result  WHERE uid = ? AND collect_date = ? AND collect_time = ?)";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("sss",$sUid,$sColDate,$sColTime);

		if($stmt->execute()){
			//error_log("After revoke_order_status update lab process COMPLETE");
		}else{
			$msg_error = "Error Update Lab Process Query";
			$msg_info = "Can't execute prepare update Fix Lab Process Query";
		}

		//error_log("After revoke_order_status update lab result");
		$query =" UPDATE p_lab_result SET lab_result_status='L1' WHERE uid = ? AND collect_date = ? AND collect_time = ?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("sss",$sUid,$sColDate,$sColTime);
		if($stmt->execute()){
			//error_log("After revoke_order_status update lab result COMPLETE");
		}else{
			$msg_error = "Error Update Lab Process Query";
			$msg_info = "Can't execute prepare update Fix Lab Process Query";
		}
		$sLogDetail = $sMode.",".$sUid.",".$sColDate.",".$sColTime.",".$sLabStatus;
	}
}
//Record Log
$query = "INSERT INTO j_db_fix_log (s_id,log_detail) VALUES(?,?);";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss",$sUser,$sLogDetail);
if($stmt->execute()){
	//error_log("After revoke_order_status update lab result COMPLETE");
}else{
	$msg_error = "Error Enter Log";
	$msg_info = "Can't Insert Log Details";
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