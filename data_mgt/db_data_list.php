<?

$flag_auth=1;

if(!isset($s_id)){
  $flag_auth == 0;
}


$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";
$tbl_name = "p_data_list";

if($flag_auth != 0){ // valid user session
  include_once("../in_auth_db.php");
  include_once("../function/in_fn_sql.php"); // sql update
  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_fn_link.php");

//echo "umode : $u_mode";
if($u_mode == "select_data_list"){ // select_data_list

      $group_main_id = isset($_POST["group_main_id"])?$_POST["group_main_id"]:"";
      $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
      $txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";
      $id_not_in = isset($_POST["id_not_in"])?$_POST["id_not_in"]:"";

      $query_add = "";
      if($group_main_id != ""){
        $query_add .= " AND gm.data_group_main_id = '$group_main_id' ";

        if($group_id != "")
        $query_add .= " AND g.data_group_id = '$group_id' ";
      }

      if($txt_search != ""){
        $query_add = " AND(
        d.data_name_en like '%$txt_search%' OR
        d.data_name_th like '%$txt_search%' OR
        d.data_id like '%$txt_search%' )";
      }

      if($id_not_in != ""){
        $query_add = " AND data_id NOT IN ($id_not_in) ";
      }


      $query = " SELECT
      gm.data_group_main_name_en as gm_name,
      gm.data_group_main_id as gm_id,
      g.data_group_id as g_id,
      g.data_group_name_en as g_name,
      d.data_id as d_id, d.data_type as d_type, d.data_category as d_cat,
      d.data_name_en as d_name_en,d.data_name_th as d_name_th
      FROM p_data_group_main as gm ,p_data_group as g ,p_data_list as d
      WHERE g.data_group_main_id=gm.data_group_main_id
      AND g.data_group_id=d.data_group_id
      $query_add
      ORDER BY d.data_name_en
      ";

      $arr_data_list = selectDataSql($query);
      $rtn['datalist'] = $arr_data_list;

}
else if($u_mode == "select_data_list_detail"){ // select_data_group_detail
    $id = isset($_POST["id"])?$_POST["id"]:"";
    //selectData($tbl_name, "*", $lst_where_data_item, $query_add, $order_by){

    $arr_where = array("data_id"=>"$id");
    $arr_data_list = selectData($tbl_name, "*", $arr_where, "", "data_name_en");
    $rtn['data_obj'] = $arr_data_list[0];

    $arr_data_list_item = array();
    if($arr_data_list[0]["data_type"] == "radio" ||
    $arr_data_list[0]["data_type"] == "dropdown" ){
      $arr_where = array("data_id"=>"$id");
      $arr_data_list_item = selectData("p_data_sub_list", "*", $arr_where, "", "data_seq");
    }
    $rtn['data_obj_itm'] = $arr_data_list_item;

}
  else if($u_mode == "update_data_list"){ // update_data_list
    $data_id = isset($_POST["data_id"])?$_POST["data_id"]:[];
    $data_type = isset($_POST["data_type"])?$_POST["data_type"]:[];
    $data_group_id = isset($_POST["data_group_id"])?$_POST["data_group_id"]:[];
    $data_obj = isset($_POST["data_obj"])?$_POST["data_obj"]:[];
    $data_obj_itm = isset($_POST["data_obj_itm"])?$_POST["data_obj_itm"]:[];
    if($flag_auth == 1){
      //print_r($data_obj);
  //  echo "count rec:".count($data_obj);

      if(count($data_obj) > 0){
        updateListDataObj($tbl_name, $data_obj, $s_id);
      }


      if($data_type == "radio" || $data_type == "dropdown" ){
        $arr_where = array("data_id"=>$data_id);

        deleteListDataObj("p_data_sub_list", $arr_where, $s_id);
        foreach($data_obj_itm as $itm) { // extract each item
          updateListDataObj("p_data_sub_list", $itm, $s_id);
        }
      }


    }
  }// update_data_group

  else if($u_mode == "delete_data_list"){ // delete_data_list
    $id = isset($_POST["id"])?$_POST["id"]:"";
    if($flag_auth == 1){
      $arr_where = array("data_id"=>"$id");
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
