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
  if($u_mode == "add_lab_test"){ // add_lab_test
    $id = isset($_POST["id"])?$_POST["id"]:"";
    $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];
    $lst_data_list = isset($_POST["lst_data_list"])?$_POST["lst_data_list"]:[];
    $result_type = isset($_POST["result_type"])?$_POST["result_type"]:"";

    $tbl_name = "p_lab_test";
    $col_id = "lab_id2";
    $id_prefix = "L";

    $id_digit = 4;
    $substr_pos_begin = 1+strlen($id_prefix);
    $where_substr_pos_end = strlen($id_prefix);

    $col_insert = "";
    $col_value = "";

    $flag = 0;
    // extract each data to domain group
    foreach($lst_data as $item) {
       $col_insert .= $item['name'].",";
       $col_value  .= "'".$item['value']."',";
    }//foreach

    $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
    $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;

    if($col_value != ""){
    $query = "INSERT INTO $tbl_name
    ($col_id, $col_insert) ";

            $query.= " SELECT @keyid := CONCAT('".$id_prefix."',
            LPAD( (SUBSTRING(  IF(MAX($col_id) IS NULL,0,MAX($col_id))   ,
            $substr_pos_begin,$id_digit)*1)+1, '".$id_digit."','0') )";
            $query.= " ,$col_value ";
            $query.= " FROM $tbl_name WHERE SUBSTRING($col_id,1,$where_substr_pos_end) = '".$id_prefix."';";
          //echo "$query" ;

                $stmt = $mysqli->prepare($query);
                if($stmt->execute()){
                  $inQuery = "SELECT @keyid;";
                  $stmt = $mysqli->prepare($inQuery.";");
                  $stmt->bind_result($rtn_id);
                  if($stmt->execute()){ // get leave id
                    if($stmt->fetch()){
                        $rtn['id'] = $rtn_id;
                        $flag = 1;
                    }
                  }

                }
                else{
                  $msg_error .= $stmt->error;
                }
                $stmt->close();


           if($flag == 1){
            // $id = $rtn_id;

            if(isset($lst_data_list["update_normal_range"])){
                if(sizeof($lst_data_list["update_normal_range"]) > 0){
                  $arr_update_normal_range = $lst_data_list["update_normal_range"];
                  $tbl_name = "p_lab_test_result_hist";
                  $col_id = "lab_id";
                  foreach($arr_update_normal_range as $lst_data_update) { // extract each item
                    updateListData($tbl_name,$col_id, $id, $lst_data_update);
                  }
                }

            }

             if(isset($lst_data_list["update_lab_test_result"])){
               if(sizeof($lst_data_list["update_lab_test_result"]) > 0){
                 $arr_update_test_result = $lst_data_list["update_lab_test_result"];
                 if($result_type == "txt"){
                   $tbl_name = "p_lab_test_result_txt"; $col_id="lab_id";
                   foreach($arr_update_test_result as $lst_data_update) { // extract each item
                     updateListData($tbl_name,$col_id, $id, $lst_data_update);
                   }
                 }
               }//>0

             }


            //cost / sale
            if(isset($lst_data_list["update_lab_cost"])){
              if(sizeof($lst_data_list["update_lab_cost"]) > 0){
                $arr_update_cost = $lst_data_list["update_lab_cost"];
                $tbl_name = "p_lab_test_sale_cost"; $col_id="lab_id";
                foreach($arr_update_cost as $lst_data_update) { // extract each item
                  updateListData($tbl_name,$col_id, $id, $lst_data_update);
                }
              }//>0
            }

            if(isset($lst_data_list["update_lab_sale"])){
              if(sizeof($lst_data_list["update_lab_cost"]) > 0){
                $arr_update_sale = $lst_data_list["update_lab_sale"];
                $tbl_name = "p_lab_test_sale_price"; $col_id="lab_id";
                foreach($arr_update_sale as $lst_data_update) { // extract each item
                  updateListData($tbl_name,$col_id, $id, $lst_data_update);
                }
              }//>0

            }


           }// flag == 1

        }// if($col_value != "")
  }// add_lab_test
  else if($u_mode == "update_lab_test"){ // update_lab_test
    $id = isset($_POST["id"])?$_POST["id"]:"";
    $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];
    $lst_data_list = isset($_POST["lst_data_list"])?$_POST["lst_data_list"]:[];
    $result_type = isset($_POST["result_type"])?$_POST["result_type"]:"";

//print_r($lst_data);
//print_r($lst_data_list);

    $tbl_name = "p_lab_test";
    $col_id = "lab_id";
    updateListData($tbl_name,$col_id, $id, $lst_data);

    // delete list
    if(isset($lst_data_list["delete_list"])){
      $arr_delete = $lst_data_list["delete_list"];
    //  print_r($arr_delete);
  //deleteListData($tbl_name,$main_col_id,$main_id,  $delete_col_id, $delete_id){
        $arr_tbl = array();

        $arr_tbl["result_txt"] = array();
        $arr_tbl["result_txt"]["tbl_name"] = "p_lab_test_result_txt";
        $arr_tbl["result_txt"]["col_id"] = "lab_txt_id";

        $arr_tbl["sale_cost"] = array();
        $arr_tbl["sale_cost"]["tbl_name"] = "p_lab_test_sale_cost";
        $arr_tbl["sale_cost"]["col_id"] = "laboratory_id";

        $arr_tbl["sale_price"] = array();
        $arr_tbl["sale_price"]["tbl_name"] = "p_lab_test_sale_price";
        $arr_tbl["sale_price"]["col_id"] = "sale_opt_id";

        $tbl_name = ""; $main_col_id="lab_id";

        foreach($arr_delete as $itm_del) { // extract each item
          //$tbl_name .= $itm_del["tbl_name"];
          $tbl_name = $arr_tbl[$itm_del["tbl_name"]]["tbl_name"];
          $col_del_id = $arr_tbl[$itm_del["tbl_name"]]["col_id"];
          $del_id = $itm_del["id"];
          deleteListData($tbl_name,$main_col_id,$id, $col_del_id, $del_id);
        }

    }

    if(isset($lst_data_list["update_normal_range"])){

       $arr_update_normal_range = $lst_data_list["update_normal_range"];
       $tbl_name = "p_lab_test_result_hist";
       $col_id = "lab_id";
       foreach($arr_update_normal_range as $lst_data_update) { // extract each item
         updateListData($tbl_name,$col_id, $id, $lst_data_update);
       }
    }


    // test_result (txt)
    if(isset($lst_data_list["update_lab_test_result"])){
      $arr_update_test_result = $lst_data_list["update_lab_test_result"];
      if($result_type == "txt"){
        $tbl_name = "p_lab_test_result_txt"; $col_id="lab_id";
        foreach($arr_update_test_result as $lst_data_update) { // extract each item
          updateListData($tbl_name,$col_id, $id, $lst_data_update);
        }
      }
    }


            //cost / sale
            if(isset($lst_data_list["update_lab_cost"])){
              $arr_update_cost = $lst_data_list["update_lab_cost"];
              $tbl_name = "p_lab_test_sale_cost"; $col_id="lab_id";
              foreach($arr_update_cost as $lst_data_update) { // extract each item
                updateListData($tbl_name,$col_id, $id, $lst_data_update);
              }
            }

            if(isset($lst_data_list["update_lab_sale"])){
              $arr_update_sale = $lst_data_list["update_lab_sale"];
              $tbl_name = "p_lab_test_sale_price"; $col_id="lab_id";
              foreach($arr_update_sale as $lst_data_update) { // extract each item
                updateListData($tbl_name,$col_id, $id, $lst_data_update);
              }
            }




  }// update_lab_test



  else if($u_mode == "select_lab_test_list"){ // select_test_menu_list to use in dropdown
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"%";
    $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
    $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";

        $arr_data_list = array();
        $query = "SELECT t.lab_id as id ,t.lab_name as name, g.lab_group_name as g_name, t.lab_result_type as type
        FROM p_lab_test as t, p_lab_test_group as g
        WHERE t.lab_group_id=g.lab_group_id AND t.is_disable = 0 AND
        (t.lab_id like ? OR t.lab_name like ?) AND g.lab_group_id like ?
        order by t.lab_name, g.lab_group_name";

          //echo " $txt_search, $group_id/ $query";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("sss", $txt_search, $txt_search, $group_id);

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

  else if($u_mode == "select_lab_test_detail"){ // select_lab_test_detail

      $id = isset($_POST["id"])?$_POST["id"]:"";
      $result_type = isset($_POST["result_type"])?$_POST["result_type"]:"";
      $arr_data_list = array();
      $query = "SELECT t.*, g.lab_group_name
      FROM p_lab_test as t , p_lab_test_group as g WHERE t.lab_id=? AND t.lab_group_id=g.lab_group_id";

        //echo " $query";
          $stmt = $mysqli->prepare($query);
          $stmt->bind_param('s',$id);
          if($stmt->execute()){
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
              $arr_data_list = $row;
            }
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

        if($result_type == "txt"){
          $arr_data_result_txt = array();
          $query = "SELECT *
          FROM p_lab_test_result_txt
          WHERE lab_id = ? ORDER BY lab_txt_seq";
          //  echo "$id/ $query";
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('s',$id);
              if($stmt->execute()){
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                  $arr_data_result_txt[] = $row;
                }
            }
            else{
              $msg_error .= $stmt->error;
            }
            $stmt->close();

            $rtn['datalist_result_txt'] = $arr_data_result_txt;

        }

        $arr_data_normal_range = array();
        $query = "SELECT start_date, lab_std_male_txt, lab_std_female_txt
        FROM p_lab_test_result_hist
        WHERE lab_id = ?
        ORDER BY start_date DESC LIMIT 1";
          //echo "$id/ $query";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('s',$id);
            if($stmt->execute()){
              $result = $stmt->get_result();
              while($row = $result->fetch_assoc()) {
                $arr_data_normal_range[] = $row;
              }
          }
          else{
            $msg_error .= $stmt->error;
          }
          $stmt->close();

        $arr_data_cost = array();
        $query = "SELECT c.*, lb.laboratory_name
        FROM p_lab_test_sale_cost as c, p_lab_laboratory as lb
        WHERE c.laboratory_id=lb.laboratory_id AND c.lab_id = ?
        ORDER BY lb.laboratory_id";
          //echo "$id/ $query";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('s',$id);
            if($stmt->execute()){
              $result = $stmt->get_result();
              while($row = $result->fetch_assoc()) {
                $arr_data_cost[] = $row;
              }
          }
          else{
            $msg_error .= $stmt->error;
          }
          $stmt->close();

          $arr_data_sale = array();
          $query = "SELECT p.*, s.sale_opt_name
          FROM p_lab_test_sale_price as p, sale_option as s
          WHERE p.sale_opt_id=s.sale_opt_id AND p.lab_id = ?
          ORDER BY s.data_seq";
            //echo "$id/ $query";
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('s',$id);
              if($stmt->execute()){
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                  $arr_data_sale[] = $row;
                }
            }
            else{
              $msg_error .= $stmt->error;
            }
            $stmt->close();



        $rtn['data'] = $arr_data_list;

        $rtn['datalist_cost'] = $arr_data_cost;
        $rtn['datalist_sale'] = $arr_data_sale;
        $rtn['datalist_normal_range'] = $arr_data_normal_range;
}// select_lab_test_menu_list



  else if($u_mode == "update_normal_range"){ // update_normal_range

      $id = isset($_POST["id"])?$_POST["id"]:"";
      $start_date = isset($_POST["start_date"])?$_POST["start_date"]:"";
      $stop_date = isset($_POST["stop_date"])?$_POST["stop_date"]:"";

      $flag = 0;
      $query = "UPDATE p_lab_test_result_hist SET stop_date=?
      WHERE start_date=? AND lab_id = ? ";
//echo "$stop_date,$start_date, $id/ query: $query";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('sss',$stop_date,$start_date, $id );
      if($stmt->execute()){
        $flag = 1;
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

      if($flag==1){
        $new_start_date = strtotime("1 day", strtotime($stop_date));
        $new_start_date = date("Y-m-d", $new_start_date);
        $query = "INSERT INTO p_lab_test_result_hist (lab_id, start_date, stop_date)
        VALUES('$id', '$new_start_date', '2100-01-01') ";
  //echo "query: $query";
        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){

        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();
        $rtn["new_start_date"] = $new_start_date;
      }

}// update_normal_range

else if($u_mode == "select_normal_range_hist"){ // select_normal_range_hist
  $id = isset($_POST["id"])?$_POST["id"]:"";

      $arr_data_list = array();
      $query = "SELECT h.*
      FROM p_lab_test as t,  p_lab_test_result_hist as h
      WHERE t.lab_id=h.lab_id AND t.lab_id=?
      order by h.start_date desc";

        //echo " $txt_search, $group_id/ $query";
          $stmt = $mysqli->prepare($query);
          $stmt->bind_param("s", $id);

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

  else if($u_mode == "select_specimen"){ // select_specimen
      $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
      $txt_search_txt = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
      $query_search = "";
      if($txt_search != ""){
        $query_search .= " WHERE specimen_name like '$txt_search_txt' ";
      }

      $query = "SELECT speciment_id, specimen_name, specimen_unit
      FROM p_lab_specimen
      $query_search
             ";

    //    echo "$clinic_id / $query";
          $stmt = $mysqli->prepare($query);
          if($stmt->execute()){
            $stmt->bind_result($speciment_id, $specimen_name, $specimen_unit);

                 while ($stmt->fetch()) {
                   $arr_data = array();
                   $arr_data["id"]= $speciment_id;
                   $arr_data["name"]= $specimen_name;
                   $arr_data["unit"]= $specimen_unit;
                   $arr_data_list[]=$arr_data;
                 }// while
               }
               else{
                 $msg_error .= $stmt->error;
               }
               $stmt->close();
               $rtn['datalist'] = $arr_data_list;
}// select_specimen



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
