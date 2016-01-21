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

$sendmail = false;
$dbconnect = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach($_POST as $key => $value)
        $post[$key] = test_input($value);

    $post["hausnummer"] = intval( $post["hausnummer"] );
    $post["plz"] = intval( $post["plz"] );
/*

    //vorher deklarieren

    $vorname = $email = $nachname = $telefon = $strasse  = $nummerzusatz = "";
    $hausnummer = $plz = 0;

    // ins if
    $vorname = test_input($_POST["vorname"]);
    $nachname = test_input($_POST["nachname"]);
    $email = test_input($_POST["email"]);
    $telefon = test_input($_POST["telefon"]);
    $strasse = test_input($_POST["strasse"]);
    $hausnummer = intval ( test_input($_POST["hausnummer"]) );
    $nummerzusatz = test_input($_POST["nummerzusatz"]);
    $plz = intval ( test_input($_POST["plz"]) );
    for($x = 8; $x < count($_POST); $x++) {
        $num = $x-8;
        $num = "nummer".$num;
        $spende[$num] = test_input($_POST[$x]);
    }
*/
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if(trim($post["email"]) != false){
    //$post = array( "email"=>$email, "vorname"=>$vorname, "nachname"=>$nachname, "telefon"=>$telefon, "strasse"=>$strasse, "hausnummer"=>$hausnummer, "nummerzusatz"=>$nummerzusatz, "plz"=>$plz);

    foreach ($post as $key => $value)
    print("<tr><td bgcolor=\"#bbbbbb\"> <strong>$key</strong></td><td>$value</td></tr>");


    /*
    foreach ($_POST as $key => $value)
    print("<tr><td bgcolor=\"#bbbbbb\"> <strong>$key</strong></td><td>htmlspecialchars($value)</td></tr>");


    </table>
    <br>
    <h1>PHP ECHO OF GET REQUEST:</h1>
    <br>
    <table border="1" cellpadding="2" cellspacing="0" width="100%"> <?php
    foreach ($_GET as $key => $value)
    print("<tr><td bgcolor=\"#bbbbbb\"> <strong>$key</strong></td>
        <td>htmlspecialchars($value)</td></tr>");
    //header( "Location: text.html" );

    */

    // mail / database
    if($sendmail == true){
        require 'Scripts/phpmailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = "pfandhelfer@gmail.com"; // GMAIL username
        $mail->Password = "pfandhelfer22"; // GMAIL password                          // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

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
        $mail->Body    = '<div style="margin: 0 auto;padding-top: 1rem; line-height: 27px; text-align: center; font-family: Verdana;">Danke, dass sie Pfandhelfer unterst&uuml;tzen. <b>Jede Spende ist uns wichtig!</b><br><a href="http://localhost:8080/pfandhelfer/MME/src/">Machen sie einen Termin oder eine von ihnen favorisierte Zeitspanne aus.</a></div>';
        $mail->AltBody = 'Danke, dass du Pfandhelfer unterst&uuml;tzt. Jede Spende is uns wichtig! Unter folgendem Link kannst du einen Termin oder eine Zeitspanne vereinbaren: http://localhost:8080/pfandhelfer/MME/src/';

        if(!$mail->send()) {
            echo 'Message could not be sent.<br>';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent<br>';
        }
    }

    if($dbconnect == true){

        $servername = "localhost:3306/";
        $username = "root";
        $password = "";
        $dbname = "pfandDB";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO User (email, vorname, nachname, telefon, strasse, hausnummer, nummerzusatz, plz)
        //VALUES ('{$_POST["email"]}', '{$_POST["vorname"]}', '{$_POST["nachname"]}', '{$_POST["telefon"]}', '{$_POST["strasse"]}', {$_POST["hausnummer"]}, ' ', {$_POST["plz"]})";
        VALUES ('{$post["email"]}', '{$post["vorname"]}', '{$post["nachname"]}', '{$post["telefon"]}', '{$post["strasse"]}', {$post["hausnummer"]}, ' ', {$post["plz"]})";

        if ($conn->query($sql) === TRUE) {

            echo "New User record created successfully<br>";

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO Pfandspende (id, date, email, pfandart, pfandanzahl)
        //VALUES ('{$_POST["email"]}', '{$_POST["vorname"]}', '{$_POST["nachname"]}', '{$_POST["telefon"]}', '{$_POST["strasse"]}', {$_POST["hausnummer"]}, ' ', {$_POST["plz"]})";
        VALUES ('{$post["email"]}', '{$post["vorname"]}', '{$post["nachname"]}', '{$post["telefon"]}', '{$post["strasse"]}', {$post["hausnummer"]}, ' ', {$post["plz"]})";

        if ($conn->query($sql) === TRUE) {

            echo "New Pfandspende record created successfully<br>";

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }


   }
?>

</table>
</output>
</body>
</html>