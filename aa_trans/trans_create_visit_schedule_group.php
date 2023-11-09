<?
// UID Data Mgt
//include_once("../in_auth.php");
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function

$proj_id = "POC";

$group_uid="'P19-09683', 'P19-09684', 'P19-09685', 'P19-09686',
'P19-09687', 'P19-09688', 'P19-09689', 'P19-09690','P19-09691', 'P19-09692', 'P19-09693', 'P19-09694'";





echo "DELETE FROM p_project_uid_visit WHERE uid IN ($group_uid);<br>";

$arr_uid = array();
/*
$query = "SELECT uid, screen_date, proj_group_id
FROM p_project_uid_list as uv
WHERE uv.proj_id='$proj_id'
AND uv.uid_status=1
ORDER BY uv.pid asc
";
*/

$query = "SELECT uid, screen_date, proj_group_id
FROM p_project_uid_list as uv
WHERE uv.proj_id='$proj_id'
AND  uv.uid IN ($group_uid)
ORDER BY uv.pid asc
";
//echo $query;
$stmt = $mysqli->prepare($query);
if($stmt->execute()){
  $stmt->bind_result($uid, $screen_date, $group_id);
  while ($stmt->fetch()) {
    $arr_obj = array();
    $arr_obj['uid'] = $uid;
    $arr_obj['screen_date'] = $screen_date;
    $arr_obj['group_id'] = $group_id;
    $arr_uid[] = $arr_obj;
  //  echo "$uid, $screen_date, $group_id <br> ";
  }// if
}
else{
  $msg_error .= $stmt->error;
}
$stmt->close();


$query = "SELECT visit_id, visit_day
FROM p_visit_list as v
WHERE v.proj_id=? AND (v.group_id=? OR v.group_id='')
AND visit_status=1 AND visit_order >= 0 AND visit_id <> 'EX'
ORDER BY v.visit_order
";

$query_insert_visit = "INSERT INTO p_project_uid_visit
 (proj_id, uid, group_id,  visit_id, visit_main, visit_status, schedule_date)
 VALUES (?,?,?,?,'1', '0',?) ";

$arr_visit = array();

foreach($arr_uid as $uid_obj){
  $uid = $uid_obj['uid'];
  $group_id = $uid_obj['group_id'];
  $date_enroll = $uid_obj['screen_date'];

  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ss", $proj_id, $group_id);
  if($stmt->execute()){
    $stmt->bind_result($visit_id, $visit_day);
    $stmt->store_result();
    while ($stmt->fetch()) {
      $dateEnroll = new DateTime($date_enroll);
      $visit_schedule_date = getDateToString($dateEnroll->modify("+$visit_day day"));

      if($visit_id=='M0'){
    //echo "<br> update0: $proj_id, $uid, $group_id, 'SCRN', $visit_schedule_date";
     echo "INSERT INTO p_project_uid_visit
     (proj_id, uid, group_id,  visit_id, visit_main, visit_status, schedule_date)
     VALUES ('$proj_id','$uid','$group_id','SCRN','1', '0','$visit_schedule_date'); <br>";

      }
      echo "INSERT INTO p_project_uid_visit
       (proj_id, uid, group_id,  visit_id, visit_main, visit_status, schedule_date)
       VALUES ('$proj_id','$uid','$group_id','$visit_id','1', '0','$visit_schedule_date'); <br>";
//echo "<br> update0: $proj_id, $uid, $group_id, $visit_id, $visit_schedule_date";

/*
      $stmt2 = $mysqli->prepare($query_insert_visit);
      $stmt2->bind_param('sssss',$proj_id, $uid, $group_id, $visit_id, $visit_schedule_date);
      if($stmt->execute()){
        echo "<br> update $proj_id, $uid, $group_id, $visit_id, $visit_schedule_date";
      }
      else{
        //$msg_error = $stmt2->error;
        echo "<br>".$stmt2->error;
      }
      $stmt2->close();
*/

    }// if




  }
  else{
    $msg_error .= $stmt->error;
  }
  $stmt->close();

} // foreach

echo " <br><br>
UPDATE p_project_uid_visit SET visit_date = schedule_date WHERE visit_id='SCRN' AND uid IN ($group_uid); <br>
UPDATE p_project_uid_visit SET visit_date = schedule_date WHERE visit_id='M0' AND uid IN ($group_uid) ; <br>
UPDATE p_project_uid_visit SET group_id = '' WHERE visit_id='SCRN' AND uid IN ($group_uid) ; <br>
UPDATE p_project_uid_visit SET visit_status = '1' WHERE visit_id IN ('SCRN', 'M0') AND uid IN ($group_uid);

";


$mysqli->close();
