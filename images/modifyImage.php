<?php
require_once '../bootstrap.php';
$images = new Images();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
} else {
    if (is_numeric($_GET['id'])) {
        $image = $images->getPhoto($_GET['id']);

        showForm($image['idimage'], $image['title'], $image['description']);
    } else {
        header("HTTP/1.0 403 Forbidden");
        echo "Cette page n'existe pas ou vous n'en avez pas les autorisations d'accès";
    }
}

function showForm($id = "", $title = "", $description = "", $message = "") {

    echo "<div class='alert alert-danger' id='infoFormValidation'>$message</div>";
    ?>
    <div class="row">
        <form action="#" method="POST" class="form-horizontal" id="formmodifyImage">
            <p>Tous les champs sont obligatoires</p>
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
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
        <button class="btn btn-danger" id="yes">Supprimer</button>
        <button class="btn btn-success" id="modify">Enregistrer les modifications</button>
    </div>
    <!-- Javascript pour le formulaire d'ajout d'utilisateur. -->
    <script type="text/javascript">
        $(document).ready(function() {
            
        $("#no").click(function(){
        $("#modal").modal('hide');
        });
            if ($("#infoFormValidation").text() === "")
                $("#infoFormValidation").hide();
            $("#formAddUser").submit(function(event) {
                // On bloque la soumission par défaut du formulaire
                event.preventDefault();
                var message = verifyFormUserAdd($("#username").val(), $("#email").val(), $("#password").val(), $("#passwordConfirm").val(),true);
                if (message !== "")
                {
                    $("#infoFormValidation").show(200);
                    $("#infoFormValidation").html(message);
                }
                else
                {
                    $("#infoFormValidation").html("");
                    var data = "email="
                        + $("#email").val()
                        + "&password=" + $("#password").val()
                        + "&passwordConfirm=" + $("#passwordConfirm").val()
                        + "&admin=" + $("#admin").val()
                        + "&id=" + $("#id").val();
                    $.ajax({
                        type: "POST",
                        url: "utilisateurs/modifyUser.php",
                        data: data,
                        success: function(result) {
                            $("#modalBody").html(result);
                            $("#modal").modal('show');
                        }

                    });
                }
            });
        });
    </script>
    <?php
}
?>