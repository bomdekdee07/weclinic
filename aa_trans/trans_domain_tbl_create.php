<?
// personal data db mgt
include_once("../in_db_conn.php");
include_once("../in_file_prop.php");
include_once("$ROOT_FILE_PATH/function/in_fn_date.php"); // date function
include_once("$ROOT_FILE_PATH/function/in_file_func.php"); // file function
//include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber
include_once("$ROOT_FILE_PATH/function/in_fn_link.php");
include_once("$ROOT_FILE_PATH/function/in_fn_number.php");

//$domain_id = "sti";
//$domain_id = "specimen_collect";
//$domain_id = "lab_result";
//$domain_id = "referral";
//$domain_id = "sex_intercourse";
//$domain_id = "poc_visit";
//$domain_id = "poc_extra_visit";
//$domain_id = "poc_screen";
//$domain_id = "poc_satisfaction";
//$domain_id = "q_demo";
//$domain_id = "xpress_satisfaction";
//$domain_id = "sero_con";
//$domain_id = "partner_sexhist";
//$domain_id = "final_status";
//$domain_id = "sdhos_retention";
//$domain_id = "sh_ae";

//$domain_id = "hiv_hist";
//$domain_id = "sti_hist";
//$domain_id = "medical_hist";
//$domain_id = "hormone_hist";
//$domain_id = "prep_npep_hist";
//$domain_id = "prep_npep_screen";
//$domain_id = "prep_npep";
//$domain_id = "vital_sign";
//$domain_id = "demo";
//$domain_id = "risk_behavior";
//$domain_id = "hiv_acute";

//$domain_id = "sh_retro";
//$domain_id = "hivtest_self";
//$domain_id = "sut_pre_screen";
//$domain_id = "sut_pre_follow";

$domain_id = "z202009_covid_visit_g3";





/*
        $query = "SELECT d.data_name
                  FROM p_data_list as d
                  WHERE d.domain_id=?
                  ORDER BY d.data_seq
                  ";
          $stmt0 = $mysqli->prepare($query);
          $stmt0->bind_param("s", $domain_id);
*/

$txt_row = "
CREATE TABLE x_$domain_id (
  <br>uid varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  <br>collect_date date NOT NULL,
  <br>collect_time time NOT NULL,
";

          $query = "SELECT distinct d.data_name, d.data_type
                    FROM p_form_data as d
                    WHERE d.domain_id like '$domain_id'
                    AND d.data_type <> 'title' and d.data_type <> 'title_row' and d.data_type <> 'topic'
                    ORDER BY d.domain_id, d.data_seq, d.data_name
                    ";
/*
                    $query = "SELECT distinct d.data_name, d.data_type
                              FROM p_form_data2 as d
                              WHERE d.domain_id like '$domain_id'
                              AND d.data_type <> 'title' and d.data_type <> 'title_row'
                              ORDER BY d.domain_id, d.data_seq
                              ";

                              */


            $stmt0 = $mysqli->prepare($query);


          if ( false===$stmt0 ) {
             die('prepare() failed: ' . htmlspecialchars($mysqli->error));
          }
          if ($stmt0->execute()){
             $stmt0->bind_result($data_name, $data_type);
             $stmt0->store_result();
             $i = 0;
             while ($stmt0->fetch()) {

               $i++;
               echo "<br>$i) data: $data_name / $data_type";

               if($data_type == "radio"){
                 //$length = strlen($data_value.trim());
                 $length = "2";
                 $txt_row .= "<br> $data_name varchar($length) COLLATE utf8_unicode_ci DEFAULT NULL,";
               }
               else if($data_type == "check"){
                 //$length = strlen($data_value.trim());
                 $length = "2";
                 $txt_row .= "<br> $data_name varchar($length) COLLATE utf8_unicode_ci DEFAULT NULL,";
               }
               else if($data_type == "text"){
                 $length = "100";
                 $txt_row .= "<br> $data_name varchar($length) COLLATE utf8_unicode_ci DEFAULT NULL,";
               }
               else if($data_type == "textarea"){
                 $length = "300";
                 $txt_row .= "<br> $data_name varchar($length) COLLATE utf8_unicode_ci DEFAULT NULL,";
               }
               else if($data_type == "date"){
                 $txt_row .= "<br> $data_name date DEFAULT NULL,";
               }
               else if($data_type == "partial_date"){
                 $length = "10";
                 $txt_row .= "<br> $data_name varchar($length) COLLATE utf8_unicode_ci DEFAULT NULL,";
               }
               else if($data_type == "int"){

                // $txt_row .= "<br> $data_name int(5) NOT NULL,";
                 $length = "10";
                 $txt_row .= "<br> $data_name varchar($length) COLLATE utf8_unicode_ci DEFAULT NULL,";

               }
               else if($data_type == "double"){

                // $txt_row .= "<br> $data_name int(5) NOT NULL,";
                 $length = "10";
                 $txt_row .= "<br> $data_name varchar($length) COLLATE utf8_unicode_ci DEFAULT NULL,";

               }
               else if($data_type == "time"){

                // $txt_row .= "<br> $data_name int(5) NOT NULL,";
                 $length = "5";
                 $txt_row .= "<br> $data_name varchar($length) COLLATE utf8_unicode_ci DEFAULT NULL,";

               }
               else if($data_type == "hidden"){

                // $txt_row .= "<br> $data_name int(5) NOT NULL,";
                 $length = "20";
                 $txt_row .= "<br> $data_name varchar($length) COLLATE utf8_unicode_ci DEFAULT NULL,";

               }

             }//while

          }
          $stmt0->close();

          $txt_row = substr($txt_row,0,strlen($txt_row)-1);
          $txt_row .= "<br>
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
<br>
          ALTER TABLE x_$domain_id
            ADD PRIMARY KEY (uid,collect_date);
          COMMIT;
          ";


echo "<br> $txt_row";



?>
