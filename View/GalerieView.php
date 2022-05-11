<?php

class GalerieView
{
    public $page;

    public function __construct()
    {
        $this->page = file_get_contents('html/header.html');
        $this->page .= file_get_contents('html/menu.html');
        if (!isset($_SESSION['user'])) {
            $this->page = str_replace('{connect}', '<a href="#connect" rel="modal:open"><i class="far fa-user-circle"></i> Se connecter</a>', $this->page);
        } else {
            $this->page = str_replace('{connect}', '<a href="index.php?page=home&action=logout" title="se déconnecter"><i class="far fa-times-circle"></i></a> Bonjour ' . $_SESSION['prenom'] . ' ! ', $this->page);
        }
        $this->page .= '</header><main id="panel">';
    }

    /**
     * @param array $photos
     * 
     * affichage de la galerie photo et des formulaires si l'usager est autorisé
     * 
     */
    public function displayGalerie($photos)
    {
        $edit = false;
        if (isset($_SESSION['access'])) {
            foreach ($_SESSION['access'] as $a) {
                if (strpos($a, $_GET['page']) !== false) {
                    $edit = true;
                }
            }
        }
        $this->page .= '<div class="postIt">';
        if(!empty($photos)) {
            $this->page .= '<p id="msgGalerie">Ces images sont destinées à la communication publique, n\'hésitez pas à vous en servir !</p>';
            $this->page .= '<div id="pageGalerie"><div id="gallery">';
            foreach ($photos as $p) {
                $this->page .= '<img alt="' . $p['titre'] . '" src="' . $p['contenu'] . '"
                    data-image="' . $p['contenu'] . '"
                    data-description="' . $p['titre'] . '">';
            }
        $this->page .= '</div>';
        } else {
            $this->page .= '<p>Aucune image dans la galerie.</p>';

        }
        
        if ($edit) {
            $this->page .= file_get_contents('html/formGalerie.html');
        }
        $this->page .= '</div>';
        $this->display();
    }
    
    /**
     * @param array $photos
     * 
     * affichage de la liste des photos avec leur miniature et checkbox pour suppression
     * 
     */
    public function displayList($photos)
    {
        $this->page .= '<div class="postIt">';
        if(!empty($photos)) {
            $this->page .= '<form action="index.php?page=galerie&action=delphotos" method="POST" id="formSuppImg"><table>';
            foreach ($photos as $p) {
                $this->page .= '<tr><td>' . $p['titre'] . '</td><td><img class="miniature" src="' . $p['contenu'] . '"></td><td><input type="checkbox" name="mgmtPost[]" value="' . $p['id'] . '"></td></tr>';
            }
            $this->page .= '</table><button type="submit" class="intraButton">Supprimer la sélection</button></form>';
        } else {
            $this->page .= '<p>Aucune image enregistrée.</p>';
        }
        $this->page .= '</div>';
        
        $this->display();
    }

    /**
     * ajout du footer et affichage de la page
     * 
     */
    public function display()
    {
        $this->page .= file_get_contents('html/footer.html');
        echo $this->page;
    }
}
