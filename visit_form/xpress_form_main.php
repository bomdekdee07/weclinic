<?

include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function
include_once("../function/in_fn_link.php");


$flag_save_form = "N";
$dateToday = getToday();
if($dateToday != $visit_date){
  $flag_save_form = "Y";
}

$initJS_txt = "";
$initFormCheck = "var cData=[];";
$initFormShowHideComp = "var shData=[];"; // init show/hide component;

function createDataObj($domain, $dataType, $dataName, $dataDesc,
$value, $valueUnit, $dataChild, $dataHide,
$isShow, // show/hide component on start
$logForm, // log form
$qParent, // question row parent : to check answer in this question is answered
$uidDataValue
)
{
  global $initJS_txt;
  global $uidData;
  global $open_link;

  //echo "<br> $dataName - $dataChild- $isShow";
//echo "<br> $dataName - $value- $isShow";
  if($uidDataValue != ""){ // check enable/disable data child
    if($dataChild != "" && $uidDataValue == $value){
    //  echo "<br> $dataName - $dataChild";
      $initJS_txt .="$('#$dataChild').prop('disabled', false); ";
    }

  }

  // data hide/show
  $startHide = ($isShow == '0')?"class='start_hide'":"";

/*
  if($dataHide != ""){

    $arr= (explode(":",$dataHide));
    if($arr[0] == "h"){
      $dataHide = "data-hide='".$arr[1]."'";
    }
    else if($arr[0] == "s"){
      $dataHide = "data-show='".$arr[1]."'";
      if($uidDataValue == $value){// if data is choosen  show other dependant
          $arrShow= (explode(",",$arr[1]));
          foreach($arrShow as $itmShow){
          //  echo "<br>show $itmShow";
            $initJS_txt.= " $('#q_$itmShow').show(); ";
            $initJS_txt.= " $('#q_$itmShow').data('is_show','1'); ";
          }
      }
    }
  }
*/

  $dataObj = "";
  if($dataType == "title"){
  //  $dataObj .= "<b>$dataDesc</b> <br>[$dataName] <span id='$dataName-c' class='result-c'></span> ";
    $dataObj .= "<b>$dataDesc</b> ";
    if($open_link != "Y") $dataObj .= "<br>[$dataName] ";

    $dataObj .= "<span id='$dataName-c' class='result-c'></span>";
  }
  else if($dataType == "text"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent'  class='save-data v_text' value='$value'> $valueUnit </div>";
  }
  else if($dataType == "check"){
    $check = "";
    if($uidDataValue == $value) $check = "checked";
    $dataObj = "<div id='div-$dataName' $startHide><label class='form-check-label' for='$dataName'> <input type='checkbox' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-child='$dataChild' data-q_parent='$qParent' $dataHide class='save-data v_checkbox' value='$value' $check>
    $dataDesc</label></div>";
  }
  else if($dataType == "radio"){
    $check = "";
    if($uidDataValue == $value) $check = "checked";

    $btn_log = "";
    if($logForm != ""){
      $log_form_name = "";
      if($logForm == "ae")  $log_form_name = "ADVERSE EVENT";
      else if($logForm == "con_med")  $log_form_name = "CON MED";
      $btn_log = '<button id="div-btnlog_'."$dataName-$value".'" class="btn btn-sm btn-primary btn-log" data-id="'.$logForm.'" type="button" onclick="openFormLog(\''.$logForm.'\');">
        <i class="fa fa-file-medical-alt " ></i> '.$log_form_name.'
      </button>';
    }

    $dataObj = "<div id='div-$dataName-$value' $startHide><label class='form-check-label' for='$dataName-$value' >
    <input type='radio' id='$dataName-$value' name='$dataName' data-dom='$domain' data-id='$dataName' data-child='$dataChild' data-logform='$logForm' data-q_parent='$qParent'  $dataHide  class='save-data-radio v_radio' value='$value' $check>
    $dataDesc </label>
    $btn_log
    </div>";
  }
  else if($dataType == "date"){
    if($uidDataValue != "" && $uidDataValue != "0000-00-00"){
      //echo "date : $uidDataValue";
       $dateVal = explode("-", $uidDataValue);
      // $dateVal[0] = $dateVal[0] + 543;
       $value = $dateVal[2]."/".$dateVal[1]."/".$dateVal[0];
    }

    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent' class='save-data v_date' value='$value'> $valueUnit</div>";
    $initJS_txt .= "
    $('#$dataName').datepicker({
      changeMonth: true,
      changeYear: true,

      dateFormat: 'dd/mm/yy'
    });
    //$('#$dataName').datepicker('setDate',currentDate );

    ";
  }

  else if($dataType == "partial_date"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent'  class='save-data v_partial_date' value='$value'> $valueUnit </div>";
    $initJS_txt .= "
    $('#$dataName').datepicker({
      changeMonth: true,
      changeYear: true,

      dateFormat: 'dd/mm/yy'
    });";

  }
  else if($dataType == "int"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent' class='save-data v_int' value='$value'> $valueUnit</div>";
  }
  else if($dataType == "double"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent' class='save-data v_double' value='$value'> $valueUnit</div>";
  }
  else if($dataType == "title_row"){
    $dataObj .= "<b>$dataDesc</b> <span id='$dataName-c' class='result-c'></span>";
  }
  else if($dataType == "topic"){
    $dataObj .= "<br><h4><b>$dataDesc</b></h4> <span id='$dataName-c' class='result-c'></span>";
  }



 return $dataObj;
}//createDataObj



function createDataChild($domain, $dataType, $dataName, $dataDesc , $value, $valueUnit, $dataParent, $isShow, $uidDataValue){
  global $initJS_txt;

//echo "<br>dataType:$dataName/ $dataType";
  // data hide/show
  $startHide = ($isShow== '0')?"class='start_hide'":"";

  $dataObj = "";
  if($dataType == "title"){
    $dataObj .= "<b>$dataDesc</b> ";
  }
  else if($dataType == "text"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-parent='$dataParent' class='save-data v_text $dataParent datachild' value='$value'> $valueUnit</div>";
  }

  else if($dataType == "check"){
    $check = "";
    if($uidDataValue == $value) $check = "checked";

    $dataObj = "<div id='div-$dataName' $startHide><label class='form-check-label' for='$dataName'> <input type='checkbox' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' class='save-data v_checkbox datachild' value='$value'>
     $dataDesc</label></div>";
  }
  else if($dataType == "radio"){
    $check = "";
    if($uidDataValue == $value) $check = "checked";

    $dataObj = "<div id='div-$dataName-$value' $startHide><label class='form-check-label' for='$dataName-$value' >
    <input type='radio' id='$dataName-$value' name='$dataName' data-dom='$domain' data-id='$dataName' data-parent='$dataParent' class='save-data-radio v_radio $dataParent datachild' value='$value'>
    $dataDesc</label></div>";
  }
  else if($dataType == "date"){
    if($uidDataValue != "" && $uidDataValue != "0000-00-00"){
      //echo "date : $uidDataValue";
       $dateVal = explode("-", $uidDataValue);
      // $dateVal[0] = $dateVal[0] + 543;
       $value = $dateVal[2]."/".$dateVal[1]."/".$dateVal[0];
    }

    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' class='save-data v_date $dataParent datachild' value='$value'> $valueUnit</div>";
    $initJS_txt .= "
    $('#$dataName').datepicker({
      changeMonth: true,
      changeYear: true,

      dateFormat: 'dd/mm/yy'
    });
    //$('#$dataName').datepicker('setDate',currentDate );

    ";

  }

  else if($dataType == "partial_date"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-parent='$dataParent' class='save-data v_partial_date $dataParent datachild' value='$value'> $valueUnit</div>";
    $initJS_txt .= "
    $('#$dataName').datepicker({
      changeMonth: true,
      changeYear: true,

      dateFormat: 'dd/mm/yy'
    });";

  }

  else if($dataType == "int"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' class='save-data v_int $dataParent datachild' value='$value'> $valueUnit</div>";
  }
  else if($dataType == "double"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' class='save-data v_double $dataParent datachild' value='$value'> $valueUnit</div>";
  }


 return $dataObj;
}//createDataObj



$arr_domain_id = array();

  $query = "SELECT distinct domain_id FROM p_form_data WHERE form_id=? AND domain_id<>'' ";
//echo "$form_id/$query";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("s", $form_id);
  if($stmt->execute()){
    $stmt->bind_result($domain_id);
    while ($stmt->fetch()) {
      $arr_domain_id[] = $domain_id;
    //  echo "<br>$form_id/$domain_id";
    }// if
  }
  else{
    $msg_error .= $stmt->error;
  }
  $stmt->close();

$arr_uid_data = array();

$query_add2 = "";
foreach($arr_domain_id as $domain_id){
//  echo "<br>$domain_id";
  $query_add = "";
  if($domain_id == "xpress_service"){
/*
    $query_add .= " AND version='$version2' ";

    if($open_link == "Y"){
      $query_add2.= " AND p1.data_seq < 1000 ";
    }
*/
    $query_add .= " AND version='$version2' ";
    $query_add2.= " AND p1.data_seq < 1000 ";

  }

  $query = "SELECT * FROM x_$domain_id
  WHERE uid=?
  AND collect_date=? $query_add";
  //echo "<br>query : $query";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $uid, $visit_date);

    if ( false===$stmt ) {
       die('prepare() failed: ' . htmlspecialchars($mysqli->error));
    }

     if ($stmt->execute()){

         $result = $stmt->get_result();
         if($result->num_rows > 0) {
           if($arr_uid_data[] = $result->fetch_assoc()) {

           }//while
         }
         $stmt->close();

     }//if

}// foreach

$uidData = array();
foreach($arr_uid_data as $uid_data_itm){
  $uidData = array_merge($uidData,$uid_data_itm);
  //$uidData = $uid_data_itm;
}


/*
   foreach($uidData as $item=> $item_value){
     echo "<br> key : $item = $item_value";
   }
*/

$arr_data_child = array();
$query = "SELECT p1.*, p2.data_name as data_parent_name
          FROM p_form_data as p1 LEFT JOIN p_form_data as p2 ON (p1.data_name=p2.data_child)
          WHERE p1.form_id =? AND p1.data_name IN (
          SELECT data_child
          FROM p_form_data
          WHERE form_id =? AND data_child <> ''
          ) $query_add2
          ORDER BY data_seq";
//echo "$query <br> $domain_id / $form_id";
  //if(isset($stmt))$stmt->close();
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ss", $form_id, $form_id);

  if ( false===$stmt ) {
     die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }

   if ($stmt->execute()){

       $result = $stmt->get_result();

       if($result->num_rows > 0) {
         while($row = $result->fetch_assoc()) {
        //   echo "<br> colName: ".$row['data_name']." - ".$row['data_desc'];

           // data value from uid record
           $uidDataValue = isset($uidData[$row['data_name']])?$uidData[$row['data_name']]:"";


           $arr_data_child[$row['data_name']] =
           createDataChild($row['domain_id'],
                         $row['data_type'],
                         $row['data_name'],
                         $row['data_desc'],
                         $row['data_value'],
                         $row['data_unit'],
                         $row['data_parent_name'],
                         $row['is_show'], // show/hide component on start 1:show, 0:not show
                         $uidDataValue
                        );
/*
                        if($uidData[$row['data_name']] != "")
                        echo "<br>".$row['data_name']."  -- value ".$uidData[$row['data_name']];
*/
         }//while
       }
       $stmt->close();

   }//if


   //$txt_row = "<table><tr><td colspan=2><h2>$form_name</h2></td>";
   $txt_row = "<table width='100%'>";

   $txt_row .= "<tr><td colspan='2'><h4><b>ส่วนที่ 1 ข้อมูลเบื้องต้น</b></h4> <span id='section1-c' class='result-c'></span></td></tr>";
   $txt_row .= "<tr><td><b>1. UIC </b></td><td><b>$uic </b></td></tr>";
   $txt_row .= "<tr><td><b>2. วันที่มารับบริการ </b></td><td><b>".changeToThaiDate($visit_date)."</b></td></tr>";

   $query = "SELECT *
             FROM p_form_data as p1
             WHERE form_id =? AND data_name NOT IN (
             SELECT data_child
             FROM p_form_data
             WHERE form_id =? AND data_child <> ''
             ) $query_add2
             ORDER BY data_seq";

   //echo "$query <br> $domain_id";
     //if(isset($stmt))$stmt->close();
     $stmt = $mysqli->prepare($query);
     $stmt->bind_param("ss", $form_id,$form_id);

     if ( false===$stmt ) {
        die('prepare() failed: ' . htmlspecialchars($mysqli->error));
     }

      if ($stmt->execute()){

          $result = $stmt->get_result();

          $q_parent = "";
          if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
           //   echo "<br> colName: ".$row['data_name']." - ".$row['data_desc'];
             // $arr_data[$row['data_name']]
             if($row['data_type'] == 'title'){ // new question
                $q_parent = "q_".$row['data_name'];
              }

              // data value from uid record
              $uidDataValue = isset($uidData[$row['data_name']])?$uidData[$row['data_name']]:"";

              $dabaObj = createDataObj($row['domain_id'],
                                       $row['data_type'],
                                       $row['data_name'],
                                       $row['data_desc'],
                                       $row['data_value'],
                                       $row['data_unit'],
                                       $row['data_child'], // related data of this obj
                                       $row['data_hide'], // opt click will hide row data_hide
                                       $row['is_show'], // show/hide component on start 1:show, 0:not show

                                       $row['log_form'], // log form (eg. AE, con med)
                                       $q_parent, // question row parent
                                       $uidDataValue // data from uid record
                                     );

             if($row['data_type'] == 'title'){ // new question

                $start_hide="";
                if($row['is_show'] == 0) $start_hide="start_hide";

                $txt_row .= "</td></tr><tr id='q_".$row['data_name']."' data-is_show='".$row['is_show']."' class='c_data $start_hide'><td width='50%'>$dabaObj</td><td>";

                $initFormCheck .= " cData['q_".$row['data_name']."']=''; "; // to check each question row

             }
             else if($row['data_type'] == 'title_row'){ // new question parent row
                $txt_row .= "</td></tr><tr id='t_".$row['data_name']."'><td colspan=2>$dabaObj";
             }
             else if($row['data_type'] == 'topic'){ // new topic
                $txt_row .= "</td></tr><tr id='t_".$row['data_name']."'><td colspan=2>$dabaObj";
             }
             else {
                $txt_row .= " $dabaObj ";
                if(isset($arr_data_child[$row['data_child']] )){
                  $txt_row .=  $arr_data_child[$row['data_child']] ;
                }
                $txt_row .= "";
             }

          //   $txt_row .=  "$dabaObj ";

            }//while
            $txt_row .=  "</td></tr></table>";
          }
          $stmt->close();

      }//if


// open link for patient fill in some form eg Demographic, satisfaction
if($open_link == "Y"){
  include_once("inc_form_head.php");
}



?>



<style>

table tr:nth-child(odd) td{ background-color:#E0F5FE; }
table tr:nth-child(even) td{ background-color:#C8F1FF; }

th, td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
tr:hover td {
/*background-color: #f5f5f5;*/
background-color:#96DDFC !important;
/* opacity: 0.9; */
}


label {
  display: inline-block;
}

@media screen and (max-width: 768px) {
  label {
    display: block;
  }
}

.form-check-label:hover {
  background-color: #FFFF73;
  cursor: pointer;
}
.form-check-label:active {
  background-color: #C9FF26;
}

.q_invalid {
  /*background-color: #FFBFBF; !important;*/
  color: #B20000;
}

.input_invalid {
  background-color : #FFBFBF; !important;
}

</style>

  <div class="container" id="divSaveData">
    <h2><span class="badge badge-primary"><? echo "$uid"; ?></span> <span class="badge badge-warning"><? echo "$visit_date"; ?></span> <span class="badge badge-info"><? echo "$version"; ?></span></h2>
    <div class="panel panel-default">
      <div class="panel-body">

        <div class="row my-4" id="xpress_pass_txt">
          <div class="col-md-12 ">
              <div class="mb-4">
               <span class="badge badge-success px-4 py-2"><h4><i class='fa fa-check'></i> ผ่านเกณฑ์การเข้ารับบริการ Xpress</h4></span>
              </div>
              <div>
                <b>ยินดีด้วย ท่านผ่านเกณฑ์การเข้ารับบริการ Xpress ในครั้งนี้</b>
    โปรดแสดงหน้านี้กับเจ้าหน้าที่ ณ จุดลงทะเบียน ท่านจะได้รับบริการที่สะดวก รวดเร็ว และเป็นส่วนตัว ในรูปแบบการบริการแบบ Xpress ของเรา
              </div>
          </div>
        </div>

        <div class="row my-4" id="xpress_fail_txt">
          <div class="col-md-12 ">
              <div class="mb-4">
               <span class="badge badge-danger px-4 py-2"><h4><i class='fa fa-times'></i> ไม่ผ่านเกณฑ์การเข้ารับบริการ Xpress</h4></span>
              </div>
              <div>
                <b>ท่านไม่ผ่านเกณฑ์การเข้ารับบริการแบบ Xpress ในครั้งนี้</b>
    ในการรับบริการครั้งนี้ท่านจะได้รับบริการตามระบบปกติของทางศูนย์สุขภาพชุมชน ขอขอบคุณที่ท่านสนใจการให้บริการแบบ Xpress ในโอกาสหน้าหากท่านผ่านเกณฑ์การเข้ารับบริการ Xpress ท่านจะได้รับการบริการที่สะดวก รวดเร็ว และเป็นส่วนตัว และขอบคุณที่ท่านไว้วางใจให้เราได้ดูแลสุขภาพของท่าน
              </div>
          </div>
        </div>



        <?

echo "$form_top";
echo "<div>$txt_row</div>" ;
echo "$form_bottom";

        ?>

      </div>
    </div>



<?
if($open_link == "Y"){ // patient done
  echo '
  <div class="row my-4">
    <div class="col-md-12 my-1">
      <button id="btn_save" class="btn btn-success form-control" type="button"> บันทึกข้อมูล</button>
    </div>
  </div>
  ';
}
else{ // staff done
  echo '
  <div class="row my-4">
  <!--
    <div class="col-md-3 my-1">
      <button id="btn_consent_clear" class="btn btn-warning form-control" type="button"> เคลียร์เอกสารยินยอม</button>
    </div>
    -->
    <div class="col-md-12 my-1">
      <button id="btn_save" class="btn btn-success form-control" type="button"> บันทึกข้อมูล</button>
    </div>
  </div>
  ';
}
?>
<!--
    <div class="row my-4">
      <div class="col-md-3 my-1">
        <button id="btn_consent_clear" class="btn btn-warning form-control" type="button"> เคลียร์เอกสารยินยอม</button>
      </div>
      <div class="col-md-9 my-1">
        <button id="btn_save" class="btn btn-success form-control" type="button"> บันทึกข้อมูล</button>
      </div>
    </div>
-->



  </div>



<input type="hidden" id="xpress_result">

<script>

$(document).ready(function(){
  initNewForm();

$(".datachild").change(function(){
    if($(this).data("child") != '' ){
       $("#"+$(this).data("child")).prop( "disabled", false );
    }
    else{
      $("."+$(this).data("id")).val('');
      $("."+$(this).data("id")).prop( "disabled", true );
    }
});

$.datepicker.setDefaults( $.datepicker.regional[ "th" ] );
var currentDate = new Date();
//currentDate.setYear(currentDate.getFullYear() + 543);
currentDate.setYear(currentDate.getFullYear());

//initJS_txt
<? echo $initJS_txt ; ?>

//initFormCheck
<?echo $initFormCheck ; ?>

var shData=[];
// declare option show/hide component
<? echo $option_showhide; ?>
showhideComponent(shData);


$(".v_date").focus(function(){ // set to current date when focus to date field
  if($(this).val() == ''){
    $(this).datepicker('setDate',currentDate);
  }
});
$(".v_partial_date").focus(function(){ // set to current date when focus to date field
  if($(this).val() == ''){
    $(this).datepicker('setDate',currentDate);
  }
});

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
            $(this).val($(this).val().replace(/[^\d].+/, ""));
             if ((event.which < 48 || event.which > 57)  && (event.which != 8) ) {
                 event.preventDefault();
             }
  });

  $(".v_double").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57) && (event.which != 8)) {
                event.preventDefault();
            }
   });

$(".v_radio, .v_checkbox").change(function(){
  //alert("data child "+$(this).data("child"));
    if($(this).data("child") != '' ){ // there is data child in this option
       $("#"+$(this).data("child")).prop( "disabled", false );
       if($("#"+$(this).data("child")).hasClass('v_date')){ // date
         $("#"+$(this).data("child")).datepicker("setDate",currentDate );
         $("#"+$(this).data("child")).focus();
         $("#"+$(this).data("child")).select();
       }
       else{ // not date
         $("#"+$(this).data("child")).focus();
         $("#"+$(this).data("child")).select();
       }
    }
    else{
      $("."+$(this).data("id")).val('');
      $("."+$(this).data("id")).removeClass('input_invalid');
    //  $("."+$(this).data("id")).css("background-color","#FFF");
      $("."+$(this).data("id")).prop( "disabled", true );
    }

    // if there this component is parent of show/hide other component
    if(typeof shData[$(this).attr("id")]  !== 'undefined' ){

      // show/hide each component
       if(typeof shData[$(this).attr("id")]['show']  !== 'undefined' ){
          var comp_show = shData[$(this).attr("id")]['show'].split(',');
          for(i=0; i<comp_show.length; i++){
            $('#div-'+comp_show[i]).show();
          }// for
       }
       if(typeof shData[$(this).attr("id")]['hide']  !== 'undefined' ){
          var comp_hide = shData[$(this).attr("id")]['hide'].split(',');
          for(i=0; i<comp_hide.length; i++){
            $('#div-'+comp_hide[i]).hide();
          }// for
       }

      // show/hide each question
       if(typeof shData[$(this).attr("id")]['show_q']  !== 'undefined' ){
          var comp_show = shData[$(this).attr("id")]['show_q'].split(',');
          for(i=0; i<comp_show.length; i++){
            $('#q_'+comp_show[i]).show();
            $('#q_'+comp_show[i]).data("is_show",'1');
            //alert("show "+comp_show[i]);
          }// for
       }

       if(typeof shData[$(this).attr("id")]['hide_q']  !== 'undefined' ){
          var comp_hide = shData[$(this).attr("id")]['hide_q'].split(',');
          for(i=0; i<comp_hide.length; i++){
            $('#q_'+comp_hide[i]).hide();
          //  $("#q_"+comp_hide[i]).find('input').val('');
            $("#q_"+comp_hide[i]).find('input[type=text]').val('');
            $("#q_"+comp_hide[i]).find('input').prop("checked", false);
            $("#q_"+comp_hide[i]).find('input').removeClass('input_invalid');
        //    $("#q_"+comp_hide[i]).find('input').css("background-color","#FFF");
            $("#q_"+comp_hide[i]).data("is_show",'0');
          }// for
       }

       // show/hide each title_row
        if(typeof shData[$(this).attr("id")]['show_t']  !== 'undefined' ){
           var comp_show = shData[$(this).attr("id")]['show_t'].split(',');
           for(i=0; i<comp_show.length; i++){
             $('#t_'+comp_show[i]).show();
           }// for
        }

        if(typeof shData[$(this).attr("id")]['hide_t']  !== 'undefined' ){
           var comp_hide = shData[$(this).attr("id")]['hide_t'].split(',');
           for(i=0; i<comp_hide.length; i++){
             $('#t_'+comp_hide[i]).hide();
           }// for
        }

    }
//alert("click radio 1 "+$(this).attr("id"));

<?
if($open_link != "Y"){
  echo "xpress_service_assessment();";
}
?>

});



  $("#btn_save").click(function(){
  //   alert("saveja");
     saveFormData(cData);
  }); // btn_save

/*
  $("#btn_consent_clear").click(function(){
     xpress_service_clear_consent();
  }); // btn_consent_clear
*/



});


//initJSForm
<? echo $initJSForm ; ?>





function initNewForm(){
  $("#xpress_pass_txt").hide();
  $("#xpress_fail_txt").hide();

  <?
     if($flag_save_form == "Y"){
       //echo "$('#btn_save').hide();";
     }


  ?>
  $(".btn-log").hide();
  $(".datachild").each(function(ix,objx){
    $(objx).prop( "disabled", true );
  });

  $(".start_hide").each(function(ix,objx){
    //alert("hide : "+$(objx).attr('id'))
    //  $(objx).data("is_show",'0');
    $(objx).hide();
  });
}


function saveFormData(cData){

//alert("save data  : "+moment($( "#work_entry_date").val(), "DD MMM YYYY").format("YYYY-MM-DD"));
//alert("save data  : ");
  var divSaveData = "#divSaveData";

  var lst_data_obj = [];

  $(divSaveData +" .save-data").each(function(ix,objx){

    var objVal = getDataObjValue($(objx));

    var objData = {
      name: $(objx).data("id"),
      dom:$(objx).data("dom"),
      value:objVal
    }
    lst_data_obj.push(objData);

		if(typeof $(objx).data("q_parent") !== 'undefined'){
			if($(objx).hasClass("v_checkbox")){
				   if($(objx).prop("checked") == true)
           cData[$(objx).data("q_parent")] += $(objx).val();
		  	}
		  else{
				cData[$(objx).data("q_parent")] += $(objx).val();
		  	}
		}


  });

//alert("save data1  : ");
//var cData = [];

  $(divSaveData +" .save-data-radio:checked").each(function(ix,objx){
  //  var objVal = getDataObjValue($(objx));
    var objData = {
      name: $(objx).data("id"),
      dom:$(objx).data("dom"),
      value:$(objx).val()
    }
    lst_data_obj.push(objData);

//alert("valuexx : "+$(objx).data("q_parent")+" / "+cData[$(objx).data("q_parent")]+" / "+objVal);
		if(typeof $(objx).data("q_parent") !== 'undefined'){
			//cData[$(objx).data("q_parent")] += $(objx).val();
      cData[$(objx).data("q_parent")] += '1';
		}
  });


//alert("save data2  : ");
var txt="";
var i=0;
  $('.c_data').removeClass("q_invalid");
	for (var key in cData) {
		//	txt += "["+i+" "+key+" value : "+cData[key]+"]";

  //  txt += "["+i+" "+key+" / "+$('#'+key).data('is_show')+"] ";
			if(cData[key] == ''){ // no answer in question

        if($('#'+key).data('is_show') == '1'){
          //alert("key is : "+key);
          $('#'+key).addClass("q_invalid");
          i++;

          if(i==1){//	scroll to first invalid q
          $("body,html").animate(
            {
              scrollTop: $('#'+key).offset().top
            },1000 //speed
            );
          }

        }

        else{ // hide question check for checkbox and radio to assign blank value
          //alert("key is : "+key);
          var data_id=[];
          $('#'+key).find(':input').each(function(ix,objx){
            //alert("key has :"+$(objx).data("id"));
            if($(objx).attr("type") == "radio"){
              if(data_id.indexOf($(objx).data("id")) < 0){

                var objData = {
                  name: $(objx).data("id"),
                  dom:$(objx).data("dom"),
                  value:""
                }
                lst_data_obj.push(objData);
                data_id.push($(objx).data("id"));
                //alert("key in :"+$(objx).data("id"));
              }
            }

          });


        }// else hide question

      //$('#'+key).addClass("q_invalid");

			}

      cData[key] = ''; // clear cData value
	}// for

//alert(txt);
//alert("i "+i);

  // check input invalid
  if(i==0){

    $(divSaveData +" .input_invalid").each(function(ix,objx){
       i++
       //alert("i "+$(objx).data("id"));
       //$(objx).addClass("q_invalid");
       if(i==1){ // scroll to first invalid input
         $("body,html").animate(
 		      {
 						scrollTop: $(objx).offset().top
 		      },1000 //speed
 			    );
       }
    });
  }


//alert("save data3  : "+txt);
  var flag_save = "N";
  var is_form_done = "N";
	if(i == 0){ // all are valid
    flag_save = "Y";
    is_form_done = "Y";
	}
  else{
    flag_save = "N";

    //alert("Invaid "+i);
    $.notify("กรอกข้อมูลไม่ครบ "+i+ " ข้อ", "warn");

    <?
    if($open_link != "Y"){
      echo '
      var result = confirm("ข้อมูลไม่ครบ ท่านต้องการยืนยันที่จะบันทึกใช่หรือไม่ ?");
      if (result) {
          flag_save = "Y";
      }
      ';
    }

    ?>

  }

  if(flag_save == "Y"){
    <? echo "$before_save_function"; ?>


    var versionData = {
      name: "version",
      dom: "xpress_service",
      value:"<? echo $version; ?>"
    }


    <?

    if($open_link == "Y"){
      echo "xpress_service_assessment();";
    }
    ?>
  //  alert("xpress_sum : "+$("#xpress_result").val());

    var resultData = {
      name: "xpress_sum",
      dom: "xpress_service",
      value:$("#xpress_result").val()
    }
    lst_data_obj.push(versionData);
    lst_data_obj.push(resultData);

    var today = getTodayDateEN();
    var aData = {
              u_mode:"save_data",
              uid:'<? echo $uid; ?>',
              form_id:'<? echo $form_id; ?>',
              form_done:is_form_done,
              lst_data:lst_data_obj,
              visit_date:'<? echo $visit_date; ?>',
              project_id:'xpress',
              group_id:'<? echo $version; ?>',
              visit_id:'<? echo $visit_date; ?>',
              open_link:'<? echo $open_link; ?>'
    };


    <?
      $db_form_path= "visit_form/";
      if($open_link == "Y") $db_form_path= "";
    ?>

  //  alert("form path : <?echo "$db_form_path-db_form_data.php" ?>");
    save_data_ajax(aData,"<? echo $db_form_path; ?>db_form_data.php",saveFormDataComplete);
    //save_data_ajax(aData,"visit_form/db_form_data.php",saveFormDataComplete);

  }

}


function saveFormDataComplete(flagSave, rtnDataAjax, aData){
  // alert("flag save99 is : "+flagSave+" open_link: <? echo $open_link;?>");
  if(flagSave){
    <?
      if($open_link == "Y"){
      //  echo "window.location = '../info/inf_txt.php?e=f1&f=$form_name&u=$uic';";
      }
    ?>
    //setDataChangeVisit();
    $.notify("บันทึกข้อมูลแล้ว", "info");
    <? echo $after_save_function; ?>
  }
}




// showhide component at start
function showhideComponent(shData){
  for (itm in shData ) {
    //alert(shData[itm].dtype+"/"+shData[itm].show_q);
    var flag=false;
    if(shData[itm].dtype == 'radio' || shData[itm].dtype == 'check'){
       if($('#'+itm).prop("checked") == true){
         flag=true;
       }
    }
    else if(shData[itm].dtype == 'text'){
       if($('#'+itm).val() != '') flag=true;
    }
    else if(shData[itm].dtype == 'date'){
       if($('#'+itm).val() != '') flag=true;
    }

      if(flag){ // action for this shData component
        if(typeof shData[itm]['hide']  !== 'undefined' ){
          var comp = shData[itm]['hide'].split(',');
          for(i=0; i<comp.length; i++){
            $('#div-'+comp[i]).hide();


          }// for
        }
        if(typeof shData[itm]['show']  !== 'undefined' ){
          var comp = shData[itm]['show'].split(',');
          for(i=0; i<comp.length; i++){
            $('#div-'+comp[i]).show();

          }// for
        }

// question
        if(typeof shData[itm]['hide_q']  !== 'undefined' ){
          var comp = shData[itm]['hide_q'].split(',');
          for(i=0; i<comp.length; i++){
            $('#q_'+comp[i]).hide();
            $('#q_'+comp[i]).data("is_show",'0');
          }// for
        }

        if(typeof shData[itm]['show_q']  !== 'undefined' ){
          var comp = shData[itm]['show_q'].split(',');
          for(i=0; i<comp.length; i++){
            $('#q_'+comp[i]).show();
            $('#q_'+comp[i]).data("is_show",'1');
          }// for
        }

// title_row
        if(typeof shData[itm]['hide_t']  !== 'undefined' ){
          var comp = shData[itm]['hide_t'].split(',');
          for(i=0; i<comp.length; i++){
            $('#t_'+comp[i]).hide();
          }// for
        }

        if(typeof shData[itm]['show_t']  !== 'undefined' ){
          var comp = shData[itm]['show_t'].split(',');
          for(i=0; i<comp.length; i++){
            $('#t_'+comp[i]).show();
          }// for
        }



      }//flag

  }// for
}// showhideComponent






//**additional

function xpress_service_assessment(){
   $(".result-c").removeClass("badge");
   $(".result-c").removeClass("badge-success");
   $(".result-c").removeClass("badge-danger");
   var xpress_pass = "Y";

   var pass_txt = "<i class='fa fa-check'></i> <b> ผ่านเกณฑ์ </b>";
   var not_pass_txt = "<i class='fa fa-times'></i> <b> ไม่ผ่านเกณฑ์ </b>";

   var part1_txt = "";
   var part2_txt = "";
   var part3_txt = "";
   var part4_txt = "";

// part1
  if ($("#xpress_interest-Y").prop("checked")) {
      part1_txt = pass_txt;
      $("#section1-c").addClass("badge badge-success");



        // part2
          if ($("#sexual_condom-Y").prop("checked")) {
              part2_txt = pass_txt;
              $("#section2-c").addClass("badge badge-success");
          }
          else if ($("#sexual_condom-N").prop("checked")) {
              part2_txt = not_pass_txt;
              $("#section2-c").addClass("badge badge-danger");
              xpress_pass = "N";
          }
          $("#section2-c").html(part2_txt+" (ป้องกันด้วยถุงยางอนามัย)");

          // part3
            var acute_count = 0;

            if ($("#hivacute_weak-Y").prop("checked")) acute_count++;
            if ($("#hivacute_sorethoat-Y").prop("checked")) acute_count++;
            if ($("#hivacute_heahache-Y").prop("checked")) acute_count++;
            if ($("#hivacute_fatigue-Y").prop("checked")) acute_count++;
            if ($("#hivacute_diarrhea-Y").prop("checked")) acute_count++;
            if ($("#hivacute_rash-Y").prop("checked")) acute_count++;
            if ($("#hivacute_jointpain-Y").prop("checked")) acute_count++;
            if ($("#hivacute_wound-Y").prop("checked")) acute_count++;
            if ($("#hivacute_nausea-Y").prop("checked")) acute_count++;
            if ($("#hivacute_candidiasis-Y").prop("checked")) acute_count++;
            if ($("#hivacute_stiffneck-Y").prop("checked")) acute_count++;





            if ($("#hivacute_fever-Y").prop("checked")){
              if (acute_count >= 3) { // not pass
                part3_txt = not_pass_txt;
                $("#section3-c").addClass("badge badge-danger");
                xpress_pass = "N";
              }
              else{ // pass
                part3_txt = pass_txt;
                $("#section3-c").addClass("badge badge-success");
              }
            }
            else{ // pass
              part3_txt = pass_txt;
              $("#section3-c").addClass("badge badge-success");
            }

            $("#section3-c").html(part3_txt+" (ข้อ1 ตอบใช่ และ ข้อ2 ตอบใช่อย่างน้อย 3 ข้อ จึงจะไม่ผ่านเกณฑ์)");

            // part4
              var part4_result = "";
              if ($("#prep_take-Y").prop("checked")) {
                part4_result = "Y";
                if ($("#prep_4pills-N").prop("checked")) part4_result = "N";
                if ($("#prep_6pills-N").prop("checked")) part4_result = "N";
                if ($("#prep_ondemand-N").prop("checked")) part4_result = "N";
                if ($("#fu_regular-N").prop("checked")) part4_result = "N";
              }
              else if ($("#prep_take-N").prop("checked")) {
                 part4_result = "N";
              }


              if(part4_result == "Y"){
                part4_txt = pass_txt;
                $("#section4-c").addClass("badge badge-success");
              }
              else{
                part4_txt = not_pass_txt;
                $("#section4-c").addClass("badge badge-danger");
                xpress_pass = "N";
              }
              $("#section4-c").html(part4_txt+" (PrEP)");


  }
  else if ($("#xpress_interest-N").prop("checked")) {
      part1_txt = not_pass_txt;
      $("#section1-c").addClass("badge badge-danger");
      xpress_pass = "N";


      $("#t_section2").hide();
      $("#t_section2_title1").hide();
      $("#t_section3").hide();
      $("#t_section3_title1").hide();
      $("#t_section4").hide();


  }
  $("#section1-c").html(part1_txt+" (มีความสนใจ)");



       // xpress result
       $("#xpress_pass_txt").hide();
       $("#xpress_fail_txt").hide();

       if(xpress_pass == "Y"){ // pass
          $("#xpress_pass_txt").show();
       }
       else if(xpress_pass == "N"){ // fail
          $("#xpress_fail_txt").show();
       }

       $("#xpress_result").val(xpress_pass);

}



function xpress_service_assessment_after(){

   <?
     if($open_link == 'Y') echo "xpress_service_assessment1_after();"; // for participant
     else echo "xpress_service_assessment2_after();"; // for csl
   ?>
}

function xpress_service_assessment1_after(){
   $("#btn_save").hide();
   window.location = "../visit_form/x_xpress_service_consent.php?link=<? echo $link; ?>&r="+$("#xpress_result").val();
}

function xpress_service_assessment2_after(){
  $('#data_update_xpress').val('Y'); // set xpress update
}

/*
function xpress_service_clear_consent(){
  $("#consent_agree-Y").prop("checked",false);
  $("#consent_agree-N").prop("checked",false);

  $("#accept_channel_agree-Y").prop("checked",false);
  $("#accept_channel_agree-N").prop("checked",false);

  $("#accept_channel-1").prop("checked",false);
  $("#accept_channel-2").prop("checked",false);
  $("#accept_channel-3").prop("checked",false);
  $("#accept_channel-4").prop("checked",false);

  $("#consent_agree_tel").val('');
  $("#accept_channel_info").val('');
}
*/





</script>
<?

include_once("../inc_foot_include.php");
include_once("../function_js/js_fn_validate.php");
include_once("../in_savedata.php");

if($open_link == "Y"){ // open by open link to patient fill
  include_once("inc_form_foot.php");
}

?>

<? $mysqli->close(); ?>
