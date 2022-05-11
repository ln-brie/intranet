<?php
include_once 'Model/Model.php';

class DiffusionModel extends Model {
    /**
     * récupération de tous les posts ayant un champ diffusion à 1
     * stockage dans une variante $diff
     */
    public function getDiffusionPosts() {
        $req = $this->connexion->prepare('SELECT * FROM post AS a JOIN emplacement AS b ON a.id_emplacement=b.id WHERE a.diffusion=1');
        $result = $req->execute();

        $diff = array();

        if ($result) {
            $diff = $req->fetchAll(PDO::FETCH_ASSOC);
        }

        return $diff;
    }

    /**
     * récupération du post de la section vidéos du service home avec diffusion=1
     * 
     */
    public function getDiffusionVideo() {
        $section = $_GET['nb'];
        $req=$this->connexion->prepare('SELECT * FROM post AS a JOIN emplacement AS b ON a.id_emplacement=b.id WHERE b.service="home" AND b.section=:section');
        $req->bindParam(':section', $section);
        $req->execute();
        $video = $req->fetch(PDO::FETCH_ASSOC);

        return $video;
    }
}