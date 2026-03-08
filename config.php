<?php
$conn = new mysqli("localhost", "root", "", "health_system_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>