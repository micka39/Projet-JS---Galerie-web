<?php

require_once '../bootstrap.php';
/*
 * Gère la génération du formulaire d'ajout, la vérification serveur et l'ajout
 * en base de données d'un nouvel utilisateur
 */
// Vérification de l'existence du champs de formulaire fullname 
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $response = "";
    $image = new Images();
    if (!empty($_FILES)) {
        $file = $_FILES['photos'];
        // Vérification du type de fichier
        switch ($file['type']) {
            case "image/png":
                $image->addImage($_POST['category'], $_FILES['photos']['name'], $_FILES['photos']['tmp_name']);
                break;
            case "image/jpeg":
                $image->addImage($_POST['category'], $_FILES['photos']['name'], $_FILES['photos']['tmp_name']);
                break;
            default: {
                    $response = array(
                        "success" => "false",
                        "message" => $_FILES['photos']['name'] . " - ce fichier n'est pas une image (png/jpeg) !",
                        "code" => "1"
                    );
                }
                break;
        }
        if($response == "")
            $response = array(
                        "success" => "true",
                        "code" => "0"
                    );
    }
    else {
        $response = array(
            "success" => "false",
            "message" => "Aucun fichier",
            "code" => "0"
        );
    }
    echo json_encode($response);
} else {
    showForm();
}

function showForm() {
    ?>
    <script src="js/vendor/jquery.ui.widget.js"></script>
    <script src="js/jquery.iframe-transport.js"></script>
    <script src="js/jquery.fileupload.js"></script>
    <div class="row">
        <form action="#" method="POST" class="form-horizontal" id="formAddUser">
            <p>Attention les fichiers sont envoyés dés qu'ils sont glissés/déposés ou alors à la selection</p>
            <div class="form-group col-xs-12">
                <label for="category" class="control-label">Catégorie (vous pouvez en sélectionner plusieurs)</label>
                <select name="category[]" id="category" class="form-control" multiple >
                    <option value="1" selected>Non catégorisé</option>
                    <option value="2">Divers</option>
                    <option value="3">Nature</option>
                </select>
            </div>

            <p>Formats acceptés: JPG, PNG</p>
            <div class="form-group col-xs-12">
                <label for="photos" class="control-label">Image</label>
                <input type="file" multiple name="photos" id="photos" class="form-control" />
            </div>
            <div class="droparea" id="droparea">
                <p><img src="img/draganddrop.png" alt="Image représentant un glisser déposer"/> Glissez vos images ici</p>
            </div>

            <ul id="results"></ul>
        </form>
    </div>
    <!-- Javascript pour le formulaire d'ajout d'utilisateur. -->
    <script type="text/javascript">
        var fileInUpload =0;
        $(function () {
            $('#photos').fileupload({
                beforeSend: function(){
                    fileInUpload ++;
                },
                url: 'images/addImage.php',
                dataType: 'json',
                done: function (e, data) {
                    var json1 = data['result'];
                    $.each(json1.photos, function(key, val) {
                        alert(key + ' ' + val);
                    });
                    fileInUpload --;
                }});
        });
                            
        $(document).ready(function() {
            if(!('draggable' in document.createElement('span')))
            {
                $("#droparea").css(
                "display",'none');
            }
        });
    </script>
    <?php

}
?>
