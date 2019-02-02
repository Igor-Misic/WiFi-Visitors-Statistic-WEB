<?php
// API for collecting MAC addresses
include 'database_config.php';

//key must be same as in bash script
$result = $conn->query("SELECT psk FROM preshared_key WHERE name='api_psk'");

if ($result->num_rows > 0)
{
    // output data of each row
    while($row = $result->fetch_assoc())
    {
        $key = $row["psk"];
    }
}
else
{
    echo "0 results";
}

$validator = $_GET["validator"];
$date = $_GET["date"];
$user_id = "";
$user = "";

$totalSpentTime = '';

$calculated_validator = hash_hmac ('sha1', $date, $key);

if(!is_string($validator) || strlen($validator) < 1)
{
    echo "We don't take kindly of your kind around here! <br>";
}
elseif(strcmp($calculated_validator,$validator) === 0)
{
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
}
else echo "Validator is wrong";

$conn->close();
?>
