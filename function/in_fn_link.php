<?
/*
Link encode / decode
use for encode and decode the link

*/
$ENC_VI = ("TRCGOODDAYARCUCP");
$SEC_CODE = "UCP2TRAINING";

// link encode
function encodeLink($param1, $param2)
{
  global $ENC_VI;
  global $SEC_CODE;

  $x1 = "$param1:$param2"; // eg. trainee_id:course_id, course_id:test_type_id (for prepost_test)
  $link = openssl_encrypt($x1,"AES-256-CBC",$SEC_CODE,0,$ENC_VI);
  $link = str_replace("+","aBcD",$link);
  return $link; // link encode
}

// link decode to trainee_id , course_id
function decodeLink($link)
{
  global $ENC_VI;
  global $SEC_CODE;
//echo "link is : ".$link;
  $link = str_replace(" ","+",$link);
  $link = str_replace("aBcD","+",$link);
  $x = openssl_decrypt($link,"AES-256-CBC",$SEC_CODE,0,$ENC_VI);
  $arr = array();
  if($x != ""){
    $arr = explode(":",$x);
  }
  return $arr;  // $arr[0] = param1, $arr[1] = param2
}

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

function createRandomCode($char_amt)
{
  $string = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-';
  $string_shuffled = str_shuffle($string);
  $random_str = substr($string_shuffled, 1, $char_amt);

  return $random_str;
}



/*
$link = "9AnMS/ZbWotfWbOh6FDngw==";
$arr = decodeLink($link);
echo "1:".$arr[0]." 2:".$arr[1];

$link2 = encodeLink($arr[0], $arr[1]);
echo "link :$link / $link2";
*/

?>
