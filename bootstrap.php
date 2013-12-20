<?php

<<<<<<< HEAD
session_start();

function autoloadClasses($name) {
    $directory = __DIR__ . "/class/" . $name . ".class.php";
=======
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
function autoloadClasses($name) {
    $directory = __DIR__. "/class/".$name.".class.php";
>>>>>>> 524ad732144f44b550bf6002fce102ad479642a3
    if (is_readable($directory)) {
        require_once $directory;
    }
}

<<<<<<< HEAD
spl_autoload_register('autoloadClasses');
if (preg_match("#utilisateur|category|images#", $_SERVER['REQUEST_URI'])) {
    if (!isset($_SESSION['connected'])) {
        header("HTTP/1.1 403 Forbidden");
        require __DIR__ . '../403.php';
        exit();
    }
}
=======
spl_autoload_register('autoloadClasses');
>>>>>>> 524ad732144f44b550bf6002fce102ad479642a3
