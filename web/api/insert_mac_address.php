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
    $conn->close();
}
?>
