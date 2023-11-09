<?



$p_citizen_id = getQueryString("citizen_id");
$p_fname = getQueryString("fname");
$p_lname = getQueryString("lname");
$p_dob = getQueryString("dob");
$p_clinic_id = getQueryString("clinic_id");

$uic = "";
$citizen_id = "";
$uid="";
$fname = "";
$lname = "";
$dob = "";
$clinic_id = "";

$query_add = "";
$returnData = "";

$found = "N";
$msg_info = "";



$choice = 0;
if(validateDate($p_dob)){

  if($p_fname !="" && $p_lname !=""){
    //echo "enter ".strlen(trim($id));

    if($p_citizen_id != ""){ // citizen id
       $query_add = " AND b.national_id=? ";
       $choice = 1;
    }
    else if($p_fname != "" && $p_lname != ""){ // name
       $query_add = " AND b.fname=? AND b.sname=?";
       $choice = 2;
    }

  }
  else{
    $msg_info = "Incomplete Data, Please check fname, lname";
  }

  if($query_add != ""){
      include_once("../in_db_conn.php");

      $query = "SELECT u.uic, u.uid, u.clinic_id, b.fname, b.sname,
      b.national_id, u.date_o_b,u.mon_o_b, u.y_o_b, u.reg_date, u.age   ,b.contact
      FROM uic_gen as u, basic_reg as b
      WHERE u.uic = b.uic $query_add
      ORDER BY b.reg_date LIMIT 1
      ";

  //echo "$p_fname, $p_lname / $query";

      $stmt = $mysqli->prepare($query);
      if($choice == 1) $stmt->bind_param("s", $p_citizen_id);
      else if($choice == 2) $stmt->bind_param("ss", $p_fname, $p_lname);

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
               if($age > 50 && $y > 60) $th_yr = "24";

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

      $arr_uic = array();
      if($found == "N" && $query_add != ""){ // not found in database

        // create uic
        $uic = "";
        for($i = 1; $i<3; $i++){
          $arr_uic =  generateUIC($p_fname, $p_lname, $p_dob, $i);
          $uic = $arr_uic["uic"];
          if($uic != ""){

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

          }
        }// for
        $stmt->close();


        if($uic != ""){

          $d=$arr_uic["d"]; $m=$arr_uic["m"]; $y=$arr_uic["y"];

          $diff = date_diff(date_create($p_dob), date_create(date("Y-m-d")));
          $age = $diff->format('%y');

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

          $id_digit = 5;
          $substr_pos_begin = 1+strlen($id_prefix);
          $where_substr_pos_end = strlen($id_prefix);

            $inQuery = "INSERT INTO uic_gen (uid, uic,uic2,flfn,flln,date_o_b,mon_o_b,y_o_b,age,reg_by,reg_date,wait,clinic_id, clinic)
            SELECT @keyid := CONCAT('$id_prefix',  LPAD( (SUBSTRING(  IF(MAX(uid) IS NULL,0,MAX(uid)) ,$substr_pos_begin,$id_digit)*1)+1, '$id_digit','0'))
             ,'$uic','$uic','$p_fname','$p_lname','$d','$m','$y','$age','auto',now(),'yes','$p_clinic_id', '$iclinic_clinic_id'
              FROM uic_gen WHERE SUBSTRING(uid,1,$where_substr_pos_end) = '$id_prefix' ;
            ";
//echo "query: $inQuery";

            $stmt = $mysqli->prepare($inQuery);

            if($stmt->execute()){
              $inQuery = "SELECT @keyid;";
              $stmt = $mysqli->prepare($inQuery.";");
              $stmt->bind_result($uid);
              if($stmt->execute()){ // get leave id
                if($stmt->fetch()){
                    //  echo "<br><br>uid=$uid / uic=$uic";
                }
              }


            }
            else{
              $msg_info .= $stmt->error;
            }
            $stmt->close();



            $inQuery = "INSERT INTO basic_reg (uic, reg_date,fname,sname,national_id,clinic, age)
            VALUES('$uic', now(),'$p_fname','$p_lname','$p_clinic_id','$iclinic_clinic_id', '$age')
            ";

            $stmt = $mysqli->prepare($inQuery);
            if($stmt->execute()){

            }

      //  echo "uic:$uic";

       }//if($arr_uic["uic"] != ""){
    }// if($found == "N" && $query_add != ""){ // not found in database


} //  if($query_add != ""){



    $mysqli->close();

}
else {
  $msg_info = "Invalid Date of Birth (dob)";
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

function generateUIC($fname, $lname, $dob, $char_pos){ // $char_pos :อักษรตัวที่ n ของนามสกุล เลื่อนนามสกุลไปได้เรื่อยๆ ถ้า uic ซ้ำ
  $uic = "";
  $arr_uic = array();
  $f = preg_replace('/[^A-Za-zก-ฮ]/','',$fname); // fname
  $l = preg_replace('/[^A-Za-zก-ฮ]/','',$lname); // lname
  $arr_dob = explode("-",$dob);
  if(mb_strlen($l) > $char_pos);{
    $uic .= mb_substr($f,0,1,'UTF-8'); // first fname
    $uic .= mb_substr($l,($char_pos-1),1,'UTF-8'); // first lname

    $arr_uic["d"] = str_pad($arr_dob[2], 2, '0', STR_PAD_LEFT);// date
    $arr_uic["m"] = str_pad($arr_dob[1], 2, '0', STR_PAD_LEFT);// month
    $arr_uic["y"] = substr(strval((int)$arr_dob[0] + 543), 2, 4); // year
  }
  $arr_uic["uic"]= $uic.$arr_uic["d"].$arr_uic["m"].$arr_uic["y"];
  return $arr_uic;
}

function validateDate($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
