<?php
include_once 'Controller.php';
include 'View/CommunicationView.php';
include 'Model/CommunicationModel.php';

class CommunicationController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct()
    {
        $this->view = new CommunicationView();
        $this->model = new CommunicationModel();
        $this->service = $_GET['page'];
    }
}