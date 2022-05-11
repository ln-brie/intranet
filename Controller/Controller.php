<?php
abstract class Controller {
   
    /**
     * affiche les posts sur la page concernée
     * 
     * récupération des posts dans la bdd et stockage dans une variable
     * tri des posts par section, stockage du tableau obtenu dans une variable
     * récupération des posts mémo, stockage dans une variable
     * envoi de cette variable vers la view pour affichage
     */
    public function showAction() {
        $list = $this->model->getPosts();
        $bySection = $this->model->group('section', $list);
        $memo = $this->model->getMemo();
        $this->view->displayPage($bySection, $memo);
    }

    /**
     * affichage du formulaire d'ajout
     * 
     * récupération de la liste des emplacements possibles dans la page et stockage dans une variable
     * envoi vers la view pour affichage
     */
    public function addFormAction() {
        $emp = $this->model->getEmplacements($this->service);
        $this->view->displayAddForm($emp);
    }

    /**
     * ajout des données à la bdd et renvoi à la page du service
     * 
     */
    public function addAction() {
        $this->model->addPost();
        header('Location: index.php?page='.$this->service);
    }

    /**
     * affichage du formulaire de mise à jour du post
     * 
     * récupération des emplacements possibles pour la page et stockage dans une variable
     * récupération des informations du post à modifier et stockage dans une variable
     * envoi des deux variables à la view pour affichage du formulaire
     * 
     */
    public function updateformAction() {
        $emp = $this->model->getEmplacements($this->service);
        $post = $this->model->getPostById();
        $this->view->displayUpdateForm($post, $emp);
    }

    /**
     * mise à jour du post dans la bdd et renvoi à la page du service
     * 
     */
    public function updateAction() {
        $this->model->updatePost();
        header('Location: index.php?page='.$this->service);
    }

    /**
     * affichage du post à supprimer pour confirmation
     * 
     * récupération des informations du post et stockage dans une variable
     * envoi de la variable à la view pour affichage
     * 
     */
    public function askdeleteAction() {
        $post = $this->model->getPostById();
        $this->view->displayConfirmDelete($post);
    }
    
    /**
     * suppression de l'entrée dans la bdd et renvoi à la page du service
     * 
     */
    public function deleteAction() {
        $this->model->deletePost();
        header('Location: index.php?page='.$_GET['page']);
    }

    /**
     * récupération des sections de la page et envoi vers la view pour affichage
     * 
     */
    public function sectionsAction() {
        $emp = $this->model->getEmplacements($this->service);
        $this->view->displaySections($emp);
    }

    /**
     * affichage du formulaire d'édition de la section
     * 
     * récupération des entrées de la table emplacement correspondant au service
     * récupération des informations de l'emplacement à mettre à jour
     * affichage des informations par la view
     * 
     */
    public function editsectionAction() {
        $emplacements = $this->model->getEmplacements($this->service);
        $section = $this->model->getSectionById();
        $this->view->displayEditSection($emplacements, $section);
    }

    /**
     * mise à jour de la section et renvoi vers la liste des sections
     * 
     */
    public function updatesectionAction(){
        $this->model->updateSection();
        header('Location: index.php?page='.$_GET['page'].'&action=sections');
    }

    /**
     * affichage du formulaire de suppression d'un emplacement
     * 
     * récupération de la liste des emplacements du service
     * récupération des posts associés à la section demandée
     * récupération des informations de la section demandée
     * affichage du formulaire par la view
     * 
     */
    public function askdeletesectionAction() {
        $sectionsService = $this->model->getEmplacements($this->service);
        $posts = $this->model->getPostsBySection();
        $section = $this->model->getSectionById();
        $this->view->displayAskDeleteSection($posts, $section, $sectionsService);
    }

    /**
     * ajout d'un emplacement
     * redirection vers la liste des sections
     * 
     */
    public function addsectionAction() {
        $this->model->addSection();
        header('location: index.php?page='.$this->service.'&action=sections');
    }

    /**
     * suppression de la section et redirection vers la liste des sections
     * 
     */
    public function deletesectionAction(){
        $this->model->deleteSection($_POST['id']);
        header('Location: index.php?page='.$_GET['page'].'&action=sections');
    }

    /**
     * déplacement des posts vers un autre emplacement et suppression de la section ainsi vidée
     * redirection vers la liste des sections
     * 
     */
    public function movepostsdelsectionAction() {
        $this->model->movePosts();
        $this->model->deleteSection($_POST['idSec']);
        header('Location: index.php?page='.$_GET['page'].'&action=sections');
    }

    /**
     * suppression des posts d'une section puis de la section
     * redirection vers la liste des sections
     * 
     */
    public function delpostsdelsectionAction() {
        $this->model->delPostsDelSection();
        header('location: index.php?page='.$this->service.'&action=sections');
    }

    /**
     * affiche une liste des posts pour choix des posts à diffuser sur les écrans et sur la page d'accueil
     * 
     */
    public function diffusionAction() {
        $liste = $this->model->getAllPostsByDate();
        $this->view->displayListeDiff($liste);
    }

    /**
     * mise à jour du champ 'diffusion' et 'home' de la table post 
     * 
     */
    public function diffusionecransAction() {
        $this->model->updateDiffusion();
        header('location: index.php?page='.$this->service.'&action=diffusion');
    }

    /**
     * upload d'un fichier (fonction appelée en ajax)
     * 
     */
    public function addfileAction() {
        $this->model->addFile('docs');
    }

    /**
     * login d'un usager, définition des variables de session
     * (fonction appelée en ajax)
     * 
     */
    public function connectAction() {
        $this->model->connectAD();
    }

    /**
     * récupération des posts brouillons et affichage de la liste
     * 
     */
    public function brouillonAction() {
        $br = $this->model->getBrouillons();
        $this->view->displayBrouillons($br);
    }

    /**
     * publication d'un post en brouillon (passage de la colonne à 0) et redirection
     * 
     */
    public function publishAction() {
        $this->model->pubBrouillon();
        header('location:index.php?page='.$this->service.'&action=brouillon');
    }
    
    /**
     * mise à jour du post mémo et redirection (refresh)
     * 
     */
    public function updatememoAction() {
        $this->model->updatePost();
        header('location:index.php?page='.$_GET['page']);
    }

    /**
     * delog, suppression des variables de session
     */
    public function logoutAction() {
        session_destroy();
        header('location:index.php');
    }
}