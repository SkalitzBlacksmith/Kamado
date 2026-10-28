<?php
$page = "masterclasses";
include __DIR__ . "/includes/header.php";
?>
    <main>
        <section>
            <h1>Masterclasses</h1>
            <p>Leer koken op een kamado.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </section>
        <section class="columns">
            <div>
                <div class="image-box" aria-label="Afbeelding"></div>
                <h2>Wat ga je leren?</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <p>Maak kennis met de kamado en verschillende kooktechnieken.</p>
            </div>
            <form action="masterclasses.php" method="get">
                <h2>Aanmelden</h2>
                <p>Vul hieronder je gegevens in.</p>
                <label for="naam">Naam</label>
                <input id="naam" name="naam" type="text" autocomplete="name" placeholder="Vul je volledige naam in" required>
                <label for="email">E-mailadres</label>
                <input id="email" name="email" type="email" autocomplete="email" placeholder="Vul een geldig e-mailadres in" required>
                <label for="masterclass">Masterclass</label>
                <select id="masterclass" name="masterclass" required>
                    <option value="">Kies een masterclass</option>
                    <option value="basis">Basis van de kamado</option>
                    <option value="grillen">Grillen en roken</option>
                </select>
                <label for="personen">Aantal personen</label>
                <select id="personen" name="personen">
                    <option>1</option><option>2</option><option>3</option><option>4</option>
                </select>
                <button type="submit">Versturen</button>
                <p><small>Dit is een oefenformulier.</small></p>
            </form>
        </section>
    </main>
<?php include __DIR__ . "/includes/footer.php"; ?>
