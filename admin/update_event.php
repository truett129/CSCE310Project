<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$uin = $_SESSION['UIN'];

// Ensure the user is logged in and is an admin
if(!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'admin') {
    die("Access denied: User not logged in or not an admin.");
}

if (!isset($_GET['event_id']) || !$_GET['event_id']) {
    die("Invalid request!");
}

include_once '../database.php'; // Adjust the path as needed

$message = '';
$error = '';

$event_id = $_GET['event_id'];

$sql = "SELECT * FROM Event_Tracking WHERE Event_ID='$event_id'";
$events = mysqli_query($conn, $sql);


// Handle Event Deletion
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete'])) {
    $UIN = $_GET['delete'];
    
    $deleteSql = "DELETE FROM Event_Tracking WHERE UIN=$UIN";
    if(mysqli_query($conn, $deleteSql)) {
        $message = "User deleted successfully";
        $sql = "SELECT * FROM Event_Tracking WHERE Event_ID='$event_id'";
        $events = mysqli_query($conn, $sql);
    } else {
        $error = "Database error: ".mysqli_error($conn);
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (!isset($_POST['action'])) {
        die("Invalid action!");
    }
    $act = $_POST['action'];
    if ($act == 'attendance') {
        $user = $_POST['UIN'];
        $user_exists = mysqli_query($conn, "SELECT * FROM Event_Tracking WHERE UIN='$user'");

        if (mysqli_num_rows($user_exists) == 0) {
            $insert = "INSERT INTO Event_Tracking (Event_ID, UIN) VALUES('$event_id', '$user')";
            if(mysqli_query($conn, $insert)) {
                $message = "User added successfully";
            } else {
                $error = "Database error: ".mysqli_error($conn);
            }

            $sql = "SELECT * FROM Event_Tracking WHERE Event_ID='$event_id'";
            $events = mysqli_query($conn, $sql);
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
    <title>Event Management</title>
    <link rel="stylesheet" href="../css/styles.css" /> <!-- Ensure correct path to style.css -->
</head>

<body>
    
    <header>
        <h1>Update Event</h1>
        <div class="header-links"><a href="./a_event.php" class="button">Back to event management</a></div>
    </header>

    <div class="container">
        <div class="content">

            <div class="new-program-form">
                <h2>Update Event Attendance</h2>
                <form action="" method="POST">
                    <input type="hidden" name="action" value="attendance">
                    <div class="input-label">
                        <label for="UIN">User</label>
                        <select name="UIN" id="UIN" required>
                            <?php
                            $result = mysqli_query($conn, "SELECT * FROM Users WHERE User_Type='student';");
                            if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='".$row['UIN']."'>".$row['First_Name']." ".$row['Last_Name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <input type="submit" value="Submit Event" class="button">
                </form>
                <?php if(!empty($message)): ?>
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Display Events -->
            <section>
                <h2>Current Event</h2>
                <table>
                    <tr>
                        <th>ET_Num</th>
                        <th>Event_ID</th>
                        <th>Name</th>
                        <th>UIN</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($events) > 0) {
                        while($row = mysqli_fetch_assoc($events)) {
                            $u = $row['UIN'];
                            $name = mysqli_query($conn, "SELECT * FROM Users WHERE UIN='$u'");
                            if(mysqli_num_rows($name) > 0) {
                                $namerow = mysqli_fetch_assoc($name);
                                echo "<tr>
                                <td>".$row['ET_Num']."</td>
                                <td>".$row['Event_ID']."</td>
                                <td>".$namerow['First_Name']." ".$namerow['Last_Name']."</td>
                                <td>".$row['UIN']."</td>
                                <td>
                        <a href='?event_id=".$event_id."&delete=".$row['UIN']."'>Delete</a> 
                    </td>
                                </tr>";
                            }
                            
                        }
                    } else {
                        echo "<tr><td colspan='5'>No students found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>

</html>