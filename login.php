<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); // Start the session at the beginning of the script
include_once './database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Prevent SQL injection
    $password = $_POST['password'];

    // Check user in the database
    $sql = "SELECT * FROM Users WHERE Username = '$username'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify password
        if ($password == $user['Passwords']) {
            // Login successful
            $_SESSION['userLoggedIn'] = true;
            $_SESSION['username'] = $user['Username'];
            $_SESSION['userRole'] = $user['User_Type']; // Store user role in session
            header('Location: index.php'); // Redirect to index.php or another suitable page
            exit();
        } else {
            $message = "Incorrect password";
        }
    } else {
        $message = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="css/styles.css" /> <!-- Ensure correct path to style.css -->
</head>

<body>
    <header>
        <h1>Texas A&M Cybersecurity</h1>
        <div class="header-links"><a href="register.php" class="button">Register</a></div>
    </header>
    <div class="form-container"> <!-- Use this class for form styling -->
        <form action="" method="POST">
            <div class="input-group"> <!-- Grouping each input for better styling and spacing -->
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-group">
                <input type="submit" value="Login" class="button"> <!-- Using 'button' class for styling -->
            </div>
        </form>
    </div>
</body>

</html>