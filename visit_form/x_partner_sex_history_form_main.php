<?

//include_once("../in_auth.php");
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
$isRequire,
$logForm, // log form
$qParent, // question row parent : to check answer in this question is answered
$uidDataValue
)
{
  global $initJS_txt;
  global $uidData;

  global $open_link;

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
    if($open_link != "Y") $dataObj .= "<br>[$dataName] ";
  }
  else if($dataType == "text"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc <input type='text' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent'  class='save-data v_text' value='$value'> $valueUnit </div>";
  }
  else if($dataType == "textarea"){
    if($uidDataValue != "") $value = $uidDataValue;
    $dataObj = "<div id='div-$dataName' $startHide>$dataDesc
    <textarea rows='4' cols='50' maxlength='500' id='$dataName' name='$dataName' data-dom='$domain' data-id='$dataName' data-q_parent='$qParent' class='save-data'>$value</textarea>
    </div>";
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
    $btn_log_caption = "ไม่มีข้อมูล";
    $btn_log_color = "btn-secondary";

    if($logForm != ""){
      $log_form_name = "";
      if($logForm == "ae") {
        $log_form_name = "ADVERSE EVENT<br>";
      }
      else if($logForm == "con_med"){
        $log_form_name = "CON MED <br>";
      }
      $log_form_name .= $btn_log_caption;
      $btn_log = '<button id="div-btnlog_'."$dataName-$value".'" class="btn btn-sm '."$btn_log_color".' btn-log" data-id="'.$logForm.'" type="button" onclick="openFormLog(\''.$logForm.'\');">
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
       $dateVal = explode("-", $uidDataValue);
       $dateVal[0] = $dateVal[0] + 543; // uncomment on 29/11/2019
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
    $dataObj .= "<b><u>$dataDesc</u></b> <span id='$dataName-c' class='result-c'></span>";
  }
  else if($dataType == "topic"){
    $dataObj .= "<br><h4><b>$dataDesc</b></h4> <span id='$dataName-c' class='result-c'></span>";
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
       $dateVal = explode("-", $uidDataValue);
       $dateVal[0] = $dateVal[0] + 543;  // uncomment on 29/11/2019
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


$arr_uid_data = array();
/*
$query = "SELECT * FROM x_partner_sexhist
WHERE uid=? AND collect_date=? AND seq_no=?";
  //echo "<br>$uid, $visit_date, $seq_no / query : $query";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $uid, $collect_date, $seq_no);
*/
    $query = "SELECT * FROM x_partner_sexhist
    WHERE uid=? AND collect_date=? AND seq_no=? AND sexhist_no=?";
      //echo "<br>$uid, $visit_date, $seq_no / query : $query";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssss", $uid, $visit_date, $seq_no, $sexhist_no);

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
                $txt_row .= "</td></tr><tr><td colspan=2>$dabaObj";
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

  <div class="container" id="divSaveData<? echo $form_id; ?>">
    <h2><span class="badge badge-primary"><? echo "$uid"; ?></span> <span class="badge badge-warning"><? echo "$visit_date"; ?></span></h2>
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
<button id="btn_save<? echo $form_id; ?>" class="btn btn-success form-control" type="button"> บันทึกข้อมูล</button>
      </div>
    </div>

  </div>



<!-- temp vaiable for general use eg. in poc_screen -->
<input type="hidden" id="tmp_form_main1">
<input type="hidden" id="tmp_form_main2">

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

$(".v_radio, .v_checkbox").change(function(){
  //alert("data child "+$(this).data("child"));

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

});



  $("#btn_save<? echo $form_id; ?>").click(function(){
  //   alert("saveja");
     saveFormData<? echo $form_id; ?>(cData);
  }); // btn_save<? echo $form_id; ?>




});


//initJSForm
<? echo $initJSForm ; ?>


function initNewForm(){

  <?
     if($flag_save_form == "Y"){

     }
     // check complete form
     //echo "alert('clinic is $visit_status/".$_SESSION["weclinic_id"]."');";
  $visit_status="21";
     if($visit_status == "1" || $visit_status == "10" || $visit_status == "11"){
       if(isset($_SESSION["weclinic_id"])){
         if($_SESSION["weclinic_id"] == "%"){ // unlock to ihri staff to save changed
           echo "$('#btn_save<? echo $form_id; ?>').show();";

         }
         else if(isset($auth["data_backdate"])){
           echo "$('#btn_save<? echo $form_id; ?>').show();";
         }
         else{ // cbo (lock visit info after visit complete)

           echo "$('#btn_save<? echo $form_id; ?>').hide();";
           echo "$('.save-data-radio').prop('disabled',true);";
           echo "$('.save-data').prop('disabled',true);";

         }
       }
     }



  ?>
  //$(".btn-log").hide();
  $(".datachild").each(function(ix,objx){
    $(objx).prop( "disabled", true );
  });

  $(".start_hide").each(function(ix,objx){
    //alert("hide : "+$(objx).attr('id'))
    //  $(objx).data("is_show",'0');

    $(objx).hide();
  });

}



function saveFormData<? echo $form_id; ?>(cData){

//alert("save data  : "+moment($( "#work_entry_date").val(), "DD MMM YYYY").format("YYYY-MM-DD"));
  var divSaveData = "#divSaveData<? echo $form_id?>";

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


/*
    // check data child  if data parent is filled but datachild not filled
    if(typeof $(objx).data("parent") !== 'undefined'){

       if(($("#"+$(objx).data("parent")).hasClass("v_checkbox")) ||
          ($("#"+$(objx).data("parent")).hasClass("v_radio")){
            if($(objx).data("parent")).prop("checked") == true){

            }
       }
       else{ // parent is not checkbox or radio
           $(objx).data("parent")).prop("checked")
       }

    }
*/

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

/*
  var txt="";
  	for (var key in cData) {
  			txt += "["+key+" value : "+cData[key]+"]";
  	}
  	alert("cData : "+txt);
*/


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
	if(i == 0){ // all are valid
    flag_save = "Y";
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

    // add seq_no
    var objData1 = {
      name:'seq_no',
      dom:'partner_sexhist',
      value:'<? echo $seq_no;?>'
    }
    lst_data_obj.push(objData1);

    var objData2 = {
      name:'sexhist_no',
      dom:'partner_sexhist',
      value:'<? echo $sexhist_no;?>'
    }
    lst_data_obj.push(objData2);

    var objData3 = {
      name:'sexhist_seq_no',
      dom:'partner_sexhist',
      value:'<? echo $sexhist_seq_no;?>'
    }
    lst_data_obj.push(objData3);




    var aData = {
              u_mode:"save_data",
              uid:'<? echo $uid; ?>',
              form_id:'<? echo $form_id; ?>',
              form_done:'N',
              lst_data:lst_data_obj,
              visit_date:'<? echo $visit_date; ?>',
              open_link:'<? echo $open_link; ?>'
    };

    <?
      $db_form_path= "visit_form/";
      if($open_link == "Y") $db_form_path= "";
    ?>

    //alert("form path : <?echo "$db_form_path-db_form_data.php" ?>");
    save_data_ajax(aData,"<? echo $db_form_path; ?>db_form_data.php",saveFormData<? echo $form_id; ?>Complete);
    //save_data_ajax(aData,"visit_form/db_form_data.php",saveFormData<? echo $form_id; ?>Complete);

  }

}

function saveFormData<? echo $form_id; ?>Complete(flagSave, rtnDataAjax, aData){
  // alert("flag save99 is : "+flagSave+" open_link: <? echo $open_link;?>");
  if(flagSave){
    <?
      if($open_link == "Y"){
        $pid_txt = (isset($pid)?"($pid)":"");
        echo "window.location = '../info/inf_txt.php?e=f1&f=$form_name&u=$uic $pid_txt';";

      }
    ?>

    $.notify("บันทึกข้อมูลแล้ว", "info");
    <? echo $after_save_function; ?>
  }
}

function setDataChangeSexHist(){

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
