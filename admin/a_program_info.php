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
            <th>Program Name</th>
            <th>Program Description</th>
            <th>Update</th>
        </tr>
        <?php
            $conn = mysqli_connect('localhost', 'root', '', 'csce310db') or die('Connection failed: ' . mysqli_connect_error());
            $sql = "SELECT * FROM `programs`";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo "<tr><td>" . $row['Name'] . "</td><td>" . $row['Description'] . "</td><td><a href='update_program.php?name=" . $row['Name'] . "'>Update</a></td></tr>";
                }
            }
        ?>
    </table>

</body>
</html>