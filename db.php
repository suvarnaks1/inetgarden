<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "golden_aura"; // നിങ്ങളുടെ ഡാറ്റാബേസിന്റെ പേര് ഇതാണെന്ന് ഉറപ്പാക്കുക

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>