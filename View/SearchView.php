<?php
include_once 'View/View.php';

class SearchView extends View {

    /**
     * @param array $results
     * 
     * affichage des résultats de la recherche
     * 
     * test si $results est vide 
     * si oui : affichage d'un message
     * si non : affichage des posts correspondants à la recherche
     */
    public function resultsDisplay($results) {
        $this->page .= '<div id="resultatsRech">';
        if (empty($results)){
            $this->page .= '<h2>Aucun contenu ne correspond à votre recherche</h2>';
        } else {
            $this->page .= '<h2>Résultats de la recherche</h2><div class="postIt">';
            foreach ($results as $result) {
                $service = $result['service'];
                if ($service == 'ressourcesh') {
                    $service = 'ressources humaines'; 
                } else if ($service == 'secuenv') {
                    $service = 'sécurité et environnement';
                }else if ($service == 'plansprod') {
                    $service = 'plans produits';
                }
                $this->page .= '<p><a href="index.php?page='.$result['service'].'&action=show#'.$result['id'].'"><strong>'.$result['titre'].'</strong> -  '.$service.'</a></p>';
            }
        }
        $this->page .= '</div></div>';
        $this->display();
    }
}