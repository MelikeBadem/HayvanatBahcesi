<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";

// Veritabanı bağlantısı oluşturma
$conn = new mysqli($servername, $username, $password, $dbname);


// Bağlantıyı kontrol etme
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
