<?php

<<<<<<< HEAD
function connectPdo() {
    $config = parse_ini_file(__DIR__ . "/config.ini");
    $dsn = "mysql:host=".$config['host'].";dbname=" . $config['database'];
    $db = new PDO($dsn, $config['user'], $config['password']);
    $db->exec("SET CHARACTER SET utf8");
    return $db;
}
=======
function getDSN() {
$dsn = "mysql:host=localhost;dbname=projetJs";
return $dsn;
}

function getLogins() { 
$tab['user'] = "root";
$tab['password'] = "nzen43";
return $tab;
}

function connectPdo()
{
$logins = getLogins();
$db = new PDO(getDSN(), $logins['user'], $logins['password']);
$db->exec("SET CHARACTER SET utf8");
return $db;
}
>>>>>>> 524ad732144f44b550bf6002fce102ad479642a3
