<?
// 02/09/2563

$Date = $_GET["last_date"];

//$Date = "2020-12-06";
echo "<br><br>28 days ". date('Y-m-d', strtotime($Date. ' + 28 days'));
echo "<br><br>84 days ". date('Y-m-d', strtotime($Date. ' + 84 days'));
echo "<br><br>168 days ". date('Y-m-d', strtotime($Date. ' + 168 days'));
//echo "<br><br>". date('Y-m-d', strtotime($Date. ' + 28 days'));
echo "<br><br>336 days ". date('Y-m-d', strtotime($Date. ' + 336 days'));
echo "<br><br>378 days ". date('Y-m-d', strtotime($Date. ' + 378 days'));
?>
