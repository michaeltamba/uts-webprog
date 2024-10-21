<?php
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

echo "Welcome, " . $_SESSION['username'] . "! <br>";
echo "<a href='logout.php'>Logout</a>";
