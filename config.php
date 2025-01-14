<?php
$host = 'localhost';
$dbname = 'todo_app';
$user = 'root';
$pass = '';

$dsnWithoutDB = "mysql:host=$host;charset=utf8mb4";
$dsnWithDB = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsnWithoutDB, $user, $pass, $options);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    $pdo = new PDO($dsnWithDB, $user, $pass, $options);

    $createUserTableQuery = "
        CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(50) NOT NULL,
            `email` varchar(100) NOT NULL UNIQUE,
            `password` varchar(255) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
    $pdo->exec($createUserTableQuery);
    $createTodoListTableQuery = "
        CREATE TABLE IF NOT EXISTS `todo_lists` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `title` varchar(255) NOT NULL,
            `description` TEXT,
            `deadline` DATE,
            `note` TEXT,
            `status` ENUM('incomplete', 'complete') DEFAULT 'incomplete',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
    $pdo->exec($createTodoListTableQuery);

    $alterTableQuery = "
        ALTER TABLE `todo_lists` 
        ADD COLUMN IF NOT EXISTS `description` TEXT,
        ADD COLUMN IF NOT EXISTS `deadline` DATE,
        ADD COLUMN IF NOT EXISTS `note` TEXT,
        ADD COLUMN IF NOT EXISTS `status` ENUM('incomplete', 'complete') DEFAULT 'incomplete';
    ";
    $pdo->exec($alterTableQuery);

} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
