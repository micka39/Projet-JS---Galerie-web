<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Projet de galerie web IUT A - Bourg en Bresse">
        <base href="http://localhost/js/"/>
        <title><?php echo $titre; ?></title>

        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/functions_backoffice.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
            $(document).ready(function() {
                $("#connect").submit(function(event) {
                    event.preventDefault();
                    var data = "username=" + $("usernameConnect").val() + "&password=" + $("passwordConnect").val();
                    $("#buttonConnect").button('loading');
                    $.ajax({
                        type: "POST",
                        url: "ajax/users/connect.php",
                        data: data,
                        success: function(result) {
                            if(result)
                            $("#buttonConnect").text("fini !");
                        },
                        error: function(xhr, type, thrownError) {

                            alert(thrownError);

                        }

                    });
                });
            });
        </script>
    </head>

    <body>

        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Afficher la navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Galerie web</a>
                </div>
                <div class="navbar-collapse collapse">
                    <form class="navbar-form navbar-right" role="form" id="connect">
                        <div class="form-group">
                            <input type="text" name="username" id="usernameConnect" class="form-control" placeholder="Nom d'utilisateur">
                        </div>
                        <div class="form-group">

                            <input type="password" name="password" id="passwordConnect" class="form-control" placeholder="Mot de passe">
                        </div>
                        <button type="submit" class="btn btn-default" id="buttonConnect" data-loading-text="Connexion en cours ...">Connexion</button>>
                    </form>
                </div><!--/.navbar-collapse -->
            </div>
        </div>