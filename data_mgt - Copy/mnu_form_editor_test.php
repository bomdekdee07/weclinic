<?
  $lastDesc = "";
?>
<!-- TextBox -->
<div class='divrow'>
  <div class='divitem'>
    <span class='itemname'>$data_name_th</span>
    <div class='itemobj'><input name='income' /></div>
  </div>
</div>

<!-- Radio -->
<div class='divrow'>
  <div class='divitem'>
    <span class='itemname'>$data_name_th</span>
    <div class='itemobj'>
      <div class='divsubitem'><input type='radio' name='gender' /><span class='itemsubname'>$data_sub_name_th<span></div>
      <div class='divsubitem'><input type='radio' name='gender' /><span class='itemsubname'>$data_sub_name_th<span></div>
    </div>
  </div>
</div>
0
<!-- Dropdown -->
<div class='divrow'>
  <div class='divitem'>
    <span class='itemname'>$data_name_th</span>
    <div class='divsubitem'>
      <div class='itemobj'>
        <SELECT type='radio' name='gender' />
          <option value='$sub_value'>$sub_name_th</option>
          <option value='$sub_value'>$sub_name_th</option>
          <option value='$sub_value'>$sub_name_th</option>
          <option value='$sub_value'>$sub_name_th</option>
        </SELECT>
      </div>
    </div>
  </div>
</div>

<!-- Checkbox -->
<div class='divrow'>
  <div class='divitem'>
    <? if($lastDesc==$data_desc_th){

    }else{
        echo("<div class='itemdesc'>$data_desc_th</div>");
    }
    ?>

    <div class='itemobj'><input type='checkbox' name='sex_oriented_1' /><span class='itemname'>$data_name_th<span></div>
  </div>
</div>
<div class='divrow'>
  <div class='divitem'>
    <div class='itemdesc' style='display:none'>$data_desc_th</div>
    <div class='itemobj'><input type='checkbox' name='sex_oriented_2' /><span class='itemname'>$data_name_th<span></div>
  </div>
</div>

<script>
jQuery.ajaxSetup({cache: false,
beforeSend: function() {
  $("#divSupply").hide();
   $('#j_loading').show();
},
complete: function(){
   $('#j_loading').hide();
    $("#divSupply").show();
},
success: function() {
  $("#divSupply").show();
  $('#j_loading').hide();
}
});
</script>
