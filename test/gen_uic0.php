<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<?
/*
$lname = "แซ่ตั้ง";
if( preg_match("/^[ะัาำิีึืฺุูเแโใไๅๆ็่้๊๋์]$/", $lname) ) {
echo "done" ;
} else {
echo "not done" ;
}
*/

$lname = "แซ่ตั้ง";

echo "$lname<br>";


$l = preg_replace('/[^A-Za-zกขฃคฅฆงจฉชซฌญฎฏฐฑฒณดตถทธนบปผฝพฟภมยรฤลฦวศษสหฬอฮ]/','',$lname); // lname
//$l = utf8_encode($l);
//$l = iconv('TIS-620','UTF-8//ignore',$l);

//$l =  str_replace("\uFFFD","",$l);
echo "<br>".strlen($l);

echo "$l";
/*
echo "<br><br>";
$msg = "ADsss";
if( preg_match("/^[0-9A-Zก-ฮ]$/", $msg) ) {
echo "done" ;
} else {
echo "not done" ;
}
*/

$text = "ุ";
$text = str_replace("ุ","",$text);
$text = preg_replace('/[^A-Za-zก-ฮ]/','',$text); // lname
if(preg_match('/^[A-Za-zกขฃคฅฆงจฉชซฌญฎฏฐฑฒณดตถทธนบปผฝพฟภมยรฤลฦวศษสหฬอฮ]+$/', $text))
{
  echo "<br> yes";
}
else{
  echo "<br> no";
}


?>
