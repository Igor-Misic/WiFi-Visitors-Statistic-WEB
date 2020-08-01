<?php
// API for collecting MAC addresses
include_once '../database_config.php';
include_once '../update_carbon.php';
include_once 'auth_handler.php';

$verified =  isVerified();

if($verified)
{
    $conn = openDbConn();

    if ($conn->connect_errno)
    {
    exit('Can\'t connect : '. $conn->connect_error);
    }

    $temperature = $_GET["temperature"];

    updateCarbon("hacklab.temperature", $temperature);

    if ($conn->query("UPDATE temperature SET temperature='$temperature', ts=current_timestamp WHERE id =1") === TRUE)
    {
        echo "New record created successfully<br>";

    }
    else
    {
        echo "UPDATE temperature SET temperature='$temperature' WHERE id = 1 <br>" . $conn->error."<br>";
    }

    $conn->close();
}
?>
