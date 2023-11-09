<?
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
$sUser = "";
if(isset($_SESSION)){ // there is session
  	$sUser = (isset($_SESSION["s_id"])?$_SESSION["s_id"]:"");
}else{
	//no session
	echo("ERROR:No SESSION. Please login again.");
	exit();
}

include("in_php_function.php");

$sMode = getQueryString("mode");
$sProjId = getQueryString("projid");
$sUid = getQueryString("uid");
$sClinicId = getQueryString("cid");
$sReqStat = getQueryString("reqid");
$sReqNote = urldecode(getQueryString("reqnote"));
$sStatId  = getQueryString("statid");
$sVid = getQueryString("vid");
$sSchDate = getQueryString("schdate");
$sSID = getQueryString("sid");
$sSCID = getQueryString("scid");
$sJob = getQueryString("job");
if($sProjId == ""){
	echo("ERROR:No projid found.");
	exit();
}
if($sUid == ""  && $sMode!="5"){
	echo("ERROR:No uid found.");
	exit();
}


include_once("../in_auth_db.php");
include_once("../in_db_conn.php");

$sLogQuery = "";
if($sMode=="1"){
	if($sClinicId == ""){
		echo("ERROR:No ClinicID found");
		exit();
	}

	$query = "UPDATE p_project_uid_list SET clinic_id=? WHERE uid=? AND proj_id=?;";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sss", $sClinicId,$sUid,$sProjId);
	if($stmt->execute()){

		$iAffRow = $stmt->affected_rows;
		if($iAffRow > 0){
			//Save Log
			$sLogQuery = "INSERT INTO site_request_form(uid,req_type,proj_id,target_id,target_data,request_by,request_time,approved_by,approved_time,req_status,req_note) VALUES(?,?,?,'clinic_id',?,?,NOW(),?,NOW(),?,?);";
				$stmt = $mysqli->prepare($sLogQuery);
				$stmt->bind_param("ssssssss", $sUid,$sMode,$sProjId,$sClinicId,$sUser,$sUser,$sReqStat,$sReqNote);
				if($stmt->execute()){
					$iAffRow = $mysqli->affected_rows;
				}
			echo("SUCCESS");
		}else{
			echo("ERROR:No row save. Please refresh and try again.");
		}
	}

}else if($sMode=="2"){
	if($sStatId == ""){
		echo("ERROR:No Status ID found");
		exit();
	}

	$query = "UPDATE p_project_uid_visit SET visit_status=? WHERE uid=? AND visit_id=? AND proj_id=?;";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ssss", $sStatId,$sUid,$sVid,$sProjId);
	if($stmt->execute()){

		$iAffRow = $stmt->affected_rows;
		if($iAffRow > 0){
			//Save Log
			$sLogQuery = "INSERT INTO site_request_form(uid,req_type,proj_id,target_id,target_data,request_by,request_time,approved_by,approved_time,req_status,req_note) VALUES(?,?,?,'visit_status',?,?,NOW(),?,NOW(),?,?);";
				$stmt = $mysqli->prepare($sLogQuery);
				$stmt->bind_param("ssssssss", $sUid,$sMode,$sProjId,$sStatId,$sUser,$sUser,$sReqStat,$sReqNote);
				if($stmt->execute()){
					$iAffRow = $mysqli->affected_rows;
				}
			echo("SUCCESS");
		}else{
			echo("ERROR:No row save. Please refresh and try again.");
		}
	}

}else if($sMode=="3"){
	if($sSchDate == ""){
		echo("ERROR:No Status ID found");
		exit();
	}
	if($sVid == ""){
		echo("ERROR:No Visit Origin found");
		exit();
	}

	$query = "INSERT INTO p_project_uid_visit(uid,uic,proj_id,visit_id,group_id,schedule_date,visit_date,visit_main,visit_status,visit_clinic_id)

	SELECT uid,uic,proj_id,'EX',group_id,?,?,visit_main,'20','%' FROM p_project_uid_visit WHERE uid=? AND proj_id=? AND visit_id=? ;";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sssss", $sSchDate,$sSchDate,$sUid,$sProjId,$sVid);
	if($stmt->execute()){

		$iAffRow = $stmt->affected_rows;
		if($iAffRow > 0){
			//Save Log
			$sLogQuery = "INSERT INTO site_request_form(uid,req_type,proj_id,target_id,target_data,request_by,request_time,approved_by,approved_time,req_status,req_note) VALUES(?,?,?,'visit_status',?,?,NOW(),?,NOW(),?,?);";
				$stmt = $mysqli->prepare($sLogQuery);
				$tempTarget=$sVid.":".$sSchDate;
				$stmt->bind_param("ssssssss", $sUid,$sMode,$sProjId,$tempTarget,$sUser,$sUser,$sReqStat,$sReqNote);
				if($stmt->execute()){
					$iAffRow = $mysqli->affected_rows;
				}
			echo("SUCCESS");
		}else{
			echo("ERROR:No row save. Please refresh and try again.");
		}
	}

}else if($sMode=="5"){
	if($sSCID == ""){
		echo("ERROR:No Status ID found");
		exit();
	}

	$query = "UPDATE p_staff_clinic SET job_id=? WHERE s_id=? AND sc_id=? AND clinic_id=?;";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ssss", $sJob,$sSID,$sSCID,$sClinicId);
	if($stmt->execute()){
		$iAffRow = $stmt->affected_rows;
		if($iAffRow > 0){
			//Save Log
			/*
			$sLogQuery = "INSERT INTO site_request_form(uid,req_type,proj_id,target_id,target_data,request_by,request_time,approved_by,approved_time,req_status,req_note) VALUES(?,?,?,'visit_status',?,?,NOW(),?,NOW(),?,?);";
				$stmt = $mysqli->prepare($sLogQuery);
				$stmt->bind_param("ssssssss", $sUid,$sMode,$sProjId,$sStatId,$sUser,$sUser,$sReqStat,$sReqNote);
				if($stmt->execute()){
					$iAffRow = $mysqli->affected_rows;
				}
			echo("SUCCESS");
			*/
		}else{
			echo("ERROR:No row save. Please refresh and try again.");
		}
	}

}






//SAVE LOG HERE
$mysqli->close();


?>