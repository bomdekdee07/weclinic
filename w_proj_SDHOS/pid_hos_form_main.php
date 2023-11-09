
<?

//include_once("../in_auth.php");
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function
include_once("../function/in_fn_link.php");


$initJS_txt = "";
$initFormCheck = "var cData=[];";
$initFormShowHideComp = "var shData=[];"; // init show/hide component;

$formDataObj = "data_$form_id";
//$initFormDataObj_txt = " var $formDataObj=[];";


function createDataObj($domain, $dataType, $dataName, $dataDesc,
$value, $valueUnit, $dataChild, $dataHide,
$isShow, // show/hide component on start
$isRequire,
$logForm, // log form
$qParent, // question row parent : to check answer in this question is answered
$uidDataValue
)
{
  global $initJS_txt;
  global $uidData;


  //echo "<br> $dataName - $dataChild- $isShow";
  //echo "<br> $dataName - $uidDataValue- $value";

  if($uidDataValue != ""){ // check enable/disable data child
    if($dataChild != "" && $uidDataValue == $value){
    //  echo "<br> $dataName - $dataChild";
      $initJS_txt .="$('#$dataChild').prop('disabled', false); ";
    }

  }

  // data hide/show
  $startHide = ($isShow == '0')?"class='start_hide'":"";


  $dataObj = "";
  if($dataType == "title"){

    $dataObj .= "<b>$dataDesc</b> ";
    $dataObj .= "<br>[$dataName] ";
  }
  else if($dataType == "text"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent'  data-odata='$value'  class='save-data v_text' value='$value'> $valueUnit </div>";
  }
  else if($dataType == "textarea"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc
    <textarea rows='4' cols='50' maxlength='500' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent'  data-odata='$value' class='save-data'>$value</textarea>
    </div>";
  }
  else if($dataType == "check"){
    $check = "";
    if($uidDataValue == $value) $check = "checked";
    $dataObj = "<div id='div-$dataName' $startHide><label class='form-check-label' for='$dataName'> <input type='checkbox' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-child='$dataChild' data-q_parent='$qParent' $dataHide  data-odata='$check'  class='save-data v_checkbox $qParent' value='$value' $check>
    $dataDesc</label></div>";
  }
  else if($dataType == "radio"){
    $check = "";
    if($uidDataValue == $value) $check = "checked";

    $dataObj = "<div id='div-$dataName-$value' $startHide><label class='form-check-label' for='$dataName-$value' >
    <input type='radio' id='$dataName-$value' name='$dataName' data-dom='$domain' data-id='$dataName' data-child='$dataChild' data-logform='$logForm' data-q_parent='$qParent'  $dataHide   data-odata='$check'  class='save-data-radio v_radio' value='$value' $check>
    $dataDesc </label>

    </div>";
  }
  else if($dataType == "date"){
    if($uidDataValue != "" && $uidDataValue != "0000-00-00"){
       $dateVal = explode("-", $uidDataValue);
       $dateVal[0] = $dateVal[0] + 543; // uncomment on 29/11/2019
       $value = $dateVal[2]."/".$dateVal[1]."/".$dateVal[0];
    }


    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent'  data-odata='$value' class='save-data v_date' value='$value'> $valueUnit</div>";
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
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent'  data-odata='$value'  class='save-data v_partial_date' value='$value'> $valueUnit </div>";
    $initJS_txt .= "
    $('#$dataName').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy'
    });";
  }
  else if($dataType == "int"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent'  data-odata='$value' class='save-data v_int' value='$value'> $valueUnit</div>";
  }
  else if($dataType == "double"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent'   data-odata='$value' class='save-data v_double' value='$value'> $valueUnit</div>";
  }
  else if($dataType == "title_row"){
    $dataObj .= "<b><u>$dataDesc</u></b> <span id='$dataName-c' class='result-c'></span>";
  }
  else if($dataType == "topic"){
    $dataObj .= "<br><h4><b>$dataDesc</b></h4> <span id='$dataName-c' class='result-c'></span>";
  }
  else if($dataType == "hidden"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<input type='hidden' id='$dataName' value='$value'  data-id='$dataName' data-dom='$domain'   data-odata='$value'  class='save-data'>";
  }


 return $dataObj;
}//createDataObj



function createDataChild($domain, $dataType, $dataName, $dataDesc , $value, $valueUnit, $dataParent, $isShow, $is_require, $uidDataValue){
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
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-parent='$dataParent'  data-odata='$value' class='save-data v_text $dataParent datachild' value='$value'> $valueUnit</div>";
  }


  else if($dataType == "check"){
    $check = "";
    if($uidDataValue == $value) $check = "checked";

    $dataObj = "<div id='div-$dataName' $startHide><label class='form-check-label' for='$dataName'> <input type='checkbox' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName'  data-odata='$value' class='save-data v_checkbox datachild' value='$value'>
     $dataDesc</label></div>";
  }
  else if($dataType == "radio"){
    $check = "";
    if($uidDataValue == $value) $check = "checked";

    $dataObj = "<div id='div-$dataName-$value' $startHide><label class='form-check-label' for='$dataName-$value' >
    <input type='radio' id='$dataName-$value' name='$dataName' data-dom='$domain' data-id='$dataName' data-parent='$dataParent'  data-odata='$value' class='save-data-radio v_radio $dataParent datachild' value='$value'>
    $dataDesc</label></div>";
  }
  else if($dataType == "date"){
    if($uidDataValue != "" && $uidDataValue != "0000-00-00"){
       $dateVal = explode("-", $uidDataValue);
       $dateVal[0] = $dateVal[0] + 543;  // uncomment on 29/11/2019
       $value = $dateVal[2]."/".$dateVal[1]."/".$dateVal[0];
    }

    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName'  data-odata='$value' class='save-data v_date $dataParent datachild' value='$value'> $valueUnit</div>";
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
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-parent='$dataParent'  data-odata='$value' class='save-data v_partial_date $dataParent datachild' value='$value'> $valueUnit</div>";
    $initJS_txt .= "
    $('#$dataName').datepicker({
      changeMonth: true,
      changeYear: true,

      dateFormat: 'dd/mm/yy'
    });";

  }

  else if($dataType == "int"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName'  data-odata='$value' class='save-data v_int $dataParent datachild' value='$value'> $valueUnit</div>";
  }
  else if($dataType == "double"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName'  data-odata='$value' class='save-data v_double $dataParent datachild' value='$value'> $valueUnit</div>";
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


$sql_add = "AND collect_date='$visit_date' ";
if(isset($sel_sql_add)){
   $sql_add = "$sel_sql_add ";
}



$arr_uid_data = array();
foreach($arr_domain_id as $domain_id){
//  echo "<br>$domain_id";
  $query = "SELECT * FROM sdhos_$domain_id
  WHERE pid='$pid'
  $sql_add";
//echo "<br>$pid, $visit_date / query : $query";
    $stmt = $mysqli->prepare($query);
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

//echo "count  : ".count($uidData);

//$k = 0;

$formData_txt = "";
foreach($uidData as $key => $value){ // each key in each row
  //$k++;
  //if($k > 3){ // except first 3 fields (pid, collect_date, collect_time)
    //if($value != ""){

      if($value == "0000-00-00") $value = "";
      else if($value == '0') $value = "";

    //}
    $value = str_replace("\n", '', $value);
    $value = str_replace("\r", '', $value);
    $formData_txt .= "$key:{dval:'$value',odata:''},";


    //$initFormDataObj_txt .= " setFormObject($formDataObj,'$key','$value'); ";

  //}
  //echo "$key - $value <br>";

}//foreach


$formData_txt =  substr($formData_txt,0,(strlen($formData_txt) -1) );
$initFormDataObj_txt = "var $formDataObj={";
$initFormDataObj_txt .= $formData_txt;
$initFormDataObj_txt .= "};";
$initFormDataObj_txt .= " setFormOData($formDataObj);";



$arr_data_child = array();
$query = "SELECT p1.*, p2.data_name as data_parent_name
          FROM p_form_data as p1 LEFT JOIN p_form_data as p2 ON (p1.data_name=p2.data_child)
          WHERE p1.form_id =?

          AND p1.data_name IN (
          SELECT data_child
          FROM p_form_data
          WHERE form_id =? AND data_child <> ''
          )
          AND p1.data_hide <> '1'
          ORDER BY data_seq       ";

//echo "$query <br> $domain_id";
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
                         $row['is_require'],
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
   $txt_row = "<table width='100%' class='tbl-form'><tr>";
   $query = "SELECT *
             FROM p_form_data
             WHERE form_id =? AND data_name NOT IN (
             SELECT data_child
             FROM p_form_data
             WHERE form_id =? AND data_child <> ''
             )
             AND data_hide <> '1'
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
                                       $row['is_require'],
                                       $row['log_form'], // log form (eg. AE, con med)
                                       $q_parent, // question row parent
                                       $uidDataValue // data from uid record
                                     );

             if($row['data_type'] == 'title'){ // new question

                $start_hide="";
                if($row['is_show'] == 0) $start_hide="start_hide";

                $txt_row .= "</td></tr><tr id='q_".$row['data_name']."' data-is_show='".$row['is_show']."'  data-is_require='".$row['is_require']."' class='c_data $start_hide'><td width='50%'>$dabaObj</td><td>";

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



?>



<style>

table.tbl-form  tr:nth-child(odd) td{ background-color:#E0F5FE; }
table.tbl-form  tr:nth-child(even) td{ background-color:#C8F1FF; }

.tbl-form th, .tbl-form td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
.tbl-form tr:hover td {
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
    <h2><span class="badge badge-primary"><? echo "$form_name"; ?></span> </h2>
    <div class="panel panel-default">
      <div class="panel-body">

        <?

echo "$form_top";
echo "<div>$txt_row</div>" ;
echo "$form_bottom";

        ?>

      </div>
    </div>


    <div class="panel panel-default">
      <div class="panel-body my-4">
<button id="btn_save_sdhos_form" class="btn btn-success form-control" type="button"> บันทึกข้อมูล</button>
      </div>
    </div>

  </div>


<div class="mt-4">
  <?

  //echo $initFormDataObj_txt ;
  ?>
</div>
<!-- temp vaiable for general use eg. in poc_screen -->
<input type="hidden" id="tmp_form_main1">
<input type="hidden" id="tmp_form_main2">

<script>

//initFormCheck
<?
echo $initFormCheck ;
echo $initFormDataObj_txt ;
?>

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



var shData=[];
// declare option show/hide component
<? echo $option_showhide; ?>
showhideComponent(shData);

/*
var txt="";
	for (var key in cData) {
			txt += "["+key+" value : "+cData[key]+"]";
	}
	alert("cData : "+txt);
*/



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


$(".v_partial_date").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});
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

  $(".v_partial_date").change(function(){ // validate partial date field
    if($(this).val().trim() != ''){
      if(!validatePartialDate($(this).val())){
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



/*
   if($(objx).hasClass("v_checkbox")){
        if($(objx).prop("checked") == true)
        cData[$(objx).data("q_parent")] += $(objx).val();
     }
*/

$(".v_radio").change(function(){
    if($(this).data("child") != '' ){ // there is data child in this option
      // set all same question input to blank and disable
      var tmp_var = $("#"+$(this).data("child")).val() ;
      $("."+$(this).data("id")).val('');
      $("."+$(this).data("id")).removeClass('input_invalid');
      $("."+$(this).data("id")).prop( "disabled", true );
      //************************
       $("#"+$(this).data("child")).prop( "disabled", false );
       $("#"+$(this).data("child")).val(tmp_var) ;

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
      $("."+$(this).data("id")).prop( "disabled", true );
    }

    // if there this component is parent of show/hide other component
    if(typeof shData[$(this).attr("id")]  !== 'undefined' ){
          checkShowHideComponent($(this));
    }

});


$(".v_checkbox").change(function(){
    if($(this).data("child") != '' ){ // there is data child in this option

       if($(this).prop("checked") == true){
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
         $("."+$(this).data("id")).prop( "disabled", true );
       }
    }
    // if there this component is parent of show/hide other component
    if(typeof shData[$(this).attr("id")]  !== 'undefined' ){
          checkShowHideComponent($(this));
    }

});

function checkShowHideComponent(objComp){ // check show hide data from this component

     if(shData[objComp.attr("id")].dtype == 'radio'){
       // show/hide each component
        if(typeof shData[objComp.attr("id")]['show']  !== 'undefined' ){
           var comp_show = shData[objComp.attr("id")]['show'].split(',');
           for(i=0; i<comp_show.length; i++){
             $('#div-'+comp_show[i]).show();
           }// for
        }
        if(typeof shData[objComp.attr("id")]['hide']  !== 'undefined' ){
           var comp_hide = shData[objComp.attr("id")]['hide'].split(',');
           for(i=0; i<comp_hide.length; i++){
             $('#div-'+comp_hide[i]).hide();
           }// for
        }

       // show/hide each question
        if(typeof shData[objComp.attr("id")]['show_q']  !== 'undefined' ){
           var comp_show = shData[objComp.attr("id")]['show_q'].split(',');
           for(i=0; i<comp_show.length; i++){
             $('#q_'+comp_show[i]).show();
             $('#q_'+comp_show[i]).data("is_show",'1');
             //alert("show "+comp_show[i]);
           }// for
        }

        if(typeof shData[objComp.attr("id")]['hide_q']  !== 'undefined' ){
           var comp_hide = shData[objComp.attr("id")]['hide_q'].split(',');
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
         if(typeof shData[objComp.attr("id")]['show_t']  !== 'undefined' ){
            var comp_show = shData[objComp.attr("id")]['show_t'].split(',');
            for(i=0; i<comp_show.length; i++){
              $('#t_'+comp_show[i]).show();
            }// for
         }

         if(typeof shData[objComp.attr("id")]['hide_t']  !== 'undefined' ){
            var comp_hide = shData[objComp.attr("id")]['hide_t'].split(',');
            for(i=0; i<comp_hide.length; i++){
              $('#t_'+comp_hide[i]).hide();
            }// for
         }
     } // end radio btn


     else if(shData[objComp.attr("id")].dtype == 'check'){
       if(objComp.prop("checked") == true){
         if(typeof shData[objComp.attr("id")]['show']  !== 'undefined' ){
            var comp_show = shData[objComp.attr("id")]['show'].split(',');
            for(i=0; i<comp_show.length; i++){
              $('#div-'+comp_show[i]).show();
            }// for
         }
         // show/hide each question
          if(typeof shData[objComp.attr("id")]['show_q']  !== 'undefined' ){
             var comp_show = shData[objComp.attr("id")]['show_q'].split(',');
             for(i=0; i<comp_show.length; i++){
               $('#q_'+comp_show[i]).show();
               $('#q_'+comp_show[i]).data("is_show",'1');
               //alert("show "+comp_show[i]);
             }// for
          }
          // show/hide each title_row
           if(typeof shData[objComp.attr("id")]['show_t']  !== 'undefined' ){
              var comp_show = shData[objComp.attr("id")]['show_t'].split(',');
              for(i=0; i<comp_show.length; i++){
                $('#t_'+comp_show[i]).show();
              }// for
           }
       }
       else{ // checkbox uncheck  hide component
         if(typeof shData[objComp.attr("id")]['show']  !== 'undefined' ){
            var comp_show = shData[objComp.attr("id")]['show'].split(',');
            for(i=0; i<comp_show.length; i++){
              $('#div-'+comp_show[i]).hide();
            }// for
         }
         // show/hide each question
         if(typeof shData[objComp.attr("id")]['show_q']  !== 'undefined' ){
            var comp_hide = shData[objComp.attr("id")]['show_q'].split(',');
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
           if(typeof shData[objComp.attr("id")]['show_t']  !== 'undefined' ){
              var comp_show = shData[objComp.attr("id")]['show_t'].split(',');
              for(i=0; i<comp_show.length; i++){
                $('#t_'+comp_show[i]).hide();
              }// for
           }

       } // else uncheck checkbox


     } // end checkbox

  }//checkShowHideComponent



  $("#btn_save_sdhos_form").click(function(){
    // alert("saveja visitdate: <? echo $visit_date ?>");

     saveFormData(cData);
     //saveFormBaseLine();
  }); // btn_save_sdhos_form


});

function saveDataBaseLine(){
  saveFormData(cData);
  //aftersaveBaseLine();
}
function saveDataSDHosLog(){
  saveFormData(cData);
}


//initJSForm
setOData("divSaveData");
<? echo $initJSForm ; ?>


function initNewForm(){

  <?

     // check complete form
     //echo "alert('clinic is $visit_status/".$_SESSION["weclinic_id"]."');";
//user permission
/*
       if(isset($_SESSION["weclinic_id"])){
         if($_SESSION["weclinic_id"] == "%"){ // unlock to ihri staff to save changed
           echo "$('#btn_save_sdhos_form').show();";

         }
         else if(isset($auth["data_backdate"])){
           echo "$('#btn_save_sdhos_form').show();";
         }
         else{ // cbo (lock visit info after visit complete)

           echo "$('#btn_save_sdhos_form').hide();";
           echo "$('.save-data-radio').prop('disabled',true);";
           echo "$('.save-data').prop('disabled',true);";

         }
       }
*/



  ?>
  //$(".btn-log").hide();
  $(".datachild").each(function(ix,objx){
    $(objx).prop( "disabled", true );
  });

  $(".start_hide").each(function(ix,objx){
    //alert("hide : "+$(objx).attr('id'));
    //  $(objx).data("is_show",'0');
    $(objx).hide();
  });

}



function saveFormData(cData){
//alert("saveFormData1 ");
  var visitDate = "<?echo $visit_date?>"; // pk main form
  var seqNo = "<?echo $seq_no?>"; // pk log form

  <?
  if(isset($before_save_function))
  echo $before_save_function;
  ?>

  var divSaveData = "#divSaveData";

  var lst_data_obj = [];

  $(divSaveData +" .save-data").each(function(ix,objx){
    var objVal = getDataObjValue($(objx));
    //alert("<? echo $formDataObj ?> objVal "+objVal);
    if(checkDataChangeFormObject(<? echo $formDataObj?>,
      $(objx).data("id"),
      objVal)){


        //objVal =  objVal.replace(/(\r\n|\n|\r)/gm, "<br>");

        var objData = {
          name: $(objx).data("id"),
          dom:$(objx).data("dom"),
          value:objVal
        }
        lst_data_obj.push(objData);

    }



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


    if(checkDataChangeFormObject(<? echo $formDataObj?>,
      $(objx).data("id"),
      $(objx).val())){

        var objData = {
          name: $(objx).data("id"),
          dom:$(objx).data("dom"),
          value:$(objx).val()
        }
        lst_data_obj.push(objData);

    }

//alert("valuexx : "+$(objx).data("q_parent")+" / "+cData[$(objx).data("q_parent")]+" / "+objVal);
		if(typeof $(objx).data("q_parent") !== 'undefined'){
			//cData[$(objx).data("q_parent")] += $(objx).val();
      cData[$(objx).data("q_parent")] += '1';
		}
  });



var txt="";
var i=0;
  $('.c_data').removeClass("q_invalid");
	for (var key in cData) {

			if(cData[key] == ''){ // no answer in question

        if($('#'+key).data('is_show') == '1'){
          //alert("key is : "+key);

            if($('#'+key).data('is_require') == '1'){ // set at datatype: title (1=require, 0=not require)
              $('#'+key).addClass("q_invalid");
              i++;
            }

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
              //  alert("key has :"+$(objx).data("id")+" type:"+$(objx).attr("type"));

              if(checkDataChangeFormObject(<? echo $formDataObj?>,
                $(objx).data("id"),"")){ // assign blank value in datachild which under uncheck parent obj
                  var objData = {
                    name: $(objx).data("id"),
                    dom:$(objx).data("dom"),
                    value:""
                  }
                  lst_data_obj.push(objData);

              }
              data_id.push($(objx).data("id"));

/*
                var objData = {
                  name: $(objx).data("id"),
                  dom:$(objx).data("dom"),
                  value:""
                }
                lst_data_obj.push(objData);
                data_id.push($(objx).data("id"));
                //alert("key in :"+$(objx).data("id"));
      */
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

  var flag_save = "N";
  var is_formDone = "N";
	if(i == 0){ // all are valid
    flag_save = "Y";
    is_formDone = "Y";
	}
  else{
    flag_save = "N";

    //alert("Invaid "+i);
    $.notify("กรอกข้อมูลไม่ครบ "+i+ " ข้อ", "warn");
    var result = confirm("ข้อมูลไม่ครบ ท่านต้องการยืนยันที่จะบันทึกใช่หรือไม่ ?");
    if (result) {
        flag_save = "Y";
    }
  }

  if(flag_save == "Y"){
    //checkDataChange("divSaveData");
  //  alert("ttl datachange  : "+lst_data_obj.length);
    if(lst_data_obj.length > 0){ // there is data changed
//alert("form done : "+is_formDone);

/*
var JSONString = JSON.stringify(lst_data_obj);
var lst_data_obj_str = JSONString.replace(/\\n/g, "\\n")
                                      .replace(/\\'/g, "\\'")
                                      .replace(/\\"/g, '\\"')
                                      .replace(/\\&/g, "\\&")
                                      .replace(/\\r/g, "\\r")
                                      .replace(/\\t/g, "\\t")
                                      .replace(/\\b/g, "\\b")
                                      .replace(/\\f/g, "\\f");

*/
      var aData = {
        u_mode:"<? echo $u_mode_save; ?>",
        form_id:'<? echo $form_id; ?>',
        pid:'<? echo $pid; ?>',
        lst_data:lst_data_obj,
        is_form_done:is_formDone,
        visit_date:visitDate,
        seq_no:seqNo
      };

      var db_page = "db_hos_pid";
      <? if ($form_id == 'sdhos_retro') echo "db_page ='db_hos_pid_retro';";?>
      save_data_ajax(aData,"w_proj_SDHOS/"+db_page+".php",saveFormDataComplete);

    }
    else{
      $.notify("ข้อมูลไม่มีการเปลี่ยนแปลง", "info");
    }
  }

}

function saveFormDataComplete(flagSave, rtnDataAjax, aData){
   //alert("flag save99 is : "+flagSave+" open_lik: ");
  if(flagSave){
    <? echo $after_save_function; ?>
    $.notify("บันทึกข้อมูลแล้ว", "info");
    setFormOData(<? echo $formDataObj; ?>);
  }
}


function checkFormDataChange(){ // check datachange in form (user before press close button)
  var flagChange = false;
  var divSaveData = "#divSaveData";
  //alert("checkFormDataChange <? echo $formDataObj?>");
  $(divSaveData +" .save-data").each(function(ix,objx){
    var objVal = getDataObjValue($(objx));
    if(checkDataChangeFormObject(<? echo $formDataObj?>,
      $(objx).data("id"),
      objVal)){
      //  alert("change : "+$(objx).data("id"));
        flagChange = true;
        return ;
    }
  });

  $(divSaveData +" .save-data-radio:checked").each(function(ix,objx){
    if(checkDataChangeFormObject(<? echo $formDataObj?>,
      $(objx).data("id"),
      $(objx).val())){
      //  alert("change rdo : "+$(objx).data("id"));
        flagChange = true;
        return ;
    }

  });

  return flagChange;
}

// showhide component at start
function showhideComponent(shData){
  for (itm in shData ) {
    //alert(shData[itm].dtype+"/"+shData[itm].show_q);
    var flag=false;
    var flag_checkbox_uncheck = false ;
    if(shData[itm].dtype == 'radio' || shData[itm].dtype == 'check'){
       if($('#'+itm).prop("checked") == true){
         flag=true;
       }
       else{
         if(shData[itm].dtype == 'check') flag_checkbox_uncheck = true;
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

      else{ // flag false  (checkbox uncheck)
          if(flag_checkbox_uncheck){ // (checkbox uncheck)
            if(typeof shData[itm]['show']  !== 'undefined' ){
              var comp = shData[itm]['show'].split(',');
              for(i=0; i<comp.length; i++){
                $('#div-'+comp[i]).hide();
              }// for
            }

    // question
            if(typeof shData[itm]['show_q']  !== 'undefined' ){
              var comp = shData[itm]['show_q'].split(',');
              for(i=0; i<comp.length; i++){
                $('#q_'+comp[i]).hide();
                $('#q_'+comp[i]).data("is_show",'0');
              }// for
            }

    // title_row

            if(typeof shData[itm]['show_t']  !== 'undefined' ){
              var comp = shData[itm]['show_t'].split(',');
              for(i=0; i<comp.length; i++){
                $('#t_'+comp[i]).hide();
              }// for
            }
          }
      }//flag false (checkbox uncheck)

  }// for


}// showhideComponent

</script>
<?

include_once("../inc_foot_include.php");
include_once("../function_js/js_fn_validate.php");
include_once("../in_savedata.php");



?>

<? $mysqli->close(); ?>
