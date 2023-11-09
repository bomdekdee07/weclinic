<?
session_start();
$sUser = "";
if(isset($_SESSION)){ // there is session
  	$sUser = (isset($_SESSION["s_id"])?$_SESSION["s_id"]:"");
  	
}else{
	//no session
	echo("ERROR no SESSION");
	exit();
}


include_once("../in_auth_db.php");
include_once("../in_db_conn.php");

//Get Project in control.
$sProjList = "";
$query = "SELECT proj_id FROM p_staff_auth WHERE s_id = ? AND allow_admin=1;";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s',$sUser);
$sqlProjList = "";
if($stmt->execute()){
  $stmt->bind_result($proj_id);


  while ($stmt->fetch()) {
    $sProjList .= "<option value='".$proj_id."'>".$proj_id." : ".$proj_id."</option>";
    $sqlProjList .= "'".(($sqlProjList=="")?"":",").$proj_id."'";
  }
  
}

if($sProjList==""){
	echo("You don't have any permission to access this page. Please contact Super Administrator if you think this is a mistake.");
	$mysqli->close();
	exit();
}

//Clinic
$query = "SELECT PPC.clinic_id,PC.clinic_name,proj_id FROM p_project_clinic PPC 
LEFT JOIN p_clinic PC 
ON PC.clinic_id = PPC.clinic_id
WHERE PC.clinic_status=1 ";

$stmt = $mysqli->prepare($query);
$sClinicList = "";
if($stmt->execute()){
  $stmt->bind_result($clinic_id,$clinic_name,$proj_id);


  while ($stmt->fetch()) {
    $sClinicList .= "<option value='".$clinic_id."' data-projid='".$proj_id."'>".$clinic_id." : ".$clinic_name."</option>";
  }

}

//status
$sVStatList = "";
$query = "SELECT status_id,status_name,status_note,status_order FROM p_visit_status ORDER BY status_order;";
$stmt = $mysqli->prepare($query);
if($stmt->execute()){
  $stmt->bind_result($status_id,$status_name,$status_note,$status_order);


  while ($stmt->fetch()) {
    $sVStatList .= "<option value='".$status_id."' title='".$status_id."' >".$status_name." : ".$status_note."</option>";
  }
}

//job
$sJobList = "";
$query = "SELECT job_id,job_name,job_desc FROM p_staff_job ORDER BY job_id;";
$stmt = $mysqli->prepare($query);
if($stmt->execute()){
  $stmt->bind_result($job_id,$job_name,$job_desc);


  while ($stmt->fetch()) {
    $sJobList .= "<option value='".$job_id."' title='".$job_desc."' >".$job_name."</option>";
  }
}

$mysqli->close();


?>
<style>
.divgroup{
	margin: 5px 0px;
}
</style>
<div id='divProjAdmin'>
	ระบบจัดการโครงการ สำหรับผู้ดูแลโครงการ เท่านั้น
	<H1>Project Admin Form For Project : <SELECT id='ddlProject'><option value=''>(--Select--)</option><? echo($sProjList); ?></SELECT></H1>
	<div id='divControlPanel' style='display:none'>
		<div>Type :<SELECT id='ddlType'><option value=''>(--Select--)</option>
			<option value='1'>ย้ายค่าย (ย้ายคลินิก) - Subject Move to other clinic</option>
			<option value='2'>เปลี่ยนสถานะ Visit - Change subject Visit</option>
			<option value='3'>เปิด Extra Visit - Add Extra Visit</option>
			<!-- option value='4'>ย้ายข้อมูล - Move Data From Visit to Visit</option -->
			<option value='5'>เปลี่ยนตำแหน่งเจ้าหน้าที่ - Change staff position</option>
			<option value='6'>เพิ่ม User เจ้าหน้าที่ - Add User</option>
		</SELECT></div>
		<div id='divControlItems' style='display:none'>
			<div id='divUserSearch' class='divgroup group5'>ค้นหา USER ที่ต้องการแก้ไข : <input id='txtUser' placeholder="User ID" /> <button class="btn btn-search" type="button" id="btnSearchUser" ><i class="fas fa-search" ></i></button>
			</div>

			<div id='divPIDSearch' class='divgroup group1 group2 group3'>ค้นหา PID ที่ต้องการแก้ไข : <input id='txtPID' placeholder="PID" /> <button class="btn btn-search" type="button" id="btnSearchPID" ><i class="fas fa-search" ></i></button>
			</div>

			<div id='divUserResult' class='divgroup group5'>เลือก User จากผลการค้นหา : <SELECT id='ddlUser'><option value=''>(--Select--)</option></SELECT><input id='txtUserName' /><span class='divgroup group5'> ตำแหน่งปัจจุบัน <SELECT id='ddlCurJob'><option value=''>(--Select--)</option><? echo($sJobList); ?></SELECT></span></div>

			<div id='divNewJob' class='divgroup group5'>เลือก ตำแหน่งใหม่ที่ต้องการ <SELECT id='ddlNewJob'><option value=''>(--Select--)</option><? echo($sJobList); ?></SELECT>
			</div>


			<div id='divPIDResult' class='divgroup group1 group2 group3'>เลือก PID จากผลการค้นหา : <SELECT id='ddlPID'><option value=''>(--Select--)</option></SELECT><span class='divgroup group1'>  - Clinic ปัจจุบัน <SELECT id='ddlCurClinic'><option value=''>(--Select--)</option><? echo($sClinicList); ?></SELECT></span></div>

			<div id='divVisit' class='divgroup group2 group3'>เลือก Visit ที่ต้องการ : <SELECT id='ddlVID'><option value=''>(--Select--)</option></SELECT> <span class='divgroup group2  group3'> - Status ปัจจุบัน <SELECT id='ddlCurVisStatus'><option value=''>(--Select--)</option><? echo($sVStatList); ?></SELECT></span></div>

			<div class='divgroup group3'>
				วันนัดใหม่ <input id='txtSchDate' placeholder="Schedule Date" />
			</div>


			<div id='divCurStatus' class='divgroup group2'>เลือก Status ใหม่ที่ต้องการ <SELECT id='ddlVisStatus'><option value=''>(--Select--)</option><? echo($sVStatList); ?></SELECT>
			</div>

			<div id='divNewClinic' class='divgroup group1'>เลือก Clinic ปลายทาง <SELECT id='ddlNewClinic'><option value=''>(--Select--)</option><? echo($sClinicList); ?></SELECT>
			</div>

			<div id='divRequestNote' class='divgroup group1 group2 group3 group5'>Note หรือ เหตุผล : <input id='txtNote' size='100' />

			</div>
			<div id='divButtons'>
				<button class="btn btn-save divgroup group1 group2 group3 group5" type="button" id="btnReqSave" ><i class="fas fa-save" ></i> Save</button>
			</div>
		</div>

		<div id='divMessage'></div>
	</div>


</div>

<script>
	$(document).ready(function(){
		$('#ddlCurClinic option').hide();    
		$("#divProjAdmin").on("change","#ddlProject",function(){
			if($(this).val()==""){
				$("#divControlItems").hide();
			}else{
				$("#divControlPanel").show();
				$("#ddlNewClinic option[value!='']").hide();
				$("#ddlNewClinic option[data-projid='"+$(this).val()+"']").show();
			}
			
		});
		$("#divProjAdmin").on("click","#btnSearchPID",function(){
			var sPID = $("#txtPID").val();
			searchPID(sPID);
		});

		$("#divProjAdmin").on("click","#btnSearchUser",function(){
			var sUser = $("#txtUser").val();
			searchUser(sUser);
		});

		function searchUser(sUser){
			if(sUser=="" || sUser.length < 3){
				$("#txtUser").notify("Please try longer User or more than 3 chars.","error");
				return;
			}
			sProjId = $("#ddlProject").val();
			$("#ddlUser").load("w_admin/in_opt_user_search.php?user="+sUser+"&projid="+sProjId,function(){
				
			});
		}

		function searchPID(sPID){
			if(sPID=="" || sPID.length < 3){
				$("#txtPID").notify("Please try longer PID or more than 4 chars.","error");
				return;
			}
			sProjId = $("#ddlProject").val();
			$("#ddlPID").load("w_admin/in_opt_pid_search.php?pid="+sPID+"&projid="+sProjId,function(){
				
			});
		}

		function clearDDL(){
			$("#ddlPID").empty();
			$("#ddlCurClinic").val("");
			$("#ddlNewClinic").val("");
			$("#ddlVID").empty();
			$("#ddlCurVisStatus").val("");
		}
		$("#divProjAdmin").on("change","#ddlUser",function(){
			sDType = $("#ddlType").val();
			sProjId = $("#ddlProject").val();
			sTempUser  = $(this).val();

			if(sDType==5){
				sCurJob = $("#ddlUser option[value='"+sTempUser+"']").attr("data-job");
				$("#ddlCurJob option").hide();
				$("#ddlCurJob option[value='"+sCurJob+"']").show();
				$("#ddlNewJob option").show();
				$("#ddlNewJob option[value='"+sCurJob+"']").hide();
				$("#ddlCurJob").val(sCurJob);
				$("#ddlNewJob").val("");

			}
		});

		$("#divProjAdmin").on("click","#btnReqSave",function(){
			var sUID = $("#ddlPID").val();	
			var sProjId = $("#ddlProject").val();
			var sDType = $("#ddlType").val();
			var sNote = ($("#txtNote").val());
			var aData = {mode:sDType,projid:sProjId,uid:sUID,reqnote:sNote,reqid:1};

			if(sUID==""){
				$("#ddlPID").notify("Please select PID","error");
				return;
			}
			if(sProjId==""){
				$("#ddlProject").notify("Please select Project","error");
				return;
			}

			if(sDType==1){
				var sClinicId = $("#ddlNewClinic").val();
				if(sClinicId==""){
					$("#ddlNewClinic").notify("Please select New Clinic","error");
					return;
				}
				aData.cid = sClinicId;
			}else if(sDType==2){
				var sNewStatus = $("#ddlVisStatus").val();
				sVid = $("#ddlVID").val();
				if(sNewStatus==""){
					$("#ddlVisStatus").notify("Please select New Status","error");
					return;
				}
				aData.statid = sNewStatus;
				aData.vid = sVid;
			}else if(sDType==3){
				var sNewDate = $("#txtSchDate").val();
				sVid = $("#ddlVID").val();
				if(sNewDate==""){
					$("#txtSchDate").notify("Please Enter Date for Extra Visit","error");
					return;
				}
				aData.schdate = sNewDate;
				aData.vid = sVid;
			}else if(sDType==5){
				var sNewJob = $("#ddlNewJob").val();
				var sID = $("#ddlUser").val();
				var sCID = $("#ddlUser option[value='"+sID+"']").attr("data-cid");
				if(sID==""){
					$("#ddlUser").notify("Please Select User","error");
					return;
				}
				if(sNewJob==""){
					$("#ddlNewJob").notify("Please Select New Job","error");
					return;
				}
				sSCID = $("#ddlUser option[value='"+sID+"']").attr("data-scid");
				aData.sid = sID;
				aData.scid = sSCID;
				aData.job = sNewJob;
				aData.cid = sCID;
			}


	
			
			var request = $.ajax({
				url: "w_admin/a_save_request.php",
				method: "POST",
				cache:false,
				data: aData,
				dataType: "html"
			});
			request.done(function( retdata ) {

			if(retdata.indexOf("ERROR")>=0){
			  $.notify(retdata);
			}else{
			  $.notify("Data Saved","success");
			}

			});

			request.fail(function( jqXHR, textStatus ) {
			  $.notify( "Request failed: " + textStatus,"warn");
			});
		});

		$("#divProjAdmin").on("change","#ddlVID",function(){
			sDType = $("#ddlType").val();
			sProjId = $("#ddlProject").val();
			sTempVID  = $(this).val();

			if(sDType==2 || sDType==3){
				sCurStatus = $("#ddlVID option[value='"+sTempVID+"']").attr("data-vstatus");
				$("#ddlCurVisStatus option").hide();
				$("#ddlCurVisStatus option[value='"+sCurStatus+"']").show();
				$("#ddlVisStatus option").show();
				$("#ddlVisStatus option[value='"+sCurStatus+"']").hide();
				$("#ddlCurVisStatus").val(sCurStatus);
				$("#ddlVisStatus").val("");

			}
		});

		$("#divProjAdmin").on("change","#ddlPID",function(){
			if($(this).val()==""){
				
			}else{
				var sDType = $("#ddlType").val();

				sTempPID = $(this).val();
				sProjId = $("#ddlProject").val();

				if(sDType==1){
					
					sClinic = $("#ddlPID option[value='"+sTempPID+"']").attr("data-clinic");
					$("#ddlCurClinic option[value!='']").hide();
					$("#ddlCurClinic option[value='"+sClinic+"'][data-projid='"+sProjId+"']").show();
					$("#ddlNewClinic option[data-projid='"+sProjId+"']").show();
					$("#ddlNewClinic option[value='"+sClinic+"'][data-projid='"+sProjId+"']").hide();
					$("#ddlCurClinic").val(sClinic);
					$("#ddlNewClinic").val("");
				}else if(sDType==2 || sDType==3){

					$("#ddlVID").load("w_admin/in_opt_vid_list.php?uid="+sTempPID+"&projid="+sProjId,function(){
						
					});
				}

				//$('#ddlCurClinic option:nth-child(0)').attr('selected', true);
			}
		});



		$("#divProjAdmin").on("change","#ddlType",function(){
			clearDDL();
			if($(this).val()=="6"){
				$("#divMessage").html("กรุณาติดต่อเจ้าหน้าที่ระบบสำหรับเพิ่มผู้ใช้งาน. Please contact super administrator for add more user to the system.");
				return;
			}

			$("#divControlItems").show();
			$(".divgroup").hide();
			if($(this).val()=="1"){
	

			}else if($(this).val()=="2"){
				

			}else if($(this).val()=="3"){

			}else if($(this).val()=="4"){

			}else if($(this).val()=="5"){

			}
			$(".group"+$(this).val()).show();
		});
	});

</script>