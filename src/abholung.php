<!DOCTYPE html>
<html>

    <head>

        <!-- Meta-Informationen -->
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
        <meta name="name" content="Pfandhelfer.de">
        <meta name="description" content="Pfandhelfer-Spende deinen Pfand! Plattform zum gemeinützigen Spenden von Pfandflaschen an soziale Einrichtungen in deiner Umgebung.">
        <meta name="author" content="Florian Kruellke">
        <meta charset="utf-8">

        <title>Pfandhelfer - Abholtermin</title>

        <!-- Icons
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">  -->
        <link rel="shortcut icon" href="apple-touch-icon.png" />
        <link rel="icon" type="image/x-icon" href="apple-touch-icon.png">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
    </head>
<?php

// define variables and set to empty values

$inputvalid = false;
require 'Scripts/php/input-valid.php'; // after success $inputvalid = true

$hash = "";
$sendmail = true;
$dbconnect = true;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $inputvalid == true ) {
    foreach($_POST as $key => $value)
        $post[$key] = test_input($value);

    $hash = $post["hash"];
    $mailTo = false;

    // database

    $dbloaded = false;
    require 'Scripts/php/db-connect.php';

    if($dbconnect == true && $dbloaded == true){

        $date = date("d.m.Y h:i:sa");

        $sql = "INSERT INTO Abholung (hash, date, date1, date1von, date1bis, date2, date2von, date2bis, info)
            VALUES ('{$post["hash"]}', '$date', '{$post["date1"]}', '{$post["date1von"]}','{$post["date1bis"]}','{$post["date2"]}', '{$post["date2von"]}', '{$post["date2bis"]}', 'leer')";

        if ($conn->query($sql) === TRUE) {

            //echo "New Abholung record created successfully<br>";
            include('danke.html');
            //header('Location: danke.html');

        } else {

            //echo "Error: " . $sql . "<br>" . $conn->error;
            include('tbv.html');
            //header('Location: tbv.html');

        }
        if ($conn->connect_error) {

                die("Connection failed: " . $conn->connect_error);

        }

        $sql = "SELECT email FROM User WHERE hash = '".$post["hash"]."'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            if($row = $result->fetch_assoc()) {
                $mailTo = $row["email"];
            }
        }

        $conn->close();

    }

    // mail
    if($sendmail == true && $mailTo !== false){

        require 'Scripts/phpmailer/PHPMailerAutoload.php';
        $mailer = false;
        require 'Scripts/php/pfand-mail.php';

        if($mailer == true){

            $mail->set("Content-type", "text/html");
            $mail->set("charset", "utf-8");

            $mail->addAddress($mailTo, "info@pfandhelfer");
            $mail->setFrom($mailTo, "info@pfandhelfer");

            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');

            $mail->isHTML(true);
            $mail->Subject = 'Dein Termin wurde eingetragen!';
            $mail->Body    = '<div style="margin: 0 auto;padding-top: 1rem; line-height: 27px; text-align: center; font-family: Verdana;">Danke, dass sie Pfandhelfer unterst&uuml;tzen. <b>Jede Spende ist uns wichtig!</b><br><a href="http://localhost:8080/pfandhelfer/MME/src/datepicker.php?'.$post["hash"].'">Machen sie einen Termin oder eine von ihnen favorisierte Zeitspanne aus.</a></div>';
            $mail->AltBody = 'Danke, dass du Pfandhelfer unterst&uuml;tzt. Jede Spende is uns wichtig! Unter folgendem Link kannst du einen Termin oder eine Zeitspanne vereinbaren: http://localhost:8080/pfandhelfer/MME/src/datepicker.php?'.$post["hash"];

            if(!$mail->send()) {
                $error["db-spendemail"] = 'Mailer Error: ' . $mail->ErrorInfo;
                //echo 'Message could not be sent.<br>';
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                $success["db-spendemail"] = "message has been sent";
                //echo 'Message has been sent<br>';

                echo '<p>';
                echo '<a href= http://localhost:8080/pfandhelfer/MME/src/datepicker.php?'.$hash;
                echo '>Termin anzeigen</a></p>';
            }
        }
    }

} else {

    include("tbv.html");
}

?>