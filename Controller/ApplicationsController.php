<?php
include_once 'Controller.php';
include 'View/ApplicationsView.php';
include 'Model/ApplicationsModel.php';

class ApplicationsController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct()
    {
        $this->view = new ApplicationsView();
        $this->model = new ApplicationsModel();
        $this->service = $_GET['page'];
    }

    /**
     * récupération des posts du service
     * récupération des sections du service
     * affichage de la page 
     * 
     */
    public function showAction() {
        $guides = $this->model->getPosts();
        $listeApp = $this->model->getEmplacements($this->service);
        $this->view->displayApp($guides, $listeApp);
    }

    /**
     * ajout d'un guide et redirection vers la page des applications
     * 
     */
    public function addguideAction() {
        $section = $_POST['emp'];
        $this->model->addShort('applications', $section);
        header('location: index.php?page=applications');
    }

    /**
     * récupération des informations d'un guide
     * affichage du formulaire de mise à jour
     * 
     */
    public function updateguideAction() {
        $guide = $this->model->getPostById();
        $this->view->displayUpdateGuide($guide);
    }

    /**
     * mise à jour du guide et redirection
     * 
     */
    public function majguideAction(){
        $this->model->majShort();
        header('location:index.php?page=applications');
    }
}