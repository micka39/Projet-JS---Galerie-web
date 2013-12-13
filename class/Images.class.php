<?php

class Images {

    private $_image_tb_name = "images";
    private $_image_com_tb_name = "images";
    private $_images_par_page = 20;
    private $_albums_par_page = 20;
    private $db;

    public function __construct() {
        require_once(__DIR__ . '/config.php');
        $this->db = connectPdo();
    }

    public function getPhoto($photo_id) {
        $sql = "SELECT a.id as album_id ,a.nom as album_nom, a.descri as album_desc,a.token as token,
            i.id as image_id, i.nom as photo_nom,i.nom_fichier_m as photo_fichier,
            i.descri as photo_desc ,i.visibilite as photo_visibilite, i.nom_fichier_th as photo_thumb,i.nom_fichier as photo_o FROM albums as a, images as i WHERE a.id = i.id_album AND i.id =" . $photo_id;
        $result = $this->_db->query($sql);

        $results = $result->fetch();
        return $results;
    }

    public function getPhotos($album_id, $limite_bas = null) {
        $result = $this->db->query("SELECT COUNT(*) as nb_photos FROM imagecategory WHERE category_idcategory=" . $album_id);
        $results = $result->fetch();
        //SELECT i.extension,i.file_name,i.idimage FROM imagecategory as ic JOIN image as i ON i.idimage = ic.image_idimage WHERE category_idcategory =1
        if ($results['nb_photos'] > 0) {
            if ($limite_bas == NULL)
                $sql = "SELECT i.extension,i.file_name,i.idimage FROM
                    imagecategory as ic
                    JOIN image as i ON i.idimage = ic.image_idimage
                    WHERE category_idcategory =1
                    ORDER BY i.idimage LIMIT 0,9 ";
            else {
                $limite_haut = $limite_bas + $_images_par_page;
                $sql = "SELECT i.extension,i.file_name,i.idimage FROM
                    imagecategory as ic
                    JOIN image as i ON i.idimage = ic.image_idimage
                    WHERE category_idcategory =1
                    ORDER BY i.idimage 
                    LIMIT " . $limite_bas . "," . $limite_haut;
            }
            $result = $this->db->query($sql);
            $results = $result->fetchAll();
            return $results;
        } else {
            return "Il n'y a pas de photos dans cet album";
        }
    }

    public function getAlbumInfo($album_id) {
        $sql = "SELECT id, nom, descri, token FROM albums WHERE id = {$album_id}";

        $result = $this->_db->query($sql);
        if (($result->rowCount()) >= 1) {
            $results = $result->fetch();
            return $results;
        }
        else
            return 0;
    }

    public function getAlbums($limite_bas = null) {
        if ($limite_bas == null)
            $sql = "SELECT a.id, a.nom, a.descri, a.nb_photos, a.token,a.photo_defaut, i.nom_fichier_th, i.descri as descri1 FROM albums as a LEFT OUTER JOIN images as i ON a.photo_defaut = i.id ORDER BY a.id LIMIT 0,10 ";
        else {
            $limite_haut = $limite_bas + $_albums_par_page;
            $sql = "SELECT a.id, a.nom, a.descri, a.nb_photos, a.token,a.photo_defaut, i.nom_fichier_th, i.descri as descri1 FROM albums as a LEFT OUTER JOIN images as i ON a.photo_defaut = i.id ORDER BY a.id LIMIT " . $limite_bas . "," . $limite_haut;
        }

        $result = $this->_db->query($sql);
        $results = $result->fetchAll();
        return $results;
    }

    public function addImage($id_categories, $nom_fichier, $nom_fichier_temp) {
        $token_file_name = uniqid();
        $path_album = __DIR__ . '../../upload/';
        $path_image_dest = $path_album . $token_file_name;
//        if (move_uploaded_file($nom_fichier_temp, $path_image_dest))
//            $retour[0] = "L'image a ete correctement transferee !";
//        else {
//            $retour[0] = "Erreur durant le transfert du fichier. Merci de reesayer. ";
//        }
        $tab = explode(".", $nom_fichier);
        
        $this->imagethumb($nom_fichier_temp, $path_album . $token_file_name . "_s." . $tab['1'], 200);
        $this->imagethumb($nom_fichier_temp, $path_album . $token_file_name . "_m." . $tab['1'], 1024);
        $this->imagethumb($nom_fichier_temp, $path_album . $token_file_name . "_l." . $tab['1'], 2048);
        $sql = "INSERT INTO image (
              title
              ,description
              ,file_name
              ,extension
            ) VALUES (
              :title  -- title
              ,:description  -- description
              ,:file_name   -- file_name
              ,:extension   -- extension
            )";
        $description = "Ajoutée le " .date("d/m/Y");
        $sql = $this->db->prepare($sql);
        $sql->execute(array(
            "title"=>$nom_fichier,
            "description"=> $description,
            "file_name" => $token_file_name,
            "extension" => $tab['1']));
        $id_photo = $this->db->lastInsertId();
        $sql = "INSERT INTO imagecategory (
               category_idcategory
              ,image_idimage
            ) VALUES (
               :category , -- id category
               :image -- id image
            )";
        $sql = $this->db->prepare($sql);
        foreach ($id_categories as $category) {
        $sql->execute(array(
            "category"=>$category,
            "image"=> $id_photo));
        }
    }

    function rrmdir($dir) {
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file))
                rrmdir($file);
            else
                unlink($file);
        }
        rmdir($dir);
    }

    function deleteAlbum($id) {
        $sql = "SELECT * FROM albums WHERE id=" . $id;
        $data_album = $this->_db->query($sql)->fetch();
        echo $data_album['token'];
        $dir = "../../../upload/" . $data_album['token'] . "/";
        if (file_exists($dir)) {
            echo "<pre>";
            print_r(scandir($dir));
            echo "</pre>";
            $this->rrmdir($dir);
        }
        if (!file_exists($dir)) {
            echo "Dossier supprimé du disque <br />";
            $sql = "DELETE FROM images WHERE id_album=" . $id;
            $this->_db->exec($sql);
            echo "Toutes les images de l'album ont effacées de la base de données <br/>";
            $sql = "DELETE FROM images_comment WHERE id_album=" . $id;
            $this->_db->exec($sql);
            echo "Tous les commentaires associés aux images de cet album ont été supprimés<br/>";
            $sql = "DELETE FROM albums WHERE id=" . $id;
            $this->_db->exec($sql);
            echo "Album totalement supprimé de la base de données<br/>";
        } else {
            echo "Une erreur est survenue lors de la tentative de suppression du dossier<br/>";
            echo "S'il s'agit de la premiere fois, reesayez. Dans le cas contraire merci de m'adresser un mail.";
        }
    }

    function deleteImage($id) {
        $photo = $this->getPhoto($id);
        echo "<pre>";
        print_r($photo);
        echo "</pre>";
        $dir = "../../../upload/" . $photo['token'] . "/";
        if (file_exists($dir . $photo['photo_o'])) {
            if (is_file($dir . $photo['photo_o'])) {
                unlink($dir . $photo['photo_o']);
                echo "L'image originale supprimée .";
            }
            else
                echo "Le fichier image n'existait pas";
        }
        if (file_exists($dir . $photo['photo_fichier'])) {
            if (is_file($dir . $photo['photo_fichier'])) {
                unlink($dir . $photo['photo_fichier']);
                echo "L'image de taille moyenne supprimée .";
            }
            else
                echo "Le fichier image n'existait pas";
        }
        if (file_exists($dir . $photo['photo_thumb'])) {
            if (is_file($dir . $photo['photo_thumb'])) {
                unlink($dir . $photo['photo_thumb']);
                echo "L'image miniature supprimée .";
            }
            else
                echo "Le fichier image n'existait pas";
        }
        $sql = "DELETE FROM images WHERE id=" . $id;
        $this->_db->exec($sql);
        echo "Image supprimée de la base de données";
        $sql = "SELECT photo_defaut FROM albums WHERE id=" . $photo['album_id'];
        $retour = $this->_db->query($sql)->fetch();
        if ($retour['photo_defaut'] == $id) {
            $sql = "UPDATE albums SET
                nb_photos = nb_photos -1
                , photo_defaut = 0
                WHERE id = " . $photo['album_id'];
            echo "L'album ne contient plus d'image pas défaut !";
        } else {
            $sql = "UPDATE albums SET
                nb_photos = nb_photos -1
                WHERE id = " . $photo['album_id'];
            echo "L'image pas défaut de l'album n'a pas été modifiée";
        }
        $this->_db->exec($sql);
    }

    function updateAlbum($titre, $description, $visibilite, $image_defaut, $id) {
        $sql = "UPDATE albums SET
   nom = '" . $titre . "' -- varchar(50)
  ,descri = '" . $description . "' -- text
  ,visibilite = " . $visibilite . " -- tinyint(2)
  ,photo_defaut = " . $image_defaut . " -- int(11)
WHERE id = " . $id . " -- int(11)";
        $this->_db->exec($sql);
        print_r($this->_db->errorInfo());
    }

    function updatePhoto($titre, $description, $photo_defaut, $photo, $album) {
        if ($photo_defaut == 1) {
            $sql = "UPDATE albums SET
   photo_defaut = " . $photo . " -- int(11)
WHERE id = " . $album . " -- int(11)";
            $this->_db->exec($sql);
            print_r($this->_db->errorInfo());
        }

        $sql = "UPDATE images SET
   nom = '" . $titre . "' -- varchar(25)
  ,descri = '" . $description . "' -- text
WHERE id = " . $photo . " -- int(11)";
        echo $sql;
        $this->_db->exec($sql);
        print_r($this->_db->errorInfo());
    }

    /**
     * Permet le redimensionnement des images
     * @author http://code.seebz.net/p/imagethumb/
     * @param type $image_src
     * @param type $image_dest
     * @param type $max_size
     * @param type $expand
     * @param type $square
     * @return boolean 
     */
    function imagethumb($image_src, $image_dest = NULL, $max_size = 100, $expand = FALSE, $square = FALSE) {
        if (!file_exists($image_src))
            return FALSE;

        // Récupère les infos de l'image
        $fileinfo = getimagesize($image_src);
        if (!$fileinfo)
            return FALSE;

        $width = $fileinfo[0];
        $height = $fileinfo[1];
        $type_mime = $fileinfo['mime'];
        $type = str_replace('image/', '', $type_mime);

        if (!$expand && max($width, $height) <= $max_size && (!$square || ($square && $width == $height) )) {
            // L'image est plus petite que max_size
            if ($image_dest) {
                return copy($image_src, $image_dest);
            } else {
                header('Content-Type: ' . $type_mime);
                return (boolean) readfile($image_src);
            }
        }

        // Calcule les nouvelles dimensions
        $ratio = $width / $height;

        if ($square) {
            $new_width = $new_height = $max_size;

            if ($ratio > 1) {
                // Paysage
                $src_y = 0;
                $src_x = round(($width - $height) / 2);

                $src_w = $src_h = $height;
            } else {
                // Portrait
                $src_x = 0;
                $src_y = round(($height - $width) / 2);

                $src_w = $src_h = $width;
            }
        } else {
            $src_x = $src_y = 0;
            $src_w = $width;
            $src_h = $height;

            if ($ratio > 1) {
                // Paysage
                $new_width = $max_size;
                $new_height = round($max_size / $ratio);
            } else {
                // Portrait
                $new_height = $max_size;
                $new_width = round($max_size * $ratio);
            }
        }

        // Ouvre l'image originale
        $func = 'imagecreatefrom' . $type;
        if (!function_exists($func))
            return FALSE;

        $image_src = $func($image_src);
        $new_image = imagecreatetruecolor($new_width, $new_height);

        // Gestion de la transparence pour les png
        if ($type == 'png') {
            imagealphablending($new_image, false);
            if (function_exists('imagesavealpha'))
                imagesavealpha($new_image, true);
        }

        // Gestion de la transparence pour les gif
        elseif ($type == 'gif' && imagecolortransparent($image_src) >= 0) {
            $transparent_index = imagecolortransparent($image_src);
            $transparent_color = imagecolorsforindex($image_src, $transparent_index);
            $transparent_index = imagecolorallocate($new_image, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
            imagefill($new_image, 0, 0, $transparent_index);
            imagecolortransparent($new_image, $transparent_index);
        }

        // Redimensionnement de l'image
        imagecopyresampled(
                $new_image, $image_src, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h
        );

        // Enregistrement de l'image
        $func = 'image' . $type;
        if ($image_dest) {
            $func($new_image, $image_dest);
        } else {
            header('Content-Type: ' . $type_mime);
            $func($new_image);
        }

        // Libération de la mémoire
        imagedestroy($new_image);

        return TRUE;
    }

}

?>