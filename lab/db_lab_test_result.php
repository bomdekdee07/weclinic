<?

include_once("../in_auth_db.php");

//$flag_auth=1;
$res = 0;
$msg_error = "";
$msg_info = "";
$returnData = "";



$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";
$proj_id = "LAB";
$s_id = isset($_SESSION["s_id"])?$_SESSION["s_id"]:"";

if($flag_auth != 0){ // valid user session
//echo "enter02";

  include_once("../function/in_fn_sql_update.php"); // sql update
  include_once("../function/in_fn_date.php"); // date function



//echo "umode : $u_mode";

if($u_mode == "select_lab_test_result"){ // select_lab_test_result

      $uid = isset($_POST["uid"])?$_POST["uid"]:"";
      $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
      $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";

      $arr_data_lab_order = array();

      $query = "SELECT o.lab_order_id, o.lab_order_note,
      o.lab_specimen_receive, o.lab_specimen_collect, o.time_specimen_collect,
      s.id as status_id , s.name as status_name,
      pt.sex
      FROM p_lab_order as o
      LEFT JOIN p_lab_status as s ON o.lab_order_status = s.id
      LEFT JOIN patient_info as pt ON pt.uid = o.uid
      WHERE o.uid = ? AND o.collect_date = ? AND o.collect_time = ?

      ";
  //echo "query1: $uid, $collect_date, $collect_time / $query";

      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("sss",$uid, $collect_date, $collect_time );

      if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
          if($row['time_specimen_collect'] != NULL)
          $row['time_specimen_collect'] = (new DateTime($row['time_specimen_collect']))->format('d/m/Y H:i');

          $arr_data_lab_order = $row;
        }
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();


      $arr_data_list = array();
      //echo "$stop_date,$start_date, $id/ query: $query";
      $txt_lab_result_txt = "";

      $query = "SELECT l.lab_id2, l.lab_id, l.lab_name,
      l.lab_result_type, l.lab_unit,
      r.lab_serial_no, r.barcode, r.lab_result,
      r.lab_result_report, r.lab_result_note,
      r.lab_result_status,r.external_lab,
      rh.lab_std_male_txt as m_lab_std_txt ,
      l.lab_result_min_male as m_min, lab_result_max_male as m_max,
      rh.lab_std_female_txt as f_lab_std_txt ,
      l.lab_result_min_female as f_min, lab_result_max_female as f_max

      ,r.time_confirm, o.is_paid

      FROM
      p_lab_test as l, p_lab_test_result_hist as rh,
      p_lab_order_lab_test as o
      LEFT JOIN p_lab_result as r  ON (r.lab_id=o.lab_id AND r.uid=o.uid
      AND r.collect_date=o.collect_date AND r.collect_time=o.collect_time)
      WHERE
      o.uid=? AND o.collect_date=? AND o.collect_time=?
      AND o.lab_id=l.lab_id AND l.lab_id = rh.lab_id AND rh.start_date <= now() AND rh.stop_date > now()
      ORDER BY l.lab_group_id, l.lab_seq
      ";


  //echo "query1: $uid, $collect_date, $collect_time / $query";

      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("sss",$uid, $collect_date, $collect_time);
      $arr_obj = array();
      $str_specimen = "";
      if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
          $arr_data_list[] = $row;

          if($row["lab_result_type"] == "txt")
          $txt_lab_result_txt .="'".$row["lab_id"]."',";
        }
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();


      $arr_data_result_txt = array();
      $arr_obj = array();
      if($txt_lab_result_txt != ""){
        $txt_lab_result_txt = substr($txt_lab_result_txt,0,strlen($txt_lab_result_txt)-1);

        $str_lab = "";
        $query = "SELECT
        l.lab_id2, r.lab_txt_id as id, r.lab_txt_name as name, r.is_normal
        FROM p_lab_test_result_txt as r, p_lab_test as l

        WHERE l.lab_id IN ($txt_lab_result_txt)
        AND l.lab_id = r.lab_id
        ORDER BY l.lab_id asc, r.is_normal desc
        ";
    //echo "query1:/ $query";

        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){
          $result = $stmt->get_result();

          while($row = $result->fetch_assoc()) {
            if($str_lab != $row["lab_id2"]){
              if($str_lab != "")

              $arr_obj[$row["lab_id2"]] = array();
              $str_lab = $row["lab_id2"];
            }

            $arr_obj[$row["lab_id2"]][$row["id"]] = $row["name"]."|".$row["is_normal"];
          }//while

        }
        else{
        $msg_error .= $stmt->error;
        }
        $stmt->close();

      }//if($txt_lab_result_txt != ""){



      $rtn['data_lab_order'] = $arr_data_lab_order;
      $rtn['data_lab_result'] = $arr_data_list;
      $rtn['datalist_result_choice'] = $arr_obj;
    //  $rtn['data_lab_specimen'] = $arr_obj;

  }// select_lab_test_report

  else if($u_mode == "update_lab_result"){ // update_lab_result
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
    $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";
    $lst_data_result = isset($_POST["lst_data_result"])?$_POST["lst_data_result"]:[];
    $affect_row = 0;
      foreach($lst_data_result as $lst_update) { // extract each item
          $lst_update["uid"] = $uid;
          $lst_update["collect_date"] = $collect_date;
          $lst_update["collect_time"] = $collect_time;
          $lst_update["time_lastupdate"] = (new DateTime())->format('Y-m-d H:i:s'); // update record time
          $lst_update["time_confirm"] = NULL; // reset confirm time
        //echo "lab id: ".  $lst_update["lab_id"];
          $affect_row +=  updateListDataObj("p_lab_result",$lst_update, $s_id);
      }//foreach

      $query = "UPDATE p_lab_order SET staff_lab_save = ?,lab_order_status= IF(lab_order_status='A4' , 'A3', lab_order_status)
      WHERE uid=? AND collect_date=? AND collect_time=?";

      //echo "$time_confirm, $uid,$collect_date,$collect_time / query: $query";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('ssss',$s_id, $uid,$collect_date,$collect_time);
      if($stmt->execute()){
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

      $rtn["affect_row"] = $affect_row;
  }// update_lab_result

  else if($u_mode == "confirm_lab_result"){ // confirm_lab_result
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
    $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";
    $s_id_confirm = isset($_POST["s_id_confirm"])?$_POST["s_id_confirm"]:"";
    $time_confirm = (new DateTime())->format('Y-m-d H:i:s');

    $ttl_wait_lab = 0; $ttl_internal_wait_lab = 0;
    $affect_row = 0;

    $query = "UPDATE p_lab_result SET time_confirm = ?
    WHERE uid=? AND collect_date=? AND collect_time=? AND lab_result <> ''
    AND time_confirm IS NULL
    ";

    //echo "$time_confirm, $uid,$collect_date,$collect_time / query: $query";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssss',$time_confirm, $uid,$collect_date,$collect_time);
    if($stmt->execute()){
      $affect_row = $stmt->affected_rows;

      $sqlCmd = "SELECT o.lab_id as o_lab_id, r.lab_id as r_lab_id, r.lab_result, o.laboratory_id
      FROM p_lab_order_lab_test as o
      LEFT JOIN p_lab_result as r ON (o.lab_id=r.lab_id
      AND o.uid=r.uid AND o.collect_date=r.collect_date AND o.collect_time=r.collect_time)
      WHERE o.uid=? AND o.collect_date=? AND o.collect_time=?
      ORDER BY o.lab_id
      ";
      $stmt = $mysqli->prepare($sqlCmd);
      $stmt->bind_param('sss', $uid,$collect_date,$collect_time);
      if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
          if($row["o_lab_id"] != $row["r_lab_id"]){ // pending result
            // $ttl_wait_lab++; close by bom 2023/07/13

            // internal laboratory
            if($row["laboratory_id"] == 'LBT001')
            $ttl_internal_wait_lab++;
          }


        }
      }

/*

      $sqlCmd = "SELECT COUNT(lab_id) as lab_confirm_amt
      FROM p_lab_result
      WHERE uid='$uid' AND collect_date='$collect_date' AND collect_time='$collect_time' AND
      time_confirm IS NOT NULL
      ";
      //echo 'query: '.$sqlCmd;
      $arrResult = selectDataSql($sqlCmd);
      $lab_confirm_amt = $arrResult[0]["lab_confirm_amt"];

      $sqlCmd = "SELECT COUNT(lab_id) as lab_order_amt
      FROM p_lab_order_lab_test
      WHERE uid='$uid' AND collect_date='$collect_date' AND collect_time='$collect_time'
      ";
      //echo 'query: '.$sqlCmd;
      $arrResult = selectDataSql($sqlCmd);
      $lab_order_amt = $arrResult[0]["lab_order_amt"];
*/
   //echo "$lab_confirm_amt == $lab_order_amt";
  //    $ttl_wait_lab = $lab_order_amt - $lab_confirm_amt;
      if($ttl_wait_lab == 0){ // update lab order after all lab result has been updated
        $lst_update = array();
        $lst_update["uid"] = $uid;
        $lst_update["collect_date"] = $collect_date;
        $lst_update["collect_time"] = $collect_time;
        $lst_update["lab_order_status"] = 'A4';
        $lst_update["staff_confirm"] = $s_id_confirm;
        $lst_update["time_lab_report_confirm"] = $time_confirm;
        updateListDataObj("p_lab_order",$lst_update, $s_id);
      }
      else{
        if($ttl_internal_wait_lab == 0){  // no more internal lab pending
          $lst_update = array();
          $lst_update["uid"] = $uid;
          $lst_update["collect_date"] = $collect_date;
          $lst_update["collect_time"] = $collect_time;
          $lst_update["lab_order_status"] = 'A3';
          $lst_update["staff_confirm"] = $s_id_confirm;
          $lst_update["time_lab_report_confirm"] = $time_confirm;
          updateListDataObj("p_lab_order",$lst_update, $s_id);
        }
      }



    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();

    //$rtn["ttl_confirm_affect"] = $ttl_confirm_affect;
    $rtn["ttl_wait_confirm"] = $ttl_wait_lab;
    $rtn["time_confirm"] = $time_confirm;
    $rtn["confirm_row"] = $affect_row;

  }// confirm_lab_result
  else if($u_mode == "remove_lab_result"){ // remove_lab_result
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $collect_date = isset($_POST["coldate"])?$_POST["coldate"]:"";
    $collect_time = isset($_POST["coltime"])?$_POST["coltime"]:"";
    $lab_id = isset($_POST["labid"])?$_POST["labid"]:"";
    $reason = isset($_POST["reason"])?$_POST["reason"]:"";

    $query = "DELETE FROM p_lab_result
    WHERE uid=? AND collect_date=? AND collect_time=? AND lab_id=?
    ";
  //  error_log("$uid,$collect_date,$collect_time, $lab_id / $query");
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssss',$uid,$collect_date,$collect_time, $lab_id);
    if($stmt->execute()){
      $affect_row = $stmt->affected_rows;
      if($affect_row > 0){
        $res = 1;
        addToLog("[p_lab_result] Remove Lab Result [$uid|$collect_date|$collect_time|$lab_id] Reason:$reason", $s_id);
      }
    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();

  }// remove_lab_result
  else if($u_mode == "update_confirm_lab_staff_seq"){ // update_confirm_lab_staff_seq
    $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];

    $query = "";

    foreach($lst_data as $item){
      $query .= "('".$item['s_id']."', '".$item['s_remark']."'),";
    }// foreach
    if(strlen($query) > 0){
      $query = substr($query,0,strlen($query)-1);
      $query = "INSERT INTO p_staff (s_id, s_remark) VALUES  $query " ;
      $query .= "ON DUPLICATE KEY UPDATE s_remark=VALUES(s_remark)";
      $stmt = $mysqli->prepare($query);
      if($stmt->execute()){
        $affect_row = $stmt->affected_rows;
        if($affect_row > 0){
          $res = 1;
          addToLog("[p_staff] Change Lab Confirm SEQ.", $s_id);
        }
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

    }
  }// update_confirm_lab_staff_seq
  else if($u_mode == "update_data_from_crea_calc"){ // update_data_from_crea_calc
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $collect_date = isset($_POST["coldate"])?$_POST["coldate"]:"";
    $collect_time = isset($_POST["coltime"])?$_POST["coltime"]:"";
    $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];

    $logtxt = "";
    $query = "";

    foreach($lst_data as $item){
      foreach($item as $sDataID=>$sDataValue){
        $query .= "('$uid', '$collect_date', '$collect_time', '$sDataID', '$sDataValue', now(), '$s_id'),";
        $logtxt.= "[$sDataID:$sDataValue]";
      }// foreach
    }// foreach
    if(strlen($query) > 0){
      $query = substr($query,0,strlen($query)-1);
      $query ="INSERT INTO p_data_result (uid, collect_date, collect_time,
        data_id, data_result, lastupdate, s_id) VALUES $query ";
      $query .= " ON DUPLICATE KEY UPDATE data_result=VALUES(data_result), s_id=VALUES(s_id)";

      $stmt = $mysqli->prepare($query);
      if($stmt->execute()){
        $affect_row = $stmt->affected_rows;
        if($affect_row > 0){
          $res = 1;
          addToLog("[p_data_result] UPDATE data from CREA Calc $logtxt", $s_id);
        }
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

    }
  }// update_data_from_crea_calc
$mysqli->close();

}//$flag_auth != 0

 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;
 $rtn['res'] = $res;
 $rtn['flag_auth'] = $flag_auth;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
