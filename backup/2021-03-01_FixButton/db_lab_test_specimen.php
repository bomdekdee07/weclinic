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


$flag_auth=1;
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
      //echo "$stop_date,$start_date, $id/ query: $query";
/*
$query = "SELECT o.uid, o.lab_order_id as o_id, o.lab_order_status as s
FROM p_lab_order as o
WHERE o.lab_order_status IN('A2', 'A3')
ORDER BY o.lab_order_id
";
*/

$query = "SELECT o.uid, o.lab_order_id as o_id, o.lab_order_status as s
FROM p_lab_order as o
WHERE (o.lab_order_status = 'A2' OR
  CONCAT(o.uid,o.collect_date,o.collect_time) IN(
    select distinct concat(os.uid,os.collect_date,os.collect_time)
    from p_lab_order_specimen as os , p_lab_order as od
    where os.specimen_status = 'S0'
    and os.uid=od.uid
    and os.collect_date=od.collect_date
    and os.collect_time=od.collect_time and od.lab_order_status='A3'
  ) 
) ";
//$query.=" OR o.collect_date = CURDATE()";
$query.=" ORDER BY o.lab_order_id";
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

$query = "SELECT * FROM p_lab_order WHERE staff_order=?";

$query = "SELECT o.uid, o.collect_date as c_date, o.collect_time as c_time,
o.time_confirm_order as cf_time, o.wait_lab_result,
st.s_name as doctor_name , b.queue as qid, rd.id as qrd,o.staff_order_room,o.lab_order_status
FROM p_lab_order as o 
LEFT JOIN p_staff as st
ON st.s_id = o.staff_order

LEFT JOIN k_visit_data as b
ON binary o.uid=b.uid
AND binary o.collect_date=b.date_of_visit
AND binary o.collect_time=b.time_of_visit

LEFT JOIN k_queue_row_detail as rd on
(rd.patient_uid = b.uid AND b.queue=rd.queue_row_detail) 


";
$query .=" WHERE o.lab_order_status = 'A2'";
$query .=" ORDER BY o.time_confirm_order";

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
else if($u_mode == "select_specimen_check"){ // select_specimen_check pending
  // update specimen check
  $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];
//  print_r($lst_data);
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

 // load specimen pending check
    $arr_data_list = array();
    //echo "$stop_date,$start_date, $id/ query: $query";
/*
    $query = "SELECT osp.uid,osp.collect_date as c_date,osp.collect_time as c_time,
    osp.specimen_id,osp.lab_group_id, osp.laboratory_id, osp.barcode,
    CONCAT(sp.specimen_name, ' ', osp.specimen_amt, ' ',sp.specimen_unit ) AS spc_info,
    b.queue as qid
    FROM p_lab_order_specimen_collect as osp, p_lab_order as o,
    p_lab_specimen as sp, k_visit_data as b
    WHERE osp.specimen_status = 'S0' AND osp.specimen_id=sp.specimen_id
    AND binary osp.uid=b.uid AND binary osp.collect_date=b.date_of_visit AND binary osp.collect_time=b.time_of_visit
    AND osp.uid=o.uid AND osp.collect_date=o.collect_date AND osp.collect_time=o.collect_time
    AND o.lab_order_status = 'A3'
    ORDER BY b.queue
    ";
*/
$query = "SELECT osp.uid,osp.collect_date as c_date,osp.collect_time as c_time,
osp.specimen_id, osp.barcode,
CONCAT(sp.specimen_name, ' ', osp.specimen_amt, ' ',sp.specimen_unit ) AS spc_info,
b.queue as qid
FROM p_lab_order_specimen as osp, p_lab_order as o,
p_lab_specimen as sp, k_visit_data as b
WHERE osp.specimen_status = 'S0' AND osp.specimen_id=sp.specimen_id
AND binary osp.uid=b.uid AND binary osp.collect_date=b.date_of_visit AND binary osp.collect_time=b.time_of_visit
AND osp.uid=o.uid AND osp.collect_date=o.collect_date AND osp.collect_time=o.collect_time
AND o.lab_order_status = 'A3'
ORDER BY b.queue
";

//echo "query1: $query";

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


      $query = "SELECT o.lab_order_id, o.uid, o.collect_date, o.collect_time,
      o.lab_order_note, o.time_confirm_order as c_time,
      st.s_name as doctor_name , b.queue as qid
      FROM p_lab_order as o , p_staff as st,  k_visit_data as b
      WHERE  o.uid=? AND o.collect_date=? AND o.collect_time=?
      AND o.lab_order_status = 'A2'
      AND binary o.uid=b.uid AND binary o.collect_date=b.date_of_visit AND binary o.collect_time=b.time_of_visit
      AND st.s_id = o.staff_order
      ORDER BY o.time_confirm_order
      ";

//echo "query1: $uid, $collect_date, $collect_time / $query";

      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("sss",$uid, $collect_date, $collect_time);

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
      $rtn['data_lab_order'] = $arr_data_list;

      $arr_opertor = array();
      $arr_opertor["1"]="=";
      $arr_opertor["2"]=">=";
      $arr_opertor[""]="";

      $arr_specimen = array();
      $query = "SELECT distinct o.lab_group_id, sp.specimen_name, ls.specimen_unit,
      ls.specimen_amt, ls.operator
      FROM p_lab_order_lab_test as o ,
      p_lab_group_specimen ls, p_lab_specimen as sp
      WHERE  o.uid=? AND o.collect_date=? AND o.collect_time=?
      AND o.lab_group_id = ls.lab_group_id AND ls.specimen_id=sp.specimen_id
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
              $arr_obj["id"] = "$laboratory_id|$lab_group_id";
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



  }// select_specimen_collect_detail



    else if($u_mode == "update_lab_specimen_collect"){ // update_lab_specimen_collect
      $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];
//  print_r($lst_data);
$wait_lab_result = $lst_data["wait_lab_result"]; // 1:yes, 2:no
$qid = $lst_data["qid"];
$qrd = $lst_data["qrd"];

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

              // update lab order : time_specimen_collect
              $lst_data_lab_order = array();
              $lst_data_lab_order[] = array("name"=>"uid", "value"=>$lst_data["uid"]);
              $lst_data_lab_order[] = array("name"=>"collect_date", "value"=>$lst_data["collect_date"]);
              $lst_data_lab_order[] = array("name"=>"collect_time", "value"=>$lst_data["collect_time"]);
              $now = (new DateTime())->format('Y-m-d H:i:s');
              $lst_data_lab_order[] = array("name"=>"time_specimen_collect", "value"=>"$now");

              $lab_order_status = ($wait_lab_result == '0')?"B0":"A3"; //B0 to cashier, A3 to specimen_check
              $lst_data_lab_order[] = array("name"=>"lab_order_status", "value"=>"$lab_order_status");
              //  $lst_data_lab_order[] = array("name"=>"lab_order_status", "value"=>"A3");

              updateListDataAll("p_lab_order", $lst_data_lab_order);


        // update Queue in Pribta to cashier
        if($wait_lab_result == '0'){ // not wait lab result
          $query_q = "INSERT INTO k_queue_row_detail_history(id,from_qrd_id,id_room,time_record)
          VALUES('','$qrd','26',NOW())
          ";
          //echo "query1: $qrd / $query";
                $stmt = $mysqli->prepare($query_q);
                if($stmt->execute()){
                }
                else{
                $msg_error .= $stmt->error;
                }
                $stmt->close();
                //*****
        }



      }

}// update_lab_specimen_collect

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
