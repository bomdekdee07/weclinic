<?

/*

This link can be filled backdate form if form is not done before
by add querystring  &bd=Y
eg. link_form.php?link=xxxx&bd=Y // xxx= 7 param, bd= is_backdate

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


$proj_id = "COVID";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
$is_backdate = isset($_GET["bd"])?$_GET["bd"]:"Y"; // Y: can be fill backdate (include 15/12/2019)

$form_name = isset($_GET["form_name"])?$_GET["form_name"]:"No Form";
$form_title = "<h1><span style='font-size:40px;'>แบบฟอร์ม $form_name</span></h1>";


/*
$link_encode = "$group_id";
$link_encode = encodeSingleLink($link_encode);
$web_link_qrcode = $GLOBALS['site_path']."visit_form/f_form_proj.php?proj_id=$proj_id&form_id=$form_id&link=$link_encode&bd=$is_backdate";
*/

$page_name = "";
if($group_id == '1') $page_name = "pis_g1_pui";
else if($group_id == '2') $page_name = "pis_g2_commu";
else if($group_id == '3') $page_name = "pis_g3_hcw";
else if($group_id == '4') $page_name = "pis_g4_public";

$web_link_qrcode = $GLOBALS['site_path']."w_proj_covid19/$page_name.php";


?>




<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    <title><? echo "$form_name" ;?></title>


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
           echo "สำหรับกลุ่ม: $group_id <br><br><br>" ; ?>

      <!--  <a href="<?echo $web_link_qrcode; ?>" target="_blank">LINK <? echo "$form_name / $uic [$uid]"; ?></a> -->


      <button class="btn btn-primary" onclick="copyFormLink();">คัดลอกลิ้งค์ / Copy Link</button>
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
      <input type="text" value="<?echo $web_link_qrcode; ?>" id="link_form" >
    <!--  <a href="<?echo $web_link_qrcode; ?>" target="_blank"><?echo $web_link_qrcode; ?></a> -->
     </center>
   </div>
</div>


  </body>
</html>

<script>
  $(document).ready(function(){
  //  $('#link_form').hide();

    $('#div_qr').qrcode({
      text: "<?echo $web_link_qrcode; ?>",
      width: 500,
      height: 500
    });



  });

  function copyFormLink() {
    /* Get the text field */
    var copyText = document.getElementById("link_form");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/

    /* Copy the text inside the text field */
    document.execCommand("copy");

    /* Alert the copied text */
    //alert("Copied the text: " + copyText.value);
  }

</script>
