<?
    include("in_auth_db.php");
    include("in_db_conn.php");
    include("in_db_conn_tc.php");
    include_once("function/in_fn_date.php"); // date function

    $sSID = getSS("s_id");
    if($sSID == ""){
        echo("Please login.");
        exit();
    }
    $sClinicID = getSS("clinic_id");
    $doc_type = isset($_POST["doctype"])?$_POST["doctype"]: getQS("doctype");
    $uid = isset($_POST["uid"])?$_POST["uid"]: getQS("uid");
    $coldate = isset($_POST["coldate"])?$_POST["coldate"]: getQS("coldate");
    $coltime = isset($_POST["coltime"])?$_POST["coltime"]: getQS("coltime");
    $temp_name_file = isset($_POST["tempname_file"])?$_POST["tempname_file"]: getQS("tempname_file");
    $bill_id = isset($_POST["billid"])?$_POST["billid"]: getQS("billid");
    // echo $temp_name_file;

    $check_coldate_cur = true;
    // if($coldate != date("Y-m-d")){
    //     $check_coldate_cur = "false";
    // }

    $sJS = "";
    $sJS .= '<div id="document_sub_bt_create">
                <div class="data-defult" data-checkdate="'.$check_coldate_cur.'" data-doccode="'.$doc_type.'" data-clinicid="'.$sClinicID.'" data-ss="'.$sSID.'"  data-uid="'.$uid.'" data-coldate="'.$coldate.'" data-coltime="'.$coltime.'" data-tempfile="'.$temp_name_file.'" data-billid="'.$bill_id.'">
                ';
    // if(isset($_SESSION["DOC"][$doc_type]["create"])){
        $sJS .= '<button type="button" id="document_new" class="btn btn-success font-s-1" style="padding: 4px 8px 4px 8px;"><b><i class="fa fa-plus-circle" aria-hidden="true"></i> สร้างเอกสารใหม่</b></button>';
    // }
    // else{
    //     $sJS .= '<span></span>';
    // }
    $sJS .= '</div>';
    $sJS .= '</div>';
    
    echo $sJS;
?>

<script>
    $(document).ready(function(){
        $("#document_sub_bt_create #document_new").off("click");
        $("#document_sub_bt_create #document_new").on("click", function(){
            var d = new Date(Date.now());
            var month = d.getMonth()+1;
            var day = d.getDate();
            var year = d.getFullYear();

            var doc_code = $("#document_sub_bt_create .data-defult").data("doccode");
            var uid_send = $("#document_sub_bt_create .data-defult").data("uid");
            var coldate_send = $("#document_sub_bt_create .data-defult").data("coldate");
            var coltime_send = $("#document_sub_bt_create .data-defult").data("coltime");
            var sid_send = $("#document_sub_bt_create .data-defult").data("ss");
            var tempfile_send = $("#document_sub_bt_create .data-defult").data("tempfile");
            var tempfile_pdf = String($("#document_sub_bt_create .data-defult").data("tempfile"));
            var con_tempfile_pdf = tempfile_pdf.substr(0, tempfile_pdf.indexOf("_main"));
            var check_cur_date = $("#document_sub_bt_create .data-defult").data("checkdate");
            var bill_id = $("#document_sub_bt_create .data-defult").data("billid");

            var check_condition_old = false;
            var cur_date = year+"-"+(month<10 ? '0' : '') + month +"-"+(day<10 ? '0' : '')+day;
            var time = (d.getHours()<10? "0":"")+d.getHours() + ":" + (d.getMinutes()<10? "0":"")+d.getMinutes() + ":" + (d.getSeconds()<10? "0":"")+d.getSeconds();
            if(coldate_send != cur_date)
                check_condition_old = true;

            var current_datetime = cur_date+" "+time;
            var sHidename = $(".chk-hidename").is(":checked");
            var sHideproj = $(".chk-hideproj").is(":checked");
            var lab_id_str = $("#document_main .data_defult").attr("data-labid");

            var data = [];
            var lab_ins_data = {
                doc_code: "LAB_REPORT_HIS",
                doc_title: "Report Lab History",
                doc_datetime: current_datetime,
                uid: uid_send,
                coldate: coldate_send,
                coltime: coltime_send,
                sid: sid_send,
                hide_name: sHidename,
                hide_proj: sHideproj,
                lab_id: lab_id_str
            };

            data.push(lab_ins_data);
            var jSonLabInsStr = JSON.stringify(data);
            // console.log(jSonLabInsStr);

            if(check_cur_date != false){
                if(check_condition_old == true){
                    if (confirm('คุณต้องการสร้างเอกสารย้อนหลัง?')) {
                        $.ajax({
                            url: "lab/document_sys_create_lab_his_ajax.php",
                            method: "POST",
                            cache: false,
                            data: {data: jSonLabInsStr},
                            success: function(sReturn){
                                if(sReturn == 1){
                                    var uid_send = $("#document_sub_bt_create .data-defult").data("uid");
                                    var coldate_send = $("#document_sub_bt_create .data-defult").data("coldate");
                                    var coltime_send = $("#document_sub_bt_create .data-defult").data("coltime");
                                    var bill_id = $("#document_sub_bt_create .data-defult").data("billid");
                                    var url_reload = "document_sys_function.php?doctype=LAB_REPORT_HIS&uid="+uid_send+"&coldate="+coldate_send+"&coltime="+coltime_send+"&billid="+bill_id;

                                    $("#document_main #document_show_data").load(url_reload);
                                }
                            }
                        });
                    }
                }
                else{
                    $.ajax({
                        url: "lab/document_sys_create_lab_his_ajax.php",
                        method: "POST",
                        cache: false,
                        data: aData,
                        success: function(sReturn){
                            if(sReturn == 1){
                                var uid_send = $("#document_sub_bt_create .data-defult").data("uid");
                                var coldate_send = $("#document_sub_bt_create .data-defult").data("coldate");
                                var coltime_send = $("#document_sub_bt_create .data-defult").data("coltime");
                                var bill_id = $("#document_sub_bt_create .data-defult").data("billid");
                                var url_reload = "document_sys_function.php?doctype=LAB_REPORT_HIS&uid="+uid_send+"&coldate="+coldate_send+"&coltime="+coltime_send+"&billid="+bill_id;

                                $("#document_main #document_show_data").load(url_reload);
                            }
                        }
                    });
                }
            }
            else{
                alert("ไม่สามารถสร้างเอกสารใหม่ ได้เนื่องจากเป็นข้อมูลเก่า");
            }
        });
    });
</script>