<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/User.php";

require_post();

$identifier = trim((string) ($_POST["identifier"] ?? ""));
$password = (string) ($_POST["password"] ?? "");

if ($identifier === "" || $password === "") {
    fail_page("Email/phone and password are required.", BASE_URL . "/login");
}

$user = new User();

try {
    $loggedIn = $user->login($identifier, $password);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/login");
}

if ($loggedIn === false) {
    fail_page("Invalid credentials or inactive account.", BASE_URL . "/login");
}

redirect("/users");
