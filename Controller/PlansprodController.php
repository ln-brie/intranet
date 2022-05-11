<?php
include_once 'Controller.php';
include 'View/PlansprodView.php';
include 'Model/PlansprodModel.php';

class PlansprodController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct()
    {
        $this->view = new PlansprodView();
        $this->model = new PlansprodModel();
        $this->service = $_GET['page'];
    }
}