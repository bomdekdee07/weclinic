<?

$flag_auth=1;

if(!isset($s_id)){
  $flag_auth == 0;
}


$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";
$tbl_name = "p_data_group_main";

if($flag_auth != 0){ // valid user session
//echo "enter02";
  include_once("../in_auth_db.php");
  include_once("../function/in_fn_sql.php"); // sql update
  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_fn_link.php");

//echo "umode : $u_mode";
 if($u_mode == "select_data_group_main_dropdown"){ // select_data_group_main_dropdown (for dropdown)
    //selectData($tbl_name, "*", $lst_where_data_item, $query_add, $order_by){
    $arr_where = array();
    $arr_data_list = selectData($tbl_name,
    "data_group_main_id as id, data_group_main_name_en as name ",
    $arr_where, "", "data_group_main_name_en");
    $rtn['datalist'] = $arr_data_list;

} // select_data_group_main
  else if($u_mode == "select_data_group_main"){ // select_data_group_main

      $txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";
      $query_add = "";
      if($txt_search != ""){
        $query_add = "
        data_group_main_name_en like '%$txt_search%' OR
        data_group_main_name_th like '%$txt_search%' OR
        data_group_main_id like '%$txt_search%' ";
      }

      $arr_where = array();
      $arr_data_list = selectData($tbl_name,
      "data_group_main_id as group_id,
      data_group_main_name_th as group_name_th, data_group_main_name_en as group_name_en,
      is_log, data_group_main_export_code as group_export",
      $arr_where, $query_add, "data_group_main_name_en");
      $rtn['datalist'] = $arr_data_list;


/*
      $arr_data_list = array();
      //echo "$stop_date,$start_date, $id/ query: $query";

      $query = "SELECT data_group_main_id as group_id, data_group_main_name as group_name,
      is_log, data_group_main_code as group_code, data_group_main_export_code as group_export
      FROM p_data_group_main
      $query_add
      ORDER BY data_group_main_id
      ";
//echo "query1: $uid, $collect_date, $collect_time / $query";

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
      */
}
else if($u_mode == "select_data_group_main_detail"){ // select_data_group_main_detail
    $id = isset($_POST["id"])?$_POST["id"]:"";
    //selectData($tbl_name, "*", $lst_where_data_item, $query_add, $order_by){
    $arr_where = array("data_group_main_id"=>"$id");
    $arr_data_list = selectData($tbl_name, "*", $arr_where, "", "data_group_main_name_en");
    $rtn['data_obj'] = $arr_data_list[0];
/*
    $arr_data_list = array();
    $query = "SELECT *
    FROM p_data_group_main WHERE data_group_main_id=?
    ";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s",$id);

    if($stmt->execute()){
      $result = $stmt->get_result();
      if($row = $result->fetch_assoc()) {
      }
    }
    else{
    $msg_error .= $stmt->error;
    }
    $stmt->close();
    $rtn['data_obj'] = $row;
    */
}
  else if($u_mode == "update_data_group_main"){ // update_data_group_main
    $data_obj = isset($_POST["data_obj"])?$_POST["data_obj"]:[];
    if($flag_auth == 1){
      updateListDataObj($tbl_name, $data_obj, $s_id);
    }
  }// update_data_group_main

  else if($u_mode == "delete_data_group_main"){ // update_data_group_main
    $id = isset($_POST["id"])?$_POST["id"]:"";
    if($flag_auth == 1){
      $arr_where = array("data_group_main_id"=>"$id");
      deleteListDataObj($tbl_name, $arr_where, $s_id);
    }

  }// update_data_group_main

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
