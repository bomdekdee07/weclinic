
<?


$sUID= isset($_GET["uid"])?$_GET["uid"]:"";
$sColdate= isset($_GET["coldate"])?$_GET["coldate"]:"";
$sColtime= isset($_GET["coltime"])?$_GET["coltime"]:"";

$txt_uid_info = "";

include('../in_db_conn.php');
	$query = "SELECT P.uic, P.sex, P.date_of_birth,
  P_HGT.data_result as height, P_WGT.data_result as weight, PLR.lab_result as serum
  FROM patient_info P
  LEFT JOIN p_data_result P_HGT ON (P_HGT.uid=P.uid AND P_HGT.data_id='heigh'
    AND P_HGT.collect_date=?)
  LEFT JOIN p_data_result P_WGT ON (P_WGT.uid=P.uid AND P_WGT.data_id='cn_weight'
    AND P_WGT.collect_date=P_HGT.collect_date)
  LEFT JOIN p_lab_result PLR  ON (PLR.uid=P.uid AND PLR.lab_id='CREA'
    AND PLR.collect_date=P_HGT.collect_date)
    WHERE P.uid=? LIMIT 1
	";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('ss', $sColdate,$sUID);

	//echo "query : $query";
	if($stmt->execute()){
		$stmt->bind_result($uic, $sex, $date_of_birth, $hgt, $wgt, $serum);
		$stmt->store_result();
	  if ($stmt->fetch()) {
      $affect_row = $stmt->affected_rows;
      if($affect_row > 0){
          $arr_age = getAge($date_of_birth);
          $years_old = $arr_age['years_old'];
          $sel_male = ($sex == 1)?"selected":"";
          $sel_female=($sex == 2)?"selected":"";

          $txt_uid_info = "
             <div class='fl-wrap-row ptxt-s12 px-1'>
               <div class='fl-fill ptxt-b'>

                  <div class='fl-wrap-row ph-40 ptxt-white' >
                      <div class='fl-fix fl-mid bg-mdark1' style='min-width:50%; max-width:50%;'>
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

                  <div class='fl-wrap-row ph-50'>
                     <div class='fl-fill fl-mid bg-sdark2 ptxt-b ptxt-white'>
                           Creatinine Clearance Calculation
                     </div>
                  </div>
                  <div class='fl-wrap-row ph-50 bg-ssoft2 ptxt-b py-1'>
                     <div class='fl-fix pw100'>
                           Sex:<br>
                           <select class='crea-calc ddlGender'>
                             <option value='' disable>-Select-</option>
                             <option value='1' $sel_male>Male</option>
                             <option value='2' $sel_female>Female</option>
                           </select>
                     </div>
                     <div class='fl-fix pw100'>
                           Age (Years):<br> <input type='number' size='5' class= 'crea-calc age' value='$years_old' \>
                     </div>
                     <div class='fl-fix pw100'>
                           Weight (kgs):<br> <input type='number' size='7' class= 'crea-calc wgt' value='$wgt' \>
                     </div>
                     <div class='fl-fix pw100'>
                           Hight (cm):<br> <input type='number' size='7' class= 'crea-calc hgt' value='$hgt' \>
                     </div>
                     <div class='fl-fix pw100'>
                           Serum (mg/dL):<br> <input type='number' size='7' class= 'crea-calc serum' value='$serum' \>
                     </div>
                     <div class='fl-fill fl-mid pbtn pbtn-blue btn-crea-calc'>
                           Calculate
                     </div>
                  </div>
                  <div class='fl-wrap-row ph-150 bg-sdark1 ptxt-s14 ptxt-b ptxt-white'>
                     <div class='fl-fix pw150 '>
                           CREA:
                     </div>
                     <div class='fl-fill fl-mid crea-result crea-main'>

                     </div>
                  </div>
                  <div class='fl-wrap-row ph-150 bg-ssoft1 ptxt-s14 ptxt-b'>

                     <div class='fl-fix pw150 '>
                            CREA REF:<br>
                            <button onclick='CopyToClipboard(\"crea_ref\")' >Copy Text</button>
                     </div>
                     <div id='crea_ref' class='fl-fill px-4 crea-result crea-ref'>

                     </div>
                  </div>

               </div>
             </div>




          ";
      }
    }
  }

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

 calculateCREA();
 $(".btn-crea-calc").click(function(){
    calculateCREA();

 }); // btn_save_lab_result



});

function calculateCREA(){
  let flag_valid = 1;
  $('.crea-calc').each(function(ix,objx){
      if($(objx).val() == ''){
        $(objx).notify("Please insert number.", "error");
        flag_valid = 0;
      }
  });

  if(flag_valid == 1){
    let sex = $('.crea-calc.ddlGender').val();
    let age = $('.crea-calc.age').val();
    let weight = $('.crea-calc.wgt').val();
    let height = $('.crea-calc.hgt').val();
    let serum = $('.crea-calc.serum').val();

    let crea_male = ((140-age)*weight)/(72*serum);
    let crea_female = crea_male * 0.85 ;

    let crea_thai_male = (175)*(Math.pow(serum, -1.154))*(Math.pow(age, -0.203))*1.219 ;
    let crea_white_male = (186.3)*(Math.pow(serum, -1.154))*(Math.pow(age, -0.203));
    let crea_black_male = crea_white_male * 1.212;

    let crea_thai_female = (175)*(Math.pow(serum, -1.154))*(Math.pow(age, -0.203))*0.742*1.129 ;
    let crea_white_female = (186.3)*(Math.pow(serum, -1.154))*(Math.pow(age, -0.203)) *0.742 ;
    let crea_black_female = crea_white_female * 1.212;

    let crea_ref = 'Creatinine Clearance ';
    crea_ref += '<br>Male: '+crea_male.toFixed(2);
    crea_ref += '<br>Female: '+crea_female.toFixed(2);
		crea_ref += '<br><br>eGFR: ';
    crea_ref += '<br>Thai Male: '+crea_thai_male.toFixed(2);
    crea_ref += '<br>White Male: '+crea_white_male.toFixed(2);
    crea_ref += '<br>Black Male: '+crea_black_male.toFixed(2);
    crea_ref += '<br>Thai Female: '+crea_thai_female.toFixed(2);
    crea_ref += '<br>White Female: '+crea_white_female.toFixed(2);
    crea_ref += '<br>Black Female: '+crea_black_female.toFixed(2);
    crea_ref += '<br><br>Reference (ml/min/1.73m2): <br>Normal	>90	<br>Mild =	60-89	<br>Moderate=	30-59	<br>Severe =	15-29';


    crea_main = (sex == 1)?crea_male:crea_female;

    $('.crea-main').html(crea_main.toFixed(2));
    $('.crea-ref').html(crea_ref);

  }
}

function CopyToClipboard(containerid) {
  if (document.selection) {
    var range = document.body.createTextRange();
    range.moveToElementText(document.getElementById(containerid));
    range.select().createTextRange();
    document.execCommand("copy");
  } else if (window.getSelection) {
    var range = document.createRange();
    range.selectNode(document.getElementById(containerid));
    window.getSelection().addRange(range);
    document.execCommand("copy");
    alert("Text has been copied, now paste in the text-area")
  }
}


</script>
