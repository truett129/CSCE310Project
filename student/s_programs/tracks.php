<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure the user is logged in and is a student
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'student') {
    die("Access denied: User not logged in or not a student.");
}

// Ensure the user has a UIN set in the session
if (!isset($_SESSION['UIN'])) {
    die("User not logged in or UIN not set");
}

$uin = $_SESSION['UIN'];

include_once '../../database.php';

$message = '';


// Fetch Program Progress for the logged-in student
$sql = "SELECT * FROM Program_Progress
        WHERE UIN = $uin";

$progress = mysqli_query($conn, $sql);

// Fetch all programs for the dropdown
$programsResult = mysqli_query($conn, "SELECT Program_Num, Name FROM Programs");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Progress Tracking</title>
    <link rel="stylesheet" href="../../css/styles.css" /> <!-- Ensure correct path to style.css -->
</head>

<body>
    <header>
        <h1>Program Progress Tracking</h1>
        <div class="header-links"><a href="../../index.php" class="button">Back to Home</a></div>
    </header>
    <div class="container">
        <div class="content">

            <!-- Display Program Progress -->
            <section>
                <h2>Your Program Progress</h2>
                <table>
                    <tr>
                        <th>Tracking Number</th>
                        <th>Program Name</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($progress) > 0) {
                        while ($row = mysqli_fetch_assoc($progress)) {
                            echo "<tr>
                            <td>" . $row['Tracking_Num'] . "</td>
                            <td>" . $row['Name'] . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No progress records found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>

</html>