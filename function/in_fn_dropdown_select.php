<?

//drop down function

function dropdownSetSelect($opt_txt, $id_param){
  $opt_txt = str_replace("'$id_param'","'$id_param' selected",$opt_txt);
  echo $opt_txt;
}

// get multiple selected to database query
function extractToDBQuery($opt_value){
//  echo "option value : ".count($opt_value);
  $opt_txt = "";
  if(count($opt_value) > 0){
    //$optArray = explode(',', $opt_value);
    foreach ($opt_value as $opt_itm){
       $opt_txt .= "'".$opt_itm."',";
    }//foreach
    $opt_txt = substr($opt_txt,0,strlen($opt_txt)-1);
  }

  return $opt_txt;
}
/*
// get multiple selected to database query
function extractToDBQuery($opt_value){
  echo "option value : $opt_value";
  $opt_txt = "";
  if($opt_value != ""){
    $optArray = explode(',', $opt_value);
    foreach ($optArray as $opt_itm){
       $opt_txt .= "'".$opt_itm."',";
    }//foreach
    $opt_txt = substr($opt_txt,0,strlen($opt_txt)-1);
  }

  return $opt_txt;
}
*/
?>
