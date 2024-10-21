<?php
// config.php
$host = 'localhost';
$dbname = 'todo_app';  // Nama database
$user = 'root';        // Username MySQL (root di XAMPP)
$pass = '';            // Password MySQL (kosong untuk XAMPP)

// Membuat koneksi ke MySQL tanpa menentukan database terlebih dahulu (untuk membuat database)
$dsnWithoutDB = "mysql:host=$host;charset=utf8mb4";
$dsnWithDB = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Menangani error dengan exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Mengambil data sebagai array asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Menonaktifkan emulasi prepare statements
];

try {
    // Koneksi tanpa database, untuk cek apakah database sudah ada
    $pdo = new PDO($dsnWithoutDB, $user, $pass, $options);

    // Cek apakah database ada, jika tidak, buat database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    // Setelah database dibuat (jika belum ada), koneksi ulang dengan database yang baru dibuat
    $pdo = new PDO($dsnWithDB, $user, $pass, $options);

    // Membuat tabel users jika belum ada
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(50) NOT NULL,
            `email` varchar(100) NOT NULL UNIQUE,
            `password` varchar(255) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
    $pdo->exec($createTableQuery);

} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
