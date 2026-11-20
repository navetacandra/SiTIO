<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Program.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));

if ($id === "") {
    fail_page("Program ID is required.", BASE_URL . "/programs");
}

$program = new Program();

try {
    $program->delete($id);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/programs");
}

redirect("/programs");
