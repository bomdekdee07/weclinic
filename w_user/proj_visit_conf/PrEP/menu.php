<?
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$job_id = isset($_SESSION["job_id"])?$_SESSION["job_id"]:"";
$status_id = isset($_GET["status_id"])?$_GET["status_id"]:"";
$group_id = isset($_GET["group_id"])?$_GET["group_id"]:"";
//echo "level : $auth_level";

if($job_id == 'CSL'){ // counselor
  include_once("menu_counselor.php");
}
else if($job_id == 'LB'){ // lab
  include_once("menu_lab.php");
}
else if($job_id == 'CS'){ // lab
  include_once("menu_cs.php");
}
else if($job_id == 'RCP'){ // lab
  include_once("menu_rcp.php");
}

else if($job_id == 'ADM'){ // counselor
  include_once("menu_counselor.php");
}


// function run after selectVisitForm trigger
$fn_after_visit_form = "
if($('#cur_visit_id').val() == 'M0'){
  // check sero conversion (other groups changed to group 004)
  if($('#cur_group_id').val() == '004' && $('#cur_pid').val().indexOf('-004-') > -1){
     $('#sero_con').remove(); // remove sero form if M0 and Group 004 by initial enroll
  }
  //check POC 200
  if($('#cur_uid_param').val().indexOf('poc200') > -1){
    selectPOC200();
  }
}
";

// extra function for this project
$js_fn_project = "
function selectPOC200(){ // select first 200 cases for POC
  var aData = {
            u_mode:\"sel_poc_200\",
            uid:$('#cur_uid').val(),
            proj_id:$('#cur_proj_id').val(),
            visit_date:$('#cur_visit_date').val()
  };

  save_data_ajax(aData,\"w_user/proj_visit_conf/POC/db_POC.php\",selectPOC200Complete);
}

function selectPOC200Complete(flagSave, rtnDataAjax, aData){

  if(flagSave){
    if(rtnDataAjax.is_poc200_visit == 'Y'){
      var txt_row = '<tr class=\"r_visit_form\">';
      txt_row += ' <td><i class=\"fa fa-first-aid fa-lg text-warning\" ></i> ';
      txt_row += ' </td>';
      txt_row += ' <td> Point of Care LAB (First 200 Cases) </td>';
      txt_row += ' <td align=\"center\"><i class=\"fa fa-check-circle fa-lg text-success\" ></i></td>';
      txt_row += ' <td align=\"center\"><button class=\"btn btn-primary btn-block\" type=\"button\" onclick=\"openUIDForm(\'poc_lab_enroll_200cases\',\'Point of Care (200 Cases)\')\"><i class=\"fa fa-folder-open\"></i> เปิด</button></td>';
      txt_row += '</tr>';

      $('#tbl_visit_form_list > tbody:last-child').append(txt_row);

    }

  }
}
";

?>
