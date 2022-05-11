<?php
include_once 'View/View.php';

class InformatiqueView extends View {


    /**
     * affichage de la page d'accès à la gestion de la bdd
     */
    public function displayBddMgmt() {
        $this->page .= '<div class="postIt" id="bddMgmt"><h2>Gestion de la base de données</h2>';
        $this->page .= '<a class="lienBouton" href="index.php?page=informatique&action=mgmtposts"><button class="intraButton">Gérer les posts</button></a>';
        $this->page .= '<a class="lienBouton" href="index.php?page=informatique&action=mgmtemp"><button class="intraButton">Gérer les emplacements</button></a></div>';
        $this->display();
    }

    /**
     * affichage de la liste de tous les posts de la base de données
     * possibilité de les déplacer, de les supprimer et de les modifier
     * 
     * 
     * @param array $posts liste des posts
     * @param array $emp liste des emplacements
     * 
     */
    public function displayMgmtPosts($posts, $emp) {
        $this->page .= '<div class="postIt"><h2>Gestion des posts</h2><form id="mgmtPostsForm" method="POST"><table class="tabMgmt"><tr><th>Service</th><th>Section</th><th>Titre du post</th></tr>';
        foreach($posts as $post) {
            $serv = $this->renommage($post['service']);
            if(($post['service'] !== 'home') && ($post['service'] !== 'galerie') && ($post['service'] !== 'applications')) {
                $this->page .= '<tr><td>'.$serv.'</td><td>'.$post['section'].'</td><td>'.$post['titre'].'</td><td><a href="index.php?page=informatique&action=mgmtpostformupdate&id='.$post['id'].'"><i class="far fa-edit"></i></a></td><td><input type="checkbox" name="mgmtPost[]" value="'.$post['id'].'"></td></tr>';
            }
        }
        $this->page .= '</table><div id="mgmtpostsbtn"><span><button type="button" id="mgmtPostsMove" class="intraButton">Déplacer la sélection vers :</button><select name="nvService" #selectserv><option disabled selected> -- choisissez une destination -- </option>';
        foreach($emp as $em) {
            $nomEmp = $this->renommage($em['service']);
            $this->page .= '<option value="'.$em['id'].'">'.$nomEmp.' - '.$em['section'].'</option>';
        }
        $this->page .= '</select></span><button type="button" id="mgmtPostsDelete" class="intraButton">Supprimer la sélection</button></div></form><a href="index.php?page=informatique&action=bdd"><i class="fas fa-arrow-left"></i> Retour à la page de gestion de la bdd</a></div>';
        $this->display();
    }
    
    /**
     * @param array $post
     * @param array $emplacements
     * 
     * formulaire de modification de post
     */
    public function mgmtPostUpdateForm($post, $emplacements) {
        $emplacement = '';
        foreach ($emplacements as $e) {
            $empServ = $this->renommage($e['service']);
            
            $emplacement .= '<option value="'.$e['id'].'"';
            if ($e['id'] == $post['id_emplacement']) {
                $emplacement .= ' selected';
            }
            $emplacement .= '>'.$empServ.' - '.$e['section'].'</option>';
        }

        $this->page .= file_get_contents('html/form.html');
        $this->page = str_replace('{page}', 'informatique', $this->page);
        $this->page = str_replace('{effet}', 'mgmtpostupdate', $this->page);
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
        $this->page = str_replace('{actionretour}', 'mgmtposts', $this->page);
        $this->display();
    }

    /**
     * @param array $emp
     * @param array $nbPosts
     * 
     * liste des emplacements de la base de données
     * 
     * gestion des emplacements : modification, déplacement et suppression
     * 
     */
    public function displayMgmtEmp($emp, $nbPosts, $group) {
        $this->page .= '<div class="postIt"><h2>Gestion des emplacements</h2><form id="mgmtEmpForm" method="POST" action="index.php?page=informatique&action=mgmtdeletesections"><table class="tabMgmt"><tr><th>Service</th><th>Section</th><th>Nb de posts</th></tr>';
        foreach($emp as $em) {
            $empServ = $this->renommage($em['service']);
            $this->page .= '<tr><td class="tableService">'.$empServ.'</td><td class="tableSection">'.$em['section'].'</td><td class="nbPosts">';
            foreach($nbPosts as $nb) {
                if($nb['id_emplacement'] == $em['id']) {
                    $this->page .= $nb['total'];
                }          
            }
            $this->page .= '</td><td><a href="index.php?page=informatique&action=mgmteditsectionform&id='.$em['id'].'"><i class="far fa-edit"></i></a></td><td><input type="checkbox" name="mgmtEmp[]" value="'.$em['id'].'"></td></tr>';
        }
        $this->page .= '</table><button id="mgmtDeleteEmpBtn" type="submit" class="intraButton">Supprimer la sélection</button></form>';
        $this->page .= '<form action="index.php?page=informatique&action=nvsection" method="POST" id="mgmtAddSection"><h4>Ajouter une section : </h4>
        <select name="service" id="listeServ">';
        foreach($group as $g) {
            $empServ = $this->renommage($g[0]['service']);
            $this->page .= '<option value="'.$g[0]["service"].'">'.$empServ.'</option>';
        }
        $this->page .= '</select><input type="text" name="section" id="section" required placeholder="nom de la nouvelle section"><button type="button" id="mgmtAddSectionBtn"><i class="fas fa-plus-circle"></i></button><p id="retourSection"></p></form>';
        $this->page .= '<a href="index.php?page=informatique&action=bdd"><i class="fas fa-arrow-left"></i> Retour à la page de gestion de la bdd</a></div>';
        $this->display();
    }

    /**
     * @param array $section
     * @param array $emplacements
     * 
     * formulaire d'édition de la section
     * 
     */
    public function mgmtEditSectionForm($section, $emplacements) {
        $empServ = $this->renommage($section['service']);
        $this->page .= '<div class="postIt"><h2>Modification de la section "'.$section['section'].'" du service "'.$empServ.'"</h2>
        <div id="mgmtSectionForm">
        <form id="mgmtUpdateSection" method="POST" action="index.php?page=informatique&action=mgmtupdatesection">
        <input hidden type="text" name="id" value="'.$_GET['id'].'">
        <input hidden type="text" id="service" name="serv" value="'.$section['service'].'">
        <label for="section">Changer le nom de la section : </label><input id="section" name="section" type="text" required>';        
        $this->page .= '<p id="retourSection"></p><button id="mgmtEditSection" type="submit" class="intraButton">Renommer</button></form><ul id="mgmtListeSec">Sections existantes pour le service "'.$empServ.'" :';
        foreach($emplacements as $emp) {
            foreach ($emp as $e) {
                if($e['service'] == $section['service']) {
                    $this->page .= '<li>'.$e['section'].'</li>';
                }
            }
        }
        $this->page.='</ul></div><a href="index.php?page=informatique&action=mgmtEmp"><i class="fas fa-arrow-left"></i> Retour à la liste des sections</a></div>';       
        $this->display();
    }

    public function renommage($servObj) {
        $serv = $servObj;
        if ($serv == 'ressourcesh') {
            $serv = 'ressources humaines';
        } else if ($serv == 'plansprod') {
            $serv = 'plans produits';
        } else if ($serv == 'secuenv') {
            $serv = 'sécurité et environnement';
        } else if ($serv == 'qualite') {
            $serv = 'qualité';
        }
        return $serv;
    }
}