<style>
field{
    width: 90px;
    padding: 3px 3px;
}
</style>

<?php

require 'Scripts/php/db-connect.php';

if($dbloaded == true){

    $sql = "SELECT email, strasse, hausnummer, plz FROM User";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        $num = 1;
        while($row = $result->fetch_assoc()) {
            echo "<div class='pfandspende'>
            <label>".$num++. ".</label>
            <field name='email'>" . $row["email"]."</field>
            <field name='strasse'>" . $row["strasse"]."</field>
            <field name='hausnummer'>" . $row["hausnummer"]."</field>
            <field name='plz'>" . $row["plz"]."</field>
            </div>";
        }

    } else {

        echo "0 results";

    }

    $conn->close();

}
?>