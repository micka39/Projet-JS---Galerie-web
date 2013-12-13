<?php 

require_once '../bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $connection = new Connection();
    if($connection->deleteUser($_POST['id']))
        echo "L'utilisateur a bien été supprimé";
    else
        echo "Erreur durant la suppression";
}
 else {
?>    

<h3> Souhaitez-vous vraiment supprimer cet utilisateur ?</h3>
<button class="btn btn-success" id="no">Annuler la suppression</button>
<button class="btn btn-danger" id="yes">Oui je souhaite le supprimer définitivement.</button>
<script type="text/javascript">
    $(document).ready(function(){
        $("#yes").click(function(){
            $.ajax({
                type: "POST",
                url: "utilisateurs/deleteUser.php",
                data: "id=<?php echo $_GET['id'];?>",
                success: function(result) {
                    $("#modalBody").html(result);
                    $("#modal").modal('show');
                }

            });
        });
        $("#no").click(function(){
        $("#modal").modal('hide');
        });
        
        
    });
</script>
<?php
}
?>