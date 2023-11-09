<?
session_start();
$sUser = "";
if(isset($_SESSION)){ // there is session
  	$sUser = (isset($_SESSION["s_id"])?$_SESSION["s_id"]:"");
}else{
	//no session
	echo("ERROR no SESSION");
	exit();
}

$sMode = (isset($_GET["mode"])?$_GET["mode"]:"");
if($sMode==""){
	$sMode = (isset($_POST["mode"])?$_POST["mode"]:"");
}
if($sMode==""){
	echo("ERROR Mode Verification");
	exit();
}



$sSID = (isset($_GET["sid"])?$_GET["sid"]:"");
if($sSID=="") $sSID = (isset($_POST["sid"])?$_POST["sid"]:"");
if($sSID==""){
	echo("ERROR Data Incomplete");
	exit();
}

include_once("../in_auth_db.php");
include_once("../in_db_conn.php");

$sLogQuery = "";
if($sMode=="a"){
	$sProjId = (isset($_GET["pjid"])?$_GET["pjid"]:"");

	$query = "INSERT INTO p_staff_auth(s_id,proj_id) VALUES(?,?) ;";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ss", $sSID,$sProjId);
	if($stmt->execute()){
		echo("DONE");
		$sLogQuery .= $query;
	}else{
		echo("ERROR SQL");
	}

}else if($sMode=="mup"){
	$aObjArray = isset($_REQUEST["objArray"])?$_REQUEST["objArray"]:array();
	$sId = isset($_POST["sid"])?$_POST["sid"]:array();
	$sSQL = "";
	foreach ($aObjArray as $key => $sObj) {
		$aObj = explode(",",$sObj);
		$sWTempSQL = ""; $sTempSQL = "";
		foreach ($aObj as $key2 => $sData) {
			$aData = explode(":",$sData);

			if($aData[0]=="pjid") $sWTempSQL .= " WHERE proj_id='".$aData[1]."' AND s_id = '".$sId."';";
			else{
				$sTempSQL .= (($sTempSQL=="")?" ":", ")." allow_".$aData[0]."=".$aData[1];
			}
		}
		$sSQL .= "UPDATE p_staff_auth SET ".$sTempSQL." ".$sWTempSQL;
	}
	if($sSQL != ""){
		$mysqli->multi_query($sSQL);
		$iAffRow = $mysqli->affected_rows;
		echo("ROW UPDATE : ".$iAffRow);
	}
	//error_log($sSQL);
}

//SAVE LOG HERE
$mysqli->close();

?>