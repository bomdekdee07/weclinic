<?
  include('../in_db_conn.php');

  $query ="SELECT laboratory_id as id, laboratory_name as name
  FROM p_lab_laboratory ORDER BY laboratory_id 
  ";
  $stmt = $mysqli->prepare($query);
  if($stmt->execute()){
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
      echo "<option value='".$row['id']."'>[".$row['id']."] ".$row['name']."</option>";
    }//while
  }
  $stmt->close();
  $mysqli->close();
?>
