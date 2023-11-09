<?

$str1 = "19-11-29";
$str2 = "20-01-30";
if($str2 > $str1){
	echo "more ";
}
else {
	echo "ok";
}

$u_name = "0001";
$u_pwd = "x12Vgj";

/*
$u_name = "phanu@prevention-trcarc.org";
$u_pwd = "xxx1122";
*/
$u_pwd2 = "3n+Pfw4/7J+l6s6DjSrFDQ==";


$ENC_VI = ("TRCGOODDAYARCPRE");
$SEC_CODE = ("A[INIPOI20-29[|".$u_name);

$u_hash = openssl_encrypt($u_name,"AES-256-CBC",$SEC_CODE,0,$ENC_VI);
$u_pwd1 = openssl_encrypt($u_pwd,"AES-256-CBC",$SEC_CODE,0,$ENC_VI);

$u_pwd2 = openssl_decrypt($u_pwd2,"AES-256-CBC",$SEC_CODE,0,$ENC_VI);

echo "<br>$u_name/$u_pwd1";
echo "<br>$u_name/$u_pwd2";


?>
