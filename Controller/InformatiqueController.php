<?php
include_once 'Controller.php';
include 'View/InformatiqueView.php';
include 'Model/InformatiqueModel.php';

class InformatiqueController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct() {
        $this->view = new InformatiqueView();
        $this->model = new InformatiqueModel();
        $this->service = $_GET['page'];
    }

    /**
     * affichage de la page d'entrée de la gestion de la bdd
     * 
     */
    public function bddAction() {
        $this->view->displayBddMgmt();
    }

    /**
     * récupération de la liste des posts et de la liste des emplacements
     * affichage de la liste dans un tableau
     */
    public function mgmtpostsAction() {
        $posts = $this->model->getAllPosts();
        $emp = $this->model->getAllEmplacements();
        $this->view->displayMgmtPosts($posts, $emp);
    }

    /**
     * récupération de la liste des emplacements et du nombre de posts par emplacement
     * affichage de la liste dans un tableau
     * 
     */
    public function mgmtempAction() {
        $emp = $this->model->getAllEmplacements();
        $group = $this->model->group('service', $emp);
        $nbPosts = $this->model->nbPostsBySection();
        $this->view->displayMgmtEmp($emp, $nbPosts, $group);
    }

    /**
     * affichage d'un post pour mise à jour avec possibilité de le déplacer vers un emplacement d'un service différent
     * 
     */
    public function mgmtpostformupdateAction() {
        $post = $this->model->getPostById();
        $emp = $this->model->getAllEmplacements();
        $this->view->mgmtPostUpdateForm($post, $emp);
    }

    /**
     * mise à jour du post et redirection
     * 
     */
    public function mgmtpostupdateAction() {
        $this->model->updatePost();
        header('Location: index.php?page='.$this->service.'&action=mgmtposts');
    }
    
    /**
     * déplacement de posts vers un autre emplacement
     * 
     */
    public function mgmtmovepostsAction() {
        $this->model->mgmtMovePosts();
        header('Location: index.php?page=informatique&action=mgmtposts');
    }

    /**
     * suppression multiple des posts
     * 
     */
    public function mgmtmultideleteAction() {
        $this->model->mgmtSuppPosts();
        header('Location: index.php?page=informatique&action=mgmtposts');
    }

    /**
     * formulaire d'édition d'une section
     * 
     */
    public function mgmteditsectionformAction() {
        $section = $this->model->getSectionById();
        $emp = $this->model->group('service', $this->model->getAllEmplacements());
        $this->view->mgmtEditSectionForm($section, $emp);
    }

    /**
     * mise à jour d'un section
     * 
     */
    public function mgmtupdatesectionAction() {
        $this->model->updateSection();
        header('Location: index.php?page=informatique&action=mgmtemp');
    }

    /**
     * suppression multiple de sections
     * 
     */
    public function mgmtdeletesectionsAction() {
        $this->model->mgmtDeleteSections();
        header('Location: index.php?page=informatique&action=mgmtemp');
    }

    /**
     * ajout d'une nouvelle section depuis la page de gestion des emplacements
     * 
     */
    public function nvsectionAction() {
        $this->model->mgmtNvSection();
        header('location:index.php?page=informatique&action=mgmtemp');
    }
}