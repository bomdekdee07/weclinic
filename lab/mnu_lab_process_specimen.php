

<div class="card" id="div_lab_process_specimen">
  <div class="card-header  " style="max-height: 3rem; background-color:#eee;">
      <div class="row ">
         <div class="col-sm-5">
           <h5><i class="fa fa-vials fa-lg" aria-hidden="true"></i> <b>Specimen List</b>            <button type="button" id="btn_load_specimen_check" class="btn btn-outline-secondary btn-sm mx-1 lab-process-specimen" > <i class="fa fa-sync fa-lg" ></i> Load Specimens</button>
</h5>

         </div>

         <div class="col-sm-6">
           <div style="display:none">
           <input type="text" id="txt_specimen_barcode" size="20" placeholder="กรอก Barcode ที่เก็บไว้">
           <button type="button" id="btn_load_specimen_check_from_barcode" class="btn btn-outline-primary btn-sm mx-1 lab-process-specimen" > <i class="fa fa-barcode fa-lg" ></i> Load Specimens </button>
           </div>
         </div>

         <div class="col-sm-1">
            Total : <b><span id="ttl_specimen_list"></span></b>
         </div>
      </div>
  </div>
  <div class="card-body" style="min-height: 300px; ">
    <table id="tbl_lab_prcess_specimen" class="table table-bordered table-sm table-striped table-hover">
        <thead>
          <tr>
            <th>No.</th>
            <th>Specimen</th>
            <th>Collect Amt</th>
            <th>Barcode</th>
            <th>UID</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        </tbody>

    </table>

  </div><!-- cardbody -->


</div>


<script>



$(document).ready(function(){
  $("#btn_load_specimen_check").click(function(){
     load_specimen_check();
  }); // btn_load_lab_process
  $("#btn_load_specimen_check_from_barcode").click(function(){
     load_specimen_check_from_barcode();
  }); // btn_load_lab_process



});

function addLabProcess_specimen(){
  choice_lab_process = "specimen";
  clearLabProcessDetail();
  $('#div_lab_process_note').hide();
  $('#txt_lab_serial_no').val("New");
  $('#txt_time_start').val("");
  $('#txt_time_complete').val("");

  $('#txt_lab_process_title').html($("#sel_laboratory option:selected").text()+" / "+$("#sel_test_menu option:selected").text());
  cur_lab_process_lab_group_id = $("#sel_test_menu").val();
  cur_lab_process_laboratory_id = $("#sel_laboratory").val();

  $(".lab-process-result").hide();
  $(".lab-process-specimen").show();
  $(".div-lab-detail").hide();
  $("#div_lab_process_specimen").show();
  load_specimen_check();
  showLabProcessDiv("detail");
}

function openSP(labSerialNo){
  var aData = {
      u_mode:"select_lab_process_specimen",
      lab_serial_no:labSerialNo
  };
  save_data_ajax(aData,"lab/db_lab_process.php",openSP_complete);

}

function openSP_complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    choice_lab_process = "lab_specimen";
    setLabProcessData(rtnDataAjax.data_lab_process[0]);
    showLabProcessDiv("detail");

    $('#div_lab_process_note').show();

    $('.r_spc_chk').remove(); // row data list
    var txt_row="";
    $('#ttl_specimen_list').html(rtnDataAjax.datalist_specimen.length);

    if(rtnDataAjax.datalist_specimen.length > 0){

      var datalist = rtnDataAjax.datalist_specimen;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_LabProcess_Specimen(
              dataObj.specimen_name,dataObj.specimen_unit, dataObj.specimen_amt, dataObj.barcode, dataObj.uid
            );
//osp.lab_group_id, osp.laboratory_id, osp.barcode,
        }//for

        $('#tbl_lab_process_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }
  }

}





function load_specimen_check(){
  var aData = {
      u_mode:"load_lab_specimen_check",
      laboratory_id:cur_lab_process_laboratory_id,
      lab_group_id:cur_lab_process_lab_group_id
  };
  save_data_ajax(aData,"lab/db_lab_process.php",load_specimen_check_complete);
}

function load_specimen_check_complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

    $('.r_spc_chk').remove(); // row data list
    var txt_row="";
    $('#ttl_specimen_list').html(rtnDataAjax.datalist.length);

    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_LabProcess_Specimen(
              dataObj.specimen_name,dataObj.specimen_unit, dataObj.specimen_amt, dataObj.barcode, dataObj.uid
            );
//osp.lab_group_id, osp.laboratory_id, osp.barcode,
        }//for

        $('#tbl_lab_process_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }
  }
}

function load_specimen_check_from_barcode(){
  var aData = {
      u_mode:"load_lab_specimen_check_from_stock",
      barcode:$('#txt_specimen_barcode').val()
  };
  save_data_ajax(aData,"lab/db_lab_process.php",load_specimen_check_from_barcode_complete);
}

function load_specimen_check_from_barcode_complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

    //$('.r_spc_chk').remove(); // row data list
    var txt_row="";
    //$('#ttl_specimen_list').html(rtnDataAjax.datalist.length);

    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_LabProcess_Specimen(
              dataObj.specimen_name,dataObj.specimen_unit, dataObj.specimen_amt, dataObj.barcode, dataObj.uid
            );
//osp.lab_group_id, osp.laboratory_id, osp.barcode,
        }//for

        $('#tbl_lab_process_list > tbody:last-child').append(txt_row);
        $('#ttl_specimen_list').html($('#ttl_specimen_list tr').length);
    }
    else{
      $.notify("No record found.", "info");
    }
  }
}



function addRowData_LabProcess_Specimen(
  specimen_name,specimen_unit, unit_amt, barcode, uid
){

  var count=$('#tbl_lab_prcess_specimen tr').length;

  if(specimen_unit != ""){
    unit_amt += " "+specimen_unit;
  }
  var txt_row = '<tr class="r_spc_chk" id="rscb'+barcode+'" data-id="'+barcode+'">' ;
  txt_row += '<td width="10%" class="rspchk_seq" >';
  txt_row += '<b>'+count+'</b>';
  txt_row += '</td>';

  txt_row += '<td width="30%">'+specimen_name+'</td>';
  txt_row += '<td width="10%">'+unit_amt+'</td>';
  txt_row += '<td >'+barcode+'</td>';
  txt_row += '<td ><b>'+uid+'</b></td>';
  txt_row += '<td width="10%">';

  if(cur_lab_serial_no == "")
  txt_row += '<button class="btn btn-danger btn_del_spc_chk" type="button" onclick="deleteSPC_LabProcess(\''+barcode+'\');" ><i class="fa fa-times fa-lg" ></i></button>';

  txt_row += '</td>';
  txt_row += '</tr">';
  $("#tbl_lab_prcess_specimen tbody").append(txt_row);
  $('#ttl_specimen_list').html(count);
}

function deleteSPC_LabProcess(barcode){
  $("#rscb"+barcode).remove();
  if($('#tbl_lab_prcess_specimen tr').length>1) {
    $(this).closest('tr').remove();
    $('td.rspchk_seq').text(function (i) {
      return i + 1;
    });
  }
}

function clearLabProcessSpecimen(){
  $(".r_spc_chk").remove();
  $("txt_time_start").val("");
  $("txt_time_complete").val("");
  cur_lab_serial_no = "";
  cur_lab_process_lab_group_id = "";
  cur_lab_process_laboratory_id = "";


}

function startLabProcess(){
  var flag_valid = 1;
  var lst_data = [];
  $("#tbl_lab_prcess_specimen .r_spc_chk").each(function(ix,objx){
     lst_data.push($(objx).data("id")); // collect barcode
  });

  if(lst_data.length > 0){
    var aData = {
        u_mode:"start_lab_process",
        laboratory_id:cur_lab_process_laboratory_id,
        lab_group_id:cur_lab_process_lab_group_id,
        lst_data_barcode:lst_data

    };
    save_data_ajax(aData,"lab/db_lab_process.php",startLabProcessComplete);
  }
  else{
    $.notify("No specimen to lab process", "info");
  }


}

function startLabProcessComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $(".lab-process-specimen").hide();
    $("#txt_lab_serial_no").val(rtnDataAjax.lab_serial_no);
    $("#txt_time_start").val(rtnDataAjax.time_start);
    $.notify("Start Lab Process successfully.", "info");
    $("#lab_process_status").val("Lab Process Working");
    $('#div_lab_process_note').show();

  }
}

</script>
