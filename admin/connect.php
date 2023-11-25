<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['submit'])){
        $conn = mysqli_connect('localhost', 'root', '', 'csce310db') or die('Connection failed: ' . mysqli_connect_error());
        if(isSet($_POST['name']) && isSet($_POST['description'])){
            $name = $_POST['name'];
            $description = $_POST['description'];
            $sql = "INSERT INTO `programs` (`name`, `description`) VALUES ('$name', '$description')";
            if(mysqli_query($conn, $sql)){
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }
?>