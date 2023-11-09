<?



include_once("../in_auth_db.php");
//$flag_auth=1;
$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($flag_auth != 0){ // valid user session
  include_once("../function/in_fn_sql_update.php"); // sql update
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_ts_log.php");


if($u_mode == "check_warning_specimen"){ // select_specimen_collect
  $arr_data_list = array();

// 1 select A2 not collected / 2 collect pending for save specimen / 3 wait for specimen check
// UPDATE query: 2021-05-20 - Cancel specimen check by lab team request
  $query = "SELECT distinct o.uid,o.lab_order_id as o_id, 'Collect' as s
    from p_lab_order_specimen as os , p_lab_order as o
    where os.uid=o.uid
    and os.collect_date=o.collect_date
    and os.collect_time=o.collect_time
    and o.lab_order_status = 'A2'
    GROUP BY o.uid, o.collect_date, o.collect_time
    HAVING COUNT(os.uid) = 0

UNION

SELECT distinct o.uid,o.lab_order_id as o_id, 'Collect' as s
    from p_lab_order_specimen as os , p_lab_order as o
    where os.specimen_status = ''
    and os.uid=o.uid
    and os.collect_date=o.collect_date
    and os.collect_time=o.collect_time

  ORDER BY o_id
";

/*
$query = "SELECT distinct o.uid,o.lab_order_id as o_id, 'Collect' as s
  from p_lab_order_specimen as os , p_lab_order as o
  where os.uid=o.uid
  and os.collect_date=o.collect_date
  and os.collect_time=o.collect_time
  and o.lab_order_status = 'A2'
  GROUP BY o.uid, o.collect_date, o.collect_time
  HAVING COUNT(os.uid) = 0

UNION

SELECT distinct o.uid,o.lab_order_id as o_id, 'Collect' as s
  from p_lab_order_specimen as os , p_lab_order as o
  where os.specimen_status = ''
  and os.uid=o.uid
  and os.collect_date=o.collect_date
  and os.collect_time=o.collect_time

UNION

  SELECT distinct o.uid,o.lab_order_id as o_id, 'Check' as s
      from p_lab_order_specimen as os , p_lab_order as o
      where os.specimen_status = 'S0'
      and os.uid=o.uid
      and os.collect_date=o.collect_date
      and os.collect_time=o.collect_time

ORDER BY o_id
";
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


}// check_warning_specimen

else if($u_mode == "select_specimen_collect_queue"){ // select_specimen_collect_queue

  $query = "SELECT o.uid, o.collect_date as c_date, o.collect_time as c_time,
  o.time_confirm_order as cf_time, o.wait_lab_result,
  st.s_name as doctor_name , b.queue as qid, rd.id as qrd,o.staff_order_room,o.lab_order_status,br.fname,br.sname,date_o_b ,mon_o_b,y_o_b
  FROM p_lab_order_specimen as os , p_lab_order as o
  LEFT JOIN p_staff as st
  ON st.s_id = o.staff_order
  LEFT JOIN uic_gen ug
  ON ug.uid = o.uid
  LEFT JOIN basic_reg br
  ON br.uic = ug.uic
  LEFT JOIN k_visit_data as b
  ON binary o.uid=b.uid
  AND binary o.collect_date=b.date_of_visit
  AND binary o.collect_time=b.time_of_visit

  LEFT JOIN k_queue_row_detail as rd on
  (rd.patient_uid = b.uid AND b.queue=rd.queue_row_detail)
  WHERE o.lab_order_status = 'A2'
  and os.uid=o.uid
  and os.collect_date=o.collect_date
  and os.collect_time=o.collect_time
  and o.collect_date = CURDATE()
  GROUP BY o.uid, o.collect_date, o.collect_time
  HAVING COUNT(os.uid) = 0

  ORDER BY o.lab_order_id ";

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

}// select_specimen_collect_queue


else if($u_mode == "select_specimen_check"){ // select_specimen_check pending
  // update specimen check
  $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];
  //print_r($lst_data);
  $query = "UPDATE p_lab_order_specimen SET
  specimen_status='S1' , time_specimen_check=now()
  WHERE barcode=? AND specimen_status <> 'S1' ";
  foreach($lst_data as $barcode) { // extract each barcode
//echo "$barcode / $query";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $barcode);
    if($stmt->execute()){
    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();

  }//foreach

// select unpaid
$arr_unpaid_barcode = array();
$query = "SELECT distinct osp.barcode
FROM p_lab_order_lab_test as ol ,
p_lab_order_specimen as osp,
p_lab_order_specimen_process as ospp
WHERE
osp.specimen_status = 'S0'
AND osp.uid=ol.uid AND osp.collect_date=ol.collect_date AND osp.collect_time=ol.collect_time
AND osp.barcode=ospp.barcode AND ol.laboratory_id=ospp.laboratory_id AND ol.lab_group_id=ospp.lab_group_id
AND ol.is_paid = 0
  ";
  $stmt = $mysqli->prepare($query);

  if($stmt->execute()){
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
      $arr_unpaid_barcode[$row['barcode']] = 1;
    }
  }
  else{
  $msg_error .= $stmt->error;
  }
  $stmt->close();



 // load specimen pending check
$arr_data_list = array();

$query = "SELECT distinct osp.uid,osp.collect_date as c_date,osp.collect_time as c_time,
osp.specimen_id, osp.barcode,
CONCAT(sp.specimen_name, ' ', osp.specimen_amt, ' ',sp.specimen_unit ) AS spc_info,
b.queue as qid, o.wait_lab_result as wait_lab
FROM p_lab_order as o,
p_lab_specimen as sp,
p_lab_order_specimen as osp
LEFT JOIN  k_visit_data as b ON
(binary osp.uid=b.uid
  AND binary osp.collect_date=b.date_of_visit
  AND binary osp.collect_time=b.time_of_visit
  AND osp.collect_date = CURDATE()
)
WHERE osp.specimen_status = 'S0' AND osp.specimen_id=sp.specimen_id
AND osp.uid=o.uid AND osp.collect_date=o.collect_date AND osp.collect_time=o.collect_time

ORDER BY qid
";

//echo "query1: $query";

    $stmt = $mysqli->prepare($query);

    if($stmt->execute()){
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()) {
        if(isset($arr_unpaid_barcode[$row['barcode']])) $row['is_paid'] = '0';
        else $row['is_paid'] = '1';

        $arr_data_list[] = $row;
      }
    }
    else{
    $msg_error .= $stmt->error;
    }
    $stmt->close();
    $rtn['datalist'] = $arr_data_list;

}// select_specimen_check

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

else if($u_mode == "update_specimen_collect_detail"){ // update_lab_specimen_collect_add
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

}

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
    if($arr_data[0]["pending_amt"] == 0){
      $lab_order_status = 'A3';


      $query = "SELECT b.queue as qid, rd.id as qrd, o.wait_lab_result
        FROM p_lab_order as o
        LEFT JOIN k_visit_data as b
          ON binary o.uid=b.uid
          AND binary o.collect_date=b.date_of_visit
          AND binary o.collect_time=b.time_of_visit
        LEFT JOIN k_queue_row_detail as rd on
        (rd.patient_uid = b.uid AND b.queue=rd.queue_row_detail)
        WHERE  o.uid=? AND o.collect_date=? AND o.collect_time=? ";

      $arr_param = array( $obj_data["uid"],  $obj_data["collect_date"],  $obj_data["collect_time"]);
      $arr_data = selectDataSql_withParam($query, $arr_param);

      // update Queue in Pribta to cashier

      if(isset($arr_data['wait_lab_result'])){
        if($arr_data['wait_lab_result']== '0'){ // not wait lab result

          $lab_order_status = 'B0';
          $qrd = $arr_data['qrd'];
          /*
          $query_q = "INSERT INTO k_queue_row_detail_history(id,from_qrd_id,id_room,time_record)
          VALUES('','$qrd','26',NOW())
          ";
*/
          $query_q = "INSERT INTO k_queue_row_detail_history(from_qrd_id,id_room,time_record)
          VALUES('$qrd','26',NOW())
          ";
          //echo "query1: $qrd / $query";
                $stmt = $mysqli->prepare($query_q);
                if($stmt->execute()){
                }
                else{
                $msg_error .= $stmt->error;
                }
                $stmt->close();
        }
      }


        if(isset($obj_data["barcode"])) unset($obj_data["barcode"]);
        if(isset($obj_data["specimen_status"])) unset($obj_data["specimen_status"]);

        $obj_data["lab_order_status"] = $lab_order_status;
        $rtn["lab_order_id"] = $lab_order_id;
        $obj_data["time_specimen_collect"] = $time_specimen_collect;
        //print_r($obj_data);
        updateListDataObj("p_lab_order",$obj_data, $s_id);
    }



    $rtn["lab_order_status"] = $lab_order_status;
    $rtn["lab_order_id"] = $lab_order_id;
    $rtn["spc_collect_time"] = $time_specimen_collect;

}//collect_specimen
/*
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
    if($arr_data[0]["pending_amt"] == 0 && $arr_data[0]["lab_order_status"] == "A2" ){
      $lab_order_status = 'A3';
      $query = "SELECT b.queue as qid, rd.id as qrd, o.wait_lab_result
        FROM p_lab_order as o
        LEFT JOIN k_visit_data as b
          ON binary o.uid=b.uid
          AND binary o.collect_date=b.date_of_visit
          AND binary o.collect_time=b.time_of_visit
        LEFT JOIN k_queue_row_detail as rd on
        (rd.patient_uid = b.uid AND b.queue=rd.queue_row_detail)
        WHERE  o.uid=? AND o.collect_date=? AND o.collect_time=? ";

      $arr_param = array( $obj_data["uid"],  $obj_data["collect_date"],  $obj_data["collect_time"]);
      $arr_data = selectDataSql_withParam($query, $arr_param);

      // update Queue in Pribta to cashier

      if(isset($arr_data['wait_lab_result'])){
        if($arr_data['wait_lab_result']== '0'){ // not wait lab result

          $lab_order_status = 'B0';
          $query_q = "INSERT INTO k_queue_row_detail_history(id,from_qrd_id,id_room,time_record)
          VALUES('','".$arr_data['qrd']."','26',NOW())
          ";
          //echo "query1: $qrd / $query";
                $stmt = $mysqli->prepare($query_q);
                if($stmt->execute()){
                }
                else{
                $msg_error .= $stmt->error;
                }
                $stmt->close();
        }
      }



        unset($obj_data["barcode"]);
        unset($obj_data["specimen_status"]);

        $obj_data["lab_order_status"] = $lab_order_status;
        $rtn["lab_order_id"] = $lab_order_id;
        $obj_data["time_specimen_collect"] = $time_specimen_collect;
        //print_r($obj_data);
        updateListDataObj("p_lab_order",$obj_data, $s_id);
    }



    $rtn["lab_order_status"] = $lab_order_status;
    $rtn["lab_order_id"] = $lab_order_id;
    $rtn["spc_collect_time"] = $time_specimen_collect;

}//collect_specimen

*/







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
/*
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
