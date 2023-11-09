<?

$flag_auth=1;

if(!isset($s_id)){
  $flag_auth == 0;
}


$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";
$tbl_name = "p_form_list";

if($flag_auth != 0){ // valid user session
//echo "enter02";
  include_once("../in_auth_db.php");
  include_once("../function/in_fn_sql.php"); // sql update
  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_fn_link.php");

//echo "umode : $u_mode";
 if($u_mode == "select_form_dropdown"){ // select_form_dropdown (for dropdown)
    //selectData($tbl_name, "*", $lst_where_data_item, $query_add, $order_by){
    $arr_where = array();
    $arr_data_list = selectData($tbl_name,
    "form_id as id, form_name_en as name ",
    $arr_where, "", "form_name_en");
    $rtn['datalist'] = $arr_data_list;

} // select_form
  else if($u_mode == "select_form_list"){ // select_form

      $txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";

      $query_add = "";
      if($txt_search != ""){
        $query_add = "
        form_name_en like '%$txt_search%' OR
        form_name_th like '%$txt_search%' OR
        form_id like '%$txt_search%' ";
      }

      $arr_where = array();
      $arr_data_list = selectData($tbl_name,
      "form_id,is_log,
      form_name_th, form_name_en,
      form_version_id ",
      $arr_where, $query_add, "form_name_en");
      $rtn['datalist'] = $arr_data_list;

}
else if($u_mode == "select_form_detail"){ // select_form_detail
    $id = isset($_POST["id"])?$_POST["id"]:"";
    //selectData($tbl_name, "*", $lst_where_data_item, $query_add, $order_by){
    $arr_where = array("form_id"=>"$id");
    $arr_data_list = selectData($tbl_name, "*", $arr_where, "", "form_name_en");
    $rtn['data_obj'] = $arr_data_list[0];

}
  else if($u_mode == "update_form"){ // update_form
    $data_obj = isset($_POST["data_obj"])?$_POST["data_obj"]:[];
    if($flag_auth == 1){
      updateListDataObj($tbl_name, $data_obj, $s_id);
    }
  }// update_form

  else if($u_mode == "delete_form"){ // delete_form
    $id = isset($_POST["id"])?$_POST["id"]:"";
    if($flag_auth == 1){
      $arr_where = array("form_id"=>"$id");

      $tbl_name = "p_form_list_data";
      deleteListDataObj($tbl_name, $arr_where, $s_id);

      $tbl_name = "p_form_list_data_action";
      deleteListDataObj($tbl_name, $arr_where, $s_id);

      $tbl_name = "p_form_list_data_attribute";
      deleteListDataObj($tbl_name, $arr_where, $s_id);

      $tbl_name = "p_form_list";
      deleteListDataObj($tbl_name, $arr_where, $s_id);

      addToLog("delete form : $id", $s_id); 
    }

  }// delete_form
  else if($u_mode == "copy_paste_form"){ // copy form1 to form2  (form1 must be blank form)
        $form_id_copy_origin = isset($_POST["origin_form_id"])?$_POST["origin_form_id"]:"";
        $form_id_copy_dest = isset($_POST["dest_form_id"])?$_POST["dest_form_id"]:"";


        $sqlCmd = "SELECT count(data_id) as count_data_row
        FROM p_form_list_data WHERE form_id=? ";
        $stmt = $mysqli->prepare($sqlCmd);
        $stmt->bind_param('s',$form_id_copy_dest);
        if($stmt->execute()){
          $stmt->bind_result($count_data_row );
          if ($stmt->fetch()) {
          }//while
        }
        else{
        $msg_error .= $stmt->error;
        }
        $stmt->close();


  if($count_data_row == 0){
    $sqlCmd = "INSERT INTO p_form_list_data (form_id, data_seq, data_id, data_type, data_value, data_value_en, is_require)
      SELECT ?, data_seq, data_id, data_type, data_value, data_value_en, is_require FROM p_form_list_data WHERE form_id=?
      ON DUPLICATE KEY UPDATE data_seq=VALUES(data_seq), data_type=VALUES(data_type),data_value=VALUES(data_value), data_value=VALUES(data_value_en), is_require=VALUES(is_require);
    ";
//error_log("$form_id_copy_dest, $form_id_copy_origin / query : $sqlCmd");
    $stmt = $mysqli->prepare($sqlCmd);
    $stmt->bind_param('ss',$form_id_copy_dest, $form_id_copy_origin);

    if($stmt->execute()){
      $affect_row = $stmt->affected_rows;
    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();

    if($affect_row > 0)
      addToLog("copy form data list: $form_id_copy_origin to $form_id_copy_dest .", $s_id);


    if($msg_error == ""){
      $sqlCmd = "INSERT INTO p_form_list_data_action (form_id, data_id, data_parent_id, action_type, data_parent_value)
        SELECT ?, data_id, data_parent_id, action_type, data_parent_value FROM p_form_list_data_action WHERE form_id=?
        ON DUPLICATE KEY UPDATE data_parent_value=VALUES(data_parent_value)
      ";

      $stmt = $mysqli->prepare($sqlCmd);
      $stmt->bind_param('ss',$form_id_copy_dest, $form_id_copy_origin);
//echo "query : $sqlCmd";
      if($stmt->execute()){
        $affect_row = $stmt->affected_rows;
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();

      if($affect_row > 0)
      addToLog("copy form action: $form_id_copy_origin to $form_id_copy_dest .", $s_id);
    }//if($msg_error != ""){

    if($msg_error == ""){
      $sqlCmd = "INSERT INTO p_form_list_data_attribute (form_id, data_id, attr_id, attr_val)
        SELECT ?, data_id, attr_id, attr_val FROM p_form_list_data_attribute WHERE form_id=?
        ON DUPLICATE KEY UPDATE attr_val=VALUES(attr_val)
      ";

      $stmt = $mysqli->prepare($sqlCmd);
      $stmt->bind_param('ss',$form_id_copy_dest, $form_id_copy_origin);
//echo "query : $sqlCmd";
      if($stmt->execute()){
        $affect_row = $stmt->affected_rows;
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

      if($affect_row > 0)
      addToLog("copy form attr: $form_id_copy_origin to $form_id_copy_dest .", $s_id);
    }//if($msg_error != ""){


  }// count_data_row==0
  else{
    $msg_error = "Can not paste to $form_id_copy_dest because it is not blank form.";
  }



  } //copy_paste_form

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
