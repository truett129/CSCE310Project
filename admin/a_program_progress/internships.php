<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

/**
* This is the admin internships management page. Users can:
*   Add new internships
*   Delete existing internships
*   Update internships
*   View all internships
* @author     pranav
* ...
*/

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'admin') {
    die("Access denied: User not logged in or not an admin.");
}

include_once '../../database.php';

$message = '';
$uinExists = true;

// Delete Internship
if (isset($_GET['delete']) && $_GET['delete']) {
    $intID = $_GET['delete'];
    $deleteSql = "DELETE FROM Internship WHERE Intern_ID = '$intID'";
    if (mysqli_query($conn, $deleteSql)) {
        $message = "Internship deleted successfully";
    } else {
        $message = "Error deleting Internship: " . mysqli_error($conn);
    }
}

// Insert or Update Internships
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['internship-name'], $_POST['internship-description'], $_POST['internship-is-gov'], $_POST['internship-location'])) {
    $intName = $_POST['internship-name'];
    $intDesc = $_POST['internship-description'];
    $intIsGov = $_POST['internship-is-gov'];
    $intLocation = $_POST['internship-location'];
    // Check if the Internship exists
    $checkIinSql = "SELECT * FROM Internship WHERE Name = '$intName'";
    $checkIinResult = mysqli_query($conn, $checkIinSql);
    if (mysqli_num_rows($checkIinResult) == 0) {
        // insert
        $insertSql = "INSERT INTO Internship (Name, Description, Is_Gov, Location) VALUES ('$intName', '$intDesc', $intIsGov, '$intLocation')";
        if (mysqli_query($conn, $insertSql)) {
            $message = "New internship created successfully";
        } else {
            $message = "Error creating internship: " . mysqli_error($conn);
        }
    } else {
        // Internship class
        $updateSql = "UPDATE Internship SET Description = '$intDesc', Is_Gov=$intIsGov, Location='$intLocation' WHERE Name = '$intName'";
        if (mysqli_query($conn, $updateSql)) {
            $message = "Internship updated successfully";
        } else {
            $message = "Error updating Internship: " . mysqli_error($conn);
        }
        
    }
}

// Fetch all Internships
$internships = mysqli_query($conn, "SELECT * FROM Internship");
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
        <h1>Internship Tracking</h1>
        <div class="header-links"><a href="../a_program_progress.php" class="button">Back to admin program portal</a></div>
    </header>
    <div class="container">
        <div class="content">
            <!-- Insert or Update Internship -->
            <div class="new-program-form">
                <h2>Record or Update Internship</h2>
                <form action="" method="POST">
                    <div class="input-label">
                        <label for="internship-name">Internship Name</label>
                        <input type="text" name="internship-name" id="internship-name" required>
                    </div>
                    <div class="input-label">
                        <label for="internship-description">Internship Description</label>
                        <input type="text" name="internship-description" id="internship-description" required>
                    </div>
                    <div class="input-label">
                        <label for="internship-is-gov">Government Internship?</label>
                        <select name="internship-is-gov" id="internship-is-gov" required>
                            <option value="true"> true </option>
                            <option value="false"> false </option>
                        </select>
                    </div>
                    <div class="input-label">
                        <label for="internship-location">Internship Location</label>
                        <input type="text" name="internship-location" id="internship-location" required>
                    </div>
                    <input type="submit" value="Submit" class="button">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Display Internships -->
            <section>
                <h2>Internship</h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Federal</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($internships) > 0) {
                        while ($row = mysqli_fetch_assoc($internships)) {
                            echo "<tr>
                            <td>" . $row['Name'] . "</td>
                            <td>" . $row['Description'] . "</td>
                            <td>" . $row['Is_Gov'] . "</td> 
                            <td>" . $row['Location'] . "</td>
                            <td><a href='?delete=" . $row['Intern_ID'] . "'>Delete</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No internships found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>

</html>