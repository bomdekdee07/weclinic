<?

$sSID = (isset($_GET["sid"])?$_GET["sid"]:"");

if($sSID==""){
	echo("Please select S_ID to assign authorization.");
	exit();
}

$sProjList = "";


include_once("../in_db_conn.php");

$query = "SELECT proj_id,proj_name FROM p_project UNION SELECT mod_id,mod_desc FROM p_module_list";
$stmt = $mysqli->prepare($query);

if($stmt->execute()){
  $stmt->bind_result($proj_id,$proj_name);

  $sProjList = "";
  while ($stmt->fetch()) {
    $sProjList .= "<option value='".$proj_id."'>".$proj_id.":".$proj_name."</option>";
  }
  $mysqli->close();
}
?>


<style>
.JStableOuter table {
  position: relative;
  width: 100%;
  background-color: #fff;
  border-collapse: collapse;
  font-family: arial;
  display: block;
  height: 320px;
  overflow: scroll;
}
.JStableOuter { max-width:1170px; margin:auto; border:1px solid #999; }

/*thead*/
.JStableOuter  thead {
  position: relative;
  /*display: block;*/ /*seperates the header from the body allowing it to be positioned*/
  overflow: visible;
}

.JStableOuter  thead th {
  background-color: #fff;
 /* min-width: 120px;*/
  height: 32px;
  padding: 3px 15px 0;
  font-size: 13px;
  vertical-align: top;
  position: relative;
  box-shadow: 0 1px 0px 1px #999;
}
.JStableOuter  thead th p { margin: 5px 0; font-weight: normal; }

.JStableOuter  thead th:nth-child(1) {/*first cell in the header*/
  position: relative;
 /* display: block;*/ /*seperates the first cell in the header from the header*/
  background-color: #fff;
  z-index: 99;
  border-right: 1px solid #999;
  box-shadow: 0 1px 1px 1px #999;
  min-width: 120px;
}

.JStableOuter  thead tr {/*first cell in the header*/
  position: relative;

}
.JStableOuter  tbody { /*border-top: 1px solid #999;*/}
.JStableOuter  tbody td {
  background-color: #fff;
  /*min-width: 120px;*/
  border: 1px solid #999;
  padding: 0 15px;
  min-width: 80px;
  font-size: 13px;
  box-shadow: 0 1px 0px 1px #999;
  text-align: center;
  align-items: middle;
}
.JStableOuter  thead tr th:nth-child(odd) {
	background: silver;
}
.JStableOuter  tbody tr td:nth-child(odd) {
	background: silver;
}

.JStableOuter tbody tr td:nth-child(1) {  /*the first cell in each tr*/
  position: relative;
  /*display: block;*/ /*seperates the first column from the tbody*/
  height: 40px;
  background-color: #fff;
  box-shadow: 0 0px 1px 1px #999;
}
.tableOuter {
    max-width: 800px;
    overflow: auto;
 }

.data_changed{
	background:red;
}

</style>
<div id='divStaffAuthAdd'>
  <? echo($sSID); ?> | <SELECT id='ddlProjList'>
    <option value=''>(--Proj or Module--)</option>
    <? echo($sProjList); ?>
  </SELECT> <button id='btnAddProj' class="btn btn-sm btn-info" type="button" ><i class="fa fa-plus"></i> Add</button>
</div>
<div class="JStableOuter" >
    <table id='tblStaffAuth' data-sid='<? echo($sSID); ?>'>
      <thead>
        <tr>
          <th>Proj_Id/Module</th>
          <th>view</th>
          <th>enroll</th>
          <th>schedule</th>
          <th>data</th>
          <th>data_log</th>
          <th>lab</th>
          <th>export</th>
          <th>query</th>
          <th>delete</th>
          <th>Backdate</th>
          <th>admin</th>
        </tr>
      </thead>
      <tbody id='tblStaffAuthBody'>

        <tr><td colspan='*'><img src='image/spinner.gif' /></td></tr>
      </tbody>
    </table>
</div>
<div>
	<button id='btnSaveAuth' class="btn btn-sm btn-info" type="button" ><i class="fa fa-save"></i> Save</button>
</div>

<script>
var sLoading = "<tr><td colspan='*'><img src='image/spinner.gif' /></td></tr>";
$(document).ready(function() {
  var sID = $("#tblStaffAuth").attr("data-sid");

  loadStaffBody(sID);

  function loadStaffBody(s_ID){
      var sUrl = "w_admin/in_user_auth_list.php?sid="+s_ID;
  
    $.ajax({
      type:"GET",
      beforeSend:function(){
      $("#tblStaffAuth").find("#tblStaffAuthBody").html(sLoading);
      },
      url:sUrl,
      success:function( retdata ) {
        $("#tblStaffAuth").find("#tblStaffAuthBody").html(retdata);
        scrollTable();
        refreshDDL();
      }
    });

  }
  


    

  function scrollTable(){

      $('.JStableOuter table').scroll(function(e) { 

        $('.JStableOuter thead').css("left", -$(".JStableOuter tbody").scrollLeft()); 
        $('.JStableOuter thead th:nth-child(1)').css("left", $(".JStableOuter table").scrollLeft() -0 ); 
        $('.JStableOuter tbody td:nth-child(1)').css("left", $(".JStableOuter table").scrollLeft()); 

        $('.JStableOuter thead').css("top", -$(".JStableOuter tbody").scrollTop());
        $('.JStableOuter thead tr th').css("top", $(".JStableOuter table").scrollTop()); 

      });    
  }

  $("#btnAddProj").on("click",function(){
    var sProjId = $("#ddlProjList").val();
    if(sProjId=="") return;
    var sUrl = "w_admin/a_save_uauth.php?mode=a&sid="+sID+"&pjid="+sProjId;

    $.ajax( sUrl)

    .done(function( retdata ) {

      //$("#tblStaffAuth").find("#tblStaffAuthBody").find("img").remove();
      if(retdata.indexOf("ERROR")>=0){
        $.notify(retdata);
      }else{
        loadStaffBody(sID);
      }
    })
    .fail(function() {
      $.notify("Error calling page");
    })
  });

  $("#btnSaveAuth").on("click",function(){

      if($("#tblStaffAuthBody").find(".data_changed").size() >0){
          var aData = {mode:"mup",sid:sID};
          var aTempObjArray = [];

          $("#tblStaffAuthBody").find("tr").each(function(key,objTr){
            if($(objTr).find(".data_changed").size()>0){
              var sUpRow = "pjid:"+ $(objTr).find(".tdprojid").attr('data-projid');
              $(objTr).find(".data_changed").find("input").each(function(cellId,objChk){
                sUpRow += ","+$(objChk).attr("data-chktype")+":"+(($(objChk).attr("data-odata")=="0")?"1":"0");
              });
               aTempObjArray.push(sUpRow);
            }
          });

          aData.objArray = aTempObjArray;

          var request = $.ajax({
            url: "w_admin/a_save_uauth.php",
            method: "POST",
            cache:false,
            data: aData,
            dataType: "html"
          });
          request.done(function( retdata ) {

            if(retdata.indexOf("ERROR")>=0){
              $.notify(retdata+"\r\n Please try again.");
            }else{
              updateDataChanged();
              $.notify("Data Saved","success");
            }

          });
           
          request.fail(function( jqXHR, textStatus ) {
              $.notify( "Request failed: " + textStatus,"warn");
          });



       }else{
          $.notify("No data changed.","warning");
       }
  });

  function updateDataChanged(){
    $("#tblStaffAuthBody").find(".data_changed").each(function(ix,objTd){
        $(objTd).find("input").each(function(ir,objChk){
           var sNewVal = (($(objChk).attr("data-odata")=="0")?"1":"0");
           $(objChk).attr("data-odata",sNewVal);
        });
        $(objTd).removeClass(".data_changed");
        $(objTd).css("background-color","");
    });

  }

	$("#tblStaffAuth").on("change","input[type='checkbox']",function(e){
		var sProjId = $(this).closest("tr").find(".tdprojid").attr("data-projid");
		var sSID = $("#tblStaffAuth").attr('data-sid');

  		var sOData = $(this).attr("data-odata");
  		var sNewVal = (($(this).is(":checked") )?"1":"0");
  		if(sOData==sNewVal){
  			//Same Value
        $(this).closest("td").css("background-color","");
  			$(this).parent().removeClass("data_changed");
  		}else{
  			//New Value
  			$(this).closest("td").css("background-color","red");

        $(this).parent().addClass("data_changed");

  		}



		/*
  		var sUrl = "w_admin/in_user_auth_list.php?sid="+sID;
  		$.ajax( sUrl)
		.done(function( retdata ) {
		

		})
		.fail(function() {
			alert( "error loading" );
		})
		.always(function() {
			//alert( "complete " );
		});
		*/
	});

  
  function refreshDDL(){

    $("#ddlProjList").find("option").show();
    $("#ddlProjList").val("");
    $("#tblStaffAuthBody").find(".tdprojid").each(function(ind,objx){

      var sProjId = $(objx).attr("data-projid");
      $("#ddlProjList option[value='"+sProjId+"']").hide();
    });
  }
});

</script>