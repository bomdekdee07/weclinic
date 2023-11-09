
<script>

var date1="10/08/2563";
calTestDate(date1);
var diff = dateDiffCal('2020-08-11', '2020-08-15')
alert("diff "+diff);
function calTestDate(date1){
  var sent_date = changeToEnDate(date1);
  alert("date send "+sent_date);
  var date = new Date(sent_date);
  // add a day
date.setDate(date.getDate() + 10);

const dateTimeFormat = new Intl.DateTimeFormat('en', { year: 'numeric', month: 'short', day: '2-digit' })
const [{ value: month },,{ value: day },,{ value: year }] = dateTimeFormat .formatToParts(date )
var str = `${day}-${month}-${year }`;
alert("str: "+str);

}

function changeToEnDate(sValue){ // eg. 10/09/2562 -> 2019-09-10
	var arrDate = sValue.split("/");
	if(arrDate.length == 3){
		//sValue = arrDate[0]+"/"+ arrDate[1]+"/"+ (parseInt(arrDate[2]) - 543);
		sValue = (parseInt(arrDate[2]) - 543)+"-"+arrDate[1]+"-"+ arrDate[0] ;
		//alert("date : "+sValue);
	}
	else{
		sValue ="0000-00-00";
	}
	return sValue;
}

function dateDiffCal(date1, date2){
  dt1 = new Date(date1);
  dt2 = new Date(date2);
  return Math.floor((Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate()) - Date.UTC(dt1.getFullYear(), dt1.getMonth(), dt1.getDate()) ) /(1000 * 60 * 60 * 24));

}


</script>
