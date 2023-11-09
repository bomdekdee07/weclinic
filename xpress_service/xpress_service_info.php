
    <?

    include_once("../a_app_info.php");
    include_once("../function/in_fn_link.php");

    $link_encode = isset($_GET["link"])?urldecode($_GET["link"]):"";
    $is_backdate = isset($_GET["bd"])?$_GET["bd"]:"N"; // back date filled

    if($link_encode == ""){ // redirect to error page
      header("location: ../info/invalid.php?e=e1");
      exit(0);
    }

    $decode_link = decodeSingleLink($link_encode);
    $arr = explode(":",$decode_link);
    $uid =""; // uid
    $visit_date =""; // visit_date
    $uic =""; // uic

    if(count($arr)==4){
      $uid = $arr[0]; // uid
      $visit_date = $arr[1]; // visit_date
      $uic = $arr[2]; // uic
      $site = $arr[3]; // site
    }
    else{// redirect to error page
      header("location: ../info/invalid.php?e=e1");
      exit(0);
    }

    ?>


    <!doctype html>
    <html>
    <head>
    <title>XPress Service <? echo $GLOBALS['title']; ?></title>
    <link rel="stylesheet" href="../asset_iclinic/iclinic.css">
    <link rel="stylesheet" href="../asset/fontawesome/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Kanit:300,400,700&amp;subset=thai" rel="stylesheet">


    <link rel="stylesheet" href="../asset/jquery-ui.css">
    <script src="../asset/jquery.min.js"></script>

    <link rel="stylesheet" href="../asset/bootstrap4.1.3/css/bootstrap.min.css">
    <script src="../asset/bootstrap4.1.3/js/bootstrap.min.js"></script>

    </head>
    <body>


    <div class="container-fluid" style="margin-top:0px">

      <div class = "mt-0 mb-4 font-kanit" id="div_main" style="background-color:#004F8B;">
        <center>
        <img src='../image/xpress_service_cover.png'>
        </center><br>
        <center>
           <a href='<? echo "../visit_form/x_xpress_service.php?link=$link_encode&bd=$is_backdate";?>' class='btn btn-warning px-4 py-2'>
             <h1>เริ่มทำแบบสอบถาม</h1>
           </a>
           <br><br><br>
        </center>



      </div>

    </div>

    <div class="jumbotron text-center" style="margin-bottom:0">
      <? //include_once("../inc_footer.php"); ?>
      <? echo "XPress Service [$uic/$uid] $visit_date"; ?>
    </div>

    </body>
    </html>
    <script>
      $(document).ready(function(){

      });
    </script>
