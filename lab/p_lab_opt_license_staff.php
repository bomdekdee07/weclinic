<?
  include('../in_db_conn.php');


  $query ="SELECT s_id, s_name , license_lab
  FROM p_staff WHERE s_status='1' AND license_lab != '' ORDER BY s_remark asc
  ";
  $stmt = $mysqli->prepare($query);
  if($stmt->execute()){
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
      echo "<option value='".$row['s_id']."'>[".$row['s_name']."] ".$row['license_lab']."</option>";
    }//while
  }
  $stmt->close();
  $mysqli->close();



?>
