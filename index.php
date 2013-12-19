<?php require 'bootstrap.php'; ?>
<!DOCTYPE html>
<html>

    <head>
        <title>Galerie JS</title>
        <link href="css/design.css" rel="stylesheet">
        <script src="js/jquery-1.10.2.js" type="text/javascript"></script>
        <script src="js/galerie.jQuery.js" type="text/javascript"></script>
        <meta charset="utf-8"/>
    </head>

    <body>
        <h1>Titre !</h1>
        <div id="sidebar">

        </div>
        <div id="galery">

        </div>
        <?php
        $images = new Images();
        $categories = $images->getCategories();
        foreach ($categories as $category) {

            echo "<ul class='hide category' data-name='" . $category['name'] . "' 
            data-id='" . $category['id'] . "' 
                data-description='" . $category['description'] . "'>";

            $listImages = $images->getPhotos($category['id']);
            if ($listImages != "Il n'y a pas de photos dans cet album") {
                if (!is_string($listImages)) {
                    foreach ($listImages as $image) {
                        echo "<li data-id='" . $image['idimage'] . "' data-large='" . $image['file_name'] . "_l." . $image['extension'] . "'"
                        . " data-medium='upload/" . $image['file_name'] . "_m." . $image['extension'] . "'"
                        . " data-small='upload/" . $image['file_name'] . "_s." . $image['extension'] . "'"
                        . " data-title='" . $image['title'] . "' data-description='" . $image['description'] . "'>"
                        . "<img src='upload/" . $image['file_name'] . "_s." . $image['extension'] . "'  alt='" . $image['description'] . "' class='img'/>"
                        . "</li>\n";
                    }
                } else {
                    echo $listImages;
                }
            }
            echo "</ul>";
        }
        ?>
        <div class="lightbox-wrapper" id="lightbox">

            <div class="lightbox-images-category" id="lightbox-images-category"></div>
            <div class="lightbox-nav">
                <img src="img/close.png" height="40" width="40" alt="fermer la fenêtre" title="fermer la fenêtre" id="lightboxClose" class="lightbox-nav-close" />
                <img src="img/download.png" class="hide" height="60" width="60" alt="télécharger l'image" title="télécharger l'image" id="lightbox-image-download" />
                <img src="img/arrow-prev.png" height="60" width="60" alt="image précédente" title="image précédente" id="lightbox-image-prev" />
                <img src="img/media-player.png" height="60" width="60" alt="lancer le diaporama" title="lancer le diaporama" id="lightbox-image-play" />
                <img src="img/media-stop.png" class="hide" height="60" width="60" alt="arrêter le diaporama" title="arrêter le diaporama" id="lightbox-image-stop" />
                <img src="img/arrow-next.png" height="60" width="60" alt="image suivante" title="image suivante" id="lightbox-image-next" />

            </div>
            <div class="lightbox-image" id="lightbox-image">
                <img class="lightbox-img" src="" title="" alt="" id="lightbox-img" />
            </div>
            <div class="lightbox-image-legend">
                <h1 id="lightbox-title">Une photo</h1>
                <hr/>
                <span id="lightbox-description">Image prise le 15 janvier 2013.Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas porttitor congue massa. Fusce posuere, magna sed pulvinar ultricies, purus lectus malesuada libero, sit amet commodo magna eros quis urna. Nunc viverra imperdiet enim. Fusce est. Vivamus a tellus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin pharetra nonummy pede. Mauris et orci. Aenean nec lorem. In porttitor. Donec laoreet nonummy augue. Suspendisse dui purus, scelerisque at, vulputate vitae, pretium mattis, nunc. Mauris eget neque at sem venenatis eleifend. Ut nonummy. Fusce aliquet pede non pede. Suspendisse dapibus lorem pellentesque magna. Integer nulla. Donec blandit feugiat ligula. Donec hendrerit, felis et imperdiet euismod, purus ipsum pretium metus, in lacinia nulla nisl eget sapien. Donec ut est in lectus consequat consequat. Etiam eget dui. Aliquam erat volutpat. Sed at lorem in nunc porta tristique. Proin nec augue.
                </span>

            </div>
        </div>


        <script type="text/javascript">
            $(document).ready(function() {
                $(document).galerie(true, 2500, true, "http://localhost/js/download.php?id=");

            });


        </script>
    </body>

</html>