<?php
require_once '../bootstrap.php';
/*
 * Gère la génération du formulaire de modification, la vérification serveur et l'ajout
 * en base de données des modifications de l'utilisateur
 */

$connection = new Connection();
// Vérification de l'existence du champs de formulaire fullname 
if (isset($_POST['email'])) {
    $message = "";
    $password = "";
    $email = "";
    $level_access = "";
    if ($_POST['password'] != $_POST['passwordConfirm'])
        $message .= "Les mots de passes ne correspondent pas !<br/>";
    else
    {
        if($password != "")
        $password = $_POST['password'];    
    }
        
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
        if($password == "")
        {
            $password = null;
        }
        if ($connection->modifyUser($_POST['id'], $email,$level_access, $password))
            echo 'Utilisateur modifié avec succès !';
        else {
            $message .= "Il y a eu un problème durant la modification en base de données."
                    . "Merci de réesayer.";
            showForm($_POST['id'],$_POST['email'],$level_access, $message);
        }
    } else {
        showForm($_POST['id'],$_POST['email'],$level_access, $message);
    }
} else {
    if(is_numeric($_GET['id'])){
    $user = $connection->getUser($_GET['id']);
    
    showForm($user['iduser'],$user['email'],$user['level_access']);
    }
    else
    {
        header("HTTP/1.0 403 Forbidden");
        echo "Cette page n'existe pas ou vous n'en avez pas les autorisations d'accès";
    }
}

function showForm($id = "", $email = "",$admin ="", $message = "") {

    echo "<div class='alert alert-danger' id='infoFormValidation'>$message</div>";
    ?>
    <div class="row">
        <form action="#" method="POST" class="form-horizontal" id="formAddUser">
            <p>Tous les champs sont obligatoires</p>
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
            <div class="form-group col-xs-12">
                <label for="email" class="control-label">Adresse courriel</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>"/>
            </div>
            <div class="form-group col-xs-12">
                <label for="password"  class="control-label">Mot de passe (laissez vide si vous ne souhaitez pas changer)</label>
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
                    <input type="checkbox" name="admin" id="admin" class="form-control" <?php if($admin == "0"){ echo "checked";} ?>/>
                </div>
            <?php }
            ?>
            <div class="form-group col-xs-3">
                <input type="submit" value="Modifier "/>
            </div>
        </form>
    </div>
    <!-- Javascript pour le formulaire d'ajout d'utilisateur. -->
    <script type="text/javascript">
        $(document).ready(function() {
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
