<?php
/*
 * Gère la génération du formulaire d'ajout, la vérification serveur et l'ajout
 * en base de données d'un nouvel utilisateur
 */

// Vérification de l'existence du champs de formulaire fullname 
if (isset($_POST['email'])) {
    var_dump($_POST);
} else {
    showForm();
}

function showForm($username = "", $email = "", $message = "") {

    echo "<div class='alert alert-danger' id='infoFormValidation'>$message</div>";
    ?>
    <form action="#" method="POST" class="form-horizontal" id="formAddUser">
        <p>Tous les champs sont obligatoires</p>
        <div class="form-group">
            <label for="email" class="control-label">Adresse courriel</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>"/>
        </div>
        <div class="form-group">
            <label for="username"  class="control-label">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>"/>
        </div>
        <div class="form-group">
            <label for="password"  class="control-label">Mot de passe (8 caractères minimum)</label>
            <input type="password" name="password" id="password" class="form-control"/>
        </div>
        <div class="form-group">
            <label for="passwordConfirm" class="control-label">Confirmation du mot de passe</label>
            <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control"/>
        </div>
        <div class="form-group">
            <input type="submit" value="Ajouter "/>
        </div>
    </form>
    <!-- Javascript pour le formulaire d'ajout d'utilisateur. -->
    <script type="text/javascript">
        $(document).ready(function() {
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
                    var data = "username=" + $("#username").val() + "&email=" + $("#email").val() + "&password=" + $("#password").val();
                    $.ajax({
                        type: "POST",
                        url: "utilisateurs/addUser.php",
                        data: data,
                        dataType: "json",
                        success: function(result) {
                            alert(result);
                        },
                        error: function(xhr, type, thrownError) {

                            alert(thrownError);

                        }

                    });
                }
            });
        });
    </script>
    <?php
}
?>
