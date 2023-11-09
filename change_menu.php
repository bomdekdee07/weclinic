<?
session_start();
$cur_menu = isset($_GET["mnu"])?$_GET["mnu"]:"";

if(isset($_SESSION)){
  //echo "<br>enter main sesseion ";
  //echo "<br>session:".$_SESSION["sc_id"]."/curMenu:".$_SESSION["cur_menu"];
  if(isset($_SESSION["cur_menu"])){
    $_SESSION["cur_menu"] = $cur_menu;
    header( "location: index.php" );
  }
  else{
    //echo "enter change menu";
    header( "location: logout.php" );
    exit(0);
  }
}
else{
  header( "location: logout.php" );
  exit(0);
}



?>
