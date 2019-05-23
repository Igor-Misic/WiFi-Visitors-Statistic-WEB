<?php
include '../database_config.php';

//key must be same as in bash script
$result = $conn->query("SELECT psk FROM preshared_key WHERE name='api_psk'");

if ($result->num_rows > 0)
{
    // output data of each row
    while($row = $result->fetch_assoc())
    {
        $key = $row["psk"];
    }
}
else
{
    echo "0 results";
}

$validator = $_POST["validator"];
$date = $_POST["date"];

$calculated_validator = hash_hmac ('sha1', $date, $key);

if(!is_string($validator) || strlen($validator) < 1)
{
    echo "We don't take kindly of your kind around here! <br>";
}
elseif(strcmp($calculated_validator,$validator) === 0)
{

    $uploaddir = $_SERVER['DOCUMENT_ROOT'].'camera/image/';
    $filename = basename($_FILES['userfile']['name']);
    $uploadfile = $uploaddir . $filename;

    //echo $uploadfile;
    $file_type = $_FILES["userfile"]["type"];

    echo '<pre>';
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
    {
        echo "File is valid, and was successfully uploaded.\n";

        if ("image/gif" == $file_type) 
        {
            $sql_query = "SELECT * FROM smart_lock_log ORDER BY (ID+0) DESC LIMIT 1";
            $result = $conn->query($sql_query);
            if ($result->num_rows > 0)
            {
                // output data of each row
                while($row=$result->fetch_row())
                {
                    $id = $row[0];
                    $sql_query = "UPDATE smart_lock_log SET gifLink='$filename' WHERE ID='$id'";
                    echo $sql_query;
                    $conn->query($sql_query);
                }
            } else
            {
                echo "0 results for $sql_query";
            }
        }

    } else {
        echo "Possible file upload attack!\n";
    }

    echo 'Here is some more debugging info:\n';
    print_r($_FILES);

    print "</pre>";
}

?>
