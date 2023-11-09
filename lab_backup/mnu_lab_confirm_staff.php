
<?


$txt_row = "";

include('../in_db_conn.php');
	$query = "SELECT s_id, s_name, s_remark, license_lab
  FROM p_staff where license_lab != '' ORDER BY s_remark
	";

	$stmt = $mysqli->prepare($query);
	//echo "query : $query";
	if($stmt->execute()){
		$stmt->bind_result($s_id, $s_name, $s_remark, $license_lab);
		$stmt->store_result();
	  while ($stmt->fetch()) {
			$txt_row .= addRowData($s_id, $s_name, $s_remark, $license_lab);
    } // while
  }
	else{

	}
  $stmt->close();

function addRowData($s_id, $s_name, $s_remark, $license_lab){
	$txt_row ="
	<div class='fl-wrap-row fl-mid ph50 p-row ptxt-s10 px-1'>
	 <div class='fl-fix pw200'>
		 <input type='text' class='save-data' data-odata='$s_remark' data-sid='$s_id' value='$s_remark'  placeholder='SEQ NO.'>
	 </div>
	 <div class='fl-fill'>$s_name</div>
	 <div class='fl-fix pw300'>$license_lab</div>
	</div>
	";
	return $txt_row;
} // addRowData

echo "
<div id='div-lab-confirm'>
	<div class='fl-wrap-row fl-mid ph-50 ptxt-s12 px-1 bg-sdark2 ptxt-white'>
		<div class='fl-fix pw100'>SEQ No.</div>
		<div class='fl-fill'>Lab Staff Name</div>
		<div class='fl-fix pw300'>Lab License</div>
	</div>
	$txt_row
	<div class='fl-wrap-row ph50 ptxt-s12 px-1 mt-4'>
	  <div class='fl-fix pw200'></div>
		<div class='fl-fill fl-mid pbtn btn-update-seq pbtn-blue'>UPDATE & RELOAD</div>
		<div class='fl-fill fl-mid spinner' style='display:none;' >Wait</div>
	  <div class='fl-fix pw200'></div>
	</div>
</div>
";

?>



<script>
$(document).ready(function(){

 $(".btn-update-seq").click(function(){
	let btnclick = $(this);
  let sLstdata = [];
	$("#div-lab-confirm .save-data").each(function(ix,objx){
		 if($(objx).val() != $(objx).attr('data-odata')){
       let objdata = {};
			 objdata['s_id'] = $(objx).attr('data-sid');
			 objdata['s_remark'] = $(objx).val();
			 sLstdata.push(objdata);
     }
	});


	 var aData = {
	 		u_mode:"update_confirm_lab_staff_seq",
	 		lst_data:	sLstdata
	 };

	 startLoad(btnclick, btnclick.next(".spinner"));

	 callAjax("lab/db_lab_test_result.php",aData,function(rtnObj,aData){
	 			endLoad(btnclick, btnclick.next(".spinner"));

	 			if(rtnObj.res == 1){
	 					$("#div-lab-confirm").parent().load('lab/mnu_lab_confirm_staff.php');
	 			}
	 			else{
	 				$.notify("Fail to update.", "error");
	 				if(rtnObj.msg_error != "")
	 				$.notify(rtnObj.msg_error, "error");
	 			}

	  });// call ajax

 }); // .btn-update-seq



});



</script>
