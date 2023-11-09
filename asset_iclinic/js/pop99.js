/*

function loadLink(sUrl, selector_dest, selector_loader){

//	console.log("loadlink99: "+sUrl);
	startLoad(selector_dest,selector_loader);
	selector_dest.html("");
	selector_dest.load(sUrl,function(){
		endLoad(selector_dest,selector_loader);
	});

}
*/

function setDivAuthComponent(divSelector){

	if(!$(divSelector).hasClass('allow_view'))
	$(divSelector+' .auth-view').hide();

	if(!$(divSelector).hasClass('allow_data')){
		$(divSelector+' .auth-data').hide();
	}

  if(!$(divSelector).hasClass('allow_admin')){
    $(divSelector+' .auth-admin').hide();
  }

}


function getWDataCompValue(obj){
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
  }
  return sValue;
}



function getWODataComp(obj){ // get odata
	var sValue = "";
  if($(obj)){
    var sTagName = $(obj).prop("tagName").toUpperCase();
    if(sTagName=="INPUT"){
      if($(obj).prop("type")){
        if($(obj).prop("type").toLowerCase()=="radio"){
          var sName = $(obj).attr("name");

          sValue =  $(obj).parent().find("input[name='"+sName+"']").attr("data-odata");
        }else{
          sValue = $(obj).attr("data-odata");
        }
      }else{
        sValue = $(obj).attr("data-odata");
      }
    }else{
      sValue = $(obj).attr("data-odata");
    }
  }
  return sValue;
}


function setWODataComp(obj, val){ // set odata

  if($(obj)){
    var sTagName = $(obj).prop("tagName").toUpperCase();
    if(sTagName=="INPUT"){
      if($(obj).prop("type")){
        if($(obj).prop("type").toLowerCase()=="radio"){
          var sName = $(obj).attr("name");
					$('['+sName+']').attr("data-odata", val);
        }else{
          $(obj).attr("data-odata", val);
        }
      }else{
        $(obj).attr("data-odata", val);
      }
    }else{
      $(obj).attr("data-odata", val);
    }
  }
}
