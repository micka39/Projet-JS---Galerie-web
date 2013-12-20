<?php

$extension;
$file = $_GET['id'];
$tab = explode(".", $file);
if(is_file("upload/".$file))
{
switch ($tab[1]) {
    case 'jpg': {
            header('Content-type: image/jpg');
            header('Content-Disposition: attachment; filename="'.$file.'"');
            readfile(__DIR__."/upload/".$file);
        }
        break;
    case 'jpeg':
            header('Content-type: image/jpeg');
            header('Content-Disposition: attachment; filename="'.$file.'"');
            readfile(__DIR__."/upload/".$file);
        break;

    case 'png': {
            header('Content-type: image/png');
            header('Content-Disposition: attachment; filename="'.$file.'"');
            readfile(__DIR__."/upload/".$file);
        }

        break;

    default:
        echo "Mauvaise requete";
        break;
}
}
else
{
    echo "Mauvaise requete";
}