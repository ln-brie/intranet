<?php
include_once 'Controller/Controller.php';
include 'View/SecuenvView.php';
include 'Model/SecuenvModel.php';

class SecuenvController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct()
    {
        $this->view = new SecuenvView();
        $this->model = new SecuenvModel();
        $this->service = $_GET['page'];
    }
}