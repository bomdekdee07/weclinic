<script>

function validateInput(divSaveData){
  //alert("validateInput: "+divSaveData);
  var isValid = true;
  var sMessage = "";
  if(divSaveData != undefined){
    divSaveData = "#"+divSaveData;
  }

  $(divSaveData +" .save-data").removeClass("bg-warning");

  $(divSaveData +" .v-no-blank").each(function(ix,objx){
    if(!validateBlank($(objx).val())){
      sMessage += "กรุณากรอกข้อมูล <b>"+($(objx).data("title") + "</b><br>");
      $(objx).addClass("bg-warning");
      $(objx).notify("กรุณากรอกข้อมูล "+$(objx).data("title"),"error");
      $.notify("กรุณากรอกข้อมูล "+$(objx).data("title"),"error");
    }
  });


  $(divSaveData +" .v-email").each(function(ix,objx){
    if(!validateEmail($(objx).val())){
      sMessage += "<b>"+($(objx).data("title") + "</b> ไม่ถูกต้อง<br>");
      $(objx).addClass("bg-warning");
      $(objx).notify( $(objx).data("title") +" ไม่ถูกต้อง","error");
      $.notify( $(objx).data("title") +" ไม่ถูกต้อง","error");
    }

  });

  $(divSaveData +" .v_date").each(function(ix,objx){
    //if(validateBlank($(objx).val()){
      if(!validateDate($(objx).val())){
        sMessage += "ข้อมูลวันที่ <b>"+($(objx).data("title") + " ไม่ถูกต้อง</b><br>");
        $(objx).addClass("bg-warning");
        $(objx).notify( $(objx).data("title") +" ไม่ถูกต้อง","error");
        $.notify("ข้อมูลวันที่ "+$(objx).data("title") +" ไม่ถูกต้อง","error");
      }
    //}

  });



  if(sMessage != ""){
    isValid = false;
  //  alert(""+sMessage);
  }
  return isValid;
}

function validateEmail2(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function validateEmail(email) {
  if(email.trim() != ""){
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }
  else{
    return false;
  }

}



function validatePhoneNo(phone_no) {
  if(phone_no.trim() != ""){
    var re = /^[0-9-]*$/;
    return re.test(phone_no);
  }
  else{
    return false;
  }

}

function validateBlank(txt) {
  if(txt.trim() == '') return false;
  else return true;
}


function validateDate(dateValue)
{
    var selectedDate = dateValue;
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

    if (month < 1 || month > 12){
        return false;
    }else if (day < 1 || day> 31){
        return false;
    }else if ((month==4 || month==6 || month==9 || month==11) && day ==31){
        return false;
    }else if (month == 2){
        var enYear = parseInt(year-543);
        var isLeapYear = (enYear % 4 == 0 && (enYear % 100 != 0 || enYear % 400 == 0));
        if (day> 29 || (day ==29 && !isLeapYear)){
            return false
        }
    }

    if(year < 2470){ // validate thai year
      //$.notify("กรุณาใส่ปี พ.ศ.","error");
      return false;
    } 


    return true;
}


function validatePartialDate(dateValue)
{
    var selectedDate = dateValue;
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
        return false;
    }else if (day> 31){
        return false;
    }else if ((month==4 || month==6 || month==9 || month==11) && day ==31){
        return false;
    }else if (month == 2 && day >29){
      return false;
    }

    if(year < 2490){ // validate thai year
      $.notify("กรุณาใส่ปี พ.ศ.","error");
      return false;
    }


    return true;
}


</script>
