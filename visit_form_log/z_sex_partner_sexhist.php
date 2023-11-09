
<?
//session_start();
include_once("../in_auth.php");

// partner sex history form
$user_id = (isset($_SESSION["sc_id"]))?$_SESSION["sc_id"]:"";

$partner_name = isset($_GET["partner_name"])?urldecode($_GET["partner_name"]):"";
$rowID = isset($_GET["row_id"])?$_GET["row_id"]:"";

?>


<div id="div_sexhist_<? echo $rowID; ?>" class='card '>
  <div class="my-2 px-0 pt-2" style="background-color:#FFF0F5;">
    <div class="row">
       <div class="col-sm-8 px-4">
         <div><b><i class="fa fa-heart fa-lg" ></i> ประวัติการมีเพศสัมพันธ์กับ <u><? echo $partner_name; ?></u> </b></div>
       </div>
       <div class="col-sm-3">
         <button class="btn btn-success btn-sm btn-block" type="button" onclick="addSexHist('<? echo $rowID?>');">
           <i class="fa fa-plus-square fa-lg" ></i> เพิ่มประวัติ
         </button>
       </div>

       <div class="col-sm-1">
         <button class="form-control form-control-sm btn btn-primary btn-sm" type="button" onclick="closeSexHist('<? echo $rowID?>');">
           <i class="fa fa-angle-up fa-lg" ></i>
         </button>
       </div>
    </div>
    <div class="mt-1 px-0 mx-0">
      <table id="tbl_sexhist<? echo $rowID; ?>" class="table table-bordered table-sm table-striped table-hover">
          <thead>
            <tr>
              <td rowspan="3" width=50px>ลำดับ</td>
              <td rowspan="3" width=100px>วันที่</td>
              <td rowspan="3" width=200px>ดื่มแอลกอฮอล์?<br>ใช้สารเสพติด?</td>
              <td colspan="8" align="center">การมีเพศสัมพันธ์</td>
              <td rowspan="3" width=200px>ประเมินความเสี่ยง<br>หมายเหตุ</td>

            </tr>
            <tr>
              <td colspan="2"  align="center"><b><i class="fa fa-bullseye fa-lg" ></i> ทวารหนัก </b></td>
              <td colspan="2"  align="center"><b><i class="fa fa-bullseye fa-lg" ></i> ปาก </b></td>
              <td colspan="2"  align="center"><b><i class="fa fa-bullseye fa-lg" ></i> ช่องคลอด </b></td>
              <td colspan="2"  align="center"><b><i class="fa fa-bullseye fa-lg" ></i> ช่องคลอดใหม่ </b></td>
            </tr>

            <tr>
              <td  align="center">รุก</td>
              <td  align="center">รับ</td>
              <td  align="center">รุก</td>
              <td  align="center">รับ</td>
              <td  align="center">รุก</td>
              <td  align="center">รับ</td>
              <td  align="center">รุก</td>
              <td  align="center">รับ</td>
            </tr>

          </thead>
          <tbody>

          </tbody>
      </table>

    </div>


  </div>


</div>

<input type="hidden" id="max_sexhist<? echo $rowID; ?>">


<script>
$(document).ready(function(){
  //alert("enter selectSexHist");
  selectSexHist('<? echo $rowID; ?>');


    var fixHelperModified<? echo $rowID; ?> = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width())
        });
        return $helper;
    },
    updateIndex<? echo $rowID; ?> = function(e, ui) {
        var seqNo<? echo $rowID; ?> = [];
        $('td.row_num<? echo $rowID; ?>', ui.item.parent()).each(function (i) {
            $(this).html(i + 1);
            var objRow = {
              sh_no:$(this).data("sexhist_no"), //sexhist_no
              sh_seq_no:$(this).html() //sexhist_seq_no
            }
            seqNo<? echo $rowID; ?>.push(objRow);
        });

        if(seqNo<? echo $rowID; ?>.length > 1){ // more than one row  update seq no
          updateSeqNoSexHist("<? echo $rowID; ?>", seqNo<? echo $rowID; ?>);
        }

        //alert("updateIndex ja "+seqNo<? echo $rowID; ?>[0]['sh_no']);
    };

    $('#tbl_sexhist<? echo $rowID; ?> tbody').sortable({
        helper: fixHelperModified<? echo $rowID; ?>,
        stop: updateIndex<? echo $rowID; ?>
    }).disableSelection();

});



</script>
