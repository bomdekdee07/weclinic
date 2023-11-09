<?
// lab setting data structure


$arr_setting_tbl = array();

$arr_setting_tbl["specimen"] = array();
$arr_setting_tbl["specimen"]["tbl_name"] = "p_lab_specimen";
$arr_setting_tbl["specimen"]["col_id"] = "specimen_id";
$arr_setting_tbl["specimen"]["prefix"] = "SP";
$arr_setting_tbl["specimen"]["id_digit"] = 4;
$arr_setting_tbl["specimen"]["col_sel_list"] = "specimen_id as id, specimen_name as name, specimen_unit as unit ";
$arr_setting_tbl["specimen"]["col_sel_detail"] = "specimen_id as id, specimen_name as name, specimen_unit as unit, specimen_note as note ";
$arr_setting_tbl["specimen"]["search_by"] = " specimen_name LIKE 'sTXT' ";
$arr_setting_tbl["specimen"]["order_by"] = " specimen_name ASC ";


$arr_setting_tbl["laboratory"] = array();
$arr_setting_tbl["laboratory"]["tbl_name"] = "p_lab_laboratory";
$arr_setting_tbl["laboratory"]["col_id"] = "laboratory_id";
$arr_setting_tbl["laboratory"]["prefix"] = "LBT";
$arr_setting_tbl["laboratory"]["id_digit"] = 3;
$arr_setting_tbl["laboratory"]["col_sel_list"] = "laboratory_id as id, laboratory_name as name";
$arr_setting_tbl["laboratory"]["col_sel_detail"] = "laboratory_id as id, laboratory_name as name, laboratory_note as note";
$arr_setting_tbl["laboratory"]["search_by"] = " laboratory_name LIKE 'sTXT' ";
$arr_setting_tbl["laboratory"]["order_by"] = " laboratory_id ASC ";

$arr_setting_tbl["lab_method"] = array();
$arr_setting_tbl["lab_method"]["tbl_name"] = "p_lab_method";
$arr_setting_tbl["lab_method"]["col_id"] = "lab_method_id";
$arr_setting_tbl["lab_method"]["prefix"] = "LM";
$arr_setting_tbl["lab_method"]["id_digit"] = 2;
$arr_setting_tbl["lab_method"]["col_sel_list"] = "lab_method_id as id, lab_method_name as name";
$arr_setting_tbl["lab_method"]["col_sel_detail"] = "lab_method_id as id, lab_method_name as name, lab_method_note as note";
$arr_setting_tbl["lab_method"]["search_by"] = " lab_method_name LIKE 'sTXT' ";
$arr_setting_tbl["lab_method"]["order_by"] = " lab_method_name ASC ";

// for select only ***
$arr_setting_tbl["sale_option"] = array();
$arr_setting_tbl["sale_option"]["tbl_name"] = "sale_option";
$arr_setting_tbl["sale_option"]["col_id"] = "sale_opt_id";
$arr_setting_tbl["sale_option"]["prefix"] = "S";
$arr_setting_tbl["sale_option"]["id_digit"] = 2;
$arr_setting_tbl["sale_option"]["col_sel_list"] = "sale_opt_id as id, sale_opt_name as name ";
$arr_setting_tbl["sale_option"]["col_sel_detail"] = "sale_opt_id as id, sale_opt_name as name ";
$arr_setting_tbl["sale_option"]["search_by"] = " sale_opt_name LIKE 'sTXT' ";
$arr_setting_tbl["sale_option"]["order_by"] = " data_seq ASC ";


$arr_setting_tbl["lab_test"] = array();
$arr_setting_tbl["lab_test"]["tbl_name"] = "p_lab_test";
$arr_setting_tbl["lab_test"]["col_id"] = "lab_id";
$arr_setting_tbl["lab_test"]["col_sel_list"] = "lab_id as id, lab_name as name ";
$arr_setting_tbl["lab_test"]["col_sel_detail"] = "sale_opt_id as id, sale_opt_name as name ";
$arr_setting_tbl["lab_test"]["search_by"] = " sale_opt_name LIKE 'sTXT' ";
$arr_setting_tbl["lab_test"]["order_by"] = " data_seq ASC ";

//*********
?>
