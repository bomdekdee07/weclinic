<?
// user management
$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";
//echo "u_mode : $u_mode";

if($u_mode == "login_check" || $u_mode == "forgot_pwd" || $u_mode == "change_forgot_pwd" ){
  session_start();
  //session_destroy();
}
else{
  include_once("../in_auth.php");
}
include_once("../a_app_info.php");
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function
//include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
include_once("../function/in_fn_link.php");
include_once("../function/in_ts_log.php");
include_once("../function/in_fn_sendmail.php");

$msg_error = "";
$msg_info = "";
$returnData = "";



if($u_mode == "login_check"){ // login check


  $sc_id = isset($_POST["staff_id"])?urldecode($_POST["staff_id"]):"";
  $sc_pwd = isset($_POST["staff_pwd"])?urldecode($_POST["staff_pwd"]):"";

  $flag_ok = 0;

  $query = "SELECT sc.sc_id, sc.clinic_id, s.s_id, s.s_name, s.s_email, sc.section_id,
  j.job_id, j.job_name, c.clinic_name, s.s_group, s.section_id as staff_section, ISC.section_id as pribta21_section_id
  FROM p_staff_clinic as sc, p_staff_job as j, p_clinic as c,
  p_staff as s
  LEFT JOIN i_staff_clinic ISC ON ISC.s_id = s.s_id

  WHERE sc.s_id=s.s_id AND sc.sc_status=1 AND s.s_status=1
  AND sc.job_id=j.job_id AND sc.clinic_id=c.clinic_id
  AND sc.sc_id=? AND sc.sc_pwd=?
  ";

//echo "$sc_id/$sc_pwd/ $query" ;
       $stmt = $mysqli->prepare($query);
       $stmt->bind_param('ss',$sc_id, $sc_pwd);
       if($stmt->execute()){
         $stmt->bind_result($sc_id, $clinic_id, $s_id, $s_name, $s_email,$section_id,
         $job_id, $job_name, $clinic_name, $s_group, $staff_section, $pribta21_section_id);
         session_destroy();
         session_start();
         if ($stmt->fetch()) {
           $_SESSION["s_id"] = $s_id;
           $_SESSION["sc_id"] = $sc_id;
           $_SESSION["s_name"] = $s_name;
           $_SESSION["s_email"] = $s_email;
           $_SESSION["weclinic_id"] = $clinic_id;

           $_SESSION["job_id"] = $job_id;
           $_SESSION["weclinic_section_id"] = $section_id;
           
           $_SESSION["section_id"] = $pribta21_section_id;
           $_SESSION["main_section"] = $staff_section;

           $_SESSION["clinic_name"] = $clinic_name;
           $_SESSION["job_name"] = $job_name;
           $_SESSION["s_group"] = $s_group;
           $_SESSION["clinic_id"] = $clinic_id;

           if($staff_section == 'D02'){
             $_SESSION["room_no"] = '26';
           }

          // if($s_group == 1 || $s_group == 99) $_SESSION["weclinic_id"] = "%";
           if($clinic_id == "IHRI") $_SESSION["weclinic_id"] = "%";
           $flag_ok = 1;
         }// if

       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();


       unset($_SESSION['auth']);
       unset($_SESSION['auth_SDHOS']);

       if($flag_ok==1){
         $query = "SELECT proj_id,
         allow_view, allow_enroll, allow_schedule, allow_data, allow_data_log,
         allow_lab, allow_export, allow_query, allow_delete, allow_data_backdate
         FROM p_staff_auth
         WHERE s_id=?
         ";

       //echo "$s_id/$query" ;
       $arr_proj_auth = array();
       $proj_count = 0;
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('s',$s_id);
              if($stmt->execute()){
                $stmt->bind_result($proj_id,
                $allow_view, $allow_enroll, $allow_schedule, $allow_data, $allow_data_log,
                $allow_lab, $allow_export, $allow_query, $allow_delete, $allow_data_backdate);

                while ($stmt->fetch()) {
                  if($allow_view == 1) $arr_proj_auth["view"] =1;
                  if($allow_enroll == 1) $arr_proj_auth["enroll"] =1;
                  if($allow_schedule == 1) $arr_proj_auth["schedule"] =1;
                  if($allow_data == 1) $arr_proj_auth["data"] =1;
                  if($allow_data_log == 1) $arr_proj_auth["log"] =1;
                  if($allow_lab == 1) $arr_proj_auth["lab"] =1;
                  if($allow_export == 1) $arr_proj_auth["export"] =1;
                  if($allow_query == 1) $arr_proj_auth["query"] =1;
                  if($allow_delete == 1) $arr_proj_auth["delete"] =1;
                  if($allow_data_backdate == 1) $arr_proj_auth["data_backdate"] =1;

                  $proj_count++;
                  if($proj_id != "POC")
                  $_SESSION["auth_$proj_id"] = $arr_proj_auth;
                  else
                  $_SESSION["auth"] = $arr_proj_auth; //POC / remove this after do POC part

                }// while


                $cur_menu = "";
                if($proj_count == 1) $cur_menu = $proj_id;
                $_SESSION["cur_menu"] = $cur_menu;
              }
              else{

                $msg_error .= $stmt->error;
              }
              $stmt->close();


              // iclinic session (login from weclinic)
              $_SESSION['auth_level'] ="x";

                  $query = "SELECT full_name, sex, auth_level, clinic_id
                  from login_user_level
                  where login_id=?
              ";

          //  echo "$sc_id/ $query" ;
                   $stmt = $mysqli->prepare($query);
                   $stmt->bind_param('s',$sc_id);
                   if($stmt->execute()){
                     $stmt->bind_result($full_name, $sex, $auth_level, $clinic_id);

                     if ($stmt->fetch()) {
                       $_SESSION['user_data'] = $sc_id;
                   		 $_SESSION['logged'] = "start";
                   	   $_SESSION['auth_level'] =$auth_level;
                   		 $_SESSION['sex'] =$sex;
                   		 $_SESSION['full_name'] =$full_name;
                   		 $_SESSION['clinic_id'] =$clinic_id;
                       //echo "auth_level - ". $_SESSION['auth_level'];
                     }// if

                   }
                   else{
                     $msg_error .= $stmt->error;
                   }
                   $stmt->close();


       }// flag ok

      $inQuery  = "UPDATE p_staff SET s_last_access=NOW() WHERE s_id=? ";
      $stmt = $mysqli->prepare($inQuery);
      $stmt->bind_param("s", $s_id);
      $stmt->execute();

      if($flag_ok == 0){
        $msg_error .= "ไม่สามารถเข้าระบบได้";
        //setLogNote("[$sc_id] can not access to system");
        setLogNote($sc_id, "Can not ccess to system");
      }
      else{

        setLogNote($sc_id, "Access to system");
      }

}// login
else if($u_mode == "change_pwd"){ // change_pwd

  $sc_pwd_old = isset($_POST["staff_pwd_old"])?urldecode($_POST["staff_pwd_old"]):"";
  $sc_pwd_new = isset($_POST["staff_pwd_new"])?urldecode($_POST["staff_pwd_new"]):"";
  $flag_ok = 0;

  $query = "UPDATE p_staff_clinic SET sc_pwd=?
  WHERE sc_id=? AND sc_pwd=?
  ";


//echo "$sc_id, $sc_pwd_new, $sc_pwd_old/ $query" ;
       $stmt = $mysqli->prepare($query);
       $stmt->bind_param('sss',$sc_pwd_new, $sc_id,  $sc_pwd_old);
       if($stmt->execute()){
           $num_row_affect = mysqli_affected_rows($mysqli);
           if($num_row_affect > 0){
             $msg_info .= "เปลี่ยนรหัสผ่านสำเร็จ";
             setLogNote($sc_id, "Change password");
           }
           else {
             $msg_error .= "ไม่สามารถเปลี่ยนรหัสผ่านได้ $s_name";
             setLogNote($sc_id, "Can not change password");
           }
       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();


       $query = "UPDATE login_user_level SET pass_key=?
       WHERE login_id=?
       ";
     //echo "$sc_id, $sc_pwd_new, $sc_pwd_old/ $query" ;
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ss',$sc_pwd_new, $sc_id);
            if($stmt->execute()){
            }

            $stmt->close();

}// change_pwd

else if($u_mode == "forgot_pwd"){ // forgot_pwd

  $staff_id = isset($_POST["staff_id"])?urldecode($_POST["staff_id"]):"";
  $flag_ok = 0;

    $query = "SELECT sc.sc_id, sc.clinic_id, s.s_id, s.s_name, s.s_email, sc.section_id,
    j.job_id, j.job_name, c.clinic_name, s.s_group
    FROM p_staff_clinic as sc, p_staff as s, p_staff_job as j, p_clinic as c
    WHERE sc.s_id=s.s_id AND sc.sc_status=1 AND s.s_status=1
    AND sc.job_id=j.job_id AND sc.clinic_id=c.clinic_id
    AND sc.sc_id=?
    ";

  //echo "$sc_id/$sc_pwd/ $query" ;
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param('s',$staff_id);
         if($stmt->execute()){
           $stmt->bind_result($sc_id, $clinic_id, $s_id, $s_name, $s_email,$section_id,
           $job_id, $job_name, $clinic_name, $s_group);

           if ($stmt->fetch()) {

           }
         else{
           $msg_error .= $stmt->error;
         }
        }
         $stmt->close();

         if($s_email != "" && $s_email !== NULL){
           $today = getToday();
           // create encode link
           $link_encode = "$sc_id:$today";
           $link = encodeSingleLink($link_encode);



               $today_thai = getDBDateThai($today);
               $emailTO = array();
               $emailCC = array();
               $emailBCC = array();

               $emailTO[$s_email] = $s_name;
               $mailSubject = "ขอเปลี่ยนรหัสผ่านในโปรแกรม weClinic ใหม่ เนื่องจากลืมรหัสผ่านเดิม";
               $mailMessage =
               "
               เรียน คุณ$s_name <br>
               <p>
               ตามที่ท่านแจ้งลืมรหัสผ่านเข้าโปรแกรม weClinic ในวันที่ $today_thai
               ทางสถาบันเพื่อการวิจัยและนวัตกรรมด้านเอชไอวี (IHRI) หรือชื่อเดิม หน่วยพรีเวนชั่น ศูนย์วิจัยโรคเอดส์ สภากาชาดไทย
               ขอให้ท่านเข้าเปลี่ยนรหัสผ่าน weClinic ได้ที่ลิ้งค์ด้านล่างนี้ <b><u>ภายในวันที่ $today_thai</u></b>:
               <br>
               <br>
               <a href='".$GLOBALS['site_path']."/system-access/cpwd.php?link=$link' target='_blank'><b>เปลี่ยนรหัสผ่านที่นี่ / Change new password here </b></a>
               <br>
               <br>
               </p>

               <br><br>
               ขอแสดงความนับถือ <br><br>
               <b>สถาบันเพื่อการวิจัยและนวัตกรรมด้านเอชไอวี </b> (ชื่อเดิม หน่วยพรีเวนชั่น ศูนย์วิจัยโรคเอดส์ สภากาชาดไทย)
               <br>
               ระบบส่งอัตโนมัติ  (กรุณาอย่าตอบกลับ) <br>
               </span>
               ";

               $msg_info = sendEmail($mailSubject, $mailMessage,
                         $emailTO,$emailCC,$emailBCC);
              // echo "msg_info ja : $msg_info ";

               setLogNote($sc_id, "Sent forgot password link");
               $rtn['info'] = $msg_info;
               $rtn['email'] = $s_email;
               $rtn['expired_date'] = $today_thai;

               //echo $mailMessage;
        } //$s_email != ""
        else{
          $msg_error .= "รหัสประจำตัวไม่ถูกต้อง";
          $rtn['email'] = "";
          $rtn['expired_date'] = "";
        }


}// forgot_pwd

else if($u_mode == "change_forgot_pwd"){ // change_forgot_pwd

  $sc_id = isset($_POST["staff_id"])?urldecode($_POST["staff_id"]):"";
  $staff_pwd_new = isset($_POST["staff_pwd_new"])?urldecode($_POST["staff_pwd_new"]):"";
  $flag_ok = 0;

  $query = "UPDATE p_staff_clinic SET sc_pwd=?
  WHERE sc_id=?
  ";


//echo "$sc_id, $staff_pwd_new / $query" ;
       $stmt = $mysqli->prepare($query);
       $stmt->bind_param('ss', $staff_pwd_new, $sc_id);
       if($stmt->execute()){

             $msg_info .= "เปลี่ยนรหัสผ่านสำเร็จ";
             setLogNote($sc_id, "Change password from forgot password link");

       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();

 // update in iclinic
       $query = "UPDATE login_user_level SET pass_key=?
       WHERE login_id=?
       ";
     //echo "$sc_id, $sc_pwd_new, $sc_pwd_old/ $query" ;
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ss',$staff_pwd_new, $sc_id);
            if($stmt->execute()){
            }

   $stmt->close();

  $query = "SELECT sc.sc_id, sc.clinic_id, s.s_id, s.s_name, s.s_email, sc.section_id,
  j.job_id, j.job_name, c.clinic_name, s.s_group
  FROM p_staff_clinic as sc, p_staff as s, p_staff_job as j, p_clinic as c
  WHERE sc.s_id=s.s_id AND sc.sc_status=1 AND s.s_status=1
  AND sc.job_id=j.job_id AND sc.clinic_id=c.clinic_id
  AND sc.sc_id=? AND sc.sc_pwd=?
  ";

//echo "$sc_id/$sc_pwd/ $query" ;
       $stmt = $mysqli->prepare($query);
       $stmt->bind_param('ss',$sc_id, $staff_pwd_new);
       if($stmt->execute()){
         $stmt->bind_result($sc_id, $clinic_id, $s_id, $s_name, $s_email,$section_id,
         $job_id, $job_name, $clinic_name, $s_group);

         if ($stmt->fetch()) {
           $_SESSION["sc_id"] = $sc_id;
           $_SESSION["s_name"] = $s_name;
           $_SESSION["s_email"] = $s_email;
           $_SESSION["weclinic_id"] = $clinic_id;
           $_SESSION["job_id"] = $job_id;
           $_SESSION["section_id"] = $section_id;
           $_SESSION["clinic_name"] = $clinic_name;
           $_SESSION["job_name"] = $job_name;
           $_SESSION["s_group"] = $s_group;


           if($clinic_id == "IHRI") $_SESSION["weclinic_id"] = "%";

           $flag_ok = 1;
         }// if

       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();




       if($flag_ok==1){
         $query = "SELECT
         allow_view, allow_enroll, allow_schedule, allow_data, allow_data_log,
         allow_lab, allow_export, allow_query, allow_data_backdate
         FROM p_staff_auth
         WHERE s_id=?
         ";

       //echo "$s_id/$query" ;
       $arr_proj_auth = array();
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('s',$s_id);
              if($stmt->execute()){
                $stmt->bind_result(
                $allow_view, $allow_enroll, $allow_schedule, $allow_data, $allow_data_log,
                $allow_lab, $allow_export, $allow_query, $allow_data_backdate);

                while ($stmt->fetch()) {
                  if($allow_view == 1) $arr_proj_auth["view"] =1;
                  if($allow_enroll == 1) $arr_proj_auth["enroll"] =1;
                  if($allow_schedule == 1) $arr_proj_auth["schedule"] =1;
                  if($allow_data == 1) $arr_proj_auth["data"] =1;
                  if($allow_data_log == 1) $arr_proj_auth["log"] =1;
                  if($allow_lab == 1) $arr_proj_auth["lab"] =1;
                  if($allow_export == 1) $arr_proj_auth["export"] =1;
                  if($allow_query == 1) $arr_proj_auth["query"] =1;
                  if($allow_data_backdate == 1) $arr_proj_auth["data_backdate"] =1;
                }// while

                $_SESSION["auth"] = $arr_proj_auth;

              }
              else{

                $msg_error .= $stmt->error;
              }
              $stmt->close();


              // iclinic session (login from weclinic)
              $_SESSION['auth_level'] ="x";

                  $query = "SELECT full_name, sex, auth_level, clinic_id
                  from login_user_level
                  where login_id=?
              ";

          //  echo "$sc_id/ $query" ;
                   $stmt = $mysqli->prepare($query);
                   $stmt->bind_param('s',$sc_id);
                   if($stmt->execute()){
                     $stmt->bind_result($full_name, $sex, $auth_level, $clinic_id);

                     if ($stmt->fetch()) {
                       $_SESSION['user_data'] = $sc_id;
                   		 $_SESSION['logged'] = "start";
                   	   $_SESSION['auth_level'] =$auth_level;
                   		 $_SESSION['sex'] =$sex;
                   		 $_SESSION['full_name'] =$full_name;
                   		 $_SESSION['clinic_id'] =$clinic_id;
                       //echo "auth_level - ". $_SESSION['auth_level'];
                     }// if

                   }
                   else{
                     $msg_error .= $stmt->error;
                   }
                   $stmt->close();
     }

}// change_forgot_pwd

$mysqli->close();





//echo "msg errr is : $msg_error";



 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
