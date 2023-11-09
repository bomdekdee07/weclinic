
<?
include_once("../in_auth.php");
include_once("../a_app_info.php");

$lab_order_id= isset($_GET["lab_order_id"])?$_GET["lab_order_id"]:"";
$uid= isset($_GET["uid"])?$_GET["uid"]:"";
$collect_date= isset($_GET["collect_date"])?$_GET["collect_date"]:"";
$collect_time= isset($_GET["collect_time"])?$_GET["collect_time"]:"";


?>

<style>
.barcode-txt{
  width:120px;
}

#tbl_specimen_collect_detail input[type=text]:disabled {
  background: #eee;
  color:#000;
}

.specimen-amt{
  width:80px;
}
.td_barcode{
  width:150px;
}
.td_specimen{
  width:250px;
}
.td_coltime{
  width:120px;
}
</style>

<div id='div_lab_specimen_collect_detail' class="my-0"
data-labid="<? echo $lab_order_id; ?>"  data-uid="<? echo $uid; ?>" data-coldate="<? echo $collect_date; ?>" data-coltime="<? echo $collect_time; ?>"   >


  <div class="card" >
    <div class="card-header bg-primary text-white" style="max-height: 3rem;">
        <div class="row ">
           <div class="col-sm-3">
             <h4><i class="fa fa-vials fa-lg" aria-hidden="true"></i> <b>Specimen Collect</b></h4>
           </div>

           <div class="col-sm-8">

              <b>
               <span id = "txt_specimen_collect_title"></span>
              </b>

           </div>


           <div class="col-sm-1">
             <button type="button" class="btn btn-sm btn-white  py-1 float-right" onclick="closeToLabOrderList();"> <i class="fa fa-times fa-lg" ></i> Close</button>
         </div>

        </div>
    </div>
    <div class="card-body">


      <div class="row my-1">
        <div class="col-sm-9">
          <div style="min-height: 100px; border:1px solid grey;">
            <table id="tbl_specimen_collect_labtest_summary" class="table table-bordered table-sm table-striped table-hover">
                <thead>
                  <tr>
                    <th>
                      Laboratory [Lab Test] / Test Menu
                    </th>
                    <th>Note</th>
                    <th>Specimen Collect</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
          </div>
        </div>
        <div class="col-sm-3">
          <div >
            <label for="lab_order_note">
              <button type="button" id="btn_add_note" class="btn btn-info btn-sm mx-1" > <i class="fa fa-edit fa-lg" ></i> Add Lab Order Note</button>

            </label>
            <textarea id="lab_order_note" rows="4"  data-title="Note" data-odata="" class="txtlabnote form-control form-control-sm bg-white" placeholder="Order Note" disabled></textarea>

          </div>
        </div>
      </div>

      <div class="px-1 py-2" style="min-height: 300px; border:1px solid grey; " >
      <table id="tbl_specimen_list" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <th>Barcode</th>
              <th>Specimen</th>
              <th>Laboratory / Test Menu</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>

          </tbody>

      </table>


      <div class="px-1 py-2" style="min-height: 300px; border:1px solid grey;">
        <div class="row my-1">
          <div class="col-sm-4">
            <label for="sel_specimen_collect"><b><u>Specimen Collect</u></b></label>
            <select id="sel_specimen_collect" class="form-control form-control-sm" >

            </select>
          </div>
          <div class="col-sm-1">
            <label for="txt_collect_amt">Collect Amt:</label>
            <input type="text" id="txt_collect_amt" class="form-control form-control-sm input-decimal" placeholder="Collect">

          </div>


          <div class="col-sm-2">
            <label for="btn_add_specimen_collect" class="text-white">.</label>
            <button id="btn_add_specimen_collect" class="form-control form-control-sm btn btn-success" type="button">
              <i class="fa fa-plus fa-lg" ></i> ADD
            </button>
          </div>
          <div class="col-sm-1">
          </div>
          <div class="col-sm-4">
             Wait Lab Result:  <b><span id="txt_wait_lab_result"></span></b>
          </div>
        </div>

        <table id="tbl_specimen_collect_detail" class="table table-bordered table-sm table-striped table-hover">
            <thead>
              <tr>
                <th></th>
                <th>No.</th>
                <th>Barcode</th>
                <th>Specimen (Unit)</th>
                <th>Collect Amt</th>
                <th>Stock ?</th>
                <th>Laboratory / Test Menu</th>
                <th>Collect Time</th>
              </tr>
            </thead>
            <tbody>

            </tbody>

        </table>



      </div>



    </div><!-- cardbody -->

    <div class="card-footer ">

      <select id="sel_barcode_print" >
       <option value="3">3</option>
       <!--
       <option value="6">6</option>
       <option value="9">9</option>
       <option value="12">12</option>
       <option value="15">15</option>
     -->
      </select>
      <button type="button" id="btn_print_specimen_barcode" class="btn btn-info mr-auto "><i class="fa fa-barcode fa-lg" ></i> Print Barcode</button>
<!--
      <button type="button" id="btn_collect_specimen" class="btn btn-warning mr-auto "><i class="fa fa-clipboard-check fa-lg" ></i> Specimen Collected</button>
      <button type="button" id="btn_reject_lab_order" class="btn btn-danger mx-1 float-right" > <i class="fa fa-times-circle fa-lg" ></i> Reject Confirmed Lab Order</button>
-->
    </div>
  </div>

</div> <!-- div_lab_specimen_collect_detail -->

<!-- The Modal  -->
<div class="modal fade" id="modal_specimen_barcode_sticker" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h4 class="modal-title ">
          <i class="fa fa-barcode fa-lg" aria-hidden="true"></i>
          <span id="specimen_barcode_title" ></span></h4>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="div_specimen_barcode_detail" style="overflow-y: auto;">
      <iframe id="barcode-content"  src="" frameborder="0" style="width:100%;height:100%" ></iframe>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">

      </div>


    </div>
  </div>
</div>





<?
include_once("dlg_add_lab_note.php");
?>

<script>

var flag_collect = 0; // collect specimen (save and collect)
var cur_spc_uid = "<? echo $uid;?>";
var cur_spc_collect_date = "<? echo $collect_date;?>";
var cur_spc_collect_time = "<? echo $collect_time;?>";
var chkbox_labTestMenu = "";

$(document).ready(function(){
  initDataSpecimenCollect();

  var row_num_spc = 0;



$("#btn_add_specimen_collect").click(function(){
   flag_collect = 0;
   addSpecimenDetail();
}); // btn_search_specimen_collect



$("#btn_close_specimen").click(function(){
   closeSpecimenCollect();
}); // btn_reject_lab_order

$("#btn_add_note").click(function(){
  addNoteLabOrderSpecimen();
}); // btn_add_note

$("#btn_print_specimen_barcode").click(function(){
  printSpecimenBarcode();
}); // btn_add_note


//
$("#div_lab_specimen_collect_detail .barcode-txt").unbind();
$("#div_lab_specimen_collect_detail").on("focusin",".barcode-txt",function(){
  if($(this).val() == ''){
    let barcodetxt = $("#div_lab_specimen_collect_detail").attr('data-labid')+'N';
    $(this).val(barcodetxt);
  }
});



});
function initDataSpecimenCollect(){
//  searchData_SpecimenCollect();
  openSPC(cur_spc_uid, cur_spc_collect_date, cur_spc_collect_time);
  selectSpecimenDropdown();
}


function addNoteLabOrderSpecimen(){

  var lst_data = [];
  lst_data.push({name:"uid", value:cur_spc_uid});
  lst_data.push({name:"collect_date", value:cur_spc_date});
  lst_data.push({name:"collect_time", value:cur_spc_time});

  openAddLabNote(
    $(".txtlabnote"),
    lst_data,
    "p_lab_order",
    "Add Lab Order Note",
    "<? echo $s_name; ?>"
  );
}


function printSpecimenBarcode(){
  var lstObj = {uid:cur_spc_uid,
  collect_date:cur_spc_date,
  collect_time:cur_spc_time
  }

  var aData = {
  u_mode:"print_specimen_barcode",
  print_amt:$("#sel_barcode_print").val(),
  uid:cur_spc_uid,
  collect_date:cur_spc_date,
  collect_time:cur_spc_time
  };
  save_data_ajax(aData,"lab/db_lab_test_specimen_v2.php",printSpecimenBarcodeComplete);

}

function printSpecimenBarcodeComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    /*
     var barcode = aData.uid+":";
     barcode += aData.collect_date.replaceAll("-", "");
     barcode += aData.collect_time.replaceAll(":", "");
*/
     var visit = aData.collect_date.replaceAll("-", "") + aData.collect_time.replaceAll(":", "");

     var link = "./lab/link_specimen_barcode.php?lab_order_id="+rtnDataAjax.lab_order_id+"&start_num="+rtnDataAjax.barcode_start_num+"&print_amt="+aData.print_amt+"&uid="+aData.uid+"&visit="+visit;
    $("#specimen_barcode_title").html("UID: "+aData.uid+" Visit: "+aData.collect_date+" "+aData.collect_time);


    $('#barcode-content').attr('src', link);
    $("#modal_specimen_barcode_sticker").modal("show");

  //   console.log(" LINK: "+link);
  //   console.log("print last num : "+rtnDataAjax.barcode_start_num+"/"+barcode+"/"+aData.print_amt+" LINK: "+link);

  }
}

function selectSpecimenDropdown(){
  var aData = {
      u_mode:"select_dropdown_list",
      setting_choice:"specimen"
  };
  save_data_ajax(aData,"lab/db_lab_setting.php",selectSpecimenDropdownComplete);
}

function selectSpecimenDropdownComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){
    //  $("#sel_test_menu").val([]);
      $("#sel_specimen_collect").empty();
      var datalist = rtnDataAjax.datalist;
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            if(dataObj.unit != "") dataObj.unit = " ("+dataObj.unit+")";
            $("#sel_specimen_collect").append(new Option(dataObj.name+dataObj.unit, dataObj.id));
        }//for

    }
    else{
      $.notify("No lab test menu found.", "info");
    }
  }
}


function openSPC(p_uid, p_collect_date, p_collect_time){ // open specimen collect
  //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
    var aData = {
        u_mode:"select_specimen_collect_detail",
        uid:p_uid,
        collect_date:p_collect_date,
        collect_time:p_collect_time
    };
    save_data_ajax(aData,"lab/db_lab_test_specimen_v2.php",openSPC_Complete);
  }

  function openSPC_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      clearData_SpecimenCollect();
      var dataLabOrder = rtnDataAjax.data_lab_order;

      $("#div_lab_specimen_collect_detail").attr('data-labid', dataLabOrder.lab_order_id);
      $("#txt_specimen_collect_title").html("Lab Order ID: <u>"+dataLabOrder.lab_order_id+"</u> | UID: <u>"+aData.uid+"</u>");
      //$("#lab_order_note").val(dataLabOrder.lab_order_note);
      $(".txtlabnote").val(dataLabOrder.lab_order_note);
      //console.log("lab_order: "+dataLabOrder.lab_order_note);

       cur_spc_uid = aData.uid; // current uid in specimen collect
       cur_spc_date = aData.collect_date; // current collect date in specimen collect
       cur_spc_time = aData.collect_time; // current collect time in specimen collect
      var datalist_specimen_summary = rtnDataAjax.data_specimen_summary;
    //  tbl_specimen_collect_labtest_summary
      var txt_row = "";
      chkbox_labTestMenu = "";
      datalist_specimen_summary.forEach(function (itm) {
        txt_row += "<tr class='r_sp_sum'>";
        txt_row += " <td width='40%'>";
        txt_row +=  "<b><u>"+itm.g_name + "</u></b>   <br>"+itm.lab_name;
        txt_row += " </td>";
        txt_row += " <td width='35%'>";
        txt_row +=  itm.g_note ;
        txt_row += " </td>";
        txt_row += " <td>";
        txt_row +=  itm.sp;
        txt_row += " </td>";
        txt_row += "</tr>";
        chkbox_labTestMenu += "<div class='form-check'><label class='form-check-label' for='chk"+itm.id+"'><input type='checkbox' id= 'chk"+itm.id+"' class='chk' data-id= '"+itm.id+"'>  "+itm.g_name+" </label></div>";

      //    addNewRow_lab_test_result(itm.lab_txt_id, itm.lab_txt_name, itm.is_normal, itm.lab_txt_seq);
        $("#sel_lbt_lab_group").append(new Option(itm.g_name, itm.id));
      });
      $('#tbl_specimen_collect_labtest_summary > tbody:last-child').append(txt_row);



        var datalist_specimen_barcode = rtnDataAjax.data_specimen_list;
        datalist_specimen_barcode.forEach(function (itm) {
           addRowData_spc_detail(itm.specimen_id, itm.spc_name,
             itm.specimen_amt, itm.barcode, itm.in_stock, itm.chk, itm.spc_time
           );
        });


    }
  }



  function clearData_SpecimenCollect(){
    $('.r_sp').remove();
    $('.rspc_detail').remove();
    $('.r_sp_sum').remove();
    $("#sel_lbt_lab_group").empty();
    row_num_spc = 0;
    cur_spc_uid = ""; // current uid in specimen collect
    cur_spc_date = ""; // current collect date in specimen collect
    cur_spc_time = "";
    $("#txt_specimen_collect_title").html("");
  }

  function addSpecimenDetail(){
    //console.log("addSpecimenDetail "+$("#sel_specimen_collect").val());
    if (isNaN($("#txt_collect_amt").val()) || $("#txt_collect_amt").val().trim() == "")
    {
      $("#txt_collect_amt").notify("Insert valid specimen amount", "info");
      return false;
    }


    addRowData_spc_detail(
      $("#sel_specimen_collect").val(),  $("#sel_specimen_collect option:selected").text(),
      $("#txt_collect_amt").val(), "", "0", [], null
    );

  }

    function addRowData_spc_detail(specimen_id, specimen_name,
    collect_amt, barcode, is_stock, arr_chk_data, time_specimen_collect){
        row_num_spc += 1;
        var count=$('#tbl_specimen_collect_detail tr').length;

        var row_id = row_num_spc;

        var chkbox_labTestMenu_row = chkbox_labTestMenu.replace(/chk/g, "chklab"+row_id);
        var is_stock = (is_stock == '1')?"checked":"";
        var barcode_disable = (barcode != '')?"disabled":"";
        specimen_name = specimen_name.replace("()", "");

        var txt_row = '<tr class="rspc_detail" id="r'+row_id+'" data-rowid="'+row_id+'" ';

        txt_row += ' data-specimen_id="'+specimen_id+'" ' ;
        txt_row += '>';

        txt_row += '<td>';
        txt_row += '<button class="btn btn-danger btn_del_spc" type="button" onclick="deleteSPC(\''+row_id+'\');" ><i class="fa fa-times fa-lg" ></i></button>';
        txt_row += '</td>';

        txt_row += '<td width="5%" class="rsp_seq"  >'+count+'</td>';
        txt_row += '<td class="td_barcode" >';
        txt_row += "<input type='text' class='barcode-txt' id='b"+row_id+"' data-odata='"+barcode+"'  size='30' value='"+barcode+"' "+barcode_disable+" onfocusout='chkBarcode(this);' >";
        txt_row += '</td>';

        txt_row += '<td class="td_specimen">'+specimen_name+'</td>';

        txt_row += '<td class="td_specimen_amt" >';
        txt_row += "<input type='text' class='input-decimal specimen-amt' id='amt"+row_id+"'  size='5' value='"+collect_amt+"'> ";
        txt_row += '</td>';

        txt_row += '<td>';
        txt_row += "<input type='checkbox' id='stock"+row_id+"' "+is_stock+">";
        txt_row += '</td>';

      //  txt_row += '<td class="text-primary">'+laboratory_labgroup_name+'</td>';
        txt_row += '<td class="text-primary">'+chkbox_labTestMenu_row+'</td>';

        txt_row += '<td>';

        txt_row += '<button id="btnsave'+row_id+'" class="btn btn-success" type="button" onclick="savSPC(\''+row_id+'\');" ><i class="fa fa-clipboard fa-lg" ></i> Save</button>';
        txt_row += '<i class="fas fa-spinner fa-spin spinner" style="display:none;"></i>';
        txt_row += '<button id="btncollect'+row_id+'" class="btn btn-warning collectbtn'+row_id+' ml-1" type="button" onclick="colSPC(\''+row_id+'\');" ><i class="fa fa-clipboard-check fa-lg" ></i> Collect</button>';
        txt_row += '<i class="fas fa-spinner fa-spin spinner collectbtn'+row_id+'" style="display:none;"></i>';

        txt_row += '<div id="spc_time'+row_id+'" onclick="editTimeSPC(\''+row_id+'\');">';

        txt_row += '</div>';
        txt_row += '</td>';

        txt_row += '</tr">';
        $("#tbl_specimen_collect_detail tbody").append(txt_row);

        arr_chk_data.forEach(function (itm) {
          //  console.log(barcode+": chklab"+row_id+itm);
            $("#chklab"+row_id+itm).prop("checked", true);
        });

        if(time_specimen_collect != null){
            $("#spc_time"+row_id).html(time_specimen_collect);
            $(".collectbtn"+row_id).hide();
        }

    }

    function editTimeSPC(rowID){
      let sCollecttime = $('#spc_time'+rowID).html();
      let sSpecimenid = $('#spc_time'+rowID).closest('.rspc_detail').attr('data-specimen_id');
      let barcode = $('#b'+rowID).val();
      sEditCollecttime = prompt(barcode+"Edit Specimen Collect Time.\r\nแก้ไขเวลาที่เก็บสิ่งส่งตรวจ", sCollecttime);
      if(sEditCollecttime.trim() == ""){
        $.notify("Please enter specimen collect time.");
        return;
      }
      sEditCollecttime = sEditCollecttime.trim();
      if(sEditCollecttime == sCollecttime){
        $.notify("No data changed.", 'info');
        return;
      }
      var aData = {
          u_mode:"edit_specimen_collect_time",
          uid: $('#div_lab_specimen_collect_detail').attr('data-uid'),
          coldate: $('#div_lab_specimen_collect_detail').attr('data-coldate'),
          coltime: $('#div_lab_specimen_collect_detail').attr('data-coltime'),
          specimenid: sSpecimenid,
          specimencoltime:sEditCollecttime,
          row_id:rowID,
          barcodeS: barcode
      };
      save_data_ajax_silent(aData,"lab/db_lab_test_specimen_v2.php",editTimeSPC_Complete);

    }
    function editTimeSPC_Complete(flagSave, rtnDataAjax, aData){
      if(flagSave){
         if(rtnDataAjax.res == '1'){
           $.notify("Data Edited.", "success");
           $('#spc_time'+aData.row_id).html(aData.specimencoltime);
         }
         else{
           $.notify("No update row.", "info");
         }
      }
    }

    function chkBarcode(txtbarcode){

      let barcode = $(txtbarcode).val().trim();
      let lab_order_id = $("#div_lab_specimen_collect_detail").attr('data-labid');
    //  console.log('chkbarcode '+$(txtbarcode).val()+"/"+lab_order_id);
      if(barcode.indexOf(lab_order_id) < 0){
        $(txtbarcode).notify('Invalid barcode: '+barcode+' / No lab order id ('+lab_order_id+') in barcode. Please check!', 'error');
        $(txtbarcode).val('');
      }

    }


    function deleteSPC(row_id){
      if($("#b"+row_id).data("odata") != ""){ // delete new record
          if(confirm("Are you sure to delete barcode "+$("#b"+row_id).data("odata")+" ?") )
          deleteSpecimen(row_id);
      }
      else{
        $("#r"+row_id).remove();
        if($('#tbl_specimen_collect_detail tr').length>1) {
          $(this).closest('tr').remove();
          $('td.rsp_seq').text(function (i) {
            return i + 1;
          });
        }
      }

    }//deleteSPC
    function deleteSpecimen(rowID){
      var p_barcode = $('#b'+rowID).val().trim();
      var arr_obj = {};
      arr_obj["uid"] = cur_spc_uid;
      arr_obj["collect_date"] = cur_spc_date;
      arr_obj["collect_time"] = cur_spc_time;
      arr_obj["barcode"] = p_barcode;

      var aData = {
          u_mode:"delete_specimen",
          obj_data: arr_obj,
          row_id:rowID
      };
      save_data_ajax_silent(aData,"lab/db_lab_test_specimen_v2.php",deleteSpecimen_Complete);
      $("#btndel"+aData.row_id).next(".spinner").show();
      $("#btndel"+aData.row_id).hide();
    }
    function deleteSpecimen_Complete(flagSave, rtnDataAjax, aData){
      if(flagSave){
         $.notify("Delete Data Successfully", "success");
         $("#r"+aData.row_id).remove();
         if($('#tbl_specimen_collect_detail tr').length>1) {
           $(this).closest('tr').remove();
           $('td.rsp_seq').text(function (i) {
             return i + 1;
           });
         }
      }
    }

    function savSPC(rowID){
      flag_collect = 0;
      saveSPC(rowID);
    }
    function colSPC(rowID){
      flag_collect = 1;
      saveSPC(rowID);
    }
    function saveSPC(rowID){
      var p_barcode = $('#b'+rowID).val().trim();
      if(p_barcode == ""){
        $('#b'+rowID).notify("Please insert barcode.", "error");
        return
      }
      var barcode_found_amt = 0;
      $(".barcode-txt").not('#b'+rowID).each(function(ix,objx){
         if($(objx).val().trim() == p_barcode){
           barcode_found_amt++;
           $(objx).notify("Duplicate this barcode", "warn");
         }
      });
      if(barcode_found_amt > 0){
        $('#b'+rowID).notify("Duplicate barcode", "error");
        $.notify("Duplicate barcode found: "+barcode_found_amt, "error");
        return;
      }


      var arr_obj = {};
      arr_obj["uid"] = cur_spc_uid;
      arr_obj["collect_date"] = cur_spc_date;
      arr_obj["collect_time"] = cur_spc_time;
      arr_obj["barcode"] = p_barcode;
      arr_obj["specimen_id"] = $("#r"+rowID).data("specimen_id");
      arr_obj["specimen_amt"] = $("#amt"+rowID).val();
      arr_obj["in_stock"] = ($("#stock"+rowID).prop("checked"))?"1":"0";

      var chklabtxt= "";
      var lst_data_barcode_lab= [];
      $(".chklab"+rowID+":checked").each(function(ix,objz){
      //       console.log("chkxx : "+$(objz).data("id"));
          var lab_id = $(objz).data("id").split("_");
          var arr_specimen_lab = {};
          arr_specimen_lab["barcode"] = p_barcode;
          arr_specimen_lab["laboratory_id"] = lab_id[0];
          arr_specimen_lab["lab_group_id"] = lab_id[1];
          lst_data_barcode_lab.push(arr_specimen_lab);
          chklabtxt+= "1";
      });

      if(chklabtxt == ""){ // check lab test menu was choosen in this barcode
        $("#b"+rowID).notify("Please choose Lab Test Menu at least 1 choice", "error");
        return;
      }


      var aData = {
          u_mode:"update_specimen_collect_detail",
          obj_data: arr_obj,
          obj_data_process: lst_data_barcode_lab,
          row_id:rowID
      };
      save_data_ajax_silent(aData,"lab/db_lab_test_specimen_v2.php",saveSPC_Complete);
      $("#btnsave"+rowID).next(".spinner").show();
      $("#btnsave"+rowID).hide();
    }

    function saveSPC_Complete(flagSave, rtnDataAjax, aData){
      if(flagSave){
         $.notify("Save Data Successfully", "success");
         $("#b"+aData.row_id).data("odata", aData.obj_data["barcode"]);
         $("#b"+aData.row_id).prop("disabled", true);

         if(flag_collect == 1) collectSPC(aData.row_id);
      }

      $("#btnsave"+aData.row_id).next(".spinner").hide();
      $("#btnsave"+aData.row_id).show();


      setLabStatus(rtnDataAjax.lab_order_status, rtnDataAjax.lab_order_id,
        aData.obj_data.uid, aData.obj_data.collect_date,aData.obj_data.collect_time);

    }


    function collectSPC(rowID){
      var p_barcode = $('#b'+rowID).val().trim();

      var arr_obj = {};
      arr_obj["uid"] = cur_spc_uid;
      arr_obj["collect_date"] = cur_spc_date;
      arr_obj["collect_time"] = cur_spc_time;
      arr_obj["barcode"] = p_barcode;

      var aData = {
          u_mode:"collect_specimen",
          obj_data: arr_obj,
          row_id:rowID
      };
      save_data_ajax_silent(aData,"lab/db_lab_test_specimen_v2.php",collectSPC_Complete);
      $("#btncollect"+rowID).next(".spinner").show();
      $("#btncollect"+rowID).hide();
    }

    function collectSPC_Complete(flagSave, rtnDataAjax, aData){
      $("#btncollect"+aData.row_id).next(".spinner").hide();
      $("#btncollect"+aData.row_id).show();

      if(flagSave){
         $.notify("Collect specimen successfully.", "success");
         $("#spc_time"+aData.row_id).html(rtnDataAjax.spc_collect_time);
         $(".collectbtn"+aData.row_id).hide();

         setLabStatus(rtnDataAjax.lab_order_status, rtnDataAjax.lab_order_id,
           aData.obj_data.uid, aData.obj_data.collect_date,aData.obj_data.collect_time);
      }
      flag_collect =0;
    }







function closeSpecimenCollect(){
  showSpecimenCollectDiv("list");
}

</script>
<? include_once("../in_savedata.php"); ?>
<? include_once("../inc_foot_include.php"); ?>
<? include_once("../function_js/js_fn_validate.php"); ?>
