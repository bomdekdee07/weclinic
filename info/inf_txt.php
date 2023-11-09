
    <?
    include_once("../a_app_info.php");

    $e = isset($_GET["e"])?$_GET["e"]:"";
    $txt_result="";
    if($e == "f1"){ // get form info successfully
      $f = isset($_GET["f"])?$_GET["f"]:"";
      $u = isset($_GET["u"])?$_GET["u"]:"";
      $txt_result="<h4 class='text-info'>ได้รับข้อมูลเรียบร้อยแล้ว</h4><br>
      ขอบคุณครับ <br><br>
      <div><h4>$f</h4></div>$u
      ";
    }

    else if($e == "np"){ // get form info successfully
        $f = isset($_GET["f"])?$_GET["f"]:""; // form name
        $c = isset($_GET["c"])?$_GET["c"]:"";
        if($c == ""){
          $txt_result="<h4 class='text-info'>ได้รับข้อมูลเรียบร้อยแล้ว</h4><br>
          ท่านไม่ผ่านเกณฑ์การคัดเลือกของโครงการ <br>
          ขอบคุณสำหรับการกรอกข้อมูลครับ <br><br>
          <div><h4>$f</h4></div>
          ";
        }
        else if($c == "no"){
          $txt_result="<h4 class='text-info'>ได้รับข้อมูลเรียบร้อยแล้ว</h4><br>
          จบแบบสอบถาม เนื่องจากท่านไม่ยินยอมเข้าร่วมโครงการ <br>
          ขอบคุณสำหรับการกรอกข้อมูลครับ <br><br>
          <div><h4>$f</h4></div>
          ";
        }

    }




    ?>


    <!doctype html>
    <html>
    <head>
      <meta http-equiv=Content-Type content="text/html; charset=utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title><? echo $GLOBALS['title']; ?></title>
    <link rel="stylesheet" href="../asset_iclinic/iclinic.css">
    <link rel="stylesheet" href="../asset/fontawesome/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Kanit:300,400,700&amp;subset=thai" rel="stylesheet">


    <link rel="stylesheet" href="../asset/jquery-ui.css">
    <script src="../asset/jquery.min.js"></script>

    <link rel="stylesheet" href="../asset/bootstrap4.1.3/css/bootstrap.min.css">
    <script src="../asset/bootstrap4.1.3/js/bootstrap.min.js"></script>

    </head>
    <body>


    <div class="container-fluid" style="margin-top:30px">
      <div class = "my-4"><center>
      <h1>  <i class="fa fa-clinic-medical fa-lg text-danger" ></i> <b>we</b>Clinic</h1>
      </center></div>
      <div class = "my-4" id="div_main">
        <center>  <? echo $txt_result; ?> </center>
      </div>

    </div>

    <div class="jumbotron text-center" style="margin-bottom:0">
      <? //include_once("../inc_footer.php"); ?>
    </div>

    </body>
    </html>
    <script>
      $(document).ready(function(){

      });
    </script>
