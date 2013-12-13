
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
          <label for="autocomplete" style="display: block">Rechercher une image:</label>
           <input type="text" class="form-control" id="autocomplete" name="autocomplete" style="width: 25%; display: inline" data-provide="typeahead" placeholder="Rechercher">
           <button type="button" class="btn btn-default" style="display: inline" >Rechercher</button>
           
           <script type="text/javascript">
$(function (){
   var deps = ['Ain','Aisne','Allier','Alpes de-Htes','Alpes de-Htes Provence','Hautes-Alpes',
'Alpes-Maritimes','Ardèche','Ardennes','Ariege','Aube','Aude','Aveyron','Bouches-du-Rhône','Calvados',
'Cantal','Charente','Charente-Maritime','Cher','Correze','Côte d\'Or', 'Côtes d\'Armor','Creuse',
'Dordogne','Doubs','Drome','Eure','Eure-et-Loire','Finistère'];
   $('#autocomplete').typeahead({source: deps});
});  
</script>
      </div> 

      <hr>


<?php include_once("footer.php"); ?>