<?
include_once("../in_auth.php");

?>

<div class="card" id="div_surveygizmo_case_list">
  <div class="card-body">
    <div class="card-title">
      <div class="row">
        <div class="col-sm-6">
          <h5><i class="fa fa-eye fa-lg" ></i> ตรวจฟอร์มทำจาก Survey Gizmo</h5>
          <div class="row">
              <div class="col-sm-4">
                <button class="btn btn-primary form-control btn_mnu_sgm" type="button"  data-mnu="1" data-id="mnu_div_uic_revise"><i class="fa fa-file" ></i> ตรวจแก้ UIC</button>
              </div>
              <div class="col-sm-4">
                <button class="btn btn-primary form-control btn_mnu_sgm" type="button"  data-mnu="2" data-id="mnu_div_check_remark"><i class="fa fa-file" ></i> ตรวจแก้ Note</button>
              </div>
              <div class="col-sm-4">
                <button class="btn btn-primary form-control btn_mnu_sgm" type="button"  data-mnu="3" data-id="mnu_div_surveygizmo_all"><i class="fa fa-file" ></i> ภาพรวม</button>
              </div>
          </div>
        </div>
        <div class="col-sm-6">
          <small> ตัวย่อ CBO Site: <br>
          <table  class="table table-bordered table-sm table-striped table-hover">
                <tr>
                  <td>
                    SBK: SWING BKK <br>
                    SPT: SWING Pattaya <br>
                  </td>
                  <td>
                    RBK: RSAT BKK <br>
                    RCB: RSAT Chonburi <br>
                  </td>
                  <td>
                    RHY: RSAT Hadyai <br>
                    RUB: RSAT Ubonratchathani <br>
                  </td>
                  <td>
                    MCM: MPlus Chiangmai <br>
                    MCR: MPlus Chiangrai <br>
                  </td>
                  <td>
                    CCM: CAREMAT Chiang Mai <br>
                    STPT: SISTER Pattaya <br>
                  </td>
                </tr>
          </table>
          </small>
        </div>
      </div>




    </div>
    <div id="div_mnu1" class="div_sgm">

    </div>
    <div id="div_mnu2" class="div_sgm">

    </div>
    <div id="div_mnu3" class="div_sgm">

    </div>


  </div>
</div>



<script>

var is_load_mnu1 = 0; // uic revised
var is_load_mnu2 = 0; // remark revised
var is_load_mnu3 = 0; // overall

$(document).ready(function(){


  $(".btn_mnu_sgm").click(function(){

     var choice = $(this).data("id");
     var mnu = $(this).data("mnu");
  //   alert("enter "+eval("is_load_mnu"+mnu));
/*
alert("param "+choice+"/"+mnu);
alert("param2 "+eval("is_load_mnu"+mnu));
*/
     if(eval("is_load_mnu"+mnu) == 0){
       loadSGM_Menu(choice, mnu);
     }
     $(".div_sgm").hide();
     $("#div_mnu"+mnu).show();
     eval("is_load_mnu"+mnu+"=1");

  }); // btn_mnu_sgm

});

function changeMenuSGM(choice){

}

function loadSGM_Menu(choice, mnu){
  var link = "w_ext_surveygizmo/"+choice+".php";
  $("#div_mnu"+mnu).load(link, function(){

  });

}



</script>
