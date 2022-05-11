<?php
include_once 'Controller.php';
include 'View/HomeView.php';
include 'Model/HomeModel.php';

class HomeController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct() {
        $this->view = new HomeView();
        $this->model = new HomeModel();
        $this->service = isset($_GET['page'])?$_GET['page']:'home';
    }

    /**
     * affichage de la page d'accueil
     * 
     * récupération des derniers posts ajoutés ou mis à jour
     * récupération des posts à afficher en page d'accueil
     * récupération des annuaires
     * 
     */
    public function showAction() {
        $actus = $this->model->getUpdates();
        $imp = $this->model->getHomePosts();
        $ann = $this->model->getAnnuaires();
        $this->view->displayHome($actus, $imp, $ann);
    }

    /**
     * récupération des posts de la section annuaires
     * affichage de la liste des annuaires
     * 
     */
    public function annuairesAction() {
        $ann = $this->model->getAnnuaires();
        $this->view->displayAnnuaires($ann);
    }

    /**
     * affichage du formulaire de mise à jour d'un annuaire
     * 
     */
    public function annupdateAction() {
        $ann = $this->model->getPostById();
        $this->view->displayUpdateAnn($ann);
    }

    /**
     * ajout d'un annuaire
     * 
     */
    public function addannAction() {
        $this->model->addShort('home', 'annuaires');
        header('location:index.php?page=home&action=annuaires');
    }

    /**
     * mise à jour d'un annuaire
     * 
     */
    public function majannAction() {
        $this->model->majShort();
        header('location:index.php?page=home&action=annuaires');
    }

    public function videoAction() {
        $videos = $this->model->getVideos();
        $emp = $this->model->getEmplacements($this->service);
        $this->view->displayVideoList($videos, $emp);
    }

    public function uploadvideoAction() {
        $this->model->addFile('videos');
    }

    public function addvideoformAction() {
        $this->view->displayAddVidForm();
    }

    public function addvideoAction() {
        $this->model->addShort('home', $_POST['emp']);
        header('location:index.php?page=home&action=video');
    }

    public function mgmtvideosAction() {
        $this->model->setVideoDiff();
        header('location:index.php?page=home&action=video');
    }

}