<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
function autoloadClasses($name) {
    $directory = __DIR__. "/class/".$name.".class.php";
    if (is_readable($directory)) {
        require_once $directory;
    }
}

spl_autoload_register('autoloadClasses');