<?
// update uid to each uic
//include_once("../in_auth.php");
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function
include_once("../function/in_fn_number.php"); // number function

$year = isset($_GET["year"])?$_GET["year"]:""; 
//$year = "2015";
//$year = "2016";
//$year = "2017";
//$year = "2018";
//$year = "2019";

//$prefix = "P";
$prefix = "P".substr($year,2,2)."-" ;

$query = "SELECT uic, reg_date
FROM uic_gen
WHERE YEAR(reg_date) = $year
ORDER BY reg_date asc
";
//echo $query;

$run_no = 1;
$stmt = $mysqli->prepare($query);
if($stmt->execute()){
  $stmt->bind_result($uic, $reg_date);
  while ($stmt->fetch()) {

    $uid= $prefix.formatDigit($run_no, 5) ;
    $run_no++;
    echo "UPDATE uic_gen SET uid='$uid' where uic='$uic'; <br>";
  }// if
}
else{
  $msg_error .= $stmt->error;
}
$stmt->close();

$mysqli->close();
