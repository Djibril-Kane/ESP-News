<?php
$host = 'localhost';
$dbname = 'mglsi_news';
$user = 'mglsi_user';
$password = 'passer';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT a.*, c.libelle AS categorie_libelle, c.id AS categorie_id
    FROM Article a 
    JOIN Categorie c ON a.categorie = c.id 
    WHERE a.id = :id
");
$stmt->execute([':id' => $id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header('Location: index.php');
    exit;
}

$stmtCat = $pdo->query("SELECT * FROM Categorie ORDER BY id");
$categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['titre']) ?> — Actualités Polytechniciennes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <h1>ACTUALITÉS POLYTECHNICIENNES</h1>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <?php foreach ($categories as $cat): ?>
            <li>
                <a href="index.php?categorie=<?= $cat['id'] ?>"
                   class="<?= (int)$cat['id'] === (int)$article['categorie_id'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['libelle']) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>

<main>
    <div class="article-detail">
        <a href="index.php?categorie=<?= $article['categorie_id'] ?>" class="retour">← Retour à <?= htmlspecialchars($article['categorie_libelle']) ?></a>

        <div class="article-badge <?= strtolower(htmlspecialchars($article['categorie_libelle'])) ?>">
            <?= htmlspecialchars($article['categorie_libelle']) ?>
        </div>

        <h2 class="article-titre"><?= htmlspecialchars($article['titre']) ?></h2>

        <div class="article-meta">
            Publié le <?= date('d/m/Y à H:i', strtotime($article['dateCreation'])) ?>
        </div>

        <div class="article-contenu">
            <?= nl2br(htmlspecialchars($article['contenu'])) ?>
        </div>
    </div>
</main>

<footer>
    <p>Copyright &copy; DGI <?= date('Y') ?></p>
</footer>

</body>
</html>
