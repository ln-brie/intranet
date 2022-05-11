<?php
include 'Controller/HomeController.php';
include 'Controller/DirectionController.php';
include 'Controller/RessourceshController.php';
include 'Controller/CommunicationController.php';
include 'Controller/SecuenvController.php';
include 'Controller/QualiteController.php';
include 'Controller/PlansprodController.php';
include 'Controller/ComptabiliteController.php';
include 'Controller/DiffusionController.php';
include 'Controller/LogistiqueController.php';
include 'Controller/AchatsController.php';
include 'Controller/SearchController.php';
include 'Controller/InformatiqueController.php';
include 'Controller/GuideController.php';
include 'Controller/ApplicationsController.php';
include 'Controller/GalerieController.php';


class Dispatcher
{
    /**
     * récupère les informations de l'URL et détermine quelle fonction appeler
     * 
     */
    public function dispatch()
    {
        $controller = (isset($_GET['page'])) ? $_GET['page'] : 'home';
        $controller = $controller . 'Controller';
        $action = $this->guard();
        $action = $action . 'Action';
        $my_controller = new $controller();
        $my_controller->$action();
    }

    /**
     * autorise ou empêche l'accès à des pages réservées aux usagers autorisés
     * 
     */
    public function guard()
    {
        $auth = false;
        $action = (isset($_GET['action'])) ? $_GET['action'] : 'show';
        
        if ((isset($_GET['page']) && (($_GET['page'] !== 'search') && ($_GET['page'] !== 'diffusion'))) && ($action !== 'show') && ($action !== 'connect') && ($action !== 'logout') && (isset($_SESSION['access']))) {
            foreach ($_SESSION['access'] as $a) {
                if ($a == $_GET['page']) {
                    $auth = true;
                }
            }
        }
        if ((isset($_GET['page']) && (($_GET['page'] !== 'search') && ($_GET['page'] !== 'diffusion'))) && ($action !== 'show') && ($action !== 'connect') && ($action !== 'logout') && ($auth == false)) {
            $action = 'show';
        } 
        return $action;
    }
}
