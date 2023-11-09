<?

include_once("inc_param.php");

//echo "$uid/$visit_date/$visit_id/$project_id/$group_id/$open_link/$form_id";


$form_id = "v1_sut_previsit";
$form_name = "STANDUP-TEEN <b>Visit</b> ";

$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$before_save_function = ""; // trigger before save function
$after_save_function = ""; // trigger after save function
$before_save_data = "
saveSUT_Visit(); alert('บันทึกข้อมูลแล้ว');
"; // trigger before save data (after validate data before save)
$initJSForm = ''; // initial js in f_form_main.php

$js_sut_form = "";
$screen_date_open_link = $visit_date; // for open_link=Y

if($open_link != "Y"){ // open link by staff
  include_once("../in_auth_db.php");

  if(!isset($auth["data"]) && !isset($auth["log"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }

}
else{ // open link by patient

    include_once("../in_db_conn.php");
    $query = "SELECT count(uid) as count
               FROM p_project_uid_visit
               WHERE uid = ? AND schedule_date=? AND visit_date <> '0000-00-00' AND proj_id='SUT_PRE'
    ";

             $stmt = $mysqli->prepare($query);
             $stmt->bind_param("ss", $uid, $visit_date);
             if($stmt->execute()){
               $stmt->bind_result($count);

               if ($stmt->fetch()) {

               }
             }
        $stmt->close();


        if($count > 0){
          header( "location: ../info/invalid.php?e=e3" );
          exit(0);
        }


   $visit_date = (new DateTime())->format('Y-m-d');
}



$option_showhide = "

// show/hide question
shData['hivself-Y'] = {dtype:'radio',
show_q:'hivself_sc'};
shData['hivself-N'] = {dtype:'radio',
hide_q:'hivself_sc'};

shData['hivself_type-liquid'] = {dtype:'radio',
show_q:'hivself_liquid',hide_q:'hivself_blood'};
shData['hivself_type-blood'] = {dtype:'radio',
hide_q:'hivself_liquid',show_q:'hivself_blood'};


";


include_once("f_form_main.php");
?>


<script>



function saveSUT_Visit(){ // update visit to (and may create pid if none)

  var aData = {
            u_mode:"update_visit_paper",
            uid:"<? echo $uid; ?>",
            screen_date:"<? echo $screen_date_open_link; ?>",
            open_link:"<? echo $open_link; ?>"

  };

  save_data_ajax(aData,"../w_proj_SUT_PRE/db_sut.php",saveSUT_VisitComplete);

}
function saveSUT_VisitComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is saveSUT_VisitComplete : "+flagSave);
  if(flagSave){
     $("#form_visit_date").val(rtnDataAjax.visit_date);
  }
}






</script>
