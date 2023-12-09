<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure the user is logged in and is an admin
/**
* This is the admin classes management page. Users can:
*   Add new classes
*   Delete existing classes
*   Update classes
*   View all classes
* @author     pranav
* ...
*/
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'admin') {
    die("Access denied: User not logged in or not an admin.");
}

include_once '../../database.php';

$message = '';
$uinExists = true;


// Delete Class
if (isset($_GET['delete']) && $_GET['delete']) {
    $classID = $_GET['delete'];
    $deleteSql = "DELETE FROM Classes WHERE Class_ID = '$classID'";
    if (mysqli_query($conn, $deleteSql)) {
        $message = "Class deleted successfully";
    } else {
        $message = "Error deleting Class: " . mysqli_error($conn);
    }
}

// Insert or Update Classes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['class-name'], $_POST['class-description'], $_POST['class-type'])) {
    $className = $_POST['class-name'];
    $classDesc = $_POST['class-description'];
    $classType = $_POST['class-type'];

    // Check if the Class exists
    $checkCinSql = "SELECT * FROM Classes WHERE Name = '$className'";
    $checkCinResult = mysqli_query($conn, $checkCinSql);
    if (mysqli_num_rows($checkCinResult) == 0) {
        // insert
        $insertSql = "INSERT INTO Classes (Name, Description, Type) VALUES ('$className', '$classDesc', '$classType')";
        if (mysqli_query($conn, $insertSql)) {
            $message = "New class created successfully";
        } else {
            $message = "Error creating class: " . mysqli_error($conn);
        }
    } else {
        // update class
        $updateSql = "UPDATE Classes SET Description = '$classDesc', Type='$classType' WHERE Name = '$className'";
        if (mysqli_query($conn, $updateSql)) {
            $message = "Class updated successfully";
        } else {
            $message = "Error updating Class: " . mysqli_error($conn);
        }
        
    }
}

// Fetch all Classes
$classes = mysqli_query($conn, "SELECT * FROM Classes");
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
        <h1>Class Tracking</h1>
        <div class="header-links"><a href="../a_program_progress.php" class="button">Back to admin program portal</a></div>
    </header>
    <div class="container">
        <div class="content">
            <!-- Insert or Update Class -->
            <div class="new-program-form">
                <h2>Record or Update Class</h2>
                <form action="" method="POST">
                    <div class="input-label">
                        <label for="class-name">Class Name</label>
                        <input type="text" name="class-name" id="class-name" required>
                    </div>
                    <div class="input-label">
                        <label for="class-description">Class Description</label>
                        <input type="text" name="class-description" id="class-description" required>
                    </div>
                    <div class="input-label">
                        <label for="class-type">Class Type</label>
                        <select name="class-type" id="class-type" required>
                            <option value='Foreign Language'>Foreign Language</option>
                            <option value='Computer Science'>Computer Science</option>
                            <option value='Math'>Math</option>
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

            <!-- Display Classes -->
            <section>
                <h2>Classes</h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($classes) > 0) {
                        while ($row = mysqli_fetch_assoc($classes)) {
                            echo "<tr>
                            <td>" . $row['Name'] . "</td>
                            <td>" . $row['Description'] . "</td>
                            <td>" . $row['Type'] . "</td>
                            <td><a href='?delete=" . $row['Class_ID'] . "'>Delete</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No classes found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>

</html>