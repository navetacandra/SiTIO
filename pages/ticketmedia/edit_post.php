<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

require_post();

$ticketId = trim((string) ($_POST["ticket_id"] ?? ""));
$oldPath = trim((string) ($_POST["old_path"] ?? ""));
$type = trim((string) ($_POST["type"] ?? ""));
$path = trim((string) ($_POST["path"] ?? ""));

if ($ticketId === "" || $oldPath === "") {
    fail_page(
        "Ticket ID and old path are required.",
        BASE_URL . "/ticket-media",
    );
}

$editUrl =
    BASE_URL .
    "/ticket-media/edit?ticket_id=" .
    urlencode($ticketId) .
    "&path=" .
    urlencode($oldPath);

if (!in_array($type, ["image", "pdf"], true)) {
    fail_page("Type is invalid.", $editUrl);
}

if ($path === "") {
    fail_page("Path is required.", $editUrl);
}

$ticketMedia = new TicketMedia();

try {
    $ticketMedia->update($ticketId, $oldPath, $type, $path);
} catch (Throwable $e) {
    fail_page($e->getMessage(), $editUrl);
}

redirect(ticket_media_url($ticketId));
