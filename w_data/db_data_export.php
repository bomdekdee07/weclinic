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

  if($u_mode == "data_export"){ // select UID Data
    $proj_id = isset($_POST["proj_id"])?urldecode($_POST["proj_id"]):"";
    $staff_clinic_id = $_SESSION['weclinic_id'];
    $arr_uid = array();
    $arr_proj_data = array();
  
           $query = "SELECT u.uid, u.uic2 as uic, p.reg_date, p.fname, p.sname, p.contact,
           p.email, p.address,p.district, p.province
           FROM uic_gen as u LEFT JOIN basic_reg as p ON (p.uic=u.uic)
           WHERE (u.uid=? OR u.uic2=?)
           ";


    //echo "$txt_search, $txt_search, $clinic_id/$query";
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("ss", $txt_search, $txt_search);
           if($stmt->execute()){
             $stmt->bind_result($uid, $uic, $reg_date, $fname, $sname, $tel, $email, $address,$district, $province);

             if ($stmt->fetch()) {
               $arr_uid["uid"]= $uid;
               $arr_uid["uic"]= $uic;

               if($fname !== NULL ){
                 $arr_uid["name"]="$fname $sname";
                 $arr_uid["address"]="$district $province";
                 $arr_uid["tel"]=$tel;
                 $arr_uid["email"]=$email;
               }
               else{
                 $arr_uid["name"]="<span class='text-danger'>รอข้อมูลพื้นฐานจากการลงทะเบียนใน iclnic</span>";
                 $arr_uid["address"]="-รอข้อมูล- <a href='http://192.168.100.11/iclinic/dashboard/auth_l_0/uic_portal.php' target='_blank'> (คลิกเพื่อลงทะเบียน)</a>";
                 //$arr_uid["address"]="-รอข้อมูล-";
                 $arr_uid["tel"]="";
                 $arr_uid["email"]="";
               }


             }// if
           }
           else{
             $msg_error .= $stmt->error;
           }
           $stmt->close();

           $query = "SELECT distinct p.proj_id,pj.proj_name, p.uid, p.pid, p.clinic_id,c.clinic_name, p.uid_status, p.screen_date,p.enroll_date, p.clinic_id,
           v.visit_date as last_visit_date, next_v.schedule_date as next_schedule_date
           FROM p_project as pj, p_clinic as c,
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
           WHERE p.uid=? AND p.proj_id=pj.proj_id AND c.clinic_id=p.clinic_id
           AND p.uid_status NOT IN ('10')
           ORDER BY p.enroll_date
           ";
  //  echo "$uid/$query";
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("s", $uid);
           if($stmt->execute()){
             $stmt->bind_result($proj_id, $proj_name, $uid, $pid, $clinic_id, $clinic_name, $uid_status,
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
               $arr_proj["clinic_name"]=$clinic_name;

               /*
               $arr_proj["last_visit_date"]=($last_visit_date != "0000-00-00")?$last_visit_date:"";
               $arr_proj["next_schedule_date"]=($next_schedule_date != "0000-00-00")?$next_schedule_date:"";
  */
               $arr_proj["last_visit_date"]=($last_visit_date !== NULL)?$last_visit_date:"";
               $arr_proj["next_schedule_date"]=($next_schedule_date !== NULL)?$next_schedule_date:"";

               $arr_proj_data[]=$arr_proj;
             }// while

           }
           else{
             $msg_error .= $stmt->error;
           }

           $rtn['uid_data'] = $arr_uid;
           $rtn['proj_list'] = $arr_proj_data;

  }// select_data_uid
  else if($u_mode == "select_uid_schedule_list"){ // select UID schedule date list (all uic)
    $schedule_date_beg = isset($_POST["schedule_date_beg"])?urldecode($_POST["schedule_date_beg"]):"";
    $schedule_date_end = isset($_POST["schedule_date_end"])?urldecode($_POST["schedule_date_end"]):"";

    $clinic_id = $_SESSION['weclinic_id'];
    $arr_uid_list = array();

    $query = "SELECT u.uid, u.uic2, ul.pid, uv.schedule_date, uv.visit_date, uv.visit_id,
  uv.proj_id, uv.group_id ,uv.visit_status,uv.schedule_note, p.proj_name, ps.status_name
  FROM p_project_uid_visit as uv, p_project_uid_list as ul, uic_gen as u,
  p_project as p, p_visit_status as ps
  WHERE uv.proj_id=p.proj_id AND uv.proj_id=ul.proj_id AND uv.visit_status=ps.status_id
  AND u.uid=uv.uid AND uv.uid=ul.uid AND ul.uid_status=1 AND uv.visit_id <> 'SCRN'
  AND ul.uid_status = '1' AND uv.visit_status NOT IN('11')
  AND ul.clinic_id like ?
  AND (uv.schedule_date >= ? AND uv.schedule_date <= ?)
  ORDER BY uv.schedule_date asc   ";

    //echo "$clinic_id, $schedule_date/ $query";
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("sss", $clinic_id, $schedule_date_beg, $schedule_date_end);
           if($stmt->execute()){
             $stmt->bind_result($uid, $uic, $pid, $schedule_date, $visit_date,
             $visit_id, $proj_id, $group_id ,$visit_status,$schedule_note, $proj_name, $status_name);

             while ($stmt->fetch()) {
               $arr_uid = array();
               $arr_uid["uid"]= $uid;
               $arr_uid["uic"]= $uic;
               $arr_uid["pid"]=$pid;
               $arr_uid["schedule_date"]=$schedule_date;
               $arr_uid["visit_date"]=$visit_date;
               $arr_uid["pid"]=$pid;
               $arr_uid["visit_id"]=$visit_id;
               $arr_uid["proj_id"]=$proj_id;
               $arr_uid["proj_name"]=$proj_name;
               $arr_uid["group_id"]=$group_id;
               $arr_uid["status_id"]=$visit_status;
               $arr_uid["status_name"]=$status_name;
               $arr_uid["schedule_note"]=$schedule_note;
               $arr_uid_list[]=$arr_uid;
             }// while
           }
           else{
             $msg_error .= $stmt->error;
           }

           $stmt->close();

           $rtn['uid_list'] = $arr_uid_list;

  }// select_uid_schedule_list

  $mysqli->close();
}




 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;

 $rtn['flag_auth'] = $flag_auth;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
