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


// Delete Class Enrollment
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $ceNum = $_GET['delete'];
    $deleteSql = "DELETE FROM Class_Enrollment WHERE CE_NUM = '$ceNum'";
    if (mysqli_query($conn, $deleteSql)) {
        $message = "Enrollment deleted successfully";
    } else {
        $message = "Error deleting enrollment: " . mysqli_error($conn);
    }
}

// Insert or Update Class Enrollments
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['class-id'], $_POST['class-status'], $_POST['class-semester'], $_POST['class-year'])) {
    $classID = $_POST['class-id'];
    $classStatus = $_POST['class-status'];
    $classSemester = $_POST['class-semester'];
    $classYear = $_POST['class-year'];

    $checkCinSql = "SELECT * FROM Class_Enrollment WHERE Class_ID = '$classID' AND UIN = '$uin'";
    $checkCinResult = mysqli_query($conn, $checkCinSql);
    if (mysqli_num_rows($checkCinResult) == 0) {
        $insertSql = "INSERT INTO Class_Enrollment (UIN, Class_ID, Status, Semester, Year) 
        VALUES ('$uin', '$classID', '$classStatus', '$classSemester', '$classYear')";
        if (mysqli_query($conn, $insertSql)) {
            $message = "New enrollment created successfully";
        } else {
            $message = "Error creating enrollment: " . mysqli_error($conn);
        }
    }
    else {
        $updateSql = "UPDATE Class_Enrollment SET Status='$classStatus', Semester='$classSemester', Year='$classYear'
        WHERE UIN = '$uin' AND Class_ID = '$classID'";
        if (mysqli_query($conn, $updateSql)) {
            $message = "Enrollment updated successfully";
        } else {
            $message = "Error updating enrollment: " . mysqli_error($conn);
        }
    }

}

// Fetch enrollments
$sql = "SELECT * FROM Class_Enrollment
        WHERE UIN = $uin";

$enrollments = mysqli_query($conn, $sql);

// Fetch all classes for the dropdown
$classResult = mysqli_query($conn, "SELECT * FROM Classes");
$classNames = array();
$classDescs = array();
$classTypes = array();
while ($row = mysqli_fetch_assoc($classResult)) {
    $classNames[$row['Class_ID']] = $row['Name'];
    $classDescs[$row['Class_ID']] = $row['Description'];
    $classTypes[$row['Class_ID']] = $row['Type'];
}
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
        <div class="header-links"><a href="../s_programs.php" class="button">Back to student programs</a></div>
    </header>
    <div class="container">
        <div class="content">
            <!-- Insert or Update Class Enrollments -->
            <div class="new-program-form">
                <h2>Record or Edit Class Enrollments</h2>
                <form action="" method="POST">
                    <div class="input-label">
                        <label for="class-id">Class Name</label>
                        <select name="class-id" id="class-id" required>
                            <?php
                            foreach ($classNames as $id => $name) {
                                echo "<option value='" . $id . "'>" . $name . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-label">
                        <label for="class-status">Class Status</label>
                        <select name="class-status" id="class-status" required>
                            <option value='In-Progress'>In-Progress</option>
                            <option value='Completed'>Completed</option>
                            <option value='Dropped'>Dropped</option>
                            <option value='Waitlisted'>Waitlisted</option>
                        </select>
                    </div>
                    <div class="input-label">
                        <label for="class-semester">Class Semester</label>
                        <input type="text" name="class-semester" id="class-semester">
                    </div>
                    <div class="input-label">
                        <label for="class-year">Class Year</label>
                        <input type="text" name="class-year" id="class-year">
                    </div>
                    <input type="submit" value="Submit" class="button">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Display Class Enrollments -->
            <section>
                <h2>Class Enrollments</h2>
                <table>
                    <tr>
                        <th>Class Name</th>
                        <th>Class Description</th>
                        <th>Class Type</th>
                        <th>Status</th>
                        <th>Semester</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($enrollments) > 0) {
                        while ($row = mysqli_fetch_assoc($enrollments)) {
                            echo "<tr>
                            <td>" . $classNames[$row['Class_ID']] . "</td>
                            <td>" . $classDescs[$row['Class_ID']] . "</td>
                            <td>" . $classTypes[$row['Class_ID']] . "</td>
                            <td>" . $row['Status'] . "</td>
                            <td>" . $row['Semester'] . "</td>
                            <td>" . $row['Year'] . "</td>
                            <td><a href='?delete=" . $row['CE_NUM'] . "'>Delete</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No class enrollments found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>

</html>