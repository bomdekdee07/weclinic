<?
include_once("../in_db_conn.php");
include_once("../in_db_conn_tc.php");

//update  or insert
function updateListData($tbl_name,$col_id, $id, $lst_data_item){
  global $mysqli; // db
  global $mysqli_tc; // db_trackchange
  global $s_id; // user_id

  global $msg_error;

        $col_insert = "";
        $col_update = "";
        $col_value = "";

      // extract each data & update
      foreach($lst_data_item as $item) {
        if($item['name'] != $col_id){
          $col_insert .= $item['name'].",";
          $col_value .= "'".$item['value']."',";
          $col_update .= $item['name']."='".$item['value']."',";
        }


      }//foreach

      $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
      $col_update = ($col_update !="")?substr($col_update,0,strlen($col_update)-1):"" ;
      $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;

      if($col_value != ""){
        $query = "INSERT INTO $tbl_name ($col_id,  $col_insert)
        VALUES ('$id',  $col_value) On Duplicate Key
        Update $col_update";
  //echo "query: $query";
        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

        // trackchange
/*
        $query = "INSERT INTO $tbl_name ($col_id,  $col_insert, update_user, update_date)
        VALUES ('$id',  $col_value, '$s_id', now())";
  //echo "query: $query";
        $stmt = $mysqli_tc->prepare($query);
        if($stmt->execute()){
        }
        else{

          $msg_error .= $stmt->error;
        //  echo "get_error: $msg_error";
        }
        $stmt->close();
*/
      }// if($col_value != "")

}

// update tbl where there is unique col id
function updateObjData($tbl_name,$col_id, $id, $lst_data_item){
  global $mysqli; // db
  global $mysqli_tc; // db_trackchange
  global $s_id; // user_id

  global $msg_error;

  $col_insert = "";
  $col_update = "";
  $col_value = "";

// extract each data & update
foreach($lst_data_item as $item) {
  if($item['name'] != $col_id){
    $col_insert .= $item['name'].",";
    $col_value .= "'".$item['value']."',";
    $col_update .= $item['name']."='".$item['value']."',";
  }


}//foreach

$col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
$col_update = ($col_update !="")?substr($col_update,0,strlen($col_update)-1):"" ;
$col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;

      if($col_update != ""){
        $query = "UPDATE $tbl_name SET $col_update
        WHERE $col_id='$id'
        ";
  //echo "query: $query";
        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

        // trackchange
        if(is_null($s_id)) $s_id = "none";

        $sql_cmd = $query;
        $query = "INSERT INTO a_log_cmd (update_user, sql_cmd)
        VALUES(?, ?)";
        $stmt = $mysqli_tc->prepare($query);
        $stmt->bind_param('ss',$s_id,$sql_cmd);
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

      }// if($col_value != "")
}



//update  or insert UID , collect_date, collect_time
function updateListDataAll($tbl_name, $lst_data_item){

//print_r($lst_data_item);

  global $mysqli; // db
  global $mysqli_tc; // db_trackchange
  global $s_id; // user_id

  global $msg_error;

        $col_insert = "";
        $col_update = "";
        $col_value = "";

      // extract each data & update
      foreach($lst_data_item as $item) {

          $col_insert .= $item['name'].",";
          $col_value .= "'".$item['value']."',";
          $col_update .= $item['name']."='".$item['value']."',";


      }//foreach

      $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
      $col_update = ($col_update !="")?substr($col_update,0,strlen($col_update)-1):"" ;
      $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;

      if($col_value != ""){
        $query = "INSERT INTO $tbl_name ($col_insert)
        VALUES ($col_value) On Duplicate Key
        Update $col_update";
  //echo "query: $query";
        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

        if(is_null($s_id)) $s_id = "none";

        $sql_cmd = $query;
        $query = "INSERT INTO a_log_cmd (update_user, sql_cmd)
        VALUES(?, ?)";
        $stmt = $mysqli_tc->prepare($query);
        $stmt->bind_param('ss',$s_id,$sql_cmd);
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();
/*
        // trackchange
        $query = "INSERT INTO $tbl_name ($col_insert, update_user, update_date)
        VALUES ($col_value, '$s_id', now())";
  //echo "query: $query";
        $stmt = $mysqli_tc->prepare($query);
        if($stmt->execute()){
        }
        else{
      //    echo "get_error: $msg_error";
          $msg_error .= $stmt->error;
        }
        $stmt->close();
*/
      }// if($col_value != "")
}





// delete
function deleteListDataAll($tbl_name,$lst_data_item){
    global $mysqli; // db
    global $mysqli_tc; // db_trackchange
    global $s_id; // user_id

    $col_delete = "";
    // extract each data & update
  //  print_r($lst_data_item);
    foreach($lst_data_item as $item) {
    //  echo "name: ".$item['name']." / ".$item['value'];
        $col_delete .= " AND ".$item['name']."='".$item['value']."' ";
    }//foreach

    $col_delete = ($col_delete !="")?substr($col_delete,4,strlen($col_delete)):"" ;
    if($col_delete != ""){

              $query = "DELETE FROM $tbl_name
              WHERE $col_delete ";
      //  echo "query: $query";
              $stmt = $mysqli->prepare($query);
              if($stmt->execute()){
              }
              else{
                $msg_error .= $stmt->error;
              }
          $stmt->close();


          $sql_cmd = "DELETE FROM $tbl_name WHERE $col_delete ";

          $query = "INSERT INTO a_log_cmd (update_user, sql_cmd)
          VALUES(?, ?)";
          $stmt = $mysqli_tc->prepare($query);
  				$stmt->bind_param('ss',$s_id,$sql_cmd);
          if($stmt->execute()){
          }
          else{
            $msg_error .= $stmt->error;
          }
          $stmt->close();

    }


}//deleteListDataAll

// delete
function deleteListData($tbl_name,$main_col_id,$main_id,  $delete_col_id, $delete_id){
    global $mysqli; // db
    global $mysqli_tc; // db_trackchange
    global $s_id; // user_id

        $query = "DELETE FROM $tbl_name
        WHERE $main_col_id =? AND $delete_col_id = ? ";
  //echo "query: $query";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ss',$main_id,$delete_id );
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();


        $sql_cmd = "DELETE FROM $tbl_name WHERE $main_col_id ='$main_id' AND $delete_col_id = '$delete_id' ";

        $query = "INSERT INTO a_log_cmd (update_user, sql_cmd)
        VALUES(?, ?)";
        $stmt = $mysqli_tc->prepare($query);
				$stmt->bind_param('ss',$s_id,$sql_cmd);
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

}



// delete
function deleteItemData($tbl_name,$delete_col_id, $delete_id){
    global $mysqli; // db
    global $mysqli_tc; // db_trackchange
    global $s_id; // user_id

        $query = "DELETE FROM $tbl_name WHERE $delete_col_id = ? ";
  //echo "query: $query";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('s',$delete_id);
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();


        $sql_cmd = "DELETE FROM $tbl_name
        WHERE $delete_col_id = '$delete_id';";

        $query = "INSERT INTO a_log_cmd (update_user, sql_cmd)
        VALUES(?, ?)";
        $stmt = $mysqli_tc->prepare($query);
				$stmt->bind_param('ss',$s_id,$sql_cmd);
        if($stmt->execute()){
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

}




// new version

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

function selectDataSql_withParam($sqlCmd, $lst_data_param){
  global $mysqli; // db
  global $s_id; // user_id
  global $msg_error;
  $arr_data_list = array();
  $sPrepare = "";
  //var $aObjData = array();
  foreach ($lst_data_param as $param){
     $sPrepare .= "s";
  }

      $stmt = $mysqli->prepare($sqlCmd);

      if($sPrepare != ""){
        $stmt->bind_param($sPrepare,...$lst_data_param);
      }
 
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

//update  or insert UID , collect_date, collect_time
function updateListDataObj($tbl_name, $lst_data_item, $s_id){

//print_r($lst_data_item);
  global $mysqli; // db
  global $msg_error;
  $affect_row = 0;

        $flag_success = true;
      	$aObjData = array();
        $col_insert = ""; $col_update = ""; $col_value = ""; $col_update_log = "";
        $sPrepare = "";

      foreach ($lst_data_item as $col => $value){
        //echo " $col / $value";
        array_push($aObjData,$value);
        $col_insert .= $col.",";
        $sPrepare .="s";
        $col_value.="?,";
        $col_update .= $col."=VALUES(".$col."),";

        $col_update_log .= $col."='".$value."',";

      }//foreach

      $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
      $col_update = ($col_update !="")?substr($col_update,0,strlen($col_update)-1):"" ;
      $col_update_log = ($col_update_log !="")?substr($col_update_log,0,strlen($col_update_log)-1):"" ;
      $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;

      if($col_value != ""){
        $query = "INSERT INTO $tbl_name ($col_insert)
        VALUES ($col_value) On Duplicate Key
        Update $col_update";
  //echo "query: $query";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param($sPrepare,...$aObjData);

        if($stmt->execute()){
          $affect_row = $stmt->affected_rows;
        }
        else{
          $flag_success = false;
          $msg_error .= $stmt->error;
        }

      //  $affect_row = $mysqli ->affected_rows;
      // echo "affectrow: $affect_row";
        $stmt->close();

       if($affect_row > 0){
        $sql_cmd = "update:[$tbl_name] $col_update_log";
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
       }

      }// if($col_value != "")

      //return $flag_success;
      return $affect_row;
}

// delete
function deleteListDataObj($tbl_name,$lst_where_data_item, $s_id){
    global $mysqli; // db
    $affect_row = 0;
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
        $affect_row = $stmt->affected_rows;
      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();

    }

      if($affect_row > 0){
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
      }



   return $affect_row ;

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

/*
//update  or insert UID , collect_date, collect_time
function updateListDataObj($tbl_name, $lst_data_item, $s_id){

//print_r($lst_data_item);
  global $mysqli; // db
  global $msg_error;
  $affect_row = 0;

        $flag_success = true;
        $col_insert = "";
        $col_update = "";
        $col_value = "";

      foreach ($lst_data_item as $col => $value){
        //echo " $col / $value";
        $col_insert .= $col.",";

        if($value != "null"){
          $col_value .= "'".$value."',";
          $col_update .= $col."='".$value."',";
        }
        else{
          $col_value .= "null,";
          $col_update .= $col."=null,";
        }

      }

      $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
      $col_update = ($col_update !="")?substr($col_update,0,strlen($col_update)-1):"" ;
      $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;

      if($col_value != ""){
        $query = "INSERT INTO $tbl_name ($col_insert)
        VALUES ($col_value) On Duplicate Key
        Update $col_update";
  //echo "query: $query";
        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){
        }
        else{
          $flag_success = false;
          $msg_error .= $stmt->error;
        }

        $affect_row = $mysqli ->affected_rows;
      // echo "affectrow: $affect_row";
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

      //return $flag_success;
      return $affect_row;
}
*/

?>
