<?php 
include_once("../header.php"); 
require ("../class/config.php");

$name=$_POST["name"];
$description=$_POST["description"];

$connect=connectPdo();
$req = ("INSERT INTO category(`idcategory`, `name`, `description`, `time`) VALUES(NULL, '".$name."', '".$description."' ,'".date("Y-m-d H:i:s")."')");
$connect->query($req);

header('Location: category.php'); 
          
include_once("../footer.php"); 

 ?>