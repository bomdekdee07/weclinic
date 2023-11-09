<?

$flag_auth=1;


$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";
$tbl_name = "p_form_list";

if($flag_auth != 0){ // valid user session
//echo "enter02";
//  include_once("../in_auth_db.php");
  include_once("../function/in_fn_sql.php"); // sql update
  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_fn_link.php");

//echo "umode : $u_mode";
if($u_mode == "form_data_update"){ // form_data_update
  $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";
  $data_obj_list = isset($_POST["data_obj"])?$_POST["data_obj"]:[];
  $uid = isset($_POST["uid"])?$_POST["uid"]:"";
  $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
  $collect_time = isset($_POST["collect_time"])?$_POST["collect_time"]:"";
  $param_s_id = isset($_POST["s_id"])?$_POST["s_id"]:"";
  /*
  if($param_s_id == ""){
    if(isset($_SESSION("s_id")))
    $param_s_id = $_SESSION("s_id");
  }
*/
//print_r($data_obj_list);
    if(!isset($s_id)) $s_id="patient";
    $tbl_name = "p_data_result";
    foreach($data_obj_list as $data_obj) {
      $lst_data_update = array();
      $lst_data_update["uid"] = "$uid";
      $lst_data_update["collect_date"] = "$collect_date";
      $lst_data_update["collect_time"] = "$collect_time";
      $lst_data_update["s_id"] = "$param_s_id";

      foreach($data_obj as $data_id => $data_result) {
        $lst_data_update["data_id"] = "$data_id";
        $lst_data_update["data_result"] = "$data_result";
      }
      updateListDataObj($tbl_name, $lst_data_update, $s_id);
    } // foreach


    $query = "INSERT INTO p_data_form_done (uid,collect_date,collect_time,form_id,is_done,record_datetime)
    VALUES (?,?,?,?,1,NOW()) ON DUPLICATE KEY UPDATE update_datetime=NOW();
    ";

    //echo "query : $uid, $collect_date, $collect_time, $form_id/ $query";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssss", $uid, $collect_date, $collect_time, $form_id );
    if($stmt->execute()){ 
    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();

}

else if($u_mode == "select_form_detail"){ // select_form_detail
    $id = isset($_POST["id"])?$_POST["id"]:"";
    //selectData($tbl_name, "*", $lst_where_data_item, $query_add, $order_by){
    $arr_where = array("form_id"=>"$id");
    $arr_data_list = selectData("p_form_list_data", "*", $arr_where, "", "data_seq");
    $rtn['data_obj_list'] =$arr_data_list[0];



}





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




 function addFormComponent($form_id, $data_type, $data_value){

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
       data_type, data_value, form_id)
       SELECT @keyid := CONCAT('$id_prefix',  LPAD( (SUBSTRING(  IF(MAX(data_id) IS NULL,0,MAX(data_id)) ,$substr_pos_begin,$id_digit))+1, '$id_digit','0'))
         ,@seq_no := (select IF(MAX(data_seq) IS NULL,0,MAX(data_seq)) +10 from p_form_list_data where form_id='$form_id') ,
         '$data_type', '$data_value', '$form_id'
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
              $rtnObj = array("data_id"=>"$data_id", "data_seq"=>"$data_seq", "data_type"=>"$data_type", "data_value"=>"$data_value");
             }
           }
         }
         else{
             $msg_error .= $stmt->error;

         }
         $stmt->close();

    return $rtnObj;
 }
