
    <?
    include_once("../a_app_info.php");

    $e = isset($_GET["e"])?$_GET["e"]:"";
    $txt_result="INVALID";
    if($e == "e1"){ // invalid form date
      $txt_result="<h4 class='text-danger'>เกิดข้อผิดพลาด / INVALID</h4><br>
      ไม่สามารถดำเนินการได้ <u>ลิ้งค์นี้ไม่ถูกต้อง</u>
      ";
    }
    else if($e == "e2"){ // invalid form date
      $txt_result="<h4 class='text-danger'>เกิดข้อผิดพลาด / INVALID</h4><br>
      ไม่สามารถดำเนินการได้ <u>ลิ้งค์นี้หมดอายุ</u>
      ";
    }
    else if($e == "e3"){ // invalid  form done already
      $txt_result="<h4 class='text-danger'>เกิดข้อผิดพลาด / INVALID</h4><br>
      ไม่สามารถดำเนินการได้ <u>ฟอร์มนี้ถูกทำไปแล้ว</u>
      ";
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
