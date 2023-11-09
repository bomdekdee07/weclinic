<?
$now = time(); // or your date as well
$date1 = strtotime("2020-11-02");
//$date2 = strtotime("2020-03-31");
$datediff = $now - $date1;

echo round($datediff / (60 * 60 * 24));

$new_group_id = "002";
$now = time();
$enroll_date = strtotime("2020-08-29");
$last_visit_date = strtotime("2020-11-02");
//$datediff = $last_visit_date - $enroll_date;
$datediff = $now - $enroll_date;
$datediff = round($datediff / (60 * 60 * 24));
echo "<br>date_diff: $datediff";
$date_left = 365 - $datediff;

if($new_group_id == "001") $date_left = $date_left-28;

echo "<br>date_left: $date_left";

$old_visit_amt_left = round($date_left / 84);

echo "<br>old_visit_amt_left: $old_visit_amt_left";


$Date = "2021-01-19";
echo "<br><br>". date('Y-m-d', strtotime($Date. ' + 84 days'));
echo "<br> floor : ".floor(3.55);
echo "<br> round : ".round(3.55);

?>
