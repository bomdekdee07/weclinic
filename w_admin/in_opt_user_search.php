<?
  	$sUser = (isset($_GET["user"])?$_GET["user"]:"");
  	$sProjId = (isset($_GET["projid"])?$_GET["projid"]:"");

  	if($sUser=="") $sUser = (isset($_POST["user"])?$_POST["user"]:"");
  	if($sProjId=="") $sProjId = (isset($_POST["projid"])?$_POST["projid"]:"");

  	
  	if($sUser==""){
  		exit();
  	}
  	$sUser = "%".$sUser."%";
  	include_once("../in_auth_db.php");
	include_once("../in_db_conn.php");
	$query = "SELECT s_id,sc_id,job_id,clinic_id FROM p_staff_clinic WHERE (s_id LIKE ? OR sc_id LIKE ?) AND clinic_id IN (SELECT clinic_id FROM p_project_clinic WHERE proj_id=?) ;";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('sss',$sUser,$sUser,$sProjId);
	$sUSErList = "<option value=''>(--Please select--)</option>";
	if($stmt->execute()){
	  $stmt->bind_result($s_id,$sc_id,$job_id,$clinic_id);

	  while ($stmt->fetch()) {
	    $sUSErList .= "<option value='".$s_id."' data-sid='".$s_id."' data-scid='".$sc_id."'  data-job='".$job_id."' data-cid='".$clinic_id."' title='".$s_id." : ".$job_id."'>".$s_id." : ".$sc_id." at ".$clinic_id."</option>";
	  }
	  
	}
	$mysqli->close();
	echo($sUSErList);

?>