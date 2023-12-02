<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once '../database.php'; // Adjust the path as needed

$message = '';
$error = '';

// Handle Event Insertion, Update, and Deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Validate input fields
    if ($action == 'insert' || $action == 'update') {
        $uin = $_POST['uin'];
        $programNum = $_POST['program_num'];
        $startDate = $_POST['start_date'];
        $time = $_POST['time'];
        $location = $_POST['location'];
        $endDate = $_POST['end_date'];
        $eventType = $_POST['event_type'];

        if ($action == 'insert') {
            $sql = "INSERT INTO Event (UIN, Program_Num, Start_Date, Time, Location, End_Date, Event_Type) VALUES ('$uin', '$programNum', '$startDate', '$time', '$location', '$endDate', '$eventType')";
        } else {
            $eventID = $_POST['event_id'];
            $sql = "UPDATE Event SET UIN='$uin', Program_Num='$programNum', Start_Date='$startDate', Time='$time', Location='$location', End_Date='$endDate', Event_Type='$eventType' WHERE Event_ID=$eventID";
        }

        if (mysqli_query($conn, $sql)) {
            $message = "Event " . ($action == 'insert' ? "created" : "updated") . " successfully";
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }
}

// Handle Event Deletion
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete'])) {
    $eventID = $_GET['delete'];

    $deleteSql = "DELETE FROM Event WHERE Event_ID=$eventID";
    if (mysqli_query($conn, $deleteSql)) {
        $message = "Event deleted successfully";
    } else {
        $error = "Database error: " . mysqli_error($conn);
    }
}

// Fetch Events for Display
$sql = "SELECT * FROM Event";
$events = mysqli_query($conn, $sql);
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
        <h1>Event Management</h1>
        <div class="header-links"><a href="../index.php" class="button">Back to Home</a></div>
    </header>

    <div class="container">
        <div class="content">
            <!-- Event Insert Form -->
            <div class="new-program-form">
                <h2>Create/Update Event</h2>
                <form action="" method="POST">
                    <input type="hidden" name="action" value="insert">
                    <input type="hidden" name="event_id" value=""> <!-- Add event ID for update -->
                    <div class="input-label">
                        <input type="text" name="uin" placeholder="UIN" required>
                    </div>
                    <div class="input-label">
                        <input type="text" name="program_num" placeholder="Program Number" required>
                    </div>
                    <div class="input-label">
                        <input type="date" name="start_date" required>
                    </div>
                    <div class="input-label">
                        <input type="time" name="time" required>
                    </div>
                    <div class="input-label">
                        <input type="text" name="location" placeholder="Location" required>
                    </div>
                    <div class="input-label">
                        <input type="date" name="end_date" required>
                    </div>
                    <div class="input-label">
                        <input type="text" name="event_type" placeholder="Event Type" required>
                    </div>
                    <input type="submit" value="Submit Event" class="button">
                </form>
                <?php if (!empty($message)): ?>
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Display Events -->
            <section>
                <h2>Current Events</h2>
                <table>
                    <tr>
                        <th>Event ID</th>
                        <th>UIN</th>
                        <th>Program Number</th>
                        <th>Start Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>End Date</th>
                        <th>Event Type</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($events) > 0) {
                        while ($row = mysqli_fetch_assoc($events)) {
                            echo "<tr>
                                <td>" . $row['Event_ID'] . "</td>
                                <td>" . $row['UIN'] . "</td>
                                <td>" . $row['Program_Num'] . "</td>
                                <td>" . $row['Start_Date'] . "</td>
                                <td>" . $row['Time'] . "</td>
                                <td>" . $row['Location'] . "</td>
                                <td>" . $row['End_Date'] . "</td>
                                <td>" . $row['Event_Type'] . "</td>
                                <td><a href='?delete=" . $row['Event_ID'] . "'>Delete</a></td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No events found</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>

</html>