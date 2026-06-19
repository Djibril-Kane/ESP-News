<?php
/** @var int $categorieId */
/** @var array $categories */
/** @var array $articles */
/** @var string $titreSection */
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
                <a href="index.php" class="<?= $categorieId === 0 ? 'active' : '' ?>">Accueil</a>
            </li>
            <?php foreach ($categories as $cat): ?>
            <li>
                <a href="index.php?categorie=<?= $cat['id'] ?>"
                   class="<?= $categorieId === (int)$cat['id'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['libelle']) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>

<div class="ribbon">
    <div class="ribbon-inner">
        <span class="ribbon-date"><?= date('l d F Y') ?></span>
        <span class="ribbon-count"><?= count($articles) ?> article<?= count($articles) > 1 ? 's' : '' ?></span>
    </div>
</div>

<main>
    <h2 class="section-title"><?= $titreSection ?></h2>

    <div class="articles-grid">
        <?php if (empty($articles)): ?>
            <p class="no-articles">Aucun article dans cette catégorie pour le moment.</p>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
            <?php $cat = strtolower(htmlspecialchars($article['categorie_libelle'])); ?>
            <article class="card cat-<?= $cat ?>">
                <div class="card-badge <?= $cat ?>">
                    <?= htmlspecialchars($article['categorie_libelle']) ?>
                </div>
                <h3 class="card-titre"><?= htmlspecialchars($article['titre']) ?></h3>
                <p class="card-extrait">
                    <?= htmlspecialchars(mb_substr($article['contenu'], 0, 220)) ?>...
                </p>
                <div class="card-footer">
                    <span class="card-meta">
                        <?= date('d/m/Y', strtotime($article['dateCreation'])) ?>
                    </span>
                    <a href="index.php?id=<?= $article['id'] ?>" class="card-lire">Lire la suite →</a>
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
