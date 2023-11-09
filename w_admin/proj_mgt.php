<input type="hidden" id="cur_s_id" >
<input type="hidden" id="cur_sc_id" >
<input type="hidden" id="cur_s_name" >
<input type="hidden" id="cur_clinic_id" >
<input type="hidden" id="cur_clinic_name" >
<input type="hidden" id="cur_job_name" >


<input type="hidden" id="u_mode_staff" >

<button class="btn btn-success" type="button" id="btn_god_mode" ><i class="fa fa-folder-plus fa-lg" ></i> Project Admin Mode</button>
<div id='div_proj_content'>

</div>
<div id='j_loading' style='display:none'>
	<div style='text-align: center;margin-top:200px'><img src='image/spinner.gif' /></div>
</div>

<style>
#j_loading {
    position: absolute;
    width: 100%;
    height: 100%;
    background: black url(spinner.gif) center center no-repeat;
    opacity: .5;
    left:0;
    top:0;
}
</style>
<script>
$(document).ready(function(){
	jQuery.ajaxSetup({cache: false,
	  beforeSend: function() {
	     $('#j_loading').show();
	  },
	  complete: function(){
	     $('#j_loading').hide();
	  },
	  success: function() {}
	});

	$("#btn_god_mode").on("click",function(){
		$("#div_proj_content").html("");
		$("#div_proj_content").load("w_admin/dlg_proj_admin.php", function( response, status, xhr ) {
		if ( status == "error" ) {
		var msg = "Sorry but there was an error: ";
		$( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
		}
		});

	});

});
</script>