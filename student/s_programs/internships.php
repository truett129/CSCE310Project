<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

/**
* This is the student internships applications page. Users can:
*   Add new internships applications
*   Delete existing internships applications
*   Update internships applications
*   View all internships applications
* @author     pranav
* ...
*/

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


// Delete Internship Application
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $IA_Num = $_GET['delete'];
    $deleteSql = "DELETE FROM Intern_App WHERE IA_Num='$IA_Num'";
    if (mysqli_query($conn, $deleteSql)) {
        $message = "Internship Application deleted successfully";
    } else {
        $message = "Error deleting Internship Application: " . mysqli_error($conn);
    }
}

// Insert or Update Intern Application
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['intern-id'], $_POST['intern-status'], $_POST['intern-year'])) {
    $internID = $_POST['intern-id'];
    $internStatus = $_POST['intern-status'];
    $internYear = $_POST['intern-year'];
    $checkIAinSql = "SELECT * FROM Intern_App WHERE Intern_ID='$internID' AND UIN='$uin'";
    $checkiAinResult = mysqli_query($conn, $checkIAinSql);
    if (mysqli_num_rows($checkiAinResult) == 0) {
        $insertSql = "INSERT INTO Intern_App (UIN, Intern_ID, Status, Year) 
        VALUES ('$uin', '$internID', '$internStatus', '$internYear')";
        if (mysqli_query($conn, $insertSql)) {
            $message = "New internship application created successfully";
        } else {
            $message = "Error creating internship application: " . mysqli_error($conn);
        }
    }
    else {
        $updateSql = "UPDATE Intern_App SET Status='$internStatus', Year='$internYear'
        WHERE UIN='$uin' AND Intern_ID='$internID'";
        if (mysqli_query($conn, $updateSql)) {
            $message = "Internship Application updated successfully";
        } else {
            $message = "Error updating Internship Application: " . mysqli_error($conn);
        }
    }

}

// Fetch enrollments
$sql = "SELECT * FROM Intern_App
        WHERE UIN = $uin";

$applications = mysqli_query($conn, $sql);

// Fetch all classes for the dropdown
$internships = mysqli_query($conn, "SELECT * FROM Internship");
$internNames = array();
$internDescs = array();
$internIsGovs = array();
while ($row = mysqli_fetch_assoc($internships)) {
    $internNames[$row['Intern_ID']] = $row['Name'];
    $internDescs[$row['Intern_ID']] = $row['Description'];
    $internIsGovs[$row['Intern_ID']] = $row['Is_Gov'];
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
            <!-- Insert or Update Internship Applications -->
            <div class="new-program-form">
                <h2>Record or Edit Internship Applications</h2>
                <form action="" method="POST">
                    <div class="input-label">
                        <label for="intern-id">Internship Name</label>
                        <select name="intern-id" id="intern-id" required>
                            <?php
                            foreach ($internNames as $id => $name) {
                                echo "<option value='" . $id . "'>" . $name . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-label">
                        <label for="intern-status">Internship Status</label>
                        <select name="intern-status" id="intern-status" required>
                            <option value='In-Progress'>In-Progress</option>
                            <option value='Completed'>Completed</option>
                            <option value='Applied'>Applied</option>
                            <option value='Rejected'>Rejected</option>
                            <option value='Accepted but turned down'>Accepted but turned down</option>
                        </select>
                    </div>
                    <div class="input-label">
                        <label for="intern-year">Class Year</label>
                        <input type="text" name="intern-year" id="intern-year">
                    </div>
                    <input type="submit" value="Submit" class="button">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Display Internship Applications -->
            <section>
                <h2>Internship Applications</h2>
                <table>
                    <tr>
                        <th>Internship Name</th>
                        <th>Internship Description</th>
                        <th>Internship Is Gov</th>
                        <th>Status</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($applications) > 0) {
                        while ($row = mysqli_fetch_assoc($applications)) {
                            echo "<tr>
                            <td>" . $internNames[$row['Intern_ID']] . "</td>
                            <td>" . $internDescs[$row['Intern_ID']] . "</td>
                            <td>" . $internIsGovs[$row['Intern_ID']] . "</td>
                            <td>" . $row['Status'] . "</td>
                            <td>" . $row['Year'] . "</td>
                            <td><a href='?delete=" . $row['IA_Num'] . "'>Delete</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No internship applications found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>

</html>