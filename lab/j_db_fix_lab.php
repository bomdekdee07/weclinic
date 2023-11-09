<?
function getQS2($sName,$sDef=""){
	$sResult = (isset($_GET[$sName])?urlencode($_GET[$sName]):"");
	if($sResult=="") $sResult = (isset($_POST[$sName])?urlencode($_POST[$sName]):"");
	if($sResult=="null") $sResult=$sDef;
	return $sResult;
}
include_once("../in_auth_db.php");

$sMode = getQS2("u_mode");
$sUid = getQS2("uid");
$sColDate = getQS2("coldate");
$sColTime = urldecode(urldecode(getQS2("coltime")));
$sLabId = urldecode(urldecode(getQS2("labid")));
$sBarcode = getQS2("barcode");
$sSpecId = getQS2("specid");
$sSerialNo = getQS2("serialno");
$sReason =  urldecode(urldecode(getQS2("reason")));
$sResult = urldecode(urldecode(getQS2("result")));
$sLabStatus = getQS2("labstat");

$msg_error = "";
$msg_info = "";
$sIsWait = getQS2("iswait");

$sUser = $_SESSION["s_id"];
$sLogDetail="";
include("../in_db_conn.php"); $rtn=array();

//error_log($sUid.":".$sColDate.":".$sColTime);
$sSID = ""; $sRoom = ""; $sTime = "";
$bIsSuccess = true;



if($sSID=="") $sSID=$sUser;
$today = date("Y-m-d h:i:s");
if($sTime=="" || $sTime=="0000-00-00 00:00:00"){
	$sTime= $today;
}

if($sMode=="fix_missing_user"){
	$sUpdateDate = "";
	$sUpdateUser= "";
	$query ="SELECT update_user,update_date
	FROM a_log_cmd
	WHERE update_date > ? AND sql_cmd LIKE '[Lab_Order]%".$sUid."%'";	
	$sKeyId = "[Lab_Order]%".$sUid."%";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("s",$sColDate);

	if($stmt->execute()){
		$stmt->bind_result($update_user,$update_date );
		while ($stmt->fetch()) {
			$sUpdateUser = $update_user; 
			$sUpdateDate = $update_date;
		}
	}else{
		$msg_error = "Error Fix SID Query";
		$msg_info = "Can't execute prepare update Fix SID Query";
	}

	if($sUpdateDate!=""){
		$query =" UPDATE p_lab_order SET staff_order=?,staff_order_room='2' ";
		$query .=" WHERE uid = ? AND collect_date = ? AND collect_time = ? AND staff_order=''";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("ssss",$sUpdateUser,$sUid,$sColDate,$sColTime);
		if($stmt->execute()){
			$sLogDetail = $sMode.",".$sUid.",".$sColDate.",".$sColTime.",".$sUpdateUser.",'2',".$sTime;
		}else{
			$msg_error = "Error Fix SID Query";
			$msg_info = "Can't execute prepare update Fix SID Query";
		}
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
}else if($sMode=="expand_lab"){
	//GET LabId
	$sLabGroupId=""; $sLaboratoryId = ""; $sBarcode=""; $sSerialNo=""; $sSaleOpt="";
	$query = "SELECT lab_group_id,laboratory_id,sale_opt_id FROM p_lab_order_lab_test 
	WHERE uid=? AND collect_date=? AND collect_time=? AND lab_id=?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ssss",$sUid,$sColDate,$sColTime,$sLabId);
	if($stmt->execute()){
	  $stmt->bind_result($lab_group_id,$laboratory_id,$sale_opt_id);
	  while ($stmt->fetch()) {
	  	 $sLaboratoryId = $laboratory_id;
	  	 $sLabGroupId = $lab_group_id;
	  	 $sSaleOpt = $sale_opt_id;
	  }
	}

	$query = "SELECT barcode,lab_serial_no FROM p_lab_result 
	WHERE uid=? AND collect_date=? AND collect_time=? AND lab_id=?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ssss",$sUid,$sColDate,$sColTime,$sLabId);
	if($stmt->execute()){
	  $stmt->bind_result($barcode,$lab_serial_no);
	  while ($stmt->fetch()) {
	  	 $sBarcode = $barcode;
	  	 $sSerialNo = $lab_serial_no;
	  }
	}

	$query =" INSERT IGNORE INTO p_lab_order_lab_test(uid,collect_date,collect_time,lab_id,lab_group_id,laboratory_id,sale_opt_id)
		SELECT ?,?,?,lab_id,?,?,?
		FROM p_lab_test 
		WHERE is_disable=0 
		AND lab_group_id=? ";
	$stmt = $mysqli->prepare($query);

	$stmt->bind_param("sssssss",$sUid,$sColDate,$sColTime,$sLabGroupId,$sLaboratoryId,$sSaleOpt,$sLabGroupId);


	if($stmt->execute()){
	}else{
		$msg_error = "Error Expand Lab";
		$msg_info = "Can't execute prepare update Expand Query";
	}


 //Add all even it is not in p_lab_order_lab_test
	$query =" INSERT IGNORE INTO p_lab_result(uid,collect_date,collect_time,lab_id,barcode,lab_serial_no,lab_result_status)
		SELECT ?,?,?,lab_id,?,?,'L0'

		FROM p_lab_test 

		WHERE is_disable=0 
		AND lab_group_id=? ";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ssssss",$sUid,$sColDate,$sColTime,$sBarcode,$sSerialNo,$sLabGroupId);

	if($stmt->execute()){
	}else{
		$msg_error = "Error Expand Lab";
		$msg_info = "Can't execute prepare update Expand Query";
	}


	$sLogDetail = $sMode.",".$sUid.",".$sColDate.",".$sColTime.",".$sLabId.",".$sBarcode.",".$sSerialNo.":".$msg_error;
}else if($sMode=="remove_lab_id"){
	//GET LabId
	$query = "INSERT INTO p_lab_result_log (uid, collect_date, collect_time, lab_id, barcode, lab_serial_no, lab_result, lab_result_report, lab_result_note, lab_result_status,log_note) 
		SELECT uid, collect_date, collect_time, lab_id, barcode, lab_serial_no, lab_result, lab_result_report, lab_result_note, lab_result_status,? 
		FROM p_lab_result WHERE uid=? AND collect_date=? AND collect_time=? AND barcode=? AND lab_serial_no=? AND lab_id=?;";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sssssss",$sReason,$sUid,$sColDate,$sColTime,$sBarcode,$sSerialNo,$sLabId);

	if($stmt->execute()){

	}else{
		$msg_error = "Error Insert Lab Id to Log ".$sLabId;
		$msg_info = "Can't execute Insert Lab Id Into Log";
	}

	$query =" DELETE FROM p_lab_result
		WHERE uid=? AND collect_date=? AND collect_time=? AND barcode=? AND lab_serial_no=? AND lab_id=?";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ssssss",$sUid,$sColDate,$sColTime,$sBarcode,$sSerialNo,$sLabId);

	if($stmt->execute()){

	}else{
		$msg_error = "Error Delete Lab Id ".$sLabId;
		$msg_info = "Can't execute Delete Lab Id Query";
	}
	$sLogDetail = $sMode.",".$sUid.",".$sColDate.",".$sColTime.",".$sLabId.",".$sBarcode.",".$sSerialNo.":".$msg_error.":".$sReason;
}else if($sMode=="restore_lab_log"){
	//GET LabId

	$query = "INSERT INTO p_lab_result(uid, collect_date, collect_time, lab_id, barcode, lab_serial_no, lab_result, lab_result_report, lab_result_note, lab_result_status) 
		SELECT uid, collect_date, collect_time, lab_id, barcode, lab_serial_no, lab_result, lab_result_report, lab_result_note, lab_result_status
		FROM p_lab_result_log WHERE uid=? AND collect_date=? AND collect_time=? AND barcode=? AND lab_serial_no=? AND lab_id=? AND lab_result_status=? AND lab_result=?;";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ssssssss",$sUid,$sColDate,$sColTime,$sBarcode,$sSerialNo,$sLabId,$sLabStatus,$sResult);

	if($stmt->execute()){

	}else{
		$msg_error = "Error Restore Lab Id from Log ".$sLabId;
		$msg_info = "Can't execute Restore Lab Id from Log";
	}

	$sLogDetail = $sMode.",".$sUid.",".$sColDate.",".$sColTime.",".$sLabId.",".$sBarcode.",".$sSerialNo.",".$sLabStatus.",".$sResult.":".$msg_error.":".$sReason;
}



//Record Log
if($sLogDetail!=""){
	$query = "INSERT INTO j_db_fix_log (s_id,log_detail) VALUES(?,?);";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ss",$sUser,$sLogDetail);
	if($stmt->execute()){
		//error_log("After revoke_order_status update lab result COMPLETE");
	}else{
		$msg_error = "Error Enter Log";
		$msg_info = "Can't Insert Log Details";
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