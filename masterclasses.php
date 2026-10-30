<?php
require __DIR__ . '/includes/session.php';
$page = 'masterclasses';
$classes = ['basis' => 'Basis van de kamado', 'grillen' => 'Grillen en roken'];
$dates = ['2027-03-06' => '6 maart 2027', '2027-03-20' => '20 maart 2027', '2027-04-10' => '10 april 2027'];
$name = $email = $selected = $date = '';
$people = '1';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = is_string($_POST['naam'] ?? null) ? trim($_POST['naam']) : '';
    $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
    $selected = is_string($_POST['masterclass'] ?? null) ? $_POST['masterclass'] : '';
    $date = is_string($_POST['datum'] ?? null) ? $_POST['datum'] : '';
    $people = is_string($_POST['personen'] ?? null) ? $_POST['personen'] : '';
    $token = is_string($_POST['token'] ?? null) ? $_POST['token'] : '';
    if (time() - ($_SESSION['last_registration'] ?? 0) < 30) $errors[] = 'Je hebt zojuist een aanmelding verstuurd. Wacht even voordat je opnieuw aanmeldt.';
    if (!hash_equals($_SESSION['token'], $token)) $errors[] = 'De sessie is verlopen. Probeer het opnieuw.';
    if ($name === '' || mb_strlen($name) > 80) $errors[] = 'Vul een naam van maximaal 80 tekens in.';
    if (strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Vul een geldig e-mailadres in.';
    if (!isset($classes[$selected])) $errors[] = 'Kies een masterclass.';
    if (!isset($dates[$date])) $errors[] = 'Kies een datum.';
    if (!in_array($people, ['1', '2', '3', '4'], true)) $errors[] = 'Kies een geldig aantal personen.';
    if (!$errors) {
        try {
            require __DIR__ . '/includes/database.php';
            $query = $db->prepare('INSERT INTO inschrijvingen (naam, email, masterclass, datum, personen) VALUES (?, ?, ?, ?, ?)');
            $query->execute([$name, $email, $classes[$selected], $date, (int) $people]);
            $_SESSION['last_registration'] = time();
            $_SESSION['confirmation'] = ['name' => $name, 'class' => $classes[$selected], 'people' => $people, 'date' => $dates[$date]];
            $_SESSION['token'] = bin2hex(random_bytes(32));
            header('Location: bevestiging.php', true, 303);
            exit;
        } catch (Throwable $exception) {
            error_log('Kamado inschrijving opslaan mislukt: ' . $exception->getMessage());
            $errors[] = 'Opslaan is niet gelukt. Probeer het later opnieuw.';
            http_response_code(500);
        }
    } else http_response_code(422);
}
function e($value) { return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
include __DIR__ . '/includes/header.php';
?>
<main id="inhoud">
    <section class="page-intro"><p class="eyebrow">Masterclasses in Breda</p><h1>Begrijp wat er onder het deksel gebeurt.</h1><p class="lead">Het vuur aanhouden lukt vaak wel. Het op de juiste temperatuur houden vraagt wat meer gevoel.</p><p>In een masterclass leer je hoe luchttoevoer en de opstelling van je rooster je bereiding beïnvloeden. Zo weet je waarom je iets aanpast wanneer het te hard gaat, in plaats van alleen een recept te volgen. Kies de basis als je nog moet beginnen, of ga verder met grillen en rookhout.</p></section>
    <section class="columns class-layout"><div>
        <img class="class-image" src="assets/kamado-breda.webp" alt="Blauwe kamado's voor de winkel in Breda" width="1470" height="820" loading="lazy">
        <h2>Welke masterclass past bij je?</h2>
        <dl class="class-details">
            <div><dt>Basis van de kamado</dt><dd>Voor wie voor het eerst met een kamado gaat koken. Je begint bij het aansteken, leert de luchttoevoer instellen en maakt kennis met direct en indirect garen. Bij je eerste gerecht zie je hoe de opstelling en het vuur samenwerken. Je hoeft nog geen ervaring met een kamado te hebben.</dd></div>
            <div><dt>Grillen en roken</dt><dd>Voor wie de basis kent en gerichter wil koken. Je kijkt naar het verschil tussen direct grillen en afgeschermd garen, en naar wat rookhout aan een bereiding toevoegt. De aandacht ligt op de keuzes tijdens het koken: wanneer gebruik je directe hitte en wanneer geef je een gerecht juist meer tijd?</dd></div>
        </dl>
        <p>Je kunt alleen aanmelden of samen met anderen. Geef één tot vier personen op en kies een voorkeursdatum. Je aanvraag is nog geen bevestigde reservering.</p>
    </div>
    <form action="masterclasses.php" method="post"><h2>Vraag een plek aan</h2><p>Kies de les die bij je ervaring past en geef je voorkeursdatum door. De beschikbaarheid op die datum moet nog worden bevestigd.</p>
    <?php if ($errors): ?><div class="errors" role="alert"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <input type="hidden" name="token" value="<?= e($_SESSION['token']) ?>">
    <label for="naam">Naam</label><input id="naam" name="naam" type="text" autocomplete="name" maxlength="80" placeholder="Je voor- en achternaam" value="<?= e($name) ?>" required>
    <label for="email">E-mailadres</label><input id="email" name="email" type="email" autocomplete="email" maxlength="254" placeholder="naam@voorbeeld.nl" value="<?= e($email) ?>" required>
    <label for="masterclass">Masterclass</label><select id="masterclass" name="masterclass" required><option value="">Kies een masterclass</option><?php foreach ($classes as $key => $label): ?><option value="<?= e($key) ?>" <?= $selected === $key ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select>
    <label for="datum">Voorkeursdatum</label><select id="datum" name="datum" required><option value="">Kies een voorkeursdatum</option><?php foreach ($dates as $value => $label): ?><option value="<?= e($value) ?>" <?= $date === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select>
    <label for="personen">Aantal personen</label><select id="personen" name="personen"><?php for ($i = 1; $i <= 4; $i++): ?><option value="<?= $i ?>" <?= $people === (string) $i ? 'selected' : '' ?>><?= $i ?></option><?php endfor; ?></select>
    <button type="submit">Verstuur mijn aanvraag</button><p class="form-note">Na het versturen zie je een overzicht van je aanvraag. We gebruiken je gegevens voor deze aanmelding.</p></form></section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
