<?
//echo "formId01";
if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

include_once("inc_auth.php"); // set permission view, update, delete
include_once("../a_app_info.php");

$form_id = isset($_GET["form_id"])?$_GET["form_id"]:"";
?>


<script>

//**** form  var

var last_data_seq = 0;
var cur_data_id = ""; // current selected data id (for show_rule, put_after, data_prop)
//****

</script>


<style>
.r_form_data {
  border: 1px solid #000;
  padding: 8px 1px;
}
.fdata-comp {
  background-color: #96EBEB;
}



.screenFiller {
    position: absolute;
    top: 22; right: 0; bottom: 0; left: 0;
    border: 2px solid orange
}


#modal_data_prop .modal-lg {
  /*  max-height: 80%; */
    max-width: 90%;
}

</style>

<div id="form_editor" style="min-height:500px;" data-formid='<? echo $form_id;?>'>

  <div class="row " >
     <div class="col-sm-6" id="div_form_editor_search">
          <table width='100%'>
            <tr><td>
              <button class="bg-warning btn_toggle_editor_search" type="button" ><i class="fa fa-angle-double-left" ></i></button>
            </td><td><b>Data Items </b></td>
            <td align="right">
              <div>

              <button class="bg-warning btn-form-component" type="button" data-id="colhead">Header Column</button>
             </div>
            </td>
            </tr>
            <tr><td>Main:</td><td colspan=2><select id = "sel_data_group_main"></select></td></tr>
            <tr><td>Group:</td><td colspan=2><select id = "sel_data_group"></select></td></tr>
            <tr><td>Search:</td>
              <td colspan=2>
                <div>
                <input type="text" id="txt_search_data_item">
                <button class="bg-primary text-white" type="button" id="btn_search_data_list"><i class="fa fa-search" ></i> Search</button>
              </div>

              </td></tr>
          </table>
          <div class="my-2"  style="min-height: 300px; border:1px solid grey;">
            <table id="tbl_data_list_item" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
                <thead>
                  <tr>
                    <th>
                      <button class="bg-primary text-white" type="button" id="btn_select_all_data_item">SelectAll</button>
                    </th>
                    <th>Data ID / DataName</th>
                    <th>Group / Main</th>
                    <th>Data Type</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
          </div>
     </div>
     <div class="col-sm-6" id="div_form_editor_detail">
          <div class="row">
               <div class="col-sm-2" >
                 <button class="bg-warning btn_toggle_editor_search" id="btn_show_search_data" type="button" style="display:none;"><i class="fa fa-angle-double-right" ></i></button>
 <b>Data SEQ</b>
               </div>
               <div class="col-sm-9" >
                  <b>Data Item</b>
               </div>
               <div class="col-sm-1" >

               </div>
          </div>
          <div id="div_form_editor_head" class="screenFiller" style="min-height: 500px; overflow-y: auto;"> </div>
          <div id="div_form_editor_foot"></div>
     </div>
  </div>


</div> <!-- div_form_editor -->





<script>
var flag_show_search = false;
$(document).ready(function(){

  searchData_DataGroupMain_Dropdown();
  searchData_DataGroup_Dropdown();


    $("#sel_data_group_main").change(function(){
      //console.log("id "+$(this).val());
      $('#sel_data_group').val("");
      var val = $(this).val()
      if (val == "") { // if there is no value means first option is selected
        $('#sel_data_group option').hide();
      }
      else {
        $('#sel_data_group option').hide().filter(function(){
          if(val == $(this).data('main_id')) return true;
        }).show();
      }
    }); // sel_data_group_main


$("#txt_search_data_item").on("keypress",function (event) {
//  console.log("key "+event.which);
  if (event.which == 13) {
    searchData_DataList();
  }
});

  $("#btn_search_data_list").click(function(){
     searchData_DataList();
  }); // btn_search_form
  $("#btn_select_all_data_item").click(function(){

     addFormDataAll();
  }); // btn_search_form
  $(".btn_toggle_editor_search").click(function(){
      showhideFormDataSearch();

  }); // btn_toggle_editor_search

  $(".btn-form-component").click(function(){
      addFormComponent($(this).data("id"));
  }); // btn-form-component

   openFormEditor('<? echo $form_id; ?>');
});

  function openFormView(){
     let formID = $("#form_editor").attr("data-formid");
      /*
       var link = "mnu_form_view_log.php?form_id="+formID+"&lang=th&uid=TEST&collect_date=0000-00-00&collect_time=00:00:00";

       var sFPath = window.location.pathname;
       aFP = sFPath.split("/");
       iCnt = aFP.length;
       $.each(aFP, function( iIn, sVal ) {
           if(iIn!=iCnt-1 && sVal!="") sPath += sVal+"/";
       });
       */
       var link = "pribta21/ext_index2.php?file=p_form_view_log&formid="+formID+"&lang=th&uid=TEST";

       //var link = "pribta21/p_form_view_log.php?form_id="+formID+"&lang=th&uid=TEST";
       var sPath = window.location.origin+"/";
       sPath += link;

       window.open(sPath, "_blank");
  }

  function showhideFormDataSearch(){

       if(flag_show_search){ // show
         $("#div_form_editor_search").addClass("col-sm-6");
         $("#div_form_editor_detail").removeClass("col-sm-12");
         $("#div_form_editor_detail").addClass("col-sm-6");
         $("#div_form_editor_search").show();
         $("#btn_show_search_data").hide();
         flag_show_search = false;
       }
       else{ // hide
         $("#div_form_editor_search").removeClass("col-sm-6");
         $("#div_form_editor_search").hide();
         $("#div_form_editor_detail").removeClass("col-sm-6");
         $("#div_form_editor_detail").addClass("col-sm-12");
         $("#btn_show_search_data").show();

         flag_show_search = true;
       }
  }//showhideFormDataSearch




  function clearData_FormEditor(){
  //  $('.r_data').remove();
    cur_form_id = "";
    $(".save-data").val("");
    $(".input-text-code").prop("disabled", false);
    $("form_id").focus();
  }

      function searchData_DataGroupMain_Dropdown(){
        var aData = {
            u_mode:"select_data_group_main_dropdown",
            is_log:"1"
        };
        save_data_ajax(aData,"data_mgt/db_data_group_main.php",searchData_DataGroupMain_Dropdown_Complete);
      }

      function searchData_DataGroupMain_Dropdown_Complete(flagSave, rtnDataAjax, aData){
        //alert("flag save is : "+flagSave);
        if(flagSave){
          var txt_row="";
          if(rtnDataAjax.datalist.length > 0){
          //  $("#sel_test_menu").val([]);
            $("#sel_data_group_main").empty();
            $("#sel_data_group_main").append(new Option("All Data", ""));
            var datalist = rtnDataAjax.datalist;
            for (i = 0; i < datalist.length; i++) {
                var dataObj = datalist[i];
                $("#sel_data_group_main").append(new Option(dataObj.name+" ["+dataObj.id+"]", dataObj.id));


            }//for
          }
          else{
            $.notify("No record found.", "info");
          }
        }
      }

    function searchData_DataGroup_Dropdown(){
      var aData = {
          u_mode:"select_data_group_dropdown",
          is_log:"1"
      };
      save_data_ajax(aData,"data_mgt/db_data_group.php",searchData_DataGroup_Dropdown_Complete);
    }

    function searchData_DataGroup_Dropdown_Complete(flagSave, rtnDataAjax, aData){
      //alert("flag save is : "+flagSave);
      if(flagSave){
        var txt_row="";
        if(rtnDataAjax.datalist.length > 0){
        //  $("#sel_test_menu").val([]);
          $("#sel_data_group").empty();
          $("#sel_data_group").append(new Option("All Data", ""));
          var datalist = rtnDataAjax.datalist;
            for (i = 0; i < datalist.length; i++) {
                var dataObj = datalist[i];
              //  $("#sel_data_group").append(new Option("["+dataObj.id+"] "+dataObj.name, dataObj.name));
                $('#sel_data_group')
                  .append($('<option />')  // Create new <option> element
                  .val(dataObj.id)
                  .text(dataObj.name+" ["+dataObj.id+"]")
                  .data({                  // Set multiple data-* attributes
                      main_id: dataObj.main_id
                  })
                );

            }//for

         $('#sel_data_group option').hide(); // show only first value (all data)


        }
        else{
          $.notify("No record found.", "info");
        }
      }
    }



    function searchData_DataList(){
      var aData = {
          u_mode:"select_form_data_item",
          group_main_id: $("#sel_data_group_main").val(),
          group_id: $("#sel_data_group").val(),
          form_id: cur_form_id,
          txt_search:$("#txt_search_data_item").val().trim()
      };
      save_data_ajax(aData,"data_mgt/db_form_editor_log.php",searchData_DataList_Complete);
    }

    function searchData_DataList_Complete(flagSave, rtnDataAjax, aData){
      //alert("flag save is : "+flagSave);
      if(flagSave){
        $('.r_dataitm').remove(); // row data list
        var txt_row="";
        if(rtnDataAjax.datalist.length > 0){

          var datalist = rtnDataAjax.datalist;
          var txt_row = "";
            for (i = 0; i < datalist.length; i++) {
                var dataObj = datalist[i];
                addRowData_DataList(
                 dataObj.gm_id,dataObj.gm_name, dataObj.g_id, dataObj.g_name,
                 dataObj.d_id, dataObj.d_name_th, dataObj.d_name_en, dataObj.d_type
                );

            }//for

            $('#tbl_data_list_item > tbody:last-child').append(txt_row);
        }
        else{
          $.notify("No record found.", "info");
        }
      }
    }



    function addRowData_DataList(group_main_id,group_main_name,
      group_id, group_name,data_id, data_name_th,data_name_en, data_type){

        var txt_row = '<tr class="r_dataitm" id="r'+data_id+'" data-id="'+data_id+'"  data-group_id="'+group_id+'" data-group_main_id="'+group_main_id+'" >' ;
        txt_row += '<td width="7%">';
        txt_row += '<button class="btn btn-primary" type="button" onclick="addFormData(\''+data_id+'\');"> Select</button>';
        txt_row += '</td>';
        txt_row += '<td width="40%" >'+data_id+'<br>';
        txt_row += '<b>'+data_name_th +'/'+data_name_en+'</b></td>';

        txt_row += '<td width="35%" ><span class="text-primary">['+group_main_id+'] '+group_main_name+'</span><br>';
        txt_row += '<span class="text-danger">['+group_id+'] '+group_name+'</span></td>';
        txt_row += '<td >'+data_type+'</td>';
        txt_row += '</tr">';
        $("#tbl_data_list_item tbody").append(txt_row);

   }



function clearFormEditor(){
  flag_show_search = true;
  cur_form_id = "";
  $(".r_form_data").remove();
  $(".r_dataitm").remove();
  $("#sel_data_group_main").val("");
  $("#sel_data_group").val("");
  $("#txt_search_data_item").val("");


}

   function openFormEditor(formID){ // openFormEditor

     //$(".txt_form_name").html(" ["+formID+"] "+formName);
     $('.txt_form_name').html("["+$('#r'+formID).find('td:nth-child(1)').text()+"] "+$('#r'+formID).find('td:nth-child(2)').text());

       var aData = {
           u_mode:"select_form_editor_detail",
           form_id:formID
           };
       save_data_ajax(aData,"data_mgt/db_form_editor_log.php",openFormEditor_Complete);
   }

   function openFormEditor_Complete(flagSave, rtnDataAjax, aData){
       if(flagSave){
         clearFormEditor();

         var data_list = rtnDataAjax.data_obj_list;
         data_list.forEach(function(itm) {
          // console.log("data: "+itm.data_id);
           //addRow_DataForm(data_id, data_name,  data_type, data_seq, data_sub_list){
            if(itm.data_type == 'colhead')
            addRow_Colhead(itm.data_id, itm.data_type ,itm.data_seq, itm.data_value);
            else{
              addRow_DataForm(itm.data_id, itm.data_name,  itm.data_type, itm.data_seq, itm.is_require);
            }

         });


         var data_sub_list = rtnDataAjax.data_obj_sub_list;
         data_sub_list.forEach(function(itm) {
           addRow_SubItem(itm);
         });

         cur_form_id = aData.form_id;
         showhideFormDataSearch();
         showFormDiv("editor");
       }
   }



    function addRow_DataForm(data_id, data_name,  data_type, data_seq, is_require){
      var data_type_list = "";
      var check_is_require = "";

      if(is_require == '1') check_is_require = "checked";

      if(data_type == "radio" || data_type == "dropdown") data_type_list += "fdata_list ";
      else if(data_type == "checkbox") data_type_list += "fdata_check ";

      var btn_update = '<button class="bg-warning" type="button" onclick="updateCompVal(\''+data_id+'\');"> Update Seq</button>';


      var txt_row = '<div class="row r_form_data fdata '+data_type_list+'" id="r'+data_id+'" data-id="'+data_id+'"  data-type="'+data_type+'" data-name="'+data_name+'"  >' ;
      txt_row += '<div class="col-sm-2 pr-0">';
      txt_row += '<div><input type="text" data-odata="'+data_seq+'"  data-col="data_seq" data-id="'+data_id+'"  class="c'+data_id+' dataseq"  size="2" value="'+data_seq+'" /> ';
      txt_row += ' <small><input type="checkbox" id="c'+data_id+'" class="c'+data_id+' chk_require" data-col="is_require" data-id="'+data_id+'"  data-odata="'+is_require+'" value="'+is_require+'"  '+check_is_require+'   onchange="updateReq(\''+data_id+'\');"/> Req.</small>';
      txt_row += '</div>';
      txt_row += '<div><b>'+data_type+'</b></div>';
      txt_row += '</div>';

      txt_row += '<div class="col-sm-9">';
      txt_row += '<div>';
      txt_row += btn_update +" ";
      txt_row += data_name+' <span class="text-primary">['+data_id+']</span>';
      txt_row += '<button class="bg-success text-white" type="button" onclick="openDataProp(\''+data_id+'\');"> Prop. </button>';
      txt_row += '<button class="bg-info text-white" type="button" onclick="openDataAttr(\''+data_id+'\');"> Attr. </button>';

      txt_row += '</div>';

      if(data_type == "radio" || data_type == "dropdown" || data_type == "checkbox" ){
        txt_row += '<div class="sub-list-item" id="sub'+data_id+'"></div>';
      }
      else{
        txt_row += '<button class="bg-secondary" type="button" onclick="setPutAfter(\''+data_id+'\');"> Put After</button>';

      }
      txt_row += '</div>';

      txt_row += '<div class="col-sm-1">';
      txt_row += '<button class="btn btn-danger" type="button" onclick="deleteFormData(\''+data_id+'\');"> X </button>';
      txt_row += '</div>';

      $("#div_form_editor_head").append(txt_row);

    }

function updateReq(dataID){

  if($("#c"+dataID).is(':checked')){
      $("#c"+dataID).val("1");
  }
  else{
      $("#c"+dataID).val("0");
  }
}

    function addRow_Colhead(data_id, data_type, data_seq, data_value){
    //  var data_value = data_value.replace(/\"/g, '\\\"');

      var btn_update = '<button class="bg-info text-white" type="button" onclick="updateCompVal(\''+data_id+'\');"> Update Seq & Text</button>';
      var comp = "";

      if(data_type == "colhead"){
        comp = '<div><input type="text" id="v'+data_id+'" data-odata="'+data_value+'" data-col="data_value" class="form-control form-control-sm c'+data_id+'" value="'+data_value+'" /></div>';
      }



      var txt_row = '<div class="row r_form_data fdata-comp bg-msoft3" id="r'+data_id+'" data-id="'+data_id+'"  data-type="'+data_type+'"  >' ;
      txt_row += '<div class="col-sm-2">';
      txt_row += '<div><input type="text" data-odata="'+data_seq+'"  data-col="data_seq" data-id="'+data_id+'"  class="c'+data_id+' dataseq"  size="5" value="'+data_seq+'" /></div>';
      txt_row += '<div><b>'+data_type+'</b></div>';
      txt_row += '</div>';

        txt_row += '<div class="col-sm-9">';
        txt_row += btn_update;
        txt_row += '<span class="text-primary">['+data_id+'] </span>';
        txt_row += '<button class="bg-success text-white" type="button" onclick="openDataProp(\''+data_id+'\');"> Prop. </button>';
        txt_row += '<button class="bg-info text-white" type="button" onclick="openDataAttr(\''+data_id+'\');"> Attr. </button>';

        txt_row += comp;
        txt_row += '</div>';

        txt_row += '<div class="col-sm-1">';
        txt_row += '<button class="btn btn-danger" type="button" onclick="deleteFormData(\''+data_id+'\');"> X </button>';
        txt_row += '</div>';

        txt_row += '</div">';

        $("#div_form_editor_head").append(txt_row);

    }

    function addRow_SubItem(itm){ // eg. radio button, dropdown
      var txt_row = '<div class="sub-item '+itm.data_id+'" data-value="'+itm.data_value+'" data-name="'+itm.data_name_th+'">['+itm.data_value+'] '+itm.data_name_th+' ';
      txt_row += '</div>';

      $('#sub'+itm.data_id).append(txt_row);
    }


function openDataProp(dataID){ // open data properties
//   initDataProp(dataID);
   selectShowRule(dataID);
   $('#modal_data_prop').modal("show");
}

function openDataAttr(dataID){ // open data properties
  let formID = $("#form_editor").attr("data-formid");
  let sUrl = "data_mgt/mnu_form_editor_data_attr.php?dataid="+dataID+"&formid="+formID;

  showDialog(sUrl," Data Attribute ["+dataID+"] "+formID+"","440","600","",function(sResult){

  },false,"");
}

function setPutAfter(dataID){ // open data properties
//   initDataProp(dataID);
   selectPutAfter(dataID);
   $('#modal_data_prop2').modal("show");
}

function addFormComponent(dataType){ // addFormComponent
    var aData = {
        u_mode:"add_form_component",
        form_id:cur_form_id,
        data_type:dataType
        };
    save_data_ajax(aData,"data_mgt/db_form_editor_log.php",addFormComponent_Complete);
}

function addFormComponent_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      if(typeof rtnDataAjax.data_obj !== 'undefined'){
        $.notify("Add data type "+aData.data_type+ " successfully.", "success");
        var dataObj = rtnDataAjax.data_obj;
      //  console.log("dataobj: "+dataObj.data_id+"/"+dataObj.data_seq);
        addRow_Colhead(dataObj.data_id, dataObj.data_type ,dataObj.data_seq, "");

        scrollToDataItem($('#div_form_editor_head').children().last().attr("id"));
        if(dataObj.data_type == "colhead"){
          $("#v"+dataObj.data_id).val("<hr>");
        }

      }
      else{
        $.notify("Error occur", "error");
      }
    }
}


function addFormData(data_id){ // addFormData
    addFormDataItem("'"+data_id+"'");
}
function addFormDataAll(){ // addFormDataAll
    var str_data_id = "";
    $("#tbl_data_list_item .r_dataitm").each(function(ix,objx){
        //arrData.push($(objx).data("id"));
        str_data_id += "'"+$(objx).data("id")+"',";
    });
    if(str_data_id != ""){
      str_data_id = str_data_id.substring(0, str_data_id.length-1);
      addFormDataItem(str_data_id);
    }
    else{
      $.notify("No data is selected", "info");
    }

}


function addFormDataItem(str_data_item){ // addFormDataItem
    var aData = {
        u_mode:"add_form_data_item",
        form_id:cur_form_id,
        txt_data_list:str_data_item
        };
    save_data_ajax(aData,"data_mgt/db_form_editor_log.php",addFormDataItem_Complete);

}

function addFormDataItem_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){

      var data_list = rtnDataAjax.data_obj_list;
      data_list.forEach(function(itm) {
        //addRow_DataForm(data_id, data_name,  data_type, data_seq, data_sub_list){
         if(itm['data_type'] == 'colhead')
         addRow_Colhead(itm.data_id, itm.data_type ,itm.data_seq, itm.data_value);
         else{
           addRow_DataForm(itm['data_id'], itm['data_name'],  itm['data_type'], itm['data_seq'], '0');
           $("#r"+itm['data_id']).remove();
         }


      });

      var data_sub_list = rtnDataAjax.data_obj_sub_list;
      data_sub_list.forEach(function(itm) {
        addRow_SubItem(itm);
      //  $('#sub'+itm.data_id).append('<div class="sub-item '+itm.data_id+'" data-value="'+itm.data_value+'">['+itm.data_value+'] '+itm.data_name_th+' </div>');

      });

      scrollToDataItem($('#div_form_editor_head').children().last().attr("id"));

      $.notify("Add Data Item successfully.", "success");
    }
}


function scrollToDataItem(dataID){

  $('#div_form_editor_head').animate({
    scrollTop: $("#"+dataID).offset().top
  }, 500);

//  console.log("scrollToDataItem "+dataID);
}




function updateCompVal(dataID){ // update data component (data_seq, data_value)

    var dataObj = {};
    var flag_update = false;
    dataObj["data_id"] = dataID;
    dataObj["form_id"] = cur_form_id;
    $(".c"+dataID).each(function(ix,objx){
      if($(objx).val() != $(objx).data("odata")){
        dataObj[$(objx).data("col")] = $(objx).val();
        flag_update = true;
      }
    });

   if(flag_update){
         var aData = {
             u_mode:"update_data_component",
             data_obj:dataObj
             };
         save_data_ajax(aData,"data_mgt/db_form_editor_log.php",updateCompValComplete);
   }
   else{
     $.notify("No data changed.", "info");
   }
}

function updateCompValComplete(flagSave, rtnDataAjax, aData){
    if(flagSave){


      if(typeof aData.data_obj.data_seq !== 'undefined'){ // there is data_seq update
    //    txt_row += '<div><input type="text" data-odata="'+data_seq+'"  data-col="data_seq" data-id="'+data_id+'"  class="c'+data_id+' dataseq"  size="5" value="'+data_seq+'" /></div>';

        $(".c"+aData.data_obj.data_id).each(function(ix,objx){
          if($(objx).val() != $(objx).data("odata")){
            var data_val = getWObjValue(objx);
            setWObjValue(objx, data_val);
          }
        });


        var data_seq = aData.data_obj.data_seq ;
        var data_id = "";
        $('.dataseq').each(function(ix,objx){
             if($(objx).data("odata") > data_seq ){
               return false;
             }
             data_id = $(objx).data("id");
        });


        if(data_id != ""){
          $('#r'+data_id).after($('#r'+aData.data_obj.data_id));
        }
        else { // move to first child
          $('#div_form_editor_head').children().first().before($('#r'+aData.data_obj.data_id));
        }


        //var row = $('#r'+aData.data_obj.data_id).("tr:first");
      //   console.log("data_seq_new: "+aData.data_obj.data_seq+"/"+data_id);
      }


      $.notify("Update data successfully.", "success");
    }
}




function deleteFormData(dataID){ // update data component (data_seq, data_value)
   var result = confirm("Are you sure to delete this data item ?");

   if(result){
         var aData = {
             u_mode:"delete_data_item",
             data_id:dataID,
             form_id:cur_form_id
             };
         save_data_ajax(aData,"data_mgt/db_form_editor_log.php",deleteFormDataComplete);
   }

}

function deleteFormDataComplete(flagSave, rtnDataAjax, aData){
    if(flagSave){
      $('#r'+aData.data_id).remove();
      $.notify("Delete data item successfully.", "warning");
    }
}




  function saveData_FormEditor(){ // save data
      var divSaveData = "div_form_detail";
      var is_data_change = false;

      var adata_obj = {};

      if(validateInput(divSaveData)){

              $("#div_form_detail .save-data").each(function(ix,objx){
//console.log("id: "+$(objx).attr("id")+"/"+ $(objx).val()+"/"+$(objx).data("odata"));
                 var newVal = getWObjValue(objx);
                 if(newVal != $(objx).data("odata")){
                   adata_obj[($(objx).attr("id"))] = newVal;
                   is_data_change = true;
                 }
                 else if($(objx).hasClass("keydata")){
                   adata_obj[($(objx).attr("id"))] = newVal;
                 }

              });

              if(is_data_change){
                var aData = {
                u_mode:"update_form",
                data_obj:adata_obj
                };
                save_data_ajax(aData,"data_mgt/db_form.php",saveData_FormMain_Complete);

              }
              else{
                $.notify("No data changed", "info");
              }


      }
      else{
        $.notify("Incomplete Data, Please Check!", "error");
      }



    }

    function saveData_FormMain_Complete(flagSave, rtnDataAjax, aData){
      if(flagSave){
        $.notify("Save Data Mained successfully.", "success");
        $("#div_form_detail .save-data").each(function(ix,objx){
            var data_val = getWObjValue(objx);
            setWObjValue(objx,data_val);
        });
      //  searchData_Form();
      //  showFormDiv("list");
      }
    }

function close_form(){
  showFormDiv("list");
}


function showFormDiv(choice){
  //alert("showFormDiv "+choice);
  $(".div-form-data").hide();
  $("#div_form_"+choice).show();
}

</script>
<?
include_once("mnu_form_editor_data_prop.php");
?>
