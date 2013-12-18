(function($) {

    $.fn.galerie = function() {

        //contenu du plugin

        var categories = new Array();

        imageNumber = 1;
        var htmlCategories = "<ul>";
        $(".category").each(function(i)
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
            if (i === 0)
                htmlCategories += "<li class='category active' data-id='" + category.id + "'>" + category.name + "(" + images.length + " images)</li>";
            else
                htmlCategories += "<li class='category' data-id='" + category.id + "'>" + category.name + "(" + images.length + " images)</li>";
            categories.push(category);
        }
        );

        htmlCategories += "</ul>";
        $("#sidebar").append(htmlCategories);
        showImages(categories[0].images, categories[0].id);

        $(".category").click(function()
        {
            id = $(this).data("id");
            var category = $.grep(categories, function(category, i)
            {
                if (category.id === id)
                    return category;
            });
            showCategory(category[0]);
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

        function showCategory(category)
        {
            showImages(category.images, category.id);
            $("#sidebar >ul>li").removeClass("active");
            $("#sidebar >ul>li[data-id='" + category.id + "']").addClass("active");
        }

        function showImages(images, idCategory)
        {
            var img = "";
            var imageNumber = 1;
            $.each(images, function(i, image)
            {
                if (imageNumber === 1)
                    img += "<div class='line'>";
                img += "<div class='img-group'>";
                img += "<img class='img' src='" + image.small + "' title='" + image.title + "' description='" + image.description + "'/>";
                img += "<div class='img-overlay' data-id='" + image.id + "' data-category='" + idCategory + "'></div></div>";
                if (imageNumber >= 3)
                {
                    img += "</div>";
                    imageNumber = 1;
                }
                else
                    imageNumber++;
            });

            $("#galery").html("");
            $("#galery").append(img);
            $(".img-overlay").click(function() {
                id = $(this).data("id");
                idCategory = $(this).data("category");

                var category = getCategoryById(idCategory);
                console.log(category);
                var indexImage;
                var image = $.grep(category.images, function(image, i)
                {
                    if (image.id === id)
                    {
                        indexImage = i;
                        return image;
                    }
                });
                showModal(image[0], category, indexImage);
            });
        }

        function showModal(image, category, index)
        {
            var img = "";
            var diaporama;
            var positionMainScrollBar = $(document).scrollTop();
            $.each(category.images, function(i, imageC)
            {
                img += "<img class='lightbox-images-category-img' src='"
                        + imageC.small + "' title='" + imageC.title + "' description='"
                        + imageC.description + "' data-id='" + imageC.id + "' data-index='" + i + "'/>";
            });
            $("#lightbox-images-category").html(img);
            
            $("#lightbox").fadeIn('slow', function() {
                
                $("html,body").scrollTop(0);
            changeImage(category, index, true);
            });

            $("#lightboxClose").click(function() {
                exitLightbox(positionMainScrollBar);
            });
            $("#lightbox-image-stop").click(function() {
                clearInterval(diaporama);

                $("#lightbox-image-stop").addClass("hide");
                $("#lightbox-image-play").removeClass("hide");
            });


            $("#lightbox-image-play").click(function()
            {
                diaporama = window.setInterval(
                        function() {
                            var index = $("#lightbox-img").data("index");
                            changeImage(category, (index + 1))
                        }, 2500);
                $("#lightbox-image-stop").removeClass("hide");
                $("#lightbox-image-play").addClass("hide");
            }
            );
            $('body').bind('keyup', function(e) {
                var index = $("#lightbox-img").data("index");
                var keycode = getCharCode(e);
                // Flèche gauche
                if (keycode == "37") {
                    changeImage(category, index - 1);
                }
                // Flèche droite
                if (keycode == "39") {
                    changeImage(category, (index + 1));
                }
                // Echap
                if (keycode == "27") {
                    exitLightbox(positionMainScrollBar);
                }

            });
        }
        
        function exitLightbox(positionMainScrollBar)
        {
            $("html,body").scrollTop(positionMainScrollBar);
                    $("#lightbox").fadeOut('fast', function()
                    {
                        $("#lightbox-img").attr("src", '');
                        $('body').unbind("keyup");
                    });
        }

        function getCategoryById(id)
        {
            var category = $.grep(categories, function(category, i)
            {
                if (category.id === id)
                    return category;
            });
            return category[0];
        }

        /**
         * Récupération du code touche tapé basé sur
         * @author http://javascript.info/tutorial/keyboard-events
         * @param {event} event Evenement keyUp
         * @returns {integer|null} Code touche ou null
         */
        function getCharCode(event) {
            if (event.which == null) {
                return event.keyCode // IE
            } else if (event.which != 0 || event.charCode != 0) {
                return event.which   // the rest
            } else {
                return null // special key
            }
        }

        /**
         * Permet de changer d'image dans la lightbox
         * @param {Category} category La catégorie affichée dans la lightbox
         * @param {integer} index L'index de l'image à afficher
         * @param {boolean} start Boolean indiquant s'il s'agit du premier lancement
         */
        function changeImage(category, index, start) {

            // Gestion de l'affichage des boutons suivant/précédent
            if (index === 0)
                $("#lightbox-image-prev").addClass("hide");
            else
                $("#lightbox-image-prev").removeClass("hide");
            console.log(category.images);
            if ((index +1) === (category.images.length - 1))
            {
                $("#lightbox-image-next").addClass("hide");
            }
            else
                $("#lightbox-image-next").removeClass("hide");
            // Permet au diaporama de boucler une fois arrivé à la fin du tableau
            if (index > (category.images.length - 1))
            {
                index = 0;
                $("#lightbox-image-next").removeClass("hide");
            }
            if (index < 0)
                index = category.images.length - 1;

            // Récupération de l'image
            image = category.images[index];
            // Au lancement de la lightbox avec création de l'attribut data-index
            if (start !== undefined)
            {
                $("#lightbox-img").attr("src", image.medium);
                $("#lightbox-img").attr("data-index", index);
            }
            else
            {
                $("#lightbox-image").fadeOut(0, function() {
                    $("#lightbox-img").attr("src", image.medium);
                    $("#lightbox-img").data("index", index);
                });

                $("#lightbox-image").fadeIn(700);


            }
            // Définition du titre et de la description
            $("#lightbox-title").text(image.title);
            $("#lightbox-description").text(image.description);

            // Mise à jour de l'image affichée dans la liste des images de la catégorie
            $(".lightbox-images-category-img").removeClass("active");
            $(".lightbox-images-category-img[data-id='" + image.id + "']").addClass("active");
            // Mise à jour du niveau de scroll en fonction de l'image active sur 800 ms
            $(".lightbox-images-category").animate({scrollTop: $("#lightbox-images-category >img[data-id='" + image.id + "']").offset().top + $("div.lightbox-images-category").scrollTop() - 10}, 800);
        }
        // Retour de l'objet
        return this;

    };

}(jQuery));
