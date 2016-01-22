<?php

include('index.html');
echo '
    <script>
       window.location += "#pfandspende";
       waitForDB();
    </script>';

// define variables and set to empty values

$inputvalid = false;
require 'Scripts/php/input-valid.php'; // after success $inputvalid = true

$sendmail = true;
$dbconnect = true;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $inputvalid == true ) {
    foreach($_POST as $key => $value)
        $post[$key] = test_input($value);

    $post["hausnummer"] = intval( $post["hausnummer"] );
    $post["plz"] = intval( $post["plz"] );

}

if(trim($post["email"]) != false){

    // declare variables

    $date = date("d.m.Y h:i:sa");
    $hash = md5(rand()."-".$post["email"]."-".$date);

    // database

    $dbloaded = false;
    require 'Scripts/php/db-connect.php';

    if($dbconnect == true && $dbloaded == true){

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO User (hash, email, vorname, nachname, telefon, strasse, hausnummer, nummerzusatz, plz)
            VALUES ('{$hash}', '{$post["email"]}', '{$post["vorname"]}', '{$post["nachname"]}', '{$post["telefon"]}', '{$post["strasse"]}', {$post["hausnummer"]}, ' ', {$post["plz"]})";

        if ($conn->query($sql) === TRUE) {
            $success["db-user"] = "new user record created successfully";
            //echo "New User record created successfully<br>";

        } else {
            $error["db-user"] = "Error: " . $sql . "<br>" . $conn->error;
            //echo "Error: " . $sql . "<br>" . $conn->error;

        }
        if ($conn->connect_error) {

                die("Connection failed: " . $conn->connect_error);

        }

        $size = count($post) - (count($post) - 8)/2;

        for($x = 8; $x < $size; $x++){
            $y = $x - 8;
            $s1 = "pfandart".$y;
            $s2 = "pfandanzahl".$y;
            $sql = "INSERT INTO Pfandspende (hash, date, email, pfandart, pfandanzahl)
                VALUES ('{$hash}', '{$date}', '{$post["email"]}', '{$post["{$s1}"]}', '{$post["{$s2}"]}')";

            if ($conn->query($sql) === TRUE) {
                $success["db-spende"] = "new pfandspende record created successfully";
                //echo "New Pfandspende record created successfully<br>";

            } else {
                $error["db-user"] = "Error: " . $sql . "<br>" . $conn->error;
                //echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        $conn->close();

    }

    // mail
    if($sendmail == true){

        require 'Scripts/phpmailer/PHPMailerAutoload.php';
        $mailer = false;
        require 'Scripts/php/pfand-mail.php';

        if($mailer == true){

            $mail->set("Content-type", "text/html");
            $mail->set("charset", "utf-8");

            $mail->addAddress("{$post["email"]}", "info@pfandhelfer");
            $mail->setFrom("{$post["email"]}", "info@pfandhelfer");

            $mail->addReplyTo('pfandhelfer@gmail.com', 'Information #'.$hash);
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');

            $mail->isHTML(true);
            $mail->Subject = 'Vielen Dank, '.$post["vorname"].'!';
            $mail->Body    = '<div style="margin: 0 auto;padding-top: 1rem; line-height: 27px; text-align: center; font-family: Verdana;">Danke, dass sie Pfandhelfer unterst&uuml;tzen. <b>Jede Spende ist uns wichtig!</b><br><a href="http://localhost:8080/pfandhelfer/MME/src/datepicker.php?'.$hash.'">Machen sie einen Termin oder eine von ihnen favorisierte Zeitspanne aus.</a></div>';
            $mail->AltBody = 'Danke, dass du Pfandhelfer unterst&uuml;tzt. Jede Spende is uns wichtig! Unter folgendem Link kannst du einen Termin oder eine Zeitspanne vereinbaren: http://localhost:8080/pfandhelfer/MME/src/datepicker.php?'.$hash;

            if(!$mail->send()) {
                $error["db-spendemail"] = 'Mailer Error: ' . $mail->ErrorInfo;
                //echo 'Message could not be sent.<br>';
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
                $text = $mail->ErrorInfo;
                echo '
                    <script>

                        errorDB('.$text.');

                    </script>';
            } else {
                $success["db-spendemail"] = "message has been sent";
                //echo 'Message has been sent<br>';
                //include('index.html');
                echo '
                <script>

                    window.setTimeout(function(){

                        successDB();

                    }, 3000);
                    window.clearTimeouts();

                </script>';
            }
        }
    }
}
?>