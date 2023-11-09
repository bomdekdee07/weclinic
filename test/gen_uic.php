<?
$lname = "แซ่ตั้ง";
$l = preg_replace('/[^A-Za-zก-ฮ]/','xxx',$lname); // lname
$l2 = preg_replace('/[^ะัาำิีึืฺุูเแโใไๅๆ็่้๊๋์]/','',$lname); // lname
echo "lname: $l / $l2 <br><br>";

$fname = "สมชาย"; $lname="-aแซ่ตั้ง"; $dob="1977-12-28"; $char_pos="0";

generateUIC($fname, $lname, $dob, $char_pos);


function generateUIC($fname, $lname, $dob, $char_pos){ // $char_pos :อักษรตัวที่ n ของนามสกุล เลื่อนนามสกุลไปได้เรื่อยๆ ถ้า uic ซ้ำ
  $uic = "";
  $arr_uic = array();
  $f = preg_replace('/[^A-Za-zก-ฮ]/','',$fname); // fname
  $l = preg_replace('/[^A-Za-zก-ฮ]/','',$lname); // lname
  echo "<br>$f/$l<br>";
  $arr_dob = explode("-",$dob);
  if(mb_strlen($l) > $char_pos){
    $uic .= mb_substr($f,0,1,'UTF-8'); // first fname
    $uic .= mb_substr($l,($char_pos-1),1,'UTF-8'); // first lname

    $arr_uic["d"] = str_pad($arr_dob[2], 2, '0', STR_PAD_LEFT);// date
    $arr_uic["m"] = str_pad($arr_dob[1], 2, '0', STR_PAD_LEFT);// month
    $arr_uic["y"] = substr(strval((int)$arr_dob[0] + 543), 2, 4); // year
  }
  $arr_uic["uic"]= $uic.$arr_uic["d"].$arr_uic["m"].$arr_uic["y"];
  echo $arr_uic["uic"];

  return $arr_uic;

}



function generateHosPID($birthDate, $firstName, $surName) {
  $newPID = "";
  $arr_birthDate = explode("-",$birthDate);
  $year = strval((int)$arr_birthDate[0] + 543);

  $newPID .= substr($firstName, 0, 1);
  $newPID .= substr($surName, 0, 1);
  $newPID .= $arr_birthDate[2];
  $newPID .= $arr_birthDate[1];
  $newPID .= substr($year, 2, 4);

  return $newPID;
}

$a = "2019-02-13";
$b = "sมชาย";
$c = "gระสบ";

//echo generateHosPID($a, $b, $c);

?>

<script>

//alert("new PID:"+generateHosPID("28/12/2520", "ภาณุ","เหมศรีวชิรโรจน์"));

//checkName("มีหห");

function generatePID(thai_birthDate, fname, lname){
  var newPID = "";

  var arrDate = thai_birthDate.split("/");

  newPID += getFirstThaiChar(fname);
  newPID += getFirstThaiChar(lname);

  newPID += arrDate[0].substring(0, 2);
  newPID += arrDate[1].substring(0, 2);
  newPID += arrDate[2].substring(2, 4);

  return newPID;
}

function generateHosPID(thai_birthDate, fname, lname){
  var newPID = "";
  var arrDate = thai_birthDate.split("/");

  newPID += getFirstThaiChar(fname);
  newPID += getFirstThaiChar(lname);
  newPID += getHosBirthEncode(thai_birthDate);

  return newPID;
}

function checkName(strName){
  if(check_is_correct(strName)){
    alert("true name "+ strName.substring(0,1));

  }
  else{
    alert("not name");
  }
}

function check_is_username(str){
    return /[A-Za-z0-9]{4,20}/.test(str);
}


function getFirstThaiChar(str){
  var rtnStr = "";
  var i;
  for(i=0; i< str.length; i++){
    if(check_is_correct(str.substring(i,i+1))){
      rtnStr = str.substring(i,i+1);
      break;
    }
  }//for

  return rtnStr;
}
function check_is_correct(str){
    str = str.substring(0,1);
    return /[^ะัาำิีึืฺุูเแโใไๅๆ็่้๊๋์]/.test(str);
}
function getHosBirthEncode(birthDate){
  var arrDate = birthDate.split("/");
  var birth_encode = "";
  birth_encode += arrDate[0].substring(0, 2);
  birth_encode += arrDate[1].substring(0, 2);
  birth_encode += arrDate[2].substring(2, 4);

  // replace all number to alphabet
  birth_encode = birth_encode.replace(/1/g, "A");
  birth_encode = birth_encode.replace(/2/g, 'B');
  birth_encode = birth_encode.replace(/3/g, "C");
  birth_encode = birth_encode.replace(/4/g, "D");
  birth_encode = birth_encode.replace(/5/g, "E");
  birth_encode = birth_encode.replace(/6/g, "F");
  birth_encode = birth_encode.replace(/7/g, "G");
  birth_encode = birth_encode.replace(/8/g, "H");
  birth_encode = birth_encode.replace(/9/g, "M");
  birth_encode = birth_encode.replace(/0/g, "N");

  return birth_encode;
}




</script>
