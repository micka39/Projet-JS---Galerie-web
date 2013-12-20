<?php 
include_once("../header.php"); 
require ("../class/config.php");
          

$connect=connectPdo();
$sql= 'DELETE FROM `projetjs`.`imagecategory` WHERE `imagecategory`.`category_idcategory` ='.$_GET["id"]; 
$connect->query($sql);
$sql ='DELETE from category WHERE idcategory='.$_GET["id"]; 
$connect->query($sql);
         
header('Location: category.php');      

include_once("../footer.php"); 
 
 ?>