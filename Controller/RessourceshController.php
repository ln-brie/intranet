<?php
include_once 'Controller.php';
include 'Model/RessourceshModel.php';
include 'View/RessourceshView.php';

class RessourceshController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct() {
        $this->view = new RessourceshView();
        $this->model = new RessourceshModel();
        $this->service = $_GET['page'];
    }
}