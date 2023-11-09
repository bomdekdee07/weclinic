<script>

function save_data_ajax_silent(objData, pageURL, returnFunc){

  var request = $.ajax({
      url:pageURL,
      type:'POST',
      data:objData, // should be consisted of u_mode and any data to update

      success: function(result) {
    //  alert("successx: "+result);

       var rtnObj = jQuery.parseJSON( result );
       if(typeof rtnObj.flag_auth !== "undefined"){

         //alert("flag_auth : "+rtnObj.flag_auth);
         if(rtnObj.flag_auth == '0'){

           myModalDlgLogin("te", "Time Expired");

         }
       }
        //alert ("rtnObj : "+rtnObj.msg_error+" / "+rtnObj.msg_info);

       if(rtnObj.msg_error == ""){
         flag_save_success = true;

         if(rtnObj.msg_info != ""){
           $.notify(rtnObj.msg_info, "info");
         }
       } //msg_err == ""
       else {
         //alert(""+rtnObj.msg_error);
         flag_save_success = false;
         if(rtnObj.msg_error == "session_expired"){
           $.notify(rtnObj.msg_error, "error");
         }
         else{

           $.notify(rtnObj.msg_error, "error");
         }


       }

        returnFunc(flag_save_success, rtnObj, objData );

     }, // end success
      error: function(xhr){

        alert("error: "+xhr.status);
        returnFunc(flag_save_success, xhr.status, objData );
      }
  });

 }


</script>
