<?php

require '../../bootstrap.php';

$connection = new Connection();
$connection->connect($_POST['username'], $_POST['password']);