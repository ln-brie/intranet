<?php
include_once 'Model/Model.php';

class GalerieModel extends Model {

    
    /**
     * ajout d'une image à la base de données
     * 
     */
    public function addImageToGallery() {
        $reqEmp = $this->connexion->prepare('SELECT id FROM emplacement WHERE service="galerie" AND section="images"');
        $reqEmp->execute();
        $id_emp = $reqEmp->fetch(PDO::FETCH_ASSOC);

        
        $req = $this->connexion->prepare('INSERT INTO post (id, titre, contenu, id_emplacement) VALUES (NULL, :titre, :contenu, :id_emp)');

        $req->bindParam(':titre', $titre);
        $req->bindParam(':contenu', $contenu);
        $req->bindParam('id_emp', $id_emp['id']);

        $titre = $_POST['titre'];
        $contenu = $_POST['lienPhoto'];

        $req->execute();

    }

    /**
     * récupération de tous les posts de la section images du service galerie
     */
    public function getPhotos() {
        $req = $this->connexion->prepare('SELECT a.id, a.titre, a.contenu FROM post AS a JOIN emplacement AS b ON a.id_emplacement = b.id WHERE b.service="galerie" AND b.section="images" ORDER BY a.date_ajout DESC');
        $result = $req->execute();
        $images = array();

        if($result) {
            $images = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $images;
    }

}