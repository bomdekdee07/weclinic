<?

$form_id = "xpress_satisfaction_after";
$form_name = "Xpress Service : Satisfaction (After Service)";

include_once("../xpress_service/inc_param.php");


if($open_link == "Y"){

include_once("../in_db_conn.php");
$is_form_done = "N";
$query = "SELECT collect_date
FROM x_xpress_satisfaction
WHERE uid=? AND collect_date=? AND xp_after_service_compare <> ''
";

         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("ss", $uid, $visit_date);
         if($stmt->execute()){
           $stmt->bind_result($collect_date);

           if ($stmt->fetch()) {
             //echo "open_link $collect_date";
             $is_form_done = "Y";
           }
    $stmt->close();
}
  //$mysqli->close();
  if($is_form_done == "Y"){
    $mysqli->close();
    //echo "form done ";
    header( "location: ../info/invalid.php?e=e2" ); // expired link
    exit(0);
  }
}

$form_top = "
<div class='mb-4 px-2 py-4' style='background-color:#eee;'>
  <div><b>แบบสอบถามประเมินความพึงพอใจต่อการบริการที่สะดวกและรวดเร็วสำหรับผู้รับบริการ (Xpress service) <i>ภายหลังได้รับการบริการแบบ Xpress service ที่ศูนย์สุขภาพชุมชน</i> (ใช้เวลาในการตอบแบบสอบถามประมาณ 1 นาที)</b></div>
</div>
"; // text display at the top of the form

$form_bottom = ""; // text display at the bottom of the form
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php
$option_showhide = "";

$group_id = "";
$project_id="";

//echo "$uid/$visit_id/$project_id/$group_id/$form_id/$visit_date/$open_link <br>";

if($open_link != "Y"){
  include_once("../in_auth_db.php");
  if(!isset($auth["data"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
}


include_once("f_form_main.php");
//include_once("xpress_satisfaction_form_main.php");

?>


<script>



</script>
