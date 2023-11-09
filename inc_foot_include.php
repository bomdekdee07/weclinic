<script>

  function gotoLogin(){
		//alert("gotoLogin");
		window.location = "login.php";
	}
  function gotoTop(){
    //window.scrollTo(0, 0);
    $("body,html").animate(
     {
       scrollTop: 0
     },300 //speed
     );
	}


	function getDataObjValue(obj){
	  var sValue = "";

	  if($(obj).length){
	    var sTagName = $(obj).prop("tagName").toUpperCase();

	    if(sTagName=="INPUT"){
	      if($(obj).prop("type")){
	        if($(obj).prop("type").toLowerCase()=="checkbox"){
	          //sValue = ($(obj).is(":checked") )?"1":"0";
	          sValue = ($(obj).is(":checked") )?$(obj).val():"";

	        }else if($(obj).prop("type").toLowerCase()=="radio"){
	          var sName = $(obj).attr("name");
	          sValue = ( $(obj).parent().find("input[name='"+sName+"']").filter(":checked").length > 0 )? $(obj).parent().find("input[name='"+sName+"']").filter(":checked").val():$(obj).attr("data-odata");

	        }else{
	          sValue = $(obj).val();
	        }
	      }else{
	        sValue = $(obj).val();
	      }
	    }else if(sTagName=="SELECT"){
	      sValue=$(obj).find(":selected").val();
	      if($(obj).find(":selected").text()=="") sValue="";
	    }else{
	      sValue = $(obj).val();
	    }


			if($(obj).hasClass("v_date")){
	        var arrDate = sValue.split("/");
					if(arrDate.length == 3){
	          //sValue = arrDate[0]+"/"+ arrDate[1]+"/"+ (parseInt(arrDate[2]) - 543);
	          sValue = (parseInt(arrDate[2]) - 543)+"-"+arrDate[1]+"-"+ arrDate[0] ;
	          //alert("date : "+sValue);
					}
			}


	/*
	    if($(obj).hasClass("datedata") || $(obj).hasClass("showdate")|| $(obj).hasClass("datagroupdate")){
	      sValue=getDateData(sValue);
	      if($(obj).attr("data-odata").length > 10){
	        //sValue += " 00:00:00";
	      }else{

	      }
	    }
	*/


	  }
	  //alert($(obj).attr("name") + " : " + sValue);
	  return sValue;
	}

	function changeToThaiDate(sValue){ // eg. 2019-09-10 -> 10/09/2562

		var arrDate = sValue.split("-");
		if(arrDate.length == 3){
      if(sValue != "0000-00-00") {
        sValue = arrDate[2]+"/"+ arrDate[1]+'/'+(parseInt(arrDate[0]) + 543);
      }
      else{
        sValue = "";
      }
			//alert("date : "+sValue);
		}
		return sValue;
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


  function pad(num, size) { // numbers with leading zeros
      var s = "000000000" + num;
      return s.substr(s.length-size);
  }

  function getTodayDateTH(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!

    var yyyy = today.getFullYear()+543;
    if (dd < 10) {
      dd = '0' + dd;
    }
    if (mm < 10) {
      mm = '0' + mm;
    }
    return dd + '/' + mm + '/' + yyyy;
  }


  function getTodayDateEN(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
      dd = '0' + dd;
    }
    if (mm < 10) {
      mm = '0' + mm;
    }

    return  yyyy+"-"+mm+"-"+dd;
  }

    function getPartialDateTH(){
      var today = new Date();
      /*
      var dd = today.getDate();
      var mm = today.getMonth() + 1; //January is 0!
*/
      var yyyy = today.getFullYear()+543;

      return  'dd/mm/' + yyyy;
    }
/*
  function initPartialDate(dataObj){
    if($(dataObj).val() == ''){
      $(dataObj).val(getTodayDateTH());
    }
  }
  */
  function initPartialDate(dataObj){
    if($(dataObj).val() == ''){
      $(dataObj).val(getPartialDateTH());
    }
  }

  // check thai date
  function checkPartialDate(dataObj)
  {
      var selectedDate = $(dataObj).val();
      if(selectedDate == '')
          return false;

      var regExp = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
      var dateArray = selectedDate.match(regExp); // is format OK?

      if (dateArray == null){
          return false;
      }

      day = dateArray[1];
      month= dateArray[3];
      year = dateArray[5];

    //  alert("dd/mm/yyyy : "+day+"/"+month+"/"+year);

      if (month > 12){
          $(dataObj).notify("เดือนไม่ถูกต้อง");
          return false;
      }else if (day> 31){
          $(dataObj).notify("วันที่ไม่ถูกต้อง");
          return false;
      }else if ((month==4 || month==6 || month==9 || month==11) && day ==31){
          $(dataObj).notify("วันที่ไม่ถูกต้อง");
          return false;
      }else if (month == 2){

        var enYear = parseInt(year-543);
        var isLeapYear = (enYear % 4 == 0 && (enYear % 100 != 0 || enYear % 400 == 0));
        if (day> 29 || (day ==29 && !isLeapYear)){
            $(dataObj).notify("วันที่ไม่ถูกต้อง");
            return false
        }
      }

      if(year < 2470){ // validate thai year
        $.notify("กรุณาใส่ปี พ.ศ.","error");
        return false;
      }



      return true;
  }




</script>
