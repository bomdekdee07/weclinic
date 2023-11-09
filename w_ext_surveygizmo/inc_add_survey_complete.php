<?

include_once("../in_db_conn.php");
$flag = 0; // 1=complete, 0=fail


  $query = "INSERT INTO p_surveygizmo_form_done (uic, form_id, visit_date, visit_name, cbo_site, submit_date)
  VALUES(?,?,?,?,?,now())";
    //  echo "$query";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssss",$uic, $form_id, $visit_date, $visit_name, $cbo_site);
    if($stmt->execute()){
      $flag = 1; 
    }
    else{
      $flag = 0;
      $msg_error .= $stmt->error;
    }
    $stmt->close();



  header( "location: p_survey_info.php?c=$flag" );
  exit(0);


?>
