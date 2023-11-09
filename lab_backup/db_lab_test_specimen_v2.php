<?



include_once("../in_auth_db.php");
//$flag_auth=1;
$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";
$proj_id = "LAB";

//echo "enter01 $open_link";
if($flag_auth != 0){ // valid user session
//echo "enter02";

  include_once("../function/in_fn_sql_update.php"); // sql update

  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_file_func.php"); // file function
  //include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../function/in_fn_link.php");
  include_once("../function/in_fn_number.php");
  include_once("../function/in_fn_sendmail.php");
  include_once("../function/in_ts_log.php");

//echo "umode : $u_mode";

if($u_mode == "check_warning_specimen"){ // select_specimen_collect
$arr_data_list = array();
// is call 1=
$query = "SELECT o.uid, o.lab_order_id as o_id, o.lab_order_status as s, o.is_call as is_call
FROM p_lab_order as o
WHERE (o.lab_order_status = 'A2' and o.is_call <> 10) OR o.is_call =1
ORDER BY o.lab_order_id";

$stmt = $mysqli->prepare($query);

if($stmt->execute()){
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()) {
    $arr_data_list[] = $row;
  }
}
else{
$msg_error .= $stmt->error;
}
$stmt->close();
$rtn['datalist'] = $arr_data_list;

}// select_specimen_collect
else if($u_mode == "select_specimen_collect"){ // select_specimen_collect
$arr_data_list = array();
      //echo "$stop_date,$start_date, $id/ query: $query";
$isBlank = false; $aRowPhd = array();
$sToday = date("Y-m-d");


$query = "SELECT o.uid, o.collect_date as c_date, o.collect_time as c_time,
o.time_confirm_order as cf_time, o.wait_lab_result, st.s_name as doctor_name ,
o.lab_order_status,P.fname,P.sname,P.date_of_birth as dob,
QL.queue, o.staff_order_room as room_no 

FROM p_lab_order as o
LEFT JOIN p_staff as st ON st.s_id = o.staff_order
LEFT JOIN patient_info P ON P.uid=o.uid
LEFT JOIN i_queue_list QL ON (o.uid = QL.uid AND o.collect_date=QL.collect_date AND o.collect_time=QL.collect_time)
 WHERE o.lab_order_status = 'A2' AND o.collect_date = '$sToday'
 ORDER BY o.time_confirm_order";

/*
 $query = "SELECT o.uid, o.collect_date as c_date, o.collect_time as c_time,
 o.time_confirm_order as cf_time, o.wait_lab_result, st.s_name as doctor_name ,
 o.lab_order_status,P.fname,P.sname,P.date_of_birth as dob,
 QL.queue, QL.room_no, QLL.room_no as prev_room

 FROM p_lab_order as o
 LEFT JOIN p_staff as st ON st.s_id = o.staff_order
 LEFT JOIN patient_info P ON P.uid=o.uid
 LEFT JOIN i_queue_list QL ON (o.uid = QL.uid AND o.collect_date=QL.collect_date AND o.collect_time=QL.collect_time)
 LEFT JOIN
 (select room_no from i_queue_list_log QL2 where QL2.uid=QL.uid and QL2.queue=QL.queue ORDER by queue_datetime desc limit 1 ) QLL
  WHERE o.lab_order_status = 'A2' AND o.collect_date = '$sToday'
  ORDER BY o.time_confirm_order";
*/
      $stmt = $mysqli->prepare($query);

      if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
          $arr_data_list[] = $row;
        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();

      $rtn['datalist'] = $arr_data_list;
}// select_specimen_collect



else if($u_mode == "select_specimen_collect_detail"){ // select_specimen_collect_detail
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
    $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";

    $arr_data_list = array();

$query = "SELECT
  o.lab_order_id, o.uid,  o.lab_order_note
  FROM p_lab_order as o
  WHERE  o.uid=? AND o.collect_date=? AND o.collect_time=? ;
";

//echo "query1: $uid, $collect_date, $collect_time / $query";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("sss",$uid, $collect_date, $collect_time);

      if($stmt->execute()){
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc()) {

        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();
      $rtn['data_lab_order'] = $row;

      $arr_opertor = array();
      $arr_opertor["1"]="=";
      $arr_opertor["2"]=">=";
      $arr_opertor[""]="";

      $arr_specimen = array();
      $query = "SELECT distinct t.lab_group_id, sp.specimen_name, ls.specimen_unit,
      ls.specimen_amt, ls.operator
      FROM p_lab_test as t, p_lab_order_lab_test as o ,
      p_lab_group_specimen ls, p_lab_specimen as sp
      WHERE  o.uid=? AND o.collect_date=? AND o.collect_time=?
      AND t.lab_id=o.lab_id AND t.lab_group_id = ls.lab_group_id
      AND ls.specimen_id=sp.specimen_id
      ORDER BY ls.lab_group_id, sp.specimen_name
      ";
//echo "query1: $uid, $collect_date, $collect_time / $query";

      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("sss",$uid, $collect_date, $collect_time);

      if($stmt->execute()){
        $stmt->bind_result(
          $lab_group_id, $specimen_name, $specimen_unit, $specimen_amt,$operator
        );
        $arr_obj = array();
        $check_str = "";
        while ($stmt->fetch()) {
          $str_specimen = "$specimen_name ".$arr_opertor[$operator]." <b>$specimen_amt</b> $specimen_unit";
          if(!isset($arr_specimen[$lab_group_id]))
          $arr_specimen[$lab_group_id] = "$str_specimen";
          else {
            $arr_specimen[$lab_group_id] .= "<br>$str_specimen";
          }

        }//while
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();



      $arr_data = array();
      $arr_data_list = array();

      $query = "SELECT lt.laboratory_id as lbt_id, lt.laboratory_name as lbt_name,
          g.lab_group_id as g_id, g.lab_group_name as g_name, g.lab_group_note as g_note,
          t.lab_id as id ,t.lab_name as name
          FROM p_lab_order_lab_test as o,
          p_lab_test as t, p_lab_test_group as g,
          p_lab_laboratory as lt
          WHERE o.uid=? AND o.collect_date=? AND o.collect_time=?
          AND o.lab_id=t.lab_id AND o.laboratory_id=lt.laboratory_id
          AND t.lab_group_id=g.lab_group_id
          order by lt.laboratory_id, g.lab_group_id, t.lab_id2
          ";

  //echo "query1: $uid, $collect_date, $collect_time / $query";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss",$uid, $collect_date, $collect_time);


        if($stmt->execute()){
          $stmt->bind_result(
            $laboratory_id, $laboratory_name ,
            $lab_group_id, $lab_group_name,$lab_group_note, $lab_id, $lab_name
          );
          $arr_obj = array();
          $check_str = "";
          while ($stmt->fetch()) {
          //  if(!isset($arr_data["$laboratory_id-$lab_group_id"])){
            if($check_str != "$laboratory_id-$lab_group_id"){
              if($check_str != ""){
                 $arr_obj["lab_name"] = substr($arr_obj["lab_name"],0,strlen($arr_obj["lab_name"])-2);
                 $arr_data_list[] = $arr_obj;
              }

              $arr_obj = array();
              $arr_obj["g_name"] = "$laboratory_name - $lab_group_name";
              $arr_obj["g_note"] = "$lab_group_note";
              $arr_obj["id"] = $laboratory_id."_".$lab_group_id;
              $arr_obj["sp"] = $arr_specimen[$lab_group_id];

              $arr_obj["lab_name"] = "";
              $check_str = "$laboratory_id-$lab_group_id";
            }

            $arr_obj["lab_name"] .= "$lab_name, ";

          }// while

          if($check_str != ""){
            $arr_obj["lab_name"] = substr($arr_obj["lab_name"],0,strlen($arr_obj["lab_name"])-2);
             $arr_data_list[] = $arr_obj;
          }
        }
        else{
        $msg_error .= $stmt->error;
        }
        $stmt->close();
        $rtn['data_specimen_summary'] = $arr_data_list;


// specimen collect detail
        $arr_spc_process = array();
        $query = "SELECT *
        FROM p_lab_order_specimen_process
        WHERE barcode IN (
          select barcode from p_lab_order_specimen
          where uid=? AND collect_date=? AND collect_time=?
        )
        ";

  //echo "query1: $uid, $collect_date, $collect_time / $query";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss",$uid, $collect_date, $collect_time);

        if($stmt->execute()){
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()) {
            if(!isset($arr_spc_process[$row["barcode"]])){
              $arr_spc_process[$row["barcode"]] = array();
            }
            $arr_spc_process[$row["barcode"]][] = $row["laboratory_id"]."_".$row["lab_group_id"];
          //  $arr_spc_process[$row["barcode"]] = array($row["lab_group_id"], $row["laboratory_id"]);
          }
        }
        else{
        $msg_error .= $stmt->error;
        }
        $stmt->close();

        $arr_data_list = array();
        $query = "SELECT osp.barcode, osp.specimen_id,
        CONCAT(sp.specimen_name,' (',sp.specimen_unit,')') as spc_name,
        osp.specimen_amt,osp.in_stock, osp.time_specimen_collect as spc_time
        FROM p_lab_order_specimen as osp,
        p_lab_specimen as sp
        WHERE osp.uid=? AND osp.collect_date=? AND osp.collect_time=?
        AND osp.specimen_id=sp.specimen_id
        ORDER BY osp.barcode
        ";

  //echo "query1: $uid, $collect_date, $collect_time / $query";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss",$uid, $collect_date, $collect_time);

        if($stmt->execute()){
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()) {
            if(isset($arr_spc_process[$row["barcode"]])){
              $row["chk"] = $arr_spc_process[$row["barcode"]]; // process: lab_group_id, laboratory_id
            }
            $arr_data_list[] = $row;

          }
        }
        else{
        $msg_error .= $stmt->error;
        }
        $stmt->close();
        $rtn['data_specimen_list'] = $arr_data_list;
  }// select_specimen_collect_detail

// add new specimen collect from lab manually
    else if($u_mode == "update_lab_specimen_collect_add"){ // update_lab_specimen_collect_add
      $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];


      $barcode_chk = $lst_data["str_barcode_chk"];
      $str_barcode_duplicate = "";
      $query = "SELECT barcode
      FROM p_lab_order_specimen
      WHERE barcode IN ($barcode_chk)
      ";
//echo "query1: $uid, $collect_date, $collect_time / $query";
      $stmt = $mysqli->prepare($query);
      if($stmt->execute()){
        $stmt->bind_result($barcode_found);
        while($stmt->fetch()) {
          $str_barcode_duplicate .= "$barcode_found ";
        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();

      if($str_barcode_duplicate != ""){// duplicate barcode found
          $msg_error .= "Duplicate barcode found: $str_barcode_duplicate";
      }
      else { // barcode not duplicate

              $arr_specimen_collect = $lst_data["lst_specimen_collect"];
              foreach($arr_specimen_collect as $lst_data_update) { // extract each item
                  array_push($lst_data_update,array("name"=>"uid", "value"=>$lst_data["uid"]));
                  array_push($lst_data_update,array("name"=>"collect_date", "value"=>$lst_data["collect_date"]));
                  array_push($lst_data_update,array("name"=>"collect_time", "value"=>$lst_data["collect_time"]));
                  array_push($lst_data_update,array("name"=>"specimen_status", "value"=>"S0"));
                  updateListDataAll("p_lab_order_specimen",$lst_data_update);
              }//foreach

              $arr_specimen_lab = $lst_data["lst_specimen_lab"];
              foreach($arr_specimen_lab as $lst_data_update) { // extract each item
                  updateListDataAll("p_lab_order_specimen_process",$lst_data_update);
              }//foreach

      }

}// update_lab_specimen_collect_add

else if($u_mode == "print_specimen_barcode"){ // print_specimen_barcode
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
    $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";
    $print_amt = isset($_POST["print_amt"])?$_POST["print_amt"]:"3";

      $arr_data_list = array();
      $barcode_current_num = 0;
      $query = "SELECT barcode_last_num, lab_order_id
      FROM p_lab_order
      WHERE  uid=? AND collect_date=? AND collect_time=?
      ";
//echo "query1: $uid, $collect_date, $collect_time / $query";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("sss",$uid, $collect_date, $collect_time);
      if($stmt->execute()){
        $stmt->bind_result($barcode_current_num, $lab_order_id);
        if ($stmt->fetch()) {
        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();

      $barcode_last_num = $barcode_current_num + (int)$print_amt;

      $query = "UPDATE p_lab_order SET barcode_last_num=?
      WHERE  uid=? AND collect_date=? AND collect_time=?";
    //echo "$barcode_last_num / $query";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssss", $barcode_last_num, $uid, $collect_date, $collect_time);
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();


      $rtn['barcode_start_num'] = $barcode_current_num+1;
      $rtn['lab_order_id'] = $lab_order_id;
    }
else if($u_mode == "reject_lab_order_confirm"){ // reject_lab_order_confirm
       $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];
     //print_r($lst_data);
       $lst_data_lab_order = array();
       $lst_data_lab_order[] = array("name"=>"uid", "value"=>$lst_data["uid"]);
       $lst_data_lab_order[] = array("name"=>"collect_date", "value"=>$lst_data["collect_date"]);
       $lst_data_lab_order[] = array("name"=>"collect_time", "value"=>$lst_data["collect_time"]);
       $lst_data_lab_order[] = array("name"=>"lab_order_status", "value"=>"A0");

       $now = (new DateTime())->format('Y-m-d H:i:s');
       $lst_data_lab_order[] = array("name"=>"time_confirm_order", "value"=>"NULL");
       updateListDataAll("p_lab_order", $lst_data_lab_order);

}// reject_lab_order_confirm

else if($u_mode == "delete_specimen"){ // delete_specimen
    $obj_data = isset($_POST["obj_data"])?$_POST["obj_data"]:[];

    $affect_row = 0;
    $affect_row = deleteListDataObj("p_lab_order_specimen",$obj_data, $s_id);
    if($affect_row > 0){
      $obj_process = array();
      $obj_process["barcode"] = $obj_data["barcode"];
      $affect_row += deleteListDataObj("p_lab_order_specimen_process",$obj_process, $s_id);
    }

    $rtn["affect_row"] = $affect_row;

}//delete_specimen
else if($u_mode == "update_specimen_collect_detail"){ // update_specimen_collect_detail
    $obj_data = isset($_POST["obj_data"])?$_POST["obj_data"]:[];
    $obj_data_process = isset($_POST["obj_data_process"])?$_POST["obj_data_process"]:[];
/*
    print_r($obj_data);
    print_r($obj_data_process);
*/
    $affect_row = 0;
    $affect_row = updateListDataObj("p_lab_order_specimen",$obj_data, $s_id);

    $arrDel = array();
    $arrDel["barcode"] = $obj_data["barcode"];
    deleteListDataObj("p_lab_order_specimen_process",$arrDel, $s_id);

    foreach($obj_data_process as $lst_update) { // extract each item
      $affect_row +=  updateListDataObj("p_lab_order_specimen_process",$lst_update, $s_id);
    }//foreach

    $query = "SELECT count(osp.uid) as pending_amt, o.lab_order_status, o.lab_order_id
    FROM p_lab_order_specimen as osp, p_lab_order as o
    WHERE osp.specimen_status='' AND
    osp.uid=o.uid AND osp.collect_date=o.collect_date AND osp.collect_time=o.collect_time
    AND osp.uid=? AND osp.collect_date=? AND osp.collect_time=?
    ";
    $arr_param = array( $obj_data["uid"],  $obj_data["collect_date"],  $obj_data["collect_time"]);
    $arr_data = selectDataSql_withParam($query, $arr_param);
  //  print_r($arr_data);
    // update to A2 if there is more specimen to collect
    $lab_order_status = $arr_data[0]["lab_order_status"];
    $lab_order_id = $arr_data[0]["lab_order_id"];
    if($arr_data[0]["pending_amt"] > 0 && $arr_data[0]["lab_order_status"] != "A2" ){

        unset($obj_data["barcode"]);
        unset($obj_data["specimen_id"]);
        unset($obj_data["specimen_amt"]);
        unset($obj_data["in_stock"]);
        $lab_order_status = "A2";
        $obj_data["lab_order_status"] = $lab_order_status;

        //print_r($obj_data);
        updateListDataObj("p_lab_order",$obj_data, $s_id);
    }

  $rtn["lab_order_status"] = $lab_order_status;
  $rtn["lab_order_id"] = $lab_order_id;

}//update_specimen_collect_detail
else if($u_mode == "collect_specimen"){ // collect_specimen
    $obj_data = isset($_POST["obj_data"])?$_POST["obj_data"]:[];

    $affect_row = 0;
    $time_specimen_collect = (new DateTime())->format('Y-m-d H:i:s');

    $obj_data["time_specimen_collect"] = $time_specimen_collect;
    $obj_data["specimen_status"] = "S0";

    $affect_row = updateListDataObj("p_lab_order_specimen",$obj_data, $s_id);

    $query = "SELECT count(osp.uid) as pending_amt, o.lab_order_status, o.lab_order_id
    FROM p_lab_order_specimen as osp, p_lab_order as o
    WHERE osp.specimen_status='' AND
    osp.uid=o.uid AND osp.collect_date=o.collect_date AND osp.collect_time=o.collect_time
    AND osp.uid=? AND osp.collect_date=? AND osp.collect_time=?
    ";

    $arr_param = array( $obj_data["uid"],  $obj_data["collect_date"],  $obj_data["collect_time"]);
    $arr_data = selectDataSql_withParam($query, $arr_param);

    // update to A3 if there is no more specimen to collect
    $lab_order_status = $arr_data[0]["lab_order_status"];
    $lab_order_id = $arr_data[0]["lab_order_id"];
  //  if($arr_data[0]["pending_amt"] == 0 && $arr_data[0]["lab_order_status"] == "A2" ){
  //error_log('Pending amt: '.$arr_data[0]["pending_amt"] );
    if($arr_data[0]["pending_amt"] == 0){
      $lab_order_status = 'A3';



      // update queue to cashier if case did not wait lab result
      $query = "SELECT QL.queue as queue, o.wait_lab_result
         FROM p_lab_order as o
         LEFT JOIN i_queue_list as QL
           ON  (o.uid=QL.uid
           AND o.collect_date=QL.collect_date
           AND o.collect_time=QL.collect_time)
         WHERE  o.uid=? AND o.collect_date=? AND o.collect_time=? ";

         $arr_param = array( $obj_data["uid"],  $obj_data["collect_date"],  $obj_data["collect_time"]);
         $arr_data = selectDataSql_withParam($query, $arr_param);
         // update Queue in Pribta to cashier
         $room_cashier = '26';
         if(isset($arr_data[0]['wait_lab_result'])){
           if($arr_data[0]['wait_lab_result']== '0'){ // not wait lab result
             $lab_order_status = 'B0';
             $query_q = "UPDATE i_queue_list SET room_no=?, queue_call=0, queue_datetime=now(), s_id=?
             WHERE uid=? and collect_date=? and collect_time=?
             ";
             //echo "query1: $qrd / $query";
                   $stmt = $mysqli->prepare($query_q);
                   $stmt->bind_param('sssss',$room_cashier, $s_id, $obj_data["uid"],$obj_data["collect_date"],  $obj_data["collect_time"]);
                   if($stmt->execute()){
                   }
                   else{
                   $msg_error .= $stmt->error;
                   }
                   $stmt->close();


              $clinic_id = (isset($_SESSION["clinic_id"]))?$_SESSION["clinic_id"]:"IHRI";
              $query_q = "INSERT INTO i_queue_list_log (event_code, uid, collect_date, collect_time,
                clinic_id, queue, room_no, queue_type, queue_datetime)
                SELECT 'q_fwd', uid, collect_date, collect_time, clinic_id, queue, room_no, queue_type, queue_datetime
                FROM i_queue_list WHERE uid=? AND collect_date=? AND collect_time=?
              ";
              //echo "query1: $qrd / $query";
              //error_log($obj_data["uid"].$obj_data["collect_date"].$obj_data["collect_time"]." / $query_q");
                    $stmt = $mysqli->prepare($query_q);
                    $stmt->bind_param('sss',$obj_data["uid"],$obj_data["collect_date"], $obj_data["collect_time"]);
                    if($stmt->execute()){
                    }
                    else{
                    $msg_error .= $stmt->error;
                    }
                    $stmt->close();

           }
         } //update queue to cashier (room 26)


        if(isset($obj_data["barcode"])) unset($obj_data["barcode"]);
        if(isset($obj_data["specimen_status"])) unset($obj_data["specimen_status"]);

        $obj_data["lab_order_status"] = $lab_order_status;
        $rtn["lab_order_id"] = $lab_order_id;
        $obj_data["time_specimen_collect"] = $time_specimen_collect;
        //print_r($obj_data);
        updateListDataObj("p_lab_order",$obj_data, $s_id);
    } //if($arr_data[0]["pending_amt"] == 0)



    $rtn["lab_order_status"] = $lab_order_status;
    $rtn["lab_order_id"] = $lab_order_id;
    $rtn["spc_collect_time"] = $time_specimen_collect;

}//collect_specimen
else if($u_mode == "update_specimen_time"){ // update_specimen_time
  $uid = isset($_POST["uid"])?$_POST["uid"]:"";
  $collect_date = isset($_POST["coldate"])?$_POST["coldate"]:"";
  $collect_time = isset($_POST["coltime"])?$_POST["coltime"]:"";
  $specimen_receive = isset($_POST["specimen_receive"])?$_POST["specimen_receive"]:"";
  $specimen_collect = isset($_POST["specimen_collect"])?$_POST["specimen_collect"]:"";
  $affect_row = 0;
  $query = "UPDATE p_lab_order
  SET lab_specimen_receive=?, lab_specimen_collect=?
  WHERE uid=? AND collect_date=? AND collect_time=?
  ";

  //  error_log("$specimen_receive,$specimen_collect,  $uid,$collect_date,$collect_time / $query ");
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sssss', $specimen_receive,$specimen_collect,  $uid,$collect_date,$collect_time);
    if($stmt->execute()){
      $affect_row = $stmt->affected_rows;
    }
    else{
      error_log($stmt->error);
    }
    $stmt->close();
    $rtn['update_amt'] = $affect_row;
}// update_specimen_time




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
