<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once './database.php';

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
    <link rel="stylesheet" href="css/styles.css" />
</head>

<body>
    <header>
        <h1>Texas A&M Cybersecurity</h1>
    </header>
    <div class="form-container">
        <h2 style = "text-align: center;" >Please Select Login User</h2>
            <form action="login/login_student.php" style = "padding: 20px 0 20px 0; ">
                <input type="submit" value="Student" class="button">
            </form>
            <form action="login/login_admin.php">
                <input type="submit" value="Admin" class="button">
            </form>
        </form>
    </div>
</body>

</html>