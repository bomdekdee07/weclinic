<?
header('Access-Control-Allow-Origin: *');

$id = getQueryString("id");
$p_citizen_id = getQueryString("citizen_id");
$p_fname = getQueryString("fname");
$p_lname = getQueryString("lname");
$p_dob = getQueryString("dob");
$p_clinic_id = getQueryString("clinic_id");
$p_phone_no = getQueryString("phone_no");


$sToday = date("Y-m-d H:i:s");
$txtDataReq = "$p_citizen_id,$p_fname,$p_lname,$p_dob,$p_clinic_id,$p_phone_no";
$txtLog = "$sToday|Request|$id|$txtDataReq";
file_put_contents('logs/uic_center_api.txt', $txtLog. PHP_EOL, FILE_APPEND);

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
$contact = "";

$id2="";
$arr_query_add = array();
$str_param = "";
$arr_param_add = array();

$msg_info_missing = "";
$sPrepare = "";
$sCheckDOB = "N"; // if search by uic or fname lname found then check dob

if($id != ""){ // define id to search UID Info
  //echo "enter ".strlen(trim($id));
  if(mb_strlen($id) == 8){ // uic
     $query_add = " uic=? ";
     $sPrepare .= "s";
     $sCheckDOB = "Y";
  }
  else if (mb_strlen($id) >= 13){ // citizen id
     $id = str_replace("-","",$id);
     $query_add = " citizen_id=? ";
     $p_citizen_id = $id;
     $sPrepare .= "s";
  }
  else if (mb_strlen($id) == 9){ // uid
  //   $query_add = " b.national_id=? ";
     $query_add = "uid=? ";
     $sPrepare .= "s";
  }
  $arr_query_add[] = $id;

}
else { // define citizen_id or fname lname, or phone_no to search or create UID

  if($p_citizen_id != ""){
    $p_citizen_id = str_replace("-","",$p_citizen_id);
    $query_add .= " REPLACE(citizen_id, '-', '')=? OR ";
    $arr_query_add[] = $p_citizen_id;
    $sPrepare .= "s";

  }
  if ($p_fname !="" && $p_lname != ""){
    $query_add .= " CONCAT(fname,sname)=? OR ";
    $arr_query_add[] = "$p_fname$p_lname";
    $sPrepare .= "s";
    $sCheckDOB = "Y";
  }
  /*
  if ($p_phone_no != ""){
    $query_add .= " tel_no=? OR ";
    $arr_query_add[] = "$p_phone_no";
    $sPrepare .= "s";
  }
*/

  if(count($arr_query_add) > 0){
    $query_add = substr($query_add,0,strlen($query_add)-3); // cut last OR from query_add
    $query_add = " ($query_add)";

  //  echo "$str_param / $query_add ";
  //  echo print_r($arr_param_add);
  }

}
/*
if($p_dob !=""){
  $aT = explode("-",$p_dob);
  if($aT[0] > 1900 && $aT[0] <= 2400){
    $p_dob = $aT[0]."-".$aT[1]."-".$aT[2];
  }
  else if($aT[0] > 2400){
    $p_dob = ($aT[0]-543)."-".$aT[1]."-".$aT[2];
  }
  else if ($aT[2] > 2400){
    $p_dob = ($aT[2]-543)."-".$aT[2]."-".$aT[1];
  }
}
*/


if($query_add != ""){


    include_once("../in_db_conn.php");

    $query = "SELECT uic, uid, fname, sname,
    REPLACE(citizen_id,'-','') , date_of_birth, last_modify_date ,tel_no
    FROM patient_info
    WHERE $query_add
    ORDER BY uid desc LIMIT 1
    ";

//echo "$sPrepare query : $query<br>";
//echo print_r($arr_query_add);
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param($sPrepare,...$arr_query_add);


    if($stmt->execute()){
      $stmt->bind_result($uic, $uid, $fname, $lname,   $citizen_id, $dob, $last_update, $contact );
      if ($stmt->fetch()) {
        if($uid != ""){
          if($citizen_id == $p_citizen_id){
            $found = "Y";

          }
          else{
            if($citizen_id == "")
            $citizen_id = $p_citizen_id;

            if((($fname.$lname) == ($p_fname.$p_lname)) && ($dob != $p_dob)){
              $found='N'; $uid='';

            }
            if(($fname.$lname) == ($p_fname.$p_lname)){
              if($p_dob !="" && ($dob != $p_dob)){
                $found='N'; $uid='';
              }
              else{
                $found = "Y";
              }

            }
            else{
              $found = "Y";
            }

          }


           $p_phone_no = ($p_phone_no != "")?$p_phone_no:$contact;
         }
      }// if
    }
    else{
      $msg_info .= $stmt->error;
    }
    $stmt->close();


    if($uid == ""){ // not found
      if(($p_fname.$p_lname.$p_dob) != ""){ // there is data to create uic
              $msg_info_missing = "";
              if($p_fname == ""){
                $msg_info_missing .="fname,";
              }
              if($p_lname == ""){
                $msg_info_missing .="lname,";
              }
              if($p_dob == ""){
                $msg_info_missing .="dob,";
              }
      }
              if($p_citizen_id != ""){
                 if(strlen($p_citizen_id) != 13)
                $msg_info_missing .="citizen_id(invalid),";
              }

      if($msg_info_missing != ""){ // missing data
            $msg_info_missing = substr($msg_info_missing,0,(strlen($msg_info_missing)-1));
            $msg_info = "Missing Data: $msg_info_missing";
      }
      else { // complete data , insert

        $clinic_id = $p_clinic_id;
        $contact = $p_phone_no;

        //validate dob
        $sDOBUic="";
        if($p_dob!=""){
          $aT = explode("-",$p_dob);
          $sDCDate = "";
          if(count($aT)==3){
            //Number
            if($aT[0]>2400){
              //This is thai year convert to DC
              $sBCDOB = $p_dob;
              $sTemp = ($aT[0]-543)."-".$aT[1]."-".$aT[2];
              $sDCDate = $p_dob;
            }else{
              $sDCDate = $p_dob;
              $sTemp = ($aT[0]+543)."-".$aT[1]."-".$aT[2];
              $sBCDOB = $p_dob;

            }
            $sBYear = $aT[0];
            if($aT[0]<2400){
              $sBYear = $aT[0]+543;
            }

            $sDOBUic = $aT[2].$aT[1].substr($sBYear,2,2);
          }

          $aUic = getAllUIC($p_fname,$p_lname,$sDOBUic);
          $uic  = $aUic[0];
        }

        // create uic
      //  echo "<br>param: $p_fname,$p_lname,$sDOBUic";

      //  print_r($uic);

        if($uic != ""){

          $iclinic_clinic_id = "";
          if($p_clinic_id == ""){
            $p_clinic_id = "IHRI";
            $iclinic_clinic_id = "IHRI";
          }


          $id_prefix = "P".date("y")."-" ;

          $id_digit = 5; // 00001-99999
          $where_substr_pos_end = strlen($id_prefix);
          $substr_pos_begin = 1+$where_substr_pos_end;


            $inQuery = "INSERT INTO patient_info (uid, uic,fname,sname,citizen_id,date_of_birth, tel_no, last_modify_date)
            SELECT @keyid := CONCAT('$id_prefix',  LPAD( (SUBSTRING(  IF(MAX(uid) IS NULL,0,MAX(uid)) ,$substr_pos_begin,$id_digit))+1, '$id_digit','0'))
             ,?,?,?,?,?,?,now()
              FROM patient_info WHERE SUBSTRING(uid,1,$where_substr_pos_end) = '$id_prefix' ;
            ";

            //echo "query: $inQuery";
            $stmt = $mysqli->prepare($inQuery);
            $stmt->bind_param('ssssss', $uic,$p_fname,$p_lname,$p_citizen_id,$p_dob, $p_phone_no);
            if($stmt->execute()){
              $inQuery = "SELECT @keyid;";
              $stmt = $mysqli->prepare($inQuery.";");
              $stmt->bind_result($uid);
              if($stmt->execute()){
                if($stmt->fetch()){

                }
              }
            }
            $stmt->close();


            $inQuery = "INSERT INTO patient_info_log (uid, uic,fname,sname,citizen_id,date_of_birth, tel_no, update_datetime, update_by)
            VALUES(?,?,?,?,?,?,?,now(), 'uid_center')
            ";
            //echo "query: $inQuery";
            $stmt = $mysqli->prepare($inQuery);
            $stmt->bind_param('sssssss', $uid, $uic,$p_fname,$p_lname,$p_citizen_id,$p_dob, $p_phone_no);
            if($stmt->execute()){
            }
            $stmt->close();




        }//if($uic != ""){



       }//else { // complete data , insert
    } // if($uid == ""){ // not found

    $mysqli->close();

}
else {
  $msg_info .= "Invalid input id";
}

$rtn["found"] = $found;
$rtn["msg_info"] = $msg_info;
$rtn["citizen_id"] = str_replace("-","",getString($citizen_id));
$rtn["uic"] = getString($uic);
$rtn["uid"] = getString($uid);
$rtn["fname"] = getString($fname);
$rtn["lname"] = getString($lname);
$rtn["dob"] = getString($dob);
$rtn["phone_no"] = getString($contact);
$rtn["clinic_id"] = getString($clinic_id);

$txtID_Rtn = $rtn["uid"].",".$rtn["uic"].",".$rtn["found"].",".$rtn["msg_info"];
$txtDataRtn = $rtn["citizen_id"].",".$rtn["fname"].",".$rtn["lname"].",".$rtn["dob"].",".$rtn["clinic_id"].",".$rtn["phone_no"];
$txtLog = "$sToday|Return|$txtID_Rtn|$txtDataRtn";
file_put_contents('logs/uic_center_api.txt', $txtLog. PHP_EOL, FILE_APPEND);


$returnData = json_encode($rtn);
echo $returnData;



function getQueryString($sName){
 $sResult = (isset($_GET[$sName])?urldecode(trim($_GET[$sName])):"");
 if($sResult=="") $sResult = (isset($_POST[$sName])?urldecode(trim($_POST[$sName])):"");
 return $sResult;
}

function getString($sName){
 $sResult = ($sName !== NULL)?$sName:"";
 return $sResult;
}

//2nd Version
function getAllUIC($sFName,$sSName,$sDob){

	$aAllUic = array();
	$s1st = ""; $s2nd = "";
	$sThVowels="*&^%$#@!_/\\\";:+.{}[]|ๆ็๋?<>=-0123456789เแ์ๅะาิีืึุูโใไำั้";
	$aVowel=str_split($sThVowels,1);

	$aTemp = getMBStrSplit($sFName);
	$a1st = array();
	foreach ($aTemp as $ikey => $sChar) {
		if(mb_strpos($sThVowels, $sChar)===false){
			array_push($a1st,$sChar);
		}
	}
	unset($aTemp);
	$aTemp = getMBStrSplit($sSName);
	$a2nd = array();
	foreach ($aTemp as $ikey => $sChar) {
		if(mb_strpos($sThVowels, $sChar)===false){
			array_push($a2nd,$sChar);
		}
	}

	foreach ($a1st as $iKey1 => $s1) {
		$sTempUic = "";
		foreach ($a2nd as $iKey2 => $s2) {
			$sTempUic = $s1.$s2.$sDob;
			array_push($aAllUic,$sTempUic);
		}

	}
	//error_log($sFName." ".$sSName);
	//error_log(implode(",",$aAllUic));

	return $aAllUic;
}

// Convert a string to an array with multibyte string
// I got it from here https://www.thaicreate.com/php/forum/076169.html

function getMBStrSplit($string, $split_length = 1){
	mb_internal_encoding('UTF-8');
	mb_regex_encoding('UTF-8');

	$split_length = ($split_length <= 0) ? 1 : $split_length;
	$mb_strlen = mb_strlen($string, 'utf-8');
	$array = array();
	$i = 0;

	while($i < $mb_strlen)
	{
		$array[] = mb_substr($string, $i, $split_length);
		$i = $i+$split_length;
	}

	return $array;
}
