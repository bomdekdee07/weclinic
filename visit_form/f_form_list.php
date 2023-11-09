<?
include_once("../in_db_conn.php");
include_once("../../function/in_fn_link.php");

$project_id="POC";
$project_name="Point of Care Form Data";

$option_form = "
<option value='poc_screen' selected class='text-success'>POC Screening</option>
<option value='poc_enroll' class='text-danger'>POC Enrollment</option>
";


?>


<!DOCTYPE html>
<html>
<head>
<title><? echo $project_name; ?></title>
<?
include_once("../inc_head_include.php");
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

</head>
<body>
  <div class='container-fluid'>
  <div id="div_form_update">
    <div class="row bg-primary pb-2">
      <div class="col-md-3 text-white">
       <h2><? echo $project_name; ?></h2>
      </div>

      <div class="col-md-2">
        <!--
        <label for="txt_pid" class="text-light">PID</label>
        <input id="txt_pid" type="text" class="form-control form-control-sm save-data" placeholder="PID" data-title="PID">
      -->
      </div>
      <div class="col-md-2">
        <label for="sel_form_id" class="text-light">FORM</label>
        <select id="sel_form_id" class="form-control form-control-sm" >
         <? echo $option_form; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label for="txt_uic" class="text-light">UIC</label>
        <input id="txt_uic" type="text" class="form-control form-control-sm save-data v-no-blank" placeholder="UIC" data-title="UIC">
      </div>
      <div class="col-md-2">
        <label for="txt_visit_date" class="text-light">Visit Date (พ.ศ.)</label>
        <input id="txt_visit_date" type="text" class="form-control form-control-sm save-data v_date v-no-blank v-date" placeholder="dd/mm/yyyy" data-title="Visit Date">
      </div>
      <div class="col-md-1">
        <label for="btn_open_form" class="text-primary">.</label>
        <button id="btn_open_form" class="form-control btn btn-warning btn-sm" type="button"><i class="fa fa-search fa-lg"></i> Open</button>
      </div>
    </div>


  </div>
  <div id="div_form_data">
    <div class="my-1">
      <select id="sel_load_form_id"  >
       <? echo $option_form; ?>
      </select>

      <button id="btn_reload_data" class="btn btn-secondary btn-sm" type="button"> Load</button>
    </div>
    <div class="my-1">
      <table id="tbl_form_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th></th>
              <th>UIC</th>
              <th>PID</th>
              <th>Form</th>
              <th>Visit Date</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
      </table>
    </div>

  </div>


</div>

  <input type="hidden" id="arr_domain" >
</body>
</html>


<script>

$(document).ready(function(){



  $.datepicker.setDefaults( $.datepicker.regional[ "th" ] );
  var currentDate = new Date();
  currentDate.setYear(currentDate.getFullYear() + 543);

  $('#txt_visit_date').datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: '+471:+570',
    dateFormat: 'dd/mm/yy'
  });
  $('#txt_visit_date').datepicker('setDate',currentDate );

  $(".txt_visit_date").focus(function(){ // set to current date when focus to date field
    if($(this).val() == ''){
      $(this).datepicker('setDate',currentDate );
    }
  });

  $("#btn_open_form").click(function(){

     if(validateInput("div_form_update")){
          if($('#txt_uic').val().trim().length != 8){
            $.notify("UIC ไม่ถูกต้อง","warn");
          }
          else{//valid

            var visitDate = getDataObjValue($('#txt_visit_date'));
             openUICFormLink(
               $('#txt_uic').val().trim(),
               visitDate,
               $('#sel_form_id').val()
             );
            /*
            var open_page = "x_"+$('#sel_form_id').val()+".php?";
            open_page+= "uic="+$('#txt_uic').val().trim();
            open_page+= "&pid="+$('#txt_pid').val().trim();
            open_page+= "&visit_date="+visitDate;
            alert("open_page : "+open_page);
            window.open(open_page, '_blank');
            */
          }
     }
     //saveFormData(cData);
  }); // btn_save

  $("#btn_reload_data").click(function(){
     loadUICFormList();

  }); // btn_reload_data



});

function openUICForm(uic, formID, visitDate){
  var open_page = "x_"+formID+".php?";
  open_page+= "uic="+uic;
  open_page+= "&visit_date="+visitDate;
  //alert("open_page : "+open_page);
  window.open(open_page, '_blank');
}

function openUICForm2(formID, link){
  var open_page = "x_"+formID+".php?";
  open_page+= "link="+link;
  //alert("open_page : "+open_page);
  window.open(open_page, '_blank');
}


function loadUICFormList(){
  var aData = {
            u_mode:"select_list",
            uic:$('#uic').val(),
            form_id:$('#sel_load_form_id').val()
  };
  save_data_ajax(aData,"db_form_admin.php",loadUICFormListComplete);
}

function loadUICFormListComplete(flagSave, rtnDataAjax, aData){
  //  alert("flag save is : "+flagSave);
  if(flagSave){
    //$("#row_"+rtnDataAjax.id).remove();
    var txt_row = "";
    var datalist = rtnDataAjax.datalist;
    if(datalist.length > 0){
      for (i = 0; i < datalist.length; i++) {
        var dataObj = datalist[i];
        txt_row += '<tr class="r_data">';
        //txt_row += ' <td><button class="btn btn-primary" type="button" onclick="openUICForm(\''+dataObj.uic+'\',\''+dataObj.form_id+'\',\''+dataObj.visit_date+'\')""><i class="fa fa-user"></i> '+dataObj.uic+'</button></td>';
        txt_row += ' <td><button class="btn btn-primary" type="button" onclick="openUICForm2(\''+dataObj.form_id+'\',\''+dataObj.link+'\')""><i class="fa fa-user"></i> '+dataObj.uic+'</button></td>';
        txt_row += ' <td>'+dataObj.uic+'</td>';
        txt_row += ' <td>'+dataObj.pid+'</td>';
        txt_row += ' <td>'+dataObj.form_id+'</td>';
        txt_row += ' <td>'+changeToThaiDate(dataObj.visit_date)+'</td>';
        //txt_row += ' <td>'+dataObj.visit_date+'</td>';
        txt_row += '</tr">';
      }//for
    }//if
    $('.r_data').remove(); // row course taken
    $('#tbl_form_list > tbody:last-child').append(txt_row);
  }
}


function openUICFormLink(uicID, visitDate, formID){
  var aData = {
            u_mode:"open_form_link",
            uic:uicID,
            visit_date:visitDate,
            form_id:formID

  };
  save_data_ajax(aData,"db_form_admin.php",openUICFormLinkComplete);
}

function openUICFormLinkComplete(flagSave, rtnDataAjax, aData){
  //  alert("flag save is : "+flagSave);
  if(flagSave){
    var open_page = "x_"+aData.form_id+".php?";
    open_page+= "link="+rtnDataAjax.link;
  //alert("open_page : "+open_page);
    window.open(open_page, '_blank');
  }
}






</script>


<? include_once("../inc_form_foot_include.php"); ?>
<? include_once("../../function_js/js_fn_validate.php"); ?>
<? include_once("../in_savedata.php");
//$mysqli->close();
?>
