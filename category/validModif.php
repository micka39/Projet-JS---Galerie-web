<?php 
require ("../bootstrap.php"); 
include_once("../header.php"); 
require ("../class/config.php");


$connect=  connectPdo();
$sql = "UPDATE category SET `name`='".$_POST['name']."', `description`='".$_POST['description']."', `time`='".$_POST['time']."' WHERE idcategory=".$_SESSION["idCategory"];
$connect->query($sql);

header('Location: category.php');    

include_once("../footer.php"); 
?>