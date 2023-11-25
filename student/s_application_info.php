<?php
    include_once '../database.php';
    $result = mysqli_query($conn,"SELECT Program_Num, Name FROM programs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Application Information</title>
</head>
<body>
    <h1>Student Application Information</h1>
    <form action="../connect.php" method="POST">
        <label for="uin">UIN</label>
        <input type="text" name="uin" id="uin" required>

        <label for="program-name">Program Name</label>
        <select name="program-name" id="program-name" required>
            <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['Program_Num'] . "'>" . $row['Name'] . "</option>";
                    }
                }
            ?>
        </select>

        <label for="uncom-cert">Are you currently enrolled in
        other uncompleted certifications
        sponsored by the Cybersecurity
        Center? (Leave blank if no)</label>
        <input type="text" name="uncom-cert" id="uncom-cert">

        <label for="com-cert">Have you completed any
        cybersecurity industry
        certifications via the
        Cybersecurity Center? (Leave blank if no)</label>
        <input type="text" name="com-cert" id="com-cert">

        <label for="purpose-statement">Purpose Statement</label>
        <input type="text" name="purpose-statement" id="purpose-statement" required>

        <input type="submit" name='submit' value="Submit">
</body>
</html>