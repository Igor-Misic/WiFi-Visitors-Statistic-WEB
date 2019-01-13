<?php
// API for collecting MAC addresses
include 'database_config.php';
include 'update_carbon.php';


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
$macArray = "";

$totalSpentTime = '';

$calculated_validator = hash_hmac ('sha1', $date, $key);

if(!is_string($validator) || strlen($validator) < 1)
{
    echo "We don't take kindly of your kind here! <br>";
}
elseif(strcmp($calculated_validator,$validator) === 0)
{
    if ($conn->connect_errno)
    {
      exit('Can\'t connect : '. $conn->connect_error);
    }
    if ($_GET["rotate"] != true)
    {
        //TRUNCAT
        if ($conn->query("TRUNCATE TABLE wifi_online_users") === TRUE)
            echo "TRUNCATE TABLE<br>";
        else
            echo "Can't TRUNCATE<br>";

        $macArray = $_GET["macArray"];
        $macArray = explode(",", $macArray);
        $numberOfUsers = 0;
        foreach ($macArray as $macAddress) {
            //echo $macAddress."<br>";
            $numberOfUsers++;

            if ($conn->query("INSERT INTO wifi_online_users (macAddress) VALUES ('$macAddress')") === TRUE)
            {
                echo "New record created successfully<br>";
            }
            else
            {
                echo "Error: INSERT INTO wifi_online_users (macAddress) VALUES ('$macAddress') <br>" . $conn->error."<br>";
            }
            // total time counter
            if ($user_time = $conn->query("SELECT totalSpentTime, day0 FROM user_time WHERE macAddress = '$macAddress'"))
            {
                $row_user_time = $user_time->fetch_row();
                $totalSpentTime = $row_user_time[0];
                $day0 = $row_user_time[1];
                $day0++;
                if ($totalSpentTime > 0) {
                    $totalSpentTime++;
                    $conn->query("UPDATE user_time SET totalSpentTime='$totalSpentTime', day0='$day0' WHERE macAddress = '$macAddress'");
                }
                else
                {
                    $conn->query("INSERT INTO user_time (totalSpentTime, macAddress, day0) VALUES (1, '$macAddress', '$day0')");
                }
            }
            else
            {
                echo "Error: SELECT totalSpentTime FROM user_time WHERE macAddress = '$macAddress' <br>" . $conn->error."<br>";
            }
        }
        updateCarbon("hacklab.LabOS", $numberOfUsers);
    }
    //rotate days
    else
    {
        if ($result = $conn->query("SELECT * FROM user_time"))
        {
            while ($row=$result->fetch_row())
            {
                $macAddress = $row[1];

                $day0 = $row[3];
                $day1 = $row[4];
                $day2 = $row[5];
                $day3 = $row[6];
                $day4 = $row[7];
                $day5 = $row[8];
                $day6 = $row[9];
                $day7 = $row[10];

                //rotation
                $day7 = $day6;
                $day6 = $day5;
                $day5 = $day4;
                $day4 = $day3;
                $day3 = $day2;
                $day2 = $day1;
                $day1 = $day0;
                $day0 = 0;

                $conn->query("UPDATE user_time SET day0='$day0', day1='$day1',day2='$day2',day3='$day3',day4='$day4',day5='$day5',day6='$day6',day7='$day7' WHERE macAddress = '$macAddress'");
            }
        }
    }
}
else echo "Validator is wrong";

$conn->close();
?>
