
<div>
<input id="txtdate" value="1231" type="text" tabindex="1" />
</div>


<script>
$(document).ready(function(){
  $("#txtdate").mask("99/99/9999",{placeholder:"mm/dd/yyyy",completed:function(){alert("completed!");}});
});



</script>
