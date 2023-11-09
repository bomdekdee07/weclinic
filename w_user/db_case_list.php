<?

// Case List in เคสรอดำเนินการ  การให้คำปรึกษา  ผลแล็ป
include_once("../in_auth_db.php");


$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($flag_auth != 0){ // valid user session
  include_once("../in_db_conn.php");
  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_file_func.php"); // file function
  include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../function/in_fn_link.php");
  include_once("../function/in_fn_number.php");


  if($u_mode == "select_in_process_list"){ // select_in_process_list
    $date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:"";
    $date_end = isset($_POST["date_end"])?$_POST["date_end"]:"";
    $case_to = isset($_POST["case_to"])?$_POST["case_to"]:""; // case to which job role eg. CSL, LB

    $clinic_id = $_SESSION['weclinic_id'];

    $query_add = "";
    if($case_to != ""){
      if($case_to == "CSL"){
        $query_add = " AND uv.visit_status IN ('20', '21') ";
      }
      else if($case_to == "LB"){
        $query_add = " AND uv.visit_status IN ('30') ";
      }

    }


    $arr_data_list = array();

    $query = "SELECT u.uid, u.uic2, ul.pid, uv.visit_date, uv.visit_id,
  uv.proj_id, uv.group_id ,uv.visit_status,uv.visit_note,
  p.proj_name, ps.status_name, vl.visit_name
  FROM p_project_uid_visit as uv, p_project_uid_list as ul, uic_gen as u,
  p_project as p, p_visit_status as ps, p_visit_list as vl
  WHERE uv.proj_id=p.proj_id AND uv.proj_id=ul.proj_id AND uv.visit_status=ps.status_id
  AND u.uid=uv.uid AND uv.uid=ul.uid AND ul.uid_status=1
  AND uv.visit_id=vl.visit_id AND uv.proj_id=vl.proj_id
  AND uv.visit_status <> 1 AND ul.clinic_id like ?
  AND (uv.visit_date >= ? AND uv.visit_date <= ?) $query_add
  ORDER BY uv.schedule_date asc   ";

    //echo "$clinic_id, $date_beg, $date_end/ $query";
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("sss", $clinic_id, $date_beg, $date_end);
           if($stmt->execute()){
             $stmt->bind_result($uid, $uic, $pid, $visit_date,
             $visit_id, $proj_id, $group_id ,$visit_status, $visit_note,
             $proj_name, $status_name, $visit_name);

             while ($stmt->fetch()) {
               $arr_data = array();
               $arr_data["uid"]= $uid;
               $arr_data["uic"]= $uic;
               $arr_data["pid"]=$pid;
               $arr_data["visit_date"]=$visit_date;
               $arr_data["pid"]=$pid;
               $arr_data["visit_id"]=$visit_id;
               $arr_data["visit_name"]=$visit_name;
               $arr_data["proj_id"]=$proj_id;
               $arr_data["proj_name"]=$proj_name;
               $arr_data["group_id"]=$group_id;
               $arr_data["status_id"]=$visit_status;
               $arr_data["status_name"]=$status_name;
               $arr_data["visit_note"]=$visit_note;
               $arr_data_list[]=$arr_data;
             }// while
           }
           else{
             $msg_error .= $stmt->error;
           }

           $stmt->close();

           $rtn['data_list'] = $arr_data_list;

  }// select_data_uid


  $mysqli->close();
}// $flag_auth != 0






 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;
 $rtn['flag_auth'] = $flag_auth;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
