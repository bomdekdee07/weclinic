<!-- Modal -->
<div class="modal fade" id="dlgProjConsent" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color:#EEEEEE;">
        <h5 id="dlgProjConsentTitle" class="modal-title"><i class="fa fa-file-signature fa-lg"></i> Consent <b><span id="txt_proj_consent_title"></span></b></h5>

        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div id= "modalProjConsent" class="modal-body">
        <div>
            <span id="txt_proj_consent"></span>

        </div>

        <div class="form-row mt-3">
          <div class="form-group col-md-12">

             <div>
                <div class="radio">
                  <label><input type="radio" class="rdo_consent_opt rdo_consent_yes"  name="consent_opt" value="1" > อาสาสมัคร<b><u>ลงนาม</u></b>ในเอกสารฉบับใหม่เรียบร้อย</label>
                </div>
                <div class="radio">
                  <label><input type="radio" class="rdo_consent_opt rdo_consent_no"  name="consent_opt" value="0" > อาสาสมัคร<span class="text-danger"><b>ยังไม่ได้ลงนาม</b></span>ในเอกสารฉบับใหม่</label>
                </div>
              <label for="consent_remark">โปรดระบุสาเหตุที่ยังไม่ลงนาม:</label>
              <textarea class="form-control form-control-sm save-data" id="consent_remark" rows="3"  data-title='หมายเหตุ'></textarea>
            </div>
          </div>
        </div>

      </div>
      <div id="modalProjConsentFooter" class="modal-footer text-danger" style="float:left;">
        <button class="btn btn-primary form-control" id="btn_send_consent"> <i class="fa fa-file fa-lg"></i> บันทึก</button>

      </div>
    </div>

  </div>
</div>


<script>

var uid_param="";
var collect_date_param="";
var proj_id_param="";
var consent_version_param="";

var consent_msg = "";
var opt_save = "1";

$(document).ready(function(){
  $(".rdo_consent_opt").change(function(){

    var consent_opt = "";

    $(".rdo_consent_opt:checked").each(function(ix,objx){
      consent_opt = $(objx).val();
    });

    if(consent_opt == "0"){
      $('#consent_remark').prop('disabled', false);
      $('#consent_remark').focus();
    }
    else if(consent_opt == "1"){
      $('#consent_remark').prop('disabled', true);
      $('#consent_remark').val("");
    }
    else{
      $('#txt_proj_consent').notify("กรุณาเลือก");
      return;
    }

	});

	$("#btn_send_consent").click(function(){
    sendProjConsent();
	});

});

function clearConsent(){
  uid_param="";
  collect_date_param="";
  proj_id_param="";
  consent_version_param="";

  consent_msg = "";
  opt_save = "1";
}

function sendProjConsent(){

  var consent_opt = "";

  $(".rdo_consent_opt:checked").each(function(ix,objx){
    consent_opt = $(objx).val();
  });

  if(consent_opt == "0"){
    $('#consent_remark').prop('disabled', false);
    $('#consent_remark').focus();
  }
  else if(consent_opt == "1"){
    $('#consent_remark').prop('disabled', true);
    $('#consent_remark').val("");
  }
  else{
    $('#txt_proj_consent').notify("กรุณาเลือก");
    return;
  }

  if(consent_opt == "0"){ // no
    if($('#consent_remark').val().trim() == ""){
      $('#consent_remark').notify("กรุณาใส่เหตุผลประกอบ", "info");
      return;
    }
  }

    var aData = {
        u_mode:"reconsent",
        uid:uid_param,
        proj_id:proj_id_param,
        collect_date:collect_date_param,
        is_consent:consent_opt,
        consent_remark:$('#consent_remark').val().trim(),
        consent_version:consent_version_param

    };
   save_data_ajax(aData,"w_user/db_proj_visit.php",sendProjConsentComplete);


}

function sendProjConsentComplete(flagSave, rtnDataAjax, aData){
    //alert("sendProjConsentComplete 555 flag save is : "+flagSave);
  if(flagSave){
    $.notify("บันทึก Consent เรียบร้อยแล้ว", "success");
     $('#dlgProjConsent').modal('hide');
     //afterLogin();
  }
  else{
    $("#modalProjConsentFooter").html(rtnDataAjax.msg_error);
  }
}



function dlgProjConsent(uidParam, collectDate, projID, consentTitle, consentMsg, consentVersion) // message show in consent dialog
{
   $('#consent_remark').prop('disabled', true);


   $("#btn_send_consent").show();

   $('#txt_proj_consent_title').html(consentTitle);
   $('#txt_proj_consent').html(consentMsg);
   uid_param = uidParam;
   collect_date_param = collectDate;
   proj_id_param = projID;
   consent_version_param = consentVersion;
   consent_msg = consentMsg;

   $('#dlgProjConsent').modal('show');
}

function openProjConsent(uidParam, collectDate, projID, consentMsg, optSave) // open dialog consent with consent data
{
   $('.rdo_consent_opt').prop('checked', false);
   $('#consent_remark').val("");

//alert("optSave "+optSave);
   opt_save = optSave;



   consent_msg = consentMsg;

   uid_param = uidParam;
   collect_date_param = collectDate;
   proj_id_param = projID;

   var aData = {
       u_mode:"open_consent",
       uid:uid_param,
       proj_id:proj_id_param,
       collect_date:collect_date_param,
       consent_version:consent_version_param

   };
  save_data_ajax(aData,"w_user/db_proj_visit.php",openProjConsentComplete);
}
function openProjConsentComplete(flagSave, rtnDataAjax, aData){

  if(flagSave){

     var title = aData.uid+"/"+aData.collect_date;
       dlgProjConsent(aData.uid,
       aData.collect_date,
       aData.proj_id,
       title, consent_msg,
       rtnDataAjax.consent_version );

       $('#consent_remark').val(rtnDataAjax.consent_remark);

       if(rtnDataAjax.is_consent == '0'){
         $('.rdo_consent_no').prop('checked', true);
         $('#consent_remark').prop('disabled', false);
       }
       else if(rtnDataAjax.is_consent == '1'){
         $('.rdo_consent_yes').prop('checked', true);
         $('#consent_remark').prop('disabled', true);
       }

       if(opt_save == '1') $("#btn_send_consent").show();
       else if(opt_save == '0') $("#btn_send_consent").hide();

  }
}




</script>
