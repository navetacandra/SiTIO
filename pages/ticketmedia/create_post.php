<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

require_post();

$ticketId = trim((string) ($_POST["ticket_id"] ?? ""));
$type = trim((string) ($_POST["type"] ?? ""));
$path = trim((string) ($_POST["path"] ?? ""));

if ($ticketId === "") {
    fail_page("Ticket is required.", "create_get.php");
}

if (!in_array($type, ["image", "pdf"], true)) {
    fail_page("Type is invalid.", "create_get.php");
}

if ($path === "") {
    fail_page("Path is required.", "create_get.php");
}

$ticketMedia = new TicketMedia();

try {
    $ticketMedia->create($ticketId, $type, $path);
} catch (Throwable $e) {
    fail_page($e->getMessage(), "create_get.php");
}

redirect("dashboard_get.php");
