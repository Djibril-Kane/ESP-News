<?php
// MODEL : gère les données liées aux articles
// Contient toutes les requêtes SQL concernant la table Article

class ArticleModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupère tous les articles (avec le libellé de leur catégorie)
    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT a.*, c.libelle AS categorie_libelle
            FROM Article a
            JOIN Categorie c ON a.categorie = c.id
            ORDER BY a.dateCreation DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère les articles filtrés par catégorie
    public function getByCategorie($categorieId) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, c.libelle AS categorie_libelle
            FROM Article a
            JOIN Categorie c ON a.categorie = c.id
            WHERE a.categorie = :cat
            ORDER BY a.dateCreation DESC
        ");
        $stmt->execute([':cat' => $categorieId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un seul article par son ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, c.libelle AS categorie_libelle, c.id AS categorie_id
            FROM Article a
            JOIN Categorie c ON a.categorie = c.id
            WHERE a.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}