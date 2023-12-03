<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure the user is logged in and has a UIN set in the session
if (!isset($_SESSION['UIN'])) {
    die("User not logged in or UIN not set");
}

$uin = $_SESSION['UIN'];

include_once '../database.php';

$message = '';

// File Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document'])) {
    if (isset($_POST['app_num']) && is_numeric($_POST['app_num']) && isset($_POST['doc_type'])) {
        $appNum = $_POST['app_num'];
        // Check if the application number is linked to the user
        $appCheckSql = "SELECT * FROM Applications WHERE App_Num = $appNum AND UIN = $uin";
        $appCheckResult = mysqli_query($conn, $appCheckSql);
        if (mysqli_num_rows($appCheckResult) > 0) {
            // Application belongs to the user, proceed with upload
            $filename = $_FILES['document']['name'];
            $tempname = $_FILES['document']['tmp_name'];
            $folder = "../uploads/" . $filename;
            $docType = $_POST['doc_type'];

            if (move_uploaded_file($tempname, $folder)) {
                $sql = "INSERT INTO Document (App_Num, Link, Doc_Type) VALUES ($appNum, '$filename', '$docType')";
                if (mysqli_query($conn, $sql)) {
                    $message = "File uploaded successfully";
                } else {
                    $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            } else {
                $message = "Failed to upload file";
            }
        } else {
            $message = "Invalid application number or not authorized";
        }
    } else {
        $message = "Application number must be a number or document type not set";
    }
} else {
    $message = "No file selected or incorrect request method";
}

// Delete Document
if (isset($_GET['delete'])) {
    $docNum = $_GET['delete'];
    $sql = "DELETE FROM Document WHERE Doc_Num = $docNum";
    if (mysqli_query($conn, $sql)) {
        $message = "Document deleted successfully";
    } else {
        $message = "Error deleting document: " . mysqli_error($conn);
    }
}

// Fetch Documents for a specific user
$sql = "SELECT Document.* FROM Document 
        INNER JOIN Applications ON Document.App_Num = Applications.App_Num 
        WHERE Applications.UIN = $uin";

$documents = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management</title>
    <link rel="stylesheet" href="../css/styles.css" /> <!-- Ensure correct path to style.css -->
</head>

<body>
    <header>
        <h1>Document Management</h1>
        <div class="header-links"><a href="../index.php" class="button">Back to Home</a></div>
    </header>
    <div class="container">
        <div class="content">
            <!-- Upload Document Section -->
            <div class="new-program-form">
                <h2>Upload Document</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="input-label">
                        <input type="file" name="document" required>
                    </div>
                    <div class="input-label">
                        <input type="text" name="app_num" placeholder="Application Number" required>
                    </div>
                    <div class="input-label">
                        <input type="text" name="doc_type" placeholder="Document Type" required>
                    </div>
                    <input type="submit" value="Upload">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Display Uploaded Documents -->
            <section>
                <h2>Uploaded Documents</h2>
                <table>
                    <tr>
                        <th>Document Number</th>
                        <th>Application Number</th>
                        <th>Document Link</th>
                        <th>Document Type</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($documents) > 0) {
                        while ($row = mysqli_fetch_assoc($documents)) {
                            echo "<tr>
                            <td>" . $row['Doc_Num'] . "</td>
                            <td>" . $row['App_Num'] . "</td>
                            <td><a href='../uploads/" . $row['Link'] . "' target='_blank'>" . $row['Link'] . "</a></td>
                            <td>" . $row['Doc_Type'] . "</td>
                            <td><a href='?delete=" . $row['Doc_Num'] . "'>Delete</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No documents found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>

</body>

</html>