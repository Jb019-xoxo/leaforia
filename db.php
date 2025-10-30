<?php
$servername = "sql104.infinityfree.com";
$username = "if0_40250089";
$password = "XwmQiRqNyd6";
$dbname = "if0_40250089_herbal_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>