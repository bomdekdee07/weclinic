<?

$flag_auth=1;

if(!isset($s_id)){
  $flag_auth == 0;
}


$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";
$tbl_name = "p_data_group";

if($flag_auth != 0){ // valid user session
  include_once("../in_auth_db.php");
  include_once("../function/in_fn_sql.php"); // sql update
  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_fn_link.php");

//echo "umode : $u_mode";
if($u_mode == "select_data_group_dropdown"){ // select_data_group_dropdown (for dropdown)
      $group_main_id = isset($_POST["group_main_id"])?$_POST["group_main_id"]:"";

      $query = " SELECT
      g.data_group_id as id,
      g.data_group_name_en as name,
      g.data_group_main_id as main_id

      FROM p_data_group as g
      ORDER BY g.data_group_name_en
      ";

      $arr_data_list = selectDataSql($query);
      $rtn['datalist'] = $arr_data_list;


} // select_data_group_dropdown


else if($u_mode == "select_data_group_list"){ // select_data_group_list
      $group_main_id = isset($_POST["group_main_id"])?$_POST["group_main_id"]:"";
      $txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";
      $query_add = "";
      if($txt_search != ""){
        $query_add = " AND (
        g.data_group_name_en like '%$txt_search%' OR
        g.data_group_name_th like '%$txt_search%' OR
        g.data_group_id like '%$txt_search%' )";
      }
      if($group_main_id != ""){
        $query_add = " AND gm.data_group_main_id='$group_main_id' ";
      }


      $query = " SELECT
      gm.data_group_main_name_en as gm_name,
      gm.data_group_main_id as gm_id,
      g.data_group_id as group_id,
      g.data_group_name_en as group_name_en, g.data_group_name_th as group_name_th,
      g.is_log, g.data_group_export_code as group_export
      FROM p_data_group_main as gm ,p_data_group as g
      WHERE g.data_group_main_id=gm.data_group_main_id
       $query_add
      ORDER BY g.data_group_name_en
      ";
//error_log($query);
      $arr_data_list = selectDataSql($query);
      $rtn['datalist'] = $arr_data_list;

}//select_data_group
else if($u_mode == "select_data_group_detail"){ // select_data_group_detail
    $id = isset($_POST["id"])?$_POST["id"]:"";
    //selectData($tbl_name, "*", $lst_where_data_item, $query_add, $order_by){
    $arr_where = array("data_group_id"=>"$id");
    $arr_data_list = selectData($tbl_name, "*", $arr_where, "", "data_group_name_en");
    $rtn['data_obj'] = $arr_data_list[0];

}
else if($u_mode == "update_data_group"){ // update_data_group
    $data_obj = isset($_POST["data_obj"])?$_POST["data_obj"]:[];
    if($flag_auth == 1){
      updateListDataObj($tbl_name, $data_obj, $s_id);
    }
  }// update_data_group

  else if($u_mode == "delete_data_group"){ // update_data_group
    $id = isset($_POST["id"])?$_POST["id"]:"";
    if($flag_auth == 1){
      $arr_where = array("data_group_id"=>"$id");
      deleteListDataObj($tbl_name, $arr_where, $s_id);
    }

  }// update_data_group



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
