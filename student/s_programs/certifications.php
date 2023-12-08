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

// Delete Cert Enrollment
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $CertE_Num = $_GET['delete'];
    $deleteSql = "DELETE FROM Cert_Enrollment WHERE CertE_Num = '$CertE_Num'";
    if (mysqli_query($conn, $deleteSql)) {
        $message = "Enrollment deleted successfully";
    } else {
        $message = "Error deleting enrollment: " . mysqli_error($conn);
    }
}

// Insert or Update Certification Enrollments
if ($_SERVER['REQUEST_METHOD'] == 'POST' && 
    isset($_POST['cert-id'], $_POST['cert-status'], $_POST['cert-semester'], $_POST['cert-year'], 
    $_POST['cert-training-status'], $_POST['cert-program-num'])) {
    $certID = $_POST['cert-id'];
    $certStatus = $_POST['cert-status'];
    $certSemester = $_POST['cert-semester'];
    $certYear = $_POST['cert-year'];
    $certTrainingStatus = $_POST['cert-training-status'];
    $certProgramNum = $_POST['cert-program-num'];

    // cert can only be applied to 1 program
    $checkCinSql = "SELECT * FROM Cert_Enrollment WHERE Cert_ID='$certID' AND UIN='$uin'";
    $checkCinResult = mysqli_query($conn, $checkCinSql);
    if (mysqli_num_rows($checkCinResult) == 0) {
        $insertSql = "INSERT INTO Cert_Enrollment (UIN, Cert_ID, Status, Semester, YEAR, Training_Status, Program_Num) 
        VALUES ('$uin', '$certID', '$certStatus', '$certSemester', '$certYear', '$certTrainingStatus', '$certProgramNum')";
        if (mysqli_query($conn, $insertSql)) {
            $message = "New certificate enrollment created successfully";
        } else {
            $message = "Error creating certificate enrollment: " . mysqli_error($conn);
        }
    }
    else {
        $updateSql = "UPDATE Cert_Enrollment SET Status='$certStatus', Semester='$certSemester', YEAR='$certYear', 
        Training_Status='$certTrainingStatus', Program_Num='$certProgramNum'
        WHERE UIN = '$uin' AND Cert_ID = '$certID'";
        if (mysqli_query($conn, $updateSql)) {
            $message = "Enrollment updated successfully";
        } else {
            $message = "Error updating enrollment: " . mysqli_error($conn);
        }
    }

}

// Fetch enrollments
$sql = "SELECT * FROM Cert_Enrollment
        WHERE UIN = $uin";

$enrollments = mysqli_query($conn, $sql);

// Fetch all classes for the dropdown
$certifications = mysqli_query($conn, "SELECT * FROM Certification");
$certNames = array();
$certDescs = array();
$certLevel = array();
while ($row = mysqli_fetch_assoc($certifications)) {
    $certNames[$row['Cert_ID']] = $row['Name'];
    $certDescs[$row['Cert_ID']] = $row['Description'];
    $certLevel[$row['Cert_ID']] = $row['Level'];
}

$programs = mysqli_query($conn, "SELECT * FROM Programs");
$programNames = array();
while ($row = mysqli_fetch_assoc($programs)) {
    $programNames[$row['Program_Num']] = $row['Name'];
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
        <div class="header-links"><a href="../../index.php" class="button">Back to Home</a></div>
    </header>
    <div class="container">
        <div class="content">
            <!-- Insert or Update Certification Enrollments -->
            <div class="new-program-form">
                <h2>Record or Edit Certification Enrollments</h2>
                <form action="" method="POST">
                    <div class="input-label">
                        <label for="cert-id">Certification Name</label>
                        <select name="cert-id" id="cert-id" required>
                            <?php
                            foreach ($certNames as $id => $name) {
                                echo "<option value='" . $id . "'>" . $name . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-label">
                        <label for="cert-status">Certification Status</label>
                        <input type="text" name="cert-status" id="cert-status">
                    </div>
                    <div class="input-label">
                        <label for="cert-semester">Certification Semester</label>
                        <input type="text" name="cert-semester" id="cert-semester">
                    </div>
                    <div class="input-label">
                        <label for="cert-year">Certification Year</label>
                        <input type="text" name="cert-year" id="cert-year">
                    </div>
                    <div class="input-label">
                        <label for="cert-training-status">Certification Training Status</label>
                        <select name="cert-training-status" id="cert-training-status" required>
                            <option value='In-Progress'>In-Progress</option>
                            <option value='Complete'>Complete</option>
                        </select>
                    </div>
                    <div class="input-label">
                        <label for="cert-program-num">Program</label>
                        <select name="cert-program-num" id="cert-program-num" required>
                            <?php
                            $programsList = mysqli_query($conn, "SELECT * FROM Programs");
                            while ($row = mysqli_fetch_assoc($programsList)) {
                                echo "<option value='" . $row['Program_Num'] . "'>" . $row['Name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <input type="submit" value="Submit" class="button">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Display Certification Enrollments -->
            <section>
                <h2>Certification Enrollments</h2>
                <table>
                    <tr>
                        <th>Certification Name</th>
                        <th>Certification Description</th>
                        <th>Certification Level</th>
                        <th>Status</th>
                        <th>Semester</th>
                        <th>Year</th>
                        <th>Training Status</th>
                        <th>Program</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($enrollments) > 0) {
                        while ($row = mysqli_fetch_assoc($enrollments)) {
                            echo "<tr>
                            <td>" . $certNames[$row['Cert_ID']] . "</td>
                            <td>" . $certDescs[$row['Cert_ID']] . "</td>
                            <td>" . $certLevel[$row['Cert_ID']] . "</td>
                            <td>" . $row['Status'] . "</td>
                            <td>" . $row['Semester'] . "</td>
                            <td>" . $row['YEAR'] . "</td>
                            <td>" . $row['Training_Status'] . "</td>
                            <td>" . $programNames[$row['Program_Num']] . "</td>
                            <td><a href='?delete=" . $row['CertE_Num'] . "'>Delete</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No certification enrollments found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>

</html>