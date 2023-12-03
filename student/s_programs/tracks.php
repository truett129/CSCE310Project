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

// Insert or Update Program Progress
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['program_num'])) {
    $programNum = $_POST['program_num'];
    $trackingNum = isset($_POST['tracking_num']) ? $_POST['tracking_num'] : null;

    if ($trackingNum) {
        // Update existing progress
        $updateSql = "UPDATE Track SET Program_Num = '$programNum' WHERE Tracking_Num = '$trackingNum' AND Student_Num = '$uin'";
        if (mysqli_query($conn, $updateSql)) {
            $message = "Progress updated successfully";
        } else {
            $message = "Error updating progress: " . mysqli_error($conn);
        }
    } else {
        // Insert new progress
        $insertSql = "INSERT INTO Track (Program_Num, Student_Num) VALUES ('$programNum', '$uin')";
        if (mysqli_query($conn, $insertSql)) {
            $message = "New progress record created successfully";
        } else {
            $message = "Error creating progress record: " . mysqli_error($conn);
        }
    }
}

// Delete Program Progress
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $trackingNum = $_GET['delete'];
    $deleteSql = "DELETE FROM Track WHERE Tracking_Num = '$trackingNum' AND Student_Num = '$uin'";
    if (mysqli_query($conn, $deleteSql)) {
        $message = "Progress deleted successfully";
    } else {
        $message = "Error deleting progress: " . mysqli_error($conn);
    }
}

// Fetch Program Progress for the logged-in student
$sql = "SELECT Track.*, Programs.Name FROM Track 
        INNER JOIN Programs ON Track.Program_Num = Programs.Program_Num
        WHERE Track.Student_Num = $uin";

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
        <div class="header-links"><a href="../index.php" class="button">Back to Home</a></div>
    </header>
    <div class="container">
        <div class="content">
            <!-- Insert or Update Program Progress Section -->
            <div class="new-program-form">
                <h2>Record or Edit Program Progress</h2>
                <form action="" method="POST">
                    <div class="input-label">
                        <label for="program_num">Program Name</label>
                        <select name="program_num" id="program_num" required>
                            <?php
                            while ($row = mysqli_fetch_assoc($programsResult)) {
                                echo "<option value='" . $row['Program_Num'] . "'>" . $row['Name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-label">
                        <label for="tracking_num">Tracking Number (optional, for updates)</label>
                        <input type="text" name="tracking_num" id="tracking_num">
                    </div>
                    <input type="submit" value="Submit" class="button">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Display Program Progress -->
            <section>
                <h2>Your Program Progress</h2>
                <table>
                    <tr>
                        <th>Tracking Number</th>
                        <th>Program Name</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($progress) > 0) {
                        while ($row = mysqli_fetch_assoc($progress)) {
                            echo "<tr>
                            <td>" . $row['Tracking_Num'] . "</td>
                            <td>" . $row['Name'] . "</td>
                            <td><a href='?delete=" . $row['Tracking_Num'] . "'>Delete</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No progress records found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>

</html>