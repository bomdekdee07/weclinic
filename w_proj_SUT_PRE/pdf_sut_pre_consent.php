<?

include_once("../in_auth.php");
include_once("../in_file_prop.php");
include_once("../in_db_conn.php");
include_once("../asset/xlsxwriter/xlsxwriter.class.php"); // include excel class
include_once("../function/in_fn_date.php"); // include date function
include_once("$ROOT_FILE_PATH/function/in_ts_log.php"); // include log file graber


$flag_check = 0;
$uid = isset($_GET["uid"])?urldecode($_GET["uid"]):"";



$query = "SELECT ul.pid, s.collect_date, s.consent, s.age
FROM x_hivtest_self as s , p_project_uid_list as ul
WHERE s.uid=ul.uid AND ul.proj_id='SUT_PRE' AND ul.uid=?";

          $stmt = $mysqli->prepare($query);
          $stmt->bind_param("ss", $month_id, $user_id);

  if ( false===$stmt ) {
      die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }

  if ($stmt->execute()){
    $stmt->bind_result($ttl_full_month_hrs, $emp_id, $emp_name, $emp_position, $month_start, $month_end, $month_ttl_hrs, $month_ttl_leave_hrs, $month_ttl_work_hrs,$month_ttl_ot_hrs, $submit_date, $approve_date, $approve_by, $approve_name,$hr_name,$fn_name, $ts_status, $ts_note, $status_name, $status_color );
  //  $stmt->store_result();

    if ($stmt->fetch()) {

    }
    $month_duration = (new DateTime($month_start))->format('D d M y')." - ".(new DateTime($month_end))->format('D d M y');

  }
  $stmt->close();



if($flag_check == 0){
  header( "location: info/invalid.php?e=e1" );
  exit(0);
}



include_once('../asset/mpdf/vendor/autoload.php');

//$mpdf = new \Mpdf\Mpdf();




/*
if (!defined('_MPDF_PATH'))
define('_MPDF_PATH','../asset/mpdf/src/');
*/



$month_duration = "";
$html = "";
$tbl_general_info = "";


$html_header =
'
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<style>

.weekend_holiday{
  background-color:#FEDEC0;
}
.special_holiday{
  background-color:#E7FFCE;
}

.work_after_hrs{
  background-color:#FFFF99;
}

table{
    border-collapse: collapse;
}

.th, td {
    border: 1px solid black;
    padding-left: 5px;
    text-align: left;
}

.th {
    font-size:10px;
}

tr.noBorder td {
  border: 0;
}


</style>
';



$query = "SELECT tm.ttl_hrs as ttl_full_month_hrs, u.user_id, u.user_fullname, u.user_position, ptm.month_start, ptm.month_end, tm.ttl_hrs, tm.ttl_leave_hrs, tm.ttl_work_hrs,tm.ttl_ot_hrs,
          tm.submit_date, tm.approve_date, tm.approve_by, ap.user_fullname as approve_name,hr.user_fullname as hr_name ,fn.user_fullname as fn_name ,
          tm.ts_status, tm.ts_note , tas.status_name, tas.status_color
          FROM  pv_ts_year_month as ptm , pv_ts_approve_status as tas, pv_user as u,
					pv_ts_month as tm LEFT JOIN pv_user as ap ON (tm.approve_by=ap.user_id)
														LEFT JOIN pv_user as hr ON (tm.hr_check_by=hr.user_id)
														LEFT JOIN pv_user as fn ON (tm.fn_check_by=fn.user_id)
          WHERE tm.month_id=ptm.month_id AND tm.user_id=u.user_id AND tm.month_id=? AND tas.status_id=tm.ts_status AND u.user_id=? ";

          $stmt = $mysqli->prepare($query);
          $stmt->bind_param("ss", $month_id, $user_id);

  if ( false===$stmt ) {
      die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }

  if ($stmt->execute()){
    $stmt->bind_result($ttl_full_month_hrs, $emp_id, $emp_name, $emp_position, $month_start, $month_end, $month_ttl_hrs, $month_ttl_leave_hrs, $month_ttl_work_hrs,$month_ttl_ot_hrs, $submit_date, $approve_date, $approve_by, $approve_name,$hr_name,$fn_name, $ts_status, $ts_note, $status_name, $status_color );
  //  $stmt->store_result();

    if ($stmt->fetch()) {

    }
    $month_duration = (new DateTime($month_start))->format('D d M y')." - ".(new DateTime($month_end))->format('D d M y');

  }
  $stmt->close();

          // leave list detail
          $tbl_leave_summary = "";
          $tbl_leave_summary_list = "";
          $tbl_leave_COMPDAY = "";
					$ttl_leave_compdayold = 0;
          $ttl_leave_compday = 0;
          $ttl_leave_wop = 0;

          $gttl_leave_hrs = 0; // all leave hrs.


          $txt_ref_work_extra_hrs = ""; // remark for work on holiday/after hrs.


          $query = "SELECT  le.leave_type_id, lt.leave_type_name, SUM(leave_hrs) as ttl_leave_hrs,
                    cd.extra_hrs_id,   eh.extra_hrs_date_from, eh.extra_hrs_date_to, eh.extra_hrs_note
                    FROM  pv_ts_leave_type as lt,pv_ts_month as tm, pv_ts_year_month as ptm, pv_ts_year_date as td,
                    pv_ts_leave_emp as le ,
                    pv_ts_leave_emp_date as led
                    LEFT JOIN pv_ts_extra_hrs_compday as cd LEFT JOIN pv_ts_extra_hrs as eh
                                        ON(cd.extra_hrs_id=eh.extra_hrs_id)
                    ON(led.leave_id=cd.leave_id AND led.leave_date_id=cd.date_id )

                    WHERE  tm.month_id=? AND tm.user_id=?
                    AND le.leave_type_id = lt.leave_type_id
                    AND td.date_id = led.leave_date_id AND tm.month_id=ptm.month_id
                    AND le.user_id = tm.user_id AND le.leave_id = led.leave_id AND le.leave_status=4
                    AND (td.date_day >= ptm.month_start AND td.date_day <= ptm.month_end )
                    group BY le.leave_type_id
          ";

            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ss", $month_id, $user_id);

          if ( false===$stmt ) {
          die('prepare() failed: ' . htmlspecialchars($mysqli->error));
          }

          if ($stmt->execute()){
            $stmt->bind_result( $leave_type_id, $leave_type_name, $ttl_leave_hrs, $extra_hrs_id, $extra_hrs_date_from,$extra_hrs_date_to, $extra_hrs_note);
            while ($stmt->fetch()) {
                if($leave_type_id == "COMPDAYOLD"){

                  $tbl_leave_COMPDAY .= '<tr style="background-color:#FCFFE8"><td ><b>'.$leave_type_id.'</b> : '.$leave_type_name.'</td><td width=40px align="center">'.$ttl_leave_hrs.'</td><tr>';
									$ttl_leave_compdayold = $ttl_leave_hrs;

								}
                else if($leave_type_id == "COMPDAY"){
                //  $txt_ref_work_extra_hrs .= "$extra_hrs_id : [".getDBDate($extra_hrs_date_from)." - ".getDBDate($extra_hrs_date_to)."] $extra_hrs_note<br>";
                  $ttl_leave_compday += $ttl_leave_hrs;
              	}
                else if($leave_type_id == "WOP"){
                  $ttl_leave_wop += $ttl_leave_hrs;

                }
                else{
                  $gttl_leave_hrs += $ttl_leave_hrs;
                  $tbl_leave_summary_list .= '<tr><td><b>'.$leave_type_id.'</b> : '.$leave_type_name.'</td><td width=40px align="center">'.$ttl_leave_hrs.'</td><tr>';
                }

            }

            $tbl_leave_summary_list = $tbl_leave_summary_list.$tbl_leave_COMPDAY;
            $gttl_leave_hrs += $ttl_leave_compdayold;

            if($tbl_leave_summary_list != ""){
              $tbl_leave_summary =
              '<table style="border:none; font-size:10px;" width=100%>
                  <tr style="background-color:#EEE;"><td width=60px><h3>General Leave Summary</h3></td><td width=40px align="center"><h3> Total Hrs. </h3></td></tr>
                    '.$tbl_leave_summary_list.'
                  <tr style="background-color:#BFEFFF;"><td><b>TTL General LEAVE :</b> </td><td width=40px align="center"><b>'.$gttl_leave_hrs.'</b></td></tr>

               </table>
                          ';
            }
						else{
							$tbl_leave_summary =
							'<table style="border:none; font-size:10px;">
									<tr style="background-color:#EEE;"><td ><h3>Leave Summary</h3></td><td width=40px align="center"><h3> Total Hrs. </h3></td></tr>


							 </table>
													';
						}

          }


          // time sheet date for this employee
          $i = 0;
          $proj_amt = 0;

          $tbl_proj_summary = "";
          $tbl_proj_summary_list = "";

          $tbl_head = "";
          $tbl_head_proj = "";
          $tbl_list = "";


          $stmt->close();


          // work on holiday summary
          $extra_sum_proj_hrs = array();
          $query = "SELECT SUM(ed.proj_hrs) AS ttl_proj_hrs , ed.proj_id
            FROM pv_ts_extra_hrs_date as ed, pv_project as pj , pv_ts_extra_hrs as e
            WHERE  e.user_id=?  AND e.extra_hrs_id=ed.extra_hrs_id AND e.extra_hrs_status=4 AND e.user_id=ed.user_id AND ed.proj_id=pj.proj_id AND ed.date_id IN
            (SELECT date_id FROM pv_ts_year_month as ym, pv_ts_year_date as yd
              WHERE ym.month_id=? AND  yd.date_day >= ym.month_start AND yd.date_day <= ym.month_end)
            GROUP BY e.user_id, ed.proj_id
            ORDER BY pj.proj_seq_no";

                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param("ss",$user_id, $month_id);

                    if ( false===$stmt ) {
                       die('prepare() failed: ' . htmlspecialchars($mysqli->error));
                     }

                     if ($stmt->execute()){
                       $stmt->bind_result($ttl_proj_hrs, $proj_id);

                       while ($stmt->fetch()) {
                         $extra_sum_proj_hrs[$proj_id] = $ttl_proj_hrs;
                       }//while

                     }

// project list with working proj hrs
  $arr_proj_table = array();


  $query = "SELECT SUM(ptd.proj_hrs) AS act_proj_hrs , ptd.proj_id, pj.proj_name
            FROM pv_ts_month_date as ptd, pv_project as pj ,
            pv_user as u LEFT JOIN pv_ts_month as ptm
                               LEFT JOIN pv_ts_approve_status as ap ON (ptm.ts_status=ap.status_id)
                               ON (u.user_id=ptm.user_id AND ptm.month_id=?)
            WHERE  u.user_id=ptd.user_id AND ptd.proj_id=pj.proj_id AND ptd.date_id IN
            (SELECT date_id FROM pv_ts_year_month as ym, pv_ts_year_date as yd
              WHERE ym.month_id=? AND u.user_id=?
                      AND  yd.date_day >= ym.month_start AND yd.date_day <= ym.month_end)
            GROUP BY u.user_id, ptd.proj_id
            ORDER BY pj.proj_seq_no";

                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param("sss", $month_id, $month_id, $user_id);

            if ( false===$stmt ) {
                die('prepare() failed: ' . htmlspecialchars($mysqli->error));
            }


            if ($stmt->execute()){
                $stmt->bind_result($act_proj_hrs, $proj_id, $proj_name);
                $loe = "";
                $ttl_actual_hrs = 0;
                $ttl_extra_hrs = 0;
                $ttl_proj_hrs = 0;
                while ($stmt->fetch()) {

                  array_push($arr_proj_table,$proj_id);
                  $tbl_head_proj .= '<th>'.$proj_id.' <br><small>'.$proj_name.'</small></th>';


                  $extra_proj_hrs = (isset($extra_sum_proj_hrs[$proj_id])?$extra_sum_proj_hrs[$proj_id]:0 );
                  $ttl_proj_hrs = $act_proj_hrs+$extra_proj_hrs;

                //  $tbl_proj_summary_list .= '<tr><td><b>'.$proj_id.'</b> '.$loe.' : '.$proj_name.'</td><td><input type="text" class="form-control txt_number bg-warning input-sm"  id="ttl_'.$proj_id.'" readonly="readonly"></td><tr>';
                  $tbl_proj_summary_list .= '
                  <tr ><td><b>'.$proj_id.'</b> : '.$proj_name.'</td>
                  <td align="center">'.$act_proj_hrs.'</td>
                  <td align="center">'.$extra_proj_hrs.'</td>
                  <td align="center">'.$ttl_proj_hrs.'</td>
                  <tr>';

                  $ttl_actual_hrs += $act_proj_hrs;
                  $ttl_extra_hrs += $extra_proj_hrs;
                  $proj_amt ++;
                }

              //  $arr_proj_list_sql = substr($arr_proj_list,0, (strlen($arr_proj_list)-1) );

 //$ttl_actual_hrs = $ttl_actual_hrs+$ttl_leave_compdayold; // add 7/6/18

                $tbl_proj_summary =
                '<table style="border:1px solid black; font-size:10px;" width=100%>
                    <tr>
                    <td style="background-color:#EEE"><h3>Project Summary</h3></td>
                    <td style="background-color:#EEE" width=40px align="center"><h4> Act Hrs. </h4></td>
                    <td style="background-color:#EEE" width=40px align="center"><h4> W/H Hrs. </h4></td>
                    <td style="background-color:#EEE" width=40px align="center"><h4> Total Hrs. </h4></td>
                    </tr>
                      '.$tbl_proj_summary_list.'
                  <tr style="background-color:#BFEFFF;">

                  <td><b>TOTAL Hrs:</b> </td>

                  <td width=40px align="center">'.$ttl_actual_hrs.'</td>
                  <td width=40px align="center">'.$ttl_extra_hrs.'</td>
                  <td width=40px align="center">'.($ttl_actual_hrs + $ttl_extra_hrs).'</td>
                  </tr>
                 </table>
                            ';

            // add 4/10/2018  update for emp wherrh
          //  $month_ttl_hrs = $gttl_leave_hrs+$ttl_extra_hrs+$ttl_actual_hrs;

            $month_ttl_hrs = $month_ttl_hrs - $ttl_leave_compday ;


            if(($gttl_leave_hrs+$ttl_extra_hrs+$ttl_actual_hrs) < $month_ttl_hrs){
                $month_ttl_hrs = $gttl_leave_hrs+$ttl_extra_hrs+$ttl_actual_hrs;
            }

            $ttl_charge_hrs  = $ttl_actual_hrs + $ttl_extra_hrs;
            $ttl_charge_hrs2 = $month_ttl_hrs - $gttl_leave_hrs + $ttl_extra_hrs;

            if($ttl_charge_hrs2 > $ttl_charge_hrs ){ // cross check  change the month_ttl_hrs balance when user have incomplete working hours eg. 168 hrs but insert data only 167 hrs
               $month_ttl_hrs = $month_ttl_hrs - ($ttl_charge_hrs2-$ttl_charge_hrs);
            }


            $summary_no = 4; // 1+2-3  next number is 4
            $summary_equation = "1+2-3";

            $tbl_ts_summary =
              '<table class="tbl_border" style="font-size:10px;" width=100%>
                <tr tyle="background-color:#FCFFE8">
                  <td style="background-color:#EEE"><b>1. Full Month Hrs.</b></td>
                  <td style="background-color:#EEE" width=40px align="center"><b>'.$ttl_full_month_hrs.'</b></td>
                </tr>
                <tr>
                  <td >2. Work On Holiday/After Hrs.</td>
                  <td width=40px align="center">'.$ttl_extra_hrs.'</td>
                </tr>
                <tr>
                  <td >3. TTL General Leave Hrs.</td>
                  <td width=40px align="center">'.$gttl_leave_hrs.'</td>
                </tr>
          ';




          if($ttl_leave_compday > 0){ // comp day leave
            $tbl_ts_summary .= '
            <tr style="background-color:#EFBFFF;">
              <td >'.$summary_no.'. Compensation Day Leave</td>
              <td width=40px align="center">'.$ttl_leave_compday.'</td>
            </tr>
            ';
            $summary_equation .= "-$summary_no";
            $summary_no++;
          }

          if($ttl_leave_wop > 0){ //  leave with out pay
            $tbl_ts_summary .= '
            <tr style="background-color:#FFBFBF;">
              <td >'.$summary_no.'. Leave WithOut Pay</td>
              <td width=40px align="center">'.$ttl_leave_wop.'</td>
            </tr>
            ';
            $summary_equation .= "-$summary_no";
            $summary_no++;
          }

          $ttl_charge_hrs2 = $ttl_full_month_hrs + $ttl_extra_hrs - $gttl_leave_hrs - $ttl_leave_compday - $ttl_leave_wop;
          $missing_hrs =  $ttl_charge_hrs2 - $ttl_charge_hrs;

          if($missing_hrs > 0){ // new or resign staff  and  missing input human error
            $tbl_ts_summary .= '
            <tr style="background-color:#FFFFBF;">
              <td >'.$summary_no.'. Missing Hrs.</td>
              <td width=40px align="center">'.$missing_hrs.'</td>
            </tr>
            ';
            $summary_equation .= "-$summary_no";
            $summary_no++;
          }

          $tbl_ts_summary .= '
          <tr style="background-color:#BFEFFF;">
            <td ><b>TTL Charge Hrs.</b> <br>('.$summary_equation.')</td>
            <td width=40px align="center"><b>'.$ttl_charge_hrs.'</b></td>
          </tr>
          </table>
          ';

                $tbl_head =
                '<table width=100%>
                    <thead>
                    <tr style="background-color:#EEE;">
                      <th> DAY </th>
                      <th> DATE </th>
                      <th> NOTE </th>
                      '.$tbl_head_proj.'
                      <th> Total Hrs. </th>
                      </tr>
                    </thead>
                  <tbody>
                            ';


            }


/*
            $timesheet_approval = "
            <table style='border:none; font-size:10px; '>
                <tr class='noBorder'><td align='right'>Status: </td><td width=120px>$status_name</td></tr>
                <tr class='noBorder'><td align='right'>Submit On: </td><td>".getDBDate($submit_date)."</td></tr>
                <tr class='noBorder'><td align='right'>Approved On: </td><td>".getDBDate($approve_date)."</td></tr>
                <tr class='noBorder'><td align='right'>Approved By: </td><td>$approve_name</td></tr>
								<tr class='noBorder' style='background-color:#EEE;'><td align='right'>H/R Checked: </td><td>$hr_name</td></tr>
								<tr class='noBorder' style='background-color:#EEE;'><td align='right'>F/N Checked: </td><td>$fn_name</td></tr>
            </table>
            ";
*/
            $timesheet_approval = "
            <table style='border:none; font-size:10px; '>
                <tr class='noBorder'><td align='right'>Status: </td><td width=120px>$status_name</td></tr>
                <tr class='noBorder'><td align='right'>Approved By: </td><td>$approve_name</td></tr>
                <tr class='noBorder' style='background-color:#EEE;'><td align='right'>H/R Checked: </td><td>$hr_name</td></tr>
                <tr class='noBorder' style='background-color:#EEE;'><td align='right'>F/N Checked: </td><td>$fn_name</td></tr>
            </table>
            ";


  $timesheet_info = "
  <table style='border:none;'>
      <tr class='noBorder'>
      <td >
        <h1>Time Sheet</h1>
        <p>

        Month ID : $month_id  ( $month_duration ) <br>
        Name : <b>$emp_name</b> [$emp_id]<br>
				Position : <b>$emp_position</b>

        </p>
        <br><br>
      </td>
      <td width=30%>
      <div style='float:left;'>
      $timesheet_approval
      </div>

      </td>
  </table>
   ";

/*
  $timesheet_approval = "
  Status : $status_name<br>
  Submitted On : ".getDBDate($submit_date)."<br>
  Approved On  : ".getDBDate($approve_date)."<br>
  Approved By  : $approve_by<br>
  ";
*/


// date proj hrs detail
$month_proj_hrs = array();
$query = "SELECT tmd.date_id, tmd.proj_id, tmd.proj_hrs
          FROM  pv_ts_month_date as tmd, pv_ts_year_date as td , pv_ts_year_month as ptm
          WHERE tmd.user_id = ? AND ptm.month_id = ? AND tmd.date_id = td.date_id
                AND (td.date_day >= ptm.month_start AND td.date_day <= ptm.month_end )
          ORDER BY td.date_id asc";

          $stmt = $mysqli->prepare($query);
          $stmt->bind_param("ss",$user_id, $month_id);

          if ( false===$stmt ) {
             die('prepare() failed: ' . htmlspecialchars($mysqli->error));
           }

           if ($stmt->execute()){
             $stmt->bind_result($date_id, $proj_id, $proj_hrs);
             $cur_date_id = "";
             while ($stmt->fetch()) {
               if($cur_date_id != $date_id){
                 $month_proj_hrs[$date_id] = array();
               }
               $month_proj_hrs[$date_id][$proj_id] =  $proj_hrs;
               $cur_date_id = $date_id;

             }//while

           }




           // work on holiday date proj hrs detail
           $txt_work_extra_hrs_note = "";
           $extra_proj_hrs = array();
           $query = "SELECT ed.extra_hrs_id, ed.date_id, ed.proj_id, ed.proj_hrs, e.extra_hrs_note
                     FROM  pv_ts_extra_hrs as e, pv_ts_extra_hrs_date as ed, pv_ts_year_date as td , pv_ts_year_month as ptm
                     WHERE e.extra_hrs_id=ed.extra_hrs_id AND e.extra_hrs_status=4 AND ed.user_id = ? AND ptm.month_id = ? AND ed.date_id = td.date_id
                           AND (td.date_day >= ptm.month_start AND td.date_day <= ptm.month_end )
                     ORDER BY td.date_id asc";

                     $stmt = $mysqli->prepare($query);
                     $stmt->bind_param("ss",$user_id, $month_id);

                     if ( false===$stmt ) {
                        die('prepare() failed: ' . htmlspecialchars($mysqli->error));
                      }

                      if ($stmt->execute()){
                        $stmt->bind_result($extra_hrs_id, $date_id, $proj_id, $proj_hrs, $extra_hrs_note);
                        $cur_date_id = "";
                        $cur_extra_hrs_id = "";
                        while ($stmt->fetch()) {
                          if($cur_date_id != $date_id){
                            $extra_proj_hrs[$date_id] = array();
                            if($cur_extra_hrs_id != $extra_hrs_id){
                              $txt_work_extra_hrs_note .= "$extra_hrs_id : $extra_hrs_note<br>";
                              $cur_extra_hrs_id = $extra_hrs_id;
                            }

                          }
                          $extra_proj_hrs[$date_id][$proj_id] =  $proj_hrs;
                          $extra_proj_hrs[$date_id]["id"] =  $extra_hrs_id;
                          $cur_date_id = $date_id;

                        }//while

                      }



           // work on holiday reference for compday leave id
           $txt_ref_work_extra_hrs  = "";
           $compday_leave_extra_hrs_ref = array();
           $query = "SELECT distinct ehc.leave_id, eh.extra_hrs_id, eh.extra_hrs_note ,eh.extra_hrs_date_from, eh.extra_hrs_date_to
                    FROM pv_ts_extra_hrs_compday as ehc, pv_ts_extra_hrs as eh
                    WHERE
                    eh.extra_hrs_id = ehc.extra_hrs_id AND
                    ehc.leave_id IN (
                    SELECT  distinct  led.leave_id
                    FROM  pv_ts_year_month as ptm,
                              pv_ts_year_date as td , pv_ts_leave_emp_date as led, pv_ts_leave_emp as le
                    WHERE td.date_id=led.leave_date_id AND led.user_id=?
                               AND led.leave_id=le.leave_id AND le.leave_type_id = 'COMPDAY'
                               AND ptm.month_id=?
                                AND (td.date_day >= ptm.month_start AND td.date_day <= ptm.month_end )
                    )
                    ORDER BY ehc.leave_id, eh.extra_hrs_id
                    ";

                     $stmt = $mysqli->prepare($query);
                     $stmt->bind_param("ss",$user_id, $month_id);

                     if ( false===$stmt ) {
                        die('prepare() failed: ' . htmlspecialchars($mysqli->error));
                      }

                      if ($stmt->execute()){
                        $stmt->bind_result($leave_id, $extra_hrs_id, $extra_hrs_note, $extra_hrs_date_from, $extra_hrs_date_to);
                        $cur_extra_hrs_id = "";
                        while ($stmt->fetch()) {
                          if(isset($compday_leave_extra_hrs_ref[$leave_id])){
                            $compday_leave_extra_hrs_ref[$leave_id] .=  "$extra_hrs_id,";
                          }
                          else{
                            $compday_leave_extra_hrs_ref[$leave_id] = "$extra_hrs_id,";
                          }


                          if($cur_extra_hrs_id != $extra_hrs_id){
                            $txt_ref_work_extra_hrs .= "$extra_hrs_id : [".getDBDate($extra_hrs_date_from)." - ".getDBDate($extra_hrs_date_to)."] $extra_hrs_note<br>";
                            $cur_extra_hrs_id = $extra_hrs_id;
                          }

                        }//while

}




// leave Hrs. in timesheet month
$leave_proj_hrs = array();
$query = "SELECT le.leave_id, led.leave_date_id, led.leave_hrs , le.leave_type_id, lt.leave_type_name
FROM pv_user as u , pv_ts_leave_emp as le, pv_ts_leave_emp_date as led, pv_ts_leave_type as lt
WHERE u.user_id=? AND u.user_id=le.user_id AND le.leave_id=led.leave_id
AND lt.leave_type_id = le.leave_type_id AND le.leave_status=4
AND led.leave_date_id IN
(
SELECT led.leave_date_id
FROM pv_ts_year_month as tm, pv_ts_year_date as td, pv_user as u ,
pv_ts_leave_emp as le, pv_ts_leave_emp_date as led
WHERE u.user_id=? AND u.user_id=le.user_id AND le.leave_id=led.leave_id
AND td.date_id = led.leave_date_id  AND le.leave_status=4
AND (td.date_day >= tm.month_start AND td.date_day <= tm.month_end ) AND tm.month_id=?
)
ORDER BY le.leave_id ";

          $stmt = $mysqli->prepare($query);
          $stmt->bind_param("sss",$user_id,$user_id, $month_id);

          if ( false===$stmt ) {
             die('prepare() failed: ' . htmlspecialchars($mysqli->error));
           }

           if ($stmt->execute()){
             $stmt->bind_result($leave_id, $date_id, $leave_hrs, $leave_type_id, $leave_type_name );
             while ($stmt->fetch()) {
               if(!isset($leave_proj_hrs[$date_id])){
                 $leave_proj_hrs[$date_id] = array();
                 $leave_proj_hrs[$date_id]["info"] = "<i class='fa fa-clipboard fa-lg'></i> $leave_id : $leave_type_name [$leave_hrs hrs.]";
                 $leave_proj_hrs[$date_id]["ttl_hrs"] = $leave_hrs;
               }
               else{
                 $leave_proj_hrs[$date_id]["info"] .= "<br><i class='fa fa-clipboard fa-lg'></i> $leave_id : $leave_type_name [$leave_hrs hrs.]";
                 $leave_proj_hrs[$date_id]["ttl_hrs"] += $leave_hrs;
               }

               if($leave_type_id == "COMPDAY"){
                 $extra_hrs_ref_id = substr($compday_leave_extra_hrs_ref[$leave_id], 0,strlen($compday_leave_extra_hrs_ref[$leave_id])-1 );
                 $leave_proj_hrs[$date_id]["info"] .= "<span style='color:red;'> * Work On Holiday/After Hrs. REF No. $extra_hrs_ref_id </span>";
              //   $extra_hrs_compday_id .= "'$extra_hrs_id',";
               }

             }//while

}

// whole date in month

/*
$query = "SELECT td.date_id, td.date_day, td.date_name, td.is_holiday, td.date_remark,
             led.leave_id,  led.leave_hrs , le.leave_type_id, lt.leave_type_name
FROM  pv_ts_year_month as ptm,
          pv_ts_year_date as td  LEFT JOIN pv_ts_leave_emp_date as led
          LEFT JOIN pv_ts_leave_emp as le LEFT JOIN pv_ts_leave_type as lt ON(le.leave_type_id=lt.leave_type_id)
          ON (led.leave_id=le.leave_id AND le.leave_status=4)
          ON (td.date_id = led.leave_date_id AND led.user_id = ?)
WHERE ptm.month_id=?
            AND (td.date_day >= ptm.month_start AND td.date_day <= ptm.month_end )
ORDER BY td.date_day, led.leave_id asc";

          $stmt = $mysqli->prepare($query);
          $stmt->bind_param("ss",$user_id, $month_id);
*/
/*
// query_add to seperate between old system (compday can be charged) and new system (compday can not be charged)
$query_add = " AND le.leave_type_id <> 'COMPDAYOLD' "; // new system
if($month_id == "May-18" || $month_id == "Jun-18" || $month_id == "Jul-18") $query_add = ""; // old system
*/
$query_add = "";
$extra_hrs_compday_id = ""; // collect extra hours id for REF NO Summary at bottom timesheet
$leave_id_compday = "";
/*
$query = "SELECT  distinct td.date_id, td.date_day, td.date_name, td.is_holiday, td.date_remark,
             led.leave_id,  led.leave_hrs , le.leave_type_id, lt.leave_type_name,  cd.extra_hrs_id
FROM  pv_ts_year_month as ptm,
          pv_ts_year_date as td
          LEFT JOIN pv_ts_leave_emp_date as led
                      LEFT JOIN pv_ts_extra_hrs_compday as cd ON (led.leave_id=cd.leave_id AND led.leave_date_id=cd.date_id)
                      LEFT JOIN pv_ts_leave_emp as le
                                         LEFT JOIN pv_ts_leave_type as lt ON(le.leave_type_id=lt.leave_type_id)
                      ON (led.leave_id=le.leave_id AND le.leave_status=4 $query_add)

          ON (
             td.date_id = led.leave_date_id AND led.user_id = ? AND led.leave_id IN
        				(
        				SELECT l.leave_id
        				FROM pv_ts_leave_emp as l, pv_ts_leave_emp_date as ld, pv_ts_year_date as d  , pv_ts_year_month as tm
        				WHERE l.leave_status=4 AND l.user_id=? AND l.leave_id=ld.leave_id
        				AND ld.leave_date_id = d.date_id
        				AND (d.date_day >= tm.month_start AND d.date_day <= tm.month_end )
        				AND tm.month_id=?
        				)
          )
          LEFT JOIN pv_ts_overtime_month_date_info as otmd ON (otmd.date_id=td.date_id AND otmd.user_id=?)
WHERE ptm.month_id=?
            AND (td.date_day >= ptm.month_start AND td.date_day <= ptm.month_end )
ORDER BY td.date_day, led.leave_id asc";
*/





$query = "SELECT td.date_id, td.date_day, td.date_name, td.is_holiday, td.date_remark
FROM pv_ts_year_month as ptm, pv_ts_year_date as td
WHERE ptm.month_id=? AND (td.date_day >= ptm.month_start AND td.date_day <= ptm.month_end )
ORDER BY td.date_day";

          $stmt = $mysqli->prepare($query);
          //$stmt->bind_param("sssss",$user_id, $user_id, $month_id, $user_id, $month_id);
          $stmt->bind_param("s",$month_id);
          if ( false===$stmt ) {
             die('prepare() failed: ' . htmlspecialchars($mysqli->error));
           }

           if ($stmt->execute()){
          //   $stmt->bind_result($date_id, $date_day, $date_name, $is_holiday, $date_remark, $leave_id, $leave_hrs , $leave_type_id, $leave_type_name, $extra_hrs_id );
             $stmt->bind_result($date_id, $date_day, $date_name, $is_holiday, $date_remark);
             $cur_date_id = "";
             $read_only = ""; // input lock for holiday
             $bg_ttl = ""; // bg of date total hrs
             $bg_data_class = ""; // some is readonly ("") / some is data row (bg-primary text-light)
             $ttl_date_hrs = 0;  // total hrs in each day (row)
             $date_proj_hrs = "date_proj_hrs"; // class that input can be editable and validate total to 8 hrs/day

             $proj_amt = count($arr_proj_table);
             $proj_hrs_input = "";

             while ($stmt->fetch()) {

                 $leave_date_hrs = 0;
                 $ttl_date_hrs = 0;

                 $bg_class = "";
                 $read_only = "";
                 //$read_only = ' readonly="readonly" ';
                 $bg_data_class = "bg-primary text-light";
                 $date_proj_hrs = "date_proj_hrs";

                 if($date_remark != ""){
                   $date_remark = "<small><b>*</b> $date_remark</small><br>";
                 }



                 if($is_holiday == "1"){
                   if($date_name == "Sat" || $date_name == "Sun"){
                     $bg_class = "weekend_holiday";

                   }
                   else{
                     $bg_class = "special_holiday";
                   }
                   $read_only = ' readonly="readonly" ';
                   $bg_data_class = "";
                   $date_proj_hrs = "";
                   $leave_date_hrs = 0;
                 }

                // leave data
                 $leave_txt = "";


                 if(isset($leave_proj_hrs[$date_id])){
                   $read_only = ' readonly="readonly" ';
                   $bg_data_class = "";
                   $date_proj_hrs = "";
                   $leave_txt = "<small>".$leave_proj_hrs[$date_id]["info"]."</small>";
                   $leave_hrs = $leave_proj_hrs[$date_id]["ttl_hrs"];
                   $ttl_date_hrs += $leave_hrs;

                   $read_only = ' readonly="readonly" ';
                   $bg_data_class = "";
                   $date_proj_hrs = "";

                   $leave_date_hrs = $leave_hrs;
                   if($leave_hrs == 4){
                     $read_only = '';
                     $date_proj_hrs = "date_proj_hrs";
                   }
                 }

  /*
              //   if($leave_id != NULL){
                 if($leave_type_id != NULL){
                   $read_only = ' readonly="readonly" ';
                   $bg_data_class = "";
                   $date_proj_hrs = "";
                   $leave_txt = "<small> ".$leave_id." : ".$leave_type_name." <b>[ ".$leave_hrs. " Hrs ]</b></small>";

                   if($leave_type_id != "COMPDAY" && $leave_type_id != "WOP"){
                   //if($leave_type_id != "WOP"){
                     $ttl_date_hrs += $leave_hrs;
                     $leave_date_hrs = 8;

                     if($leave_hrs == 4 ){
                       $read_only = '';
                       $leave_date_hrs = 4;
                       $date_proj_hrs = "date_proj_hrs";
                     }
                   }
                   else{
                     if($leave_type_id == "COMPDAY"){
                       //substr($extra_hrs_compday_id,0, strlen($extra_hrs_compday_id)-1);
                       $extra_hrs_ref_id = substr($compday_leave_extra_hrs_ref[$leave_id], 0,strlen($compday_leave_extra_hrs_ref[$leave_id])-1 );
                       $leave_txt .= "<small><span style='color:red;'> * Work On Holiday/After Hrs. REF No. $extra_hrs_ref_id </span></small>";
                    //   $extra_hrs_compday_id .= "'$extra_hrs_id',";
                     }

                   }

                 }// leave id == null
*/

                 $proj_hrs_input = "";
                 $row_after_hrs = ""; // row appear on work after hours
                 $flag_work_after_hrs = 0;

                 if(isset($month_proj_hrs[$date_id])){ // there is proj_hrs data in date_id
                   for($i=0; $i<$proj_amt; $i++){ // put the working project hrs
                       $proj_id = $arr_proj_table[$i];
                       if(isset($month_proj_hrs[$date_id][$proj_id])){ // exist working proj hrs
                         $proj_hrs = $month_proj_hrs[$date_id][$proj_id];
                         $proj_hrs_input .="<td width=50px align='center'>$proj_hrs</td>";
                         $ttl_date_hrs += $proj_hrs;
                       }
                       else{ // No working proj hrs in this proj_id
                         $proj_hrs_input .= ' <td width=50px align="center"></td>'; //0
                       }
                    }//for

                    // check for work after hrs
                    if(isset($extra_proj_hrs[$date_id])){ // there is extra_hrs data in date_id
                         $flag_work_after_hrs = 1;
                    }// end check for work after hrs
                 }
                else{// No working proj hrs in this date_id

                     if($is_holiday == "0"){ // normal day
                        for($i=0; $i<$proj_amt; $i++){ // put the none working project
                            $proj_hrs_input .= ' <td width=50px align="center"></td>'; //0
                        }//for

                        // check for work after hrs
                        if(isset($extra_proj_hrs[$date_id])){ // there is extra_hrs data in date_id
                             $flag_work_after_hrs = 1;
                        }// end check for work after hrs
                     }
                     else{// holiday
                       // check for work on holiday
                       if(isset($extra_proj_hrs[$date_id])){ // there is proj_hrs data in date_id
                         $leave_txt = '<small>'.$extra_proj_hrs[$date_id]["id"].' : ทำงานวันหยุด / Work On Holiday </small>';

                         for($i=0; $i<$proj_amt; $i++){ // put the working project hrs
                             $proj_id = $arr_proj_table[$i];
                             if(isset($extra_proj_hrs[$date_id][$proj_id])){ // exist working proj hrs
                                 $proj_hrs = $extra_proj_hrs[$date_id][$proj_id];
                                 $proj_hrs_input .="<td width=50px align='center'>$proj_hrs</td>";
                                 $ttl_date_hrs += $proj_hrs;
                             }
                             else{ // No working proj hrs in this proj_id
                               $proj_hrs_input .= ' <td width=50px align="center"></td>'; //0
                             }
                          }//for

                     }
                     else{ // normal holiday and special holiday or normal day but no input proj hrs
                       for($i=0; $i<$proj_amt; $i++){ // put the none working project
                            $proj_hrs_input .= ' <td width=50px align="center"></td>'; //0
                        }//for
                     }


                   }// end holiday
                } //end No working proj hrs in this date_id


                // check for work after hrs  detail
                if($flag_work_after_hrs == 1){
                  $ttl_date_after_hrs = 0;
                  $after_hrs_txt = $extra_proj_hrs[$date_id]["id"].' : ทำงานนอกเวลา / Work After Hours ';
                  $after_hrs_input= "";
                  for($i=0; $i<$proj_amt; $i++){ // put the working project hrs
                      $proj_id = $arr_proj_table[$i];
                      if(isset($extra_proj_hrs[$date_id][$proj_id])){ // exist working proj hrs
                          $proj_hrs = $extra_proj_hrs[$date_id][$proj_id];
                          $after_hrs_input .="<td width=50px align='center'>$proj_hrs</td>";
                          $ttl_date_after_hrs += $proj_hrs;
                      }
                      else{ // No working proj hrs in this proj_id
                        $after_hrs_input .= ' <td width=50px align="center"></td>'; //0
                      }
                   }//for

                   $row_after_hrs .='<tr class="row_detail work_after_hrs">';
                   $row_after_hrs .=' <td width=50px>'.$date_name. '</td>';
                   $row_after_hrs .=' <td width=70px>'.(new DateTime($date_day))->format('d M y').'</td>';
                   $row_after_hrs .=' <td>'.$date_remark.'<small>'.$after_hrs_txt.'</small> </td>';

                   $row_after_hrs .= $after_hrs_input;

                   $row_after_hrs .=' <td width=50px align="center">'.$ttl_date_after_hrs.'</td>';
                   $row_after_hrs .='</tr>';
                } // end check for work after hrs  detail


                  $tbl_list .='<tr class="row_detail '.$bg_class.'" id="row_'.$date_id.'" data-date_id="'.$date_id.'" data-leave_hrs="'.$leave_date_hrs.'" >';
                  $tbl_list .=' <td width=50px>'.$date_name. '</td>';
                  $tbl_list .=' <td width=70px>'.(new DateTime($date_day))->format('d M y').'</td>';
                  $tbl_list .=' <td>'.$date_remark.$leave_txt.' <span id="info_'.$date_id.'" class="text-light"></span></td>';

                   $tbl_list .= $proj_hrs_input;

                   $bg_ttl = "";


                   $tbl_list .=" <td width=50px align='center'><b>$ttl_date_hrs</b></td>";
                   $tbl_list .='</tr>';
                   $tbl_list .= $row_after_hrs;

             }//while

             $tbl_list .="</tbody>";
             $tbl_list .="</table>";
           }


$tbl_list =  $tbl_head . $tbl_list;

            // $tbl_list =  $tbl_head . $tbl_list2;

if($ts_note !=""){
  $ts_note =  str_replace("\n","<br>",$ts_note);
  $ts_note = "<tr style='background-color:#FCFFE8'><td colspan=3 ><p>Note:<br> $ts_note </p></td></tr>";
}


if($txt_work_extra_hrs_note !=""){
  $txt_work_extra_hrs_note = "<tr ><td style='padding: 5px 15px 5px;' colspan=3><b>Work on holiday / after hrs. note :</b><br><small>".$txt_work_extra_hrs_note."</small></td></tr>";
}




if($txt_ref_work_extra_hrs != "")
$txt_ref_work_extra_hrs = "<tr><td colspan=3 style='color:red;padding: 5px 15px 5px;'><small><b>Compensation Day Leave Reference (Work on holiday / after hrs.) :</b><br>".$txt_ref_work_extra_hrs."</small></td></tr>";


$tbl_general_info ="
 <table width=100% >
     <tr class='noBorder'><td colspan=2 style='padding-left:0px;'><img src='../img/comp_logo.jpg' width=200px><br><br><br></td><td align='right'><small>Published On: ".(new DateTime())->format('d M y [H:i]')."</small></td></tr>
     <tr><td colspan=3>$timesheet_info</td></tr>

     <tr><td valign='top'>$tbl_ts_summary</td><td valign='top' style='padding-left:0px;'>$tbl_proj_summary</td><td valign='top'>$tbl_leave_summary</td></tr>
     $ts_note
     $txt_work_extra_hrs_note
     <tr><td colspan=3>$tbl_list</td></tr>
     $txt_ref_work_extra_hrs

 </table>
";

$html .=$html_header.$tbl_general_info;

//echo $html;


$mpdf = new \Mpdf\Mpdf([
	'default_font_size' => 9,
	'default_font' => 'Garuda'
]);

   $mpdf->SetTitle("TimeSheet-".$month_id." (".$user_id.")");
   $mpdf->WriteHTML($html);



   // Output a PDF file directly to the browser
   //$mpdf->Output();

	 $mpdf->Output('Timesheet_'.$month_id.'_'.$user_id.'.pdf', 'I');


?>
