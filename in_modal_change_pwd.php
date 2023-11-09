<!-- Modal -->
<div class="modal fade" id="myModalChangePwd" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color:#EEEEEE;">
        <h4 id="modalChangePwdTitle" class="modal-title"><i class="fa fa-key  fa-lg"></i> Change Password</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id= "modalChangePwdBody" class="modal-body">

        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="user_old_password">Old Password</label>
            <input type="password"  class="form-control input-sm" id="user_old_password" data-title='User Old Password'  data-isrequire='1'>
          </div>
        </div>

        <div class="form-row py-4 my-4" style="background-color:#EEEEEE;">
          <div class="form-group col-md-6">
            <label for="user_new_password">New Password</label>
            <input type="password"  class="form-control input-sm" id="user_new_password" data-title='User New Password'  data-isrequire='1'>
          </div>
          <div class="form-group col-md-6">
            <label for="user_new_password">Confirm New Password</label>
            <input type="password"  class="form-control input-sm" id="user_new_password2" data-title='User New Password2'  data-isrequire='1'>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-12">
            <button class="btn btn-primary" id="btn_change_pwd"> <i class="fa fa-pencil-square-o fa-lg"></i> Change Password</button>

          </div>
        </div>


      </div>
      <div class="modal-footer">
        <span id="login_alert" style="color:red;"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<script>


$(document).ready(function(){
	$("#btn_change_pwd").click(function(){
			//alert("click change pwd : "+$('#user_old_password').val()+" / "+$('#user_new_password').val());
      if(validatePassword()){

        var aData = {
            u_mode:"change_pwd",
            staff_pwd_old:$('#user_old_password').val().trim(),
            staff_pwd_new:$('#user_new_password').val().trim()
        };

        save_data_ajax(aData,"system-access/db_user.php",changePasswordComplete);
      }
      else{
        alert("Error change password.");

      }
	});

});

function validatePassword(){
  var flag = true;
  if ($('#user_old_password').val() == "" || $('#user_new_password').val()== "" ||
     $('#user_new_password2').val() == ""){
     flag = false;
  }

  if($('#user_new_password').val().trim() != $('#user_new_password2').val().trim()) {
  //  msg_alert = "Error change password.";
    flag = false;
    $("#login_alert").html("Error change password.");
  }

  if(flag){
    if($('#user_new_password').val().trim().length < 4){
      $("#login_alert").html("Password length must be at least 4 charecters.");
      flag = false;
    }
  }
  return flag;
}

function changePasswordComplete(flagSave, rtnDataAjax, aData){
    //alert("changePasswordComplete flag save is : "+flagSave);
  if(flagSave){
       $('#myModalChangePwd').modal('hide');
  }
}



function myModalChangePwd(){

//  alert("title : "+mTitle+" Content : "+mContent);
    $('#user_old_password').val("");
    $('#user_new_password').val("");
    $('#user_new_password2').val("");

    $('#user_old_password').focus();
    $('#myModalChangePwd').modal('show');
}


</script>
