<link rel="stylesheet" type="text/css" href="style.css">
<?php
include 'database_config.php';
include 'menu.php';
include 'admin_functions.php';
include 'login_form.php';

if (true === isUserLogged())
{
    echo "<img src = camera/image/camera.jpg>";
} else 
{
    loginForm();
}

?>
