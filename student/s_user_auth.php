<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure the user is logged in and is a student
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'student') {
    die("Access denied: User not logged in or not a student.");
}

include_once '../database.php'; // Adjust the path as needed

$message = '';
$error = '';
$uin = $_SESSION['UIN']; // Assuming UIN is stored in session upon login

// Fetch the logged-in student's user and college student information
$userSql = "SELECT * FROM Users WHERE UIN = '$uin'";
$collegeStudentSql = "SELECT * FROM College_Student WHERE UIN = '$uin'";

$userResult = mysqli_query($conn, $userSql);
$collegeStudentResult = mysqli_query($conn, $collegeStudentSql);

if (!$userResult || !$collegeStudentResult) {
    die("Error fetching user information.");
}

$userData = mysqli_fetch_assoc($userResult);
$collegeStudentData = mysqli_fetch_assoc($collegeStudentResult);

// Handle User Information Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $firstName = $_POST['first_name'];
    $mInitial = $_POST['m_initial'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $discordName = $_POST['discord_name'];

    // Prepare statement for user update
    $stmt = $conn->prepare("UPDATE Users SET First_Name = ?, M_Initial = ?, Last_Name = ?, Email = ?, Discord_Name = ? WHERE UIN = ?");
    $stmt->bind_param("sssssi", $firstName, $mInitial, $lastName, $email, $discordName, $uin);

    if ($stmt->execute()) {
        $message .= "User information updated successfully. ";
    } else {
        $error .= "Error updating user information: " . $stmt->error;
    }
    $stmt->close();
}

// Handle College Student Information Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_college_student'])) {
    $gender = $_POST['gender'];
    $race = $_POST['race'];
    $major = $_POST['major'];
    $minor1 = $_POST['minor_1'];
    $minor2 = $_POST['minor_2'];
    $school = $_POST['school'];
    $phone = $_POST['phone'];

    // Prepare statement for college student update
    $stmt = $conn->prepare("UPDATE College_Student SET Gender = ?, Race = ?, Major = ?, Minor_1 = ?, Minor_2 = ?, School = ?, Phone = ? WHERE UIN = ?");
    $stmt->bind_param("sssssssi", $gender, $race, $major, $minor1, $minor2, $school, $phone, $uin);

    if ($stmt->execute()) {
        $message .= "College student information updated successfully.";
    } else {
        $error .= "Error updating college student information: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch updated user information if profile was just updated
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($message)) {
    $userResult = mysqli_query($conn, $userSql);
    $userData = mysqli_fetch_assoc($userResult);

    $collegeStudentResult = mysqli_query($conn, $collegeStudentSql);
    $collegeStudentData = mysqli_fetch_assoc($collegeStudentResult);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="../css/styles.css" />
</head>

<body>
    <header>
        <h1>Student Profile</h1>
        <div class="header-links">
            <a href="../index.php" class="button">Back to Home</a>
        </div>
    </header>

    <div class="container">
        <div class="content">
            <!-- Profile Update Form -->
            <section>
                <h2>Profile Information</h2>
                <form action="" method="POST">
                    <input type="hidden" name="update_profile" value="1">
                    <label>First Name: <input type="text" name="first_name"
                            value="<?php echo isset($userData['First_Name']) ? htmlspecialchars($userData['First_Name']) : ''; ?>"></label><br>
                    <label>Middle Initial: <input type="text" name="m_initial"
                            value="<?php echo htmlspecialchars($userData['M_Initial']); ?>"></label><br>
                    <label>Last Name: <input type="text" name="last_name"
                            value="<?php echo htmlspecialchars($userData['Last_Name']); ?>"></label><br>
                    <label>Email: <input type="email" name="email"
                            value="<?php echo htmlspecialchars($userData['Email']); ?>"></label><br>
                    <label>Discord Name: <input type="text" name="discord_name"
                            value="<?php echo htmlspecialchars($userData['Discord_Name']); ?>"></label><br>
                    <input type="submit" name="update_profile" value="Update Profile" class="button">

                </form>
            </section>

            <!-- College Student Information Update Form -->
            <section>
                <h2>College Student Information</h2>
                <form action="" method="POST">
                    <input type="hidden" name="update_college_student" value="1">

                    <!-- Add all fields as per the College_Student table -->
                    <input type="hidden" name="update_college_student" value="1">
                    <label>Gender:
                        <select name="gender">
                            <option value="Female" <?php echo isset($collegeStudentData['Gender']) && $collegeStudentData['Gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                            <option value="Male" <?php echo isset($collegeStudentData['Gender']) && $collegeStudentData['Gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        </select>
                    </label><br>
                    <label>Hispanic/Latino:
                        <input type="checkbox" name="hispanic_latino" value="1" <?php echo $collegeStudentData['Hispanic_Latino'] ? 'checked' : ''; ?>>
                    </label><br>
                    <label>Race: <input type="text" name="race"
                            value="<?php echo isset($collegeStudentData['Race']) ? htmlspecialchars($collegeStudentData['Race']) : ''; ?>"></label><br>
                    <label>US Citizen:
                        <input type="checkbox" name="us_citizen" value="1" <?php echo $collegeStudentData['US_Citizen'] ? 'checked' : ''; ?>>
                    </label><br>
                    <label>First Generation:
                        <input type="checkbox" name="first_generation" value="1" <?php echo $collegeStudentData['First_Generation'] ? 'checked' : ''; ?>>
                    </label><br>
                    <label>Date of Birth: <input type="date" name="dob"
                            value="<?php echo isset($collegeStudentData['DoB']) ? htmlspecialchars($collegeStudentData['DoB']) : ''; ?>"></label><br>
                    <label>GPA: <input type="text" name="gpa"
                            value="<?php echo isset($collegeStudentData['GPA']) ? htmlspecialchars($collegeStudentData['GPA']) : ''; ?>"></label><br>
                    <label>Major: <input type="text" name="major"
                            value="<?php echo isset($collegeStudentData['Major']) ? htmlspecialchars($collegeStudentData['Major']) : ''; ?>"></label><br>
                    <label>Minor 1: <input type="text" name="minor_1"
                            value="<?php echo isset($collegeStudentData['Minor_1']) ? htmlspecialchars($collegeStudentData['Minor_1']) : ''; ?>"></label><br>
                    <label>Minor 2: <input type="text" name="minor_2"
                            value="<?php echo isset($collegeStudentData['Minor_2']) ? htmlspecialchars($collegeStudentData['Minor_2']) : ''; ?>"></label><br>
                    <label>Expected Graduation: <input type="number" name="expected_graduation"
                            value="<?php echo isset($collegeStudentData['Expected_Graduation']) ? htmlspecialchars($collegeStudentData['Expected_Graduation']) : ''; ?>"></label><br>
                    <label>School: <input type="text" name="school"
                            value="<?php echo isset($collegeStudentData['School']) ? htmlspecialchars($collegeStudentData['School']) : ''; ?>"></label><br>
                    <label>Classification: <input type="text" name="classification"
                            value="<?php echo isset($collegeStudentData['Classification']) ? htmlspecialchars($collegeStudentData['Classification']) : ''; ?>"></label><br>
                    <label>Phone: <input type="tel" name="phone"
                            value="<?php echo isset($collegeStudentData['Phone']) ? htmlspecialchars($collegeStudentData['Phone']) : ''; ?>"></label><br>
                    <label>Student Type: <input type="text" name="student_type"
                            value="<?php echo isset($collegeStudentData['Student_Type']) ? htmlspecialchars($collegeStudentData['Student_Type']) : ''; ?>"></label><br>

                    <input type="submit" value="Update College Information" class="button">
                </form>
            </section>


            <!-- Account Deactivation -->
            <section>
                <h2>Account Settings</h2>
                <a href="?deactivate" class="button">Deactivate Account</a>
            </section>
        </div>
    </div>

    <!-- Display Messages -->
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
</body>

</html>