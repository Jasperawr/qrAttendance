<?php
$servername = "srv1758.hstgr.io";
$serverusername = "u188083669_qrams_db";
$password = "Bf411Wg|^~";
$dbname = "u188083669_qrAttendance";

// Create connection
$conn = mysqli_connect($servername, $serverusername, $password, $dbname);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>