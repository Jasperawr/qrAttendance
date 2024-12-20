<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Logo\Logo;

require 'vendor/autoload.php';
require 'configmailer.php';

function sendMail($email, $hashedid, $name)
{

    $data = "attendance|" . $hashedid;
    $qr_code = QrCode::create($data)
        ->setSize(600)
        ->setMargin(40);
    $logo = Logo::create("assets/img/bsulogotranparent.png");
    $writer = new PngWriter;
    $result = $writer->write($qr_code, logo: $logo);

    $qrCodeString = $result->getString();

    $mail = new PHPMailer(true);

    try {

        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
        $mail->isSMTP();
        $mail->Host       = MAILHOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = USERNAME;
        $mail->Password   = PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom(SEND_FROM, SEND_FROM_NAME);
        $mail->addAddress($email, $name);
        $mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        // $mail->addAttachment('/var/tmp/file.tar.gz');        
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    

        $mail->isHTML(true);
        $mail->Subject = 'QR For Your Attendance';
        $mail->Body    = '
                          <p style="text-align:center; font-size:20px; font-weight:bold;">BulSU Attendance Management System</p>
                          <p style="text-align:center;">This is the <strong>QR Code</strong> for your attendance. Please Save the QR Code</b></p>
                          <p style="text-align:center;"><img style="text-align:center;" src="cid:qrcode" width="150"></p>
                          <p style="text-align:center;"><strong >Download it please.</strong></p>
                         ';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->addStringEmbeddedImage($qrCodeString, 'qrcode', 'qrcode.png', 'base64', 'image/png');

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

//Function to send creadentials of faculty
function sendCredentials($email, $password, $name)
{

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings

        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
        $mail->isSMTP();
        $mail->Host       = MAILHOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = USERNAME;
        $mail->Password   = PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom(SEND_FROM, SEND_FROM_NAME);
        $mail->addAddress($email, $name);
        $mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'QR For Your Attendance';
        $mail->Body    = '
                          <p style="text-align:center; font-size:20px; font-weight:bold;">BulSU Attendance Management System</p>
                          <p style="text-align:center;">This is your <strong>Credentials</strong> to login to the attendance management system.<br>Please update your credentials. </b></p>
                          <p style="text-align:center; ">
                            Email: <span style="font-weight:bold;">' . $email . '</span><br>
                            Password: <span style="font-weight:bold;">' . $password . '</span>
                          </p>
                          <p style="text-align:center;"><strong >Thank you!</strong></p>
                         ';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
