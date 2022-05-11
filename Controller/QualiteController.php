<?php
include_once 'Controller.php';
include 'View/QualiteView.php';
include 'Model/QualiteModel.php';

class QualiteController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct()
    {
        $this->view = new QualiteView();
        $this->model = new QualiteModel();
        $this->service = $_GET['page'];
    }
}