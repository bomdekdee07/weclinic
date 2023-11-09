<?
// update uid to each uic
//include_once("../in_auth.php");
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function
include_once("../function/in_fn_number.php"); // number function


$clinic_id = "SBK" ;


$query = "SELECT uid, visit_date FROM `p_project_uid_visit`
where visit_id='M1' and visit_date <> '0000-00-00' ORDER BY `uic` ASC
";
//echo $query;
$inQuery1="";
$inQuery2="";
$stmt = $mysqli->prepare($query);
if($stmt->execute()){
  $stmt->bind_result($uid, $visit_date);

  while ($stmt->fetch()) {

    $inQuery1 .= "UPDATE x_specimen_collect SET specimen_plasma=specimen_syphilis,
    specimen_plasma_no_txt = specimen_syphilis_no_txt
    WHERE uid='$uid' AND collect_date='$visit_date'; <br>";

    $inQuery2 .= "UPDATE x_specimen_collect SET specimen_syphilis=NULL,
    specimen_syphilis_no_txt = ''
    WHERE uid='$uid' AND collect_date='$visit_date'; <br>";

  }// if
}
else{
  $msg_error .= $stmt->error;
}
$stmt->close();

echo "$inQuery1 <br><br>$inQuery2";

$mysqli->close();
