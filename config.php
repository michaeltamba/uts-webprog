<?php
// config.php - Database connection
$host = 'localhost'; // Change if needed
$dbname = 'todo_app'; // Change if needed
$user = 'root'; // Change if needed
$pass = ''; // Change if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
