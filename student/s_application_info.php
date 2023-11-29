<?php
    include_once '../database.php';
    $result = mysqli_query($conn,"SELECT Program_Num, Name FROM programs");

    // insert
    if(isSet($_POST['uin']) && isSet($_POST['program-num']) && isSet($_POST['purpose-statement'])){
        $uin = $_POST['uin'];
        $program_num = $_POST['program-num'];
        $uncom_cert = $_POST['uncom-cert'];
        $com_cert = $_POST['com-cert'];
        $purpose_statement = $_POST['purpose-statement'];
        $sql = "INSERT INTO `applications` (`uin`, `program_num`, `uncom_cert`, `com_cert`, `purpose_statement`) VALUES ('$uin', '$program_num', '$uncom_cert', '$com_cert', '$purpose_statement')";
        if(mysqli_query($conn, $sql)){
            $message = "New record created successfully";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
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
    <title>Student Application Information</title>
</head>
<body>
    <h1>Student Application Information</h1>
    <div class="student-application-form">
        <h2>New Application Form</h2>
        <form action="" method="POST">
            <div class="input-label">
                <label for="uin">UIN</label>
                <input type="text" name="uin" id="uin" required>
            </div>
            <div class="input-label">
                <label for="program-num">Program Name</label>
                <select name="program-num" id="program-num" required>
                    <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['Program_Num'] . "'>" . $row['Name'] . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>
    
            <div class="input-label">
                <label for="uncom-cert">Are you currently enrolled in
                other uncompleted certifications
                sponsored by the Cybersecurity
                Center? (Leave blank if no)</label>
                <input type="text" name="uncom-cert" id="uncom-cert">
            </div>
    
            <div class="input-label">
                <label for="com-cert">Have you completed any
                cybersecurity industry
                certifications via the
                Cybersecurity Center? (Leave blank if no)</label>
                <input type="text" name="com-cert" id="com-cert">
            </div>
    
            <div class="input-label">
                <label for="purpose-statement">Purpose Statement</label>
                <input type="text" name="purpose-statement" id="purpose-statement" required>
            </div>

    
            <input type="submit" name='submit' value="Submit">
        </form>
        <?php if(!empty($message)): ?>
            <div class="message">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>
    <h2>My Applications</h2>
    <table>
        <tr>
            <th>Program Name</th>
            <th>Status</th>
            <th>Update</th>
            <th>Select</th>
            <th>Delete</th>
        </tr>
        <?php
            $userUIN = 230002124;
            $result = mysqli_query($conn, "SELECT applications.*, programs.Name FROM applications INNER JOIN programs ON applications.Program_Num = programs.Program_Num WHERE applications.uin = $userUIN");
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo "<tr>
                    <td>" . $row['Name'] . "</td>
                    <td>N/A</td>
                    <td><a href='update_application.php?program_num=" . $row['Program_Num'] . "&uin=" . $userUIN . "'>Update</a></td>
                    <td><a href='select_application.php?program_num=" . $row['Program_Num'] . "&uin=" . $userUIN . "'>Select</a></td>
                    <td><a href='delete_application.php?program_num=" . $row['Program_Num'] . "&uin=" . $userUIN . "'>Delete</a></td>
                    </tr>";
                }
            }
            else{
                echo "<tr><td colspan='4'>No applications found</td></tr>";
            }
        ?>
    </table>
    <button><a href="s_application_info.php">Refresh</a></button>
</body>
</html>