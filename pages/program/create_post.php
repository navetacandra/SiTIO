<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Program.php";

require_post();

$type = trim((string) ($_POST["type"] ?? ""));
$name = trim((string) ($_POST["name"] ?? ""));
$startAt = trim((string) ($_POST["start_at"] ?? ""));
$endAt = trim((string) ($_POST["end_at"] ?? ""));

if (!in_array($type, ["inbound", "outbound"], true)) {
    fail_page("Type is invalid.", "/programs/create");
}

if ($name === "" || $startAt === "" || $endAt === "") {
    fail_page("Name, start, and end are required.", "/programs/create");
}

$program = new Program();

try {
    $program->create($type, $name, $startAt, $endAt);
} catch (Throwable $e) {
    fail_page($e->getMessage(), "/programs/create");
}

redirect("/programs");
