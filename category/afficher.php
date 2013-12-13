<?php
$titre = "Afficher une cathégorie";
include_once("../header.php"); 
?>

<div class="jumbotron">
      <div class="container">
        <h1><?php echo $_GET["name"]; ?></h1>
        
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">

      </div>
    </div>


<?php include_once("../footer.php"); ?>