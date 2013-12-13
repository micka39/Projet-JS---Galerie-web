
<?php 
$titre = "Hello World";
include_once("header.php"); ?>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Galerie web</h1>
        
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
          <a href="category.php"><button type="button" class="btn btn-default navbar-btn">Categorie</button></a>
          <label for="typeahead" style="display: block">Rechercher une image:</label>
           <input type="text" class="form-control" id="typeahead" name="typeahead" style="width: 25%; display: inline" data-provide="typeahead" placeholder="Rechercher" autocomplete="off">
           <button type="button" class="btn btn-default" style="display: inline" >Rechercher</button>
           
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#typeahead').typeahead({
                        source: function (query, process) {
                            $.ajax({
                                url: 'data.php',
                                type: 'POST',
                                dataType: 'JSON',
                                data: 'query=' + query,
                                success: function(data) {
                                    process(data);
                                }
                            });
                        }
                    });
                });
            </script>
            
      </div> 

      <hr>


<?php include_once("footer.php"); ?>