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
  include_once("inc_lab_setting.php");

  if($u_mode == "add_setting"){ // add_setting

    $setting_choice = isset($_POST["setting_choice"])?$_POST["setting_choice"]:"";
    $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];

    $arr_select = $arr_setting_tbl[$setting_choice];
    $tbl_name = $arr_select["tbl_name"];
    $col_id = $arr_select["col_id"];
    $id_prefix = $arr_select["prefix"];

    $id_digit = $arr_select["id_digit"];
    $substr_pos_begin = 1+strlen($id_prefix);
    $where_substr_pos_end = strlen($id_prefix);

    $col_insert = "";
    $col_value = "";

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
              //  $stmt->bind_param('sss',$proj_id, $clinic_id,$uid);

                if($stmt->execute()){
                  $inQuery = "SELECT @keyid;";
                  $stmt = $mysqli->prepare($inQuery.";");
                  $stmt->bind_result($rtn_id);
                  if($stmt->execute()){ // get leave id
                    if($stmt->fetch()){
                        $rtn['id'] = $rtn_id;

                    }
                  }

                }
                else{
                  $msg_error .= $stmt->error;
                }
                $stmt->close();
        }// if($col_value != "")
  }// add_setting
  else if($u_mode == "update_setting"){ // update_setting

    $id = isset($_POST["id"])?$_POST["id"]:"";
    $setting_choice = isset($_POST["setting_choice"])?$_POST["setting_choice"]:"";
    $lst_data = isset($_POST["lst_data_obj"])?$_POST["lst_data_obj"]:[];

    $arr_select = $arr_setting_tbl[$setting_choice];
    $tbl_name = $arr_select["tbl_name"];
    $col_id = $arr_select["col_id"];

    updateObjData($tbl_name,$col_id, $id, $lst_data);

  }// update_setting


    else if($u_mode == "select_setting_list"){ // select_setting_list
        $setting_choice = isset($_POST["setting_choice"])?$_POST["setting_choice"]:"";
        $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";

        $arr_select = $arr_setting_tbl[$setting_choice];
        $query_add = "";


        $search_by = "";
        if($txt_search != ""){
          $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
          $search_by = "WHERE ".(str_replace("sTXT",$txt_search,$arr_select["search_by"]) );
        }

        if($setting_choice == "sale_option"){
          if($txt_search != ""){
            $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
            $search_by = "WHERE sale_opt_name like '$txt_search' AND is_enable='1' ";
          }
          else{
            $search_by = "WHERE is_enable='1' ";
          }
        }



        $arr_data_list = array();
        $query = "SELECT ".$arr_select["col_sel_list"]." FROM ";
        $query.= $arr_select["tbl_name"]." ".$search_by;
        $query.= " ORDER BY ".$arr_select["order_by"];

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

  else if($u_mode == "get_setting_data"){ // select_setting_data
      $id = isset($_POST["id"])?$_POST["id"]:"";
      $setting_choice = isset($_POST["setting_choice"])?$_POST["setting_choice"]:"";
      $arr_select = $arr_setting_tbl[$setting_choice];

      $arr_data_obj = array();
      $query = "SELECT ".$arr_select["col_sel_detail"]." FROM ";
      $query.= $arr_select["tbl_name"]." WHERE ".$arr_select["col_id"]."=?";

        //echo " $query";
          $stmt = $mysqli->prepare($query);
          $stmt->bind_param('s',$id);
          if($stmt->execute()){
            $result = $stmt->get_result();
            if($row = $result->fetch_assoc()) {
              $arr_data_obj = $row;
            }
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();
        $rtn['data'] = $arr_data_obj;
}// select_setting_data
else if($u_mode == "delete_setting_data"){ // delete_setting_data
    $id = isset($_POST["id"])?$_POST["id"]:"";
    $setting_choice = isset($_POST["setting_choice"])?$_POST["setting_choice"]:"";
    $arr_select = $arr_setting_tbl[$setting_choice];

    $tbl_name = $arr_select["tbl_name"];
    $col_del_id = $arr_select["col_id"];
    deleteItemData($tbl_name,$col_del_id, $id);
}// delete_setting_data


else if($u_mode == "select_dropdown_list"){ // select_list to use in dropdown
  $setting_choice = isset($_POST["setting_choice"])?$_POST["setting_choice"]:"";
  $arr_select = $arr_setting_tbl[$setting_choice];

  $arr_data_list = array();
  $query = "SELECT ".$arr_select["col_sel_list"]." FROM ";
  $query.= $arr_select["tbl_name"] ;
  $query.= " ORDER BY ".$arr_select["order_by"];

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
