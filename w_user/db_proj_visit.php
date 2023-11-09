<?
// UID Data Mgt
include_once("../in_auth_db.php");



$msg_error = "";
$msg_info = "";
$returnData = "";
$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($flag_auth != 0){ // valid user session
  include_once("../in_db_conn.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_file_func.php"); // file function
  include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../function/in_fn_link.php");
  include_once("../function/in_fn_number.php");



  if($u_mode == "create_new_visit_schedule"){ // create visit schedule
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $clinic_id = $_SESSION['weclinic_id'];

    $dateEnroll = new DateTime(getToday());
    $arr_visit = array();

  // visit_day 	visit_day_before 	visit_day_after 	visit_order 	visit_status
           $query = "SELECT visit_id, visit_day
           FROM p_visit_list as v
           WHERE v.proj_id=? AND (v.group_id=? OR v.group_id='')
           AND visit_status=1 AND visit_order >= 0 AND visit_id <> 'EX'
           ORDER BY v.visit_order
           ";
    //echo $query;
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("ss", $proj_id, $group_id);
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


           $query = "INSERT INTO p_project_uid_visit
            (proj_id, uid, group_id,  visit_id, visit_main, visit_status, schedule_date)
            VALUES (?,?,?,?,'1', '0',?) ";
          $count=0;
          foreach ($arr_visit as $visit_id => $visit_schedule_date){
          //  echo "[visit_id :$visit_id / $visit_date] ";
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('sssss',$proj_id, $uid, $group_id, $visit_id, $visit_schedule_date);
              if($stmt->execute()){
                $count++;
              }
              else{
                $msg_error .= $stmt->error;
              }
              $stmt->close();

          } // foreach

           $rtn['visit_count'] = $count;

  }// create_new_visit_schedule

  else if($u_mode == "select_uid_visit_list"){ // select_uid_visit_list
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";

    $arr_list = array();
    $uidStatus = "";
    $finalStatusDate = "";
    $last_date_visit = "";
           $query = "SELECT uv.visit_id, v.visit_name,uv.schedule_date,
           uv.visit_date, ul.uid_param, ul.uid_status, vs.status_name, vs.status_id, uv.group_id, pg.proj_group_name,
           v.visit_day_before, v.visit_day_after, uv.visit_note, uv.schedule_note,
           DATE_ADD(uv.schedule_date, INTERVAL -(v.visit_day_before) DAY) as before_date ,
           DATE_ADD(uv.schedule_date, INTERVAL v.visit_day_after DAY) as after_date

           FROM p_visit_list as v, p_visit_status as vs, p_project_uid_list as ul,
           p_project_uid_visit as uv
           LEFT JOIN p_project_group as pg ON (uv.group_id=pg.proj_group_id AND pg.proj_id=?)

           WHERE uv.uid=? AND uv.proj_id=?
           AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND ul.uid_status IN (1, 2)
           AND uv.proj_id = v.proj_id AND uv.visit_id=v.visit_id
           AND uv.visit_status = vs.status_id AND uv.visit_status <> 'C'
           ORDER BY CASE WHEN uv.visit_date= '0000-00-00' THEN 2 ELSE 1 END,
           uv.visit_date, vs.status_order, v.visit_order, uv.schedule_date
           ";


    //echo "$query / $proj_id, $uid, $proj_id";
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("sss", $proj_id, $uid, $proj_id);
           if($stmt->execute()){
             $stmt->bind_result($visit_id, $visit_name,$schedule_date,
             $visit_date, $uid_param, $uid_status,  $visit_status_name, $status_id, $group_id, $group_name,
             $visit_day_before, $visit_day_after, $visit_note, $schedule_note,
             $visit_date_before, $visit_date_after
           );

             while ($stmt->fetch()) {
               $w_period = "";
               if($visit_date_before != $visit_date_after){
                 $w_period = "[".changeToThaiDate($visit_date_before)." - ".changeToThaiDate($visit_date_after)."]";

               }

               if($visit_note != ""){
                // $visit_note = (strlen($visit_note) > 50)? substr($visit_note,0,48)."...":$visit_note;
               }
//$visit_note = "";
               $arr_obj = array();
               $arr_obj['visit_id'] = $visit_id;
               $arr_obj['visit_name'] = $visit_name;
               $arr_obj['schedule_date'] = $schedule_date;
               $arr_obj['visit_date'] = $visit_date;
               $arr_obj['d_before'] = $visit_day_before;
               $arr_obj['d_after'] = $visit_day_after;
               $arr_obj['date_before'] = $visit_date_before;
               $arr_obj['date_after'] = $visit_date_after;
               $arr_obj['w_period'] = $w_period;
               $arr_obj['group_id'] = $group_id;
               $arr_obj['group_name'] = ($group_name !== NULL)?$group_name:"";
               $arr_obj['status_name'] = $visit_status_name;
               $arr_obj['status_id'] = $status_id;
               $arr_obj['visit_note'] = $visit_note;
               $arr_obj['schedule_note'] = $schedule_note;
               $arr_obj['uid_status'] = $uid_status;


              // $arr_obj['is_latest_visit'] = ($visit_date == $lastest_visit_date)?"Y":"N";
               $arr_list[] = $arr_obj;
               $uidStatus = $uid_status;

             }// while

             //last visit date (enroll date + 336+42)
             $last_date_visit = date('Y-m-d', strtotime($arr_list[0]["schedule_date"]. ' + 378 days'));
             $arr_list[count($arr_list)-1]['date_after']=$last_date_visit;
             $date1=date_create($arr_list[count($arr_list)-1]['schedule_date']);
             $date2=date_create($last_date_visit);
             $diff=date_diff($date1,$date2); // diff between last schedule date, last date in proj
             $last_visit_window_period = $diff->format("%a");
             $arr_list[count($arr_list)-1]['d_after']=$last_visit_window_period;


           }
           else{
             $msg_error .= $stmt->error;
           }
           $stmt->close();

           $arr_final_status = array();
           if($uidStatus == '2'){
             $query = "SELECT collect_date, finalstatus, final_reason, finalfu_date,
             death_date, death_cause , final_date, final_note
             FROM x_final_status
             WHERE uid=?
             ";

    //  echo "$query2 / $uid";
             $stmt = $mysqli->prepare($query);
             $stmt->bind_param("s", $uid);
             if($stmt->execute()){
               $stmt->bind_result($collect_date, $finalstatus, $final_reason, $finalfu_date,
               $death_date, $death_course, $final_date, $final_note
             );

               if ($stmt->fetch()) {
                 $arr_final_status['collect_date'] = $collect_date;
                 $arr_final_status['f_status'] = $finalstatus;
                 $arr_final_status['f_reason'] = $final_reason;
                 $arr_final_status['fu_date'] = ($finalfu_date !== NULL)?changeToThaiDate($finalfu_date):"";
                 $arr_final_status['death_date'] = ($death_date !== NULL)?changeToThaiDate($death_date):"";
                 $arr_final_status['death_course'] = $death_course;
                 $arr_final_status['final_visit_date'] = $final_date;
                 $arr_final_status['final_note'] = $final_note;

               }// if
             }
             else{
               $msg_error .= $stmt->error;
             }
             $stmt->close();
           }



           $rtn['datalist'] = $arr_list;
           $rtn['uid_param'] = $uid_param;
           $rtn['uid_status'] = $uidStatus;
           $rtn['final_status_obj'] = $arr_final_status;

           $rtn['last_visit_date'] = $last_date_visit;

  }// select_uid_visit_list

  else if($u_mode == "select_uid_visit_info"){ // select_uid_visit_info
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";

    $arr_obj = array();

  //  uid 	proj_id 	visit_id 	group_id 	schedule_date 	visit_date 	visit_main 	visit_status 	visit_note
           $query = "SELECT uv.visit_id, v.visit_name,ul.is_consent,  ulc.uid as consent_uid, uv.schedule_date,
           uv.visit_date, vs.status_name, vs.status_id,uv.visit_note, uv.group_id, pg.proj_group_name,
           gc.groupchange, gc.groupchange_note, pg_gc.proj_group_name as groupchange_name,
                      (SELECT visit_date
           FROM p_project_uid_visit
           WHERE uid=? AND proj_id=?
           ORDER BY visit_date DESC LIMIT 1) as last_visit_date

           FROM p_visit_list as v, p_visit_status as vs,
           p_project_uid_list as ul
           LEFT JOIN p_project_uid_list_consent as ulc ON (ul.proj_id=ulc.proj_id AND ul.uid=ulc.uid AND ulc.collect_date=? )

           ,
           p_project_uid_visit as uv
           LEFT JOIN p_project_group as pg ON (uv.group_id=pg.proj_group_id AND pg.proj_id=?)
           LEFT JOIN x_groupchange as gc left join p_project_group as pg_gc on (gc.groupchange=pg_gc.proj_group_id)

            ON (uv.visit_status=11 AND uv.visit_date=gc.collect_date AND uv.uid=gc.uid)
           WHERE ul.uid=uv.uid AND uv.uid=? AND uv.proj_id=? AND uv.visit_id=? AND uv.group_id=?  AND uv.visit_date=?
           AND uv.proj_id = v.proj_id AND uv.visit_id=v.visit_id
           AND uv.visit_status = vs.status_id

           ";
  //  echo "$proj_id, $uid, $proj_id, $visit_id, $group_id, $visit_date / $query";
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("sssssssss", $uid, $proj_id, $visit_date, $proj_id, $uid, $proj_id, $visit_id, $group_id, $visit_date);
           if($stmt->execute()){
             $stmt->bind_result($visit_id, $visit_name,$is_consent, $consent_uid, $schedule_date,
             $visit_date, $status_name, $status_id, $visit_note, $group_id, $group_name,
             $groupchange, $groupchange_note, $groupchange_name, $last_visit_date);

             if ($stmt->fetch()) {

               $arr_obj['visit_id'] = $visit_id;
               $arr_obj['visit_name'] = $visit_name;
               $arr_obj['schedule_date'] = $schedule_date;
               $arr_obj['visit_date'] = $visit_date;
               $arr_obj['group_id'] = $group_id;
               $arr_obj['group_name'] = $group_name;
               $arr_obj['status_name'] = $status_name;
               $arr_obj['status_id'] = $status_id;
               $arr_obj['visit_note'] = $visit_note;

               if($consent_uid === NULL) $is_consent = '0';


               $arr_obj['is_consent'] = $is_consent;
               // this visit has consent option to check
               $arr_obj['visit_consent'] = ($consent_uid !== NULL)?"1":"0";



//echo "last visit : $visit_date/$last_visit_date";
               if($visit_date == $last_visit_date){ // is lastest visit to decide add form log
                 $arr_obj['is_lastest_visit'] = "Y";
               }
               else{
                 $arr_obj['is_lastest_visit'] = "N";
               }

               $group_change_txt = "";
               if($groupchange !== null){
                 $group_change_txt = "<b>เปลี่ยนเป็นกลุ่ม:<br> $groupchange_name</b><br>$groupchange_note";
               }
               $arr_obj['groupchange'] = $group_change_txt;

             }// if
           }
           else{
             $msg_error .= $stmt->error;
           }
           $stmt->close();

           $rtn['visit_info'] = $arr_obj;

  }// select_uid_visit_info


  else if($u_mode == "meet_visit"){ // uid meet this visit
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";

    // set visit_statis = pre counseling
      $query = "UPDATE p_project_uid_visit SET
      visit_date=now(), visit_status='20', visit_clinic_id=?
      WHERE uid=? AND proj_id=? AND group_id=? AND visit_id=?
      ";
    //  echo "$uid, $proj_id,$group_id, $visit_id /query: $query" ;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('sssss',$staff_clinic_id, $uid, $proj_id,$group_id, $visit_id);
      if($stmt->execute()){
           $rtn['visit_date'] = getToday();
           setLogNote($sc_id, "[$proj_id] $uid meet visit $visit_id");
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();
  }// meet visit uid

  else if($u_mode == "update_visit_status"){ // update_visit_status
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
    $status_id = isset($_POST["status_id"])?$_POST["status_id"]:"";

    $query_add = "";
    if($status_id == "10"){
      $query_add .= ", visit_date=schedule_date ";
    }
    // set visit_statis = pre counseling
      $query = "UPDATE p_project_uid_visit SET
      visit_status=? $query_add
      WHERE uid=? AND proj_id=? AND group_id=? AND visit_id=? AND visit_date=?
      ";
      //echo "$status_id,  $uid, $proj_id,$group_id, $visit_id, $visit_date /query: $query" ;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('ssssss',$status_id,  $uid, $proj_id,$group_id, $visit_id, $visit_date);
      if($stmt->execute()){
           $msg_info = "เปลี่ยนสถานะสำเร็จ";
           setLogNote($sc_id, "[$proj_id] change visit status $uid in $visit_id (status:$status_id)");
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

  }// update_visit_status


  else if($u_mode == "update_visit_note"){ // update_visit_note

    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
    $visit_note = isset($_POST["visit_note"])?$_POST["visit_note"]:"";

    // set visit_statis = pre counseling
      $query = "UPDATE p_project_uid_visit SET visit_note=?
      WHERE uid=? AND proj_id=? AND group_id=? AND visit_id=? AND visit_date=?
      ";
    //  echo "$visit_note,  $uid, $proj_id,$group_id, $visit_id, $visit_date /query: $query" ;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('ssssss',$visit_note,  $uid, $proj_id,$group_id, $visit_id, $visit_date);
      if($stmt->execute()){
           $msg_info = "เปลี่ยน visit note สำเร็จ";
           setLogNote($sc_id, "[$proj_id] update visit note $uid in $visit_id");
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

  }// update_visit_note

  else if($u_mode == "update_schedule_date"){ // change schedule date
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";
    $old_schedule_date = isset($_POST["old_schedule_date"])?$_POST["old_schedule_date"]:"";
    $new_schedule_date = isset($_POST["new_schedule_date"])?$_POST["new_schedule_date"]:"";


    // set visit_statis = pre counseling
      $query = "UPDATE p_project_uid_visit SET
      schedule_date=?
      WHERE uid=? AND proj_id=? AND group_id=? AND visit_id=?
      ";
    //  echo "$uid, $proj_id,$group_id, $visit_id /query: $query" ;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('sssss',$new_schedule_date, $uid, $proj_id,$group_id, $visit_id);
      if($stmt->execute()){
           $msg_info .= "ได้เปลี่ยนวันนัดหมายสำเร็จแล้ว";
           setLogNote($sc_id, "[$proj_id] change schedule date $uid in $visit_id (from $old_schedule_date to $new_schedule_date)");
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();
  }// update_schedule_date

    else if($u_mode == "update_schedule_note"){ // update_schedule_note

      $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
      $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
      $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
      $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";
      $schedule_note = isset($_POST["schedule_note"])?urldecode($_POST["schedule_note"]):"";

        $query = "UPDATE p_project_uid_visit SET schedule_note=?
        WHERE uid=? AND proj_id=? AND group_id=? AND visit_id=?
        ";
      //  echo "$visit_note,  $uid, $proj_id,$group_id, $visit_id, $visit_date /query: $query" ;
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssss',$schedule_note,  $uid, $proj_id,$group_id, $visit_id);
        if($stmt->execute()){
             $msg_info = "บันทึก schedule note สำเร็จ";
             setLogNote($sc_id, "[$proj_id] update schedule note $uid in $visit_id");
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

    }// update_schedule_note
  else if($u_mode == "meet_extra_visit"){ // uid meet extra visit
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";


    // add extra visit and set visit_status = pre counseling
      $query = "INSERT INTO p_project_uid_visit (uid, proj_id, group_id, visit_id, schedule_date, visit_date, visit_main, visit_status, visit_clinic_id)
      VALUES(?,?,?,'EX', now(),now(), '1', '20',?)
      ";
    //  echo "$uid, $proj_id,$group_id, $visit_id /query: $query" ;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('ssss',$uid, $proj_id,$group_id, $staff_clinic_id);
      if($stmt->execute()){
           $rtn['visit_date'] = getToday();
           setLogNote($sc_id, "[$proj_id] $uid meet extra visit");
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();
  }// meet extra visit uid

  else if($u_mode == "update_final_status"){ // update_final_status
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $flag_update = "N";

    // set uid_status = 2 (exit from project, complete)
      $query = "UPDATE p_project_uid_list SET
      uid_status=2
      WHERE uid=? AND proj_id=? AND uid_status=1
      ";
      //echo "$status_id,  $uid, $proj_id,$group_id, $visit_id, $visit_date /query: $query" ;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('ss',$uid, $proj_id);
      if($stmt->execute()){
           $msg_info = "สิ้นสุดโครงการ $proj_id";
           setLogNote($sc_id, "[$proj_id] Final Status $uid ");
           $flag_update = "Y";
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

      if($flag_update == "Y"){
        $query = "DELETE FROM p_project_uid_visit
        WHERE uid=? AND proj_id=? AND visit_status=0";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ss',$uid, $proj_id);
        if($stmt->execute()){

        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();
      }

  }// update_final_status

  else if($u_mode == "set_lost_to_followup_visit"){ // set lost to follow up visit which patient come over the visit's window period
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";

    // set visit_statis = pre counseling
      $query = "UPDATE p_project_uid_visit SET
      visit_date=schedule_date, visit_status='10'
      WHERE uid=? AND proj_id=? AND group_id=? AND visit_id=?
      ";
  //  echo "$query / $uid, $proj_id,$group_id, $visit_id" ;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('ssss',$uid, $proj_id,$group_id, $visit_id);
      if($stmt->execute()){
           $rtn['visit_date'] = getToday();
           setLogNote($sc_id, "[$proj_id] $uid lost to followup visit $visit_id");
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();
  }// set_lost_to_followup_visit

    else if($u_mode == "reconsent"){ // uid reconsent
      $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
      $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
      $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
      $is_consent = isset($_POST["is_consent"])?$_POST["is_consent"]:"";
      $consent_remark = isset($_POST["consent_remark"])?$_POST["consent_remark"]:"";
      $consent_version = isset($_POST["consent_version"])?$_POST["consent_version"]:"";

      // set reconsent
        $query = "REPLACE INTO p_project_uid_list_consent
        (proj_id, uid, collect_date, consent_version, is_consent, consent_remark) VALUES
        (?,?,?,?,?,?)
        ";
      //  echo "$uid, $proj_id,$group_id, $visit_id /query: $query" ;
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssss',$proj_id, $uid, $collect_date,$consent_version, $is_consent, $consent_remark);
        if($stmt->execute()){
             setLogNote($sc_id, "[$proj_id] $uid reconsent ");
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

        // set reconsent
          $query = "UPDATE p_project_uid_list SET is_consent='1'
          WHERE uid=? AND proj_id=?
          ";
        //  echo "$uid, $proj_id,$group_id, $visit_id /query: $query" ;
          $stmt = $mysqli->prepare($query);
          $stmt->bind_param('ss', $uid,$proj_id);
          if($stmt->execute()){

          }
          else{
            $msg_error .= $stmt->error;
          }
          $stmt->close();


    }// reconsent
    else if($u_mode == "open_consent"){ // uid open_consent
      $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
      $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
      $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
      $consent_version = isset($_POST["consent_version"])?$_POST["consent_version"]:"";

      // set visit_statis = pre counseling
        $query = "SELECT is_consent, consent_version, consent_remark
        FROM p_project_uid_list_consent
        WHERE proj_id=? AND uid=? AND collect_date=?
        ";
      //  echo "$proj_id, $uid, $collect_date /query: $query" ;
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sss',$proj_id, $uid, $collect_date);
        if($stmt->execute()){
             $stmt->bind_result($is_consent, $consent_version, $consent_remark);
             if ($stmt->fetch()) {

             }// if
        }
        else{
          $msg_error .= $stmt->error;
        }
        $rtn["is_consent"] = $is_consent;
        $rtn["consent_version"] = $consent_version;
        $rtn["consent_remark"] = $consent_remark;



        $stmt->close();
    }// open_consent


  $mysqli->close();
}//$flag_auth != 0







 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;
 $rtn['flag_auth'] = $flag_auth;


 // change to javascript readable form
 $returnData = json_encode($rtn);

 echo $returnData;
