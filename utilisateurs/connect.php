<?php
$titre = "Connexion";

require '../header.php';
?>
<div class="jumbotron">
    <div class="container">
        <h1>Gestion des utilisateurs</h1>
    </div>
</div>
<div class="container">
    <div class="row">
        

    </div>

</div>

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

