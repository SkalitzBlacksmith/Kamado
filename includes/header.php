<?php
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; frame-src https://www.google.com; frame-ancestors 'none'; base-uri 'self'; form-action 'self'; object-src 'none'");
$titles = ['index' => 'Home', 'kamados' => "Kamado’s", 'masterclasses' => 'Masterclasses'];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ontdek blauwe kamado's, persoonlijk advies en masterclasses in Breda.">
    <title><?= htmlspecialchars($titles[$page] ?? 'Kamado Breda') ?> | Kamado Breda</title>
    <link rel="icon" href="assets/logo.svg" type="image/svg+xml">
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime(__DIR__ . '/../css/style.css') ?>">
    <script src="script.js" defer></script>
</head>
<body>
<a class="skip-link" href="#inhoud">Direct naar inhoud</a>
<header class="site-header"><div class="header-inner">
    <a class="logo" href="index.php" aria-label="Kamado Breda, naar home"><img class="logo-mark" src="assets/logo.svg" alt="" width="48" height="56"><span>KAMADO <small>BREDA</small></span><span class="first">1<sup>st</sup></span></a>
    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav"><span></span><span></span><span></span><span class="sr-only">Menu</span></button>
    <nav id="main-nav" aria-label="Hoofdnavigatie">
        <?php foreach ($titles as $link => $title): ?>
            <a href="<?= $link ?>.php" <?= $page === $link ? 'aria-current="page"' : '' ?>><?= htmlspecialchars($title) ?></a>
        <?php endforeach; ?>
        <a href="#contact">Contact</a>
    </nav>
</div></header>
