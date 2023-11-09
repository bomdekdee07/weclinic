<?
    include('../in_db_conn.php');
    include('../in_php_function.php');

    $tbl_name = getQS("tbl_name");
    $flag_auth = 1;
    $msg_error = "";
    $msg_info = "";
    $returnData = "";
    $u_mode = $tbl_name;

    if($flag_auth != 0){
        function updateListDataObj($tbl_name, $lst_data_item){
            //print_r($lst_data_item);
            global $mysqli; // db
            global $msg_error;
            $sid = getQS("sid");
            
            $flag_success = true;
            $col_insert = "";
            $col_update = "";
            $col_value = "";
            $colume_val = "";
            $colume_val_id = "";
        
            foreach ($lst_data_item as $col => $value){
                if($col == "data_old"){
                if($value != ""){
                    $colume_val = $value;
                }
                else{
                    $colume_val = null;
                }
                }
            }
        
            foreach ($lst_data_item as $col => $value){
                if($col == "data_old_id"){
                if($value != ""){
                    $colume_val_id = $value;
                }
                else{
                    $colume_val_id = null;
                }
                }
            }
            // echo $colume_val."/".$colume_val_id;
            
            foreach ($lst_data_item as $col => $value){
                // echo "TESTLL:"."$col / $value"."<br>";
                if($col != "data_old"){
                    if($col != "data_old_id"){
                        $col_insert .= $col.",";
                        $col_value .= "'".($colume_val_id == $col? ($colume_val != null? $colume_val : $value) : $value)."',";
                        $col_update .= $col."='".$value."',";
                    }
                }
            }
            
            $col_insert = ($col_insert !="")?substr($col_insert,0,strlen($col_insert)-1):"" ;
            $col_update = ($col_update !="")?substr($col_update,0,strlen($col_update)-1):"" ;
            $col_value = ($col_value !="")?substr($col_value,0,strlen($col_value)-1):"" ;
            
            if($col_value != ""){
                $query = "INSERT INTO $tbl_name ($col_insert)
                VALUES ($col_value) On Duplicate Key
                Update $col_update";
                // echo $query;
                $stmt = $mysqli->prepare($query);
            
                if($stmt->execute()){}
                else{
                    $flag_success = false;
                    $msg_error .= $stmt->error; //error จะบอกตรงนี้ ถ้า duplicate kry
                }
                $stmt->close();

                $sql_cmd = "update:[$tbl_name] $col_update";
                $query = "INSERT INTO a_log_cmd (update_user, sql_cmd) VALUES(?, ?)";
                $stmt = $mysqli->prepare($query);

                // echo "query: $query";
                $stmt->bind_param('ss',$sid,$sql_cmd);

                if($stmt->execute()){
                }
                else{
                    $msg_error .= $stmt->error;
                }
                $stmt->close();
            }// if($col_value != "")
            
            return $flag_success;
        }
            
        // delete
        function deleteListDataObj($tbl_name,$lst_where_data_item){
            global $mysqli; // db
            global $msg_error;
            $sid = getQS("sid");
        
            $str_where = "";
            foreach ($lst_where_data_item as $col => $value){
            $str_where .= " $col = '$value' AND ";
            }
        
            if($str_where != ""){
            $str_where = substr($str_where,0,strlen($str_where)-4);
            $str_where = " WHERE $str_where ";
        
            $query = "DELETE FROM $tbl_name $str_where";
            // echo "query: $query";
            $stmt = $mysqli->prepare($query);
            if($stmt->execute()){}
            else{
                $msg_error .= $stmt->error;
            }
            $stmt->close();
            }
        
            $sql_cmd = "delete:[$tbl_name] $str_where";
            $query = "INSERT INTO a_log_cmd (update_user, sql_cmd)
            VALUES(?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ss', $sid ,$sql_cmd);
            if($stmt->execute()){
        
            }
            else{
            $msg_error .= $stmt->error;
            }
            $stmt->close();
        }

        // Create Sinature
        if($u_mode == "package_lab_group"){
            $group_enable = getQS("group_enable");
            $sid = getQS("sid");
            $pack_id = getQS("lab_packid");
            $laboratory_id = getQS("laboratory_id");
            $sale_option_id = getQS("sale_option_id");

            $flag_auth=1;

            $lst_data_update = array();
            if($flag_auth == 1){      
                $lst_data_update["lab_package_id"] = $pack_id;
                $lst_data_update["group_enable"] = $group_enable;
                $lst_data_update["laboratory_lab"] = $laboratory_id;
                $lst_data_update["sale_option"] = $sale_option_id;
                $lst_data_update["s_id"] = $sid;
                $lst_data_update["upd_date"] = date('Y-m-d H:i:s');

                // print_r($lst_data_update);
                updateListDataObj($tbl_name, $lst_data_update);
            }
        }
    }

    // return object
    $rtn['mode'] = $u_mode;
    $rtn['msg_error'] = $msg_error;
    $rtn['msg_info'] = $msg_info;
    $rtn['flag_auth'] = $flag_auth;

    $returnData = json_encode($rtn);
    echo $returnData;
?>