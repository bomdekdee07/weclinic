<?
// POC db mgt
include_once("../../../in_auth_db.php");


$msg_error = "";
$msg_info = "";
$returnData = "";

$proj_id="POC";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($flag_auth != 0){ // valid user session
  include_once("../../../in_db_conn.php");
  include_once("../../../function/in_fn_date.php"); // date function
  include_once("../../../function/in_file_func.php"); // file function
  include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../../../function/in_fn_link.php");
  include_once("../../../function/in_fn_number.php");



  if($u_mode == "change_group"){ // create visit schedule

    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $old_group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $new_group_id = isset($_POST["new_group_id"])?$_POST["new_group_id"]:"";
    $old_visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";
    $old_visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";

    $groupchange_note = isset($_POST["groupchange_note"])?$_POST["groupchange_note"]:"";

  //  $clinic_id = $_SESSION['weclinic_id'];
  //echo "($old_visit_id) $uid, $proj_id, $group_id/$new_group_id";


/*
  $query = "SELECT count(uid)
  FROM p_project_uid_visit
  WHERE uid=? AND proj_id=? AND (group_id=? OR group_id='')
  AND visit_status=0
  ";
  //echo "$query [$uid, $proj_id, $old_group_id]";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("sss", $uid, $proj_id, $old_group_id);
  if($stmt->execute()){
    $stmt->bind_result($old_visit_amt_left);
    if ($stmt->fetch()) {

    }// if
  }
  else{
    $msg_error .= $stmt->error;
  }
  $stmt->close();
*/




/*
  // group 1 has month 1 (more than other group 1 visit)
  if($new_group_id == "001"){
    $old_visit_amt_left += 1;
  }
*/


$query = "SELECT enroll_date
FROM p_project_uid_list
WHERE uid=? AND proj_id=? AND uid_status=1
";
//echo "$query [$uid, $proj_id, $old_group_id]";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $uid, $proj_id);
if($stmt->execute()){
  $stmt->bind_result($enroll_date);
  if ($stmt->fetch()) {
  }// if
}
else{
  $msg_error .= $stmt->error;
}
$stmt->close();

$now = time();
$enroll_date = strtotime($enroll_date);
//$last_visit_date = strtotime($old_visit_date);
$datediff = $now - $enroll_date;
$datediff = round($datediff / (60 * 60 * 24));
//$date_left = 365+42 - $datediff; // include window period 42 days
$date_left = (336+42) - $datediff; // include window period 42 days

$visit_add = 1; //for new group baseline

if($new_group_id == "001"){
  $date_left = $date_left-28;

  if($date_left > 0)
  $visit_add += 1;// include month 1 group1 visit
}


$old_visit_amt_left = floor($date_left / 84) + $visit_add ;

  //echo "old_visit_amt_left : $old_visit_amt_left";
    $dateEnroll = new DateTime(getToday());
    $arr_visit = array();

  // visit_day 	visit_day_before 	visit_day_after 	visit_order 	visit_status
           $query = "SELECT visit_id, visit_day
           FROM p_visit_list
           WHERE proj_id=? AND (group_id=? OR group_id='')
           AND visit_status=1 AND visit_order >= 0 AND visit_id <> 'EX'
           ORDER BY visit_order ASC LIMIT $old_visit_amt_left
           ";

           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("ss", $proj_id, $new_group_id);
           if($stmt->execute()){
             $stmt->bind_result($visit_id, $visit_day);
             while ($stmt->fetch()) {
               $dateEnroll = new DateTime(getToday());
               $arr_visit[$visit_id] = getDateToString($dateEnroll->modify("+$visit_day day"));
             }// if
           }
           else{
             $msg_error .= $stmt->error;
           }
           $stmt->close();

           // delete all old non-visit left
           $query = "DELETE FROM p_project_uid_visit
           WHERE uid=? AND proj_id=? AND visit_status=0
           ";
           //echo $query;
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("ss", $uid, $proj_id);
           if($stmt->execute()){
             if ($stmt->fetch()) {
             }// if
           }
           else{
             $msg_error .= $stmt->error;
           }
           $stmt->close();

           // update change group visit status (11) to old group
           $query = "UPDATE p_project_uid_visit
           SET visit_status = 11
           WHERE uid=? AND proj_id=? AND visit_id=? AND group_id=?
           ";
        //   echo "<br> $query /$uid, $proj_id, $old_visit_id, $old_group_id";
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("ssss", $uid, $proj_id, $old_visit_id, $old_group_id);
           if($stmt->execute()){
             if ($stmt->fetch()) {

             }// if
           }
           else{
             $msg_error .= $stmt->error;
           }
           $stmt->close();

           // update uid to new group
           $query = "UPDATE p_project_uid_list
           SET proj_group_id = ?
           WHERE uid=? AND proj_id=? AND proj_group_id=?
           ";
          // echo "<br> $query /$new_group_id, $uid, $proj_id, $old_group_id";
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("ssss", $new_group_id, $uid, $proj_id, $old_group_id);
           if($stmt->execute()){
             if ($stmt->fetch()) {

             }// if
           }
           else{
             $msg_error .= $stmt->error;
           }
           $stmt->close();

           // create new visit schedule to new group as the visit amt left
           $query = "INSERT INTO p_project_uid_visit
            (proj_id, uid, group_id,  visit_id, visit_main, visit_status, schedule_date)
            VALUES (?,?,?,?,'1', '0',?) ";
          $count=0;
          foreach ($arr_visit as $visit_id => $visit_schedule_date){
          //  echo "[visit_id :$proj_id, $uid, $new_group_id, $visit_id, $visit_schedule_date] ";
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('sssss',$proj_id, $uid, $new_group_id, $visit_id, $visit_schedule_date);
              if($stmt->execute()){
                $count++;
              }
              else{
                $msg_error .= $stmt->error;
              }
              $stmt->close();
          } // foreach
          // setLogNote("[$sc_id] change group $uid from $old_group_id to $new_group_id at visit $visit_id");
           $rtn['visit_count'] = $count;

           // insert groupchange history
           $query = "INSERT INTO x_groupchange
           (uid, collect_date, collect_time, proj_id, groupchange, groupchange_note)
           VALUES(?,now(),now(),?,?,?)
           ";
           //echo $query;
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("ssss", $uid, $proj_id, $new_group_id, $groupchange_note);
           if($stmt->execute()){
             if ($stmt->fetch()) {
                setLogNote($sc_id, "[$proj_id] change group $uid from $old_group_id to $new_group_id");
             }// if
           }
           else{
             $msg_error .= $stmt->error." ไม่สามารถเปลี่ยนกลุ่มได้ภายในวันเดียวกัน";
           }
           $stmt->close();


  }// change_group

  else if($u_mode == "sel_poc_200"){ // select lab for first 200 case
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $is_poc200_visit = "N";
    $query = "SELECT uid
FROM  p_project_uid_list
WHERE uid=? AND proj_id=? AND uid_param='poc200'
AND enroll_date=? ";

  //echo "$query / $uid,$proj_id,$visit_date" ;
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param('sss',$uid,$proj_id,$visit_date);
         if($stmt->execute()){
           $stmt->bind_result($uidPOC200);
           $arr_list = array();
           if ($stmt->fetch()) {
             $is_poc200_visit = "Y";
           }// if

         }
         else{
           $msg_error .= $stmt->error;
         }
         $rtn['is_poc200_visit'] = $is_poc200_visit;
         $stmt->close();
  }// sel_poc_200




  $mysqli->close();

}// $flag_auth != 0



 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
