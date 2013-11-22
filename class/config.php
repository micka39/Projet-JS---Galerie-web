<?php

function getDSN() {
	$dsn = "mysql:host=localhost;dbname=test";
	return $dsn;
}

function getLogins() { 
	$tab['user'] = "root";
	$tab['password'] = "ya9VkWeA";
	return $tab;
}

function connectPdo()
{
	$logins = getLogins();
	$db = new PDO(getDSN(), $logins['user'], $logins['password']);
	$db->exec("SET CHARACTER SET utf8");
	return $db;
}
