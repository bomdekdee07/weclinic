
<?

if (session_status() == PHP_SESSION_NONE) {
    include_once("../in_auth.php");
}

echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
?>

<script>

</script> 
<? include_once("../in_savedata.php"); ?>
<? include_once("../inc_foot_include.php"); ?>
<? include_once("../function_js/js_fn_validate.php"); ?>
