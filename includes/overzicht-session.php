<?php
ini_set('session.use_strict_mode', '1');
session_name('kamado_overzicht');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Strict', 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
session_start();
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');
header('X-Robots-Tag: noindex, nofollow');
header("Content-Security-Policy: default-src 'self'; frame-ancestors 'none'; base-uri 'none'; form-action 'self'; object-src 'none'");

$settingsFile = dirname(__DIR__, 3) . '/private/overzicht.php';
$settings = is_file($settingsFile) ? require $settingsFile : [];
$passwordHash = getenv('SHOWCASE_PASSWORD_HASH') ?: ($settings['password_hash'] ?? '');
$error = '';
if (empty($_SESSION['token'])) $_SESSION['token'] = bin2hex(random_bytes(32));
if (($_SESSION['overzicht_until'] ?? 0) <= time()) unset($_SESSION['overzicht_until']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = is_string($_POST['token'] ?? null) ? $_POST['token'] : '';
    if (!hash_equals($_SESSION['token'], $token)) {
        $error = 'De sessie is verlopen. Probeer opnieuw.';
        http_response_code(422);
    } elseif (($_POST['actie'] ?? '') === 'uitloggen') {
        $_SESSION = [];
        session_regenerate_id(true);
        header('Location: overzicht.php', true, 303);
        exit;
    } elseif ($passwordHash !== '') {
        $path = sys_get_temp_dir() . '/kamado-overzicht-' . hash('sha256', __DIR__ . ($_SERVER['REMOTE_ADDR'] ?? ''));
        $file = fopen($path, 'c+');
        if (!$file || !flock($file, LOCK_EX)) {
            $error = 'Inloggen is tijdelijk niet mogelijk. Probeer later opnieuw.';
            http_response_code(503);
        } else {
            chmod($path, 0600);
            $attempts = json_decode(stream_get_contents($file), true) ?: [];
            if (($attempts['until'] ?? 0) < time()) $attempts = ['count' => 0, 'until' => time() + 600];
            $password = is_string($_POST['wachtwoord'] ?? null) ? $_POST['wachtwoord'] : '';
            if ($attempts['count'] >= 5) {
                $error = 'Te veel inlogpogingen. Probeer over tien minuten opnieuw.';
                http_response_code(429);
            } elseif (strlen($password) <= 200 && password_verify($password, $passwordHash)) {
                $_SESSION['overzicht_until'] = time() + 1800;
                $_SESSION['token'] = bin2hex(random_bytes(32));
                session_regenerate_id(true);
                $attempts['count'] = 0;
            } else {
                $attempts['count']++;
                $error = 'Het wachtwoord klopt niet.';
                http_response_code(401);
            }
            rewind($file);
            ftruncate($file, 0);
            fwrite($file, json_encode($attempts));
            flock($file, LOCK_UN);
            fclose($file);
            if (!$error) {
                header('Location: overzicht.php', true, 303);
                exit;
            }
        }
    }
}
if ($passwordHash === '') {
    $error = 'Het overzicht is nog niet ingesteld.';
    http_response_code(503);
}
