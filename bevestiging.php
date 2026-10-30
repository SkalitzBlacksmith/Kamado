<?php
require __DIR__ . '/includes/session.php';
$confirmation = $_SESSION['confirmation'] ?? null;
unset($_SESSION['confirmation']);
if (!$confirmation) { header('Location: masterclasses.php', true, 303); exit; }
$page = 'masterclasses';
include __DIR__ . '/includes/header.php';
?>
<main id="inhoud"><section class="confirmation"><p class="eyebrow">Je aanvraag is ontvangen</p><h1>Bedankt, <?= htmlspecialchars($confirmation['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>.</h1><p>Je hebt <?= htmlspecialchars($confirmation['class'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> aangevraagd voor <?= (int) $confirmation['people'] ?> <?= (int) $confirmation['people'] === 1 ? 'persoon' : 'personen' ?>.</p><p>Je voorkeursdatum is <?= htmlspecialchars($confirmation['date'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>. Bewaar deze gegevens. De beschikbaarheid is nog niet bevestigd; je hebt dus nog geen definitieve plek op deze datum.</p><p>Je aanvraag is opgeslagen. Je hoeft het formulier niet opnieuw te versturen.</p><a class="button" href="kamados.php">Lees verder over de kamado</a></section></main>
<?php include __DIR__ . '/includes/footer.php'; ?>
