<?
  include('../in_db_conn.php');

  $query ="SELECT sale_opt_id as id, sale_opt_name as name
  FROM sale_option WHERE is_enable='1' ORDER BY data_seq
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
