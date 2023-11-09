<?
function getQueryString($sName,$sDef=""){
	$sResult = (isset($_GET[$sName])?urlencode($_GET[$sName]):"");
	if($sResult=="") $sResult = (isset($_POST[$sName])?urlencode($_POST[$sName]):"");
	if($sResult=="null") $sResult=$sDef;
	return $sResult;
}

$sMode = getQueryString("u_mode");
$sUid = getQueryString("uid");
$sColDate = getQueryString("coldate");
$sColTime = urldecode(getQueryString("coltime"));
$msg_error = "";
$msg_info = "";
$sIsWait = getQueryString("iswait");
$sLabStatus = getQueryString("labstat");
include("../in_db_conn.php"); $rtn=array();
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
	}
}else if($sMode=="fix_order_status"){
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