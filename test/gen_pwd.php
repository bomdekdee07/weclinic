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



for($i=0; $i<100; $i++){
  echo generateTextPassword(6)."<br>";
  //echo generateNumberPassword(6)."<br>";
}

?>


<script>

var x="xxx";
alert("x is :"+x);

//alert("pop");
checkWindowPeriod('2019-11-23', 42, 42);

test();
function test(){
  alert("x2 is :"+x);
}
function checkWindowPeriod(scheduleDate, dateBefore, dateAfter){

   //alert("pop "+dateBefore+" / "+dateAfter);
   var result = "";
   var arrDate = scheduleDate.split("-");

   var currentDate = new Date();
   var begDate = new Date();
   var endDate = new Date();

   begDate.setFullYear(arrDate[0], parseInt(arrDate[1])-1, arrDate[2]);
   endDate.setFullYear(arrDate[0], parseInt(arrDate[1])-1, arrDate[2]);

   begDate.setDate(begDate.getDate() - dateBefore);
   endDate.setDate(endDate.getDate() + dateAfter);

   alert("cur: "+currentDate+" / beg: "+begDate+" / end: "+endDate);

   if(currentDate < begDate){
     result = "before";
   }
   else if(currentDate > endDate){
     result = "after";
   }
   return result;
}

</script>
