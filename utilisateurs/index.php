<?php
$title = "Gestion des utilisateurs";

require '../header.php';
?>
<div class="jumbotron">
    <div class="container">
        <h1>Gestion des utilisateurs</h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <p><a href="utilisateurs/ajouter">Ajouter un nouvel utilisateur</a></p>
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
                <tr><td><?php echo $user['iduser'];?></td>
                    <td><?php echo $user['fullname'];?></td>
                    <td><?php echo $user['username'];?></td>
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

<?php
require '../footer.php';
?>
