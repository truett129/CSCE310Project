<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'admin') {
    die("Access denied: User not logged in or not an admin.");
}

include_once '../database.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Progress Tracking</title>
    <link rel="stylesheet" href="../css/styles.css" />
</head>

<body>
    <header>
        <h1>Program Progress Tracking</h1>
        <div class="header-links"><a href="../index.php" class="button">Back to Home</a></div>
    </header>
    <div class="container">
        <div class="content">
            <form action="./a_program_progress/tracks.php">
                <input type="submit" value="Tracks" class="button">
            </form>
            <form action="./a_program_progress/classes.php">
                <input type="submit" value="Classes" class="button">
            </form>
            <form action="./a_program_progress/internships.php">
                <input type="submit" value="Internships" class="button">
            </form>
            <form action="./a_program_progress/certifications.php">
                <input type="submit" value="Certifications" class="button">
            </form>
        </div>
    </div>
</body>

</html>