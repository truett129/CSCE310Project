<?php
session_start();

// Destroy the session.
session_destroy();

// Redirect to the login page or home page after logging out
header("Location: login.php");
exit();
?>