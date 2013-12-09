<?php
require_once '../bootstrap.php';
/*
 * Gère la génération du formulaire d'ajout, la vérification serveur et l'ajout
 * en base de données d'un nouvel utilisateur
 */

// Vérification de l'existence du champs de formulaire fullname 
if (isset($_FILES['email'])) {
    var_dump($_FILES);
} else {
    showForm();
}

function showForm($username = "", $email = "", $message = "") {

    echo "<div class='alert alert-danger' id='infoFormValidation'>$message</div>";
    ?>
<script src="js/vendor/jquery.ui.widget.js"></script>
<script src="js/jquery.iframe-transport.js"></script>
<script src="js/jquery.fileupload.js"></script>
<div class="row">
    <form action="#" method="POST" class="form-horizontal" id="formAddUser">
        <p>Tous les champs sont obligatoires</p>
        <div class="form-group col-xs-12">
            <label for="email" class="control-label">Adresse courriel</label>
            <input type="file" multiple name="email" id="email" class="form-control" value="<?php echo $email; ?>"/>
        </div>
        <div class="form-group col-xs-12">
            <label for="username"  class="control-label" id="labelUsername">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>"/>
            
    </div>
    <div class="form-group col-xs-12">
        <label for="password"  class="control-label">Mot de passe (8 caractères minimum)</label>
        <input type="password" name="password" id="password" class="form-control"/>
    </div>
    <div class="form-group  col-xs-12">
        <label for="passwordConfirm" class="control-label">Confirmation du mot de passe</label>
        <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control"/>
    </div>
    <?php if ($_SESSION['admin']) {
        ?>
        <div class="form-group  col-xs-12">
            <label for="admin" class="control-label">Administrateur</label>
            <input type="checkbox" name="admin" id="admin" class="form-control"/>
        </div>
    <?php } 
    ?>
    <div class="form-group col-xs-3">
        <input type="submit" value="Ajouter "/>
    </div>
    </form>
</div>
    <!-- Javascript pour le formulaire d'ajout d'utilisateur. -->
    <script type="text/javascript">
        $(function () {
    $('#email').fileupload({
        url: 'images/addImage.php',
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }
    });
});
        
        $(document).ready(function() {
            $("#username").keyup(function()
            {
                $.post("ajax/users/checkAvailability.php?username=" + $("#username").val(), function(data)
                {
                    if (data == "1")
                        $("#labelUsername").text("Nom d'utilisateur (disponible)");
                    else
                        $("#labelUsername").text("Nom d'utilisateur (indisponible)");
                });
                    
            });
            if ($("#infoFormValidation").text() === "")
                $("#infoFormValidation").hide();
            $("#formAddUser").submit(function(event) {
                // On bloque la soumission par défaut du formulaire
                event.preventDefault();
                var message = verifyFormUserAdd($("#username").val(), $("#email").val(), $("#password").val(), $("#passwordConfirm").val());
                if (message !== "")
                {
                    $("#infoFormValidation").show(200);
                    $("#infoFormValidation").html(message);
                }
                else
                {
                    $("#infoFormValidation").html("");
                    var data = "username=" + $("#username").val() + "&email="
                            + $("#email").val()
                            + "&password=" + $("#password").val()
                            + "&passwordConfirm=" + $("#passwordConfirm").val()
                            + "&admin=" + $("#admin").val();
                    $.ajax({
                        type: "POST",
                        url: "utilisateurs/addUser.php",
                        data: data,
                        dataType: "json",
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
