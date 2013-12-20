<?php

class Images {

    private $db;

    public function __construct() {
        require_once(__DIR__ . '/config.php');
        $this->db = connectPdo();
    }

    public function getPhoto($photo_id) {
        $sql = "SELECT i.* FROM image as i WHERE idimage =" . $photo_id;
        $result = $this->db->query($sql);

        $results = $result->fetch();
        return $results;
    }

    public function getPhotos($album_id) {
        $result = $this->db->query("SELECT COUNT(*) as nb_photos FROM imagecategory WHERE category_idcategory=" . $album_id);
        $results = $result->fetch();
        if ($results['nb_photos'] > 0) {
            $sql = "SELECT i.extension,i.file_name,i.idimage,i.title,i.description FROM
                    imagecategory as ic
                    JOIN image as i ON i.idimage = ic.image_idimage
                    WHERE category_idcategory =" . $album_id . "
                    ORDER BY i.idimage ";

            $result = $this->db->query($sql);
            $results = $result->fetchAll();
            return $results;
        } else {
            return "Il n'y a pas de photos dans cet album";
        }
    }

    /**
     * Retourne toutes les catégories
     * @return type Retourne toutes les catégories
     */
    public function getCategories() {
        $sql = "SELECT idcategory as id, name,description FROM category";
        $result = $this->db->query($sql);
        $results = $result->fetchAll();
        return $results;
    }

    /**
     * Retourne les catégories d'une image
     * @param type $id Id de l'image
     * @return type Tableau contenant les catégories de l'image
     */
    public function getCategoriesById($id) {
        $sql = "SELECT category_idcategory as id FROM imagecategory WHERE image_idimage = ".$id;
        $result = $this->db->query($sql);
        $result->setFetchMode(PDO::FETCH_BOTH);
        $results = $result->fetchAll();
        return $results;
    }

    public function addImage($id_categories, $nom_fichier, $nom_fichier_temp) {
        // Token pour le nom du fichier
        $token_file_name = uniqid();
        // Répertoire de stockage
        $path_album = __DIR__ . '../../upload/';
        $path_image_dest = $path_album . $token_file_name;
        // Récupération de l'extension
        $tab = explode(".", $nom_fichier);
        // Création des différentes tailles d'image
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
        $description = "Ajoutée le " . date("d/m/Y");
        $sql = $this->db->prepare($sql);
        $sql->execute(array(
            "title" => $nom_fichier,
            "description" => $description,
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
                "category" => $category,
                "image" => $id_photo));
        }
    }

    /**
     * Fonction de suppression d'une image
     * @param type $id Id de l'image
     * @return string Message d'erreur
     */
    function deleteImage($id) {
        // Récupération d'une image
        $photo = $this->getPhoto($id);
        $dir = __DIR__ . '../../upload/';
        $message = "";
        // Suppression de la miniature
        if (file_exists($dir . $photo['file_name'] . "_s." . $photo['extension'])) {
            if (is_file($dir . $photo['file_name'] . "_s." . $photo['extension'])) {
                unlink($dir . $photo['file_name'] . "_s." . $photo['extension']);
            } else
                $message += "L'image de taille miniature n'existait pas";
        }
        // Suppression de la taille moyenne
        if (file_exists($dir . $photo['file_name'] . "_m." . $photo['extension'])) {
            if (is_file($dir . $photo['file_name'] . "_m." . $photo['extension'])) {
                unlink($dir . $photo['file_name'] . "_m." . $photo['extension']);
            } else
                $message += "L'image de taille moyenne n'existait pas";
        }
        // Suppression de la taille large
        if (file_exists($dir . $photo['file_name'] . "_l." . $photo['extension'])) {
            if (is_file($dir . $photo['file_name'] . "_l." . $photo['extension'])) {
                unlink($dir . $photo['file_name'] . "_l." . $photo['extension']);
            } else
                $message += "L'image de taille large n'existait pas";
        }
        // Suppression en base de données des associations aux catégories
        $sql = "DELETE FROM imagecategory WHERE image_idimage= :id";
        $requete = $this->db->prepare($sql);
        $requete->execute(array(
            "id" => $id
        ));
        // Suppression en base de données de l'image
        $sql = "DELETE FROM image WHERE idimage= :id";
        $requete = $this->db->prepare($sql);
        $requete->execute(array(
            "id" => $id
        ));
        return $message;
    }

    /**
     * Mise à jour d'une photo
     * @param type $title Titre
     * @param type $description Description
     * @param type $id Id de l'image
     */
    function updatePhoto($title, $description, $id,$id_categories) {


        $sql = "UPDATE image SET
                title = :title
                ,description = :description
                WHERE idimage = :id";
        $requete = $this->db->prepare($sql);
        $requete->execute(array(
            "title" => $title,
            "description" => $description,
            "id" => $id
        ));
        // Suppression des catégories existante
        $sql = "DELETE FROM imagecategory WHERE image_idimage= :id";
        $requete = $this->db->prepare($sql);
        $requete->execute(array(
            "id" => $id
        ));
        // Ajout dans les nouvelles catégories
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
                "category" => $category,
                "image" => $id));
        }
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
