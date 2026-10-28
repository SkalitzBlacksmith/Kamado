<?php
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
session_start();
$page = 'masterclasses';
$classes = ['basis' => 'Basis van de kamado', 'grillen' => 'Grillen en roken'];
$dates = ['2027-03-06' => '6 maart 2027', '2027-03-20' => '20 maart 2027', '2027-04-10' => '10 april 2027'];
$name = $email = $selected = $date = '';
$people = '1';
$errors = [];
if (empty($_SESSION['token'])) $_SESSION['token'] = bin2hex(random_bytes(32));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = is_string($_POST['naam'] ?? null) ? trim($_POST['naam']) : '';
    $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
    $selected = is_string($_POST['masterclass'] ?? null) ? $_POST['masterclass'] : '';
    $date = is_string($_POST['datum'] ?? null) ? $_POST['datum'] : '';
    $people = is_string($_POST['personen'] ?? null) ? $_POST['personen'] : '';
    $token = is_string($_POST['token'] ?? null) ? $_POST['token'] : '';
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
    <section class="page-intro"><p class="eyebrow">Samen rond het vuur</p><h1>Masterclasses</h1><p class="lead">Leer koken op een kamado.</p><p>Ontdek de basis en probeer verschillende technieken uit.</p></section>
    <section class="columns class-layout"><div><img class="class-image" src="assets/kamado-breda.jpg" alt="Blauwe kamado's voor de winkel in Breda" width="1470" height="820" loading="lazy"><h2>Wat ga je leren?</h2><p>Van het aansteken en op temperatuur brengen tot grillen en roken: maak kennis met de mogelijkheden van de kamado.</p><p>Laat je gegevens achter en geef aan welke masterclass je interesse heeft.</p></div>
    <form action="masterclasses.php" method="post"><h2>Aanmelden</h2><p>Kies een masterclass en een datum. De data zijn voorbeelden voor deze cursuswebsite.</p>
    <?php if ($errors): ?><div class="errors" role="alert"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <input type="hidden" name="token" value="<?= e($_SESSION['token']) ?>">
    <label for="naam">Naam</label><input id="naam" name="naam" type="text" autocomplete="name" maxlength="80" value="<?= e($name) ?>" required>
    <label for="email">E-mailadres</label><input id="email" name="email" type="email" autocomplete="email" maxlength="254" value="<?= e($email) ?>" required>
    <label for="masterclass">Masterclass</label><select id="masterclass" name="masterclass" required><option value="">Kies een masterclass</option><?php foreach ($classes as $key => $label): ?><option value="<?= e($key) ?>" <?= $selected === $key ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select>
    <label for="datum">Datum</label><select id="datum" name="datum" required><option value="">Kies een datum</option><?php foreach ($dates as $value => $label): ?><option value="<?= e($value) ?>" <?= $date === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select>
    <label for="personen">Aantal personen</label><select id="personen" name="personen"><?php for ($i = 1; $i <= 4; $i++): ?><option value="<?= $i ?>" <?= $people === (string) $i ? 'selected' : '' ?>><?= $i ?></option><?php endfor; ?></select>
    <button type="submit">Verstuur aanmelding</button><p class="form-note">Je gegevens worden gebruikt om je aanmelding te verwerken.</p></form></section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
