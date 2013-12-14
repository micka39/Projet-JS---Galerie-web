<?php

session_start();

function autoloadClasses($name) {
    $directory = __DIR__ . "/class/" . $name . ".class.php";
    if (is_readable($directory)) {
        require_once $directory;
    }
}

spl_autoload_register('autoloadClasses');
if (preg_match("#utilisateur|category|images#", $_SERVER['REQUEST_URI'])) {
    if (!isset($_SESSION['connected'])) {
        header("HTTP/1.1 403 Forbidden");
        require __DIR__ . '../403.php';
        exit();
    }
}