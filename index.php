<?php require 'bootstrap.php'; ?>
<!DOCTYPE html>
<html>

    <head>
        <title>Galerie JS</title>
        <link href="css/design.css" rel="stylesheet">
        <script src="js/jquery-1.10.2.js" type="text/javascript"></script>
        <meta charset="utf-8"/>
    </head>

    <body>
        <h1>Titre !</h1>
        <div id="sidebar">

        </div>
        <div id="galery">

        </div>
        <ul class="category" data-name="Non catégorisé" data-id="1" data-description="Hello world">
            <?php
            $images = new Images();

            $listImages = $images->getPhotos(1);

            if (!is_string($listImages)) {
                foreach ($listImages as $image) {
                    echo "<li data-id='" . $image['idimage'] . "' data-large='upload/" . $image['file_name'] . "_l." . $image['extension'] . "'"
                    . " data-medium='upload/" . $image['file_name'] . "_m." . $image['extension'] . "'"
                    . " data-small='upload/" . $image['file_name'] . "_s." . $image['extension'] . "'"
                    . " data-title='" . $image['title'] . "' data-description='" . $image['description'] . "'>"
                    . "<img src='upload/" . $image['file_name'] . "_s." . $image['extension'] . "'  alt='abd' class='img'/>"
                    . "</li>";
                }
            } else {
                echo $listImages;
            }
            ?>
        </ul>
        <a href="#" id="modal2">Afficher la modale !</a>
        <div class="lightbox-wrapper" id="lightbox">
            <div class="lightbox-nav">
                <img src="img/close.png" height="40" width="40" alt="fermer la fenêtre" id="lightboxClose" class="lightbox-nav-close" />
                <img src="img/arrow-prev.png" height="60" width="60" alt="fermer la fenêtre" id="lightbox-image-prev" />
                <img src="img/arrow-next.png" height="60" width="60" alt="fermer la fenêtre" id="lightbox-image-next" />

            </div>
            <div class="lightbox-images-category" id="lightbox-images-category"></div>
            <div class="lightbox-image" id="lightbox-image">
                <img src="upload/52a87ef732647_m.jpg" class="lightbox-img" id="lightbox-img"/>


            </div>
            <div class="lightbox-image-legend">
                <h1>Une photo</h1>
                <hr/>Image prise le 15 janvier 2013.Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas porttitor congue massa. Fusce posuere, magna sed pulvinar ultricies, purus lectus malesuada libero, sit amet commodo magna eros quis urna. Nunc viverra imperdiet enim. Fusce est. Vivamus a tellus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin pharetra nonummy pede. Mauris et orci. Aenean nec lorem. In porttitor. Donec laoreet nonummy augue. Suspendisse dui purus, scelerisque at, vulputate vitae, pretium mattis, nunc. Mauris eget neque at sem venenatis eleifend. Ut nonummy. Fusce aliquet pede non pede. Suspendisse dapibus lorem pellentesque magna. Integer nulla. Donec blandit feugiat ligula. Donec hendrerit, felis et imperdiet euismod, purus ipsum pretium metus, in lacinia nulla nisl eget sapien. Donec ut est in lectus consequat consequat. Etiam eget dui. Aliquam erat volutpat. Sed at lorem in nunc porta tristique. Proin nec augue.


            </div>
        </div>


        <script type="text/javascript">
            function Category(name, description, id)
            {
                this.name = name;
                this.description = description;
                this.id = id;
                this.images;
            }

            function Image(small, medium, large, title, id, description)
            {
                this.small = small;
                this.medium = medium;
                this.large = large;
                this.title = title;
                this.id = id;
                this.description = description;
            }

            function showImages(images)
            {
                var img = "";
                $.each(images, function(i, image)
                {
                    if (imageNumber === 1)
                        img += "<div class='line'>";
                    img += "<div class='img-group'>";
                    img += "<img class='img' src='" + image.small + "' title='" + image.title + "' description='" + image.description + "'/>";
                    img += "<div class='img-overlay' data-id='" + image.id + "'></div></div>";
                    if (imageNumber >= 3)
                    {
                        img += "</div>";
                        imageNumber = 1;
                    }
                    else
                        imageNumber++;
                });

                return img;
            }
            
            function scrollToElement(selector, time, verticalOffset) {
			time = typeof(time) != 'undefined' ? time : 500;
			verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
			element = $(selector);
			offset = element.offset();
			offsetTop = offset.top + verticalOffset;
			$('html, body, div.lightbox-images-category').animate({
				scrollTop: offsetTop
			}, time);			
		}

            function showModal(image, category)
            {
                changeImage(image, true);
                var img = "";
                $.each(category.images, function(i, imageC)
                {
                    if (image.id === imageC.id)
                        img += "<img class='lightbox-images-category-img active' src='" + imageC.small + "' title='" + imageC.title + "' description='" + imageC.description + "' data-id='" + imageC.id + "'/>";
                    else
                        img += "<img class='lightbox-images-category-img' src='" + imageC.small + "' title='" + imageC.title + "' description='" + imageC.description + "' data-id='" + imageC.id + "'/>";

                });
                $("#lightbox-images-category").html(img);
                scr = $("#lightbox-images-category >img[data-id='" + image.id + "']").scrollTop();
                $("#lightbox").fadeIn('fast',function(){
                    window.setTimeout(1600,$("div.lightbox-images-category").scrollTop($("#lightbox-images-category >img[data-id='" + image.id + "']").offset().top));
                
                //scrollToElement("#lightbox-images-category >img[data-id='" + image.id + "']");
    });
                
                $("#lightboxClose").bind('click', function() {
                    $("#lightbox").fadeOut();
                    $(document).unbind("keyup");
                });
                $('body').bind('keyup', function(e) {
                    var keycode = getCharCode(e);
                    if (keycode == "37") {
                        changeImage();
                    }

                    if (keycode == "39") {
                        changeImage();
                    }

                });
            }

            $(document).ready(function() {
                imageNumber = 1;
                var categories = new Array();
                var htmlCategories = "<ul>";
                $(".category").each(function()
                {
                    var category = new Category(
                            $(this).data("name"),
                            $(this).data("description"),
                            $(this).data("id"));

                    var images = new Array();
                    $(".category[data-id='" + category.id + "'] >li").each(function(i, image)
                    {
                        var image = new Image();
                        image.description = $(this).data("description");
                        image.id = $(this).data("id");
                        image.large = $(this).data("large");
                        image.medium = $(this).data("medium");
                        image.small = $(this).data("small");
                        image.title = $(this).data("title");

                        images.push(image);
                    });
                    category.images = images;
                    htmlCategories += "<li>" + category.name + "(" + images.length + " images)</li>";
                    categories.push(category);
                }
                );

                htmlCategories += "</ul>";
                $("#sidebar").append(htmlCategories);
                $("#galery").append(showImages(categories[0].images));
                $(".img-overlay").click(function() {
                    console.log($(this).data("id"));
                    id = $(this).data("id");
                    var image = $.grep(categories[0].images, function(image, i)
                    {
                        if (image.id === id)
                            return image;
                    });
                    showModal(image[0], categories[0]);
                });
                $("#modal").click(function() {
                    $("#lightbox").fadeOut();
                    $(document).unbind("keyup");
                });
                $("#modal2").click(function() {
                    showModal(21, 0);
                });
                $("#lightbox-image-prev").click(function() {
                    $("#lightbox-image").fadeOut(500, function() {
                        $("#img").attr("src", "upload/52a87ef71b556_m.jpg");
                    });

                    $("#lightbox-image").fadeIn(700);
                });

                $("#lightbox-image-next").click(function() {
                    changeImage();
                });

            });

            // Basé sur http://javascript.info/tutorial/keyboard-events
            function getCharCode(event) {
                if (event.which == null) {
                    return event.keyCode // IE
                } else if (event.which != 0 || event.charCode != 0) {
                    return event.which   // the rest
                } else {
                    return null // special key
                }
            }

            function changeImage(image, start) {
                if (start !== undefined)
                {
                    $("#lightbox-img").attr("src", image.medium);
                }
                else
                {
                    $("#lightbox-image").fadeOut(0, function() {
                        $("#img").attr("src", image.medium);
                    });

                    $("#lightbox-image").fadeIn(700);
                }
            }
        </script>
    </body>

</html>