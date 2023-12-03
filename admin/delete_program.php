<?php
    include_once '../database.php';
    if (count($_POST) > 0) {
        if (isset($_POST['delete'])) {
            if ($_POST['delete'] === 'Set Inactive') {
                // Soft delete: set is_active attribute to false
                mysqli_query($conn, "UPDATE programs SET is_active = 0 WHERE Program_Num='" . $_GET['Program_Num'] . "'");
                $message = "Program marked as inactive";
            } elseif ($_POST['delete'] === 'Delete') {
                // Hard delete: remove the program entirely
                mysqli_query($conn, "DELETE FROM programs WHERE Program_Num='" . $_GET['Program_Num'] . "'");
                $message = "Program deleted successfully";
            }
            elseif ($_POST['delete'] === 'Set Active') {
                // Soft delete: set is_active attribute to false
                mysqli_query($conn, "UPDATE programs SET is_active = 1 WHERE Program_Num='" . $_GET['Program_Num'] . "'");
                $message = "Program marked as active";
            }
        }
    }
    $result = mysqli_query($conn, "SELECT * FROM programs WHERE Program_Num='" . $_GET['Program_Num'] . "'");
    $row= mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include '../css/styles.css'; ?>
        < !-- Ensure the path is correct -->
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
                    <input type="text" name="Program_Num" id="Program_Num" value="<?php echo $row['Program_Num']; ?>" readonly>

                    <label for="Name">Program Name</label>
                    <input type="text" name="Name" id="Name" value="<?php echo $row['Name']; ?>" readonlu>

                    <label for="Description">Program Description</label>
                    <input type="text" name="Description" id="Description" value="<?php echo $row['Description']; ?>" readonly>

                    <!-- Soft delete form -->
                    <form action="" method="POST">
                    <?php
                        if ($row['is_active'] == 1) {
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
                <p><?php if(isset($message)) echo $message; ?></p>
            </div>
        </div>
    </div>
</body>
</html>