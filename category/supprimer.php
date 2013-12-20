<?php 
require ("../bootstrap.php");$image = new Images();

$image->deleteCategory($_GET["id"]);
       
header('Location: index.php');   
 
 ?>