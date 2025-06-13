<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_apk_ormawa";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    // Kembalikan error tapi jangan echo HTML atau JSON
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
