<?
// UID Data Mgt
//include_once("../in_auth.php");
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function


$query = "SELECT uid, collect_date, collect_time,
specimen_id, specimen_amt, lab_group_id, laboratory_id,
barcode, lab_serial_no, specimen_status, in_stock,
time_specimen_check, time_specimen_destroy
FROM p_lab_order_specimen_collect as osp
ORDER BY uid
";

//echo $query;
$txt_row = "<br>INSERT INTO p_lab_order_specimen (uid, collect_date, collect_time, specimen_id, specimen_amt, barcode, specimen_status, in_stock,time_specimen_check ) VALUES ";
$txt_row2 = "<br>INSERT INTO p_lab_order_specimen_process (barcode, lab_group_id, laboratory_id, lab_serial_no) VALUES ";

$stmt = $mysqli->prepare($query);
if($stmt->execute()){
  $stmt->bind_result($uid, $collect_date, $collect_time,
  $specimen_id, $specimen_amt, $lab_group_id, $laboratory_id,
  $barcode, $lab_serial_no, $specimen_status, $in_stock,
  $time_specimen_check, $time_specimen_destroy);
  while ($stmt->fetch()) {
    $txt_row .= "
     <br>('$uid', '$collect_date', '$collect_time', '$specimen_id','$specimen_amt',
     '$barcode','$specimen_status','$in_stock', '$time_specimen_check'),
    ";

    $txt_row2 .= "
     <br>('$barcode','$lab_group_id','$laboratory_id','$lab_serial_no'),
    ";

  //  echo "$uid, $screen_date, $group_id <br> ";
  }// if
}
else{
  $msg_error .= $stmt->error;
}
$stmt->close();


echo " $txt_row<br><br>$txt_row2

";


$mysqli->close();
