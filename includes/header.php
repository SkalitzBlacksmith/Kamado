<?php
$titles = ['index' => 'Home', 'kamados' => "Kamado’s", 'masterclasses' => 'Masterclasses'];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Kamado's, advies en masterclasses in Breda.">
    <title><?= htmlspecialchars($titles[$page] ?? 'Kamado Breda') ?> | Kamado Breda</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="script.js" defer></script>
</head>
<body>
<header>
    <a class="logo" href="index.php">KAMADO <small>BREDA</small></a>
    <nav aria-label="Hoofdnavigatie">
        <?php foreach ($titles as $link => $title): ?>
            <a href="<?= $link ?>.php" <?= $page === $link ? 'aria-current="page"' : '' ?>><?= htmlspecialchars($title) ?></a>
        <?php endforeach; ?>
        <a href="#contact">Contact</a>
    </nav>
</header>
