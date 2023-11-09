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

  if($u_mode == "schedule_data"){ // select schedule data
    $project_group_id = isset($_POST["project_group_id"])?$_POST["project_group_id"]:"";
    $query_add = "";

    if($staff_clinic_id != "%" ){
      $query_add .= " AND ul.clinic_id ='$staff_clinic_id' ";
    }

    $arr_demo = array();
    $query = "SELECT  u.uic2, uv.uid, uv.group_id, ul.pid, ul.enroll_date,
    d.gender, d.gender_text,
    d.sexatbirth, d.sexorient_male, d.sexorient_female,
    b.fname, b.sname, b.contact
    FROM
    p_project_uid_list as ul,
    uic_gen as u
    LEFT JOIN basic_reg as b ON (u.uic=b.uic)  ,

    p_project_uid_visit as uv
    LEFT JOIN x_q_demo as d ON (uv.uid=d.uid AND d.collect_date=uv.visit_date)

    WHERE ul.proj_id='POC' AND uv.visit_id = 'M0'
    AND ul.uid=u.uid
    AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id $query_add
    AND uv.group_id IN ($project_group_id)
    ORDER BY ul.pid
    ";

    //echo "$query<br>";

    $stmt = $mysqli->prepare($query);
    //$stmt->bind_param("s", $clinic_id);
    if($stmt->execute()){
     $stmt->bind_result($uic, $uid, $group_id, $pid, $enroll_date, $gender, $gender_other,
     $sexatbirth, $sexorient_male, $sexorient_female, $fname, $sname, $tel  );

     while ($stmt->fetch()) {
       if(!isset($arr_demo["$uid-$group_id"])){
          $arr_demo["$uid-$group_id"] = array();
          $arr_demo["$uid-$group_id"]["uid"]=$uid;
          $arr_demo["$uid-$group_id"]["uic"]=$uic;
          $arr_demo["$uid-$group_id"]["pid"]=$pid;
          $arr_demo["$uid-$group_id"]["enroll"]=$enroll_date;
          $arr_demo["$uid-$group_id"]["group_id"]=$group_id;

          if($gender !== NULL){
            if($gender == 1) {//male
              if($sexorient_male == "Y" && $sexatbirth==1) $gender = "MSM"; // ผู้ชาย
              else $gender = "M"; // ผู้ชาย
            }

            if($gender == 2) {//female
              if($sexorient_male == "Y" && $sexatbirth==1) $gender = "MSM"; // ผู้ชาย
              else $gender = "F"; // ผู้หญิง
            }

            else if($gender == 3) $gender = "TGM"; //ู้ชายข้ามเพศ/ ทอม (Transgender men)
            else if($gender == 4) $gender = "TGW"; //ผู้หญิงข้ามเพศ/ สาวประเภทสอง/ กะเทย (Transgender women)
            else if($gender == 5) $gender = "OTHER ($gender_other)"; //อื่นๆ

          }
          else{
            $gender = "";
          }
          $arr_demo["$uid-$group_id"]["gender"]=$gender;

          if($fname !== NULL){
            $arr_demo["$uid-$group_id"]["name"]="$fname $sname";
            $arr_demo["$uid-$group_id"]["tel"]=$tel;
          }
          else{
            $arr_demo["$uid-$group_id"]["name"]='<span class="text-danger">- รอกรอกข้อมูล -</span>';
            $arr_demo["$uid-$group_id"]["tel"]="";
          }



       }
     }//while

   }
   else{
    $msg_error .= $stmt->error;
   }

   $stmt->close();


   // create schedule visit_id
   $arr_visit_id = array();
   $arr_visit_id[] = "M0";
   $arr_visit_id[] = "M1";
   $arr_visit_id[] = "M3";
   $arr_visit_id[] = "M6";
   $arr_visit_id[] = "M9";
   $arr_visit_id[] = "M12";


     $query_add = "";

     if($staff_clinic_id != "%" ){
       //$sheet_title .= " | Clinic: $staff_clinic_id";
       $query_add .= " AND ul.clinic_id = '$staff_clinic_id' ";
     }
     $query_add .= " AND uv.group_id='$project_group_id' ";
     $arr_data_list = array();

             $query = "SELECT uv.uid, uv.group_id, uv.visit_id,uv.schedule_date,uv.schedule_note,
             uv.visit_date, uv.visit_status, gc.groupchange

             FROM p_project_uid_list as ul,
             p_project_uid_visit as uv LEFT JOIN x_groupchange as gc ON (uv.uid=gc.uid AND uv.visit_date=gc.collect_date)

             WHERE uv.proj_id='POC'
             AND uv.visit_id <> 'SCRN'
             AND ul.uid=uv.uid AND ul.proj_id=uv.proj_id AND ul.uid_status=1
             $query_add
             ORDER BY ul.pid, uv.schedule_date, uv.visit_date
             ";
   //echo "2 $query<br>";
            $stmt = $mysqli->prepare($query);
            //$stmt->bind_param("s", $clinic_id);
            if($stmt->execute()){

              $stmt->bind_result($uid, $group_id, $visit_id,$schedule_date,$schedule_note,
              $visit_date, $visit_status , $groupchange
              );

              $arr_uid = array();
              $cur_visit_id = "";
              $extra_amt = 0;
              $extra_change_group = "";
              while ($stmt->fetch()) {
                if(!isset($arr_uid["$uid-$group_id"])){
                   $arr_uid["$uid-$group_id"] = array();
                   $arr_uid["$uid-$group_id"]["uid"]=$arr_demo["$uid-$group_id"]["uid"];
                   $arr_uid["$uid-$group_id"]["uic"]=$arr_demo["$uid-$group_id"]["uic"];
                   $arr_uid["$uid-$group_id"]["gender"]=$arr_demo["$uid-$group_id"]["gender"];
                   $arr_uid["$uid-$group_id"]["name"]=$arr_demo["$uid-$group_id"]["name"];
                   $arr_uid["$uid-$group_id"]["tel"]=$arr_demo["$uid-$group_id"]["tel"];
   /*
                   $arr_uid["$uid-$group_id"]["uid"]=$uid;
                   $arr_uid["$uid-$group_id"]["uic"]=$uic;
   */
                   $arr_uid["$uid-$group_id"]["pid"]=$arr_demo["$uid-$group_id"]["pid"];
                   $arr_uid["$uid-$group_id"]["group"]=$arr_demo["$uid-$group_id"]["group_id"];
                   $arr_uid["$uid-$group_id"]["enroll"]=$arr_demo["$uid-$group_id"]["enroll"];
                   $arr_uid["$uid-$group_id"]["extra"]="";
                   $arr_uid["$uid-$group_id"]["extra_amt"] = "";
                   $extra_amt = 0;
                   $extra_change_group = "";
                }

                $visit_date = ($visit_date != "0000-00-00")?$visit_date:"";

                if($visit_id != "EX"){ // normal visit
                  if($groupchange !== NULL){
                    if($visit_status == 11) $groupchange = " ($groupchange)";
                    else $groupchange="";
                  }

                  $arr_uid["$uid-$group_id"][$visit_id."s_date"]=$schedule_date;
                  $arr_uid["$uid-$group_id"][$visit_id."v_date"]=$visit_date.$groupchange;
                  $arr_uid["$uid-$group_id"][$visit_id."st"]=$visit_status;
                  $arr_uid["$uid-$group_id"][$visit_id."s_note"]=$schedule_note;
                  $cur_visit_id = $visit_id;

                }
                else{ // extra visit
                  if($visit_status == 11) // change group in extra visit
                  $extra_change_group = "Y";
                  if($schedule_note != "") $schedule_note = "|Note: $schedule_note";

                  $arr_uid["$uid-$group_id"]["extra"].= "[$cur_visit_id|$visit_date $schedule_note] ";
                  $extra_amt += 1;

                  if($groupchange !== NULL) $groupchange = " ($groupchange)";
                  else $groupchange = "";

                  $arr_uid["$uid-$group_id"]["extra_amt"] = "$extra_amt $groupchange";
                  $arr_uid["$uid-$group_id"]["extra_chg"] = "$extra_change_group";
                }

              }// while
            }
            else{
              $msg_error .= $stmt->error;
            }

            $stmt->close();


            $dataList = array();


            foreach($arr_uid as $uid_index => $uid_obj){
              //echo "<br>uic " .$uid_obj["pid"]." | ".$uid_obj["uic"];
              $row_data = array();

              $row_data["pid"] = $uid_obj["pid"] ;
              $row_data["uic"] = $uid_obj["uic"]  ;
              $row_data["uid"] = $uid_obj["uid"]  ;
              $row_data["enroll"] = $uid_obj["enroll"]  ;
              $row_data["group"] = $uid_obj["group"]  ;
              $row_data["gender"] = $uid_obj["gender"]  ;
              $row_data["name"] = $uid_obj["name"]  ;
              $row_data["tel"] = $uid_obj["tel"]  ;
              $row_data["extra_amt"] = $uid_obj["extra_amt"]  ;

              $row_data["extra_chg"] = (isset($uid_obj["extra_chg"])?$uid_obj["extra_chg"]:"")  ; // extra change group  Y=yes


              foreach($arr_visit_id as $visit_id){ // each proj visit id
                //blue
                $row_data[$visit_id."s_date"] = isset($uid_obj[$visit_id."s_date"])?$uid_obj[$visit_id."s_date"]:"";

                // background color of visit_date
                if(isset($uid_obj[$visit_id."st"])){
/*
                   if($uid_obj[$visit_id."st"] == "0"){ // นัดหมาย white
                     $row_data[] = isset($uid_obj[$visit_id."v_date"])?$uid_obj[$visit_id."v_date"]:"";
                   }
                   else if($uid_obj[$visit_id."st"] == "1"){ // เสร็จสิ้น green
                     $row_data[] = isset($uid_obj[$visit_id."v_date"])?$uid_obj[$visit_id."v_date"]:"";
                   }
                   else if($uid_obj[$visit_id."st"] == "11"){ // change group (pink)
                     $row_data[] = isset($uid_obj[$visit_id."v_date"])?$uid_obj[$visit_id."v_date"]:"";
                     $row_data_format[] = $formatDataPink;
                   }
                   else if($uid_obj[$visit_id."st"] == "10"){ // lost to followup (purple)
                     $row_data[] = "ไม่มา Lost FU";
                     $row_data_format[] = $formatDataPurple;
                   }
                   else{ // other status  (visit pending) // yellow
                     $row_data[] = isset($uid_obj[$visit_id."v_date"])?$uid_obj[$visit_id."v_date"]:"";
                     $row_data_format[] = $formatDataYellow;
                   }
*/
                   $row_data[$visit_id."st"] = $uid_obj[$visit_id."st"];
                   $row_data[$visit_id."v_date"] = isset($uid_obj[$visit_id."v_date"])?$uid_obj[$visit_id."v_date"]:"";

                }
                else{
                  $row_data[$visit_id."st"] = "";
                  $row_data[$visit_id."v_date"] = "";
                }
                //lightblue
                $row_data[$visit_id."s_note"] = isset($uid_obj[$visit_id."s_note"])?$uid_obj[$visit_id."s_note"]:"";

              }// foreach

              $row_data["extra"] = $uid_obj["extra"]  ; // extra visit desc

              $dataList[] = $row_data;

            }// foreach




      $rtn['last_update'] = "Last Update: ".(new DateTime())->format('d M y H:i:s');;
      $rtn['datalist'] = $dataList;

  }// select_data_uid


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
