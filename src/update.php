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

$sendmail = false;
$dbconnect = true;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $inputvalid == true ) {
    foreach($_POST as $key => $value)
        $post[$key] = test_input($value);

    // database

    $dbloaded = false;
    require 'Scripts/php/db-connect.php';

    if($dbconnect == true && $dbloaded == true){

        $date = date("d.m.Y h:i:sa");

        $sql = "UPDATE Abholung SET date='$date', date1='{$post["date1"]}', date1von='{$post["date1von"]}', date1bis='{$post["date1bis"]}', date2='{$post["date2"]}', date2von='{$post["date2von"]}', date2bis='{$post["date2bis"]}' WHERE hash = '{$post["hash"]}'";

        if ($conn->query($sql) === TRUE) {

            //echo "New Abholung record created successfully<br>";
            //include('danke.html');
            include('danke.html');
            echo '<p>';
            echo '<a href= http://localhost:8080/pfandhelfer/MME/src/datepicker.php?'.$post["hash"];
            echo '>Termin anzeigen</a></p>';

        } else {

            //echo "Error: " . $sql . "<br>" . $conn->error;
            include('tbv.html');
            //header('Location: tbv.html');
            echo '<p>';
            echo '<a href= http://localhost:8080/pfandhelfer/MME/src/datepicker.php?'.$post["hash"];
            echo '>Termin anzeigen</a></p>';

        }
        if ($conn->connect_error) {

                die("Connection failed: " . $conn->connect_error);

        }

    }

}

?>