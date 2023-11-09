<?
header('Access-Control-Allow-Origin: *');

$id = getQueryString("id");
$p_citizen_id = getQueryString("citizen_id");
$p_fname = getQueryString("fname");
$p_lname = getQueryString("lname");
$p_dob = getQueryString("dob");
$p_clinic_id = getQueryString("clinic_id");
$p_phone_no = getQueryString("phone_no");


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

if($id != ""){ // define id to search UID Info
  //echo "enter ".strlen(trim($id));
  if(mb_strlen($id) == 8){ // uic
     $query_add = " b.uic=? ";
  }
  else if (mb_strlen($id) == 13){ // citizen id
  //   $query_add = " b.national_id=? ";
     $query_add = " REPLACE(b.national_id, '-', '')=? ";
     $p_citizen_id = $id;
  }
  else if (mb_strlen($id) == 9){ // uid
  //   $query_add = " b.national_id=? ";
     $query_add = " u.uid=? ";
  }
  $arr_query_add[] = $id;

}
else { // define citizen_id or fname lname, or phone_no to search or create UID

  if($p_citizen_id != ""){
    $query_add .= " REPLACE(b.national_id, '-', '')=? OR ";
    $arr_query_add[] = $p_citizen_id;
  }
  if ($p_fname !="" && $p_lname != ""){
    $query_add .= " CONCAT(b.fname, b.sname)=? OR ";
    $arr_query_add[] = "$p_fname$p_lname";
  }

  /*
  if ($p_phone_no != ""){
    $query_add .= " b.contact=? OR ";
    $arr_query_add[] = "$p_phone_no";

  }
  */


  if(count($arr_query_add) > 0){
    $query_add = substr($query_add,0,strlen($query_add)-3); // cut last OR from query_add
    $query_add = " ($query_add)";

  //  echo "$str_param / $query_add ";
  //  echo print_r($arr_param_add);
  }

}



if($query_add != ""){

  foreach ($arr_query_add as $key => $value){
    $arr_param_add[$key] = &$arr_query_add[$key];
    $str_param .= "s";
  }// foreach

    include_once("../in_db_conn.php");

    $query = "SELECT u.uic, u.uid, u.clinic_id, b.fname, b.sname,
    b.national_id, u.date_o_b,u.mon_o_b, u.y_o_b, u.reg_date, u.age   ,b.contact
    FROM uic_gen as u, basic_reg as b
    WHERE u.uic = b.uic AND $query_add
    ORDER BY b.reg_date LIMIT 1
    ";

//echo "$str_param query : $query<br>";
    $stmt = $mysqli->prepare($query);
    $param = array_merge(array("$str_param"),$arr_param_add);
  /*
    $param1="0001111122222"; $param2="ภาณุศรีวชิรโรจน์";
    $param = array_merge(array("ss"),array(&$param1, &$param2));
    */
    call_user_func_array(array($stmt, "bind_param"),$param);

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
           $p_phone_no = ($p_phone_no != "")?$p_phone_no:$contact;
         }
      }// if
    }
    else{
      $msg_info .= $stmt->error;
    }
    $stmt->close();


    if($uid == ""){ // not found
      if(($p_fname.$p_lname.$p_dob.$p_clinic_id) != ""){ // there is data to insert
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
              if($p_clinic_id == ""){
                $msg_info_missing .="clinic_id,";
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
        $arr_uic = array();

        $clinic_id = $p_clinic_id;
        $contact = $p_phone_no;

        // create uic
        $uic = ""; // generated uic
        $actual_uic = ""; // real uic
        // แทนค่าสระอุ
        $fname = str_replace("ุ","",$p_fname);
        $lname = str_replace("ุ","",$p_lname);

        $count = strlen($lname);
        for($i = 1; $i<$count; $i++){ // i คืออักษรตัวที่ i ของนามสกุล  ถ้าตัวแรก gen มาแล้วซ้ำกับ uic ที่มี ก็เอาอักษรถัดไป
          $lname = mb_substr($lname,($i-1), strlen($lname),'UTF-8'); // first lname
          $arr_uic =  generateUIC($fname, $lname, $p_dob);
          $uic = $arr_uic["uic"];


          if($uic != ""){
            if($i == 1) $actual_uic = $uic;
            $query = "SELECT count(u.uic) as amt
            FROM uic_gen as u, basic_reg as b
            WHERE u.uic = b.uic AND u.uic='$uic'
            ";
//echo "queryuic:$query";

            $stmt = $mysqli->prepare($query);
            if($stmt->execute()){
              $stmt->bind_result($uic_amt);
              if ($stmt->fetch()) {

                  if($uic_amt == 0){
                  break;
                  }
              }// if
            }
            else{
              $msg_info .= $stmt->error;
            }
            $stmt->close();
          }

        }// for

        if(isset($stmt)) $stmt->close();



        if($uic != ""){

          $d=$arr_uic["d"]; $m=$arr_uic["m"]; $y=$arr_uic["y"];

          $diff = date_diff(date_create($p_dob), date_create(date("Y-m-d")));
          $age = $diff->format('%y'); //calculate age

          $iclinic_clinic_id = "";
          if($p_clinic_id == ""){
            $p_clinic_id = "IHRI";
            $iclinic_clinic_id = "IHRI";
          }
          else{ // get iclinic id

            $query = "SELECT clinic_id
            FROM clinic
            WHERE weclinic_id='$p_clinic_id';
            ";
//echo "queryuic:$query";
            $stmt = $mysqli->prepare($query);
            if($stmt->execute()){
              $stmt->bind_result($iclinic_clinic_id);
              if ($stmt->fetch()) {
              }// if
            }
            else{
              $msg_info .= $stmt->error;
            }
            $stmt->close();

          }// get clinic id

          $cur_year =  (new DateTime())->format('y');
          $id_prefix = "P".$cur_year."-" ;

          $id_digit = 5; // 00001-99999
          $where_substr_pos_end = strlen($id_prefix);
          $substr_pos_begin = 1+$where_substr_pos_end;

            $inQuery = "INSERT INTO uic_gen (uid, uic,uic2,flfn,flln,date_o_b,mon_o_b,y_o_b,age,reg_by,reg_date,wait,clinic_id, clinic, dob)
            SELECT @keyid := CONCAT('$id_prefix',  LPAD( (SUBSTRING(  IF(MAX(uid) IS NULL,0,MAX(uid)) ,$substr_pos_begin,$id_digit))+1, '$id_digit','0'))
             ,'$uic','$actual_uic','$p_fname','$p_lname','$d','$m','$y','$age','auto',now(),'yes','$p_clinic_id', '$iclinic_clinic_id', '$p_dob'
              FROM uic_gen WHERE SUBSTRING(uid,1,$where_substr_pos_end) = '$id_prefix' ;
            ";
//echo "query: $inQuery";

            $stmt = $mysqli->prepare($inQuery);

            if($stmt->execute()){
              $inQuery = "SELECT @keyid;";
              $stmt = $mysqli->prepare($inQuery.";");
              $stmt->bind_result($uid);
              if($stmt->execute()){
                if($stmt->fetch()){

                }
              }
            }
            else{
              $msg_info .= $stmt->error;
            }
            $stmt->close();

            $inQuery = "INSERT INTO basic_reg (uic, reg_date,fname,sname,national_id,clinic,contact, age)
            VALUES('$uic', now(),'$p_fname','$p_lname','$p_citizen_id','$iclinic_clinic_id', '$p_phone_no', '$age')
            ";

            $stmt = $mysqli->prepare($inQuery);
            if($stmt->execute()){
               $msg_info .= "Insert new record ($uic)";
            }
            $stmt->close();



            $inQuery = "INSERT IGNORE INTO patient_info (uid, uic,fname,sname,citizen_id,date_of_birth, tel_no)
            VALUES('$uid', '$actual_uic','$p_fname','$p_lname','$p_citizen_id','$p_dob', '$p_phone_no')
            ";

            //echo "query: $inQuery";
            $stmt = $mysqli->prepare($inQuery);
            if($stmt->execute()){
            }
            $stmt->close();


            $inQuery = "INSERT INTO patient_info_log (uid, uic,fname,sname,citizen_id,date_of_birth, tel_no, update_datetime)
            VALUES('$uid', '$actual_uic','$p_fname','$p_lname','$p_citizen_id','$p_dob', '$p_phone_no', now())
            ";

            $stmt = $mysqli->prepare($inQuery);
            if($stmt->execute()){
            }
            $stmt->close();




        }//if($arr_uic["uic"] != ""){




       }//else { // complete data , insert

      }
      else{ // no data to insert
        $msg_info .= "Not found data";
      }


    } // if($uid == ""){ // not found

    $mysqli->close();

}
else {
  $msg_info .= "Invalid input id";
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
$rtn["phone_no"] = getString($contact);
$rtn["clinic_id"] = getString($clinic_id);

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

function generateUIC($fname, $lname, $dob){ // $char_pos :อักษรตัวที่ n ของนามสกุล เลื่อนนามสกุลไปได้เรื่อยๆ ถ้า uic ซ้ำ
  $uic = "";
  $arr_uic = array();
  $arr_dob = explode("-",$dob);

  $f = getFirstChar($fname);
  $l = getFirstChar($lname);
  if(($f !="") && ($l != "")){
    $uic .= $f; // first fname
    $uic .= $l; // last lname
  }

    $arr_uic["d"] = str_pad($arr_dob[2], 2, '0', STR_PAD_LEFT);// date
    $arr_uic["m"] = str_pad($arr_dob[1], 2, '0', STR_PAD_LEFT);// month
    $arr_uic["y"] = substr(strval((int)$arr_dob[0] + 543), 2, 4); // year

  $arr_uic["uic"]= $uic.$arr_uic["d"].$arr_uic["m"].$arr_uic["y"];
  return $arr_uic;
}

/*
function generateUIC($fname, $lname, $dob, $char_pos){ // $char_pos :อักษรตัวที่ n ของนามสกุล เลื่อนนามสกุลไปได้เรื่อยๆ ถ้า uic ซ้ำ
  $uic = "";
  $arr_uic = array();
  $f = preg_replace('/[^A-Za-zก-ฮ]/','',$fname); // fname
  $l = preg_replace('/[^A-Za-zก-ฮ]/','',$lname); // lname
  $arr_dob = explode("-",$dob);
  if(mb_strlen($l) > $char_pos){
    $uic .= mb_substr($f,0,1,'UTF-8'); // first fname
    $uic .= mb_substr($l,($char_pos-1),1,'UTF-8'); // first lname

    $arr_uic["d"] = str_pad($arr_dob[2], 2, '0', STR_PAD_LEFT);// date
    $arr_uic["m"] = str_pad($arr_dob[1], 2, '0', STR_PAD_LEFT);// month
    $arr_uic["y"] = substr(strval((int)$arr_dob[0] + 543), 2, 4); // year
  }
  $arr_uic["uic"]= $uic.$arr_uic["d"].$arr_uic["m"].$arr_uic["y"];
  return $arr_uic;
}
*/


function validateDate($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function getFirstChar($strName){
  $f = ""; $k=0;
  $count= strlen($strName);
  for($k=0; $k<$count; $k++){
    $f = mb_substr($strName,$k,1,'UTF-8');
    if(checkFirstChar($f)){
      break;
    }
  }// for

  return $f;
}


function checkFirstChar($str_name){
//  if(preg_match('/^[A-Za-zกขฃคฅฆงจฉชซฌญฎฏฐฑฒณดตถทธนบปผฝพฟภมยรฤลฦวศษสหฬอฮ]n+/', $str_name))
  if(preg_match('/^[A-Za-zกขฃคฅฆงจฉชซฌญฎฏฐฑฒณดตถทธนบปผฝพฟภมยรฤลฦวศษสหฬอฮ]+$/', $str_name))
  {
    return true;
  }
  else{
    return false;
  }
}
