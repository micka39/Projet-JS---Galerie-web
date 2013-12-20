<?php
require ("../bootstrap.php");
$titre = "Modifier une catégorie";
include_once("../header.php"); 
?>

<div class="jumbotron">
      <div class="container">
        <h1>Modifier la catégorie: <?php echo $_GET["name"]; ?></h1>
        
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
          
<?php
         echo '<form method="POST" action="category/validModif.php">'; 
         echo '<label for="name">Nom</label>';
         echo '<input type="text" class="form-control" placeholder="Text input" name="name" value='.$_GET["name"].'>';
         echo '<label for="description">Description</label>';
         echo '<input type="text" class="form-control" placeholder="Text input" name="description" value='.$_GET["description"].'>';
         echo '<label for="time">Date de création</label>';
         echo '<input type="text" class="form-control" placeholder="Text input" name="time" value='.$_GET["time"].'>';
         echo '<button type="submit" class="btn btn-primary">Modifier</button>';
         echo '<a href="../ProjetJS/category.php"><input type="button" class="btn btn-primary" value="Annuler"></input></a>';
         echo '</form>';
?>

      </div>
    </div>


<?php include_once("../footer.php"); ?>
