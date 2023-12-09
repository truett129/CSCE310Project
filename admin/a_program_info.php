<?php

session_start();

if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'admin') {
    die("Access denied: User not logged in or not an admin.");
}


include_once '../database.php';
$result = mysqli_query($conn, "SELECT * FROM programs");

// insert
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    if (isset($_POST['name']) && isset($_POST['description'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $sql = "INSERT INTO `programs` (`name`, `description`) VALUES ('$name', '$description')";
        if (mysqli_query($conn, $sql)) {
            $message = "New record created successfully";
            $result = mysqli_query($conn, "SELECT * FROM programs");
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Program Information</title>
    <link rel="stylesheet" href="../css/styles.css" /> <!-- Ensure the path to style.css is correct -->
</head>

<body>
    <header>
        <h1>Admin Program Information</h1>
        <div class="header-links"><a href="../index.php" class="button">Back to Home</a></div>
    </header>

    <div class="container">
        <div class="content">
            <div class="new-program-form">
                <h2>Add New Program</h2>
                <form action="" method="POST">
                    <div class="input-label">
                        <label for="name">Program Name</label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div class="input-label">
                        <label for="description">Program Description</label>
                        <textarea type="text" name="description" id="description" required></textarea>
                    </div>
                    <input type="submit" name='submit' value="Submit" class="button">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <section>
                <h2>Current Programs</h2>
                <table>
                    <tr>
                        <th>Program Number</th>
                        <th>Program Name</th>
                        <th>Program Description</th>
                        <th>Status</th>
                        <th>Generate Report</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                    <td>" . $row['Program_Num'] . "</td>
                    <td>" . $row['Name'] . "</td>
                    <td>" . $row['Description'] . "</td>
                    <td>" . ($row['Is_Active'] == 1 ? 'Active' : 'Inactive') . "</td>
                    <td><a href='program_report.php?Program_Num=" . $row['Program_Num'] . "'>Generate Report</a></td>
                    <td><a href='update_program.php?Program_Num=" . $row['Program_Num'] . "'>Update</a></td>
                    <td><a href='delete_program.php?Program_Num=" . $row['Program_Num'] . "'>Delete</a></td>
                    </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No programs found</td></tr>";
                    }
                    ?>
                </table>
                </table>
            </section>
        </div>
    </div>
</body>

</html>