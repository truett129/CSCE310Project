<?php
/**
* This is the admin page for deleting new programs. Users can:
*   Delete existing programs
*   Set programs as inactive and have them not populate in the dropdown menu
*   
*   
* @author     Anthony Ciardelli
* ...
*/

session_start();

if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'admin') {
    die("Access denied: User not logged in or not an admin.");
}
include_once '../database.php';
if (count($_POST) > 0) {
    if (isset($_POST['delete'])) {
        if ($_POST['delete'] === 'Set Inactive') {
            mysqli_query($conn, "UPDATE programs SET Is_Active = 0 WHERE Program_Num='" . $_GET['Program_Num'] . "'");
            $message = "Program marked as inactive";
        } elseif ($_POST['delete'] === 'Delete') {
            mysqli_query($conn, "DELETE FROM programs WHERE Program_Num='" . $_GET['Program_Num'] . "'");
            $message = "Program deleted successfully";
        } elseif ($_POST['delete'] === 'Set Active') {
            mysqli_query($conn, "UPDATE programs SET Is_Active = 1 WHERE Program_Num='" . $_GET['Program_Num'] . "'");
            $message = "Program marked as active";
        }
    }
}
$result = mysqli_query($conn, "SELECT * FROM programs WHERE Program_Num='" . $_GET['Program_Num'] . "'");
if($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
}
else{
    $row = ['Is_Active' => 0];
    $message = "Program has been deleted";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include '../css/styles.css'; ?>
    </style>
    <title>Update Program Information</title>
</head>

<body>
    <header>
        <h1>Admin Program Information</h1>
        <div class="header-links"><a href="a_program_info.php" class="button">Back to Programs</a></div>
    </header>
    <div class="container">
        <div class="content">
            <div class="new-program-form">
                <h2>Update Program Information</h2>
                <form action="" method="POST">
                    <label for="Program_Num">Program Number</label>
                    <input type="text" name="Program_Num" id="Program_Num" value="<?php echo isset($row['Program_Num']) ? $row['Program_Num'] : ''; ?>"
                        readonly>

                    <label for="Name">Program Name</label>
                    <input type="text" name="Name" id="Name" value="<?php echo isset($row['Name']) ? $row['Name'] : ''; ?>" readonly>

                    <label for="Description">Program Description</label>
                    <input type="text" name="Description" id="Description" value="<?php echo isset($row['Description']) ? $row['Description'] : ''; ?>"
                        readonly>

                    <!-- Soft delete form -->
                    <form action="" method="POST">
                    <?php
                        if ($row['Is_Active'] == 1) {
                            echo '<input class="button" name="delete" type="submit" value="Set Inactive">';
                        } else {
                            echo '<input class="button" name="delete" type="submit" value="Set Active">';
                        }
                        ?>
                    </form>

                    <!-- Hard delete form -->
                    <form action="" method="POST">
                        <input class="button" name="delete" type="submit" value="Delete">
                    </form>
                </form>
                <p>
                    <?php if (isset($message))
                        echo $message; ?>
                </p>
            </div>
        </div>
    </div>
</body>

</html>