
<?


$sUID= isset($_GET["uid"])?$_GET["uid"]:"";
$sColdate= isset($_GET["collect_date"])?$_GET["collect_date"]:"";
$sColtime= isset($_GET["collect_time"])?$_GET["collect_time"]:"";

$txt_uid_info = "";

//include('../in_db_conn.php');
//include(realpath($_SERVER["DOCUMENT_ROOT"])."/weclinic/in_db_conn.php");

//include('../in_db_conn.php');

	$query = "SELECT P.uic, P.sex, P.date_of_birth,
  P_HGT.data_result as height, P_WGT.data_result as weight,
  MD.s_name as md, CL.s_name as cl, RN.s_name as rn
  FROM patient_info P
  LEFT JOIN p_data_result P_HGT ON (P_HGT.uid=P.uid AND P_HGT.data_id='heigh'
    AND P_HGT.collect_date=? AND P_HGT.collect_time=?)
  LEFT JOIN p_data_result P_WGT ON (P_WGT.uid=P.uid AND P_WGT.data_id='cn_weight'
    AND P_WGT.collect_date=P_HGT.collect_date AND P_WGT.collect_time=P_HGT.collect_time)

  LEFT JOIN p_data_result p_staff_md ON (p_staff_md.uid=P.uid AND p_staff_md.data_id='staff_md'
    AND p_staff_md.collect_date=P_HGT.collect_date AND p_staff_md.collect_time=P_HGT.collect_time)
  LEFT JOIN p_staff MD ON (MD.s_id = p_staff_md.data_result)

  LEFT JOIN p_data_result p_staff_cl ON (p_staff_cl.uid=P.uid AND p_staff_cl.data_id='staff_cl'
    AND p_staff_cl.collect_date=P_HGT.collect_date AND p_staff_cl.collect_time=P_HGT.collect_time)
  LEFT JOIN p_staff CL ON (CL.s_id = p_staff_cl.data_result)

  LEFT JOIN p_data_result p_staff_rn ON (p_staff_rn.uid=P.uid AND p_staff_rn.data_id='staff_rn'
    AND p_staff_rn.collect_date=P_HGT.collect_date AND p_staff_rn.collect_time=P_HGT.collect_time)
  LEFT JOIN p_staff RN ON (RN.s_id = p_staff_rn.data_result)

  WHERE P.uid=? LIMIT 1
	";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('sss', $sColdate,$sColtime,$sUID);

	//echo "query : $query";
	if($stmt->execute()){
		$stmt->bind_result($uic, $sex, $date_of_birth, $hgt, $wgt, $staff_md, $staff_cl, $staff_rn);
		$stmt->store_result();
	  if ($stmt->fetch()) {
      $affect_row = $stmt->affected_rows;
      if($affect_row > 0){
          $arr_age = getAge($date_of_birth);
          $md_cl_rn = ($staff_md != "")?"MD:$staff_md ":"";
          $md_cl_rn .= ($staff_cl != "")?"CL:$staff_cl ":"";
          $md_cl_rn .= ($staff_rn != "")?"RN:$staff_rn ":"";

          $txt_uid_info = "
             <div class='fl-wrap-row ptxt-s12 px-1 div-lab-uid-info'
               data-uid='$sUID' data-coldate='$sColdate' data-coltime='$sColtime'>
               <div class='fl-fill ptxt-b'>

                  <div class='fl-wrap-row ph-40 ptxt-white' >
                      <div class='fl-fix fl-mid bg-mdark1 pbtn btn-lab-uid-edit' style='min-width:50%; max-width:50%;'>
                          UID: $sUID
                      </div>
                      <div class='fl-fill fl-mid bg-mdark2'>
                          UIC: $uic
                      </div>
                  </div>
                  <div class='fl-wrap-row ph-40' >
                      <div class='fl-fill fl-mid bg-msoft2'>
                          ".getSex($sex). " สูง: $hgt cm. | หนัก: $wgt Kgs.
                      </div>
                  </div>
                  <div class='fl-wrap-row ph-40' >
                      <div class='fl-fill fl-mid bg-msoft2'>
                          อายุ ".$arr_age['age']." (DOB: $date_of_birth)
                      </div>
                  </div>

               </div>


             </div>
             <div class='fl-wrap-row fl-mid ptxt-s12 px-1 bg-ssoft2'>

               $md_cl_rn
             </div>


          ";
      }
    }
  }

  $stmt->close();








 $mysqli->close();
 echo $txt_uid_info;



 function getAge($dob){
 	$dob_a = explode("-", $dob);
 	$today_a = explode("-", date("Y-m-d"));
 	$dob_d = $dob_a[2];$dob_m = $dob_a[1];$dob_y = $dob_a[0];
 	$today_d = $today_a[2];$today_m = $today_a[1];$today_y = $today_a[0];
 	$years = $today_y - $dob_y;
 	$months = $today_m - $dob_m;
 	$days=$today_d - $dob_d;
 	if ($today_m.$today_d < $dob_m.$dob_d) {
 		$years--;
 		$months = 12 + $today_m - $dob_m;
 	}

 	if ($today_d < $dob_d){
 		$months--;
 	}

 	$firstMonths=array(1,3,5,7,8,10,12);
 	$secondMonths=array(4,6,9,11);
 	$thirdMonths=array(2);

 	if($today_m - $dob_m == 1){
 		if(in_array($dob_m, $firstMonths)){
 			array_push($firstMonths, 0);
 		}elseif(in_array($dob_m, $secondMonths)) {
 			array_push($secondMonths, 0);
 		}elseif(in_array($dob_m, $thirdMonths)){
 			array_push($thirdMonths, 0);
 		}
 	}

  $arr_age = array();
  $arr_age['years_old'] = $years;
  $arr_age['age'] = "$years ปี $months เดือน ".abs($days)." วัน";

 	return $arr_age;
 }

 function getSex($sex){
 	$aR=array("","ชาย (Male)","หญิง (Female)","มีสรีระทั้งชายและหญิง (Intersex)")	;

 	return (isset($aR[$sex])?$aR[$sex]:"");
 }

?>



<script>
$(document).ready(function(){

  $(".div-lab-uid-info .btn-lab-uid-edit").off("click");
  $(".div-lab-uid-info .btn-lab-uid-edit").on("click",function(){
    let sUID = $('.div-lab-uid-info').attr('data-uid');
  //  var sUrl = window.location.protocol + "//" + window.location.host+'/pribta21/';

    sUrl = "lab/mnu_lab_view_uid_info.php?uid="+sUID+"&hideedit=1";
    showDialog(sUrl,"View Data [UID:"+sUID+"]","600","99%","",function(sResult){
       if(sResult != ""){
       }
   },false,function(){
   });
  });

});
</script>
