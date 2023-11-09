<?

$visit = "20201030112136";
$visit_date = substr($visit,0,strlen($visit)-6) ;
$visit_time = substr($visit,8,strlen($visit)) ;

echo "<br>visit:  $visit";
echo "<br>visit_date:  $visit_date";
echo "<br>visit_time:  $visit_time";

$visit_date1 = substr($visit_date,0,strlen($visit_date)-4) ;
$visit_date2 = substr($visit_date,4,strlen($visit_date)-6) ;
$visit_date3 = substr($visit_date,6,strlen($visit_date)) ;
echo "<br>visit_date2:  $visit_date1/$visit_date2/$visit_date3";

$visit_time1 = substr($visit_time,0,strlen($visit_time)-4) ;
$visit_time2 = substr($visit_time,2,strlen($visit_time)-4) ;
$visit_time3 = substr($visit_time,4,strlen($visit_time)) ;
$visit_time = "$visit_time1:$visit_time2:$visit_time3";
echo "<br>visit_time2:  $visit_time";

?>
