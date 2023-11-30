<?php
    include_once '../database.php';
    if(count($_POST)>0){
        mysqli_query($conn, "UPDATE applications SET uin='" . $_POST['uin'] . "', program_num='" . $_POST['program-num'] . "', uncom_cert='" . $_POST['uncom-cert'] . "', com_cert='" . $_POST['com-cert'] . "', purpose_statement='" . $_POST['purpose-statement'] . "' WHERE uin='" . $_POST['uin'] . "'");
        $message = "Record Modified Successfully";
    }
    $result = mysqli_query($conn, "SELECT * FROM applications WHERE uin='" . $_GET['uin'] . "'");
    $row= mysqli_fetch_array($result);
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
    <h1>Update Application</h1>
    <div class="student-application-form">
        <h2>New Application Form</h2>
        <form action="" method="POST">
            <div class="input-label">
                <label for="uin">UIN</label>
                <input type="text" name="uin" id="uin" value=<?php echo $_GET['uin'] ?> readonly>
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
</body>
</html>