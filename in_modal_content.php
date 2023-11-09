<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="modalTitle" class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id= "modalContent" class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<script>
function myModalContent(mTitle, mContent, pic){
//  alert("title : "+mTitle+" Content : "+mContent);
  var is_pic = false;
  var content = "";

  if(pic == "info") mTitle = "<i class='fa fa-info-circle fa-lg'></i> "+mTitle;
  else if(pic == "success") mTitle = "<i class='fa fa-check-square fa-lg'></i> "+mTitle;
  else if(pic == "delete") mTitle = "<i class='fa fa-fa-times fa-lg'></i> "+mTitle;
  else{
    is_pic = true;
    pic = "<img src='img/"+pic+"'>";
  }

  if(is_pic)
  content = "<div class='row'><div class='col-md-4 col-centered' style='padding:5px'>"+pic+" </div><div class='col-md-8' style='padding:5px'>"+mContent+"</div></div>";

  else
  content = "<div style='padding:10px'>"+mContent+"</div>";


  $("#modalTitle").html(mTitle);
  //content = "<div class='row'><div class='col-md-4 col-md-offset-5' style='padding:5px'>abc</div><div class='col-md-8' style='padding:5px'>"+mContent+"</div></div>";
  $("#modalContent").html(content);

  $('#myModal').modal('show');
}

</script>
