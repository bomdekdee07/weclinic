<?
//include_once("../in_auth_db.php");
if (session_status() == PHP_SESSION_NONE) session_start();




$flag_auth=1;
$res=0;

$s_id= getSS("s_id");
if($s_id == ""){
  $s_id = getQS("s_id");
  $s_id =($s_id == "")?getQS("sid"):"";
}

$s_room_no= getSS("room_no");
if($s_room_no == ""){
  $s_room_no = getQS("room_no");
  $s_room_no =($s_room_no == "")?getQS("staff_id_room"):"";
  $s_room_no =($s_room_no == "")?getQS("roomid"):"";
}



$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

$proj_id = "LAB";
//echo "umode: $u_mode";
//echo "enter01 $open_link";
if($flag_auth != 0){ // valid user session
//echo "enter02";

  include_once("../function/in_fn_sql_update.php"); // sql update
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_ts_log.php");

//echo "umode : $u_mode";
if($u_mode == "select_lab_order_list"){ // select_lab_order_list

    $txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";

    $date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:"";
    $date_end = isset($_POST["date_end"])?$_POST["date_end"]:"";
    $lab_status = isset($_POST["lab_status"])?$_POST["lab_status"]:"";

    $lst_data_param = array();

    $query_add = "";
    if($txt_search != ""){

      $query_add = " WHERE
      (o.lab_order_id like ? OR
      o.uid like ?) ";

      $lst_data_param[] = "%$txt_search%";
      $lst_data_param[] = "%$txt_search%";
    }


    if($date_beg !="" && $date_end != ""){
      $query_add .= ($query_add == "")?" WHERE ":" AND ";

      $query_add .= " (o.collect_date >=? AND o.collect_date <=? )";
      $lst_data_param[] = $date_beg;
      $lst_data_param[] = $date_end;
    }

    if($lab_status != ""){
      $query_add .= ($query_add == "")?" WHERE ":" AND ";

      $query_add .= "o.lab_order_status = ? ";
      $lst_data_param[] = $lab_status;
    }



    $arr_data_list = array();
    //echo "$stop_date,$start_date, $id/ query: $query";

    $query ="SELECT o.lab_order_id, o.uid, o.collect_date,o.collect_time, o.lab_order_status as status_id,  st.name as status_name, o.wait_lab_result, p.clinic_type , s.s_name as request_by, o.staff_order_room as room_no,
    time_confirm_order,time_specimen_collect,time_lab_order_pmt,time_lab_report_confirm,proj_id,proj_pid,proj_visit,timepoint_id, o.lab_order_note,
    QL.queue, QL.room_no
    FROM p_lab_status as st
    LEFT JOIN p_lab_order as o ON o.lab_order_status = st.id
    LEFT JOIN p_staff as s ON s.s_id = o.staff_order
    LEFT JOIN patient_info as p ON(o.uid=p.uid)
    LEFT JOIN i_queue_list as QL ON(QL.uid = o.uid AND QL.collect_date=o.collect_date AND QL.collect_time=o.collect_time)
    $query_add
    ORDER BY o.lab_order_id DESC ";
    /*
echo "query: $query";
print_r($lst_data_param);
*/
    $arr_data_list = selectDataSql_withParam($query, $lst_data_param);


    $rtn['datalist'] = $arr_data_list;
}// select_lab_order_list
else if($u_mode == "check_queue_exist"){ // check_queue_exist
          $uid = isset($_POST["uid"])?$_POST["uid"]:"";
          $collect_date = isset($_POST["coldate"])?$_POST["coldate"]:"";
          $collect_time = isset($_POST["coltime"])?$_POST["coltime"]:"";

          $queue = ""; $room_no="";
          $query = "SELECT queue, room_no
          FROM i_queue_list
          WHERE uid = ? AND collect_date = ? AND collect_time = ?
          ";

          $stmt = $mysqli->prepare($query);
          $stmt->bind_param("sss",$uid, $collect_date, $collect_time );

                if($stmt->execute()){
                  $result = $stmt->get_result();
                  if($row = $result->fetch_assoc()) {
                    $rtn['queue'] = $row['queue'];
                    $rtn['room_no'] = $row['room_no'];
                    $res = 1;
                  }
                }
                else{
                $msg_error .= $stmt->error;
                }
                $stmt->close();

    $rtn['res'] = $res;
  }// check_queue_exist
else if($u_mode == "select_lab_test_order"){ // select_lab_test_order

      $uid = isset($_POST["uid"])?$_POST["uid"]:"";
      $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
      $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";
      $sale_opt_id = isset($_POST["sale_opt_id"])?$_POST["sale_opt_id"]:"S01";

      $arr_data_list = array();


      $query = "SELECT o.lab_order_id, o.lab_order_status as status_id, st.name as status_name,
      o.ttl_cost, o.ttl_sale, o.lab_order_note, o.wait_lab_result, o.is_call
      FROM p_lab_order as o,
      p_lab_status as st
      WHERE o.uid=? AND o.collect_date=? AND o.collect_time=?
      AND o.lab_order_status = st.id
      ";

      // echo "$uid,$collect_date, $collect_time/ query: $query";
//error_log("query1: $uid, $collect_date, $collect_time / $query") ;

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


      if(count($arr_data_list) > 0){
        $arr_data_list = array();

$query = "SELECT t.lab_id2 as id, t.lab_name as name, l.lab_id as dataid,
        g.lab_group_id as g_id, g.lab_group_name as g_name,
        lbt.laboratory_id as lbt_id, lbt.laboratory_name as lbt_name,
        sa.sale_opt_id as sa_id, sa.sale_opt_name as sa_name,
        l.sale_cost as lab_cost, l.sale_price as lab_price, c.lab_turnaround_from as turnaround,
        r.barcode, r.lab_result_status as lab_status, l.is_paid

        FROM  p_lab_test as t,p_lab_test_group as g,
         p_lab_laboratory as lbt,
         p_lab_order_lab_test as l
         LEFT JOIN p_lab_test_sale_cost as c ON (c.lab_id=l.lab_id AND c.laboratory_id=l.laboratory_id)
         LEFT JOIN sale_option as sa ON (sa.sale_opt_id=l.sale_opt_id)
         LEFT JOIN p_lab_result as r
         ON (r.uid = l.uid and r.collect_date=l.collect_date AND  r.collect_time=l.collect_time AND r.lab_id=l.lab_id)

        WHERE l.uid=? AND l.collect_date=? AND l.collect_time=?
        AND l.lab_id = t.lab_id
        AND t.lab_group_id = g.lab_group_id
        AND l.laboratory_id = lbt.laboratory_id
        ORDER BY g.lab_group_id, lbt.laboratory_id, t.lab_id2
";


//echo "query: $uid, $collect_date, $collect_time / $query";
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
        $rtn['data_list_labtest'] = $arr_data_list;
      }


}// select_lab_test_order

else if($u_mode == "select_lab_test_order_edit"){ // select_lab_test_order_edit

      $uid = isset($_POST["uid"])?$_POST["uid"]:"";
      $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
      $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";

      $arr_data_list = array();


      $query = "SELECT o.lab_order_id, o.lab_order_status as status_id, st.name as status_name,
      o.ttl_cost, o.ttl_sale, o.lab_order_note
      FROM p_lab_order as o,
      p_lab_status as st
      WHERE o.uid=? AND o.collect_date=? AND o.collect_time=?
      AND o.lab_order_status = st.id
      ";

//echo "$uid,$collect_date, $collect_time/ query: $query";
//error_log("query1: $uid, $collect_date, $collect_time / $query") ;

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


      if(count($arr_data_list) > 0){
        $arr_data_list = array();
        $query = "SELECT t.lab_id2 as id, t.lab_name as name, l.lab_id as dataid,
        g.lab_group_id as g_id, g.lab_group_name as g_name,
        lbt.laboratory_id as lbt_id, lbt.laboratory_name as lbt_name,
        sa.sale_opt_id as sa_id, sa.sale_opt_name as sa_name,
        c.lab_cost, s.lab_price ,
        c.lab_turnaround_from as turnaround

        FROM p_lab_order_lab_test as l, p_lab_test as t,p_lab_test_group as g,
         p_lab_laboratory as lbt, sale_option as sa,
         p_lab_test_sale_cost as c, p_lab_test_sale_price as s
        WHERE l.uid=? AND l.collect_date=? AND l.collect_time=?
        AND l.lab_id = t.lab_id
        AND l.lab_group_id = g.lab_group_id
        AND l.laboratory_id = lbt.laboratory_id
        AND l.sale_opt_id = sa.sale_opt_id
        AND l.lab_id=c.lab_id AND l.laboratory_id = c.laboratory_id
        AND l.lab_id=s.lab_id AND l.sale_opt_id = s.sale_opt_id
        ORDER BY g.lab_group_id, lbt.laboratory_id, t.lab_id2
        ";
//echo "query: $uid, $collect_date, $collect_time / $query";
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
        $rtn['data_list_labtest'] = $arr_data_list;
      }


}// select_lab_test_order_edit





else if($u_mode == "select_proj_pid"){ // select_proj_pid
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $query = "SELECT pid
    FROM p_project_uid_list WHERE uid = ? AND proj_id=?";

//echo " query: $query";
    $pid = "";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss",$uid, $proj_id);
    if($stmt->execute()){
      $result = $stmt->get_result();
      if($row = $result->fetch_assoc()) {
        $pid = $row["pid"];
      }
    }
    else{
    $msg_error .= $stmt->error;
    }
    $stmt->close();

    $rtn['pid'] = $pid;
}// select_clinic_room_list

else if($u_mode == "select_lab_test_costsale"){ // sselect_lab_test_costsale
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"%";
    $sale_opt_id = isset($_POST["sale_opt_id"])?$_POST["sale_opt_id"]:"%";
    $is_outsource = isset($_POST["is_outsource"])?$_POST["is_outsource"]:"0";
    $not_lab_id = isset($_POST["not_lab_id"])?$_POST["not_lab_id"]:[];

    $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
    $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";

    $query_add = "";
    if($group_id != "%"){
      $query_add .= " AND g.lab_group_id='$group_id' ";
    }
    if($sale_opt_id != "%"){
      $query_add .= " AND s.sale_opt_id='$sale_opt_id' ";
    }


    if($is_outsource != "1"){
      $query_add .= " AND lt.laboratory_id='LBT001' "; // IHRI
    }

    if(count($not_lab_id) > 0){
      $lab_id_not_inc = "";
      foreach($not_lab_id as $itm){
        $lab_id_not_inc .= "'$itm',";
      }
      $lab_id_not_inc = substr($lab_id_not_inc,0,strlen($lab_id_not_inc)-1);
      $query_add .= " AND t.lab_id2 NOT IN ($lab_id_not_inc) ";
    }

        $arr_data_list = array();
        $query = "SELECT t.lab_id2 as id ,t.lab_name as name, t.lab_id as dataid,
        g.lab_group_id as g_id, g.lab_group_name as g_name,
        s.sale_opt_id as sa_id, s.sale_opt_name as sa_name,
        sc.lab_cost, sp.lab_price , sc.lab_turnaround_from as turnaround,
        lt.laboratory_id as lbt_id, lt.laboratory_name as lbt_name

        FROM p_lab_test as t, p_lab_test_group as g, sale_option as s,
        p_lab_test_sale_price as sp, p_lab_test_sale_cost as sc,
        p_lab_laboratory as lt
        WHERE t.lab_group_id=g.lab_group_id AND
        t.lab_id=sp.lab_id AND sp.sale_opt_id=s.sale_opt_id and
        t.lab_id = sc.lab_id AND sc.laboratory_id=lt.laboratory_id
         AND t.is_disable = 0 AND
        (t.lab_id like ? OR t.lab_name like ?) $query_add
        order by t.lab_name, g.lab_group_name, lt.laboratory_id";

        //  echo " $txt_search, $group_id, $is_outsource, $sale_opt_id/ $query";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ss", $txt_search, $txt_search);

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
  }// select_lab_test_menu_list

  else if($u_mode == "add_lab_order"){ // add_lab_order
    $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];

/*
    $s_id_update = isset($_POST["staff_id_update"])?$_POST["staff_id_update"]:"";
    $s_id = ($s_id_update == "")?$s_id:$s_id_update; // use pribta s_id or not
*/


//print_r($lst_data);
    $uid = $lst_data["uid"];
    $collect_date = $lst_data["collect_date"];
    $collect_time = $lst_data["collect_time"];
    $ttl_cost = $lst_data["ttl_cost"];
    $ttl_sale = $lst_data["ttl_sale"];
    $wait_lab_result = $lst_data["wait_lab_result"];
    $lab_order_status = 'A2';

    $lab_order_id = "";
    $is_call = "10"; // first call status (not notify)

    $lab_order_id = createLabOrderID($uid, $collect_date, $collect_time,
    $ttl_cost, $ttl_sale, $wait_lab_result, $lab_order_status, $s_id, $s_room_no,
    '','','','');

    if($lab_order_id != ""){
       addToLog("[Lab Order] create $lab_order_id [$uid|$collect_date|$collect_time] wait_lab_result:$wait_lab_result", $s_id);
       if(isset($lst_data["lst_order_lab_test"])){
         $arr_lab_id = array();
         $arr_update_lab_test = $lst_data["lst_order_lab_test"];
         foreach($arr_update_lab_test as $lst_data_update) { // extract each item
           $lst_data_update["uid"] = $lst_data["uid"];
           $lst_data_update["collect_date"] = $lst_data["collect_date"];
           $lst_data_update["collect_time"] = $lst_data["collect_time"];
           $arr_lab_id[] = $lst_data_update["lab_id"];
             updateListDataObj("p_lab_order_lab_test",$lst_data_update, $s_id);
         }//foreach

        // updateLabSalePriceCost
        $row_update = updateLabSalePriceCost($uid, $collect_date, $collect_time, $arr_lab_id);



       }
    }

    $rtn['lab_order_id'] = $lab_order_id;
  }// add_lab_order

  else if($u_mode == "add_blank_lab_order"){ // add_blank_lab_order : new case that doctor/counselor/lab staff can put order with out new register
    $data_obj = isset($_POST["data_obj"])?$_POST["data_obj"]:[];

    $uid = $data_obj["uid"];
    $collect_date = $data_obj["collect_date"];
    $collect_time = $data_obj["collect_time"];
    $proj_id = $data_obj["proj_id"];
    $proj_pid = $data_obj["proj_pid"];
    $proj_visit = $data_obj["proj_visit"];
    $order_note = $data_obj["order_note"];
    $staff_order = $data_obj["staff_order"];

    if($staff_order == ""){
      $staff_order = $s_id;
    }

    $lab_order_id = createLabOrderID($uid, $collect_date, $collect_time,
    '0', '0', '0', 'A2', $staff_order, $s_room_no,  $proj_id, $proj_pid, $proj_visit, $order_note);


    $rtn['lab_order_id'] = $lab_order_id;
  }// add_lab_order


  else if($u_mode == "add_package_labtest"){ // add_package_labtest
        $lab_group_id = isset($_POST["lab_group_id"])?$_POST["lab_group_id"]:"";
        $sale_opt_id = isset($_POST["sale_opt_id"])?$_POST["sale_opt_id"]:"";
        $laboratory_id = isset($_POST["laboratory_id"])?$_POST["laboratory_id"]:"";
        $not_lab_id = isset($_POST["not_lab_id"])?$_POST["not_lab_id"]:[];

        $query_add = "";
        if(count($not_lab_id) > 0){
          $lab_id_not_inc = "";
          foreach($not_lab_id as $itm){
            $lab_id_not_inc .= "'$itm',";
          }
          $lab_id_not_inc = substr($lab_id_not_inc,0,strlen($lab_id_not_inc)-1);
          $query_add .= " AND t.lab_id2 NOT IN ($lab_id_not_inc) ";
        }

            $arr_data_list = array();
            $query = "SELECT t.lab_id2 as id ,t.lab_name as name, t.lab_id as dataid,
            g.lab_group_id as g_id, g.lab_group_name as g_name,
            s.sale_opt_id as sa_id, s.sale_opt_name as sa_name,
            sc.lab_cost, sp.lab_price , sc.lab_turnaround_from as turnaround,
            lt.laboratory_id as lbt_id, lt.laboratory_name as lbt_name

            FROM p_lab_test as t, p_lab_test_group as g, sale_option as s,
            p_lab_test_sale_price as sp, p_lab_test_sale_cost as sc,
            p_lab_laboratory as lt
            WHERE t.lab_group_id=g.lab_group_id AND
            t.lab_id=sp.lab_id AND sp.sale_opt_id=s.sale_opt_id and
            t.lab_id = sc.lab_id AND sc.laboratory_id=lt.laboratory_id
            AND t.is_disable = 0
            AND g.lab_group_id=?  AND s.sale_opt_id=? AND lt.laboratory_id=?
             $query_add
            order by t.lab_name, g.lab_group_name, lt.laboratory_id";

            //  echo " $lab_group_id, $sale_opt_id/ $query";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("sss", $lab_group_id, $sale_opt_id, $laboratory_id);

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
      }// select_lab_test_menu_list
      else if($u_mode == "add_package_labtest2"){ // add_package_labtest2
          $package_lab = isset($_POST["package_lab"])?$_POST["package_lab"]:"";
          $sale_opt_id = isset($_POST["sale_opt_id"])?$_POST["sale_opt_id"]:"";
          $laboratory_id = isset($_POST["laboratory_id"])?$_POST["laboratory_id"]:"";
          $not_lab_id = isset($_POST["not_lab_id"])?$_POST["not_lab_id"]:[];

          $query_add = " AND t.lab_id IN ($package_lab) ";
          if(count($not_lab_id) > 0){
            $lab_id_not_inc = "";
            foreach($not_lab_id as $itm){
              $lab_id_not_inc .= "'$itm',";
            }
            $lab_id_not_inc = substr($lab_id_not_inc,0,strlen($lab_id_not_inc)-1);
            $query_add .= " AND t.lab_id2 NOT IN ($lab_id_not_inc) ";
          }

              $arr_data_list = array();
              $query = "SELECT t.lab_id2 as id ,t.lab_name as name, t.lab_id as dataid,
              g.lab_group_id as g_id, g.lab_group_name as g_name,
              s.sale_opt_id as sa_id, s.sale_opt_name as sa_name,
              sc.lab_cost, sp.lab_price , sc.lab_turnaround_from as turnaround,
              lt.laboratory_id as lbt_id, lt.laboratory_name as lbt_name

              FROM p_lab_test as t, p_lab_test_group as g, sale_option as s,
              p_lab_test_sale_price as sp, p_lab_test_sale_cost as sc,
              p_lab_laboratory as lt
              WHERE t.lab_group_id=g.lab_group_id AND
              t.lab_id=sp.lab_id AND sp.sale_opt_id=s.sale_opt_id and
              t.lab_id = sc.lab_id AND sc.laboratory_id=lt.laboratory_id
               AND t.is_disable = 0
               AND s.sale_opt_id=? AND lt.laboratory_id=?
               $query_add
              order by  lt.laboratory_id,g.lab_group_name,t.lab_seq ";

        //        echo " $laboratory_id, $sale_opt_id/ $query";
                  $stmt = $mysqli->prepare($query);
                  $stmt->bind_param("ss",  $sale_opt_id, $laboratory_id);

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
        }// select_lab_test_menu_list

        else if($u_mode == "add_package_item"){ // add_package_item
            $res=0;
            $sPackageid = isset($_POST["packageid"])?$_POST["packageid"]:"";
            $sProjid = isset($_POST["projid"])?$_POST["projid"]:"";
            $sVisitid = isset($_POST["visitid"])?$_POST["visitid"]:"";
            $sUid = isset($_POST["uid"])?$_POST["uid"]:"";
            $sColdate = isset($_POST["coldate"])?$_POST["coldate"]:"";
            $sColtime = isset($_POST["coltime"])?$_POST["coltime"]:"";

            $lab_order_id = ""; $lab_order_proj_visit = "";
            //check existing lab order
            $query ="SELECT lab_order_id, proj_visit
            FROM p_lab_order
            WHERE uid=? AND collect_date=? AND collect_time=? ";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("sss", $sUid, $sColdate, $sColtime);
            //echo "$sID, $sProjid / $query";
            if($stmt->execute()){
              $stmt->bind_result($lab_order_id, $lab_order_proj_visit);
              if($stmt->fetch()) {

              }
            }
            $stmt->close();
            if($lab_order_id == ""){ // not found lab order  / create it!!
              $lab_order_id = createLabOrderID($sUid, $sColdate, $sColtime,
              '0', '0', '', 'A2', $s_id, $s_room_no,
              $sProjid,'',$sVisitid,'');
              if($lab_order_id != ""){
                   addToLog("[Lab Order] create $lab_order_id [$sUid|$sColdate|$sColtime]", $s_id);
              }
            }

           if($lab_order_id != ""){

              $queryInsertLab = "";
              $query ="SELECT PKI.lab_id, PKI.laboratory_id, PKI.sale_opt_id, SP.lab_price, SC.lab_cost
              from p_package_item PKI
              LEFT JOIN p_lab_test_sale_price SP ON SP.lab_id=PKI.lab_id AND SP.sale_opt_id=PKI.sale_opt_id
              LEFT JOIN p_lab_test_sale_cost SC ON SC.lab_id=PKI.lab_id AND SC.laboratory_id=PKI.laboratory_id
              WHERE PKI.package_id=? ";

              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('s',$sPackageid);
              //echo "$sID, $sProjid / $query";
              if($stmt->execute()){
                $stmt->bind_result($lab_id, $laboratory_id, $sale_opt_id, $lab_price, $lab_cost);
                while($stmt->fetch()) {
                   $queryInsertLab .= "('$sUid','$sColdate','$sColtime' ,'$sProjid','$lab_id','$laboratory_id','$sale_opt_id', '$lab_price', '$lab_cost', '0'),";
                }
              }
              $stmt->close();


              if($queryInsertLab != ""){
                $queryInsertLab = substr($queryInsertLab,0,strlen($queryInsertLab)-1);
                $queryInsertLab = "INSERT INTO p_lab_order_lab_test
                (uid, collect_date, collect_time, proj_id, lab_id, laboratory_id, sale_opt_id, sale_price, sale_cost, is_paid)
                VALUES $queryInsertLab ON DUPLICATE KEY UPDATE
                laboratory_id=values(laboratory_id), sale_opt_id=values(sale_opt_id), sale_price=values(sale_price), sale_cost=values(sale_cost)
                ";

              //  error_log("query: $queryInsertLab");
                $stmt = $mysqli->prepare($queryInsertLab);
                if($stmt->execute()){
                  $affect_row = $stmt->affected_rows;
                  if($affect_row > 0){
                    $res=1;
                    addToLog("add proj p_lab_order_lab_test [$sUid|$sColdate|$sColtime]|amt:$affect_row", $s_id);
                  }else{
                    error_log("ERROR: add p_lab_order_lab_test [$sUid|$sColdate|$sColtime] ");
                    $msg_error = 'Fail to insert lab test in lab order.';
                  }
                }
                else{
                  error_log($stmt->error);
                  $msg_error .= " ".$stmt->error;
                }
                $stmt->close();

                $query = "UPDATE p_lab_order SET proj_visit=?
                WHERE uid=? AND collect_date=? AND collect_time=? AND proj_visit='' ";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("ssss",  $sVisitid, $sUid, $sColdate, $sColtime);
                if($stmt->execute()){
                  $affect_row = $stmt->affected_rows;
                  if($affect_row > 0){
                    $res=1;
                    addToLog("update p_lab_order [$sUid|$sColdate|$sColtime]|proj_visit:$sVisitid", $s_id);
                  }
                }
                else{
                  error_log($stmt->error);
                  $msg_error .= " ".$stmt->error;
                }
                $stmt->close();
              }//$queryInsertLab != ""
            }

            $rtn['laborderid'] = $lab_order_id;
            $rtn['res'] = $res;
       }// add_package_item


        else if($u_mode == "update_lab_order_dlg"){ // update_lab_order_dlg
          $uid = isset($_POST["uid"])?$_POST["uid"]:"";
          $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
          $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";
          $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];
          $sale_opt_id = isset($_POST["sale_opt_id"])?$_POST["sale_opt_id"]:"";
          $laboratory_id = isset($_POST["laboratory_id"])?$_POST["laboratory_id"]:"";

          $lab_order_id = isset($_POST["orderid"])?$_POST["orderid"]:"";

         if($lab_order_id == ""){
           $lab_order_status = 'A2';
           $wait_lab_status = '1'; //wait=yes


           $lab_order_id = createLabOrderID($uid,$collect_date,$collect_time,
           '0','0','1',$lab_order_status,$s_id,$s_room_no, '', '', '','');
            if($lab_order_id != ""){
              addToLog("[Lab Order] create $lab_order_id [$uid|$collect_date|$collect_time] Room:$sRoomid,wait_lab_result:$wait_lab_status", $s_id);
            }
         }// if sOrderid==''
         else{ // check lab order cancel
           $query = "SELECT lab_order_status
           FROM p_lab_order
           WHERE uid=? AND collect_date=? AND collect_time=?
           ";
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param('sss',$uid, $collect_date , $collect_time);
           //echo "query : $query";
           if($stmt->execute()){
             $stmt->bind_result($lab_order_status);
             if ($stmt->fetch()) {

             }// while
           }
           else{
           $msg_error .= $stmt->error;
           }
           $stmt->close();

           if($lab_order_status == 'C'){ // update lab status to A2 if it is cancelled.
             $sqlCmd = "UPDATE p_lab_order SET lab_order_status='A2'
             WHERE uid=? AND collect_date=? AND collect_time=?";
             $stmt = $mysqli->prepare($sqlCmd);
             $stmt->bind_param("sss", $uid,  $collect_date, $collect_time);
             if($stmt->execute()){
               $affect_row = $stmt->affected_rows;
               if($affect_row > 0) $msg_info = "Lab order is activate.";
             }
             else{
             $msg_error .= $stmt->error;
             }
             $stmt->close();
           }
         }


          $query = "INSERT INTO p_lab_order_lab_test (uid,collect_date,collect_time,lab_id, laboratory_id, sale_opt_id)
          VALUES(?,?,?,?,?,?)
          ON DUPLICATE KEY UPDATE laboratory_id=?, sale_opt_id=?;
          ";
          foreach($lst_data as $lab_id){
              $stmt = $mysqli->prepare($query);
          //    echo "$uid, $collect_date, $collect_time, $lab_id, $laboratory_id, $sale_opt_id, $laboratory_id, $sale_opt_id / $query";
              $stmt->bind_param("ssssssss", $uid, $collect_date, $collect_time, $lab_id, $laboratory_id, $sale_opt_id, $laboratory_id, $sale_opt_id);
              if($stmt->execute()){
                $affect_row = $stmt->affected_rows;
                if($affect_row > 0){
                  $res = 1;
                  addToLog("add lab order [$uid|$collect_date|$collect_time] $lab_id|$laboratory_id|$sale_opt_id", $s_id);
                }else{
                  $res = 0;
                  error_log("ERROR: add lab order [$uid|$collect_date|$collect_time] $lab_id|$laboratory_id|$sale_opt_id");
                }
              }
              else{
                error_log($stmt->error);
              }
            $stmt->close();
          }//foreach

          $row_update = updateLabSalePriceCost($uid, $collect_date, $collect_time, $lst_data);


     $rtn['res'] = $res;
     $rtn['oid'] = $lab_order_id;
     $rtn['pricecost_update'] = $row_update;
}// update_lab_order_dlg

else if($u_mode == "remove_lab_order_dlg"){ // remove_lab_order_dlg
          $uid = isset($_POST["uid"])?$_POST["uid"]:"";
          $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
          $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";
          $lab_id = isset($_POST["lab_id"])?$_POST["lab_id"]:"";

          $flag_delete = 1;
          $query = "SELECT OLT.is_paid, OLT.sale_price, LR.lab_result
          FROM p_lab_order_lab_test OLT
          LEFT JOIN p_lab_result LR ON LR.uid=OLT.uid AND LR.collect_date=OLT.collect_date
          AND LR.collect_time=OLT.collect_time AND LR.lab_id=OLT.lab_id
          WHERE OLT.uid=? AND OLT.collect_date=? AND OLT.collect_time=? AND OLT.lab_id=?
          ";

        //  error_log("$uid, $collect_date, $collect_time, $lab_id,$uid, $collect_date, $collect_time, $lab_id / $query");
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ssss", $uid, $collect_date, $collect_time, $lab_id);
                if($stmt->execute()){
                  $result = $stmt->get_result();
                  if($row = $result->fetch_assoc()) {

                    if($row['is_paid'] == 1 && ($row['sale_price'] > 0)){
                      error_log("paid already");
                      $flag_delete = 0;
                      $msg_error = 'Can not delete: This lab test is paid already. Please contact cashier.';
                    }

                    if(is_null($row['lab_result'])){
                    }
                    else{ // lab result is exist
                      $flag_delete = 0;
                      $msg_error = 'Can not delete: This lab test is paid already. Please remove this lab test in lab result.';
                    }

                  }//if

                }
                else{
                  error_log($stmt->error);
                }
              $stmt->close();

          if($flag_delete == 1){
            $query = "DELETE FROM p_lab_order_lab_test
            WHERE uid=? AND collect_date=? AND collect_time=?
              AND lab_id=? AND lab_id NOT IN(
              select lab_id from p_lab_result
              where uid=? AND collect_date=? AND collect_time=?
              and lab_id=?
            )
            ";

            //error_log("$uid, $collect_date, $collect_time, $lab_id,$uid, $collect_date, $collect_time, $lab_id / $query");
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param("ssssssss", $uid, $collect_date, $collect_time, $lab_id,$uid, $collect_date, $collect_time, $lab_id);
                  if($stmt->execute()){
                    $affect_row = $stmt->affected_rows;
                    if($affect_row > 0){
                      $res = 1;
                      addToLog("remove lab order [$uid|$collect_date|$collect_time] $lab_id", $s_id);
                    }else{
                      $res = 0;
                      //error_log("ERR: remove lab order [$uid|$collect_date|$collect_time] $lab_id");
                    }
                  }
                  else{
                    error_log($stmt->error);
                  }
                $stmt->close();
          }
    $rtn['res'] = $res;
  }// remove_lab_order_dlg

  else if($u_mode == "remove_lab_order_dlg_group"){ // remove_lab_order_dlg_group
            $uid = isset($_POST["uid"])?$_POST["uid"]:"";
            $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
            $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";
            $lab_id = isset($_POST["lab_id"])?$_POST["lab_id"]:"";
            $arr_lab_id = explode(":",$lab_id); $query_add = "";
            foreach($arr_lab_id as $itm_lab_id){
              $query_add .= "'$itm_lab_id',";
            }
            if($query_add != ""){
              $query_add = substr($query_add,0, strlen($query_add)-1);
              $flag_delete = 1;
              $query = "SELECT OLT.is_paid, OLT.sale_price, LR.lab_result
              FROM p_lab_order_lab_test OLT
              LEFT JOIN p_lab_result LR ON LR.uid=OLT.uid AND LR.collect_date=OLT.collect_date
              AND LR.collect_time=OLT.collect_time AND LR.lab_id=OLT.lab_id
              WHERE OLT.uid=? AND OLT.collect_date=? AND OLT.collect_time=? AND OLT.lab_id IN ($query_add)
              ";

              //error_log("$uid, $collect_date, $collect_time, $query_add / $query");
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("sss", $uid, $collect_date, $collect_time);
                    if($stmt->execute()){
                      $result = $stmt->get_result();
                      while($row = $result->fetch_assoc()) {

                        if($row['is_paid'] == 1 && ($row['sale_price'] > 0)){
                          error_log("paid already");
                          $flag_delete = 0;
                          $msg_error = "Can not delete $lab_id: This lab test is paid already. Please contact cashier.";
                        }

                        if(is_null($row['lab_result'])){
                        }
                        else{ // lab result is exist
                          $flag_delete = 0;
                          $msg_error .= "Can not delete $lab_id : This lab test is paid already. Please remove this lab test in lab result.";
                        }

                      }//while

                    }
                    else{
                      error_log($stmt->error);
                    }
                  $stmt->close();

              if($flag_delete == 1){
                $query = "DELETE FROM p_lab_order_lab_test
                WHERE uid=? AND collect_date=? AND collect_time=?
                  AND lab_id IN ($query_add) AND lab_id NOT IN(
                  select lab_id from p_lab_result
                  where uid=? AND collect_date=? AND collect_time=?
                  and lab_id IN ($query_add)
                )
                ";

                //error_log("$uid, $collect_date, $collect_time / $query");
                  $stmt = $mysqli->prepare($query);
                  $stmt->bind_param("ssssss", $uid, $collect_date, $collect_time, $uid, $collect_date, $collect_time);
                      if($stmt->execute()){
                        $affect_row = $stmt->affected_rows;
                        if($affect_row > 0){
                          $res = 1;
                          addToLog("remove lab order group [$uid|$collect_date|$collect_time] $query_add", $s_id);
                        }else{
                          $res = 0;
                          //error_log("ERR: remove lab order [$uid|$collect_date|$collect_time] $lab_id");
                        }
                      }
                      else{
                        error_log($stmt->error);
                      }
                    $stmt->close();
              }
            }
      $rtn['res'] = $res;
    }// remove_lab_order_dlg_group
  else if($u_mode == "update_lab_order_note_dlg"){ // update_lab_order_note_dlg
            $uid = isset($_POST["uid"])?$_POST["uid"]:"";
            $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
            $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";
            $txt_note = isset($_POST["txt_note"])?$_POST["txt_note"]:"";

            $query = "UPDATE p_lab_order SET lab_order_note=?
            WHERE uid=? AND collect_date=? AND collect_time=?";

            $stmt = $mysqli->prepare($query);
              //   echo "$uid, $collect_date, $collect_time, $lab_id / $query";
            $stmt->bind_param("ssss",$txt_note, $uid, $collect_date, $collect_time );
                  if($stmt->execute()){
                    $affect_row = $stmt->affected_rows;
                    if($affect_row > 0){
                      $res = 1;
                    }else{
                      $res = 0;
                    }
                  }
                  else{
                    error_log($stmt->error);
                  }
            $stmt->close();

      $rtn['res'] = $res;
    }// update_lab_order_note_dlg


  else if($u_mode == "update_lab_order"){ // update_lab_order
    $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];

/*
    $s_id_update = isset($_POST["staff_id_update"])?$_POST["staff_id_update"]:"";
    $s_id = ($s_id_update == "")?$s_id:$s_id_update; // use pribta s_id or not
*/
//print_r($lst_data);
    $uid = $lst_data["uid"];
    $collect_date = $lst_data["collect_date"];
    $collect_time = $lst_data["collect_time"];
error_log("updateLabOrder $uid");

    $arr_lab_order = array();
    $arr_lab_order["uid"] = $lst_data["uid"];
    $arr_lab_order["collect_date"] = $lst_data["collect_date"];
    $arr_lab_order["collect_time"] = $lst_data["collect_time"];
    $arr_lab_order["ttl_cost"] = $lst_data["ttl_cost"];
    $arr_lab_order["ttl_sale"] = $lst_data["ttl_sale"];
    $arr_lab_order["wait_lab_result"] = $lst_data["wait_lab_result"];
    updateListDataObj("p_lab_order",$arr_lab_order, $s_id);


    if(isset($lst_data["lst_order_lab_test_delete"])){
      $arr_delete_lab_test = $lst_data["lst_order_lab_test_delete"];
      //print_r($arr_delete_lab_test);
      foreach($arr_delete_lab_test as $lst_data_delete) { // extract each item
        $lst_data_delete["uid"] = $lst_data["uid"];
        $lst_data_delete["collect_date"] = $lst_data["collect_date"];
        $lst_data_delete["collect_time"] = $lst_data["collect_time"];
        deleteListDataObj("p_lab_order_lab_test",$lst_data_delete, $s_id);
      }
    }

    if(isset($lst_data["lst_order_lab_test"])){

      $arr_update_lab_test = $lst_data["lst_order_lab_test"];
      $arr_lab_id = array();
      foreach($arr_update_lab_test as $lst_data_update) { // extract each item
        $arr_lab_result = array();
    //    unset($lst_data_update["barcode"]);
        $lst_data_update["uid"] = $lst_data["uid"];
        $lst_data_update["collect_date"] = $lst_data["collect_date"];
        $lst_data_update["collect_time"] = $lst_data["collect_time"];
        $arr_lab_id[] = $lst_data_update["lab_id"];
        updateListDataObj("p_lab_order_lab_test",$lst_data_update, $s_id);
      }//foreach

      $row_update = updateLabSalePriceCost($uid, $collect_date, $collect_time, $arr_lab_id);
    }

    // update is_paid=1 if lab price = 0
    $sqlCmd = "UPDATE p_lab_order_lab_test SET is_paid=1
    WHERE uid=? AND collect_date=? AND collect_time=?
    AND CONCAT(lab_id, sale_opt_id) IN (
    SELECT CONCAT(lab_id, sale_opt_id)
    FROM p_lab_test_sale_price as p
    WHERE  p.lab_price=0)";
    //echo "$uid,  $collect_date, $collect_time, / query : $sqlCmd";
    $stmt = $mysqli->prepare($sqlCmd);

    $stmt->bind_param("sss", $uid,  $collect_date, $collect_time);

    if($stmt->execute()){
    }
    else{
    $msg_error .= $stmt->error;
    }
    $stmt->close();


    if($lst_data["lab_order_status"] == 'C'){ // update lab status to A2 if it is cancelled.
      $sqlCmd = "UPDATE p_lab_order SET lab_order_status='A2'
      WHERE uid=? AND collect_date=? AND collect_time=?";
      $stmt = $mysqli->prepare($sqlCmd);
      $stmt->bind_param("sss", $uid,  $collect_date, $collect_time);
      if($stmt->execute()){
        $affect_row = $stmt->affected_rows;
        if($affect_row > 0) $msg_info = "Lab order is activate.";

      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();
    }

}// update_lab_order


   else if($u_mode == "update_lab_order_notify"){ // update_lab_order_notify
     $oid = isset($_POST["oid"])?$_POST["oid"]:"";
     $uid = isset($_POST["uid"])?$_POST["uid"]:"";
     $coldate = isset($_POST["coldate"])?$_POST["coldate"]:"";
     $coltime = isset($_POST["coltime"])?$_POST["coltime"]:"";
     $is_call = isset($_POST["is_call"])?$_POST["is_call"]:"0";
     $res = 0;

     $query_add = ($is_call == '1')?", time_confirm_order=now() ":"";
     if($uid != ""){
        //error_log("$u_mode : uid blank");

        $query = "UPDATE p_lab_order SET is_call=? $query_add
             WHERE uid=? AND collect_date=? AND collect_time=?";
               $stmt = $mysqli->prepare($query);
               $stmt->bind_param("ssss", $is_call, $uid, $coldate, $coltime);

               if($stmt->execute()){
                 $affect_row = $stmt->affected_rows;
                 if($affect_row > 0){
                   $res = 1;
                 }
             }
             else{
               $msg_error .= $stmt->error;
               error_log($u_mode.": ".$stmt->error);
             }
             $stmt->close();
     }
     else if($oid != ''){
       $query = "UPDATE p_lab_order SET is_call=? $query_add
            WHERE lab_order_id=?";
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param("ss", $is_call, $oid);

              if($stmt->execute()){
                $affect_row = $stmt->affected_rows;
                if($affect_row > 0){
                  $res = 1;
                }
            }
            else{
              $msg_error .= $stmt->error;
              error_log($u_mode.": ".$stmt->error);
            }
            $stmt->close();
     }



     $rtn['res'] = $res;

    }// update_lab_order_notify

   else if($u_mode == "update_lab_order_confirm"){ // update_lab_order_confirm
     $uid = isset($_POST["uid"])?$_POST["uid"]:"";
     $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
     $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";
     $res = 0;
      $query = "UPDATE p_lab_order SET is_call=1, time_confirm_order = now()
      WHERE uid=? AND collect_date=? AND collect_time=? ";

    //  echo " $txt_search, $group_id, $is_outsource, $sale_opt_id/ $query";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss", $uid ,$collect_date, $collect_time);

        if($stmt->execute()){
          $affect_row = $stmt->affected_rows;
          if($affect_row > 0){
            $res = 1;
          }
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();


     $rtn['res'] = $res;

    }// update_lab_order_confirm

    else if($u_mode == "select_sale_option_order"){ // select_sale_option_order
        $sale_opt_id = isset($_POST["sale_opt_id"])?$_POST["sale_opt_id"]:"%";


        $arr_data_list = array();
        $query = "SELECT s.sale_opt_id as id, s.sale_opt_name as name
        FROM sale_option as s
        order by s.data_seq ";

            //  echo " $txt_search, $group_id, $is_outsource, $sale_opt_id/ $query";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("s", $sale_opt_id);

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
      }// select_lab_test_menu_list

    else if($u_mode == "add_lab_note"){ // add_lab_note
      $lst_data_id = isset($_POST["lst_data_id"])?$_POST["lst_data_id"]:[];
      $lst_data_obj = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];

      $now = (new DateTime())->format('d/m/Y H:i:s');
      $txt_note = $lst_data_obj["user_name"]." [$now]:\n ".$lst_data_obj["txt_note"]." \n\n";

      $where_info = "";
      foreach($lst_data_id as $itm) { // extract each item
          $where_info .= $itm["name"]."='".$itm["value"]."' AND ";
      }//foreach
      $where_info = substr($where_info,0,strlen($where_info)-4);
      $tbl_name = $lst_data_obj["choice"];
      $col = $lst_data_obj["col"]."_note";

      $query = "UPDATE $tbl_name SET $col = CONCAT('$txt_note', $col)
      WHERE $where_info
      ";
    //  echo "query : $query";
      $stmt = $mysqli->prepare($query);
      if($stmt->execute()){
      }
      $stmt->close();
      $rtn["msg_note"] = $txt_note;
     }// add_lab_note
else if($u_mode == "cancel_lab_order"){ // cancel_lab_order
  $lst_data_obj = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];
  $cancel_note = isset($_POST["cancel_note"])?$_POST["cancel_note"]:"";
  $msg_cancel_error = "";
  $affect_row = 0;

  $query = "SELECT count(lab_id) as lab_result_amt
  FROM p_lab_result
  WHERE uid=? AND collect_date=? AND collect_time=?
  ";

//  print_r($lst_data_obj);
  $lst_data = array($lst_data_obj["uid"],$lst_data_obj["collect_date"],$lst_data_obj["collect_time"]);
  $arr_count = selectDataSql_withParam($query, $lst_data);
  //print_r($arr_count);
  if($arr_count[0]["lab_result_amt"] > 0){
    $msg_cancel_error = "Can not cancel order, ".$arr_count[0]["lab_result_amt"]." lab result already!";
  }
  else{
    $lst_data_obj["cancel_note"] = "CANCEL NOTE: ".(new DateTime())->format('Y-m-d H:i:s')." \n".$lst_data_obj["cancel_note"];

    $query = "UPDATE p_lab_order SET lab_order_note=CONCAT(lab_order_note, ?), lab_order_status='C'
    WHERE uid=? AND collect_date=? AND collect_time=?
    ";

  //  echo $lst_data_obj["cancel_note"].", ".$lst_data_obj["uid"].", ".$lst_data_obj["collect_date"].", ".$lst_data_obj["collect_time"];
  //  echo "/query: $query";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssss",
     $lst_data_obj["cancel_note"], $lst_data_obj["uid"], $lst_data_obj["collect_date"], $lst_data_obj["collect_time"]
    );

    if($stmt->execute()){
      $affect_row = $stmt->affected_rows;
      addToLog("Cancel Lab Order:uid=".$lst_data_obj["uid"].", collect_date=".$lst_data_obj["collect_date"].", collect_time=".$lst_data_obj["collect_time"], $s_id);
    }
    else{
      $msg_error .= $stmt->error;
    }
  }

  $rtn["affect_row"] = $affect_row;
  $rtn["msg_cancel_error"] = $msg_cancel_error;

}
else if($u_mode == "select_specimen_collect_detail"){ // select_specimen_collect_detail
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
    $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";

    $arr_data_list = array();

      $query = "SELECT o.lab_order_id, o.uid, o.collect_date, o.collect_time,
      o.lab_order_note, o.time_confirm_order as c_time
      FROM p_lab_order as o
      WHERE  o.uid=? AND o.collect_date=? AND o.collect_time=?
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



        $arr_data_list = array();

        $query = "SELECT osp.barcode,
        l.laboratory_name, g.lab_group_name, st.name as status,
        CONCAT(sp.specimen_name, ' ', osp.specimen_amt, ' ',sp.specimen_unit) AS spc_info,osp.specimen_id,osp.specimen_status
        FROM p_lab_order_specimen as osp, p_lab_order_specimen_process as ospp,
        p_lab_order as o,
        p_lab_specimen as sp, p_lab_laboratory as l , p_lab_test_group as g, p_lab_status as st
        WHERE o.uid=? AND o.collect_date=? AND o.collect_time=?
        AND osp.barcode=ospp.barcode
        AND osp.specimen_id=sp.specimen_id
        AND osp.specimen_status=st.id
        AND ospp.laboratory_id = l.laboratory_id AND ospp.lab_group_id=g.lab_group_id
        AND osp.uid=o.uid AND osp.collect_date=o.collect_date AND osp.collect_time=o.collect_time
        ORDER BY osp.barcode
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
        $rtn['data_specimen_list'] = $arr_data_list;




  }// select_specimen_collect_detail

  else if($u_mode == "select_sale_cost_labtest"){ // select_sale_cost_labtest
      $lab_id = isset($_POST["lab_id"])?$_POST["lab_id"]:"";
      $sale_opt_id = isset($_POST["sale_opt_id"])?$_POST["sale_opt_id"]:"";
      $laboratory_id = isset($_POST["laboratory_id"])?$_POST["laboratory_id"]:"";

      $arr_data_obj = array();
      $query = "SELECT c.lab_turnaround_from as turnaround, c.lab_cost as cost, p.lab_price as sale
      FROM p_lab_test as l
      LEFT JOIN p_lab_test_sale_cost  as c ON (l.lab_id=c.lab_id and c.laboratory_id=?)
      LEFT JOIN p_lab_test_sale_price  as p ON (l.lab_id=p.lab_id and p.sale_opt_id=?)
      WHERE l.lab_id = ?
      ";
//  echo "query1: $uid, $collect_date, $collect_time, $lab_id, $sale_opt_id, $laboratory_id / $query";

      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("sss",
      $laboratory_id, $sale_opt_id,  $lab_id);

      if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
          $arr_data_obj = $row;
        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();
      $rtn['data_obj'] = $arr_data_obj;

  }// select_sale_cost_labtest

  else if($u_mode == "send_queue_by_lab"){ // send_queue_to_nurse  manually send queue to related nurse counter
      $lab_order_id = isset($_POST["lab_order_id"])?$_POST["lab_order_id"]:"";
      $room_no = isset($_POST["room_no"])?$_POST["room_no"]:"1";
      $qrd= "";

      $query = "SELECT rd.id as qrd
      from p_lab_order as o,
      k_visit_data as b
      left join k_queue_row_detail as rd on
      (rd.patient_uid = b.uid and b.queue=rd.queue_row_detail)
      WHERE o.uid = b.uid AND o.lab_order_id=?
      ";
          //echo "query1: $lab_order_id  / $query";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("s",$lab_order_id);
      $arr_data_list[] = array();
      if($stmt->execute()){
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc()) {
          $qrd= $row['qrd'];
        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();

//echo "query: $query";

$query = "INSERT INTO k_queue_row_detail_history(id,from_qrd_id,id_room,time_record)
VALUES('','$qrd','$room_no',NOW())
";
              $stmt = $mysqli->prepare($query);
              if($stmt->execute()){
              }
              else{
                $msg_error .= $stmt->error;
              }
              $stmt->close();

              $lst_data_update = array();
              array_push($lst_data_update,array("name"=>"wait_lab_result", "value"=>"2"));
              updateObjData("p_lab_order","lab_order_id", $lab_order_id, $lst_data_update);

  }// send_queue_to_nurse


  else if($u_mode == "send_queue_to_nurse"){ // send_queue_to_nurse  manually send queue to related nurse counter
      $lab_order_id = isset($_POST["lab_order_id"])?$_POST["lab_order_id"]:"";

      $clinic_type= "";
      $qrd= "";

      $query = "SELECT rd.id as qrd, pt.clinic_type
      from p_lab_order as o , patient_info as pt,
      k_visit_data as b
      left join k_queue_row_detail as rd on
      (rd.patient_uid = b.uid and b.queue=rd.queue_row_detail)
      WHERE binary o.uid=pt.uid AND o.lab_order_status = 'A3'
      AND o.uid = b.uid AND o.wait_lab_result = '1' AND o.lab_order_id=?
      ";
          //echo "query1: $lab_order_id  / $query";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("s",$lab_order_id);
      $arr_data_list[] = array();
      if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
          $clinic_type = $row['clinic_type'];
          $qrd= $row['qrd'];
        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();

      $query = "";

      if($clinic_type== 'P'){
        $query = "INSERT INTO k_queue_row_detail_history(id,from_qrd_id,id_room,time_record)
        VALUES('','$qrd','25',NOW())
        ";
      }
      else if($clinic_type == 'T'){
        $query = "INSERT INTO k_queue_row_detail_history(id,from_qrd_id,id_room,time_record)
        VALUES('','$qrd','29',NOW())
        ";
      }

//echo "query: $query";

      if($query != ""){ // update wait lab status to sent queue

              $stmt = $mysqli->prepare($query);
              if($stmt->execute()){
              }
              else{
                $msg_error .= $stmt->error;
              }
              $stmt->close();

              $lst_data_update = array();
              array_push($lst_data_update,array("name"=>"wait_lab_result", "value"=>"2"));
              updateObjData("p_lab_order","lab_order_id", $lab_order_id, $lst_data_update);
      }
      else{ // no record found
         $msg_info = "No Queue to send";
      }



      $rtn['clinic_type'] = $clinic_type;
  }// send_queue_to_nurse

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



 function createLabOrderID($sUID, $sColdate, $sColtime,
 $sTotalCost, $sTotalSale, $sWaitresult, $orderstatus, $sID, $roomid,
 $sProjid,$sProjpid,$sProjvisitid,$sOrdernote){
 global $mysqli;

       $lab_order_id = "";
       $is_call = '10';
       $now = (new DateTime ())->format('Y-m-d H:i:s');

       $id_prefix = "L".(new DateTime())->format('y'); // prefix & current year eg IH20

       $id_digit = 5; // 00001-99999
       $substr_pos_begin = 1+strlen($id_prefix);
       $where_substr_pos_end = strlen($id_prefix);

         $inQuery = "INSERT INTO p_lab_order (lab_order_id,
         uid, collect_date, collect_time, is_call,  ttl_cost, ttl_sale, wait_lab_result, lab_order_status, staff_order, staff_order_room,
         proj_id, proj_pid, proj_visit, lab_order_note )
         SELECT @keyid := CONCAT('$id_prefix',  LPAD( (SUBSTRING(  IF(MAX(lab_order_id) IS NULL,0,MAX(lab_order_id)) ,$substr_pos_begin,$id_digit))+1, '$id_digit','0'))
          ,?,?,?,?,?,?,?,?,?,?,?,?,?,?
           FROM p_lab_order WHERE SUBSTRING(lab_order_id,1,$where_substr_pos_end) = '$id_prefix' ;
        ";
//error_log($inQuery);
               $stmt = $mysqli->prepare($inQuery);
               $stmt->bind_param('ssssssssssssss', $sUID,$sColdate,$sColtime, $is_call,
               $sTotalCost,$sTotalSale,$sWaitresult,$orderstatus,$sID,$roomid,
               $sProjid,$sProjpid,$sProjvisitid,$sOrdernote
               );

               if($stmt->execute()){
                 $inQuery = "SELECT @keyid;";
                 $stmt = $mysqli->prepare($inQuery);
                 $stmt->bind_result($lab_order_id);
                 if($stmt->execute()){
                   if($stmt->fetch()){

                   }
                 }
               }
               else{
                 error_log($stmt->error);
               }
               $stmt->close();
        return $lab_order_id;
 }

 function updateLabSalePriceCost($sUID, $sColdate, $sColtime, $arr_lab_id){
    global $mysqli;

    $row_update = 0;
    $txt_lab_id = "";
    foreach($arr_lab_id as $lab_id){
      $txt_lab_id .= "'$lab_id',";
    }//foreach
    $txt_lab_id = substr($txt_lab_id,0, strlen($txt_lab_id)-1);

    $query = "SELECT LT.lab_id,  LTSP.lab_price, LTSC.lab_cost
    FROM p_lab_order_lab_test LT
    JOIN p_lab_test_sale_price LTSP ON (LT.lab_id=LTSP.lab_id AND LTSP.sale_opt_id=LT.sale_opt_id)
    JOIN p_lab_test_sale_cost LTSC ON (LT.lab_id=LTSC.lab_id AND LTSC.laboratory_id=LT.laboratory_id)
    WHERE LT.uid=? AND LT.collect_date=? AND LT.collect_time=?
    AND LT.lab_id IN($txt_lab_id)
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sss', $sUID,$sColdate,$sColtime);

    if($stmt->execute()){
      $stmt->bind_result($lab_id,$lab_price,$lab_cost);
      if($stmt->execute()){
        $query_update_pricecost = "UPDATE p_lab_order_lab_test
        SET sale_price=?, sale_cost=?
        WHERE lab_id=? AND uid=? AND collect_date=? AND collect_time=?
        ";

        $stmt->store_result();
        while($stmt->fetch()){
          //error_log("$lab_price,$lab_cost, $lab_id, $sUID,$sColdate,$sColtime ");
          $stmt2 = $mysqli->prepare($query_update_pricecost);
          $stmt2->bind_param('ssssss', $lab_price,$lab_cost, $lab_id, $sUID,$sColdate,$sColtime);
          if($stmt2->execute()){
            $affect_row = $stmt2->affected_rows;
            $row_update += $affect_row;
          }
          else{
            error_log($stmt2->error);
          }
          $stmt2->close();

        }//while
      }
    }
    else{
      error_log($stmt->error);
    }
    $stmt->close();
    return $row_update;

 }//updateLabSalePriceCost

 function getQS($sName,$sDef=""){
 	$sResult = (isset($_GET[$sName])?urlencode($_GET[$sName]):"");
 	if($sResult=="") $sResult = (isset($_POST[$sName])?urlencode($_POST[$sName]):"");
 	if($sResult=="null" || $sResult=="") $sResult=$sDef;
 	return urldecode($sResult);

 }

 function getSS($sName){
 	$sResult = (isset($_SESSION[$sName])?urldecode($_SESSION[$sName]):"");
 	return $sResult;
 }
