<?

//open link : check whether form is done or not

if($open_link == "Y"){

include_once("../in_db_conn.php");
$is_form_done = "N";
$query = "SELECT collect_date
FROM p_visit_form_done
WHERE uid=? AND proj_id=? AND group_id=? AND form_id=? AND visit_id=?
";

         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("sssss", $uid, $project_id, $group_id, $form_id, $visit_id);
         if($stmt->execute()){
           $stmt->bind_result($collect_date);

           if ($stmt->fetch()) {
             //echo "open_link $collect_date";
             $is_form_done = "Y";
           }
    $stmt->close();
}
  //$mysqli->close();
  if($is_form_done == "Y"){
    $mysqli->close();
    //echo "form done ";
    header( "location: ../info/invalid.php?e=e2" ); // expired link
    exit(0);
  }
}

?>
