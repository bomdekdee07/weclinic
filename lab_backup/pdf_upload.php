<?
function getQueryString($sName,$sDef=""){
	$sResult = (isset($_GET[$sName])?urlencode($_GET[$sName]):"");
	if($sResult=="") $sResult = (isset($_POST[$sName])?urlencode($_POST[$sName]):"");
	if($sResult=="null") $sResult=$sDef;
	return $sResult;
} 
include_once("../in_auth_db.php");
$sUser = $_SESSION["s_id"];
$sUid = getQueryString("uid");
$sColDate = getQueryString("coldate");
$sColTime= urldecode(urldecode(getQueryString("coltime")));
$sFileDesc = urldecode(urldecode(getQueryString("filedesc")));

$sFileTime = str_replace(":", "", $sColTime);

/* Getting file name */
$sOriginalName = $_FILES['file']['name']; 
  
/* Location */
$sNewFName = $sUid."_".$sColDate."_".$sFileTime."_";
$iOrd = 1;
while(file_exists("pdf_result/".$sNewFName.$iOrd.".pdf")){
	$iOrd++;
}

$sNewFName .= $iOrd.".pdf";

$sUpPath = "pdf_result/".$sNewFName; 
$isSuccess = 1; 

/* Upload file */
if(move_uploaded_file($_FILES['file']['tmp_name'], $sUpPath)){ 

	include("../in_db_conn.php"); 
	//error_log("After revoke_order_status update lab result");
	$query =" INSERT INTO p_lab_result_pdf(uid,collect_date,collect_time,file_id,file_text,file_name) VALUES(?,?,?,?,?,?);";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ssssss",$sUid,$sColDate,$sColTime,$iOrd,$sFileDesc,$sOriginalName);
	if($stmt->execute()){
		//error_log("After revoke_order_status update lab result COMPLETE");
		$sLogDetail = "Upload PDF,".$sUser.",".$sUid.",".$sColDate.",".$sColTime.",".$iOrd.",".$sFileDesc.",".$sOriginalName;

	}else{
		$msg_error = "Error Insert Lab PDF";
		$msg_info = "Can't execute prepare Insert Lab PDF";
	}

	//Update Log
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

}else{ 
	error_log("Not uploaded because of error #".$_FILES["file"]["error"]);
  $isSuccess = 0; 
} 
$sLogDetail="";




if($isSuccess==0){
	echo($isSuccess);
}else{
	//echo($iOrd.",".$sFileDesc.",".$sOriginalName);
	echo("<option value='".$iOrd."' title='".$iOrd."' >".$iOrd.":".$sFileDesc."</option>");
}



?> 