<?php
    include_once '../database.php';
    if(count($_POST)>0){
        mysqli_query($conn, "UPDATE programs SET Name='" . $_POST['Name'] . "', Description='" . $_POST['Description'] . "' WHERE Program_Num='" . $_POST['Program_Num'] . "'");
        $message = "Record Modified Successfully";
    }
    $result = mysqli_query($conn, "SELECT * FROM programs WHERE Program_Num='" . $_GET['Program_Num'] . "'");
    $row= mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Program Information</title>
</head>
<body>
    <form action="" method="POST">
        <h2>Update Program Information</h2>
        <label for="Program_Num">Program Number</label>
        <input type="text" name="Program_Num" id="Program_Num" value="<?php echo $row['Program_Num']; ?>" readonly>

        <label for="Name">Program Name</label>
        <input type="text" name="Name" id="Name" value="<?php echo $row['Name']; ?>" required>

        <label for="Description">Program Description</label>
        <input type="text" name="Description" id="Description" value="<?php echo $row['Description']; ?>" required>

        <input type="submit" name="submit" value="Submit">
    </form>
    <p><?php if(isset($message)) echo $message; ?></p>
    <a href="a_program_info.php">Back to Program Information</a>
</body>
</html>