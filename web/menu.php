<?php

$temp;
$timeStamp;

function get_temp()
{

    global $temp;
    global $timeStamp;
    global $conn;

    $result = $conn->query("SELECT temp, ts FROM temp WHERE id = 1");

    if ($result->num_rows > 0) 
    {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $temp = $row["temp"];  
            $timeStamp = $row["ts"];
        }
    } 
    else 
    {
        echo "No temp in table<br>";
    }
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

get_temp();

echo 
"<table  class=menu >
    <tr>
        <td width=15%><b><a href=\"index.php\">Home</a></b></td>
        <td width=15%><b><a href=\"admin_panel.php\">Admin</a></b></td>
        $checkBoxMenuHtml
        <td width=15%><b><a href=\"smart_lock_log.php\">Smart lock</a></b></td>
        <td width=5%><b>".$temp." &#8451;</b></td>
        <td width=30%></td>
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

