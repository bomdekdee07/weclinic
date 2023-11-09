<?

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once("in_ts_log.php"); // include log file graber
//echo "path ".$_SERVER['DOCUMENT_ROOT'];
//echo "web path ".$WEB_PATH;

require_once("../asset/phpmailer/src/Exception.php") ;
require_once("../asset/phpmailer/src/PHPMailer.php") ;
require_once("../asset/phpmailer/src/SMTP.php") ;
//include_once('in_ts_get_error_mail.php') ; // get error email sending



function sendEmail($email_subject, $email_message,
                   $emailListTO,
                   $emailListCC,
                   $emailListBCC
                  ){
//  echo "sendEmail : ".$email_subject;
  $msgInfo = ""; //return sendmail result to log
  $mail = new PHPMailer(true);
  try {
      //Server settings
      $mail->SMTPDebug = 0; // no debug
    //$mail->SMTPDebug = 2;                                // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'info.prevention.trcarc@gmail.com';    // SMTP username
      $mail->Password = 'infov12345';                          // SMTP password
      $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 465 ;                                    // TCP port to connect to

      $mail->oauthUserEmail = "info.prevention.trcarc@gmail.com";
      $mail->oauthClientId = "165656939221-em5arfu36q7gm3vk92n5mi4b437foi06.apps.googleusercontent.com";
      $mail->oauthClientSecret = "ybDst4eJt0HGTcetUA3QgOP5";
      $mail->oauthRefreshToken = "1/zK4nloAs7wV4TGsNz1PSZ4wJNiM_wR_xteW3fkbNqX0";

      //Recipients
      $mail->setFrom('info.prevention.trcarc@gmail.com', 'weClinic PREVENTION Thai Red Cross AIDS Research Center');
      //$emailListTO = array("phanu@trcarc.org"=>"Phanu Srivachiraroj", "pop_v99@hotmail.com"=>"Phanu 2 S.");

      // Add a recipient
      //echo "amt of mailTO : ".sizeof($emailListTO);

      foreach($emailListTO as $person_email => $person_name){
        // echo "add mail to : ".$person_email." : ".$person_name;

         $mail->addAddress($person_email, $person_name);
      }

      foreach($emailListCC as $person_email => $person_name){
         $mail->addCC($person_email, $person_name);
      }

      foreach($emailListCC as $person_email => $person_name){
         $mail->addBCC($person_email, $person_name);
      }

      //$mail->addAddress('phanu@trcarc.org', 'Phanu S.');     // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

      //Content
      $mail->isHTML(true); // Set email format to HTML
      $mail->CharSet = 'UTF-8';
      $mail->Subject = $email_subject;
      $mail->Body    = $email_message;
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
    //  echo 'Message has been sent';
      $msgInfo = "Send Mail : ".$email_subject; //return sendmail result to log


  } catch (Exception $e) {
      //echo 'Message could not be sent.';
      //echo 'Mailer Error: ' . $mail->ErrorInfo;

      $msgInfo = "Send Mail Error : ".$mail->ErrorInfo; //return sendmail result to log
      $msgInfo .= "<br>The email will be resent later."; //return sendmail result to log
/*
      foreach($emailListTO as $person_email => $person_name){
         getErrorMail($_SESSION["user_id"],
                               $email_subject, // subject
                               $person_email, // receiver
                               $person_name, //receiver_name
                               $email_message
                             );

      }

*/

  }
  return $msgInfo;

}

// send email for multiple time (in for loop)
function sendEmailLoop($email_subject, $email_message,
                       $emailListTO,
                       $emailListCC,
                       $emailListBCC
                  ){


  $msgInfo = ""; //return sendmail result to log
  $mail = new PHPMailer(true);
  try {
      //Server settings
      $mail->SMTPDebug = 0; // no debug                    // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'info.prevention.trcarc@gmail.com';    // SMTP username
      $mail->Password = 'infov12345';                          // SMTP password
      $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 465 ;                                    // TCP port to connect to

      $mail->oauthUserEmail = "info.prevention.trcarc@gmail.com";
      $mail->oauthClientId = "165656939221-em5arfu36q7gm3vk92n5mi4b437foi06.apps.googleusercontent.com";
      $mail->oauthClientSecret = "ybDst4eJt0HGTcetUA3QgOP5";
      $mail->oauthRefreshToken = "1/zK4nloAs7wV4TGsNz1PSZ4wJNiM_wR_xteW3fkbNqX0";

      //Recipients
      $mail->setFrom('info.prevention.trcarc@gmail.com', 'PREVENTION Timesheet System');

      foreach($emailListTO as $person_email => $person_name){
         $mail->addAddress($person_email, $person_name);
      }

      foreach($emailListCC as $person_email => $person_name){
         $mail->addCC($person_email, $person_name);
      }

      foreach($emailListCC as $person_email => $person_name){
         $mail->addBCC($person_email, $person_name);
      }

      //Content
      $mail->isHTML(true); // Set email format to HTML
      $mail->CharSet = 'UTF-8';
      $mail->Subject = $email_subject;
      $mail->Body    = $email_message;
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
    //  echo 'Message has been sent';
      $msgInfo = "Send Mail : ".$email_subject; //return sendmail result to log


  } catch (Exception $e) {
      $msgInfo = "Send Mail Error : ".$mail->ErrorInfo; //return sendmail result to log
      $msgInfo .= "<br>The email will be resent later."; //return sendmail result to log

      foreach($emailListTO as $person_email => $person_name){
         getErrorMail("TS",
                               $email_subject, // subject
                               $person_email, // receiver
                               $person_name, //receiver_name
                               $email_message
                             );

      }
  }
  finally {
    sleep(3); // wait 3 second to send other mail
  }

  return $msgInfo;

}


?>
