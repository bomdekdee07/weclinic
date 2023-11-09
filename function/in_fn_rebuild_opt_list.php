<?

//drop down function

function rebuildDataCategory($col_id, $col_name, $tbl_name){
  //  echo "rebuildDataCategory $tbl_name";
    include("in_db_conn.php");
    include("in_file_prop.php");

    $txt = "";
    $query = "SELECT $col_id, $col_name FROM $tbl_name
              ORDER BY $col_name
              ";
    echo $query;
    $stmt = $mysqli->prepare($query);
    if ($stmt->execute()) {

      $stmt->bind_result($id, $name);
      $txt = "<?\n";
      $txt .= "\$opt_$tbl_name = \"\n";
      while ($stmt->fetch()) {
         $txt .= "<option value='".$id."' title='".$name."'>".$name."</option>\n";
      }// while
      $txt .= "\";?>";

      $file = fopen("$ROOT_FILE_PATH/data/opt_data_$tbl_name.php","w");
      fwrite($file,$txt);
      fclose($file);
    }
    else{
       die("Errormessage: ". $stmt->error);
       $msg_error .= "Error : ".$stmt->error;
    }
    $stmt->close();
    $mysqli-> close();
}

?>
