
<div class="my-1" style="min-height: 200px;">
<div>
    List Value
    <button class="btn btn-success btn-sm" type="button" id="btn_add_list"><i class="fa fa-plus" ></i> Add new list</button>
</div>
    <table id="tbl_data_list_item" class="table table-bordered table-sm table-striped table-hover">
        <thead>
          <tr>
            <th>Seq No.</th>
            <th>
              <span id="txt_head_data_value">Choice Value</span>
            </th>
            <th>Choice Name TH</th>
            <th>Choice Name EN</th>
            <th>eWAT value</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>


$(document).ready(function(){
  $("#btn_add_list").click(function(){
     if(cur_data_type == "checkbox") addData_list('', $("#data_id").val()+'_', '', '');
     else addData_list('', '', '', '', '');
  }); // btn_add_list

});


  function clearData_list(){
    $("#tbl_data_list_item .data-item").remove();
  }

  function addData_list(seq_no, list_value, list_name_th, list_name_en, ewat_data_value=''){
     var count=$('#tbl_data_list_item tr').length;
     if(seq_no == '') seq_no = count;

     var txt_row ='<tr class="data-item" id="dataitm'+count+'" data-rowid="'+count+'" >' ;
     txt_row += '<td>';
     txt_row += "<input type='text' id='seq"+seq_no+"' value='"+seq_no+"' data-odata='"+seq_no+"' data-col_id='data_seq' class='input-decimal list_seq dataitm"+count+"' size='5' >";
     txt_row += '</td>';

     txt_row += '<td>';
     txt_row += "<input type='text' value='"+list_value+"' data-odata='"+list_value+"'  data-col_id='data_value' class='dataitm"+count+"' size='20'>";
     txt_row += '</td>';
     txt_row += '<td>';
     txt_row += "<input type='text' value='"+list_name_th+"' data-odata='"+list_name_th+"' data-col_id='data_name_th' class='dataitm"+count+"' size='50'>";
     txt_row += '</td>';
     txt_row += '<td>';
     txt_row += "<input type='text' value='"+list_name_en+"' data-odata='"+list_name_en+"' data-col_id='data_name_en' class='dataitm"+count+"' size='50'>";
     txt_row += '</td>';
     txt_row += '<td>';
     txt_row += "<input type='text' value='"+ewat_data_value+"' data-odata='"+ewat_data_value+"' data-col_id='ewat_data_value' class='dataitm"+count+"' size='30'>";
     txt_row += '</td>';
     txt_row += '<td>';
     txt_row += '<button class="btn btn-danger" type="button" onclick="deleteData_list(\''+count+'\');" ><i class="fa fa-times fa-lg" ></i></button>';
     txt_row += '</td>';

     txt_row += '</tr">';
     $("#tbl_data_list_item tbody").append(txt_row);
  }
  function deleteData_list(rowid){
    //console.log("delete "+rowid);
    $("#dataitm"+rowid).remove();
    if($('#tbl_data_list_item tr').length>1) {
      $(this).closest('tr').remove();
      $('#list_seq').text(function (i) {
        return i + 1;
      });
    }
  }




</script>
