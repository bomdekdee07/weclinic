<?
// user management
$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";
//echo "u_mode : $u_mode";

if($u_mode == "login_check"){
  session_start();
}
else{
  include_once("../in_auth.php");
}


include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function
//include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
include_once("../function/in_fn_link.php");

$msg_error = "";
$msg_info = "";
$returnData = "";



if($u_mode == "login_check"){ // login check
  $sc_id = isset($_POST["staff_id"])?urldecode($_POST["staff_id"]):"";
  $sc_pwd = isset($_POST["staff_pwd"])?urldecode($_POST["staff_pwd"]):"";
  $flag_ok = 0;

  $query = "SELECT sc.sc_id, sc.clinic_id, s.s_id, s.s_name, s.s_email,
  j.job_id, j.job_name, c.clinic_name
  FROM p_staff_clinic as sc, p_staff as s, p_staff_job as j, p_clinic as c
  WHERE sc.s_id=s.s_id AND sc.sc_status=1 AND s.s_group=99
  AND sc.job_id=j.job_id AND sc.clinic_id=c.clinic_id
  AND sc.sc_id=? AND sc.sc_pwd=?
  ";


//echo "$sc_id, $sc_pwd/ $query" ;
       $stmt = $mysqli->prepare($query);
       $stmt->bind_param('ss',$sc_id, $sc_pwd);
       if($stmt->execute()){
         $stmt->bind_result($sc_id, $clinic_id, $s_id, $s_name, $s_email,
         $job_id, $job_name, $clinic_name);

         if ($stmt->fetch()) {
           $_SESSION["sc_id"] = $sc_id;
           $_SESSION["s_name"] = $s_name;
           $_SESSION["s_email"] = $s_email;
           $_SESSION["clinic_id"] = $clinic_id;
           $_SESSION["job_id"] = $job_id;
           $_SESSION["clinic_name"] = $clinic_name;
           $_SESSION["job_name"] = $job_name;

           $_SESSION["s_group"] = '1';

           $flag_ok = 1;
         }// if

       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();

      $inQuery  = "UPDATE p_staff SET s_last_access=NOW() WHERE s_id=? ";
      $stmt = $mysqli->prepare($inQuery);
      $stmt->bind_param("s", $s_id);
      $stmt->execute();

      if($flag_ok == 0){
        $msg_error .= "ไม่สามารถเข้าระบบได้";
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
           }
           else {
             $msg_error .= "ไม่สามารถเปลี่ยนรหัสผ่านได้ $s_name";
           }
       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();


}// change_pwd


$mysqli->close();


 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
