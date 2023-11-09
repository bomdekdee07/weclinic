<?

include_once("in_php_function.php");
$sFormid = getQS("formid");
$sDataid = getQS("dataid");




  include("in_php_pop99_sql.php");


  $ATTR_DATATYPE = array();
  $ATTR_DATATYPE['form'] = array('headrowheight','rowheight', 'externallink');
  $ATTR_DATATYPE['logform'] = array('logformid','formheight','onlyvisitid');
  $ATTR_DATATYPE['iframe'] = array('pagepath','formheight','onlyvisitid');
  $ATTR_DATATYPE['includepage'] = array('pagepath');
  $ATTR_DATATYPE['colhead'] = array('width');
  $ATTR_DATATYPE['dropdown'] = array('showlabel','width','hideprefixsuffix','hidesomechoice');
  $ATTR_DATATYPE['number'] = array('showlabel','width','minvalue','maxvalue','placeholder','hideprefixsuffix','tagname','keyname', "sumscoretotal");
  $ATTR_DATATYPE['text'] = array('showlabel','width','minchar','maxchar','placeholder','hideprefixsuffix','tagname','keyname');
  $ATTR_DATATYPE['textarea'] = array('showlabel','row','col','placeholder','hideprefixsuffix','tagname','keyname');
  $ATTR_DATATYPE['radio'] = array('showlabel','optalign','hidesomechoice','tagname','keyname', "sumscore");// V (vertical), H (horizontal)
  $ATTR_DATATYPE['date'] = array('showlabel','width','placeholder','hideprefixsuffix','partialdate', 'isthaidate', 'notexceedcoldate', 'tagname','keyname' );
  $ATTR_DATATYPE['checkbox'] = array('tagname','keyname');

  $data_type = "";
  $data_attr = array();
  $data_set_value = "";
$query = "";

if($sDataid != ""){
  $query ="SELECT DA.attr_id, DA.attr_val, DL.data_type
  FROM p_form_list_data DL
  LEFT JOIN p_form_list_data_attribute DA ON (DA.data_id=DL.data_id AND DA.form_id=?)
  WHERE DL.data_id= ?
  ";
}
else{
  $query ="SELECT attr_id, attr_val, 'form' as data_type
  FROM p_form_list_data_attribute
  WHERE form_id=? and data_id= ?
  ";
  $data_type = 'form';
}

//echo " $sFormid, $sDataid / $query ";

  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('ss', $sFormid, $sDataid);


  if($stmt->execute()){
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
      $data_type = $row['data_type'];
      $data_attr[$row['attr_id']] = $row['attr_val'];
    //  echo "<br>data: ".$row['attr_id']." / ".$row['attr_val']."/".$data_attr['width'];
    }
  }
  $stmt->close();
  $mysqli->close();


  $txt_row = "";
  if(isset($ATTR_DATATYPE[$data_type])){
    foreach($ATTR_DATATYPE[$data_type] as $attr_id){
      $txt_row .= addRowAttr($attr_id, $data_attr);
    }
  }





function addRowAttr($attr_id, $data_attr){
  $attr_name = "";
  $attr_comp = "";
  $attr_val = isset($data_attr[$attr_id])?$data_attr[$attr_id]:"";
  //echo "<br>$attr_id / $attr_val";
  if($attr_id == "showlabel"){
    $attr_name = "Show Label";
    $check = ($attr_val == '1')?"checked":"";
    $attr_comp = "<input type='checkbox' class='save-data v-checkbox' data-id='$attr_id' data-odata='$attr_val' $check>";
  }
  else if($attr_id == "headrowheight"){
    $attr_name = "Form Head Row Height";
    $attr_comp = "<input type='text' class='save-data v-int' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "rowheight"){
    $attr_name = "Row Height";
    $attr_comp = "<input type='text' class='save-data v-int' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "externallink"){
    $attr_name = "External Link";
    $check = ($attr_val == '1')?"checked":"";
    $attr_comp = "<input type='checkbox' class='save-data v-checkbox' data-id='$attr_id' data-odata='$attr_val' $check>";
  }
  else if($attr_id == "width"){
    $attr_name = "Width";
    $attr_comp = "<input type='text' class='save-data v-int' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }

  else if($attr_id == "placeholder"){
    $attr_name = "Placeholder";
    $attr_comp = "<input type='text' class='save-data v-text' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "tagname"){
    $attr_name = "Tag Name (Reference)";
    $attr_comp = "<input type='text' size=70 class='save-data v-text' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "keyname"){
    $attr_name = "Key Name (Shortcut)";
    $attr_comp = "<input type='text' size=70 class='save-data v-text' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "sumscore"){ // Add by BOM 18052023
    $attr_name = "Sum score (Value)";
    $check = ($attr_val == '1')?"checked":"";
    $attr_comp = "<input type='checkbox' class='save-data v-checkbox' data-id='$attr_id' data-odata='$attr_val' $check>";
  }
  else if($attr_id == "sumscoretotal"){ // Add by BOM 18052023
    $attr_name = "Sum score (Total)";
    $check = ($attr_val == '1')?"checked":"";
    $attr_comp = "<input type='checkbox' class='save-data v-checkbox' data-id='$attr_id' data-odata='$attr_val' $check>";
  }

  else if($attr_id == "optalign"){
    $attr_name = "Choice align";
    $attr_comp = "<select class='save-data v-dropdown' data-id='$attr_id'  data-odata='$attr_val'><option value='V'>Vertical แนวตั้ง</option><option value='H'>Horizontal แนวนอน</option></select>";

  }
  else if($attr_id == "hideprefixsuffix"){
    $attr_name = "Hide Prefix/Suffix";
    $check = ($attr_val == '1')?"checked":"";
    $attr_comp = "<input type='checkbox' class='save-data v-checkbox' data-id='$attr_id' data-odata='$attr_val' $check>";
  }
  else if($attr_id == "hidesomechoice"){
    $attr_name = "Hide Some Choice";
    $attr_comp = "<input type='text' class='save-data v-text' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val' size=70 placeholder='put data value to hide | eg. 1,5,7'>";

  }
  else if($attr_id == "partialdate"){
    $attr_name = "Partial Date";
    $check = ($attr_val == '1')?"checked":"";
    $attr_comp = "<input type='checkbox' class='save-data v-checkbox' data-id='$attr_id' data-odata='$attr_val' $check>";
  }
  else if($attr_id == "isthaidate"){
    $attr_name = "Thai Date (พ.ศ.)";
    $check = ($attr_val == '1')?"checked":"";
    $attr_comp = "<input type='checkbox' class='save-data v-checkbox' data-id='$attr_id' data-odata='$attr_val' $check>";
  }
  else if($attr_id == "notexceedcoldate"){
    $attr_name = "Not exceed (Collect date)";
    $check = ($attr_val == '1')?"checked":"";
    $attr_comp = "<input type='checkbox' class='save-data v-checkbox' data-id='$attr_id' data-odata='$attr_val' $check>";
  }


  else if($attr_id == "minchar"){
    $attr_name = "Min Char";
    $attr_comp = "<input type='text' class='save-data v-int' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "maxchar"){
    $attr_name = "Max Char";
    $attr_comp = "<input type='text' class='save-data v-int' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "minvalue"){
    $attr_name = "Min Value";
    $attr_comp = "<input type='text' class='save-data v-int' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "maxvalue"){
    $attr_name = "Max Value";
    $attr_comp = "<input type='text' class='save-data v-int' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "row"){
    $attr_name = "Row";
    $attr_comp = "<input type='text' class='save-data v-int' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "col"){
    $attr_name = "Column";
    $attr_comp = "<input type='text' class='save-data v-int' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "logformid"){
    $attr_name = "Log form id";
    $attr_comp = "<input type='text' size='70' class='save-data v-int' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "formheight"){
    $attr_name = "Form Height <br>(in master form)";
    $attr_comp = "<input type='text' class='save-data v-int' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }
  else if($attr_id == "onlyvisitid"){
    $attr_name = "Show Data<br>Only Selected Visit ID";
    $check = ($attr_val == '1')?"checked":"";
    $attr_comp = "<input type='checkbox' class='save-data v-checkbox' data-id='$attr_id' data-odata='$attr_val' $check>";
  }
  else if($attr_id == "pagepath"){
    $attr_name = "Page Path";
    $attr_comp = "<input type='text' size='100' class='save-data' data-id='$attr_id'  data-odata='$attr_val' value='$attr_val'>";
  }




  $txt_row = "
  <div class='fl-fix ph30 bg-msoft2 p-row v-mid '>
      <div class='fl-fix pw150 px-1'>
        $attr_name
      </div>
      <div class='fl-fill px-1'>
        $attr_comp
      </div>
  </div>";

  return $txt_row;

}// addRowAttr


?>




  <div class='fl-wrap-col div_data_attr' data-formid='<? echo $sFormid; ?>' data-dataid='<? echo $sDataid; ?>'>

    <div class='fl-fill ptxt-s12'>
      <div class='fl-wrap-col'>
        <div class='fl-fix ph30 bg-mdark2 p-row v-mid ptxt-b ptxt-white px-1'>
            <div class='fl-fix pw150'>
              Attribute <? echo "($data_type)"; ?>
            </div>
            <div class='fl-fill px-1'>
                Value
            </div>
        </div>

        <? echo $txt_row;?>

      </div>

    </div>
    <div class='fl-fix ph50  bg-mdark1 fl-mid'>
        <button class='pbtn pbtn-warning btn_update_attr'> UPDATE Attribute</button>
        <i class='fa fa-spinner fa-spin fa-lg spinner' style="display:none;" ></i>
    </div>
  </div>
<script>
$(document).ready(function(){
  $(".div_data_attr .v-dropdown").each(function(ix,objx){
    //console.log("val: "+$(objx).attr('data-odata'));
    $(objx).val($(objx).attr('data-odata'));
  });

  $('.div_data_attr .btn_update_attr').unbind();
  $('.div_data_attr .btn_update_attr').on("click",function(){

    let formid=$('.div_data_attr').attr("data-formid");
    let dataid=$('.div_data_attr').attr("data-dataid");
    let dataObj = {}; let flag_update = 0;
    $(".div_data_attr .save-data").each(function(ix,objx){
        let data_value = getWDataCompValue(objx);

            //console.log($(objx).attr('data-id')+" data: "+data_value+"/"+$(objx).attr('data-odata'));
        if(data_value != $(objx).attr('data-odata')){
          dataObj[$(objx).attr("data-id")] = data_value;
          flag_update = 1;
        }
    });

    if(flag_update == 1){
      var aData = {
          u_mode:"update_data_attr",
          data_obj:dataObj,
          form_id:formid,
          data_id:dataid
          };

          startLoad($('.btn_update_attr'), $(".btn_update_attr").next(".spinner"));
          callAjax("data_mgt/db_form_editor_log.php",aData,function(rtnObj,aData){
              endLoad($('.btn_update_attr'), $(".btn_update_attr").next(".spinner"));
              if(rtnObj.res == 1){
                $.notify("Attribute UPDATE", "success");
                $(".div_data_attr .save-data").each(function(ix,objx){
                    let data_value = getWDataCompValue(objx);
                    $(objx).attr('data-odata', data_value);
                });
              }
          });// call ajax
    }
    else{
      $.notify("No data changed.", "info");
    }
    //console.log("visit: "+visitid+"/"+visitdate);



  });





});


</script>
