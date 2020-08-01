<?php

global $password;

// Set Cookie
function setTokenCookie($password)
{
	$time =  time();
	$calculated_token = hash_hmac ('sha1', $time, $password);
	$cookie_name = "TOKEN";
	
	if(!isset($_COOKIE[$cookie_name])) 
	{
		setcookie($cookie_name, $calculated_token, time() + (60*15), "/"); // 60*15 = 15 min
		setcookie("TIME", $time, time() + (60*15), "/"); // 60*15 = 15 min
	} 
}
function checkTokenCookie($password)
{
	if(isset($_COOKIE["TOKEN"]) and isset($_COOKIE["TIME"])) 
	{	$token = $_COOKIE["TOKEN"];
		$time = $_COOKIE["TIME"];
		$calculated_token = hash_hmac ('sha1', $time, $password);
		if ($calculated_token === $token) return true;
	}
	else return false;
}
function setUsernameCookie($username)
{
	$time =  time();
	$cookie_name = "USERNAME";
	
	if(!isset($_COOKIE[$cookie_name])) 
	{
		setcookie($cookie_name, $username, time() + (60*15), "/"); // 60*15 = 15 min
	} 
}

function isUserLogged() {
	$retValue = false;
		//login
	if (isset($_POST["username"]) OR isset($_COOKIE["TOKEN"]))
	{
		if (isset($_POST["username"])) 
		{
			$username = $_POST["username"];
			setUsernameCookie($username);
		}
		else if (isset($_COOKIE["USERNAME"]))
		{	
			$username = $_COOKIE["USERNAME"];
		}
		else echo "No username value!";

		$conn = openDbConn();
		$result = $conn->query("SELECT password FROM login_data WHERE username = '$username'");

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$password = $row["password"];
			}
		} else {
			echo "No username in table<br>";
		}

		if (md5($_POST["password"]) === $password OR checkTokenCookie($password) === true) 
		{
			setTokenCookie($password);
			$retValue = true;
		}
		else 
		{
			echo "Wrong password!";
		}

		$conn->close();
	}

	return $retValue;
}
?>