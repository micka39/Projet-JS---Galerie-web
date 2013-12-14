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
        <p><button class="btn btn-primary" id="addImage">Envoyer de nouvelles images</button></p>
    </div>
    <div class="row">
        <?php
        if (!is_string($listImages)) {
            foreach ($listImages as $image) {
                echo "<img src='upload/" . $image['file_name'] . "_s." . $image['extension'] . "' data-id='" . $image['idimage'] . "' alt='abd' class='img img-overlay img-modal'>";
            }
        } else {
            echo $listImages;
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
        $("#addImage").click(function() {
            showModal("Ajout d'image(s)", "images/addImage.php");
        });
        $(".img-modal").click(function() {
            showModal("Modification d'image", "images/modifyImage.php?id=" + $(this).data("id"));
        });

        function showModal(title, url)
        {
            $("#modalTitle").text(title);
            $.ajax({
                type: "GET",
                url: url,
                statusCode: {
                    403: function() {
                        location.reload();
                    }
                }
            }).done(function(data) {
                $("#modalBody").html(data);
            });
            $("#modal").modal('show');
        }

    });
</script>

<?php
require '../footer.php';

