<?php
require_once '../bootstrap.php';
$images = new Images();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (is_numeric($_POST['id'])) {
        $message = "";
        switch ($_GET['action']) {
            case "delete": {
                    $message = $images->deleteImage($_POST['id']);
                    if ($message == "")
                        echo "L'image a bien été supprimée";
                }
                break;
            case "modify": {
                    $message = $images->updatePhoto($_POST['title'], $_POST['description'], $_POST['id'],$_POST['category']);
                    if ($message == "")
                        echo "L'image a bien été modifiée";
                }
                break;

            default:
                break;
        }
        if ($message != "") {
            $image = $images->getPhoto($_GET['id']);
            $categories = $images->getCategoriesById($_GET['id']);
            showForm($image['idimage'], $image['title'], $image['description'], $image['file_name'], $image['extension'],$categories);
        }
    } else {
        header("HTTP/1.0 403 Forbidden");
        echo "Cette page n'existe pas ou vous n'en avez pas les autorisations d'accès";
    }
} else {
    if (is_numeric($_GET['id'])) {
        $image = $images->getPhoto($_GET['id']);
        $categories = $images->getCategoriesById($_GET['id']);
        showForm($image['idimage'], $image['title'], $image['description'], $image['file_name'], $image['extension'], $categories);
    } else {
        header("HTTP/1.0 403 Forbidden");
        echo "Cette page n'existe pas ou vous n'en avez pas les autorisations d'accès";
    }
}

function showForm($id = "", $title = "", $description = "", $file_name = "", $extension = "", $categories = "", $message = "") {

    echo "<div class='alert alert-danger' id='infoFormValidation'>$message</div>";
    ?>
    <div class="row">
        <div>
            <img src="<?php echo "upload/" . $file_name . "_s." . $extension; ?>" alt="<?php echo $description; ?>"/>
        </div>

        <form action="#" method="POST" class="form-horizontal" id="formModifyImage">
            
            <?php 
            $idsCategory = array();
                foreach ($categories as $category) {
                    $idsCategory[$category['id']]= $category['id'];
                }
            ?>
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
            <div class="form-group col-xs-12">
                <label for="category" class="control-label">Catégorie (vous pouvez en sélectionner plusieurs)</label>
                <select name="category[]" id="category" class="form-control" multiple >
                    <?php
                    $image = new Images();
                    $categories = $image->getCategories();
                    foreach ($categories as $category) {
                        if ($category['id'] == array_key_exists($category['id'], $idsCategory))
                            echo "<option value='" . $category['id'] . "' selected>" . $category['name'] . "</option>";
                        else
                            echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-xs-12">
                <label for="title" class="control-label">Titre</label>
                <input type="title" name="title" id="title" class="form-control" value="<?php echo $title; ?>"/>
            </div>
            <div class="form-group col-xs-12">
                <label for="description" class="control-label">Description</label>
                <input type="description" name="description" id="description" class="form-control" value="<?php echo $description; ?>"/>
            </div>
        </form>
        <button class="btn btn-default" id="no">Annuler</button>
        <button class="btn btn-danger" id="delete">Supprimer</button>
        <button class="btn btn-success" id="modify">Enregistrer les modifications</button>
    </div>
    <!-- Javascript pour le formulaire d'ajout d'utilisateur. -->
    <script type="text/javascript">
        $(document).ready(function() {

            $("#no").click(function() {
                $("#modal").modal('hide');
            });

            $("#delete").click(function() {
                var data = "id="
                        + $("#id").val();
                $.ajax({
                    type: "POST",
                    url: "images/modifyImage.php?action=delete",
                    data: data,
                    success: function(result) {
                        $("#modalBody").html(result);
                        $("#modal").modal('show');
                    }

                });
            });

            $("#modify").click(function() {
                var data = $('#formModifyImage').serialize();
                $.ajax({
                    type: "POST",
                    url: "images/modifyImage.php?action=modify",
                    data: data,
                    success: function(result) {
                        $("#modalBody").html(result);
                        $("#modal").modal('show');
                    }

                });
            });
            if ($("#infoFormValidation").text() === "")
                $("#infoFormValidation").hide();
        });
    </script>
    <?php
}
?>