<?php
// config.php
$host = 'localhost'; // Localhost karena kita pakai XAMPP
$dbname = 'todo_app'; // Nama database yang Anda buat di phpMyAdmin
$user = 'root'; // Default user MySQL di XAMPP
$pass = ''; // Default password kosong di XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
