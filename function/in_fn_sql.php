<?
include_once("../in_db_conn.php");
include_once("../in_db_conn_tc.php");





function selectData($tbl_name, $select_field, $lst_where_data_item, $query_add, $order_by){

//print_r($lst_data_item);
  global $mysqli; // db
  global $s_id; // user_id

  global $msg_error;
      $arr_data_list = array();
      $str_where = "";
      foreach ($lst_where_data_item as $col => $value){
        $str_where .= " $col = '$value' AND ";
      }
      if($str_where != ""){
        $str_where = substr($str_where,0,strlen($str_where)-4);
        $str_where = " WHERE $str_where ";
      }
      else {
        if(trim($query_add) != "") $str_where = " WHERE $query_add ";
      }

      $order_by = ($order_by !="")?" ORDER BY $order_by ":"" ;
      $query = "SELECT $select_field FROM $tbl_name $str_where $order_by ";
      $stmt = $mysqli->prepare($query);
//echo "query : $query";
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

   return $arr_data_list;
}

function selectDataSql($sqlCmd){
  global $mysqli; // db
  global $s_id; // user_id
  global $msg_error;

      $arr_data_list = array();
      $stmt = $mysqli->prepare($sqlCmd);
//echo "query : $sqlCmd";
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

   return $arr_data_list;
}

function selectCount($tbl_name, $col_id, $lst_where_data_item, $query_add){

//print_r($lst_data_item);
  global $mysqli; // db
  global $s_id; // user_id

  global $msg_error;
      $arr_data_list = array();
      $str_where = "";
      $count=0;
      foreach ($lst_where_data_item as $col => $value){
        $str_where .= " $col = '$value' AND ";
      }
      if($str_where != ""){
        $str_where = substr($str_where,0,strlen($str_where)-4);
        $str_where = " WHERE $str_where ";
      }

      $query = "SELECT count($col_id) FROM $tbl_name $str_where";
      $stmt = $mysqli->prepare($query);
//echo "query : $query";
      if($stmt->execute()){
        $stmt->bind_result($count);
        if ($stmt->fetch()) {
        }
      }
      else{
      $msg_error .= $stmt->error;
      }
      $stmt->close();

   return $count;
}


function updateListDataObj($tbl_name, $lst_data_item, $s_id){

//print_r($lst_data_item);
  global $mysqli; // db
  global $msg_error;

        $flag_success = true;
        $col_insert = "";
        $col_update = "";
        $col_value = "";

      foreach ($lst_data_item as $col => $value){
        //echo " $col / $value";
        $col_insert .= $col.",";
        $col_value .= "'".$value."',";
        $col_update .= $col."='".$value."',";
      }

      $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
      $col_update = ($col_update !="")?substr($col_update,0,strlen($col_update)-1):"" ;
      $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;

      if($col_value != ""){
        $query = "INSERT INTO $tbl_name ($col_insert)
        VALUES ($col_value) On Duplicate Key
        Update $col_update";
//  echo "query: $query";
        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){
        }
        else{
          $flag_success = false;
          $msg_error .= $stmt->error;
        }
        $stmt->close();

        $sql_cmd = "update:[$tbl_name] $col_update";
        $query = "INSERT INTO a_log_cmd (update_user, sql_cmd)
        VALUES(?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ss',$s_id,$sql_cmd);
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

      }// if($col_value != "")

      return $flag_success;
}

// delete
function deleteListDataObj($tbl_name,$lst_where_data_item, $s_id){
    global $mysqli; // db
    $col_delete = "";

    $str_where = "";
    foreach ($lst_where_data_item as $col => $value){
      $str_where .= " $col = '$value' AND ";
    }

    if($str_where != ""){
      $str_where = substr($str_where,0,strlen($str_where)-4);
      $str_where = " WHERE $str_where ";

      $query = "DELETE FROM $tbl_name $str_where";
    //  echo "query: $query";
      $stmt = $mysqli->prepare($query);
      if($stmt->execute()){
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

    }

            $sql_cmd = "delete:[$tbl_name] $str_where";
            $query = "INSERT INTO a_log_cmd (update_user, sql_cmd)
            VALUES(?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ss',$s_id,$sql_cmd);
            if($stmt->execute()){

            }
            else{
              $msg_error .= $stmt->error;
            }
            $stmt->close();




}//deleteListDataObj


function addToLog($msgInfo, $s_id){
  global $mysqli; // db
  global $msg_error;

  $query = "INSERT INTO a_log_cmd (update_user, sql_cmd)
  VALUES(?, ?)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('ss',$s_id,$msgInfo);
  if($stmt->execute()){
  }
  else{
    $msg_error .= $stmt->error;
  }
  $stmt->close();
}


?>
