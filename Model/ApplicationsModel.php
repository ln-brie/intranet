<?php
include_once 'Model/Model.php';

class ApplicationsModel extends Model {

    /**
     * ajoute d'un guide d'utilisation 
     * 
     */
    public function addGuide() {        
        $reqAdd = $this->connexion->prepare('INSERT INTO post (titre, contenu, id_emplacement) VALUES (:titre, :lien, :emp)');
        $reqAdd->bindParam(':titre', $titre);
        $reqAdd->bindParam(':lien', $lien);
        $reqAdd->bindParam(':emp', $emp);

        $titre = $_POST['titre'];
        $lien = $_POST['lien'];
        $emp = $_POST['emp'];
        $reqAdd->execute();
    }

    /**
     * mise à jour d'un guide
     * 
     */
    public function majGuide() {
            $req = $this->connexion->prepare('UPDATE post SET titre=:titre, contenu=:lien, date_update=now() WHERE id=:id ');
            $req->bindParam(':titre', $titre);
            $req->bindParam(':lien', $lien);
            $req->bindParam(':id', $id);
    
            $titre = $_POST['titre'];
            $lien = $_POST['lien'];
            $id = $_POST['id'];
    
            $req->execute();
    }
    
}