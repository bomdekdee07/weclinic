

  <!-- The Modal  -->
  <div class="modal fade" id="modal_lab_note" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h4 class="modal-title ">
            <i class="fa fa-cog fa-lg" aria-hidden="true"></i>
            <span id="lab_note_title" ></span></h4>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body" id="div_lab_note_detail" style="overflow-y: auto;">
          <textarea id="txt_lab_note_add" rows="6"  data-title="Note" class="form-control save-data" placeholder="Add lab note here..."></textarea>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" id="btn_save_lab_note" class="btn btn-success" > <i class="fa fa-save fa-lg" ></i> Save Note</button>
            <button type="button" id="btn_cancel_lab_note" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-times-circle fa-lg" ></i> Cancel</button>
        </div>


      </div>
    </div>
  </div>


<script>

var note_choice = "p_lab_order"; // tbl ref
var note_column = "lab_order"; // column in destination note eg. lab_order  (lab_order_note)
var note_user_name = ""; // user name who add note
var lst_data = []; // identify record to save eg. uid, collect_date, collect_time
var ref_comp_lab_note; // ref component to return value

<?
if (isset($col_note)) {
  echo "note_column='$col_note';";
}
if (isset($tbl_note)) {
  echo "note_choice='$tbl_note';";
}
?>

$(document).ready(function(){
  $("#btn_save_lab_note").click(function(){
     addLabNote();
  }); // btn_save_lab_note

});


function openAddLabNote(ref_comp, lstdata,choice, title, user_id){
  ref_comp_lab_note = ref_comp;
  lst_data = lstdata;
  note_choice = choice;
  note_user_name = user_id;
  $("#lab_note_title").html(title);


  //comp_lab_note.val("xxxx");
  $("#modal_lab_note").modal("show");
}



function addLabNote(){

  var lst_data_detail = {
  choice:note_choice,
  col:note_column,
  user_name:note_user_name,
  txt_note: $("#txt_lab_note_add").val()
  };

  var aData = {
      u_mode:"add_lab_note",
      lst_data_id: lst_data,
      lst_data_obj: lst_data_detail
  };
  save_data_ajax(aData,"lab/db_lab_test_order.php",addLabNoteComplete);
}

function addLabNoteComplete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    $.notify("Add note successfully.", "info");

    var txt = rtnDataAjax.msg_note+ref_comp_lab_note.val();
    ref_comp_lab_note.val(txt);
    $("#modal_lab_note").modal("hide");
  }
}


</script>
