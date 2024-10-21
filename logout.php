<?php
session_start();
session_unset();
session_destroy();

// LogOut

header("Location: login.php");
exit;
?>
