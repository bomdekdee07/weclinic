
        <!-- Put After : text, textarea put after specific choice and will be enable after that choice is selected  -->
        <!-- The Modal Data properties (Put After) -->
        <div class="modal fade" id="modal_data_prop2" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

              <!-- Modal Header -->
              <div class="modal-header bg-primary text-white">
                <h4 class="modal-title ">
                  <i class="fa fa-database fa-lg" aria-hidden="true"></i>
                   Put After:  <span class="show_rule_data_id2"></span></h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
              </div>

              <!-- Modal body -->
              <div class="modal-body" id="div_modal_data_prop2_detail" style="overflow-y: auto;">
                <div class="row">
                  <div class="col-sm-5" >
                    <label for="v_data_form_sub2">Parent Data Item </label>
                    <select id = "v_data_form_sub2" class="form-control form-control-sm">

                    </select>
                  </div>
                  <div class="col-sm-5" >
                    <label for="v_data_form_sub2">Put <u><span class="show_rule_data_id2"></span></u> after choice :</label>
                    <select id = "sel_v_data_form_sub2" class="form-control form-control-sm v_data_form_sub2" style="display:none">
                    </select>
                  <!--  <input type="text" id="txt_v_data_form_sub2" class="form-control form-control-sm v_data_form_sub2" > -->
                  </div>

                  <div class="col-sm-2" >
                    <label for="btn_add_data_prop2" class="text-white">.</label>
                    <button type="button" id="btn_add_data_prop2" class="btn btn-success form-control form-control-sm " > <i class="fa fa-plus fa-lg" aria-hidden="true"></i> ADD</button>
                  </div>

                </div>

                <table id="tbl_data_show_action2" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
                    <thead>
                      <tr>
                        <th>Data Parent</th>
                        <th>Put After choice value</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
              </div>

              <!-- Modal footer -->
              <div class="modal-footer">
                  <button type="button"  class="btn btn-info" data-dismiss="modal"> <i class="fa fa-times fa-lg" ></i> Close</button>
              </div>


            </div>
          </div>
        </div>

<script>

$(document).ready(function(){

$("#btn_add_data_prop2").click(function(){
  addPutAfter();
});

$("#v_data_form_sub2").change(function(){
     var data_parent_id = $(this).val();
     var data_parent_type = $("#r"+data_parent_id).data("type");
     $(".v_data_form_sub2").hide();
     if(data_parent_type == "radio" || data_parent_type == "dropdown"){ //list
       $("#sel_v_data_form_sub2").empty();
       $("."+data_parent_id).each(function(ix,objx){
         $("#sel_v_data_form_sub2").append(new Option($(objx).data("name")+" ["+$(objx).data("value")+"]", $(objx).data("value")));
       });
       $("#sel_v_data_form_sub2").show();
       $("#sel_v_data_form_sub2").focus();
     }
     else if(data_parent_type == "checkbox"){

     }
     else{ // text
       $("#txt_v_data_form_sub2").val("");
       $("#txt_v_data_form_sub2").show();
       $("#txt_v_data_form_sub2").focus();
     }
  }); // v_data_form_sub2

});

function initDataProp2(dataID){
  cur_data_id = dataID;
//console.log("initDataProp2:"+dataID);

  $(".show_rule_data_id2").html("<b>"+dataID+"</b>");
  $(".v_data_form_sub2").hide();
  $("#v_data_form_sub2").empty();
  $("#sel_v_data_form_sub2").empty();
  $("#txt_v_data_form_sub2").val("");


  $("#v_data_form_sub2").append(new Option("--Select--", ""));
  $("#v_data_form_sub2").find('option[value=""]').prop('disabled', true);

  $(".fdata_list, .fdata_check").each(function(ix,objx){
    //$(".fdata_list").each(function(ix,objx){

      if($(objx).data("id") != dataID){

    //    console.log("data: "+$(objx).data("id")+"/"+$(objx).data("type"));
          //arrData.push($(objx).data("id"));
          var data_id = $(objx).data("id");
          var data_type = $(objx).data("type");
          var data_name = $(objx).data("name");


          $("#v_data_form_sub2").append(new Option(data_type+" : "+data_name+" ["+data_id+"]", data_id));

      }

  });

  // remove option that already set in showif in this data_id
  $(".r_put_after").each(function(ix,objx){
      $("#v_data_form_sub2").find("option[value='"+$(objx).data("id")+"']").remove();
  });

}


function selectPutAfter(dataID){ // select showif data_id
    var aData = {
        u_mode:"select_show_rule",
        data_id:dataID,
        form_id:cur_form_id,
        action:"put_after"
        };
      //  console.log(cur_form_id+"/"+cur_data_id+"/"+parentDataID+"/"+parentDataValue+"/"+data_parent_type);
   save_data_ajax(aData,"data_mgt/db_form_editor.php",selectPutAfter_Complete);

}

function selectPutAfter_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){

     $('.r_put_after').remove(); // row data list
     var txt_row="";
     if(rtnDataAjax.data_obj_list.length > 0){

       var datalist = rtnDataAjax.data_obj_list;
       var txt_row = "";
         for (i = 0; i < datalist.length; i++) {
             var dataObj = datalist[i];
             addRowData_PutAfter(
              dataObj.data_parent_id,dataObj.data_parent_value
             );
         }//for
         $('#tbl_data_show_action2 > tbody:last-child').append(txt_row);
     }

     initDataProp2(aData.data_id);
    }
}



function addPutAfter(){ // add parent data id and data value to trigger show if
    var parentDataID = $("#v_data_form_sub2").val();
    var parentDataValue = '';
    var data_parent_type = $("#r"+parentDataID).data("type");

    if(data_parent_type == "checkbox") parentDataValue ="1";
    else parentDataValue = $("#sel_v_data_form_sub2").val();


    if(parentDataValue == ''){
      $.notify("Please specify value.", "info");
      return;
    }

    var dataObj = {};
    dataObj["form_id"] = cur_form_id;
    dataObj["data_id"] = cur_data_id;
    dataObj["data_parent_id"] = parentDataID;
    dataObj["data_parent_value"] = parentDataValue;
    dataObj["action_type"] = "put_after";

    var aData = {
        u_mode:"add_show_rule",
        data_obj:dataObj
        };

      //  console.log(cur_form_id+"/"+cur_data_id+"/"+parentDataID+"/"+parentDataValue+"/"+data_parent_type);
   save_data_ajax(aData,"data_mgt/db_form_editor.php",addPutAfter_Complete);

}

function addPutAfter_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
     $.notify("Add show rule data action successfully.", "success");
/*
     var form_id = aData.data_obj.form_id;
     var data_id = aData.data_obj.data_id;
*/
     var parent_id = aData.data_obj.data_parent_id;
     var parent_value = aData.data_obj.data_parent_value;
     addRowData_PutAfter(parent_id, parent_value);
    }
}

function addRowData_PutAfter(parent_id,parent_value){

  var txt_row = '<tr class="r_put_after" id="act2'+parent_id+parent_value+'" data-id="'+parent_id+'"  >' ;
  txt_row += '<td><b>'+parent_id +'</b></td>';
  txt_row += '<td>'+parent_value+'</td>';

  txt_row += '<td width="10%">';
  txt_row += '<button class="btn btn-danger" type="button" onclick="removeShowRule2(\''+parent_id+'\',\''+parent_value+'\');"> X </button>';
  txt_row += '</td>';
  txt_row += '</tr>';

  $("#tbl_data_show_action2 tbody").append(txt_row);
}


function removeShowRule2(parentDataID, parentDataValue){ // remove rule

    var dataObj = {};
    dataObj["form_id"] = cur_form_id;
    dataObj["data_id"] = cur_data_id;
    dataObj["data_parent_id"] = parentDataID;
    dataObj["data_parent_value"] = parentDataValue;
    dataObj["action_type"] = "put_after";

    var aData = {
        u_mode:"remove_show_rule",
        data_obj:dataObj
        };

      //  console.log(cur_form_id+"/"+cur_data_id+"/"+parentDataID+"/"+parentDataValue+"/"+data_parent_type);
   save_data_ajax(aData,"data_mgt/db_form_editor.php",removeShowRule2_Complete);

}

function removeShowRule2_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
     $.notify("Remove data action successfully.", "success");
     $('#act2'+aData.data_obj["data_parent_id"]+aData.data_obj["data_parent_value"]).remove(); // row data list
    }
}





</script>
