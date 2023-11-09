
    <?
    include_once("../a_app_info.php");

    $e = isset($_GET["r"])?$_GET["r"]:"";


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
      <div class = "my-4 px-4 py-4" id="div_main">

          <div><h3><b>สิ้นสุดการกรอกข้อมูล</b></h3></div>
          ขอบคุณที่ท่านสละเวลาอ่านข้อมูลการให้บริการแบบ Xpress ในโอกาสหน้าหากท่านต้องการรับบริการที่สะดวก รวดเร็ว และเป็นส่วนตัว ท่านสามารถแจ้งเจ้าหน้าที่ เพื่อรับบริการแบบ Xpress ได้ทุกเมื่อ และขอบคุณที่ท่านไว้วางใจให้เราได้ดูแลสุขภาพของท่าน

      </div>

      <div class="row my-4 px-4 py-4 " id="xpress_pass_txt" style="display:none; background-color:#eee;">
        <div class="col-md-12 ">
            <div class="mb-1">
             <span class="text-success px-4 py-2"><h4><i class='fa fa-check'></i> ผ่านเกณฑ์การเข้ารับบริการ Xpress</h4></span>
            </div>
            <div>
              <b>ยินดีด้วย ท่านผ่านเกณฑ์การเข้ารับบริการ Xpress ในครั้งนี้</b>
  โปรดแสดงหน้านี้กับเจ้าหน้าที่ ณ จุดลงทะเบียน ท่านจะได้รับบริการที่สะดวก รวดเร็ว และเป็นส่วนตัว ในรูปแบบการบริการแบบ Xpress ของเรา
            </div>
        </div>
      </div>


      <div class="row my-4 px-4 py-4" id="xpress_fail_txt" style="display:none; background-color:#eee;">
        <div class="col-md-12 ">
            <div class="mb-1">
             <span class="text-danger px-4 py-2"><h4><i class='fa fa-times'></i> ไม่ผ่านเกณฑ์การเข้ารับบริการ Xpress</h4></span>
            </div>
            <div>
              <b>ท่านไม่ผ่านเกณฑ์การเข้ารับบริการแบบ Xpress ในครั้งนี้</b>
  ในการรับบริการครั้งนี้ท่านจะได้รับบริการตามระบบปกติของทางศูนย์สุขภาพชุมชน ขอขอบคุณที่ท่านสนใจการให้บริการแบบ Xpress ในโอกาสหน้าหากท่านผ่านเกณฑ์การเข้ารับบริการ Xpress ท่านจะได้รับการบริการที่สะดวก รวดเร็ว และเป็นส่วนตัว และขอบคุณที่ท่านไว้วางใจให้เราได้ดูแลสุขภาพของท่าน
            </div>
        </div>
      </div>
    </div>

    <div class="jumbotron text-center" style="margin-bottom:0">
      <? //include_once("../inc_footer.php"); ?>
    </div>

    </body>
    </html>
    <script>
      $(document).ready(function(){

<?
if($e == "Y"){ // pass
  echo '$("#xpress_pass_txt").show();';
}
else if($e == "N"){ // fail
  echo '$("#xpress_fail_txt").show();';
}

?>
      });
    </script>
