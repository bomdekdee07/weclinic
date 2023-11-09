<?

/*

this link can be filled backdate form

*/


include_once("../in_auth.php");
include_once("../a_app_info.php");

include_once("../function/in_fn_date.php");
include_once("../function/in_fn_link.php"); // include date function

$form_id = isset($_GET["form_id"])?$_GET["form_id"]:"";
if($form_id==""){
  header( "location: ../info/invalid.php?e=e1" );
  exit(0);
}


$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$proj_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";

$uic = isset($_GET["uic"])?urldecode($_GET["uic"]):"";
$pid = isset($_GET["pid"])?$_GET["pid"]:"";
$is_backdate = isset($_GET["bd"])?$_GET["bd"]:"N";

$form_name = isset($_GET["form_name"])?$_GET["form_name"]:"No Form";
$form_title = "<h1><span style='font-size:40px;'>แบบฟอร์ม $form_name</span></h1>";

$link_encode = "$uid:$visit_date:$visit_id:$proj_id:$group_id:$uic:$pid:$is_backdate";
$link_encode = encodeSingleLink($link_encode);

$web_link_qrcode = $GLOBALS['site_path']."visit_form/x_$form_id.php?link=$link_encode";

?>




<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    <title><? echo "$uic / $form_name" ;?></title>


    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="../asset_iclinic/iclinic.css">
    <link rel="stylesheet" href="../asset/fontawesome/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Kanit:300,400,700&amp;subset=thai" rel="stylesheet">
    <!-- Bootstrap core JavaScript-->
    <link rel="stylesheet" href="../asset/jquery-ui.css">
    <script src="../asset/jquery.min.js"></script>

    <link rel="stylesheet" href="../asset/bootstrap4.1.3/css/bootstrap.min.css">
    <script src="../asset/bootstrap4.1.3/js/bootstrap.min.js"></script>
    <script src="../asset/jquery.qrcode.min.js"></script>



  </head>
  <body id="page-top" class="font-kanit" style="margin: 0px">



<div style="border: 2px solid #000;">
   <div style="padding:0px;">

     <center>
        <h2><? echo $form_title; ?></h2><br>
        <?
           $visitDate = changeToThaiDate($visit_date);
           echo "สำหรับ UIC: $uic / PID: $pid <br>
           นัดหมายวันที่ $visitDate ($visit_id)
           <br><br>" ; ?>

        <a href="<?echo $web_link_qrcode; ?>" target="_blank">LINK <? echo "$form_name / $uic [$pid]"; ?></a>
     </center>
   </div>

   <div style="padding: 8px 10px 15px;">
     <center>
           <div id="div_qr" >

           </div>
     </center>
   </div>

   <div style="padding: 8px 10px 15px;">
     <center>
      <a href="<?echo $web_link_qrcode; ?>" target="_blank"><?echo $web_link_qrcode; ?></a>
     </center>
   </div>
</div>


  </body>
</html>

<script>
  $(document).ready(function(){

    $('#div_qr').qrcode({
      text: "<?echo $web_link_qrcode; ?>",
      width: 500,
      height: 500
    });

  });

</script>
