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


    if($u_mode == "select_schedule_poc"){ // select_schedule_poc
      $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
      $query_add = "";


      if($txt_search != "" ){
        $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
        $query_add .= " AND (p.uid LIKE '$txt_search' OR u.uic2 LIKE '$txt_search') ";
      }


      if($staff_clinic_id != "%" ){
        $query_add .= " AND p.site ='$staff_clinic_id' ";
      }

        $arr_data_list = array();

        $query = "SELECT u.uic2, ul.pid, uv.uid, uv.visit_id,uv.schedule_date,
        uv.visit_date, uv.visit_status, uv.group_id

        FROM uic_gen as u, p_project_uid_list as ul,
        p_project_uid_visit as uv

        WHERE uv.proj_id='POC'
        AND uv.uid=u.uid
        AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND ul.uid_status=1
        $query_add
        ORDER BY ul.pid
        ";

      //echo $query;
        $stmt = $mysqli->prepare($query);

        if($stmt->execute()){
        $stmt->bind_result( $uic, $pid, $uid, $visit_id,$schedule_date,
        $visit_date, $visit_status, $group_id
                 );
        $arr_uid = array();
        while ($stmt->fetch()) {
          if(!isset($arr_uid["$uid"])){
            $arr_uid[$uid]= array();
            $arr_uid[$uid]["uid"]=$uid;
            $arr_uid[$uid]["uic"]=$uic;
            $arr_uid[$uid]["pid"]=$pid;
          }
          $visit_date = ($visit_date != "0000-00-00")?$visit_date:"";

          $arr_uid[$uid]["$visit_id-s_date"]=$schedule_date;
          $arr_uid[$uid]["$visit_id-v_date"]=$visit_date;
          $arr_uid[$uid]["$visit_id-g"]=$group_id;
          $arr_uid[$uid]["$visit_id-st"]=$visit_status;

          $arr_data_list[]=$arr_uid;

          }// while

        }// if
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();
        $rtn['datalist'] = $arr_data_list;

    }// select_uid_xpress_list




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
