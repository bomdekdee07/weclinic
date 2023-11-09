<?

include_once("../function/in_fn_link.php");

$open_link="N";

$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$is_backdate = isset($_GET["bd"])?$_GET["bd"]:"N"; // back date filled

if(isset($_GET["link"])){ // if there is link param (from open link / qr code)

  $decode_link = decodeSingleLink($_GET["link"]);
  $arr = explode(":",$decode_link);
  if(count($arr)==4){
    $uid = $arr[0]; // uid
    $visit_date = $arr[1]; // visit_date
    $uic = $arr[2]; // uic
    $site = $arr[3]; // site

    $open_link="Y";
    $cur_date = (new DateTime())->format('Y-m-d');

    if($is_backdate == "N"){
      if($cur_date != $visit_date){
        /*
        header( "location: ../info/invalid.php?e=e2" );
        exit(0);
        */
      }
    }

  }
  else{ // invalid link
    header( "location: ../info/invalid.php?e=e1" );
    exit(0);
  }

}





//$link = isset($_GET["link"])?urldecode($_GET["link"]):"";
