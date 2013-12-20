<?php 
require ("../bootstrap.php"); 
require ("../class/config.php");


$connect=  connectPdo();
$sql = "UPDATE category SET `name`='".$_POST['name']."', `description`='".$_POST['description']."' WHERE idcategory=".$_POST["id"];
$connect->query($sql);

header('Location: index.php'); 

include_once("../footer.php"); 
?>