<?
include("in_db_conn.php");

function getQueryString($sName,$sDef=""){
	$sResult = (isset($_GET[$sName])?urlencode($_GET[$sName]):"");
	if($sResult=="") $sResult = (isset($_POST[$sName])?urlencode($_POST[$sName]):"");
	if($sResult=="null") $sResult=$sDef;
	return $sResult;
}

$sUid=getQueryString("uid");

$query = "SELECT lab_order_id,uid,collect_date,collect_time,ttl_cost,ttl_sale,PLO.lab_order_status ,name
FROM p_lab_order PLO
LEFT JOIN p_lab_status PLS 
ON PLS.id = PLO.lab_order_status
WHERE uid=?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("s",$sUid);
$sHtmlLab = "";

if($stmt->execute()){
  $stmt->bind_result($lab_order_id,$uid,$collect_date,$collect_time,$ttl_cost,$ttl_sale,$lab_order_status ,$name );
  while ($stmt->fetch()) {

    $sHtmlLab .= "<tr>
			<th>$lab_order_id</th>
			<th>$uid</th>
			<th>$collect_date</th>
			<th>$collect_time</th>
			<th>$ttl_sale</th>
			<th>$name</th>
			<th><i  class='fa fa-search ibtn btnViewUid' data-uid='$uid' data-coldate='$collect_date' data-coltime='$collect_time'> FIND</i></th>
		</tr>";
  }
}
$mysqli->close();
?>
<div class='div-auto'>
	<table style='font-size: small;width:100%;' cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th>Lab Order</th>
				<th>UID</th>
				<th>Date</th>
				<th>Time</th>
				<th>Sale</th>
				<th>Status</th>
				<th>CMD</th>
			</tr>
		</thead>
		<tbody>
			<? echo($sHtmlLab); ?>
		</tbody>
	</table>
</div>