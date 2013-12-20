<?php
require ("../bootstrap.php");
require ("../class/config.php");
$titre = "Modifier une catégorie";
include_once("../header.php");
if (is_numeric($_GET['id'])) {
    $db = connectPdo();
    $sql = "SELECT idcategory as id, name,description,time FROM category WHERE idcategory=" . $_GET['id'];
    $result = $db->query($sql);
    $category = $result->fetch();
} else {
    header("HTTP/1.0 403 Forbidden");
    echo "Cette page n'existe pas ou vous n'en avez pas les autorisations d'accès";
}
?>

<div class="jumbotron">
    <div class="container">
        <h2>Modifier la catégorie: <?php echo $category["name"]; ?></h2>

    </div>
</div>

<div class="container">
    <!-- Example row of columns -->
    <div class="row">

        <?php
        echo '<form method="POST" action="category/validModif.php">';
        echo '<input type="hidden" name="id" value="'.$category['id'].'"/>';
        echo '<label for="name">Nom</label>';
        echo '<input type="text" class="form-control" placeholder="Text input" name="name" value="' . $category["name"] . '">';
        echo '<label for="description">Description</label>';
        echo '<input type="text" class="form-control" placeholder="Text input" name="description" value="' . $category["description"] . '">';
        echo '<button type="submit" class="btn btn-primary">Modifier</button>';
        echo '<a href="category/"><input type="button" class="btn btn-primary" value="Annuler"></input></a>';
        echo '</form>';
        ?>

    </div>
</div>


<?php include_once("../footer.php"); ?>
