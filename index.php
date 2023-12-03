<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['userLoggedIn'])) {
    header('Location: login.php');
    exit();
}

$userRole = $_SESSION['userRole'];
?>



<!DOCTYPE html>
<html>

<head>
    <title>My Homepage</title>
    <link rel="stylesheet" href="./css/styles.css" />
</head>

<body>
    <header>
        <h1>Texas A&M Cybersecurity</h1>
        <div class="header-links">
            <?php if ($userRole == 'admin' || $userRole == 'student'): ?>
                <a href="logout.php">Logout</a>
            <?php endif; ?>
        </div>
    </header>

    <div id="content">
        <p>Please select your view</p>
    </div>

    <?php if ($userRole == 'admin'): ?>
        <div class="role-container">
            <h2>Admin</h2>
            <a href="admin/a_auth.php">Authentication</a>
            <a href="admin/a_event.php">Events</a>
            <a href="admin/a_program_info.php">Program Info</a>
            <a href="admin/a_program_progress.php">Program Progress</a>
        </div>
    <?php endif; ?>

    <?php if ($userRole == 'student'): ?>
        <div class="role-container">
            <h2>Student</h2>
            <a href="student/s_application_info.php">Applications</a>
            <a href="student/s_documents.php">Documents</a>
            <a href="student/s_programs.php">Programs</a>
            <a href="student/s_user_auth.php">Authentication</a>
        </div>
    <?php endif; ?>
</body>

</html>