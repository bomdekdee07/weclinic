<?

$id = getQueryString("id");

$query_add = "";
$returnData = "";

$found = "N";
$msg_info = "";

$uic = "";
$citizen_id = "";
$uid="";
$fname = "";
$lname = "";
$dob = "";
$clinic_id = "";

if($id != ""){
  //echo "enter ".strlen(trim($id));
  if(mb_strlen($id) == 8){ // uic
     $query_add = " b.uic=? ";
  }
  else if (mb_strlen($id) == 13){ // citizen id
  //   $query_add = " b.national_id=? ";
     $query_add = " REPLACE(b.national_id, '-', '')=? ";


  }
}

if($query_add != ""){
    include_once("../in_db_conn.php");

    $query = "SELECT u.uic, u.uid, u.clinic_id, b.fname, b.sname,
    b.national_id, u.date_o_b,u.mon_o_b, u.y_o_b, u.reg_date, u.age   ,b.contact
    FROM uic_gen as u, basic_reg as b
    WHERE u.uic = b.uic AND $query_add
    ORDER BY b.reg_date LIMIT 1
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id);
    if($stmt->execute()){
      $stmt->bind_result($uic, $uid, $clinic_id, $fname, $lname,  $citizen_id,
      $d, $m, $y, $reg_date, $age,
      $contact );
      if ($stmt->fetch()) {
        if($uid != ""){
           $d = str_pad($d, 2, '0', STR_PAD_LEFT);
           $m = str_pad($m, 2, '0', STR_PAD_LEFT);
           $y = str_pad($y, 2, '0', STR_PAD_LEFT);

           $th_yr = "25";
           //if($age > 50 && $y > 60) $th_yr = "24";
           if($y > 60) $th_yr = "24";

           $y = (int) $th_yr.$y;
           $y = $y-543;
           $dob = "$y-$m-$d";

           $found = "Y";
         }
      }// if
    }
    else{
      $msg_info .= $stmt->error;
    }


    $stmt->close();
    $mysqli->close();

}
else {
  $msg_info = "Invalid input id";
}

$rtn["found"] = $found;
$rtn["msg_info"] = $msg_info;

//$rtn["citizen_id"] = $citizen_id;
$rtn["citizen_id"] = str_replace("-","",getString($citizen_id));

$rtn["uic"] = getString($uic);
$rtn["uid"] = getString($uid);
$rtn["fname"] = getString($fname);
$rtn["lname"] = getString($lname);
$rtn["dob"] = getString($dob);
$rtn["clinic_id"] = getString($clinic_id);

$returnData = json_encode($rtn);
echo $returnData;


function getQueryString($sName){
 $sResult = (isset($_GET[$sName])?urldecode($_GET[$sName]):"");
 if($sResult=="") $sResult = (isset($_POST[$sName])?urldecode($_POST[$sName]):"");
 return $sResult;
}

function getString($sName){
 $sResult = ($sName !== NULL)?$sName:"";
 return $sResult;
}
