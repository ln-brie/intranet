<?php
include_once 'Controller.php';
include 'Model/ComptabiliteModel.php';
include 'View/ComptabiliteView.php';

class ComptabiliteController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct()
    {
        $this->view = new ComptabiliteView();
        $this->model = new ComptabiliteModel();
        $this->service = $_GET['page'];
    }
}