<?php
$host = getenv('KAMADO_DB_HOST') ?: '127.0.0.1';
$port = getenv('KAMADO_DB_PORT') ?: '3306';
$name = getenv('KAMADO_DB_NAME') ?: 'kamado_breda';
$user = getenv('KAMADO_DB_USER') ?: 'root';
$password = getenv('KAMADO_DB_PASSWORD') ?: '';
$db = new PDO("mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4", $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]);
