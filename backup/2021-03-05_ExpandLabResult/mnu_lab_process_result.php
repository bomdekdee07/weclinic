

<div class="card" id="div_lab_process_result">
  <div class="card-header" style="max-height: 3rem; background-color:#eee;">
      <div class="row ">
         <div class="col-sm-3">
           <h5><i class="fa fa-clipboard-list fa-lg" aria-hidden="true"></i> <b>Lab Result</b></h5>

         </div>

         <div class="col-sm-6">

         </div>

         <div class="col-sm-3">
            Total : <b><span id="ttl_result_list"></span></b>
         </div>
      </div>
  </div>
  <div class="card-body" style="min-height: 300px; ">
    <table id="tbl_lab_prcess_result" class="table table-bordered table-sm table-striped table-hover">
        <thead>
          <tr>
            <th>Barcode : Specimen</th>
            <th>Lab Test</th>
            <th>Result</th>
            <th>Result in Report</th>
            <th>Normal?</th>
            <th>Normal Range</th>
            <th>Note</th>
          </tr>
        </thead>
        <tbody>

        </tbody>

    </table>

  </div><!-- cardbody -->


</div>


<script>

var cur_txt_result = [];
var is_confirm_lab = 0;

$(document).ready(function(){


});

function openResult(labSerialNo){
  var aData = {
      u_mode:"select_lab_process_result",
      lab_serial_no:labSerialNo
  };
  save_data_ajax(aData,"lab/db_lab_process.php",openResult_complete);
}

function openResult_complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    choice_lab_process = "lab_result";
    setLabProcessData(rtnDataAjax.data_lab_process[0]);
    showLabProcessDiv("detail");
    cur_lab_serial_no = aData.lab_serial_no;
    cur_txt_result = rtnDataAjax.datalist_result_choice;
    /*
    for (x in cur_txt_result) {
               console.log("enter"+"col:"+x+" / "+cur_txt_result[x]);
                for (k in cur_txt_result[x]) {
                    console.log("enter2"+"col:"+k+" / "+cur_txt_result[x][k]);
                }
                //console.log("col:"+x+" / "+rs_choice[x]);
    }
*/

    $('.r_lab_result').remove(); // row data list
    var txt_row="";
    $('#ttl_result_list').html(rtnDataAjax.datalist_result.length);

    if(rtnDataAjax.datalist_result.length > 0){

      var datalist = rtnDataAjax.datalist_result;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_LabProcess_result(
              dataObj.uid,dataObj.collect_date, dataObj.collect_time, dataObj.barcode,
              dataObj.specimen_id,dataObj.specimen_name,
              dataObj.lab_id2, dataObj.lab_id, dataObj.lab_name,
              dataObj.lab_result,dataObj.lab_result_type, dataObj.lab_result_report, dataObj.lab_result_note,
              dataObj.lab_unit, dataObj.lab_result_status, dataObj.min, dataObj.max,
              dataObj.lab_std_txt
            );
        }//for

        $('#ttl_result_list').html(rtnDataAjax.datalist_result.length);
        $('#div_lab_process_note').show();

    }
    else{
      $.notify("No record found.", "info");
    }
  }

}



function addRowData_LabProcess_result(
  uid,collect_date, collect_time, barcode,specimen_id, specimen_name,
  lab_id2, lab_id, lab_name, lab_result, lab_result_type, lab_result_report, lab_result_note, lab_unit,
  lab_result_status, lab_min_val, lab_max_val, normal_range
){

  var row_id = barcode+specimen_id+lab_id2;

  lab_result = (lab_result == null)?"":lab_result;
  lab_result_report = (lab_result_report == null)?"":lab_result_report;
  lab_result_note = (lab_result_note == null)?"":lab_result_note;

  var txt_input_result = "";
  if(lab_result_type == "txt"){

    if(typeof cur_txt_result[lab_id2]  !== 'undefined'){
      txt_input_result = "<select id='rs_"+row_id+"' class='result-txt' onchange='setResultReport(\""+row_id+"\");'><option value=''>Select</option>";
      for (k in cur_txt_result[lab_id2]) {
          var res = cur_txt_result[lab_id2][k].split("|"); // result choice | is_normal (1=normal, 0= abnormal)
          txt_input_result += "<option value='"+k+"' data-id='"+res[1]+"'>"+res[0]+"</option>";
          //alert("enter2"+"col:"+k+" / "+cur_txt_result[lab_id2][k]);
      }
      txt_input_result += "</select>";
    }
  }
  else{
    txt_input_result = "<input type='text' class='result-txt input-decimal' id='rs_"+row_id+"' size='15' value='"+lab_result+"' onfocusout='autoFillLabReport(\""+row_id+"\");' placeholder='Lab Resultsss'>";
  }


  var txt_row = '<tr class="r_lab_result" id="'+row_id+'" ' ;
  txt_row += ' data-uid="'+uid+'" data-collect_date="'+collect_date+'" data-collect_time="'+collect_time+'" ';
  txt_row += ' data-specimen_id="'+specimen_id+'" data-lab_id="'+lab_id+'" data-unit="'+lab_unit+'"  data-r_type="'+lab_result_type+'" ';
  txt_row += ' data-min="'+lab_min_val+'" data-max="'+lab_max_val+'" data-barcode="'+barcode+'" ';
  txt_row += ' >';

  txt_row += '<td >['+uid+'] '+barcode +' <br> <b><span class="text-primary">'+ specimen_name+'</span></b></td>';
  txt_row += '<td >'+lab_name+'<br><small>('+ lab_unit+')</small></td>';
  txt_row += '<td >';
  txt_row += txt_input_result;
  txt_row += '</td>';

  txt_row += '<td >';
  txt_row += "<input type='text' class='result-report-txt' id='rsr_"+row_id+"' size='20'  placeholder='Lab Report' value='"+lab_result_report+"'>";
//  txt_row += '<textarea id="rsn_'+row_id+'" rows="1"  class="form-control form-control-sm" placeholder="Result Note></textarea>';
  txt_row += '</td>';
  txt_row += '<td ><select id="rss_'+row_id+'"><option value="L0">Pending</option><option value="L1">Yes</option><option value="L2">No</option></select></td>';
  txt_row += '<td ><span class="text-success">'+normal_range+'</span></td>';
  txt_row += '<td >';
  //txt_row += "<input type='text' id='rsn_"+row_id+"' size='20'>";
  txt_row += '<textarea id="rsn_'+row_id+'" rows="1"  class="form-control form-control-sm" placeholder="Result Note">'+lab_result_note+'</textarea>';
  txt_row += '</td>';


  txt_row += '</tr">';

  $("#tbl_lab_prcess_result tbody").append(txt_row);
  if(lab_result_type == "txt"){ // set value to dropdown
    $("#rs_"+row_id).val(lab_result);
    //console.log(row_id+"/"+lab_result);
  }

  $("#rss_"+row_id).val(lab_result_status); // is normal?

}

// set result for lab txt type when focus in lab result report
function setResultReport(rowID){
  if($("#rs_"+rowID).val() != "")
  $("#rsr_"+rowID).val($("#rs_"+rowID+" option:selected").text());
  else{
    $("#rsr_"+rowID).val("");
  }

// select is normal?
  if($("#"+rowID).find(':selected').data('id') == 1){ // normal range
    $("#rss_"+rowID).val("L1");
  }
  else if($("#"+rowID).find(':selected').data('id') == 0){ // not normal range
    $("#rss_"+rowID).val("L2");
  }
  else{ // pending confirm normal?
    $("#rss_"+rowID).val("L0");
  }

}

// set result for lab num type when focus in lab result report
function autoFillLabReport(rowID){
//  console.log("autofill "+rowID+" / "+$("#"+rowID).data("r_type"));
  if($("#"+rowID).data("r_type") == "num"){
    if($("#rs_"+rowID).val().trim() != ""){
      $("#rsr_"+rowID).val($("#rs_"+rowID).val().trim()+" "+$("#"+rowID).data("unit"));

      if(!Number.isNaN($("#"+rowID).data("min")) &&
         !Number.isNaN($("#"+rowID).data("max")) &&
         !Number.isNaN($("#rs_"+rowID).val())
       ){
        var min = parseFloat($("#"+rowID).data("min"));
        var max = parseFloat($("#"+rowID).data("max"));
        var rs_val = parseFloat($("#rs_"+rowID).val());
  //console.log(min+"/"+max+"/"+rs_val);
        if((rs_val < min) || (rs_val > max) ){ // not normal
        //  console.log("value : "+rs_val+" ("+min+"/"+max+")");
          $("#rss_"+rowID).val("L2"); // NO (not normal)
          if(rs_val < min) $("#rsn_"+rowID).val("L");
          else if(rs_val > max) $("#rsn_"+rowID).val("H");
        }
        else{ // normal
          $("#rss_"+rowID).val("L1"); // Yes (normal)
        }
      }


    }
    else{
      $("#rsr_"+rowID).val("");
      $("#rss_"+rowID).val("L0");
    }


  }
}//autoFillLabReport


function saveLabResultData(){
  is_confirm_lab = 0;
  saveLabResult();
}
function confirmLabResultData(){
  is_confirm_lab = 1;
  saveLabResult();
}



function saveLabResult(){
  var flag_valid = 1;
  var lst_data = [];
  $("#tbl_lab_prcess_result .r_lab_result").each(function(ix,objx){
     var row_id = $(objx).attr("id");
     var str = $("#rs_"+row_id).val()+$("#rsr_"+row_id).val()+$("#rsn_"+row_id).val();
  //  console.log("str: "+str);
     if(str.length > 0){ // chk record has data to save
       var arr_obj = [];
       arr_obj.push({name:"uid", value:$(objx).data("uid")});
       arr_obj.push({name:"collect_date", value:$(objx).data("collect_date")});
       arr_obj.push({name:"collect_time", value:$(objx).data("collect_time")});
       arr_obj.push({name:"lab_id", value:$(objx).data("lab_id")});
       arr_obj.push({name:"barcode", value:$(objx).data("barcode")});
       arr_obj.push({name:"lab_result", value:$("#rs_"+row_id).val()});
       arr_obj.push({name:"lab_result_report", value:$("#rsr_"+row_id).val()});
       arr_obj.push({name:"lab_result_note", value:$("#rsn_"+row_id).val()});
       arr_obj.push({name:"lab_result_status", value:$("#rss_"+row_id).val()});
       lst_data.push(arr_obj); // lab result obj
     }

  });

  if(lst_data.length > 0){
    var aData = {
        u_mode:"save_lab_result",
        lab_serial_no:cur_lab_serial_no,
        lst_data_result:lst_data
    };
    save_data_ajax(aData,"lab/db_lab_process.php",saveLabResultComplete);
  }
  else{
    $.notify("No lab result to save", "info");
  }


}

function saveLabResultComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $.notify("Save lab result successfully.", "info");
    if(is_confirm_lab == 1){
      confirmLabResult();
    }
  }
}

function confirmLabResult(){
  var flag_valid = 1;
  var lst_data = [];
  $("#tbl_lab_prcess_result .result-txt").each(function(ix,objx){
     if($(objx).val().trim() == ""){
       flag_valid = 0;
       $(objx).notify("Incomplete Lab Result", "warning");
     }
  });
  $("#tbl_lab_prcess_result .result-report-txt").each(function(ix,objx){
     if($(objx).val().trim() == ""){
       flag_valid = 0;
       $(objx).notify("Incomplete Lab Report Result", "warning");
     }
  });

  if(flag_valid == 1){
    var aData = {
        u_mode:"confirm_lab_result",
        lab_serial_no:cur_lab_serial_no
    };
    save_data_ajax(aData,"lab/db_lab_process.php",confirmLabResultComplete);
  }
  else{
    $.notify("Incomplete Lab Result to confirm", "error");
  }


}

function confirmLabResultComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $.notify("Confirmed lab result successfully.", "info");
    $("#btn_check_lab_result").hide();
    $("#btn_save_lab_result").hide();
    $("#txt_time_complete").val(rtnDataAjax.time_lab_confirm);

  }
}



</script>
