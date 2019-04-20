<?php

$server = "localhost"; 

$user = "exhibition";        

$password = "BLUHpR4t442BsLw4M72i8RJM9xSq6yz";

$db = "exhibition";

 

// error_reporting(E_ALL || ~E_NOTICE); 

$conn = new mysqli($server, $user, $password, $db);

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

}

mysqli_query($conn, "SET NAMES UTF8");

?>