<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

include_once("inc_auth.php"); // set permission view, update, delete



?>


<script>

//**** lab specimen collect  var

</script>


<div id='div_lab_specimen_check_list' class='div-specimen-collect my-0'>
  <div class="row mt-0">
    <div class="col-sm-12">
      <h4><i class="fa fa-vials fa-lg" ></i><i class="fa fa-check fa-lg" ></i> <b>Specimen Check</b> </h4>


    </div>

  </div>

  <div class="row my-2" >
    <div class="col-sm-2">
      <label for="btn_specimen_barcode_check" class="text-white">.</label>
     <button class="btn btn-info btn-sm form-control form-control-sm " type="button" id="btn_specimen_barcode_check"><i class="fa fa-barcode" ></i> Check Barcode</button>
    </div>
    <div class="col-sm-8">
      <label for="txt_specimen_barcode">Insert specimen barcode:</label>
      <input type="text" id="txt_specimen_barcode" class="form-control form-control-sm" placeholder="พิมพ์ Specimen Barcode">
    </div>
    <div class="col-sm-2">
      <label for="btn_reload_specimen_check">Total check pending:
       <b><span id="ttl_check_pending" class="text-primary"></span></b>
      </label>

      <button class="btn btn-primary btn-sm" type="button" id="btn_reload_specimen_check"><i class="fa fa-sync" ></i> Reload / Save Checked Barcode</button>
    </div>

   </div>

   <div class="mt-2"  style="min-height: 300px; border:1px solid grey;">
     <table id="tbl_lab_specimen_check_list" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
         <thead>
           <tr>
             <th>Queue</th>
             <th>UID</th>
             <th>Specimen</th>
             <th>Check ?</th>
             <th>Barcode</th>
             <th>Wait Lab?</th>
             <th>Paid?</th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_lab_specimen_check -->






<script>
var lst_data_specimen_check = [];
$(document).ready(function(){
  searchData_SpecimenCheck();
  var row_num_spc = 0;
  $("#btn_reload_specimen_check").click(function(){
     searchData_SpecimenCheck();
  }); // btn_search_specimen_collect

  $("#txt_specimen_barcode").on("keypress",function (event) {
    if (event.which == 13) {
      checkSpecimenBarcode();
    }
  });

  $("#btn_specimen_barcode_check").click(function(){
     checkSpecimenBarcode();
  }); // btn_search_specimen_collect



});


function searchData_SpecimenCheck(){

  var aData = {
      u_mode:"select_specimen_check",
      lst_data:lst_data_specimen_check
  };
  save_data_ajax(aData,"lab/db_lab_test_specimen_collect.php",searchData_SpecimenCheck_Complete);
}

function searchData_SpecimenCheck_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    lst_data_specimen_check=[];


    $('.rspchk').remove(); // row data list
    var txt_row="";

    $('#ttl_check_pending').html(rtnDataAjax.datalist.length);
    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_SpecimenCheck(
             dataObj.qid, dataObj.uid, dataObj.c_date, dataObj.c_time, dataObj.spc_info,
             dataObj.specimen_id, dataObj.barcode,  dataObj.wait_lab,  dataObj.is_paid
            );

        }//for

        $('#tbl_lab_specimen_check_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }
  }
}




function addRowData_SpecimenCheck(qid, uid, collect_date, collect_time, specimen_info,
  specimen_id,  barcode, wait_lab, is_paid
){

    var row_id = barcode;


    qid = (qid != null)?qid="#"+qid:"-";
    wait_lab = (wait_lab == '1')?wait_lab="<span class='text-success'>Yes</span>":"<span class='text-danger'>No</span>";
    is_paid = (is_paid == '1')?is_paid="<i class='fa fa-check fa-lg text-success' ></i>":"";


    var txt_row = '<tr class="rspchk" id="'+barcode+'" data-uid="'+uid+'" data-collect_date="'+collect_date+'" data-collect_time="'+collect_time+'">' ;
    txt_row += '<td width="10%">';
    txt_row += '<b>'+qid+'</b>';
    txt_row += '</td>';
    txt_row += '<td width="25%" >'+uid+'</td>';
    txt_row += '<td >'+specimen_info+'</td>';
    txt_row += '<td width="8%" class="text-success" id="c'+barcode+'"></td>';
    txt_row += '<td>';
    txt_row += "<input type='text' class='barcode-txt' id='b"+barcode+"' value='"+barcode+"'  size='40' disabled>";
    txt_row += '</td>';

    txt_row += '<td>';
    txt_row += wait_lab;
    txt_row += '</td>';
    txt_row += '<td>';
    txt_row += is_paid;
    txt_row += '</td>';


    txt_row += '</tr">';
    $("#tbl_lab_specimen_check_list tbody").append(txt_row);

}

function checkSpecimenBarcode(){
  var flag_check = 0;
  $("#tbl_lab_specimen_check_list .rspchk").not(".checked").each(function(ix,objx){
     var barcode = $(objx).attr("id");

     if($("#txt_specimen_barcode").val().trim() == barcode){
       lst_data_specimen_check.push(barcode);
       $(objx).addClass("checked");
       $("#b"+barcode).addClass("bg-warning");
       $("#c"+barcode).html('<i class="fa fa-check fa-lg" ></i>');
       //console.log("check : "+barcode);
       flag_check = 1;
     }

  });

  if(flag_check == 1){
    $("#txt_specimen_barcode").val("");
    $("#txt_specimen_barcode").focus();
  }
  else{
  //  console.log("not found");
    $("#txt_specimen_barcode").notify("not found barcode", "info");
  }

}

function closeSpecimenCollect(){
  showSpecimenCollectDiv("list");
}


function showSpecimenCollectDiv(choice){
  //alert("showSpecimenCollectDiv "+choice);
  $(".div-specimen-collect").hide();
  $("#div_lab_specimen_check_"+choice).show();
}

</script>
