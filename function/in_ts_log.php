<?
    function setLogNote($staffID, $logNote){

      include("../in_db_conn.php");
      $client_ip_address = get_client_ip();
      $today_date = new DateTime();
      $inQuery = "INSERT INTO pv_log (log_id, log_ip_address, log_note, staff_id) ";
      $inQuery.= " SELECT @keyid := CONCAT('".$today_date->format("y")."',
      LPAD( (SUBSTRING(  IF(MAX(log_id) IS NULL,0,MAX(log_id))   ,3,6)*1)+1, '6','0') )";
      $inQuery.= ",?,?,?  FROM pv_log WHERE SUBSTRING(log_id,1,2) = '".$today_date->format("y")."';";

//echo $inQuery;
      $stmt = $mysqli->prepare($inQuery);
      $stmt->bind_param("sss",$client_ip_address, $logNote, $staffID);
      if ($stmt->execute()) {

      }
      else{
         die("Errormessage: ". $stmt->error);
         $msg_error .= "Error : ".$stmt->error;
      }
      $stmt->close();
      $mysqli-> close();

    }

    // Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

?>
