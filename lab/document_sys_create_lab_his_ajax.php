<?
    include("../in_auth_db.php");
    include("../in_db_conn.php");
    include("../in_db_conn_tc.php");
    include_once("../function/in_fn_date.php"); // date function

    $data = json_decode($_POST["data"], true);
    foreach($data AS $key=>$val){
        $doc_code = $val["doc_code"];
        $doc_title = $val["doc_title"];
        $doc_datetime = $val["doc_datetime"];
        $uid = $val["uid"];
        $coldate = $val["coldate"];
        $coltime = $val["coltime"];
        $sid = $val["sid"];
        $hide_name = $val["hide_name"];
        $hide_proj = $val["hide_proj"];
        $lab_id = $val["lab_id"];
    }

    if($hide_name == "true"){
        $hide_name = "1";
    }
    if($hide_proj == "true"){
        $hide_proj = "1";
    }

    // insert doc list
    $status_ins = false;
    $query = "INSERT into i_doc_list values('$doc_code', '$doc_title', '$doc_datetime', '', '$uid', '$coldate', '$coltime', '$sid', '1');";
    $stmt = $mysqli->prepare($query);
    
    if($stmt->execute()){
        $status_ins = true;
    } 
    else{
        echo "Error: " . $query . "<br>" . $stmt->error;
    }
    $stmt->close();

    // insert order his
    if($status_ins == true){
        $status_ins = false;
        $bind_param = "sss";
        $array_val = array($uid, $coldate, $coltime);
        $strIns_lab_order_his = "";

        $query = "SELECT
            o.lab_order_id,
            o.lab_report_note,
            o.uid,
            o.collect_date,
            o.collect_time,
            s.id AS status_id,
            s.NAME AS status_name,
            p.fname,
            p.sname,
            p.en_fname,
            p.en_sname,
            p.date_of_birth AS dob,
            p.sex,
            p.passport_id,
            po.s_name AS staff_order,
            o.proj_id,
            o.proj_pid,
            o.proj_visit,
            o.timepoint_id,
            o.lab_specimen_receive,
            o.lab_specimen_collect,
            o.time_specimen_collect,
            ps.s_name AS staff_lab_save,
            ps.license_lab AS staff_lab_save_license,
            pc.s_name AS staff_confirm,
            pc.license_lab AS staff_confirm_license,
            o.time_lab_report_confirm
        FROM
            p_lab_order AS o
            LEFT JOIN p_lab_status AS s ON o.lab_order_status = s.id
            LEFT JOIN patient_info AS p ON ( BINARY o.uid = p.uid )
            LEFT JOIN p_staff AS po ON ( o.staff_order = po.s_id )
            LEFT JOIN p_staff AS ps ON ( o.staff_lab_save = ps.s_id )
            LEFT JOIN p_staff AS pc ON ( o.staff_confirm = pc.s_id ) 
        WHERE
            o.lab_order_id = (select lab_order_id from p_lab_order where uid = ? and collect_date = ? and collect_time = ? and lab_order_status != 'C');";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param($bind_param, ...$array_val);

        if($stmt->execute()){
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $strIns_lab_order_his = "INSERT into log_p_lab_order_his(lab_order_id,lab_report_note,uid,collect_date,collect_time,status_id,status_name,fname,sname,en_fname,en_sname,dob,sex,passport_id,staff_order,proj_id,proj_pid,proj_visit,timepoint_id,lab_specimen_receive,lab_specimen_collect,time_specimen_collect,staff_lab_save,staff_lab_save_license,staff_confirm,staff_confirm_license,time_lab_report_confirm,doc_datetime,hide_patient_name,hide_project) 
                values('".$row["lab_order_id"]."'
                ,'".$row["lab_report_note"]."'
                ,'".$row["uid"]."'
                ,'".$row["collect_date"]."'
                ,'".$row["collect_time"]."'
                ,'".$row["status_id"]."'
                ,'".$row["status_name"]."'
                ,'".$row["fname"]."'
                ,'".$row["sname"]."'
                ,'".$row["en_fname"]."'
                ,'".$row["en_sname"]."'
                ,'".$row["dob"]."'
                ,'".$row["sex"]."'
                ,'".$row["passport_id"]."'
                ,'".$row["staff_order"]."'
                ,'".$row["proj_id"]."'
                ,'".$row["proj_pid"]."'
                ,'".$row["proj_visit"]."'
                ,'".$row["timepoint_id"]."'
                ,'".$row["lab_specimen_receive"]."'
                ,'".$row["lab_specimen_collect"]."'
                ,'".$row["time_specimen_collect"]."'
                ,'".$row["staff_lab_save"]."'
                ,'".$row["staff_lab_save_license"]."'
                ,'".$row["staff_confirm"]."'
                ,'".$row["staff_confirm_license"]."'
                ,'".$row["time_lab_report_confirm"]."'
                ,'$doc_datetime'
                ,'$hide_name'
                ,'$hide_proj');";
            }
            $status_ins = true;
        }
        else{
            echo "Error: " . $query . "<br>" . $stmt->error;
        }
        $stmt->close();
        // echo "query str:".$strIns_lab_order_his;

        if($status_ins == true){
            $status_ins = false;
            $stmt = $mysqli->prepare($strIns_lab_order_his);
            if($stmt->execute()){
                $last_log_lab_order_id = $stmt->insert_id;
                $status_ins = true;
            }
            else{
                echo "Error: " . $query . "<br>" . $stmt->error;
            }
            $stmt->close();

            // insert specimen collect his
            if($status_ins == true){
                $status_ins = false;
                $bind_param = "sss";
                $array_val = array($uid, $coldate, $coltime);
                $strInsLabSpecimenHis = "";

                $query = "INSERT into log_p_lab_specimen_his(specimen_name,specimen_transform,specimen_id,time_specimen_collect,lab_group_id,laboratory_id,uid,collect_date,collect_time,pk_log_p_lab_order)
                SELECT
                    SP.specimen_name,
                    SP.specimen_transform,
                    LOS.specimen_id,
                    LOS.time_specimen_collect,
                    LOSP.lab_group_id,
                    LOSP.laboratory_id,
                    '$uid', 
                    '$coldate', 
                    '$coltime',
                    '$last_log_lab_order_id'
                FROM
                p_lab_order_specimen LOS
                LEFT JOIN p_lab_order_specimen_process LOSP ON LOSP.barcode = LOS.barcode
                LEFT JOIN p_lab_specimen SP ON SP.specimen_id = LOS.specimen_id 
                WHERE
                LOS.uid = ?
                AND LOS.collect_date = ?
                AND LOS.collect_time = ? ;";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param($bind_param, ...$array_val);

                if($stmt->execute()){
                    $status_ins = true;
                }
                else{
                    echo "Error: " . $query . "<br>" . $stmt->error;
                }
                $stmt->close();

                if($status_ins == true){
                    $status_ins = false;
                    $bind_param = "ssss";
                    $array_val = array($sid, $uid, $coldate, $coltime);

                    $query = "INSERT into log_p_lab_result_his(lab_id2,lab_id,lab_name,lab_name_report,lab_serial_no,ext_lab,lab_result_report,lab_result_note,lab_result_status,lab_method_name,lab_group_id,laboratory_id,specimen_transform,staff_save,staff_save_license,staff_confirm,staff_confirm_license,staff_print_by,time_lab_confirm,m_lab_std_txt,m_min,m_max,f_lab_std_txt,f_min,f_max,uid,collect_date,collect_time,pk_log_p_lab_order)
                    SELECT
                        PLT.lab_id2,
                        PLR.lab_id,
                        PLT.lab_name,
                        PLT.lab_name_report,
                        PLR.lab_serial_no,
                        PLR.external_lab AS ext_lab,
                        PLR.lab_result_report,
                        PLR.lab_result_note,
                        PLR.lab_result_status,
                        PLM.lab_method_name,
                        PLT.lab_group_id,
                        PLO.laboratory_id,
                        PLT.specimen_transform,
                        PS.s_name AS staff_save,
                        PS.license_lab AS staff_save_license,
                        PC.s_name AS staff_confirm,
                        PC.license_lab AS staff_confirm_license,
                        PP.s_name AS staff_print_by,
                        PLP.time_lab_confirm,
                        PLTRH.lab_std_male_txt AS m_lab_std_txt,
                        PLT.lab_result_min_male AS m_min,
                        PLT.lab_result_max_male AS m_max,
                        PLTRH.lab_std_female_txt AS f_lab_std_txt,
                        PLT.lab_result_min_female AS f_min,
                        PLT.lab_result_max_female AS f_max,
                        '$uid', 
                        '$coldate', 
                        '$coltime',
                        '$last_log_lab_order_id'
                    FROM
                        p_lab_result PLR
                        LEFT JOIN p_lab_order_lab_test PLO ON PLO.uid = PLR.uid 
                        AND PLO.collect_date = PLR.collect_date 
                        AND PLO.collect_time = PLR.collect_time 
                        AND PLO.lab_id = PLR.lab_id
                        LEFT JOIN p_lab_test PLT ON PLT.lab_id = PLR.lab_id
                        LEFT JOIN p_lab_test_group PLTG ON PLTG.lab_group_id = PLT.lab_group_id
                        LEFT JOIN p_lab_method PLM ON PLM.lab_method_id = PLTG.lab_method_id
                        LEFT JOIN p_lab_test_result_hist PLTRH ON PLTRH.lab_id = PLT.lab_id
                        LEFT JOIN p_lab_process PLP ON ( PLP.lab_serial_no = PLR.lab_serial_no AND PLP.lab_process_status = 'P1' )
                        LEFT JOIN p_staff PS ON PS.s_id = PLP.staff_save
                        LEFT JOIN p_staff PC ON PC.s_id = PLP.staff_confirm
                        LEFT JOIN p_staff PP ON PP.s_id = ?
                    WHERE
                        PLTRH.start_date <= now() AND PLTRH.stop_date > now() 
                        AND PLT.lab_id IN ($lab_id) 
                        AND PLR.uid = ?
                        AND PLR.collect_date = ?
                        AND PLR.collect_time = ?
                        AND PLR.lab_result <> '' 
                    ORDER BY
                        PLR.external_lab,
                        PLT.lab_group_id,
                        PLT.lab_id2;";
                    
                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param($bind_param, ...$array_val);
                    
                    if($stmt->execute()){
                        $status_ins = true;
                    }
                    else{
                        echo "Error: " . $query . "<br>" . $stmt->error;
                    }
                }
            }
        }
    }

    echo $status_ins;
?>