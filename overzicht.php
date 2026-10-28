<?php
$local = str_ends_with($_SERVER['HTTP_HOST'] ?? '', '.test');
header('Location: ' . ($local ? 'http://gegevensoverzicht.test/' : '../overzicht/'), true, 302);
exit;
