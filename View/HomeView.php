<?php
include_once 'View/View.php';

class HomeView extends View
{

    /**
     * @param array $actus : les 8 derniers posts ajoutés ou mis à jour
     * @param array $imp : le(s) post(s) choisis pour figurer sur la page d'accueil
     * @param array $ann : les annuaires
     * 
     * affichage de la page d'accueil
     * 
     * test pour savoir si l'utilisateur est autorisé à éditer l'accueil
     * si autorisé : affichage de la barre d'édition
     * affichage du ou des posts séléctionnés pour être affichés sur la page d'accueil
     * affichage des derniers posts ajoutés ou mis à jour sur le site
     * affichage des annuaires
     * affichage du flux rss
     * 
     */
    public function displayHome($actus, $imp, $ann)
    {
        $edit = false;
        if (isset($_SESSION['access'])) {
            foreach ($_SESSION['access'] as $a) {
                if (strstr($a, 'home')) {
                    $edit = true;
                }
            }
        }
        if ($edit) {
            $this->page .= '<div id="edition"><ul>
                <li><a href="index.php?page=home&action=diffusion">Choisir les posts à mettre en avant</a></li>
                <li><a href="index.php?page=home&action=annuaires">Gérer les annuaires</a></li>
                <li><a href="index.php?page=diffusion&action=slider" target="_blank">Voir le diaporama</a></li>
                <li><a href="index.php?page=home&action=video">Choisir les vidéos à diffuser</a></li>
                </ul></div>';
        }

        if (!empty($imp)) {
            $this->page .= '<div id="important">';
            foreach ($imp as $i) {
                $service = $i['service'];
                if ($service == 'Ressourcesh') {
                    $service = 'Ressources humaines';
                } else if ($service == 'Secuenv') {
                    $service = 'Sécurité et environnement';
                } else if ($service == 'Qualite') {
                    $service = 'Qualité';
                } else if ($service == 'Plansprod') {
                    $service = 'Plans produits';
                }
                $this->page .= '<div class="imp"><i class="pin"></i><blockquote class="note yellow"><h2>' . $i['titre'] . '</h2><p>' . $i['contenu'] . '</p><cite class="service">- ' . $service . '</cite></blockquote></div>';
            }
            $this->page .= '</div>';
        }
        $this->page .= '<div id="haut"><div id="homeGauche">';

        $this->page .= '<div id="nvtes" class="postIt"><h2>Nouveautés</h2><ul>';
        foreach ($actus as $update) {
            $service = ucfirst($update['service']);
            if ($service == 'Ressourcesh') {
                $service = 'Ressources humaines';
            } else if ($service == 'Secuenv') {
                $service = 'Sécurité et environnement';
            } else if ($service == 'Qualite') {
                $service = 'Qualité';
            } else if ($service == 'Plansprod') {
                $service = 'Plans produits';
            } else if ($service == 'Home') {
                $service = 'Annuaires';
            }
            if (($update['service'] == 'informatique') && ($update['section'] == 'incidents en cours')) {
                if ((strpos($update['titre'], 'résolu') == false) && (strpos($update['titre'], 'Résolu') == false) && (strpos($update['titre'], 'RESOLU') == false)) {
                    $this->page .= '<li><a href="index.php?page=' . $update['service'] . '&action=show#' . $update['id'] . '"><i class="fas fa-caret-right"></i> <i class="fas fa-exclamation-triangle"></i> <span class="capitale">' . $service . '</span> - ' . $update['titre'] . '</a></li>';
                } else {
                    $this->page .= '<li><a href="index.php?page=' . $update['service'] . '&action=show#' . $update['id'] . '"><i class="fas fa-caret-right"></i> <span class="capitale">' . $service . '</span> - ' . $update['titre'] . '</a></li>';
                }
            } else {
                $this->page .= '<li><a href="index.php?page=' . $update['service'] . '&action=show#' . $update['id'] . '"><i class="fas fa-caret-right"></i> <span class="capitale">' . $service . '</span> - ' . $update['titre'] . '</a></li>';
            }
        }
        $this->page .= '</ul></div></div><div id="homeCentre">';

        if (!empty($ann)) {
            $this->page .= '<div id="annuaires" class="postIt"><h2>Annuaires</h2>
        <ul>';
            foreach ($ann as $a) {
                $this->page .= '<li><a href="' . $a['contenu'] . '">' . $a['titre'] . '</a></li>';
            }
            $this->page .= '</ul></div>';
        }
        $this->page .= '</div>';

        $this->page .= '<div id="homeDroite"><div id="actusRss" class="postIt">
        <a href="https://www.industrie-techno.com/" target="_blank"><img src="img/industrieT.jpg" alt="Industrie-Techno"></a><div id="rssAccueil"></div></div></div>';
        $this->display();
    }

    /**
     * @param mixed $ann : annuaires
     * 
     * affichage de la liste des annuaires et des options de modification et suppression
     * formulaire d'ajout d'un annuaire via url ou upload de fichier
     * 
     */
    public function displayAnnuaires($ann)
    {
        $this->page .= '<div class="postIt"><h2>Gestion des annuaires</h2><table class="tabMgmt"><tr><th>Annuaire</th><th>Lien</th></tr>';
        foreach ($ann as $a) {
            $this->page .= '<tr><td>' . $a['titre'] . '</td><td><a href="' . $a['contenu'] . '">' . $a['contenu'] . '</a></td><td><a href="index.php?page=home&action=askdelete&id=' . $a['id'] . '"><i class="far fa-trash-alt"></i></a></td></tr>';
        }
        $this->page .= '</table>';
        $this->page .= file_get_contents('html/formShort.html');
        $this->page = str_replace('{page}', 'home', $this->page);
        $this->page = str_replace('{ctrl}', 'addann', $this->page);
        $this->page = str_replace('{action}', 'Ajouter un annuaire', $this->page);
        $this->page = str_replace('{select}', '', $this->page);
        $this->page = str_replace('{id}', '', $this->page);
        $this->page = str_replace('{phTitre}', 'Nom de l\'annuaire', $this->page);
        $this->page = str_replace('{titre}', '', $this->page);
        $this->page = str_replace('{phContenu}', 'l\'annuaire', $this->page);
        $this->page = str_replace('{lien}', '', $this->page);
        $this->page = str_replace('{importDoc}', 'l\'annuaire', $this->page);
        $this->page = str_replace('{formId}', 'upload', $this->page);
        $this->page = str_replace('{bouton}', 'Ajouter l\'annuaire', $this->page);
        $this->page .= '</div>';
        $this->display();
    }

    /**
     * @param mixed $ann : annuaire à modifier
     * 
     * formulaire de mise à jour d'un annuaire
     * 
     */
    public function displayUpdateAnn($ann)
    {
        $this->page .= '<div class="postIt">';
        $this->page .= file_get_contents('html/formShort.html');
        $this->page = str_replace('{page}', 'home', $this->page);
        $this->page = str_replace('{ctrl}', 'majann', $this->page);
        $this->page = str_replace('{action}', 'Modifier un annuaire', $this->page);
        $this->page = str_replace('{select}', '', $this->page);
        $this->page = str_replace('{id}', $ann['id'], $this->page);
        $this->page = str_replace('{phTitre}', 'Nom de l\'annuaire', $this->page);
        $this->page = str_replace('{titre}', $ann['titre'], $this->page);
        $this->page = str_replace('{phContenu}', 'l\'annuaire', $this->page);
        $this->page = str_replace('{lien}', $ann['contenu'], $this->page);
        $this->page = str_replace('{importDoc}', 'l\'annuaire', $this->page);
        $this->page = str_replace('{bouton}', 'Mettre à jour l\'annuaire', $this->page);
        $this->page .= '</div>';
        $this->display();
    }

    /**
     * @param array $video
     * 
     * affichage des vidéos avec actions de suppression et de sélection, et du formulaire d'ajout
     * 
     */
    public function displayVideoList($video, $emp)
    {
        $this->page .= '<div class="postIt" id="formSuppImg">';
        if (!empty($video)) {
            $vid = '';
            $this->page .= '<table>';
            foreach ($emp as $e) {
                if ($e['section'] !== 'annuaires') {
                    $this->page .= '<tr><td>' . $e['section'] . '</td>';
                    foreach ($video as $v) {
                        if ($v['id_emplacement'] == $e['id']) {
                            $vid = '<td><video height="120" controls><source src="' . $v['contenu'] . '">Votre navigateur ne supporte pas le contenu vidéo.</video></td><td><a href="index.php?page=home&action=askdelete&id=' . $v['id'] . '"><i class="fas fa-trash-alt"></i></a></td><td><a href="index.php?page=diffusion&action=showvideo&nb=' . $v['section'] . '" target="_blank"><i class="fas fa-eye"></i></a></td>';
                            break;
                        } else {
                            $vid = '<td>Aucune vidéo</td><td colspan="2"><a href="index.php?page=home&action=addvideoform&emp='.$e['section'].'"><i class="fas fa-plus-circle"></i></a></td>';
                        }
                    }
                    $this->page .= $vid.'</tr>';
                }
            }

            $this->page .= '</table>';
            $this->page .= '</div>';
        }
        $this->display();
    }
    
    public function displayAddVidForm() {
        $select = '<input type="hidden" name="emp" value="'.$_GET['emp'].'">';
        $this->page .= '<div class="postIt">';
        $this->page .= file_get_contents('html/formShort.html');
        $this->page = str_replace('{page}', 'home', $this->page);
        $this->page = str_replace('{ctrl}', 'addvideo', $this->page);
        $this->page = str_replace('{action}', 'Ajouter une vidéo à diffuser sur les écrans', $this->page);
        $this->page = str_replace('{select}', $select, $this->page);
        $this->page = str_replace('{id}', '', $this->page);
        $this->page = str_replace('{phTitre}', 'Titre de la vidéo', $this->page);
        $this->page = str_replace('{titre}', '', $this->page);
        $this->page = str_replace('{phContenu}', 'la vidéo', $this->page);
        $this->page = str_replace('{lien}', '', $this->page);
        $this->page = str_replace('{bouton}', 'Ajouter la vidéo', $this->page);
        $this->page = str_replace('{formId}', 'uploadvideo', $this->page);
        $this->page = str_replace('{importDoc}', 'la vidéo', $this->page);
        $this->page .= '</div>';

        $this->display();
    }

}
