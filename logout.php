<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to login page or home page
header("Location: home.php");
exit;
?>
