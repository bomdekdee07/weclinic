<?
// update uid to each uic
//include_once("../in_auth.php");
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function
include_once("../function/in_fn_number.php"); // number function


$clinic_id = "SPT" ;
$clinic_id = "MCM" ;
$proj_id = "POC" ;

$query = "SELECT s_id, job_id
FROM p_staff_clinic
WHERE sc_status = 1 AND clinic_id='$clinic_id'
ORDER BY sc_id asc
";
//echo $query;

$stmt = $mysqli->prepare($query);
if($stmt->execute()){
  $stmt->bind_result($s_id, $job_id);
  echo "REPLACE INTO p_staff_auth (s_id, proj_id ,
    allow_view , allow_enroll, allow_schedule, allow_data,
    allow_lab , allow_export, allow_query
  ) VALUES ";

  while ($stmt->fetch()) {


    if($job_id == "CSL")
    echo "('$s_id', '$proj_id', '1', '1', '1', '1', '0', '1', '0'), <br>";
    else if($job_id == "CS")
    echo "('$s_id', '$proj_id', '1', '1', '1', '0', '0', '0', '0'), <br>";
    else if($job_id == "RCP")
    echo "('$s_id', '$proj_id', '1', '1', '1', '0', '0', '0', '0'), <br>";
    else if($job_id == "LB")
    echo "('$s_id', '$proj_id', '1', '0', '0', '0', '1', '0', '0'), <br>";

  }// if
}
else{
  $msg_error .= $stmt->error;
}
$stmt->close();

$mysqli->close();
