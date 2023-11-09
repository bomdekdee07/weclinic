<?
$sSID = (isset($_GET["sid"])?$_GET["sid"]:"");

if($sSID==""){
  echo("Please select S_ID to assign authorization.");
  exit();
}

include_once("../in_auth_db.php");
include_once("../in_db_conn.php");

$query = "SELECT proj_id,allow_view,allow_enroll,allow_schedule,allow_data,allow_data_log,allow_lab,allow_export,allow_query,allow_delete,allow_data_backdate,allow_admin FROM p_staff_auth PSA WHERE PSA.s_id = ?;";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $sSID);
if($stmt->execute()){
	$stmt->bind_result($proj_id,$allow_view,$allow_enroll,$allow_schedule,$allow_data,$allow_data_log,$allow_lab,$allow_export,$allow_query,$allow_delete,$allow_data_backdate,$allow_admin);

	$sHtml = "";
	while ($stmt->fetch()) {
		$sHtml .= "<tr>
              <td class='tdprojid' data-projid='$proj_id'>$proj_id</td>
              <td><input data-chktype='view' type='checkbox' data-odata='$allow_view'  ".(($allow_view==1)?"checked='checked'":"")." /></td>
              <td><input data-chktype='enroll' type='checkbox' data-odata='$allow_enroll'  ".(($allow_enroll==1)?"checked='checked'":"")." /></td>
              <td><input data-chktype='schedule' type='checkbox' data-odata='$allow_schedule'  ".(($allow_schedule==1)?"checked='checked'":"")." /></td>
              <td><input data-chktype='data' type='checkbox' data-odata='$allow_data'  ".(($allow_data==1)?"checked='checked'":"")." /></td>
              <td><input data-chktype='data_log' type='checkbox' data-odata='$allow_data_log'  ".(($allow_data_log==1)?"checked='checked'":"")." /></td>
              <td><input data-chktype='lab' type='checkbox' data-odata='$allow_lab'  ".(($allow_lab==1)?"checked='checked'":"")." /></td>
              <td><input data-chktype='export' type='checkbox' data-odata='$allow_export'  ".(($allow_export==1)?"checked='checked'":"")." /></td>
              <td><input data-chktype='query' type='checkbox' data-odata='$allow_query'  ".(($allow_query==1)?"checked='checked'":"")." /></td>
              <td><input data-chktype='delete' type='checkbox' data-odata='$allow_delete'  ".(($allow_delete==1)?"checked='checked'":"")." /></td>
              <td><input data-chktype='data_backdate' type='checkbox' data-odata='$allow_data_backdate'  ".(($allow_data_backdate==1)?"checked='checked'":"")." /></td>
              <td><input data-chktype='admin' type='checkbox' data-odata='$allow_admin'  ".(($allow_admin==1)?"checked='checked'":"")." /></td>
            </tr>";
	}
	echo($sHtml);
}
$mysqli->close();
?>