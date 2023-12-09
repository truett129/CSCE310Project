<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'admin') {
    die("Access denied: User not logged in or not an admin.");
}

include_once '../../database.php';

$message = '';
$uinExists = true;

// Delete certification
if (isset($_GET['delete']) && $_GET['delete']) {
    $certificationID = $_GET['delete'];
    $deleteSql = "DELETE FROM Certification WHERE Cert_ID = '$certificationID'";
    if (mysqli_query($conn, $deleteSql)) {
        $message = "Certification deleted successfully";
    } else {
        $message = "Error deleting Certification: " . mysqli_error($conn);
    }
}

// Insert or Update Certifications
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['certification-name'], $_POST['certification-description'], $_POST['certification-level'])) {
    $certificationName = $_POST['certification-name'];
    $certificationDesc = $_POST['certification-description'];
    $certificationLevel = $_POST['certification-level'];

    // Check if the certification exists
    $checkCinSql = "SELECT * FROM Certification WHERE Name = '$certificationName'";
    $checkCinResult = mysqli_query($conn, $checkCinSql);
    if (mysqli_num_rows($checkCinResult) == 0) {
        // insert
        $insertSql = "INSERT INTO Certification (Name, Description, Level) VALUES ('$certificationName', '$certificationDesc', '$certificationLevel')";
        if (mysqli_query($conn, $insertSql)) {
            $message = "New certification created successfully";
        } else {
            $message = "Error creating certification: " . mysqli_error($conn);
        }
    } else {
        // update certification
        $updateSql = "UPDATE Certification SET Description = '$certificationDesc', Level='$certificationLevel' WHERE Name = '$certificationName'";
        if (mysqli_query($conn, $updateSql)) {
            $message = "Certification updated successfully";
        } else {
            $message = "Error updating Certification: " . mysqli_error($conn);
        }
        
    }
}

// Fetch all Certifications
$certifications = mysqli_query($conn, "SELECT * FROM Certification");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Progress Tracking</title>
    <link rel="stylesheet" href="../../css/styles.css" />
</head>

<body>
    <header>
        <h1>Certification Tracking</h1>
        <div class="header-links"><a href="../a_program_progress.php" class="button">Back to admin program portal</a></div>
    </header>
    <div class="container">
        <div class="content">
            <!-- Insert or Update certification -->
            <div class="new-program-form">
                <h2>Record or Update Certification</h2>
                <form action="" method="POST">
                    <div class="input-label">
                        <label for="certification-name">Certification Name</label>
                        <input type="text" name="certification-name" id="certification-name" required>
                    </div>
                    <div class="input-label">
                        <label for="certification-description">Certification Description</label>
                        <input type="text" name="certification-description" id="certification-description" required>
                    </div>
                    <div class="input-label">
                        <label for="certification-level">Certification Level</label>
                        <select name="certification-level" id="certification-level" required>
                            <option value='Beginner'>In-Progress</option>
                            <option value='Intermediate'>Intermediate</option>
                            <option value='Advanced'>Advanced</option>
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

            <!-- Display certificationes -->
            <section>
                <h2>Certifications</h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Level</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($certifications) > 0) {
                        while ($row = mysqli_fetch_assoc($certifications)) {
                            echo "<tr>
                            <td>" . $row['Name'] . "</td>
                            <td>" . $row['Description'] . "</td>
                            <td>" . $row['Level'] . "</td>
                            <td><a href='?delete=" . $row['Cert_ID'] . "'>Delete</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No Certifications found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>

</html>