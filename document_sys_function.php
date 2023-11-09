<?
    include("in_auth_db.php");
    include("in_db_conn.php");
    include("in_db_conn_tc.php");
    include_once("function/in_fn_date.php"); // date function

    $doc_code = isset($_POST["doctype"])?$_POST["doctype"]: getQS("doctype");
    $uid = isset($_POST["uid"])?$_POST["uid"]: getQS("uid");
    $coldate = getQS("coldate");
    $coltime = getQS("coltime");
    $bill_id = isset($_POST["billid"])?$_POST["billid"]: getQS("billid");
    
    $sSID = getSS("s_id");
    $sClinicID = getSS("clinic_id");
    // echo "code: ".$doc_code."/".$uid;

    if($bill_id != ""){
        $uid = $bill_id;
    }

    $parameter_like = "";
    $parameter_like = "'".$uid."%'";
    $data_check_cash_sub = 0;

    $query = "SELECT count(*) AS count_total_sub 
    from i_doc_list 
    where doc_code = 'RECEIPT_SUB' 
    and uid like ".$parameter_like."
    and doc_status = '1';";
    $stmt = $mysqli->prepare($query);

    if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            $data_check_cash_sub = $row["count_total_sub"];
        }
        // echo $data_check_cash_sub;
    }
    $stmt->close();

    $str_query_cash_sub = "";
    $str_query_cash_sub0 = "";
    if($data_check_cash_sub > 0){
        $str_query_cash_sub0 .= " OR main.doc_code = 'RECEIPT_SUB' ";
        $str_query_cash_sub .= " OR main.uid like ".$parameter_like." ";
    }

    $data_doc_detail = array();
    $query = "SELECT CONCAT(
		main.doc_code,
		'_',
		main.uid,
		'_',
        REPLACE ( REPLACE ( REPLACE ( main.doc_datetime, '-', '' ), ':', '' ), ' ', '' )) AS doc_match_name,
        main.doc_datetime,
        main.doc_note,
        CONCAT( main.collect_date, ' ', main.collect_time ) AS visit,
        ( SELECT DISTINCT s_name FROM p_staff WHERE s_id = main.s_id ) AS name_staff,
        main.doc_status,
        lab_order_his.pk_log_p_lab_order,
        main.uid,
        main.collect_date,
        main.collect_time,
        main.doc_datetime
    from i_doc_list as main
    left join log_p_lab_order_his AS lab_order_his on(lab_order_his.uid = main.uid and lab_order_his.collect_date = main.collect_date and lab_order_his.collect_time = main.collect_time and lab_order_his.doc_datetime = main.doc_datetime)
    where (main.doc_code = ? ".$str_query_cash_sub0.")
    and main.uid = ?
    and main.collect_date = ?
    and main.collect_time = ? 
    $str_query_cash_sub
    and main.doc_status = 1
    order by main.doc_datetime DESC;";
    // echo $query;

    $stmt = $mysqli->prepare($query);
    $stmt -> bind_param("ssss", $doc_code, $uid, $coldate, $coltime);

    if($stmt->execute()){
        $stmt->bind_result($doc_match_name, $doc_datetime, $doc_note, $visit, $name_staff, $doc_status, $pk_log_p_lab_order, $uid, $coldate, $coltime, $doc_datetime);
        while ($stmt->fetch()) {
            $data_doc_detail[$doc_match_name]["cre_date"] = $doc_datetime;
            $data_doc_detail[$doc_match_name]["note"] = $doc_note;
            $data_doc_detail[$doc_match_name]["visit"] = $visit;
            $data_doc_detail[$doc_match_name]["by"] = $name_staff;
            $data_doc_detail[$doc_match_name]["status"] = $doc_status;
            $data_doc_detail[$doc_match_name]["view_name"] = $doc_match_name;
            $data_doc_detail[$doc_match_name]["pk_log"] = $pk_log_p_lab_order;
            $data_doc_detail[$doc_match_name]["uid"] = $uid;
            $data_doc_detail[$doc_match_name]["coldate"] = $coldate;
            $data_doc_detail[$doc_match_name]["coltime"] = $coltime;
            $data_doc_detail[$doc_match_name]["doc_datetime"] = $doc_datetime;
        }
        // print_r($data_doc_detail);
    }
    $stmt->close();
    $mysqli->close();

    $sJS = '';
    
    if(count($data_doc_detail) > 0){
        foreach($data_doc_detail as $key => $value){
            $sJS .= '<div class="fl-wrap-row row-color document_detail" style="margin-left: 2px; margin-top: 3px">';
            $sJS .=     '<div class="fl-fix holiday-text-detail holiday-smallfont2" style="min-width: 175px;">';
            $sJS .=         '<span>'.$value["cre_date"].'</span>';
            $sJS .=     '</div>';
            $sJS .=     '<div class="fl-fill holiday-text-detail-left holiday-smallfont2">';
            $sJS .=         '<span>'.$value["note"].'</span>';
            $sJS .=     '</div>';
            $sJS .=     '<div class="fl-fix holiday-text-detail holiday-smallfont2" style="min-width: 210px;">';
            $sJS .=         '<span>'.$value["visit"].'</span>';
            $sJS .=     '</div>';
            $sJS .=     '<div class="fl-fix holiday-text-detail-left holiday-smallfont2" style="min-width: 200px;">';
            $sJS .=         '<span>'.$value["by"].'</span>';
            $sJS .=     '</div>';
            $sJS .=     '<div class="fl-fix holiday-text-detail holiday-smallfont2" style="min-width: 80px;">';
            $sJS .=         '<span><i class="fa fa-check-square" aria-hidden="true" data-value="'.$value["status"].'" style="color: #6DC23E;"></i></span>';
            $sJS .=     '</div>';
            $sJS .=     '<div class="fl-fix holiday-text-detail holiday-smallfont2" style="min-width: 80px;">';
            // if(isset($_SESSION["DOC"][$doc_code]["view"])){
                $sJS .=         '<span><i class="fa fa-search document-click" aria-hidden="true" data-pk="'.$value["pk_log"].'" data-uid="'.$value["uid"].'" data-coldate="'.$value["coldate"].'" data-coltime="'.$value["coltime"].'" data-docdatetime="'.$value["doc_datetime"].'" style="color: #F5781C;"></i></span>';
            // }else{
            //     $sJS .=         '<span></span>';
            // }
            $sJS .=     '</div>';
            $sJS .= '</div>';
        }

        echo $sJS;
    }
?>

<script>
    $(document).ready(function(){
        $(".document_detail .document-click").unbind("click");
        $(".document_detail").on("click",".document-click",function(){
            var sPkVal = $(this).data("pk");
            var sUid = $(this).data("uid");
            var sColdate = $(this).data("coldate");
            var sColtime = $(this).data("coltime");
            var sDateTime = $(this).data("docdatetime");
            var gen_link = "lab/custom_lab_report_his.php?pk="+sPkVal+"&uid="+sUid+"&coldate="+sColdate+"&coltime="+sColtime+"&doc_datetime="+sDateTime;
            // console.log(gen_link);
            window.open(gen_link,'_blank');
        });
    });
</script>