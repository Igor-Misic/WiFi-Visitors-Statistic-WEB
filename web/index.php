<link rel="stylesheet" type="text/css" href="style.css">
<?php
include 'database_config.php';
include 'menu.php';

$counter = 1;
echo "<img align=\"center\" width = 60% src=http://cromish.com:81/render/?width=1100&height=308&vtitle=Broj%20WiFi%20uredaja&target=hacklab.LabOS&yMin=0&yStep=2&from=-24hours&title=24%20hours&xFormat=%25H%3A%25M&tz=Europe%2FZagreb>";
echo 
"<table align=\"left\">
	<tr>
		<th>No:</th>
		<th>Nick:</th>
		<th>WiFi card company:</th>";
if (admin())
{
	echo "<th>Mac Address:</th>";
}
echo "	</tr>";
	
	
	
	// Get cURL resource
$curl = curl_init();

$device = "";

function checkTokenCookie($password)
{
        if(isset($_COOKIE["TOKEN"]) and isset($_COOKIE["TIME"])) 
        {       $token = $_COOKIE["TOKEN"];
                $time = $_COOKIE["TIME"];
                $calculated_token = hash_hmac ('sha1', $time, $password);
                if ($calculated_token === $token) return true;
        }
        else return false;
}

function admin()
{
	if (!isset($_COOKIE["TOKEN"])) return;
	$username = $_COOKIE["USERNAME"];
	global $conn;
	$result = $conn->query("SELECT password FROM login_data WHERE username = '$username'");

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$password = $row["password"];  
		}
	} else {
		echo "No username in table<br>";
	}
	return checkTokenCookie($password);
}

if ($result = $conn->query("SELECT * FROM wifi_online_users")) {
	while ($row=$result->fetch_row())
	{	
		$macAddress = $row[1];
		if ($result_wifi_users = $conn->query("SELECT * FROM wifi_registered_users WHERE macAddress = '$macAddress'"))
		{
			$row_wifi_users=$result_wifi_users->fetch_row();
			if ($row_wifi_users[2] != "")
				$nick_name = $row_wifi_users[2];
			else
				$nick_name = "Gost";
				
			if ($row_wifi_users[3] != "")
				$device = $row_wifi_users[3];
			else {
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://www.macvendorlookup.com/api/v2/{'.$macAddress.'}',
					CURLOPT_USERAGENT => 'Codular Sample cURL Request'
				));
				// Send the request & save response to $resp
				$json = curl_exec($curl);
				$obj = json_decode($json);
				
				$device = $obj[0]->{'company'};
			}
		}
		echo "<tr><td>".$counter."</td>";
		echo "<td>".$nick_name."</td>";
		echo "<td>".$device."</td>";
		if (admin())
		{
			echo "<td>".$macAddress."</td>";
		}
		echo "</tr>";
		
		$counter++;
	}	
echo "</table>";
echo "<br><br>";
echo "<img align=\"left\" width=100% src=http://cromish.com:81/render/?width=1920&height=308&vtitle=Broj%20WiFi%20uredaja&target=hacklab.LabOS&yMin=0&yStep=2&from=-168hours&title=7%20days&tz=Europe%2FZagreb>";
}
else echo "Can't SELECT";

$userTime = "menu";
// total time
echo "<br><table class=$userTime align=\"left\">
	<tr>
		<th class=$userTime>No:</th>
		<th class=$userTime>Nick:</th>
		<th class=$userTime><a href=\"?day=total\">Total time</a></th>
		<th class=$userTime><a href=\"?day=day0\">Today</a></th>
		<th class=$userTime><a href=\"?day=day1\">Yesterday</a></th>
		<th class=$userTime><a href=\"?day=day2\">Two days ago</a></th>
		<th class=$userTime><a href=\"?day=day3\">Three days ago</a></th>
		<th class=$userTime><a href=\"?day=day4\">Four days ago</a></th>
		<th class=$userTime><a href=\"?day=day5\">Five days ago</a></th>
		<th class=$userTime><a href=\"?day=day6\">Six days ago</a></th>
		<th class=$userTime><a href=\"?day=day7\">Seven days ago</a></th>
	</tr>";
	
$counter = 1;

// switch prevent SQL injection
$orderBy = "totalSpentTime";
if (!isset($_GET["day"])) $_GET["day"] = '';
switch ($_GET["day"]) {
    case "total":
        $orderBy = "totalSpentTime";
        break;
    case "day0":
        $orderBy = "day0";
        break;
    case "day1":
        $orderBy = "day1";
        break;
	case "day2":
        $orderBy = "day2";
        break;
	case "day3":
        $orderBy = "day3";
        break;
	case "day4":
        $orderBy = "day4";
        break;
	case "day5":
        $orderBy = "day5";
        break;
	case "day6":
        $orderBy = "day6";
        break;
	case "day7":
        $orderBy = "day6";
        break;
		
}
if ($result = $conn->query("SELECT * FROM user_time ORDER BY ($orderBy+0) DESC")) {
	while ($row=$result->fetch_row())
	{	
		$macAddress = $row[1];
		$totalTime = $row[2];
		$day0 = $row[3];
		$day1 = $row[4];
		$day2 = $row[5];
		$day3 = $row[6];
		$day4 = $row[7];
		$day5 = $row[8];
		$day6 = $row[9];
		$day7 = $row[10];
		if ($result_wifi_users = $conn->query("SELECT * FROM wifi_registered_users WHERE macAddress = '$macAddress'"))
		{
			$row_wifi_users=$result_wifi_users->fetch_row();
			if ($row_wifi_users[2] != "")
				$nick_name = $row_wifi_users[2];
			else
				$nick_name = "Gost";
		}
		
		echo "<tr><td class=$userTime>".$counter."</td>";
		echo "<td class=$userTime>".$nick_name."</td>";
		if ($totalTime >= 60 and $totalTime < 1440 )
			echo "<td class=$userTime>".((int)($totalTime/60))."h ".($totalTime%60)."min</td>";
		else if ($totalTime >= 1440)
			echo "<td class=$userTime>".((int)($totalTime/1440))."d ".((int)(($totalTime%1440)/60))."h ".($totalTime%60)."min</td>";
		else 
			echo "<td class=$userTime>".$totalTime." min</td>";
			
		//day0
		if ($day0 >= 60)
			echo "<td class=$userTime>".((int)($day0/60))."h ".($day0%60)."min</td>\n";
		else 	
			echo "<td class=$userTime>".$day0." min</td>\n";
		//day1
		if ($day1 >= 60)
			echo "<td class=$userTime>".((int)($day1/60))."h ".($day1%60)."min</td>\n";
		else 	
			echo "<td class=$userTime>".$day1." min</td>\n";
		//day2
		if ($day2 >= 60)
			echo "<td class=$userTime>".((int)($day2/60))."h ".($day2%60)."min</td>\n";
		else 	
			echo "<td class=$userTime>".$day2." min</td>\n";
		//day3
		if ($day3 >= 60)
			echo "<td class=$userTime>".((int)($day3/60))."h ".($day3%60)."min</td>\n";
		else 	
			echo "<td class=$userTime>".$day3." min</td>\n";	
		//day4
		if ($day4 >= 60)
			echo "<td class=$userTime>".((int)($day4/60))."h ".($day4%60)."min</td>\n";
		else 	
			echo "<td class=$userTime>".$day4." min</td>\n";	
		//day5
		if ($day5 >= 60)
			echo "<td class=$userTime>".((int)($day5/60))."h ".($day5%60)."min</td>\n";
		else 	
			echo "<td class=$userTime>".$day5." min</td>\n";	
		//day6
		if ($day6 >= 60)
			echo "<td class=$userTime>".((int)($day6/60))."h ".($day6%60)."min</td>\n";
		else 	
			echo "<td class=$userTime>".$day6." min</td>\n";	
		//day7
		if ($day7 >= 60)
			echo "<td class=$userTime>".((int)($day7/60))."h ".($day7%60)."min</td>\n";
		else 	
			echo "<td class=$userTime>".$day7." min</td>\n";	
		echo "</tr>";
		
		$counter++;
	}
	echo "</table>";	
}
else echo "Can't SELECT";




$conn->close();
curl_close($curl);
?>
