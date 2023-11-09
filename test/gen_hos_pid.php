<?


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

echo generateHosPID($a, $b, $c);

?>

<script>

alert("new PID:"+generateHosPID("28/12/2520", "ภาณุ","เหมศรีวชิรโรจน์"));

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


function check_is_correct(str){
    str = str.substring(0,1);
    return /[^ะัาำิีึืฺุูเแโใไๅๆ็่้๊๋์]/.test(str);
}

</script>
