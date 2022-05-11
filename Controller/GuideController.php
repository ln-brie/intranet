<?php
include 'View/GuideView.php';
include 'Model/GuideModel.php';

class GuideController {
    public $view;
    public $model;
    
    public function __construct() {
        $this->view = new GuideView();
        $this->model = new GuideModel();        
    }

    /**
     * affichage de la page
     * 
     */
    public function showAction() {
        $this->view->display();
    }
}