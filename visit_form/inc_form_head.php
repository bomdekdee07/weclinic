

<?
 if($uic != "")
 $form_name .= " - $uic";

?>

<!doctype html>
<html>
<head>
  <meta http-equiv=Content-Type content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title><? echo "$form_name" ?></title>


<link rel="stylesheet" href="../asset/jquery-ui.css">
<script src="../asset/jquery.min.js"></script>
<script src="../asset/jquery-ui-custom.js"></script>
<script src="../asset/datepicker-th.js"></script>

<link rel="stylesheet" href="../asset/bootstrap4.1.3/css/bootstrap.min.css">
<script src="../asset/popper.min.js" ></script>
<script src="../asset/bootstrap4.1.3/js/bootstrap.min.js"></script>

<script src="../asset/notify.min.js"></script>
<script src="../asset/jquery.qrcode.min.js"></script>
<script src="../asset/jquery.maskedinput.js"></script>

<link rel="stylesheet" href="../asset_iclinic/iclinic.css">
<link rel="stylesheet" href="../asset/fontawesome/css/all.css">




<style>
#div_loading {
  /*  background-color:#EEE;*/
    display: table;
    width: 100%;
    height: 400px;
}
#loading_box {
    display: inline-block;
    vertical-align: top;
}
.v-align {
    padding: 10px;
    display: table-cell;
    text-align: center;
    vertical-align: middle;
}
</style>

</head>
<body>
  <div id = div_loading>
      <div class="v-align">
          <div id="loading_box">
            <h1><i class="fas fa-spinner fa-spin text-danger"></i> Loading </h1><br>
            <i class="fas fa-cat fa-lg text-info"></i> กรุณารอสักครู่

          </div>
      </div>
  </div>
  <div id = div_main>
    <div class="bg-primary text-white">
      <center>
      <?

        if(isset($pid) && isset($visit_id)){
          echo "$form_name [UIC:$uic / PID:$pid] visit:$visit_id";
        }
        else{
          if($uic != "")
          echo "$form_name [UIC:$uic / UID:$uid]";
        }

      ?>
      </center>
    </div>
