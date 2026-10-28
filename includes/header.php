<?php
$titles = ['index' => 'Home', 'kamados' => "Kamado’s", 'masterclasses' => 'Masterclasses'];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ontdek blauwe kamado's, persoonlijk advies en masterclasses in Breda.">
    <title><?= htmlspecialchars($titles[$page] ?? 'Kamado Breda') ?> | Kamado Breda</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="script.js" defer></script>
</head>
<body>
<a class="skip-link" href="#inhoud">Direct naar inhoud</a>
<header class="site-header"><div class="header-inner">
    <a class="logo" href="index.php" aria-label="Kamado Breda, naar home"><span class="logo-mark" aria-hidden="true">◒</span><span>KAMADO <small>BREDA</small></span><span class="first">1<sup>st</sup></span></a>
    <nav aria-label="Hoofdnavigatie">
        <?php foreach ($titles as $link => $title): ?>
            <a href="<?= $link ?>.php" <?= $page === $link ? 'aria-current="page"' : '' ?>><?= htmlspecialchars($title) ?></a>
        <?php endforeach; ?>
        <a href="#contact">Contact</a>
    </nav>
</div></header>
