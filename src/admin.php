<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">

    <!-- Icons
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">  -->
    <link rel="shortcut icon" href="apple-touch-icon.png" />
    <link rel="icon" type="image/x-icon" href="apple-touch-icon.png">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    <title>Admin - Pfandhelfer</title>

    <!-- CSS-Styles -->
    <link rel="stylesheet" href="CSS/bootstrap.min.css"/>
    <link rel="stylesheet" href="CSS/style.css" />
    <link href="data:text/css;charset=utf-8," data-href="../dist/css/bootstrap-theme.min.css" rel="stylesheet" id="bs-theme-stylesheet">
    <link rel="stylesheet" href="CSS/leaflet.css" />
    <link rel="stylesheet" href="CSS/Control.OSMGeocoder.css" />

    <!-- Scripts -->
    <script type="text/javascript" src="Scripts/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="Scripts/bootstrap.min.js"></script>
    <script type="text/javascript" src="Scripts/leaflet.js"></script>
    <script type="text/javascript" src="Scripts/Control.OSMGeocoder.js"></script>


    <script type="text/javascript" src="Scripts/main.js"></script>
</head>
<style>
    iframe{
        //border: 1px solid black;
        width: 100%;
        height: 450px;;
        z-index: 100;
    }
</style>
<body>
<div class="navbar-wrapper">

            <div class="container">

                <div class="navbar navbar-default navbar-static-top" role="navigation">

                    <div class="container">

                        <div class="navbar-header">

                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>

                            <a class="navbar-brand" href="index.html">Pfandhelfer.de</a>

                        </div> <!-- End of Navbar-Header -->

                        <div class="navbar-collapse collapse">

                            <div id="logout" class="navbar-nav right-inner-addon pull-right logout" style="display: none; z-index: 1000">

                                <a class="btn btn-default" href="admin.php">Logout</a>

                            </div>

                            <div class="right-inner-addon pull-right" style="display: none;">

                                    <button name="home" type="submit" class="btn btn-default login" data-toggle="modal" data-target="#myModal"/>Anmelden</button>

                            </div> <!-- End of Logout -->

                            <ul class="nav navbar-nav logout" style="display: none;">
                                                            <li><a href="#about">Wie funktioniert's?</a></li>
                                                            <li><a href="#contact">Kontakt</a></li>
                            </ul>

                        </div> <!-- End of Navbar-Collapse -->

                    </div> <!-- End of Container -->

                </div> <!-- End of Basic Navbar -->

            </div> <!-- End of Container -->

        </div> <!-- End of Navbar-Wrapper -->


<div class="container col-sm-12">
<?php
$inputvalid = false;
require 'Scripts/php/input-valid.php'; // after success $inputvalid = true

$sendmail = true;
$dbconnect = true;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $inputvalid == true ) {
    foreach($_POST as $key => $value)
        $post[$key] = test_input($value);

    if(trim($post["email"]) != false){

        // declare variables

        $date = date("d.m.Y h:i:sa");
        $hash = md5(rand()."-".$post["email"]."-".$date);

        // database

        $recordCreated = true;
        $dbloaded = false;
        require 'Scripts/php/db-connect.php';
        $login = false;

        if($dbconnect == true && $dbloaded == true){
            $sql = "SELECT email, pwd, name FROM UserRegistry WHERE email = '{$post['email']}'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    $row = $result->fetch_assoc();
                    if($post["pwd"] === test_input($row["pwd"])) $login = true;
                }

                $conn->close();

                if($login === true){

                    echo '<h3>Pfandspender</h3>
                    <div class="" style="background-color: #efefef;border-radius: 4px;padding: 10px;"><div>Hello '.$row["name"].'!</div></div>

                                                                                                                                                                                             <p></p>

                                                                                                                                                                                           </div>';
                    echo '
                                        <div class="container col-sm-12">
                                            <div class="container col-sm-5"">
                                                <div class=""><iframe id="data-db" src="db-test.php" frameborder="0"></iframe></div>

                                            </div>
                                            <div class="col-sm-7">
                                                <div id="map" style="width: 100%; height: 450px;" class="col-sm-7"></div>
                                            </div>
                                        </div>
                                        </body>
                                      <script>';
                    echo "/*
                                      var map = new L.Map('map');
                                        var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                                        osmAttrib = 'Map data &copy; 2011 OpenStreetMap contributors',
                                        osm = new L.TileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib});

                                        map.setView(new L.LatLng(52.55050, 13.35900), 16).addLayer(osm);

var cloudmadeAttribution = 'Map data &copy; 2011 OpenStreetMap contributors, Imagery &copy; 2011 CloudMade',
    cloudmade = new L.TileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {attribution: cloudmadeAttribution});

var map = new L.Map('map').addLayer(cloudmade).setView(new L.LatLng(48.5, 2.5), 15);*/

var map = new L.Map('map');
                                        var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                                        osmAttrib = 'Map data &copy; 2011 OpenStreetMap contributors',
                                        osm = new L.TileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib});

                                        map.setView(new L.LatLng(52.55050, 13.35900), 16).addLayer(osm);
var osmGeocoder = new L.Control.OSMGeocoder();

map.addControl(osmGeocoder);
                                        $(document).ready(function(){
                                            $('.logout').toggle();
                                        });
                                      </script>

                                      ";
                    echo ' <!-- Kontakt -->

                                <div id="contact" class="container col-sm-12">

                                    <h3>Kontakt<div class="small pull-right"><a href="#">nach oben</a></div></h3>

                                    <div class="col-sm-12" style="background-color: #efefef;border-radius: 4px;padding: 10px;">

                                        <div id="impress" class="row col-sm-6">
                                            <h3>Impressum</h3>
                                              <p>Pfandhelfer.de</p>
                                              <p>Beuth Hochschule (FBVI - MME1) <a href="mailto:f.kruellke@beuth-hochschule.de?Subject=Pfandhelfer-Info" target="_top"><span class="glyphicon glyphicon-send"></span></a></p>
                                              <p>Luxemburger Str. 3</p>
                                              <p>13353 Berlin</p>
                                              <p>(alle Rechte vorbehalten)</p>
                                        </div><!-- End of Col-6

                                        <div class="row col-sm-6">
                                            <h3>Sie sind als soziale Einrichtung interessiert?</h3>
                                                Alle weiteren Informationen zur Anmeldung finden sie hier:
                                                <div class="" style="padding-top: 20px">
                                                    <button id="sozlogin" name="einrichtung" type="submit" class=" btn btn-default" data-toggle="modal" data-target="#myModal"/>Anmeldung für soziale Einrichtungen</button>
                                                </div>
                                        </div><!-- End of Col-6 -->

                                    </div><!-- End of Col-12 -->

                                </div><!-- End of Contact-Row -->

                                <div class="row">

                                      <p></p>

                                    </div>

                              <!-- End of Kontakt-->

                           <!-- End of Main Content -->

                      <!-- Footer
                      ========================================= -->

                              <footer class="">

                                <div class="container text-center">

                                    <p>made by <a href="mailto:pfandhelfer@gmail.com?Subject=Pfandhelfer-Info" target="_top"><span class="glyphicon glyphicon-send"></span> Katharina Bolinski & Florian Krüllke</a> ©2015</p>

                                </div>

                              </footer> <!-- End of Footer -->
                              </html>';
                } else {
                    header('Location: admin.php?');
                }
        }

    }

} else {
     $msg = $_SERVER['REQUEST_URI'];
     $from = intval( strpos($msg, "?") );
     if($from > 0){
        $msg = '<span class="text-danger small">Falsche Email oder ungültiges Passwort eingeben. Versuchen sie es erneut!</span>';
     } else $msg = "";
     echo '</div>
             <div class="modal fade" id="myModal" role="dialog">

                         <div class="modal-dialog">

                           <!-- Modal content-->

                           <div class="modal-content">

                             <div class="modal-header">

                               <button type="button" class="close" data-dismiss="modal">&times;</button>
                               <div class="btn glyphicon glyphicon-info-sign disabled">
                                 </div>
                                 '.$msg.'
                               </div>

                               <div class="modal-body">

                                 <div class="tab-content">

                                   <div id="home" class="tab-pane fade in active">

                                     <h3>Login</h3>

                                     <form class="form-login" action="admin.php" method="POST">
                                       <div class="form-group">
                                         <div class="">
                                           <label for="loginEmail" class="sr-only">Email-Adresse</label>
                                           <input id="loginEmail" name="email" class="form-control" type="email" placeholder="Email" autofocus data-placement="top" data-toggle="popover" title="Email" data-content="Gib eine richtige Email-Adresse ein!" required>
                                         </div>
                                       </div>

                                       <div class="form-group">
                                         <div class="">
                                           <label for="loginPasswort" class="sr-only">Passwort</label>
                                           <input id="loginPasswort" class="form-control" name="pwd" placeholder="Passwort" data-placement="top" data-toggle="popover" title="Passwort" data-content="Dieses Passwort ist falsch!" required type="password">
                                         </div>
                                       </div>

                                       <button class="btn btn-lg btn-primary btn-block" type="submit">Anmelden</button>

                                     </form>

                                   </div>
                               </div> <!-- End of Tab-Content -->

                             </div> <!-- End of Modal-Body -->

                             <div class="modal-footer">

                             </div>

                         </div> <!-- End of Modal-Content -->

                       </div>  <!-- End of Modal-Dialog -->

                   </div>  <!-- End of myModal-->';

                   echo '
                    </body>
                    <script>
                        $(".login").click();
                    </script>
                  </html>';
}

?>
