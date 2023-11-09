<?
// update uid to each uic
//include_once("../in_auth.php");
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function
include_once("../function/in_fn_number.php"); // number function


//$clinic_id = "SPT" ;
$clinic_id = "IHRI" ;


$query = "SELECT s_id, sc_id
FROM p_staff_clinic
WHERE sc_status = 1 AND clinic_id='$clinic_id' 
ORDER BY sc_id asc
";
//echo $query;

$stmt = $mysqli->prepare($query);
if($stmt->execute()){
  $stmt->bind_result($s_id, $sc_id);

  $string = 'abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
  while ($stmt->fetch()) {
    $string_shuffled = str_shuffle($string);
    $new_pwd = substr($string_shuffled, 1, 6);
    $inQuery = "UPDATE p_staff_clinic SET sc_pwd ='$new_pwd' WHERE sc_id='$sc_id'; ";
    echo "$inQuery <br>";

  }// if
}
else{
  $msg_error .= $stmt->error;
}
$stmt->close();

$mysqli->close();
