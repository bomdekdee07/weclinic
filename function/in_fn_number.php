<?
/*
//generate random group of numbers
function randomGroupNumber($min, $max, $quantity) {
    $numbers = range($min, $max);

    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}

//generate single random number
function randomNumber($min, $max) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return $numbers[0];
}
*/

//generate random group of numbers
function randomGroupNumber($min, $max, $quantity) {
    $quantity += 10;
    $arr = array();
    for($i=0;$i<$quantity;$i++){
      $arr[] = rand($min,$max);
    }
    $arr = array_unique($arr);
    return $arr;
}


//generate single random number
function randomNumber($min, $max) {
    return rand($min,$max);
}



//generate format  eg. number=2, num_digit=4   result: 0002
function formatDigit($number, $num_digit) {
	  //echo "++$number, $num_digit";
    return str_pad($number, $num_digit, '0', STR_PAD_LEFT);
}

//generate single random pincode 6 digit
function randomSinglePinCode() {
    $numbers = randomNumber(0, 999999);
		return formatDigit($numbers, 6);
}




//echo randomSinglePinCode();






?>
