<?php
$titre = "Gestion des utilisateurs";

require_once '../bootstrap.php';
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
            <thead><th>Id</th><th>Email</th><th>Nom d'utilisateur</th><th>Action</th></thead>
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
                                    <li><a href="#" data-id="<?php echo $user['iduser']; ?>" class="modifyUser">Modifier</a></li>
                                    <li><a href="#" data-id="<?php echo $user['iduser']; ?>" class="deleteUser">Supprimer</a></li>
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
        $(".modifyUser").click(function(e) {
            e.preventDefault(); 
            e.stopPropagation();
            showModal("Modifier un utilisateur", "utilisateurs/modifyUser.php?id="+e.currentTarget.dataset['id']);
        });
        $(".deleteUser").click(function(e) {
            e.preventDefault(); 
            e.stopPropagation();
            showModal("Suppression d'un utilisateur", "utilisateurs/deleteUser.php?id="+e.currentTarget.dataset['id']);
        });
        
        $("#addUser").click(function() {
            showModal("Ajouter un utilisateur", "utilisateurs/addUser.php");
        });

        function showModal(title, url)
        {
            $("#modalTitle").text(title);
            $.ajax({
                type: "GET",
                url: url,
                statusCode: {
                    403: function(){
                        location.reload();
                    }
                }
            }).done(function(data) {
                console.log(data);
                $("#modalBody").html(data);
            });
            $("#modal").modal('show');
            $("#modal").on('hidden.bs.modal', function (e) {
                location.reload();
            });
        }

    });
</script>

<?php
require '../footer.php';

