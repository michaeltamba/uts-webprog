<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ToDoList.php");
exit();
} else {
    header('Location: login.php');
}
exit;
?>
