<?php
include_once 'Controller.php';

include 'View/LogistiqueView.php';
include 'Model/LogistiqueModel.php';

class LogistiqueController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct()
    {
        $this->view = new LogistiqueView();
        $this->model = new LogistiqueModel();
        $this->service = $_GET['page'];
    }
}