<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/UserLegal.php";

require_post();

$userId = trim((string) ($_POST["user_id"] ?? ""));

if ($userId === "") {
    fail_page("User ID is required.", BASE_URL . "/user-legal");
}

$nullable = static function (string $key): ?string {
    $value = trim((string) ($_POST[$key] ?? ""));
    return $value === "" ? null : $value;
};

$userLegal = new UserLegal();

try {
    $userLegal->update(
        $userId,
        $nullable("nik"),
        $nullable("nim"),
        $nullable("nip"),
        $nullable("paspor_num"),
        $nullable("ktp"),
        $nullable("paspor"),
    );
} catch (Throwable $e) {
    fail_page(
        $e->getMessage(),
        BASE_URL . "/user-legal/edit?user_id=" . urlencode($userId),
    );
}

redirect("/user-legal");
