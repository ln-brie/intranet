<?php
include_once 'View/View.php';

class ApplicationsView extends View
{

    /**
     * @param array $guides : posts-guides d'application
     * @param array $app : emplacements correspondants aux applications
     * 
     * affichage de la page des applications et outils (raccourcis et guides d'utilisation)
     * 
     * vérification des droits d'édition de la page
     * affichage de chaque logo avec lien vers l'application et les guides associés
     * si l'usager est autorisé, affichage de la gestion (ajout, modification et suppression des guides)
     * 
     */
    public function displayApp($guides, $app)
    {
        $edit = false;
        if (isset($_SESSION['access'])) {
            foreach ($_SESSION['access'] as $a) {
                if (strpos($a, $_GET['page']) !== false) {
                    $edit = true;
                }
            }
        }
        $this->page .= '<div class="postIt"><h2>Outils et applications</h2><div id="appli">';

        $this->page .= '<div><a href="http://www.google.com/" target="_blank" class="lienApp"><img src="img/logosAppli/appli1.png" alt="Appli1"></a>';
        $this->guides('appli1', $edit, $guides);
        $this->page .= '</div>';

        $this->page .= '<div><a href="http://www.google.com/" target="_blank" class="lienApp"><img src="img/logosAppli/appli2.png" alt="Appli2"></a>';
        $this->guides('appli2', $edit, $guides);
        $this->page .= '</div>';

        $this->page .= '<div><a href="http://www.google.com/" target="_blank" class="lienApp"><img src="img/logosAppli/appli3.png" alt="Appli3"></a>';
        $this->guides('appli3', $edit, $guides);
        $this->page .= '</div>';

        $this->page .= '<div><a href="http://www.google.com/" target="_blank" class="lienApp"><img src="img/logosAppli/appli4.png" alt="Appli4"></a>';
        $this->guides('appli4', $edit, $guides);
        $this->page .= '</div>';

        //coller ici le bloc pour la nouvelle application

        $this->page .= '</div>';
        if ($edit) {
            $listeApp = '<span><label for="emp">Sélectionnez l\'application concernée : </label><select name="emp" required>';
            foreach ($app as $a) {
                $listeApp .= '<option value="' . $a['section'] . '">' . $a['section'] . '</option>';
            }
            $listeApp .= '</select></span>';
           
            $this->page .= file_get_contents('html/formShort.html');
            $this->page = str_replace('{page}', 'applications', $this->page);
            $this->page = str_replace('{ctrl}', 'addguide', $this->page);
            $this->page = str_replace('{action}', 'Ajouter une documentation', $this->page);
            $this->page = str_replace('{select}', $listeApp, $this->page);
            $this->page = str_replace('{id}', '', $this->page);
            $this->page = str_replace('{phTitre}', 'Nom du guide', $this->page);
            $this->page = str_replace('{titre}', '', $this->page);
            $this->page = str_replace('{phContenu}', 'le guide', $this->page);
            $this->page = str_replace('{lien}', '', $this->page);
            $this->page = str_replace('{formId}', 'upload', $this->page);
            $this->page = str_replace('{importDoc}', 'le guide', $this->page);
            $this->page = str_replace('{bouton}', 'Ajouter le guide', $this->page);
        }
        $this->page .= '</div>';
        $this->display();
    }

    /**
     * @param string $nomApp : nom de l'application
     * @param bool $edit 
     * @param array $guidesArray 
     * 
     * fonction d'affichage de la liste des guides 
     * 
     */
    public function guides($nomApp, $edit, $guidesArray)
    {
        if (!empty($guidesArray)) {
            foreach ($guidesArray as $guide) {
                if ($guide['section'] == $nomApp) {
                    $this->page .= '<p class="itemGuide"><a id="'.$guide['id'].'"></a><i class="fas fa-angle-right"></i> <a href="' . $guide['contenu'] . '" target="_blank">' . $guide['titre'] . '</a>';
                    if ($edit) {
                        $this->page .= ' <a id="supprimer" href="index.php?page=' . $_GET['page'] . '&action=askdelete&id=' . $guide['id'] . '"><i class="far fa-trash-alt"></i></a>';
                    }
                }
            }
        }
    }

    /**
     * @param array $guide
     * 
     * affichage du formulaire de mise à jour d'un guide 
     * 
     */
    public function displayUpdateGuide($guide)
    {
        $this->page .= '<div class="postIt">';
        $this->page .= file_get_contents('html/formShort.html');
        $this->page = str_replace('{page}', 'applications', $this->page);
        $this->page = str_replace('{ctrl}', 'majguide', $this->page);
        $this->page = str_replace('{action}', 'Modifier un guide', $this->page);
        $this->page = str_replace('{select}', '', $this->page);
        $this->page = str_replace('{id}', $guide['id'], $this->page);
        $this->page = str_replace('{phTitre}', 'Nom du guide', $this->page);
        $this->page = str_replace('{titre}', $guide['titre'], $this->page);
        $this->page = str_replace('{phContenu}', 'le guide', $this->page);
        $this->page = str_replace('{lien}', $guide['contenu'], $this->page);
        $this->page = str_replace('{importDoc}', 'le guide', $this->page);
        $this->page = str_replace('{bouton}', 'Mettre à jour le guide', $this->page);
        $this->page .= '</div>';
        $this->display();
    }
}
