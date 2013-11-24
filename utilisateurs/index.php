<?php
$titre = "Gestion des utilisateurs";

require '../header.php';
?>
<div class="jumbotron">
    <div class="container">
        <h1>Gestion des utilisateurs</h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <p><button class="btn btn-primary" id="addUser">Ajouter un nouvel utilisateur</button></p>
    </div>
    <div class="row">
        <table class="table">
            <thead><th>Id</th><th>Nom complet</th><th>Nom d'utilisateur</th><th>Action</th></thead>
            <tbody>
                <?php
                require '../class/connection.class.php';

                $connection = new Connection();
                $users = $connection->getListUsers();
                foreach ($users as $user) {
                    ?>
                    <tr><td><?php echo $user['iduser']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><div class="btn-group">
                                <button class="btn dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="utilisateurs/modifier">Modifier</a></li>
                                    <li><a href="utilisateurs/supprimer">Supprimer</a></li>
                                </ul>
                            </div>
                        </td></tr>
                    <?php
                }
                ?>

            </tbody>
        </table>

    </div>

</div>

<!-- Début de la fenêtre modale -->
<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>
</div>
<!-- Fin de la fenêtre modale -->

<script type="text/javascript">
    $(document).ready(function() {
        $("#addUser").click(function() {
            showModal("Ajouter un utilisateur", "utilisateurs/addUser.php");
        });

        function showModal(title, url)
        {
            $("#modalTitle").text(title);
            $.ajax({
                type: "GET",
                url: url
            }).done(function(data) {
                $("#modalBody").html(data);
            });
            $("#modal").modal('show');
        }

    });
</script>

<?php
require '../footer.php';
?>
