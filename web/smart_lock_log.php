<link rel="stylesheet" type="text/css" href="style.css">
<?php
include 'database_config.php';
include 'menu.php';

$userTime = "menu";
// total time
echo "<br><table style=\"width:30%\" align=\"left\">
    <tr>
        <th class=$userTime>No:</th>
        <th class=$userTime>User:</th>
        <th class=$userTime>Date:</th>
    </tr>";

if ($result = $conn->query("SELECT * FROM smart_lock_log ORDER BY (ID+0) DESC")) 
{
    while ($row=$result->fetch_row())
    {    
        $id = $row[0];
        $user = $row[1];
        $date = $row[2];
    

        echo "<tr><td>".$id."</td>";
        echo "<td>".$user."</td>";
        echo "<td>".$date."</td>";
        echo "</tr>";
    }
        echo "</table>";    
}
else echo "Can't SELECT";




$conn->close();
curl_close($curl);
?>
