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
      $query_add .= " AND s.uic like '$txt_search' ";
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
    s.sc_id_check, sr.revise_pid,sr.revise_acid,sr.revise_uic
    FROM p_surveygizmo_form_done_trc as s
    left join p_surveygizmo_form_done_trc_revise as sr ON (s.pid=sr.pid and s.acid=sr.acid and s.uic=sr.uic and s.visit_date=sr.visit_date)
    WHERE s.visit_date >= '$date_beg' AND s.visit_date <= '$date_end' $query_add
    ORDER BY s.visit_date desc , s.uic;
           ";
    //echo " $query";
           $stmt = $mysqli->prepare($query);
           if($stmt->execute()){
             $stmt->bind_result($form_id, $pid,$acid,$uic, $clinic_id, $visit_date, $submit_date, $visit_name, $sc_id_check,
              $r_pid,$r_acid,$r_uic);

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

    $query = "UPDATE p_surveygizmo_form_done_trc SET
    sc_id_check=?, check_date=now()
    WHERE pid=? AND acid=? AND uic=? AND visit_date=? AND sc_id_check=''
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sssss',$sc_id,$pid, $acid,$uic, $visit_date);
    if($stmt->execute()){
        $msg_info .= "Update Form Check Visit Date : $visit_date [$pid] successfully.";
        //setLogNote($sc_id, "Update Form Check Visit Date : $visit_date [$uic]");
    }
    else{
        $msg_error .= $stmt->error;
    }
    $stmt->close();

    if($is_revise_id){

              $query = "INSERT INTO p_surveygizmo_form_done_trc_revise
              (pid, acid, uic, visit_date,revise_pid, revise_acid,revise_uic, submit_date, sc_id_submit)
              VALUES(?,?,?,?,?,?,?,now(),?)
              ";

              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('ssssssss',$pid, $acid,$uic, $visit_date, $r_pid, $r_acid, $r_uic, $sc_id);
              if($stmt->execute()){
                  $msg_info .= "/ ส่งการแก้ไข ID สำเร็จ";
              }
              else{
                  $msg_error .= $stmt->error;
              }
              $stmt->close();
    }


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

        $query = "INSERT INTO p_surveygizmo_form_done_trc_revise
        (pid, acid, uic, visit_date,revise_pid, revise_acid,revise_uic, submit_date, sc_id_submit)
        VALUES(?,?,?,?,?,?,?,now(),?)
        ";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssss',$pid, $acid,$uic, $visit_date, $r_pid, $r_acid, $r_uic, $sc_id);
        if($stmt->execute()){
            $msg_info .= "ส่งการแก้ไข ID สำเร็จ";
        }
        else{
            $msg_error .= $stmt->error;
        }
        $stmt->close();
    }//submit_revise_id

    else if($u_mode == "check_revise_id"){ // check_revise_id
      $pid = isset($_POST["pid"])?$_POST["pid"]:"";
      $acid = isset($_POST["acid"])?$_POST["acid"]:"";
      $uic = isset($_POST["uic"])?$_POST["uic"]:"";

      $r_pid = isset($_POST["r_pid"])?$_POST["r_pid"]:"";
      $r_acid = isset($_POST["r_acid"])?$_POST["r_acid"]:"";
      $r_uic = isset($_POST["r_uic"])?$_POST["r_uic"]:"";
      $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";


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
