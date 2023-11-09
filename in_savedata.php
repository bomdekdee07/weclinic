<script>

function save_data_ajax(objData, pageURL, returnFunc){
  //alert("save_data_ajax: "+returnFunc);
  //alert("pageURL: "+pageURL);

  extendSession();
  loadingShow();

  var flag_save_success = true;
  var request = $.ajax({
      url:pageURL,
      type:'POST',
      data:objData, // should be consisted of u_mode and any data to update

      success: function(result) {
    //  alert("successx: "+result);
      //console.log("successx: "+result);

      loadingHide();

       var rtnObj = jQuery.parseJSON( result );
       if(typeof rtnObj.flag_auth !== "undefined"){

         //alert("flag_auth : "+rtnObj.flag_auth);
         if(rtnObj.flag_auth == '0'){
           flag_save_success = false;
           myModalDlgLogin("te", "Time Expired");
           /*
           alert("session expired / Please Login to system");
           gotoLogin();
           */

         }
       }
        //alert ("rtnObj : "+rtnObj.msg_error+" / "+rtnObj.msg_info);

       if(rtnObj.msg_error == ""){

         if(rtnObj.msg_info != ""){
           /*
           myModalContent("Information",
           rtnObj.msg_info,
           "info");
           */
           $.notify(rtnObj.msg_info, "info");

         }
       } //msg_err == ""
       else {
         alert("ERROR: "+rtnObj.msg_error);
         flag_save_success = false;
         $.notify(rtnObj.msg_error, "error");
       }

        returnFunc(flag_save_success, rtnObj, objData );

     }, // end success
      error: function(xhr){
        loadingHide();
        /*
        myModalContent("Error",
        "xhr.status : "+xhr.status,
        "info");
        */
        if(xhr.status != '0'){
          alert("error: "+xhr.status);
          returnFunc(flag_save_success, xhr.status, objData );
        }

      }
  });

 }


 function save_data_ajax_silent(objData, pageURL, returnFunc){
   var flag_save_success = true;
   var request = $.ajax({
       url:pageURL,
       type:'POST',
       data:objData, // should be consisted of u_mode and any data to update

       success: function(result) {
         //  alert("successx: "+result);
         console.log("successx: "+result);

          var rtnObj = jQuery.parseJSON( result );
          if(typeof rtnObj.flag_auth !== "undefined"){

            //alert("flag_auth : "+rtnObj.flag_auth);
            if(rtnObj.flag_auth == '0'){
              flag_save_success = false;
              myModalDlgLogin("te", "Time Expired");
            }
          }

          if(rtnObj.msg_error == ""){
            if(rtnObj.msg_info != ""){
              $.notify(rtnObj.msg_info, "info");
            }
          } //msg_err == ""
          else {
            alert("ERROR: "+rtnObj.msg_error);
            flag_save_success = false;
            $.notify(rtnObj.msg_error, "error");
          }

           returnFunc(flag_save_success, rtnObj, objData );

         }, // end success
         error: function(xhr){
           alert("error: "+xhr.status);
           returnFunc(flag_save_success, xhr.status, objData );
         }
   });

  }

function validateEmpty(divSaveData){

  var isValid = true;

  var sMessage = "";
  if(divSaveData != undefined){
    divSaveData = "#"+divSaveData;
  }

  $(divSaveData +" .save-data").each(function(ix,objx){
    if($(objx).data("isrequire") && $(objx).val().trim() == ""){
      sMessage += ($(objx).data("title") + " can not be empty.<br>");
      $(objx).addClass("bg-warning");
    }
  });


  if(sMessage != ""){
    isValid = false;
    $.notify(sMessage, "info");
  }


  return isValid;
}

function validateEmptyListData(divSaveData){
  //alert("validateEmptyListData  : ");
  var isValid = true;
//alert("validateEmptyListData  : "+divSaveData+" .save-data-list");
  var sMessage = "";
  if(divSaveData != undefined){
    divSaveData = "#"+divSaveData;
  }

  $(divSaveData +" .save-data-list").each(function(ix,objx){
    //alert("validate : "+$(objx).data("title"));
    if($(objx).data("isrequire") && $(objx).val().trim() == ""){
      sMessage += "ID ["+$(objx).data("rowid") +"] "+$(objx).data("title") + " can not be empty.<br>";
      $(objx).addClass("bg-warning");
    }
  });

  if(sMessage != ""){
    isValid = false;
    $.notify(sMessage, "info");
  }
  return isValid;
}


function checkDataChange(divSaveData){
  var isChanged = false;
  if(divSaveData != undefined){
    divSaveData = "#"+divSaveData;
  }
  var txt = "";
  var txt2 = "";
  $(divSaveData +" .save-data").each(function(ix,objx){
   if($(objx).data("odata") != undefined){
     var objValue = getDBMSObjValue($(objx));
     //if($(objx).data("odata").trim() != $(objx).val().trim()){
     if($(objx).data("odata") != objValue){
       txt += "[obj:"+$(objx).attr("id")+"/"+$(objx).val().trim()+"]";
       //alert("data changed : "+$(objx).data("odata")+"/"+$(objx).val().trim());
       isChanged = true;
     }
     else{
       txt2 += "[obj2:"+$(objx).attr("id")+"/"+$(objx).val().trim()+"]";
     }
   }

  });
  alert("check change : "+txt);
  alert("check change2 : "+txt2);
  //alert("flag data change : "+isChanged);
  return isChanged;
}

function getDataListChange(tblID){
  var arrListID = [];
  $("#"+tblID+" .save-data-list").each(function(ix,objx){
//alert("data list : "+$(objx).data("odata")+"/"+$(objx).val());
   if($(objx).data("odata") != undefined){

     // check if it is chk box
     if ( $(objx).is( ".chk_box" )) {
        //alert("chkbox1 : ");
        sValue = ($(objx).is(":checked"))?1:0;
        $(objx).val(sValue);
        //alert("chkbox : "+$(objx).val());
     }

     //if($(objx).data("odata").trim() != $(objx).val().trim()){
     if($(objx).data("odata") != $(objx).val().trim()){
       //alert("data list changed : "+$(objx).data("odata")+"/"+$(objx).val().trim());
       arrListID.push($(objx).data("rowid"));

     }
   }

  });

  var uniqueID = [];
  $.each(arrListID, function(i, el){
      if($.inArray(el, uniqueID) === -1) uniqueID.push(el);
  });
//alert("arrListID/uniqueID : "+arrListID+" / "+uniqueID);
  return uniqueID;
}

function urlEncode(urlText){
  if (typeof urlText === "undefined") return "";
  else if(urlText=="0") return "0";
  else if(urlText==false) return "";
  else if(urlText=="") return "";
  urlText =	encodeURIComponent(urlText);
  urlText = urlText.toString().replace(/!/g, '%21');
    urlText = urlText.toString().replace(/'/g, '%27');
    urlText = urlText.toString().replace(/\(/g, '%28');
    urlText = urlText.toString().replace(/\)/g, '%29');
    urlText = urlText.toString().replace(/\*/g, '%2A');
    //urlText = urlText.replace(/%20/g, '+')
  return (urlText);
}
function urlDecode(urlText){
  if (typeof urlText === "undefined") return "";
  else if(urlText=="0") return "0";
  else if(urlText==false) return "";
  else if(urlText=="") return "";
  return decodeURIComponent(urlText.toString().replace(/\+/g, "%20"));
}



function getWObjValue(obj){
  var sValue = "";
  if($(obj)){
    var sTagName = $(obj).prop("tagName").toUpperCase();

    if(sTagName=="INPUT"){
      if($(obj).prop("type")){
        if($(obj).prop("type").toLowerCase()=="checkbox"){
          sValue = ($(obj).prop("checked"))?1:0;
        }else if($(obj).prop("type").toLowerCase()=="radio"){
          var sName = $(obj).attr("name");
          sValue = ( $(obj).parent().find("input[name='"+sName+"']").filter(":checked").length > 0 )? $(obj).parent().find("input[name='"+sName+"']").filter(":checked").val():$(obj).data("odata");
        }else{
          sValue = $(obj).val();
        }
      }else{
        sValue = $(obj).val();
      }
    }else{
      sValue = $(obj).val();
    }

    /*
    if($(obj).hasClass("v_date") || $(obj).hasClass("showdate")){
      sValue=getDateData(sValue);
    }
    */

  }
  return sValue;
}
function getDateData(strDate){
  var tempVal = (new Date(strDate)=="Invalid Date")?"":new Date(strDate);
  return (tempVal=="0000-00-00")?"":(tempVal.getFullYear()+"-"+pad(tempVal.getMonth()+1,2)+"-"+pad(tempVal.getDate(),2));

}


function setWObjValue(obj,value){
  if($(obj).length){
    //alert($(obj).attr("data-name") );
    var sTagName = $(obj).prop("tagName").toUpperCase();

    if(sTagName=="INPUT" && ($(obj).prop("type"))){
//console.log("INPUT: "+value+ " type: "+$(obj).prop("type"));

        if($(obj).prop("type").toLowerCase()=="checkbox"){

          if(value=="1"){
            //	$(obj).attr("checked","checked");
              $(obj).prop("checked",true);
            //  console.log("chkbox: "+value);
          }
          else{
            $(obj).prop("checked",false);
          //  $(obj).prop("checked",true);
            //$(obj).removeAttr("checked");
          }
        }else if($(obj).prop("type").toLowerCase()=="radio"){
          //alert($(obj).prop("id") + " : " + value);
          $(obj).filter("[value='" + value + "']").prop('checked', true);
        }else{
          $(obj).val(value);
        }

    }else{
      $(obj).val(value);
    }
    /*
    if($(obj).hasClass("datedata") || $(obj).hasClass("showdate")){
      $(obj).val(getLongDateData(value));
    }
    */

    //$(obj).data("odata",urlEncode(value));
    $(obj).data("odata",value);
  }
}

function setOData(divSaveData){
  $("#"+divSaveData + " .save-data").each(function(i,objx){
    setWObjValue(objx, getWObjValue(objx)  );
  });
}

function setODataList(divSaveData){
  $("#"+divSaveData + " .save-data-list").each(function(i,objx){
    setWObjValue(objx, getWObjValue(objx)  );
  });
}




// set value to form main
function setFormObject(dataObj, dataID, dataValue){
  //dataObj["'"+data_ID+"'"] = {dval:"'"+dataValue+"'",odata:"'"+dataValue+"'"};
  if(dataValue == '0') dataValue = '';
  dataObj[dataID] = {dval:dataValue,odata:dataValue};
}

// set form old data
function setFormOData(dataObj){
  //dataObj["'"+data_ID+"'"] = {dval:"'"+dataValue+"'",odata:"'"+dataValue+"'"};
  //var txt = "";
  for (var key in dataObj) {
      dataObj[key]['odata']=dataObj[key]['dval'];
      //txt += key+"/"+dataObj[key]['dval']+", ";
  }
//  alert("txt : "+txt);
}

// check data changed in form
function checkDataChangeFormObject(dataObj, dataID, dataValue){
  //alert("enter "+dataID);
  if(typeof dataObj[dataID] !== 'undefined'){
    dataObj[dataID]['dval'] =  dataValue;

    var flag = true;
    if(dataValue == dataObj[dataID]['odata']){ // data not changed
    //  return false;
      flag = false;
    }
    else{
      dataValue =  dataValue.replace(/(\r\n|\n|\r)/gm, "");
      dataOldValue =  dataObj[dataID]['odata'].replace(/(\r\n|\n|\r)/gm, "");
      if(dataValue == dataOldValue)
      flag = false;
    }
    return flag;

  }
  else{ // no record in dataObj, new record
    dataObj[dataID] = {dval:dataValue,odata:''};
  }

  //alert("datachange :"+dataID+"/"+dataValue+"="+dataObj[dataID]['odata']);
  return true; // data changed
}




</script>
