<?

$choice = isset($_GET["c"])?$_GET["c"]:"";


$msg_info="";
if($choice == "1"){
  $msg_info="
  <center>
    <h2>ระบบได้รับข้อมูลเรียบร้อยแล้ว</h2><br>
    ขอบคุณครับ
  </center>
  ";
}
else if($choice == "0"){
  $msg_info="
  <center>
    <h2>อาจมีข้อมูลแบบฟอร์มที่ท่านกรอกอยู่ในระบบแล้ว</h2><br>
    กรุณาติดต่อเจ้าหน้าที่ หากมีข้อสงสัย
  </center>
  ";
}

else if($choice == "2"){
  $msg_info="
  <center>
    <h2>ข้อมูล<u>ไม่ถูกต้อง</u></h2><br>
  </center>
  ";
}

?>
<!doctype html>
<html>
<head>
<title>weClinic Form Completed</title>
<link rel="stylesheet" href="../asset/fontawesome/css/all.css">
<link href="https://fonts.googleapis.com/css?family=Kanit:300,400,700&amp;subset=thai" rel="stylesheet">


<link rel="stylesheet" href="../asset/jquery-ui.css">
<script src="../asset/jquery.min.js"></script>


</head>
<body>


<div id="div_survey" class="Kanit" style="margin-top:50px;">
   <? echo $msg_info; ?>
</div>


</body>
</html>

<script>

</script>
