<?php
include_once 'Controller.php';
include 'View/DiffusionView.php';
include 'Model/DiffusionModel.php';

class DiffusionController extends Controller {
    public $model;
    public $view;
    public $service;

    public function __construct() {
        $this->model = new DiffusionModel();
        $this->view = new DiffusionView();
        $this->service = $_GET['page'];
    }

    /**
     * récupération des posts ayant un champ diffusion à 1
     * affichage du carousel
     */
    public function sliderAction() {
        $diff = $this->model->getDiffusionPosts();
        $this->view->displayCarousel($diff);
    }


    /**
     * récupération des infos d'un post et aperçu en slide
     */
    public function showslideAction() {
        $post = $this->model->getPostById();
        $this->view->displaySlide($post);
    }

    /**
     * récupère la vidéo séléctionnées (diffusion=1) et affichage 
     * 
     */
    public function showvideoAction() {
        $video = $this->model->getDiffusionVideo();
        $this->view->displayVideo($video);
    }
}