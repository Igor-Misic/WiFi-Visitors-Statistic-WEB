<?php
include_once '../database_config.php';

function isVerified()
{
 
    $verified = false;
    $ask_hash = $_GET["ask_hash"];

    session_start();

    //Step 1 - get hash
    if($ask_hash == "yes")
    {

        $bytes = openssl_random_pseudo_bytes(16);
        $hash = base64_encode($bytes);
        $_SESSION["hash"] = $hash;
        echo $_SESSION["hash"];

    //Step 2 - verification
    }
    else 
    {
        $conn = openDbConn();
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = $_POST["validator"];
        } else {
            $validator = $_GET["validator"];
        }

        $calculated_validator = hash_hmac ('sha256', $_SESSION["hash"], $key);

        if(!is_string($validator) || strlen($validator) < 1)
        {
            echo "We don't take kindly of your kind here! <br>";
        }
        elseif((strcmp($calculated_validator,$validator) === 0) && ($_SESSION["hash"] != "NOT IN USE")) //validator is valid
        {
            $_SESSION["hash"] = "NOT IN USE";
            $verified = true;
            echo "Verification OK";
        } else {
            echo "Verification FAIL";
        }
        
        $conn->close();
    }

    return $verified;
}


?>