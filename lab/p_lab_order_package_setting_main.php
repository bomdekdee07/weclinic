<?
    include('../in_db_conn.php');
    include('../in_php_function.php');

    $package_id = getQS("package_id");
    $uid = getQS("uid");
    $coldate = getQS("coldate");
    $coltime = getQS("coltime");
    $sid = getQS("sid");

    $bind_param = "s";
    $array_val = array($package_id);

    $query = "SELECT group_enable,
        laboratory_lab,
        sale_option
    from package_lab_group
    where lab_package_id = ?;";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param($bind_param, ...$array_val);

    $data_package_group = "";
    $data_laboratory = "";
    $data_sale_option = "";
    if($stmt->execute()){
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            $data_package_group = $row["group_enable"];
            $data_laboratory = $row["laboratory_lab"];
            $data_sale_option = $row["sale_option"];
        }
        // print_r($data_package_group);
    }
    $stmt->close();
    $mysqli->close();

    $html_js = "";
    if($data_package_group != "" && $data_package_group == "1"){
        $html_js .= '$("[name=group_enable]").attr("checked", true);';
        $html_js .= '$("[name=laboratory_lab]").val('.json_encode($data_laboratory).');';
        $html_js .= '$("[name=sale_option]").val('.json_encode($data_sale_option).');';
    }
?>
<link rel="stylesheet" href="../weclinic/asset/css/bom.css?t<? echo("=".time()); ?>" />

<div class="fl-wrap-col" id="package_setting_lab" data-sid="<?echo $sid; ?>" data-uid="<? echo $uid; ?>" data-coldate="<? echo $coldate; ?>" data-coltime="<? echo $coltime; ?>">
    <div class="fl-wrap-row h-20"></div>
    <div class="fl-wrap-row h-25 font-s-2">
        <div class="fl-fix w-15"></div>
        <div class="fl-fix w-200">
            <label class="fl-mid-left"><input type="checkbox" name="group_enable" value="<?echo $package_id; ?>" class="bigcheckbox save-data"><i class="fas fa-spinner fa-spin spinner" style="display:none;"></i><span class="holiday-ml-1">Group <?echo $package_id; ?></span></label>
        </div>
        <div class="fl-fix w-230">
            <select id="laboratory_lab" name="laboratory_lab" class="w-230 package-lab-option">
                <option value="">-- Please select Laboratory Lab --</option>
                <? include_once("p_lab_opt_laboratory.php"); ?>
            </select>
        </div>
        <div class="fl-fix w-10"></div>
        <div class="fl-fix w-300">
            <select id="sale_option" name="sale_option" class="w-300 package-lab-option">
                <option value="">-- Please select Sale Option --</option>
                <? include_once("p_lab_opt_sale.php"); ?>
            </select>
        </div>
    </div>
    <div class="fl-wrap-row h-15"></div>
    <div class="fl-wrap-row h-25">
        <div class="fl-fix w-15"></div> 
        <div class="fl-fill fl-mid-left">
            <button id="bt_close_setting_package_lab" class="btn btn-danger font-s-2" style="padding: 0px 5px 0px 5px;">Close</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        <? echo $html_js; ?>

        // BT CLOSE
        $("#package_setting_lab #bt_close_setting_package_lab").off("click");
        $("#package_setting_lab #bt_close_setting_package_lab").on("click", function(){
            close_dlg($(this));
        });

        // Change select option
        $("#package_setting_lab .package-lab-option").off("change");
        $("#package_setting_lab .package-lab-option").on("change", function(){
            alert("โปรดติ๊กอีกครั้งเพื่ออัพเดทข้อมูล");
            $("[name=group_enable]").prop("checked", false);
        });

        // Update check box status
        $("#package_setting_lab [name=group_enable]").off("change");
        $("#package_setting_lab [name=group_enable]").on("change", function(){
            $("#package_setting_lab [name=group_enable]").hide();
            $("#package_setting_lab [name=group_enable]").next().show();

            var laboratory_lab_check = $("[name=laboratory_lab]").val();
            var sale_option_check = $("[name=sale_option]").val();
            if(laboratory_lab_check == "" || sale_option_check == ""){
                alert("Please Select Laboratory Lab or Sale Option.");
                $("[name=group_enable]").prop("checked", false);
                if(laboratory_lab_check == "")
                    $("[name=laboratory_lab]").attr("style", "background-color:red");
                if(sale_option_check == "")
                    $("[name=sale_option]").attr("style", "background-color:red");

                $("#package_setting_lab [name=group_enable]").show();
                $("#package_setting_lab [name=group_enable]").next().hide();
            }
            else{
                $("[name=laboratory_lab]").attr("style", "background-color:#85EB52");
                $("[name=sale_option]").attr("style", "background-color:#85EB52");

                var getval_staus = $("#package_setting_lab [name=group_enable]").is(":checked");
                var sSid = $("#package_setting_lab").attr("data-sid");
                var sUid = $("#package_setting_lab").attr("data-uid");
                var sColdate = $("#package_setting_lab").attr("data-coldate");
                var sColtime = $("#package_setting_lab").attr("data-coltime");
                var sLab_packid = $(this).val();
                var sLaboratory_val = $("[name=laboratory_lab]").val();
                var sSaleOption_val = $("[name=sale_option]").val();
                var convert_status = "";

                if(getval_staus)
                    convert_status = "1";
                else
                    convert_status = "0";
                
                var aData = {
                    tbl_name: "package_lab_group",
                    group_enable: convert_status,
                    sid: sSid,
                    lab_packid: sLab_packid,
                    laboratory_id : sLaboratory_val,
                    sale_option_id : sSaleOption_val
                };
                // console.log(aData);

                $.ajax({
                    url: "lab/p_lab_order_package_setting_updAjax.php",
                    method: "POST",
                    cache: false,
                    data: aData,
                    success: function(sReturn){
                        var json_rtn = $.parseJSON(sReturn);
                        if(json_rtn["msg_error"] == ""){
                            $("#package_setting_lab [name=group_enable]").show();
                            $("#package_setting_lab [name=group_enable]").next().hide();
                        }
                    }
                });
            }
        });
    });

    function close_dlg(obj){
        closeDlg(obj, "0");
    }
</script>