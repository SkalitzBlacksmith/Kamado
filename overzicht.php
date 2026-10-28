<?php
require __DIR__ . '/includes/overzicht-session.php';
date_default_timezone_set('Europe/Amsterdam');
function e($value) { return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
$loggedIn = !empty($_SESSION['overzicht_until']);
$sections = [
    'inschrijvingen' => ['title' => 'Masterclasses', 'site' => 'Kamado Breda', 'link' => 'masterclasses.php', 'columns' => ['id' => 'Nr.', 'naam' => 'Naam', 'email' => 'E-mail', 'masterclass' => 'Masterclass', 'datum' => 'Datum', 'personen' => 'Personen', 'aangemaakt' => 'Opgeslagen']],
    'contactberichten' => ['title' => 'Contactberichten', 'site' => 'LevelUp', 'link' => '../levelup/contact.php', 'columns' => ['id' => 'Nr.', 'naam' => 'Naam', 'email' => 'E-mail', 'onderwerp' => 'Onderwerp', 'bericht' => 'Bericht', 'aangemaakt' => 'Opgeslagen']],
    'nieuwsbrief' => ['title' => 'Nieuwsbrief', 'site' => 'LevelUp', 'link' => '../levelup/index.php#nieuwsbrief', 'columns' => ['id' => 'Nr.', 'naam' => 'Naam', 'email' => 'E-mail', 'aangemaakt' => 'Opgeslagen']],
    'bestellingen' => ['title' => 'Bestelaanvragen', 'site' => 'LevelUp', 'link' => '../levelup/producten.php', 'columns' => ['id' => 'Nr.', 'naam' => 'Naam', 'email' => 'E-mail', 'producten' => 'Producten', 'totaal' => 'Totaal', 'aangemaakt' => 'Opgeslagen']]
];
$dataError = '';
if ($loggedIn) {
    try {
        require __DIR__ . '/includes/database.php';
        $kamadoDb = $db;
        require dirname(__DIR__) . '/levelup/includes/database.php';
        foreach ($sections as $table => &$section) {
            $connection = $table === 'inschrijvingen' ? $kamadoDb : $db;
            $section['count'] = (int) $connection->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            $columns = implode(', ', array_keys($section['columns']));
            $section['rows'] = $connection->query("SELECT $columns FROM $table ORDER BY id DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($section);
    } catch (Throwable $exception) {
        error_log('Overzicht ophalen mislukt: ' . $exception->getMessage());
        $dataError = 'De gegevens konden niet worden opgehaald. Probeer later opnieuw.';
        http_response_code(503);
    }
}
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Gegevensoverzicht | Kamado &amp; LevelUp</title>
    <link rel="icon" href="assets/logo.svg" type="image/svg+xml">
    <link rel="stylesheet" href="css/overzicht.css?v=<?= filemtime(__DIR__ . '/css/overzicht.css') ?>">
</head>
<body>
<header><div class="shell header-inner"><a href="overzicht.php" class="brand">Kamado <span>&amp;</span> LevelUp</a><span>Gegevensoverzicht</span></div></header>
<main class="shell">
<?php if (!$loggedIn): ?>
    <section class="login"><p class="eyebrow">Alleen voor beheer</p><h1>Bekijk wat er binnenkomt.</h1><p>Log in om de opgeslagen gegevens van beide websites te bekijken.</p>
    <?php if ($error): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
    <form action="overzicht.php" method="post">
        <input type="hidden" name="token" value="<?= e($_SESSION['token']) ?>">
        <label for="wachtwoord">Wachtwoord</label><input id="wachtwoord" name="wachtwoord" type="password" autocomplete="current-password" maxlength="200" required>
        <button type="submit" <?= $passwordHash === '' ? 'disabled' : '' ?>>Inloggen</button>
    </form></section>
<?php else: ?>
    <div class="intro"><div><p class="eyebrow">Rechtstreeks uit de database</p><h1>Alle inzendingen op een rij.</h1><p>Dien een formulier in en vernieuw dit overzicht. Per onderdeel zie je de laatste 50 inzendingen.</p></div>
    <div class="tools"><a class="button" href="overzicht.php">Vernieuwen</a><form action="overzicht.php" method="post"><input type="hidden" name="token" value="<?= e($_SESSION['token']) ?>"><button class="secondary" name="actie" value="uitloggen">Uitloggen</button></form></div></div>
    <p class="updated">Vernieuwd om <?= date('H:i:s') ?> · Je sessie verloopt om <?= date('H:i', $_SESSION['overzicht_until']) ?>.</p>
    <?php if ($error): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
    <?php if ($dataError): ?><p class="error" role="alert"><?= e($dataError) ?></p><?php else: ?>
    <nav class="summary" aria-label="Onderdelen"><?php foreach ($sections as $table => $section): ?><a href="#<?= e($table) ?>"><strong><?= $section['count'] ?></strong><span><?= e($section['title']) ?></span></a><?php endforeach; ?></nav>
    <?php foreach ($sections as $table => $section): ?>
    <section id="<?= e($table) ?>" class="data-section"><div class="section-heading"><div><p class="eyebrow"><?= e($section['site']) ?></p><h2><?= e($section['title']) ?></h2></div><a href="<?= e($section['link']) ?>" target="_blank" rel="noopener noreferrer">Open formulier <span aria-hidden="true">&rarr;</span></a></div>
    <?php if (!$section['rows']): ?><p class="empty">Nog geen inzendingen. Vul het formulier in om de eerste te zien.</p><?php else: ?>
    <div class="table-scroll" tabindex="0" role="region" aria-label="<?= e($section['title']) ?>"><table><caption class="sr-only"><?= e($section['title']) ?>, nieuwste eerst</caption><thead><tr><?php foreach ($section['columns'] as $label): ?><th scope="col"><?= e($label) ?></th><?php endforeach; ?></tr></thead><tbody>
    <?php foreach ($section['rows'] as $row): ?><tr><?php foreach ($section['columns'] as $column => $label): ?><td><?php if ($column === 'totaal'): ?>&euro; <?= number_format((float) $row[$column], 2, ',', '.') ?><?php elseif ($column === 'producten'): ?>
    <?php foreach ((json_decode($row[$column], true) ?: []) as $product): ?><div><?= (int) ($product['aantal'] ?? 0) ?>&times; <?= e($product['product'] ?? '') ?></div><?php endforeach; ?>
    <?php else: ?><?= nl2br(e($row[$column])) ?><?php endif; ?></td><?php endforeach; ?></tr><?php endforeach; ?>
    </tbody></table></div><?php endif; ?></section>
    <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>
</main>
</body>
</html>
