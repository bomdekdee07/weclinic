<?

$fname = "แสมชาย"; $lname="แซ่ตั้ง"; $dob="1977-12-28"; $char_pos="0";
//$fname = "abcd";
echo "is_valid: ".checkFirstChar($fname)."<br>";

$f = getFirstChar($fname);
$l = getFirstChar($lname);
echo "<br>$f/$l";

/*
$k = 0;
$count= strlen($fname);
while($k<$count){

}
*/
  /*
  for($k=0; $k<20; $k++){
  if(checkFirstChar(mb_substr($fname,$k,($k+1),'UTF-8'))){
    $f=mb_substr($fname,0,1,'UTF-8');
    echo "enter<br>";
    break;
  }
  $k++;
}
*/



  /*
while($f == ""){
//  if(($k+1)<strlen($fname)){
    echo "enter0<br>";
    if(checkFirstChar(mb_substr($fname,$k,($k+1),'UTF-8'))){
      $f=mb_substr($fname,0,1,'UTF-8');
      echo "enter<br>";
      break;
    }
    $k++;
    $fname = mb_substr($fname,$k,($k+1),'UTF-8');

//  }

}//while
*/
//echo "fx: $f";


/*


function generateUIC($fname, $lname, $dob){ // $char_pos :อักษรตัวที่ n ของนามสกุล เลื่อนนามสกุลไปได้เรื่อยๆ ถ้า uic ซ้ำ
  $uic = "";

  $f = ""; $k=0;
  while($f == ""){
    if(($k+1)<strlen($fname)){
      if(checkFirstChar(mb_substr($fname,$k,($k+1),'UTF-8'))){
        $f=mb_substr($fname,0,1,'UTF-8');
        break;
      }
      $k++;
      $fname = mb_substr($fname,$k,($k+1),'UTF-8');
    }
  }//while

  $l = ""; $k=0;
  while($f == ""){
    if(($k+1)<strlen($fname)){
      if(checkFirstChar(mb_substr($fname,$k,($k+1),'UTF-8'))){
        $f=mb_substr($fname,0,1,'UTF-8');
        break;
      }
      $k++;
      $fname = mb_substr($fname,$k,($k+1),'UTF-8');
    }
  }//while


  $arr_dob = explode("-",$dob);
  $arr_uic = array();
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
function validateDate($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

*/



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
  if(preg_match('/^[A-Za-zกขฃคฅฆงจฉชซฌญฎฏฐฑฒณดตถทธนบปผฝพฟภมยรฤลฦวศษสหฬอฮ]+$/', $str_name))
  {
    return true;
  }
  else{
    return false;
  }
}
?>
