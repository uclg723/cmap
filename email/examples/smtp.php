<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PHPMailer - SMTP test</title>
</head>
<body>
<?php

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require '../PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer();
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
$mail->SMTPSecure = "tls";
//Set the hostname of the mail server
$mail->Host = "smtp.gmail.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "hkcmap@gmail.com";
//Password to use for SMTP authentication
$mail->Password = "chinamax";
//Set who the message is to be sent from
$mail->setFrom('wsfreelanzer@gmail.com', 'Soundhar Raj');
//Set an alternative reply-to address
$mail->addReplyTo('wsfreelanzer@gmail.com', 'Soundhar Raj');
//Set who the message is to be sent to
$mail->addAddress('webscarlets@gmail.com', 'Soundhar Raj');
$mail->addAddress('hkcmap@gmail.com', 'China Max');
//Set the subject line
$mail->Subject = 'Testing Mail for China Max';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML('Read an HTML message body from an external file, convert referenced images to embedded, <br> convert HTML into a basic plain-text alternative body');
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

//send the message, check for errors
if($mail->send()){
	echo "Mail Sent";
}
?>
</body>
</html>
