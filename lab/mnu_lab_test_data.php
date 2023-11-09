
<script>

ResetTimeOutTimer();

</script>

<style>
.tbl_test_data tr td:first-child{
    width:1%;
    white-space:nowrap;
}
</style>

<div class="card" id = "div_lab_test_detail">
  <div class="card-header bg-primary text-white" style="max-height: 3rem;">
      <div class="row ">
         <div class="col-sm-4">
           <h4><i class="fa fa-flask fa-lg" aria-hidden="true"></i> <b>Lab Test</b> [<span id="lab_test_group_title"></span>]</h4>
         </div>
         <div class="col-sm-7">

           Record ID: <input type="text" id="lab_id2" size="20" disabled>

         </div>
         <div class="col-sm-1 pr-0">
           <button id="btn_close_lab_test_detail" class="my-1 form-control form-control-sm btn btn-light btn-sm float-right mb-1" type="button">
             <i class="fa fa-times-circle fa-lg" ></i> ปิด
           </button>
         </div>
      </div>
  </div>
  <div class="card-body">
    <div class="row my-1">
      <div class="col-sm-1">
        <label for="lab_id">Lab ID:</label>
        <input type="text" id="lab_id" data-title="ID"  class="form-control form-control-sm save-data v-no-blank input-text-code" maxlength="10">
      </div>
      <div class="col-sm-4">
        <label for="lab_name">Lab Test Name:</label>
        <input type="text" id="lab_name" data-title="Name"  class="form-control form-control-sm save-data v-no-blank" maxlength="150">
      </div>
      <div class="col-sm-1">
        <label for="lab_unit">Lab Test Unit:</label>
        <input type="text" id="lab_unit" data-title="unit"  class="form-control form-control-sm save-data" maxlength="30">
      </div>
      <div class="col-sm-4">
        <label for="lab_name_report">Special Name pattern in Lab Report: (<i>Italic</i>|<b>Bold</b>|<u>Underline</u>)</label>
        <input type="text" id="lab_name_report" data-title="Name In Report"  class="form-control form-control-sm save-data" maxlength="150">
      </div>
      <div class="col-sm-2 py-1 bg-msoft2 ptxt-s10">

          <b><u>ตัวอย่างการใช้งาน</u></b><br>
          Italic / ตัวเอียง = &lt;i&gt;Text&lt;/i&gt; <br>
          Bold / ตัวหนา = &lt;b&gt;Text&lt;/b&gt; <br>
          Underline / ขีดเส้นใต้ = &lt;u&gt;Text&lt;/u&gt;<br>
          ตัวอย่าง:  NG &lt;i&gt;Diagnosis&lt;/i&gt; = NG <i>Diagnosis</i>

      </div>

    </div>

    <div class="my-2">

      <div class="row my-1">
        <div class="col-sm-6">
          <div class="my-1  px-2 py-2" style="min-height: 150px; border:1px solid #ccc;">
            <div id = "div_lab_test_txt" class="div-tbl-labtest" style="display:none;">
            <b><u>Lab Result</u></b> (Text)
            <button id="btn_add_lab_test_txt_result" class="btn btn-light" type="button">
             <i class="fa fa-plus fa-lg" ></i> ADD
            </button>
            <div>
              <table id="tbl_lab_test_txt_result" class="table table-bordered table-sm table-striped table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Description</th>
                      <th>Normal Range?</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
              </table>
              </div>
            </div> <!-- div_lab_test_txt-->

            <div id = "div_lab_test_num" class="div-tbl-labtest" style="display:none;">
            <b><u>Lab Result</u></b> (Numeric)
            <table id="tbl_lab_test_num_result" class="table table-bordered table-sm table-hover">
                <thead>
                  <tr>
                    <th>DESC</th>
                    <th>Minimum Value</th>
                    <th>Maximum Value</th>

                  </tr>
                </thead>
                <tbody>
                  <tr style="background-color:#eee;">
                    <td >Possible result: </td>
                    <td>
                      <input type="text" id="lab_result_min" data-title="Lab Minimum"  class="form-control form-control-sm save-data lab-result-num input-decimal" size="15" maxlength="10" placeholder="Min Value">
                    </td>
                    <td>
                      <input type="text" id="lab_result_max" data-title="Lab Maximum"  class="form-control form-control-sm save-data lab-result-num input-decimal" size="15" maxlength="10" placeholder="Max Value">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3">Standard result: </td>
                  </tr>
                  <tr style="background-color:#BFFFFF;">
                    <td><i class="fa fa-male fa-lg" ></i> Male </td>
                    <td>
                      <input type="text" id="lab_result_min_male" data-title="Lab Minimum"  class="form-control form-control-sm save-data lab-result-num input-decimal" size="15" maxlength="10" placeholder="Male Min Value">
                    </td>
                    <td>
                      <input type="text" id="lab_result_max_male" data-title="Lab Maximum"  class="form-control form-control-sm save-data lab-result-num input-decimal" size="15" maxlength="10" placeholder="Male Max Value">
                    </td>
                  </tr>
                  <tr style="background-color:#EFBFFF;">
                    <td><i class="fa fa-female fa-lg" ></i> Female </td>
                    <td>
                      <input type="text" id="lab_result_min_female" data-title="Lab Minimum Female"  class="form-control form-control-sm save-data lab-result-num" size="15" maxlength="10" placeholder="Female Min Value">
                    </td>
                    <td>
                      <input type="text" id="lab_result_max_female" data-title="Lab Maximum Female"  class="form-control form-control-sm save-data lab-result-num" size="15" maxlength="10" placeholder="Female Max Value">
                    </td>
                  </tr>
                </tbody>
            </table>

            </div> <!-- div_lab_test_num-->

          </div>

          <div class="my-1  px-2 py-2" style="min-height: 150px; border:1px solid #ccc;">
            <i class="fa fa-file-invoice-dollar fa-lg text-danger" ></i> <b><u>Lab Cost</u></b>
            <button id="btn_add_lab_test_cost" class="btn btn-light" type="button">
             <i class="fa fa-plus fa-lg" ></i> ADD
            </button>
            <div>
              <table id="tbl_lab_test_cost_list" class="table table-bordered table-sm table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Laboratory</th>
                      <th>Turn Around Time<br><u>From</u></th>
                      <th>Turn Around Time<br><u>To</u></th>
                      <th>Cost(Baht)</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
              </table>

            </div>
          </div>
          <div class="my-1  px-2 py-2" style="min-height: 150px; border:1px solid #ccc;">
            <i class="fa fa-hand-holding-usd fa-lg text-success" ></i> <b><u>Lab Sale</u></b>
            <button id="btn_add_lab_test_sale" class="btn btn-light" type="button">
             <i class="fa fa-plus fa-lg" ></i> ADD
            </button>
            <div>
              <table id="tbl_lab_test_sale_list" class="table table-bordered table-sm table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Sale Option</th>
                      <th>Price (Baht)</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
              </table>

            </div>
          </div>

        </div><!-- col-sm-6 -->

        <div class="col-sm-6">

          <div class="my-2 px-2 py-2" style="background-color:#BFEFFF;">


            <div class="row my-1">
              <div class="col-sm-7">
                <b><u>Normal Range in Lab Report</u></b>
              </div>
              <div class="col-sm-5 text-right">
                <button class="btn btn-outline-secondary" type="button" onclick="openNormalRangeHist();">Normal Range History</button>
              </div>
            </div>

            <div class="row my-1 px-1 ">
              <div class="col-sm-4">
                <label for="lab_group_name">Start Date: (dd/mm/yyyy) </label>
                <input type="text" id="start_date" data-title="lab test start"  class="form-control form-control-sm save-data-normal-range" disabled>
              </div>
              <div class="col-sm-4">
                <label for="lab_group_name">Stop Date: (dd/mm/yyyy)</label>
                <input type="text" id="stop_date" data-title="lab test stop"  class="form-control form-control-sm " disabled>
              </div>
              <div class="col-sm-4 ">
                <label for="btn_normal_range">.</label>
                <button class="btn btn-primary form-control-sm form-control normal-range" id="btn_new_normal_range" type="button" onclick="newNormalRange1();"> New Normal Range</button>
                <div id="div_btn_normal_range" class="normal-range" style="display:none;">
                <button class="btn btn-success form-control-sm form-control" type="button" onclick="newNormalRange2();" > Next</button>
                <button class="btn btn-danger form-control-sm form-control" type="button" onclick="cancelNewNormalRange();" > Cancel</button>
                </div>
              </div>
            </div>

            <div>
              <label for="lab_std_male_txt">Male</label>
              <textarea id="lab_std_male_txt" rows="2"  data-title="Note" data-odata="" class="form-control save-data-normal-range" placeholder="Male Standard Result"></textarea>
            </div>
             <div>
              <label for="lab_std_female_txt">Female</label>
              <textarea id="lab_std_female_txt" rows="2"  data-title="Note" data-odata="" class="form-control save-data-normal-range" placeholder="Female Standard Result"></textarea>
            </div>

          </div>

          <div class="my-4">
            <label for="lab_note">Note:</label>
            <textarea id="lab_note" rows="4"  data-title="Note" class="form-control save-data" placeholder="Lab Test Note..."></textarea>
          </div>

        </div><!-- col-sm-6 -->

      </div>

    </div>


  </div><!-- cardbody -->

  <div class="card-footer ">
    <button type="button" id="btn_cancel_lab_test" class="btn btn-danger mx-1 float-right" > <i class="fa fa-times-circle fa-lg" ></i> Cancel</button>
    <button type="button" id="btn_save_lab_test" class="btn btn-success mx-1 float-right" > <i class="fa fa-save fa-lg" ></i> Save Data</button>
  </div>
</div>


<script>

var cur_date = changeToThaiDate("<? echo (new DateTime())->format('Y-m-d');?>");
var lab_result_type = "";
var is_new_normal_range = 0;
var row_amt_test_result = 0;
var row_amt_test_cost = 0;
var row_amt_test_sale = 0;
//var lst_delete_data = [];


$(document).ready(function(){

//  $(".input-right").keydown(function (event) {
  //$(".input-right").live('click', function (event) {


  $("#btn_save_lab_test").click(function(){
     saveLabTestData();
  }); // btn_search_test_menu

  $("#btn_save_lab_test").on("keypress",function (event) {
    if (event.which == 13) {
      saveLabTestData();
    }
  });

  $("#btn_cancel_lab_test").click(function(){

  }); // btn_new_test_menu
  $("#btn_close_lab_test_detail").click(function(){
     closeLabTestData();
  }); // btn_close_lab_test_detail

  $("#btn_add_lab_test_txt_result").click(function(){
     addNewRow_lab_test_result('', '', '', '');
  }); // btn_close_lab_test_detail
  $("#btn_add_lab_test_cost").click(function(){
     addNewRow_lab_test_cost('', '', '1','1', '9999');
  }); // btn_close_lab_test_detail
  $("#btn_add_lab_test_sale").click(function(){
     addNewRow_lab_test_sale('', '', '9999');
  }); // btn_close_lab_test_detail

  $("#lab_std_male_txt").focusin(function(){
     if($(this).val() == ""){
       if(lab_result_type == "num"){
         if($("#lab_result_min_male").val() !="" && $("#lab_result_max_male").val() !=""){
           $(this).val($("#lab_result_min_male").val()+" - "+$("#lab_result_max_male").val()+" "+$("#lab_unit").val());
         }
       }
       else if(lab_result_type == "txt"){
         var txt_result = "";
         $(".r_test_result").each(function(ix,objx){
            var row = $(objx).data("row");
            if($("#tr3_"+row).prop("checked") == true){
              txt_result += " "+$("#tr2_"+row).val();
            }
         });
         if(txt_result != ""){
           txt_result = txt_result.substring(1, txt_result.txt_result);
           $(this).val(txt_result);
         }
       }
     }
  }); // lab_std_male_txt


    $("#lab_std_female_txt").focusin(function(){
       if($(this).val() == ""){
         if(lab_result_type == "num"){
           if($("#lab_result_min_female").val() !="" && $("#lab_result_max_female").val() !=""){
             $(this).val($("#lab_result_min_female").val()+" - "+$("#lab_result_max_female").val()+" "+$("#lab_unit").val());
           }
         }
         else if(lab_result_type == "txt"){
           var txt_result = "";
           $(".r_test_result").each(function(ix,objx){
              var row = $(objx).data("row");
              if($("#tr3_"+row).prop("checked") == true){
                txt_result += " "+$("#tr2_"+row).val();
              }
           });
           if(txt_result != ""){
             txt_result = txt_result.substring(1, txt_result.txt_result);
             $(this).val(txt_result);
           }
         }
       }
    }); // lab_std_female_txt


    $(".input-text-code").on("keypress",function (event) {
      var ew = event.which;
      //console.log("keycode: "+ew);

      if(65 <= ew && ew <= 90)
          return true; // A-Z
      if(97 <= ew && ew <= 122)
          return true; // a-z
      if(48 <= ew && ew <= 57)
          return true; // 0-9
      if(ew == 95)
          return true; // underscore
      if(ew == 45)
          return true;// minus

      return false;
    });

});
function closeLabTestData(){
    showMenuTestDiv("list");
}

function addLabTest(choice){
  clearData_LabTest();
  u_mode_lab_test = "add_lab_test";
  lab_result_type = choice;
  is_new_normal_range = 1;

  $('#lab_test_group_title').html(cur_test_menu_title);


  $('.div-tbl-labtest').hide();
  if(lab_result_type == "txt"){
    $('#div_lab_test_txt').show();
  }
  else if(lab_result_type == "num"){
    $('#div_lab_test_num').show();
  }


// auto default add
//cost
addNewRow_lab_test_cost("LBT001", "IHRI", "1", "1", "9999");


addNewRow_lab_test_sale("S01", "ราคาขายสำหรับผู้มารับบริการทั่วไป", "9999");
addNewRow_lab_test_sale("R01", "ราคาขายสำหรับโครงการวิจัยประเภท 1", "9999");
addNewRow_lab_test_sale("R02", "ราคาขายสำหรับโครงการวิจัยประเภท 2", "9999");
addNewRow_lab_test_sale("S02", "ราคาขายสำหรับส่วนลดพนักงาน", "9999");
addNewRow_lab_test_sale("S03", "ราคาขายสำหรับส่วนลดครอบครัว", "9999");
addNewRow_lab_test_sale("S04", "ราคาขายสำหรับส่วนลดของญาติ/คนรู้จัก/บุคคลอ้างอิง", "9999");


// init normal range txt

$('#start_date').val(cur_date);


  showMenuTestDiv("detail");
  $('#lab_id2').val("ADD NEW");
  $('#lab_id').prop("disabled", false);
  $('#lab_id').focus();

}

function newNormalRange1(){
  $('.normal-range').hide();
  $('#div_btn_normal_range').show();

   $('#stop_date').prop("disabled", false);
   $('#stop_date').notify("Check stop date and click NEXT.", "info");
   $('#stop_date').val(cur_date);
   $('#stop_date').focus();

}
function cancelNewNormalRange(){
  $('.normal-range').hide();
  $('#btn_new_normal_range').show();
  $('#stop_date').val("");
  $('#stop_date').prop("disabled", true);

}

function newNormalRange2(){

  if(validateDate($('#stop_date').val())){
    var stopDate = changeToEnDate($('#stop_date').val());
    var startDate = changeToEnDate($('#start_date').val());

    var diff = dateDiffCal(
    new Date(startDate),
    new Date(stopDate)
    );

    if(diff < 1){
      $("#stop_date").notify("Invalid stop date, Stop date must more than start date.", "error");
    }
    else{
      var aData = {
          u_mode:"update_normal_range",
          id:cur_lab_test_id,
          start_date:startDate,
          stop_date:stopDate
      };
      save_data_ajax(aData,"lab/db_lab_test.php",updateNormalRange_Complete);
    }
  }// validateDate
  else{
    $("#stop_date").notify("Invalid stop date, Please check.", "error");
  }

}//newNormalRange2

function updateNormalRange_Complete(flagSave, rtnDataAjax, aData){
  if(flagSave){
    var stopDate = changeToEnDate($('#stop_date').val());
    var startDate = changeToEnDate($('#start_date').val());
    $('#start_date').val(changeToThaiDate(rtnDataAjax.new_start_date));

    $('.normal-range').hide();
    $('#btn_new_normal_range').show();
    $('#stop_date').val("");
    $('#stop_date').prop("disabled", true);

    alert("Please insert new Normal Range!");
    $('#lab_std_male_txt').notify("Please insert new Normal Range!", "info");
    $('#lab_std_female_txt').notify("Please insert new Normal Range!", "info");

  }
}


/*
function initNormalRange(){
  if(u_mode_lab_test == "add_lab_test"){

  }
  else if (u_mode_lab_test == "update_lab_test"){

  }
}
*/
function openLabTest(lab_id, choice){
  clearData_LabTest();
  u_mode_lab_test = "update_lab_test";
  lab_result_type = choice;

  $('.div-tbl-labtest').hide();
  if(lab_result_type == "txt"){
    $('#div_lab_test_txt').show();
  }
  else if(lab_result_type == "num"){
    $('#div_lab_test_num').show();
  }

  var aData = {
      u_mode:"select_lab_test_detail",
      id:lab_id,
      result_type:lab_result_type
  };
  save_data_ajax(aData,"lab/db_lab_test.php",openLabTest_Complete);
}

function openLabTest_Complete(flagSave, rtnDataAjax, aData){
  if(flagSave){

    var dataObj = rtnDataAjax.data;
    $('#lab_id2').val(dataObj.lab_id2);
    $('#lab_id').val(dataObj.lab_id);
    $('#lab_name').val(dataObj.lab_name);
    $('#lab_name_report').val(dataObj.lab_name_report);
    $('#lab_note').val(dataObj.lab_note);
    $('#lab_unit').val(dataObj.lab_unit);
    cur_lab_test_id = dataObj.lab_id;
    cur_test_menu_id = dataObj.lab_group_id;
    cur_test_menu_title = dataObj.lab_group_name;
    $('#lab_test_group_title').html(cur_test_menu_title);
    $('#lab_id').prop("disabled", true);


    if(aData.result_type == "txt"){
      var datalist_result_txt = rtnDataAjax.datalist_result_txt;
      datalist_result_txt.forEach(function (itm) {
          addNewRow_lab_test_result(itm.lab_txt_id, itm.lab_txt_name, itm.is_normal, itm.lab_txt_seq);
      });
    }
    else if(aData.result_type == "num"){
      $('#lab_result_min').val(dataObj.lab_result_min);
      $('#lab_result_max').val(dataObj.lab_result_max);
      $('#lab_result_min_male').val(dataObj.lab_result_min_male);
      $('#lab_result_max_male').val(dataObj.lab_result_max_male);
      $('#lab_result_min_female').val(dataObj.lab_result_min_female);
      $('#lab_result_max_female').val(dataObj.lab_result_max_female);
    }


    var datalist_normal_range = rtnDataAjax.datalist_normal_range;
    datalist_normal_range.forEach(function (itm) {
        $("#start_date").val(changeToThaiDate(itm.start_date));
        $("#lab_std_male_txt").val(itm.lab_std_male_txt);
        $("#lab_std_female_txt").val(itm.lab_std_female_txt);

        $("#lab_std_male_txt").data("odata", itm.lab_std_male_txt);
        $("#lab_std_female_txt").data("odata", itm.lab_std_female_txt);
    });

    var datalist_cost = rtnDataAjax.datalist_cost;
    datalist_cost.forEach(function (itm) {
        addNewRow_lab_test_cost(itm.laboratory_id, itm.laboratory_name, itm.lab_turnaround_from, itm.lab_turnaround_to, itm.lab_cost);
    });
    var datalist_sale = rtnDataAjax.datalist_sale;
    datalist_sale.forEach(function (itm) {
        addNewRow_lab_test_sale(itm.sale_opt_id, itm.sale_opt_name, itm.lab_price);
    });



    showMenuTestDiv("detail");
    $('#lab_name').focus();


  }
}

function clearData_LabTest(){
  cur_lab_test_id = "";
  is_new_normal_range = 0; // 0: not new normal range/ 1:new normal range
  row_amt_test_result = 0;
  row_amt_test_cost = 0;
  row_amt_test_sale = 0;
  lst_delete_data = [];

  $('.r_test_result').remove();
  $('.r_test_cost').remove();
  $('.r_test_sale').remove();
  $('#div_lab_test_detail .save-data').val("");
  $('#div_lab_test_detail .save-data-normal-range').val("");
}



function addNewRow_lab_test_result(id, name, is_normal, seq_no){
  row_amt_test_result +=1;

  var add_new = "";
  if(id == "") add_new = "add-new";

  var txt_row = '<tr class="r_test_result '+add_new+'" id="rtr'+row_amt_test_result+'" data-row="'+row_amt_test_result+'"  >' ;
  if(id == ''){ // add new data
    txt_row += '<td>';
    txt_row += '<input type="text" id="tr1_'+row_amt_test_result+'" data-id="" data-tbl="tbl_lab_test_txt_result" class="data-id  btn-lab-list"  placeholder="New ID" size="10" size="10" maxlength="10" value="" onfocusout="checkDuplicateTextID(\'tr1_'+row_amt_test_result+'\');">';
    txt_row += '</td>';
  }
  else{ // update data
    txt_row += '<td>';
    txt_row += '<input type="text" id="tr1_'+row_amt_test_result+'" data-id="'+id+'" class="data-id" placeholder="Result ID" size="10" size="10" value="'+id+'" disabled>';
    txt_row += '</td>';
  }
  txt_row += '<td>';
  txt_row += '<input type="text" id="tr2_'+row_amt_test_result+'" placeholder="Result Desc." value="'+name+'">';
  txt_row += '</td>';

  txt_row += '<td>';
  txt_row += '<input type="checkbox" id="tr3_'+row_amt_test_result+'" />';
  txt_row += '</td>';

  txt_row += '<td>';
  txt_row += '<button class="btn btn-danger" type="button" onclick="deleteRowData(\'rtr'+row_amt_test_result+'\', \'tr1_'+row_amt_test_result+'\',\'result_txt\' );"><i class="fa fa-times fa-lg" ></i></button>';
  txt_row += '</td>';

  txt_row += '</tr">';
  $("#tbl_lab_test_txt_result tbody").append(txt_row);
  if(is_normal == '1') $("#tr3_"+row_amt_test_result).prop("checked", true);
}



function addNewRow_lab_test_cost(laboratory_id, laboratory_name, turnaround_from, turnaround_to, cost_amt){

  row_amt_test_cost +=1;
  var add_new = "";
  if(laboratory_id == "") add_new = "add-new";

  var txt_row = '<tr class="r_test_cost '+add_new+'" id="rtc'+row_amt_test_cost+'" data-row="'+row_amt_test_cost+'"  >' ;
  if(laboratory_id == ''){ // add new data
  txt_row += '<td>';
  txt_row += '<div class="input-group mb-3">';
  txt_row += '  <div class="input-group-prepend">';
  txt_row += '    <button class="btn btn-primary btn-lab-list" type="button" onclick="openSettingDlgSelect(\'laboratory\', \'tc1_'+row_amt_test_cost+'\');"> <i class="fa fa-hand-pointer fa-lg" ></i></button>';
  txt_row += '  </div>';
  txt_row += '  <input type="text" id="tc1_'+row_amt_test_cost+'" data-id="" data-tbl="tbl_lab_test_cost_list" class="form-control form-control-sm data-id"  placeholder="Select" value="" size="50" disabled>';
  txt_row += '</div>';
  txt_row += '</td>';
//txt_row += '<td width=45%><a class="text-success" id="tc1_'+row_amt_test_cost+'" data-id="" href="javascript:void(0)"  onclick="openDlgSpecimen('+row_amt_test_cost+');">-Select Specimen-</a></td>';

  }
  else{ // update data

  txt_row += '<td width=45%><span class="text-primary data-id" id="tc1_'+row_amt_test_cost+'" data-id="'+laboratory_id+'">'+laboratory_name+'</span></td>';
  }


  txt_row += '<td>';
  txt_row += '<select id="tc2_'+row_amt_test_cost+'" onchange="setTurnaroundTime('+row_amt_test_cost+');">';
  txt_row += '<option value="1"> 1 Hr. </option>';
  txt_row += '<option value="2"> 2 Hrs. </option>';
  txt_row += '<option value="3"> 3 Hrs. </option>';
  txt_row += '<option value="4"> 4 Hrs. </option>';
  txt_row += '<option value="24"> 1 Day (24 Hrs.) </option>';
  txt_row += '<option value="48"> 2 Days (48 Hrs.) </option>';
  txt_row += '<option value="72"> 3 Days (72 Hrs.) </option>';
  txt_row += '<option value="96"> 4 Days (96 Hrs.) </option>'; 
  txt_row += '<option value="120"> 5 Days (120 Hrs.) </option>';
  txt_row += '<option value="144"> 6 Days (144 Hrs.) </option>';
  txt_row += '<option value="168"> 7 Days (168 Hrs.) </option>';
  txt_row += '<option value="192"> 8 Days (192 Hrs.) </option>';
  txt_row += '<option value="264"> 11 Days (264 Hrs.) </option>';
  txt_row += '<option value="336"> 2 Weeks (336 Hrs.) </option>';
  txt_row += '<option value="504"> 3 Weeks (504 Hrs.) </option>';
  txt_row += '<option value="384"> 16 Days (384 Hrs.) </option>';
  txt_row += '<select">';
  txt_row += '</td>';

  txt_row += '<td>';
  txt_row += '<select id="tc3_'+row_amt_test_cost+'">';
  txt_row += '<option value="1"> 1 Hr. </option>';
  txt_row += '<option value="2"> 2 Hrs. </option>';
  txt_row += '<option value="3"> 3 Hrs. </option>';
  txt_row += '<option value="4"> 4 Hrs. </option>';
  txt_row += '<option value="24"> 1 Day (24 Hrs.) </option>';
  txt_row += '<option value="48"> 2 Days (48 Hrs.) </option>';
  txt_row += '<option value="72"> 3 Days (72 Hrs.) </option>';
  txt_row += '<option value="96"> 4 Days (96 Hrs.) </option>';
  txt_row += '<option value="120"> 5 Days (120 Hrs.) </option>';
  txt_row += '<option value="144"> 6 Days (144 Hrs.) </option>';
  txt_row += '<option value="168"> 7 Days (168 Hrs.) </option>';
  txt_row += '<option value="192"> 8 Days (192 Hrs.) </option>';
  txt_row += '<option value="264"> 11 Days (264 Hrs.) </option>';
  txt_row += '<option value="336"> 2 Weeks (336 Hrs.) </option>';
  txt_row += '<option value="504"> 3 Weeks (504 Hrs.) </option>';
  txt_row += '<option value="384"> 16 Days (384 Hrs.) </option>';
  txt_row += '<select">';
  txt_row += '</td>';

  txt_row += '<td>';
  txt_row += '<input type="text" id="tc4_'+row_amt_test_cost+'" placeholder="Cost (Baht)" size="10" value="'+cost_amt+'" class="input-right input-decimal" data-digit="2">';
  txt_row += '</td>';

  txt_row += '<td>';
  txt_row += '<button class="btn btn-danger" type="button" onclick="deleteRowData(\'rtc'+row_amt_test_cost+'\', \'tc1_'+row_amt_test_cost+'\',\'sale_cost\' );"><i class="fa fa-times fa-lg" ></i></button>';
  txt_row += '</td>';

  txt_row += '</tr">';
  $("#tbl_lab_test_cost_list tbody").append(txt_row);
  $("#tc2_"+row_amt_test_cost).val(turnaround_from);
  $("#tc3_"+row_amt_test_cost).val(turnaround_to);

}

function setTurnaroundTime(row_id){ // set turnaround to value (turnaround from)
   $("#tc3_"+row_id).val($("#tc2_"+row_id).val());
}

function addNewRow_lab_test_sale(sale_opt_id, sale_opt_name, sale_amt){

  row_amt_test_sale +=1;
  var add_new = "";
  if(sale_opt_id == "") add_new = "add-new";

  var txt_row = '<tr class="r_test_sale '+add_new+'" id="rts'+row_amt_test_sale+'" data-row="'+row_amt_test_sale+'"  >' ;
  if(sale_opt_id == ''){ // add new data
  txt_row += '<td>';
  txt_row += '<div class="input-group mb-3">';
  txt_row += '  <div class="input-group-prepend">';
  txt_row += '    <button class="btn btn-primary btn-lab-list" type="button" onclick="openSettingDlgSelect(\'sale_option\', \'ts1_'+row_amt_test_sale+'\');"> <i class="fa fa-hand-pointer fa-lg" ></i></button>';
  txt_row += '  </div>';
  txt_row += '  <input type="text" id="ts1_'+row_amt_test_sale+'" data-id="" data-tbl="tbl_lab_test_sale_list" class="form-control form-control-sm data-id" placeholder="Select" value="" size="50" disabled>';
  txt_row += '</div>';
  txt_row += '</td>';
//txt_row += '<td width=45%><a class="text-success" id="tc1_'+row_amt_test_cost+'" data-id="" href="javascript:void(0)"  onclick="openDlgSpecimen('+row_amt_test_cost+');">-Select Specimen-</a></td>';

  }
  else{ // update data

  txt_row += '<td width=45%><span class="text-primary data-id" id="ts1_'+row_amt_test_sale+'" data-id="'+sale_opt_id+'" "  ">'+sale_opt_name+'</span></td>';
  }

  txt_row += '<td>';
  txt_row += '<input type="text" id="ts2_'+row_amt_test_sale+'"  class="input-right input-decimal" data-digit="2" placeholder="Price (Baht)" size="10" value="'+sale_amt+'"  ">';
  txt_row += '</td>';

  txt_row += '<td>';
  txt_row += '<button class="btn btn-danger" type="button" onclick="deleteRowData(\'rts'+row_amt_test_sale+'\', \'ts1_'+row_amt_test_sale+'\',\'sale_price\' );"><i class="fa fa-times fa-lg" ></i></button>';
  txt_row += '</td>';

  txt_row += '</tr">';
  $("#tbl_lab_test_sale_list tbody").append(txt_row);

}


function deleteRowData(row_id, row_component_id, tblName){
  if($('#'+row_id).hasClass("add-new")){
    $('#'+row_id).remove();
  }
  else{
    var result = confirm("Are you sure to delete this data ?");
    //var result = confirm("ท่านต้องการลบข้อมูล "+$('#'+row_component_id).val()+" นี้ใช่หรือไม่ ?");
    if (result) {
      if($('#'+row_id).data("id") != ""){ // there is data id
        //lst_data_delete[] = $('#'+row_id).data("id");
          lst_delete_data.push({tbl_name:tblName, id:$('#'+row_component_id).data("id")});
      }
      $('#'+row_id).remove();
    }
  }
}//deleteRowData

function saveLabTestData(){
 //console.log("save data");
  //$("#btn_save_lab_test").prop("disabled", true);
  var divSaveData = "div_lab_test_detail";
  var flag_valid = true;
  if(validateInput(divSaveData)){

   var lstDataObj = [];
   var lstDataObj_update_test_result= [];
   var lstDataObj_update_test_cost= [];
   var lstDataObj_update_test_sale= [];
   var lstDataObj_update_normal_range= [];

   var lstDataList = [];
   // text result type

   lstDataObj.push({name:"lab_result_type", value:lab_result_type});
   lstDataObj.push({name:"lab_group_id", value:cur_test_menu_id});

   //check first alphabet in lab_id is string
   var first_str_id = $("#lab_id").val().substring(0, 1);
   if(!first_str_id.match(/^[A-Za-z]+$/)){
     $("#lab_id").notify("First alphabet in Lab ID must be A-Z or a-z", "error");
     flag_valid = false;
   }


   if(lab_result_type == "txt"){
     $("#"+divSaveData +" .save-data").not("lab-result-num").each(function(ix,objx){
        lstDataObj.push({name:$(objx).attr("id"), value:$(objx).val()});
     });

     $("#"+divSaveData +" .r_test_result").each(function(ix,objx){
        var row = $(objx).data("row");
        var arr_obj = [];
        if($("#tr1_"+row).data("id") != ""){
          arr_obj.push({name:"lab_txt_id", value:$("#tr1_"+row).data("id")});
          arr_obj.push({name:"lab_txt_name", value:$("#tr2_"+row).val()});
          arr_obj.push({name:"is_normal", value:($("#tr3_"+row).prop("checked") == true)?"1":"0"});
          lstDataObj_update_test_result.push(arr_obj);
        }
        else{
           $("#tr1_"+row).notify("Please select this value", "error");
           flag_valid = false;
        }

     });
   }
   else if(lab_result_type == "num"){
     $("#"+divSaveData +" .save-data").each(function(ix,objx){
        lstDataObj.push({name:$(objx).attr("id"), value:$(objx).val()});
     });

     if(Number($("#lab_result_max").val()) < Number($("#lab_result_min").val())){
       flag_valid = false;
       $("#lab_result_max").notify("Max value can not be less than Min value","error");
     }
     if(Number($("#lab_result_max_male").val()) < Number($("#lab_result_min_male").val())){
       flag_valid = false;
       $("#lab_result_max_male").notify("Max value can not be less than Min value","error");
     }
     if(Number($("#lab_result_max_female").val()) < Number($("#lab_result_min_female").val())){
       flag_valid = false;
       $("#lab_result_max_female").notify("Max value can not be less than Min value","error");
     }

/*
     if(Number($("#lab_result_max_male").val()) > Number($("#lab_result_max").val())){
       flag_valid = false;
       $("#lab_result_max_male").notify("Max value can not be more than Possible Max value","error");
     }
     if(Number($("#lab_result_max_female").val()) > Number($("#lab_result_max").val())){
       flag_valid = false;
       $("#lab_result_max_female").notify("Max value can not be more than Possible Max value","error");
     }

     if(Number($("#lab_result_min_male").val()) < Number($("#lab_result_min").val())){
       flag_valid = false;
       $("#lab_result_min_male").notify("Min value can not be more than Possible Min value","error");
     }
     if(Number($("#lab_result_min_female").val()) < Number($("#lab_result_min").val())){
       flag_valid = false;
       $("#lab_result_min_female").notify("Min value can not be more than Possible Min value","error");
     }

     */

   }// num



   $("#"+divSaveData +" .r_test_cost").each(function(ix,objx){
      var row = $(objx).data("row");
      var arr_obj = [];
      if($("#tc1_"+row).data("id") != ""){
        arr_obj.push({name:"laboratory_id", value:$("#tc1_"+row).data("id")});
        arr_obj.push({name:"lab_turnaround_from", value:$("#tc2_"+row).val()});
        arr_obj.push({name:"lab_turnaround_to", value:$("#tc3_"+row).val()});
        arr_obj.push({name:"lab_cost", value:$("#tc4_"+row).val()});
        lstDataObj_update_test_cost.push(arr_obj);
      }
      else{
         $("#tc1_"+row).notify("Please select this value", "error");
         flag_valid = false;
      }


   });

   $("#"+divSaveData +" .r_test_sale").each(function(ix,objx){
      var row = $(objx).data("row");
      var arr_obj = [];
      if($("#ts1_"+row).data("id") != ""){
        arr_obj.push({name:"sale_opt_id", value:$("#ts1_"+row).data("id")});
        arr_obj.push({name:"lab_price", value:$("#ts2_"+row).val()});
        lstDataObj_update_test_sale.push(arr_obj);
      }
      else{
         $("#ts1_"+row).notify("Please select this value", "error");
         flag_valid = false;
      }
   });

   // normal Range

   if($("#lab_std_male_txt").val() != $("#lab_std_male_txt").data("odata") ||
      $("#lab_std_female_txt").val() != $("#lab_std_female_txt").data("odata")
    ){
      is_new_normal_range = 1;
    }

   if(is_new_normal_range == 1){ // add new normal range
     var arr_obj = [];
     arr_obj.push({name:"start_date", value:changeToEnDate($("#start_date").val())});
     arr_obj.push({name:"lab_std_male_txt", value:$("#lab_std_male_txt").val()});
     arr_obj.push({name:"lab_std_female_txt", value:$("#lab_std_female_txt").val()});
     arr_obj.push({name:"stop_date", value:"2100-01-01"});

     lstDataObj_update_normal_range.push(arr_obj);
   }

   if(flag_valid){
     var lstDataList = {
               update_lab_test_result: lstDataObj_update_test_result,
               update_lab_cost: lstDataObj_update_test_cost,
               update_lab_sale: lstDataObj_update_test_sale,
               update_normal_range:lstDataObj_update_normal_range,
               delete_list: lst_delete_data
     };

      var aData = {
                u_mode:u_mode_lab_test,
                result_type:lab_result_type,
                id:$("#lab_id").val(),
                lst_data_obj: lstDataObj,
                lst_data_list: lstDataList
      };

      save_data_ajax(aData,"lab/db_lab_test.php",saveLabTestDataComplete);
   }
   else{ // flag_valid = false
     $.notify("Incomplete Data", "error");
   }


  }
  else{
    $("#btn_save_lab_test").prop("disabled", false);
  }

}

function saveLabTestDataComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
   if(u_mode_lab_test == "add_lab_test"){
     u_mode_lab_test = "update_lab_test";
     $('#lab_id2').val(rtnDataAjax.id);
     cur_lab_test_id = $('#lab_id').val();
     $('#lab_id').prop("disabled", true);

     var divSaveData = "div_lab_test_detail";
     $("#"+divSaveData +" .btn-lab-list").each(function(ix,objx){
        $(objx).prop("disabled", true);
        if($(objx).hasClass("btn-primary")){
          $(objx).removeClass("btn-primary");
          $(objx).addClass("btn-secondary");
        }
     });
     $.notify("Insert Lab Test successfully.","info");

     // addrow in list
   }
   else{ // update lab_test
     $.notify("Update Lab Test successfully.", "info");
     $('#r'+$('#lab_id').val()).remove();
   }

   addRowData_LabTest($('#lab_id').val(),
   $('#lab_name').val(),
   cur_test_menu_title,
   lab_result_type);

   $("#lab_std_male_txt").data("odata", $("#lab_std_male_txt").val());
   $("#lab_std_female_txt").data("odata", $("#lab_std_female_txt").val());

   $('.r_test_result').removeClass("add-new");
   $('.r_test_cost').removeClass("add-new");
   $('.r_test_sale').removeClass("add-new");

  }

  $("#btn_save_lab_test").prop("disabled", false);
}

function openNormalRangeHist(){
  var link = "dlg_normal_range_hist.php";
  $("#div_modal_select_detail").html("");
  $("#div_modal_select_detail").load("lab/"+link, function(){
      $('#modal_select').modal('show');
  });
}



</script>
