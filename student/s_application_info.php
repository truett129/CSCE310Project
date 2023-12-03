<?php
session_start();

// Ensure the user is logged in and has a UIN set in the session
if (!isset($_SESSION['UIN'])) {
    die("User not logged in or UIN not set");
}

$userUIN = $_SESSION['UIN'];

include_once '../database.php';

// Fetch programs for dropdown
$result = mysqli_query($conn, "SELECT Program_Num, Name FROM programs");

$message = '';

// Insert new application
if (isset($_POST['submit'])) {
    $uin = $userUIN;  // Use the UIN from the session
    $program_num = $_POST['program-num'];
    $uncom_cert = $_POST['uncom-cert'];
    $com_cert = $_POST['com-cert'];
    $purpose_statement = $_POST['purpose-statement'];

    $sql = "INSERT INTO `applications` (`uin`, `program_num`, `uncom_cert`, `com_cert`, `purpose_statement`) VALUES ('$uin', '$program_num', '$uncom_cert', '$com_cert', '$purpose_statement')";
    if (mysqli_query($conn, $sql)) {
        $message = "New record created successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Fetch only the applications belonging to the logged-in user
$applicationResult = mysqli_query($conn, "SELECT applications.*, programs.Name FROM applications INNER JOIN programs ON applications.Program_Num = programs.Program_Num WHERE applications.uin = $userUIN");
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
    <title>Student Application Information</title>
</head>

<body>
    <header>
        <h1>Student Application Information</h1>
        <div class="header-links"><a href="../index.php" class="button">Back to Home</a></div>
    </header>
    <div class="container">
        <div class="content">
            <div class="form-container">
                <h2>New Application Form</h2>
                <form action="" method="POST">
                    <div class="input-label">
                        <input type="hidden" name="uin" id="uin" value="<?php echo $userUIN; ?>" required>
                    </div>
                    <div class="input-label">
                        <label for="program-num">Program Name</label>
                        <select name="program-num" id="program-num" required>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['Program_Num'] . "'>" . $row['Name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-label">
                        <label for="uncom-cert">Uncompleted Certifications</label>
                        <input type="text" name="uncom-cert" id="uncom-cert">
                    </div>
                    <div class="input-label">
                        <label for="com-cert">Completed Certifications</label>
                        <input type="text" name="com-cert" id="com-cert">
                    </div>
                    <div class="input-label">
                        <label for="purpose-statement">Purpose Statement</label>
                        <textarea name="purpose-statement" id="purpose-statement" required></textarea>
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
                <h2>My Applications</h2>
                <table>
                    <tr>
                        <th>Program Name</th>
                        <th>Status</th>
                        <th>Update</th>
                        <th>Select</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($applicationResult) > 0) {
                        while ($row = mysqli_fetch_assoc($applicationResult)) {
                            echo "<tr>
                            <td>" . $row['Name'] . "</td>
                            <td>N/A</td>
                            <td><a href='update_application.php?program_num=" . $row['Program_Num'] . "&uin=" . $userUIN . "'>Update</a></td>
                            <td><a href='select_application.php?program_num=" . $row['Program_Num'] . "&uin=" . $userUIN . "'>Select</a></td>
                            <td><a href='delete_application.php?program_num=" . $row['Program_Num'] . "&uin=" . $userUIN . "'>Delete</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No applications found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>

</html>