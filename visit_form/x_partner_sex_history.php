<?

// ประวัติการมีเพศสัมพันธ์กับคู่นอน (Partner Sex History) ต่อเนื่องมาจาก form log


$open_link="N";

$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";
$collect_date = isset($_GET["collect_date"])?$_GET["collect_date"]:"";
$sexhist_no = isset($_GET["sexhist_no"])?$_GET["sexhist_no"]:"-1";
$sexhist_seq_no = isset($_GET["sexhist_seq_no"])?$_GET["sexhist_seq_no"]:"-1";
$seq_no = isset($_GET["seq_no"])?$_GET["seq_no"]:"";
$is_backdate = isset($_GET["bd"])?$_GET["bd"]:"N"; // back date filled

$visit_date = $collect_date;


$form_id = "partner_sexhist";
//$form_id = "sero_con";
$form_name = "ประวัติการมีเพศสัมพันธ์กับคู่นอน (Partner Sex History)";
$form_top = ""; // text display at the top of the form
$form_bottom = ""; // text display at the bottom of the form
$after_save_function = ""; // trigger after save function
$initJSForm = ''; // initial js in f_form_main.php

if($open_link != "Y"){
  include_once("../in_auth_db.php");
  if(!isset($auth["data"]) && !isset($auth["log"])){ // check auth
     $initJSForm .= '$("#btn_save").hide();';
  }
}

//show partner info
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function

$div_data = "";

$hiv_result_txt = "-";
$partner_relate_txt = "-";
$partner_thai_txt = "-";
$partner_gender_txt = "-";

$query = "SELECT z.partner_name, z.partner_relate, z.partner_thai, z.gender, z.hiv_result, z.age,
max(x.sexhist_no) as max_seq, max(x.sexhist_seq_no) as max_sexhist_seq_no
FROM z_sex_partner as z
LEFT JOIN x_partner_sexhist as x ON (z.uid=x.uid AND x.seq_no=z.seq_no)
WHERE z.uid = ? AND z.collect_date = ? AND z.seq_no=?
";
//echo "$uid, $visit_date / $query";
         $stmt = $mysqli->prepare($query);
         $stmt->bind_param("sss",  $uid, $collect_date, $seq_no);
         if($stmt->execute()){
           $stmt->bind_result( $partner_name,$partner_relate, $partner_thai,
           $gender, $hiv_result, $age, $max_seq_no, $max_sexhist_seq_no
            );


           if ($stmt->fetch()) {
             $row_count = $stmt->num_rows;

             if($hiv_result !== NULL){
               if($hiv_result == "NR")  $hiv_result_txt = "ผลเป็นลบ (Non-reactive)";
               else if($hiv_result == "R") {
                 $hiv_result_txt = "ผลเป็นบวก (Reactive)";
               }
               else if($hiv_result == "NS")  $hiv_result_txt = "ไม่ทราบ";
             }

             if($partner_relate !== NULL){
               if($partner_relate == "1")  $partner_relate_txt = "คู่นอนประจำ";
               else if($partner_relate == "2")  $partner_relate_txt = "คู่นอนชั่วคราว";
               else if($partner_relate == "3")  $partner_relate_txt = "พนักงานบริการ";
               else if($partner_relate == "4")  $partner_relate_txt = "ผู้ใช้บริการ (ได้รับเงินหรือสิ่งของเพื่อแลกกับการมีเพศสัมพันธ์)";
             }

             if($partner_thai !== NULL){
               if($partner_thai == "Y")  $partner_thai_txt = "คนไทย";
               else if($partner_thai == "N")  $partner_thai_txt = "คนต่างชาติ";
             }

             if($gender !== NULL){
               if($gender == "M")  $partner_gender_txt = "ชาย";
               else if($gender == "F")  $partner_gender_txt = "หญิง";
               else if($gender == "TGW")  $partner_gender_txt = "ชายข้ามเพศเป็นหญิง";
               else if($gender == "TGF")  $partner_gender_txt = "หญิงข้ามเพศเป็นชาย";
             }



           }

          $stmt->close();
         }


         if($sexhist_no == '-1'){ // add new
           if($max_seq_no !== NULL) $sexhist_no = $max_seq_no+1;
           else $sexhist_no = 0;
         }

         if($sexhist_seq_no == '-1'){ // add new
           if($max_sexhist_seq_no !== NULL) $sexhist_seq_no = $max_sexhist_seq_no+1;
           else $sexhist_seq_no = 1;
         }


         $form_top = "
         <div class='px-4 py-1'>
           <div><h3>ข้อมูลคู่นอน</h3></div>
           <div><b>ชื่อเรียก: <u>$partner_name</u> ($partner_thai_txt) </b> </div>
           <div>เพศ: $partner_gender_txt  ความสัมพันธ์: $partner_relate_txt </div>
         </div>
         ";




$section_druguse_q="lastneg_date,lastneg_place,lastneg_result";
$section_lastneg_t="lastneg";

$option_showhide = "
shData['sexhist_druguse-Y'] = {dtype:'radio',
show_q:'sexhist_drugmethod'};
shData['sexhist_druguse-N'] = {dtype:'radio',
hide_q:'sexhist_drugmethod,sexhist_needleshare'};


shData['vagina_insert-Y'] = {dtype:'radio',
show_q:'vagina_insert_condom,vagina_insert_nocondom'};
shData['vagina_insert-N'] = {dtype:'radio',
hide_q:'vagina_insert_condom,vagina_insert_nocondom'};

shData['anal_insert-Y'] = {dtype:'radio',
show_q:'anal_insert_condom,anal_insert_nocondom'};
shData['anal_insert-N'] = {dtype:'radio',
hide_q:'anal_insert_condom,anal_insert_nocondom'};

shData['anal_recep-Y'] = {dtype:'radio',
show_q:'anal_recep_condom,anal_recep_nocondom'};
shData['anal_recep-N'] = {dtype:'radio',
hide_q:'anal_recep_condom,anal_recep_nocondom'};

shData['oral_insert-Y'] = {dtype:'radio',
show_q:'oral_insert_condom,oral_insert_nocondom'};
shData['oral_insert-N'] = {dtype:'radio',
hide_q:'oral_insert_condom,oral_insert_nocondom'};

shData['oral_recep-Y'] = {dtype:'radio',
show_q:'oral_recep_condom,oral_recep_nocondom'};
shData['oral_recep-N'] = {dtype:'radio',
hide_q:'oral_recep_condom,oral_recep_nocondom'};

shData['neovagina_insert-Y'] = {dtype:'radio',
show_q:'neovagina_insert_condom,neovagina_insert_nocondom'};
shData['neovagina_insert-N'] = {dtype:'radio',
hide_q:'neovagina_insert_condom,neovagina_insert_nocondom'};

shData['neovagina_recep-Y'] = {dtype:'radio',
show_q:'neovagina_recep_condom,neovagina_recep_nocondom'};
shData['neovagina_recep-N'] = {dtype:'radio',
hide_q:'neovagina_recep_condom,neovagina_recep_nocondom'};

shData['vagina_insert_condom-1'] = {dtype:'radio',
hide_q:'vagina_insert_nocondom'};
shData['vagina_insert_condom-2'] = {dtype:'radio',
show_q:'vagina_insert_nocondom'};
shData['vagina_insert_condom-3'] = {dtype:'radio',
show_q:'vagina_insert_nocondom'};

shData['anal_insert_condom-1'] = {dtype:'radio',
hide_q:'anal_insert_nocondom'};
shData['anal_insert_condom-2'] = {dtype:'radio',
show_q:'anal_insert_nocondom'};
shData['anal_insert_condom-3'] = {dtype:'radio',
show_q:'anal_insert_nocondom'};

shData['oral_insert_condom-1'] = {dtype:'radio',
hide_q:'oral_insert_nocondom'};
shData['oral_insert_condom-2'] = {dtype:'radio',
show_q:'oral_insert_nocondom'};
shData['oral_insert_condom-3'] = {dtype:'radio',
show_q:'oral_insert_nocondom'};

shData['neovagina_insert_condom-1'] = {dtype:'radio',
hide_q:'neovagina_insert_nocondom'};
shData['neovagina_insert_condom-2'] = {dtype:'radio',
show_q:'neovagina_insert_nocondom'};
shData['neovagina_insert_condom-3'] = {dtype:'radio',
show_q:'neovagina_insert_nocondom'};

shData['anal_recep_condom-1'] = {dtype:'radio',
hide_q:'anal_recep_nocondom'};
shData['anal_recep_condom-2'] = {dtype:'radio',
show_q:'anal_recep_nocondom'};
shData['anal_recep_condom-3'] = {dtype:'radio',
show_q:'anal_recep_nocondom'};

shData['neovagina_recep_condom-1'] = {dtype:'radio',
hide_q:'neovagina_recep_nocondom'};
shData['neovagina_recep_condom-2'] = {dtype:'radio',
show_q:'neovagina_recep_nocondom'};
shData['neovagina_recep_condom-3'] = {dtype:'radio',
show_q:'neovagina_recep_nocondom'};

/*
shData['sexhist_drug_inject'] = {dtype:'radio',
show_q:'sexhist_needleshare'};
shData['sexhist_druguse-N'] = {dtype:'radio',
hide_q:'sexhist_needleshare'};
*/


";
//$option_showhide = "";

include_once("x_partner_sex_history_form_main.php");
//include_once("f_form_main.php");

?>


<script>
$(document).ready(function(){

  $('html, body').animate({
     scrollTop: 50
  }, 500);

  $("#sexhist_drug_inject").click(function(){ // ฉีดยา
      if($(this).prop("checked") == true){
        $("#q_sexhist_needleshare").show();
        $("#q_sexhist_needleshare").data("is_show",'1');

      }
      else{
        $("#q_sexhist_needleshare").hide();
        $("#q_sexhist_needleshare").data("is_show",'0');
      }

  });

});


</script>
