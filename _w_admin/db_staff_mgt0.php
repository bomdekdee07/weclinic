<?

// staff Data Mgt
include_once("../in_auth_db.php");
include_once("../in_db_conn.php");
include_once("../in_file_prop.php");
include_once("../function/in_fn_date.php"); // date function
include_once("../function/in_file_func.php"); // file function
//include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
include_once("../function/in_fn_link.php");
include_once("../function/in_fn_number.php");
include_once("../function/in_fn_sendmail.php");
include_once("../function/in_ts_log.php");

$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";




if($u_mode == "select_data_staff"){ // select staff Data
  $txt_search = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
  $clinic_id = $_SESSION['clinic_id'];
  $arr_uid = array();
  $arr_proj_data = array();

         $query = "SELECT u.uid, u.uic2 as uic, p.reg_date, p.fname, p.sname, p.contact,
         p.email, p.address,p.district, p.province
         FROM basic_reg as p, uic_gen as u
         WHERE p.uic=u.uic AND (u.uid=? OR u.uic2=?) AND u.clinic_id=?
         ";

//  echo "$txt_search/$query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("sss", $txt_search, $txt_search, $clinic_id);
         if($stmt->execute()){
           $stmt->bind_result($uid, $uic, $reg_date, $fname, $sname, $tel, $email, $address,$district, $province);

           if ($stmt->fetch()) {
             $arr_uid["uid"]= $uid;
             $arr_uid["uic"]= $uic;
             $arr_uid["name"]="$fname $sname";
             $arr_uid["address"]="$district $province";
             $arr_uid["tel"]=$tel;
             $arr_uid["email"]=$email;
           }// if
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();

         $query = "SELECT distinct p.proj_id,pj.proj_name, p.uid, p.pid, p.uid_status, p.screen_date,p.enroll_date, p.clinic_id,
         v.visit_date as last_visit_date, next_v.schedule_date as next_schedule_date
         FROM p_project as pj,
         p_project_uid_list as p
         LEFT JOIN p_project_uid_visit as v
         ON (p.uid=v.uid AND v.visit_date =
         (SELECT v2.visit_date
FROM p_project_uid_visit as v2
WHERE v2.uid=v.uid
ORDER BY v2.visit_date DESC LIMIT 1 )
         )
         LEFT JOIN p_project_uid_visit as next_v
         ON (p.uid=next_v.uid AND next_v.schedule_date =
         (SELECT nv2.schedule_date
FROM p_project_uid_visit as nv2
WHERE nv2.uid=next_v.uid AND nv2.visit_status=0
ORDER BY nv2.schedule_date ASC LIMIT 1 )
         )
         WHERE p.uid=? AND p.proj_id=pj.proj_id
         AND p.uid_status <> 10
         ORDER BY p.enroll_date
         ";
//  echo "$uid/$query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("s", $uid);
         if($stmt->execute()){
           $stmt->bind_result($proj_id, $proj_name, $uid, $pid, $uid_status,
           $screen_date, $enroll_date, $clinic_id, $last_visit_date, $next_schedule_date);

           while ($stmt->fetch()) {

             $arr_proj = array();
             $arr_proj["proj_id"]=$proj_id;
             $arr_proj["proj_name"]=$proj_name;
             $arr_proj["pid"]=$pid;
             $arr_proj["uid_status"]=$uid_status;
             $arr_proj["screen_date"]=$screen_date;
             $arr_proj["enroll_date"]=$enroll_date;
             $arr_proj["clinic_id"]=$clinic_id;
             /*
             $arr_proj["last_visit_date"]=($last_visit_date != "0000-00-00")?$last_visit_date:"";
             $arr_proj["next_schedule_date"]=($next_schedule_date != "0000-00-00")?$next_schedule_date:"";
*/
             $arr_proj["last_visit_date"]=$last_visit_date;
             $arr_proj["next_schedule_date"]=$next_schedule_date;

             $arr_proj_data[]=$arr_proj;
           }// while

         }
         else{
           $msg_error .= $stmt->error;
         }

         $rtn['uid_data'] = $arr_uid;
         $rtn['proj_list'] = $arr_proj_data;

}// select_data_uid
else if($u_mode == "select_list"){ // select_staff_list
  $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
  $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
  $clinic_id = $_SESSION['weclinic_id'];

  $arr_staff_list = array();

  $query = "SELECT s.s_id, sc.sc_id, sc.clinic_id, s.s_name, s.s_email , c.clinic_name, j.job_name, sc.sc_status
  FROM p_staff_clinic as sc , p_staff as s , p_clinic as c, p_staff_job as j
  WHERE s.s_id=sc.s_id AND sc.clinic_id=c.clinic_id AND sc.job_id=j.job_id
  AND s.s_status=1 AND sc.sc_status=1
  AND (s.s_name LIKE ?  OR s.s_id LIKE ? OR sc.sc_id LIKE ? OR j.job_name LIKE ? OR c.clinic_name LIKE ?)
  ORDER BY sc.clinic_id, s.s_id
         ";
  //echo "$clinic_id, $schedule_date/ $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("sssss", $txt_search, $txt_search, $txt_search, $txt_search, $txt_search);
         if($stmt->execute()){
           $stmt->bind_result($s_id, $sc_id, $s_clinic_id, $s_name, $s_email ,$clinic_name, $job_name, $sc_status);

           while ($stmt->fetch()) {
             $arr_staff = array();
             $arr_staff["s_id"]= $s_id;
             $arr_staff["sc_id"]= $sc_id;
             $arr_staff["clinic_id"]= $s_clinic_id;
             $arr_staff["name"]= $s_name;
             $arr_staff["email"]= $s_email;
             $arr_staff["clinic_name"]= $clinic_name;
             $arr_staff["job_name"]= $job_name;
             $arr_staff["sc_status"]= $sc_status;

             $arr_staff_list[]=$arr_staff;
           }// while
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
         $rtn['data_list'] = $arr_staff_list;
}// select_staff_list

else if($u_mode == "send_email_auth"){ // select_staff_list
  $sc_id = isset($_POST["sc_id"])?$_POST["sc_id"]:"";


  $query = "SELECT s.s_id, sc.sc_id, sc.sc_pwd,  sc.clinic_id, s.s_name, s.s_email , c.clinic_name, j.job_name, sc.sc_status
  FROM p_staff_clinic as sc , p_staff as s , p_clinic as c, p_staff_job as j
  WHERE s.s_id=sc.s_id AND sc.clinic_id=c.clinic_id AND sc.job_id=j.job_id
  AND s.s_status=1 AND sc.sc_status=1
  AND sc.sc_id=?
         ";
  //echo "$clinic_id, $schedule_date/ $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("s", $sc_id);
         if($stmt->execute()){
           $stmt->bind_result($s_id, $sc_id, $sc_pwd, $s_clinic_id, $s_name, $s_email ,$clinic_name, $job_name, $sc_status);

           if ($stmt->fetch()) {

               $emailTO = array();
               $emailCC = array();
               $emailBCC = array();

               $emailTO[$s_email] = $s_name;
               $mailSubject = "ข้อมูลการเข้าโปรแกรม weClinic ";
               $mailMessage =
               "
               เรียน คุณ$s_name <br>
               <p>
               ทางหน่วยพรีเวนชั่นฯ ขอแจ้งข้อมูลการเข้าใช้งานโปรแกรม weClinic
               <br>

               <br>
                 ข้อมูลรหัสประจำตัว และรหัสผ่านของท่านคือ <br>
                 รหัสประจำตัว: <b>$sc_id</b><br>
                 รหัสผ่าน: <b>$sc_pwd</b>
                 <br><small><span style='color:red;'>* รหัสผ่าน ท่านสามารถเปลี่ยนแปลงได้ด้วยตนเองในโปรแกรม weClinic</span></small>
               </p>

               ลิ้งค์เข้าโปรแกรม weClinic <br>
               <a href='https://healthcare.prevention-trcarc.org/weclinic.php'>https://healthcare.prevention-trcarc.org/weclinic.php</a>

               <br><br>
               ขอแสดงความนับถือ <br><br>

               หน่วยพรีเวนชั่น ศูนย์วิจัยโรคเอดส์ สภากาชาดไทย <br>
               ระบบส่งอัตโนมัติ  (กรุณาอย่าตอบกลับ) <br>
               </span>
               ";

               $msg_info = sendEmail($mailSubject, $mailMessage,
                         $emailTO,$emailCC,$emailBCC);
              // echo "msg_info ja : $msg_info ";
               setLogNote("$msg_info");
               $rtn['info'] = $msg_info;
           }

         }

}// send_mail_auth

$mysqli->close();

 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;

 $rtn['flag_auth'] = $flag_auth;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
