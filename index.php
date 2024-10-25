<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the main ToDoList page if logged in
    header("Location: ToDoList.php");
exit();
} else {
    // Redirect to login page if not logged in
    header('Location: login.php');
}
exit;
?>
