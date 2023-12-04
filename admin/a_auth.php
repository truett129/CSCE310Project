<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'admin') {
    die("Access denied: User not logged in or not an admin.");
}

include_once '../database.php'; // Adjust the path as needed

$message = '';
$error = '';

$editingUser = null;
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['edit'])) {
    $uinToEdit = $_GET['edit'];
    $editSql = "SELECT * FROM Users WHERE UIN = '$uinToEdit'";
    $editResult = mysqli_query($conn, $editSql);
    if (mysqli_num_rows($editResult) > 0) {
        $editingUser = mysqli_fetch_assoc($editResult);
    } else {
        $error = "User not found.";
    }
}


// Handle User Insertion, Update, and Deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    // Handle Insert and Update
    if ($action == 'insert' || $action == 'update') {
        $uin = $_POST['uin'];
        $firstName = $_POST['first_name'];
        $mInitial = $_POST['m_initial'];
        $lastName = $_POST['last_name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $userType = $_POST['user_type'];
        $email = $_POST['email'];
        $discordName = $_POST['discord_name'];
        $sql2 = "";

        if ($action == 'insert') {
            // $password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO Users (UIN, First_Name, M_Initial, Last_Name, Username, Passwords, User_Type, Email, Discord_Name) VALUES ('$uin', '$firstName', '$mInitial', '$lastName', '$username', '$password', '$userType', '$email', '$discordName')";
            if ($userType == 'student'){
                $sql2 = "INSERT INTO College_Student (UIN) VALUES ('$uin')";
            }
        } else {
            $sql = "UPDATE Users SET First_Name='$firstName', M_Initial='$mInitial', Last_Name='$lastName', Username='$username', User_Type='$userType', Email='$email', Discord_Name='$discordName' WHERE UIN='$uin'";
            if (!empty($password)) {
                // $password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE Users SET First_Name='$firstName', M_Initial='$mInitial', Last_Name='$lastName', Username='$username', Passwords='$password', User_Type='$userType', Email='$email', Discord_Name='$discordName' WHERE UIN='$uin'";
                
            }
            if ($userType == 'admin') {
                $sql2 = "DELETE FROM College_Student WHERE UIN = '$uin'";

            } else {
                $checkSql = "SELECT * FROM College_Student WHERE UIN = '$uin'";

                $result = mysqli_query($conn, $checkSql);

                if ($result->num_rows == 0) {
                    // SIN does not exist, so insert the record
                    // Prepare your insert query with the appropriate columns and values
                    $sql2 = "INSERT INTO College_Student (UIN) VALUES ('$uin')";
                }
            }
        }

        if (mysqli_query($conn, $sql)) {
            $message = "User " . ($action == 'insert' ? "added" : "updated") . " successfully";
            if (!empty($sql2)) {
                try {
                    mysqli_query($conn, $sql2);
                } catch (Exception $e) {
                    $error = "Database error: " . mysqli_error($conn);
                }
            }
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }

    // Handle Delete
    if ($action == 'delete') {
        $uin = $_POST['uin'];
        $deleteType = $_POST['delete_type'];

        if ($deleteType == 'soft') {
            // Soft delete: Set Is_Deleted to TRUE or set Deleted_At to current datetime
            $sql = "UPDATE Users SET Is_Deleted = TRUE WHERE UIN = '$uin'";
            // For datetime: $sql = "UPDATE Users SET Deleted_At = NOW() WHERE UIN = '$uin'";
        } else {
            // Hard delete: Remove the user record from the database
            $sql = "DELETE FROM Users WHERE UIN = '$uin'";
        }

        if (mysqli_query($conn, $sql)) {
            $message = "User " . ($deleteType == 'soft' ? "soft-deleted" : "hard-deleted") . " successfully";
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }
}

// Handle Soft and Hard Delete Requests
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['soft_delete'])) {
        $uin = $_GET['soft_delete'];
        $sql = "UPDATE Users SET Is_Deleted = TRUE WHERE UIN = '$uin'";
        if (mysqli_query($conn, $sql)) {
            $message = "User soft-deleted successfully";
        } else {
            $error = "Error soft-deleting user: " . mysqli_error($conn);
        }
    } elseif (isset($_GET['hard_delete'])) {
        $uin = $_GET['hard_delete'];
        $sql = "DELETE FROM Users WHERE UIN = '$uin'";
        if (mysqli_query($conn, $sql)) {
            $message = "User hard-deleted successfully";
        } else {
            $error = "Error hard-deleting user: " . mysqli_error($conn);
        }
    }

    // Reactivation should be a separate condition, not nested inside hard_delete
    if (isset($_GET['reactivate'])) {
        $uin = $_GET['reactivate'];
        $sql = "UPDATE Users SET Is_Deleted = FALSE WHERE UIN = '$uin'";
        if (mysqli_query($conn, $sql)) {
            $message = "User reactivated successfully";
        } else {
            $error = "Error reactivating user: " . mysqli_error($conn);
        }
    }
}



// Fetch Users for Display
$sql = "SELECT * FROM Users";
$users = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="../css/styles.css" /> <!-- Ensure correct path to style.css -->
</head>

<body>
    <header>
        <h1>User Management</h1>
        <div class="header-links"><a href="../index.php" class="button">Back to Home</a></div>
    </header>

    <div class="container">
        <div class="content">

            <!-- User Form -->
            <div class="new-program-form">
                <h2>
                    <?php echo $editingUser ? "Update" : "Create"; ?> User
                </h2>
                <form action="" method="POST">
                    <input type="hidden" name="action" value="<?php echo $editingUser ? "update" : "insert"; ?>">
                    <div class="input-label">
                        <input type="text" name="uin" placeholder="UIN" value="<?php echo $editingUser['UIN'] ?? ''; ?>"
                            required>
                    </div>
                    <div class="input-label">
                        <input type="text" name="first_name" placeholder="First Name"
                            value="<?php echo $editingUser['First_Name'] ?? ''; ?>" required>
                    </div>
                    <div class="input-label">
                        <input type="text" name="m_initial" placeholder="Middle Initial"
                            value="<?php echo $editingUser['M_Initial'] ?? ''; ?>">
                    </div>
                    <div class="input-label">
                        <input type="text" name="last_name" placeholder="Last Name"
                            value="<?php echo $editingUser['Last_Name'] ?? ''; ?>" required>
                    </div>
                    <div class="input-label">
                        <input type="text" name="username" placeholder="Username"
                            value="<?php echo $editingUser['Username'] ?? ''; ?>" required>
                    </div>
                    <div class="input-label">
                        <input type="password" name="password" placeholder="Password" <?php echo $editingUser ? '' : 'required'; ?>>
                    </div>
                    <div class="input-label">
                        <label for="user_type">User Type</label>
                        <select name="user_type" id="user_type" required>
                            <option value="admin" <?php echo (isset($editingUser) && $editingUser['User_Type'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="student" <?php echo (isset($editingUser) && $editingUser['User_Type'] == 'student') ? 'selected' : ''; ?>>Student</option>
                        </select>
                    </div>

                    <div class="input-label">
                        <input type="email" name="email" placeholder="Email"
                            value="<?php echo $editingUser['Email'] ?? ''; ?>" required>
                    </div>
                    <div class="input-label">
                        <input type="text" name="discord_name" placeholder="Discord Name"
                            value="<?php echo $editingUser['Discord_Name'] ?? ''; ?>">
                    </div>
                    <input type="submit" value="<?php echo $editingUser ? "Update" : "Create"; ?> User" class="button">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="error">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
            </div>



            <!-- Display Users -->
            <section>
                <h2>Users</h2>
                <table>
                    <tr>
                        <th>UIN</th>
                        <th>First Name</th>
                        <th>Middle Initial</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>User Type</th>
                        <th>Email</th>
                        <th>Discord Name</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($users) > 0) {
                        while ($row = mysqli_fetch_assoc($users)) {
                            $softDeleteAction = !$row['Is_Deleted'] ? "<a href='?soft_delete=" . $row['UIN'] . "'>Soft Delete</a>" : "";
                            $reactivateAction = $row['Is_Deleted'] ? "<a href='?reactivate=" . $row['UIN'] . "'>Reactivate</a>" : "";
                            $hardDeleteAction = "<a href='?hard_delete=" . $row['UIN'] . "'>Hard Delete</a>";

                            echo "<tr>
            <td>" . $row['UIN'] . "</td>
            <td>" . $row['First_Name'] . "</td>
            <td>" . $row['M_Initial'] . "</td>
            <td>" . $row['Last_Name'] . "</td>
            <td>" . $row['Username'] . "</td>
            <td>" . $row['User_Type'] . "</td>
            <td>" . $row['Email'] . "</td>
            <td>" . $row['Discord_Name'] . "</td>
            <td>
                <a href='?edit=" . $row['UIN'] . "'>Edit</a> |
                $hardDeleteAction |
                $softDeleteAction
                $reactivateAction
            </td>
            </tr>";
                        }
                    }
                    ?>
                </table>
            </section>

            <div class="switch-mode">
                <?php if ($editingUser): ?>
                    <a href="a_auth.php" class="button">Create New User</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>