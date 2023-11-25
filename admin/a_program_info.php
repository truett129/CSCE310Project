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
    
    <form action="connect.php" method="POST">
        <label for="name">Program Name</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Program Description</label>
        <input type="text" name="description" id="description" required>

        <input type="submit" name='submit' value="Submit">
    </form>

</body>
</html>