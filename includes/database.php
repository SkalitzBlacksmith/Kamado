<?php
$settingsFile = dirname(__DIR__, 3) . '/private/kamado-database.php';
$settings = is_file($settingsFile) ? require $settingsFile : [];
$databaseHost = getenv('KAMADO_DB_HOST') ?: ($settings['host'] ?? '127.0.0.1');
$databasePort = getenv('KAMADO_DB_PORT') ?: ($settings['port'] ?? '3306');
$databaseName = getenv('KAMADO_DB_NAME') ?: ($settings['name'] ?? 'kamado_breda');
$databaseUser = getenv('KAMADO_DB_USER') ?: ($settings['user'] ?? 'root');
$databasePassword = getenv('KAMADO_DB_PASSWORD') ?: ($settings['password'] ?? '');
$db = new PDO("mysql:host=$databaseHost;port=$databasePort;dbname=$databaseName;charset=utf8mb4", $databaseUser, $databasePassword, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]);
