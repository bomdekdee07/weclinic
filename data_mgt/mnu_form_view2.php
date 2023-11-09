<?


/*
$form_id = isset($_GET["form_id"])?$_GET["form_id"]:"";
$form_id = isset($_GET["form_id"])?$_GET["form_id"]:"";
$form_id = isset($_GET["form_id"])?$_GET["form_id"]:"";

$form_id = isset($_GET["form_id"])?$_GET["form_id"]:"";
$form_id =  isset($_GET["lang"])?$_GET["lang"]:"th";
*/


include_once("inc_auth.php"); // set permission view, update, delete
include_once("../a_app_info.php");
include_once("../in_db_conn.php");



$uid = getQueryString("uid");
$collect_date = getQueryString("collect_date");
$collect_time = getQueryString("collect_time");

//$uid="P21-9999"; $collect_date="2021-03-17"; $collect_time="00:00:00";

$form_id = getQueryString("form_id");
$lang = getQueryString("lang");
$s_id = getQueryString("s_id");


//Jeng Code for next form
$sNFormId = getQueryString("next_form_id");
$aFormList = array(); $qsNextForm = "";
if($sNFormId!=""){
  $aFormList = explode(",",$sNFormId);
  $sNFormId = $aFormList[0];
  for($ix=1;$ix<count($aFormList);$ix++){
    $qsNextForm.= (($qsNextForm=="")?"":",").$aFormList[$ix];
  }
}
// End Jeng Code

if($s_id == ""){
  if (session_status() == PHP_SESSION_NONE) session_start();

   if(isset($_SESSION["s_id"])){
     $s_id =$_SESSION["s_id"];
   }
}

$show_data_id = getQueryString("show_data_id");

if($lang == "") $lang="th"; // thai default language

$d_sub_item = array(); // sub data item in form
$query = "SELECT  data_id, data_seq, data_name_$lang, data_value
FROM p_data_sub_list
WHERE data_id IN
(select data_id from p_form_list_data where form_id=? )

ORDER BY data_id, data_seq
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('s',$form_id);
//echo "query : $query";
if($stmt->execute()){
  $stmt->bind_result($data_id, $data_seq, $data_name, $data_value);
  while ($stmt->fetch()) {
      if(!isset($d_sub_item[$data_id] ))  $d_sub_item[$data_id] = array();

      $d_sub_item[$data_id][$data_name] = $data_value;
  }// while
}
else{
$msg_error .= $stmt->error;
}
$stmt->close();


$d_put_after_item = array(); // put after item in form
$query = "SELECT  da.data_id, da.data_parent_id, da.data_parent_value,
dl.data_type,  dl.data_prefix_$lang, dl.data_suffix_$lang, fld.is_require
FROM p_form_list_data_action as da
LEFT JOIN p_data_list as dl ON(da.data_id=dl.data_id)
LEFT JOIN p_form_list_data as fld ON(da.data_id=fld.data_id)

WHERE da.form_id=? AND da.action_type='put_after'
ORDER BY da.data_id
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('s',$form_id);
//echo "query : $query";
if($stmt->execute()){
  $stmt->bind_result($data_id, $data_parent_id, $data_parent_value, $data_type, $data_prefix, $data_suffix, $is_require);
  while ($stmt->fetch()) {
      if(!isset($d_put_after_item[$data_parent_id] ))  $d_put_after_item[$data_parent_id] = array();

      $d_put_after_item[$data_parent_id][$data_parent_value] = "$data_id<$data_type<$data_prefix<$data_suffix<$is_require";

  }// while
}
else{
$msg_error .= $stmt->error;
}
$stmt->close();



$cur_requireif = "";
$dtxt_requireif_item = "";


$query = "SELECT  da.data_id, da.data_parent_id, da.data_parent_value, dl.data_type
FROM p_form_list_data_action as da , p_data_list as dl
WHERE da.form_id=? AND da.action_type = 'require_if'
AND da.data_parent_id = dl.data_id
ORDER BY da.data_id
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('s',$form_id);
if($stmt->execute()){
  $stmt->bind_result($data_id, $data_parent_id, $data_parent_value, $data_parent_type);
    while ($stmt->fetch()) {
      if($cur_requireif != $data_id){
        $cur_requireif = $data_id;
        if($dtxt_requireif_item != ""){
          $dtxt_requireif_item = substr($dtxt_requireif_item,0,strlen($dtxt_requireif_item)-1)."'},";
        }
        $dtxt_requireif_item .= "{"."$data_id:'";
      }
      if($data_parent_type == "radio") $dtxt_requireif_item .= "$data_parent_type@$data_parent_id-$data_parent_value,";
      else if($data_parent_type == "dropdown") $dtxt_requireif_item .= "$data_parent_type@$data_parent_id>$data_parent_value,";
      else $dtxt_requireif_item .= "$data_parent_type@$data_parent_id,";
    }// while

  if($dtxt_requireif_item != ""){
    $dtxt_requireif_item = substr($dtxt_requireif_item,0,strlen($dtxt_requireif_item)-1);
    $dtxt_requireif_item .="'}";
  }
  else{
    $dtxt_requireif_item = "{}";
  }
}
else{
$msg_error .= $stmt->error;
}
$stmt->close();



$cur_hideif = "";
$dtxt_hideif_item = "";
$query = "SELECT  da.data_id, da.data_parent_id, da.data_parent_value, dl.data_type
FROM p_form_list_data_action as da , p_data_list as dl
WHERE da.form_id=? AND da.action_type = 'hide_if'
AND da.data_parent_id = dl.data_id
ORDER BY da.data_id
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('s',$form_id);
//echo "query : $query";
if($stmt->execute()){
  $stmt->bind_result($data_id, $data_parent_id, $data_parent_value, $data_parent_type);
    while ($stmt->fetch()) {
      if($cur_hideif != $data_id){
        $cur_hideif = $data_id;
        if($dtxt_hideif_item != ""){
          $dtxt_hideif_item = substr($dtxt_hideif_item,0,strlen($dtxt_hideif_item)-1)."'},";
        }
        $dtxt_hideif_item .= "{"."$data_id:'";
      }
      if($data_parent_type == "radio") $dtxt_hideif_item .= "$data_parent_type@$data_parent_id-$data_parent_value,";
      else if($data_parent_type == "dropdown") $dtxt_hideif_item .= "$data_parent_type@$data_parent_id>$data_parent_value,";
      else $dtxt_hideif_item .= "$data_parent_type@$data_parent_id,";
    }// while

  if($dtxt_hideif_item != ""){
    $dtxt_hideif_item = substr($dtxt_hideif_item,0,strlen($dtxt_hideif_item)-1);
    $dtxt_hideif_item .="'}";
  }
  else{
    $dtxt_hideif_item = "{}";
  }
}
else{
$msg_error .= $stmt->error;
}
$stmt->close();







$d_item = array(); // main data item in form
$query = "SELECT  fld.data_seq, fld.data_id,fld.data_type, fld.data_value, fld.is_require,
dl.data_prefix_$lang, dl.data_suffix_$lang, dl.data_name_$lang
FROM p_form_list_data as fld LEFT JOIN p_data_list as dl ON(fld.data_id = dl.data_id)
WHERE fld.form_id=?
AND fld.data_id NOT IN
(select data_id from p_form_list_data_action
  where form_id=? and action_type='put_after')
ORDER BY fld.data_seq
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('ss',$form_id, $form_id);
//echo "query : $query";
if($stmt->execute()){
  $stmt->bind_result($data_seq, $data_id, $data_type, $data_value,$is_require, $data_prefix, $data_suffix, $data_name);
  while ($stmt->fetch()) {
      //echo "<br>$data_seq, $data_id, $data_type, $data_value";

      if(!isset($d_item[$data_id] ))  $d_item[$data_id] = array();

      $d_item[$data_id]["data_type"] = $data_type;
      $d_item[$data_id]["data_value"] = $data_value;
      $d_item[$data_id]["data_prefix"] = $data_prefix;
      $d_item[$data_id]["data_suffix"] = $data_suffix;
      $d_item[$data_id]["data_name"] = $data_name;
      $d_item[$data_id]["is_require"] = $is_require;
  }// while
}
else{
$msg_error .= $stmt->error;
}
$stmt->close();



$d_data_result = array(); // data result of uid, collect_date, collect_time
$query = "SELECT  ds.data_id, ds.data_result
FROM p_data_result as ds, p_form_list_data as fld
WHERE ds.uid=? AND ds.collect_date=? AND ds.collect_time=?
AND ds.data_id=fld.data_id AND fld.form_id=?
";

//echo "$uid, $collect_date, $collect_time, $form_id / $query";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('ssss',$uid, $collect_date, $collect_time, $form_id);
//echo "query : $query";
if($stmt->execute()){
  $stmt->bind_result($data_id, $data_result);
  while ($stmt->fetch()) {
      $d_data_result[$data_id] = $data_result;
  }// while
}
else{
$msg_error .= $stmt->error;
}
$stmt->close();



// latest data
$query_add_latest = '';
if($collect_date != "0000-00-00")
$query_add_latest = " AND r.collect_date <='$collect_date' ";

$query = "SELECT  r.data_id, r.data_result
FROM p_data_result as r, p_data_list as dl ,
p_form_list_data as f
WHERE r.uid=? AND f.form_id=?
AND r.data_id = f.data_id AND r.data_id=dl.data_id
AND dl.data_category = '2' $query_add_latest
ORDER BY r.collect_date DESC
";


$stmt = $mysqli->prepare($query);
$stmt->bind_param('ss',$uid, $form_id);
//echo "query : $query";
if($stmt->execute()){
  $stmt->bind_result($data_id, $data_result);
  while ($stmt->fetch()) {
     if(!isset($d_data_result[$data_id]))
     $d_data_result[$data_id] = $data_result;
  }// while
}
else{
$msg_error .= $stmt->error;
}
$stmt->close();




//print_r($d_data_result);

$path_weclinic = "../";

$js_data_init = "";
$sHtml = "";
$last_qlabel = "";
foreach ($d_item as $d_id => $d_val) {
  $data_type = $d_val["data_type"];
  $data_value = $d_val["data_value"]; // for q_label, html
  $data_prefix = $d_val["data_prefix"];
  $data_suffix = $d_val["data_suffix"];
  $data_name = $d_val["data_name"]; // for checkbox
  $is_require = $d_val["is_require"]; // data requirement


  $sHtml .= "<div id='div_$d_id' class='mt-1'>";
  if($data_type == "html"){
    $sHtml .= $data_value;
  }
  else if($data_type == "q_label"){
    $sHtml .= "<hr><span id='$d_id' class='q_label' data-val=''><b>$data_value</b></span>";
    $last_qlabel_id = $d_id;
  }
  else if($data_type == "line"){
    $sHtml .= "<hr>";
  }
  else{ // component
    $sHtml .= " <span class='d_id_txt'>[$d_id]</span> ";
    $d_result = "";
    if(isset($d_data_result[$d_id])){
      $d_result = $d_data_result[$d_id];
    }


  $sHtml .= "$data_prefix ";
  if($data_type == "text"){
    $sHtml .= "<input type='text' id='$d_id' data-id='$d_id' data-require='$is_require'  data-odata='$d_result'  data-require='$is_require' class='save-data v_text' value='$d_result'>";
  }
  else if($data_type == "textarea"){
    $sHtml .= "<textarea id='$d_id' data-id='$d_id' data-require='$is_require' data-odata='$d_result'  data-require='$is_require' rows='4' class='save-data v_text' value='$d_result'></textarea>";
  }
  else if($data_type == "date"){
    $sHtml .= setDateData($d_id, $d_result, $is_require);
/*
    $odata_date = $d_result;
    if($d_result != "" && $d_result != "0000-00-00"){
       $dateVal = explode("-", $d_result);
       $dateVal[0] = $dateVal[0] + 543; // uncomment on 29/11/2019
       $d_result = $dateVal[2]."/".$dateVal[1]."/".$dateVal[0];
    }
  //  echo "date: $d_id/$d_result";
    $sHtml .= "<input type='text' id='$d_id' data-id='$d_id' data-odata='$odata_date' class='save-data v_date' value='$d_result'>";
*/
  }

  else if($data_type == "number"){
    $sHtml .= "<input type='text' id='$d_id' data-id='$d_id' data-odata='$d_result'  data-require='$is_require' class='save-data v_int' value='$d_result'>";
  }

  else if($data_type == "checkbox"){
    $check = "";
    if($d_result == "1"){
      $check = "checked";
      $js_data_init .= "$('#$last_qlabel_id').data('val', '1');";
    }
    $sHtml .= "<div class='form-check'><label class='form-check-label' for='$d_id'>
    <input type='checkbox' id='$d_id' name='$d_id' data-id='$d_id' data-odata='$d_result' data-chkmaster='$last_qlabel_id' data-require='$is_require'  class='form-check-input save-data v_checkbox' value='1' $check> $data_name
    </label></div>
    ";
      if(isset($d_put_after_item[$d_id]["1"])){
        $arr_put_after = explode("<",$d_put_after_item[$d_id]["1"]) ;
        $put_after_data_id = $arr_put_after[0];
        $put_after_data_type = $arr_put_after[1];
        $put_after_data_prefix = $arr_put_after[2];
        $put_after_data_suffix = $arr_put_after[3];
        $put_after_is_require = $arr_put_after[4];

        $put_after_data_result = "";
        if(isset($d_data_result[$put_after_data_id])){
          $put_after_data_result = $d_data_result[$put_after_data_id];
        }

        $txt_put_after_item = getPutAfterComp(
          $put_after_data_id, $put_after_data_type,
          $put_after_data_prefix, $put_after_data_suffix,
          $put_after_data_result, $put_after_is_require
        );

        $sHtml .= "$txt_put_after_item";
      }


  }
  else if($data_type == "radio"){

    $sHtml .= "<div id='$d_id' class='save-data v_radio_master' data-id='$d_id' data-require='$is_require'  data-odata='$d_result' data-val='$d_result'>  ";
    if(isset($d_sub_item[$d_id] )){

      foreach ($d_sub_item[$d_id] as $d_sub_name => $d_sub_val) {
            $check = "";
            if($d_result == $d_sub_val) $check = "checked";

            $sHtml .= "<div>";
            $sHtml .= "<label class='form-check-label' for='$d_id-$d_sub_val'>";
            //$sHtml .= "<input type='radio' class='save-data-radio v_radio' id='$d_id-$d_sub_val' name='$d_id' value='$d_sub_val' data-id='$d_id' $check> $d_sub_name - $check /$d_id/$data_result=$d_sub_val";
            $sHtml .= "<input type='radio' class='save-data-radio v_radio' id='$d_id-$d_sub_val' name='$d_id' value='$d_sub_val' data-id='$d_id' $check> $d_sub_name <span class='d_id_txt'>[$d_sub_val]</span>";
            $sHtml .= "</label>";
            $sHtml .= "</div>";

            if(isset($d_put_after_item[$d_id][$d_sub_val])){

              $arr_put_after = explode("<",$d_put_after_item[$d_id][$d_sub_val]) ;
              $put_after_data_id = $arr_put_after[0];
              $put_after_data_type = $arr_put_after[1];
              $put_after_data_prefix = $arr_put_after[2];
              $put_after_data_suffix = $arr_put_after[3];
              $put_after_is_require = $arr_put_after[4];

              $put_after_data_result = "";
              if(isset($d_data_result[$put_after_data_id])){
                $put_after_data_result = $d_data_result[$put_after_data_id];
              }

              $txt_put_after_item = getPutAfterComp(
                $put_after_data_id, $put_after_data_type,
                $put_after_data_prefix, $put_after_data_suffix,
                $put_after_data_result, $put_after_is_require
              );

              $sHtml .= "$txt_put_after_item";

            }
      }//foreach
    }
    $sHtml .= "</div>";
  }//radio
  else if($data_type == "dropdown"){
    if(isset($d_sub_item[$d_id] )){
      $txt_put_after_item = " ";
      $requireif_data_id = "";
      $sHtml .= "<select id='$d_id' data-id='$d_id' data-odata='$d_result'  data-require='$is_require' class='save-data v_dropdown'>";
      $sHtml .= "<option value='' disabled selected> -Select- </option>";
      foreach ($d_sub_item[$d_id] as $d_sub_name => $d_sub_val) {
            //$selected = ($d_sub_val == $d_result)?"selected":"";
            $sHtml .= "<option value='$d_sub_val'> $d_sub_name</option>";

            if(isset($d_put_after_item[$d_id][$d_sub_val])){
              $arr_put_after = explode("<",$d_put_after_item[$d_id][$d_sub_val]) ;
              $put_after_data_id = $arr_put_after[0];
              $put_after_data_type = $arr_put_after[1];
              $put_after_data_prefix = $arr_put_after[2];
              $put_after_data_suffix = $arr_put_after[3];
              $put_after_is_require = $arr_put_after[4];

              $put_after_data_result = "";
              if(isset($d_data_result[$put_after_data_id])){
                $put_after_data_result = $d_data_result[$put_after_data_id];
              }

              $txt_put_after_item = getPutAfterComp(
                $put_after_data_id, $put_after_data_type,
                $put_after_data_prefix, $put_after_data_suffix,
                $put_after_data_result, $put_after_is_require
              );


            }

      }//foreach

      $sHtml .= "</select>";
      $sHtml .= $txt_put_after_item;
      $js_data_init .= "$($d_id).val('$d_result');";

    }
  }// dropdown

  $sHtml .= " $data_suffix";



}//else

  $sHtml .= "</div>";


}//foreach



function getPutAfterComp($data_id, $data_type, $data_prefix, $data_suffix, $data_result, $is_require){
  $txtHTML = "<div id='div_$data_id'>";
  if($data_prefix != "") $txtHTML .= " $data_prefix ";

    if($data_type == "text"){
      $txtHTML .= "<input type='text' id='".$data_id."' data-id='$data_id' data-odata='$data_result'  data-require='$is_require' class='save-data v_text' value='$data_result'> ";
    }
    else if($data_type == "textarea"){
      $txtHTML .= "<textarea id='".$data_id."' rows='4' data-odata='$data_result'  data-require='$is_require' class='save-data v_text' value='$data_result'></textarea>";
    }
    if($data_type == "number"){
      $txtHTML .= "<input type='text' id='".$data_id."' data-id='$data_id' data-odata='$data_result'  data-require='$is_require' class='save-data v_int' value='$data_result'> ";
    }
    else if($data_type == "date"){
      $txtHTML .= setDateData($data_id, $data_result, $is_require);
    }

  if($data_suffix != "") $txtHTML .= " $data_suffix ";

  $txtHTML .= "</div>";
  return $txtHTML;
}

function setDateData($data_id, $data_result, $is_require){
  $odata_date = $data_result;
  if($data_result != "" && $data_result != "0000-00-00"){
     $dateVal = explode("-", $data_result);
     $dateVal[0] = $dateVal[0] + 543; // uncomment on 29/11/2019
     $data_result = $dateVal[2]."/".$dateVal[1]."/".$dateVal[0];
  }
  return "<input type='text' id='".$data_id."' data-id='$data_id' data-require='$is_require' data-odata='$odata_date' class='save-data v_date' value='$data_result'> ";
}


echo "<span style='background-color:#0066cc;color:white'>UID:$uid [$collect_date]</span> <span style='background-color:yellow;color:black;'>$form_id | $lang | $s_id | $show_data_id</span>";

?>



<!doctype html>
<html>
<head>
  <meta http-equiv=Content-Type content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>weClinic Form View</title>

<link rel="stylesheet" href="<? echo $path_weclinic; ?>asset/jquery-ui.css">
<script src="<? echo $path_weclinic; ?>asset/jquery.min.js"></script>
<script src="<? echo $path_weclinic; ?>asset/jquery-ui-custom.js"></script>

<link rel="stylesheet" href="<? echo $path_weclinic; ?>asset/bootstrap4.1.3/css/bootstrap.min.css">

<script src="<? echo $path_weclinic; ?>asset/bootstrap4.1.3/js/bootstrap.min.js"></script>

<script src="<? echo $path_weclinic; ?>asset/notify.min.js"></script>
<script src="<? echo $path_weclinic; ?>asset/jquery.maskedinput.js"></script>
<!--
<link rel="stylesheet" href="<? echo $path_weclinic; ?>asset/fontawesome/css/all.css">
-->

</head>

<style>

.d_id_txt {
    color:blue;
}

.form-check-label:hover {
  background-color: #FFFF73;
  cursor: pointer;
}
.form-check-label:active {
  background-color: #C9FF26;
}

</style>



<div id="div_form_view_data" style="min-height:500px; padding-top:20px; padding-left:30px;" data-uid='<? echo($uid); ?>' data-coldate='<? echo($collect_date); ?>' data-coltime='<? echo($collect_time); ?>' data-formid='<? echo($form_id); ?>'>

<?
echo $sHtml;
?>
  <div class="my-4">
   <button id="btn_save_form_view" class="btn btn-primary" type="button" onclick="saveFormData();"><i class="fa fa-disk" ></i> บันทึกข้อมูล / SAVE DATA</button>
   <i class="fas fa-spinner fa-spin spinner" style="display:none;"></i>
  </div>
</div> <!-- div_form_view_data -->




</html>

<script>
/*
console.log("requireif: <? echo $dtxt_requireif_item; ?>");
console.log("hideif: <? echo $dtxt_hideif_item; ?>");
*/
var dtxt_requireif_list = [<? echo $dtxt_requireif_item; ?>]; // requireif dataitem
var dtxt_hideif_list = [<? echo $dtxt_hideif_item; ?>]; // hideif dataitem
//var dtxt_requireif_item =[{demo_info_C0001:'religion-2,sexatbirth-M'},{demo_info_C0007:'religion-2'},{demo_info_C0013:'religion-2'},{sexatbirth:'religion-2'}];

$(document).ready(function(){
<?
  if($show_data_id == "Y"){
    echo "$('.d_id_txt').show();";
  }
  else{
    echo "$('.d_id_txt').hide(); ";
  }

  echo $js_data_init;
?>
//console.log("data requireif: <? echo $dtxt_requireif_item; ?>");
//console.log("data hideif: <? echo $dtxt_hideif_item; ?>");
//  $("#religion-2").prop("checked", true);
//  $("#sexatbirth-M").prop("checked", true);

$.datepicker.setDefaults( $.datepicker.regional[ "th" ] );
var currentDate = new Date();
//currentDate.setYear(currentDate.getFullYear() + 543);
currentDate.setYear(currentDate.getFullYear());

$('.v_date').datepicker({
  changeMonth: true,
  changeYear: true,
  dateFormat: 'dd/mm/yy'
});

$(".v_date").focus(function(){ // set to current date when focus to date field
  if($(this).val() == ''){
    $(this).datepicker('setDate',currentDate);
  }
});

  $(".v_date").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});

  $(".v_date").change(function(){ // validate date field
    if($(this).val().trim() != ''){
      if(!validateDate($(this).val())){
        $(this).addClass("input_invalid");
        $(this).css("background-color","#FFBFBF");
        $(this).notify("วันที่ไม่ถูกต้อง","warn");
      }
      else{
        $(this).removeClass("input_invalid");
        $(this).css("background-color","#FFF");
      }
    }
  });


$(".v_int").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
    $(this).val($(this).val().replace(/[^0-9\.]/g,''));
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57) && (event.which != 8)) {
      event.preventDefault();
    }
});

$(".v_radio").change(function(){
    $("#"+$(this).data("id")).data("val", $(this).val());
    requireif_change();
    hideif_change();
});

$(".v_dropdown").change(function(){
    $("#"+$(this).data("id")).data("val", $(this).val());
    requireif_change();
    hideif_change();
});

$(".v_checkbox").change(function(){

    // ใช้เพื่อตรวจสอบว่า checkbox กลุ่มเดียวกัน มีการ check บ้างหรือไม่
    var chkmaster = $(this).data("chkmaster");
    var val_chk = "";
    //console.log("chkmaster: "+chkmaster);
    $('.v_checkbox[data-chkmaster="'+chkmaster+'"]').each(function(ix,objx){
      //console.log("chk id: "+$(objx).data("id"));
      if($(objx).is(':checked')){
        val_chk += "1";
      }
    });
    $("#"+chkmaster).data("val", val_chk);
  //  console.log("chkmaster:"+chkmaster+"/"+$("#"+chkmaster).data("val"));
    requireif_change();
    hideif_change();
});


initFormView();

});

function initFormView(){
  requireif_change();
  hideif_change();
  //console.log("initFormView ");
}

function requireif_change(){ // check to show components if the related choice is trigger
  data_parent_id="";
  data_parent_type="";

dtxt_requireif_list.forEach(function(arr_data) {
  Object.keys(arr_data).forEach(function(key) { // extrace key/value (data_id/parent_data_id-value to trigger shown)
  //  console.log('Key : ' + key + ', Value : ' + arr_data[key]);
      var chk_requireif = true;
      arrRequireIf = arr_data[key].split(",");

        for(var data_parent_chk of arrRequireIf) {
          data_parent = data_parent_chk.split("@");
          data_parent_type = data_parent[0];
          data_parent_id = data_parent[1];


         console.log(key+' 00 '+data_parent_id+' type: '+data_parent_type);

          if(data_parent_type == "radio" || data_parent_type=="checkbox"){
            if($("#"+data_parent_id).is(':checked')){

            }
            else{
            //   console.log(key+' 01 '+data_parent_id);
               chk_requireif = false;
               break;
            }
          }
          else if (data_parent_type == "dropdown"){
            var dropdown_data = data_parent_id.split(">");
            if($("#"+dropdown_data[0]).val() == dropdown_data[1]){

            }
            else{
              // console.log(key+' 01 '+data_parent_id);
               chk_requireif = false;
               break;
            }
          }// dropdown

        }

        //console.log(key+' 02 '+data_parent_id+" / "+chk_requireif);
        if(chk_requireif){ // show data component
          $("#div_"+key).show();
          $("#"+key).show();
        }
        else { // hide data component and clear it's value
          $("#div_"+key).hide();
          $("#"+key).hide();

            if($("#"+key).hasClass("save-data")){
//if(data_parent_id != 'serv_coun_hiv'){
              if($("#"+key).hasClass("v_radio_master")){
                $(".v_radio[data-id='"+key+"']").prop('checked', false);
                $("#"+key).data("val", "");
              }
              else{
                $(".v_checkbox[data-id='"+key+"']").prop('checked', false);
                $(".v_dropdown[data-id='"+key+"']").val('');
                $(".v_text[data-id='"+key+"']").val('');
                $(".v_date[data-id='"+key+"']").val('');
                $(".v_int[data-id='"+key+"']").val('');
              }
//}

            }



  }// else hide component

  });
});

}// requireif_change



function hideif_change(){ // check to show components if the related choice is trigger
  data_parent_id="";
  data_parent_type="";

dtxt_hideif_list.forEach(function(arr_data) {
  Object.keys(arr_data).forEach(function(key) { // extrace key/value (data_id/parent_data_id-value to trigger shown)
  //  console.log('Key : ' + key + ', Value : ' + arr_data[key]);
      var chk_hideif = true;
      arrHideif = arr_data[key].split(",");

        for(var data_parent_chk of arrHideif) {
          data_parent = data_parent_chk.split("@");
          data_parent_type = data_parent[0];
          data_parent_id = data_parent[1];
        //  console.log(key+' 00 '+data_parent_id);
          if(data_parent_type == "radio" || data_parent_type=="checkbox"){
            if($("#"+data_parent_id).is(':checked')){

            }
            else{
              // console.log(key+' 01 '+data_parent_id);
               chk_hideif = false;
               break;
            }
          }
          else if (data_parent_type == "dropdown"){
            var dropdown_data = data_parent_id.split(">");
            if($("#"+dropdown_data[0]).val() == dropdown_data[1]){

            }
            else{
              // console.log(key+' 01 '+data_parent_id);
               chk_hideif = false;
               break;
            }
          }// dropdown

        }

        //console.log(key+' 02 '+data_parent_id+" / "+chk_hideif);
        if(chk_hideif){ // show data component
          $("#div_"+key).show();
          $("#"+key).show();
        }
        else { // hide data component and clear it's value
          $("#div_"+key).hide();
          $("#"+key).hide();

   }// else hide component

  });
});

}// hideif_change



function saveFormData(){
  //alert("asave");
  var divSaveData = "div_form_view_data";

  var lst_data_obj = [];
  //var is_data_change = false;
  $("#"+divSaveData +" .save-data-radio:checked").each(function(ix,objx){
    $("#"+$(objx).data("id")).data("val",  $(objx).val());
  // console.log("data_id_radio: "+$(objx).data("id")+"/"+$(objx).val()+":"+odata);
  });

  var flag_data_require_check = true;

  $("#"+divSaveData +" .save-data").each(function(ix,objx){
    var objVal ="";
    if($(objx).hasClass("v_radio_master")){
      objVal = $(objx).data("val"); // data from radio btn
    }
    else{
      objVal = getWObjValue($(objx));
    }

   // check data requirement

   //console.log("datavalue "+$(objx).data("id")+"/"+ objVal+"/"+$(objx).data("require"));
   if(objVal == ""){
     if($(objx).data("require") == "1" && $(objx).is(':visible')){
    //   console.log("enter01 "+$(objx).data("id")+"/"+$(objx).hasClass("v_checkbox"));
       if($(objx).hasClass("v_checkbox")){
         var chkbox_master = $(objx).data("chkmaster");
         if($("#"+chkbox_master).data("val") == ""){
           flag_data_require_check = false;
         }
       }
       else{ // not checkbox
          flag_data_require_check = false;
       }

       if(!flag_data_require_check){ // scroll to invalid data
         $("body,html").animate(
          {
            scrollTop: $(objx).offset().top-30
          },1000 //speed
          );

          if($(objx).hasClass("v_checkbox") || $(objx).hasClass("v_radio")){
            $(objx).notify("กรุณาเลือกตอบคำถามนี้ / Please select choice in this question", "error");
          }
          else{
            $(objx).notify("กรุณากรอกข้อมูล / Please fill data", "error");
          }

          return false;
       }
     }
   }



   if(objVal != $(objx).data("odata")){

  //   console.log("datachange: "+$(objx).data("id")+"- "+objVal+"/"+$(objx).data("odata"));

     var data_item = {};
     data_item[$(objx).data("id")] = objVal;
     lst_data_obj.push(data_item);
   }
  // console.log("data_id: "+$(objx).data("id")+"- "+objVal+"/"+objVal);
  });

  if(!flag_data_require_check) return;

  if(lst_data_obj.length > 0){
    //alert("enterhere1");
    var aData = {
        u_mode:"form_data_update",
        uid:"<? echo $uid; ?>",
        collect_date:"<? echo $collect_date; ?>",
        collect_time:"<? echo $collect_time; ?>",
        form_id:"<? echo $form_id; ?>",
        data_obj:lst_data_obj,
        s_id:"<? echo $s_id; ?>",
        };
    save_data_ajax(aData,"../data_mgt/db_form_update.php",saveFormDataComplete);
    $("#btn_save_form_view").next(".spinner").show();
    $("#btn_save_form_view").hide();
  }
  else{
  //  alert("enterhere2");
    $.notify("No data change", "warn");
  }

} // saveFormData

function saveFormDataComplete(flagSave, rtnDataAjax, aData){

  $("#btn_save_form_view").next(".spinner").hide();
  $("#btn_save_form_view").show();

  if(flagSave){
     //alert("complete save");
     $.notify("บันทึกเรียบร้อยแล้ว | Data saved.", "success");


               var divSaveData = "div_form_view_data";
               //Jeng's Code
               var sFormList = '<? echo($qsNextForm); ?>';
               var sNextForm = '<? echo($sNFormId); ?>';
               var sUid = $("#div_form_view_data").attr("data-uid");
               var sColDate = $("#div_form_view_data").attr("data-coldate");
               var sColTime = $("#div_form_view_data").attr("data-coltime");

               if(sNextForm!=""){
                let sUrl="mnu_form_view.php?form_id="+sNextForm+"&uid="+sUid+"&collect_date="+sColDate+"&collect_time="+sColTime+"&next_form_id="+sFormList;
                //$("#div_form_view_data").parent().load(sUrl);
                window.location.href=sUrl;
                 return;
               }
               //JENG


     // update all odata of  value changed data_id
     Object.keys(aData.data_obj).forEach(function(i){
        //  console.log(i + ' - ' + aData.data_obj[i]);
          Object.keys(aData.data_obj[i]).forEach(function(data_id){
              $("#"+data_id).data("odata",aData.data_obj[i][data_id]);

          });

     });

     let isComplete = 1
     let formid = $('#div_form_view_data').attr('data-formid');
     window.parent.callback_func(formid, isComplete);

  }



}
</script>
<?

include_once("../in_savedata_form.php");
include_once("../function_js/js_fn_validate.php");
?>
