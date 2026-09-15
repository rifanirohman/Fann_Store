<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_diamondstore;charset=utf8","root","");
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Koneksi database gagal: ".$e->getMessage());
}
?>
