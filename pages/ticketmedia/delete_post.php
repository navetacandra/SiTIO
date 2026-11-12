<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

require_post();

$ticketId = trim((string) ($_POST["ticket_id"] ?? ""));
$path = trim((string) ($_POST["path"] ?? ""));

if ($ticketId === "" || $path === "") {
    fail_page("Ticket ID and path are required.", "dashboard_get.php");
}

$ticketMedia = new TicketMedia();

try {
    $ticketMedia->delete($ticketId, $path);
} catch (Throwable $e) {
    fail_page($e->getMessage(), "dashboard_get.php");
}

redirect("dashboard_get.php");
