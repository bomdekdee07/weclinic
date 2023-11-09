<?
include_once("../in_auth_db.php");
// form log

$msg_error = "";
$msg_info = "";
$returnData = "";
$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($flag_auth != 0){ // valid user session
  include_once("../in_db_conn.php");
  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_file_func.php"); // file function
  include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../function/in_fn_link.php");
  include_once("../function/in_fn_number.php");

  /*
  if($u_mode == "save_data_batch"){ // trainee register into course
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $project_id = isset($_POST["project_id"])?$_POST["project_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $dom_id = isset($_POST["dom_id"])?$_POST["dom_id"]:"";
    $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
    $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];



    $query = "DELETE FROM z_$dom_id WHERE uid=? AND collect_date=?";
  //echo $query;
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $uid, $visit_date);
    if($stmt->execute()){

    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();

  //echo "$uid / ".count($lst_data);
    // extract each data to domain group
    $seq_no = 1;
    foreach($lst_data as $row) {
      $col_insert = "";
      $col_value = "";
      $col_update = "";

       foreach($row as $item) {
         $col_insert .= $item['name'].",";
         $col_value .= "'".$item['value']."',";
       }
     //echo $col_value;
       // update to table domain

          $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
          $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;


          if($col_value != ""){
            $query = "INSERT INTO z_$dom_id (uid, collect_date,seq_no, $col_insert)
            VALUES ('$uid', '$visit_date','$seq_no', $col_value) ";

     //echo $query;

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

      $seq_no++;
    }//foreach

     echo $seq_no;

  }// save_data
  */
  if($u_mode == "add_data"){ // add log form data
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $project_id = isset($_POST["project_id"])?$_POST["project_id"]:"";
    $last_visit_date = isset($_POST["last_visit_date"])?$_POST["last_visit_date"]:"";
    $dom_id = isset($_POST["dom_id"])?$_POST["dom_id"]:"";
    $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];


  //echo "$uid / ".count($lst_data);
    // extract each data to domain group
    $seq_no = 1;

      $col_insert = "";
      $col_value = "";
      $col_update = "";

       foreach($lst_data as $item) {
         $col_insert .= $item['name'].",";
         $col_value .= "'".$item['value']."',";
       }
     //echo $col_value;
       // update to table domain

          $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
          $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;

          if($col_value != ""){
            $form_log = "Created By $s_name [".(new DateTime())->format('d M y H:i:s')."]";

            $query = "INSERT INTO z_$dom_id (seq_no, uid, initial_staff, form_log, $col_insert)
            SELECT @keyid := (IF(MAX(seq_no) IS NULL,0,MAX(seq_no)+1) ),'$uid', '$user_id', '$form_log', $col_value
            FROM z_$dom_id WHERE uid='$uid'
            ";

    // echo $query;

            $stmt = $mysqli->prepare($query);
            if($stmt->execute()){
              $inQuery = "SELECT @keyid;";
              $stmt = $mysqli->prepare($inQuery.";");
              $stmt->bind_result($seq_no);
              if($stmt->execute()){ // get seq no
                if($stmt->fetch()){
                    $rtn['seq_no'] = $seq_no;
                    //$rtn['collect_date'] = changeToThaiDate((new DateTime())->format('Y-m-d'));

                }
              }

              $msg_info = "ได้ดำเนินการเรียบร้อยแล้ว";
              
            }
            else{
              $msg_error .= $stmt->error;
              echo "erorr occur $msg_error";
            }
            $stmt->close();

          }
    // echo $seq_no;

  }// save_data
  else if($u_mode == "update_data"){ // add log form data
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $project_id = isset($_POST["project_id"])?$_POST["project_id"]:"";
    $dom_id = isset($_POST["dom_id"])?$_POST["dom_id"]:"";
    $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];

  //echo "$uid / ".count($lst_data);
    // extract each data to domain group
      $col_update = "";
      $seq_no = "";
       foreach($lst_data as $item) {
         if($item['name'] == "seq_no"){
           $seq_no = $item['value'];
         }
         else{
           $col_update .= $item['name']."='".$item['value']."',";
         }
       } // foreach
       // update to table domain
          $col_update = ($col_update !="")?substr($col_update,0,strlen($col_update)-1):"" ;

          if($col_update != ""){
            $form_log = "Updated By $s_name [".(new DateTime())->format('d M y H:i:s')."]";

            $query = "UPDATE z_$dom_id SET form_log=CONCAT(form_log, '<br>$form_log'), $col_update
            WHERE uid='$uid' AND seq_no=$seq_no
            ";

     //echo $query;

            $stmt = $mysqli->prepare($query);
            if($stmt->execute()){
              $inQuery = "SELECT @keyid;";
              $stmt = $mysqli->prepare($inQuery.";");
              $stmt->bind_result($seq_no);
              if($stmt->execute()){ // get seq no
                if($stmt->fetch()){

                }
              }

              $msg_info = "ได้ดำเนินการเรียบร้อยแล้ว";
            }
            else{
              $msg_error .= $stmt->error;
              echo "erorr occur $msg_error";
            }
            $stmt->close();

          }
    // echo $seq_no;

  }// update_data
  else if($u_mode == "remove_data"){ // remove log form data
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $seq_no = isset($_POST["seq_no"])?$_POST["seq_no"]:"";
    $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
    $dom_id = isset($_POST["dom_id"])?$_POST["dom_id"]:"";

    $query = "DELETE FROM z_$dom_id
    WHERE uid=? AND seq_no=? AND collect_date=?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $uid, $seq_no, $collect_date );
    if($stmt->execute()){

    }
    else{
      $msg_error .= $stmt->error;
    //  echo "erorr occur $msg_error";
    }
    $stmt->close();

  }// remove_data

  else if($u_mode == "row_info_log"){ // show row log data
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $seq_no = isset($_POST["seq_no"])?$_POST["seq_no"]:"";
    $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
    $dom_id = isset($_POST["dom_id"])?$_POST["dom_id"]:"";

    $query = "SELECT form_log FROM z_$dom_id
    WHERE uid=? AND seq_no=? AND collect_date=?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $uid, $seq_no, $collect_date );
    if($stmt->execute()){

      $stmt->bind_result($form_log);
      if ($stmt->fetch()) {

      }// if
      else{
        $form_log = "ไม่พบข้อมูล";
      }
    }
    else{
      $msg_error .= $stmt->error;
    //  echo "erorr occur $msg_error";
    }
    $stmt->close();
    $rtn["form_log"] = $form_log;


  }// remove_data

  else if($u_mode == "select_list_visit"){ // select log from visit
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $project_id = isset($_POST["project_id"])?$_POST["project_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
    $dom_id = isset($_POST["dom_id"])?$_POST["dom_id"]:"";
    $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";


    $arr_data_list = array();

    $query = "SELECT * FROM z_$dom_id WHERE uid=? ORDER BY collect_date, seq_no";
  //echo "$form_id/$query";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $uid);
    if($stmt->execute()){

        $result = $stmt->get_result();
        if($result->num_rows > 0) {
          $arr_data = array();
          while($arr_data = $result->fetch_assoc()) {
            $arr_data_list[] = $arr_data;
          }//if
        }
        $stmt->close();
    }
    else{
      $msg_error .= $stmt->error;
    }


    $rtn['datalist'] = $arr_data_list;

  }// select_list_visit


  $mysqli->close();
}



 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;
 $rtn['flag_auth'] = $flag_auth;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
