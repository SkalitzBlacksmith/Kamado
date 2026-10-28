<?php
require __DIR__ . '/includes/session.php';
$confirmation = $_SESSION['confirmation'] ?? null;
unset($_SESSION['confirmation']);
if (!$confirmation) { header('Location: masterclasses.php', true, 303); exit; }
$page = 'masterclasses';
include __DIR__ . '/includes/header.php';
?>
<main id="inhoud"><section class="confirmation"><p class="eyebrow">Aanmelding ontvangen</p><h1>Bedankt, <?= htmlspecialchars($confirmation['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>.</h1><p>Je interesse in <?= htmlspecialchars($confirmation['class'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> voor <?= (int) $confirmation['people'] ?> <?= (int) $confirmation['people'] === 1 ? 'persoon' : 'personen' ?> is opgeslagen.</p><p>Voorkeursdatum: <?= htmlspecialchars($confirmation['date'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>. Beschikbaarheid is nog niet bevestigd.</p><a class="button" href="index.php">Terug naar home</a></section></main>
<?php include __DIR__ . '/includes/footer.php'; ?>
