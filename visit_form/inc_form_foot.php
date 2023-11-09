
  </div> <!-- div_main-->

</body>
</html>
<script>

$(document).ready(function(){
  loadingHide();
//loadingShow();


  $(".mnu-main").click(function(){
    var link = $(this).data("id")+".php";
    $("#div_main").load("w_user/"+link, function(){
      //   alert("load "+link);
    });
  }); // .mnu-main




});

function loadingShow(){
  $("#div_loading").show();
  $("#div_main").hide();
}
function loadingHide(){
  $("#div_loading").hide();
  $("#div_main").show();
}

function extendSession(){

}
/*
function myModalDlgLogin(p1, p2){

}
*/

</script>
