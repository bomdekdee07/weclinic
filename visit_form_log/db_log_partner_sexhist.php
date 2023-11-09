<?
// Partner sex history db
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


    if($u_mode == "select_list"){ // select all sex history
      $uid = isset($_POST["uid"])?$_POST["uid"]:"";
      $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
      $seq_no = isset($_POST["seq_no"])?$_POST["seq_no"]:"";


      $arr_data_list = array() ;

             $query = "SELECT
             uid, collect_date, collect_time, seq_no, sexhist_no, sexhist_date, sexhist_place, sexhist_alc, sexhist_druguse,
sexhist_drug_eat, sexhist_drug_smoke, sexhist_drug_smell,
sexhist_drug_inject, sexhist_drug_oth, sexhist_drug_oth_spec, sexhist_needleshare,
anal_insert, anal_insert_condom, anal_insert_nocondom, anal_recep, anal_recep_condom, anal_recep_nocondom,
oral_insert, oral_insert_condom, oral_insert_nocondom, oral_recep, oral_recep_condom, oral_recep_nocondom,
vagina_insert, vagina_insert_condom, vagina_insert_nocondom, vagina_recep, vagina_recep_condom, vagina_recep_nocondom,
neovagina_insert, neovagina_insert_condom, neovagina_insert_nocondom, neovagina_recep, neovagina_recep_condom, neovagina_recep_nocondom,
sexhist_risk_evaluate, sexhist_note
             FROM x_partner_sexhist
             WHERE uid=? AND collect_date=? AND seq_no=?
             ORDER BY sexhist_no, sexhist_date  DESC
             ";

//echo "$uid, $collect_date, $seq_no /$query";

             $stmt = $mysqli->prepare($query);
             $stmt->bind_param('sss',$uid, $collect_date, $seq_no);
             if($stmt->execute()){
               $stmt->bind_result(
                 $uid, $collect_date, $collect_time, $seq_no, $sexhist_no, $sexhist_date, $sexhist_place, $sexhist_alc, $sexhist_druguse,
$sexhist_drug_eat, $sexhist_drug_smoke, $sexhist_drug_smell,
$sexhist_drug_inject, $sexhist_drug_oth, $sexhist_drug_oth_spec, $sexhist_needleshare,
$anal_insert, $anal_insert_condom, $anal_insert_nocondom, $anal_recep, $anal_recep_condom, $anal_recep_nocondom,
$oral_insert, $oral_insert_condom, $oral_insert_nocondom, $oral_recep, $oral_recep_condom, $oral_recep_nocondom,
$vagina_insert, $vagina_insert_condom, $vagina_insert_nocondom, $vagina_recep, $vagina_recep_condom, $vagina_recep_nocondom,
$neovagina_insert, $neovagina_insert_condom, $neovagina_insert_nocondom, $neovagina_recep, $neovagina_recep_condom, $neovagina_recep_nocondom,
$sexhist_risk_evaluate, $sexhist_note
               );

               while ($stmt->fetch()) {

                 $arr_data = array();
                 $arr_data["sexhist_no"]="$sexhist_no";
                 $arr_data["date"]="$sexhist_date";
                 $arr_data["place"]="$sexhist_place";
                 $arr_data["alc"]="$sexhist_alc";

                 $arr_data["druguse"]="$sexhist_druguse";
                 $arr_data["drug_eat"]="$sexhist_drug_eat";
                 $arr_data["drug_smoke"]="$sexhist_drug_smoke";
                 $arr_data["drug_smell"]="$sexhist_drug_smell";
                 $arr_data["drug_inject"]="$sexhist_drug_inject";
                 $arr_data["drug_oth"]="$sexhist_drug_oth";
                 $arr_data["drug_oth_spec"]="$sexhist_drug_oth_spec";
                 $arr_data["drug_needleshare"]="$sexhist_needleshare";

                 $arr_data["anal_insert"]="$anal_insert";
                 $arr_data["anal_insert_condom"]="$anal_insert_condom";
                 $arr_data["anal_insert_nocondom"]="$anal_insert_nocondom";
                 $arr_data["anal_recep"]="$anal_recep";
                 $arr_data["anal_recep_condom"]="$anal_recep_condom";
                 $arr_data["anal_recep_nocondom"]="$anal_recep_nocondom";

                 $arr_data["oral_insert"]="$oral_insert";
                 $arr_data["oral_insert_condom"]="$oral_insert_condom";
                 $arr_data["oral_insert_nocondom"]="$oral_insert_nocondom";
                 $arr_data["oral_recep"]="$oral_recep";
                 $arr_data["oral_recep_condom"]="$oral_recep_condom";
                 $arr_data["oral_recep_nocondom"]="$oral_recep_nocondom";

                 $arr_data["vagina_insert"]="$vagina_insert";
                 $arr_data["vagina_insert_condom"]="$vagina_insert_condom";
                 $arr_data["vagina_insert_nocondom"]="$vagina_insert_nocondom";
                 $arr_data["vagina_recep"]="$vagina_recep";
                 $arr_data["vagina_recep_condom"]="$vagina_recep_condom";
                 $arr_data["vagina_recep_nocondom"]="$vagina_recep_nocondom";

                 $arr_data["neovagina_insert"]="$neovagina_insert";
                 $arr_data["neovagina_insert_condom"]="$neovagina_insert_condom";
                 $arr_data["neovagina_insert_nocondom"]="$neovagina_insert_nocondom";
                 $arr_data["neovagina_recep"]="$neovagina_recep";
                 $arr_data["neovagina_recep_condom"]="$neovagina_recep_condom";
                 $arr_data["neovagina_recep_nocondom"]="$neovagina_recep_nocondom";

                 $arr_data["risk_evaluate"]= $sexhist_risk_evaluate;
                 $arr_data["note"]= $sexhist_note;

                 $arr_data_list[]=$arr_data;
               }// while

             }
             else{
               $msg_error .= $stmt->error;
             }
             $stmt->close();

             $rtn['datalist'] = $arr_data_list;

    }// select_list
    else if($u_mode == "update_seq_no_sexhist"){ // update_seq_no_sexhist

          $uid = isset($_POST["uid"])?$_POST["uid"]:"";
          $collect_date = isset($_POST["collect_date"])?$_POST["collect_date"]:"";
          $seq_no = isset($_POST["seq_no"])?$_POST["seq_no"]:"";
          $lst_data = isset($_POST["lst_data"])?$_POST["lst_data"]:[];

          foreach($lst_data as $item) {
            $sexhist_no = $item['sh_no']; // sex history id
            $sexhist_seq_no = $item['sh_seq_no']; // seq no show in program

            $query = "UPDATE x_partner_sexhist SET sexhist_seq_no=?
            WHERE uid=? AND collect_date=? AND seq_no=? AND
            sexhist_no=? ";
//echo "$sexhist_seq_no, $uid, $collect_date, $seq_no, $sexhist_no / $query";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sssss',$sexhist_seq_no, $uid, $collect_date, $seq_no, $sexhist_no);
            if($stmt->execute()){

            }
            else{
              $msg_error .= $stmt->error;
            }
            $stmt->close();
          } // foreach



        }// // update_seq_no_sexhist


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
