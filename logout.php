<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $p = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $p["path"] ?? '/',
        $p["domain"] ?? '',
        $p["secure"] ?? false,
        $p["httponly"] ?? false
    );
}

if (session_status() === PHP_SESSION_ACTIVE) {
    @session_destroy();
}

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = ($scriptDir === '/' || $scriptDir === '\\' || $scriptDir === '.') ? '' : rtrim($scriptDir, '/');
if (str_ends_with($basePath, '/public')) {
    $basePath = substr($basePath, 0, -7);
}

$loginUrl = ($basePath ?: '') . '/login?logout=1';

if (headers_sent()) {
    echo "<script>window.location.href = " . json_encode($loginUrl) . ";</script>";
    echo "<noscript><meta http-equiv='refresh' content='0;url=" . htmlspecialchars($loginUrl, ENT_QUOTES) . "'></noscript>";
    exit;
}

header("Location: " . $loginUrl);
exit;
