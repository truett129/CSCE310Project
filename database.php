<?php
/**
*   This page is just for connecting to the database. 
*   It is included in every page that needs to connect to the database.
*  
* @author     Anthony Ciardelli
* ...
*/

    $conn = mysqli_connect('localhost', 'root', '', 'csce310db') or die('Connection failed: ' . mysqli_connect_error());
?>