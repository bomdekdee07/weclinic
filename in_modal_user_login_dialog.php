<!-- Modal -->
<div class="modal fade" id="myModalDlgLogin" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color:#EEEEEE;">
        <h4 id="modalLoginTitle" class="modal-title"><i class="fa fa-key  fa-lg"></i> System Access</h4>
      </div>
      <div id= "modalLogin" class="modal-body">
        <div class="my-2 mx-2 px-2 py-2 bg-warning">
            <b>คุณ<? echo $s_name; ?></b>
            กรุณาเข้าระบบอีกครั้งก่อนจะทำรายการต่อ เนื่องจาก <span class="text-danger">session expired</span> ครับ
        </div>

        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="login_user_name">รหัสประจำตัว</label>
            <input type="text"  class="form-control input-sm" id="login_user_name" data-title='User Name'  data-isrequire='1' value="<? echo $sc_id; ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="login_user_pwd">รหัสผ่าน</label>
            <input type="password"  class="form-control input-sm" id="login_user_pwd" data-title='User Password'  data-isrequire='1' maxlength="25">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-12">
            <button class="btn btn-primary form-control" id="btn_login_access"> <i class="fa fa-key fa-lg"></i> เข้าระบบ</button>

          </div>
        </div>


      </div>
      <div id="modalLoginFooter" class="modal-footer text-danger" style="float:left;">

      </div>
    </div>

  </div>
</div>
<input type="hidden" id="login_mode" >

<script>


$(document).ready(function(){

	$("#btn_login_access").click(function(){
    systemAccess();
	});

  $("#login_user_pwd").on("keypress",function (event) {
    if (event.which == 13) {
      systemAccess();
    }
  });


});

function systemAccess(){
  if (validateLoginPassword()){

    var aData = {
        u_mode:"login_check",
        staff_id:$('#login_user_name').val().trim(),
        staff_pwd:$('#login_user_pwd').val().trim()
    };
   save_data_ajax(aData,"system-access/db_user.php",systemAccessComplete);
  }
  else{
    alert("Error system access.");
  }
}

function systemAccessComplete(flagSave, rtnDataAjax, aData){
    //alert("systemAccessComplete 555 flag save is : "+flagSave);
  if(flagSave){
    $.notify("เข้าระบบได้แล้ว", "success");
     $('#myModalDlgLogin').modal('hide');
     //afterLogin();
  }
  else{
    $("#modalLoginFooter").html(rtnDataAjax.msg_error);
  }
}

function validateLoginPassword(){
  var flag = true;

  if ($('#login_user_name').val().trim() == "" || $('#login_user_pwd').val().trim() == ""){
     flag = false;
  }
  return flag;
}

function myModalDlgLogin(accessMode, // mode to access eg. te = login due to Time Expired, other system_access
                         msgContent) // message show in login dialog
                         {
  //alert("title : "+userName+" Content : "+msgContent);
   $('#login_mode').val(accessMode);
   $('#login_user_pwd').val("");


     $('#login_user_name').prop('disabled', true);
     $('#login_user_pwd').focus();


    $("#modalLoginFooter").html(msgContent);
    $('#myModalDlgLogin').modal('show');
}






</script>
