<?php
    include_once '../database.php';
    if(count($_POST)>0){
        mysqli_query($conn, "DELETE FROM applications WHERE Program_Num='" . $_GET['program_num'] . "' AND UIN='" . $_GET['uin'] . "'");
        $message = "Record Deleted Successfully";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>View Application</h1>
    <div class="student-application-form">
        <form action="" method="POST">
            <div class="input-label">
                <label for="uin">UIN</label>
                <input type="text" name="uin" id="uin" value=<?php echo $_GET['uin'] ?> readonly>
            </div>
            <div class="input-label">
            <label for="program-name">Program Name</label>
            <input type="text" name="program-name" id="program-name" value="<?php

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
                $result = mysqli_query($conn,"SELECT * FROM applications WHERE uin=$uin AND program_num=$programNum");
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                }
            ?>
    
            <div class="input-label">
                <label for="uncom-cert">Are you currently enrolled in
                other uncompleted certifications
                sponsored by the Cybersecurity
                Center? (Leave blank if no)</label>
                <input type="text" name="uncom-cert" id="uncom-cert" value="<?php echo isset($row['Uncom_Cert']) ? $row['Uncom_Cert'] : ''; ?>" readonly>
            </div>
    
            <div class="input-label">
                <label for="com-cert">Have you completed any
                cybersecurity industry
                certifications via the
                Cybersecurity Center? (Leave blank if no)</label>
                <input type="text" name="com-cert" id="com-cert" value="<?php echo isset($row['Com_Cert']) ? $row['Com_Cert'] : ''; ?>" readonly>
            </div>
    
            <div class="input-label">
                <label for="purpose-statement">Purpose Statement</label>
                <input type="text" name="purpose-statement" id="purpose-statement" value="<?php echo isset($row['Purpose_Statement']) ? $row['Purpose_Statement'] : ''; ?>" readonly>
            </div>
            <input type="submit" name="delete" value="Delete Application?">
            <p><?php if(isset($message)) echo $message; ?></p>
        </form>
</body>
</html>