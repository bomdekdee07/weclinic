<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}
include_once("inc_auth.php"); // set permission view, update, delete

?>


<script>

var s_clinic_id = "<? echo $staff_clinic_id; ?>";
ResetTimeOutTimer();


var choice_title = "Normal Range History";
/*
var selObj = [];
selObj["lab_test"]=[];
selObj["lab_test"]["title"] = "Lab Test";
selObj["lab_test"]["tbl_head"] = [{col_name:"Name", col_width:""},{col_name:"Test Menu", col_width:"40%"} ]
*/
</script>


<div id='div_dlg_hist_list' class='my-0'>
   <div class="mt-2">
      <table id="tbl_dlg_normal_range_list" class="table table-bordered table-sm table-striped table-hover">
         <thead>

           <tr>
             <th>Period</th>
             <th>Male</th>
             <th>Female</th>
           </tr>

         </thead>
         <tbody>

         </tbody>
     </table>
   </div>

</div> <!-- div_dlg_select_list -->


<script>

$('#select_detail_title').html(choice_title);
var u_mode_select = "";

$(document).ready(function(){
searchData_NormalRangeHist();
});

function searchData_NormalRangeHist(){
  var aData = {
      u_mode:"select_normal_range_hist",
      id: cur_lab_test_id
  };
  save_data_ajax(aData,"lab/db_lab_test.php",searchData_NormalRangeHist_Complete);
}

function searchData_NormalRangeHist_Complete(flagSave, rtnDataAjax, aData){
  //alert("flag save is : "+flagSave);
  if(flagSave){
    var txt_row="";
    if(rtnDataAjax.datalist.length > 0){

      var datalist = rtnDataAjax.datalist;
      var txt_row = "";
        for (i = 0; i < datalist.length; i++) {
            var dataObj = datalist[i];
            var stop_date ="";
            if(dataObj.stop_date == "2100-01-01") stop_date ="Now";
            else stop_date =changeToThaiDate(dataObj.stop_date);

            txt_row += '<tr class="r_hist">' ;
            txt_row +='<td>'+changeToThaiDate(dataObj.start_date)+' - '+stop_date+'</td>';
            txt_row +='<td>'+dataObj.lab_std_male_txt+'</td>';
            txt_row +='<td>'+dataObj.lab_std_female_txt+'</td>';
            txt_row +='</tr">';

        }//for

        $('#tbl_dlg_normal_range_list > tbody:last-child').append(txt_row);
    }
    else{
      $.notify("No record found.", "info");
      txt_row += '<tr class="r_hist"><td colspan="3" align="center">ไม่พบข้อมูล</td></tr">';
      $('#tbl_dlg_normal_range_list > tbody:last-child').append(txt_row);
    }
  }
}

</script>
