<?

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
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_ts_log.php");

//echo "umode : $u_mode";
if($u_mode == "select_lab_order_list"){ // select_lab_order_list

    $txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";


    $query_add = "";
    if($txt_search != ""){
      $query_add = " AND
      (o.lab_order_id like '%$txt_search%' OR
      o.uid like '%$txt_search%') ";
    }

    $arr_data_list = array();
    //echo "$stop_date,$start_date, $id/ query: $query";
/*
    $query = "SELECT o.lab_order_id, o.uid,
    concat(o.collect_date,' ', o.collect_time ) as collect_date,
    o.lab_order_status as status_id, st.name as status_name
*/
    $query = "SELECT o.lab_order_id, o.uid,
    o.collect_date,o.collect_time,
    o.lab_order_status as status_id, st.name as status_name,
    o.wait_lab_result, p.clinic_type ,
    s.s_name as request_by, o.staff_order_room as room_no

    FROM p_lab_status as st, p_staff as s,
    p_lab_order as o LEFT JOIN patient_info as p ON(binary o.uid=p.uid)
    WHERE o.staff_order = s.s_id AND  o.lab_order_status = st.id $query_add
    ORDER BY o.lab_order_id DESC LIMIT 100
    ";
      //  echo " query: $query";
//error_log("query1: $uid, $collect_date, $collect_time / $query") ;

    $stmt = $mysqli->prepare($query);

    $today = (new DateTime())->format('Y-m-d');
    $str_update_wait_lab_order_id = ""; //txt lab order id update
    if($stmt->execute()){
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()) {

/*
        if($row["wait_lab_result"] == "1" && $row["wait_lab_result"] == $today){ //lab can send queue to other room

        }
        else{ // clear send queue
          $str_update_wait_lab_order_id .= "'".$row["lab_order_id"]."',";
          $row["wait_lab_result"] = "2";
        }
*/
        $arr_data_list[] = $row;
      }
    }
    else{
    $msg_error .= $stmt->error;
    }
    $stmt->close();

    if($str_update_wait_lab_order_id != ""){

      $str_update_wait_lab_order_id = substr($str_update_wait_lab_order_id,0,strlen($str_update_wait_lab_order_id)-1);
      $query = "UPDATE p_lab_order SET wait_lab_result ='2'
      WHERE lab_order_id IN ($str_update_wait_lab_order_id)
      ";
    //  echo "query : $query";
      $stmt = $mysqli->prepare($query);
      if($stmt->execute()){
      }
      $stmt->close();
    }


    $rtn['datalist'] = $arr_data_list;
}// select_lab_order_list

else if($u_mode == "select_lab_test_order"){ // select_lab_test_order

      $uid = isset($_POST["uid"])?$_POST["uid"]:"";
      $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
      $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";
      $sale_opt_id = isset($_POST["sale_opt_id"])?$_POST["sale_opt_id"]:"S01";

      $arr_data_list = array();


      $query = "SELECT o.lab_order_id, o.lab_order_status as status_id, st.name as status_name,
      o.ttl_cost, o.ttl_sale, o.lab_order_note
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
        c.lab_cost, s.lab_price
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
        c.lab_cost, s.lab_price
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




else if($u_mode == "select_clinic_room_list"){ // select_clinic_room_list

    $arr_data_list = array();
    $query = "SELECT room_number as id, room_detail as name
    FROM k_room
    ORDER BY room_number
    ";

//echo " query: $query";
    $stmt = $mysqli->prepare($query);

    $today = (new DateTime())->format('Y-m-d');
    $str_update_wait_lab_order_id = ""; //txt lab order id update
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
        sc.lab_cost, sp.lab_price ,
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
//print_r($lst_data);
    $uid = $lst_data["uid"];
    $collect_date = $lst_data["collect_date"];
    $collect_time = $lst_data["collect_time"];
    $ttl_cost = $lst_data["ttl_cost"];
    $ttl_sale = $lst_data["ttl_sale"];
    $lab_order_status = $lst_data["lab_order_status"];



    $lab_order_id = "";
    $now = (new DateTime ())->format('Y-m-d H:i:s');

    $id_prefix = "L".(new DateTime())->format('y'); // prefix & current year eg IH20

    $id_digit = 5; // 00001-99999
    $substr_pos_begin = 1+strlen($id_prefix);
    $where_substr_pos_end = strlen($id_prefix);

      $inQuery = "INSERT INTO p_lab_order (lab_order_id,
      uid, collect_date, collect_time,  ttl_cost, ttl_sale, lab_order_status)
      SELECT @keyid := CONCAT('$id_prefix',  LPAD( (SUBSTRING(  IF(MAX(lab_order_id) IS NULL,0,MAX(lab_order_id)) ,$substr_pos_begin,$id_digit))+1, '$id_digit','0'))
       ,'$uid','$collect_date', '$collect_time', '$ttl_cost', '$ttl_sale', '$lab_order_status'
        FROM p_lab_order WHERE SUBSTRING(lab_order_id,1,$where_substr_pos_end) = '$id_prefix' ;
     ";

            $stmt = $mysqli->prepare($inQuery);

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
              $msg_info .= $stmt->error;
            }
            $stmt->close();



    $arr_update_lab_test = $lst_data["lst_order_lab_test"];
    foreach($arr_update_lab_test as $lst_data_update) { // extract each item
        array_push($lst_data_update,array("name"=>"uid", "value"=>$lst_data["uid"]));
        array_push($lst_data_update,array("name"=>"collect_date", "value"=>$lst_data["collect_date"]));
        array_push($lst_data_update,array("name"=>"collect_time", "value"=>$lst_data["collect_time"]));
        updateListDataAll("p_lab_order_lab_test",$lst_data_update);
    }//foreach


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
              order by t.lab_name, g.lab_group_name, lt.laboratory_id";

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


  else if($u_mode == "update_lab_order"){ // update_lab_order
    $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];
//print_r($lst_data);
    $uid = $lst_data["uid"];
    $collect_date = $lst_data["collect_date"];
    $collect_time = $lst_data["collect_time"];

    $lst_data_lab_order = array();
    $lst_data_lab_order[] = array("name"=>"uid", "value"=>$lst_data["uid"]);
    $lst_data_lab_order[] = array("name"=>"collect_date", "value"=>$lst_data["collect_date"]);
    $lst_data_lab_order[] = array("name"=>"collect_time", "value"=>$lst_data["collect_time"]);
    $lst_data_lab_order[] = array("name"=>"ttl_cost", "value"=>$lst_data["ttl_cost"]);
    $lst_data_lab_order[] = array("name"=>"ttl_sale", "value"=>$lst_data["ttl_sale"]);
    $lst_data_lab_order[] = array("name"=>"lab_order_status", "value"=>$lst_data["lab_order_status"]);
    updateListDataAll("p_lab_order", $lst_data_lab_order);

    if(isset($lst_data["lst_order_lab_test_delete"])){
      $arr_delete_lab_test = $lst_data["lst_order_lab_test_delete"];
      //print_r($arr_delete_lab_test);
      foreach($arr_delete_lab_test as $lst_data_delete) { // extract each item
        array_push($lst_data_delete,array("name"=>"uid", "value"=>$lst_data["uid"]));
        array_push($lst_data_delete,array("name"=>"collect_date", "value"=>$lst_data["collect_date"]));
        array_push($lst_data_delete,array("name"=>"collect_time", "value"=>$lst_data["collect_time"]));
        deleteListDataAll("p_lab_order_lab_test",$lst_data_delete);
      }
    }

    $arr_update_lab_test = $lst_data["lst_order_lab_test"];
    foreach($arr_update_lab_test as $lst_data_update) { // extract each item
        array_push($lst_data_update,array("name"=>"uid", "value"=>$lst_data["uid"]));
        array_push($lst_data_update,array("name"=>"collect_date", "value"=>$lst_data["collect_date"]));
        array_push($lst_data_update,array("name"=>"collect_time", "value"=>$lst_data["collect_time"]));
        updateListDataAll("p_lab_order_lab_test",$lst_data_update);
    }//foreach
   }// update_lab_order

   else if($u_mode == "update_lab_order_confirm"){ // update_lab_order_confirm
     $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];
   //print_r($lst_data);
     $lst_data_lab_order = array();
     $lst_data_lab_order[] = array("name"=>"uid", "value"=>$lst_data["uid"]);
     $lst_data_lab_order[] = array("name"=>"collect_date", "value"=>$lst_data["collect_date"]);
     $lst_data_lab_order[] = array("name"=>"collect_time", "value"=>$lst_data["collect_time"]);
     $lst_data_lab_order[] = array("name"=>"staff_order", "value"=>$lst_data["staff_order"]);
  //   $lst_data_lab_order[] = array("name"=>"lab_order_status", "value"=>"A1");
$lst_data_lab_order[] = array("name"=>"lab_order_status", "value"=>"A2");
     $now = (new DateTime())->format('Y-m-d H:i:s');
     $lst_data_lab_order[] = array("name"=>"time_confirm_order", "value"=>"$now");
     updateListDataAll("p_lab_order", $lst_data_lab_order);

     $rtn['status'] = array("id"=>"A1", "name"=>"Lab Order Confirmed");

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
        /*
        $query = "SELECT osp.barcode,
        l.laboratory_name, g.lab_group_name, st.name as status,
        CONCAT(sp.specimen_name, ' ', osp.specimen_amt, ' ',sp.specimen_unit) AS spc_info
        FROM p_lab_order_specimen_collect as osp, p_lab_order as o,
        p_lab_specimen as sp, p_lab_laboratory as l , p_lab_test_group as g, p_lab_status as st
        WHERE o.uid=? AND o.collect_date=? AND o.collect_time=?
        AND osp.specimen_id=sp.specimen_id AND osp.specimen_status=st.id
        AND osp.laboratory_id = l.laboratory_id AND osp.lab_group_id=g.lab_group_id
        AND osp.uid=o.uid AND osp.collect_date=o.collect_date AND osp.collect_time=o.collect_time
        ORDER BY osp.barcode
        ";
*/
        $query = "SELECT osp.barcode,
        l.laboratory_name, g.lab_group_name, st.name as status,
        CONCAT(sp.specimen_name, ' ', osp.specimen_amt, ' ',sp.specimen_unit) AS spc_info
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
  else if($u_mode == "select_lab_test_result"){ // select_lab_test_report

      $uid = isset($_POST["uid"])?$_POST["uid"]:"";
      $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
      $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";

      $arr_data_lab_order = array();
      $query = "SELECT o.lab_order_id, o.lab_report_note,
      s.id as status_id , s.name as status_name,
      pt.sex
      FROM p_lab_order as o, p_lab_status as s, patient_info as pt
      WHERE o.uid = ? AND o.collect_date = ? AND o.collect_time = ?
      AND binary pt.uid = o.uid
      AND o.lab_order_status = s.id
      ";
  //echo "query1: $uid, $collect_date, $collect_time / $query";

      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("sss",$uid, $collect_date, $collect_time );

      if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
          $arr_data_lab_order = $row;
        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();


      $arr_data_list = array();
      //echo "$stop_date,$start_date, $id/ query: $query";

      $query_add = "";
/*
      if(isset($arr_data_lab_order["sex"])){
        if($arr_data_lab_order["sex"] == "1"){ // M
          $query_add = "rh.lab_std_male_txt as lab_std_txt ,
          l.lab_result_min_male as min, lab_result_max_male as max
          ";
        }
        else if($arr_data_lab_order["sex"] == "2"){ // F
          $query_add = "rh.lab_std_female_txt as lab_std_txt ,
          l.lab_result_min_female as min, lab_result_max_female as max
          ";
        }
      }
      else{ // $arr_data_lab_order["gender"] is undefined
        $query_add = "rh.lab_std_male_txt as lab_std_txt ,
        l.lab_result_min_male as min, lab_result_max_male as max
        ";
      }
*/
      $query_add = " rh.lab_std_male_txt as m_lab_std_txt ,
            l.lab_result_min_male as m_min, lab_result_max_male as m_max,

            rh.lab_std_female_txt as f_lab_std_txt ,
            l.lab_result_min_female as f_min, lab_result_max_female as f_max
      ";


      $query = "SELECT sp.specimen_id, sp.specimen_name,
      l.lab_id2, l.lab_id, l.lab_name, r.lab_serial_no, r.barcode,
      r.lab_result_report, r.lab_result_note,
      r.lab_result_status,
      $query_add

      FROM p_lab_order_specimen as osp, p_lab_order_specimen_process as ospp,
      p_lab_test as l, p_lab_result as r, p_lab_process as lp,
      p_lab_specimen as sp,
      p_lab_test_result_hist as rh

      WHERE osp.uid = ? AND osp.collect_date = ? AND osp.collect_time = ?
      AND osp.specimen_id=sp.specimen_id
      AND osp.barcode=ospp.barcode
      AND osp.uid=r.uid AND osp.collect_date=r.collect_date AND osp.collect_time=r.collect_time
      AND osp.barcode=r.barcode AND ospp.lab_serial_no=r.lab_serial_no
      AND r.lab_id = l.lab_id
      AND ospp.lab_serial_no = lp.lab_serial_no AND lp.lab_process_status='P1'
      AND r.lab_id = rh.lab_id AND rh.start_date <= now() AND rh.stop_date > now()

      ORDER BY sp.specimen_name, l.lab_id2
      ";


  //echo "query1: $uid, $collect_date, $collect_time / $query";

      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("sss",$uid, $collect_date, $collect_time);
      $arr_obj = array();
      $str_specimen = "";
      if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
          if($str_specimen != $row["specimen_id"]){
            $arr_obj[$row["specimen_id"]] = $row["specimen_name"];
            $str_specimen = $row["specimen_id"];
          }
          $arr_data_list[] = $row;
        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();
      $rtn['data_lab_order'] = $arr_data_lab_order;
      $rtn['data_lab_result'] = $arr_data_list;
      $rtn['data_lab_specimen'] = $arr_obj;

  }// select_lab_test_report
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
