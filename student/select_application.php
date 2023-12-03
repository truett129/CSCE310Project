<?php

session_start();

// Ensure the user is logged in and is an student
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'student') {
    die("Access denied: User not logged in or not an student.");
}
include_once '../database.php';

$result = mysqli_query($conn, "SELECT * FROM applications WHERE App_Num='" . $_GET['app_num'] . "'");
if ($result) {
    $row = mysqli_fetch_assoc($result);
}
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
    <title>Document</title>
</head>

<body>
    <header>
        <h1>Student Application Information</h1>
        <div class="header-links"><a href="./s_application_info.php" class="button">Back to Applications</a></div>
    </header>
    <div class="container">
        <div class="content">
            <div class="form-container">
                <h2>View Application</h2>
                <div class="student-application-form">
                    <form>
                        <div class="input-label">
                            <label for="uin">UIN</label>
                            <input type="text" name="uin" id="uin" value=<?php echo $row['UIN'] ?> readonly>
                        </div>
                        <div class="input-label">
                            <label for="program-name">Program Name</label>
                            <input type="text" name="program-name" id="program-name" value="<?php
                            $programNum = $row['Program_Num'];
                            $query = "SELECT Name FROM programs WHERE Program_Num = '$programNum'";
                            $result = mysqli_query($conn, $query);

                            if ($result) {
                                $row = mysqli_fetch_assoc($result);

                                if ($row) {
                                    echo htmlspecialchars($row['Name']);
                                } else {
                                    echo 'No program found';
                                }
                            } else {
                                echo 'Error fetching program';
                            }
                            ?>" readonly required>

                            </select>
                        </div>
                        <?php
                        $appNum = $_GET['app_num'];
                        $result = mysqli_query($conn, "SELECT * FROM applications WHERE App_Num='$appNum'");
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                        }
                        ?>

                        <div class="input-label">
                            <label for="uncom-cert">Are you currently enrolled in
                                other uncompleted certifications
                                sponsored by the Cybersecurity
                                Center? (Leave blank if no)</label>
                            <input type="text" name="uncom-cert" id="uncom-cert"
                                value="<?php echo isset($row['Uncom_Cert']) ? $row['Uncom_Cert'] : ''; ?>" readonly>
                        </div>

                        <div class="input-label">
                            <label for="com-cert">Have you completed any
                                cybersecurity industry
                                certifications via the
                                Cybersecurity Center? (Leave blank if no)</label>
                            <input type="text" name="com-cert" id="com-cert"
                                value="<?php echo isset($row['Com_Cert']) ? $row['Com_Cert'] : ''; ?>" readonly>
                        </div>

                        <div class="input-label">
                            <label for="purpose-statement">Purpose Statement</label>
                            <input type="text" name="purpose-statement" id="purpose-statement"
                                value="<?php echo isset($row['Purpose_Statement']) ? $row['Purpose_Statement'] : ''; ?>"
                                readonly>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>