<?php
require 'Scripts/php/db-connect.php';
?>
<!DOCTYPE html>
<html>

    <head>

        <!-- Meta-Informationen -->
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
        <meta name="name" content="Pfandhelfer.de">
        <meta name="description" content="Pfandhelfer-Spende deinen Pfand! Plattform zum gemeinützigen Spenden von Pfandflaschen an soziale Einrichtungen in deiner Umgebung.">
        <meta name="author" content="Florian Kruellke">
        <meta charset="utf-8">


        <!-- Icons
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">  -->
        <link rel="shortcut icon" href="apple-touch-icon.png" />
        <link rel="icon" type="image/x-icon" href="apple-touch-icon.png">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <title>Pfandhelfer - Vereinbare einen Termin!</title>

        <!-- CSS-Styles -->
        <link rel="stylesheet" href="CSS/bootstrap.min.css"/>
        <link rel="stylesheet" href="CSS/style.css" />
        <link href="data:text/css;charset=utf-8," data-href="../dist/css/bootstrap-theme.min.css" rel="stylesheet" id="bs-theme-stylesheet">
        <link rel="stylesheet" href="CSS/leaflet.css" />

        <!-- Scripts -->
        <script type="text/javascript" src="Scripts/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="Scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="Scripts/leaflet.js"></script>
        <!--
        <script type="text/javascript" src="Scripts/main.js"></script>
        -->

    </head>

<body>

<div class="container jumbotron">

<?php

$inputvalid = false;
require 'Scripts/php/input-valid.php'; // after success $inputvalid = true

$hash = $_SERVER['REQUEST_URI'];
$from = intval( strpos($hash, "?") );
$from++;
$hash = substr($hash, $from, strlen($hash));
$hash = test_input( $hash );

if($dbloaded == true){

    $sql = "SELECT email, pfandart, pfandanzahl, date FROM Pfandspende WHERE hash = '$hash'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        echo '<div class="container">
            <div class="col-sm-12">';

        if($firstRow = $result->fetch_assoc())

            echo '
             <p class="well">Die Pfandspende vom '.$firstRow["date"].' von '.$firstRow["email"].':</p>
                <table class="well table table-striped">
                  <thead>
                  <tr>
                    <th>Pfandart</th>
                    <th>Pfandanzahl</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>'.$firstRow["pfandart"].'</td>
                    <td>'.$firstRow["pfandanzahl"].'</td>
                  </tr>';

        // output data of each row
        while($row = $result->fetch_assoc()) {

                echo '
                  <tr>
                    <td>'.$row["pfandart"].'</td>
                    <td>'.$row["pfandanzahl"].'</td>
                  </tr>';
        }
            echo '
                </tbody>
              </table>
            </div>';

    } else {

        echo "0 results";

    }


    $sql = "SELECT date, date1, date1von, date1bis, date2, date2von, date2bis, info FROM Abholung WHERE hash = '$hash'";
    $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            echo '<div class="container">
                <div class="col-sm-12">';
            if($row = $result->fetch_assoc()){
                echo '<p>Ihre vereinbarten Termine:</p>';
                echo "<p class='well'>Datum 1: ".$row["date1"]." - von ".$row["date1von"]." bis ".$row["date1bis"]." Uhr.";
                if(trim($row["date2"]) !== ""){
                echo "<br>Datum 2: ".$row["date2"]." - von ".$row["date2von"]." bis ".$row["date2bis"]." Uhr.</p>";
                }
                echo "<br><p>Oder wählen sie neue Termine aus:</p>";
                echo '<form class="form-horizontal" action="update.php" method="POST" onsubmit="';
                echo "javascript:window.alert('Ihre Anfrage wird bearbeitet.')";
                echo '">';
            }
        } else {

            echo '
                <p><small>Ist das deine Pfandspende?<br><br>
                Ja? Super! Wann sollen wir deine Spende abholen?</small></p>
                <form class="form-horizontal" action="abholung.php" method="POST" onsubmit="';
            echo "javascript:window.alert('Ihre Anfrage wird bearbeitet.')";
            echo '">';


        }
        echo '

            <div class="form-group col-sm-12">

            <div class="col-sm-2">
            <label>Datum 1</label>
            <input name="date1" class="form-control" type="date" required></><input class="form-control col-sm-3" type="text" name="date1von" placeholder="von" required></><input name="date1bis" class="form-control col-sm-3" type="text" placeholder="bis" required></>
            </div>

            <div class="col-sm-2">
            <label>Datum 2*</label>
            <input name="date2" class="form-control" type="date"></><input class="form-control col-sm-3" type="text" name="date2von" placeholder="von"></><input name="date2bis" class="form-control col-sm-3"type="text" placeholder="bis"></>
            </div>


            </div>

            <div class="form-group col-sm-5">

            <div class="col-sm-12">
            <label><small>w&aumlhle ein Datum und eine Zeitspanne mit den von ihnen favorisierten Uhrzeiten</small>

            <small>(mit * markierte Felder sind optional)</small>
            </label>
            </div>

            <div class="col-sm-4">
            <button class="btn btn-danger" type="submit">Absenden</button>
            </div>

            </div>';

} else {

    echo "NO Database REACHABLE - Please try again later";
}



?>


<?php

    echo '
        <div class="col-sm-2" style="display: none;">
        <input name="hash" class="form-control" type="text" value="'.$hash.'"></>';

?>

</div>

</form>

</div>

</div>

</div>

</body>

</html>