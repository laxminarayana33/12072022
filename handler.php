<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
 * example to see how to use XOAUTH2.
 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
 */
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

if($_POST)
{
//check if its an ajax request, exit if not
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	
    //exit script outputting json data
    $output = json_encode(
    array(
        'type'=>'error', 
        'text' => 'Request must come from Ajax'
    ));
    
    die($output);
} 

//check $_POST vars are set, exit if any missing
// if( !isset($_POST["userEmail"]) || !isset($_POST["userSubject"]) || !isset($_POST["userMessage"]))
// {
//     $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
//     die($output);
// }

//Sanitize input data using PHP filter_var().
// $user_Name        = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
// $user_Email      = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
// $org_names       = filter_var($_POST["userName"], FILTER_SANITIZE_EMAIL);
// $user_Email      = $_POST["email"];
// $org_names       = $_POST["userName"];
// $org_names  = explode(",",$org_names);
$user_Message    = 'Name: '.filter_var($_POST["fname"], FILTER_SANITIZE_STRING).'  Email: '.filter_var($_POST["email"], FILTER_SANITIZE_STRING).' Phone:'.filter_var($_POST["phone"], FILTER_SANITIZE_STRING).' Message: '.filter_var($_POST["message"], FILTER_SANITIZE_STRING);
// $user_Message    = 'Hello';
$user_Subject    = 'ARF from Website ';
//Create a new PHPMailer instance
$mail = new PHPMailer(true);
//Tell PHPMailer to use SMTP
try{

    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;
    //Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "siddetulasi2796@gmail.com";
    //Password to use for SMTP authentication
    $mail->Password = "siddetulasi2796";
    //Set who the message is to be sent from
    $mail->setFrom('siddetulasi2796@gmail.com', 'Fairlife Form from Website');
    $mail->addAddress('siddetulasi2796@gmail.com', 'Fairlife Form from Website');
    //Set an alternative reply-to address
    // $mail->addReplyTo($user_Email, ""  );
    // $mail->addReplyTo('naveenboda1993@gmail.com', "sadasdd"  );
    //Set who the message is to be sent to
    //  $mail->addAddress(filter_var($_POST["email"], FILTER_SANITIZE_STRING), filter_var($_POST["fname"], FILTER_SANITIZE_STRING).filter_var($_POST["lname"], FILTER_SANITIZE_STRING));
    // $index=0;
    // foreach (explode(",",$user_Email) as $value) {
    //     $mail->addAddress($value, $org_names[$index]);
    //     $index=$index+1;
    // }
    //  $mail->addAddress($user_Email, 'John Doe');
    //Set the subject line
    $mail->Subject = $user_Subject ;
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
    //Replace the plain text body with one created manually
    $mail->Body = $user_Message;
    //Attach an image file
    // $mail->addAttachment('images/phpmailer_mini.png');
    //send the message, check for errors
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration. ' . $mail->ErrorInfo));
        die($output);
    } else {
        echo "Message sent!";
        $output = json_encode(array('type'=>'message', 'text' => 'Message sent! ' . $mail->ErrorInfo));
        die($output);
        //Section 2: IMAP
        //Uncomment these to save your message in the 'Sent Mail' folder.
        #if (save_mail($mail)) {
        #    echo "Message saved!";
        #}
    }
}catch(Exception $e){
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    echo $e;
}
//Section 2: IMAP
//IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
//Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
//You can use imap_getmailboxes($imapStream, '/imap/ssl') to get a list of available folders or labels, this can
//be useful if you are trying to get this working on a non-Gmail IMAP server.
// function save_mail($mail)
// {
//     //You can change 'Sent Mail' to any other folder or tag
//     $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";
//     //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
//     $imapStream = imap_open($path, $mail->Username, $mail->Password);
//     $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
//     imap_close($imapStream);
//     return $result;
// }

}