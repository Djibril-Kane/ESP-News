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

$categorieActive = isset($_GET['categorie']) ? (int)$_GET['categorie'] : 0;

$stmtCat = $pdo->query("SELECT * FROM Categorie ORDER BY id");
$categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

if ($categorieActive > 0) {
    $stmt = $pdo->prepare("
        SELECT a.*, c.libelle AS categorie_libelle 
        FROM Article a 
        JOIN Categorie c ON a.categorie = c.id 
        WHERE a.categorie = :cat
        ORDER BY a.dateCreation DESC
    ");
    $stmt->execute([':cat' => $categorieActive]);
} else {
    $stmt = $pdo->query("
        SELECT a.*, c.libelle AS categorie_libelle 
        FROM Article a 
        JOIN Categorie c ON a.categorie = c.id 
        ORDER BY a.dateCreation DESC
    ");
}
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$titreSection = "Les dernières actualités";
if ($categorieActive > 0) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $categorieActive) {
            $titreSection = "Actualités : " . htmlspecialchars($cat['libelle']);
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualités Polytechniciennes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <h1>ACTUALITÉS POLYTECHNICIENNES</h1>
    </div>
    <nav>
        <ul>
            <li>
                <a href="index.php" class="<?= $categorieActive === 0 ? 'active' : '' ?>">
                    Accueil
                </a>
            </li>
            <?php foreach ($categories as $cat): ?>
            <li>
                <a href="index.php?categorie=<?= $cat['id'] ?>" 
                   class="<?= $categorieActive === (int)$cat['id'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['libelle']) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>

<main>
    <h2 class="section-title"><?= $titreSection ?></h2>

    <div class="articles-grid">
        <?php if (empty($articles)): ?>
            <p class="no-articles">Aucun article dans cette catégorie pour le moment.</p>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
            <article class="card">
                <div class="card-badge <?= strtolower(htmlspecialchars($article['categorie_libelle'])) ?>">
                    <?= htmlspecialchars($article['categorie_libelle']) ?>
                </div>
                <h3 class="card-titre"><?= htmlspecialchars($article['titre']) ?></h3>
                <p class="card-extrait">
                    <?= htmlspecialchars(mb_substr($article['contenu'], 0, 220)) ?>...
                </p>
                <div class="card-footer">
                    <span class="card-date">
                        <?= date('d/m/Y', strtotime($article['dateCreation'])) ?>
                    </span>
                    <a href="article.php?id=<?= $article['id'] ?>" class="card-lire">Lire la suite →</a>
                </div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>Copyright &copy; DGI <?= date('Y') ?></p>
</footer>

</body>
</html>
