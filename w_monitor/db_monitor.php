<?

// UID Data Mgt
include_once("../in_auth_db.php");


$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($flag_auth != 0){ // valid user session
  include_once("../in_db_conn.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_file_func.php"); // file function
  include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../function/in_fn_link.php");
  include_once("../function/in_fn_number.php");

  if($u_mode == "select_viewlog"){ // select view log
    $txt_search_lognote = isset($_POST["txt_search_lognote"])?urldecode($_POST["txt_search_lognote"]):"";
    $txt_search_lognote = trim($txt_search_lognote);

    $txt_search_staff = isset($_POST["txt_search_staff"])?urldecode($_POST["txt_search_staff"]):"";
    $txt_search_staff = trim($txt_search_staff);

    $date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:(new DateTime())->format('Y-m-d');
    $date_end = isset($_POST["date_end"])?$_POST["date_end"]:(new DateTime())->format('Y-m-d');

    $staff_clinic_id = $_SESSION['weclinic_id'];
    $arr_data_list = array();

    $query_add_lognote = "";
    if($txt_search_lognote != ""){
      $query_add_lognote = " AND l.log_note like '%$txt_search_lognote%' ";
    }

    $query_add_staff = "";
    if($txt_search_staff != ""){
      $query_add_staff = " AND (s.s_name like '%$txt_search_staff%' OR sc.sc_id LIKE '%$txt_search_staff%') ";
    }

    $query = "SELECT
    l.log_date, l.log_note, l.staff_id, s.s_name, c.clinic_name
    FROM pv_log as l, p_staff as s, p_staff_clinic as sc ,p_clinic as c
    WHERE l.staff_id=sc.sc_id AND sc.s_id=s.s_id AND sc.clinic_id=c.clinic_id
    AND l.log_date >= '$date_beg 00:00:00' AND l.log_date <='$date_end 23:59:00'
    $query_add_lognote $query_add_staff
    ORDER BY log_id DESC
           ";
  //  echo "$query";
           $stmt = $mysqli->prepare($query);
           if($stmt->execute()){
             $stmt->bind_result($log_date, $log_note, $staff_id, $s_name, $clinic_name);

             while ($stmt->fetch()) {
               $arr_data = array();
               $arr_data["log_date"]=$log_date;
               $arr_data["log_note"]=$log_note;
               $arr_data["staff"]="[$staff_id] $s_name";
               $arr_data["site"]=$clinic_name;

               $arr_data_list[]=$arr_data;
             }// while

           }
           else{
             $msg_error .= $stmt->error;
           }

           $rtn['datalist'] = $arr_data_list;

  }// select_viewlog

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
