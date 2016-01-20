<!DOCTYPE html> <html>
<head>
<meta charset="utf-8">
<title>HTML5 FORM RESPONSE</title> </head>
<body> <output>
<h1>PHP ECHO OF POST REQUEST:</h1>
<br>
<table border="1" cellpadding="2" cellspacing="0" width="100%"> <?php
foreach ($_POST as $key => $value)
print("<tr><td bgcolor=\"#bbbbbb\"> <strong>$key</strong></td>
<td>htmlspecialchars($value)</td></tr>");
?>
</table>
<br>
<h1>PHP ECHO OF GET REQUEST:</h1>
<br>
<table border="1" cellpadding="2" cellspacing="0" width="100%"> <?php
foreach ($_GET as $key => $value)
print("<tr><td bgcolor=\"#bbbbbb\"> <strong>$key</strong></td>
	<td>htmlspecialchars($value)</td></tr>");
//header( "Location: text.html" );
?>

<?php
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
VALUES ('{$_POST["email"]}', '{$_POST["vorname"]}', '{$_POST["nachname"]}', '{$_POST["telefon"]}', '{$_POST["strasse"]}', {$_POST["hausnummer"]}, ' ', {$_POST["plz"]})";
//VALUES ( 'floriankruellke@gmx.de', 'Florian', 'Krüllke', '0123-1234', 'Weg', 3, 'b', 12345)";
//VALUES ('" . $_POST[0] . "', '" . $_POST[1] . "', '" . $_POST[2] . "', '" . $_POST[3] . "', '" . $_POST[4] . "', '" . $_POST[5] . "', ' ', '" . $_POST[6] . "')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
</table> </output>
</body> </html>