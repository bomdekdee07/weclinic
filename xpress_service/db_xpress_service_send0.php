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


    if($u_mode == "select_xpress_service_list"){ // select all xpress list
      $send_result = isset($_POST["send_result"])?$_POST["send_result"]:"";
      $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
      $date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:(new DateTime())->format('Y-m-d');
      $date_end = isset($_POST["date_end"])?$_POST["date_end"]:(new DateTime())->format('Y-m-d');

      //$clinic_id = $staff_clinic_id;




      $query_add = "";
      if($txt_search != "" ){
        $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
        $query_add .= " AND (x.uid LIKE '$txt_search' OR u.uic2 LIKE '$txt_search') ";
      }
      if($send_result != "" ){
        if($send_result == "Y" ){ // already send xpress result
          $query_add .= " AND (x.uid, x.collect_date) IN (
            select uid, collect_date from p_xpress_service
            where collect_date >= '$date_beg' AND collect_date <='$date_end'
          ) ";
        }
        else if($send_result == "N" ){ // not send xpress result
          $query_add .= " AND (x.uid, x.collect_date) NOT IN (
            select uid, collect_date from p_xpress_service
            where collect_date >= '$date_beg' AND collect_date <='$date_end'
          ) ";
        }


      }

      if($staff_clinic_id != "%" ){
        $query_add .= " AND x.site ='$staff_clinic_id' ";
      }


      $arr_data_list = array() ;

    // visit_day 	visit_day_before 	visit_day_after 	visit_order 	visit_status

             $query = "SELECT
             u.uic2, x.collect_date, x.uid, x.xpress_sum, x.consent_agree,
             x.consent_agree_tel, x.accept_channel, x.accept_channel_info,
             p.sc_id, p.send_date, p.xpress_note, xs.xp_after_service_compare
             FROM uic_gen as u, x_xpress_service as x

             LEFT JOIN p_xpress_service as p ON
             (x.uid=p.uid AND x.collect_date=p.collect_date)

             LEFT JOIN x_xpress_satisfaction as xs ON
             (x.uid=xs.uid AND x.collect_date=xs.collect_date)

             WHERE u.uid=x.uid AND x.version='CSL' AND x.consent_agree = 'Y'
             AND x.collect_date >= '$date_beg' AND x.collect_date <='$date_end'
             $query_add
             ORDER BY x.collect_date DESC
             ";

//echo "$send_result/$query";

             $stmt = $mysqli->prepare($query);
             if($stmt->execute()){
               $stmt->bind_result(
                 $uic, $collect_date, $uid, $xpress_sum, $consent_agree,
                 $consent_agree_tel, $accept_channel, $accept_channel_info,
                 $sc_id, $send_date, $xpress_note,
                 $xpress_after_service
               );

               while ($stmt->fetch()) {

                 $arr_data = array();
                 $arr_data["collect_date"]= $collect_date;
                 $arr_data["uid"]= $uid;
                 $arr_data["uic"]= $uic;
                 $arr_data["x_sum"]= "$xpress_sum";
              //   $arr_data["c_agree"]= "$consent_agree";
                 $arr_data["c_agree_tel"]= "$consent_agree_tel";
                 $arr_data["ch"]= "$accept_channel"; // 1:LINE, 2:email, 3:Tel, 4:SMS
                 $arr_data["ch_info"]= "$accept_channel_info"; // info of channel eg. tel no.

                 if($sc_id !== NULL){
                   $arr_data["sc_id"]= $sc_id;
                   $arr_data["send_date"]= getDBDateTime($send_date);
                   $arr_data["xpress_note"]= $xpress_note;
                 }
                 else{
                   $arr_data["sc_id"]="";
                   $arr_data["send_date"]= "";
                   $arr_data["xpress_note"]= "";
                 }

                 $arr_data["after_service"] = ($xpress_after_service !== NULL)?"$xpress_after_service":"";

                 $arr_data_list[]=$arr_data;
               }// while

             }
             else{
               $msg_error .= $stmt->error;
             }
             $stmt->close();

             $rtn['datalist'] = $arr_data_list;

    }// select_uid_xpress_list

    else if($u_mode == "send_xpress_result"){ // send_xpress_result
         //global $sc_id;

          $uid = isset($_POST["uid"])?$_POST["uid"]:"";
          $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
          $send_status = isset($_POST["send_status"])?$_POST["send_status"]:"1"; // 1:normal send, 2:abnormal send
          $xpress_note = isset($_POST["xpress_note"])?$_POST["xpress_note"]:"";



          $query = "REPLACE INTO p_xpress_service (uid, collect_date, send_date, sc_id, send_status, xpress_note )
                    VALUES (?,?,now(), ?,?,?)
                   ";
//echo "sc_id : $sc_id * $uid, $visit_date";
          //echo $query;
                 $stmt = $mysqli->prepare($query);
                 $stmt->bind_param('sssss',$uid, $visit_date, $sc_id, $send_status, $xpress_note);
                 if($stmt->execute()){
                   $rtn['s_name'] = $s_name;
                   $rtn['send_date'] = (new DateTime())->format('d/m/y (H:i:s)');

                 }
                 else{
                   $msg_error .= $stmt->error;
                   $rtn['sc_id'] = "";
                 }
                 $stmt->close();


  }// send_xpress_result



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
