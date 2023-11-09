<?
session_start();
$sUser = "";
if(isset($_SESSION)){ // there is session
  	$sUser = (isset($_SESSION["sc_id"])?$_SESSION["sc_id"]:"");
}else{
	//no session
	echo("ERROR no SESSION");
	exit();
}


include_once("../in_auth_db.php");
include_once("../in_db_conn.php");

$query = "SELECT clinic_id,clinic_name FROM p_clinic WHERE clinic_status=1;";
$stmt = $mysqli->prepare($query);

if($stmt->execute()){
  $stmt->bind_result($clinic_id,$clinic_name);

  $sClinicList = "";
  while ($stmt->fetch()) {
    $sClinicList .= "<option value='".$clinic_id."'>".$clinic_id." : ".$clinic_name."</option>";
  }
  $mysqli->close();
}

?>

<div>
	I want all staff in clinic <SELECT><option value=''>(....)</option><? echo($sClinicList); ?></SELECT> with these jobs
	<div>

	</div>
	to <SELECT></SELECT> these permission
</div>