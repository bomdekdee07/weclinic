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






    if($u_mode == "select_search_xpress_list"){ // select_search_xpress_list
      $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
      $date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:(new DateTime())->format('Y-m-d');
      $date_end = isset($_POST["date_end"])?$_POST["date_end"]:(new DateTime())->format('Y-m-d');
      $csl_check = isset($_POST["csl_check"])?$_POST["csl_check"]:"";

      $query_add = "";

      if($txt_search != "" ){
        $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
        $query_add .= " AND (p.uid LIKE '$txt_search' OR u.uic2 LIKE '$txt_search') ";
      }

      if($csl_check != "" ){ // xpress form confirmed by counselor
        if($csl_check == "Y" ){
          $query_add .= " AND (p.uid, p.collect_date) IN ";
        }
        else if($csl_check == "N" ){
          $query_add .= " AND (p.uid, p.collect_date) NOT IN ";
        }
        $query_add .= "(
          select uid, collect_date from x_xpress_service
          where collect_date >= '$date_beg' AND collect_date <='$date_end'
          and version='CSL')";
      }

      if($staff_clinic_id != "%" ){
        $query_add .= " AND p.site ='$staff_clinic_id' ";
      }

        $arr_data_list = array();

        $query = "SELECT
        p.collect_date,u.uic2, p.uid, p.xpress_sum, p.consent_agree,
        c.uid, c.xpress_sum, c.consent_agree, s.uid, s.xp_after_service_compare, ps.sc_id, ps.send_date
        FROM uic_gen as u, x_xpress_service as p

        LEFT JOIN x_xpress_service as c
            LEFT JOIN p_xpress_service as ps ON (c.uid=ps.uid AND c.collect_date=ps.collect_date)
        ON (p.uid=c.uid AND p.collect_date=c.collect_date AND c.version='CSL')

        LEFT JOIN x_xpress_satisfaction as s ON
        (p.uid=s.uid AND p.collect_date=s.collect_date)

        WHERE u.uid=p.uid AND p.version='RAW'
        AND p.collect_date >= '$date_beg' AND p.collect_date <='$date_end'
        $query_add
        ORDER BY p.collect_date DESC
                 ";

    //  echo $query;
               $stmt = $mysqli->prepare($query);

               if($stmt->execute()){
                 $stmt->bind_result( $p_collect_date,$p_uic,
                   $p_uid, $p_xpress_sum, $p_consent_agree,
                   $c_uid, $c_xpress_sum, $c_consent_agree, $s_uid,
                   $xpress_after_service,
                   $xpress_sc_id_send, $xpress_send_date
                 );

                 while ($stmt->fetch()) {

                   $arr_data = array();
                   $arr_data["collect_date"]= $p_collect_date;
                   $arr_data["uid"]= $p_uid;
                   $arr_data["uic"]= $p_uic;
                   $arr_data["p_x_sum"]= "$p_xpress_sum";
                   $arr_data["p_c_agree"]= "$p_consent_agree";

                   $arr_data["after_service"]= "$xpress_after_service";

                   $arr_data["sc_id"]= ($xpress_sc_id_send !== NULL)?$xpress_sc_id_send:"";
                   $arr_data["send_date"]= ($xpress_send_date !== NULL)?$xpress_send_date:"";


                   if($c_uid !== NULL){
                     $arr_data["c_uid"]=$c_uid;
                     $arr_data["c_x_sum"]= "$c_xpress_sum";
                     $arr_data["c_c_agree"]= "$c_consent_agree";
                   }
                   else{
                     $arr_data["c_uid"]="";
                     $arr_data["c_x_sum"]= "";
                     $arr_data["c_c_agree"]= "";
                   }

                   if($s_uid !== NULL){
                     $arr_data["s_uid"]=$s_uid;
                   }
                   else{
                     $arr_data["s_uid"]="";
                   }

                   $arr_data_list[]=$arr_data;
                 }// while

               }// if
               else{
                 $msg_error .= $stmt->error;
               }
               $stmt->close();
               $rtn['datalist'] = $arr_data_list;

    }// select_uid_xpress_list
  else if($u_mode == "select_uid_xpress_list"){ // select_uid_xpress_list
      $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
      $cur_visit_date = getToday();
      $flag_new_xpress="Y";
      $arr_data_list = array();

    // visit_day 	visit_day_before 	visit_day_after 	visit_order 	visit_status
/*
             $query = "SELECT
             p.collect_date, p.uid, p.xpress_sum, p.consent_agree,
             c.uid, c.xpress_sum, c.consent_agree, s.uid
             FROM x_xpress_service as p
             LEFT JOIN x_xpress_service as c ON
             (p.uid=c.uid AND p.collect_date=c.collect_date AND c.version='CSL')

             LEFT JOIN x_xpress_satisfaction as s ON
             (p.uid=s.uid AND p.collect_date=s.collect_date)

             WHERE p.uid = ? AND p.version='RAW'
             ORDER BY p.collect_date DESC
             ";
             */
             $query = "SELECT
             p.collect_date, p.uid, p.xpress_sum, p.consent_agree,
             c.uid, c.xpress_sum, c.consent_agree, s.uid, s.xp_after_service_compare, ps.sc_id, ps.send_date
             FROM x_xpress_service as p

             LEFT JOIN x_xpress_service as c
                 LEFT JOIN p_xpress_service as ps ON (c.uid=ps.uid AND c.collect_date=ps.collect_date)
             ON (p.uid=c.uid AND p.collect_date=c.collect_date AND c.version='CSL')

             LEFT JOIN x_xpress_satisfaction as s ON
             (p.uid=s.uid AND p.collect_date=s.collect_date)

             WHERE p.uid = ? AND p.version='RAW'
             ORDER BY p.collect_date DESC
                      ";
      //echo $query;
             $stmt = $mysqli->prepare($query);
             $stmt->bind_param("s", $uid);
             if($stmt->execute()){
               $stmt->bind_result( $p_collect_date,
                 $p_uid, $p_xpress_sum, $p_consent_agree,
                 $c_uid, $c_xpress_sum, $c_consent_agree, $s_uid,
                 $xpress_after_service,
                 $xpress_sc_id_send, $xpress_send_date
               );

               while ($stmt->fetch()) {

                 if($p_collect_date == $cur_visit_date) $flag_new_xpress="N";

                 $arr_data = array();
                 $arr_data["collect_date"]= $p_collect_date;
                 $arr_data["p_uid"]= $p_uid;
                 $arr_data["p_x_sum"]= "$p_xpress_sum";
                 $arr_data["p_c_agree"]= "$p_consent_agree";

                 $arr_data["after_service"]= "$xpress_after_service";

                 $arr_data["sc_id"]= ($xpress_sc_id_send !== NULL)?$xpress_sc_id_send:"";
                 $arr_data["send_date"]= ($xpress_send_date !== NULL)?$xpress_send_date:"";


                 if($c_uid !== NULL){
                   $arr_data["c_uid"]=$c_uid;
                   $arr_data["c_x_sum"]= "$c_xpress_sum";
                   $arr_data["c_c_agree"]= "$c_consent_agree";
                 }
                 else{
                   $arr_data["c_uid"]="";
                   $arr_data["c_x_sum"]= "";
                   $arr_data["c_c_agree"]= "";
                 }

                 if($s_uid !== NULL){
                   $arr_data["s_uid"]=$s_uid;
                 }
                 else{
                   $arr_data["s_uid"]="";
                 }

                 $arr_data_list[]=$arr_data;
               }// while

             }// if
             else{
               $msg_error .= $stmt->error;
             }
             $stmt->close();
             $rtn['is_new_xpress'] = $flag_new_xpress;
             $rtn['cur_visit_date'] = $cur_visit_date;
             $rtn['datalist'] = $arr_data_list;

  }// select_uid_xpress_list

  else if($u_mode == "select_visit_xpress"){ // select_visit_xpress
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
    $arr_data_list = array();

    // visit_day 	visit_day_before 	visit_day_after 	visit_order 	visit_status

    $query = "SELECT
    p.collect_date, p.uid, p.xpress_sum, p.consent_agree,
    c.uid, c.xpress_sum, c.consent_agree, s.uid, s.xp_after_service_compare, ps.sc_id, ps.send_date
    FROM x_xpress_service as p

    LEFT JOIN x_xpress_service as c
        LEFT JOIN p_xpress_service as ps ON (c.uid=ps.uid AND c.collect_date=ps.collect_date)
    ON (p.uid=c.uid AND p.collect_date=c.collect_date AND c.version='CSL')

    LEFT JOIN x_xpress_satisfaction as s ON
    (p.uid=s.uid AND p.collect_date=s.collect_date)

    WHERE p.uid = ? AND p.version='RAW' AND p.collect_date=?
             ";

    //  echo "$query / $uid, $visit_date";
             $stmt = $mysqli->prepare($query);
             $stmt->bind_param("ss", $uid, $visit_date);
             if($stmt->execute()){
               $stmt->bind_result( $p_collect_date,
                 $p_uid, $p_xpress_sum, $p_consent_agree,
                 $c_uid, $c_xpress_sum, $c_consent_agree, $s_uid,
                 $xpress_after_service,
                 $xpress_sc_id_send, $xpress_send_date
               );

               if ($stmt->fetch()) {
                 $arr_data = array();

                 $arr_data["collect_date"]= $p_collect_date;
                 $arr_data["p_uid"]= $p_uid;
                 $arr_data["p_x_sum"]= "$p_xpress_sum";
                 $arr_data["p_c_agree"]= "$p_consent_agree";

                 $arr_data["after_service"]= "$xpress_after_service";

                 $arr_data["sc_id"]= ($xpress_sc_id_send !== NULL)?$xpress_sc_id_send:"";
                 $arr_data["send_date"]= ($xpress_send_date !== NULL)?$xpress_send_date:"";

                 if($c_uid !== NULL){
                   $arr_data["c_uid"]=$c_uid;
                   $arr_data["c_x_sum"]= "$c_xpress_sum";
                   $arr_data["c_c_agree"]= "$c_consent_agree";
                 }
                 else{
                   $arr_data["c_uid"]="";
                   $arr_data["c_x_sum"]= "";
                   $arr_data["c_c_agree"]= "";
                 }

                 if($s_uid !== NULL){
                   $arr_data["s_uid"]=$s_uid;
                 }
                 else{
                   $arr_data["s_uid"]="";
                 }

                 $arr_data_list[]=$arr_data;
               }// if fetch

             }// if
             else{
               $msg_error .= $stmt->error;
             }
             $stmt->close();

             $rtn['datalist'] = $arr_data_list;

    }// select_visit_xpress

  else if($u_mode == "reload_new_xpress_service"){ // reload_new_xpress_service
      $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
      $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
      $arr_data_list = array();

    // visit_day 	visit_day_before 	visit_day_after 	visit_order 	visit_status
             $query = "SELECT p.collect_date, p.xpress_sum,
             fd.uid as fd_uid
             FROM x_xpress_service as p
             LEFT JOIN p_visit_form_done as fd ON
             (p.uid=fd.uid AND p.collect_date=fd.collect_date AND fd.form_id='xpress_service')

             WHERE p.uid = ? AND p.version='RAW' AND p.collect_date=?
             ORDER BY p.collect_date DESC
             ";
      //echo $query;
             $stmt = $mysqli->prepare($query);
             $stmt->bind_param("ss", $uid, $visit_date);
             if($stmt->execute()){
               $stmt->bind_result($collect_date, $result, $fd_uid);
               while ($stmt->fetch()) {
                 $arr_data = array();
                 $arr_data["collect_date"]= $collect_date;
                 $arr_data["form_done"]= ($fd_uid !== NULL)?"Y":"N";
                 $arr_data["result"]= ($result !== NULL)?$result:"Pending";
                 $arr_data_list[]=$arr_data;
               }// if

             }
             else{
               $msg_error .= $stmt->error;
             }
             $stmt->close();

             $rtn['datalist'] = $arr_data_list;


    }// select_uid_xpress_list



      else if($u_mode == "confirm_xpress_service_patient"){ // counselor confirm xpress result+xpress consent from patient
          $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
          $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
          $is_confirm_success = "N";


          $query = "SELECT * FROM x_xpress_service
          WHERE uid=? AND collect_date=? AND version='RAW'";
          $stmt = $mysqli->prepare($query);
          $stmt->bind_param("ss", $uid, $visit_date);
//echo "$query / $uid, $visit_date";
           if ($stmt->execute()){
               $result = $stmt->get_result();
               $arr_uid_data = $result->fetch_assoc();

           }//if
           $stmt->close();

           $inQuery = "select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='x_xpress_service' AND TABLE_SCHEMA='$db_name'";
         //echo "<br><b>".$inQuery."</b>";

           $stmt = $mysqli->prepare($inQuery);
           //$stmt->bind_param("s", $course_id);
           $stmt->execute();
           $stmt->bind_result($col_name);


           $copy_sql_param="";
           $copy_sql_value="";
           while ($stmt->fetch()) {
             if($col_name != "version"){
               $copy_sql_param .= "$col_name,";
               $copy_sql_value .= "'".$arr_uid_data[$col_name]."',";

             }
             else{ // copy RAW to version CSL
               $copy_sql_param .= "version,";
               $copy_sql_value .= "'CSL',";
             }
           } // while
           $copy_sql_param = substr($copy_sql_param,0,strlen($copy_sql_param)-1 );
           $copy_sql_value = substr($copy_sql_value,0,strlen($copy_sql_value)-1 );

           $stmt->close();


           $copy_sql = "INSERT INTO x_xpress_service ($copy_sql_param) VALUES($copy_sql_value)";

           $stmt = $mysqli->prepare($copy_sql);

           if ( false===$stmt ) {
              die('prepare() failed: ' . htmlspecialchars($mysqli->error));
           }

            if ($stmt->execute()){
                $is_confirm_success = "Y";
            }//if
            $stmt->close();



          $rtn['x_result'] = $arr_uid_data["xpress_sum"];
          $rtn['x_consent'] = $arr_uid_data["consent_agree"];
          $rtn['is_success'] = $is_confirm_success;


        }// select_uid_xpress_list





  else if($u_mode == "update_uid_contact_info"){ // update_uid_contact_info after save xpress consent
    $uid = isset($_POST["uid"])?urldecode($_POST["uid"]):"";
    $uic = isset($_POST["uic"])?urldecode($_POST["uic"]):"";

    $line_id = isset($_POST["line_id"])?$_POST["line_id"]:"";
    $email = isset($_POST["pid_format"])?$_POST["pid_format"]:"";
    $tel = isset($_POST["tel"])?$_POST["tel"]:"";
    $sms_tel = isset($_POST["sms_tel"])?$_POST["sms_tel"]:"";



        $query = "UPDATE basic_reg SET
        line_id=?, email=?,contact=?,sms_tel=?
        WHERE uic IN (select uic from uic_gen where uid=?)
        ";
        //echo "$line_id, $email, $tel, $sms_tel, $uid / $query" ;
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssss',$line_id, $email, $tel, $sms_tel, $uid);
        if($stmt->execute()){

        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();


  }// select_uid_visit_list



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
