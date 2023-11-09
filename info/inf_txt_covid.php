
    <?

    include_once("../a_app_info.php");

    $e = isset($_GET["e"])?$_GET["e"]:"";
    $txt_result="";
    if($e == "end"){ // get form info successfully
      $txt_result="<h4 class='text-info'>ขณะนี้ทางโครงการวิจัยได้รับข้อมูลแบบสอบถามครบตามจำนวนที่ต้องการแล้ว</h4><br>
       <br>
      <div><h4>
      <i class='fa fa-praying-hands fa-lg' ></i>
      ขอขอบพระคุณท่านที่ให้ความสนใจ
      </h4></div>
      ";
    }
    if($e == "dup"){ // get form info successfully
      $txt_result="<h4 class='text-info'>ทางโครงการวิจัยได้รับข้อมูลแบบสอบถามของท่านมาก่อนหน้านี้แล้วครับ</h4><br>
       <br>
      <div><h4> 
      <i class='fa fa-praying-hands fa-lg' ></i>
      ขอขอบพระคุณท่านที่ให้ความสนใจ
      </h4></div>
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
      <h1> <b>IHRI</b> <i class="fa fa-clinic-medical fa-lg text-danger" ></i> <b>we</b>Clinic</h1>
      </center></div>

      <div class = "my-4" id="div_main">
        <div class = "my-2 py-2 px-2 bg-info text-white" >
          <center>
        <h4>
        <b><u>โครงการวิจัย</u></b><br>การศึกษาเพื่อแก้ไขการตีตราและการเลือกปฏิบัติต่อชุมชนที่ได้รับผลกระทบจากโควิด-19 ผ่านการเตรียมชุมชนและการสื่อสารสาธารณะในประเทศไทย
      </h4>
    </center>
        </div>
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
