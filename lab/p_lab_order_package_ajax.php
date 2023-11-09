<?
    include('../in_db_conn.php');

    function getQS($sName,$sDef=""){
        $sResult = (isset($_GET[$sName])?urlencode($_GET[$sName]):"");
        if($sResult=="") $sResult = (isset($_POST[$sName])?urlencode($_POST[$sName]):"");
        if($sResult=="null" || $sResult=="") $sResult=$sDef;
        return urlDecode($sResult);
    }

    $u_mode = getQS("u_mode");
    $uid = getQS("uid");
    $coldate = getQS("coldate");
    $coltime = getQS("coltime");
    $package_id_fixs = getQS("package_id");

    $rtn = array();
    // echo "check:".$u_mode."/".$package_id_fixs;

    if($u_mode == "update_p_lab_order_package"){
        $rtn["success"] = false;
        $bind_param = 'ssss';
        $array_val = array($package_id_fixs, $uid, $coldate, $coltime);

        $query = "UPDATE p_lab_order set package_lab_id = ? where uid = ? and collect_date = ? and collect_time = ?;";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param($bind_param, ...$array_val);

        if($stmt->execute()){}
        $stmt->close();
        $mysqli->close();

        $rtn["success"] = true;
        $rtnConvert = json_encode($rtn);
        echo $rtnConvert;
    }
?>