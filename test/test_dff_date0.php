<?
  include_once("../in_db_conn.php");
  include_once("../function/in_fn_date.php");

  $visit_id = "M6";
  $proj_id = "POC";
  $new_group_id = "003";
  $arr_visit = array();
  $last_proj_date = "2021-03-11";

// visit_day 	visit_day_before 	visit_day_after 	visit_order 	visit_status
         $query = "SELECT visit_id, visit_day
         FROM p_visit_list
         WHERE proj_id=? AND (group_id=? OR group_id='')
         AND visit_status=1 AND visit_order >= 0 AND visit_id <> 'EX'
         ORDER BY visit_order ASC
         ";

         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("ss", $proj_id, $new_group_id);
         if($stmt->execute()){
           $stmt->bind_result($visit_id, $visit_day);
           $last_visit_id="";
           while ($stmt->fetch()) {

             $dateChangeGroup = new DateTime(getToday());
             $dateVisit = getDateToString($dateChangeGroup->modify("+$visit_day day"));
             echo " -- datevisit: $dateVisit/$last_proj_date -- ";

               if($dateVisit <= $last_proj_date){
                 $arr_visit[$visit_id] = $dateVisit;
                 $last_visit_id=$visit_id;
               }
             }//while

             echo "<br>last visit id: $last_visit_id";
             //calculate distance between last visit date and last project date
             $last_proj_date_1 = strtotime($last_proj_date);
             $last_visit_date = strtotime($arr_visit[$last_visit_id]);
             $datediff = $last_proj_date_1 - $last_visit_date;
             $datediff = round($datediff / (60 * 60 * 24));
             echo "<br>last visit date/last proj date: $last_visit_date/$last_proj_date_1 --- datediff:$datediff";
             if($datediff < 10){ // last visit date to last date in proj less than 10 days
               // set last visit date prior 14 days from the previous

    //           $arr_visit[$last_visit_id] = date('Y-m-d', strtotime($last_visit_date. '-14 days'));

echo "<br>** last visit date: ".$arr_visit[$last_visit_id];
          //   $arr_visit[$last_visit_id] = getDateToString(new DateTime($arr_visit[$last_visit_id])->modify("-14 day"));
             $last_visit = new DateTime($arr_visit[$last_visit_id]);
             $arr_visit[$last_visit_id] = getDateToString($last_visit->modify("-14 day"));


             echo "<br>new last visit date: ".$arr_visit[$last_visit_id];
             }
         }
         else{
           $msg_error .= $stmt->error;
         }
         $stmt->close();


?>
