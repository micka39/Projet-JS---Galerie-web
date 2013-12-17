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
        <?php
        $images = new Images();
        $categories = $images->getCategories();
        foreach ($categories as $category) {

            echo "<ul class='hide category' data-name='" . $category['name'] . "' 
            data-id='" . $category['id'] . "' 
                data-description='" . $category['description'] . "'>";

            $listImages = $images->getPhotos($category['id']);
            if (!is_string($listImages)) {
                foreach ($listImages as $image) {
                    echo "<li data-id='" . $image['idimage'] . "' data-large='upload/" . $image['file_name'] . "_l." . $image['extension'] . "'"
                    . " data-medium='upload/" . $image['file_name'] . "_m." . $image['extension'] . "'"
                    . " data-small='upload/" . $image['file_name'] . "_s." . $image['extension'] . "'"
                    . " data-title='" . $image['title'] . "' data-description='" . $image['description'] . "'>"
                    . "<img src='upload/" . $image['file_name'] . "_s." . $image['extension'] . "'  alt='" . $image['description'] . "' class='img'/>"
                    . "</li>";
                }
            } else {
                echo $listImages;
            }
            echo "</ul>";
        }
        ?>
        <div class="lightbox-wrapper" id="lightbox">

            <div class="lightbox-images-category" id="lightbox-images-category"></div>
            <div class="lightbox-nav">
                <img src="img/close.png" height="40" width="40" alt="fermer la fenêtre" id="lightboxClose" class="lightbox-nav-close" />
                <img src="img/arrow-prev.png" height="60" width="60" alt="image précédente" id="lightbox-image-prev" />
                <img src="img/media-player.png" height="60" width="60" alt="lancer le diaporama" id="lightbox-image-play" />
                <img src="img/arrow-next.png" height="60" width="60" alt="image suivante" id="lightbox-image-next" />

            </div>
            <div class="lightbox-image" id="lightbox-image">
                <img src="upload/52a87ef732647_m.jpg" class="lightbox-img" id="lightbox-img"/>


            </div>
            <div class="lightbox-image-legend">
                <h1 id="lightbox-title">Une photo</h1>
                <hr/>
                <span id="lightbox-description">Image prise le 15 janvier 2013.Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas porttitor congue massa. Fusce posuere, magna sed pulvinar ultricies, purus lectus malesuada libero, sit amet commodo magna eros quis urna. Nunc viverra imperdiet enim. Fusce est. Vivamus a tellus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin pharetra nonummy pede. Mauris et orci. Aenean nec lorem. In porttitor. Donec laoreet nonummy augue. Suspendisse dui purus, scelerisque at, vulputate vitae, pretium mattis, nunc. Mauris eget neque at sem venenatis eleifend. Ut nonummy. Fusce aliquet pede non pede. Suspendisse dapibus lorem pellentesque magna. Integer nulla. Donec blandit feugiat ligula. Donec hendrerit, felis et imperdiet euismod, purus ipsum pretium metus, in lacinia nulla nisl eget sapien. Donec ut est in lectus consequat consequat. Etiam eget dui. Aliquam erat volutpat. Sed at lorem in nunc porta tristique. Proin nec augue.
                </span>

            </div>
        </div>


        <script type="text/javascript">
            
            
            var categories = new Array();
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
        showImages(category.images,category.id);
        $("#sidebar >ul>li").removeClass("active");
        $("#sidebar >ul>li[data-id='" + category.id + "']").addClass("active");
    }

    function showImages(images,idCategory)
    {
        var img = "";
        var imageNumber =1;
        $.each(images, function(i, image)
        {
            if (imageNumber === 1)
                img += "<div class='line'>";
            img += "<div class='img-group'>";
            img += "<img class='img' src='" + image.small + "' title='" + image.title + "' description='" + image.description + "'/>";
            img += "<div class='img-overlay' data-id='" + image.id + "' data-category='"+idCategory+"'></div></div>";
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
            var indexImage;
            var image = $.grep(category.images, function(image, i)
            {
                if (image.id === id)
                {
                    indexImage = i;
                    return image;
                }
            });
        showModal(image[0], category,indexImage);
    });
}

function showModal(image, category,index)
{
changeImage(category,index, true);
var img = "";
$.each(category.images, function(i, imageC)
{
    if (image.id === imageC.id)
    {
        img += "<img class='lightbox-images-category-img active' src='" 
            + imageC.small + "' title='" + imageC.title + "' description='" 
            + imageC.description + "' data-id='" + imageC.id + "' data-index='"+i+"'/>";
    }
    else
    {
        img += "<img class='lightbox-images-category-img' src='"
            + imageC.small + "' title='" + imageC.title + "' description='"
            + imageC.description + "' data-id='" + imageC.id + "' data-index='"+i+"'/>";
    }

});
$("#lightbox-images-category").html(img);
$("#lightbox-title").text(image.title);
//$("#lightbox-description").text(image.description);
$("#lightbox").fadeIn('fast',function(){
    $("div.lightbox-images-category").scrollTop($("#lightbox-images-category >img[data-id='" + image.id + "']").offset().top);
});
                
$("#lightboxClose").bind('click', function() {
    $("#lightbox").fadeOut();
    $(document).unbind("keyup");
});
$('body').bind('keyup', function(e) {
    var index =$("#lightbox-img").data("index");
    console.log($("#lightbox-img").data());
    var keycode = getCharCode(e);
    // Flèche gauche
    if (keycode == "37") {
        if((index -1) >=0)
            changeImage(category,index-1);
    }
    // Flèche droite
    if (keycode == "39") {
        changeImage(category,(index+1));
    }
    // Echap
    if (keycode == "27") {
        $("#lightbox").fadeOut();
        $(document).unbind("keyup");
    }

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

$(document).ready(function() {
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
                        
        console.log(category.id + " img" + image.id);
        images.push(image);
    });
    category.images = images;
    if(i === 0)
        htmlCategories += "<li class='category active' data-id='"+category.id+"'>" + category.name + "(" + images.length + " images)</li>";
    else
        htmlCategories += "<li class='category' data-id='"+category.id+"'>" + category.name + "(" + images.length + " images)</li>";
    categories.push(category);
}
);

htmlCategories += "</ul>";
$("#sidebar").append(htmlCategories);
showImages(categories[0].images,categories[0].id );
        
$(".category").click(function()
{
    id = $(this).data("id");
    var category = $.grep(categories, function(category, i)
    {
        if (category.id === id)
            return category;
    });
    console.log(category);
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

function changeImage(category,index,start) {
console.log(index);
                
if(index == 0)
    $("#lightbox-image-prev").addClass("hide");
else
    $("#lightbox-image-prev").removeClass("hide");
if(index == (category.images.length-1))
    $("#lightbox-image-next").addClass("hide");
else
    $("#lightbox-image-next").removeClass("hide");
                    
if (start !== undefined)
{
    console.log(category);
    $("#lightbox-img").attr("src", category.images[index].medium);
    $("#lightbox-img").attr("data-index", index);
}
else
{
    $("#lightbox-image").fadeOut(0, function() {
        image = category.images[index];
        console.log(image);
        $("#lightbox-img").attr("src", image.medium);
        $("#lightbox-img").data("index", index);
    });

    $("#lightbox-image").fadeIn(700);
}
}
        </script>
    </body>

</html>