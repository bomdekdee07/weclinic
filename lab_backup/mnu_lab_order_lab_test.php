

<!-- The Modal select -->
<div class="modal fade" id="modal_select_lab_test" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h4 class="modal-title ">
          <i class="fa fa-vials fa-lg" aria-hidden="true"></i>
          Lab Test</h4>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="div_modal_select_lab_order_detail" style="overflow-y: auto;">
        <div class="row mt-0">
          <div class="col-sm-4  px-1">
            <label for="txt_search_lab_test"> <center><input type="checkbox" id="is_outsource_lab" > Inc. Outsource Lab</center></label>
            <input type="text" id="txt_search_lab_test" class="form-control form-control-sm" placeholder="พิมพ์คำค้นหา">
          </div>
          <div class="col-sm-3 px-1">
            <label for="sel_test_menu">Test Menu:</label>
            <select id="sel_test_menu" class="form-control form-control-sm" >

            </select>
          </div>
          <div class="col-sm-3  px-1">
            <label for="sel_sale_option2">Sale Option:</label>
            <select id="sel_sale_option2" class="form-control form-control-sm" >

            </select>
          </div>

           <div class="col-sm-2">
            <label for="btn_search_lab_test" class="text-white">.</label>
            <button class="btn btn-info form-control" type="button" id="btn_search_lab_test"><i class="fa fa-search" ></i> ค้นหา</button>
           </div>
         </div>

         <div style="min-height: 300px; border:1px solid grey;">
           <table id="tbl_lab_test_select" class="table table-bordered table-sm table-striped table-hover">
               <thead>
                 <tr>
                   <th></th>
                   <th>Lab Test</th>
                   <th>Test Menu</th>
                   <th>Laboratory</th>
                   <th>Cost</th>
                   <th>Price</th>
                   <th>Sale Option</th>
                 </tr>
               </thead>
               <tbody>

               </tbody>
           </table>
         </div>

      </div>


      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" id="btn_lab_test_select" class="btn btn-primary mr-auto"><i class="fa fa-clipboard-check fa-lg" ></i> Select</button>
        <button type="button" id="btn_lab_test_close" class="btn btn-secondary" data-dismiss="modal"> <i class="fa fa-times fa-lg" ></i> Close</button>
      </div>



    </div>
  </div>
</div>


<script>
var row_num_labtest_select = 0;
initDataLabOrder_modal_labtest();

$(document).ready(function(){
  $("#btn_search_lab_test").click(function(){
     searchLabTest();
  }); // btn_search_lab_test

  $("#txt_search_lab_test").on("keypress",function (event) {
    if (event.which == 13) {
      searchLabTest();
    }
  });

  $(document).on('click', '.chk-labtest-sel', function() {
     var lab_id = $(this).data("lab_id");
     //console.log("chk:"+lab_id+"/"+$(this).attr("id")+encodeURI($(this).attr("id")));

     $("."+lab_id+":not(#"+$(this).attr("id")+")").prop("checked", false);

  }); // chk-labtest-sel

  $("#btn_lab_test_select").click(function(){
    $(".chk-labtest-sel:checked").each(function(ix,objx){
    //  console.log("grab : "+$(objx).data("lab_id"));
      var row_id = $(objx).data("lab_id")+$(objx).data("laboratory_id")+$(objx).data("sale_opt_id");
      var lab_id = $(objx).data("lab_id");
/*
      console.log("grab 1: "+  $('#r_'+row_id).find('td:nth-child(0)').text());
      console.log("grab 2 "+  $('#r_'+row_id).find('td:nth-child(1)').text());
      console.log("grab 3 "+  $('#r_'+row_id).find('td:nth-child(2)').text());
      console.log("grab 4 "+  $('#r_'+row_id).find('td:nth-child(3)').text());
      console.log("grab 5 "+  $('#r_'+row_id).find('td:nth-child(4)').text());
      console.log("grab 6 "+  $('#r_'+row_id).find('td:nth-child(5)').text());
      console.log("grab 7 "+  $('#r_'+row_id).find('td:nth-child(6)').text());
      console.log("grab 8 "+  $('#r_'+row_id).find('td:nth-child(7)').text());
      console.log("grab 9 "+  $('#r_'+row_id).find('td:nth-child(8)').text());
*/

/*
addRowLabTestOrder(addnew, lab_id, lab_name, dataid, group_id, group_name,
  laboratory_id, laboratory_name, turnaround,
  cost_amt, sale_amt, sale_opt_id, sale_opt_name,
  barcode, lab_status)
*/
      addRowLabTestOrder("new","0", $(objx).data("lab_id"),
      $('#r_'+row_id).find('td:nth-child(2)').text(),
      $(objx).data("dataid"),
      $(objx).data("group_id"), $('#r_'+row_id).find('td:nth-child(3)').text(),
      $(objx).data("laboratory_id"), $('#r_'+row_id).find('td:nth-child(4)').text(),
      $(objx).data("turnaround"), $(objx).data("cost_amt"), $(objx).data("sale_amt"),
      $(objx).data("sale_opt_id"), $('#r_'+row_id).find('td:nth-child(7)').text(),
      "", "",'Y'
    );

      $('#r_'+row_id).remove();
    });
  //  $('#modal_select_lab_test').modal('hide');

  }); // btn_search_lab_test


});

function initDataLabOrder_modal_labtest(){
  selectLabTestMenuList();
  selectLabTestSaleOption();

  row_num_labtest_select = 0;
  $("#txt_search_lab_test").val("");
  $("#txt_search_lab_test").focus();
}


function selectLabTestMenuList(){
  //alert("selectLabTestMenuList");
  var aData = {
      u_mode:"select_lab_test_menu_list"
  };
  save_data_ajax(aData,"lab/db_lab_test_menu.php",selectLabTestMenuListComplete);

}

function selectLabTestMenuListComplete(flagSave, rtnDataAjax, aData){
//  alert("selectLabTestMenuListComplete flag save is : "+flagSave);
  if(flagSave){
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){
    //  $("#sel_test_menu").val([]);
      $("#sel_test_menu").empty();
      $("#sel_test_menu").append(new Option("All Lab Testing Menu", "%"));
      var datalist = rtnDataAjax.datalist;
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            $("#sel_test_menu").append(new Option(dataObj.name, dataObj.id));
        }//for

    }
    else{
      $.notify("No lab test menu found.", "info");
    }
  }
}





function selectLabTestSaleOption(){
  var aData = {
      u_mode:"select_setting_list",
      setting_choice:"sale_option"

  };
  save_data_ajax(aData,"lab/db_lab_setting.php",selectLabTestSaleOptionComplete);
}

function selectLabTestSaleOptionComplete(flagSave, rtnDataAjax, aData){

  if(flagSave){
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){
    //  $("#sel_test_menu").val([]);
      $("#sel_sale_option2").empty();
      $("#sel_sale_option2").append(new Option("All Sale Option", "%"));
      var datalist = rtnDataAjax.datalist;
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            $("#sel_sale_option2").append(new Option(dataObj.name, dataObj.id));
        }//for
      $("#sel_sale_option2").val(cur_lab_order_sale_opt_id);
    }
    else{
      $.notify("No sale option found.", "info");
    }
  }
}


function searchLabTest(){
  var lab_id_order = []; // list lab id to ignore to search
  $(".r_labtest_order").each(function(ix,objx){
  //   console.log("enter "+$(objx).data("lab_id"));
    lab_id_order.push($(objx).data("lab_id"));
  });

  //if(lab_id_order != "") lab_id_order = lab_id_order.substring(0, lab_id_order.length-1);

  var inc_outsource = "0";
  if($("#is_outsource_lab").prop("checked") == true) inc_outsource = "1";

  var aData = {
      u_mode:"select_lab_test_costsale",
      group_id:$("#sel_test_menu").val(),
      sale_opt_id:$("#sel_sale_option2").val(),
      txt_search:$("#txt_search_lab_test").val(),
      is_outsource:inc_outsource,
      not_lab_id:lab_id_order
  };

  save_data_ajax(aData,"lab/db_lab_test_order.php",searchLabTestComplete);
}

function searchLabTestComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var txt_row="";
    row_num_labtest_select = 0;
    $(".r_labtest_sel").remove();
    if(rtnDataAjax.datalist.length > 0){
    //  $("#sel_test_menu").val([]);

      var datalist = rtnDataAjax.datalist;
      datalist.forEach(function (itm) {
        addRowLabTestSelect(itm.id, itm.name, itm.dataid, itm.g_id, itm.g_name,
          itm.lbt_id, itm.lbt_name, itm.turnaround,
          itm.lab_cost, itm.lab_price, itm.sa_id, itm.sa_name);
      });

    }
    else{
      $.notify("No lab test found.", "info");
    }
  }
}


function addRowLabTestSelect(lab_id, lab_name, dataid, group_id, group_name,
  laboratory_id, laboratory_name, turnaround,
  cost_amt, sale_amt, sale_opt_id, sale_opt_name){

  row_num_labtest_select += 1;

  var row_id = lab_id+laboratory_id+sale_opt_id
  var txt_row = "<tr class='r_labtest_sel' id='r_"+row_id+"'>";

  txt_row += "<td>";
  txt_row += "<input type='checkbox' class='chk-labtest-sel "+lab_id+"' id='"+lab_id+laboratory_id+sale_opt_id+"' data-row='"+row_num_labtest_select+"' ";

  txt_row += "data-lab_id='"+lab_id+"' data-dataid='"+dataid+"'  data-group_id='"+group_id+"' data-laboratory_id='"+laboratory_id+"'  data-sale_opt_id='"+sale_opt_id+"' ";
  txt_row += "data-sale_amt='"+sale_amt+"' data-cost_amt='"+cost_amt+"' data-turnaround='"+turnaround+"'  >";
  txt_row += "</td>";

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
  txt_row += "</tr>";

  $("#tbl_lab_test_select tbody").append(txt_row);

}

</script>
