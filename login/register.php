<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../database.php'; // Adjust the path as needed

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract and sanitize input
    $uin = mysqli_real_escape_string($conn, $_POST['uin']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $m_initial = mysqli_real_escape_string($conn, $_POST['m_initial']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $discord_name = mysqli_real_escape_string($conn, $_POST['discord_name']);
    $user_type = "student";

    // Check if the UIN already exists
    $checkUinSql = "SELECT UIN FROM Users WHERE UIN = '$uin'";
    $checkUinResult = mysqli_query($conn, $checkUinSql);
    if (mysqli_num_rows($checkUinResult) > 0) {
        $message = "Error: UIN already in use.";
    } else {
        // SQL to insert new user, now including the UIN
        $sql = "INSERT INTO Users (UIN, First_Name, M_Initial, Last_Name, Username, Passwords, User_Type, Email, Discord_Name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "issssssss", $uin, $first_name, $m_initial, $last_name, $username, $password, $user_type, $email, $discord_name);

        //adding user into college student table
        $sql2 = "INSERT INTO College_Student(UIN) VALUES (?)";

        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "i", $uin);

        if (mysqli_stmt_execute($stmt) && mysqli_stmt_execute($stmt2)) {
            $message = "Registration successful!";
        } else {
            $message = "Error: " . mysqli_error($conn);
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
    <title>Register - Texas A&M Cybersecurity</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <header>
        <h1>Texas A&M Cybersecurity - Registration</h1>
        <div class="header-links"><a href="login_student.php" class="button">Back to Student Login</a></div>
    </header>

    <div class="form-container">
        <form action="" method="POST">
            <div class="input-group">
                <label for="uin">UIN</label>
                <input type="text" name="uin" id="uin" required>
            </div>
            <div class="input-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" required>
            </div>
            <div class="input-group">
                <label for="m_initial">Middle Initial</label>
                <input type="text" name="m_initial" id="m_initial">
            </div>
            <div class="input-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" required>
            </div>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="input-group">
                <label for="discord_name">Discord Name</label>
                <input type="text" name="discord_name" id="discord_name">
            </div>
            <div class="input-group">
                <input type="submit" value="Register" class="button">
            </div>
        </form>
        <?php if (!empty($message)): ?>
            <div class="message">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>