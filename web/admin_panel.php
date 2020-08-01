<link rel="stylesheet" type="text/css" href="style.css">

<?php
include 'database_config.php';
include 'menu.php';
include 'admin_functions.php';
include 'login_form.php';

$conn = openDbConn();

// Get cURL resource
$curl = curl_init();

if (!isset($_POST["Submit"]) && !isset($_POST["Save"]) && !isset($_POST["new_nick"]) && !isset($_COOKIE["TOKEN"]))
{
	loginForm();
}

if (isset($_POST["Save"]))
{
	$id = $_POST["id"];
	$i = 0;
	while ($i <= $id)
	{
		if (isset($_POST["nickname_$i"]))
		{
			$nickname = $_POST["nickname_$i"];
			$macAddress = $_POST["macAddress_$i"];
			if ($result_wifi_users = $conn->query("SELECT device FROM wifi_registered_users WHERE macAddress = '$macAddress'"))
			{
				$row_wifi_users=$result_wifi_users->fetch_row();
				if ($row_wifi_users[0] != "") {
					$device = $row_wifi_users[0];
				}
				else 
				{
					sleep(10);
					$url = "https://api.macvendors.com/".$macAddress;
					$ch = curl_init();
  					curl_setopt($ch, CURLOPT_URL, $url);
  					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					//header with key is not necessary but god to have
					$headers = ['Authorization: Bearer eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJhdWQiOiJtYWN2ZW5kb3JzIiwiZXhwIjoxODUxNTI1MDgwLCJpYXQiOjE1MzcwMjkwODAsImlzcyI6Im1hY3ZlbmRvcnMiLCJqdGkiOiI0MDFmNGU1YS00NTY4LTQ2ZTEtYWYzZC02NTgxNDE3ZTFlMjYiLCJuYmYiOjE1MzcwMjkwNzksInN1YiI6IjczOCIsInR5cCI6ImFjY2VzcyJ9.NzyEdVNtwWqSBg23pIr3t7z9MvBmsn3fkHd7OwlXMRVthVQcDNrxq8UJDCVmChQKVvqm5DzLLoD-KfS3u3IAug'];
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					$device = curl_exec($ch);
				}
			}
			
			if ($conn->query("UPDATE wifi_registered_users SET macAddress='$macAddress', userName='$nickname', device ='$device' WHERE id = '$i'") === FALSE)
				echo "Error: " . $sql . "<br>" . $conn->error."<br>";		
		}
		$i++;
	}
}

if (isset($_POST["new_nick"]))
{
	if ($conn->query("INSERT INTO wifi_registered_users (macAddress) VALUES ('')") === FALSE) 
		echo "Error: " . $sql . "<br>" . $conn->error."<br>";	
}

if (isset($_POST["delete"]))
{	
	$id = $_POST["id"];
	$i = 0;
	
	while ($i <= $id)
	{	
		if (isset($_POST["checkbox_$i"]) and ($_POST["checkbox_$i"]) === "Yes")
		{	
			if ($conn->query("DELETE FROM wifi_registered_users WHERE id='$i'") === FALSE) 
				echo "Error: " . $sql . "<br>" . $conn->error."<br>";
		}
		$i++;
	}
	
}

if (true === isUserLogged()) 
{
	$counter = 0;
	echo "
	<form action=\"\" method=\"POST\">
	<table class=adminPanel>
		<tr>
			<th>No:</th>
			<th>MAC Address</th>
			<th>Nickname</th>
			<th>Device</th>
			<th>Delete</th>
		</tr>";
		
	if ($result = $conn->query("SELECT * FROM wifi_registered_users")) 
	{
		while ($row=$result->fetch_row())
		{
			$id = $row[0];
			echo "<input type=\"hidden\" name=\"id\" value=\"$id\" />\n";
			echo "<tr><td>$counter</td>\n";
			echo "<td><input type=\"text\" name=\"macAddress_$id\" value=\"".$row[1]."\"]></td>\n";
			echo "<td><input type=\"text\" name=\"nickname_$id\" value=\"".$row[2]."\"]></td>\n";
			echo "<td>".$row[3]."</td>\n";
			echo "<td><input type=\"checkbox\" name=\"checkbox_$id\" value=\"Yes\" /></td>";
			echo "</tr>";
			$counter++;
		}
	echo "<tr><td><input type=\"submit\" name=\"new_nick\" value=\"New&nbsp;nick\"></td>
	<td></td>
	<td><input type=\"submit\" name=\"Save\" value=\"Save\"></td>
	<td><input type=\"submit\" name=\"delete\" value=\"Delete\"></td></tr>
	</table>\n
	</form>\n";
	}
}

else if (isset($_POST["Submit"]))
	echo "Insert username";
	
$conn->close();
curl_close($curl);
?>
