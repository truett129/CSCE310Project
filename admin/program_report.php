<?php
    include_once '../database.php';

    // basic program information
    $result = mysqli_query($conn, "SELECT * FROM programs WHERE Program_Num='" . $_GET['Program_Num'] . "'");
    $row= mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include '../css/styles.css'; ?>
        < !-- Ensure the path is correct -->
    </style>
    <title>Update Program Information</title>
</head>
<body>
    <header>
        <h1>Admin Program Information</h1>
        <div class="header-links"><a href="a_program_info.php" class="button">Back to Programs</a></div>
    </header>
    <div class="container">
        <div class="content">
            <div class="program-report">
                <h2>Program Report</h2>
                <h3>Program Info</h3>
                <p>Program Number: <?php echo $row['Program_Num'] ?></p>
                <p>Name: <?php echo $row['Name'] ?></p>
                <p>Description: <?php echo $row['Description'] ?></p>
                <h3>Enrollment Information</h3>
                <?php
                    $programNumber = $_GET['Program_Num'];

                    $enrollmentQuery = "SELECT COUNT(*) AS total_students,
                    SUM(CASE WHEN Gender = 'Male' THEN 1 ELSE 0 END) AS male_count,
                    SUM(CASE WHEN Gender = 'Female' THEN 1 ELSE 0 END) AS female_count,
                    SUM(CASE WHEN Hispanic_Latino = 1 THEN 1 ELSE 0 END) AS hispanic_latino_count,
                    SUM(CASE WHEN First_Generation = 1 THEN 1 ELSE 0 END) AS first_gen_count
                    FROM College_Student CS
                    INNER JOIN Track T ON CS.UIN = T.Student_Num
                    WHERE T.Program_Num = $programNumber";

                    $result = mysqli_query($conn, $enrollmentQuery);
                    $row= mysqli_fetch_array($result);

                ?>
                <p>Total Students: <?php echo $row['total_students'] ?></p>
                <p>Male Students: <?php echo $row['male_count'] ?></p>
                <p>Female Students: <?php echo $row['female_count'] ?></p>
                <p>Hispanic or Latino Students: <?php echo $row['hispanic_latino_count'] ?></p>
                <p>First Generation Students: <?php echo $row['first_gen_count'] ?></p>
            </div>
        </div>
    </div>
</body>
</html>