<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome, " . htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') . "! <br>";
echo "<a href='logout.php'>Logout</a>";
?>
