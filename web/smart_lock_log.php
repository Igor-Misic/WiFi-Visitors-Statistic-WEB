<link rel="stylesheet" type="text/css" href="style.css">
<?php
include 'database_config.php';
include 'menu.php';
include 'admin_functions.php';

$userTime = "menu";
// total time
echo "<br><table style=\"width:30%\" align=\"left\">";
echo " <tr>";
echo "        <th class=$userTime>No:</th>";
echo "        <th class=$userTime>Id:</th>";
echo "        <th class=$userTime>User:</th>";
echo "        <th class=$userTime>Date:</th>";
if (true === isUserLogged())
{
    echo "        <th class=$userTime>Gif link:</th>";
};
echo "    </tr>";

if ($result = $conn->query("SELECT * FROM smart_lock_log ORDER BY (ID+0) DESC LIMIT 40")) 
{
    while ($row=$result->fetch_row())
    {    
        $id = $row[0];
        $user_id = $row[1];
        $user = $row[2];
        $date = $row[3];
        $gifLink = $row[4];

        echo "<tr><td>".$id."</td>";
        echo "<td>".$user_id."</td>";
        echo "<td>".$user."</td>";
        echo "<td>".$date."</td>";
        if (true === isUserLogged())
        {
            echo "<td><a href=http://".$_SERVER['SERVER_NAME']."/camera/image/".$gifLink.">Link</a></td>";
        }
        echo "</tr>";
    }
        echo "</table>";    
}
else echo "Can't SELECT";




$conn->close();
curl_close($curl);
?>
