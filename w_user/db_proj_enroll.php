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

  if($u_mode == "sel_pre_project_enroll"){
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $clinic_id = $_SESSION['weclinic_id'];

    // select project group  for uid to enroll
    $query = "SELECT p.proj_group_amt, p.proj_pid_format, p.proj_pid_runing_digit, pc.prefix_id
    FROM p_project as p, p_project_clinic as pc
    WHERE p.proj_id=? AND p.proj_id=pc.proj_id AND pc.clinic_id=?
    ";

  //echo "$uid/$clinic_id/ $query" ;
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss',$proj_id, $clinic_id);
    if($stmt->execute()){
      $stmt->bind_result($group_amt, $pid_format, $pid_digit, $prefix_id);
      if ($stmt->fetch()) {
        $rtn['pid_format'] = $pid_format;
        $rtn['pid_digit'] = $pid_digit;
        $rtn['clinic_prefix_id'] = $prefix_id;
      }// if
    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();

    $arr_data = array();
    if ($group_amt > 1){

      // select project group  for uid to enroll
      $query = "SELECT g.proj_group_id, g.proj_group_name
      FROM p_project_group as g
      WHERE g.proj_id=? AND g.is_disable <> 1
      ORDER BY g.proj_group_seq
      ";

  //echo "$uid/$clinic_id/ $query" ;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('s',$proj_id);
      if($stmt->execute()){
        $stmt->bind_result($group_id, $group_name);

        while ($stmt->fetch()) {
          $arr_obj = array();
          $arr_obj["group_id"]=$group_id;
          $arr_obj["group_name"]=$group_name;
          $arr_data[]=$arr_obj;

        }// while

      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

    }
    $rtn['datalist'] = $arr_data;

  }// select_list


  else if($u_mode == "enroll_uid"){ // enroll uid to project
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $clinic_prefix_id = isset($_POST["clinic_prefix_id"])?$_POST["clinic_prefix_id"]:"";
    $pid_digit = isset($_POST["pid_digit"])?$_POST["pid_digit"]:"";
    $pid_format = isset($_POST["pid_format"])?$_POST["pid_format"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:""; // screen date
    $visit_note = isset($_POST["visit_note"])?$_POST["visit_note"]:"";

    $msg_err = checkEnroll($proj_id, $group_id);
    if($msg_err == ""){ // pass enroll verify

          $clinic_id = $_SESSION['weclinic_id'];
          $create_by = $user_id;

          $running_prefix = str_replace("{s}",$clinic_prefix_id,$pid_format);
          $running_prefix = str_replace("{g}",$group_id,$running_prefix);
          $running_prefix = str_replace("{r}","",$running_prefix);

          //echo "($uid/$proj_id/$clinic_prefix_id/$pid_format/$pid_digit/$group_id:$running_prefix)";

        /*
          01-002-013
          SELECT CONCAT('01-002-',
                 LPAD(SUBSTRING(  IF(MAX(pid) IS NULL,0,MAX(pid))   ,8, 3)+1, '3','0'))
          FROM p_project_uid_list WHERE SUBSTRING(pid,1,7) = '01-002-'
        */
        /*
        UPDATE p_project_uid_list SET pid=(select max from (SELECT CONCAT('01-002-',
               LPAD(SUBSTRING(  IF(MAX(pid) IS NULL,0,MAX(pid))   ,8, 3)+1, '3','0')) as max
        FROM p_project_uid_list WHERE SUBSTRING(pid,1,7) = '01-002-') t) where proj_id='PEP'
        */

        $is_success = "";

        $uid_status = '1'; // normal
        $id_digit = $pid_digit;
        $substr_pos_begin = 1+strlen($running_prefix);
        $where_substr_pos_end = strlen($running_prefix);

        $query = "UPDATE p_project_uid_list SET enroll_date=now(), proj_group_id=?, uid_status=?, pid= ";
        $query.= "(select max from (";
        $query.= "SELECT @keyid := CONCAT('$running_prefix',
          LPAD( (SUBSTRING(  IF(MAX(pid) IS NULL,0,MAX(pid)) ,$substr_pos_begin,$id_digit)*1)+1, '$id_digit','0') ";
        $query.= ") as max ";
        $query.= "FROM p_project_uid_list WHERE SUBSTRING(pid,1,$where_substr_pos_end) = '$running_prefix'
        AND proj_id='$proj_id') t) ";
        $query.= "WHERE uid=? AND proj_id=? AND screen_date=? AND pid='wait_pid' ";
        //$query.= "WHERE uid='$uid' AND proj_id='$proj_id' ";
        //echo "$query" ;

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssss',$group_id,$uid_status, $uid, $proj_id, $visit_date);
        if($stmt->execute()){
            $rtn_id = "";
            $inQuery = "SELECT @keyid;";
            $stmt = $mysqli->prepare($inQuery.";");
            $stmt->bind_result($rtn_id);
            if($stmt->execute()){ // get pid
              if($stmt->fetch()){
                $rtn['id'] = $rtn_id;
                $is_success = "Y";
              }
            }
            $rtn['enroll_date'] = getToday();
            $msg_info .= "ลงทะเบียน $uid ได้ PID: $rtn_id สำเร็จแล้ว";
            setLogNote($sc_id, "[$proj_id] enroll $uid [pid:$rtn_id]");
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();


        if($is_success =="Y"){

          $query = "UPDATE p_project_uid_visit SET
          visit_main=1, visit_status=1, visit_note=?
          WHERE uid=? AND proj_id=? AND visit_date=?
          ";
          //echo "$query" ;
          $stmt = $mysqli->prepare($query);
          $stmt->bind_param('ssss',$visit_note, $uid, $proj_id, $visit_date);
          if($stmt->execute()){

          }
          else{
            $msg_error .= $stmt->error;
          }
          $stmt->close();
        }//$is_success =="Y"
    }
    else{ // not pass enroll verify
      $rtn["id"] = "";
      $rtn["msg_enroll"] = "$msg_err";
    }

  }// enroll uid




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



 function checkEnroll($proj_id, $group_id){ // check LIMIT Count in Each group in POC
       global $mysqli;
       $msg_err = "";
       if($proj_id == "POC"){
         /*
         $query = " SELECT count(pid) FROM `p_project_uid_list`
         WHERE pid like '%-$group_id-%' AND proj_id='$proj_id' AND uid_status <> '10'
         ORDER BY `pid` ASC";
*/
/*
         $query = " SELECT count(pid) FROM `p_project_uid_list`
         WHERE proj_group_id = '$group_id' AND proj_id='$proj_id' AND uid_status <> '10'
         ORDER BY `pid` ASC";
*/
         $query = " SELECT count(pid) FROM `p_project_uid_list`
         WHERE proj_group_id = '$group_id' AND proj_id='$proj_id' AND uid_status IN ('1', '2')
         ORDER BY `pid` ASC";

          $stmt = $mysqli->prepare($query);
          if($stmt->execute()){
            $stmt->bind_result($count_pid);
            if ($stmt->fetch()) {

            }
          }
          else{
              $msg_err .= $stmt->error;
          }
          $stmt->close();

          if($group_id == "004" && $count_pid >= 300){
            $msg_err = "ไม่สามารถรับเพิ่มได้ เนื่องจากกลุ่ม $group_id ถึงจำนวนจำกัด 300 เคสแล้ว";
          }
          else if($group_id == "001" && $count_pid >= 629){
            $msg_err = "ไม่สามารถรับเพิ่มได้ เนื่องจากกลุ่ม $group_id ถึงจำนวนจำกัดแล้ว";
          }
/*
          else if($group_id == "002" && $count_pid >= 571){
            $msg_err = "ไม่สามารถรับเพิ่มได้ เนื่องจากกลุ่ม $group_id ถึงจำนวนจำกัดแล้ว";
          }
          */
          else if($group_id == "002" && $count_pid >= 573){
            $msg_err = "ไม่สามารถรับเพิ่มได้ เนื่องจากกลุ่ม $group_id ถึงจำนวนจำกัดแล้ว";
          }

          else if($group_id == "003" && $count_pid >= 600){
            $msg_err = "ไม่สามารถรับเพิ่มได้ เนื่องจากกลุ่ม $group_id ถึงจำนวนจำกัดแล้ว";
          }

          if($msg_err != ""){
            $query = "UPDATE `p_project_group` SET is_disable='1'
            WHERE proj_group_id = '$group_id'  AND proj_id='$proj_id' ";

             $stmt = $mysqli->prepare($query);
             if($stmt->execute()){

             }
             else{
                 $msg_err .= $stmt->error;
             }
             $stmt->close();
          }

       } // poc


        return $msg_err;
 }
