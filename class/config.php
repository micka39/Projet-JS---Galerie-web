<?php

function connectPdo() {
    $config = parse_ini_file(__DIR__ . "/config.ini");
    $dsn = "mysql:host=".$config['host'].";dbname=" . $config['database'];
    $db = new PDO($dsn, $config['user'], $config['password']);
    $db->exec("SET CHARACTER SET utf8");
    return $db;
}
