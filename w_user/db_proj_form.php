<?
// project form
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
  include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
  include_once("../function/in_fn_link.php");
  include_once("../function/in_fn_number.php");

  if($u_mode == "sel_proj_visit_form"){ // select form to visit
    $uid = isset($_POST["uid"])?$_POST["uid"]:"";
    $visit_id = isset($_POST["visit_id"])?$_POST["visit_id"]:"";
    $visit_date = isset($_POST["visit_date"])?$_POST["visit_date"]:"";
    $proj_id = isset($_POST["proj_id"])?$_POST["proj_id"]:"";
    $group_id = isset($_POST["group_id"])?$_POST["group_id"]:"";
/*
         $query = "SELECT f.form_id, f.form_name, vfd.collect_date, f.open_link
         FROM p_form as f, p_visit_form as vf
         LEFT JOIN p_visit_form_done as vfd ON

         (vf.visit_id=vfd.visit_id AND vf.proj_id=vfd.proj_id
          AND vf.form_id=vfd.form_id AND vfd.uid=? AND vfd.collect_date=? and vfd.group_id=vf.group_id)

         WHERE f.form_id=vf.form_id AND vf.proj_id=? AND vf.visit_id = ?
         AND (vf.group_id=? OR vf.group_id='')
         ORDER BY vf.form_seq
         ";
*/

/*
         $query = "SELECT distinct f.form_id, f.form_name, vfd.collect_date, f.open_link
         FROM p_form as f, p_visit_form as vf
         LEFT JOIN p_visit_form_done as vfd ON

         (vf.visit_id=vfd.visit_id AND vf.proj_id=vfd.proj_id
          AND vf.form_id=vfd.form_id AND vfd.uid=? AND vfd.collect_date=?)

         WHERE f.form_id=vf.form_id AND vf.proj_id=? AND vf.visit_id = ?
         AND (vf.group_id=? OR vf.group_id='')
         ORDER BY vf.form_seq
         ";
*/

         $query = "SELECT distinct fv.form_version_name, f.form_id, f.form_name, vfd.collect_date, f.open_link
         FROM p_form as f, p_form_version as fv, p_visit_form as vf
         LEFT JOIN p_visit_form_done as vfd ON

         (vf.visit_id=vfd.visit_id AND vf.proj_id=vfd.proj_id
          AND vf.form_id=vfd.form_id AND vfd.uid=? AND vfd.collect_date=?)

         WHERE f.form_id=vf.form_id AND f.form_version_id= fv.form_version_id
         AND vf.proj_id=? AND vf.visit_id = ?
         AND (vf.group_id=? OR vf.group_id='')
         AND fv.form_start_date <=? AND fv.form_stop_date >=?
         ORDER BY vf.form_seq
         ";

  //echo "$uid,$visit_date, $proj_id, $visit_id, $group_id / $query" ;
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param('sssssss',$uid,$visit_date, $proj_id, $visit_id, $group_id,$visit_date,$visit_date);
         if($stmt->execute()){
           $stmt->bind_result($form_version, $form_id, $form_name, $collect_date, $open_link);
           $arr_list = array();
           while ($stmt->fetch()) {

             $arr_obj = array();
             $arr_obj["form_id"]=$form_id;
             $arr_obj["form_name"]="$form_name";
             $arr_obj["form_ver"]="$form_version";
             $arr_obj["form_done"]=($collect_date !== NULL)?"Y":"N";

             $arr_obj["open_link"]=$open_link;

             $arr_list[]=$arr_obj;

           }// while
           $rtn['datalist'] = $arr_list;
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();
  }// select_list

  $mysqli->close();
}// $flag_auth != 0




 // return object
 $rtn['mode'] = $u_mode;
 $rtn['msg_error'] = $msg_error;
 $rtn['msg_info'] = $msg_info;
 $rtn['flag_auth'] = $flag_auth;

 // change to javascript readable form
 $returnData = json_encode($rtn);
 echo $returnData;
