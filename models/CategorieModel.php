<?php
// MODEL : gère les données liées aux catégories

class CategorieModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupère toutes les catégories (pour la navbar dynamique)
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM Categorie ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}