<?php

session_start();

// Ensure the user is logged in and is an student
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'student') {
    die("Access denied: User not logged in or not an student.");
}
include_once '../database.php';
if (count($_POST) > 0) {
    mysqli_query($conn, "UPDATE applications SET Uncom_Cert='" . $_POST['uncom_cert'] . "', Com_Cert='" . $_POST['com_cert'] . "', Purpose_Statement='" . $_POST['purpose_statement'] . "' WHERE Program_Num='" . $_GET['program_num'] . "' AND UIN='" . $_GET['uin'] . "'");
    $message = "Record Modified Successfully";
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
                <h2>Update Application</h2>
                <div class="student-application-form">
                    <form action="" method="POST">
                        <div class="input-label">
                            <label for="uin">UIN</label>
                            <input type="text" name="uin" id="uin" value=<?php echo $_GET['uin'] ?> readonly>
                        </div>
                        <div class="input-label">
                            <label for="program-name">Program Name</label>
                            <input type="text" name="program_name" id="program_name" value="<?php

                            $programNum = mysqli_real_escape_string($conn, $_GET['program_num']);
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
                        $uin = $_GET['uin'];
                        $programNum = $_GET['program_num'];
                        $result = mysqli_query($conn, "SELECT * FROM applications WHERE uin=$uin AND program_num=$programNum");
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                        }
                        ?>

                        <div class="input-label">
                            <label for="uncom_cert">Are you currently enrolled in
                                other uncompleted certifications
                                sponsored by the Cybersecurity
                                Center? (Leave blank if no)</label>
                            <input type="text" name="uncom_cert" id="uncom_cert"
                                value="<?php echo isset($row['Uncom_Cert']) ? $row['Uncom_Cert'] : ''; ?>">
                        </div>

                        <div class="input-label">
                            <label for="com_cert">Have you completed any
                                cybersecurity industry
                                certifications via the
                                Cybersecurity Center? (Leave blank if no)</label>
                            <input type="text" name="com_cert" id="com_cert"
                                value="<?php echo isset($row['Com_Cert']) ? $row['Com_Cert'] : ''; ?>">
                        </div>

                        <div class="input-label">
                            <label for="purpose_statement">Purpose Statement</label>
                            <input type="text" name="purpose_statement" id="purpose_statement"
                                value="<?php echo isset($row['Purpose_Statement']) ? $row['Purpose_Statement'] : ''; ?>">
                        </div>
                        <input class="button" type="submit" name='submit' value="Submit">
                        <p>
                            <?php if (isset($message))
                                echo $message; ?>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>