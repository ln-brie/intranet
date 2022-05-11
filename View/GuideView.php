<?php
class GuideView {
    public $page;
    
    public function __construct() {
        $this->page = file_get_contents('html/header.html');
        $this->page .= file_get_contents('html/menu.html');
        if (!isset($_SESSION['user'])) {
            $this->page = str_replace('{connect}', '<a href="#connect" rel="modal:open"><i class="far fa-user-circle"></i> Se connecter</a>', $this->page);
        } else {
            $this->page = str_replace('{connect}', '<a href="index.php?page=home&action=logout" title="se déconnecter"><i class="far fa-times-circle"></i></a> Bonjour ' . $_SESSION['prenom'] . ' ! ', $this->page);
        }
        $this->page .= '</header><main id="panel">';
        $this->page .= file_get_contents('html/guide.html');
    }

    /**
     * affichage de la page de guide
     * 
     */
    public function display() {
        $this->page .= file_get_contents('html/footer.html');
        echo $this->page;

    }
}