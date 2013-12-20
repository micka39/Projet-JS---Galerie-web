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
        $max_size = ini_get("upload_max_filesize");
        if ($file['error'] == 1) {
            $response = array(
                "success" => "false",
                "message" => $_FILES['photos']['name'] . " - ce fichier dépasse la taille autorisée par le serveur ($max_size) !",
                "code" => "1"
            );
        } else
            switch ($file['type']) {
                case "image/png":
                    $image->addImage($_POST['category'], $_FILES['photos']['name'], $_FILES['photos']['tmp_name']);
                    break;
                case "image/jpeg":
                    $image->addImage($_POST['category'], $_FILES['photos']['name'], $_FILES['photos']['tmp_name']);
                    break;
                case "image/pjpeg":
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
                        "code" => "0",
                        "file" => $_FILES['photos']['name']
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
                    <?php
                    
    $image = new Images();
    $categories = $image->getCategories();
    foreach ($categories as $category) {
        if($category['id'] == 1)
        echo "<option value='".$category['id']."' selected>".$category['name']."</option>";
        else
            echo "<option value='".$category['id']."'>".$category['name']."</option>";
    }
                    ?>
                </select>
            </div>
            <p>Les titres et description de chaque image sont définis par défaut. Vous pouvez les modifier ensuite</p>
            <p>Formats acceptés: JPG, PNG</p>
            <div class="form-group col-xs-12">
                <label for="photos" class="control-label">Image</label>
                <input type="file" multiple name="photos" id="photos" class="form-control" />
            </div>
            <div class="droparea" id="droparea">
                <p><img src="img/draganddrop.png" alt="Image représentant un glisser déposer"/> Glissez vos images ici</p>
            </div>
            <div class="hide" id="wait"><img class="center" src="img/wait.png" alt="Envoi en cours" title="Envoi en cours"/>
            <div class="progress progress-striped active">
  <div class="progress-bar" id="progress" style="width: 0%;">
  </div>
</div></div>
            
            <ul id="results"></ul>
        </form>
    </div>
    <!-- Javascript pour le formulaire d'ajout d'utilisateur. -->
    <script type="text/javascript">
        // Pour l'avancement de la barre de progression
        var fileInUpload =0;
        $(function () {
            // Envoi des photos
            $('#photos').fileupload({
                beforeSend: function(){
                    fileInUpload ++;
                    $("#wait").removeClass("hide");
                    $("#progress").text("Envoi en cours");
                },
                url: 'images/addImage.php',
                dataType: 'json',
                done: function (e, data) {
                    var json1 = data['result'];
                    if(json1.success == "true")
                    $("#results").prepend("<li>"+json1.file+" est bien enregistré !</li>");
                    else
                    $("#results").prepend("<li class='alert alert-danger'>"+json1.message+"</li>");
                // Pour l'avancement de la barre de progression
                    fileInUpload --;
                    $("#progress").css("width", 100/fileInUpload+ "%");
                    if(fileInUpload == 0)
                    {
                        $("#progress").text("Terminé !")
                        
                    $("#wait").addClass("hide");
                    }
                }});
        });
                            
        $(document).ready(function() {
            // Si le drag and drop n'est pas supporté,on enlève la zone de drop
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
