
<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

$lab_order_id= isset($_GET["lab_order_id"])?$_GET["lab_order_id"]:"";
$uid= isset($_GET["uid"])?$_GET["uid"]:"";
$collect_date= isset($_GET["collect_date"])?$_GET["collect_date"]:"";
$collect_time= isset($_GET["collect_time"])?$_GET["collect_time"]:"";

$sale_opt_id = "S01";
?>


<div class="card" id="div_lab_order">
  <div class="card-header bg-primary text-white" style="max-height: 3rem;">
      <div class="row ">
         <div class="col-sm-3">
           <h4><i class="fa fa-flask fa-lg" aria-hidden="true"></i> <b>Lab Order</b> <span id = "txt_lab_order_id"></span></h4>
         </div>

         <div class="col-sm-5">

            <b>
             <span id = "txt_lab_order_title"></span>
            </b>

         </div>

         <div class="col-sm-3">
            Status: <input type="text" id="lab_order_status" size="20" disabled>
         </div>
         <div class="col-sm-1">
           <button type="button" class="btn btn-sm btn-white mr-auto " onclick="closeToLabOrderList();"><i class="fa fa-times fa-lg" ></i> Close</button>

         </div>



      </div>
  </div>
  <div class="card-body">


    <div class="row my-1">
      <div class="col-sm-9">
        <div style="min-height: 300px; border:1px solid grey;">
          <table id="tbl_lab_test_order" class="table table-bordered table-sm table-striped table-hover">
              <thead>
                <tr>
                  <th>
                    Lab Test
                  </th>
                  <th>Test Menu</th>
                  <th>Laboratory</th>
                  <th>Cost</th>
                  <th>Sale</th>
                  <th>Sale Option</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

              </tbody>

          </table>
        </div>
      </div>
      <div class="col-sm-3">
        <div style="min-height: 150px; border:1px solid grey;">
          <table id="tbl_lab_test_order_summary" class="table table-bordered table-sm table-striped table-hover">
              <thead>
                <tr>
                  <th colspan="2">
                    Lab Order Summary:
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Sale (Baht):</td>
                  <td align="right"><span id="lab_order_ttl_sale"></span></td>
                </tr>
                <tr>
                  <td>Cost (Baht):</td>
                  <td align="right"><span id="lab_order_ttl_cost"></span></td>
                </tr>

                <tr>
                  <td>Balance (Baht):</td>
                  <td align="right"><span id="lab_order_ttl_balance"></span></td>
                </tr>
              </tbody>
          </table>
        </div>
        <div class="mt-1" id="div_lab_order_note" style="display:none;">
          <label for="lab_order_note">
            <button type="button" id="btn_add_note" class="btn btn-info btn-sm mx-1" > <i class="fa fa-edit fa-lg" ></i> Add Lab Order Note</button>
          </label>
          <textarea id="lab_order_note" rows="4"  data-title="Note" data-odata="" class="form-control form-control-sm bg-white" placeholder="Order Note" disabled></textarea>

        </div>
      </div>
    </div>


  </div><!-- cardbody -->

  <div class="card-footer ">
    <!--
    <button type="button" id="btn_confirm_lab_order" class="btn btn-warning mr-auto btn-lab-order"><i class="fa fa-clipboard-check fa-lg" ></i> Confirm Order</button>

    <button type="button" id="btn_save_lab_order" class="btn btn-success mx-1 float-right btn-lab-order" > <i class="fa fa-save fa-lg" ></i> Save Data</button>
-->
  </div>
</div>


<?

include_once("dlg_add_lab_note.php");
?>


<script>

var u_mode_order = "update_lab_order";
var lab_order_lst_delete_data = [];
var ttl_cost_lab_order = 0;
var ttl_sale_lab_order = 0;
var p_lab_order_status = "A0";

var cur_lab_order_sale_opt_id = '<? echo $sale_opt_id; ?>';

$(document).ready(function(){
  initDataLabOrder();

  $("#btn_add_lab_order").click(function(){
     addNew_LabTest();
  }); // btn_search_test_menu

  $("#btn_add_lab_order").on("keypress",function (event) {
    if (event.which == 13) {
      addNew_LabTest();
    }
  });
  $("#btn_save_lab_order").click(function(){
     saveLabTestOrder();
  }); // btn_save_lab_order
  $("#btn_confirm_lab_order").click(function(){
     confirmLabOrder();
  }); // btn_save_lab_order

  $("#btn_add_note").click(function(){

     var lst_data = [];
     lst_data.push({name:"uid", value:"<? echo $uid; ?>"});
     lst_data.push({name:"collect_date", value:"<? echo $collect_date; ?>"});
     lst_data.push({name:"collect_time", value:"<? echo $collect_time; ?>"});

     openAddLabNote(
       $("#lab_order_note"),
       lst_data,
       "p_lab_order",
       "Add Lab Order Note",
       "<? echo $s_id; ?>"
     );

  }); // btn_save_lab_order



});


function initDataLabOrder(){

   $('#btn_confirm_lab_order').prop("disabled", true);
   selectLabTestOrder();

}
function addNew_LabTest(){
  $('#modal_select_lab_test').modal('show');
}




function selectLabTestOrder(){

  var aData = {
      u_mode:"select_lab_test_order",
      uid:"<? echo $uid;?>",
      collect_date:"<? echo $collect_date;?>",
      collect_time:"<? echo $collect_time;?>",
      sale_opt_id:"<? echo $sale_opt_id;?>"
  };

  save_data_ajax(aData,"lab/db_lab_test_order.php",selectLabTestOrderComplete);

}


function selectLabTestOrderComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
  //  console.log("selectLabTestOrderComplete ");

    if(rtnDataAjax.data_lab_order.length > 0){
       var dataObj = rtnDataAjax.data_lab_order[0];
       u_mode_order = "update_lab_order";
       $("#txt_lab_order_id").html(dataObj.lab_order_id) ;

       $("#txt_lab_order_title").html("UID: <u>"+aData.uid+"</u> | Visit Date: <u>"+changeToThaiDate(aData.collect_date)+" "+aData.collect_time+"</u>") ;
       $("#lab_order_status").val(dataObj.status_name);
       $("#lab_order_note").val(dataObj.lab_order_note);
       p_lab_order_status = dataObj.status_id;

       if(rtnDataAjax.data_list_labtest.length > 0){
         var datalist = rtnDataAjax.data_list_labtest;
         datalist.forEach(function (itm) {
           addRowLabTestOrder("", itm.id, itm.name, itm.dataid, itm.g_id, itm.g_name,
             itm.lbt_id, itm.lbt_name,
             itm.lab_cost, itm.lab_price, itm.sa_id, itm.sa_name);
         });
       }



       $('#div_lab_order_note').show();
    }
    else{
      u_mode_order = "add_lab_order";
      $("#txt_lab_order_title").html("UID: <u><? echo $uid;?></u> | Visit Date: <u>"+changeToThaiDate('<? echo $collect_date;?>') +" <? echo $collect_time;?></u> ") ;

      $("#lab_order_status").val("New Lab Order");
      $('#btn_confirm_lab_order').prop("disabled", true);


    }



  }
}


function saveLabTestOrder(){
  var divSaveData = "div_lab_order";
  var flag_valid = true;
  var lstDataObj = [];
  var lstDataObj_laborder = [];
  if(validateInput(divSaveData)){

    $("#"+divSaveData +" .r_labtest_order").each(function(ix,objx){
       var row = $(objx).data("lab_id");
       var arr_obj = [];

         arr_obj.push({name:"lab_id", value:$(objx).data("dataid")});
         arr_obj.push({name:"lab_group_id", value:$(objx).data("group_id")});
         arr_obj.push({name:"laboratory_id", value:$(objx).data("laboratory_id")});
         arr_obj.push({name:"sale_opt_id", value:$(objx).data("sale_opt_id")});
         lstDataObj_laborder.push(arr_obj);
    });
  }

  var lstObj = {uid:"<? echo $uid; ?>",
  collect_date:"<? echo $collect_date; ?>",
  collect_time:"<? echo $collect_time; ?>",
  ttl_cost:ttl_cost_lab_order,
  ttl_sale:ttl_sale_lab_order,
  lab_order_status:p_lab_order_status,
  lst_order_lab_test: lstDataObj_laborder,
  lst_order_lab_test_delete:lab_order_lst_delete_data
  };

  var aData = {
      u_mode:u_mode_order,
      lst_data_obj:lstObj
  };
  save_data_ajax(aData,"lab/db_lab_test_order.php",saveLabTestOrderComplete);
}

function saveLabTestOrderComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    lab_order_lst_delete_data = [];
    $('#btn_confirm_lab_order').prop("disabled", false);
    $("#lab_order_status").val("Lab Order Confirm Pending");

    $.notify("Save lab order successfully.", "info");
    if(u_mode_order == "add_lab_order"){
      u_mode_order = "update_lab_order";
      $("#txt_lab_order_id").html(rtnDataAjax.lab_order_id) ;
      $('#div_lab_order_note').show();
    }
  }
}

function confirmLabOrder(){
  var lstObj = {uid:"<? echo $uid; ?>",
  collect_date:"<? echo $collect_date; ?>",
  collect_time:"<? echo $collect_time; ?>",
  staff_order:"<? echo $s_id; ?>"
  };

  var aData = {
      u_mode:"update_lab_order_confirm",
      lst_data_obj:lstObj
  };
  save_data_ajax(aData,"lab/db_lab_test_order.php",confirmLabOrderComplete);

}

function confirmLabOrderComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
  //  console.log("selectLabTestOrderComplete ");
     var dataObj_status = rtnDataAjax.status;
     p_lab_order_status = dataObj_status.id; // confirm lab order status
     $("#lab_order_status").val(dataObj_status.name);
     $(".btn-lab-order").hide();


     $.notify("Confirm Lab order successfully.", "info");

  }
}

function addRowLabTestOrder(addnew, lab_id, lab_name, dataid, group_id, group_name,
  laboratory_id, laboratory_name,
  cost_amt, sale_amt, sale_opt_id, sale_opt_name){

  var row_id = lab_id+laboratory_id+sale_opt_id;
  //  console.log("addrow: "+row_id);
  var txt_row = "<tr class='r_labtest_order "+addnew+"' id='ro"+lab_id+laboratory_id+sale_opt_id+"' data-lab_id='"+lab_id+"' ";

  txt_row += "data-laboratory_id='"+laboratory_id+"' data-sale_opt_id='"+sale_opt_id+"' ";
  txt_row += "data-dataid='"+dataid+"' data-group_id='"+group_id+"'  data-sale_amt='"+sale_amt+"' data-cost_amt='"+cost_amt+"' >";

  txt_row += "<td>";
  txt_row += lab_name;
  txt_row += "</td>";
  txt_row += "<td>";
  txt_row += group_name;
  txt_row += "</td>";
  txt_row += "<td>";
  txt_row += laboratory_name;
  txt_row += "</td>";
  txt_row += "<td>";
  txt_row += cost_amt;
  txt_row += "</td>";
  txt_row += "<td>";
  txt_row += sale_amt;
  txt_row += "</td>";
  txt_row += "<td>";
  txt_row += sale_opt_name;
  txt_row += "</td>";
  txt_row += '<td>';
//  txt_row += '<button class="btn btn-danger btn_del_lab_order btn-lab-order" type="button" onclick="deleteLabOrder_labtest(\''+lab_id+laboratory_id+sale_opt_id+'\');" >x</button>';
  txt_row += '</td>';
  txt_row += "</tr>";

  $("#tbl_lab_test_order tbody").append(txt_row);
  calSummary_LabOrder();
}

function calSummary_LabOrder(){
  var ttl_cost = 0; var ttl_sale= 0; var ttl_balance= 0;
  $(".r_labtest_order").each(function(ix,objx){
     ttl_cost += parseFloat($(objx).data("cost_amt"));
     ttl_sale += parseFloat($(objx).data("sale_amt"));
     ttl_balance = ttl_sale-ttl_cost;

  });
   ttl_cost_lab_order = ttl_cost;
   ttl_sale_lab_order = ttl_sale;

  $("#lab_order_ttl_cost").html(ttl_cost.toFixed(2));
  $("#lab_order_ttl_sale").html(ttl_sale.toFixed(2));
  $("#lab_order_ttl_balance").html("<b>"+ttl_balance.toFixed(2)+"</b>");


}

function deleteLabOrder_labtest(id){
//  console.log("enter delete id "+id);
  if($("#ro"+id).hasClass("new")){
    $("#ro"+id).remove();
//    console.log("enter new del");
  }
  else{ // add to delete list

     if($("#ro"+id).data("lab_id") != ""){ // there is data id
    //   console.log("enter update del");
          var arr_obj = [];
          arr_obj.push({name:"lab_id", value:$("#ro"+id).data("dataid")});
          lab_order_lst_delete_data.push(arr_obj);
          $("#ro"+id).remove();
     }
  }
  calSummary_LabOrder();
}





</script>
<? include_once("../in_savedata.php"); ?>
<? include_once("../inc_foot_include.php"); ?>
<? include_once("../function_js/js_fn_validate.php"); ?>
