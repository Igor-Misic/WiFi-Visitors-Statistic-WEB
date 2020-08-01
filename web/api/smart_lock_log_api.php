<?php
// API for collecting MAC addresses
include_once 'database_config.php';
include_once 'auth_handler.php';

$verified =  isVerified();

if($verified)
{
    $conn = openDbConn();
    
    if ($conn->connect_errno)
    {
      exit('Can\'t connect : '. $conn->connect_error);
    }

    $user_id = $_GET["id"];
    $user = $_GET["user"];
    if ($conn->query("INSERT INTO smart_lock_log (user_id, user) VALUES ('$user_id', '$user')") === TRUE)
    {
        echo "New record created successfully<br>";
    }
    else
    {
        echo "Error: INSERT INTO smart_lock_log (user_id, user) VALUES ('$user_id', '$user') <br>" . $conn->error."<br>";
    }
    $conn->close();
}
?>
