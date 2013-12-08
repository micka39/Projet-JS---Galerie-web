<?php

require_once '../../bootstrap.php';

$connection = new Connection();
if(is_string($_GET['username']))
{
    $username = $_GET['username'];
    if($connection->checkAvailibity($username))
        echo "1";
    else
        echo "0";
}
 else {
     echo "0";
}