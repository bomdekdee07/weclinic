<?


function generateTextPassword($digit_num) {
  $string = 'abcdefghijkmnpqrstuvwxyz123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
  $string_shuffled = str_shuffle($string);

  return substr($string_shuffled, 0, $digit_num);
}
function generateNumberPassword($digit_num) {
  $string = '123456789012345678901234567890';
  $string_shuffled = str_shuffle($string);

  return substr($string_shuffled, 0, $digit_num);
}


?>
