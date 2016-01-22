<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>HTML5 FORM RESPONSE</title> </head>
<body>
<output>
<h1>PHP ECHO OF POST REQUEST:</h1>
<br>
<table border="1" cellpadding="2" cellspacing="0" width="100%">

<?php

// define variables and set to empty values

$inputvalid = false;
require 'Scripts/php/input-valid.php'; // after success $inputvalid = true

$sendmail = false;
$dbconnect = true;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $inputvalid == true ) {
    foreach($_POST as $key => $value)
        $post[$key] = test_input($value);

    // declare variables

    foreach ($post as $key => $value)
        print("<tr><td bgcolor=\"#bbbbbb\"> <strong>$key</strong></td><td>$value</td></tr>");

    // database

    $dbloaded = false;
    require 'Scripts/php/db-connect.php';

    if($dbconnect == true && $dbloaded == true){

        $date = date("d.m.Y h:i:sa");

        $sql = "INSERT INTO Abholung (hash, date, date1, date1von, date1bis, date2, date2von, date2bis, info)
            VALUES ('{$post["hash"]}', '$date', '{$post["date1"]}', '{$post["date1von"]}','{$post["date1bis"]}','{$post["date2"]}', '{$post["date2von"]}', {$post["date2bis"]}, 'leer')";

        if ($conn->query($sql) === TRUE) {

            echo "New Abholung record created successfully<br>";

        } else {

            echo "Error: " . $sql . "<br>" . $conn->error;

        }
        if ($conn->connect_error) {

                die("Connection failed: " . $conn->connect_error);

        }

    }

    // mail
    if($sendmail == true){

        require 'Scripts/phpmailer/PHPMailerAutoload.php';
        $mailer = false;
        require 'Scripts/php/pfand-mail.php';

        if(§mailer == true){

            $mail->set("Content-type", "text/html");
            $mail->set("charset", "utf-8");

            $mail->addAddress("{$post["email"]}", "info@pfandhelfer");
            $mail->setFrom("{$post["email"]}", "info@pfandhelfer");

            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');

            $mail->isHTML(true);
            $mail->Subject = 'Vielen Dank, '.$post["vorname"].'!';
            $mail->Body    = '<div style="margin: 0 auto;padding-top: 1rem; line-height: 27px; text-align: center; font-family: Verdana;">Danke, dass sie Pfandhelfer unterst&uuml;tzen. <b>Jede Spende ist uns wichtig!</b><br><a href="http://localhost:8080/pfandhelfer/MME/src/dates.php?'.$post["hash"].'">Machen sie einen Termin oder eine von ihnen favorisierte Zeitspanne aus.</a></div>';
            $mail->AltBody = 'Danke, dass du Pfandhelfer unterst&uuml;tzt. Jede Spende is uns wichtig! Unter folgendem Link kannst du einen Termin oder eine Zeitspanne vereinbaren: http://localhost:8080/pfandhelfer/MME/src/dates.php?'.$post["hash"];

            if(!$mail->send()) {
                $error["db-spendemail"] = 'Mailer Error: ' . $mail->ErrorInfo;
                //echo 'Message could not be sent.<br>';
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                $success["db-spendemail"] = "message has been sent";
                //echo 'Message has been sent<br>';
                include('index.html');
                echo '
                <script>
                    $(document).ready(function(){

                        $("#aniButton").click();

                    });
                </script>';
            }
        }
    }

}

?>

</table>
</output>
</body>
</html>