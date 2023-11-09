<?

// staff Data Mgt
  include_once("../in_auth_db.php");

$msg_error = "";
$msg_info = "";
$returnData = "";

$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($flag_auth != 0){ // valid user session

  include_once("../in_db_conn.php");
  include_once("../in_file_prop.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_file_func.php"); // file function
  //include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../function/in_fn_link.php");
  include_once("../function/in_fn_number.php");
  include_once("../function/in_fn_sendmail.php");
  include_once("../function/in_ts_log.php");

if($u_mode == "select_hos_pid_list"){ // select_pid_list
  $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
  $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";

  $retention = isset($_POST["search_hos_opt2"])?$_POST["search_hos_opt2"]:"1";

  $query_add_clinic = "";
  if($clinic_id != "%")
  $query_add_clinic = " AND s.clinic_id like '$clinic_id' ";

  $query_add = "$query_add_clinic";
  if($txt_search != "")
  $query_add .= " AND (s.pid LIKE '$txt_search'  OR s.ul LIKE '$txt_search' OR s.f_name LIKE '$txt_search' OR s.s_name LIKE '$txt_search' OR s.hn LIKE '$txt_search' ) ";

  // retention select
  $query_retention = "";
  $query_retention_1 = ""; // pid & collect date
  $query_retention_2 = ""; // pid
  $arr_retention = array();
  if($retention != ''){
    if($retention == '0'){

      $query_retention = " FROM sdhos_pid_retro as s
      WHERE pid NOT IN(
           select r.pid from sdhos_sh_retention as r, sdhos_pid_retro as s
           where r.pid=s.pid $query_add_clinic $query_add
      )
      $query_add_clinic  $query_add
      ";
      $query_retention_1 = "SELECT pid, '' $query_retention";
      $query_retention_2 = " AND s.pid IN (SELECT pid $query_retention) ";

    }
    else { // ติดตามการรักษา
      $check_opt = "";
      if($retention == '1')$check_opt = "check_retention";
      else if($retention == '2')$check_opt = "check_vl";
      else if($retention == '3')$check_opt = "check_cd4";
      else if($retention == '4')$check_opt = "check_ART_change";
      else if($retention == '5')$check_opt = "check_AE";

      $query_retention = "
      FROM `sdhos_sh_retention` r1
      JOIN (SELECT pid, MAX(collect_date) collect_date, $check_opt
      FROM sdhos_sh_retention WHERE $check_opt = 1 GROUP BY pid)
      r2 ON r1.pid = r2.pid AND r1.collect_date = r2.collect_date
      AND r1.$check_opt=r2.$check_opt
      ";

      $query_retention_1 = "SELECT r1.pid, r1.collect_date $query_retention";
      $query_retention_2 = " AND s.pid IN (SELECT r1.pid $query_retention) ";
    }


  }//if($retention != '')
  else{ // select all
    $query_retention = "
    FROM `sdhos_sh_retention` r1
    JOIN (SELECT pid, MAX(collect_date) collect_date
    FROM sdhos_sh_retention GROUP BY pid)
    r2 ON r1.pid = r2.pid AND r1.collect_date = r2.collect_date
    ";

    $query_retention_1 = "SELECT r1.pid, r1.collect_date $query_retention";
    $query_retention_2 = "  ";


  }

  $stmt = $mysqli->prepare($query_retention_1);
  if($stmt->execute()){
    $stmt->bind_result($pid_retention, $visit_retention);

    while ($stmt->fetch()) {
      $arr_retention["$pid_retention"]= $visit_retention;
    }// while
  }
  else{
    $msg_error .= $stmt->error;
  }
  $stmt->close();


  $arr_data_list = array();

  $arr_pending_retention = array();
  $query = "SELECT s.pid, count(r.pid) as num
  FROM sdhos_pid_retro as s, sdhos_sh_retention as r
  WHERE s.pid = r.pid AND
  (r.pid, r.collect_date)  NOT IN (
    select d.pid, d.collect_date
    from sdhos_form_done as d , sdhos_pid_retro as s
    where d.form_id='sdhos_retention' $query_add
  ) $query_add
  GROUP BY r.pid
  ORDER BY r.pid;
         ";
  //echo "$clinic_id, $schedule_date/ $query";
         $stmt = $mysqli->prepare($query);
         if($stmt->execute()){
           $stmt->bind_result($pid_rtn, $count_rtn);

           while ($stmt->fetch()) {
             $arr_pending_retention["$pid_rtn"]= $count_rtn;
           }// while
         }
         else{
           $msg_error .= $stmt->error;
         }
      $stmt->close();

      $arr_pending_ae = array();
      $query = "SELECT s.pid, count(r.pid) as num
      FROM sdhos_pid_retro as s, sdhos_sh_ae as r
      WHERE s.pid = r.pid AND
      (r.pid, r.seq_no)  NOT IN (
        select d.pid, d.seq_no
        from sdhos_form_done as d , sdhos_pid_retro as s
        where d.form_id='sdhos_ae' $query_add
      ) $query_add
      GROUP BY r.pid
      ORDER BY r.pid;
             ";
//echo "$clinic_id, $schedule_date/ $query";
       $stmt = $mysqli->prepare($query);
       if($stmt->execute()){
         $stmt->bind_result($pid_ae, $count_ae);

         while ($stmt->fetch()) {
           $arr_pending_ae["$pid_ae"]= $count_ae;
         }// while
       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();

       $arr_pending_baseline = array();
       $query = "SELECT s.pid, count(r.pid) as num
       FROM sdhos_pid_retro as s, sdhos_sh_retro as r
       WHERE s.pid = r.pid AND
       (r.pid)  NOT IN (
         select d.pid
         from sdhos_form_done as d , sdhos_pid_retro as s
         where d.form_id='sdhos_retro' $query_add
       ) $query_add
       GROUP BY r.pid
       ORDER BY r.pid
              ";
 //echo "$clinic_id, $schedule_date/ $query";
        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){
          $stmt->bind_result($pid_b, $count_b);

          while ($stmt->fetch()) {
            $arr_pending_baseline["$pid_b"]= $count_b;
          }// while
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

  $query = "SELECT s.pid, s.ul, s.f_name, s.s_name, s.hn, s.create_date, s.is_import,
  b.arv_previous, b.hiv_test_date, b.startART_date

  FROM sdhos_pid_retro as s
  LEFT JOIN sdhos_sh_retro as b ON (s.pid=b.pid)
  WHERE  (s.pid LIKE ?  OR s.ul LIKE ? OR s.f_name LIKE ? OR s.s_name LIKE ? OR s.hn LIKE ? )
  $query_add_clinic
  $query_retention_2
  ORDER BY s.pid desc
         ";
  //echo "$clinic_id / $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("sssss", $txt_search, $txt_search, $txt_search, $txt_search, $txt_search);
         if($stmt->execute()){
           $stmt->bind_result($pid, $ul, $f_name, $s_name, $hn, $create_date, $is_import, $arv_previous, $hiv_test_date, $hiv_art_date );

           while ($stmt->fetch()) {
             $arr_data = array();
             $arr_data["pid"]= $pid;
             $arr_data["ul"]= $ul;
             $arr_data["name"]= "$f_name $s_name";
             $arr_data["hn"]= $hn;
             $arr_data["create_date"]= $create_date;
             $arr_data["im"]= $is_import;

             $art_pass = "0";//pass standard to SDART Hospital
             if($arv_previous == "N"){
               if($hiv_art_date > "2017-07-31" && $hiv_art_date < "2018-08-01"){ // ส.ค.60-ก.ค.61
                 $art_pass = "1";
               }
             }

             $arr_data["art_pass"]= $art_pass;

             if($art_pass == '1'){
               if(isset($arr_pending_retention[$pid])){
                 $arr_data["rtn_pend"]= $arr_pending_retention[$pid];
               }
               else $arr_data["rtn_pend"] = 0;

               if(isset($arr_pending_ae[$pid])){
                 $arr_data["ae_pend"]= $arr_pending_ae[$pid];
               }
               else $arr_data["ae_pend"] = 0;


             }

             if(isset($arr_pending_baseline[$pid])){
               $arr_data["base_pend"]= $arr_pending_baseline[$pid];
             }
             else{
               $arr_data["base_pend"]= "";
             }

             // lastest retention date
             $arr_data["rtn_date"]= isset($arr_retention[$pid])?$arr_retention[$pid]:"";


             $arr_data_list[]=$arr_data;
           }// while
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
         $rtn['datalist'] = $arr_data_list;
}// select_data_list

else if($u_mode == "select_hos_pid_list_center"){ // select from IHRI Staff to see overall hospital
  $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
  $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
  $clinic_id = isset($_POST["search_hos_opt"])?$_POST["search_hos_opt"]:"%";
  $retention = isset($_POST["search_hos_opt2"])?$_POST["search_hos_opt2"]:"1";


  $query_add_clinic = "";
  if($clinic_id != "%")
  $query_add_clinic = " AND s.clinic_id like '$clinic_id' ";

  $query_add = "$query_add_clinic";
  if($txt_search != "")
  $query_add .= " AND (s.pid LIKE '$txt_search'  OR s.ul LIKE '$txt_search' OR s.f_name LIKE '$txt_search' OR s.s_name LIKE '$txt_search' OR s.hn LIKE '$txt_search' ) ";

  // retention select
  $query_retention = "";
  $query_retention_1 = ""; // pid & collect date
  $query_retention_2 = ""; // pid
  $arr_retention = array();
  if($retention != ''){
    if($retention == '0'){

      $query_retention = " FROM sdhos_pid_retro as s
      WHERE pid NOT IN(
           select r.pid from sdhos_sh_retention as r, sdhos_pid_retro as s
           where r.pid=s.pid $query_add_clinic $query_add
      )
      $query_add_clinic  $query_add
      ";
      $query_retention_1 = "SELECT pid, '' $query_retention";
      $query_retention_2 = " AND s.pid IN (SELECT pid $query_retention) ";

    }
    else { // ติดตามการรักษา
      $check_opt = "";
      if($retention == '1')$check_opt = "check_retention";
      else if($retention == '2')$check_opt = "check_vl";
      else if($retention == '3')$check_opt = "check_cd4";
      else if($retention == '4')$check_opt = "check_ART_change";
      else if($retention == '5')$check_opt = "check_AE";

      $query_retention = "
      FROM `sdhos_sh_retention` r1
      JOIN (SELECT pid, MAX(collect_date) collect_date, $check_opt
      FROM sdhos_sh_retention WHERE $check_opt = 1 GROUP BY pid)
      r2 ON r1.pid = r2.pid AND r1.collect_date = r2.collect_date
      AND r1.$check_opt=r2.$check_opt
      ";

      $query_retention_1 = "SELECT r1.pid, r1.collect_date $query_retention";
      $query_retention_2 = " AND s.pid IN (SELECT r1.pid $query_retention) ";
    }


  }//if($retention != '')
  else{ // select all
    $query_retention = "
    FROM `sdhos_sh_retention` r1
    JOIN (SELECT pid, MAX(collect_date) collect_date
    FROM sdhos_sh_retention GROUP BY pid)
    r2 ON r1.pid = r2.pid AND r1.collect_date = r2.collect_date
    ";

    $query_retention_1 = "SELECT r1.pid, r1.collect_date $query_retention";
    $query_retention_2 = "  ";


  }

  $stmt = $mysqli->prepare($query_retention_1);
  if($stmt->execute()){
    $stmt->bind_result($pid_retention, $visit_retention);

    while ($stmt->fetch()) {
      $arr_retention["$pid_retention"]= $visit_retention;
    }// while
  }
  else{
    $msg_error .= $stmt->error;
  }
  $stmt->close();


  $arr_data_list = array();

  $arr_pending_retention = array();
  $query = "SELECT s.pid, count(r.pid) as num
  FROM sdhos_pid_retro as s, sdhos_sh_retention as r
  WHERE s.pid = r.pid AND
  (r.pid, r.collect_date)  NOT IN (
    select d.pid, d.collect_date
    from sdhos_form_done as d , sdhos_pid_retro as s
    where d.form_id='sdhos_retention' $query_add
  ) $query_add
  GROUP BY r.pid
  ORDER BY r.pid;
         ";
  //echo "$clinic_id, $schedule_date/ $query";
         $stmt = $mysqli->prepare($query);
         if($stmt->execute()){
           $stmt->bind_result($pid_rtn, $count_rtn);

           while ($stmt->fetch()) {
             $arr_pending_retention["$pid_rtn"]= $count_rtn;
           }// while
         }
         else{
           $msg_error .= $stmt->error;
         }
      $stmt->close();

      $arr_pending_ae = array();
      $query = "SELECT s.pid, count(r.pid) as num
      FROM sdhos_pid_retro as s, sdhos_sh_ae as r
      WHERE s.pid = r.pid AND
      (r.pid, r.seq_no)  NOT IN (
        select d.pid, d.seq_no
        from sdhos_form_done as d , sdhos_pid_retro as s
        where d.form_id='sdhos_ae' $query_add
      ) $query_add
      GROUP BY r.pid
      ORDER BY r.pid;
             ";
//echo "$clinic_id, $schedule_date/ $query";
       $stmt = $mysqli->prepare($query);
       if($stmt->execute()){
         $stmt->bind_result($pid_ae, $count_ae);

         while ($stmt->fetch()) {
           $arr_pending_ae["$pid_ae"]= $count_ae;
         }// while
       }
       else{
         $msg_error .= $stmt->error;
       }
       $stmt->close();

       $arr_pending_baseline = array();
       $query = "SELECT s.pid, count(r.pid) as num
       FROM sdhos_pid_retro as s, sdhos_sh_retro as r
       WHERE s.pid = r.pid AND
       (r.pid)  NOT IN (
         select d.pid
         from sdhos_form_done as d , sdhos_pid_retro as s
         where d.form_id='sdhos_retro' $query_add
       ) $query_add
       GROUP BY r.pid
       ORDER BY r.pid
              ";
 //echo "$clinic_id, $schedule_date/ $query";
        $stmt = $mysqli->prepare($query);
        if($stmt->execute()){
          $stmt->bind_result($pid_b, $count_b);

          while ($stmt->fetch()) {
            $arr_pending_baseline["$pid_b"]= $count_b;
          }// while
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

  $query = "SELECT s.pid, s.ul, s.f_name, s.s_name, s.hn, s.create_date, s.is_import,
  b.arv_previous, b.hiv_test_date, b.startART_date

  FROM sdhos_pid_retro as s
  LEFT JOIN sdhos_sh_retro as b ON (s.pid=b.pid)
  WHERE  (s.pid LIKE ?  OR s.ul LIKE ? OR s.f_name LIKE ? OR s.s_name LIKE ? OR s.hn LIKE ? )
  $query_add_clinic
  $query_retention_2
  ORDER BY s.pid desc
         ";
  //echo "$clinic_id / $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("sssss", $txt_search, $txt_search, $txt_search, $txt_search, $txt_search);
         if($stmt->execute()){
           $stmt->bind_result($pid, $ul, $f_name, $s_name, $hn, $create_date, $is_import, $arv_previous, $hiv_test_date, $hiv_art_date );

           while ($stmt->fetch()) {
             $arr_data = array();
             $arr_data["pid"]= $pid;
             $arr_data["ul"]= $ul;
             $arr_data["name"]= "$f_name $s_name";
             //$arr_data["hn"]= $hn;
             $arr_data["create_date"]= $create_date;
             $arr_data["im"]= $is_import;

             $art_pass = "0";//pass standard to SDART Hospital
             if($arv_previous == "N"){
               if($hiv_art_date > "2017-07-31" && $hiv_art_date < "2018-08-01"){ // ส.ค.60-ก.ค.61
                 $art_pass = "1";
               }
             }

             $arr_data["art_pass"]= $art_pass;

             if($art_pass == '1'){
               if(isset($arr_pending_retention[$pid])){
                 $arr_data["rtn_pend"]= $arr_pending_retention[$pid];
               }
               else $arr_data["rtn_pend"] = 0;

               if(isset($arr_pending_ae[$pid])){
                 $arr_data["ae_pend"]= $arr_pending_ae[$pid];
               }
               else $arr_data["ae_pend"] = 0;


             }

             if(isset($arr_pending_baseline[$pid])){
               $arr_data["base_pend"]= $arr_pending_baseline[$pid];
             }
             else{
               $arr_data["base_pend"]= "";
             }

             // lastest retention date
             $arr_data["rtn_date"]= isset($arr_retention[$pid])?$arr_retention[$pid]:"";


             $arr_data_list[]=$arr_data;
           }// while
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
         $rtn['datalist'] = $arr_data_list;
}// select_data_list


else if($u_mode == "get_pid_personal_data"){ // get_pid_data
  $pid = isset($_POST["pid"])?$_POST["pid"]:"";

    $arr_data = array();

    $query = "SELECT s.*
    FROM sdhos_pid_retro as s
    WHERE s.pid = ?
           ";
    //echo "$clinic_id, $schedule_date/ $query";
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param("s", $pid);
           if($stmt->execute()){

             $result = $stmt->get_result();
             while ($row = $result->fetch_assoc()) {
               $arr_data[]=$row;
             }//while

           }
           else{
             $msg_error .= $stmt->error;
           }
           $stmt->close();
           $rtn['data_obj'] = $arr_data[0];
}


else if($u_mode == "add_new_pid"){ // add_new_pid

  $ul = isset($_POST["ul"])?$_POST["ul"]:"";
  $f_name = isset($_POST["f_name"])?urldecode($_POST["f_name"]):"";
  $s_name = isset($_POST["s_name"])?urldecode($_POST["s_name"]):"";
  $remark = isset($_POST["remark"])?urldecode($_POST["remark"]):"";
  $birth_date = isset($_POST["birth_date"])?$_POST["birth_date"]:"";
  $tel = isset($_POST["tel"])?$_POST["tel"]:"";
  $citizen_id = isset($_POST["citizen_id"])?$_POST["citizen_id"]:"";
  $hn = isset($_POST["hn"])?$_POST["hn"]:"";
  $nation = isset($_POST["nation"])?$_POST["nation"]:"";

  $clinic_id = $_SESSION['weclinic_id'];

  //if(checkDuplicateCitizenID("", $citizen_id)){
  $count_duplicate = sdhos_checkHN($hn, $clinic_id);
  if($count_duplicate == 0){

    $id_prefix = "R$clinic_id";
    $id_digit = "4";
    $substr_pos_begin = 3+strlen($id_prefix);
    $where_substr_pos_end = 1+strlen($id_prefix);


    $query = "INSERT INTO sdhos_pid_retro
    (pid, clinic_id,  ul, birth_date, f_name,  s_name, tel, citizen_id,
      nation, hn, remark, create_date, create_by ) ";
    $query.= " SELECT @keyid := CONCAT('$id_prefix-',
    LPAD( (SUBSTRING(  IF(MAX(pid) IS NULL,0,MAX(pid))   ,
    $substr_pos_begin,$id_digit)*1)+1, '".$id_digit."','0') )";
    $query.= " ,?,?,?,?,?,?,?,?,?,?, now(), ?";
    $query.= " FROM sdhos_pid_retro WHERE SUBSTRING(pid,1,$where_substr_pos_end) = '".$id_prefix."-';";
  //echo "$query" ;

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssssssssss',$clinic_id,$ul,$birth_date, $f_name,$s_name,$tel,$citizen_id,$nation ,$hn ,$remark , $sc_id);
        if($stmt->execute()){
          $inQuery = "SELECT @keyid;";
          $stmt = $mysqli->prepare($inQuery.";");
          $stmt->bind_result($rtn_id);
          if($stmt->execute()){ // get leave id
            if($stmt->fetch()){
                $rtn['pid'] = $rtn_id;
                $rtn['create_date'] = getToday();
            }
          }

          $msg_info .= "Insert new PID : [$rtn_id]  successfully.";
        }
        else{
          $msg_error .= $stmt->error;
        }
        $stmt->close();

      }
      else{
        $msg_error .= "HN $hn มีในระบบแล้วใน ร.พ.นี้";
      }



}// add_new_pid
else if($u_mode == "update_pid"){ // update_pid
  $pid = isset($_POST["pid"])?$_POST["pid"]:"";
  $ul = isset($_POST["ul"])?$_POST["ul"]:"";
  $f_name = isset($_POST["f_name"])?urldecode($_POST["f_name"]):"";
  $s_name = isset($_POST["s_name"])?urldecode($_POST["s_name"]):"";
  $remark = isset($_POST["remark"])?urldecode($_POST["remark"]):"";
  $birth_date = isset($_POST["birth_date"])?$_POST["birth_date"]:"";
  $hn = isset($_POST["hn"])?$_POST["hn"]:"";


   $clinic_id = $_SESSION['weclinic_id'];


  // if(checkDuplicateCitizenID($pid, $citizen_id)){

        $query = "UPDATE sdhos_pid_retro SET
        ul=?,  birth_date=?,  f_name=?,   s_name=?,
        hn=?,  remark=?
        WHERE pid=?
          ";


            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sssssss',$ul,$birth_date, $f_name,$s_name ,$hn ,$remark , $pid);
            if($stmt->execute()){

              $msg_info .= "Update PID : $pid [$ul] successfully.";
              setLogNote($sc_id, "Update Personal Data PID : $pid");
            }
            else{
              $msg_error .= $stmt->error;
            }
            $stmt->close();
            /*
   }
   else{
     $msg_error .= "บัตรประชาชนหรือพาสปอร์ตนี้ มีในระบบแล้ว";
   }
*/

}// update_pid



else if($u_mode == "save_data_sdhos"){ // save_data_sdhos

  $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";
  $pid = isset($_POST["pid"])?urldecode($_POST["pid"]):"";

  $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";

  $is_form_done = isset($_POST["is_form_done"])?$_POST["is_form_done"]:"N";
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
       //$col_update = "collect_time=now(),$col_update";

     }


     if($col_value != ""){
       /*
       $query = "INSERT INTO sdhos_$dom_id (pid, collect_date,collect_time, $col_insert)
       VALUES ('$uid', '$visit_date',now(), $col_value) On Duplicate Key
       Update $col_update";
       */
//echo "visit date: $visit_date / $col_insert";

      $query = "INSERT INTO sdhos_$dom_id (pid, collect_date, $col_insert)
      VALUES ('$pid', '$visit_date', $col_value) On Duplicate Key
      Update $col_update";

/*
       if($visit_date != ""){
         $query = "INSERT INTO sdhos_$dom_id (pid, collect_date, $col_insert)
         VALUES ('$pid', '$visit_date', $col_value) On Duplicate Key
         Update $col_update";
       }
       else{
         $query = "INSERT INTO sdhos_$dom_id (pid, $col_insert)
         VALUES ('$pid', $col_value) On Duplicate Key
         Update $col_update";
       }
*/

//echo "<br>$pid / $visit_date<br>$query;";
//echo "query: $query;";
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

  if($is_form_done == "Y"){
    $query = "REPLACE INTO sdhos_form_done (pid, form_id  ,collect_date)
    VALUES ('$pid', '$form_id', '$visit_date')";

    $stmt = $mysqli->prepare($query);
    if($stmt->execute()){
    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();
  }
  else if($is_form_done == "N"){

    $query = "DELETE FROM sdhos_form_done
    WHERE pid='$pid' AND form_id='$form_id'  AND collect_date='$visit_date'";

    $stmt = $mysqli->prepare($query);
    if($stmt->execute()){

    }
    else{
      $msg_error .= $stmt->error;
    }
    $stmt->close();
  }


  //  echo "msg_error / $msg_error/[$uid/$proj_id/$group_id/$visit_id/$form_id]";
    if($msg_error == ""){ // if no error  update form is done
      setLogNote($sc_id, "[SDHos] save form: $form_id [$pid|$visit_date]");
      $rtn["flag_success"] = "1";
    }

  }// save sdhos form


  else if($u_mode == "save_data_log_sdhos"){ // save_data_log_sdhos
    $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";
    $pid = isset($_POST["pid"])?urldecode($_POST["pid"]):"";
    $seq_no = isset($_POST["seq_no"])?$_POST["seq_no"]:"";

    $is_form_done = isset($_POST["is_form_done"])?$_POST["is_form_done"]:"N";
    $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];

    $arr_domain = array(); // domain_list_id
    $arr_domain_data = array(); // each domain data item

    $col_insert = "";
    $col_value = "";
    $col_update = "";

    // extract each data to domain group
    foreach($lst_data as $item) {
       $dom_id = $item['dom'];
/*
       if($item['name'] == "seq_no"){

         if($item['value'] == ""){
           $seq_no= getMaxLogSeqNo($dom_id, $pid);
           $item['value'] = $seq_no;
         }

       }
*/
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


    if($seq_no == "") $seq_no = getMaxLogSeqNo($dom_id, $pid);

    // update each table domain
    foreach($arr_domain as $dom_id) {
       $col_insert = $arr_domain_data[$dom_id]["insert"];
       $col_update = $arr_domain_data[$dom_id]["update"];
       $col_value = $arr_domain_data[$dom_id]["value"];

       $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
       $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;
       if($col_update !=""){
         $col_update = substr($col_update,0,strlen($col_update)-1);
         //$col_update = "collect_time=now(),$col_update";

       }


       if($col_value != ""){

          $query = "INSERT INTO sdhos_$dom_id (pid, seq_no, $col_insert)
          VALUES ('$pid', '$seq_no', $col_value) On Duplicate Key
          Update $col_update";

  //echo "query: $query;";
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

    $rtn["seq_no"] = $seq_no;

    if($is_form_done == "Y"){

      $query = "REPLACE INTO sdhos_form_done (pid, form_id, seq_no)
      VALUES ('$pid', '$form_id', '$seq_no')";

//echo "$query";
      $stmt = $mysqli->prepare($query);
      if($stmt->execute()){

      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();
    }
    else if($is_form_done == "N"){
      $query = "DELETE FROM sdhos_form_done
      WHERE pid='$pid' AND form_id='$form_id'  AND seq_no='$seq_no' ";

//echo "$query";
      $stmt = $mysqli->prepare($query);
      if($stmt->execute()){

      }
      else{
        $msg_error .= $stmt->error;
      }
      $stmt->close();
    }

    //  echo "msg_error / $msg_error/[$uid/$proj_id/$group_id/$visit_id/$form_id]";
      if($msg_error == ""){ // if no error  update form is done
        setLogNote($sc_id, "[SDHos] save form log: $form_id [$pid|$seq_no]");
      }

}// save sdhos log form


else if($u_mode == "select_hos_pid_retention"){ // select_hos_pid_retention

  $pid = isset($_POST["pid"])?urldecode($_POST["pid"]):"";
  $arr_data_list = array();

  $query = "SELECT r.collect_date,
   r.check_retention, r.check_vl, r.check_cd4, r.check_ART_change, r.check_AE,
   r.retention, r.VL,r.VL_sign, r.CD4_count, r.ART_reason,count(a.seq_no) as ae_amt,
   d.pid
  FROM sdhos_sh_retention as r
  left join sdhos_sh_ae as a ON (r.collect_date=a.ae_collect_date AND r.pid=a.pid)
  left join sdhos_form_done as d ON (r.pid=d.pid and r.collect_date=d.collect_date and d.form_id='sdhos_retention')
  WHERE  r.pid=?
  GROUP BY r.collect_date, r.pid
  ORDER BY r.collect_date DESC
  ";
  //echo "$clinic_id, $schedule_date/ $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("s", $pid);
         if($stmt->execute()){
           $stmt->bind_result($collect_date, $check_retention, $check_vl, $check_cd4, $check_ART_change, $check_AE,
           $retention, $VL,$VL_sign, $CD4_count, $ART_reason,  $AE_amt, $d_pid  );

           while ($stmt->fetch()) {
             $arr_data = array();
             $arr_data["collect_date"]= $collect_date;

             $arr_data["c_rtn"]= $check_retention;
             $arr_data["c_vl"]= $check_vl;
             $arr_data["c_cd4"]= $check_cd4;
             $arr_data["c_art"]= $check_ART_change;
             $arr_data["c_ae"]= $check_AE;

             $arr_data["rtn"]= $retention;
             $arr_data["vl"]= "$VL";
             $arr_data["vl_sign"]= "$VL_sign";
             $arr_data["cd4"]= $CD4_count;
             $arr_data["art"]= $ART_reason;
             $arr_data["ae"]= $AE_amt;

             $arr_data["fd"]= ($d_pid !== NULL)?"1":"0";


             $arr_data_list[]=$arr_data;
           }// while
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
         $rtn['datalist'] = $arr_data_list;
}// select_data_list


else if($u_mode == "select_hos_pid_ae"){ // select_hos_pid_ae

  $pid = isset($_POST["pid"])?urldecode($_POST["pid"]):"";
  $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";

  $query_add = "";
  if($collect_date != ""){
    $query_add = " AND a.ae_collect_date = '$collect_date' ";
  }

  $arr_data_list = array();
/*
  $query = "SELECT r.seq_no,r.ae_collect_date, r.ae_symptom, r.ae_start_date, r.ae_stop_date,
  r.ae_treatment, r.ae_outcome, r.ae_vl_check, r.ae_cd4_check,
  r.ae_vl_date, r.ae_vl, r.ae_cd4_date, r.ae_cd4_count,
  d.pid
  FROM sdhos_sh_ae as r
  left join sdhos_form_done as d ON (r.pid=d.pid and r.seq_no=d.seq_no  and d.form_id='sdhos_ae')

  WHERE  r.pid=? AND $query_add
  ORDER BY r.ae_collect_date desc, r.seq_no asc
  ";
*/

$query = "SELECT a.seq_no,a.ae_collect_date, a.ae_symptom, a.ae_start_date, a.ae_stop_date,
a.ae_treatment0,a.ae_treatment1,a.ae_treatment2,a.ae_treatment3,a.ae_treatment4, a.ae_outcome,
r.check_vl, r.check_cd4, r.VL,r.VL_sign, r.CD4_count, r.CD4pc ,
d.pid
FROM sdhos_sh_retention as r, sdhos_sh_ae as a
left join sdhos_form_done as d ON (a.pid=d.pid and a.seq_no=d.seq_no  and d.form_id='sdhos_ae')

WHERE  a.pid=? AND a.pid=r.pid AND a.ae_collect_date=r.collect_date $query_add
ORDER BY a.ae_collect_date desc, a.seq_no asc
";

  //echo "$clinic_id, $schedule_date/ $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("s", $pid);
         if($stmt->execute()){
           $stmt->bind_result($seq_no, $ae_date, $ae_symptom, $ae_start_date, $ae_stop_date,
           $ae_treatment0, $ae_treatment1,$ae_treatment2,$ae_treatment3,$ae_treatment4, $ae_outcome,
           $check_vl, $check_cd4, $vl, $vl_sign, $cd4_count, $cd4_pc, $d_pid
          );

           while ($stmt->fetch()) {
             $arr_data = array();
             $arr_data["seq_no"]= $seq_no;
             $arr_data["ae_date"]= $ae_date;
             $arr_data["symptom"]= $ae_symptom;
             $arr_data["start_date"]= ($ae_start_date !== NULL)?$ae_start_date:"";
             $arr_data["stop_date"]= ($ae_stop_date !== NULL)?$ae_stop_date:"";
             $arr_data["t0"]= $ae_treatment0;
             $arr_data["t1"]= $ae_treatment1;
             $arr_data["t2"]= $ae_treatment2;
             $arr_data["t3"]= $ae_treatment3;
             $arr_data["t4"]= $ae_treatment4;
             $arr_data["outcome"]= $ae_outcome;

             $ae_vl_complete = "";
             $ae_vl_txt = "";
             if($check_vl == "1"){
               if($vl != "") $ae_vl_complete = "Y";
               else $ae_vl_complete = "N";

               if($vl_sign == 1) $vl_sign = "<";
               else if($vl_sign == 2) $vl_sign = "=";
               else if($vl_sign == 3) $vl_sign = ">";

               $ae_vl_txt = "$vl_sign $vl";
             }

             $ae_cd4_complete = "";
             $ae_cd4_txt = "";
             if($check_cd4 == "1"){
               if($cd4_count != "") $ae_cd4_complete = "Y";
               else $ae_cd4_complete = "N";

               $ae_cd4_txt = "$cd4_count [$cd4_pc%]";
             }

             $arr_data["vl"]= $ae_vl_txt;
             $arr_data["cd4"]= $ae_cd4_txt;

             $arr_data["vl_check"]= $check_vl;
             $arr_data["cd4_check"]= $check_cd4;

             $arr_data["vl_complete"]= $ae_vl_complete;
             $arr_data["cd4_complete"]= $ae_cd4_complete;

             $arr_data["fd"]= ($d_pid !== NULL)?"1":"0";


             $arr_data_list[]=$arr_data;
           }// while
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
         $rtn['datalist'] = $arr_data_list;
}// select_data_list

else if($u_mode == "delete_retention"){ // delete_retention
  $pid = isset($_POST["pid"])?$_POST["pid"]:"";
  $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";

  $clinic_id = $_SESSION['weclinic_id'];


  // if(checkDuplicateCitizenID($pid, $citizen_id)){

        $query = "DELETE FROM sdhos_sh_retention
        WHERE pid=? AND collect_date=?
          ";

            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ss',$pid,$collect_date);
            if($stmt->execute()){
              setLogNote($sc_id, "Delete SDHos Retention: [$pid/$collect_date]");
            }
            else{
              $msg_error .= $stmt->error;
            }
            $stmt->close();


}// delete_retention

else if($u_mode == "remove_ae"){ // remove_ae
  $pid = isset($_POST["pid"])?$_POST["pid"]:"";
  $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";

  $clinic_id = $_SESSION['weclinic_id'];


  // if(checkDuplicateCitizenID($pid, $citizen_id)){

        $query = "DELETE FROM sdhos_sh_ae
        WHERE pid=? AND ae_collect_date=?
          ";
//echo "$pid,$collect_date / $query";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ss',$pid,$collect_date);
            if($stmt->execute()){
              setLogNote($sc_id, "Delete SDHos AE: [$pid/$collect_date]");
            }
            else{
              $msg_error .= $stmt->error;
            }
            $stmt->close();


}// remove_ae


else if($u_mode == "delete_ae"){ // delete_ae
  $pid = isset($_POST["pid"])?$_POST["pid"]:"";
  $seq_no = isset($_POST["seq_no"])?$_POST["seq_no"]:"";
  $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";

  $clinic_id = $_SESSION['weclinic_id'];


        $query = "DELETE FROM sdhos_sh_ae
        WHERE pid=? AND seq_no=?
          ";
//echo "$pid,$seq_no / $query";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ss',$pid,$seq_no);
            if($stmt->execute()){
              setLogNote($sc_id, "Delete SDHos AE: [$pid/$collect_date/$seq_no]");
            }
            else{
              $msg_error .= $stmt->error;
            }
            $stmt->close();


}// delete_retention



else if($u_mode == "check_exist_log_data"){ // check_exist_log_data  eg. retention
  $log_form = isset($_POST["log_form"])?$_POST["log_form"]:"";
  $pid = isset($_POST["pid"])?urldecode($_POST["pid"]):"";
  $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";

  $query = "SELECT count(r.pid)
  FROM sdhos_sh_$log_form as r
  WHERE  r.pid=? AND r.collect_date=?
  ";
  //echo " $pid, $visit_date/ $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("ss", $pid, $visit_date);
         if($stmt->execute()){
           $stmt->bind_result($rec_found);

           if ($stmt->fetch()) {

           }// if
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
         $rtn['rec_found'] = $rec_found;
}// check_exist_log_data

else if($u_mode == "get_pid_birth_year"){ // get age to retro baseline

  $pid = isset($_POST["pid"])?urldecode($_POST["pid"]):"";
  $birth_yr="";
  $query = "SELECT birth_date
  FROM sdhos_pid_retro
  WHERE  pid=?
  ";
  //echo "$clinic_id, $schedule_date/ $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("s", $pid);
         if($stmt->execute()){
           $stmt->bind_result($birth_date);

           if ($stmt->fetch()) {
             if($birth_date != "0000-00-00" && $birth_date != NULL){
               $birthDate = explode("-", $birth_date);
               $birth_yr = $birthDate[0] + 543;
             }
           }// if
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
         $rtn['birth_yr'] = $birth_yr;
}// select_data_list



$mysqli->close();
}//$flag_auth != 0
else{
  $msg_error = "Session Expired, Please handle pending job after login.";
}

 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;

 $rtn['flag_auth'] = $flag_auth;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;


 function checkDuplicateCitizenID($strPID, $strCitizenID){
       global $mysqli;

       $query = "SELECT count(citizen_id)
       FROM sdhos_pid_retro WHERE citizen_id = ?";

       if($strPID != "") $query .= " AND pid <> '$strPID'";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $strCitizenID);
        if($stmt->execute()){
          $stmt->bind_result($count);
          if ($stmt->fetch()) {

          }
        }
        else{
            $msg_error .= $stmt->error;
        }
        $stmt->close();

        if($count == 0) return true;
        else false;

 }

 function getMaxLogSeqNo($domainID, $pid){
       global $mysqli;

       $query = "SELECT max(seq_no)
       FROM sdhos_$domainID WHERE pid = ?";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $pid);
        if($stmt->execute()){
          $stmt->bind_result($max_seq_no);
          if ($stmt->fetch()) {

          }
        }
        else{
            $msg_error .= $stmt->error;
        }
        $stmt->close();
        $max_seq_no += 1;

        return $max_seq_no;
 }


  function sdhos_checkHN($hn, $hos_id){ // 1:found, 0:not found
    global $mysqli;
    $count_hn = 0;
    $inQuery = "SELECT count(pid) FROM
    sdhos_pid WHERE hn = ? AND clinic_id=?
    ";
    $stmt = $mysqli->prepare($inQuery);
    $stmt->bind_param("ss",$hn, $hos_id);
    if ($stmt->execute()) {
      $stmt->bind_result($count_hn);
      if($stmt->fetch()){

      }
    }
    else{
       die("Errormessage: ". $stmt->error);
       $msg_error .= "Error : ".$stmt->error;
    }
    $stmt->close();

    if($count_hn == 0){
      $inQuery = "SELECT count(pid) FROM
      sdhos_pid_retro WHERE hn = ? AND clinic_id=?
      ";
      $stmt = $mysqli->prepare($inQuery);
      $stmt->bind_param("ss",$hn, $hos_id);
      if ($stmt->execute()) {
        $stmt->bind_result($count_hn);
        if($stmt->fetch()){

        }
      }
      else{
         die("Errormessage: ". $stmt->error);
         $msg_error .= "Error : ".$stmt->error;
      }
      $stmt->close();
    }

    return $count_hn;

  }
