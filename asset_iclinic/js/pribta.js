var aMainObj={};
$(document).ready(function(){
	$("#pribta21 .toggle-bar").unbind("click");
	$("#pribta21").on("click",".toggle-bar",function(){
		$(this).parent().find(".left-bar").toggle();
	});

	$("#pribta21 .fl-cascade-btn").unbind("click");
	$("#pribta21").on("click",".fl-cascade-btn",function(){
		$(this).parent().parent().next(".fl-cascade").toggle();
	});


	jQuery.expr[':'].Contains = function(a, i, m) {
	  return jQuery(a).text().toUpperCase()
	      .indexOf(m[3].toUpperCase()) >= 0;
	};
});

function checkEmail(sEmail){
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	return (emailReg.test(sEmail));

}

function startLoad(objCont,objLoad){
	$(objCont).hide();
	$(objLoad).show();
}

function endLoad(objCont,objLoad){
	$(objCont).show();
	$(objLoad).hide();
}


function loadDivURL(sDivName,sPage,retFunction,doneLoadFunction){
	//$( window ).off("resize.windowsresize");
	var objDiv = "";
	if(jQuery.type(sDivName) == "object"){
		objDiv = sDivName;
	}else if(sDivName.indexOf("#") < 0){
		objDiv = "#"+sDivName;
	}else{
		objDiv = sDivName;
	}


	//$("#loading").focus();

	if(sPage.indexOf("?") >=0) sPage += "&caxx=" + (new Date()).getTime();
	else  sPage += "?caxx=" + (new Date()).getTime();

	if($(objDiv).length){

		$(objDiv).html("");
		$(objDiv).load(sPage,function(responseTxt, statusTxt, xhr){
			if(checkFunction(doneLoadFunction)) doneLoadFunction();

			if(statusTxt == "success"){
				//return true;
				if(responseTxt=="E99") {
					//showLogin();
					if(checkFunction(retFunction)) retFunction(false,objDiv);
				}
				if(checkFunction(retFunction)) retFunction(true);
			}else{
				$.notify( "Error "+statusTxt ,"error");
				//return false;
				if(checkFunction(retFunction)) retFunction(true);
			}

		});
		$(objDiv).show();
	}else{

	}


}
function checkFunction(functionName){
	if (typeof functionName == 'function') return true;
	else return false;
	}
function callAjax(saveURL,aData,retFunction){
	var request = $.ajax({
	  url: saveURL,
	  method: "POST",
	  cache:false,
	  data: aData,
	});

	request.done(function( retdata ) {
		console.log(retdata);
		if(retdata!="")	var rtnObj = jQuery.parseJSON( retdata );
		if(checkFunction(retFunction)) retFunction(rtnObj,aData);
	});

	request.fail(function( jqXHR, textStatus ) {
	  	console.log(jqXHR.status);
	});
}

function createDialog(objDlg,isModal,autoOpen){
	$(objDlg).dialog({ modal: isModal,autoOpen:autoOpen,
		open: function(event, ui) {
			//$(".ui-dialog-titlebar-close", ui.dialog | ui).show();

		},
		close: function(event,ui){

		}
    });
}

function closeDlg(objThis="",sResult=""){
	if(objThis==""){
		var iDlg = 50; var bFinding = true; var objCurDlg = "";
		do {
			iDlg--;

			objCurDlg = ".divdlg[data-id='"+iDlg+"']";
			if($(objCurDlg).hasClass('ui-dialog-content')){
				bFinding=$(objCurDlg).dialog("isOpen");
				if(bFinding) {
					if(sResult!="") $(objCurDlg).find(".dlgResult").val(sResult);
					$(objCurDlg).dialog("close");
				}
			}
		}
		while (bFinding && iDlg > 0);
	}else{
		dlgThis = $(objThis).closest(".divdlg");
		iDlgId = $(dlgThis).attr("data-id");
		if(sResult!="")
			$(dlgThis).find(".dlgResult").val(sResult);
		$(dlgThis).dialog("close");


	}




}

function setDlgResult(sResult){
	var iDlg = 50; var bFinding = true; var objCurDlg = "";
	do {
		iDlg--;

		objCurDlg = ".divdlg[data-id='"+iDlg+"']";
		if($(objCurDlg).hasClass('ui-dialog-content')){
			bFinding=$(objCurDlg).dialog("isOpen");
			if(bFinding) {
				$(objCurDlg).find(".dlgResult").val(sResult);
			}
		}
	}
	while (bFinding && iDlg > 0);
}

function getDlgResult(){
	var iDlg = 50; var bFinding = true; var objCurDlg = "";
	do {
		iDlg--;

		objCurDlg = ".divdlg[data-id='"+iDlg+"']";
		if($(objCurDlg).hasClass('ui-dialog-content')){
			bFinding=$(objCurDlg).dialog("isOpen");
		}
	}
	while (bFinding && iDlg > 0);

	if(bFinding) {
		return $(objCurDlg).find(".dlgResult").val();
	}else{
		return "";
	}

}


function showDialog(dlgPage,dlgTitle,dlgHeight,dlgWidth,dlgCss,closeFunction,closeHide,doneLoadFunction){

	var iDlg = 0; var bFinding = true; var objCurDlg = "";
	do {
		iDlg++;

		objCurDlg = ".divdlg[data-id='"+iDlg+"']";
		if($(objCurDlg).hasClass('ui-dialog-content')){
			bFinding=$(objCurDlg).dialog("isOpen");
		}else{
			bFinding=false;
			let sDlg = "<div title='dialog' class='divdlg dbmsfontfamily fl-wrap-col' data-id='"+iDlg+"'><div class='fl-fix h-xxl divloading'><img src='assets/image/spinner.gif' /></div><input type='hidden' class='dlgResult' /><input type='hidden' class='dlgData' /><div class='dlgContent fl-wrap-col'></div></div>";
			$("body").append(sDlg);
			//$(".divdlg[data-id='"+iDlg+"']").asortable('refresh');
			createDialog($(".divdlg[data-id='"+iDlg+"']"),true,false);
		}

	}
	while (bFinding && iDlg < 50);



	if(dlgCss != "") $(objCurDlg).css(dlgCss);

	if(dlgHeight=="100%"){
		dlgHeight = $(window).height() - 100;
	}

	$(objCurDlg).dialog({"position":{ my: "center", at: "center", of: window },"height":dlgHeight,"width":dlgWidth, closeOnEscape: !closeHide});
	$(objCurDlg).siblings().find(".ui-dialog-title").html(decodeURI(dlgTitle));
	$(objCurDlg).siblings().find(".ui-dialog-title").css("text-align","left");

	$(objCurDlg).prev(".ui-dialog-titlebar").css({"background-color":"#0059b3","color":"white","font-size":"14px"});
	if(closeHide){
		$(objCurDlg).closest("div[role='dialog']").find(".ui-dialog-titlebar-close").hide();
	}else{
		$(objCurDlg).closest("div[role='dialog']").find(".ui-dialog-titlebar-close").show();
	}
	$(objCurDlg).css({"background-color":"#white"});
	$(objCurDlg).unbind("dialogclose");
	if(checkFunction(closeFunction)) {
		$(objCurDlg).dialog({
		   close: function(event, ui)
		    {
		    	$(objCurDlg).dialog( 'option', 'hide', 'none' );
		    	return closeFunction($(objCurDlg).find(".dlgResult").val(),dlgPage);
			}
		});
	}

	dlgPage += ((dlgPage.indexOf("?") >=0 )?"&":"?")+"dlg="+iDlg;
	$(objCurDlg).find(".dlgResult").val("");

	$(objCurDlg).dialog("open");
	$(objCurDlg).find(".divloading").show();
	loadDivURL($(objCurDlg).find(".dlgContent"),dlgPage,function(bResult){
		//if(bResult) $(objCurDlg).dialog("open");
		$(objCurDlg).find(".divloading").hide();
	},doneLoadFunction);

}


function showLogin(){
	let sUrl = "login_inc.php?hidelogo=1";
	showDialog(sUrl,"Staff Login","440","420","",function(sResult){
		//CLose function
		if(sResult=="1"){
			//$("#isLogin").val("1");
		}
	},true,function(){
		//Load Done Function

	});
}

function resetRowColor(objRows){
	$(objRows).removeClass("row-odd");
	$(objRows).removeClass("row-even");
	$(objRows).filter(":odd").addClass("row-odd");
	$(objRows).filter(":even").addClass("row-even");
}

function getDataRow(inputRow){
	let aData={}; let sCol=""; let sColPk=""; let isValid =true;
	//PK only
	$(inputRow).find(".saveinput").each(function(ix,objx){
		sKey = $(objx).attr('data-keyid');
		sIsPK = $(objx).attr("data-pk");
		sVal = $(objx).val();
		if(sIsPK==undefined || sIsPK==""){
			if(sVal!=$(objx).attr('data-odata')){
				sCol+= ((sCol=="")?"":",")+sKey;
				aData[sKey] = sVal;
			}
		}else{

			//Primary Key can't be none.
			sColPk+= ((sColPk=="")?"":",")+sKey;
			aData[sKey] = sVal;
			if(sVal=="") isValid=false;

			//$.notify("Key:"+sCol);
		}
	});

	if(sColPk!="") aData["colpk"] = sColPk;
	if(sCol!="" && isValid) aData["col"] = sCol;
	else aData="";


	return aData;
}
