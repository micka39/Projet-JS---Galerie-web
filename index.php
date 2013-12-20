<?php require 'bootstrap.php'; ?>
<!DOCTYPE html>
<!--[if IE 7 ]><html class="ie7"><![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->

    <head>
        <title>Galerie JS</title>
        <link href="css/design.css" rel="stylesheet">
        <script src="js/jquery-1.10.2.js" type="text/javascript"></script>
        <script src="js/galerie.jQuery.js" type="text/javascript"></script>
        <meta charset="utf-8"/>
    </head>

    <body>
        <h1 class="title">Galerie photos</h1>

        <div id="plugin-galerie">
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
                        echo "<li data-id='" . $image['idimage'] . "' data-large='" . $image['file_name'] . "_l." . strtolower($image['extension']) . "'"
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
        </div>
        


        <script type="text/javascript">
            $(document).ready(function() {
                $("#plugin-galerie").galerie(true, 2500, true, "download.php?id=");

            });


        </script>
    </body>

</html>