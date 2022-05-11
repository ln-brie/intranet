<?php
include_once 'Model/Model.php';

class SearchModel extends Model {

    /**
     * @param string $req
     * 
     * @return array $results
     * 
     * récupération des résultats d'une recherche
     * 
     * recherche de $req dans les colonnes titre et contenu
     * stockage des posts correspondants dans un tableau
     */
    public function getPostsByReq($req) {
        $requete = $this->connexion->prepare('SELECT a.id, a.titre, a.contenu, b.service FROM post AS a JOIN emplacement AS b ON a.id_emplacement = b.id  WHERE (titre LIKE :req OR contenu LIKE :req) AND NOT b.section="memo"');
        $requete->bindParam(':req', $req);

        $req = '%'.$_GET['req'].'%';
        $response = $requete->execute();
        $results = array();
        if($response){
            $results = $requete->fetchAll(PDO::FETCH_ASSOC);
        }
        return $results;
    }
}