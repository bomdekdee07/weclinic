<?
include("in_db_conn.php");

function getQueryString($sName,$sDef=""){
	$sResult = (isset($_GET[$sName])?urlencode($_GET[$sName]):"");
	if($sResult=="") $sResult = (isset($_POST[$sName])?urlencode($_POST[$sName]):"");
	if($sResult=="null") $sResult=$sDef;
	return $sResult;
}

$sUid=getQueryString("uid");
$sColDate=getQueryString("coldate");
$sColTime=urldecode(urldecode(getQueryString("coltime")));

$query = "SELECT PLOS.barcode,PLOS.specimen_id,PLP.lab_serial_no,PLP.laboratory_id,PLP.lab_group_id,lab_process_status, PLS.name , specimen_status, PLS2.name AS specimen_status_txt,PLSP.specimen_name  FROM p_lab_process PLP
LEFT JOIN p_lab_status PLS ON PLS.id = PLP.lab_process_status

JOIN p_lab_order_specimen_process PLOSP
ON PLOSP.lab_serial_no = PLP.lab_serial_no
AND PLOSP.lab_group_id = PLP.lab_group_id
AND PLOSP.laboratory_id = PLP.laboratory_id
JOIN p_lab_order_specimen PLOS
ON PLOS.barcode = PLOSP.barcode
LEFT JOIN p_lab_status PLS2 ON PLS2.id = PLOS.specimen_status

LEFT JOIN p_lab_specimen PLSP
ON PLSP.specimen_id = PLOS.specimen_id

WHERE uid=? AND collect_date=? AND collect_time=?";


$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss",$sUid,$sColDate,$sColTime);
$sLabProc = "";

if($stmt->execute()){
  $stmt->bind_result($barcode,$specimen_id,$lab_serial_no,$laboratory_id,$lab_group_id,$lab_process_status,$name,$specimen_status,$specimen_status_txt,$specimen_name );
  while ($stmt->fetch()) {

    $sLabProc .= "<tr>
			<th>$barcode<br/>$lab_serial_no</th>
			<th title='$specimen_id'>$specimen_name<br/>$specimen_status_txt</th>
			<th>$lab_group_id<br/>$laboratory_id</th>
			<th>$name</th>
		</tr>";
  }
}




$query = "SELECT PLOLT.lab_id,PLT.lab_name,PLOLT.laboratory_id,PLOLT.lab_group_id FROM p_lab_order_lab_test PLOLT
LEFT JOIN p_lab_test PLT
ON PLT.lab_id = PLOLT.lab_id
AND PLT.lab_group_id = PLT.lab_group_id

WHERE uid=? AND collect_date=? AND collect_time=?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss",$sUid,$sColDate,$sColTime);
$sLabTest = "";

if($stmt->execute()){
  $stmt->bind_result($lab_id,$lab_name,$laboratory_id,$lab_group_id );
  while ($stmt->fetch()) {

    $sLabTest .= "<tr>
			<th>$lab_id<br/>$lab_name</th>
			<th>$lab_group_id</th>
			<th>$laboratory_id</th>
		</tr>";
  }
}

$query = "SELECT PLR.lab_id,PLT.lab_name,PLR.barcode,PLR.lab_serial_no,lab_result,lab_result_status,name FROM p_lab_result PLR
LEFT JOIN p_lab_test PLT
ON PLT.lab_id = PLR.lab_id
LEFT JOIN p_lab_status PLS
ON PLS.id = PLR.lab_result_status
WHERE uid=? AND collect_date=? AND collect_time=?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss",$sUid,$sColDate,$sColTime);
$sLabResult = "";

if($stmt->execute()){
  $stmt->bind_result($lab_id,$lab_name,$barcode,$lab_serial_no,$lab_result,$lab_result_status,$name );
  while ($stmt->fetch()) {

    $sLabResult .= "<tr>
			<th>$lab_id<br/>$lab_name</th>
			<th>$barcode<br/>$lab_serial_no</th>
			<th>$lab_result</th>
			<th>$name</th>
		</tr>";
  }
}

$query = "SELECT PLR.lab_id,PLT.lab_name,PLR.barcode,PLR.lab_serial_no,lab_result,lab_result_status,name FROM p_lab_result_log PLR
LEFT JOIN p_lab_test PLT
ON PLT.lab_id = PLR.lab_id
LEFT JOIN p_lab_status PLS
ON PLS.id = PLR.lab_result_status
WHERE uid=? AND collect_date=? AND collect_time=?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss",$sUid,$sColDate,$sColTime);
$sLabLog = "";

if($stmt->execute()){
  $stmt->bind_result($lab_id,$lab_name,$barcode,$lab_serial_no,$lab_result,$lab_result_status,$name );
  while ($stmt->fetch()) {

    $sLabLog .= "<tr>
			<th>$lab_id<br/>$lab_name</th>
			<th>$barcode<br/>$lab_serial_no<br/><button class='btnrestorelab' data-labid='".$lab_id."' data-barcode='".$barcode."' data-serial_no='".$lab_serial_no."' data-labstatus='".$lab_result_status."' data-result='".$lab_result."' data-uid='".$sUid."'  data-coldate='".$sColDate."'  data-coltime='".$sColTime."'>Restore</button><img class='spinner' src='image/spinner.gif' style='hight:30px;display:none' /></th>
			<th>$lab_result</th>
			<th>$name</th>
		</tr>";
  }
}

$mysqli->close();
?>

<div class='div-auto' style='overflow: auto;border-right:1px solid white'>
	<table style='font-size: small'>
		<thead>
			<tr>
				<th>Barcode/Serial#</th>
				<th>Specimen_id</th>
				
				<th>Group/Lab</th>
				
				
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<? echo($sLabProc); ?>
		</tbody>
	</table>
</div>

<div class='div-auto' style='overflow: auto;border-right:1px solid white'>
	<table style='font-size: small'>
		<thead>
			<tr>
				<th>Lab Name</th>
				<th>Group</th>
				<th>Lab</th>
			</tr>
		</thead>
		<tbody>
			<? echo($sLabTest); ?>
		</tbody>
	</table>
</div>

<div class='div-auto' style='overflow: auto;border-right:1px solid white'>
	<table style='font-size: small'>
		<thead>
			<tr>
				<th>Lab Name</th>
				<th>Barcode/SerialNo</th>
				<th>Result</th>
				<th>Status</th>

			</tr>
		</thead>
		<tbody>
			<? echo($sLabResult); ?>
		</tbody>
	</table>
</div>

<div class='div-auto' style='overflow: auto;border-right:1px solid white'>
	<table style='font-size: small;color:red'>
		<thead>
			<tr>
				<th>Lab Log</th>
				<th>Barcode/SerialNo</th>
				<th>Result</th>
				<th>Status</th>

			</tr>
		</thead>
		<tbody>
			<? echo($sLabLog); ?>
		</tbody>
	</table>
</div>
