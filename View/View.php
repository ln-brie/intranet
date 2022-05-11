<?php
class View {
    protected $page;

    public function __construct() {
        $this->page = file_get_contents('html/header.html');
        if((!isset($_GET['page'])) || ($_GET['page'] != 'diffusion')) {
            $this->page .= file_get_contents('html/menu.html');
            if(!isset($_SESSION['user'])) {
                $this->page = str_replace('{connect}', '<a href="#connect" rel="modal:open"><i class="far fa-user-circle"></i> Se connecter</a>', $this->page);
            } else {
                $this->page = str_replace('{connect}', '<a href="index.php?page=home&action=logout" title="se déconnecter"><i class="far fa-times-circle"></i></a> Bonjour '.$_SESSION['prenom'].' ! ', $this->page);
            }
            
        }
        $this->page .= '</header><main id="panel">';
    }

    /**
     * @param array $bySection
     * @param array $memo
     * 
     * affichage des posts
     * 
     * vérification des droits de modification
     * création de la mise en page
     * affichage des posts
     * affichage de la partie droite avec l'image du service et la partie 'mémo'
     * 
     */
    public function displayPage($bySection, $memo) {
        $edit = false;
        if (isset($_SESSION['access'])) {
            foreach ($_SESSION['access'] as $a) {
                if(strpos($a, $_GET['page']) !== false) {
                    $edit = true;
                }
            }
        }
        if($edit) {
            if ($_GET['page'] == 'informatique') {
                $this->page .= '<div id="edition"><ul>
                    <li><a href="index.php?page=informatique&action=addForm">Ajouter un post</a></li>
                    <li><a href="index.php?page=informatique&action=brouillon">Voir les brouillons</a></li>
                    <li><a href="index.php?page=informatique&action=sections">Gérer les sections de la page</a></li>
                    <li><a href="index.php?page=informatique&action=bdd">Gérer la base de données</a></li>
                </ul></div>';
            } else {
                $this->page .= '<div id="edition"><ul>
                    <li><a href="index.php?page='.$_GET['page'].'&action=addForm">Ajouter un post</a></li>
                    <li><a href="index.php?page='.$_GET['page'].'&action=brouillon">Voir les brouillons</a></li>
                    <li><a href="index.php?page='.$_GET['page'].'&action=sections">Gérer les sections de la page</a></li>
                </ul></div>';
            }
        }
        $this->page = str_replace('{page}', $_GET['page'], $this->page);
        $this->page .= '<div id="wrapperPage"><div id="gauche">';
        foreach ($bySection as $section) {
            $this->page .= '<div class="postIt"><h2 class="pointer">'.$section[0]['section'].'<i class="fas fa-plus-circle"></i></h2><span class="articles">';
            foreach ($section as $post) {
                $this->page .= '<article><h3 class="titrePost"><a id="'.$post['id'].'"></a>'.$post['titre'].'</h3><p class="contenupost">'.$post['contenu'].'</p>';
                if($edit) {
                    $this->page .= '<a href="index.php?page='.$_GET['page'].'&action=updateform&id='.$post['id'].'"><i class="far fa-edit"></i></a> <a id="supprimer" href="index.php?page='.$_GET['page'].'&action=askdelete&id='.$post['id'].'"><i class="far fa-trash-alt"></i></a> <a title="aperçu de l\'image en diaporama" target="_blank" id="visualiser" href="index.php?page=diffusion&action=showslide&id='.$post['id'].'"><i class="far fa-eye"></i></a>';
                }
                $this->page .= '</article>';
            }
            $this->page .= '</span></div>';
        }
        $this->page .= '</div><div id="droite">';
        $serv = $_GET['page'];
        if ($_GET['page'] == 'ressourcesh') {
            $serv = 'ressources humaines';
        } else if($_GET['page'] == 'secuenv') {
            $serv = 'sécurité environnement';
        } else if($_GET['page'] == 'qualite') {
            $serv = 'qualité';
        } else if($_GET['page'] == 'plansprod') {
            $serv = 'plans produits';
        } else if($_GET['page'] == 'comptabilite') {
            $serv = 'comptabilité';
        } else if($_GET['page'] == 'logistique') {
            $serv = 'logistique, magasin et service clients';
        }
        $this->page .= '<h3>'.$serv.'</h3><img src="img/bandeauxServices/'.$_GET['page'].'.png" class="imgService">';
        $this->page .= '<div class="memo"><h3>Notes</h3>';

        if($edit) {
            $this->page .= '<a id="memoService"><i class="fas fa-edit"></i></a>';
        }
        $this->page .= '<span id="memoContenu">'.$memo['contenu'].'</span>';
        $this->page .= '<form id="formMemo" action="index.php?page='.$_GET['page'].'&action=updatememo" method="POST">
        <input type="hidden" name="id" value="'.$memo['id'].'">
        <input type="hidden" name="titre" value="'.$memo['titre'].'">
        <input type="hidden" name="emplacement" value="'.$memo['id_emplacement'].'">
        <textarea id="contenu" cols="30" rows="10" placeholder="Notes, liens utiles, etc." required name="contenu">'.$memo['contenu'].'</textarea><button type="submit" class="intraButton">Valider</button></form>';
        $this->page .= '</div></div>';
        $this->display();
    }

    /**
     * @param array $emp
     * 
     * affichage du formulaire d'ajout de post
     * 
     * récupération des emplacements possibles pour le service concerné
     * remplacement des tags dans le template html puis affichage
     */
    public function displayAddForm($emp) {
        $emplacement = '';
        if (empty($emp)) {
            $this->page .= '<div class="postIt"><h3 class="center">Veuillez créer une section avant de créer un nouveau post</h3>';
            $this->page .= '<p class="center"><a href="index.php?page='.$_GET['page'].'&action=sections"><button class="intraButton" type="button">Ajouter une section</button></a></p><p><a href="index.php?page='.$_GET['page'].'&action=show"><i class="fas fa-arrow-left"></i> Retour à la page du service</a></p></div>';

        } else {
            foreach ($emp as $e) {
                $emplacement .= '<option value="'.$e['id'].'">'.$e['section'].'</option>';
            }
            $this->page .= file_get_contents('html/form.html');
            $this->page = str_replace('{page}', $_GET['page'], $this->page);
            $this->page = str_replace('{effet}', 'add', $this->page);
            $this->page = str_replace('{action}', 'Ajout', $this->page);
            $this->page = str_replace('{id}', '', $this->page);
            $this->page = str_replace('{titre}', '', $this->page);
            $this->page = str_replace('{contenu}', '', $this->page);
            $this->page = str_replace('{selected}', '', $this->page);
            $this->page = str_replace('{options}',$emplacement, $this->page);
            $this->page = str_replace('{coche}', '', $this->page);
            $this->page = str_replace('{brouillon}', '', $this->page);
            $this->page = str_replace('{actionretour}', 'show', $this->page);
        }
        
        $this->display();
    }

    /**
     * @param array $emp
     * 
     * affichage de la liste des sections de la page pour mise à jour, ajout ou suppression
     */
    public function displaySections($emp) {
        $titre = $_GET['page'];
        if($titre == 'ressourcesh') {
            $titre = 'ressources humaines';
        } else if ($titre == 'secuenv') {
            $titre = 'sécurité et environnement';
        } else if ($titre == 'qualite') {
            $titre = 'qualité';
        } else if ($titre == 'plansprod') {
            $titre = 'plans produits';
        }
        $this->page .= '<div id="listeSections" class="postIt"><h2>Sections de la page '.$titre.'</h2>';
        if(!empty($emp)) {
            $this->page .= '<div><table class="tabMgmt">';
            foreach ($emp as $e) {
                $this->page .= '<tr><td class="titreSec">'.$e['section'].'</td><td><a href="index.php?page='.$_GET['page'].'&action=editsection&id='.$e['id'].'"><i class="editName fas fa-edit"></i></a></td><td><a href="index.php?page='.$_GET['page'].'&action=askdeletesection&id='.$e['id'].'"><i class="fas fa-trash"></i></a></td></tr>
                ';
            }
            $this->page .= '</table></div>' ;
        }
        
        $this->page .= '<form id="newSection" method="post" action="index.php?page='.$_GET['page'].'&action=addsection"><label for="nvSec">Ajouter une section : </label><input type="text" name="nvSec" id="nvSec" placeholder="Nouvelle section" required><button type="submit"><i class="fas fa-plus-circle"></i></button><p id="response"></p></form><p><a href="index.php?page='.$_GET['page'].'&action=show"><i class="fas fa-arrow-left"></i> Retour à la page du service</a></p></div>';
        $this->display();
    }

    /**
     * injection du footer et affichage de la page
     */
    public function display(){
        $this->page .= file_get_contents('html/footer.html');
        echo $this->page;
    }

    /**
     * @param array $post
     * @param array $emp
     * 
     * affichage du formulaire de mise à jour d'un post
     * 
     * affichage de $emp sous forme de liste déroulante
     * préremplissage du formulaire avec les données du post
     */
    public function displayUpdateForm($post, $emp) {
        $emplacement = '';
        foreach ($emp as $e) {
            $emplacement .= '<option value="'.$e['id'].'"';
            if ($e['id'] == $post['id_emplacement']) {
                $emplacement .= ' selected';
            }
            $emplacement .= '>'.$e['section'].'</option>';
        }
        $this->page .= file_get_contents('html/form.html');
        $this->page = str_replace('{page}', $_GET['page'], $this->page);
        $this->page = str_replace('{effet}', 'update', $this->page);
        $this->page = str_replace('{action}', 'Modification', $this->page);
        $this->page = str_replace('{id}', $post['id'], $this->page);
        $this->page = str_replace('{options}', $emplacement, $this->page);
        $this->page = str_replace('{titre}', $post['titre'], $this->page);
        $this->page = str_replace('{contenu}', $post['contenu'], $this->page);
        if($post['exclu'] == '1') {
            $this->page = str_replace('{coche}', 'checked', $this->page);
        } else {
            $this->page = str_replace('{coche}', '', $this->page);
        }
        if($post['diffusion'] == 1) {
            $this->page = str_replace('{diff}', 'checked', $this->page);
        } else {
            $this->page = str_replace('{diff}', '', $this->page);
        }
        if ($post['brouillon'] == 1) {
            $this->page = str_replace('{brouillon}', 'checked', $this->page);
        } else {
            $this->page = str_replace('{brouillon}', '', $this->page);
        }
        $this->page = str_replace('{actionretour}', 'show', $this->page);
        $this->display();
    }

    /**
     * @param array $post
     * 
     * affichage d'un message de confirmation avant suppression du post
     * 
     * affichage du titre, du contenu et des boutons d'action
     */
    public function displayConfirmDelete($post) {
        $this->page .= '<div class="postIt"><h2>Voulez-vous supprimer ce contenu ?</h2>';
        $this->page .= '<div id="post"><h3>'.$post['titre'].'</h3><p>'.$post['contenu'].'</p></div>';
        $this->page .= '<form id="delpostform" method="POST" action="index.php?page='.$_GET['page'].'&action=delete"><input hidden name="id" type="text" value="'.$post['id'].'">
        <button type="submit" class="intraButton">Valider</a></button>
        <p class="pAlertSupp">ATTENTION toute suppression est définitive !!</p></form>';
        $this->page.= '<a href="index.php?page='.$_GET['page'].'&action=show"><i class="fas fa-arrow-left"></i> Retour à la page du service</a></div>';
        $this->display();
    }

    /**
     * @param array $posts
     * @param array $section
     * @param array $sectionsService
     * 
     * affichage du formulaire de confirmation de suppression de la section
     * 
     * si la section n'est associée à aucun post, juste un bouton de validation
     * si la section est associée à des posts, affichage de ces posts, liste déroulante 
     * proposant les sections du service pour déplacement de ces posts
     * bouton de confirmation
     */
    public function displayAskDeleteSection($posts, $section, $sectionsService) {
        $this->page .= '<div class="postIt" id="deleteSec"><h2>Suppression de la section "'.$section['section'].'"</h2>';
        if(empty($posts)) {
            $this->page .= '<div id="suppSec"><h3>Voulez-vous supprimer définitivement cette section ?</h3>
            <form class="depPosts" method="POST" action="index.php?page='.$_GET['page'].'&action=deletesection">
            <input hidden value="'.$section['id'].'" name="id">
            <button type="submit" class="intraButton">Valider</button><p class="pAlertSupp">ATTENTION toute suppression est définitive !!</p></form>';
        } else {
            $this->page .= '<div id="suppSec"><h3>Le(s) post(s) suivant(s) sont rattachés à cette section : </h3><ul>';
            foreach($posts as $post) {
                $this->page .= '<li>'.$post['titre'].'</li>';
            }
            $this->page .= '</ul>';
            if (count($sectionsService) == 1) {
                $this->page .= '<form class="depPosts" method="POST" action="index.php?page='.$_GET['page'].'&action=delpostsdelsection">
                <input hidden type="text" value="'.$section['id'].'" name="idSec">
                <h3 class="center">Il n\'y a pas d\'autre section pour accueillir les posts existants. <br> En supprimant la section, vous supprimerez aussi les posts qui y sont rattachés !</h3>
                <div id="suppSecPostsButtons"><button class="intraButton" type="submit">Supprimer la section et les posts rattachés</button></div>';
                $this->page .= '<p class="pAlertSupp">ATTENTION toute suppression est définitive !!</p></form>';
            } else {
                $this->page .= '<form class="depPosts" method="POST" action="index.php?page='.$_GET['page'].'&action=movepostsdelsection">
                <input hidden type="text" value="'.$section['id'].'" name="idSec">
                <label for="destSec">Vers quelle section voulez-vous déplacer les posts ?</label>
                <select name="destSec" id="destSec" required>';
                foreach($sectionsService as $sec) {
                    if($sec['id'] != $section['id']) {
                        $this->page .= '<option value="'.$sec['id'].'">'.$sec['section'].'</option>';
                    }
                }
                $this->page .= '</select><div id="suppSecButtons"><button class="intraButton" type="submit">Déplacer les posts et supprimer la section</button></div>';
                $this->page .= '<p class="pAlertSupp">ATTENTION toute suppression est définitive !!</p></form>';
            }
        }
        $this->page .= '</div><p><a href="index.php?page='.$_GET['page'].'&action=sections"><i class="fas fa-arrow-left"></i> Retour à la liste des sections</a></p></div>';
        $this->display();

    }

    /**
     * @param array $emplacements
     * @param array $section
     * 
     * affichage du formulaire de modification de la section
     * affichage des emplacements du service dans la liste déroulante
     */
    public function displayEditSection($emplacements, $section) {
        
        $this->page .= '<div class="postIt"><h2>Modification de la section "'.$section['section'].'"</h2>
        <form id="majSection" method="POST" action="index.php?page='.$_GET['page'].'&action=updatesection">
        <input hidden type="text" name="id" value="'.$_GET['id'].'">
        <label for="section">Changer le nom de la section : </label><input id="section" name="section" type="text" required>
        <p id="response"></p>
        <button id="submitEditSection" type="submit" class="intraButton">Valider</button>
        </form>
        <ul id="listeSec">Sections existantes :';
        foreach($emplacements as $emp) {
            $this->page .= '<li>'.$emp['section'].'</li>';
        }
        $this->page .= '</ul>
        <a href="index.php?page='.$_GET['page'].'&action=sections"><i class="fas fa-arrow-left"></i> Retour à la liste des sections</a></div>';       
        $this->display();
    }

    /**
     * @param array $liste
     * 
     * affiche la liste des posts par ordre chronologique
     * et une case à cocher pour déterminer les posts à afficher sur les écrans
     * 
     */
    public function displayListeDiff($liste) {
        $this->page .= '<div class="postIt"><h2>Choisir les posts à mettre en avant</h2><form method="POST" action="index.php?page='.$_GET['page'].'&action=diffusionecrans" id="diffusionform"><button class="intraButton" type="submit">Valider</button><p id="alertValid">Pensez à valider pour sauvegarder vos modifications !</p>';
        $this->page .= '<table class="tabMgmt" id="listedifftable"><tr><th>Titre du post</th><th>Service</th><th>Section</th><th>Diaporama</th><th>Page d\'accueil</th></tr>';
        foreach ($liste as $post) {
            $serv = $post['service'];
            if ($serv == 'ressourcesh') {
                $serv = 'ressources humaines';
            } else if ($serv == 'plansprod') {
                $serv = 'plans produits';
            } else if ($serv == 'secuenv') {
                $serv = 'sécurité et environnement';
            } else if ($serv == 'qualite') {
                $serv = 'qualité';
            }
            if(($post['section'] != 'memo') && ($post['section'] != 'annuaires') && ($post['service'] != 'galerie') && ($post['service'] != 'applications') ) {
                $this->page .= '<tr><td>'.$post['titre'].'</td><td>'.$serv.'</td><td>'.$post['section'].'</td><td><input type="checkbox" name="id[]" value="'.$post['id'].'"';
                if ($post['diffusion'] == 1 ) {
                    $this->page .= ' checked';
                }            
                $this->page .= '></td><td><input class="homeChkbx" type="checkbox" name="home[]" value="'.$post['id'].'"';
                if ($post['home'] == 1 ) {
                    $this->page .= ' checked';
                } 
                $this->page .= '></td></tr>';
            }
        }
        $this->page .= '</table></form></div>';
        $this->display();
    }

    /**
     * @param mixed $brouillon
     * 
     * affichage des posts en brouillon
     * 
     * affichage des informations du post, et des boutons de validation, modification et suppression 
     */
    public function displayBrouillons($brouillon) {
        $serv = $_GET['page'];
            if ($serv == 'ressourcesh') {
                $serv = 'ressources humaines';
            } else if ($serv == 'plansprod') {
                $serv = 'plans produits';
            } else if ($serv == 'secuenv') {
                $serv = 'sécurité et environnement';
            } else if ($serv == 'qualite') {
                $serv = 'qualité';
            }
        $this->page .= '<div class="postIt"><h2>'.ucfirst($serv).' - Posts en attente</h2>';
        if(!empty($brouillon)) {
            $this->page .= '<div  id="listeBr">';
            foreach ($brouillon as $b) {
                $this->page .= '<div class="brouillon"><div class="postBr"><h3>'.$b['titre'].'</h3><p>'.$b['contenu'].'</p><p><small>Section : '.$b['section'].'</small></p></div><div class="editBr"><a href="index.php?page='.$_GET['page'].'&action=publish&id='.$b['id'].'"><i class="fas fa-check"></i></a> <a href="index.php?page='.$_GET['page'].'&action=updateform&id='.$b['id'].'"><i class="fas fa-pen"></i></a> <a href="index.php?page='.$_GET['page'].'&action=askdelete&id='.$b['id'].'"><i class="fas fa-trash-alt"></i></a></div></div>';
            } 
            $this->page .= '</div>';
        } else {
            $this->page .= '<p>Aucun post en attente</p>';
        }
        $this->page .= '</div>';
        $this->display();
    }

    
}