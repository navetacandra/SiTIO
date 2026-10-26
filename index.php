<?php declare(strict_types=1);

require_once __DIR__ . "/app/core/Database.php";
require_once __DIR__ . "/app/core/Storage.php";
require_once __DIR__ . "/app/core/Validator.php";
require_once __DIR__ . "/app/core/Repository.php";
require_once __DIR__ . "/app/core/Service.php";
require_once __DIR__ . "/app/core/Controller.php";
require_once __DIR__ . "/app/core/Request.php";
require_once __DIR__ . "/app/core/Response.php";
require_once __DIR__ . "/app/core/Router.php";

$router = new Router();

// Dashboard Utama
$router->get("/", function() {
    Response::render("Dashboard.index", ["page" => "dashboard"]);
});

$router->get("/sample", ["SampleController", "test"]);

$renderPdln = function (Request $request, array $params) {
    if (session_status() === PHP_SESSION_NONE && !session_start()) {
        throw new RuntimeException("Gagal memulai sesi untuk halaman PDLN.");
    }

    Response::render("Dashboard.index", ["page" => "pdln"]);
};

$renderOutbound = function (Request $request, array $params) {
    if (session_status() === PHP_SESSION_NONE && !session_start()) {
        throw new RuntimeException("Gagal memulai sesi untuk halaman Outbound.");
    }

    Response::render("Dashboard.index", ["page" => "outbound"]);
};

$renderInbound = function (Request $request, array $params) {
    if (session_status() === PHP_SESSION_NONE && !session_start()) {
        throw new RuntimeException("Gagal memulai sesi untuk halaman Inbound.");
    }

    Response::render("Dashboard.index", ["page" => "inbound"]);
};

$renderTiket = function (Request $request, array $params) {
    if (session_status() === PHP_SESSION_NONE && !session_start()) {
        throw new RuntimeException("Gagal memulai sesi untuk halaman Tiket.");
    }

    Response::render("Dashboard.index", ["page" => "tiket"]);
};

// Outbound & Inbound
$router->get("/daftar-outbound", $renderOutbound);
$router->post("/daftar-outbound", $renderOutbound);

$router->get("/daftar-inbound", $renderInbound);
$router->post("/daftar-inbound", $renderInbound);

// Tiket
$router->get("/daftar-tiket", $renderTiket);
$router->post("/daftar-tiket", $renderTiket);
$router->get("/daftar-tiket.php", $renderTiket);
$router->post("/daftar-tiket.php", $renderTiket);

// Detail & Aksi Tiket
$router->get('/detail-tiket', ['TiketController', 'detail']);
$router->post('/detail-tiket', ['TiketController', 'detail']);
$router->get('/detail-tiket.php', ['TiketController', 'detail']);
$router->post('/detail-tiket.php', ['TiketController', 'detail']);
$router->post('/mark-done-tiket', ['TiketController', 'markDone']);
$router->get('/mark-done-tiket', ['TiketController', 'markDone']);

// PDLN
$router->get("/daftar-pdln", $renderPdln);
$router->post("/daftar-pdln", $renderPdln);
$router->get("/daftar-pdln.php", $renderPdln);
$router->post("/daftar-pdln.php", $renderPdln);

$router->get('/detail-pdln', ['PdlnController', 'detail']);
$router->post('/detail-pdln', ['PdlnController', 'detail']);

// Autentikasi: Logout
$handleLogout = function (Request $request, array $params) {
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

    $basePath = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
    $loginUrl = ($basePath ?: '') . '/login?logout=1';

    if (headers_sent()) {
        echo "<script>window.location.href = " . json_encode($loginUrl) . ";</script>";
        echo "<noscript><meta http-equiv='refresh' content='0;url=" . htmlspecialchars($loginUrl, ENT_QUOTES) . "'></noscript>";
        exit;
    }

    header("Location: " . $loginUrl);
    exit;
};

$router->get('/logout', $handleLogout);
$router->post('/logout', $handleLogout);
$router->get('/logout.php', $handleLogout);
$router->post('/logout.php', $handleLogout);

// Autentikasi: Login
$handleLogin = function (Request $request, array $params) {
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }

    $basePath = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim((string)($_POST['username'] ?? 'admin'));
        $_SESSION['user'] = [
            'username' => $username,
            'name' => 'Administrator SiTIO',
            'role' => 'Admin'
        ];
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Selamat datang kembali di SiTIO PNJ!'];

        $dest = ($basePath ?: '') . '/';
        if (headers_sent()) {
            echo "<script>window.location.href = " . json_encode($dest) . ";</script>";
            exit;
        }
        header("Location: " . $dest);
        exit;
    }

    Response::render("Dashboard.login");
};

$router->get('/login', $handleLogin);
$router->post('/login', $handleLogin);
$router->get('/login.php', $handleLogin);
$router->post('/login.php', $handleLogin);

// Fallback Route
$router->get("/:param_1", function(Request $request, array $params) {
    Response::render("Dashboard.index", ["params" => $params]);
});

$router->run();
