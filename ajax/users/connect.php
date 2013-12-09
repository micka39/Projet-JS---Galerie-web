<?php

require '../../bootstrap.php';

$connection = new Connection();
if($connection->connect($_POST['username'], $_POST['password']))
        echo "connect";
else
    echo "error";