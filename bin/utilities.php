<?php
require_once('initialize.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../vendor/autoload.php';


require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//namespace dailyboost;

/**
 * Description of utilities
 *
 * @author mgris
 */
class utilities {
 
public function generate_string($input, $strength = 16) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 //echo $random_string;
    return $random_string;
}

/* Returns:
 * 0 if generic error
 * 1 if mail sent correctly
 * 2  if error while send an e-mail
 */
public function sendmail($recipients, $subject, $body)
{
    $ret = 0;
    $mail = new PHPMailer(true);

// Settings
$mail->IsSMTP();
$mail->Mailer = "smtp";
$mail->CharSet = 'UTF-8';

$mail->Host       = AppConfig::$MAIL_HOST;
$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
$mail->SMTPAuth   = TRUE;                  // enable SMTP authentication
$mail->SMTPSecure = "tls";
$mail->Port       = AppConfig::$MAIL_PORT;                    // set the SMTP port for the GMAIL server
$mail->Username   = AppConfig::$MAIL_USER; // SMTP account username example
$mail->Password   = AppConfig::$MAIL_PASSWORD;        // SMTP account password example


$mail->SetFrom(AppConfig::$MAIL_USER, "Matteo from DailyBoost");
$mail->addAddress($recipients);
$mail->addCC("mgrisoster@gmail.com");
// Content
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = $subject;
$mail->Body    = $body;
//$mail->AltBody = $body;

//$mail->MsgHTML($body); 
if(!$mail->Send()) {
    $ret =2;
} else {
  $ret =1;
}

return $ret;
}
 
public function getGoogleClient()
{
    $client = new Google_Client();
    $client->setClientId(AppConfig::$GOOGLE_CLIENT_ID);
    $client->setClientSecret(AppConfig::$GOOGLE_CLIENT_SECRET);
    //$client->setRedirectUri("https://www.virtualchief.net");
    $client->setRedirectUri("http://localhost:88");
    $client->setAccessType("offline");
    return $client;
}


}

class log
{    
    public function write($accountid, $userid, $username, $ip, $description, $page)
    {        
        $ret = "false";
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
 
        // Attempt insert query execution
        $sql = "INSERT INTO log (timestamp, accountid, userid, username, ip, description, page) "
                . "VALUES ('" . gmdate("Y-m-d\TH:i:s") ."',"
                .   $accountid . ", "
                .  $userid . ", "
                . "'" .$username . "', "
                . "'" .$ip . "', "
                . "'" .$description . "', "
                . "'" .$page ."'"
                . ")";
        if(mysqli_query($link, $sql)){
            $ret = "Records inserted successfully.";
        } else{
            $ret = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
            //$ret = false;
        }
 
        // Close connection
        mysqli_close($link);
        return $ret;

    }
}

