<?


include_once("../in_auth_db.php");

$flag_auth=1;
$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";


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
  include_once("../function/in_ts_log.php");

//echo "umode : $u_mode";

if($u_mode == "select_lab_process"){ // select lab process
    $laboratory_id = isset($_POST["laboratory_id"])?$_POST["laboratory_id"]:"";
    $lab_group_id = isset($_POST["lab_group_id"])?$_POST["lab_group_id"]:"";
    $lab_process_status = isset($_POST["lab_process_status"])?$_POST["lab_process_status"]:"";
    $txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";
    $txt_search_uid = isset($_POST["txt_search_uid"])?$_POST["txt_search_uid"]:"";

    $query_add = "";
    if($laboratory_id != "") $query_add .= " AND lp.laboratory_id = '$laboratory_id' ";
    if($lab_group_id != "") $query_add .= " AND lp.lab_group_id = '$lab_group_id' ";
    if($lab_process_status != "") $query_add .= " AND lp.lab_process_status = '$lab_process_status' ";

    if($txt_search != "") $query_add .= " AND lp.lab_serial_no like '%$txt_search%' ";
    if($txt_search_uid != "")
    $query_add .= " AND lp.lab_serial_no IN (
      select distinct lab_serial_no from p_lab_result
      where (uid like '%$txt_search_uid%' OR barcode like '%$txt_search_uid%' )

    )";

    $arr_data_list = array();
    //echo "$stop_date,$start_date, $id/ query: $query";
//    CONCAT(sp.specimen_name, ' ', osp.specimen_amt, ' ',sp.specimen_unit ) AS spc_info
//    CONCAT(l.laboratory_name, '/', g.lab_group_name) AS group_info,
    $query = "SELECT lp.lab_serial_no,
    lp.lab_process_status as status_id,s.name as status_name,

    l.laboratory_name as lbt_name, g.lab_group_name as test_menu,
    lp.time_start, lp.time_lab_confirm
    FROM p_lab_process as lp, p_lab_test_group as g,
    p_lab_laboratory as l, p_lab_status as s

    WHERE lp.lab_process_status = s.id $query_add
    AND lp.laboratory_id=l.laboratory_id AND lp.lab_group_id=g.lab_group_id
    ORDER BY lp.lab_serial_no DESC
    ";
//echo "query1: $laboratory_id, $lab_group_id / $query";
//error_log($query);
    $stmt = $mysqli->prepare($query);
//    $stmt->bind_param("ss",$laboratory_id, $lab_group_id);

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



}// select_lab_process

else if($u_mode == "select_new_lab_process"){ // select_new_lab_process (check available lab process to start)


    $arr_data_list = array();
/*
    $query = "SELECT distinct
    lbt.laboratory_id as l_id, g.lab_group_id as g_id ,
    lbt.laboratory_name as l_name, g.lab_group_name as g_name
    FROM p_lab_order_specimen as osp , p_lab_order_specimen_process as ospp ,
    p_lab_laboratory as lbt, p_lab_test_group as g
    WHERE osp.specimen_status = 'S1' AND osp.in_stock = 0
    AND osp.barcode=ospp.barcode
    AND ospp.laboratory_id=lbt.laboratory_id AND ospp.lab_group_id=g.lab_group_id
    AND ospp.lab_serial_no = ''
    AND osp.barcode NOT IN (

      select distinct osp2.barcode from
      p_lab_order_specimen_process as ospp2, p_lab_order_specimen as osp2
      where ospp2.barcode = ospp2.barcode and osp2.specimen_status='S1'
    )
    ORDER BY lbt.laboratory_id, g.lab_group_id
    ";
*/


        $query = "SELECT distinct
        lbt.laboratory_id as l_id, g.lab_group_id as g_id ,
        lbt.laboratory_name as l_name, g.lab_group_name as g_name
        FROM p_lab_order_specimen as osp , p_lab_order_specimen_process as ospp ,
        p_lab_laboratory as lbt, p_lab_test_group as g
        WHERE osp.specimen_status = 'S1' AND osp.in_stock = 0
        AND osp.barcode=ospp.barcode
        AND ospp.laboratory_id=lbt.laboratory_id AND ospp.lab_group_id=g.lab_group_id
        AND ospp.lab_serial_no = ''

        ORDER BY lbt.laboratory_id, g.lab_group_id
        ";

//echo "query1: $laboratory_id, $lab_group_id / $query";
//error_log($query);
    $stmt = $mysqli->prepare($query);
//    $stmt->bind_param("ss",$laboratory_id, $lab_group_id);

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

}// select_new_lab_process
else if($u_mode == "select_lab_process_specimen"){ // select_lab_process_specimen

    $lab_serial_no = isset($_POST["lab_serial_no"])?$_POST["lab_serial_no"]:"";

        $arr_data_obj = array();
        $query = "SELECT lp.lab_serial_no, lp.lab_process_note,
        lp.lab_process_status as status_id,s.name as status_name ,
        CONCAT(l.laboratory_name, '/', g.lab_group_name) AS group_info,
        l.laboratory_id, g.lab_group_id,
        lp.time_start, lp.time_lab_confirm
        FROM p_lab_process as lp, p_lab_test_group as g,
        p_lab_laboratory as l , p_lab_status as s
        WHERE lp.lab_process_status = s.id AND lp.lab_serial_no=?
        AND lp.laboratory_id=l.laboratory_id AND lp.lab_group_id=g.lab_group_id
        ";
    //echo "query1: $laboratory_id, $lab_group_id / $query";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s",$lab_serial_no);

        if($stmt->execute()){
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()) {
            $arr_data_obj[] = $row;
          }
        }
        else{
        $msg_error .= $stmt->error;
        }
        $stmt->close();


    $arr_data_list = array();
    $query = "SELECT osp.barcode, o.uid,
    sp.specimen_name,osp.specimen_amt,sp.specimen_unit

    FROM p_lab_order_specimen as osp, p_lab_order_specimen_process as ospp,
    p_lab_order as o,
    p_lab_specimen as sp
    WHERE ospp.lab_serial_no = ? AND osp.barcode=ospp.barcode
    AND osp.specimen_id=sp.specimen_id
    AND osp.uid=o.uid AND osp.collect_date=o.collect_date AND osp.collect_time=o.collect_time
    ORDER BY sp.specimen_name,  osp.specimen_amt
    ";
//echo "query1: $laboratory_id, $lab_group_id / $query";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s",$lab_serial_no);

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
    $rtn['data_lab_process'] = $arr_data_obj;
    $rtn['datalist_specimen'] = $arr_data_list;



}// select_new_lab_process
else if($u_mode == "select_lab_process_result"){ // select_lab_process_result (load lab test prepare to put the result)

    $lab_serial_no = isset($_POST["lab_serial_no"])?$_POST["lab_serial_no"]:"";
    $lab_status_id = "";
    $gender = "M";
        $arr_data_obj = array();
        $query = "SELECT lp.lab_serial_no, lp.lab_process_note,
        lp.lab_process_status as status_id,s.name as status_name ,
        CONCAT(l.laboratory_name, '/', g.lab_group_name) AS group_info,
        l.laboratory_id, g.lab_group_id,
        lp.time_start, lp.time_lab_confirm
        FROM p_lab_process as lp, p_lab_test_group as g,
        p_lab_laboratory as l , p_lab_status as s
        WHERE lp.lab_process_status = s.id AND lp.lab_serial_no=?
        AND lp.laboratory_id=l.laboratory_id AND lp.lab_group_id=g.lab_group_id
        ";
    //echo "query1: $laboratory_id, $lab_group_id / $query";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s",$lab_serial_no);

        if($stmt->execute()){
          $result = $stmt->get_result();
          if($row = $result->fetch_assoc()) {
            $arr_data_obj[] = $row;
          }
          $lab_status_id = $row["status_id"];
        }
        else{
        $msg_error .= $stmt->error;
        }
        $stmt->close();

    $query_add = "";
    if($gender == "M"){
      $query_add = "rh.lab_std_male_txt as lab_std_txt ,
      l.lab_result_min_male as min, lab_result_max_male as max
      ";
    }
    else if($gender == "F"){
      $query_add = "rh.lab_std_female_txt as lab_std_txt ,
      l.lab_result_min_female as min, lab_result_max_female as max
      ";
    }

    $arr_data_list = array();
    $txt_lab_result_txt = "";
    $query = "SELECT r.uid, r.collect_date, r.collect_time,
    sp.specimen_id, sp.specimen_name,
    l.lab_id2, l.lab_id, l.lab_name, l.lab_result_type, l.lab_unit,
    osp.barcode, r.lab_result, r.lab_result_report, r.lab_result_note,
    r.lab_result_status,
    $query_add

    FROM p_lab_order_specimen as osp, p_lab_order_specimen_process as ospp,
    p_lab_test as l, p_lab_result as r,
    p_lab_specimen as sp,
    p_lab_test_result_hist as rh

    WHERE ospp.lab_serial_no = ?
    AND osp.specimen_id=sp.specimen_id
    AND osp.barcode=ospp.barcode
    AND osp.uid=r.uid AND osp.collect_date=r.collect_date AND osp.collect_time=r.collect_time
    AND ospp.barcode=r.barcode AND ospp.lab_serial_no=r.lab_serial_no
    AND r.lab_id = l.lab_id
    AND r.lab_id = rh.lab_id AND rh.start_date <= now() AND rh.stop_date > now()

    ORDER BY osp.barcode, l.lab_id2
    ";
//echo "query1: $lab_serial_no / $query";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s",$lab_serial_no);

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


    $rtn['data_lab_process'] = $arr_data_obj;
    $rtn['datalist_result'] = $arr_data_list;
    $rtn['datalist_result_choice'] = $arr_obj;


}// load_lab_specimen_check
else if($u_mode == "load_lab_specimen_check"){ // load specimen checked to lab process
      $laboratory_id = isset($_POST["laboratory_id"])?$_POST["laboratory_id"]:"";
      $lab_group_id = isset($_POST["lab_group_id"])?$_POST["lab_group_id"]:"";

      $arr_data_list = array();
      //echo "$stop_date,$start_date, $id/ query: $query";
//      CONCAT(sp.specimen_name, ' ', osp.specimen_amt, ' ',sp.specimen_unit ) AS spc_info

      $query = "SELECT osp.barcode, o.uid,
      sp.specimen_name,osp.specimen_amt,sp.specimen_unit

      FROM p_lab_order_specimen as osp, p_lab_order_specimen_process as ospp,
      p_lab_order as o,
      p_lab_specimen as sp
      WHERE ospp.laboratory_id=? AND ospp.lab_group_id=?

      AND osp.barcode = ospp.barcode
      AND ospp.lab_serial_no = ''
      AND osp.specimen_status = 'S1' AND osp.in_stock=0 AND osp.specimen_id=sp.specimen_id
      AND osp.uid=o.uid AND osp.collect_date=o.collect_date AND osp.collect_time=o.collect_time
      AND o.lab_order_status = 'A3'

      ORDER BY sp.specimen_name,  osp.specimen_amt
      "; 


/*
      $query = "SELECT osp.barcode, o.uid,
      sp.specimen_name,osp.specimen_amt,sp.specimen_unit

      FROM p_lab_order_specimen as osp, p_lab_order_specimen_process as ospp,
      p_lab_order as o,
      p_lab_specimen as sp
      WHERE ospp.laboratory_id=? AND ospp.lab_group_id=?

      AND osp.barcode = ospp.barcode
      AND osp.specimen_status = 'S1' AND osp.in_stock=0 AND osp.specimen_id=sp.specimen_id
      AND osp.uid=o.uid AND osp.collect_date=o.collect_date AND osp.collect_time=o.collect_time
      AND o.lab_order_status = 'A3'
      AND osp.barcode NOT IN (

        select distinct osp2.barcode from
        p_lab_order_specimen_process as ospp2, p_lab_order_specimen as osp2
        where ospp2.barcode = ospp2.barcode and osp2.specimen_status='S1'
        and ospp2.laboratory_id=? AND ospp2.lab_group_id=?
      )
      ORDER BY sp.specimen_name,  osp.specimen_amt
      ";
*/


//echo "query1:  $query";

      $stmt = $mysqli->prepare($query);
  //    $stmt->bind_param("ssss",$laboratory_id, $lab_group_id, $laboratory_id, $lab_group_id);
      $stmt->bind_param("ss",$laboratory_id, $lab_group_id);

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



}// load_lab_specimen_check
else if($u_mode == "load_lab_specimen_check_from_stock"){ // load specimen checked from stock to lab process
      $barcode = isset($_POST["barcode"])?$_POST["barcode"]:"";

      $arr_data_list = array();
      //echo "$stop_date,$start_date, $id/ query: $query";
//      CONCAT(sp.specimen_name, ' ', osp.specimen_amt, ' ',sp.specimen_unit ) AS spc_info
      $query = "SELECT osp.barcode, osp.uid,
      sp.specimen_name,osp.specimen_amt,sp.specimen_unit

      FROM p_lab_order_specimen as osp,
      p_lab_specimen as sp
      WHERE osp.barcode=?
      AND osp.in_stock=1 AND osp.specimen_id=sp.specimen_id


      ";
//echo "query1: $laboratory_id, $lab_group_id / $query";

      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("s",$barcode);

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
}// load_lab_specimen_check_from_stock

else if($u_mode == "start_lab_process"){ // start lab process with selected specimen barcodes

    $lst_data_barcode = isset($_POST["lst_data_barcode"])?$_POST["lst_data_barcode"]:[];
    $laboratory_id = isset($_POST["laboratory_id"])?$_POST["laboratory_id"]:"";
    $lab_group_id = isset($_POST["lab_group_id"])?$_POST["lab_group_id"]:"";

    $lab_serial_no = "";
    $now = (new DateTime ())->format('Y-m-d H:i:s');
    //$now2 = (new DateTime())->format('d/m/Y H:i:s');

    $id_prefix = "IH".(new DateTime())->format('y'); // prefix & current year eg IH20

    $id_digit = 5; // 00001-99999
    $substr_pos_begin = 1+strlen($id_prefix);
    $where_substr_pos_end = strlen($id_prefix);

      $inQuery = "INSERT INTO p_lab_process (lab_serial_no, lab_group_id, laboratory_id, time_start, lab_process_status)
      SELECT @keyid := CONCAT('$id_prefix',  LPAD( (SUBSTRING(  IF(MAX(lab_serial_no) IS NULL,0,MAX(lab_serial_no)) ,$substr_pos_begin,$id_digit))+1, '$id_digit','0'))
       ,'$lab_group_id','$laboratory_id',now(),'P0'
        FROM p_lab_process WHERE SUBSTRING(lab_serial_no,1,$where_substr_pos_end) = '$id_prefix' ;
     ";

            $stmt = $mysqli->prepare($inQuery);

            if($stmt->execute()){
              $inQuery = "SELECT @keyid;";
              $stmt = $mysqli->prepare($inQuery);
              $stmt->bind_result($lab_serial_no);
              if($stmt->execute()){
                if($stmt->fetch()){

                }
              }
            }
            else{
              $msg_info .= $stmt->error;
            }
            $stmt->close();


    foreach($lst_data_barcode as $barcode) { // extract each item
      $lst_data_update = array();
      array_push($lst_data_update,array("name"=>"barcode", "value"=>$barcode));
      array_push($lst_data_update,array("name"=>"laboratory_id", "value"=>$laboratory_id));
      array_push($lst_data_update,array("name"=>"lab_group_id", "value"=>$lab_group_id));
      array_push($lst_data_update,array("name"=>"lab_serial_no", "value"=>$lab_serial_no));
      updateListDataAll("p_lab_order_specimen_process",$lst_data_update);

/*
      $lst_data_update2 = array();
      array_push($lst_data_update2,array("name"=>"specimen_status", "value"=>"S2"));
      updateObjData("p_lab_order_specimen","barcode", $barcode, $lst_data_update2);
      */
    }//foreach



        // update specimen to  S2 (specimen in lab process)
        $query = "UPDATE p_lab_order_specimen SET specimen_status = 'S2'
        WHERE specimen_status = 'S1' AND barcode NOT IN (
          select distinct barcode
          from p_lab_order_specimen_process
          where lab_serial_no = ''
        )
        ";

    //echo "query1: $laboratory_id, $lab_group_id / $query";
        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){
        }
        else{
        $msg_error .= $stmt->error;
        }
        $stmt->close();




    // create lab result record
    $arr_lab_result = array();
    $query = "SELECT osp.uid, osp.collect_date, osp.collect_time,
    ol.lab_id, osp.barcode
    FROM p_lab_order_specimen as osp, p_lab_order_specimen_process as ospp,
    p_lab_order_lab_test as ol, p_lab_specimen as sp

    WHERE ospp.lab_serial_no = '$lab_serial_no'
    AND osp.barcode=ospp.barcode
    AND osp.specimen_id=sp.specimen_id
    AND osp.uid=ol.uid AND osp.collect_date=ol.collect_date AND osp.collect_time=ol.collect_time
    AND ospp.laboratory_id=ol.laboratory_id AND ospp.lab_group_id=ol.lab_group_id
    ORDER BY osp.uid, osp.collect_date, osp.collect_time
    ";

//echo "query1: $laboratory_id, $lab_group_id / $query";

    $stmt = $mysqli->prepare($query);
  //  $stmt->bind_param("ss",$laboratory_id, $lab_group_id);

    if($stmt->execute()){
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()) {
        $arr_obj = array();
        foreach($row as $col_name => $val) {
          array_push($arr_obj,array("name"=>"$col_name", "value"=>$val));

        }
        array_push($arr_obj,array("name"=>"lab_serial_no", "value"=>$lab_serial_no));
        array_push($arr_obj,array("name"=>"lab_result_status", "value"=>"L0"));
        $arr_lab_result[] = $arr_obj;
      }
    }
    else{
    $msg_error .= $stmt->error;
    }
    $stmt->close();

    // add lab result  pending for put the value inside
    foreach($arr_lab_result as $lst_result_add) {
      updateListDataAll("p_lab_result",$lst_result_add);
    }

    $rtn['lab_serial_no'] = $lab_serial_no;
    $rtn['time_start'] = $now;

}// select_lab_test_order
else if($u_mode == "save_lab_result"){ // save_lab_result

    $lst_data_result = isset($_POST["lst_data_result"])?$_POST["lst_data_result"]:[];
    $lab_serial_no = isset($_POST["lab_serial_no"])?$_POST["lab_serial_no"]:"";

    foreach($lst_data_result as $lst_data_update) { // extract each item
      array_push($lst_data_update,array("name"=>"lab_serial_no", "value"=>$lab_serial_no));
      updateListDataAll("p_lab_result",$lst_data_update);
    }
}// save_lab_result
else if($u_mode == "confirm_lab_result"){ // chk and confirm lab result

    $lab_serial_no = isset($_POST["lab_serial_no"])?$_POST["lab_serial_no"]:"";
    $lst_data_update = array();

    $now = (new DateTime())->format('Y-m-d H:i:s');
    array_push($lst_data_update,array("name"=>"lab_serial_no", "value"=>$lab_serial_no));
    array_push($lst_data_update,array("name"=>"time_lab_check", "value"=>$now));
    array_push($lst_data_update,array("name"=>"time_lab_confirm", "value"=>$now));
    array_push($lst_data_update,array("name"=>"lab_process_status", "value"=>"P1"));
    array_push($lst_data_update,array("name"=>"staff_save", "value"=>$s_id));

    array_push($lst_data_update,array("name"=>"staff_confirm", "value"=>"P20046")); // นันทนา ตันติบูล
    //array_push($lst_data_update,array("name"=>"staff_confirm", "value"=>"P20047")); // รพี ไตรชวโรจน์

    updateListDataAll("p_lab_process",$lst_data_update);

    // update specimen = S3 (specimen lab completed / ready to keep in stock box)

/*
    $lst_data_update = array();
    array_push($lst_data_update,array("name"=>"specimen_status", "value"=>"S3"));
    updateObjData("p_lab_order_specimen","lab_serial_no", $lab_serial_no, $lst_data_update);
*/

          // update completed specimen collect
          $query = "UPDATE p_lab_order_specimen SET specimen_status='S3'
          WHERE specimen_status = 'S2' AND
          barcode IN(
            select ospp.barcode
            from p_lab_order_specimen_process as ospp, p_lab_process as lp
            where ospp.lab_serial_no = lp.lab_serial_no and lp.lab_process_status='P1'
            AND ospp.barcode not in (
              select ospp.barcode
              from p_lab_order_specimen_process as ospp, p_lab_process as lp
              where ospp.lab_serial_no = lp.lab_serial_no and lp.lab_process_status='P0'
            )
          )
          ";
        //echo "$lab_serial_no / $query";
            $stmt = $mysqli->prepare($query);
            if($stmt->execute()){
            }
            else{
              $msg_error .= $stmt->error;
            }
            $stmt->close();

            // update completed lab order
            $arr_update_queue = array();
            $str_lab_order_id = "";
            $query = "SELECT o.lab_order_id, o.uid, o.collect_date, o.collect_time, rd.id as qrd, pt.clinic_type
            from p_lab_order as o , patient_info as pt,
            k_visit_data as b
            left join k_queue_row_detail as rd on
            (rd.patient_uid = b.uid and b.queue=rd.queue_row_detail)

              WHERE binary o.uid=pt.uid AND o.lab_order_status = 'A3'
              AND o.uid = b.uid AND o.wait_lab_result = '1' AND
              CONCAT(o.uid, o.collect_date, o.collect_time) NOT IN(
              select distinct CONCAT(uid, collect_date, collect_time)
              from p_lab_order_specimen
              where specimen_status IN ('S1', 'S2')
              )

            ";
          //echo "$lab_serial_no / $query";
              $stmt = $mysqli->prepare($query);
              $stmt->store_result();
              $stmt->bind_result($lab_order_id, $uid, $collect_date, $collect_time, $qrd, $clinic_type );
              if($stmt->execute()){
                while($stmt->fetch()){
                //  echo "-- gender: $gender--";
                  if($clinic_type == 'P'){ //Pribta counter
                    $arr_update_queue[] = "INSERT INTO k_queue_row_detail_history(id,from_qrd_id,id_room,time_record)
                    VALUES('','$qrd','25',NOW())
                    ";
                  }
                  else if($clinic_type == 'T'){//Tangerine counter
                    $arr_update_queue[] = "INSERT INTO k_queue_row_detail_history(id,from_qrd_id,id_room,time_record)
                    VALUES('','$qrd','29',NOW())
                    ";
                  }

                  //  update wait lab status = 2
                  $str_lab_order_id .= "'$lab_order_id',";

                }//while
              }
              else{
              $msg_error .= $stmt->error;
              }
              $stmt->close();

      foreach($arr_update_queue as $query_q) { // extract each item
        // update room queue
        //  echo "-- qeury: $query_q";
          $stmt = $mysqli->prepare($query_q);
          if($stmt->execute()){
          }
          else{
            $msg_error .= $stmt->error;
          }
          $stmt->close();
      }//foreach

      if($str_lab_order_id != ""){
        $str_lab_order_id = substr($str_lab_order_id,0,strlen($str_lab_order_id)-1);

        // update wait lab status = 2
        $query = "UPDATE p_lab_order SET wait_lab_result='2'
        WHERE wait_lab_result = '1' AND
        lab_order_id IN ($str_lab_order_id)
        ";
      //echo "$str_lab_order_id / $query";
          $stmt = $mysqli->prepare($query);
          if($stmt->execute()){
          }
          else{
            $msg_error .= $stmt->error;
          }
          $stmt->close();
      }

      // update completed lab order
      $query = "UPDATE p_lab_order SET lab_order_status='A4',time_lab_report_confirm=NOW()
      WHERE lab_order_status = 'A3' AND
      CONCAT(uid, collect_date, collect_time) NOT IN(
        select distinct CONCAT(uid, collect_date, collect_time)
        from p_lab_order_specimen
        where specimen_status IN ('S0', 'S1', 'S2')
      )
      ";
    //echo "$lab_serial_no / $query";
        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();


        $rtn['time_lab_confirm'] = $now;

}// confirm_lab_result



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
