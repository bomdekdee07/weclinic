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
$proj_id = "COVID";

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


  //include_once("inc_pid_format.php");



  if($u_mode == "update_screen_id"){ // update screen id replace to tmp id
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";

    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
    $birth_date = isset($_POST["birth_date"])?$_POST["birth_date"]:"";
    $age = isset($_POST["age"])?$_POST["age"]:"";

          $id_prefix = "SCRN";
          $id_digit = 4;
          $substr_pos_begin = 1+strlen($id_prefix);
          $where_substr_pos_end = strlen($id_prefix);
        $query = "UPDATE x_z202009_covid_screen SET uid= ";
        $query.= "(select max from (";
        $query.= "SELECT @keyid := CONCAT('$id_prefix',
          LPAD( (SUBSTRING(  IF(MAX(uid) IS NULL,0,MAX(uid)) ,$substr_pos_begin,$id_digit)*1)+1, '$id_digit','0') ";
        $query.= ") as max ";
        $query.= "FROM x_z202009_covid_screen WHERE SUBSTRING(uid,1,$where_substr_pos_end) = '$id_prefix'
        ) t) ";
        $query.= "WHERE uid=? ";
//echo $query;
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('s',$uid);
              if($stmt->execute()){
                $inQuery = "SELECT @keyid;";
                $stmt = $mysqli->prepare($inQuery.";");
                $stmt->bind_result($rtn_id);
                if($stmt->execute()){ // get leave id
                  if($stmt->fetch()){
                      $rtn['uid'] = $rtn_id;
                      // create temp pid
                      $pid = $rtn_id.$group_id.(new DateTime())->format('d M y H:i:s');
                      $pid = encodeSingleLink($pid);
                      $pid = "T-".str_shuffle($pid);
                      $pid = substr($pid,0,10) ;

                      $rtn['link'] = encodeSingleLink("$group_id:$rtn_id:$visit_date:$birth_date:$age:$pid");
                  }
                }

                $msg_info .= "Screen ID : $rtn_id";

              }
              else{
                $msg_error .= $stmt->error;
              }
              $stmt->close();


  }// update_screen_id


    else if($u_mode == "check_duplicate_pid"){ // check_duplicate_pid
      $name = isset($_POST["name"])?$_POST["name"]:"";
      $sur_name = isset($_POST["sur_name"])?$_POST["sur_name"]:"";
      $dob = isset($_POST["dob"])?$_POST["dob"]:"";
      $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";

      $tbl_name = "x_z202009_covid_visit_g$group_id";
      $pid=""; $visit_date="";
      $query = "SELECT uid as pid, collect_date
      FROM x_z202009_covid_visit_g$group_id
      WHERE dob = ? and name=? and sur_name=?
      ";


//echo "$dob, $name, $sur_name /  $query" ;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('sss',$dob, $name, $sur_name);
      if($stmt->execute()){
        $stmt->bind_result($pid, $visit_date);
        $arr_data = array();
        if ($stmt->fetch()) {
        }// if

      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

      $rtn['pid'] = ($pid !== NULL)?$pid:"";
      $rtn['visit_date'] = ($visit_date !== NULL)?$visit_date:"";
  }// check_duplicate_pid

  else if($u_mode == "update_pid"){ // update screen id replace to tmp id
      $uid = isset($_POST["uid"])?$_POST["uid"]:"";

      $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
      $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";

            $id_prefix = "0$group_id-";
            $id_digit = 4;
            $substr_pos_begin = 1+strlen($id_prefix);
            $where_substr_pos_end = strlen($id_prefix);

            $tbl_name = "x_z202009_covid_visit_g$group_id";


          $query = "UPDATE $tbl_name SET uid= ";
          $query.= "(select max from (";
          $query.= "SELECT @keyid := CONCAT('$id_prefix',
            LPAD( (SUBSTRING(  IF(MAX(uid) IS NULL,0,MAX(uid)) ,$substr_pos_begin,$id_digit)*1)+1, '$id_digit','0') ";
          $query.= ") as max ";
          $query.= "FROM $tbl_name WHERE SUBSTRING(uid,1,$where_substr_pos_end) = '$id_prefix'
          ) t) ";
          $query.= "WHERE uid=? ";
  //echo $query;
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param('s',$uid);
                if($stmt->execute()){
                  $inQuery = "SELECT @keyid;";
                  $stmt = $mysqli->prepare($inQuery.";");
                  $stmt->bind_result($rtn_id);
                  if($stmt->execute()){ // get leave id
                    if($stmt->fetch()){
                        $rtn['pid'] = $rtn_id;
                    }
                  }

                  $msg_info .= "PID : $rtn_id";

                }
                else{
                  $msg_error .= $stmt->error;
                }
                $stmt->close();


    }// update_pid

      else if($u_mode == "update_not_pass_pid"){ // update not pass consent replace temp id
          $uid = isset($_POST["uid"])?$_POST["uid"]:"";

          $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
          $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";

                $id_prefix = "NC-0$group_id-";
                $id_digit = 4;
                $substr_pos_begin = 1+strlen($id_prefix);
                $where_substr_pos_end = strlen($id_prefix);

                $tbl_name = "x_z202009_covid_visit_g$group_id";


              $query = "UPDATE $tbl_name SET uid= ";
              $query.= "(select max from (";
              $query.= "SELECT @keyid := CONCAT('$id_prefix',
                LPAD( (SUBSTRING(  IF(MAX(uid) IS NULL,0,MAX(uid)) ,$substr_pos_begin,$id_digit)*1)+1, '$id_digit','0') ";
              $query.= ") as max ";
              $query.= "FROM $tbl_name WHERE SUBSTRING(uid,1,$where_substr_pos_end) = '$id_prefix'
              ) t) ";
              $query.= "WHERE uid=? ";
      //echo $query;
                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param('s',$uid);
                    if($stmt->execute()){
                      $inQuery = "SELECT @keyid;";
                      $stmt = $mysqli->prepare($inQuery.";");
                      $stmt->bind_result($rtn_id);
                      if($stmt->execute()){ // get leave id
                        if($stmt->fetch()){
                            $rtn['pid'] = $rtn_id;
                        }
                      }

                      $msg_info .= "PID : $rtn_id";

                    }
                    else{
                      $msg_error .= $stmt->error;
                    }
                    $stmt->close();


        }// update_pid
    else if($u_mode == "select_pid"){ // select_pid
      $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
      $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
      $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";

      $query_add = "";
      if($txt_search != "%%"){
        $query_add .= " AND (v.uid LIKE '$txt_search') ";
      }

      $query = "SELECT v.uid as pid, s.uid as screen_id,
      v.collect_date, s.is_pass,
      v.consent_voice_rec, v.consent_video_rec,
      v.consent_accept, v.consent_staff_check
      FROM x_z202009_covid_visit_g$group_id as v, x_z202009_covid_screen as s
      WHERE v.screen_id = s.uid and v.consent='Y' and v.is_disable=0
      $query_add
      ORDER BY v.uid desc
      ";


//echo " $query" ;
      $stmt = $mysqli->prepare($query);
      if($stmt->execute()){
        $stmt->bind_result($pid, $screen_id,
        $collect_date, $is_pass_screen, $consent_voice_rec, $consent_video_rec,
        $consent_accept, $consent_staff_check);
        $arr_data = array();
        while ($stmt->fetch()) {
            $arr_proj = array();
            $arr_proj["pid"]=$pid;
            $arr_proj["sid"]=$screen_id;
            $arr_proj["c_date"]=$collect_date;
            $arr_proj["staff"]=$consent_staff_check;
            $arr_proj["is_online"]=($consent_voice_rec !="")?"1":"0";
            $arr_data[]=$arr_proj;
        }// while
        $rtn['datalist'] = $arr_data;
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();


  }// select_pid
  else if($u_mode == "select_pid2"){ // select_pid2 ผ่านคัดกรอง ไม่ตอบแบบสอบถาม
    $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
    $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";

    $query_add = "";
    if($txt_search != "%%"){
      $query_add .= " AND (v.uid LIKE '$txt_search') ";
    }

    $query = "SELECT v.uid as pid, s.uid as screen_id,
    v.collect_date, s.is_pass,
    v.consent_voice_rec, v.consent_video_rec,
    v.consent_accept, v.consent_staff_check
    FROM x_z202009_covid_visit_g$group_id as v, x_z202009_covid_screen as s
    WHERE v.screen_id = s.uid and (v.consent_accept='N' OR v.consent='N') and v.is_disable=0
    $query_add
    ORDER BY v.uid desc
    ";


//echo " $query" ;
    $stmt = $mysqli->prepare($query);
    if($stmt->execute()){
      $stmt->bind_result($pid, $screen_id,
      $collect_date, $is_pass_screen, $consent_voice_rec, $consent_video_rec,
      $consent_accept, $consent_staff_check);
      $arr_data = array();
      while ($stmt->fetch()) {
          $arr_proj = array();
          $arr_proj["pid"]=$pid;
          $arr_proj["sid"]=$screen_id;
          $arr_proj["c_date"]=$collect_date;
          $arr_proj["staff"]=$consent_staff_check;
          $arr_proj["is_online"]=($consent_voice_rec !="" && $consent_voice_rec !== NULL)?"1":"0";
          $arr_data[]=$arr_proj;
      }// while
      $rtn['datalist'] = $arr_data;
    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();


}// select_pid2



  else if($u_mode == "select_not_pass_scrn"){ // select_pid
    $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
    $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";

    $query_add = "";
    if($txt_search != "%%"){
      $query_add .= " AND (s.uid LIKE '$txt_search') ";
    }

    $query = "SELECT s.uid as screen_id,
    s.collect_date, s.is_pass
    FROM x_z202009_covid_screen as s
    WHERE s.is_pass='N' and s.group_id='$group_id'
    $query_add
    ORDER BY s.uid desc
    ";
//echo " $query" ;
    $stmt = $mysqli->prepare($query);
    if($stmt->execute()){
      $stmt->bind_result($screen_id,
      $collect_date, $is_pass_screen);
      $arr_data = array();
      while ($stmt->fetch()) {
          $arr_proj = array();
          $arr_proj["sid"]=$screen_id;
          $arr_proj["c_date"]=$collect_date;

          $arr_proj["pid"]="";
          $arr_proj["staff"]="";
          $arr_proj["is_online"]="";

          $arr_data[]=$arr_proj;
      }// while
      $rtn['datalist'] = $arr_data;
    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();
}// select_not_pass_scrn

  else if($u_mode == "consent_staff_check"){ // check consent by staff
      $pid = isset($_POST["pid"])?$_POST["pid"]:"";
      $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
      $tbl_name = "x_z202009_covid_visit_g$group_id";

      $query = "UPDATE $tbl_name SET consent_staff_check='$s_id'
      WHERE uid=?
      ";
  //echo $query;
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('s',$pid);
      if($stmt->execute()){
         $rtn["staff_name"] = $s_name;
      }
      else{
          $msg_error .= $stmt->error;
      }
      $stmt->close();


    }// consent_staff_check

    else if($u_mode == "update_contact_data"){ // update_personal_data
      $uid = isset($_POST["uid"])?$_POST["uid"]:"";
      $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
      $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
      $is_tc = isset($_POST["is_tc"])?$_POST["is_tc"]:"";
      $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];

      $update_txt = "";
      $tbl_name = "x_z202009_covid_visit_g$group_id";

      $arr_obj_data = array(); // each data obj
      $col_insert = "";
      $col_value = "";
      $col_update = "";

      // extract each data to domain group
      foreach($lst_data as $item) {
         $col_insert .= $item['name'].",";
         $col_update .= $item['name']."='".$item['value']."',";
         $col_value  .= "'".$item['value']."',";
      }//foreach

      $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
      $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;
      if($col_update !=""){
        $col_update = substr($col_update,0,strlen($col_update)-1);
        $col_update = "collect_time=now(),$col_update";
      }

      if($col_value != ""){
        $query = "INSERT INTO $tbl_name (uid, collect_date,collect_time, $col_insert)
        VALUES ('$uid', '$visit_date',now(), $col_value) On Duplicate Key
        Update $col_update";

 //echo "<br><br>$query;";
        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){
          $msg_info = "ได้ดำเนินการเรียบร้อยแล้ว";

        }
        else{
          $msg_error .= $stmt->error;
          echo "erorr occur ";
        }
        $stmt->close();

        // track change record
        if($is_tc == "Y"){
          include_once("../in_db_conn_tc.php");
          $query = "INSERT INTO $tbl_name (uid, collect_date,collect_time, $col_insert, change_user)
          VALUES ('$uid', '$visit_date',now(), $col_value, '$s_id') On Duplicate Key
          Update $col_update, change_user='$s_id'";

        //echo $query;
          $stmt = $mysqli_tc->prepare($query);
          if($stmt->execute()){
            //$msg_info = "ได้ดำเนินการเรียบร้อยแล้ว";
          }
          else{
            $msg_error .= $stmt->error;
          }
          $stmt->close();
        }// is_tc

      }


   if($is_tc == "Y")$mysqli_tc->close();

  }// update_personal_data

  else if($u_mode == "delete_data"){ // delete_data
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";

    $update_txt = "";
    $tbl_name = "x_z202009_covid_visit_g$group_id";

      $query = "UPDATE $tbl_name SET is_disable=1 WHERE uid =? ";

//echo "<br><br>$query;";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('s',$uid);
      if($stmt->execute()){
        $msg_info = "ได้ดำเนินการเรียบร้อยแล้ว";
        setLogNote($sc_id, "[$proj_id] remove data: [PID:$uid|Group$group_id]");
      }
      else{
        $msg_error .= $stmt->error;
        echo "erorr occur ";
      }
      $stmt->close();

}// delete_data

/*

else if($u_mode == "update_personal_data"){ // update_personal_data
  $uid = isset($_POST["uid"])?$_POST["uid"]:"";
  $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
  $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];

  $update_txt = "";

  foreach($lst_data as $item) {
    $update_txt .= $item['name']."='".$item['value']."',";

  }//foreach
  if($update_txt != "")
  $update_txt = substr($update_txt,0,strlen($update_txt)-1);

  $query = "UPDATE x_z202009_covid_visit_g$group_id SET $update_txt
  WHERE uid=?
  ";
//echo " $query" ;
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s',$uid);
if($stmt->execute()){
$msg_info = "ได้ดำเนินการเรียบร้อยแล้ว";
setLogNote($sc_id, "[$proj_id] save data:Personal Info [PID:$uid|Group$group_id]");

}
else{
$msg_error .= $stmt->error;
echo "erorr occur ";
}
$stmt->close();
}// update_personal_data
*/

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
