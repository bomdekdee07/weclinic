

        <!-- The Modal Data properties -->
        <div class="modal fade" id="modal_data_prop" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

              <!-- Modal Header -->
              <div class="modal-header bg-primary text-white">
                <h4 class="modal-title ">
                  <i class="fa fa-database fa-lg" aria-hidden="true"></i>
                   Show Rules:  <span class="show_rule_data_id"></span></h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
              </div>

              <!-- Modal body -->
              <div class="modal-body" id="div_modal_data_prop_detail" style="overflow-y: auto;">
                <div class="row">
                  <div class="col-sm-4" >
                    <label for="sel_data_form_all">Show Data ID <u><span class="show_rule_data_id"></span></u> if form data item:</label>
                    <select id = "sel_data_form_all" class="form-control form-control-sm">

                    </select>
                  </div>
                  <div class="col-sm-4" >
                    <label for="v_data_form_sub">equal to Data Value:</label>
                    <select id = "sel_v_data_form_sub" class="form-control form-control-sm v_data_form_sub" style="display:none">
                    </select>
                    <input type="text" id="txt_v_data_form_sub" class="form-control form-control-sm v_data_form_sub" >
                  </div>
                  <div class="col-sm-2" >
                    <label for="v_data_form_sub">Action:</label>
                    <select id = "sel_v_data_action" class="form-control form-control-sm">
                      <option value="require_if">Require if</option>
                      <option value="hide_if">Hide if</option>
                    </select>
                  </div>

                  <div class="col-sm-2" >
                    <label for="btn_add_data_prop" class="text-white">.</label>
                    <button type="button" id="btn_add_data_prop" class="btn btn-success form-control form-control-sm " > <i class="fa fa-plus fa-lg" aria-hidden="true"></i> ADD</button>
                  </div>

                </div>

                <table id="tbl_data_show_action" class="table table-bordered table-sm table-striped table-hover tbl-mtn-list">
                    <thead>
                      <tr>
                        <th>Data Parent</th>
                        <th>Show if Data Value</th>
                        <th>Data Action</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
              </div>

              <!-- Modal footer -->
              <div class="modal-footer">
                  <button type="button" id="btn_close_data_prop_alert" class="btn btn-info" data-dismiss="modal"> <i class="fa fa-times fa-lg" ></i> Close</button>
              </div>


            </div>
          </div>
        </div>

<script>

$(document).ready(function(){

$("#btn_add_data_prop").click(function(){
  addShowRule();
});

$("#sel_data_form_all").change(function(){
     var data_parent_id = $(this).val();
     var data_parent_type = $("#r"+data_parent_id).data("type");
     $(".v_data_form_sub").hide();

     if(data_parent_type == "radio" || data_parent_type == "dropdown"){ //list
       $("#sel_v_data_form_sub").empty();
       $("."+data_parent_id).each(function(ix,objx){
         $("#sel_v_data_form_sub").append(new Option($(objx).data("name")+" ["+$(objx).data("value")+"]", $(objx).data("value")));
       });

       $("#sel_v_data_form_sub").show();
       $("#sel_v_data_form_sub").focus();

     }
     else if (data_parent_type == "checkbox"){
       $("#txt_v_data_form_sub").val("1");
     }
     else{ // text
       $("#txt_v_data_form_sub").val("");
       $("#txt_v_data_form_sub").show();
       $("#txt_v_data_form_sub").focus();
     }

     $("#sel_v_data_action").val("require_if"); // require_if default value
  }); // sel_data_form_all

});

function initDataProp(dataID){
  cur_data_id = dataID;
//console.log("initdataProp:"+dataID);

  $(".show_rule_data_id").html("<b>"+dataID+"</b>");
  $(".v_data_form_sub").hide();
  $("#sel_data_form_all").empty();
  $("#sel_v_data_form_sub").empty();
  $("#txt_v_data_form_sub").val("");


  $("#sel_data_form_all").append(new Option("--Select--", ""));
  $("#sel_data_form_all").find('option[value=""]').prop('disabled', true);

  $(".fdata").each(function(ix,objx){

      if($(objx).data("id") != dataID){

        //console.log("data: "+$(objx).data("id")+"/"+$(objx).data("type"));
          //arrData.push($(objx).data("id"));
          var data_id = $(objx).data("id");
          var data_type = $(objx).data("type");
          var data_name = $(objx).data("name");

/*
          if(data_type == "q_label" || data_type == "html"){
            //console.log("data v : "+$("#v"+data_id).val());
            if( $("#v"+data_id).val().length > 50)
            data_name = $("#v"+data_id).val().substring(0, 50)+" ...";
            else
            data_name = $("#v"+data_id).val();
          }
          else{
            data_name = $(objx).data("name");
          }
*/


          $("#sel_data_form_all").append(new Option(data_type+" : "+data_name+" ["+data_id+"]", data_id));

      }

  });

  // remove option that already set in require_if, hide_if in this data_id
  $(".r_show_rule").each(function(ix,objx){
      $("#sel_data_form_all").find("option[value='"+$(objx).data("id")+"']").remove();
  });

}





function selectShowRule(dataID){ // select require_if, hide_if data_id
    var aData = {
        u_mode:"select_show_rule_showhide_if",
        data_id:dataID,
        form_id:cur_form_id
        };
      //  console.log(cur_form_id+"/"+cur_data_id+"/"+parentDataID+"/"+parentDataValue+"/"+data_parent_type);
   save_data_ajax(aData,"data_mgt/db_form_editor.php",selectShowRule_Complete);

}

function selectShowRule_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){

     $('.r_show_rule').remove(); // row data list
     var txt_row="";
     if(rtnDataAjax.data_obj_list.length > 0){

       var datalist = rtnDataAjax.data_obj_list;
       var txt_row = "";
         for (i = 0; i < datalist.length; i++) {
             var dataObj = datalist[i];
             addRowData_ShowRule(
              dataObj.data_parent_id,dataObj.data_parent_value, dataObj.action_type
             );
         }//for
         $('#tbl_data_list_item > tbody:last-child').append(txt_row);
     }

     initDataProp(aData.data_id);
    }
}



function addShowRule(){ // add parent data id and data value to trigger show if
    var parentDataID = $("#sel_data_form_all").val();
    var parentDataValue = '';
    var data_parent_type = $("#r"+parentDataID).data("type");
    if(data_parent_type == "radio" || data_parent_type == "dropdown"){ //list
      parentDataValue = $("#sel_v_data_form_sub").val();
    }
    else{
      parentDataValue = $("#txt_v_data_form_sub").val();
    }

    if(parentDataValue == ''){
      $.notify("Please specify value.", "info");
      return;
    }

    var dataObj = {};
    dataObj["form_id"] = cur_form_id;
    dataObj["data_id"] = cur_data_id;
    dataObj["data_parent_id"] = parentDataID;
    dataObj["data_parent_value"] = parentDataValue;
    dataObj["action_type"] = $("#sel_v_data_action").val();

    var aData = {
        u_mode:"add_show_rule",
        data_obj:dataObj
        };

      //  console.log(cur_form_id+"/"+cur_data_id+"/"+parentDataID+"/"+parentDataValue+"/"+data_parent_type);
   save_data_ajax(aData,"data_mgt/db_form_editor.php",addShowRule_Complete);

}

function addShowRule_Complete(flagSave, rtnDataAjax, aData){
    if(flagSave){
     $.notify("Add show rule data action successfully.", "success");
/*
     var form_id = aData.data_obj.form_id;
     var data_id = aData.data_obj.data_id;
*/
     var parent_id = aData.data_obj.data_parent_id;
     var parent_value = aData.data_obj.data_parent_value;
     var data_action = aData.data_obj.action_type;
     addRowData_ShowRule(parent_id, parent_value, data_action);
    }
}

function addRowData_ShowRule(parent_id,parent_value, data_action){

  var txt_row = '<tr class="r_show_rule" id="act'+parent_id+parent_value+'" data-id="'+parent_id+'"  >' ;
  txt_row += '<td><b>'+parent_id +'</b></td>';
  txt_row += '<td>'+parent_value+'</td>';
  txt_row += '<td>'+data_action+'</td>';

  txt_row += '<td width="10%">';
  txt_row += '<button class="btn btn-danger" type="button" onclick="removeShowRule(\''+parent_id+'\',\''+parent_value+'\',\''+data_action+'\');"> X </button>';
  txt_row += '</td>';
  txt_row += '</tr>';

  $("#tbl_data_show_action tbody").append(txt_row);
}


function removeShowRule(parentDataID, parentDataValue, dataAction){ // remove rule
    var dataObj = {};
    dataObj["form_id"] = cur_form_id;
    dataObj["data_id"] = cur_data_id;
    dataObj["data_parent_id"] = parentDataID;
    dataObj["data_parent_value"] = parentDataValue;
    dataObj["action_type"] = dataAction;

    var aData = {
        u_mode:"remove_show_rule",
        data_obj:dataObj
        };

      //  console.log(cur_form_id+"/"+cur_data_id+"/"+parentDataID+"/"+parentDataValue+"/"+data_parent_type);
   save_data_ajax(aData,"data_mgt/db_form_editor.php",removeShowRule_Complete);

}

function removeShowRule_Complete(flagSave, rtnDataAjax, aData){
  if(flagSave){
   $.notify("Remove data action successfully.", "success");
   $('#act'+aData.data_obj["data_parent_id"]+aData.data_obj["data_parent_value"]).remove(); // row data list
  }
}



</script>
