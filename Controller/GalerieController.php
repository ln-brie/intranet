<?php
include 'Model/GalerieModel.php';
include 'View/GalerieView.php';

class GalerieController {
    public $view;
    public $model;
    public $service;

    public function __construct()
    {
        $this->view = new GalerieView();
        $this->model = new GalerieModel();
        $this->service = $_GET['page'];
    }

    /**
     * récupération des posts du service galerie (photos) et affichage de la page
     * 
     */
    public function showAction() {
        $photos = $this->model->getPhotos();
        $this->view->displayGalerie($photos);
    }

    /**
     * upload d'une image (appel en ajax)
     * 
     */
    public function uploadAction() {
        $this->model->addFile('img/gallery');
    }

    /**
     * ajout d'un post dans la bdd contenant les infos de l'image et redirection
     * 
     */
    public function addAction() {
        $this->model->addImageToGallery();
        header('location:index.php?page=galerie&action=show');
    }

    /**
     * récupération des posts et affichage de la liste des images
     * 
     */
    public function delimgtableAction() {
        $photos = $this->model->getPhotos();
        $this->view->displayList($photos);
    }

    /**
     * suppression d'un post image et redirection
     * 
     */
    public function delphotosAction() {
        $this->model->mgmtSuppPosts();
        header('location:index.php?page=galerie&action=delimgtable');
    }
}