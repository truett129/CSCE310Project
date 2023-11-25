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
        else if(isSet($_POST['uin']) && isSet($_POST['program-name']) && isSet($_POST['purpose-statement'])){
            $uin = $_POST['uin'];
            $program_name = $_POST['program-name'];
            $uncom_cert = $_POST['uncom-cert'];
            $com_cert = $_POST['com-cert'];
            $purpose_statement = $_POST['purpose-statement'];
            $sql = "INSERT INTO `applications` (`uin`, `program_name`, `uncom_cert`, `com_cert`, `purpose_statement`) VALUES ('$uin', '$program_name', '$uncom_cert', '$com_cert', '$purpose_statement')";
            if(mysqli_query($conn, $sql)){
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }
?>