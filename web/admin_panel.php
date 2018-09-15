<link rel="stylesheet" type="text/css" href="style.css">

<?php
include 'database_config.php';
include 'menu.php';

// Get cURL resource
$curl = curl_init();

if (!isset($_POST["Submit"]) && !isset($_POST["Save"]) && !isset($_POST["new_nick"]) && !isset($_COOKIE["TOKEN"]))
echo "
<form action=\"\" method=\"POST\">
Username:<br>
<input type=\"text\" name=\"username\">
<br>
Password:<br>
<input type=\"password\" name=\"password\">
<br>
<input type=\"submit\" name=\"Submit\">
</form>";

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
    {   
		$token = $_COOKIE["TOKEN"];
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
    
        $result = $conn->query("SELECT password FROM login_data WHERE username = '$username'");

        if ($result->num_rows > 0) {
            // output data of each row
			while($row = $result->fetch_assoc()) 
			{
                $password = $row["password"];
            }
		} 
		else 
		{
            echo "No username in table<br>";
        }

    if (md5($_POST["password"]) === $password OR checkTokenCookie($password) === true) 
    {
        setTokenCookie($password);
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
            
        if ($result = $conn->query("SELECT * FROM wifi_registered_users")) {
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
    else 
    {
        echo "Wrong password!";
    }
}

else if (isset($_POST["Submit"]))
    echo "Insert username";
    
$conn->close();
curl_close($curl);
?>