<?php
include_once 'Controller.php';
include 'View/AchatsView.php';
include 'Model/AchatsModel.php';

class AchatsController extends Controller {
    public $view;
    public $model;
    public $service;

    public function __construct()
    {
        $this->view = new AchatsView();
        $this->model = new AchatsModel();
        $this->service = $_GET['page'];
    }
}