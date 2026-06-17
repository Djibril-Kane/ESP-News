<?php

class ArticleController {
    private $articleModel;
    private $categorieModel;

    public function __construct() {
        global $pdo;
        $this->articleModel   = new ArticleModel($pdo);
        $this->categorieModel = new CategorieModel($pdo);
    }

    // Action : page d'accueil / liste filtrée par catégorie
    public function index(int $categorieId) {
        // Appel au Model pour récupérer les données
        $categories = $this->categorieModel->getAll();

        if ($categorieId > 0) {
            $articles = $this->articleModel->getByCategorie($categorieId);
            // Trouver le libellé de la catégorie active
            $titreSection = "Actualités : ";
            foreach ($categories as $cat) {
                if ((int)$cat['id'] === $categorieId) {
                    $titreSection .= htmlspecialchars($cat['libelle']);
                    break;
                }
            }
        } else {
            $articles     = $this->articleModel->getAll();
            $titreSection = "Les dernières actualités";
        }

        // Transmission des données à la vue
        require 'views/home.php';
    }

    // Action : page de détail d'un article
    public function show($id) {
        $article    = $this->articleModel->getById($id);
        $categories = $this->categorieModel->getAll();

        // Si l'article n'existe pas, retour à l'accueil
        if (!$article) {
            header('Location: index.php');
            exit;
        }

        require 'views/article.php';
    }
}