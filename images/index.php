<?php
$titre = "Gestion des images";

require_once '../bootstrap.php';
require '../header.php';
$images = new Images();
$listImages = $images->getPhotos("1");
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
        <?php
        foreach ($listImages as $image) {
            echo "<img src='upload/". $image['file_name'] . "_s.". $image['extension'] ."' data-id='".$image['idimage']."' alt='abd' class='img-thumbnail img-modal'>";
        }
        ?>
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
        $(".img-modal").click(function(e) {
            
            showModal("Modification d'image", "images/modifyImage.php?id="+e.currentTarget.dataset['id']);
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

