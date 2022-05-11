<?php
include_once 'View/View.php';

class DiffusionView extends View {
    
    /**
     * @param array $diff
     * 
     * affichage du carousel affichant les posts contenus dans $diff
     * 
     */
    public function displayCarousel($diff) {
        $this->page .= '<div class="owl-carousel">';
        foreach ($diff as $d) {
            $this->page .= '<div class="item"><h2>'.$d['titre'].'</h2><p>'.$d['contenu'].'</p></div>';
        }
        $this->page .= '</div>';
        $this->display();
    }
    
    /**
     * @param array $post
     * 
     * apreçu d'un post en diaporama
     * 
     */
    public function displaySlide($post) {
        $this->page .= '<div class="owl-carousel"><div class="item"><h2>'.$post['titre'].'</h2><p>'.$post['contenu'].'</p></div></div>';
        $this->display();
    }
    
    /**
     * @param array $video
     * 
     * affichage de la vidéo sélectionnée dans la base
     * 
     */
    public function displayVideo($video) {
        if(!empty($video)) {
            $this->page .= '<div  id="diffVideo"><video muted autoplay loop><source src="'.$video['contenu'].'"></video></div>';
        } else {
            $this->page .= '<h2>Aucune vidéo pour cet écran.</h2>';
        }
        $this->display();
    }
}