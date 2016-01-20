<style>
field{
    width: 90px;
    padding: 3px 3px;
}
</style>
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

$sql = "SELECT email, strasse, hausnummer, plz FROM User";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    $num = 1;
    while($row = $result->fetch_assoc()) {
        echo "<label>".$num++. ".</label>
        <field name='email'>" . $row["email"]."</field>
        <field name='strasse'>" . $row["strasse"]."</field>
        <field name='hausnummer'>" . $row["hausnummer"]."</field>
        <field name='plz'>" . $row["plz"]."</field>
        <br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>