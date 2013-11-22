<?php

function getDSN() {
	$dsn = "mysql:host=localhost;dbname=projetJs";
	return $dsn;
}

function getLogins() { 
	$tab['user'] = "root";
	$tab['password'] = "root";
	return $tab;
}

function connectPdo()
{
	$logins = getLogins();
	$db = new PDO(getDSN(), $logins['user'], $logins['password']);
	$db->exec("SET CHARACTER SET utf8");
	return $db;
}
