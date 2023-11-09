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
  include_once("../function/in_fn_sql_update.php"); // sql update
  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_fn_link.php");

//echo "umode : $u_mode";
if($u_mode == "select_form_data_item"){ // select_form_data_item

      $group_main_id = isset($_POST["group_main_id"])?$_POST["group_main_id"]:"";
      $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
      $txt_search = isset($_POST["txt_search"])?$_POST["txt_search"]:"";
      $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";

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



      $query = " SELECT
      gm.data_group_main_name_en as gm_name,
      gm.data_group_main_id as gm_id,
      g.data_group_id as g_id,
      g.data_group_name_en as g_name,
      d.data_id as d_id, d.data_type as d_type,
      d.data_name_en as d_name_en,d.data_name_th as d_name_th
      FROM p_data_group_main as gm ,p_data_group as g ,p_data_list as d
      WHERE g.data_group_main_id=gm.data_group_main_id
      AND g.data_group_id=d.data_group_id
      AND d.data_id NOT IN (
        select data_id from p_form_list_data
        where form_id='$form_id'
        and data_type not in ('q_label','label', 'html', 'line')
      )
      $query_add
      ORDER BY d.data_id
      ";

      $arr_data_list = selectDataSql($query);
      $rtn['datalist'] = $arr_data_list;

}
else if($u_mode == "select_form_detail"){ // select_form_detail
    $id = isset($_POST["id"])?$_POST["id"]:"";
    //selectData($tbl_name, "*", $lst_where_data_item, $query_add, $order_by){
    $arr_where = array("form_id"=>"$id");
    $arr_data_list = selectData("p_form_list_data", "*", $arr_where, "", "data_seq");
    $rtn['data_obj_list'] =$arr_data_list[0];



}
else if($u_mode == "select_form_editor_detail"){ // select_form_editor_detail
    $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";
    $arr_data_list = array();
    $query = " SELECT fd.data_id,  CONCAT(d.data_name_th, '/', d.data_name_en) as data_name,
      fd.data_value,fd.data_value_en,fd.data_seq, fd.data_type as form_data_type, d.data_type, fd.is_require
      FROM p_form_list_data as fd
           LEFT JOIN p_data_list as d ON (fd.data_id=d.data_id)
      WHERE fd.form_id=?
      ORDER BY fd.data_seq
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $form_id);
        if($stmt->execute()){
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()) {
             $row['data_type'] = ($row['data_type'] !== NULL)? $row['data_type']: $row['form_data_type'];
             unset($row['form_data_type']);
             $arr_data_list[] = $row;
          }//while

        }
        else{
          error_log($stmt->error);
        }
      $stmt->close();

    //$arr_data_list = selectDataSql($query);

    $arr_data_sub_list = array();
    $query = " SELECT ds.data_id, ds.data_value, ds.data_name_th,ds.data_name_en
      FROM p_data_sub_list as ds,
           p_form_list_data as d
      WHERE ds.data_id=d.data_id AND d.form_id='$form_id'
      ORDER BY ds.data_seq
    ";
    $arr_data_sub_list = selectDataSql($query);

    $rtn["data_obj_list"] = $arr_data_list;
    $rtn['data_obj_sub_list'] = $arr_data_sub_list;

}
else if($u_mode == "add_form_data_item"){ // add_form_data_item
    $txt_data_list = isset($_POST["txt_data_list"])?$_POST["txt_data_list"]:"";
    $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";

    $query = " SELECT
    d.data_id, d.data_type,
    d.data_name_en ,d.data_name_th,
    d.data_question_en ,d.data_question_th
    FROM p_data_list as d
    WHERE d.data_id IN ($txt_data_list)
    ";
//echo $query;
    $arr_data_list = selectDataSql($query);

    $data_add_item = array();
    $txt_sub_item_list = "";
    foreach ($arr_data_list as $data_item) {
//echo " data: ".$data_item['data_id'];
        $data_id = $data_item['data_id'];
        $data_type = $data_item['data_type'];
        $data_name = $data_item['data_name_th'];
        $data_question_th = $data_item['data_question_th'];
        $data_question_en = $data_item['data_question_en'];

        if($data_type != "checkbox"){
          $data_add_item[] =addFormComponent($form_id, "q_label", $data_question_th, $data_question_en);

          if($data_type == "radio" || $data_type == "dropdown"){
            $txt_sub_item_list .= "'$data_id',";
          }
        }

        $inQuery = "INSERT INTO p_form_list_data (data_seq, data_id, data_type, form_id)
        SELECT @seq_no := (select IF(MAX(data_seq) IS NULL,0,MAX(data_seq)) +10 from p_form_list_data where form_id='$form_id') ,
        '$data_id' , '$data_type', '$form_id'
        ";
    //echo $inQuery;
        $stmt = $mysqli->prepare($inQuery);
        if($stmt->execute()){
          $inQuery = "SELECT @seq_no;";
          $stmt = $mysqli->prepare($inQuery);
          $stmt->bind_result($data_seq);
            if($stmt->execute()){
              if($stmt->fetch()){
              //  echo "comp_id: $data_id, $data_seq";
                $data_add_item[] = array("data_id"=>"$data_id", "data_name"=>"$data_name", "data_seq"=>"$data_seq", "data_type"=>"$data_type", "data_value"=>"");
              }
            }
          }
          else{
              $msg_info .= $stmt->error;
          }
          $stmt->close();
    }// foreach

    $arr_data_sub_list = array();
    if($txt_sub_item_list != ""){ // there is sub items component
      $txt_sub_item_list = substr($txt_sub_item_list,0,strlen($txt_sub_item_list)-1) ;
      $query = " SELECT data_id, data_value, data_name_th,data_name_en
      FROM p_data_sub_list
      WHERE data_id IN ($txt_sub_item_list)
      ORDER BY data_seq
      ";
      $arr_data_sub_list = selectDataSql($query);
    }
/*
$query = " SELECT ds.data_id, ds.data_value, ds.data_name_th,ds.data_name_en, d2.data_id as c_data_id
  FROM p_data_sub_list as ds,
       p_form_list_data as d LEFT JOIN p_form_list_data as d2 ON(d.data_id=d2.parent_id)
  WHERE ds.data_id=d.data_id AND d.form_id='$form_id'
  ORDER BY ds.data_seq
";
*/
    $rtn["data_obj_list"] = $data_add_item;
    $rtn['data_obj_sub_list'] = $arr_data_sub_list;

  }// add_form_component

else if($u_mode == "add_form_component"){ // add_form_component
    $data_type = isset($_POST["data_type"])?$_POST["data_type"]:"";
    $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";
    $rtn["data_obj"] =addFormComponent($form_id, $data_type, '');
  }// add_form_component

else if($u_mode == "update_data_component"){ // update_data_component
  $data_obj = isset($_POST["data_obj"])?$_POST["data_obj"]:[];
  if($flag_auth == 1){
    updateListDataObj('p_form_list_data', $data_obj, $s_id);
  }
}// update_data_component
else if($u_mode == "delete_data_item"){ // delete_data_item
  $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";
  $data_id = isset($_POST["data_id"])?$_POST["data_id"]:"";
  if($flag_auth == 1){
    $arr_where = array("data_id"=>"$data_id", "form_id"=>"$form_id");
    deleteListDataObj("p_form_list_data", $arr_where, $s_id);

    $arr_where = array("data_id"=>"$data_id", "form_id"=>"$form_id");
    deleteListDataObj("p_form_list_data_action", $arr_where, $s_id);

    $arr_where = array("data_id"=>"$data_id", "form_id"=>"$form_id");
    deleteListDataObj("p_form_list_data_attribute", $arr_where, $s_id);
  }

}// delete_data_item

  else if($u_mode == "delete_form"){ // delete_form
    $id = isset($_POST["id"])?$_POST["id"]:"";
    if($flag_auth == 1){
      $arr_where = array("form_id"=>"$id");
      deleteListDataObj($tbl_name, $arr_where, $s_id);
    }

  }// delete_form


  else if($u_mode == "select_show_rule"){ // select_show_rule
      $data_id = isset($_POST["data_id"])?$_POST["data_id"]:"";
      $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";
      $action = isset($_POST["action"])?$_POST["action"]:"";
      //selectData($tbl_name, "*", $lst_where_data_item, $query_add, $order_by){
      $arr_where = array("data_id"=>"$data_id", "form_id"=>"$form_id", "action_type"=>"$action");
      $arr_data_list = selectData("p_form_list_data_action",
      "data_parent_id, data_parent_value", $arr_where, "", "data_parent_id");
    //  print_r($arr_data_list);
      $rtn['data_obj_list'] =$arr_data_list;
  }

  else if($u_mode == "select_show_rule_showhide_if"){ // select_show_rule
      $data_id = isset($_POST["data_id"])?$_POST["data_id"]:"";
      $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";

      $query ="SELECT data_parent_id, data_parent_value, action_type
      FROM p_form_list_data_action
      WHERE data_id=? AND form_id=? AND action_type <> 'put_after'
      ORDER BY data_parent_id
      ";
      $lst_data_param= array($data_id, $form_id);
      $arr_data_list = selectDataSql_withParam($query,$lst_data_param);

    //  print_r($arr_data_list);
      $rtn['data_obj_list'] =$arr_data_list;
  }

  else if($u_mode == "add_show_rule"){ // add_show_rule to data id
    $data_obj = isset($_POST["data_obj"])?$_POST["data_obj"]:[];
    if($flag_auth == 1){
      updateListDataObj('p_form_list_data_action', $data_obj, $s_id);
    }
      //$rtn["data_obj"] =addFormComponent($form_id, $data_type, '');
  }// add_show_rule

  else if($u_mode == "remove_show_rule"){ // remove_show_rule to data id
    $data_obj = isset($_POST["data_obj"])?$_POST["data_obj"]:[];
  //  print_r($data_obj);
    if($flag_auth == 1){
      deleteListDataObj('p_form_list_data_action', $data_obj, $s_id);
    }
      //$rtn["data_obj"] =addFormComponent($form_id, $data_type, '');
  }// add_show_rule





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




 function addFormComponent($form_id, $data_type, $data_value_th, $data_value_en){

 //print_r($lst_data_item);
   global $mysqli; // db
   global $s_id; // user_id

   global $msg_error;

   $rtnObj = array();
       $id_prefix = $form_id."_C"; // prefix & current year eg IH20
       $substr_pos_begin = 1+strlen($id_prefix);
       $where_substr_pos_end = strlen($id_prefix);
       $id_digit = 4; // 0001-9999


       $inQuery = "INSERT INTO p_form_list_data (data_id, data_seq,
       data_type, data_value, data_value_en, form_id)
       SELECT @keyid := CONCAT('$id_prefix',  LPAD( (SUBSTRING(  IF(MAX(data_id) IS NULL,0,MAX(data_id)) ,$substr_pos_begin,$id_digit))+1, '$id_digit','0'))
         ,@seq_no := (select IF(MAX(data_seq) IS NULL,0,MAX(data_seq)) +10 from p_form_list_data where form_id='$form_id') ,
         '$data_type', '$data_value_th', '$data_value_en','$form_id'
       FROM p_form_list_data WHERE SUBSTRING(data_id,1,$where_substr_pos_end) = '$id_prefix' ;
       ";
   //echo $inQuery;
       $stmt = $mysqli->prepare($inQuery);

       if($stmt->execute()){
         $inQuery = "SELECT @keyid, @seq_no;";
         $stmt = $mysqli->prepare($inQuery);
         $stmt->bind_result($data_id, $data_seq);
           if($stmt->execute()){
             if($stmt->fetch()){
             //  echo "comp_id: $data_id, $data_seq";
              $rtnObj = array("data_id"=>"$data_id", "data_seq"=>"$data_seq", "data_type"=>"$data_type", "data_value"=>"$data_value_th", "data_value_en"=>"$data_value_en");
             }
           }
         }
         else{
             $msg_error .= $stmt->error;

         }
         $stmt->close();

    return $rtnObj;
 }
