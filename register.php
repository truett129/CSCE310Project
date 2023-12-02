<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once './database.php'; // Adjust the path as needed

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract and sanitize input
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $m_initial = mysqli_real_escape_string($conn, $_POST['m_initial']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $discord_name = mysqli_real_escape_string($conn, $_POST['discord_name']);

    // SQL to insert new user
    $sql = "INSERT INTO Users (First_Name, M_Initial, Last_Name, Username, Passwords, Email, Discord_Name) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $first_name, $m_initial, $last_name, $username, $password, $email, $discord_name);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Registration successful!";
    } else {
        $message = "Error: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="css/styles.css"> <!-- Ensure correct path to style.css -->
</head>

<body>
    <header>
        <h1>Texas A&M Cybersecurity - Registration</h1>
        <div class="header-links"><a href="index.php" class="button">Back to Login</a></div>
    </header>

    <div class="form-container">
        <form action="" method="POST">
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