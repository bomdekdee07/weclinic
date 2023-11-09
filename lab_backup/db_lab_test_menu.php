<?


$open_link = isset($_POST["open_link"])?$_POST["open_link"]:"N";
if($open_link != "Y"){ // staff save form
//  include_once("../in_auth_db.php");
}
else{ // patient save form
  $ROOT_FILE_PATH = $_SERVER['DOCUMENT_ROOT']."/weclinic/";
  $sc_id="Patient";
  $flag_auth=1;
}

$flag_auth=1;
$s_id = isset($_SESSION["s_id"])?$_SESSION["s_id"]:"";

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



  if($u_mode == "add_test_menu"){ // add_test_menu

    $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];
    $lst_data_list = isset($_POST["lst_data_list"])?$_POST["lst_data_list"]:"";
    //$specimen_add = isset($_POST["specimen_add"])?$_POST["specimen_add"]:"";


    $tbl_name = "p_lab_test_group";
    $col_id = "lab_group_id";
    $id_prefix = "LMT";

    $id_digit = 2;
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
            $arr_specimen_update = $lst_data_list["specimen_update"];
            $tbl_name = "p_lab_group_specimen"; $col_id="lab_group_id";
            $id = $rtn_id;
            foreach($arr_specimen_update as $lst_data_update) { // extract each item
              updateListData($tbl_name,$col_id, $id, $lst_data_update);
            }


           }// flag == 1

        }// if($col_value != "")
  }// add_setting
  else if($u_mode == "update_test_menu"){ // update_test_menu
    $id = isset($_POST["id"])?$_POST["id"]:"";
    $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];
    $lst_data_list = isset($_POST["lst_data_list"])?$_POST["lst_data_list"]:"";

    $tbl_name = "p_lab_test_group";
    $col_id = "lab_group_id";
    updateListData($tbl_name,$col_id, $id, $lst_data);
         $arr_specimen_update = $lst_data_list["specimen_update"];
         $tbl_name = "p_lab_group_specimen"; $col_id="lab_group_id";
         foreach($arr_specimen_update as $lst_data_update) { // extract each item
           updateListData($tbl_name,$col_id, $id, $lst_data_update);
         }

         // delete list
         if(isset($lst_data_list["delete_list"])){
           $arr_delete = $lst_data_list["delete_list"];
         //  print_r($arr_delete);
       //deleteListData($tbl_name,$main_col_id,$main_id,  $delete_col_id, $delete_id){
             $arr_tbl = array();

             $arr_tbl["specimen"] = array();
             $arr_tbl["specimen"]["tbl_name"] = "p_lab_group_specimen";
             $arr_tbl["specimen"]["col_id"] = "specimen_id";
             $tbl_name = ""; $main_col_id="lab_group_id";

             foreach($arr_delete as $itm_del) { // extract each item
               //$tbl_name .= $itm_del["tbl_name"];
               $tbl_name = $arr_tbl[$itm_del["tbl_name"]]["tbl_name"];
               $col_del_id = $arr_tbl[$itm_del["tbl_name"]]["col_id"];
               $del_id = $itm_del["id"];
               deleteListData($tbl_name,$main_col_id,$id, $col_del_id, $del_id);
             }

         }

  }// update_test_menu




  else if($u_mode == "select_lab_test_menu_list"){ // select_test_menu_list to use in dropdown

        $arr_data_list = array();
        $query = "SELECT lab_group_id as id, lab_group_name as name
        FROM p_lab_test_group WHERE is_disable = 0 order by lab_group_name";

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
  }// select_lab_test_menu_list
  else if($u_mode == "select_lab_test_menu_detail"){ // select_test_menu_list to use in dropdown
      $id = isset($_POST["id"])?$_POST["id"]:"";
      $arr_data_list = array();
      $query = "SELECT lg.lab_group_id, lg.lab_group_name, lg.lab_group_reject, lg.lab_group_note,
      lm.lab_method_id, lm.lab_method_name, lg.ref_lab_id, lt.lab_name as ref_lab_name
      FROM p_lab_test_group as lg
      LEFT JOIN
      p_lab_method as lm ON (lg.lab_method_id = lm.lab_method_id)
      LEFT JOIN
      p_lab_test as lt ON (lg.ref_lab_id = lt.lab_id)
      WHERE lg.lab_group_id = ?  ";

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

        $arr_data_specimen = array();
        $query = "SELECT l.specimen_id as id, sp.specimen_name as name,
        l.operator as opr, l.specimen_amt as amt, l.specimen_unit as unit
        FROM p_lab_group_specimen as l, p_lab_specimen as sp
        WHERE l.lab_group_id = ? AND l.specimen_id=sp.specimen_id
        ORDER BY sp.specimen_name";

          //echo "$id/ $query";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('s',$id);
            if($stmt->execute()){
              $result = $stmt->get_result();
              while($row = $result->fetch_assoc()) {
                $arr_data_specimen[] = $row;
              }
          }
          else{
            $msg_error .= $stmt->error;
          }
          $stmt->close();

        $rtn['data'] = $arr_data_list;
        $rtn['datalist_specimen'] = $arr_data_specimen;
}// select_lab_test_menu_list


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
