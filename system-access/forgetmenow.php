<?
// clear cookie and send to login page
setcookie("prevention_mail", "", time() + (86400 * 30), "/"); // 86400 = 1 day
setcookie("prevention_name", "", time() + (86400 * 30), "/"); // 86400 = 1 day
header("Location: ../login.php");
die();

?>
