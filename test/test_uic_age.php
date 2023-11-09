<?
$uic = "ศข190247";
//$uic = "AA170421";
//$uic = urlencode($uic);
$arr = calculateUICAge($uic);
echo "age: ".$arr['Y']." - ".$arr['M']." - ".$arr['D'];

$dateOfBirth = "01-08-1985";
$today = date("Y-m-d");
$diff = date_diff(date_create($dateOfBirth), date_create($today));
echo '<br>Age is '.$diff->format('%y-%m-%d');


function calculateUICAge($uic_param){
  //mb_substr(ข้อความ,เริ่มต้นตัดที่อักขระ,จำนวนอักขระที่ตัด,'UTF-8');
   $dob_date = mb_substr($uic_param,2,2, 'UTF-8') ;
   $dob_month = mb_substr($uic_param,4,2, 'UTF-8') ;
   $dob_year = "25".mb_substr($uic_param,6,2, 'UTF-8') ;
   $dob_year = ((int) $dob_year)-543;

   $dob = $dob_year."-$dob_month-$dob_date";
   $today = date("Y-m-d");
   $diff = date_diff(date_create($dob), date_create($today));

   $arr_age = array();
   $arr_age['Y'] = $diff->format('%y');
   $arr_age['M'] = $diff->format('%m');
   $arr_age['D'] = $diff->format('%d');

   return $arr_age;
}
?>


<script>


</script>
