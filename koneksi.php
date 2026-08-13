<?php

$host = "127.0.0.1";
$user = "root";
$pass = "root";
$db   = "rsgm_unimus_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}

// Set charset supaya konsisten & aman dari isu encoding
$conn->set_charset("utf8mb4");
?>
