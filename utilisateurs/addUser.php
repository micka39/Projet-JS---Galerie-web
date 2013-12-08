<?php
require_once '../bootstrap.php';
/*
 * Gère la génération du formulaire d'ajout, la vérification serveur et l'ajout
 * en base de données d'un nouvel utilisateur
 */

// Vérification de l'existence du champs de formulaire fullname 
if (isset($_POST['email'])) {
    $connection = new Connection();
    $message = "";
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = "";
    $email = "";
    $level_access = "";
    // Si l'utilisateur est valide on vérifie qu'il n'excède pas les 45 caractères
    if ($username != (FALSE || NULL)) {
        if (count($username) > 45 || count($username) == 0) {
            $message .= "Le nom d'utilisateur ne doit pas être vide ni excéder"
                    . " 45 caractères <br/>";
        }
        else
        {
            if(!$connection->checkAvailibity($username))
            {
                $message .= "Le nom d'utilisateur est déjà utilisé. <br/>";
            }
        }
    } else {
        $message .= "Le nom d'utilisateur ne peut excéder 45 caractères <br/>";
    }
    if ($_POST['password'] != $_POST['passwordConfirm'])
        $message .= "Les mots de passes ne correspondent pas !<br/>";
    else
        $password = $_POST['password'];
    if (isset($_SESSION['admin']))
        if ($_POST['admin'] && $_SESSION['admin'])
            $level_access = 0;
        else
            $level_access = 1;
    if (!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL))
        $message . + "L'adresse email n'est pas valide  <br/>";
    else
        $email = $_POST['email'];
    if ($message == "") {
        if ($connection->addUser($username, $password, $level_access, $email))
            echo 'Utilisateur ajouté avec succès !';
        else {
            $message .= "Il y a eu un problème durant l'ajout en base de données."
                    . "Merci de réesayer.";
            showForm($username, $_POST['email'], $message);
        }
    } else {
        showForm($username, $_POST['email'], $message);
    }
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
            <label for="username"  class="control-label" id="labelUsername">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>"/>
            <lege
    </div>
    <div class="form-group">
        <label for="password"  class="control-label">Mot de passe (8 caractères minimum)</label>
        <input type="password" name="password" id="password" class="form-control"/>
    </div>
    <div class="form-group">
        <label for="passwordConfirm" class="control-label">Confirmation du mot de passe</label>
        <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control"/>
    </div>
    <?php if ($_SESSION['admin']) {
        ?>
        <div class="form-group">
            <label for="admin" class="control-label">Administrateur</label>
            <input type="checkbox" name="admin" id="admin" class="form-control"/>
        </div>
    <?php } ?>
    <div class="form-group">
        <input type="submit" value="Ajouter "/>
    </div>
    </form>
    <!-- Javascript pour le formulaire d'ajout d'utilisateur. -->
    <script type="text/javascript">
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
