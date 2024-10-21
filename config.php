<?php
// config.php
$host = 'localhost'; // Localhost karena kita pakai XAMPP
$dbname = 'todo_app'; // Nama database yang Anda buat di phpMyAdmin
$user = 'root'; // Default user MySQL di XAMPP
$pass = ''; // Default password kosong di XAMPP

// Menggunakan charset UTF-8 untuk mendukung karakter lebih luas
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Menangani error dengan exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Mengambil data sebagai array asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Menonaktifkan emulasi prepare statements
];
// Test

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Untuk lingkungan produksi, lebih baik jangan tampilkan detail error ke pengguna
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
