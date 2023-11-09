<?


$sUID = getQS("uid");
$sColdate = getQS("coldate");
$sColtime = getQS("coltime");
$sOrderid = getQS("orderid");
$sSaleoptid = getQS("saleoptid");
$sSaleoptid = ($sSaleoptid == "")?"S01":$sSaleoptid;

$sLaboratoryid = getQS("laboratoryid");
$sLaboratoryid = ($sLaboratoryid == "")?"LBT001":$sLaboratoryid;

//echo "saleopt: $sSaleoptid";

// optoinal
$sID = getQS("sid");
$sRoomid = getQS("roomid");


$title_order = "";
if($sUID != "" && $sColdate !="" && $sColtime!=""){
  $title_order = "Lab Request $sOrderid <br>[UID: $sUID | Visit: $sColdate $sColtime]";

  include('../in_db_conn.php');

  $aLabID = array();
  $query ="SELECT LT.lab_id, LT.lab_id2, LT.lab_name, LBT_SEL.laboratory_id, SALEOPT_SEL.sale_opt_id
  FROM p_lab_test LT
  LEFT JOIN p_lab_test_sale_cost LBT_SEL ON (LBT_SEL.laboratory_id = ? AND LBT_SEL.lab_id=LT.lab_id)
  LEFT JOIN p_lab_test_sale_price SALEOPT_SEL ON (SALEOPT_SEL.sale_opt_id = ? AND SALEOPT_SEL.lab_id=LT.lab_id)
  WHERE is_disable=0
  order by lab_id
  ";
  //echo "$sLaboratoryid, $sSaleoptid /$query ";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('ss', $sLaboratoryid, $sSaleoptid);

  if($stmt->execute()){
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
      if(!isset($aLabID[$row['lab_id']])){
        $aLabID[$row['lab_id']] = array();
        $aLabID[$row['lab_id']]['id'] = $row['lab_id2'];
        $aLabID[$row['lab_id']]['name'] = $row['lab_name'];

        if(is_null($row['laboratory_id']) || is_null($row['sale_opt_id']))
        $aLabID[$row['lab_id']]['enable_sel'] = '0';
        else $aLabID[$row['lab_id']]['enable_sel'] = 1;
      }
    }//while
  }
  $stmt->close();

//print_r($aLabID);

  $aLabOrder = array();

  $query ="SELECT LO.lab_id, LO.is_paid, LR.lab_result, LO.sale_opt_id, LBT.laboratory_name
  FROM p_lab_order_lab_test LO
  LEFT JOIN p_lab_result LR ON (LR.lab_id=LO.lab_id AND LR.uid=LO.uid AND LR.collect_date=LO.collect_date AND LR.collect_time=LO.collect_time )
  LEFT JOIN p_lab_laboratory LBT ON (LBT.laboratory_id = LO.laboratory_id)
  WHERE LO.uid= ? AND LO.collect_date=? AND LO.collect_time=?
  ";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('sss', $sUID, $sColdate, $sColtime);

  if($stmt->execute()){
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
      if(!isset($aLabOrder[$row['lab_id']])){
        $aLabOrder[$row['lab_id']] = array();
        $aLabOrder[$row['lab_id']]['paid'] = $row['is_paid'];
        $aLabOrder[$row['lab_id']]['result'] = $row['lab_result'];
        $aLabOrder[$row['lab_id']]['sale_opt_id'] = $row['sale_opt_id'];
        $aLabOrder[$row['lab_id']]['lbt'] = $row['laboratory_name'];
      }
    }//while
  }
  $stmt->close();


    $query ="SELECT lab_order_note
    FROM p_lab_order
    WHERE uid= ? AND collect_date=? AND collect_time=?
    ";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sss', $sUID, $sColdate, $sColtime);
    if($stmt->execute()){
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()) {
        $txt_order_note = $row['lab_order_note'];
      }//while
    }
    $stmt->close();




  $mysqli->close();



}

//print_r($aLabOrder);
$txt_row = "";

$aLabPkg = array();
$aLabGroupID = array();


$aLabPkg['antihiv'] = array();
$aLabPkg['antihiv']['name'] = "Anti-HIV";
$aLabPkg['antihiv']['lab_id'] = array('HIV_Ab');
$aLabPkg['antihiv']['spc'] = array('ส่ง AHI SEARCH','ส่ง AHI IHRI','ไม่ส่ง Screen AHI');

$aLabPkg['antihiv2nd'] = array();
$aLabPkg['antihiv2nd']['name'] = "Anti-HIV 2nd Tube";
$aLabPkg['antihiv2nd']['lab_id'] = array('HIV_Ab_2nd');
$aLabPkg['antihiv2nd']['spc'] = array('ส่ง AHI SEARCH','ไม่ส่ง Screen AHI');

$aLabPkg['hiv'] = array();
$aLabPkg['hiv']['name'] = "HIV";
//$aLabPkg['hiv']['lab_id'] = array('HIV_Ab', 'CD4%', 'HIV_VL', 'HIV_VL_DT' );
$aLabPkg['hiv']['lab_id'] = array( 'CD4%', 'HIV_VL');
$aLabPkg['hiv']['spc'] = array();

$aLabID['HIV_VL']['name'] = "HIV-1 Viral Load";
$aLabGroupID['HIV_VL'] = array('HIV_VL', 'HIV_VL_DT');
$aLabID['CD4%']['name'] = "CD4";
$aLabGroupID['CD4%'] = array('CD4%', 'CD4#', 'CD4#', 'HGB');



$aLabPkg['syphilis'] = array();
$aLabPkg['syphilis']['name'] = "Syphilis";
$aLabPkg['syphilis']['lab_id'] = array('TP_Ab', 'RPR_Titer', 'RPR', 'TPPA');
$aLabPkg['syphilis']['spc'] = array();


$aLabPkg['natctng'] = array();
$aLabPkg['natctng']['name'] = "NAT CT/NG";
$aLabPkg['natctng']['lab_id'] = array('NAT_CT');
$aLabPkg['natctng']['spc'] = array('Pool Urine','Pool Urethra','Pool oral','Pool anus','Pool vagina','Pool neovagina','Pool cervix',
'Urine','Urethra','oral','anus','vagina','neovagina','cervix' );
$aLabID['NAT_CT']['name'] = "CT/NG Diagnosis";
$aLabGroupID['NAT_CT'] = array('NAT_CT', 'NAT_NG');

$aLabPkg['natstis'] = array();
$aLabPkg['natstis']['name'] = "NAT STIs (11 Pathogens)";
$aLabPkg['natstis']['lab_id'] = array('MPX_PCR');
$aLabPkg['natstis']['spc'] = array('Pool Urine','Pool Urethra','Pool oral','Pool anus','Pool vagina','Pool neovagina','Pool cervix',
'Urine','Urethra','oral','anus','vagina','neovagina','cervix' );

$aLabPkg['hav'] = array();
$aLabPkg['hav']['name'] = "HAV";
$aLabPkg['hav']['lab_id'] = array('HAV_Ab', 'HAV_Ab_IgM', 'HAV_Ab_IgG');
$aLabPkg['hav']['spc'] = array();

$aLabPkg['hbv'] = array();
$aLabPkg['hbv']['name'] = "HBV";
$aLabPkg['hbv']['lab_id'] = array('HBsAg', 'HBs_Ab', 'HBc_Ab');
$aLabPkg['hbv']['spc'] = array();

$aLabPkg['hcv'] = array();
$aLabPkg['hcv']['name'] = "HCV";
$aLabPkg['hcv']['lab_id'] = array('HCV_Ab', 'HCV_VL');
$aLabPkg['hcv']['spc'] = array();

$aLabPkg['gram'] = array();
$aLabPkg['gram']['name'] = "Gram stain";
$aLabPkg['gram']['lab_id'] = array('GRAM');
$aLabPkg['gram']['spc'] = array('Urethra','cervix','anus','vagina','neovagina');

$aLabPkg['wet'] = array();
$aLabPkg['wet']['name'] = "Wet Smear";
$aLabPkg['wet']['lab_id'] = array('WET');
$aLabPkg['wet']['spc'] = array('vagina','neovagina');

$aLabPkg['koh'] = array();
$aLabPkg['koh']['name'] = "KOH";
$aLabPkg['koh']['lab_id'] = array('KOH');
$aLabPkg['koh']['spc'] = array('vagina','neovagina');

$aLabPkg['hpv'] = array();
$aLabPkg['hpv']['name'] = "NAT HPV";
$aLabPkg['hpv']['lab_id'] = array('HPV');
$aLabPkg['hpv']['spc'] = array('vagina','neovagina');

$aLabPkg['papsmear'] = array();
$aLabPkg['papsmear']['name'] = "Pap smear";
$aLabPkg['papsmear']['lab_id'] = array('SURE_PREP','THIN_PREP','SURE_PATHA',
'SURE_PREPC','THIN_PREPC','SURE_PATHC', 'SURE_PREPN','THIN_PREPN','SURE_PATHN');
$aLabPkg['papsmear']['spc'] = array();


$aLabPkg['hormone'] = array();
$aLabPkg['hormone']['name'] = "Hormone";
$aLabPkg['hormone']['lab_id'] = array('E2','T');
$aLabPkg['hormone']['spc'] = array();

$aLabPkg['kidney'] = array();
$aLabPkg['kidney']['name'] = "kidney function";
$aLabPkg['kidney']['lab_id'] = array('CREA','CrCl', 'BUN');
$aLabPkg['kidney']['spc'] = array();

$aLabPkg['urineexam'] = array();
$aLabPkg['urineexam']['name'] = "Urine examination";
$aLabPkg['urineexam']['lab_id'] = array('US_GLU','UM_RBC');
$aLabPkg['urineexam']['spc'] = array();


$aLabID['US_GLU']['name'] = "Urine Strip ";
$aLabGroupID['US_GLU'] = array('US_GLU','US_pH', 'US_ALB','US_SG');

$aLabID['UM_RBC']['name'] = "Urine Microscopy ";
$aLabGroupID['UM_RBC'] = array('UM_AC','UM_BC','UM_BR','UM_COC','UM_GC','UM_HC','UM_M','UM_O','UM_OC','UM_RBC','UM_RTEC','UM_SEC','UM_TEC','UM_UAC','UM_WBC','UM_Y');


$aLabPkg['liver'] = array();
$aLabPkg['liver']['name'] = "Liver function";
$aLabPkg['liver']['lab_id'] = array('ALT','AST', 'ALP','TBI','DBI');
$aLabPkg['liver']['spc'] = array();

$aLabPkg['fbs'] = array();
$aLabPkg['fbs']['name'] = "FBS and lipid profile";
$aLabPkg['fbs']['lab_id'] = array('GLU','CHOL', 'TG','HDL','LDL');
$aLabPkg['fbs']['spc'] = array();



$aLabPkg['WBC'] = array();
$aLabPkg['WBC']['name'] = "CBC";
$aLabPkg['WBC']['lab_id'] = array('WBC');
$aLabPkg['WBC']['spc'] = array();

$aLabID['WBC']['name'] = "CBC (All in CBC)";
$aLabGroupID['WBC'] = array('BA#', 'BA%', 'EO#', 'EO%', 'Hb', 'HCT', 'LY#', 'LY%',
'MCH', 'MCHC', 'MCV', 'MO#', 'MO%', 'MPV', 'NE#', 'NE%', 'PLT', 'RBC', 'RDW-CV', 'RDW-SD', 'WBC' );

$aLabPkg['Electrolyte'] = array();
$aLabPkg['Electrolyte']['name'] = "Electrolyte";
$aLabPkg['Electrolyte']['lab_id'] = array('Na');
$aLabPkg['Electrolyte']['spc'] = array();

$aLabID['Na']['name'] = "Electrolyte";
$aLabGroupID['Na'] = array('Na','CO2', 'Cl', 'K');




$aLabPkg['SARS-CoV-2'] = array();
$aLabPkg['SARS-CoV-2']['name'] = "SARS-CoV-2 (COVID-19) RT-PCR";
$aLabPkg['SARS-CoV-2']['lab_id'] = array('SARS-CoV-2');
$aLabPkg['SARS-CoV-2']['spc'] = array();
$aLabGroupID['SARS-CoV-2'] = array('SARS-CoV-2', 'E_Ct', 'N2_Ct');

$aLabPkg['COVID_T'] = array();
$aLabPkg['COVID_T']['name'] = "COVID-19 Antibody (IgM & IgG)";
$aLabPkg['COVID_T']['lab_id'] = array('COVID_T');
$aLabPkg['COVID_T']['spc'] = array();
$aLabGroupID['COVID_T'] = array('COVID_T', 'COVID_IgM', 'COVID_IgG');

$aLabPkg['ATK'] = array();
$aLabPkg['ATK']['name'] = "COVID-19 Antigen Rapid Test";
$aLabPkg['ATK']['lab_id'] = array('ATK');
$aLabPkg['ATK']['spc'] = array();




//***  ตรวจร่างกายประจำปี

$aLabPkg['physicalexam'] = array();
$aLabPkg['physicalexam']['name'] = "ตรวจสุขภาพประจำปี IHRI (ตัวเลือกพื้นฐาน)";
$aLabPkg['physicalexam']['lab_id'] = array('HIV_Ab', 'US_GLU' ,'UM_RBC',
 'WBC', 'CREA','ALT', 'HBsAg','HBs_Ab', 'FOB','GLU', 'UA','CHOL', 'TG','HDL','LDL'
);
$aLabPkg['physicalexam']['spc'] = array();

$aLabPkg['physicalexam_option'] = array();
$aLabPkg['physicalexam_option']['name'] = "ตรวจสุขภาพประจำปี IHRI (ตัวเลือกเพิ่มเติม)";
$aLabPkg['physicalexam_option']['lab_id'] = array('SURE_PREP','SURE_PREPC','HPV');

$aLabID['HIV_Ab']['name'] = "Anti HIV (ตรวจคัดกรองเอชไอวี)";
$aLabID['WBC']['name'] = "CBC (ตรวจหาความสมบูรณ์ของเม็ดเลือด)";
$aLabID['CREA']['name'] = "CREA (ตรวจการทำงานของไต)";

$aLabID['ALT']['name'] = "ALT (ตรวจการทำงานของตับ)";
$aLabID['HBsAg']['name'] = "HBsAg (ตรวจหาการติดเชื้อของไวรัสตับอักเสบบี)";
$aLabID['HBs_Ab']['name'] = "HBs_Ab (ตรวจหาภูมิต้านทานไวรัสตับอักเสบบี)";
$aLabID['FOB']['name'] = "FOB (ตรวจหาเลือดในอุจจาระ)";
$aLabID['GLU']['name'] = "GLU (ตรวจระดับน้ำตาลในเลือด)";
$aLabID['UA']['name'] = "UA (ตรวจระดับกรดยูริคในเลือด)";

$aLabID['CHOL']['name'] = "CHOL (ตรวจระดับไขมันในเลือด Cholesterol)";
$aLabID['TG']['name'] = "TG (ตรวจระดับไขมันในเลือด Triglyceride)";
$aLabID['HDL']['name'] = "HDL (ตรวจระดับไขมันดี)";
$aLabID['LDL']['name'] = "LDL (ตรวจระดับไขมันไม่ดี)";

$aLabID['SURE_PREP']['name'] = "Anal Pap Smear (ตรวจคัดกรองมะเร็งปากทวารหนัก)";
$aLabID['SURE_PREPC']['name'] = "Pap Smear (แนะนำสำหรับผู้หญิงอายุต่ำกว่า 30 ปี)";
$aLabID['HPV']['name'] = "HPV (แนะนำสำหรับผู้หญิงอายุ 30 ปีขึ้นไป)";
//$aLabID['CD4%']['name'] = "CD4";

$aLabPkg['physicalexam_option']['spc'] = array();


//***************





/*
foreach($aLabPkg as $key=>$itm){

}//foreach
*/
function showLabPackage($pkg_id, $pkg_color='bg-mdark1', $pkg_color_lab='bg-msoft1' ){
  global $aLabID;
  global $aLabPkg;
  global $aLabOrder;
  global $aLabGroupID;
  global $txt_order_note;
  //echo "<br>showLabPackagess $pkg_id ".count($aLabPkg);

   $txt_row = "";
   $pkg_name = $aLabPkg[$pkg_id]['name'];
   if(isset($aLabPkg[$pkg_id])){

     $txt_row .= "<div class='fl-wrap-col pw300 ptxt-s10 mb-1'>";
     // topic
     $txt_row .= "  <div class='fl-wrap-row px-1 ph20 v-mid ptxt-b ptxt-white $pkg_color ptxt-s12'>";
     $txt_row .= "    <div class='fl-fill btn-showhide pbtn' data-id='$pkg_id' data-show='1'>";
     $txt_row .= "       <input type='checkbox' class='chk-pkg v-checkbox mr-1' data-id='$pkg_id'>  $pkg_name ";
     $txt_row .= "    </div>";
  /*
     $txt_row .= "    <div class='fl-fix pbtn pbtn-warning pw-50 ptxt-s8 py-1 btn-showhide' data-id='$pkg_id' data-show='1'>
                          Show/Hide ";
     $txt_row .= "    </div>";
  */
     $txt_row .= "  </div>";
     foreach($aLabPkg[$pkg_id]['lab_id'] as $lab_id){
       $lab_name = ""; // eg RPR Titer
       $lab_real_id = ""; // eg L0183
       $chk_enable_sel = 1;

       if(isset($aLabID[$lab_id])){
            $lab_real_id =$aLabID[$lab_id]['id'] ;
            $lab_name =$aLabID[$lab_id]['name'] ;
            $chk_enable_sel = $aLabID[$lab_id]['enable_sel'] ;
       }
       else{
         $lab_name = "Unknown for $lab_id";
         $lab_real_id = "no";
         $chk_enable_sel = 0 ;
       }

       $data_lab_id = $lab_id;

       if(isset($aLabGroupID[$lab_id])){ // lab group with same price
         $lab_name =$aLabID[$lab_id]['name'];
         $data_lab_id = "";

         foreach($aLabGroupID[$lab_id] as $lab_item_id){
           $data_lab_id .= "$lab_item_id:";
         }//foreach
         if($data_lab_id != ""){
           $data_lab_id = substr($data_lab_id,0,strlen($data_lab_id)-1);
         }
        //  echo "<br> group lab3 : $data_lab_id";
       }



       $check = ""; $isPaid =""; $labResult=""; $lbt=""; $sale_opt = ""; $hideRemoveBtn = " style='display:none;'";;
       if(isset($aLabOrder[$lab_id])){
         if($aLabOrder[$lab_id]['sale_opt_id'] != '')
         $sale_opt = "<span class='ml-1 px-1 bg-saleoption'>".$aLabOrder[$lab_id]['sale_opt_id']."</span>";
         if($aLabOrder[$lab_id]['lbt'] != '')
         $lbt = "<span class='ml-1 px-1 bg-laboratory'>".$aLabOrder[$lab_id]['lbt']."</span>";


         $check = "checked disabled";

         if($aLabOrder[$lab_id]['paid'] == '1')
         $isPaid = "<span class='ml-1 px-1 pbtn-ok'>Paid</span>";

         if($aLabOrder[$lab_id]['result'] != '')
         $labResult = "<span class='ml-1 px-1 pbtn-blue'>".$aLabOrder[$lab_id]['result']."</span>";


         if($isPaid == '' && $labResult ==''){
           $hideRemoveBtn = "";
         }
       }
       else{
         if($chk_enable_sel == 0){
           $check = " disabled";
           $lab_name .= " <span class='text-danger'>(Not Available)</span>";
         }
       }


       $txt_row .= "  <div class='fl-wrap-row px-1 p-row lab-row $pkg_color_lab $pkg_id $lab_real_id '>";

       //$txt_row .= "  <div class='fl-wrap-row px-1 ph20 p-row bg-msoft1 $pkg_id $lab_real_id '>";
      // $txt_row .= "  <div class='fl-fix px-1 p-row pw250'>";
      // $txt_row .= "   <label class='pbtn'> <input type='checkbox' class='chk-lab v-checkbox' data-id='".$lab_id."' data-pkg='$pkg_id'> ".$lab_name."</label>"; | $lbt $sale_opt

       $txt_row .= "   <div class='fl-fill'>";
       $txt_row .= "     <label class='pbtn'><input type='checkbox' class='chk-lab v-checkbox mr-2' data-id='".$lab_real_id."' data-labid='".$data_lab_id."' data-pkg='$pkg_id' $check />  ".$lab_name." " .$isPaid.$labResult ."<span id='tag_$lab_real_id'>".$lbt. $sale_opt."</span> </label>" ;
       $txt_row .= "   </div>";
       $txt_row .= "   <div class='fl-fix fl-mid px-1 pw15 ptxt-b ptxt-s10 pbtn pbtn-cancel btn-lab-remove' data-id='$lab_real_id' title='Remove from order' $hideRemoveBtn>X</div>";

       $txt_row .= "  </div>";
     } // foreach

     $spc_txt = "";
     foreach($aLabPkg[$pkg_id]['spc'] as $spc){ //specimen collect
       $pkg_spc = "$pkg_id: $spc"; $check_spc = "";
       if(strpos($txt_order_note, $pkg_spc,0) > -1) $check_spc = "checked";
    //   $spc_txt .= "  <div class='fl-float px-1 mr-1 pbtn pbtn-blue'>$spc</div>";
       $spc_txt .= "  <div class='fl-fix px-1 ph20  p-row bg-msoft3'>";
      // $txt_row .= "   <label class='pbtn'> <input type='checkbox' class='chk-lab v-checkbox' data-id='".$lab_id."' data-pkg='$pkg_id'> ".$lab_name."</label>";
       $spc_txt .= "  <label class='pbtn'>  <input type='checkbox' class='chk-spc v-checkbox mr-2' data-id='$spc' data-pkg='$pkg_id' $check_spc>  ".$spc. "</label>";
       $spc_txt .= "  </div>";
     }
     if($spc_txt != ""){
       $txt_row .= "  <div class='fl-wrap-col pw300 ptxt-s10 pbtn pbtn-blue btn-spc' data-pkg='$pkg_id'> + Specimen collect (click to see)</div>";
       $txt_row .= "  <div class='fl-wrap-col fl-fill px-1 spc-$pkg_id'  style='display:none;'>";
      // $txt_row .= "  <div class='fl-wrap-col fl-fill px-1 spc-$pkg_id' >";
       //$txt_row .= ;
       $txt_row .= $spc_txt;
       $txt_row .= "  </div>";
     }

     $txt_row .= "</div>";

   }
   else{
     $txt_row .= "<div class='fl-wrap-col pw300'>";
     $txt_row .= "  <div class='fl-fix ph30 fl-mid'>$pkg_topic</div>";
     $txt_row .= "   No $pkg_id package found.";
     $txt_row .= "  </div>";
     $txt_row .= "</div>";
   }
   echo $txt_row;
}



  function getQS($sName,$sDef=""){
  	$sResult = (isset($_GET[$sName])?urlencode($_GET[$sName]):"");
  	if($sResult=="") $sResult = (isset($_POST[$sName])?urlencode($_POST[$sName]):"");
  	if($sResult=="null" || $sResult=="") $sResult=$sDef;
  	return urlDecode($sResult);

  }

  $add_js = "$('#ddl_dlg_laboratory').val('$sLaboratoryid'); $('#ddl_dlg_sale_opt').val('$sSaleoptid');";




?>
<style>
.bg-laboratory{
  background-color: #99E5FF;
  color:black;
  border:1px solid black;
}
.bg-saleoption{
  background-color: #FFEFBF;
  color:black;
  border:1px solid black;
}
.lab-row{
  min-height: 20px;
  line-height: 15px;
}
</style>

  <div class='div-lab-request' data-uid='<? echo $sUID; ?>' data-coldate='<? echo $sColdate; ?>' data-coltime='<? echo $sColtime; ?>'
    data-orderid='<? echo $sOrderid; ?>'   data-sid='<? echo $sID; ?>' data-roomid='<? echo $sRoomid; ?>'>
      <div class='fl-wrap-row ph40 ptxt-b pbg-blue ptxt-white mb-2' >
        <div class='fl-fill ptxt-s12 v-mid px-2' >
             <? echo $title_order; ?>
        </div>
        <div class='fl-fix pw200 pr-4 ptxt-s10 bg-mdark1' >
            Laboratory:
            <SELECT id='ddl_dlg_laboratory' class='bg-laboratory labtest-check'>
               <? include_once("p_lab_opt_laboratory.php"); ?>
            </SELECT>
        </div>
        <div class='fl-fix pw300 pr-2 ptxt-s10 bg-mdark1' >
            Sale Option:
            <SELECT id='ddl_dlg_sale_opt' class='bg-saleoption labtest-check'>
               <? include_once("p_lab_opt_sale.php"); ?>
            </SELECT>
        </div>
        <div class='fl-fix pw100  fl-mid ptxt-white ptxt-s14 ptxt-b pbtn pbtn-ok btn-select-lab-order-dlg' >
             Select | เลือก
        </div>
        <div class='fl-fix pw100 fl-mid ptxt-white ptxt-s14 ptxt-b pbtn-ok spinner' style='display:none;'>
             <i class='fas fa-spinner fa-spin fa-lg' ></i> Wait
        </div>

        <div class='fl-fill' >
        </div>

<!--
        <div class='fl-fix pw300  fl-mid ptxt-white ptxt-s10 bg-sdark1' >
           Lab Package: <br>
             <select class='ddl_lab_pkg_list'>
               <option value='' selected="true" disabled="disabled">-select-</option>
               <option value='physical_exam_ihri'>ตรวจสุขภาพประจำปี IHRI</option>
             </select>
             <button class='btn-lab-pkg-list pbtn'>Open</button>
        </div>
-->

        <div class='fl-fix fl-mid ptxt-s10 pw50 pbtn pbtn-cancel btn-close-lab-order-dlg' >
             X Close
        </div>
        <div class='fl-fix fl-mid ptxt-s8 pw50 pbtn pbtn-cancel spinner' style='display:none;'>
             <i class='fas fa-spinner fa-spin fa-lg' ></i> Update Note
        </div>
      </div>

      <div class='fl-wrap-row' >
        <div class='fl-fix pw300 mr-1' >
             <?
             showLabPackage('antihiv');
             showLabPackage('antihiv2nd');

             showLabPackage('hiv');
             showLabPackage('syphilis');
             showLabPackage('natctng');
             showLabPackage('natstis');
             showLabPackage('hav');
             showLabPackage('hbv');
             showLabPackage('hcv');


             ?>
        </div>
        <div class='fl-fix pw300 mr-1' >
             <?
             showLabPackage('gram');
             showLabPackage('wet');
             showLabPackage('koh');
             showLabPackage('hpv');
             showLabPackage('papsmear');

             showLabPackage('hormone');
             showLabPackage('kidney');

             showLabPackage('urineexam');
             showLabPackage('liver');
             showLabPackage('fbs');
             showLabPackage('WBC'); //cbc
             showLabPackage('Electrolyte'); //Electrolyte

             showLabPackage('SARS-CoV-2'); //covid-19 rt-pcr
             showLabPackage('ATK'); //covid-19 atk
             showLabPackage('COVID_T'); //covid-19 antibody

             ?>
        </div>
        <div class='fl-fix pw400 mr-1' >
             <?


             showLabPackage('physicalexam', 'bg-sdark1', 'bg-ssoft2'); //standard physical exam
             showLabPackage('physicalexam_option', 'bg-sdark1', 'bg-ssoft2'); //optional physical exam

             ?>
        </div>
        <div class='fl-fill ptxt-s10 ptxt-b' >
             Specimen Collect: <br>
             <textarea id='txtSPC_note' class='ptxt-s16' data-odata='<? echo $txt_order_note; ?>' style="width: 100%; max-width: 100%; height: 90%; max-height: 90%;"><? echo $txt_order_note; ?></textarea>
        </div>
      </div>

  </div>

<script>
$(document).ready(function(){

  <? echo $add_js; ?>

/*
       $('.div-lab-request .btn-lab-pkg-list').off("click");
       $('.div-lab-request .btn-lab-pkg-list').on("click",function(){
         console.log('select '+$('.ddl_lab_pkg_list').val());
         if($('.ddl_lab_pkg_list').val() != ''){
           let sUID=$('.div-lab-request').attr("data-uid");
           let sColdate=$('.div-lab-request').attr("data-coldate");
           let sColtime=$('.div-lab-request').attr("data-coltime");
           let pkg =$('.ddl_lab_pkg_list').val();

           let sUrl = "lab/p_lab_order_package_page_dlg.php?uid="+sUID+"&coldate="+sColdate+"&coltime="+sColtime+"&pkg="+pkg;
           showDialog(sUrl,"Lab Package ["+sUID+"]",'600','95%',"",function(sResult){

           },false,"");
         }
       });
*/

  <?
    if($sSaleoptid != "")
    echo "$('#ddl_dlg_sale_opt').val('$sSaleoptid');";
  ?>

  $(".div-lab-request .btn-showhide").each(function(ix,objx){
    //console.log("val: "+$(objx).attr('data-id'));
    let pkg_id = $(objx).attr("data-id");
    showPKG(pkg_id, '0');
  });

  $('.div-lab-request .labtest-check').off("change");
  $('.div-lab-request .labtest-check').on("change",function(){
     let uid = $('.div-lab-request').attr('data-uid');
     let coldate = $('.div-lab-request').attr('data-coldate');
     let coltime = $('.div-lab-request').attr('data-coltime');
     let laboratoryid = $('#ddl_dlg_laboratory').val();
     let saleoptid =$('#ddl_dlg_sale_opt').val();
     let orderid=$('.div-lab-request').attr("data-orderid");

     let link = "lab/p_lab_order_package_dlg.php?uid="+uid+"&coldate="+coldate+"&coltime="+coltime+"&orderid="+orderid+"&saleoptid="+saleoptid+"&laboratoryid="+laboratoryid;

     $('.div-lab-request').parent().load(link);
  });


  $('.div-lab-request .btn-close-lab-order-dlg').off("click");
  $('.div-lab-request .btn-close-lab-order-dlg').on("click",function(){
      let btnClose = $(this);
      if($('#txtSPC_note').val() != $('#txtSPC_note').attr('data-odata')){
        let sUID=$('.div-lab-request').attr("data-uid");
        let sColdate=$('.div-lab-request').attr("data-coldate");
        let sColtime=$('.div-lab-request').attr("data-coltime");

        var aData = {
            u_mode:"update_lab_order_note_dlg",
            uid:sUID,
            collect_date:sColdate,
            collect_time:sColtime,
            txt_note: $('#txtSPC_note').val().trim()
            };

            startLoad(btnClose, btnClose.next(".spinner"));
            callAjax("lab/db_lab_test_order.php",aData,function(rtnObj,aData){
                endLoad(btnClose, btnClose.next(".spinner"));
                if(rtnObj.res == 1){
                  $.notify("Lab note updated.", "success");
                  closeLabRequest();
                }
            });// call ajax
      }
      else{ // no changed lab order note
        closeLabRequest();
      }
  });


  $('.div-lab-request .btn-spc').off("click");
  $('.div-lab-request .btn-spc').on("click",function(){
        let pkg_id = $(this).attr("data-pkg");
      //  console.log("click: "+pkg_id);
        if($('.spc-'+pkg_id).is(':visible')){
          $('.spc-'+pkg_id).hide();
        }
        else{
          $('.spc-'+pkg_id).show();
        }
  });

 // click package
  $('.div-lab-request .chk-pkg').off("click");
  $('.div-lab-request .chk-pkg').on("click",function(){
        let pkg_id = $(this).attr("data-id");
        let isPkgCheck = $(this).prop("checked");
      //  console.log("check : "+$(this).prop("checked"));
        let ischeck = 0;
        $(".chk-lab[data-pkg='"+pkg_id+"']").each(function(ix,objx){
          if($(objx).is(':disabled')){
            ischeck = 1;
          }
          else{
            if(isPkgCheck){
              $(objx).prop('checked', true);
            }
            else{
              $(objx).prop('checked', false);
            }
          }
        });

        if($(this).prop("checked")){
          showPKG(pkg_id, 1);
        }
        else{
          if(ischeck == 1){
            $(this).prop("checked", true);
            showPKG(pkg_id, 1);
          }
          else{
            showPKG(pkg_id, 0);
          }

        }
  });

 // click lab id
  $('.div-lab-request .chk-lab').off("click");
  $('.div-lab-request .chk-lab').on("click",function(){
        let pkg_id = $(this).attr("data-pkg");
        if($(this).is(":checked")){
          if($('.chk-pkg[data-id="'+pkg_id+'"]').is(":checked")){}
          else{showPKG(pkg_id, 1);}
        }
        else{
        //  console.log("click098 "+$('.chk-lab[data-pkg="'+pkg_id+'"]:checked').length);
          if($('.chk-lab[data-pkg="'+pkg_id+'"]:checked').length == 0){
            showPKG(pkg_id, 0);
          }
        }
  });


  $('.div-lab-request .btn-showhide').off("click");
  $('.div-lab-request .btn-showhide').on("click",function(){
        let show = $(this).attr("data-show");
        let pkg_id = $(this).attr("data-id");
      //  console.log("pkg: "+pkg_id+"/"+show);
        if(show == '1'){showPKG(pkg_id, '0');}
        else if(show == '0'){showPKG(pkg_id, '1');}
  });




  // click specimen collect
   $('.div-lab-request .chk-spc').off("click");
   $('.div-lab-request .chk-spc').on("click",function(){
         let pkg_id = $(this).attr("data-pkg");
         let spc_id = $(this).attr("data-id");
         let spc_note = pkg_id+": "+spc_id+"\n";
         let spc_all_note = $("#txtSPC_note").val();
         if($(this).is(":checked")){
           if(spc_all_note.indexOf(spc_note) > -1){ // found , do nothing
           }
           else{// not found, put txt
              spc_all_note = spc_note+spc_all_note;
              $("#txtSPC_note").val(spc_all_note);
           }
         }
         else{
           spc_all_note = spc_all_note.replace(spc_note, "");
           $("#txtSPC_note").val(spc_all_note);
         }
   });





function showPKG(pkg_id, is_show){
  //console.log("showPKG  "+pkg_id+" / "+is_show);
  let lab_chk_amt = $('.chk-lab[data-pkg="'+pkg_id+'"]:checked').length;
  if(is_show == '1'){ // show
      if(lab_chk_amt >0){
        $('.chk-pkg[data-id="'+pkg_id+'"]').prop("checked", true);
        $('.btn-spc[data-pkg="'+pkg_id+'"]').show();
        $('.spc-'+pkg_id).show();
      }
    $(".p-row."+pkg_id).show();
    $('.btn-showhide[data-id="'+pkg_id+'"]').attr("data-show", '1');


  }
  else if(is_show == '0'){ // hide
  //  console.log("hide: "+pkg_id);
    if($('.chk-lab[data-pkg="'+pkg_id+'"]:checked').length == 0){
      $('.chk-pkg[data-id="'+pkg_id+'"]').prop("checked", false);
      $('.btn-spc[data-pkg="'+pkg_id+'"]').hide();
      $('.spc-'+pkg_id).hide();
    }
    else{
      $('.chk-pkg[data-id="'+pkg_id+'"]').prop("checked", true);
      $('.btn-spc[data-pkg="'+pkg_id+'"]').show();
      $('.spc-'+pkg_id).show();
    }

    $(".p-row."+pkg_id).hide();
    $(".chk-lab[data-pkg='"+pkg_id+"']:checked").each(function(ix,objx){
       $(".p-row."+pkg_id+"."+$(objx).attr('data-id')).show();

    });
    $('.btn-showhide[data-id="'+pkg_id+'"]').attr("data-show", '0');

  }
}



  $('.div-lab-request .btn-select-lab-order-dlg').off("click");
  $('.div-lab-request .btn-select-lab-order-dlg').on("click",function(){
    let btnselect = $(this);
    let sUID=$('.div-lab-request').attr("data-uid");
    let sColdate=$('.div-lab-request').attr("data-coldate");
    let sColtime=$('.div-lab-request').attr("data-coltime");

    let sOrderid=$('.div-lab-request').attr("data-orderid");
    let sID=$('.div-lab-request').attr("data-sid");
    let sRoomid=$('.div-lab-request').attr("data-roomid");


    let lst_data_obj = []; let flag_update = 0;
    $(".div-lab-request .chk-lab:checked").each(function(ix,objx){
         if($(objx).prop("disabled")){
         }
         else{
           //console.log("addLab0: "+$(objx).attr('data-labid'));
           let arr_data_lab_id = $(objx).attr('data-labid').split(':');
           arr_data_lab_id.forEach(function(lab_id) {
             //console.log("addLab: "+lab_id);
             lst_data_obj.push(lab_id);
           });

          // lst_data_obj.push($(objx).attr('data-labid'));
           $(objx).addClass("data-update");
         }
    });

    if(lst_data_obj.length > 0){
    //  console.log("sale_opt_id: "+$('#ddl_dlg_sale_opt').val());
    //  console.log("laboratory_id: "+$('#ddl_dlg_laboratory').val());
      var aData = {
          u_mode:"update_lab_order_dlg",
          uid:sUID,
          collect_date:sColdate,
          collect_time:sColtime,
          lst_data:lst_data_obj,
          sale_opt_id: $('#ddl_dlg_sale_opt').val(),
          laboratory_id: $('#ddl_dlg_laboratory').val(),

          orderid : sOrderid,
          sid : sID,
          roomid : sRoomid

          };

          startLoad(btnselect, btnselect.next(".spinner"));
          callAjax("lab/db_lab_test_order.php",aData,function(rtnObj,aData){
              endLoad(btnselect, btnselect.next(".spinner"));
              if(rtnObj.res == 1){
                $.notify("Lab order updated.", "success");
                $(".div-lab-request .data-update").each(function(ix,objx){

                     let laboratory_id = "<span class='ml-1 px-1 bg-laboratory'>"+$("#ddl_dlg_laboratory option:selected").text()+"</span>";
                     let sale_opt_id = "<span class='ml-1 px-1 bg-saleoption'>"+aData.sale_opt_id+"</span>";
                     $("#tag_"+$(objx).attr('data-id')).html(laboratory_id+" "+sale_opt_id);
                     $(".btn-lab-remove[data-id='"+$(objx).attr('data-id')+"']").show();
                     $(objx).prop('disabled', true);
                });
                $(".chk-lab").removeClass("data-update");
                if(rtnObj.oid != '')
                $('.div-lab-request').attr("data-orderid", rtnObj.oid);
              }
          });// call ajax

    }
    else{
      $.notify("No data changed.", "info");
    }
    //console.log("visit: "+visitid+"/"+visitdate);

  });



  $('.div-lab-request .btn-lab-remove').off("click");
  $('.div-lab-request .btn-lab-remove').on("click",function(){
    let btnremove = $(this);
    let sUID=$('.div-lab-request').attr("data-uid");
    let sColdate=$('.div-lab-request').attr("data-coldate");
    let sColtime=$('.div-lab-request').attr("data-coltime");
    let s_lab_id =$(this).attr('data-id');
    let labid_show =$(this).closest(".lab-row").find(".chk-lab").attr("data-labid");


    if(confirm('Are you sure to remove '+labid_show+' from this order?')){
      let s_umode = "remove_lab_order_dlg";
      if(labid_show.indexOf(":")) s_umode = "remove_lab_order_dlg_group";

      var aData = {
          u_mode:s_umode,
          uid:sUID,
          collect_date:sColdate,
          collect_time:sColtime,
          lab_id:labid_show 
          };

          startLoad(btnremove, btnremove.next(".spinner"));
          callAjax("lab/db_lab_test_order.php",aData,function(rtnObj,aData){
              endLoad(btnremove, btnremove.next(".spinner"));
              if(rtnObj.res == 1){
                btnremove.notify("Lab Removed.", "success");
                $('.chk-lab[data-id="'+s_lab_id+'"]').prop('checked', false);
                $('.chk-lab[data-id="'+s_lab_id+'"]').prop('disabled', false);
                $('.chk-lab[data-id="'+s_lab_id+'"]').prop('disabled', false);
                $("#tag_"+s_lab_id).html('');
                btnremove.hide();

                let pkg_id = $('.chk-lab[data-id="'+s_lab_id+'"]').attr('data-pkg');

                if($('.chk-lab[data-pkg="'+pkg_id+'"]:checked').length == 0){
                  showPKG(pkg_id, 0);
                }
              }
          });// call ajax

    }

  });



});


</script>
