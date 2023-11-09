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

$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";
$proj_id = "LAB";

//echo "enter01 $open_link";
if($flag_auth != 0){ // valid user session
//echo "enter02";
  include_once("../in_db_conn.php");
  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_file_func.php"); // file function
  //include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../function/in_fn_link.php");
  include_once("../function/in_fn_number.php");
  include_once("../function/in_fn_sendmail.php");
  include_once("../function/in_ts_log.php");



//echo "umode : $u_mode";


// for select only ***

$arr_tbl["lab_test"] = array();
$arr_tbl["lab_test"]["tbl_name"] = "p_lab_test";
$arr_tbl["lab_test"]["col_id"] = "lab_id";
$arr_tbl["lab_test"]["select_list"] = "
  SELECT lab_id as id, lab_name as name , g.lab_group_name as group_name
  FROM p_lab_test as t, p_lab_test_group as g
  WHERE t.lab_group_id=g.lab_group_id
";
$arr_tbl["lab_test"]["select_from_txt_search"] = "
 AND t.lab_name LIKE 'sTXT'
";
$arr_tbl["lab_test"]["select_from_parent_id"] = "
 AND g.lab_group_id = '[PARENT_ID]'
";

$arr_tbl["lab_test"]["order_by"] = " ORDER BY lab_name ASC ";


   if($u_mode == "select_list"){ // select_list
        $choice = isset($_POST["choice"])?$_POST["choice"]:"";
        $parent_id = isset($_POST["parent_id"])?$_POST["parent_id"]:"";
        $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";

        $arr_select = $arr_tbl[$choice];
        $query = $arr_select["select_list"];

        if($txt_search != ""){
          $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
          $query .= $arr_select["select_from_txt_search"];
          $query = str_replace("sTXT",$txt_search,$query) ;
        }
        if($parent_id != ""){
          $query .= $arr_select["select_from_parent_id"];
          $query = str_replace("[PARENT_ID]",$parent_id,$query) ;
        }
        $query .= $arr_select["order_by"];

        $arr_data_list = array();
          //echo " $query";
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
