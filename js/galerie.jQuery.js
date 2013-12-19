(function($) {

    /**
     * Plugin de galerie jQuery
     * @param {boolean} responsive True si les trois types d'image sont renseignés
     * @param {integer} duration La durée en ms entre chaque slide durant le diaporama
     * @returns {_L1.$.fn} Retourne l'objet
     */
    $.fn.galerie = function(responsive, duration, download, url) {

        //contenu du plugin

        var categories = new Array();

        var diaporama;
        var lightbox = '<div id="sidebar"></div><div id="galery"></div><div class="lightbox-wrapper" id="lightbox">'
                + '<div class="lightbox-images-category" id="lightbox-images-category"></div>'
                + '<div class="lightbox-nav">'
                + '<img src="img/close.png" height="40" width="40" alt="fermer la fenêtre" title="fermer la fenêtre" id="lightboxClose" class="lightbox-nav-close" />'
                + '<img src="img/download.png" class="hide" height="60" width="60" alt="télécharger l\'image" title="télécharger l\'image" id="lightbox-image-download" />'
                + '<img src="img/arrow-prev.png" height="60" width="60" alt="image précédente" title="image précédente" id="lightbox-image-prev" />'
                + '<img src="img/media-player.png" height="60" width="60" alt="lancer le diaporama" title="lancer le diaporama" id="lightbox-image-play" />'
                + '<img src="img/media-stop.png" class="hide" height="60" width="60" alt="arrêter le diaporama" title="arrêter le diaporama" id="lightbox-image-stop" />'
                + '<img src="img/arrow-next.png" height="60" width="60" alt="image suivante" title="image suivante" id="lightbox-image-next" />'
                + '</div><div class="lightbox-image" id="lightbox-image">'
                + '<img class="lightbox-img" src="" title="" alt="" id="lightbox-img" data-index="0" />'
                + '</div>'
                + '<div class="lightbox-image-legend">'
                + '<h1 id="lightbox-title">Une photo</h1>'
                + '<hr/>'
                + '<span id="lightbox-description">'
                + '</span>'
                + '</div>'
                + '</div>';
        this.append(lightbox);
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
                var image = new ImageClass();
                image.description = $(this).data("description");
                image.id = $(this).data("id");
                if (responsive === true)
                {
                    image.large = $(this).data("large");
                    image.medium = $(this).data("medium");
                    image.small = $(this).data("small");
                }
                else
                {
                    image.medium = $(this).data("medium");
                    image.large = image.medium;
                    image.small = image.medium;
                }
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

        function Category(name, description, id)
        {
            this.name = name;
            this.description = description;
            this.id = id;
            this.images = new Array();
        }

        function ImageClass(small, medium, large, title, id, description)
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
            var imgToDownload = new Array();
            var img = "";
            var imageNumber = 1;
            $.each(images, function(i, image)
            {
                if (imageNumber === 1)
                    img += "<div class='line'>";
                img += "<div class='img-group'>";
                img += "<img class='img' src='" + image.small + "' title='" + image.title + "' alt='" + image.description + "'/>";
                img += "<div class='img-overlay' data-id='" + image.id + "' data-category='" + idCategory + "'></div></div>";
                if (imageNumber >= 3)
                {
                    img += "</div>";
                    imageNumber = 1;
                }
                else
                    imageNumber++;
                imgToDownload.push(image.medium);
            });

            $("#galery >.line").remove();
            $("#galery").append(img);
            downloadImages(imgToDownload);
            $(".img-overlay").click(function() {
                id = $(this).data("id");
                idCategory = $(this).data("category");

                var category = getCategoryById(idCategory);
                var indexImage;
                var image = $.grep(category.images, function(image, i)
                {
                    if (image.id === id)
                    {
                        indexImage = i;

                        $("#lightbox-img").attr("src", image.medium);
                        return image;
                    }
                });
                showModal(image[0], category, indexImage);
            });
        }

        function downloadImages(listImages)
        {
            $.each(listImages, function(i, url)
            {
                var img = new Image();
                img.src = url;
            });
        }

        function showModal(image, category, index)
        {
            var img = "";
            var positionMainScrollBar = $(document).scrollTop();
            $.each(category.images, function(i, imageC)
            {
                img += "<img class='lightbox-images-category-img' src='"
                        + imageC.small + "' title='" + imageC.title + "' description='"
                        + imageC.description + "' data-id='" + imageC.id + "' data-index='" + i + "'/>";
            });
            $("#lightbox-images-category").html(img);
            if (download)
            {
                $("#lightbox-image-download").removeClass("hide");
                $("#lightbox-image-download").click(function()
                {
                    var index = $("#lightbox-img").data("index");
                    window.open(url + category.images[index].large);
                });
            }
            $("#lightbox").fadeIn('slow', function() {

                $("html,body").scrollTop(0);
                changeImage(category, index);
            });

            $("#lightboxClose").click(function() {
                exitLightbox(positionMainScrollBar);
            });
            $("#lightbox-image-stop").click(function() {
                clearInterval(diaporama);

                $("#lightbox-image-stop").addClass("hide");
                $("#lightbox-image-play").removeClass("hide");
            });

            $(".lightbox-images-category-img").click(function() {
                var index = $(this).data("index");
                changeImage(category, index);
            });


            $("#lightbox-image-play").click(function()
            {
                diaporama = window.setInterval(
                        function() {
                            var index = $("#lightbox-img").data("index");
                            changeImage(category, (index + 1), true);
                        }, duration);
                $("#lightbox-image-stop").removeClass("hide");
                $("#lightbox-image-play").addClass("hide");
            }
            );



            $("#lightbox-image-prev").click(function() {
                var index = $("#lightbox-img").data("index");
                changeImage(category, index - 1);
            });

            $("#lightbox-image-next").click(function() {
                var index = $("#lightbox-img").data("index");
                changeImage(category, index + 1);
            });

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

        /**
         * Fonction permettant de quitter la lightbox
         * @param {integer} positionMainScrollBar La position de la barre de scroll au départ
         */
        function exitLightbox(positionMainScrollBar)
        {
            // Positionne la barre de scroll principale
            $("html,body").scrollTop(positionMainScrollBar);
            $("#lightbox").fadeOut('fast', function()
            {
                $("#lightbox-img").attr("src", '');
                $('body').unbind("keyup");
                $("#lightboxClose").unbind("click");
                $("#lightbox-image-stop").unbind("click");
                $("#lightbox-image-play").unbind("click");
                $("#lightbox-image-prev").unbind("click");
                $("#lightbox-image-next").unbind("click");
                $(".lightbox-images-category-img").unbind("click");
                if (download)
                    $("#lightbox-image-download").unbind("click");

            });
        }

        /**
         * Retourne la catégorie en fonction de son id
         * @param {integer} id Id a rechercher
         * @returns {Category} Categorie
         */
        function getCategoryById(id)
        {
            // Parcours de toutes les catégories jusqu'à trouver la bonne catégorie
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
            if (event.which === null) {
                return event.keyCode; // IE
            } else if (event.which !== 0 || event.charCode !== 0) {
                return event.which;   // the rest
            } else {
                return null; // special key
            }
        }

        /**
         * Permet de changer d'image dans la lightbox
         * @param {Category} category La catégorie affichée dans la lightbox
         * @param {integer} index L'index de l'image à afficher
         * @param {boolean} start Boolean indiquant s'il s'agit du premier lancement
         */
        function changeImage(category, index, inDiaporama) {
            // Arrêt du diaporama si on y est plus
            if (inDiaporama === undefined)
            {
                clearInterval(diaporama);
                $("#lightbox-image-stop").addClass("hide");
                $("#lightbox-image-play").removeClass("hide");
            }

            // Permet au diaporama de boucler une fois arrivé à la fin du tableau
            if (index > (category.images.length - 1))
            {
                index = 0;
            }
            if (index < 0)
                index = category.images.length - 1;

            // Récupération de l'image
            image = category.images[index];

            $("#lightbox-image").fadeOut(0, function() {
                $("#lightbox-img").attr("src", image.medium);
                $("#lightbox-img").attr("title", image.title);
                $("#lightbox-img").attr("alt", image.description);
                $("#lightbox-img").data("index", index);
            });
            $("#lightbox-image").fadeIn(700);



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
