
<?
/*
if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}
*/
$lab_order_id= isset($_GET["lab_order_id"])?$_GET["lab_order_id"]:"";
$uid= isset($_GET["uid"])?$_GET["uid"]:"";
$collect_date= isset($_GET["collect_date"])?$_GET["collect_date"]:"";
$collect_time= isset($_GET["collect_time"])?$_GET["collect_time"]:"";

$is_pribta= isset($_GET["is_pribta"])?$_GET["is_pribta"]:"";
$is_doctor= isset($_GET["is_doctor"])?$_GET["is_doctor"]:"1";
$s_id_room= isset($_GET["s_id_room"])?$_GET["s_id_room"]:"";
//echo "is_pribta: $is_pribta";
if(!isset($s_id)){
  $s_id= isset($_GET["s_id"])?$_GET["s_id"]:"";
}



$sale_opt_id = "";

//include('../in_db_conn.php');
include(realpath($_SERVER["DOCUMENT_ROOT"])."/weclinic/in_db_conn.php");
//include("../weclinic/in_db_conn.php");

$query = "SELECT QL.sale_opt_id
FROM i_queue_list QL
WHERE uid=? AND collect_date=? AND collect_time=?
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss",$uid, $collect_date, $collect_time);

if($stmt->execute()){
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()) {
    $sale_opt_id = $row['sale_opt_id'];
  }
}
else{
$msg_error .= $stmt->error;
}
$stmt->close();

if($sale_opt_id == '')
$sale_opt_id= isset($_GET["sale_opt_id"])?$_GET["sale_opt_id"]:"S01";


$data_attr = "data-uid='$uid' data-coldate='$collect_date' data-coltime='$collect_time' data-orderid='$lab_order_id' data-saleoptid='$sale_opt_id' data-statusid='' ";

?>




<div id='div_main_lab_order' <? echo $data_attr;?>>
  <div id="div_lab_order" class="card main-lab-order"  >
    <div class="card-header bg-primary text-white" style="max-height: 3rem;">
        <div class="row ">
           <div class="col-sm-2 ptxt-s16">
             <b><i class="fa fa-flask fa-lg" aria-hidden="true"></i> Lab Order <span id = "txt_lab_order_id"></span></b>
           </div>
           <div class="col-sm-2">
          <!--   <button type="button" class="pbtn pbtn-blue btn-lab-order-package-dlg"><i class="fa fa-tag fa-lg" ></i> Lab Order Package</button> -->
             <button type="button" class="btn btn-sm btn-warning btn-lab-order-package-dlg"><i class="fa fa-tag fa-lg" ></i> Lab Order Package</button>

           </div>
           <div class="col-sm-4">

              <b>
               <span id = "txt_lab_order_title"></span>
              </b>

           </div>

           <div class="col-sm-3">
              Status: <input type="text" id="lab_order_status" size="20" disabled>
           </div>
           <div class="col-sm-1">
             <button type="button" class="btn btn-sm btn-white mr-auto lab-btn" onclick="closeToLabOrderList();"><i class="fa fa-times fa-lg" ></i> Close</button>

           </div>



        </div>
    </div>
    <div class="card-body">
      <div class="row mt-0">
        <div class="col-sm-9 ">
          <div class="row ">
            <div class="col-sm-6 " id="div_sel_sale_option">

            </div>
            <div class="col-sm-6 " id="div_sel_laboratory_option">

            </div>
          </div>

          <div class="my-1">
              <button type="button" class="py-1 btn btn-sm btn-info btn-lab-package" data-lmt_id="LMT04" data-id="CBC"><i class="fa fa-clipboard-check fa-lg" ></i> CBC</button>
              <button type="button" class="py-1 btn btn-sm btn-info btn-lab-package" data-lmt_id="LMT03" data-id="CD4"><i class="fa fa-clipboard-check fa-lg" ></i> CD4</button>
              <button type="button" class="py-1 btn btn-sm btn-info btn-lab-package" data-lmt_id="LMT12" data-id="HIV Viral Load"><i class="fa fa-clipboard-check fa-lg" ></i> HIV Viral Load</button>
              <button type="button" class="py-1 btn btn-sm btn-info btn-lab-package" data-lmt_id="LMT14" data-id="Urine Strip"><i class="fa fa-clipboard-check fa-lg" ></i> Urine Strip</button>

              <button type="button" class="py-1 my-1 btn btn-sm btn-warning btn-lab-package2" data-pkg_lab="'HIV_Ab'" ><i class="fa fa-clipboard-check fa-lg" ></i> Anti-HIV</button>
              <button type="button" class="py-1 my-1 btn btn-sm btn-warning btn-lab-package2" data-pkg_lab="'RPR'" ><i class="fa fa-clipboard-check fa-lg" ></i> RPR</button>
              <button type="button" class="py-1 my-1 btn btn-sm btn-warning btn-lab-package2" data-pkg_lab="'E2','T'" ><i class="fa fa-clipboard-check fa-lg" ></i> Estradiol & Testosterone</button>
              <button type="button" class="py-1 my-1 btn btn-sm btn-danger btn-lab-package2" data-pkg_lab="'HIV_Ab_2nd'" ><i class="fa fa-clipboard-check fa-lg" ></i> Anti-HIV (2nd tube) </button>
<!--
              <button type="button" class="py-1 my-1 btn btn-sm btn-white  btn-lab-package2"
              data-pkg_lab="'HIV_Ab','HBsAg','HBs_Ab','HCV_Ab','TP_Ab','CHOL','LDL','HDL','TG','ALT', 'CrCl', 'CREA','BA#', 'BA%', 'EO#', 'EO%', 'Hb', 'HCT', 'LY#', 'LY%',
              'MCH', 'MCHC', 'MCV', 'MO#', 'MO%', 'MPV', 'NE#', 'NE%', 'PLT', 'RBC', 'RDW-CV', 'RDW-SD', 'WBC','INR','PTT', 'GLU' " >
              <i class="fa fa-clipboard-check fa-lg" ></i> IFACT3 Screen</button>
-->

<button type="button" class="py-1 my-1 btn btn-sm btn-white btn-lab-packageitem"
data-packageid="IFACT_SCRN" data-projid='IFACT' data-visitid='SCRN' >
<i class="fa fa-clipboard-check fa-lg" ></i> IFACT3 Screen</button>
<i class='fa fa-spinner spinner fa-2x' style='display:none;'></i>

          </div>
        </div>
        <div class="col-sm-3 px-0">
              <?

              include_once("p_lab_uid_info.php");
              ?>
        </div>
      </div>



      <div class="row my-1">
        <div class="col-sm-9">
          <div style="min-height: 300px; border:1px solid grey;">
            <table id="tbl_lab_test_order" class="table table-bordered table-sm table-striped table-hover">
                <thead>
                  <tr>
                    <th>
                      Lab Test | <span class="text-primary">Test Menu</span><br>
                      <button id="btn_add_lab_order" class="my-1 bg-success text-white btn-lab-order" type="button">
                        <i class="fa fa-plus fa-lg" ></i> ADD Lab Test
                      </button>
                    </th>
                    <th>Laboratory</th>
                    <th>Sale Option</th>
                    <th>Status</th>

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
                    <td align="right"><span id="lab_order_ttl_sale" class="save-data" data-odata=''></span></td>
                  </tr>
                  <tr class='lab-btn'>
                    <td>Cost (Baht):</td>
                    <td align="right"><span id="lab_order_ttl_cost" class="save-data"  data-odata=''></span></td>
                  </tr>

                  <tr class='lab-btn'>
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

    <div class="card-footer " id="div_lab_order_foot">
  <!--
      <button type="button" id="btn_confirm_lab_order" class="btn btn-warning mr-auto btn-lab-order pribta-btn"><i class="fa fa-clipboard-check fa-lg" ></i> Confirm Order</button>
  -->
      <span class="btn-lab-order lab-order-confirm " >Wait Lab Result? :</span>
      <select id="sel_wait_lab_result" class="btn-lab-order lab-order-confirm save-data" data-odata='' >
      <option value="" selected disabled> -Select- </option>
      <option value="1">ใช่ | Yes</option>
      <option value="0">ไม่ | No</option>
      <option value="2">ส่งคิวแล้ว | Sent Queue</option>
      </select>




      <button type="button" id="btn_cancel_lab_order" data-id = "cancel" class="btn btn-danger mr-auto  btn-lab-cancel lab-btn"><i class="fa fa-ban fa-lg" ></i> Cancel Order</button>

       <div id="div_cancel_lab_order" style="display:none;">
         Cancel Order Note: กรุณากรอกเหตุผลที่ยกเลิก Lab Order <br>
         <textarea id="txt_cancel_lab_order" rows="2"  data-odata="" placeholder="Cancel Order Reason Note" ></textarea>
         <div class="mt-1">
           <button type="button"  id="btn_cancel_lab_order_now" data-id = "cancel_now" class="btn btn-warning mx-1 btn-lab-cancel" > <i class="fa fa-save fa-lg" ></i> Cancel Order Now</button>
           <button type="button" data-id = "not_cancel"  class="btn btn-secondary mx-1 btn-lab-cancel" > <i class="fa fa-times fa-lg" ></i> Close</button>

         </div>
       </div>




      <button type="button" id="btn_lab_notify"  class="btn btn-warning mr-auto  btn-lab-notify pribta-btn" ><i class="fa fa-bell fa-lg" ></i> Notify Lab Order (แจ้งเตือนที่ห้องแล๊ป)</button> <i id="not_notify" class="fa fa-bell-slash fa-2x text-danger "></i>
      <button type="button" id="btn_save_lab_order" class="btn btn-success mx-1 float-right btn-lab-order" > <i class="fa fa-save fa-lg" ></i> Save Data</button>

    </div>
    <div id='txtcancel' style='display:none;'>
      <span class='text-danger'><b>This Lab Order is cancelled.</b></span><br>
      Lab order can be enable by order new lab test to this lab order.
    </div>


  </div>

  <div id= "div_lab_order_package_dialog" class="main-lab-order" style='display:none;'>

  </div>

</div>

<div id= "div_lab_test_sel">
<?
include_once("mnu_lab_order_lab_test.php");
?>
</div>

<?
include_once("dlg_add_lab_note.php");
?>




<script>
var divSaveData = "div_lab_order";
var u_mode_order = "update_lab_order";
var lab_order_lst_delete_data = [];
var ttl_cost_lab_order = 0;
var ttl_sale_lab_order = 0;
var p_lab_order_status = "A0";


<?
echo "
var is_pribta='$is_pribta';
var is_doctor='$is_doctor';
var s_id_update='$s_id'; // pribta s_id
var s_id_room='$s_id_room';
var cur_lab_order_sale_opt_id  = '$sale_opt_id';
";
?>


$(document).ready(function(){
  initDataLabOrder();

  $(".btn-lab-package").click(function(){
    var lab_group_id = $(this).data("lmt_id");
     addPackageLab(lab_group_id);

  }); // btn-lab-package
  $(".btn-lab-package2").click(function(){
    var pkg_lab = $(this).data("pkg_lab");
     addPackageLab2(pkg_lab);

  }); // btn-lab-package2
  $(".btn-lab-packageitem").click(function(){
    var packageid = $(this).attr("data-packageid");
    var projid = $(this).attr("data-projid");
    var visitid = $(this).attr("data-visitid");
     addPackageItem(packageid,projid,visitid,  $(this));

  }); // btn-lab-package2



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
  $(".btn-lab-cancel").click(function(){
     let choice = $(this).data("id");
     if(choice == "cancel"){
       $("#txt_cancel_lab_order").val("");
       $("#div_cancel_lab_order").show();
      // $("#txt_cancel_lab_order").notify("กรุณากรอกเหตุผลที่ยกเลิก", "info");
       $("#txt_cancel_lab_order").focus();
     }
     else if(choice == "cancel_now"){
       cancelLabTestOrder();
     }
     else if(choice == "not_cancel"){
       $("#div_cancel_lab_order").hide();
     }
  }); // btn_cancel_lab_order
/*
  $("#btn_confirm_lab_order").click(function(){
     confirmLabOrder();
  }); // btn_save_lab_order
*/
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


  $(".btn-lab-order-package-dlg").click(function(){
    openLabRequest();
  }); // btn-lab-order-package-dlg

  $("#btn_lab_notify").off('click');
  $("#btn_lab_notify").click(function(){
    notifyLab();
  }); // btn_lab_notify

});





function openLabRequest(){

  let uid = $("#div_main_lab_order").attr("data-uid");
  let sColdate = $("#div_main_lab_order").attr("data-coldate");
  let sColtime = $("#div_main_lab_order").attr("data-coltime");
  let orderid = $("#div_main_lab_order").attr("data-orderid");
  let saleoptid = $("#div_main_lab_order").attr("data-saleoptid");
  let laboratoryid = $("#sel_laboratory").val();

  let link = "lab/p_lab_order_package_dlg.php?uid="+uid+"&coldate="+sColdate+"&coltime="+sColtime+"&orderid="+orderid+"&sid="+s_id_update+"&saleoptid="+saleoptid+"&laboratoryid="+laboratoryid+"&roomid="+s_id_room;
  $(".main-lab-order").hide();
  $("#div_lab_order_package_dialog").html("");
  $("#div_lab_order_package_dialog").load(link, function(){
      $("#div_lab_order_package_dialog").show();
  });
}

function closeLabRequest(){
  $(".main-lab-order").hide();
  $("#div_lab_order_package_dialog").html("");
  selectLabTestOrder();
  $("#div_lab_order").show();
}

function initDataLabOrder(){
  //console.log("isPribta: "+is_pribta+" / isDoctor: "+is_doctor);
  if(is_pribta == "1"){ // pribta counselor, doctor
    $('.pribta-btn').show(); $('.lab-btn').hide();
    if(is_doctor=='0') $('.btn-lab-notify').hide();

  }
  else{ //lab
    $('.pribta-btn').hide(); $('.lab-btn').show();
  }

   //$('#btn_confirm_lab_order').prop("disabled", true);
   var sel_saleOption = "<select id='sel_sale_option'>"+v_sale_option+"</select>";
   var sel_laboratoryOption = "<select id='sel_laboratory'>"+v_laboratory_option+"</select>";
   $('#div_sel_laboratory_option').html("Laboratory: <br>"+sel_laboratoryOption);
   $('#div_sel_sale_option').html("Sale Option: <br>"+sel_saleOption);

   $('#sel_sale_option').val('<? echo $sale_opt_id; ?>' );


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
      collect_time:"<? echo $collect_time;?>"
  };

  save_data_ajax(aData,"lab/db_lab_test_order.php",selectLabTestOrderComplete);

}


function selectLabTestOrderComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
  //  console.log("selectLabTestOrderComplete ");

    $(".r_labtest_order").remove();
    if(rtnDataAjax.data_lab_order.length > 0){
       var dataObj = rtnDataAjax.data_lab_order[0];
       u_mode_order = "update_lab_order";
       $("#txt_lab_order_id").html(dataObj.lab_order_id) ;
       $("#div_main_lab_order").attr("data-orderid", dataObj.lab_order_id);
       $("#div_main_lab_order").attr("data-statusid", dataObj.status_id);

       $("#txt_lab_order_title").html("UID: <u>"+aData.uid+"</u> | Visit Date: <u>"+changeToThaiDate(aData.collect_date)+" "+aData.collect_time+"</u>") ;
       $("#lab_order_status").val(dataObj.status_name);
       $("#lab_order_note").val(dataObj.lab_order_note);
       $("#sel_wait_lab_result").val(dataObj.wait_lab_result);

      $("#lab_order_ttl_cost").attr("data-odata", dataObj.ttl_cost);
      $("#lab_order_ttl_sale").attr("data-odata", dataObj.ttl_sale);
      $("#sel_wait_lab_result").attr("data-odata", dataObj.wait_lab_result);

      if(dataObj.is_call != '10') $('#not_notify').hide();


       p_lab_order_status = dataObj.status_id;

       if(rtnDataAjax.data_list_labtest.length > 0){
         var datalist = rtnDataAjax.data_list_labtest;
         datalist.forEach(function (itm) {
           addRowLabTestOrder("",itm.is_paid, itm.id, itm.name, itm.dataid, itm.g_id, itm.g_name,
             itm.lbt_id, itm.lbt_name, itm.turnaround,
             itm.lab_cost, itm.lab_price, itm.sa_id, itm.sa_name,
             itm.barcode, itm.lab_status, 'N'
           );
         });
       }

       //if($("#txt_lab_order_id").html() != "")$("#sel_wait_lab_result").prop('disabled', true);

       $('#div_lab_order_note').show();

       if(p_lab_order_status == "C"){
         //$('#div_lab_order_foot').html("<center><h4>-- CANCEL ORDER --</h4></center>");
         //$('.btn-lab-order').hide();

         $('#btn_cancel_lab_order').hide();
         $('#txtcancel').show();
       }
    }
    else{
      u_mode_order = "add_lab_order";
      $("#txt_lab_order_title").html("UID: <u><? echo $uid;?></u> | Visit Date: <u>"+changeToThaiDate('<? echo $collect_date;?>') +" <? echo $collect_time;?></u> ") ;

      $("#lab_order_status").val("New Lab Order");
    //  $('#btn_confirm_lab_order').prop("disabled", true);


    }



  }
}



function saveLabTestOrder(){
  var flag_save_update = 0;

  var flag_valid = true;
  var lstDataObj = [];
  var lstDataObj_laborder = [];

  if($('#sel_wait_lab_result').val() == "" || $('#sel_wait_lab_result').val() == null){
    $('#sel_wait_lab_result').notify("Please select this.", "info");
    return;
  }



  $("#"+divSaveData +" .save-data").each(function(ix,objx){
      if($(objx).val() != $(objx).attr("data-odata"))
      flag_save_update = 1;
  });


  let flag_labtest_update = 0;
  $("#"+divSaveData +" .r_labtest_order").each(function(ix,objx){
     flag_labtest_update = 0;
     var row_id = $(objx).attr("id");

     //console.log("datarow:"+row_id+'/'+$(objx).data("isnew"));

       if($(objx).data("isnew") == 'Y'){ // add new data
         flag_labtest_update = 1;
         //console.log("update:"+row_id);
       }
       else{
         if($("#sel_laboratory_"+row_id).attr("data-odata") != $("#sel_laboratory_"+row_id).val()){
           flag_labtest_update = 1;
           //console.log("update laboratory:"+row_id);
         }
         else if($("#sel_sale_option_"+row_id).attr("data-odata") != $("#sel_sale_option_"+row_id).val()){
           flag_labtest_update = 1;
           //console.log("update sale_option:"+row_id+" - "+$("#sel_sale_option_"+row_id).data("odata")+'/'+$("#sel_sale_option_"+row_id).val());
         }
       }

       if(flag_labtest_update == 1){
         var arr_obj = {};
         arr_obj["lab_id"] = $(objx).data("dataid");
         arr_obj["lab_group_id"] = $(objx).data("group_id");
         arr_obj["laboratory_id"] = $("#sel_laboratory_"+row_id).val();
         arr_obj["sale_opt_id"] = $("#sel_sale_option_"+row_id).val();
         //arr_obj["barcode"] = $(objx).data("barcode");
         lstDataObj_laborder.push(arr_obj);
       }


  });
//console.log("flag: "+flag_save_update+"/"+flag_labtest_update);
  if(flag_save_update == "1" || flag_labtest_update == "1"){

      var lstObj = {uid:"<? echo $uid; ?>",
      collect_date:"<? echo $collect_date; ?>",
      collect_time:"<? echo $collect_time; ?>",
      ttl_cost:ttl_cost_lab_order,
      ttl_sale:ttl_sale_lab_order,
      wait_lab_result: $('#sel_wait_lab_result').val(),
      lab_order_status:p_lab_order_status,
      lst_order_lab_test: lstDataObj_laborder,
      lst_order_lab_test_delete:lab_order_lst_delete_data
      };

    //console.log("order id : "+$('#txt_lab_order_id').html());
    if(is_pribta == "1"){
      if(s_id_update == ""){
        alert('Staff id (s_id) is not found.');
        return;
      }
      else{
        if($('#txt_lab_order_id').html()==''){
            u_mode_order = 'add_lab_order';
        }
      }

    }



      var aData = {
          u_mode:u_mode_order,
          lst_data_obj:lstObj,
          staff_id_update:s_id_update,
          staff_id_room:s_id_room
      };
    //  console.log('<? echo $s_id; ?> u_mode_order '+aData.u_mode+'/'+aData.staff_id_update+'/'+$('#txt_lab_order_id').html());
      save_data_ajax(aData,"lab/db_lab_test_order.php",saveLabTestOrderComplete);

  }
  else{//no data change
    $.notify("No data changed.", "info");
  }


}

function saveLabTestOrderComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    lab_order_lst_delete_data = [];
    /*
    $('#btn_confirm_lab_order').prop("disabled", false);
    $("#lab_order_status").val("Lab Order Confirm Pending");
*/
    $.notify("Save lab order successfully.", "success");
    if(rtnDataAjax.msg_info != '') $.notify(rtnDataAjax.msg_info, 'info');

    $("#"+divSaveData +" .save-data").each(function(ix,objx){
        $(objx).attr("data-odata", $(objx).val());
    });
    $("#"+divSaveData +" .r_labtest_order").each(function(ix,objx){
      var row_id = $(objx).attr("id");
      $(objx).attr('data-isnew','N');
      $("#sel_laboratory_"+row_id).attr("data-odata",$("#sel_laboratory_"+row_id).val() );
      $("#sel_sale_option_"+row_id).attr("data-odata",$("#sel_sale_option_"+row_id).val() );
       //console.log(row_id+" set to "+$("#sel_sale_option_"+row_id).attr("data-odata"));
    });

    //alert("Data save");
    if(u_mode_order == "add_lab_order"){
      u_mode_order = "update_lab_order";
      $("#txt_lab_order_id").html(rtnDataAjax.lab_order_id) ;
      $('#div_lab_order_note').show();
    }
    else{
      $('#btn_cancel_lab_order').show();
      $('#txtcancel').hide();
    }


  }
}


function notifyLab(){

  var aData = {
      u_mode:"update_lab_order_notify",
      uid:$("#div_main_lab_order").attr("data-uid"),
      coldate:$("#div_main_lab_order").attr("data-coldate"),
      coltime:$("#div_main_lab_order").attr("data-coltime"),
      oid:$("#div_main_lab_order").attr("data-orderid"),
      is_call:"1"
  };
  //console.log("confirm "+aData.uid);
  $('#btn_lab_notify').hide();
  //$.notify("Loading....");
  save_data_ajax(aData,"lab/db_lab_test_order.php",notifyLabComplete);

}

function notifyLabComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
  //  console.log("selectLabTestOrderComplete ");
     if(rtnDataAjax.res == '1'){
       $.notify("Notify to Lab successfully.", "success");
       $('#not_notify').hide();
     }
     else{
       $('#btn_lab_notify').show();
       $.notify("Already notified.", "info");
     }

  }else{

  }

}
/*
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
*/


function addRowLabTestOrder(addnew,is_paid, lab_id, lab_name, dataid, group_id, group_name,
  laboratory_id, laboratory_name, turnaround,
  cost_amt, sale_amt, sale_opt_id, sale_opt_name,
  barcode, lab_status, is_new
){

  var row_id = lab_id+laboratory_id+sale_opt_id;


  var sel_saleOption = "<select id='sel_sale_option_"+row_id+"' data-odata='' onchange='selectNewCostSale(\""+row_id+"\");'>"+v_sale_option+"</select>";
  var sel_laboratoryOption = "<select id='sel_laboratory_"+row_id+"' data-odata='' onchange='selectNewCostSale(\""+row_id+"\");'>"+v_laboratory_option+"</select>";

  var lab_result_status = "[Wait Lab]";
  if(lab_status != null) {
    if(lab_status != "" && lab_status != "L0") lab_result_status = "[Complete]";
  }

  if(is_paid=='1') lab_result_status += "<div class='text-success'><b>PAID</b></div>";


  var cost_amt_txt = cost_amt;
  if(cost_amt == null) {
    cost_amt = 0;
    cost_amt_txt = "<b>Not defined from Lab</b>";
  }

  var sale_amt_txt = sale_amt;
  if(sale_amt == null) {
    sale_amt = 0;
    sale_amt_txt = "<b>Not defined from Lab</b>";
  }


  cost_amt_txt = (is_pribta != '1')?"Cost: "+cost_amt_txt+" Baht |":"";

// if isnew=Y  new labtest pending to insert into database (p_lab_order_lab_test)
  var txt_row = "<tr class='r_labtest_order "+addnew+"' id='"+row_id+"' data-isnew='"+is_new+"' data-ispaid='"+is_paid+"' data-lab_id='"+lab_id+"'  ";

  txt_row += "data-laboratory_id='"+laboratory_id+"' data-sale_opt_id='"+sale_opt_id+"' data-barcode='"+barcode+"'  ";
  txt_row += "data-dataid='"+dataid+"' data-group_id='"+group_id+"'  data-sale_amt='"+sale_amt+"' data-cost_amt='"+cost_amt+"' >";

  txt_row += "<td><b>";
  txt_row += lab_name;
  txt_row += "</b><br><span class='text-primary'>"+group_name+"</span>";
  txt_row += "</td>";
  txt_row += "<td>";
  txt_row += sel_laboratoryOption;
  txt_row += "<br><small>Turnaround: <span id='turnaround"+row_id+"'>"+turnaround+"</span> hrs.</small>";
  txt_row += "</td>";
  txt_row += "<td>";
  txt_row += sel_saleOption;
  txt_row += "<br><small><span id='costsale"+row_id+"'>"+cost_amt_txt+" Sale: "+sale_amt_txt+" Baht</span></small>";
  txt_row += "</td>";
  txt_row += "<td>";
  txt_row += "<small>"+lab_result_status+"</small>";
  txt_row += "</td>";
  txt_row += '<td>';
  txt_row += '<button class="btn btn-danger btn_del_lab_order btn-lab-order" type="button" onclick="deleteLabOrder_labtest(\''+row_id+'\');" >x</button>';
  txt_row += '</td>';
  txt_row += "</tr>";


  $("#tbl_lab_test_order tbody").append(txt_row);

  calSummary_LabOrder();

  $("#sel_sale_option_"+row_id).val(sale_opt_id);
  $("#sel_laboratory_"+row_id).val(laboratory_id);

  $("#sel_sale_option_"+row_id).attr("data-odata", sale_opt_id);
  $("#sel_laboratory_"+row_id).attr("data-odata", laboratory_id);

}

function selectNewCostSale(rowID){
   var saleOptID = $("#sel_sale_option_"+rowID).val();
   var laboratoryID = $("#sel_laboratory_"+rowID).val();
   var labID = $("#"+rowID).data("dataid");
  // console.log(rowID+" : "+saleOptID+"/"+laboratoryID+"/"+labID);

   var aData = {
       u_mode:"select_sale_cost_labtest",
       lab_id:labID,
       sale_opt_id:saleOptID,
       laboratory_id:laboratoryID,
       row_id:rowID
   };
   save_data_ajax(aData,"lab/db_lab_test_order.php",selectNewCostSaleComplete);

}
function selectNewCostSaleComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

    if(rtnDataAjax.data_obj != null){

      var data_obj = rtnDataAjax.data_obj;
      var msg_warning = "";
      var msg_cost_sale = "";
      var rowID = aData.row_id;


      if(data_obj.cost == null){
        msg_cost_sale += " Cost: <b>ไม่ได้กำหนดไว้ ติดต่อแล๊ป</b>";
        msg_warning += "ราคาทุนไม่ได้ถูกตั้งไว้กับ "+aData.lab_id+" / "+aData.laboratory_id;
        $("#sel_laboratory_"+rowID).notify("ราคาทุนไม่ได้ถูกตั้งไว้กับ "+aData.lab_id+" / "+aData.laboratory_id, "info");
      }

      if(data_obj.sale == null){
        msg_cost_sale += " Sale: <b>ไม่ได้กำหนดไว้ ติดต่อแล๊ป</b>";
        msg_warning += " ราคาขายไม่ได้ถูกตั้งไว้กับ "+aData.lab_id+" / "+aData.sale_opt_id;
        $("#sel_sale_option_"+rowID).notify(" ราคาขายไม่ได้ถูกตั้งไว้กับ "+aData.lab_id+" / "+aData.sale_opt_id, "info");
      }

      if(msg_warning == ""){

        $("#"+rowID).data("sale_amt", data_obj.sale);
        $("#"+rowID).data("cost_amt", data_obj.cost);
        calSummary_LabOrder();

        msg_cost_sale += "Cost: <b>"+data_obj.cost+"</b> Baht";
        msg_cost_sale += " Sale: <b>"+data_obj.sale+"</b> Baht";
        $("#costsale"+rowID).html(msg_cost_sale);
        $("#turnaround"+rowID).html(data_obj.turnaround);

        $("#"+rowID).data("sale_opt_id", $("#sel_sale_option_"+rowID).val() );
        $("#"+rowID).data("laboratory_id", $("#laboratory_id"+rowID).val() );
      }
      else{
        alert(msg_warning);
        $("#sel_sale_option_"+rowID).val($("#"+rowID).data("sale_opt_id"));
        $("#sel_laboratory_"+rowID).val($("#"+rowID).data("laboratory_id"));
      }
    }
    else{
      $.notify("No data found.", "info");
    }


  }
}

function calSummary_LabOrder(){
  var ttl_cost = 0; var ttl_sale= 0; var ttl_balance= 0;
  $(".r_labtest_order").each(function(ix,objx){
     ttl_cost += parseFloat($(objx).data("cost_amt"));
     ttl_sale += parseFloat($(objx).data("sale_amt"));
     ttl_balance = ttl_sale-ttl_cost;

  });
   ttl_cost_lab_order = ttl_cost.toFixed(2);
   ttl_sale_lab_order = ttl_sale.toFixed(2);



  $("#lab_order_ttl_cost").html(ttl_cost_lab_order);
  $("#lab_order_ttl_sale").html(ttl_sale_lab_order);
  $("#lab_order_ttl_balance").html("<b>"+ttl_balance.toFixed(2)+"</b>");

}

function deleteLabOrder_labtest(id){
//  console.log("enter delete id "+id);
  if($("#"+id).hasClass("new")){
    $("#"+id).remove();
  }
  else{ // add to delete list

    if($("#"+id).data("dataid") != ""){
      let sUid = $("#div_main_lab_order").attr("data-uid");
      let sColdate = $("#div_main_lab_order").attr("data-coldate");
      let sColtime = $("#div_main_lab_order").attr("data-coltime");


       let s_lab_id = $("#"+id).data("dataid");

       var aData = {
           u_mode:"remove_lab_order_dlg",
           uid: sUid,
           collect_date:sColdate,
           collect_time: sColtime,
           lab_id:s_lab_id
       };

       //startLoad(btnclick, btnclick.next(".spinner"));
        callAjax("lab/db_lab_test_order.php",aData,function(rtnObj,aData){
        //  endLoad(btnclick, btnclick.next(".spinner"));
              if(rtnObj.res=='1'){
                 $.notify('Delete Lab id: '+aData.lab_id, 'info');
                 selectLabTestOrder();
               }
               else{
                 $.notify("Fail to delete lab order.", "error");
                 if(rtnObj.msg_error !='')
                 $.notify(rtnObj.msg_error, "error");

                 alert("ไม่สามารถลบ Lab Test นี้ได้ เนื่องจากอาจมีการชำระเงินแล้ว หรือออกผลแล็ปแล้ว");
               }

             });// call ajax


    }
  }
}



/*
function deleteLabOrder_labtest(id){
//  console.log("enter delete id "+id);
  if($("#"+id).hasClass("new")){
    $("#"+id).remove();
  }
  else{ // add to delete list

     if($("#"+id).data("lab_id") != ""){ // there is data id
          let flag_del = true;
          if($("#"+id).attr("data-ispaid") == 1){
            flag_del = confirm("Lab Test นี้จ่ายเงินแล้ว คุณต้องการยืนยันจะลบใช่หรือไม่? (หากลบแล้ว ต้องกด Save ตามจึงจะลบออกจากระบบ)");
          }

          if(flag_del){
            var arr_obj = {"lab_id":$("#"+id).data("dataid")};
            lab_order_lst_delete_data.push(arr_obj);
            $("#"+id).remove();
          }
     }
  }
  calSummary_LabOrder();
}
*/


function addPackageLab(labGroupID){
  var lab_id_order = []; // list lab id to ignore to search
  $(".r_labtest_order").each(function(ix,objx){
    lab_id_order.push($(objx).data("lab_id"));
  });

  //console.log("confirmLabOrder "+$("#sel_wait_lab_result").val());
    var aData = {
        u_mode:"add_package_labtest",
        lab_group_id:labGroupID,
        sale_opt_id:$("#sel_sale_option").val(),
        laboratory_id:$("#sel_laboratory").val(),
        not_lab_id:lab_id_order
    };
    save_data_ajax(aData,"lab/db_lab_test_order.php",addPackageLabComplete);

}

function addPackageLab2(pkg_lab){
  var lab_id_order = []; // list lab id to ignore to search
  $(".r_labtest_order").each(function(ix,objx){
    lab_id_order.push($(objx).data("lab_id"));
  });

  //console.log("addPackageLab2 "+pkg_lab);
    var aData = {
        u_mode:"add_package_labtest2",
        package_lab:pkg_lab,
        sale_opt_id:$("#sel_sale_option").val(),
        laboratory_id:$("#sel_laboratory").val(),
        not_lab_id:lab_id_order
    };
    save_data_ajax(aData,"lab/db_lab_test_order.php",addPackageLabComplete);

}

function addPackageLabComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){

    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      datalist.forEach(function (itm) {
//console.log("saId: "+itm.sa_id+"/"+itm.sa_name);
        addRowLabTestOrder("new",'0', itm.id, itm.name, itm.dataid, itm.g_id, itm.g_name,
          itm.lbt_id, itm.lbt_name, itm.turnaround,
          itm.lab_cost, itm.lab_price, itm.sa_id, itm.sa_name,'','', 'Y');

      });
    }
    else{
      $.notify("No lab test found.", "info");
    }


  }
}
function addPackageItem(sPackageid, sProjid,sVisitid,  btnclick){
  let sUid = $("#div_main_lab_order").attr("data-uid");
  let sColdate = $("#div_main_lab_order").attr("data-coldate");
  let sColtime = $("#div_main_lab_order").attr("data-coltime");

    var aData = {
        u_mode:"add_package_item",
        packageid:sPackageid,
        projid:sProjid,
        visitid:sVisitid,
        uid:sUid,
        coldate:sColdate,
        coltime:sColtime
    };

      startLoad(btnclick, btnclick.next(".spinner"));
      callAjax("lab/db_lab_test_order.php",aData,function(rtnObj,aData){
        endLoad(btnclick, btnclick.next(".spinner"));
        if(rtnObj.res=='1'){
          $.notify('Add Lab Package: '+aData.packageid+' in Lab Order:'+rtnObj.laborderid, 'success');
          selectLabTestOrder();
        }
        else{
          $.notify("Fail to add lab order.", "error");
        }

      });// call ajax

}


function cancelLabTestOrder(){
  if($("#txt_cancel_lab_order").val().trim() == ""){
    $("#btn_cancel_lab_order_now").notify("กรุณากรอกเหตุผลที่ยกเลิก", "error");
    return;
  }

  var lst_data = {};
  lst_data["uid"]="<? echo $uid; ?>";
  lst_data["collect_date"]="<? echo $collect_date; ?>";
  lst_data["collect_time"]="<? echo $collect_time; ?>";
  lst_data["cancel_note"]=$("#txt_cancel_lab_order").val().trim();

  var aData = {
      u_mode:"cancel_lab_order",
      lst_data_obj:lst_data,
      cancel_note: $("#txt_cancel_lab_order").val().trim()
  };
  save_data_ajax(aData,"lab/db_lab_test_order.php",cancelLabTestOrderComplete);

}
function cancelLabTestOrderComplete(flagSave, rtnDataAjax, aData){
//  alert("flag save is : "+flagSave);
  if(flagSave){

    if(rtnDataAjax.msg_cancel_error == ""){
      if(rtnDataAjax.affect_row != '0'){
        $.notify("Cancel Lab Order Successfully.", "info");
        $('#btn_cancel_lab_order').hide();
        $('#div_cancel_lab_order').hide();

        $('#txtcancel').show();
        //$('#div_lab_order_foot').html("<center><h4>-- CANCEL ORDER --</h4></center>");
      }
    }
    else{
      alert("ERROR: "+rtnDataAjax.msg_cancel_error);
      $.notify(rtnDataAjax.msg_cancel_error, "error");
    }

  }
}

function close_cancelLabTestOrder(){
  $("#div_cancel_lab_order").hide();
  $('#txtcancel').hide();
  $("#btn_cancel_lab_order").show();
}


</script>

<?
/*
include_once("../in_savedata.php");
include_once("../inc_foot_include.php");
include_once("../function_js/js_fn_validate.php");
*/
?>
