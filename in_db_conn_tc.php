<?

// trackchage database
/*
$host_tc="localhost"; // Host name
$username_tc="root"; // Mysql username
$password_tc="p1234"; // Mysql password

$db_name_tc="weclinic_tc"; // Database name
 */
/*
$host_tc="localhost"; // Host name
$username_tc="pre_weclinic_tc"; // Mysql username
$password_tc="e7wdA85y7H"; // Mysql password
$db_name_tc="pre_weclinic_tc"; // Database name
*/

$host_tc="localhost"; // Host name
$username_tc="root"; // Mysql username
$password_tc="P@ssw0rd"; // Mysql password
$db_name_tc="iclinic"; // Database name


/*
$host_tc="localhost"; // Host name
$username_tc="iclinic"; // Mysql username
$password_tc="cinilci14"; // Mysql password
$db_name_tc="test_weclinic_tc"; // Database name
*/
$mysqli_tc = new mysqli($host_tc, $username_tc, $password_tc, $db_name_tc);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
mysqli_set_charset($mysqli_tc,'utf8');

?>
