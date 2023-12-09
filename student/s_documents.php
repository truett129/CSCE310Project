<?php

/**
 * Document Upload and Management Page
 * 
 * This PHP script is designed for the management of documents by students within Texas A&M Cybersecurity system. 
 * It enables students to upload, update, view, and delete their documents such as resumes and other relevant files.
 * 
 * Functionalities:
 * a. Insert: Allows students to upload documents for program opportunities.
 *    - Process: Students select a document and upload it to the server. The document's details are stored in the database.
 * 
 * b. Update: Enables students to replace or edit details of their uploaded documents.
 *    - Process: Students select an existing document and can either update its details or replace the file itself.
 * 
 * c. Select: Provides students the ability to view a list of their uploaded documents.
 *    - Process: Fetches and displays documents uploaded by the student from the database.
 * 
 * d. Delete: Offers the option to remove a specific document from the system.
 *    - Process: Deletes the selected document's record from the database and the file from the server, if applicable.
 * 
 * @file        s_documents.php
 * @author      Abdullah Balbaid
 * @package     student
 * 
 * Dependencies: 
 * - Requires 'database.php' for database connection.
 * - 'styles.css' for page styling.
 * Security Note: 
 * - Utilizes session-based authentication to ensure only authorized students can manage documents.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure the user is logged in and is a student
if(!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'student') {
    die("Access denied: User not logged in or not a student.");
}

// Ensure the user has a UIN set in the session
if(!isset($_SESSION['UIN'])) {
    die("User not logged in or UIN not set.");
}

$uin = $_SESSION['UIN'];

include_once '../database.php';

$message = '';

// Check for Update Request
$updateDoc = null;
if(isset($_GET['update'])) {
    $docNum = $_GET['update'];
    $updateSql = "SELECT * FROM Document WHERE Doc_Num = $docNum";
    $updateResult = mysqli_query($conn, $updateSql);
    if(mysqli_num_rows($updateResult) > 0) {
        $updateDoc = mysqli_fetch_assoc($updateResult);
    }
}

// Process File Upload or Update
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update Document
    if(isset($_POST['doc_num']) && is_numeric($_POST['doc_num'])) {
        $docNum = $_POST['doc_num'];
        $docType = $_POST['doc_type'];
        $appNum = $_POST['app_num'];

        // Initialize SQL query for update
        $updateSql = "UPDATE Document SET Doc_Type = '$docType', App_Num = '$appNum'";

        // Check if a new file is uploaded
        if(isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
            $filename = $_FILES['document']['name'];
            $tempname = $_FILES['document']['tmp_name'];
            $folder = "../uploads/".$filename;

            // Move the new file and update the SQL query
            if(move_uploaded_file($tempname, $folder)) {
                $updateSql .= ", Link = '$filename'";
            } else {
                $message = "Failed to upload new file.";
            }
        }

        // Finalize and execute the update query
        $updateSql .= " WHERE Doc_Num = $docNum";
        if(mysqli_query($conn, $updateSql)) {
            $message = "Document updated successfully";
        } else {
            $message = "Error updating document: ".mysqli_error($conn);
        }
    }
    // Upload New Document
    elseif(isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        if(isset($_POST['app_num']) && is_numeric($_POST['app_num']) && isset($_POST['doc_type'])) {
            $appNum = $_POST['app_num'];
            $docType = $_POST['doc_type'];
            $filename = $_FILES['document']['name'];
            $tempname = $_FILES['document']['tmp_name'];
            $folder = "../uploads/".$filename;

            if(move_uploaded_file($tempname, $folder)) {
                $sql = "INSERT INTO Document (App_Num, Link, Doc_Type) VALUES ($appNum, '$filename', '$docType')";
                if(mysqli_query($conn, $sql)) {
                    $message = "File uploaded successfully";
                } else {
                    $message = "Error: ".$sql."<br>".mysqli_error($conn);
                }
            } else {
                $message = "Failed to upload file";
            }
        } else {
            $message = "Application number must be a number, or document type not set";
        }
    }
}

// Delete Document
if(isset($_GET['delete'])) {
    $docNum = $_GET['delete'];
    $sql = "DELETE FROM Document WHERE Doc_Num = $docNum";
    if(mysqli_query($conn, $sql)) {
        $message = "Document deleted successfully";
    } else {
        $message = "Error deleting document: ".mysqli_error($conn);
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
    <link rel="stylesheet" href="../css/styles.css" />
</head>

<body>
    <header>
        <h1>Document Management</h1>
        <div class="header-links"><a href="../index.php" class="button">Back to Home</a></div>
    </header>
    <div class="container">
        <div class="content">
            <!-- Upload/Update Document Section -->
            <div class="new-program-form">
                <h2>
                    <?php echo isset($updateDoc) ? 'Update Document' : 'Upload Document'; ?>
                </h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <?php if(isset($updateDoc)): ?>
                        <input type="hidden" name="doc_num" value="<?php echo $updateDoc['Doc_Num']; ?>">
                    <?php endif; ?>

                    <div class="input-label">
                        <input type="file" name="document" <?php echo isset($updateDoc) ? '' : 'required'; ?>>
                    </div>
                    <div class="input-label">
                        <label for="app_num">Application Number</label>
                        <select name="app_num" id="app_num" required>
                            <?php
                            $result = mysqli_query($conn, "SELECT App_Num, Name FROM ApplicationsProgramsView WHERE UIN = $uin");
                            if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    $isSelected = isset($updateDoc) && $updateDoc['App_Num'] == $row['App_Num'] ? 'selected' : '';
                                    echo "<option value='".$row['App_Num']."' $isSelected>".$row['App_Num']." - ".$row['Name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-label">
                        <input type="text" name="doc_type" placeholder="Document Type" required
                            value="<?php echo isset($updateDoc) ? $updateDoc['Doc_Type'] : ''; ?>">
                    </div>
                    <input type="submit" class="button" value="<?php echo isset($updateDoc) ? 'Update' : 'Upload'; ?>">
                </form>
                <?php if(!empty($message)): ?>
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
                    if(mysqli_num_rows($documents) > 0) {
                        while($row = mysqli_fetch_assoc($documents)) {
                            echo "<tr>
                                <td>".$row['Doc_Num']."</td>
                                <td>".$row['App_Num']."</td>
                                <td><a href='../uploads/".$row['Link']."' target='_blank'>".$row['Link']."</a></td>
                                <td>".$row['Doc_Type']."</td>
                                <td>
                                    <a href='?update=".$row['Doc_Num']."'>Update</a> |
                                    <a href='?delete=".$row['Doc_Num']."'>Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No documents found</td></tr>";
                    }
                    ?>
                </table>
            </section>

            <?php if(isset($updateDoc)): ?>
                <a href="s_documents.php" class="button">upload new document</a>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>