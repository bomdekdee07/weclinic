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



// personal data db mgt
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function
include_once("../function/in_file_func.php"); // file function
include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
include_once("../function/in_fn_link.php");
include_once("../function/in_fn_number.php");

$msg_error = "";
$msg_info = "";
$returnData = "";



$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($flag_auth != 0){ // valid user session

  if($u_mode == "save_data"){ // save data to form
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $project_id = isset($_POST["project_id"])?$_POST["project_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";
    $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
    $is_form_done = isset($_POST["form_done"])?$_POST["form_done"]:"N";

    $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];

    $arr_domain = array(); // domain_list_id
    $arr_domain_data = array(); // each domain data item

    $col_insert = "";
    $col_value = "";
    $col_update = "";

    // extract each data to domain group
    foreach($lst_data as $item) {
       $dom_id = $item['dom'];
    //echo "[$dom_id - ".$item['name']."=".$item['value']."]";
    //echo "[".$item['name']."=".$item['value']."]";

       if(!isset($arr_domain_data[$dom_id])){
         $arr_domain[] = $dom_id;
         $arr_domain_data[$dom_id] = array();
         $arr_domain_data[$dom_id]["insert"] = "";
         $arr_domain_data[$dom_id]["update"] = "";
         $arr_domain_data[$dom_id]["value"] = "";
       }

       $arr_domain_data[$dom_id]["insert"] .= $item['name'].",";
       $arr_domain_data[$dom_id]["update"] .=$item['name']."='".$item['value']."',";
       $arr_domain_data[$dom_id]["value"] .= "'".$item['value']."',";
    }//foreach

    // update each table domain
    foreach($arr_domain as $dom_id) {
       $col_insert = $arr_domain_data[$dom_id]["insert"];
       $col_update = $arr_domain_data[$dom_id]["update"];
       $col_value = $arr_domain_data[$dom_id]["value"];

       $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
       $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;
       if($col_update !=""){
         $col_update = substr($col_update,0,strlen($col_update)-1);
         $col_update = "collect_time=now(),$col_update";
       }

       if($col_value != ""){
         $query = "INSERT INTO x_$dom_id (uid, collect_date,collect_time, $col_insert)
         VALUES ('$uid', '$visit_date',now(), $col_value) On Duplicate Key
         Update $col_update";

  //echo "<br><br>$query;";
         $stmt = $mysqli->prepare($query);
         if($stmt->execute()){
           $msg_info = "ได้ดำเนินการเรียบร้อยแล้ว";

         }
         else{
           $msg_error .= $stmt->error;
           echo "erorr occur ";
         }
         $stmt->close();

       }
    }//foreach dom_item
  //  echo "msg_error / $msg_error/[$uid/$proj_id/$group_id/$visit_id/$form_id]";
    if($msg_error == ""){ // if no error  update form is done
      setLogNote($sc_id, "[$project_id] save form:$form_id [$uid|$visit_id|$visit_date|$project_id|$group_id|$is_form_done]");
      if($is_form_done == "Y"){
        $query = "INSERT INTO p_visit_form_done (uid,proj_id,group_id,visit_id,form_id,collect_date)
        VALUES (?, ?, ?, ?, ?, ?) On Duplicate Key
        Update collect_date=?";

      //echo $query;
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sssssss", $uid, $project_id,$group_id,$visit_id,$form_id, $visit_date, $visit_date);
        if($stmt->execute()){
          $msg_info = "ได้ดำเนินการเรียบร้อยแล้ว";
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();
      }

    }




  }// save_data


  if($u_mode == "save_data_admin"){ // save by admin
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $project_id = isset($_POST["project_id"])?$_POST["project_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
    $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";
    $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];

    $arr_domain = array(); // domain_list_id
    $arr_domain_data = array(); // each domain data item

    $col_insert = "";
    $col_value = "";
    $col_update = "";

    // extract each data to domain group
    foreach($lst_data as $item) {
       $dom_id = $item['dom'];
  //echo "[$dom_id - ".$item['name']."=".$item['value']."]";

       if(!isset($arr_domain_data[$dom_id])){
         $arr_domain[] = $dom_id;
         $arr_domain_data[$dom_id] = array();
         $arr_domain_data[$dom_id]["insert"] = "";
         $arr_domain_data[$dom_id]["update"] = "";
         $arr_domain_data[$dom_id]["value"] = "";
       }

       $arr_domain_data[$dom_id]["insert"] .= $item['name'].",";
       $arr_domain_data[$dom_id]["update"] .=$item['name']."='".$item['value']."',";
       $arr_domain_data[$dom_id]["value"] .= "'".$item['value']."',";
    }//foreach

    // update each table domain
    foreach($arr_domain as $dom_id) {
       $col_insert = $arr_domain_data[$dom_id]["insert"];
       $col_update = $arr_domain_data[$dom_id]["update"];
       $col_value = $arr_domain_data[$dom_id]["value"];

       $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
       $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;
       if($col_update !=""){
         $col_update = substr($col_update,0,strlen($col_update)-1);
         $col_update = "collect_time=now(),$col_update";
       }

       if($col_value != ""){
         $query = "INSERT INTO x_$dom_id (uid, collect_date,collect_time, $col_insert)
         VALUES (?, ?, '12:00', $col_value) On Duplicate Key
         Update $col_update";

  //echo "$uid, $visit_date / $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("ss", $uid, $visit_date);
         if($stmt->execute()){
           $msg_info = "ได้ดำเนินการเรียบร้อยแล้ว";
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();

       }
    }//foreach dom_item

  //echo "msg_error / $msg_error/[$uid/$project_id/$group_id/$visit_id/$form_id]";
   if($msg_error == ""){ // if no error  update form is done
     $query = "INSERT INTO p_visit_form_done (uid,proj_id,group_id,visit_id,form_id,collect_date)
     VALUES (?, ?, ?, ?, ?, ?) On Duplicate Key
     Update collect_date=?";

   //echo $query;
     $stmt = $mysqli->prepare($query);
     $stmt->bind_param("sssssss", $uid, $project_id,$group_id,$visit_id,$form_id, $visit_date, $visit_date);
     if($stmt->execute()){
       $msg_info = "ได้ดำเนินการเรียบร้อยแล้ว";
     }
     else{
       $msg_error .= $stmt->error;
     }
     $stmt->close();
   }




  }// save_data

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
