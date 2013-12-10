<?php
$titre = "Gestion des images";

require_once '../bootstrap.php';
require '../header.php';
?>
<div class="jumbotron">
    <div class="container">
        <h1>Gestion des images</h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <p><button class="btn btn-primary" id="addUser">Envoyer de nouvelles images</button></p>
    </div>
    <div class="row">

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
            showModal("Ajout d'image(s)", "images/addImage.php");
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

