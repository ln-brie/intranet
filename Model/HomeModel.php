<?php
include_once 'Model/Model.php';

class HomeModel extends Model {
    
    /**
     * @return array
     * 
     * récupération des posts récents
     * 
     * récupération des posts récemment ajoutés ou mis à jour qui ne sont pas exclus du flux
     * sélection des 8 plus récents
     */
    public function getUpdates() {
        $req = "SELECT a.id, a.titre, a.contenu, b.service, b.section FROM post AS a JOIN emplacement as b ON a.id_emplacement = b.id WHERE a.exclu=0 AND a.brouillon=0 AND NOT b.section='memo' AND NOT (b.service='home' AND b.section LIKE 'video%') ORDER BY CASE WHEN date_update IS NULL THEN date_ajout WHEN date_update > date_ajout THEN date_update END DESC LIMIT 8";
        $result = $this->connexion->query($req);
        $list = array();
        if($result) {
            $list = $result->fetchAll(PDO::FETCH_ASSOC);
        }
        return $list;
    }

    /**
     * récupération des posts à afficher dans la partie haute de la page d'accueil
     * classés par ordre chronologique inversé
     * 
     * @return array
     */
    public function getHomePosts() {
        $req = $this->connexion->prepare('SELECT a.*, b.service FROM post AS a JOIN emplacement AS b ON a.id_emplacement=b.id WHERE home=1 ORDER BY CASE WHEN date_update IS NULL THEN date_ajout WHEN date_update > date_ajout THEN date_update END DESC');
        $result = $req->execute();
        $home = array();
        if($result) {
            $home = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $home;
    }

    /**
     * récupération des posts contenus dans la section annuaires du service home
     * 
     * @return array
     */
    public function getAnnuaires() {
        $req = $this->connexion->prepare('SELECT a.id, a.titre, a.contenu FROM post AS a JOIN emplacement AS b ON a.id_emplacement = b.id WHERE b.service="home" AND b.section="annuaires"');

        $result = $req->execute();
        $annuaires = array();

        if($result) {
            $annuaires = $req->fetchAll(PDO::FETCH_ASSOC);
        }

        return $annuaires;
    }

    /**
     * récupère les posts de la section vidéos du service home
     */
    public function getVideos() {
        $req = $this->connexion->prepare('SELECT a.id, a.titre, a.contenu, a.id_emplacement, b.section, a.diffusion FROM post AS a JOIN emplacement AS b ON a.id_emplacement = b.id WHERE b.service="home" AND NOT b.section="annuaires"');
        $result = $req->execute();

        $videos = array();

        if($result) {
            $videos = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $videos;

    }

    /**
     * passe toutes les vidéos du service home à diffusion=0
     * passe la vidéo sélectionnée dans le formulaire à diffusion=1
     */
    public function setVideoDiff() {
        
    }
}