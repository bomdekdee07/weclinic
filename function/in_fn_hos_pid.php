<?


function generateHosPID($birthDate, $firstName, $surName) {
  $newPID = "";
  $arr_birthDate = explode("-",$birthDate);
  $year = strval((int)$arr_birthDate[0] + 543);

  $newPID .= substr($firstName, 0, 1);
  $newPID .= substr($surName, 0, 1);
  $newPID .= $arr_birthDate[2];
  $newPID .= $arr_birthDate[1];
  $newPID .= substr($year, 0, 2);

  return $newPID;
}


?>
