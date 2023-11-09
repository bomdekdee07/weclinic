<?
include_once("../function/in_fn_link.php");

$open_link="N";

$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$visit_date = isset($_GET["visit_date"])?$_GET["visit_date"]:"";
$visit_id = isset($_GET["visit_id"])?$_GET["visit_id"]:"";
$project_id = isset($_GET["proj_id"])?$_GET["proj_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
$is_backdate = isset($_GET["bd"])?$_GET["bd"]:"N"; // back date filled

$link = isset($_GET["link"])?$_GET["link"]:"";

if(isset($_GET["link"])){ // if there is link param (from open link / qr code)

  $decode_link = decodeSingleLink($_GET["link"]);
  $arr = explode(":",$decode_link);
  if(count($arr)==7){
    $uid = $arr[0]; // uid
    $visit_date = $arr[1]; // visit_date
    $visit_id = $arr[2]; // visit_id
    $project_id = $arr[3]; // proj_id
    $group_id = $arr[4]; // group_id

    $uic = $arr[5]; // uic
    $pid = $arr[6]; // pid

    $open_link="Y";
    $cur_date = (new DateTime())->format('Y-m-d');

    if($is_backdate == "N"){
      if($cur_date != $visit_date){
        header( "location: ../info/invalid.php?e=e2" );
        exit(0);
      }
    }

  }
  else{
  //  echo "count: ".count($arr);

    header( "location: ../info/invalid.php?e=e1" );
    exit(0);


  }

}





//$link = isset($_GET["link"])?urldecode($_GET["link"]):"";
