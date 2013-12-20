<?php
$titre = "Index";
include_once("header.php"); 
?>
      

      
      <div id="corp">
          
          
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
            




      <hr>
      
      </div>
      </body>
      
      <div id="footer">
      <footer>
        
      </footer>
    
      </div>

    
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/typeahead.js"></script>
    <script src="js/bootstrap.min.js"></script>
   
    
  </body>
</html>

