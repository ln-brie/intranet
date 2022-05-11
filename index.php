<?php
session_start();
$_SESSION['user'] = 'user';
$_SESSION['access'] = array('direction', 'communication', 'secuenv', 'ressourcesh', 'qualite', 'informatique', 'plansprod', 'comptabilite', 'logistique', 'achats', 'galerie', 'applications', 'home');
$_SESSION['prenom'] = 'Lorem Ipsum';
include_once 'Dispatcher.php';
$dispatcher = new Dispatcher();
$dispatcher->dispatch();