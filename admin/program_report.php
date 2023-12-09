<?php

session_start();

$programNumber = $_GET['Program_Num'];

if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 'admin') {
    die("Access denied: User not logged in or not an admin.");
}
include_once '../database.php';

// basic program information
$result = mysqli_query($conn, "SELECT * FROM programs WHERE Program_Num='" . $_GET['Program_Num'] . "'");
$row = mysqli_fetch_array($result);
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
                <p>Program Number:
                    <?php echo $row['Program_Num'] ?>
                </p>
                <p>Name:
                    <?php echo $row['Name'] ?>
                </p>
                <p>Description:
                    <?php echo $row['Description'] ?>
                </p>
                <h3>Enrollment Information</h3>
                <?php

                $programParticipation = "SELECT * FROM Program_Participation_Details WHERE Program_Num = $programNumber";

                $result = mysqli_query($conn, $programParticipation);
                $row = mysqli_fetch_array($result);

                ?>
                <p>Total Students:
                    <?php echo $row['Total_Students'] ?>
                </p>
                <p>Minority Students:
                    <?php echo $row['Total_Minority'] ?>
                </p>
                <p>K12 Students:
                    <?php echo $row['K12_Students'] ?>
                </p>
                <h3>Student Course Information</h3>
                <?php

                function $allCourses($status){
                    $allCoursesCompleted = "SELECT COUNT(DISTINCT CE.UIN) AS Num_Students_Completed_All_Courses
                    FROM Class_Enrollment CE
                    INNER JOIN Classes C ON CE.Class_ID = C.Class_ID
                    WHERE CE.Status = $status 
                        AND CE.UIN IN (
                            SELECT CS.UIN
                            FROM College_Student CS
                            INNER JOIN Track T ON CS.UIN = T.Student_Num
                            WHERE T.Program_Num = $programNum
                        )";
                
                    $result = mysqli_query($conn, $allCoursesCompleted);
                
                    $row = mysqli_fetch_array($result);
                }

                
                ?>
                <p>Students Completed All Courses:
                    <?php echo $row['All_Courses_Completed'] ?>
                </p>
                <?php

                $courseEnrollmentQuery = "SELECT * FROM Course_Certification_Details WHERE Program_Num = $programNumber";

                $result = mysqli_query($conn, $courseEnrollmentQuery);
                $row = mysqli_fetch_array($result);
                ?>
                <p>Students in Foreign Language Courses:
                    <?php echo $row['Students_Foreign_Language'] ?>
                </p>
                <p>Students in Cryptography:
                    <?php echo $row['Students_Cryptography'] ?>
                </p>
                <p>Students in Data Science:
                    <?php echo $row['Students_Data_Science'] ?>
                </p>
                <h3>Students in DoD 8570.01M</h3>
                <?php 
                $studentsEnrolled = "SELECT COUNT(DISTINCT CE.UIN) AS Enrolled_Students
                FROM Class_Enrollment CE
                JOIN Classes C ON CE.Class_ID = C.Class_ID
                WHERE C.Name = 'DoD 8570.01M Preparation Course' AND CE.Status = 'In-Progress';
                ";

                $studentsCompleted = "SELECT COUNT(DISTINCT CE.UIN) AS Completed_Students
                FROM Class_Enrollment CE
                JOIN Classes C ON CE.Class_ID = C.Class_ID
                WHERE C.Name = 'DoD 8570.01M Preparation Course' AND CE.Status = 'Completed'";

                $studentsCertified = "SELECT COUNT(DISTINCT CE.UIN) AS Certified_Students
                FROM Cert_Enrollment CE
                JOIN Certification C ON CE.Cert_ID = C.Cert_ID
                WHERE C.Name = 'DoD 8570.01M Certification Examination' AND CE.Status = 'Completed'";

                $result = mysqli_query($conn, $studentsEnrolled);
                $row = mysqli_fetch_array($result);
                echo "<p>Students Enrolled: " . $row['Enrolled_Students'] . "</p>";

                $result = mysqli_query($conn, $studentsCompleted);
                $row = mysqli_fetch_array($result);
                echo "<p>Students Completed: " . $row['Completed_Students'] . "</p>";

                $result = mysqli_query($conn, $studentsCertified);
                $row = mysqli_fetch_array($result);
                echo "<p>Students Certified: " . $row['Certified_Students'] . "</p>";
                ?>
                <h3>Student Internship Information</h3>
                <?php

                $studentsFedInternship = "SELECT COUNT(DISTINCT IA.UIN) AS Students_Pursuing_Internships
                FROM Intern_App IA
                JOIN Internship I ON IA.Intern_ID = I.Intern_ID
                JOIN Track T ON IA.UIN = T.Student_Num
                WHERE T.Program_Num = $programNumber AND I.Is_Gov = TRUE;
                ";

                $studentMajors = "SELECT DISTINCT CS.Major
                FROM College_Student CS
                JOIN Track T ON CS.UIN = T.Student_Num
                WHERE T.Program_Num = $programNumber;
                ";

                $studentLocations = "SELECT DISTINCT I.Location
                FROM Internship I
                JOIN Intern_App IA ON I.Intern_ID = IA.Intern_ID
                JOIN Track T ON IA.UIN = T.Student_Num
                WHERE T.Program_Num = $programNumber";


                $result = mysqli_query($conn, $studentsFedInternship);
                $row = mysqli_fetch_array($result);
                echo "<p>Students Pursuing Federal Internships: " . $row['Students_Pursuing_Internships'] . "</p>";

                $result = mysqli_query($conn, $studentMajors);
                echo "<p>Student Majors: ";
                while ($row = mysqli_fetch_array($result)) {
                    echo $row['Major'] . ", ";
                }
                echo "</p>";

                $result = mysqli_query($conn, $studentLocations);
                echo "<p>Student Locations: ";
                while ($row = mysqli_fetch_array($result)) {
                    echo $row['Location'] . ", ";
                }
                echo "</p>";
                ?>
            </div>
        </div>
    </div>
</body>

</html>