<?
  	$sSCID = (isset($_GET["scid"])?$_GET["scid"]:"");
  	if($sSCID=="") $sSCID = (isset($_POST["scid"])?$_POST["scid"]:"");
  	
  	$sProjId = (isset($_GET["projid"])?$_GET["projid"]:"");
  	if($sProjId=="") $sProjId = (isset($_POST["projid"])?$_POST["projid"]:"");

  	if($sSCID=="" || $sProjId == ""){
  		exit();
  	}

  	include_once("../in_auth_db.php");
	include_once("../in_db_conn.php");
	$query = "SELECT visit_id,visit_status FROM p_project_uid_visit WHERE uid = ? AND proj_id = ? ORDER BY schedule_date;";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('ss',$sUID,$sProjId);
	$sOptList = "<option value=''>(--Please select--)</option>";
	if($stmt->execute()){
	  $stmt->bind_result($visit_id,$visit_status);


	  while ($stmt->fetch()) {
	    $sOptList .= "<option value='".$visit_id."'  data-vstatus='".$visit_status."'  title='".$visit_id." : ".$visit_status."'>".$visit_id."</option>";
	  }
	  
	}
	$mysqli->close();
	echo($sOptList);

?>