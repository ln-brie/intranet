<?php
include_once 'Controller.php';
include 'Model/DirectionModel.php';
include 'View/DirectionView.php';

class DirectionController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct() {
        $this->view = new DirectionView();
        $this->model = new DirectionModel();
        $this->service = $_GET['page'];
    }
}