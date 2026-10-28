<?php
$databaseHost = getenv('KAMADO_DB_HOST') ?: '127.0.0.1';
$databasePort = getenv('KAMADO_DB_PORT') ?: '3306';
$databaseName = getenv('KAMADO_DB_NAME') ?: 'kamado_breda';
$databaseUser = getenv('KAMADO_DB_USER') ?: 'root';
$databasePassword = getenv('KAMADO_DB_PASSWORD') ?: '';
$db = new PDO("mysql:host=$databaseHost;port=$databasePort;dbname=$databaseName;charset=utf8mb4", $databaseUser, $databasePassword, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]);
