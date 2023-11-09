<?

// survey gizmo check form done
include_once("../in_auth_db.php");

$msg_error = "";
$msg_info = "";
$returnData = "";



$u_mode = isset($_POST["u_mode"])?$_POST["u_mode"]:"";

if($flag_auth != 0){ // valid user session
  include_once("../in_db_conn.php");
  include_once("../function/in_fn_date.php"); // date function
  include_once("../function/in_file_func.php"); // file function
  include_once("../function/in_fn_link.php");
  include_once("../function/in_fn_number.php");

  if($u_mode == "select_list"){ // select completed survey gizmo
    $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
    $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
    $sel_opt = isset($_POST["sel_opt"])?urldecode($_POST["sel_opt"]):"%";


    $date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:(new DateTime())->format('Y-m-d');
    $date_end = isset($_POST["date_end"])?$_POST["date_end"]:(new DateTime())->format('Y-m-d');

    $staff_clinic_id = $_SESSION['weclinic_id'];

    $query_date_range = " s.visit_date >= '$date_beg' AND s.visit_date <= '$date_end' ";

    $query_add = " ";
    if($txt_search != ""){
      $query_add .= " AND (s.pid like '$txt_search' OR s.acid like '$txt_search' OR s.uic like '$txt_search') ";
    }

    if($sel_opt != "all"){
      if($sel_opt == "0"){
        $query_add .= " AND s.uic IN
        (select uic from p_surveygizmo_form_done_trc as s
         where sc_id_check = '' AND $query_date_range
        )
        ";
      }

      else if($sel_opt == "1")
       $query_add .= " AND s.sc_id_check <> '' ";
      else if($sel_opt == "2")
       $query_add .= " AND s.uic IN
       (select uic from p_surveygizmo_form_done_revise
         where check_staff_id = '' AND $query_date_range
       )

       ";
    }

    if($staff_clinic_id != "%"){
       $query_add .= " AND s.trc_site = '$staff_clinic_id' ";
    }

    $arr_obj = array();
    $query = "SELECT s.form_id, s.pid,s.acid,s.uic, s.trc_site, s.visit_date, s.submit_date, s.visit_name,
    s.sc_id_check, sr.revise_pid,sr.revise_acid,sr.revise_uic, r.form_remark
    FROM p_surveygizmo_form_done_trc as s
    left join p_surveygizmo_form_done_trc_revise as sr ON (s.pid=sr.pid and s.acid=sr.acid and s.uic=sr.uic and s.visit_date=sr.visit_date)
    left join p_surveygizmo_form_done_trc_remark as r ON (s.pid=r.pid and s.acid=r.acid and s.uic=r.uic and s.visit_date=r.visit_date)


    WHERE s.visit_date >= '$date_beg' AND s.visit_date <= '$date_end' $query_add
    ORDER BY s.visit_date desc , s.uic;
           ";
    //echo " $query";
           $stmt = $mysqli->prepare($query);
           if($stmt->execute()){
             $stmt->bind_result($form_id, $pid,$acid,$uic, $clinic_id, $visit_date, $submit_date, $visit_name, $sc_id_check,
              $r_pid,$r_acid,$r_uic, $form_remark);

             while ($stmt->fetch()) {
               if(!isset($arr_obj["$visit_date-$uic"])){
                 $arr_obj["$visit_date-$uic"] = array();

                 if($staff_clinic_id == "%")
              //   $arr_obj["$visit_date-$uic"]["is_chk"] = ($sc_id_check != "")?"1":"0";
                 $arr_obj["$visit_date-$uic"]["site"] = $clinic_id;

                 $arr_obj["$visit_date-$uic"]["pid"] = $pid;
                 $arr_obj["$visit_date-$uic"]["acid"] = $acid;
                 $arr_obj["$visit_date-$uic"]["uic"] = $uic;

                 $arr_obj["$visit_date-$uic"]["r_pid"] = ($r_pid !== NULL)?$r_pid:"";
                 $arr_obj["$visit_date-$uic"]["r_acid"] = ($r_acid !== NULL)?$r_acid:"";
                 $arr_obj["$visit_date-$uic"]["r_uic"] = ($r_uic !== NULL)?$r_uic:"";

                 $arr_obj["$visit_date-$uic"]["visit_date"] = $visit_date;
                 $arr_obj["$visit_date-$uic"]["remark"] = ($form_remark !== NULL)?$form_remark:"";
                 $arr_obj["$visit_date-$uic"]["f1"] = ""; // prep intake
                 $arr_obj["$visit_date-$uic"]["f2"] = ""; // prep fu
                 $arr_obj["$visit_date-$uic"]["f3"] = ""; // risk behavior
                 $arr_obj["$visit_date-$uic"]["f4"] = ""; // assist

                 // form check by
                 $arr_obj["$visit_date-$uic"]["fc1"] = ""; // prep intake
                 $arr_obj["$visit_date-$uic"]["fc2"] = ""; // prep fu
                 $arr_obj["$visit_date-$uic"]["fc3"] = ""; // risk behavior
                 $arr_obj["$visit_date-$uic"]["fc4"] = ""; // assist
               }
              // $arr_obj["$visit_date-$uic"]["f$form_id"] = "$visit_name";
               $arr_obj["$visit_date-$uic"]["f$form_id"] = "$visit_name (".(new DateTime($submit_date))->format('d/m/y H:i').")";
               $arr_obj["$visit_date-$uic"]["fc$form_id"] = "$sc_id_check";

             }// while
           }
           else{
             $msg_error .= $stmt->error;
           }
        $stmt->close();

        $arr_list = array();
        foreach($arr_obj as $obj_index => $obj_data){
          //echo "<br>uic " .$uid_obj["pid"]." | ".$uid_obj["uic"];
          $arr_list[] = $obj_data;
        }
        $rtn['datalist'] = $arr_list;


  }// select_list

  else if($u_mode == "check_formdone"){ // check_formdone
    $pid = isset($_POST["pid"])?$_POST["pid"]:"";
    $acid = isset($_POST["acid"])?$_POST["acid"]:"";
    $uic = isset($_POST["uic"])?$_POST["uic"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";

    $is_revise_id = isset($_POST["is_revise_id"])?$_POST["is_revise_id"]:"0";
    $r_pid = isset($_POST["r_pid"])?$_POST["r_pid"]:"";
    $r_acid = isset($_POST["r_acid"])?$_POST["r_acid"]:"";
    $r_uic = isset($_POST["r_uic"])?$_POST["r_uic"]:"";



    $clinic_id = $_SESSION['weclinic_id'];
/*
    $query = "UPDATE p_surveygizmo_form_done_trc SET
    sc_id_check=?, check_date=now()
    WHERE pid=? AND acid=? AND uic=? AND visit_date=? AND sc_id_check=''
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssss',$revise_id, $sc_id,$pid, $acid,$uic, $visit_date);
    if($stmt->execute()){
        $msg_info .= "Update Form Check Visit Date : $visit_date [$pid $acid $uic] successfully.";
        //setLogNote($sc_id, "Update Form Check Visit Date : $visit_date [$uic]");
    }
    else{
        $msg_error .= $stmt->error;
    }
    $stmt->close();
*/
    if($is_revise_id){

        $today_date = new DateTime();
        $query = "INSERT INTO p_surveygizmo_form_done_trc_revise
        (revise_id, pid, acid, uic, visit_date,revise_pid, revise_acid,revise_uic, submit_date, sc_id_submit)
         SELECT @keyid := CONCAT('".$today_date->format("y")."',
        LPAD( (SUBSTRING(  IF(MAX(revise_id) IS NULL,0,MAX(revise_id))   ,3,6)*1)+1, '6','0') )";
        $query.= ",?,?,?,?,?,?,?,now(),?  FROM p_surveygizmo_form_done_trc_revise WHERE SUBSTRING(revise_id,1,2) = '".$today_date->format("y")."';";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssss',$pid, $acid,$uic, $visit_date, $r_pid, $r_acid, $r_uic, $sc_id);
        if($stmt->execute()){
            $msg_info .= "ส่งการแก้ไข ID สำเร็จ";
            $inQuery = "SELECT @keyid;";
            $stmt = $mysqli->prepare($inQuery.";");
            $stmt->bind_result($revise_id);
            if($stmt->execute()){ // get pid
              if($stmt->fetch()){
                $rtn['revise_id'] = $revise_id;
              }
            }

        }
        else{
            $msg_error .= $stmt->error;
        }
        $stmt->close();


    }

    $query_add = (isset($revise_id))?" , revise_id='$revise_id' ": "";

    $query = "UPDATE p_surveygizmo_form_done_trc SET
    sc_id_check=?, check_date=now() $query_add
    WHERE pid=? AND acid=? AND uic=? AND visit_date=? AND sc_id_check=''
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sssss',$sc_id,$pid, $acid,$uic, $visit_date);
    if($stmt->execute()){
        $msg_info .= "Update Form Check Visit Date : $visit_date [$pid/$acid/$uic] successfully.";
        //setLogNote($sc_id, "Update Form Check Visit Date : $visit_date [$uic]");
    }
    else{
        $msg_error .= $stmt->error;
    }
    $stmt->close();


  }//check_formdone


  else if($u_mode == "submit_revise_id"){ // submit_revise_id
    $pid = isset($_POST["pid"])?$_POST["pid"]:"";
    $acid = isset($_POST["acid"])?$_POST["acid"]:"";
    $uic = isset($_POST["uic"])?$_POST["uic"]:"";

    $r_pid = isset($_POST["r_pid"])?$_POST["r_pid"]:"";
    $r_acid = isset($_POST["r_acid"])?$_POST["r_acid"]:"";
    $r_uic = isset($_POST["r_uic"])?$_POST["r_uic"]:"";

    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";

    $clinic_id = $_SESSION['weclinic_id'];
/*
        $query = "INSERT INTO p_surveygizmo_form_done_trc_revise
        (pid, acid, uic, visit_date,revise_pid, revise_acid,revise_uic, submit_date, sc_id_submit)
        VALUES(?,?,?,?,?,?,?,now(),?)
        ";
*/
        $query = "INSERT INTO p_surveygizmo_form_done_trc_revise
        (revise_id, pid, acid, uic, visit_date,revise_pid, revise_acid,revise_uic, submit_date, sc_id_submit)
         SELECT @keyid := CONCAT('".$today_date->format("y")."',
        LPAD( (SUBSTRING(  IF(MAX(revise_id) IS NULL,0,MAX(revise_id))   ,3,6)*1)+1, '6','0') )";
        $query.= ",?,?,?,?,?,?,?,now(),?  FROM p_surveygizmo_form_done_trc_revise WHERE SUBSTRING(revise_id,1,2) = '".$today_date->format("y")."';";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssss',$pid, $acid,$uic, $visit_date, $r_pid, $r_acid, $r_uic, $sc_id);
        if($stmt->execute()){
            $msg_info .= "ส่งการแก้ไข ID สำเร็จ";
            $inQuery = "SELECT @keyid;";
            $stmt = $mysqli->prepare($inQuery.";");
            $stmt->bind_result($revise_id);
            if($stmt->execute()){ // get pid
              if($stmt->fetch()){
                $rtn['revise_id'] = $revise_id;
              }
            }

        }
        else{
            $msg_error .= $stmt->error;
        }
        $stmt->close();

        $query_add = (isset($revise_id))?" , revise_id='$revise_id' ": "";

        $query = "UPDATE p_surveygizmo_form_done_trc SET
        sc_id_check=?, check_date=now() $query_add
        WHERE pid=? AND acid=? AND uic=? AND visit_date=? AND sc_id_check=''
        ";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssss',$sc_id,$pid, $acid,$uic, $visit_date);
        if($stmt->execute()){
            $msg_info .= "Update Form Check Visit Date : $visit_date [$pid/$acid/$uic] successfully.";
            //setLogNote($sc_id, "Update Form Check Visit Date : $visit_date [$uic]");
        }
        else{
            $msg_error .= $stmt->error;
        }
        $stmt->close();


  }//submit_revise_id

  else if($u_mode == "select_revise_list"){ // select_revise_list completed survey gizmo
    $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
    $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
    $sel_opt = isset($_POST["sel_opt"])?urldecode($_POST["sel_opt"]):"%";


    $date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:(new DateTime())->format('Y-m-d');
    $date_end = isset($_POST["date_end"])?$_POST["date_end"]:(new DateTime())->format('Y-m-d');

    $staff_clinic_id = $_SESSION['weclinic_id'];

    $query_date_range = " s.visit_date >= '$date_beg' AND s.visit_date <= '$date_end' ";

    $query_add = " ";
    if($txt_search != ""){
      $query_add .= " AND (s.pid like '$txt_search' OR s.acid like '$txt_search' OR s.uic like '$txt_search') ";
    }

    if($sel_opt != "all"){
      if($sel_opt == "0"){ // ยังไม่ตรวจ
        $query_add .= " AND s.sc_id_revise = '' ";
      }
      else if($sel_opt == "1"){ // ตรวจแล้ว
        $query_add .= " AND s.sc_id_revise <> '' ";
      }
    }

    $arr_list = array();

    $query = "SELECT s.form_id, f.form_name, s.pid,s.acid,s.uic, s.trc_site, s.visit_date, s.submit_date, s.visit_name,
    s.sc_id_check, st.s_name,  sr.revise_pid,sr.revise_acid,sr.revise_uic, s.revise_date, s.sc_id_revise , st2.s_name
    FROM p_surveygizmo_form as f , p_staff as st, p_staff_clinic as sc ,
    p_surveygizmo_form_done_trc_revise as sr,
    p_surveygizmo_form_done_trc as s
    LEFT JOIN p_staff_clinic as sc2
        LEFT JOIN p_staff as st2 ON(sc2.s_id=st2.s_id)
    ON (s.sc_id_revise=sc2.sc_id)

    WHERE $query_date_range AND s.revise_id = sr.revise_id AND f.form_id = s.form_id  AND
    s.sc_id_check = sc.sc_id AND sc.s_id=st.s_id $query_add
    ORDER BY s.visit_date desc , s.uic, s.form_id
           ";
    //echo "-- $query";
           $stmt = $mysqli->prepare($query);
           if($stmt->execute()){
             $stmt->bind_result($form_id, $form_name, $pid,$acid,$uic,$clinic_id, $visit_date, $submit_date, $visit_name, $sc_id_check,
             $submit_name, $r_pid,$r_acid,$r_uic, $check_date, $check_staff_id, $check_staff_name );
             while ($stmt->fetch()) {
               $arr_obj = array();
               $arr_obj["site"] = $clinic_id;
               $arr_obj["visit_date"] = $visit_date;
               $arr_obj["form"] = $form_name;
               $arr_obj["form_id"] = $form_id;
               $arr_obj["pid"] = $pid;
               $arr_obj["acid"] = $acid;
               $arr_obj["uic"] = $uic;
               $arr_obj["r_pid"] = $r_pid;
               $arr_obj["r_acid"] = $r_acid;
               $arr_obj["r_uic"] = $r_uic;
               $arr_obj["visit_name"] = $visit_name;
               $arr_obj["submit_date"] = (new DateTime($submit_date))->format('d/m/y H:i') ;
               $arr_obj["submit_name"] = $submit_name;
               $arr_obj["check_date"] = ($check_date !== NULL)?(new DateTime($check_date))->format('d/m/y H:i'):"" ;
               $arr_obj["check_name"] = ($check_staff_name !== NULL)?$check_staff_name:"";

               $arr_list[] = $arr_obj;
             }// while
           }
           else{
             $msg_error .= $stmt->error;
           }
        $stmt->close();
        $rtn['datalist'] = $arr_list;
  }// select_revise_list

    else if($u_mode == "check_revise_id"){ // check_revise_id
      $pid = isset($_POST["pid"])?$_POST["pid"]:"";
      $acid = isset($_POST["acid"])?$_POST["acid"]:"";
      $uic = isset($_POST["uic"])?$_POST["uic"]:"";

      $r_pid = isset($_POST["r_pid"])?$_POST["r_pid"]:"";
      $r_acid = isset($_POST["r_acid"])?$_POST["r_acid"]:"";
      $r_uic = isset($_POST["r_uic"])?$_POST["r_uic"]:"";
      $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
      $form_id = isset($_POST["form_id"])?$_POST["form_id"]:"";


        $clinic_id = $_SESSION['weclinic_id'];

        //ihri staff update check revised uic
        $query = "UPDATE p_surveygizmo_form_done_trc_revise SET
        check_staff_id=?, check_date=now()
        WHERE pid=? AND acid=? AND uic=? AND visit_date=?
        ";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssss',$sc_id,$pid, $acid, $uic, $visit_date);
        if($stmt->execute()){
            $msg_info .= "Check Revised ID successfully.";
            //setLogNote($sc_id, "Update Form Check Visit Date : $visit_date [$uic]");
        }
        else{
            $msg_error .= $stmt->error;
        }
        $stmt->close();

        $query_update = "";
        if($r_pid != "") $query_update .= " pid='$r_pid',";
        if($r_acid != "") $query_update .= " acid='$r_acid',";
        if($r_uic != "") $query_update .= " uic='$r_uic',";

        $query_update = substr($query_update,0,strlen($query_update)-1) ;

        // update invalid uic to valid uic in cbo site
        $query = "UPDATE p_surveygizmo_form_done_trc SET
        sc_id_revise=?, revise_date=now(), $query_update
        WHERE pid=? AND acid=? AND uic=? AND visit_date=? AND form_id=?
        ";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssss',$sc_id, $pid, $acid, $uic, $visit_date, $form_id);
        if($stmt->execute()){
            $msg_info .= "Check Revised ID : $visit_date [PID: $pid /ACID: $acid /UIC: $uic] successfully.";
            //setLogNote($sc_id, "Update Form Check Visit Date : $visit_date [$uic]");
        }
        else{
            $msg_error .= $stmt->error;
        }
        $stmt->close();


        // update invalid id to valid id in trc remark
        $query = "UPDATE p_surveygizmo_form_done_trc_remark SET
        $query_update
        WHERE pid=? AND acid=? AND uic=? AND visit_date=?
        ";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssss',$pid, $acid, $uic, $visit_date);
        if($stmt->execute()){
            $msg_info .= "Check Revised ID : $visit_date [PID: $pid /ACID: $acid /UIC: $uic] successfully.";
            //setLogNote($sc_id, "Update Form Check Visit Date : $visit_date [$uic]");
        }
        else{
            $msg_error .= $stmt->error;
        }
        $stmt->close();



      }//check_revise_id



        else if($u_mode == "select_remark_list"){ // select_remark_list completed survey gizmo
          $txt_search = isset($_POST["txt_search"])?urldecode($_POST["txt_search"]):"";
          $txt_search = (strpos($txt_search, '*') !== false) ?str_replace("*","%",$txt_search):"%".$txt_search."%";
          $sel_opt = isset($_POST["sel_opt"])?urldecode($_POST["sel_opt"]):"%";


          $date_beg = isset($_POST["date_beg"])?$_POST["date_beg"]:(new DateTime())->format('Y-m-d');
          $date_end = isset($_POST["date_end"])?$_POST["date_end"]:(new DateTime())->format('Y-m-d');

          $staff_clinic_id = $_SESSION['weclinic_id'];

          $query_date_range = " s.visit_date >= '$date_beg' AND s.visit_date <= '$date_end' ";

          $query_add = " ";
          if($txt_search != ""){
            $query_add .= " AND sr.uic like '$txt_search' ";
          }

          if($sel_opt != "all"){
            if($sel_opt == "0"){ // ยังไม่ตรวจ
              $query_add .= " AND sr.check_staff_id = '' ";
            }
            else if($sel_opt == "1"){ // ตรวจแล้ว
              $query_add .= " AND sr.check_staff_id <> '' ";
            }
          }


          $arr_obj = array();
          $query = "SELECT s.form_id, s.pid, s.acid, s.uic, s.trc_site, s.visit_date, sr.submit_date, s.visit_name,
          sr.sc_id_submit, st.s_name,  sr.form_remark, sr.check_date, sr.check_staff_id , st2.s_name
          FROM p_staff as st, p_staff_clinic as sc ,
          p_surveygizmo_form_done_trc as s,
          p_surveygizmo_form_done_trc_remark as sr
          LEFT JOIN p_staff_clinic as sc2
              LEFT JOIN p_staff as st2 ON(sc2.s_id=st2.s_id)
          ON (sr.check_staff_id=sc2.sc_id)

          WHERE $query_date_range
          AND s.pid = sr.pid AND s.acid = sr.acid AND s.uic = sr.uic
          AND s.visit_date = sr.visit_date AND
          sr.sc_id_submit = sc.sc_id AND sc.s_id=st.s_id $query_add
          ORDER BY s.submit_date desc , s.visit_date, s.uic
                 ";
          //echo " $query";
                 $stmt = $mysqli->prepare($query);
                 if($stmt->execute()){
                   $stmt->bind_result($form_id, $pid,$acid,$uic,$clinic_id, $visit_date, $submit_date, $visit_name, $sc_id_check,
                   $submit_name, $remark, $check_date, $check_staff_id, $check_staff_name );
                   while ($stmt->fetch()) {
                     if(!isset($arr_obj["$visit_date-$uic"])){
                       $arr_obj["$visit_date-$uic"] = array();

                       if($staff_clinic_id == "%")
                       $arr_obj["$visit_date-$uic"]["site"] = $clinic_id;

                       $arr_obj["$visit_date-$uic"]["pid"] = $pid;
                       $arr_obj["$visit_date-$uic"]["acid"] = $acid;
                       $arr_obj["$visit_date-$uic"]["uic"] = $uic;
                       $arr_obj["$visit_date-$uic"]["remark"] = ($remark !== NULL)?$remark:"";
                       $arr_obj["$visit_date-$uic"]["visit_date"] = $visit_date;
                       $arr_obj["$visit_date-$uic"]["submit_date"] = (new DateTime($submit_date))->format('d/m/y H:i');
                       $arr_obj["$visit_date-$uic"]["submit_name"] = $submit_name;
                       $arr_obj["$visit_date-$uic"]["check_date"] = ($check_date !== NULL)?(new DateTime($check_date))->format('d/m/y H:i'):"" ;
                       $arr_obj["$visit_date-$uic"]["check_name"] = ($check_staff_name !== NULL)?$check_staff_name:"";
                       $arr_obj["$visit_date-$uic"]["f1"] = ""; // prep intake
                       $arr_obj["$visit_date-$uic"]["f2"] = ""; // prep fu
                       $arr_obj["$visit_date-$uic"]["f3"] = ""; // risk behavior
                       $arr_obj["$visit_date-$uic"]["f4"] = ""; // assist

                       // form check by
                       $arr_obj["$visit_date-$uic"]["fc1"] = ""; // prep intake
                       $arr_obj["$visit_date-$uic"]["fc2"] = ""; // prep fu
                       $arr_obj["$visit_date-$uic"]["fc3"] = ""; // risk behavior
                       $arr_obj["$visit_date-$uic"]["fc4"] = ""; // risk behavior
                     }
                    // $arr_obj["$visit_date-$uic"]["f$form_id"] = "$visit_name";
                     $arr_obj["$visit_date-$uic"]["f$form_id"] = "$visit_name";
                     $arr_obj["$visit_date-$uic"]["fc$form_id"] = "$sc_id_check";

                   }// while
                 }
                 else{
                   $msg_error .= $stmt->error;
                 }
              $stmt->close();

              $arr_list = array();
              foreach($arr_obj as $obj_index => $obj_data){
                //echo "<br>uic " .$uid_obj["pid"]." | ".$uid_obj["uic"];
                $arr_list[] = $obj_data;
              }
              $rtn['datalist'] = $arr_list;


        }// select_remark_list



      else if($u_mode == "update_form_remark"){ // update_form_remark
          $pid = isset($_POST["pid"])?$_POST["pid"]:"";
          $acid = isset($_POST["acid"])?$_POST["acid"]:"";
          $uic = isset($_POST["uic"])?$_POST["uic"]:"";
          $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
          $remark = isset($_POST["remark"])?$_POST["remark"]:"";
          $clinic_id = $_SESSION['weclinic_id'];

          $query = "REPLACE INTO p_surveygizmo_form_done_trc_remark
          (pid, acid, uic, visit_date, form_remark, submit_date, sc_id_submit )
          VALUES(?,?,?,?,?,now(),?)
          ";

          $stmt = $mysqli->prepare($query);
          $stmt->bind_param('ssssss',$pid,$acid,$uic, $visit_date, $remark, $sc_id);
          if($stmt->execute()){
              $msg_info .= "บันทึกแก้ไข Note: $pid $acid $uic / $visit_date สำเร็จ";
          }
          else{
              $msg_error .= $stmt->error;
          }
          $stmt->close();
        }//update_form_remark


        else if($u_mode == "check_revise_remark"){ // check_revise_uic
                $pid = isset($_POST["pid"])?$_POST["pid"]:"";
                $acid = isset($_POST["acid"])?$_POST["acid"]:"";
                $uic = isset($_POST["uic"])?$_POST["uic"]:"";
                $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
                $clinic_id = $_SESSION['weclinic_id'];

                //ihri staff update check revised uic
                $query = "UPDATE p_surveygizmo_form_done_trc_remark SET
                check_date=now(), check_staff_id=?
                WHERE pid=? AND acid=? AND uic=? AND visit_date=?
                ";

                $stmt = $mysqli->prepare($query);
                $stmt->bind_param('sssss',$sc_id, $pid,$acid,$uic, $visit_date);
                if($stmt->execute()){
                    $msg_info .= "Check Revised Note: $pid, $acid, $uic/$visit_date successfully.";
                    //setLogNote($sc_id, "Update Form Check Visit Date : $visit_date [$uic]");
                }
                else{
                    $msg_error .= $stmt->error;
                }
                $stmt->close();

      }//check_revise_remark



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
