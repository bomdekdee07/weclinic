<?
// update uid to each uic
//include_once("../in_auth.php");
include_once("../in_db_conn.php");
include_once("../function/in_fn_date.php"); // date function
include_once("../function/in_fn_number.php"); // number function


$query = "SELECT *
FROM prep_follow
WHERE a2_remark IN ('0','1') ORDER BY visit_date
";
//echo $query;
$txt_row = "";
$run_no = 1;
$stmt = $mysqli->prepare($query);
if ($stmt->execute()){

    $result = $stmt->get_result();
    if($result->num_rows > 0) {
       while($row = $result->fetch_assoc()) {
         $txt_row .= "<br>UPDATE prep_follow SET ";
         $txt_row .= " prep_method='".$row['a2_remark']."' , ";
         $txt_row .= " a2_21_1='".$row['prep_method']."' , ";
         $txt_row .= " a2_21_2='".$row['a2_21_1']."' , ";
         $txt_row .= " a2_21_3='".$row['a2_21_2']."' , ";
         $txt_row .= " a2_21_4='".$row['a2_21_3']."' , ";
         $txt_row .= " a2_21_5='".$row['a2_21_4']."' , ";
         $txt_row .= " a2_21_6='".$row['a2_21_5']."' , ";
         $txt_row .= " a2_21_7='".$row['a2_21_6']."' , ";
         $txt_row .= " a2_21_8='".$row['a2_21_7']."' , ";
         $txt_row .= " a2_21_9='".$row['a2_21_8']."' , ";
         $txt_row .= " a2_21_10='".$row['a2_21_9']."' , ";
         $txt_row .= " a2_21_11='".$row['a2_21_10']."' , ";
         $txt_row .= " a2_21_12='".$row['a2_21_11']."' , ";

         $txt_row .= " a2_22='".$row['a2_21_12']."' , ";
         $txt_row .= " a2_23='".$row['a2_22']."' , ";
         $txt_row .= " a2_24='".$row['a2_23']."' , ";
         $txt_row .= " a2_25='".$row['a2_24']."' , ";
         $txt_row .= " a2_26='".$row['a2_25']."' , ";
         $txt_row .= " a2_26_detail='".$row['a2_26']."' , ";
         $txt_row .= " a2_27='".$row['a2_26_detail']."' , ";
         $txt_row .= " a2_28='".$row['a2_27']."' , ";
         $txt_row .= " a2_29='".$row['a2_28']."' , ";
         $txt_row .= " a2_30='".$row['a2_29']."' , ";
         $txt_row .= " a2_31='".$row['a2_30']."' , ";
         $txt_row .= " a2_31_detail='".$row['a2_31']."' , ";
         $txt_row .= " a2_32='".$row['a2_31_detail']."' , ";
         $txt_row .= " a2_33='".$row['a2_32']."' , ";
         $txt_row .= " a2_34='".$row['a2_33']."' , ";
         $txt_row .= " a2_remark='".$row['a2_34']."'  ";
         $txt_row .= " WHERE case_no='".$row['case_no']."' ;";


      }//while
    }
    $stmt->close();

}//if
else{
  $msg_error .= $stmt->error;
}


$mysqli->close();


echo $txt_row;
