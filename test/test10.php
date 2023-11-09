<?


echo "xxx ";
?>

<script>
alert("enter 1");
var data_sdhos_baseline=[];
alert("enter 2 "+data_sdhos_baseline);

data_sdhos_baseline['x1'] = {dval:'xx1',odata:'xxx1'};
data_sdhos_baseline['x2'] = {dval:'xx2',odata:'xxx2'};
setFormObject(data_sdhos_baseline,'genderidentity_txt','xx1');
setFormObject(data_sdhos_baseline,'sexualorientation9txt','xx2');


var data_sdhos_baseline2={x1:{dval:'xx1',odata:'xx1'},x2:{dval:'xx2',odata:'xx2'}};

var txt="";
	for (var key in data_sdhos_baseline2) {
			txt += "["+key+" value : "+data_sdhos_baseline2[key]+"]";
	}
	alert("cData : "+txt);
	alert("cData2 : "+data_sdhos_baseline['x1']['dval']);
  	alert("cData3 : "+data_sdhos_baseline['genderidentity_txt']['dval']);

alert("enter 3xx "+data_sdhos_baseline.length+"/"+data_sdhos_baseline);
function setFormObject(dataObj, dataID, dataValue){
//  data_sdhos_baseline[] = {dval:dataValue,odata:dataValue};
  //data_sdhos_baseline["'"+dataID+"'"] = {dval:dataValue,odata:dataValue};
  dataObj[dataID] = {dval:"'"+dataValue+"'",odata:"'"+dataValue+"'"};
//  dataObj[dataID] = {dval:dataValue,odata:dataValue};
}
/*
function setFormObject(dataObj, dataID, dataValue){
  dataObj["'"+data_ID+"'"] = {dval:"'"+dataValue+"'",odata:"'"+dataValue+"'"};
}
*/
function setDataFormObject(dataObj, dataID, dataValue){
  dataObj[data_ID]['dVal'] =  dataValue;
}

function checkDataChangeFormObject(dataObj, dataID, dataValue){
  alert("check data :"+dataID+"/"+dataValue);
  dataObj[data_ID]['dVal'] =  dataValue;
  if(dataValue == dataObj[data_ID]['odata']){ // data not changed
    return false;
  }
  return true; // data changed
}


</script>
