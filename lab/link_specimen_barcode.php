<?

$lab_order_id = isset($_GET["lab_order_id"])?$_GET["lab_order_id"]:"";
$uid = isset($_GET["uid"])?$_GET["uid"]:"";
$visit = isset($_GET["visit"])?$_GET["visit"]:"";
$print_amt = isset($_GET["print_amt"])?$_GET["print_amt"]:"";
$start_num = isset($_GET["start_num"])?$_GET["start_num"]:"";

/*
$lab_order_id = "KKKK0001";
$uid = "P20-00000";
$visit = "20200112121201";
$print_amt = "3";
$start_num = "1";
*/

//echo "$lab_order_id/$print_amt/$start_num";




$visit_date = substr($visit,0,strlen($visit)-6) ;
$visit_time = substr($visit,8,strlen($visit)) ;

$visit_date1 = substr($visit_date,0,strlen($visit_date)-4) ;
$visit_date2 = substr($visit_date,4,strlen($visit_date)-6) ;
$visit_date3 = substr($visit_date,6,strlen($visit_date)) ;

$visit_time1 = substr($visit_time,0,strlen($visit_time)-4) ;
$visit_time2 = substr($visit_time,2,strlen($visit_time)-4) ;
$visit_time3 = substr($visit_time,4,strlen($visit_time)) ;


$visit_date = "$visit_date1/$visit_date2/$visit_date3";
$visit_time = "$visit_time1:$visit_time2";
//$visit_time = "$visit_time1:$visit_time2:$visit_time3";

$visit = "$visit_date $visit_time";



include_once("barcode.php");
$generator = new barcode_generator();


$lstData = array();

$last_num = $start_num + $print_amt;
for($i=$start_num; $i<$last_num; $i++){
  //$barcode =  $uid."_".$visit."_".$i;
  //  $barcode =  $uid."A".$visit."A".$i;
  $barcode =  $lab_order_id."N".$i;
  $sBarFile = "barcode_specimen/".$barcode.".jpg";
  if(file_exists ($sBarFile) ){

  }else{

    $sImage = $generator->render_image("code-128", $barcode, array('p'=>'0','pv'=>'0','ph'=>'0'));
    imagejpeg($sImage,$sBarFile);
    imagedestroy($sImage);
  }
  $lstData[] = $barcode;

}

$sUidFile = "barcode_specimen/".$uid.".jpg";
if(file_exists ($sUidFile) ){

}else{

  $sImage = $generator->render_image("code-128", $uid, array('p'=>'0','pv'=>'0','ph'=>'0'));
  imagejpeg($sImage,$sUidFile);
  imagedestroy($sImage);
}


require('../asset/fpdf/alphapdf.php');
$pdf = new AlphaPDF();
//$pdf->AddPage('L',array(170,30),'mm');
$pdf->AddPage('L',array(200,40),'mm');
//$pdf->AddPage("L","mm","A4");
$pdf->SetLineWidth(2);

$pdf->SetFont('Arial', '', 12);


$iPos = 12; $iCnt =0; $iMult=64;

foreach($lstData as $barcode) {
  $sBarFile = "barcode_specimen/".$barcode.".jpg";
  $pdf->Image($sBarFile,$iPos+($iCnt*$iMult),1,57,14);
  $pdf->Text($iPos+3+($iCnt*$iMult),19,$barcode);
  $pdf->Text($iPos+3+($iCnt*$iMult),23,"UID:".$uid);
  $pdf->Text($iPos+3+($iCnt*$iMult),27,"VISIT:".$visit);
  $pdf->Image($sUidFile,$iPos+($iCnt*$iMult),28,57,8);
  $iCnt++;
}

$pdf->Output();





?>
