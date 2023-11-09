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


if($u_mode == "select_list"){ // select_staff_list
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
else if($u_mode == "get_staff_data"){ // get_staff_data
  $s_id = isset($_POST["s_id"])?$_POST["s_id"]:"";
  $arr_staff = array();
/*
  $query = "SELECT s_id, s_name, s_email , s_remark, s_status, sc.sc_id,
  FROM p_staff as s, p_staff_clinic as sc, p_clinic as c
  WHERE s.s_id=? and s.s_id= sc.s_id
  and sc.sc_status = 1 and sc.clinic_id=c.clinic_id
         ";
*/
         $query = "SELECT s_id, s_name, s_email, s_tel, s_remark, s_status
         FROM p_staff as s
         WHERE s.s_id=?
                ";
  //echo "$s_id/ $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("s", $s_id);
         if($stmt->execute()){
           $stmt->bind_result($s_id, $s_name, $s_email ,$s_tel ,$s_remark, $s_status);

           if ($stmt->fetch()) {
             $arr_staff["s_id"]= $s_id;
             $arr_staff["s_name"]= $s_name;
             $arr_staff["s_email"]= $s_email;
             $arr_staff["s_tel"]= $s_tel;
             $arr_staff["s_remark"]= $s_remark;
             $arr_staff["s_status"]= $s_status;
           }// while
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
         $rtn['arr_staff'] = $arr_staff;
}
else if($u_mode == "get_staff_clinic"){ // get_staff_clinic
  $s_id = isset($_POST["s_id"])?$_POST["s_id"]:"";
  $arr_sc = array();
  $arr_auth_list = array();

  $query = "SELECT s.s_id, s.s_name,
  sc.job_id, sc.clinic_id,sc.sc_status
  FROM p_staff as s, p_staff_clinic as sc, p_clinic as c
  WHERE sc.sc_id=? and s.s_id= sc.s_id
  and sc.sc_status = 1 and sc.clinic_id=c.clinic_id
         ";
  //echo "$s_id/ $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("s", $s_id);
         if($stmt->execute()){
           $stmt->bind_result($s_id, $s_name, $job_id ,$clinic_id ,$sc_status);

           if ($stmt->fetch()) {
             $arr_sc["s_id"]= $s_id;
             $arr_sc["s_name"]= $s_name;
             $arr_sc["job_id"]= $job_id;
             $arr_sc["clinic_id"]= $clinic_id;
             $arr_sc["sc_status"]= $sc_status;
           }// if
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();

         $query = "SELECT * FROM p_staff_auth
         WHERE s_id=?
                ";
         //echo "$s_id/ $query";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("s", $s_id);
                if($stmt->execute()){
                  $result = $stmt->get_result();
                  while ($row = $result->fetch_assoc()) {
                    $arr_auth_list[] = $row;
                    /*
                       echo 'ID: '.$row['id'].'<br>';
                       echo 'First Name: '.$row['first_name'].'<br>';
                       echo 'Last Name: '.$row['last_name'].'<br>';
                       echo 'Username: '.$row['username'].'<br><br>';
                       */
                  }

                  /* free results */
                  $stmt->free_result();

                  /* close statement */
                  $stmt->close();


                }
                else{
                  $msg_error .= $stmt->error;
                }


         $rtn['arr_sc'] = $arr_sc;
         $rtn['auth_list'] = $arr_auth_list;
}// get_staff_clinic

else if($u_mode == "update_staff_personal_data"){ // update_staff_personal_data
  $s_id = isset($_POST["s_id"])?$_POST["s_id"]:"";
  $s_name = isset($_POST["s_name"])?urldecode($_POST["s_name"]):"";
  $s_remark = isset($_POST["s_remark"])?urldecode($_POST["s_remark"]):"";
  $s_tel = isset($_POST["s_tel"])?$_POST["s_tel"]:"";
  $s_email = isset($_POST["s_email"])?$_POST["s_email"]:"";


  $query = "UPDATE p_staff SET s_name=?, s_remark=?, s_tel=?, s_email=?
  WHERE s_id=?
  ";

//echo "$sc_id, $sc_pwd_new, $sc_pwd_old/ $query" ;
       $stmt = $mysqli->prepare($query);
       $stmt->bind_param('sssss',$s_name, $s_remark,$s_tel,$s_email,$s_id );
       if($stmt->execute()){
           $num_row_affect = mysqli_affected_rows($mysqli);
           if($num_row_affect > 0){
             setLogNote($s_id, "Update personal info");
           }
           else {
             setLogNote($s_id, "Can not update personal info");
           }
       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();

}// update_staff_personal_data

else if($u_mode == "get_staff_data"){ // get_staff_data
  $s_id = isset($_POST["s_id"])?$_POST["s_id"]:"";
  $arr_staff = array();
/*
  $query = "SELECT s_id, s_name, s_email , s_remark, s_status, sc.sc_id,
  FROM p_staff as s, p_staff_clinic as sc, p_clinic as c
  WHERE s.s_id=? and s.s_id= sc.s_id
  and sc.sc_status = 1 and sc.clinic_id=c.clinic_id
         ";
*/
         $query = "SELECT s_id, s_name, s_email , s_remark, s_status
         FROM p_staff as s
         WHERE s.s_id=?
                ";
  //echo "$s_id/ $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("s", $s_id);
         if($stmt->execute()){
           $stmt->bind_result($s_id, $s_name, $s_email ,$s_remark, $s_status);

           if ($stmt->fetch()) {
             $arr_staff["s_id"]= $s_id;
             $arr_staff["s_name"]= $s_name;
             $arr_staff["s_email"]= $s_email;
             $arr_staff["s_remark"]= $s_remark;
             $arr_staff["s_status"]= $s_status;
           }// while
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
         $rtn['arr_staff'] = $arr_staff;
}






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
               <a href='https://healthcare.ihri.org/weclinic.php'>https://healthcare.ihri.org/weclinic.php</a>

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
