<?
/*
$host="localhost"; // Host name
$username="root"; // Mysql username
$password="p1234"; // Mysql password
//$db_name="iclinic_r"; // Database name
//$db_name="iclinic_r_demo"; // Database name
//$db_name="iclinic_r25122019"; // Database name

$db_name="weclinic"; // Database name
//$db_name="weclinic2"; // Database name
//$db_name="weclinic3"; // Database name
*/

// $username = 'pre_pribta';
// $password = 'prib8520';
$host = 'localhost';
// $db_name = 'pre_weclinic2';

// $username= "iclinic"; //"root";  // Mysql username
// $password= "cinilci14"; //"P@ssw0rd"; // Mysql password
// $host="127.0.0.1"; // Host name
// $username="root"; // Mysql username
// $password="ok"; // Mysql password
//$db_name="iclinic_29092022"; // Database name
//$db_name="iclinic_12092022_1"; // Database name
// $db_name="iclinic_test"; // Database name

$username= "root"; //"iclinic"; // Mysql username
$password= "P@ssw0rd";//"cinilci14"; // Mysql password
$db_name="iclinic"; // Database name


$mysqli = new mysqli($host, $username, $password, $db_name);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
mysqli_set_charset($mysqli,'utf8');

?>
