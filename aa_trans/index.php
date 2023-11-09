<script>
var flag_timer=0;
// Set timeout variables.

var timoutWarning = 1800000; // Display warning in 30 Mins.
var timoutNow = 1800000; // Warning has been shown, give the user 30 minute to interact


/*
var timoutWarning = 10000; // Display warning in 30 Mins.
var timoutNow = 5000; // Warning has been shown, give the user 1 minute to interact
*/

//var timoutWarning = 840000; // Display warning in 14 Mins.
//var timoutNow = 60000; // Warning has been shown, give the user 1 minute to interact
var logoutUrl = 'logout.php'; // URL to logout page.

var warningTimer;
var timeoutTimer;


// Start warning timer.
function StartWarningTimer() {
    warningTimer = setTimeout("IdleWarning()", timoutWarning);
    flag_timer = 1;
}

// Reset timers.
function ResetTimeOutTimer() {
    //alert("reset timer");
    clearTimeout(timeoutTimer);
    clearTimeout(warningTimer);
    StartWarningTimer();
  //  $("#timeout").dialog('close');
}

// Show idle timeout warning dialog.
function IdleWarning() {
    clearTimeout(warningTimer);
    timeoutTimer = setTimeout("IdleTimeout()", timoutNow);
    myModalDlgLogin("te", "Time Expired");

    // Add code in the #timeout element to call ResetTimeOutTimer() if
    // the "Stay Logged In" button is clicked
}

// Logout the user.
function IdleTimeout() {
//  alert("itle timeout");
    window.location = "logout.php";
}


function extendSession(){
  //alert("extendSession "+flag_timer);
  if(flag_timer == 1){
    ResetTimeOutTimer();
  }
}
//StartWarningTimer();
</script>


<?
//include_once("/w_user/index.php");
//session_start();

include_once("./in_auth.php");

$s_group = isset($_SESSION["s_group"])?$_SESSION["s_group"]:"x";
//echo "group : $s_group";
if($s_group == "2"){ // hospital (sameday)
  include_once("w_hos/index.php");
}
else{// cbo
  $s_section_id = isset($_SESSION["section_id"])?$_SESSION["section_id"]:"";
//  echo "section : $s_section_id";
  if($s_section_id == "TS"){ // ihri technical support
    include_once("w_hos/index.php"); // sdhos center
  }
  else if($s_section_id == "SGM"){ // ihri surveygizmo check
    include_once("w_ext_surveygizmo/index.php"); // surveygizmo check
  }
  else if($s_section_id == "CVD"){ // covid19
    include_once("w_proj_covid19/index.php"); // covid19 check
  }
  else if($s_section_id == "LAB"){ // ihri lab
    include_once("lab/index.php"); // lab
  }


  else{
    include_once("w_user/index.php");
  }




}


/*
if($s_group == "0") include_once("w_user/index.php");
else if ($s_group == "99") include_once("w_admin/index.php");
*/


?>
