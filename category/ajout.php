
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Projet de galerie web IUT A - Bourg en Bresse">
    <meta http-equiv="refresh" content="0.000001; URL=http://localhost/ProjetJS/category.php">
    <base href="http://localhost/ProjetJS/"/>

    <title>Ajouter catégorie</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

<?php


$name=$_POST["name"];
$description=$_POST["description"];
$creationDate=$_POST["creationDate"];

require ("../class/config.php");

$connect=connectPdo();
$req = ("INSERT INTO category(`idcategory`, `name`, `description`, `time`) VALUES(NULL, '".$name."', '".$description."' ,'".$creationDate."')");
$connect->query($req);
?>

<?php include_once("../footer.php"); ?>