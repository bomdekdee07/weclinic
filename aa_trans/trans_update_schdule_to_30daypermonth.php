<?
// UID Data Mgt
//include_once("../in_auth.php");
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function

$proj_id = "POC";

$visitDateAmt = array();
$visitDateAmt["M1"] = "30 days";
$visitDateAmt["M3"] = "90 days";
$visitDateAmt["M6"] = "180 days";
$visitDateAmt["M9"] = "270 days";
$visitDateAmt["M12"]= "360 days";

$arr_uid = array();
$query = "SELECT uid, group_id, visit_id, schedule_date FROM p_project_uid_visit
WHERE proj_id='POC' AND (visit_date = '0000-00-00' || visit_id='M0')
ORDER BY group_id, uid, schedule_date
";

//echo $query;
$M0_date = "";
$stmt = $mysqli->prepare($query);
if($stmt->execute()){
  $stmt->bind_result($uid, $group_id, $visit_id, $schedule_date);
  while ($stmt->fetch()) {
    if($visit_id == "M0"){
      $M0_date = $schedule_date;
    }
    else{ // other month
      $m0_begin_date=date_create($M0_date);
      date_add($m0_begin_date,date_interval_create_from_date_string($visitDateAmt[$visit_id]));
      $new_schedule_date = date_format($m0_begin_date,"Y-m-d");
      //echo "<br>$M0_date - UPDATE p_project_uid_visit SET schedule_date='$new_schedule_date' WHERE uid ='$uid' AND group_id='$group_id' AND visit_id='$visit_id' AND schedule_date='$schedule_date'; ";
      echo "<br>$M0_date - UPDATE p_project_uid_visit SET schedule_date='$new_schedule_date' WHERE uid ='$uid' AND group_id='$group_id' AND visit_id='$visit_id' AND schedule_date='$schedule_date'; ";

    }
  }// if
}
else{
  $msg_error .= $stmt->error;
}
$stmt->close();



$mysqli->close();
