<?php
include_once 'Controller.php';
include 'View/SearchView.php';
include 'Model/SearchModel.php';

class SearchController extends Controller {
    public $view;
    public $model;
    public $service;
    
    public function __construct() {
        $this->view = new SearchView();
        $this->model = new SearchModel();
    }

    /**
     * fonction de recherche
     * 
     * envoi du terme saisi dans la barre de recherche vers le model
     * récupération du tableau obtenu et envoi vers le view pour affichage 
     */
    public function resultsAction() {
        $req = $_GET['req'];
        $results = $this->model->getPostsByReq($req);
        $this->view->resultsDisplay($results);
    }
}