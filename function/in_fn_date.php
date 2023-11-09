<?

//Date function

// get Date from DB and change to date format d-M-y
function changeToThaiDate($dateData)
{
  $dateVal = explode("-", $dateData);
  $dateVal[0] = $dateVal[0] + 543;
  $value = $dateVal[2]."/".$dateVal[1]."/".$dateVal[0];
  return $value;
}

// get Date from DB and change to date format d-M-y
function getDBDate($dateData)
{
  $txtDate = "";
  if($dateData != "0000-00-00" || $dateData != "0000-00-00 00:00:00"){
     $txtDate = (new DateTime($dateData))->format('d M y');
  }
  return $txtDate;
}

function getDBTime($dateData)
{
  $txtDate = "";
  if($dateData != "0000-00-00 00:00:00"){
     $txtDate = (new DateTime($dateData))->format('H:i');
  }

  return $txtDate;
}

function getDBDateThai($dateData)
{
  $txtDate = "";
  if($dateData != "0000-00-00" || $dateData != "0000-00-00 00:00:00"){
    // Thai Month
    $mtn_arr = array();
    $mtn_arr[] = "ทั้งหมด";
    $mtn_arr[] = "ม.ค.";
    $mtn_arr[] = "ก.พ.";
    $mtn_arr[] = "มี.ค.";
    $mtn_arr[] = "เม.ย.";
    $mtn_arr[] = "พ.ค.";
    $mtn_arr[] = "มิ.ย.";
    $mtn_arr[] = "ก.ค.";
    $mtn_arr[] = "ส.ค.";
    $mtn_arr[] = "ก.ย.";
    $mtn_arr[] = "ต.ค.";
    $mtn_arr[] = "พ.ย.";
    $mtn_arr[] = "ธ.ค.";

    $num_date = (int) (new DateTime($dateData))->format('d');
    $num_month = (int) (new DateTime($dateData))->format('m');
    $thai_year = (int)(new DateTime($dateData))->format('Y') + 543;
    $txtDate = $num_date." ".$mtn_arr[$num_month]." $thai_year";
  }
  return $txtDate;
}

function getDBDateTime($dateData)
{
  $txtDate = "";
  if($dateData != "0000-00-00 00:00:00"){
     $txtDate = (new DateTime($dateData))->format('d M y H:i:s');
  }
  return $txtDate;
}

function getToday()
{
  $txtDate = (new DateTime())->format('Y-m-d');
  return $txtDate; 
}

function getDateToString($dateObj)
{
  $txtDate = $dateObj->format('Y-m-d');
  return $txtDate;
}


?>
