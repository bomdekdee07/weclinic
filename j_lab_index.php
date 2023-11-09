<!DOCTYPE html>
<html>
<head>
<title>Jeng's Lab Order Overview</title>
<? include("inc_head_include.php"); ?>
</head>
<style>
	body{
		padding: 0;
		margin: 0;
		height: 100vh;
		width:100%;
		overflow: hidden;
		display:flex;
	}

	.div-fix{
		flex:0;
	}
	.div-auto{
		flex:1;
	}
	.div-flex-row{
		display:flex;
		flex-direction: column;
	}
	.div-flex-col{
		display:flex;
		flex-direction: row;
	}
	.ibtn{
		cursor: pointer;
		filter: brightness(80%);
	}
	.imgloader{

	}
	#divLab table tbody{
		background-color: white;
	}
	#divLab table tbody tr:hover{
		filter: brightness(80%);
	}
	#divLab table tbody tr:nth-child(even){
		background-color: silver;
	}
	#divLab table tbody tr:nth-child(odd){
		background-color: lightgrey;
	}
</style>
<body>
	<div id='divLab' class='div-flex-row div-auto' style='overflow: hidden;height: 100%'>
		<div class='div-fix div-flex-col ' style='height: 30px;border-bottom:1px solid red '>
			<div class='div-auto'>
				<span class='fa'>ค้นหา</span>
				<input class='fa' id='txtUID' placeholder="PXX-XXXXX" maxlength="9" size='10' />
				<i id='btnSearch' class="fa fa-search ibtn " > FIND</i>
			</div>
		</div>
		<div class='div-flex-row div-auto divinfo' style='border-bottom:1px solid red;max-height: 120px' id='divLabOrder'>
		</div>
		<div class='div-flex-col div-auto divinfo' id='divLabSpecimen' style='overflow: hidden'>

		</div>
		<img id='imgLoader' src='image/spinner.gif' style='width:64px;display:none' />
	</div>
	
</body>
<script>
	$(function(){
		$("#divLab").on("click","#btnSearch",function(){
			let sUid = validateUid(  $("#txtUID").val().toUpperCase()  );
			if(sUid.length!=9){
				alert(sUid);
				return;
			}

			$("#divLab .divinfo").html("");
			sUrl="j_in_lab_order.php?uid="+sUid;

			$("#divLab #divLabOrder").html("");
			$("#divLab #divLabOrder").hide();
			$("#divLab #imgLoader").show();

			$("#divLab #divLabOrder").load(sUrl,function(){
				$("#divLab #divLabOrder").show();
				$("#divLab #imgLoader").hide();
			});
		});

/*
<button class='btnrestorelab' data-labid='".$lab_id."' data-barcode='".$barcode."' data-serial_no='".$lab_serial_no."' data-labstatus='".$lab_result_status."' data-result='".$lab_result."' >Restore</button> */

		$("#divLabSpecimen").on("click",".btnrestorelab",function(){
			sUid = $(this).attr('data-uid');
			sColDate = $(this).attr('data-coldate');
			sColTime = $(this).attr('data-coltime');
			sBar = $(this).attr('data-barcode');
			sSerial = $(this).attr('data-serial_no');
			sStatus = $(this).attr('data-labstatus');
			sLabId = $(this).attr('data-labid');
			sResult = $(this).attr('data-result');
			var fd = new FormData();
			fd.append("u_mode","restore_lab_log"); 
			fd.append("barcode",sBar);
			fd.append("labid",sLabId);
			fd.append("serialno", sSerial);
			fd.append("labstat", sStatus);
			fd.append("result", sResult);
			fd.append("uid",sUid);
			fd.append("coldate",sColDate);
			fd.append("coltime",sColTime);
			let sObjBtn = $(this);

			$(sObjBtn).hide();
			$(sObjBtn).next(".spinner").show();
			$.ajax({ 
			    url: 'lab/j_db_fix_lab.php', 
			    type: 'post', 
			    data: fd, 
			    contentType: false, 
			    processData: false, 

			    success: function(response){ 
			        if(response != 0){ 
			          
			        } 
			        else{ 
			            $.notify("check error","error"); 
			        } 
			      $(sObjBtn).show();
			      $(sObjBtn).next(".spinner").hide();
			      
			    } 
			}); 

		});

		$("#divLabOrder").on("click",".btnViewUid",function(){
			let sUid = $(this).attr("data-uid");
			let sColDate = $(this).attr("data-coldate");
			let sColTime = $(this).attr("data-coltime");

			$("#divLab #divLabSpecimen").html("");
			$("#divLab #divLabSpecimen").hide();
			$("#divLab #imgLoader").show();
			sUrl="j_in_lab_specimen.php?uid="+sUid+"&coldate="+sColDate+"&coltime="+sColTime;
			$("#divLab #divLabSpecimen").load(sUrl,function(){
				$("#divLab #divLabSpecimen").show();
				$("#divLab #imgLoader").hide();
			});
		});


		function validateUid(sUid){
			sRet = sUid;
			if(sUid.length != 9){
				sRet = "UID format is incorrect";
			}else{
				aTmp = sRet.split("");
				if(aTmp[0]=="P" && aTmp[3]=="-"){

				}else{
					sRet = "UID format is incorrect";
				}				
			}
			return sRet;
		}
	});

</script>
</html>