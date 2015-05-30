<?php

echo 
"<table  class=menu >
	<tr>
		<td width=15%><b><a href=\"index.php\">Home</a></b></td>
		<td width=15%><b><a href=\"admin_panel.php\">Admin</a></b></td>		
		<td width=70%></td>";
		
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