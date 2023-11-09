<?
    $hr_email = "sukanya.y@trcarc.org";
    $hr_email_name = "Sukanya (HR)";

    function getErrorMail($userID,
                          $mailSubject,
                          $mailReceiver,
                          $mailReceiverName,
                          $mailContent
    ){
      include("in_db_conn.php");
      $id_prefix = "E";
      $id_digit = "4";

      $inQuery = "INSERT INTO pv_ts_email_error
      (email_id, user_id, email_subject,email_receiver, email_receiver_name, email_content) ";
      $inQuery.= " SELECT @keyid := CONCAT('".$id_prefix."',
      LPAD( (SUBSTRING(  IF(MAX(email_id) IS NULL,0,MAX(email_id))   ,2,4)*1)+1, '".$id_digit."','0') )";
      $inQuery.= " ,?,?,?,?,?";
      $inQuery.= " FROM pv_ts_email_error;";
/*
      $inQuery = "INSERT INTO pv_ts_email_error
      (user_id, email_subject,email_receiver, email_receiver_name, email_content)
      VALUES (?,?,?,?,?) ";
*/
      $stmt = $mysqli->prepare($inQuery);
      $stmt->bind_param("sssss", $userID, $mailSubject,$mailReceiver,$mailReceiverName, $mailContent);
      if ($stmt->execute()) {

      }
      else{
         die("Errormessage: ". $stmt->error);
         $msg_error .= "Error : ".$stmt->error;
      }
      $stmt->close();
      $mysqli-> close();
    }



    function updateErrorMail($emailID,
                             $isSend
    ){
      include("in_db_conn.php");
      $inQuery = "";
      if($isSend == 1){
        $inQuery = "UPDATE pv_ts_email_error SET send_success_date=NOW(), is_send=1
                    WHERE email_id=?";
      }
      else if($isSend == 0){
        $inQuery = "UPDATE pv_ts_email_error SET send_error_date=NOW(), is_send=0
                    WHERE email_id=?";
      }


      $stmt = $mysqli->prepare($inQuery);
      $stmt->bind_param("s", $emailID);
      if ($stmt->execute()) {

      }
      else{
         die("Errormessage: ". $stmt->error);
         $msg_error .= "Error : ".$stmt->error;
      }
      $stmt->close();
      $mysqli-> close();
    }

?>
