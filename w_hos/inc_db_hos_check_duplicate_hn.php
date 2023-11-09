<?
    // check duplicate patient by hn and hospital  both prospective & retrospective
    function sdhos_checkHN($hn, $hos_id){ // 1:found, 0:not found
      include("../in_db_conn.php");
      $flag = 0;
      $inQuery = "SELECT pid FROM
      sdhos_pid WHERE hn = ? AND clinic_id=?
      ";
      $stmt = $mysqli->prepare($inQuery);
      $stmt->bind_param("ss",$hn, $hos_id);
      if ($stmt->execute()) {
          $flag = 1;
      }
      else{
         die("Errormessage: ". $stmt->error);
         $msg_error .= "Error : ".$stmt->error;
      }
      $stmt->close();

      if($flag == 0){
        $inQuery = "SELECT pid FROM
        sdhos_pid_retro WHERE hn = ? AND clinic_id=?
        ";
        $stmt = $mysqli->prepare($inQuery);
        $stmt->bind_param("ss",$hn, $hos_id);
        if ($stmt->execute()) {
            $flag = 1;
        }
        else{
           die("Errormessage: ". $stmt->error);
           $msg_error .= "Error : ".$stmt->error;
        }
        $stmt->close();
      }

      $mysqli-> close();
      return flag;

    }


?>
