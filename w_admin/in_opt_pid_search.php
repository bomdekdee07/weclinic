<?
  	$sPID = (isset($_GET["pid"])?$_GET["pid"]:"");

  	$sProjId = (isset($_GET["projid"])?$_GET["projid"]:"");

  	if($sPID=="") $sPID = (isset($_POST["pid"])?$_POST["pid"]:"");
  	if($sProjId=="") $sProjId = (isset($_POST["projid"])?$_POST["projid"]:"");

  	
  	if($sPID==""){
  		exit();
  	}
  	$sPID = "%".$sPID."%";
  	include_once("../in_auth_db.php");
	include_once("../in_db_conn.php");
	$query = "SELECT uid,pid,clinic_id FROM p_project_uid_list WHERE pid LIKE ? AND proj_id=?;";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('ss',$sPID,$sProjId);
	$sPIDList = "<option value=''>(--Please select--)</option>";
	if($stmt->execute()){
	  $stmt->bind_result($uid,$pid,$clinic_id);


	  while ($stmt->fetch()) {
	    $sPIDList .= "<option value='".$uid."' data-uid='".$uid."' data-pid='".$pid."'  data-clinic='".$clinic_id."' title='".$uid." : ".$clinic_id."'>".$pid."</option>";
	  }
	  
	}
	$mysqli->close();
	echo($sPIDList);

?>