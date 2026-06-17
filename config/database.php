<?php
// Configuration de la base de données
$host     = 'localhost';
$dbname   = 'mglsi_news';
$user     = 'mglsi_user';
$password = 'passer';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}