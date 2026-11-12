<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Program.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));
$type = trim((string) ($_POST["type"] ?? ""));
$name = trim((string) ($_POST["name"] ?? ""));
$startAt = trim((string) ($_POST["start_at"] ?? ""));
$endAt = trim((string) ($_POST["end_at"] ?? ""));

if ($id === "") {
    fail_page("Program ID is required.", "dashboard_get.php");
}

if (!in_array($type, ["inbound", "outbound"], true)) {
    fail_page("Type is invalid.", "edit_get.php?id=" . urlencode($id));
}

if ($name === "" || $startAt === "" || $endAt === "") {
    fail_page(
        "Name, start, and end are required.",
        "edit_get.php?id=" . urlencode($id),
    );
}

$program = new Program();

try {
    $program->update($id, $type, $name, $startAt, $endAt);
} catch (Throwable $e) {
    fail_page($e->getMessage(), "edit_get.php?id=" . urlencode($id));
}

redirect("dashboard_get.php");
