
    <!-- CSS-Styles -->
    <link rel="stylesheet" href="CSS/bootstrap.min.css"/>
    <link rel="stylesheet" href="CSS/style.css" />
    <link href="data:text/css;charset=utf-8," data-href="../dist/css/bootstrap-theme.min.css" rel="stylesheet" id="bs-theme-stylesheet">
    <link rel="stylesheet" href="CSS/leaflet.css" />

    <!-- Scripts -->
    <script type="text/javascript" src="Scripts/jquery-1.11.3.min.js"></script>

    <script>
        $(document).ready(function(){
            $(".pfandspende").on("click", function(event){
                parent.addressFromClick(event.currentTarget);
            });
        });
    </script>

    <style>
        .pfandspende{
            cursor: pointer;
       }
    </style>

<?php

$dbloaded = false;
require 'Scripts/php/db-connect.php';

if($dbloaded == true){

    $sql = "SELECT email, strasse, hausnummer, nummerzusatz, plz FROM User";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        $num = 1;
        echo '<table class="table table-condensed table-hover">
                                            <thead>
                                            <tr>
                                              <th>Nr.</th>
                                              <th>Email</th>
                                              <th>Strasse</th>
                                              <th>Haus</th>
                                              <th>PLZ</th>
                                            </tr>
                                          </thead>
                                          <tbody>';
        while($row = $result->fetch_assoc()) {
            echo "<tr class='pfandspende'>
            <td>".$num++. ".</td>
            <td name='email'>" . $row["email"]."</td>
            <td name='strasse'>" . $row["strasse"]."</td>
            <td name='hausnummer'>" . $row["hausnummer"].$row["nummerzusatz"]."</td>
            <td name='plz'>" . $row["plz"]."</td>
            </tr>";
        }
        echo '</table>';

    } else {

    echo '<table class="table table-condensed table-hover">
                                                <thead>
                                                <tr>
                                                  <th>Nr.</th>
                                                  <th>Email</th>
                                                  <th>Strasse</th>
                                                  <th>Haus</th>
                                                  <th>PLZ</th>
                                                </tr>
                                              </thead>
                                              <tbody>';
    echo "<tr class='pfandspende'>
                <td>1.</td>
                <td name='email'>info@de</td>
                <td name='strasse'>Späthstrasse</td>
                <td name='hausnummer'>31</td>
                <td name='plz'>12359</td>
                </tr></table>";


/*
        echo '<div class="jumbotron">
                Keine Daten in der Datenbank! Kontaktieren sie den Pfandhelfer IT - Support<br>
                <a href="mailto:pfandhelfer@gmail.com?Subject=IT-DB-Problem" target="_top"><span class="glyphicon glyphicon-send"></span> Jetzt schreiben!</a>
                </div>';
*/
    }

    $conn->close();

} else {

          echo '<div class="jumbotron">
                  Keine Datenbankverbindung möglich! Kontaktieren sie den Pfandhelfer Support<br>
                  <a href="mailto:pfandhelfer@gmail.com?Subject=Pfandhelfer Support" target="_top"><span class="glyphicon glyphicon-send"></span> Jetzt schreiben!</a>
                  </div>';

}

?>