<?php
$servername = "localhost";
$username = "root";       // Your MySQL username
$password = "";           // Your MySQL password
$dbname = "ADSFinal";    // The name of your database (case-sensitive)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>