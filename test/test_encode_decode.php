

<?
$ENC_VI = ("TRCGOODDAYARCUCP");
$SEC_CODE = "UCP2TRAINING";


function encodeSingleLink($link)
{
  global $ENC_VI;
  global $SEC_CODE;

  $link = openssl_encrypt($link,"AES-256-CBC",$SEC_CODE,0,$ENC_VI);
  $link = str_replace("+","aBcD",$link);
  return $link; // link encode
}

// link decode
function decodeSingleLink($link)
{
  global $ENC_VI;
  global $SEC_CODE;
//echo "link is : ".$link;
  $link = str_replace(" ","+",$link);
  $link = str_replace("aBcD","+",$link);
  $x = openssl_decrypt($link,"AES-256-CBC",$SEC_CODE,0,$ENC_VI);
  $arr = array();

  return $x;
}


echo encodeSingleLink("atthanee21");
echo "<br>";
echo decodeSingleLink("/Ax254n8JOvfFs6axrjbhg==");


?>
