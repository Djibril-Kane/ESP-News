<?php
// Point d'entrée unique de l'application

require_once 'config/database.php';
require_once 'models/ArticleModel.php';
require_once 'models/CategorieModel.php';
require_once 'controllers/ArticleController.php';

$controller = new ArticleController();

if (isset($_GET['id'])) {
    $controller->show((int)$_GET['id']);
} else {
    $controller->index(isset($_GET['categorie']) ? (int)$_GET['categorie'] : 0);
}