<?php
include_once '../database.php';
$result = mysqli_query($conn,"SELECT * FROM programs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Program Information</title>
</head>
<body>
    <h1>Admin Program Information</h1>
    
    <h2>Add New Program</h3>
    <form action="../connect.php" method="POST">
        <label for="name">Program Name</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Program Description</label>
        <input type="text" name="description" id="description" required>

        <input type="submit" name='submit' value="Submit">
    </form>

    <h2>Current Programs</h2>
    <table>
        <tr>
            <th>Program Number</th>
            <th>Program Name</th>
            <th>Program Description</th>
            <th>Generate Report</th>
            <th>Update</th>
        </tr>
        <?php
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo "<tr>
                    <td>" . $row['Program_Num'] . "</td>
                    <td>" . $row['Name'] . "</td>
                    <td>" . $row['Description'] . "</td>
                    <td><button class='generate-report' program-num='" . $row['Program_Num'] . "' program-name='" . $row['Name'] . "' program-description='" . $row['Description'] . "'>Generate Report</button></td>
                    <td><a href='update_program.php?Program_Num=" . $row['Program_Num'] . "'>Update</a></td>
                    </tr>";
                }
            }
            else{
                echo "<tr><td colspan='4'>No programs found</td></tr>";
            }
        ?>
    </table>

    <h2>Program Report</h2>
    <div id="report-container"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var generateReportButtons = document.querySelectorAll('.generate-report');

            generateReportButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var programNum = this.getAttribute('program-num');
                    var programName = this.getAttribute('program-name');
                    var programDescription = this.getAttribute('program-description');

                    generateReport(programNum, programName, programDescription);
                });
            });

            function generateReport(programNum, programName, programDescription) {
                var reportContainer = document.getElementById('report-container');
                reportContainer.innerHTML = "<h3>Report for Program " + programNum + "</h3>" +
                    "<p><strong>Name:</strong> " + programName + "</p>" +
                    "<p><strong>Description:</strong> " + programDescription + "</p>";
            }
        });
    </script>

</body>
</html>