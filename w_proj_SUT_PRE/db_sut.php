<?



$open_link = isset($_POST["open_link"])?$_POST["open_link"]:"N";
if($open_link != "Y"){ // staff save form
  include_once("../in_auth_db.php");
}
else{ // patient save form
  $ROOT_FILE_PATH = $_SERVER['DOCUMENT_ROOT']."/weclinic/";
  $sc_id="Patient";
  $flag_auth=1;
}

$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";
$proj_id = "SUT_PRE";

//echo "enter01 $open_link";
if($flag_auth != 0){ // valid user session
//echo "enter02";
  include_once("../in_db_conn.php");
  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_file_func.php"); // file function
  //include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../function/in_fn_link.php");
  include_once("../function/in_fn_number.php");
  include_once("../function/in_fn_sendmail.php");
  include_once("../function/in_ts_log.php");


  include_once("inc_pid_format.php");



  if($u_mode == "addnew_sut"){ // addnew_sut
      if(isset($clinic_prefix_id[$clinic_id])){

              $txt_new_sut = isset($_POST["txt_new_sut"])?$_POST["txt_new_sut"]:"";
              $flag_add = 0;

              $query = "SELECT u.uid, u.uic, ul.pid, ul.uid as uv_uid, uv.schedule_date
              FROM uic_gen as u
              LEFT JOIN p_project_uid_list as ul ON (u.uid=ul.uid AND ul.proj_id='$proj_id')
              LEFT JOIN p_project_uid_visit as uv ON (u.uid=uv.uid AND uv.proj_id='$proj_id' AND uv.visit_date<>'0000-00-00')

              WHERE (u.uic = '$txt_new_sut' OR u.uid = '$txt_new_sut')
              ";
              //echo "$clinic_id, $schedule_date/ $query";
                     $stmt = $mysqli->prepare($query);
                     if($stmt->execute()){
                       $stmt->bind_result($uid, $uic, $pid, $uv_uid, $send_consent_date);

                       if ($stmt->fetch()) {


                       }// while
                     }
                     else{
                       $msg_error .= $stmt->error;
                     }
                     $stmt->close();


               if($uid != '' && $uv_uid === NULL){
              //if($uv_uid === NULL){
                 $flag_valid_age = 1; // 1: valid (age between 15-19 yrs), 0: invalid
                 $arr_age = calculateUICAge($uic);
                 $uic_age_y = (int) $arr_age['Y'];
                 $uic_age_m = (int) $arr_age['M'];
                 $uic_age_d = (int) $arr_age['D'];
//echo "$uic_age_y-$uic_age_m-$uic_age_d";
                 if($uic_age_y >= 15 && $uic_age_y <= 19){
                   if($uic_age_y == 19){
                  //   $uic_age = "$uic_age_y-$uic_age_m-$uic_age_d";
                     if(($uic_age_m == 11 && $uic_age_d >29) OR $uic_age_m == 12){
                  //   if($uic_age > "19-11-29"){
                       $flag_valid_age = 0;
                     }
                   }
                 }
                 else{
                   $flag_valid_age = 0;
                 }


                 if($flag_valid_age == 1){

                  $msg_info .= "อายุ $uic_age_y ปี $uic_age_m เดือน $uic_age_d วัน ผ่านเข้าเกณฑ์การสมัคร";
                  $rtn["uid"] = $uid;
                  $rtn["uic"] = $uic;
                  $rtn["age"] = "$uic_age_y-$uic_age_m-$uic_age_d";

                 }
                 else{
                   $msg_error .= "อายุ $uic_age_y ปี $uic_age_m เดือน $uic_age_d วัน ไม่เข้าเกณฑ์การสมัครโครงการ";

                   $age = "$uic_age_y-$uic_age_m-$uic_age_d";
                   $query = "INSERT INTO x_sut_pre_screen
                   (uid, collect_date, scr_age, age, clinic_id) VALUES
                   (?,now(),'N', ?, ?)
                   ";

                     //echo "$uid, $age / $query" ;
                       $stmt = $mysqli->prepare($query);
                       $stmt->bind_param('sss',$uid, $age, $clinic_id);
                       if($stmt->execute()){
                         $msg_info .= "$uic [$uid] อายุ $uic_age_y ปี $uic_age_m เดือน $uic_age_d วัน ผ่านเกณฑ์การสมัคร กรุณาส่งแบบสอบถามให้อาสาสมัคร";
                         setLogNote($sc_id, "[SUT] $uid add to previsit Screen");

                       }
                       else{
                         $msg_error .= $stmt->error;
                       }
                       $stmt->close();

                 }

               }
               else{// invalid uv.visit_date='0000-00-00' มีการส่ง consent ค้างไว้ อาสายังไม่ได้ตอบกลับมา

               if($uid == '')
               $msg_error .= "$txt_new_sut ไม่พบในระบบ กรุณาลงทะเบียน UIC ก่อน";
               else if($uv_uid !== NULL){
                 $msg_error .= "$uic [$uid] มีการลงทะเบียนไปก่อนหน้านั้นแล้ว ";
               }


               }
      }
      else{
        $msg_error .= "ไม่อนุญาตให้ท่านลงทะเบียน PID เนื่องจากไม่เกี่ยวข้อง ";
      }



    }// addnew_sut



    else if($u_mode == "add_sut_paper"){ // add_sut_screen

      $uid = isset($_POST["uid"])?$_POST["uid"]:"";
      if(isset($clinic_prefix_id[$clinic_id])){

        $id_prefix = str_replace("{s}",$clinic_prefix_id[$clinic_id],$pid_format);
        $id_prefix = str_replace("{r}","",$id_prefix);

          $id_digit = $running_digit;
          $substr_pos_begin = 3+strlen($id_prefix);
          $where_substr_pos_end = strlen($id_prefix);


          $query = "INSERT INTO p_project_uid_list
          (pid, proj_id, clinic_id, uid, uid_status, screen_date, enroll_date) ";

          $query.= " SELECT @keyid := CONCAT('".$id_prefix."',
          LPAD( (SUBSTRING(  IF(MAX(pid) IS NULL,0,MAX(pid))   ,
          $substr_pos_begin,$id_digit)*1)+1, '".$id_digit."','0') )";
          $query.= " ,?,?,?,'1',now(), now() ";
          $query.= " FROM p_project_uid_list WHERE SUBSTRING(pid,1,$where_substr_pos_end) = '".$id_prefix."';";
      //  echo "$query" ;

              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('sss',$proj_id, $clinic_id,$uid);


              if($stmt->execute()){
                $inQuery = "SELECT @keyid;";
                $stmt = $mysqli->prepare($inQuery.";");
                $stmt->bind_result($rtn_id);
                if($stmt->execute()){ // get leave id
                  if($stmt->fetch()){
                      $rtn['pid'] = $rtn_id;

                  }
                }

              }
              else{
                $msg_error .= $stmt->error;
              }
              $stmt->close();


         $visit_id = "PRE";
         $query = "INSERT INTO p_project_uid_visit
         (proj_id, visit_clinic_id, uid, visit_id, schedule_date, visit_status) VALUES
         ('SUT_PRE',?,?,?,now(), '0')
         ";

         //  echo "$clinic_id,$uid / $query" ;
             $stmt = $mysqli->prepare($query);
             $stmt->bind_param('sss',$clinic_id,$uid, $visit_id);
             if($stmt->execute()){
               $msg_info .= "Add PID:$rtn_id [$uid] to pre visit Screen";
               setLogNote($sc_id, "[SUT] $uid add PID:$rtn_id to previsit Screen");
               $rtn['visit_date'] = getToday();
             }
             else{
               $msg_error .= $stmt->error;
             }
             $stmt->close();
      }
      else{
        $msg_error .= "[$clinic_id] Not allow for this site to create pid.";
      }

  }// add_sut_paper

  else if($u_mode == "update_visit"){ // update visit after online form done by patient
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $screen_date = isset($_POST["screen_date"])?$_POST["screen_date"]:"";
    $clinic_id = isset($_POST["clinic_id"])?$_POST["clinic_id"]:"";
    $is_consent = isset($_POST["is_consent"])?$_POST["is_consent"]:"0";

    if($is_consent == "1"){ // consent = yes
      if(checkExistingPID($uid)){ // in SUT but not create pid yet
        $query = "UPDATE p_project_uid_list SET enroll_date=now(),  uid_status='1', pid= 'wait_pid'
        WHERE uid=? AND proj_id=? AND screen_date=? AND pid='wait_pid' ";

              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('sss',$uid, $proj_id, $screen_date);
              if($stmt->execute()){
                $rtn['pid'] = "wait_pid";
            //    $msg_info .= "Create PID : [$rtn_id] for $uid.";

              }
              else{
                $msg_error .= $stmt->error;
              }
              $stmt->close();
        } // checkExistingPID

      }//if($is_consent == "1")
      else if($is_consent == "0"){ // consent = no

      }


              $query = "UPDATE p_project_uid_visit SET visit_date=now(),  visit_status='1'
                        WHERE uid=? AND proj_id=? AND schedule_date=?
              ";

              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('sss',$uid,$proj_id, $screen_date);
              if($stmt->execute()){
                $rtn['visit_date'] = (new DateTime())->format('Y-m-d');
                $msg_info .= "ได้รับข้อมูลแล้วจาก $uid.";
              }
              else{
                $msg_error .= $stmt->error;
              }
              $stmt->close();


  }// update_visit

  else if($u_mode == "update_visit_paper"){ // update visit form from patient

       $uid = isset($_POST["uid"])?$_POST["uid"]:"";
       $screen_date = isset($_POST["screen_date"])?$_POST["screen_date"]:"";

       $visit_id = "PRE";
       $query = "UPDATE p_project_uid_visit SET
       visit_date=now(), visit_status='1'
       WHERE proj_id='SUT_PRE' AND uid=? AND schedule_date=?
       ";

         //echo "$clinic_id,$uid / $query" ;
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param('ss',$uid, $screen_date);
           if($stmt->execute()){
             setLogNote($sc_id, "[SUT] $uid update pre visit form.");
             $rtn['visit_date'] = getToday();
           }
           else{
             $msg_error .= $stmt->error;
           }
           $stmt->close();

}// update_visit_paper


  else if($u_mode == "add_sut_online"){ // add_sut_online

        $uid = isset($_POST["uid"])?$_POST["uid"]:"";

        $query = "INSERT INTO p_project_uid_list
        (pid, proj_id, clinic_id, uid, uid_status, screen_date) VALUES
        ('wait_pid', ?,?,?,'1', now())
        ";

            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sss',$proj_id, $clinic_id,$uid);

            if($stmt->execute()){
            }
            else{
              $msg_error .= $stmt->error;
            }
            $stmt->close();


       $visit_id = "PRE";
       $query = "INSERT INTO p_project_uid_visit
       (proj_id, visit_clinic_id, uid, visit_id, schedule_date, visit_status) VALUES
       ('SUT_PRE',?,?,?,now(), '0')
       ";

       //  echo "$clinic_id,$uid / $query" ;
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param('sss',$clinic_id,$uid, $visit_id);
           if($stmt->execute()){
             $msg_info .= "Add [$uid] to previsit Screen (online)";
             setLogNote($sc_id, "[SUT] $uid add to previsit Screen (online)");
             $rtn['visit_date'] = getToday();
           }
           else{
             $msg_error .= $stmt->error;
           }
           $stmt->close();

}// add_sut_screen
else if($u_mode == "confirm_consent"){ // confirm_consent

      $uid = isset($_POST["uid"])?$_POST["uid"]:"";
      $confirm_choice = isset($_POST["confirm_choice"])?$_POST["confirm_choice"]:"0";
      $case_consent = isset($_POST["case_consent"])?$_POST["case_consent"]:"";
      $screen_date = isset($_POST["screen_date"])?$_POST["screen_date"]:"";
      $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";


      if($confirm_choice == '1'){// confirm consent

        if($case_consent == 'Y'){ // accept consent
        $id_prefix = str_replace("{s}",$clinic_prefix_id[$clinic_id],$pid_format);
        $id_prefix = str_replace("{r}","",$id_prefix);

          $id_digit = $running_digit;
          $substr_pos_begin = 3+strlen($id_prefix);
          $where_substr_pos_end = strlen($id_prefix);


          $query = "UPDATE p_project_uid_list SET is_consent=? , pid= ";
          $query.= "(select max from (";
          $query.= "SELECT @keyid := CONCAT('$id_prefix',
            LPAD( (SUBSTRING(  IF(MAX(pid) IS NULL,0,MAX(pid)) ,$substr_pos_begin,$id_digit)*1)+1, '$id_digit','0') ";
          $query.= ") as max ";
          $query.= "FROM p_project_uid_list WHERE SUBSTRING(pid,1,$where_substr_pos_end) = '$id_prefix'
          AND proj_id='$proj_id') t) ";
          $query.= "WHERE uid=? AND proj_id=? AND screen_date=? AND pid='wait_pid' ";


//echo "$confirm_choice, $uid, $screen_date / $query";
             $stmt = $mysqli->prepare($query);
             $stmt->bind_param('ssss',$confirm_choice, $uid, $proj_id, $screen_date);

             if($stmt->execute()){
               $inQuery = "SELECT @keyid;";
               $stmt = $mysqli->prepare($inQuery.";");
               $stmt->bind_result($rtn_id);
               if($stmt->execute()){ // get leave id
                 if($stmt->fetch()){
                     $rtn['pid'] = $rtn_id;
                 }
               }

               $msg_info .= "Create PID : [$rtn_id] for $uid.";

             }
             else{
               $msg_error .= $stmt->error;
             }
             $stmt->close();
           }
           else if($case_consent == 'N'){ // N not accept consent
             $query = "DELETE FROM p_project_uid_list
             WHERE uid=? AND screen_date=? AND pid='wait_pid' AND proj_id=?";
             $stmt = $mysqli->prepare($query);
             $stmt->bind_param('sss', $uid, $screen_date, $proj_id);
              if($stmt->execute()){
               }
              else{
                   $msg_error .= $stmt->error;
              }
              $stmt->close();

              $query = "DELETE FROM p_project_uid_visit
              WHERE uid=? AND schedule_date=?  AND proj_id=?";

            //  echo "$uid, $screen_date, $proj_id / $query";
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('sss', $uid, $screen_date, $proj_id);
               if($stmt->execute()){
               }
               else{
                    $msg_error .= $stmt->error;
               }
               $stmt->close();

           }

       }
       else if($confirm_choice == '0'){// cancel consent
         $query = "UPDATE p_project_uid_list SET
         is_consent=? WHERE uid=? AND screen_date=? AND proj_id='SUT_PRE'
         ";

             $stmt = $mysqli->prepare($query);
             $stmt->bind_param('sss',$confirm_choice, $uid, $screen_date);

             if($stmt->execute()){

             }
             else{
               $msg_error .= $stmt->error;
             }
             $stmt->close();


             $query = "UPDATE p_project_uid_visit SET
             visit_date='0000-00-00' WHERE uid=? AND schedule_date=? AND proj_id='SUT_PRE'
             ";

                 $stmt = $mysqli->prepare($query);
                 $stmt->bind_param('ss', $uid, $screen_date);

                 if($stmt->execute()){
                 }
                 else{
                   $msg_error .= $stmt->error;
                 }
                 $stmt->close();


             $query = "DELETE FROM x_hivtest_self
             WHERE uid=? AND collect_date=?
             ";
//echo "$uid, $visit_date / $query";
                 $stmt = $mysqli->prepare($query);
                 $stmt->bind_param('ss', $uid, $visit_date);

                 if($stmt->execute()){
                   $msg_info = "ยกเลิก consent แล้ว";
                   setLogNote($sc_id, "[SUT] cancel consent $uid");

                 }
                 else{
                   $msg_error .= $stmt->error;
                 }
                 $stmt->close();
       }



}// confirm_consent

else if($u_mode == "remove_paper_consent"){ // remove_paper_consent ลบฟอร์มกระดาษที่ไม่ยินยอม หลังจากการกรอกฟอร์ม

      $uid = isset($_POST["uid"])?$_POST["uid"]:"";
      $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
      $screen_date = isset($_POST["screen_date"])?$_POST["screen_date"]:"";
      $query = "DELETE FROM p_project_uid_list
             WHERE uid=? AND screen_date=? AND pid='wait_pid' AND proj_id=?";
             $stmt = $mysqli->prepare($query);
             $stmt->bind_param('sss', $uid, $screen_date, $proj_id);
              if($stmt->execute()){
               }
              else{
                   $msg_error .= $stmt->error;
              }
              $stmt->close();

              $query = "DELETE FROM p_project_uid_visit
              WHERE uid=? AND schedule_date=?  AND proj_id=?";

            //  echo "$uid, $screen_date, $proj_id / $query";
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('sss', $uid, $screen_date, $proj_id);
               if($stmt->execute()){
               }
               else{
                    $msg_error .= $stmt->error;
               }
               $stmt->close();

                 $query = "DELETE FROM x_hivtest_self
                 WHERE uid=? AND collect_date=? ";
               //  echo "$uid, $screen_date, $proj_id / $query";
                 $stmt = $mysqli->prepare($query);
                 $stmt->bind_param('ss', $uid, $visit_date);
                  if($stmt->execute()){
                  }
                  else{
                       $msg_error .= $stmt->error;
                  }
                  $stmt->close();

}// remove_paper_consent

else if($u_mode == "check_st_type"){ // checkST_Type - เช็คชุดตรวจที่เคสเลือก แล้วมาใส่ใน followup

      $uid = isset($_POST["uid"])?$_POST["uid"]:"";
      $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";

      //$query = "SELECT hivself_type FROM x_hivtest_self";

      $query = "SELECT hivself_type FROM x_hivtest_self
      WHERE uid=? AND collect_date=?";

//echo "$uid, $visit_date / $query";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('ss', $uid, $visit_date);
      $hivself_type = "";
      if($stmt->execute()){
        $stmt->bind_result($hivself_type);
        if($stmt->fetch()) {
        }// if

      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

      $rtn["hivself_type"] = $hivself_type;
}// checkST_Type

    else if($u_mode == "select_sut_list"){ // select_sut_list
      $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
      $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
      //$clinic_id = isset($_POST["search_hos_opt"])?$_POST["search_hos_opt"]:"";
      $search_form_opt = isset($_POST["form_opt"])?$_POST["form_opt"]:"";

      $query_no_sel = 1; // 1:basic query 2:
      $query_add_clinic = "";

      if($clinic_id != "%")
      $query_add_clinic = " AND uv.visit_clinic_id = '$clinic_id' ";

      $query_add_search = "";
      if($txt_search != "%%"){
        $query_add_search .= " AND (u.uid LIKE '$txt_search' OR u.uic LIKE '$txt_search' OR ul.pid LIKE '$txt_search') ";
      }

      $arr_data_list = array();
      if($search_form_opt == ""){
        $query = "SELECT ul.uid, ul.pid, u.uic, uv.schedule_date, uv.visit_date, uv.schedule_date, hs.consent, hs.age,
        ps.consent as scrn_consent, ps.consent_type, ul.is_consent ,
        fu.st_exp_send_date, fu.st_sent_date, fu.st_receive_date, fu.st_exp_test_date,fu.st_test_date, fu.st_destroy_date,fu.st_need_repeat,
        fu.st2_exp_send_date,fu.st2_sent_date,fu.st2_receive_date, fu.st2_exp_test_date,fu.st2_test_date, fu.st2_destroy_date

        FROM p_project_uid_list as ul, uic_gen as u, x_sut_pre_screen as ps,
        p_project_uid_visit as uv
           LEFT JOIN x_hivtest_self as hs ON(uv.uid=hs.uid AND uv.visit_date=hs.collect_date)
           LEFT JOIN x_sut_pre_follow as fu ON(uv.uid=fu.uid AND uv.schedule_date=fu.collect_date)

        WHERE ul.uid=ps.uid AND ul.screen_date=ps.collect_date AND
        ul.uid=u.uid AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND
        ul.proj_id='$proj_id' $query_add_search $query_add_clinic
        ORDER BY ul.pid desc
        ";
      }
      else if($search_form_opt == "1"){ // รอการทำฟอร์มจากอาสา
        $query = "SELECT ul.uid, ul.pid, u.uic, uv.schedule_date, uv.visit_date, uv.schedule_date, hs.consent, hs.age,
        ps.consent as scrn_consent, ps.consent_type, ul.is_consent ,
        fu.st_exp_send_date, fu.st_sent_date, fu.st_receive_date, fu.st_exp_test_date,fu.st_test_date, fu.st_destroy_date,fu.st_need_repeat,
        fu.st2_exp_send_date,fu.st2_sent_date,fu.st2_receive_date, fu.st2_exp_test_date,fu.st2_test_date, fu.st2_destroy_date

        FROM p_project_uid_list as ul, uic_gen as u, x_sut_pre_screen as ps,
        p_project_uid_visit as uv
           LEFT JOIN x_hivtest_self as hs ON(uv.uid=hs.uid AND uv.visit_date=hs.collect_date)
           LEFT JOIN x_sut_pre_follow as fu ON(uv.uid=fu.uid AND uv.schedule_date=fu.collect_date)

        WHERE ul.uid=ps.uid AND ul.screen_date=ps.collect_date AND
        ul.uid=u.uid AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND
        uv.visit_date='0000-00-00' AND
        ul.proj_id='$proj_id' $query_add_search $query_add_clinic
        ORDER BY ul.pid desc
        ";
      }
      else if($search_form_opt == "2"){ // รอส่งชุดตรวจ

        $query = "SELECT ul.uid, ul.pid, u.uic, uv.schedule_date, uv.visit_date, uv.schedule_date, hs.consent, hs.age,
        ps.consent as scrn_consent, ps.consent_type, ul.is_consent ,
        '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00',
        '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00'
        FROM p_project_uid_list as ul, uic_gen as u, x_sut_pre_screen as ps,
        x_hivtest_self as hs,
        p_project_uid_visit as uv

        WHERE ul.uid=ps.uid AND ul.screen_date=ps.collect_date AND
        ul.uid=u.uid AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND
        uv.uid=hs.uid AND uv.visit_date=hs.collect_date AND
        ul.uid NOT IN (
           select fu.uid from x_sut_pre_follow as fu , p_project_uid_visit as uv
           where fu.uid=uv.uid AND fu.collect_date=uv.visit_date
           $query_add_clinic
        )
        AND ul.proj_id='$proj_id' $query_add_search $query_add_clinic
UNION
        SELECT ul.uid, ul.pid, u.uic, uv.schedule_date, uv.visit_date, uv.schedule_date, hs.consent, hs.age,
        ps.consent as scrn_consent, ps.consent_type, ul.is_consent ,
        fu.st_exp_send_date, fu.st_sent_date, fu.st_receive_date, fu.st_exp_test_date,fu.st_test_date, fu.st_destroy_date,fu.st_need_repeat,
        fu.st2_exp_send_date,fu.st2_sent_date,fu.st2_receive_date, fu.st2_exp_test_date,fu.st2_test_date, fu.st2_destroy_date

        FROM p_project_uid_list as ul, uic_gen as u, x_sut_pre_screen as ps,
        p_project_uid_visit as uv, x_hivtest_self as hs, x_sut_pre_follow as fu

        WHERE ul.uid=ps.uid AND ul.screen_date=ps.collect_date AND
        ul.uid=u.uid AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND
        uv.uid=hs.uid AND uv.visit_date=hs.collect_date AND
        uv.uid=fu.uid AND uv.schedule_date=fu.collect_date AND
        fu.st_sent_date = '0000-00-00' AND
        fu.st_receive_date = '0000-00-00' AND
(
        (fu.st_sent_date = '0000-00-00' AND fu.st_receive_date = '0000-00-00') OR
        (fu.st_need_repeat = 'Y' AND fu.st2_sent_date = '0000-00-00' AND fu.st2_receive_date = '0000-00-00')
) AND

        ul.proj_id='$proj_id' $query_add_search $query_add_clinic

        ORDER BY visit_date asc
        ";

      }
      else if($search_form_opt == "3"){ // รอใช้ชุดตรวจ

        $query = "SELECT ul.uid, ul.pid, u.uic, uv.schedule_date, uv.visit_date, uv.schedule_date, hs.consent, hs.age,
        ps.consent as scrn_consent, ps.consent_type, ul.is_consent ,
        fu.st_exp_send_date, fu.st_sent_date, fu.st_receive_date, fu.st_exp_test_date,fu.st_test_date, fu.st_destroy_date,fu.st_need_repeat,
        fu.st2_exp_send_date,fu.st2_sent_date,fu.st2_receive_date, fu.st2_exp_test_date,fu.st2_test_date, fu.st2_destroy_date

        FROM p_project_uid_list as ul, uic_gen as u, x_sut_pre_screen as ps,
        p_project_uid_visit as uv, x_hivtest_self as hs, x_sut_pre_follow as fu

        WHERE ul.uid=ps.uid AND ul.screen_date=ps.collect_date AND
        ul.uid=u.uid AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND
        uv.uid=hs.uid AND uv.visit_date=hs.collect_date AND
        uv.uid=fu.uid AND uv.schedule_date=fu.collect_date AND
(
    (fu.st_test <> 'N' AND (fu.st_sent_date <> '0000-00-00' OR fu.st_receive_date <> '0000-00-00')
     AND fu.st_test_date = '0000-00-00')
    OR
    (fu.st_need_repeat ='Y' AND fu.st2_test <> 'N' AND
      (fu.st2_sent_date <> '0000-00-00' OR fu.st2_receive_date <> '0000-00-00')
      AND fu.st_test_date = '0000-00-00')

) AND
        ul.proj_id='$proj_id' $query_add_search $query_add_clinic
        ORDER BY ul.pid desc
        ";

      }
      else if($search_form_opt == "4"){ // รอทำลายชุดตรวจ
        $query = "SELECT ul.uid, ul.pid, u.uic, uv.schedule_date, uv.visit_date, uv.schedule_date, hs.consent, hs.age,
        ps.consent as scrn_consent, ps.consent_type, ul.is_consent ,
        fu.st_exp_send_date, fu.st_sent_date, fu.st_receive_date, fu.st_exp_test_date,fu.st_test_date, fu.st_destroy_date,fu.st_need_repeat,
        fu.st2_exp_send_date,fu.st2_sent_date,fu.st2_receive_date, fu.st2_exp_test_date,fu.st2_test_date, fu.st2_destroy_date

        FROM p_project_uid_list as ul, uic_gen as u, x_sut_pre_screen as ps,
        p_project_uid_visit as uv, x_hivtest_self as hs, x_sut_pre_follow as fu

        WHERE ul.uid=ps.uid AND ul.screen_date=ps.collect_date AND
        ul.uid=u.uid AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND
        uv.uid=hs.uid AND uv.visit_date=hs.collect_date AND
        uv.uid=fu.uid AND uv.schedule_date=fu.collect_date AND


(
        (fu.st_destroy_date = '0000-00-00' AND fu.st_test = 'N') OR
        (fu.st2_destroy_date = '0000-00-00' AND fu.st2_test = 'N')
)  AND
        ul.proj_id='$proj_id' $query_add_search $query_add_clinic
        ORDER BY ul.pid desc
        ";
      }
      else if($search_form_opt == "0"){

        $query = "SELECT u.uid, '', u.uic, ps.collect_date, '0000-00-00', '0000-00-00', '', '',
        ps.consent as scrn_consent, ps.consent_type, '' ,
        '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00','0000-00-00', '0000-00-00','',
        '0000-00-00','0000-00-00','0000-00-00', '0000-00-00','0000-00-00', '0000-00-00'

        FROM uic_gen as u, x_sut_pre_screen as ps
        WHERE u.uid= ps.uid AND
        (ps.consent <> 'Y' OR (ps.consent = 'Y' AND ps.uid NOT IN
        (select uid from x_hivtest_self where consent='Y')
      ))
        ORDER BY ps.collect_date desc
        ";
      }


    //    echo "$clinic_id / $query";
          $stmt = $mysqli->prepare($query);
          if($stmt->execute()){
            $stmt->bind_result($uid, $pid, $uic,$screen_date, $visit_date, $schedule_date, $consent, $age,
            $scrn_consent, $consent_type, $is_confirm_consent,
            $st_exp_send_date, $st_send_date, $st_receive_date, $st_exp_test_date,$st_test_date, $st_destroy_date,$st_need_repeat,
            $st2_exp_send_date,$st2_send_date,$st2_receive_date, $st2_exp_test_date,$st2_test_date, $st2_destroy_date

          );

                 while ($stmt->fetch()) {
                   $arr_data = array();
                   $arr_data["pid"]= $pid;
                   $arr_data["uic"]= $uic;
                   $arr_data["uid"]= $uid;
                   $arr_data["s_consent"]= $scrn_consent;
                   $arr_data["consent_type"]= $consent_type;
                   $arr_data["p_consent"]= ($consent !== NULL)?$consent:"";

                   $arr_data["cfm_consent"]= $is_confirm_consent;

                   $arr_data["screen_date"]=$screen_date;
                   $arr_data["visit_date"]= ($visit_date != "0000-00-00")?$visit_date:"";

                   $arr = getSUTDate($st_exp_send_date, $st_send_date, $st_receive_date, $st_exp_test_date,$st_test_date, $st_destroy_date,$st_need_repeat,
                   $st2_exp_send_date,$st2_send_date,$st2_receive_date, $st2_exp_test_date,$st2_test_date, $st2_destroy_date
                   );

                   $arr_data["send_date"]=$arr["send_date"];
                   $arr_data["receive_date"]=$arr["receive_date"];
                   $arr_data["test_date"]=$arr["test_date"];
                   $arr_data["destroy_date"]=$arr["destroy_date"];

                   $arr_data["test_no"]=($st_need_repeat == 'Y')?"2":"1";


                   $arr_data_list[]=$arr_data;
                 }// while
               }
               else{
                 $msg_error .= $stmt->error;
               }
               $stmt->close();
               $rtn['datalist'] = $arr_data_list;
}// select_sut_list



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



 function getSUTDate($st_exp_send_date, $st_send_date, $st_receive_date, $st_exp_test_date,$st_test_date, $st_destroy_date,$st_need_repeat,
 $st2_exp_send_date,$st2_send_date,$st2_receive_date, $st2_exp_test_date,$st2_test_date, $st2_destroy_date
 ){

   $send_date = "";
   $receive_date = "-";
   $test_date = "";
   $destroy_date = "-";

   $arrDate = array();
   if($st_need_repeat != 'Y'){ // 1st test
     if($st_send_date != "0000-00-00" && $st_send_date !==NULL) $send_date = changeToThaiDate($st_send_date);
     else{
       if($st_exp_send_date != "0000-00-00"  && $st_exp_send_date !==NULL){
         $send_date = "(นัด) ".changeToThaiDate($st_exp_send_date);
       }
       else if($st_receive_date != "0000-00-00"  && $st_receive_date !==NULL){
         $send_date = "รับที่ CBO";
       }
       else $send_date = "ยังไม่กำหนด";
     }

     if($st_receive_date != "0000-00-00" && $st_receive_date !==NULL) $receive_date = changeToThaiDate($st_receive_date);

     if($st_test_date != "0000-00-00" && $st_test_date !==NULL) $test_date = changeToThaiDate($st_test_date);
     else{
       if($st_exp_test_date != "0000-00-00" && $st_exp_test_date !==NULL){
         $test_date = "(นัด) ".changeToThaiDate($st_exp_test_date);
       }
       else $test_date = "ยังไม่กำหนด";
     }

     if($st_destroy_date != "0000-00-00" && $st_destroy_date !==NULL) $destroy_date = changeToThaiDate($st_destroy_date);
   }
   else{ // 2nd test
     if($st2_send_date != "0000-00-00" && $st2_send_date !==NULL) $send_date = changeToThaiDate($st2_send_date);
     else{
       if($st2_exp_send_date != "0000-00-00"  && $st2_exp_send_date !==NULL){
         $send_date = "(นัด) ".changeToThaiDate($st2_exp_send_date);
       }
       else if($st2_receive_date != "0000-00-00"  && $st2_receive_date !==NULL){
         $send_date = "รับที่ CBO";
       }
       else $send_date = "ยังไม่กำหนด";
     }

     if($st2_receive_date != "0000-00-00" && $st2_receive_date !==NULL) $receive_date = changeToThaiDate($st2_receive_date);

     if($st2_test_date != "0000-00-00" && $st2_test_date !==NULL) $test_date = changeToThaiDate($st2_test_date);
     else{
       if($st2_exp_test_date != "0000-00-00" && $st2_exp_test_date !==NULL){
         $test_date = "(นัด) ".changeToThaiDate($st2_exp_test_date);
       }
       else $test_date = "ยังไม่กำหนด";
     }

     if($st2_destroy_date != "0000-00-00" && $st2_destroy_date !==NULL) $destroy_date = changeToThaiDate($st2_destroy_date);
   } // else


   $arrDate["send_date"]=$send_date;
   $arrDate["receive_date"]=$receive_date;
   $arrDate["test_date"]=$test_date;
   $arrDate["destroy_date"]=$destroy_date;

   return $arrDate;
 }

 function calculateUICAge($uic_param){
   //mb_substr(ข้อความ,เริ่มต้นตัดที่อักขระ,จำนวนอักขระที่ตัด,'UTF-8');
    $dob_date = mb_substr($uic_param,2,2, 'UTF-8') ;
    $dob_month = mb_substr($uic_param,4,2, 'UTF-8') ;
    $dob_year = "25".mb_substr($uic_param,6,2, 'UTF-8') ;
    $dob_year = ((int) $dob_year)-543;

    $dob = $dob_year."-$dob_month-$dob_date";
    $today = date("Y-m-d");
    $diff = date_diff(date_create($dob), date_create($today));

    $arr_age = array();
    $arr_age['Y'] = $diff->format('%y');
    $arr_age['M'] = $diff->format('%m');
    $arr_age['D'] = $diff->format('%d');

    return $arr_age;
 }


  function checkExistingPID($uid){
        global $mysqli;

        $query = "SELECT count(uid)
        FROM p_project_uid_list WHERE uid = ? AND pid='wait_pid' AND proj_id='SUT_PRE'";

         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("s", $uid);
         if($stmt->execute()){
           $stmt->bind_result($count);
           if ($stmt->fetch()) {

           }
         }
         else{
             $msg_error .= $stmt->error;
         }
         $stmt->close();

         if($count == 1) return true;
         else false;

  }
