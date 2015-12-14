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
/*$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('John', 'Doe', 'john@example.com')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();*/
?>
</table> </output>
</body> </html>