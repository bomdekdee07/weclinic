<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

include_once("inc_auth.php"); // set permission view, update, delete

include_once("../a_app_info.php");

?>


<script>

//**** lab specimen collect  var

var cur_specimen_collect_uid = ""; // current uid in specimen collect
var cur_specimen_collect_date = ""; // current collect date in specimen collect
var cur_specimen_collect_time = ""; // current collect time in specimen collect
//****

</script>


<div id='div_lab_specimen_collect_list' class='div-specimen-collect my-0'>
  <div class="row mt-0">
    <div class="col-sm-12">
      <h4><i class="fa fa-vials fa-lg" ></i> <b>Specimen Collect</b> <button class="btn btn-primary btn-sm" type="button" id="btn_reload_specimen_collect"><i class="fa fa-sync" ></i> Reload</button></h4>
    </div>
  </div>
<!--
  <div class="row mt-0">
    <div class="col-sm-10">
      <label for="txt_search_specimen_collect">คำค้นหา:</label>
      <input type="text" id="txt_search_specimen_collect" class="form-control form-control-sm" placeholder="พิมพ์คำค้นหา Lab ID หรือ Lab Test Name">
    </div>
     <div class="col-sm-2">
       <label for="btn_search_specimen_collect" class="text-white">.</label>
      <button class="btn btn-primary btn-sm form-control form-control-sm " type="button" id="btn_search_specimen_collect"><i class="fa fa-search" ></i> ค้นหา</button>
     </div>
   </div>
-->
   <div class="mt-2"  style="min-height: 300px; border:1px solid grey;">
     <table id="tbl_lab_specimen_collect_list" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
         <thead>
           <tr>
             <th>Queue</th>

             <th>UID</th>
             <th>Wait Lab Result?</th>
             <th>Confirm Lab Order</th>
             <th>Confirmed By</th>
           </tr>
         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_lab_specimen_collect -->

<div id='div_lab_specimen_collect_detail' class="div-specimen-collect my-0" style="display:none;">


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
             <button type="button" id="btn_close_specimen" class="btn btn-sm btn-white  py-1 float-right" > <i class="fa fa-times fa-lg" ></i> Close</button>
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
            <textarea id="lab_order_note" rows="4"  data-title="Note" data-odata="" class="form-control form-control-sm bg-white" placeholder="Order Note" disabled></textarea>

          </div>
        </div>
      </div>

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
                <th>No.</th>
                <th>Barcode</th>
                <th>Specimen (Unit)</th>
                <th>Collect Amt</th>
                <th>Stock ?</th>
                <th>Laboratory / Test Menu</th>
                <th></th>
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

      <button type="button" id="btn_collect_specimen" class="btn btn-warning mr-auto "><i class="fa fa-clipboard-check fa-lg" ></i> Specimen Collected</button>

      <button type="button" id="btn_reject_lab_order" class="btn btn-danger mx-1 float-right" > <i class="fa fa-times-circle fa-lg" ></i> Reject Confirmed Lab Order</button>

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
var cur_qid = ""; var cur_qrd = ""; var cur_wait_lab_result="";
var chkbox_labTestMenu = "";
$(document).ready(function(){
  initDataSpecimenCollect();
  var row_num_spc = 0;
  $("#btn_reload_specimen_collect").click(function(){
     searchData_SpecimenCollect();
  }); // btn_search_specimen_collect




/*
  $("#txt_search_specimen_collect").on("keypress",function (event) {
  //  console.log("key "+event.which);
    if (event.which == 13) {
      searchData_SpecimenCollect();
    }
  });
*/

$("#btn_add_specimen_collect").click(function(){
   addSpecimenDetail();
}); // btn_search_specimen_collect
$("#btn_collect_specimen").click(function(){
   collectSpecimen();
}); // btn_collect_specimen
$("#btn_reject_lab_order").click(function(){
   rejectLabOrder();
}); // btn_reject_lab_order
$("#btn_close_specimen").click(function(){
   closeSpecimenCollect();
}); // btn_reject_lab_order

$("#btn_add_note").click(function(){
  addNoteLabOrderSpecimen();
}); // btn_add_note

$("#btn_print_specimen_barcode").click(function(){
  printSpecimenBarcode();
}); // btn_add_note



});
function initDataSpecimenCollect(){
  searchData_SpecimenCollect();
  selectSpecimenDropdown();
}


function addNoteLabOrderSpecimen(){

  var lst_data = [];
  lst_data.push({name:"uid", value:cur_specimen_collect_uid});
  lst_data.push({name:"collect_date", value:cur_specimen_collect_date});
  lst_data.push({name:"collect_time", value:cur_specimen_collect_time});

  openAddLabNote(
    $("#lab_order_note"),
    lst_data,
    "p_lab_order",
    "Add Lab Order Note",
    "<? echo $s_name; ?>"
  );
}


function printSpecimenBarcode(){
  var lstObj = {uid:cur_specimen_collect_uid,
  collect_date:cur_specimen_collect_date,
  collect_time:cur_specimen_collect_time
  }

  var aData = {
  u_mode:"print_specimen_barcode",
  print_amt:$("#sel_barcode_print").val(),
  uid:cur_specimen_collect_uid,
  collect_date:cur_specimen_collect_date,
  collect_time:cur_specimen_collect_time
  };
  save_data_ajax(aData,"lab/db_lab_test_specimen.php",printSpecimenBarcodeComplete);

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

     var link = "link_specimen_barcode.php?lab_order_id="+rtnDataAjax.lab_order_id+"&start_num="+rtnDataAjax.barcode_start_num+"&print_amt="+aData.print_amt+"&uid="+aData.uid+"&visit="+visit;
    // link = "<? echo $GLOBALS['site_path'] ;?>lab/"+link;
     link = "<? echo $GLOBALS['site_local_path'] ;?>lab/"+link;


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



function searchData_SpecimenCollect(){
  var aData = {
      u_mode:"select_specimen_collect"
  };
  save_data_ajax(aData,"lab/db_lab_test_specimen.php",searchData_SpecimenCollect_Complete);
}

function searchData_SpecimenCollect_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $('.rspc').remove(); // row data list
    var txt_row="";

    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            addRowData_SpecimenCollectList(
             dataObj.qid, dataObj.uid, dataObj.wait_lab_result, dataObj.c_date, dataObj.c_time, dataObj.cf_time, dataObj.doctor_name,dataObj.qrd
            );
        }//for

        $('#tbl_lab_specimen_collect_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
    }
  }
}



function addRowData_SpecimenCollectList(qid, uid, wait_lab_result, collect_date, collect_time, confirm_order_time, confirm_by, qrd){

  var wait_txt = "";
  if(wait_lab_result=='1') wait_txt = '<span class="text-success"><b>Yes</b></span>';
  else if(wait_lab_result=='0') wait_txt = '<span class="text-danger"><b>No</b></span>';
  else wait_txt = 'Unknown';

    var txt_row = '<tr class="rspc" id="rspc'+qid+uid+'"  data-uid="'+uid+'" data-collect_date="'+collect_date+'" data-collect_time="'+collect_time+'" data-qrd="'+qrd+'" data-wait_lab_result="'+wait_lab_result+'">' ;

    txt_row += '<td width="15%">';
    txt_row += '<button class="btn btn-primary" type="button" onclick="openSPC(\''+qid+uid+'\');"> <b># '+qid+'</b></button>';
    txt_row += '</td>';
    txt_row += '<td width="25%" ><b>'+uid+'</b></td>';
    txt_row += '<td width="10%" >'+wait_txt+'</td>';
    txt_row += '<td width="25%" class="text-primary">'+confirm_order_time+'</td>';
    txt_row += '<td >'+confirm_by+qrd+'</td>';

    txt_row += '</tr">';
    $("#tbl_lab_specimen_collect_list tbody").append(txt_row);

}

function openSPC(id){ // open specimen collect
  //cconsole.log("open "+id+" / "+$("#rspc"+id).data("uid"));
    //console.log("id: "+id+" / "+$("#rspc"+id).data("wait_lab_result"));
    cur_qrd = $("#rspc"+id).data("qrd");
    cur_wait_lab_result = $("#rspc"+id).data("wait_lab_result");
    console.log("cur_qrd: "+cur_qrd);
    var aData = {
        u_mode:"select_specimen_collect_detail",
        uid:$("#rspc"+id).data("uid"),
        collect_date:$("#rspc"+id).data("collect_date"),
        collect_time:$("#rspc"+id).data("collect_time")
    };
    save_data_ajax(aData,"lab/db_lab_test_specimen.php",openSPC_Complete);
  }

  function openSPC_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      clearData_SpecimenCollect();



      var dataLabOrder = rtnDataAjax.data_lab_order[0];
      $("#txt_specimen_collect_title").html("Lab Order ID: <u>"+dataLabOrder.lab_order_id+"</u> | UID: <u>"+aData.uid+"</u>");
      $("#lab_order_note").val(dataLabOrder.lab_order_note);
      //$("#lab_order_note").val("xxxx");
       cur_specimen_collect_uid = aData.uid; // current uid in specimen collect
       cur_specimen_collect_date = aData.collect_date; // current collect date in specimen collect
       cur_specimen_collect_time = aData.collect_time; // current collect time in specimen collect
       cur_qid = dataLabOrder.qid;

      // console.log("qid: "+cur_qid+"/"+dataLabOrder.qid);
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

        chkbox_labTestMenu += "<div><input type='checkbox' class='chk' data-id= '"+itm.id+"'>  "+itm.g_name+" </div>";

      });
      $('#tbl_specimen_collect_labtest_summary > tbody:last-child').append(txt_row);

      var wait_lab = (cur_wait_lab_result == 1)?"Yes":"No";
      $('#txt_wait_lab_result').html(wait_lab);

      showSpecimenCollectDiv("detail");
    }
  }

  function clearData_SpecimenCollect(){
    $('.r_sp').remove();
    $('.rspc_detail').remove();
    $('.r_sp_sum').remove();

    cur_qid = "";
    row_num_spc = 0;
    cur_specimen_collect_uid = ""; // current uid in specimen collect
    cur_specimen_collect_date = ""; // current collect date in specimen collect
    cur_specimen_collect_time = "";
    chkbox_labTestMenu = "";
    $("#txt_specimen_collect_title").html("");
  }

  function addSpecimenDetail(){
    if (isNaN($("#txt_collect_amt").val()) || $("#txt_collect_amt").val().trim() == "")
    {
      $("#txt_collect_amt").notify("Insert valid specimen amount", "info");
      return false;
    }

    addRowData_spc_detail(
      $("#sel_specimen_collect").val(),  $("#sel_specimen_collect option:selected").text(),
      $("#txt_collect_amt").val()
    );

  }

  function addRowData_spc_detail(specimen_id, specimen_name,
  collect_amt){
      row_num_spc += 1;
      var count=$('#tbl_specimen_collect_detail tr').length;

  //    var spc_id = laboratory_labgroup_id.split("|");
      //var row_id = specimen_id+spc_id[0]+spc_id[1]+row_num_spc;
/*
      <th>No.</th>
      <th>Barcode</th>
      <th>Specimen (Unit)</th>
      <th>Collect Amt</th>
      <th>Stock ?</th>
      <th>Laboratory / Test Menu</th>
      <th></th>

*/
      var row_id = row_num_spc;

      var chkbox_labTestMenu_row = chkbox_labTestMenu.replace(/chk/g, "chklab"+row_id);



      var txt_row = '<tr class="rspc_detail" id="r'+row_id+'" data-rowid="'+row_id+'" ';

      txt_row += ' data-specimen_id="'+specimen_id+'" ' ;
      txt_row += ' data-specimen_amt="'+collect_amt+'"' ;
      txt_row += '>';

      txt_row += '<td width="5%" class="rsp_seq"  >'+count+'</td>';
      txt_row += '<td width="30%" >';
      txt_row += "<input type='text' class='barcode-txt' id='b"+row_id+"' size='30'>";
      txt_row += '</td>';

      txt_row += '<td >'+specimen_name+'</td>';
      txt_row += '<td width="10%" >'+collect_amt+'</td>';
      txt_row += '<td>';
      txt_row += "<input type='checkbox' id='chk"+row_id+"' >";
      txt_row += '</td>';

    //  txt_row += '<td class="text-primary">'+laboratory_labgroup_name+'</td>';
      txt_row += '<td class="text-primary">'+chkbox_labTestMenu_row+'</td>';

      txt_row += '<td>';
      txt_row += '<button class="btn btn-danger btn_del_spc" type="button" onclick="deleteSPC(\''+row_id+'\');" ><i class="fa fa-times fa-lg" ></i></button>';
      txt_row += '</td>';

      txt_row += '</tr">';
      $("#tbl_specimen_collect_detail tbody").append(txt_row);

  }


  function deleteSPC(row_id){
    $("#r"+row_id).remove();
    if($('#tbl_specimen_collect_detail tr').length>1) {
      $(this).closest('tr').remove();
      $('td.rsp_seq').text(function (i) {
        return i + 1;
      });
    }
  }


  function collectSpecimen(){ // collected specimen and bring to specimen check
    //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
      var flag_valid = true;
      var lst_data = []; // specimen, barcode
      var lst_data_barcode_lab = []; //barcode, laboratory_id, lab_group_id
      var barcode_chk = "";

// check barcode duplicate
      var stored  =   [];
      var inputs  =   $('.barcode-txt');
      $.each(inputs,function(k,v){
         var getVal  =   $(v).val().trim();
        // console.log("chk "+getVal);
         if(stored.indexOf(getVal) != -1){
             if(getVal != "")
             $(v).notify("Duplicate Barcode: "+getVal, "error");
             else
             $(v).notify("Please insert Barcode: "+getVal, "info");

             flag_valid = false;
             }
         else
             stored.push($(v).val().trim());
      });

      if(!flag_valid){
        $.notify("Please check barcode .", "error");
        return;
      }

      $("#tbl_specimen_collect_detail .rspc_detail").each(function(ix,objx){
         var row_id = $(objx).data("rowid");
         var arr_obj = [];

         var in_stock = "0"; // specimen instock ? 0:use, 1:keep instock
         if($("#b"+row_id).val() != ""){
           in_stock = "0";
           arr_obj.push({name:"specimen_id", value:$(objx).data("specimen_id")});
           arr_obj.push({name:"specimen_amt", value:$(objx).data("specimen_amt")});
           arr_obj.push({name:"barcode", value:$("#b"+row_id).val()});
           var chklabtxt= "";
           $(".chklab"+$(objx).data("rowid")+":checked").each(function(ix,objz){
        //       console.log("chkxx : "+$(objz).data("id"));
               var lab_id = $(objz).data("id").split("|");
               var arr_specimen_lab = [];
               arr_specimen_lab.push({name:"barcode", value:$("#b"+row_id).val().trim()});
               arr_specimen_lab.push({name:"laboratory_id", value:lab_id[0]});
               arr_specimen_lab.push({name:"lab_group_id", value:lab_id[1]});
               lst_data_barcode_lab.push(arr_specimen_lab);
               chklabtxt+= "1";
           });

           if(chklabtxt == ""){ // check lab test menu was choosen in this barcode
             $("#b"+row_id).notify("Please choose Lab Test Menu at least 1 choice", "error");
             flag_valid = false;
           }


           if($("#chk"+row_id).prop("checked")){
             in_stock = "1"; // specimen keep in stock
           }
        //   console.log($("#b"+row_id).val()+" check: "+$("#chk"+row_id).prop("checked"));
           arr_obj.push({name:"in_stock", value:in_stock});
           lst_data.push(arr_obj);
           barcode_chk += "'"+$("#b"+row_id).val()+"',";

         }
         else{
            $("#b"+row_id).notify("Please insert barcode", "error");
            flag_valid = false;
         }

      });


      if(barcode_chk.length > 0){
         barcode_chk = barcode_chk.substring(0, barcode_chk.length - 1);
      }
      else {
        flag_valid = false;
        $.notify("No specimen collect!", "error");
      }

      if(flag_valid){
        var lstObj = {uid:cur_specimen_collect_uid,
        collect_date:cur_specimen_collect_date,
        collect_time:cur_specimen_collect_time,
        str_barcode_chk: barcode_chk,
        lst_specimen_collect:lst_data,
        lst_specimen_lab:lst_data_barcode_lab,
        qid:cur_qid,
        qrd:cur_qrd,
        wait_lab_result:cur_wait_lab_result
        }
//console.log("send qid "+cur_qid);
        var aData = {
        u_mode:"update_lab_specimen_collect",
        lst_data_obj:lstObj
        };

        save_data_ajax(aData,"lab/db_lab_test_specimen.php",collectSpecimenComplete);
      }
      else{
        $.notify("Incomplete Data, Please Check!", "error");
      }


    }

    function collectSpecimenComplete(flagSave, rtnDataAjax, aData){
      if(flagSave){
        $.notify("Specimen Collected successfully.", "success");
        searchData_SpecimenCollect();
        showSpecimenCollectDiv("list");
      }
    }



    function rejectLabOrder(){ // reject confirmed lab order
      //console.log("open "+id+" / "+$("#rspc"+id).data("uid"));
      var lstObj = {uid:cur_specimen_collect_uid,
      collect_date:cur_specimen_collect_date,
      collect_time:cur_specimen_collect_time
      }
          var aData = {
          u_mode:"reject_lab_order_confirm",
          lst_data_obj:lstObj

          };
          save_data_ajax(aData,"lab/db_lab_test_specimen.php",rejectLabOrderComplete);

      }

      function rejectLabOrderComplete(flagSave, rtnDataAjax, aData){
        if(flagSave){
          searchData_SpecimenCollect();
          showSpecimenCollectDiv("list");
        }
      }
function closeSpecimenCollect(){
  showSpecimenCollectDiv("list");
}


function showSpecimenCollectDiv(choice){
  //alert("showSpecimenCollectDiv "+choice);
  $(".div-specimen-collect").hide();
  $("#div_lab_specimen_collect_"+choice).show();
}

</script>
