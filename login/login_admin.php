<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once '../database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];


    $sql = "SELECT * FROM Users WHERE Username = '$username'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);


        if ($password == $user['Passwords']) {

            $_SESSION['userLoggedIn'] = true;
            $_SESSION['UIN'] = $user['UIN'];
            $_SESSION['userRole'] = $user['User_Type'];
            header('Location: index.php');
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
    <link rel="stylesheet" href="../css/styles.css" />
</head>

<body>
    <header>
        <h1>Texas A&M Cybersecurity</h1>
    </header>

    <div class="form-container">
	<h2 style = "text-align: center; padding-bottom: 20px;" >Admin Login</h2>
        <form action="" method="POST">
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-group">
                <input type="submit" value="Login" class="button">
            </div>
            <div class="input-group">
                <?php if ($message != ''): ?>
                    <p class="error-message">
                        <?php echo $message; ?>
                    </p>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>

</html>