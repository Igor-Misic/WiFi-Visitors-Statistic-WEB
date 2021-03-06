<style>
.hover_img { 
  position:relative;  
}
.hover_img a span { 
  position:absolute; 
  display:none; 
  z-index:99;
  left:50%;
  -webkit-transform: translateX(-50%);
  -ms-transform: translateX(-50%);
  transform: translateX(-50%);
}
.hover_img a:hover span { 
  display:block;
}
</style>

<?php

//include 'access_log.php';

$temperature;
$timeStamp;
$server = $_SERVER['PHP_SELF'];

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

function get_temp()
{
    global $temperature;
    global $timeStamp;
    $conn = openDbConn();

    $result = $conn->query("SELECT temperature, ts FROM temperature WHERE id = 1");

    if ($result->num_rows > 0) 
    {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $temperature = $row["temperature"];  
            $timeStamp = $row["ts"];
        }
    } 
    else 
    {
        echo "No temp in table<br>";
    }

    $conn->close();
}


if (!isset($_GET["showGuests"]) )
{
    $checkBoxMenuHtml = "
    <form id=\"formName\" action=\"".$_SERVER['PHP_SELF']."\" method=\"get\">
    <td width=10%><input type =\"checkbox\" name=\"showGuests\" value = \"true\" onchange=\"document.getElementById('formName').submit()\">Show guests</input></td>
    </form>";
}
else
{
    $checkBoxMenuHtml = "
    <form id=\"formName\" action=\"".$_SERVER['PHP_SELF']."\" method=\"get\">
    <td width=10%><input type =\"checkbox\" name=\"showGuests\" value = \"false\" checked onchange=\"document.getElementById('formName').submit()\">Show guests</input></td>
    </form>";
}


$temperatureImageLink = $protocol.$_SERVER['SERVER_NAME'].":81/render/?width=586&height=308&_salt=1545851943.431&yMin=0&yMax=50&from=-168hours&title=Lab%20temperature&target=hacklab.temperature";

get_temp();

$temperatureHtml = "<div class=\"hover_img\"><a href=\"#\">".$temperature."&#8451; <span><img src=".$temperatureImageLink." alt=\"image\" height=\"300\" /></span></a></div>";

echo 
"<table  class=menu >
    <tr>
        <td width=15%><b><a href=\"index.php\">Home</a></b></td>
        <td width=15%><b><a href=\"admin_panel.php\">Admin</a></b></td>
        $checkBoxMenuHtml
        <td width=15%><b><a href=\"smart_lock_log.php\">Smart lock</a></b></td>
        <td width=10%><b>".$temperatureHtml."</b></td>
        <td width=10%><b><a href=\"show_image.php\">Camera</a></b></td>
        <td width=5%></td>
        <td width=10%>".$timeStamp."</td>";
        
//if (isset($_COOKIE["TOKEN"])) 
//echo "<th><a href=\"index.php?logout=true\">Logout</th>";

echo "</tr>
</table>";

/*
if ($_GET["logout"] === "true")
{    
    echo "yes";
    unset($_COOKIE["TOKEN"]);
    setcookie("TOKEN", "", time()-3600);
}*/

